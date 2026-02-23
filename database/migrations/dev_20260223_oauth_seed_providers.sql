-- FILE: database/migrations/dev_20260223_oauth_seed_providers.sql
-- TYPE: dev migration (one-time)
-- PURPOSE: Seed lupo_auth_providers with Google and GitHub OAuth2 provider configs.
-- DOCTRINE: No FKs, no triggers. BIGINT YmdHis timestamps. No display widths.
-- NOTE: Replace {{GOOGLE_CLIENT_ID}}, {{GOOGLE_CLIENT_SECRET}}, {{GITHUB_CLIENT_ID}},
--       {{GITHUB_CLIENT_SECRET}} with actual credentials before running.

INSERT INTO lupo_auth_providers (
    auth_provider_id,
    provider_name,
    client_id,
    client_secret,
    scopes,
    authorization_endpoint,
    token_endpoint,
    userinfo_endpoint,
    jwks_uri,
    created_ymdhis,
    updated_ymdhis,
    is_active
) VALUES (
    1,
    'google',
    '{{GOOGLE_CLIENT_ID}}',
    '{{GOOGLE_CLIENT_SECRET}}',
    'openid email profile',
    'https://accounts.google.com/o/oauth2/v2/auth',
    'https://oauth2.googleapis.com/token',
    'https://www.googleapis.com/oauth2/v3/userinfo',
    'https://www.googleapis.com/oauth2/v3/certs',
    20260223145600,
    20260223145600,
    1
);

INSERT INTO lupo_auth_providers (
    auth_provider_id,
    provider_name,
    client_id,
    client_secret,
    scopes,
    authorization_endpoint,
    token_endpoint,
    userinfo_endpoint,
    jwks_uri,
    created_ymdhis,
    updated_ymdhis,
    is_active
) VALUES (
    2,
    'github',
    '{{GITHUB_CLIENT_ID}}',
    '{{GITHUB_CLIENT_SECRET}}',
    'read:user user:email',
    'https://github.com/login/oauth/authorize',
    'https://github.com/login/oauth/access_token',
    'https://api.github.com/user',
    NULL,
    20260223145600,
    20260223145600,
    1
);

-- flip.footer:
--   referenced_by:
--     - install.php
--   consumed_by_services:
--     - App\Services\OAuthService
--   cited_by_docs:
--     - docs/directives/channel_42_broadcast.md
--   related_toons:
--     - docs/toons/lupo_auth_providers.toon.json
--   channels:
--     - 42
