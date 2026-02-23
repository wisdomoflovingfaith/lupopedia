<?php
/**
 * OAuth Authentication Service
 * 
 * Handles OAuth 2.0 authentication for Google, GitHub, and other providers.
 * Integrates with Lupopedia's unified actor model and auth system.
 * 
 * @package Lupopedia
 * @subpackage Services
 * @version 4.0.31
 * @x_lupo_forwarded 1001:10000
 */

namespace App\Services;

class OAuthService {
    
    private $db;
    private $tablePrefix;
    private $providers;
    
    /**
     * Constructor
     * 
     * @param object $db Database connection (PDO_DB wrapper)
     */
    public function __construct($db) {
        $this->db = $db;
        $this->tablePrefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $this->loadProviderConfig();
    }
    
    /**
     * Load OAuth provider configuration
     */
    private function loadProviderConfig() {
        $this->providers = array(
            'google' => array(
                'client_id' => defined('OAUTH_GOOGLE_CLIENT_ID') ? OAUTH_GOOGLE_CLIENT_ID : '',
                'client_secret' => defined('OAUTH_GOOGLE_CLIENT_SECRET') ? OAUTH_GOOGLE_CLIENT_SECRET : '',
                'auth_url' => 'https://accounts.google.com/o/oauth2/v2/auth',
                'token_url' => 'https://oauth2.googleapis.com/token',
                'user_info_url' => 'https://www.googleapis.com/oauth2/v2/userinfo',
                'scope' => 'openid email profile',
                'name' => 'Google'
            ),
            'github' => array(
                'client_id' => defined('OAUTH_GITHUB_CLIENT_ID') ? OAUTH_GITHUB_CLIENT_ID : '',
                'client_secret' => defined('OAUTH_GITHUB_CLIENT_SECRET') ? OAUTH_GITHUB_CLIENT_SECRET : '',
                'auth_url' => 'https://github.com/login/oauth/authorize',
                'token_url' => 'https://github.com/login/oauth/access_token',
                'user_info_url' => 'https://api.github.com/user',
                'scope' => 'user:email',
                'name' => 'GitHub'
            )
        );
    }
    
    /**
     * Get authorization URL for OAuth provider
     * 
     * @param string $provider Provider name (google, github)
     * @param string $redirectUri Callback URL
     * @param string $state CSRF state token
     * @return string|null Authorization URL or null if provider not configured
     */
    public function getAuthorizationUrl($provider, $redirectUri, $state) {
        if (!isset($this->providers[$provider])) {
            return null;
        }
        
        $config = $this->providers[$provider];
        
        if (empty($config['client_id'])) {
            return null;
        }
        
        $params = array(
            'client_id' => $config['client_id'],
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $config['scope'],
            'state' => $state
        );
        
        return $config['auth_url'] . '?' . http_build_query($params);
    }
    
    /**
     * Exchange authorization code for access token
     * 
     * @param string $provider Provider name
     * @param string $code Authorization code
     * @param string $redirectUri Callback URL
     * @return array|null Token data or null on failure
     */
    public function exchangeCodeForToken($provider, $code, $redirectUri) {
        if (!isset($this->providers[$provider])) {
            return null;
        }
        
        $config = $this->providers[$provider];
        
        $postData = array(
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code'
        );
        
        $ch = curl_init($config['token_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json'
        ));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            return null;
        }
        
        $data = json_decode($response, true);
        
        if (!isset($data['access_token'])) {
            return null;
        }
        
