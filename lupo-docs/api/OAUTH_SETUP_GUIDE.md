# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\OAUTH_SETUP_GUIDE.md"
  file_hash: "62b9354e16915d3f377a77e42bb23a19af6f58fb6cca9e8966be079ff6d920c1"
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
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\OAUTH_SETUP_GUIDE.md"
  file_hash: "d555ccbe991ceb607d6d06636d95049e4dcecc25c0ca0b6dc99f8078c11e55d8"
  file_path_from_root: "docs\OAUTH_SETUP_GUIDE.md"
  file_hash: "04cc625c241e05b6ef5903ccbbe736f351ca3c8ce2212f3fc96ca0aceb0cd972"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "OAuth Setup Guide - Quick Start"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "oauth_setup_guidemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# OAuth Setup Guide - Quick Start

**Version:** 4.0.31  
**Date:** 2026-02-23  
**Time to Complete:** 15 minutes  
**X-Lupo-Forwarded:** 1001:10000  

## Overview

This guide walks you through setting up Google and GitHub OAuth authentication for Lupopedia.

## Prerequisites

- Lupopedia 4.0.83 or higher installed
- Access to `lupopedia-config.php`
- Google account (for Google OAuth)
- GitHub account (for GitHub OAuth)

## Step 1: Google OAuth Setup (10 minutes)

### 1.1 Create Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Click "Select a project" → "New Project"
3. Enter project name: "Lupopedia OAuth"
4. Click "Create"

### 1.2 Enable Google+ API

1. In your project, go to "APIs & Services" → "Library"
2. Search for "Google+ API"
3. Click "Enable"

### 1.3 Create OAuth Credentials

1. Go to "APIs & Services" → "Credentials"
2. Click "Create Credentials" → "OAuth client ID"
3. If prompted, configure OAuth consent screen:
   - User Type: External
   - App name: "Lupopedia"
   - User support email: your email
   - Developer contact: your email
   - Click "Save and Continue"
   - Scopes: Skip (click "Save and Continue")
   - Test users: Add your email
   - Click "Save and Continue"

4. Create OAuth Client ID:
   - Application type: "Web application"
   - Name: "Lupopedia Web Client"
   - Authorized redirect URIs: Add your callback URL
     ```
     https://yourdomain.com/lupopedia/oauth/callback/google
     ```
     (Replace with your actual domain and Lupopedia path)
   - Click "Create"

5. Copy your credentials:
   - Client ID: `1234567890-abcdefghijklmnop.apps.googleusercontent.com`
   - Client Secret: `GOCSPX-abcdefghijklmnopqrstuvwx`

### 1.4 Add to Configuration

Edit `lupopedia-config.php` and add:

```php
// Google OAuth Configuration
define('OAUTH_GOOGLE_CLIENT_ID', '1234567890-abcdefghijklmnop.apps.googleusercontent.com');
define('OAUTH_GOOGLE_CLIENT_SECRET', 'GOCSPX-abcdefghijklmnopqrstuvwx');
```

## Step 2: GitHub OAuth Setup (5 minutes)

### 2.1 Register OAuth Application

