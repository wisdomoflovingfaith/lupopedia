<?php
/**
 * file_path_from_root: app/Services/OAuthService.php
 * file.last_modified_system_version: 4.0.29
 * file.last_modified_utc: 20260223145600
 * file.created_by_agent: warp
 * file.purpose: OAuth2 authorization code flow for Google and GitHub providers
 */

namespace App\Services;

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

/**
 * OAuthService — handles OAuth2 authorization code flow.
 *
 * Supports Google and GitHub as Phase 1 providers.
 * Provider configuration is read from lupo_auth_providers table.
 * User identity is stored in lupo_auth_users (auth_provider, provider_id).
 * Actor pairing sets paired_actor_id on the lupo_actors row.
 *
 * PHP 5.3 compatible: no typed properties, no named args, no arrow functions.
 */
class OAuthService
{
    /** @var \PDO_DB */
    private $db;

    /** @var string */
    private $prefix;

    /** @var array Default provider endpoints (used when lupo_auth_providers has no row) */
    private static $defaultEndpoints = array(
        'google' => array(
            'authorization_endpoint' => 'https://accounts.google.com/o/oauth2/v2/auth',
            'token_endpoint'         => 'https://oauth2.googleapis.com/token',
            'userinfo_endpoint'      => 'https://www.googleapis.com/oauth2/v3/userinfo',
            'scopes'                 => 'openid email profile',
        ),
        'github' => array(
            'authorization_endpoint' => 'https://github.com/login/oauth/authorize',
            'token_endpoint'         => 'https://github.com/login/oauth/access_token',
            'userinfo_endpoint'      => 'https://api.github.com/user',
            'scopes'                 => 'read:user user:email',
        ),
    );

    public function __construct($db)
    {
        $this->db = $db;
        $this->prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }

    /**
     * Load provider config from lupo_auth_providers by provider_name.
     *
     * @param string $provider 'google' or 'github'
     * @return array|false Provider row or false
     */
    public function getProviderConfig($provider)
    {
        $provider = strtolower(trim($provider));
        if ($provider === '') {
            return false;
        }
        $table = $this->prefix . 'auth_providers';
        $row = $this->db->fetchRow(
            "SELECT * FROM {$table} WHERE provider_name = :name AND is_active = 1 LIMIT 1",
            array('name' => $provider)
        );
        if ($row) {
            return $row;
        }
        // Fall back to defaults (client_id/secret must still be configured)
        if (isset(self::$defaultEndpoints[$provider])) {
            return array_merge(
                self::$defaultEndpoints[$provider],
                array(
                    'provider_name' => $provider,
                    'client_id'     => '',
                    'client_secret' => '',
                )
            );
        }
        return false;
    }

    /**
     * Build the authorization redirect URL for a provider.
     *
     * @param string $provider 'google' or 'github'
     * @param string $callbackUrl Full callback URL
     * @param string $state CSRF state token
     * @return string|false Authorization URL or false on error
     */
    public function getAuthorizationUrl($provider, $callbackUrl, $state)
    {
        $config = $this->getProviderConfig($provider);
        if (!$config || empty($config['client_id'])) {
            return false;
        }

        $params = array(
            'client_id'     => $config['client_id'],
            'redirect_uri'  => $callbackUrl,
            'state'         => $state,
            'response_type' => 'code',
        );

        $scopes = isset($config['scopes']) ? $config['scopes'] : '';
        if ($scopes !== '') {
            $params['scope'] = $scopes;
        }

        // Google-specific: request offline access for refresh token
        if ($provider === 'google') {
            $params['access_type'] = 'offline';
            $params['prompt'] = 'consent';
        }

        $authEndpoint = isset($config['authorization_endpoint']) ? $config['authorization_endpoint'] : '';
        if ($authEndpoint === '') {
            return false;
        }

        return $authEndpoint . '?' . http_build_query($params, '', '&');
    }

