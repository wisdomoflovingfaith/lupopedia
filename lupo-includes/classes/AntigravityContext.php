<?php
/**
 * AntigravityContext — Exposes auth user and actor context for Antigravity (conflict resolution, context awareness).
 * Uses ContextResolver for base context and optional AuthService for current/paired user.
 *
 * @package Lupopedia
 * @version 4.0.61
 */

class AntigravityContext
{
    private $context;
    private $auth;
    private $actorData;

    /**
     * Build context from kernel if context not provided.
     *
     * @param array|null $resolvedContext From ContextResolver::resolve() or null to use Kernel
     * @param object|null $authService App\Auth\AuthService (e.g. $GLOBALS['lupo_auth_service'])
     */
    public function __construct($resolvedContext = null, $authService = null)
    {
        if ($resolvedContext === null) {
            if (!class_exists('ContextKernel')) {
                require_once dirname(__FILE__) . '/ContextKernel.php';
            }
            $kernel = ContextKernel::getInstance();
            $this->context = $kernel->getContext();
            $this->auth = $kernel->getAuthUser();
        } else {
            $this->context = is_array($resolvedContext) ? $resolvedContext : array();
            $this->auth = null;
        }

        $this->actorData = array(
            'name' => isset($this->context['actor_name']) ? $this->context['actor_name'] : null,
            'id' => isset($this->context['actor_id']) ? (int) $this->context['actor_id'] : null,
            'type' => isset($this->context['actor_type']) ? $this->context['actor_type'] : null,
            'paired_actor_id' => isset($this->context['paired_actor_id']) ? (int) $this->context['paired_actor_id'] : null,
        );

        if ($this->auth === null && $authService && method_exists($authService, 'getCurrentUser')) {
            $user = $authService->getCurrentUser();
            if ($user !== false && is_array($user)) {
                $this->auth = array(
                    'user_id' => isset($user['auth_user_id']) ? (int) $user['auth_user_id'] : null,
                    'username' => isset($user['username']) ? $user['username'] : null,
                    'display_name' => isset($user['display_name']) ? $user['display_name'] : null,
                    'email' => isset($user['email']) ? $user['email'] : null,
                    'role' => isset($user['is_admin']) && $user['is_admin'] ? 'admin' : 'user',
                );
            }
        }
        if ($this->auth === null && $authService && method_exists($authService, 'getUserByActorId') && !empty($this->actorData['id'])) {
            $byActor = $authService->getUserByActorId($this->actorData['id']);
            if (is_array($byActor)) {
                $this->auth = array(
                    'user_id' => isset($byActor['auth_user_id']) ? (int) $byActor['auth_user_id'] : null,
                    'username' => isset($byActor['username']) ? $byActor['username'] : null,
                    'display_name' => isset($byActor['display_name']) ? $byActor['display_name'] : null,
                    'email' => isset($byActor['email']) ? $byActor['email'] : null,
                    'role' => 'user',
                );
            }
        }
    }

    /**
     * Get current auth user (for conflict resolution).
     *
     * @return array|null user_id, username, display_name, email, role or null
     */
    public function getAuthUser()
    {
        return $this->auth;
    }

    /**
     * Get current actor summary.
     *
     * @return array name, id, type, paired_actor_id
     */
    public function getActor()
    {
        return $this->actorData;
    }

    /**
     * Whether current actor is an authenticated human.
     *
     * @return bool
     */
    public function isAuthenticatedHuman()
    {
        if ($this->auth === null || $this->actorData === null) {
            return false;
        }
        $type = isset($this->actorData['type']) ? $this->actorData['type'] : '';
        $authId = isset($this->auth['user_id']) ? $this->auth['user_id'] : null;
        $actorId = isset($this->actorData['id']) ? $this->actorData['id'] : null;
        return $type === 'human' && $authId !== null && $actorId !== null && (int) $actorId === (int) $authId;
    }

    /**
     * Whether current actor is an agent with a paired human.
     *
     * @return bool
     */
    public function isPairedAgent()
    {
        $type = isset($this->actorData['type']) ? $this->actorData['type'] : '';
        $paired = isset($this->actorData['paired_actor_id']) ? (int) $this->actorData['paired_actor_id'] : 0;
        return ($type === 'agent' || $type === 'ide_agent') && $paired > 0;
    }

    /**
     * Get resolution context for logging / conflict decisions.
     *
     * @return array timestamp, version, session_mode, actor_name, auth_username, channel_id, node_id
     */
    public function getResolutionContext()
    {
        return array(
            'timestamp' => gmdate('Y-m-d H:i:s'),
            'version' => function_exists('get_lupo_version') ? get_lupo_version() : '4.0.61',
            'session_mode' => isset($this->context['session_mode']) ? $this->context['session_mode'] : 'unknown',
            'actor_name' => isset($this->actorData['name']) ? $this->actorData['name'] : null,
            'auth_username' => $this->auth && isset($this->auth['username']) ? $this->auth['username'] : null,
            'channel_id' => isset($this->context['channel_id']) ? (int) $this->context['channel_id'] : null,
            'node_id' => isset($this->context['federation_node_id']) ? (int) $this->context['federation_node_id'] : null,
        );
    }

    /**
     * Get full Antigravity-shaped context (actor, auth, session, channel, workspace).
     *
     * @return array
     */
    public function getAntigravityContext()
    {
        return array(
            'actor' => $this->actorData,
            'auth' => $this->auth,
            'session' => array(
                'mode' => isset($this->context['session_mode']) ? $this->context['session_mode'] : null,
                'id' => isset($this->context['session_id']) ? $this->context['session_id'] : null,
                'source' => isset($this->context['context_source']) ? $this->context['context_source'] : (isset($this->context['source']) ? $this->context['source'] : null),
            ),
            'channel' => array(
                'id' => isset($this->context['channel_id']) ? (int) $this->context['channel_id'] : null,
                'node' => isset($this->context['federation_node_id']) ? (int) $this->context['federation_node_id'] : null,
            ),
            'workspace' => isset($this->context['workspace']) ? $this->context['workspace'] : null,
        );
    }
}
