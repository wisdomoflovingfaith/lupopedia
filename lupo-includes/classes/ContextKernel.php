<?php
# file: Context Kernel — session: L-LUPO-ANTIGRAVITY — delegation: antigravity:cursor:captain — web_path: http://www.lupopedia.com/docs/api/ContextKernel
# ---
# flare.headers:
#   flare.version: "1.0"
#   flare.schema: "documentation"
#   file_path_from_root: "lupo-includes/classes/ContextKernel.php"
#   last_updated_utc: "20260307"
#   system_version: "4.0.65"
#   actor_name: "antigravity"
#   artifact_type: "code"
#   purpose: "Single source of truth for runtime context resolution and validation (v4.0.65 update)."
# ---
/**
 * ContextKernel — Single runtime context object for Lupopedia.
 *
 * @package Lupopedia
 * @version 4.0.65
 */

class ContextKernel
{
    /** @var ContextKernel|null */
    private static $_instance = null;

    /** @var array|null */
    private $context = null;

    /** @var array|null */
    private $auth = null;

    /**
     * Get singleton instance.
     *
     * @return ContextKernel
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor (private).
     */
    private function __construct()
    {
    }

    /**
     * Bootstrap the kernel (resolve context).
     *
     * @param object|null $db PDO_DB or null
     * @param string $table_prefix Table prefix
     * @param string $state_file Path to .lupo_actor file
     * @param string $base_path Project root path
     * @param object|null $authService AuthService instance
     * @return array The resolved context
     */
    public function bootstrap($db, $table_prefix, $state_file, $base_path, $authService = null)
    {
        if (!class_exists('ContextResolver')) {
            require_once dirname(__FILE__) . '/ContextResolver.php';
        }

        $this->context = ContextResolver::resolve($db, $table_prefix, $state_file, $base_path);

        // Resolve auth user if service provided
        $this->auth = null;
        if ($authService && method_exists($authService, 'getCurrentUser')) {
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

        // Fallback for auth user via actor ID
        if ($this->auth === null && $authService && method_exists($authService, 'getUserByActorId')) {
            $actor_id = isset($this->context['actor_id']) ? (int) $this->context['actor_id'] : 0;
            if ($actor_id > 0) {
                $byActor = $authService->getUserByActorId($actor_id);
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

        return $this->context;
    }

    /**
     * Set context manually (e.g. if already resolved).
     *
     * @param array $ctx
     */
    public function setContext($ctx)
    {
        $this->context = $ctx;
    }

    /**
     * Get the full context array.
     *
     * @return array
     */
    public function getContext()
    {
        return $this->context !== null ? $this->context : array();
    }

    /**
     * Get Effective Actor identity.
     *
     * @return array array('name' => string, 'id' => int)
     */
    public function getEffectiveActor()
    {
        return array(
            'name' => isset($this->context['actor_name']) ? $this->context['actor_name'] : 'system',
            'id' => isset($this->context['actor_id']) ? (int) $this->context['actor_id'] : 0,
            'type' => isset($this->context['actor_type']) ? $this->context['actor_type'] : 'system'
        );
    }

    /**
     * Get Human Identity (paired or self).
     *
     * @return array array('name' => string, 'id' => int)
     */
    public function getHumanIdentity()
    {
        return array(
            'name' => isset($this->context['human_actor_name']) ? $this->context['human_actor_name'] : 'none',
            'id' => isset($this->context['human_actor_id']) ? (int) $this->context['human_actor_id'] : 0
        );
    }

    /**
     * Get Active Agent persona.
     *
     * @return array array('name' => string, 'id' => int)
     */
    public function getActiveAgent()
    {
        $name = isset($this->context['agent_name']) ? $this->context['agent_name'] : 'none';
        return array(
            'name' => $name,
            'id' => ($name !== 'none' && isset($this->context['actor_id'])) ? (int) $this->context['actor_id'] : 0
        );
    }

    /**
     * Get authenticated user.
     *
     * @return array|null
     */
    public function getAuthUser()
    {
        return $this->auth;
    }

    /**
     * Validate the current context for inconsistencies.
     *
     * @param object|null $db For deeper DB checks
     * @param string $table_prefix
     * @param string $session_md Path to session.md (to check for file-vs-db conflicts)
     * @return array List of warning strings
     */
    public function validate($db = null, $table_prefix = '', $session_md = '')
    {
        $issues = array();

        if ($this->context === null) {
            $issues[] = "Context not bootstrapped.";
            return $issues;
        }

        // Check 1: Session file used but DB session exists (Priority 2 risk)
        if (isset($this->context['context_source']) && $this->context['context_source'] === 'session.md' && $db) {
            // Check if there is an active DB session for the actor in .lupo_actor
            $t = $table_prefix . 'sessions';
            $actor_id = isset($this->context['actor_id']) ? (int) $this->context['actor_id'] : 0;
            if ($actor_id > 0) {
                try {
                    $stmt = $db->prepare("SELECT session_id FROM {$t} WHERE actor_id = :aid AND is_deleted = 0 LIMIT 1");
                    $stmt->execute(array('aid' => $actor_id));
                    if ($stmt->fetch()) {
                        $issues[] = "Session file (session.md) is being used, but a database session also exists for this actor. Potential split-brain context.";
                    }
                } catch (Exception $e) {
                    // Ignore DB errors in validation
                }
            }
        }

        // Check 2: Paired actor mismatch
        $type = isset($this->context['actor_type']) ? $this->context['actor_type'] : '';
        $paired_id = isset($this->context['paired_actor_id']) ? (int) $this->context['paired_actor_id'] : 0;
        if (($type === 'agent' || $type === 'ide_agent') && $paired_id === 0) {
            $issues[] = "Actor is an agent but has no paired human actor ID (paired_actor_id=0).";
        }

        // Check 3: Human identity resolution
        if (isset($this->context['human_actor_name']) && $this->context['human_actor_name'] === 'system' && $paired_id > 0) {
            $issues[] = "Paired actor ID exists ({$paired_id}) but human identity could not be resolved from registry/database.";
        }

        return $issues;
    }
}
