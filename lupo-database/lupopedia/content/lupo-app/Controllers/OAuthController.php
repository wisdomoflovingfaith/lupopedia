---
# FLIP Header (alias: Wolfie Header, CROP Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: "app/Controllers/OAuthController.php"
file.last_modified_system_version: "4.0.31"
file.last_modified_utc: "20260223144700"
channel_id: 42
mood_rgb: "4B0082"
---

<?php

/**
 * OAuth Controller - Unified OAuth Authentication for Lupopedia
 * 
 * Handles Google OAuth2 and GitHub OAuth2 authentication
 * Creates human user actors (actor_id 10000+) and pairs with AI partners
 * Integrates with FLIP Header Doctrine and semantic security
 * 
 * @author Lupopedia Development Team
 * @version 4.0.31
 */

class OAuthController {
    
    private $db;
    private $actor_service;
    private $auth_service;
    
    // OAuth provider configurations
    private $providers = [
        'google' => [
            'auth_url' => 'https://accounts.google.com/o/oauth2/v2/auth',
            'token_url' => 'https://oauth2.googleapis.com/token',
            'user_info_url' => 'https://www.googleapis.com/oauth2/v2/userinfo',
            'scope' => 'openid email profile'
        ],
        'github' => [
            'auth_url' => 'https://github.com/login/oauth/authorize',
            'token_url' => 'https://github.com/login/oauth/access_token',
            'user_info_url' => 'https://api.github.com/user',
            'scope' => 'user:email'
        ]
    ];
    
    public function __construct() {
        $this->db = DatabaseFactory::getConnection();
        $this->actor_service = $GLOBALS['lupo_actor_service'];
        $this->auth_service = $GLOBALS['lupo_auth_service'];
    }
    
    /**
     * Redirect to OAuth provider authorization
     */
    public function redirectToProvider($provider) {
        if (!isset($this->providers[$provider])) {
            throw new Exception("Unsupported OAuth provider: {$provider}");
        }
        
        $config = $this->providers[$provider];
        $client_id = $this->getClientId($provider);
        $redirect_uri = $this->getRedirectUri($provider);
        $state = $this->generateState();
        
        // Store state in session for CSRF protection
        $_SESSION['oauth_state'] = $state;
        $_SESSION['oauth_provider'] = $provider;
        
        $auth_url = $config['auth_url'] . '?' . http_build_query([
            'client_id' => $client_id,
            'redirect_uri' => $redirect_uri,
            'scope' => $config['scope'],
            'response_type' => 'code',
            'state' => $state
        ]);
        
        header('Location: ' . $auth_url);
        exit;
    }
    
    /**
     * Handle OAuth callback
     */
    public function handleCallback($provider) {
        // Verify state for CSRF protection
        if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['oauth_state']) {
            throw new Exception('Invalid OAuth state');
        }
        
        if (!isset($_GET['code'])) {
            throw new Exception('Authorization code not found');
        }
        
        $config = $this->providers[$provider];
        $code = $_GET['code'];
        
        // Exchange authorization code for access token
        $token_data = $this->exchangeCodeForToken($provider, $code);
        
        // Get user information
        $user_info = $this->getUserInfo($provider, $token_data['access_token']);
        
        // Create or update human actor
        $human_actor_id = $this->createOrUpdateHumanActor($provider, $user_info);
        
        // Pair with AI partner (Captain Wolfie - actor_id 1000)
        $this->pairWithAIPartner($human_actor_id);
        
        // Create session for human user
        $this->createHumanSession($human_actor_id);
        