1. Go to [GitHub Developer Settings](https://github.com/settings/developers)
2. Click "New OAuth App"
3. Fill in application details:
   - Application name: "Lupopedia"
   - Homepage URL: `https://yourdomain.com/lupopedia`
   - Application description: "Lupopedia authentication"
   - Authorization callback URL:
     ```
     https://yourdomain.com/lupopedia/oauth/callback/github
     ```
     (Replace with your actual domain and Lupopedia path)
4. Click "Register application"

### 2.2 Generate Client Secret

1. On your OAuth app page, click "Generate a new client secret"
2. Copy the secret immediately (you won't see it again)

### 2.3 Copy Credentials

- Client ID: `Iv1.1234567890abcdef`
- Client Secret: `1234567890abcdef1234567890abcdef12345678`

### 2.4 Add to Configuration

Edit `lupopedia-config.php` and add:

```php
// GitHub OAuth Configuration
define('OAUTH_GITHUB_CLIENT_ID', 'Iv1.1234567890abcdef');
define('OAUTH_GITHUB_CLIENT_SECRET', '1234567890abcdef1234567890abcdef12345678');
```

## Step 3: Test OAuth Login

### 3.1 Visit Login Page

1. Go to `https://yourdomain.com/lupopedia/login`
2. You should see OAuth buttons:
   - "Continue with Google"
   - "Continue with GitHub"

### 3.2 Test Google Login

1. Click "Continue with Google"
2. Select your Google account
3. Grant permissions to Lupopedia
4. You should be redirected back and logged in

### 3.3 Test GitHub Login

1. Log out if logged in
2. Go back to login page
3. Click "Continue with GitHub"
4. Authorize Lupopedia
5. You should be redirected back and logged in

## Troubleshooting

### OAuth Buttons Not Showing

**Problem:** Login page doesn't show OAuth buttons

**Solutions:**
1. Check that constants are defined in `lupopedia-config.php`
2. Verify constants are not empty strings
3. Clear PHP opcode cache: `opcache_reset()`
4. Check PHP error log for syntax errors

### Redirect URI Mismatch

**Problem:** Error "redirect_uri_mismatch" from provider

**Solutions:**
1. Verify redirect URI in provider settings exactly matches:
   ```
   {protocol}://{domain}{LUPOPEDIA_PUBLIC_PATH}/oauth/callback/{provider}
   ```
2. Check for trailing slashes (should not have one)
3. Verify HTTPS vs HTTP matches
4. For subdirectory installs, include full path

### Invalid Client Error

**Problem:** "invalid_client" error during OAuth flow

**Solutions:**
1. Verify Client ID is correct
2. Verify Client Secret is correct
3. Check for extra spaces in configuration
4. Regenerate client secret if needed

### Session Not Created

**Problem:** OAuth succeeds but user not logged in

**Solutions:**
1. Check `lupo_sessions` table exists
2. Verify session configuration in `lupopedia-config.php`
3. Check PHP session settings
4. Verify write permissions on session directory

### Email Already Exists

**Problem:** Error "Email already exists"

**Note:** This is actually correct behavior! The system automatically links OAuth to existing accounts with matching emails. If you see this error, it means the linking failed.

**Solutions:**
1. Check database logs for errors
2. Verify `lupo_auth_users` table structure
3. Check unique constraints on table

## Local Development Setup

### Using Localhost

For local testing, configure OAuth redirect URIs:

**Google:**
```
http://localhost/lupopedia/oauth/callback/google
```

**GitHub:**
```
http://localhost/lupopedia/oauth/callback/github
```

**Note:** Google may require HTTPS even for localhost. Consider using:
- ngrok for HTTPS tunnel
- Local SSL certificate
- `https://localhost` with self-signed cert

### Using ngrok

1. Install ngrok: `https://ngrok.com/download`
2. Start ngrok: `ngrok http 80`
3. Use ngrok URL in OAuth settings:
   ```
   https://abc123.ngrok.io/lupopedia/oauth/callback/google
   ```
4. Update `LUPOPEDIA_PUBLIC_PATH` to match ngrok URL

## Security Best Practices

### Protect Your Secrets

1. **Never commit credentials to version control**
   ```bash
   # Add to .gitignore
   lupopedia-config.php
   ```

2. **Use environment variables for production**
   ```php
   define('OAUTH_GOOGLE_CLIENT_ID', getenv('OAUTH_GOOGLE_CLIENT_ID'));
   define('OAUTH_GOOGLE_CLIENT_SECRET', getenv('OAUTH_GOOGLE_CLIENT_SECRET'));
   ```

3. **Separate credentials per environment**
   - Development OAuth app
   - Staging OAuth app
   - Production OAuth app

### Monitor OAuth Usage

1. Check Google Cloud Console for usage
2. Review GitHub OAuth app access
3. Monitor `lupo_auth_users` for OAuth accounts
4. Check session logs for OAuth logins

## Next Steps

### Add More Providers

The architecture supports additional OAuth providers:

1. **Facebook** - Follow similar setup process
2. **Microsoft** - Azure AD OAuth
3. **Twitter/X** - Twitter OAuth 2.0
4. **LinkedIn** - LinkedIn OAuth
5. **Apple** - Sign in with Apple

See `docs/oauth_authentication.md` for details on adding providers.

### Profile Management

Future enhancements:
- Link multiple OAuth providers to one account
- Unlink OAuth providers
- Set primary authentication method
- Manage connected accounts

## Support

### Documentation

- Full documentation: `docs/oauth_authentication.md`
- Configuration template: `config/oauth.example.php`
- Source code: `app/Services/OAuthService.php`

### Common Issues

- Redirect URI must match exactly (including protocol and path)
- Client secrets are case-sensitive
- OAuth buttons only show when providers are configured
- Account linking is automatic for matching emails

---

**Setup Complete!** Your users can now sign in with Google and GitHub. 🎉

**Last Updated:** 2026-02-23  
**Version:** 4.0.31