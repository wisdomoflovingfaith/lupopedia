---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: changelog
  when_updated: "20260406064800"
  file_path_from_root: "lupo-docs/versions/4.0.95/CHANGELOG.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.95/CHANGELOG.md"
  last_modified_utc: "20260406064800"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.95-changelog"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "changelog"
  artifact_kind: "version"
  purpose: "Changelog for Lupopedia 4.0.95 — routing without mod_rewrite, query-string URLs, login.php surface"
  tags: ["changelog", "version", "4.0.95", "cursor"]
lupopedia.footer:
  last_verified: "20260406064800"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.95/CHANGELOG.md — delegation: cursor:root

# Changelog - Lupopedia 4.0.95

## [4.0.95] - 2026-04-06

### Version bump (runtime + docs)

- **`lupo-config/global_atoms.yaml`** — `version`, **`GLOBAL_CURRENT_LUPOPEDIA_VERSION`**: **4.0.95**
- **`lupo-includes/version.php`**, **`version.txt`**, **`lupo-docs/doctrine/VERSIONING_DOCTRINE.md`** (canonical current version §1), root **`README.md`** / **`CHANGELOG.md`** pointers — aligned to **4.0.95**
- **`lupo-rules/root/php-7-4-compatibility.md`** — rule stamp **4.0.95**

### Prior work carried into this line (from late 4.0.94 development)

- Install wizard: **mysqli**-backed **`InstallWizardMysqliLink`** for installer DB (WordPress-style buffering; avoids PDO MySQL **2014**), buffered PDO only for **`PDO_DB`** activation paths
- **`lupopedia-config.php` generator** — fixed missing **`}`** after **`LUPOPEDIA_PATH`** define (parse error on first load after install)

### Routing and URLs: no Apache mod_rewrite front controller

- **Root `.htaccess`** — Removed **`mod_rewrite`** rules that mapped pretty paths to **`index.php`** (catch-all slug, doctrine/qa/docs/flp funnel, channel/chat shortcuts). Kept **`DirectoryIndex index.php`**, **`Options -Indexes`**, security headers, and **`FilesMatch`** deny rules. Operators rely on explicit entry points: **`index.php?slug=…`**, **`index.php?resolved_uri=…`**, **`login.php`**, **`admin.php`**, **`channel.php`**, etc.
- **`lupo-includes/.htaccess`** — Denies direct HTTP access to **`*.php`** under **`lupo-includes/`** (replaces the old Rewrite-based block).
- **`lupo-install/InstallWizardHtaccessWriter::getRootHtaccessBody()`** — Aligned with the new no-rewrite root policy for installs that regenerate **`.htaccess`**.

### URL helpers (`lupo-includes/functions/auth-helpers.php`)

- **`lupo_index_slug_url($slug, $extra_query)`** — Builds **`…/index.php?slug=…`** (optional extra GET params).
- **`lupo_index_resolved_uri_url($uri)`** — Builds **`…/index.php?resolved_uri=…`** for doctrine/qa/docs/flp-style paths.
- **`lupo_login_url($redirect_uri)`** — **`…/login.php`** with optional **`redirect`** query parameter.
- **`lupo_change_password_url()`** — **`index.php?slug=change-password`** (slug-routed UI).

### Auth and UI surfaces

- **`require_login()`** and scattered redirects now target **`login.php`** (not **`/login`**).
- **`auth-controller`** — Slug **`login`** redirects to **`lupo_login_url()`**; auth renderer and **OAuth** redirects use **`login.php`**.
- **`login.php`** — Password-change redirects use a **`$cpw_url`** variable (avoids nested-ternary parse issues); **`channel.php`** loads **`auth-helpers`** and uses **`lupo_login_url()`** for gated access.
- **Layouts / chrome** — **`header.php`**, **`topbar.php`**, **`auth-ui-helpers.php`**, **`admin_layout.php`**, **`basic_layout.php`**: sign-in links use **`lupo_login_url()`** where available.
- **`actors-controller`** — Login redirects and **`/my-profile`** redirects use **`lupo_index_slug_url('my-profile')`** + **`lupo_login_url()`**.
- **`lupo-database/.../auth/AuthService.php`** — **`requireLogin()`** / password-change redirects use helpers or equivalent **`index.php`** query URLs.
- **`AGENTS.md`** — Documents **`login.php`** / **`lupo_login_url()`** instead of a pretty **`/login`** path.

### Channels

- **`channels-controller`** — All login redirects and **`Location`** headers use **`lupo_login_url()`** and **`lupo_index_slug_url()`** for channel paths (**`channels/{id}`**, **`…/log`**, **`…/edit`**, **`channels/my-channels`**, etc.).
- **Views** — **`show.php`**, **`my-channels.php`**, **`channel-log.php`**: links and forms use **`lupo_index_slug_url()`** (with **`auth-helpers`** fallback include where needed).
- **`module-loader.php`** — Registers slug routes (before the generic numeric channel route) for **`channels/{id}/edit/save`** (POST), **`channels/{id}/log/create`** (POST), **`channels/{id}/log`**, **`channels/{id}/edit`**.
- **`main_layout.php`** — Hides semantic collections chrome when **`REQUEST_URI`** contains **`/channels/`** or **`?slug=`** starts with **`channels/`** (works with query-string routing).

### OAuth

- **`oauth-controller.php`** — Registered OAuth **redirect URI** uses **`index.php?slug=oauth/callback/{provider}`** (must match provider console settings).
- **`lupo-database/.../views/auth/login.php`** — Google/GitHub buttons use slug URLs.
- **`lupo-database/.../Controllers/OAuthController.php`** — **`getRedirectUri()`** returns slug form.
- **`lupo-config/oauth.example.php`** — Comments show **`index.php?slug=…`** callback examples.
- Post-OAuth redirect to **`admin.php`** (was **`/admin`**, which no longer resolves without rewrite).

### Channel admin samples

- **`lupo-database/lupopedia/channels/channel_id/1/admin/settings.php`** — System links use **`index.php?slug=…`** and **`login.php`**; docs entry uses **`resolved_uri=docs`**.
- **`admin_bootstrap.php`** — Login redirect uses **`lupo_login_url()`** + **`lupo_index_slug_url('channels/1')`**.

### Other

- **`index.php`** — Debug copy when slug is empty points to **`?slug=`** / **`?resolved_uri=`** instead of “check .htaccess rewrite”.

This output complies with Lupopedia Constitutional Root Rules.