        return $data;
    }
    
    /**
     * Get user information from OAuth provider
     * 
     * @param string $provider Provider name
     * @param string $accessToken Access token
     * @return array|null User data or null on failure
     */
    public function getUserInfo($provider, $accessToken) {
        if (!isset($this->providers[$provider])) {
            return null;
        }
        
        $config = $this->providers[$provider];
        
        $ch = curl_init($config['user_info_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $accessToken,
            'Accept: application/json',
            'User-Agent: Lupopedia-OAuth-Client'
        ));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            return null;
        }
        
        $userData = json_decode($response, true);
        
        return $this->normalizeUserData($provider, $userData);
    }
    
    /**
     * Normalize user data from different providers to common format
     * 
     * @param string $provider Provider name
     * @param array $rawData Raw user data from provider
     * @return array Normalized user data
     */
    private function normalizeUserData($provider, $rawData) {
        $normalized = array(
            'provider' => $provider,
            'provider_id' => null,
            'email' => null,
            'name' => null,
            'username' => null,
            'avatar' => null
        );
        
        switch ($provider) {
            case 'google':
                $normalized['provider_id'] = isset($rawData['id']) ? $rawData['id'] : null;
                $normalized['email'] = isset($rawData['email']) ? $rawData['email'] : null;
                $normalized['name'] = isset($rawData['name']) ? $rawData['name'] : null;
                $normalized['avatar'] = isset($rawData['picture']) ? $rawData['picture'] : null;
                $normalized['username'] = isset($rawData['email']) ? explode('@', $rawData['email'])[0] : null;
                break;
                
            case 'github':
                $normalized['provider_id'] = isset($rawData['id']) ? (string)$rawData['id'] : null;
                $normalized['email'] = isset($rawData['email']) ? $rawData['email'] : null;
                $normalized['name'] = isset($rawData['name']) ? $rawData['name'] : null;
                $normalized['username'] = isset($rawData['login']) ? $rawData['login'] : null;
                $normalized['avatar'] = isset($rawData['avatar_url']) ? $rawData['avatar_url'] : null;
                
                // GitHub may not return email in main response
                if (empty($normalized['email']) && !empty($rawData['email'])) {
                    $normalized['email'] = $rawData['email'];
                }
                break;
        }
        
        return $normalized;
    }
    
    /**
     * Find or create user from OAuth data
     * 
     * @param array $userData Normalized user data from OAuth provider
     * @return array|null User record or null on failure
     */
    public function findOrCreateUser($userData) {
        // Check if user exists with this provider
        $existingUser = $this->db->fetchOne(
            "SELECT * FROM {$this->tablePrefix}auth_users 
             WHERE auth_provider = :provider 
             AND provider_id = :provider_id 
             AND is_deleted = 0",
            array(
                'provider' => $userData['provider'],
                'provider_id' => $userData['provider_id']
            )
        );
        
        if ($existingUser) {
            // Update last login
            $this->db->update(
                $this->tablePrefix . 'auth_users',
                array(
                    'last_login_ymdhis' => gmdate('YmdHis'),
                    'updated_ymdhis' => gmdate('YmdHis')
                ),
                array('auth_user_id' => $existingUser['auth_user_id'])
            );
            
            return $existingUser;
        }
        
        // Check if user exists with same email
        if (!empty($userData['email'])) {
            $existingByEmail = $this->db->fetchOne(
                "SELECT * FROM {$this->tablePrefix}auth_users 
                 WHERE email = :email 
                 AND is_deleted = 0",
                array('email' => $userData['email'])
            );
            
            if ($existingByEmail) {
                // Link OAuth provider to existing account
                $this->db->update(
                    $this->tablePrefix . 'auth_users',
                    array(
                        'auth_provider' => $userData['provider'],
                        'provider_id' => $userData['provider_id'],
                        'profile_image_url' => $userData['avatar'],
                        'last_login_ymdhis' => gmdate('YmdHis'),
                        'updated_ymdhis' => gmdate('YmdHis')
                    ),
                    array('auth_user_id' => $existingByEmail['auth_user_id'])
                );
                
                return $existingByEmail;
            }
        }
        
        // Create new user
        $username = $this->generateUniqueUsername($userData);
        $displayName = !empty($userData['name']) ? substr($userData['name'], 0, 42) : $username;
        
        $newUserId = $this->db->insert(
            $this->tablePrefix . 'auth_users',
            array(
                'username' => $username,
                'display_name' => $displayName,
                'email' => $userData['email'],
                'password_hash' => null,
                'auth_provider' => $userData['provider'],
                'provider_id' => $userData['provider_id'],
                'profile_image_url' => $userData['avatar'],
                'last_login_ymdhis' => gmdate('YmdHis'),
                'created_ymdhis' => gmdate('YmdHis'),
                'updated_ymdhis' => gmdate('YmdHis'),
                'is_active' => 1,
                'is_deleted' => 0
            )
        );
        
        if (!$newUserId) {
            return null;
        }
        
        return $this->db->fetchOne(
            "SELECT * FROM {$this->tablePrefix}auth_users WHERE auth_user_id = :id",
            array('id' => $newUserId)
        );
    }
    
    /**
     * Generate unique username from OAuth data
     * 
     * @param array $userData Normalized user data
     * @return string Unique username
     */
    private function generateUniqueUsername($userData) {
        $baseUsername = !empty($userData['username']) ? $userData['username'] : 'user';
        
        // Sanitize username
        $baseUsername = preg_replace('/[^a-z0-9_]/', '', strtolower($baseUsername));
        
        if (empty($baseUsername)) {
            $baseUsername = 'user';
        }
        
        $username = $baseUsername;
        $counter = 1;
        
        while ($this->usernameExists($username)) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
    
    /**
     * Check if username exists
     * 
     * @param string $username Username to check
     * @return bool True if exists
     */
    private function usernameExists($username) {
        $result = $this->db->fetchOne(
            "SELECT auth_user_id FROM {$this->tablePrefix}auth_users 
             WHERE username = :username AND is_deleted = 0",
            array('username' => $username)
        );
        
        return !empty($result);
    }
    
    /**
     * Check if provider is configured
     * 
     * @param string $provider Provider name
     * @return bool True if configured
     */
    public function isProviderConfigured($provider) {
        return isset($this->providers[$provider]) 
            && !empty($this->providers[$provider]['client_id'])
            && !empty($this->providers[$provider]['client_secret']);
    }
    
    /**
     * Get list of configured providers
     * 
     * @return array Array of configured provider names
     */
    public function getConfiguredProviders() {
        $configured = array();
        
        foreach ($this->providers as $name => $config) {
            if ($this->isProviderConfigured($name)) {
                $configured[] = array(
                    'name' => $name,
                    'display_name' => $config['name']
                );
            }
        }
        
        return $configured;
    }
}