        // Redirect to dashboard
        header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/dashboard');
        exit;
    }
    
    /**
     * Exchange authorization code for access token
     */
    private function exchangeCodeForToken($provider, $code) {
        $config = $this->providers[$provider];
        $client_id = $this->getClientId($provider);
        $client_secret = $this->getClientSecret($provider);
        $redirect_uri = $this->getRedirectUri($provider);
        
        $post_data = [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'code' => $code,
            'redirect_uri' => $redirect_uri,
            'grant_type' => 'authorization_code'
        ];
        
        $ch = curl_init($config['token_url']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code !== 200) {
            throw new Exception("Failed to exchange code for token");
        }
        
        return json_decode($response, true);
    }
    
    /**
     * Get user information from OAuth provider
     */
    private function getUserInfo($provider, $access_token) {
        $config = $this->providers[$provider];
        
        $ch = curl_init($config['user_info_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_token,
            'Accept: application/json'
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code !== 200) {
            throw new Exception("Failed to get user information");
        }
        
        return json_decode($response, true);
    }
    
    /**
     * Create or update human actor from OAuth user info
     */
    private function createOrUpdateHumanActor($provider, $user_info) {
        $oauth_id = $user_info['id'];
        $email = $user_info['email'];
        $name = $user_info['name'] ?? $user_info['login'];
        $avatar_url = $user_info['avatar_url'] ?? $user_info['picture'] ?? '';
        
        // Check if OAuth user already exists
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        
        $existing = $this->db->fetchRow(
            "SELECT actor_id FROM {$table_prefix}auth_oauth 
             WHERE provider = :provider AND oauth_id = :oauth_id",
            ['provider' => $provider, 'oauth_id' => $oauth_id]
        );
        
        if ($existing) {
            // Update existing actor
            $this->db->update(
                "{$table_prefix}actors",
                [
                    'actor_name' => $name,
                    'actor_email' => $email,
                    'actor_avatar' => $avatar_url,
                    'modified_ymdhis' => gmdate('YmdHis')
                ],
                ['actor_id' => $existing['actor_id']]
            );
            
            return $existing['actor_id'];
        }
        
        // Create new human actor (actor_id 10000+)
        $actor_data = [
            'actor_name' => $name,
            'actor_email' => $email,
            'actor_avatar' => $avatar_url,
            'actor_type' => 'human',
            'actor_status' => 'active',
            'security_level' => 'standard',
            'created_ymdhis' => gmdate('YmdHis'),
            'modified_ymdhis' => gmdate('YmdHis')
        ];
        
        $this->db->insert("{$table_prefix}actors", $actor_data);
        $actor_id = $this->db->lastInsertId();
        
        // Ensure actor_id is in human range (10000+)
        if ($actor_id < 10000) {
            $actor_id = 10000 + $actor_id;
            $this->db->update(
                "{$table_prefix}actors",
                ['actor_id' => $actor_id],
                ['actor_id' => $this->db->lastInsertId()]
            );
        }
        
        // Store OAuth mapping
        $this->db->insert("{$table_prefix}auth_oauth", [
            'actor_id' => $actor_id,
            'provider' => $provider,
            'oauth_id' => $oauth_id,
            'created_ymdhis' => gmdate('YmdHis')
        ]);
        
        return $actor_id;
    }
    
    /**
     * Pair human actor with AI partner (Captain Wolfie)
     */
    private function pairWithAIPartner($human_actor_id) {
        $ai_partner_id = 1000; // Captain Wolfie
        
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        
        // Check if pairing already exists
        $existing = $this->db->fetchRow(
            "SELECT id FROM {$table_prefix}actor_pairs 
             WHERE human_actor_id = :human_id AND ai_actor_id = :ai_id",
            ['human_id' => $human_actor_id, 'ai_id' => $ai_partner_id]
        );
        
        if (!$existing) {
            $this->db->insert("{$table_prefix}actor_pairs", [
                'human_actor_id' => $human_actor_id,
                'ai_actor_id' => $ai_partner_id,
                'pairing_status' => 'active',
                'created_ymdhis' => gmdate('YmdHis')
            ]);
        }
    }
    
    /**
     * Create session for authenticated human user
     */
    private function createHumanSession($actor_id) {
        $session_id = session_id();
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        
        // Update existing session with actor information
        $this->db->update(
            "{$table_prefix}sessions",
            [
                'actor_id' => $actor_id,
                'session_type' => 'oauth_authenticated',
                'authenticated_ymdhis' => gmdate('YmdHis'),
                'modified_ymdhis' => gmdate('YmdHis')
            ],
            ['session_id' => $session_id]
        );
        
        // Store actor information in session
        $_SESSION['authenticated_actor_id'] = $actor_id;
        $_SESSION['authenticated_via_oauth'] = true;
        $_SESSION['oauth_authenticated_at'] = gmdate('YmdHis');
    }
    
    /**
     * Get OAuth client ID for provider
     */
    private function getClientId($provider) {
        $config_key = "oauth_{$provider}_client_id";
        return defined($config_key) ? constant($config_key) : '';
    }
    
    /**
     * Get OAuth client secret for provider
     */
    private function getClientSecret($provider) {
        $config_key = "oauth_{$provider}_client_secret";
        return defined($config_key) ? constant($config_key) : '';
    }
    
    /**
     * Get OAuth redirect URI for provider
     */
    private function getRedirectUri($provider) {
        if (function_exists('lupo_index_slug_url')) {
            return lupo_index_slug_url('oauth/callback/' . $provider);
        }
        $pub = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
        return $pub . '/index.php?' . http_build_query(array('slug' => 'oauth/callback/' . $provider));
    }
    
    /**
     * Generate CSRF protection state
     */
    private function generateState() {
        return bin2hex(random_bytes(16));
    }
    
    /**
     * Logout current OAuth session
     */
    public function logout() {
        // Clear session
        session_destroy();
        
        // Redirect to home
        header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/');
        exit;
    }
}

// Route handlers
if (isset($_GET['action'])) {
    $oauth_controller = new OAuthController();
    
    switch ($_GET['action']) {
        case 'redirect':
            $provider = $_GET['provider'] ?? '';
            $oauth_controller->redirectToProvider($provider);
            break;
            
        case 'callback':
            $provider = $_GET['provider'] ?? '';
            $oauth_controller->handleCallback($provider);
            break;
            
        case 'logout':
            $oauth_controller->logout();
            break;
    }
}

?>