    /**
     * Exchange authorization code for access token, then fetch user info.
     *
     * @param string $provider 'google' or 'github'
     * @param string $code Authorization code from callback
     * @param string $callbackUrl The same callback URL used in the authorization request
     * @return array|false User info array or false on error
     *   Keys: provider, provider_user_id, email, name, avatar_url
     */
    public function handleCallback($provider, $code, $callbackUrl)
    {
        $config = $this->getProviderConfig($provider);
        if (!$config || empty($config['client_id']) || empty($config['client_secret'])) {
            return false;
        }

        // Exchange code for token
        $tokenEndpoint = isset($config['token_endpoint']) ? $config['token_endpoint'] : '';
        if ($tokenEndpoint === '') {
            return false;
        }

        $tokenParams = array(
            'client_id'     => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'code'          => $code,
            'redirect_uri'  => $callbackUrl,
            'grant_type'    => 'authorization_code',
        );

        $tokenResponse = $this->httpPost($tokenEndpoint, $tokenParams, $provider);
        if (!$tokenResponse || !isset($tokenResponse['access_token'])) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('OAuthService: token exchange failed for ' . $provider);
            }
            return false;
        }

        $accessToken = $tokenResponse['access_token'];

        // Fetch user info
        $userinfoEndpoint = isset($config['userinfo_endpoint']) ? $config['userinfo_endpoint'] : '';
        if ($userinfoEndpoint === '') {
            return false;
        }

        $userInfo = $this->httpGet($userinfoEndpoint, $accessToken, $provider);
        if (!$userInfo) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('OAuthService: userinfo fetch failed for ' . $provider);
            }
            return false;
        }

        return $this->normalizeUserInfo($provider, $userInfo, $accessToken);
    }

    /**
     * Normalize user info from different providers into a common format.
     *
     * @param string $provider
     * @param array $userInfo Raw user info from provider
     * @param string $accessToken For GitHub email fetch
     * @return array Normalized: provider, provider_user_id, email, name, avatar_url
     */
    private function normalizeUserInfo($provider, $userInfo, $accessToken)
    {
        if ($provider === 'google') {
            return array(
                'provider'         => 'google',
                'provider_user_id' => isset($userInfo['sub']) ? (string) $userInfo['sub'] : '',
                'email'            => isset($userInfo['email']) ? strtolower($userInfo['email']) : '',
                'name'             => isset($userInfo['name']) ? $userInfo['name'] : '',
                'avatar_url'       => isset($userInfo['picture']) ? $userInfo['picture'] : '',
            );
        }

        if ($provider === 'github') {
            $email = isset($userInfo['email']) ? strtolower($userInfo['email']) : '';
            // GitHub may not include email in profile; fetch from /user/emails
            if ($email === '' && $accessToken !== '') {
                $emails = $this->httpGet('https://api.github.com/user/emails', $accessToken, 'github');
                if (is_array($emails)) {
                    foreach ($emails as $entry) {
                        if (isset($entry['primary']) && $entry['primary'] && isset($entry['email'])) {
                            $email = strtolower($entry['email']);
                            break;
                        }
                    }
                }
            }
            return array(
                'provider'         => 'github',
                'provider_user_id' => isset($userInfo['id']) ? (string) $userInfo['id'] : '',
                'email'            => $email,
                'name'             => isset($userInfo['name']) ? $userInfo['name'] : (isset($userInfo['login']) ? $userInfo['login'] : ''),
                'avatar_url'       => isset($userInfo['avatar_url']) ? $userInfo['avatar_url'] : '',
            );
        }

        return array(
            'provider'         => $provider,
            'provider_user_id' => '',
            'email'            => '',
            'name'             => '',
            'avatar_url'       => '',
        );
    }

    /**
     * Find existing auth_user by provider+provider_id, or by email. Create if not found.
     * Also ensures an actor record exists and is paired with the default agent.
     *
     * @param array $oauthUser Normalized user info from handleCallback()
     * @return array|false Array with 'auth_user_id' and 'actor_id', or false
     */
    public function findOrCreateOAuthUser($oauthUser)
    {
        $provider = isset($oauthUser['provider']) ? $oauthUser['provider'] : '';
        $providerUserId = isset($oauthUser['provider_user_id']) ? $oauthUser['provider_user_id'] : '';
        $email = isset($oauthUser['email']) ? $oauthUser['email'] : '';
        $name = isset($oauthUser['name']) ? $oauthUser['name'] : '';
        $avatarUrl = isset($oauthUser['avatar_url']) ? $oauthUser['avatar_url'] : '';

        if ($provider === '' || $providerUserId === '') {
            return false;
        }

        $authUsersTable = $this->prefix . 'auth_users';
        $actorsTable = $this->prefix . 'actors';
        $now = class_exists('timestamp_ymdhis') ? \timestamp_ymdhis::now() : (int) gmdate('YmdHis');

        // 1. Look up by provider + provider_id
        $existing = $this->db->fetchRow(
            "SELECT auth_user_id, username, email FROM {$authUsersTable} WHERE auth_provider = :provider AND provider_id = :pid AND is_deleted = 0 LIMIT 1",
            array('provider' => $provider, 'pid' => $providerUserId)
        );

        if ($existing) {
            // Update profile image and last login
            $this->db->update(
                $authUsersTable,
                array(
                    'profile_image_url'  => $avatarUrl,
                    'last_login_ymdhis'  => $now,
                    'updated_ymdhis'     => $now,
                ),
                'auth_user_id = :id',
                array('id' => $existing['auth_user_id'])
            );
            $actorId = $this->getOrCreateActorForAuthUser((int) $existing['auth_user_id'], $email, $name);
            if ($actorId) {
                $this->pairWithDefaultAgent($actorId);
            }
            return array('auth_user_id' => (int) $existing['auth_user_id'], 'actor_id' => $actorId);
        }

        // 2. Look up by email (link OAuth to existing local account)
        if ($email !== '') {
            $byEmail = $this->db->fetchRow(
                "SELECT auth_user_id, username FROM {$authUsersTable} WHERE email = :email AND is_deleted = 0 LIMIT 1",
                array('email' => $email)
            );
            if ($byEmail) {
                // Link OAuth provider to existing account
                $this->db->update(
                    $authUsersTable,
                    array(
                        'auth_provider'      => $provider,
                        'provider_id'        => $providerUserId,
                        'profile_image_url'  => $avatarUrl,
                        'last_login_ymdhis'  => $now,
                        'updated_ymdhis'     => $now,
                    ),
                    'auth_user_id = :id',
                    array('id' => $byEmail['auth_user_id'])
                );
                $actorId = $this->getOrCreateActorForAuthUser((int) $byEmail['auth_user_id'], $email, $name);
                if ($actorId) {
                    $this->pairWithDefaultAgent($actorId);
                }
                return array('auth_user_id' => (int) $byEmail['auth_user_id'], 'actor_id' => $actorId);
            }
        }

        // 3. Create new auth_user
        $authUserId = function_exists('lupo_findpuka')
            ? lupo_findpuka($this->db, $authUsersTable, 'auth_user_id', 1, null)
            : null;
        if ($authUserId === null) {
            return false;
        }

        $username = $this->generateUsername($provider, $email, $name);

        $ok = $this->db->insert($authUsersTable, array(
            'auth_user_id'      => $authUserId,
            'username'          => $username,
            'display_name'      => substr($name, 0, 42),
            'email'             => $email !== '' ? $email : null,
            'password_hash'     => null,
            'auth_provider'     => $provider,
            'provider_id'       => $providerUserId,
            'profile_image_url' => $avatarUrl,
            'last_login_ymdhis' => $now,
            'created_ymdhis'    => $now,
            'updated_ymdhis'    => $now,
            'is_active'         => 1,
            'is_deleted'        => 0,
        ));

        if ($ok === false) {
            return false;
        }

        $actorId = $this->getOrCreateActorForAuthUser($authUserId, $email, $name);
        if ($actorId) {
            $this->pairWithDefaultAgent($actorId);
        }

        return array('auth_user_id' => $authUserId, 'actor_id' => $actorId);
    }

    /**
     * Get or create actor for an auth_user_id. Delegates to ActorService if available.
     *
     * @param int $authUserId
     * @param string $email
     * @param string $name
     * @return int|false Actor ID or false
     */
    private function getOrCreateActorForAuthUser($authUserId, $email, $name)
    {
        $actorService = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
        if ($actorService) {
            $actorId = $actorService->getActorIdFromAuthUserId($authUserId);
            if ($actorId) {
                return $actorId;
            }
            return $actorService->createActorForAuthUser($authUserId, $email, $name);
        }

        // Fallback: use legacy helper functions
        if (function_exists('lupo_get_actor_id_from_auth_user_id')) {
            $actorId = lupo_get_actor_id_from_auth_user_id($authUserId);
            if ($actorId) {
                return $actorId;
            }
        }
        if (function_exists('lupo_create_actor_for_auth_user')) {
            return lupo_create_actor_for_auth_user($authUserId, $email, $name);
        }

        return false;
    }

    /**
     * Set paired_actor_id on the actor row to the default AI agent (1000 = CAPTAIN WOLFIE).
     *
     * @param int $actorId Human actor ID
     * @return bool
     */
    public function pairWithDefaultAgent($actorId)
    {
        $defaultAgentId = 1000; // CAPTAIN WOLFIE AI
        $actorsTable = $this->prefix . 'actors';
        $now = class_exists('timestamp_ymdhis') ? \timestamp_ymdhis::now() : (int) gmdate('YmdHis');

        try {
            $this->db->update(
                $actorsTable,
                array(
                    'paired_actor_id' => $defaultAgentId,
                    'updated_ymdhis'  => $now,
                ),
                'actor_id = :id AND (is_deleted = 0 OR is_deleted IS NULL)',
                array('id' => $actorId)
            );
            return true;
        } catch (\Exception $e) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('OAuthService::pairWithDefaultAgent failed: ' . $e->getMessage());
            }
            return false;
        }
    }

    /**
     * Generate a unique username from provider + email/name.
     *
     * @param string $provider
     * @param string $email
     * @param string $name
     * @return string
     */
    private function generateUsername($provider, $email, $name)
    {
        if ($email !== '') {
            $local = strpos($email, '@') !== false ? substr($email, 0, strpos($email, '@')) : $email;
        } else {
            $local = $name !== '' ? $name : $provider . '_user';
        }
        $slug = strtolower(preg_replace('/[^a-z0-9_]/', '_', strtolower($local)));
        $slug = preg_replace('/_+/', '_', $slug);
        $slug = trim($slug, '_');
        if ($slug === '') {
            $slug = $provider . '_user';
        }

        $authUsersTable = $this->prefix . 'auth_users';
        $base = $slug;
        $counter = 0;
        while (true) {
            $candidate = $counter === 0 ? $base : $base . '_' . $counter;
            $check = $this->db->fetchRow(
                "SELECT 1 FROM {$authUsersTable} WHERE username = :u AND is_deleted = 0 LIMIT 1",
                array('u' => $candidate)
            );
            if (!$check) {
                return $candidate;
            }
            $counter++;
            if ($counter > 100) {
                return $base . '_' . gmdate('YmdHis');
            }
        }
    }

    /**
     * Generate a CSRF state token and store it in session.
     *
     * @return string State token
     */
    public function generateStateToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // PHP 5.3 compatible random bytes
        if (function_exists('random_bytes')) {
            $bytes = random_bytes(32);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes(32);
        } else {
            $bytes = '';
            for ($i = 0; $i < 32; $i++) {
                $bytes .= chr(mt_rand(0, 255));
            }
        }
        $state = bin2hex($bytes);
        $_SESSION['oauth_state'] = $state;
        return $state;
    }

    /**
     * Validate CSRF state token from callback.
     *
     * @param string $state State from callback query string
     * @return bool
     */
    public function validateStateToken($state)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['oauth_state']) || $_SESSION['oauth_state'] === '') {
            return false;
        }
        $valid = hash_equals($_SESSION['oauth_state'], $state);
        unset($_SESSION['oauth_state']);
        return $valid;
    }

    /**
     * HTTP POST request (cURL).
     *
     * @param string $url
     * @param array $params POST body (form-urlencoded)
     * @param string $provider For Accept header handling
     * @return array|false Decoded JSON or false
     */
    private function httpPost($url, $params, $provider)
    {
        if (!function_exists('curl_init')) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('OAuthService: cURL extension not available');
            }
            return false;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params, '', '&'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $headers = array('Content-Type: application/x-www-form-urlencoded');
        // GitHub token endpoint returns JSON when Accept header is set
        if ($provider === 'github') {
            $headers[] = 'Accept: application/json';
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode >= 400) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('OAuthService httpPost: HTTP ' . $httpCode . ' from ' . $url);
            }
            return false;
        }

        $decoded = json_decode($response, true);
        return is_array($decoded) ? $decoded : false;
    }

    /**
     * HTTP GET request with Bearer token (cURL).
     *
     * @param string $url
     * @param string $accessToken
     * @param string $provider
     * @return array|false Decoded JSON or false
     */
    private function httpGet($url, $accessToken, $provider)
    {
        if (!function_exists('curl_init')) {
            return false;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $headers = array(
            'Authorization: Bearer ' . $accessToken,
            'Accept: application/json',
        );
        // GitHub API requires User-Agent
        if ($provider === 'github') {
            $headers[] = 'User-Agent: Lupopedia/4.0';
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode >= 400) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('OAuthService httpGet: HTTP ' . $httpCode . ' from ' . $url);
            }
            return false;
        }

        $decoded = json_decode($response, true);
        return is_array($decoded) ? $decoded : false;
    }
}

/*
 * flip.footer:
 *   referenced_by:
 *     - lupo-includes/modules/auth/oauth_controller.php
 *     - lupo-includes/modules/auth/auth-controller.php
 *   consumed_by_services:
 *     - OAuthService (self)
 *   cited_by_docs:
 *     - docs/directives/channel_42_broadcast.md
 *     - docs/doctrine/flip_footer_doctrine.md
 *   related_toons:
 *     - docs/toons/lupo_auth_providers.toon.json
 *     - docs/toons/lupo_auth_users.toon.json
 *     - docs/toons/lupo_actors.toon.json
 *   channels:
 *     - 42
 */
