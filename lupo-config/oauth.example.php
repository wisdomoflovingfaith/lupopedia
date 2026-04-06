<?php
/**
 * OAuth Configuration Example
 * 
 * Copy this file to lupopedia-config.php and add these constants
 * to enable OAuth authentication with Google and GitHub.
 * 
 * @package Lupopedia
 * @version 4.0.31
 * @x_lupo_forwarded 1001:10000
 */

// ============================================
// GOOGLE OAUTH CONFIGURATION
// ============================================
// Get credentials from: https://console.cloud.google.com/apis/credentials
// 
// 1. Create a new OAuth 2.0 Client ID
// 2. Set authorized redirect URI to (no mod_rewrite): https://yourdomain.com/your-lupopedia-path/index.php?slug=oauth%2Fcallback%2Fgoogle
// 3. Copy Client ID and Client Secret below

define('OAUTH_GOOGLE_CLIENT_ID', '');
define('OAUTH_GOOGLE_CLIENT_SECRET', '');

// ============================================
// GITHUB OAUTH CONFIGURATION
// ============================================
// Get credentials from: https://github.com/settings/developers
// 
// 1. Register a new OAuth application
// 2. Set authorization callback URL to: https://yourdomain.com/your-lupopedia-path/index.php?slug=oauth%2Fcallback%2Fgithub
// 3. Copy Client ID and Client Secret below

define('OAUTH_GITHUB_CLIENT_ID', '');
define('OAUTH_GITHUB_CLIENT_SECRET', '');

// ============================================
// FUTURE OAUTH PROVIDERS
// ============================================
// Uncomment and configure when ready to add more providers

// Facebook OAuth
// define('OAUTH_FACEBOOK_CLIENT_ID', '');
// define('OAUTH_FACEBOOK_CLIENT_SECRET', '');

// Microsoft OAuth
// define('OAUTH_MICROSOFT_CLIENT_ID', '');
// define('OAUTH_MICROSOFT_CLIENT_SECRET', '');

// Twitter/X OAuth
// define('OAUTH_TWITTER_CLIENT_ID', '');
// define('OAUTH_TWITTER_CLIENT_SECRET', '');
