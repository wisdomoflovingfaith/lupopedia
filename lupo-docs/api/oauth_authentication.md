# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\oauth_authentication.md"
  file_hash: "157ed1dd76d7dd4b8877a2eb243f2b17fbd9a971831f11581eec34521fa019b1"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\oauth_authentication.md"
  file_hash: "cfefa69ff2401cf2feab44089dcec98b5865093b867ac916cd1582c31f88102b"
  file_path_from_root: "lupo-docs\oauth_authentication.md"
  file_hash: "9221ddd49ec39e5a4d13c4e63535855537b29bbc338ebeaa5103d39dd6d373b8"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "OAuth Authentication in Lupopedia"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "oauth_authenticationmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# OAuth Authentication in Lupopedia

**Version:** 4.0.31  
**Status:** Active  
**Supported Providers:** Google, GitHub  
**X-Lupo-Forwarded:** 1001:10000  

## Overview

Lupopedia supports OAuth 2.0 authentication allowing users to sign in with their Google or GitHub accounts. The OAuth system integrates seamlessly with Lupopedia's unified actor model and existing authentication infrastructure.

## Architecture

### Components

1. **OAuthService** (`app/Services/OAuthService.php`)
   - Handles OAuth 2.0 flow with providers
   - Exchanges authorization codes for access tokens
   - Retrieves user information from providers
   - Creates or links user accounts

2. **OAuth Controller** (`lupo-includes/modules/auth/oauth-controller.php`)
   - Routes OAuth requests (`/oauth/login/{provider}`, `/oauth/callback/{provider}`)
   - Manages CSRF protection with state tokens
   - Handles OAuth callbacks and session creation

3. **Login Form** (`app/views/auth/login.php`)
   - Displays OAuth buttons when providers are configured
   - Maintains existing email/password login flow

### Database Integration

OAuth users are stored in the existing `lupo_auth_users` table with these fields:

- `auth_provider` - Provider name (google, github, etc.)
- `provider_id` - Unique user ID from provider
- `profile_image_url` - Avatar URL from provider
- `email` - Email address from provider
- `username` - Generated or imported username
- `display_name` - User's display name
- `password_hash` - NULL for OAuth-only accounts

The unique index `lupo_auth_users_unique_provider_user` on (`auth_provider`, `provider_id`) ensures one account per provider identity.

## Configuration

### 1. Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/apis/credentials)
2. Create a new project or select existing
3. Enable Google+ API
4. Create OAuth 2.0 Client ID credentials
5. Add authorized redirect URI:
   ```
   https://yourdomain.com/lupopedia/oauth/callback/google
   ```
6. Copy Client ID and Client Secret

### 2. GitHub OAuth Setup

1. Go to [GitHub Developer Settings](https://github.com/settings/developers)
2. Click "New OAuth App"
3. Fill in application details
4. Set authorization callback URL:
   ```
   https://yourdomain.com/lupopedia/oauth/callback/github
   ```
5. Copy Client ID and Client Secret

### 3. Add to Configuration

Add these constants to your `lupopedia-config.php`:

```php
// Google OAuth
define('OAUTH_GOOGLE_CLIENT_ID', 'your-google-client-id');
define('OAUTH_GOOGLE_CLIENT_SECRET', 'your-google-client-secret');

// GitHub OAuth
define('OAUTH_GITHUB_CLIENT_ID', 'your-github-client-id');
define('OAUTH_GITHUB_CLIENT_SECRET', 'your-github-client-secret');
```

See `config/oauth.example.php` for a complete template.

## OAuth Flow

### 1. Initiation (`/oauth/login/{provider}`)

1. User clicks "Continue with Google" or "Continue with GitHub"
2. System generates CSRF state token
3. Stores state, provider, and redirect URL in session
4. Redirects user to provider's authorization page

### 2. Authorization

1. User authenticates with provider
2. User grants permissions to Lupopedia
3. Provider redirects back to callback URL with authorization code

### 3. Callback (`/oauth/callback/{provider}`)

1. Verify CSRF state token matches
2. Exchange authorization code for access token
3. Retrieve user information from provider
4. Find or create user account:
   - If provider+ID exists: log in existing user
   - If email exists: link OAuth to existing account
   - Otherwise: create new user account
5. Create session and redirect to destination

## User Account Linking

### Automatic Linking

When a user signs in with OAuth and their email matches an existing account:

1. System links OAuth provider to existing account
2. Updates `auth_provider` and `provider_id` fields
3. User can now sign in with either email/password or OAuth

### Manual Linking

Future enhancement: Allow users to link multiple OAuth providers to one account from profile settings.

## Security Features

### CSRF Protection

- State token generated for each OAuth flow
- Stored in session and verified on callback
- Prevents cross-site request forgery attacks

### Session Security

- OAuth sessions use same security as email/password sessions
- Session data stored in `lupo_sessions` table
- Automatic session expiration and cleanup

### Provider Verification

- Only configured providers are allowed
- Client secrets never exposed to browser
- All OAuth communication server-to-server

## Error Handling

### Configuration Errors

- Missing client ID/secret: OAuth buttons hidden
- Invalid provider: Redirect to login with error message

### OAuth Errors

- State mismatch: CSRF protection triggered
- Code exchange failure: Token request failed
- User info failure: Cannot retrieve profile
- Account creation failure: Database error

All errors redirect to `/login` with error message in session.

## Testing

### Local Development

For local testing, configure OAuth redirect URIs:

```
http://localhost/lupopedia/oauth/callback/google
http://localhost/lupopedia/oauth/callback/github
```

Note: Some providers (like Google) may require HTTPS even for localhost.

### Test Accounts

Create test OAuth applications for development:

1. Use separate OAuth apps for dev/staging/production
2. Configure appropriate redirect URIs for each environment
3. Never commit real credentials to version control

## Future Enhancements

### Additional Providers

The architecture supports adding more OAuth providers:

- Facebook
- Microsoft
- Twitter/X
- LinkedIn
- Apple

To add a provider:

1. Add configuration to `OAuthService::loadProviderConfig()`
2. Add normalization logic to `OAuthService::normalizeUserData()`
3. Add button to login form
4. Add configuration constants

### Profile Management

Future features:

- Link multiple OAuth providers to one account
- Unlink OAuth providers
- Set primary authentication method
- Manage connected accounts

### Admin Features

Future admin capabilities:

- View OAuth provider usage statistics
- Disable specific OAuth providers
- Audit OAuth login attempts
- Manage OAuth-only accounts

## Troubleshooting

### OAuth Buttons Not Showing

- Check that constants are defined in `lupopedia-config.php`
- Verify constants are not empty strings
- Clear PHP opcode cache if using OPcache

### Redirect URI Mismatch

- Ensure redirect URI in provider settings exactly matches:
  ```
  {protocol}://{domain}{LUPOPEDIA_PUBLIC_PATH}/oauth/callback/{provider}
  ```
- Check for trailing slashes
- Verify HTTPS vs HTTP

### Email Already Exists

- System automatically links OAuth to existing email account
- User can sign in with either method after linking
- No duplicate accounts created

### Session Not Created

- Check session configuration in `lupopedia-config.php`
- Verify `lupo_sessions` table exists
- Check PHP session settings

## Database Schema

### Relevant Tables

**lupo_auth_users**
```sql
CREATE TABLE lupo_auth_users (
    auth_user_id BIGINT NOT NULL PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    display_name VARCHAR(42) NOT NULL,
    email VARCHAR(100),
    password_hash VARCHAR(255),
    auth_provider VARCHAR(50),
    provider_id VARCHAR(255),
    profile_image_url VARCHAR(2000),
    last_login_ymdhis BIGINT,
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT NOT NULL,
    is_active TINYINT NOT NULL DEFAULT 1,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT,
    UNIQUE KEY lupo_auth_users_unique_provider_user (auth_provider, provider_id)
);
```

**lupo_sessions**
```sql
CREATE TABLE lupo_sessions (
    session_id VARCHAR(255) NOT NULL PRIMARY KEY,
    user_id BIGINT,
    created_ymdhis BIGINT NOT NULL,
    last_activity_ymdhis BIGINT NOT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255)
);
```

## API Reference

### OAuthService Methods

**getAuthorizationUrl($provider, $redirectUri, $state)**
- Returns authorization URL for OAuth provider
- Parameters: provider name, callback URL, CSRF state token
- Returns: string URL or null if not configured

**exchangeCodeForToken($provider, $code, $redirectUri)**
- Exchanges authorization code for access token
- Parameters: provider name, auth code, callback URL
- Returns: array with access_token or null on failure

**getUserInfo($provider, $accessToken)**
- Retrieves user information from provider
- Parameters: provider name, access token
- Returns: normalized user data array or null

**findOrCreateUser($userData)**
- Finds existing user or creates new account
- Parameters: normalized user data
- Returns: user record array or null on failure

**isProviderConfigured($provider)**
- Checks if OAuth provider is configured
- Parameters: provider name
- Returns: boolean

**getConfiguredProviders()**
- Returns list of configured providers
- Returns: array of provider info

### OAuth Controller Functions

**oauth_login_initiate($provider)**
- Initiates OAuth login flow
- Redirects to provider authorization page

**oauth_callback_handle($provider)**
- Handles OAuth callback
- Creates session and redirects to destination

**oauth_route_request($slug)**
- Routes OAuth requests to appropriate handler
- Called by module loader

## Compliance

### GDPR Considerations

- User email and profile data stored with consent
- Users can delete accounts (soft delete)
- OAuth tokens not stored (only used during authentication)
- Profile images loaded from provider CDN

### Privacy

- Minimal data collection (email, name, avatar)
- No tracking or analytics in OAuth flow
- Provider tokens discarded after authentication
- Session data follows existing privacy policy

---

**Last Updated:** 2026-02-23  
**Maintained By:** Lupopedia Development Team  
**Related Documentation:** 
- `lupo-docs/authentication.md`
- `lupo-docs/actor_model.md`
- `lupo-docs/session_management.md`
