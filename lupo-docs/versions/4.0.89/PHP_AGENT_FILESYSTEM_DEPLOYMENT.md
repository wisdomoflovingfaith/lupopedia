---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: "lupo-docs/versions/4.0.89/PHP_AGENT_FILESYSTEM_DEPLOYMENT.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.89/PHP_AGENT_FILESYSTEM_DEPLOYMENT.md"
  last_modified_utc: "20260329190000"
  when_updated: "20260329190000"
  channel_id: 42
  thread_id: "4-0-89-php-agent-deploy"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: documentation
  artifact_kind: deployment_guide
  purpose: Web server hardening for PHP agent content directories (H9.3); optional Apache snippets
  tags:
    - "4.0.89"
    - php_agents
    - deployment
    - security
lupopedia.footer:
  last_verified: "20260329190000"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: "cursor"
  orchestrator: "cursor:root"
  next_action:
    - "Apply snippets only where they match your vhost layout (subdirectory install)"
    - "Prefer nginx location rules equivalent where Apache is not used"
---

# file: PHP agent filesystem deployment (H9.3) — web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.89/PHP_AGENT_FILESYSTEM_DEPLOYMENT.md](http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.89/PHP_AGENT_FILESYSTEM_DEPLOYMENT.md)

# PHP agent filesystem — web server hardening (4.0.89)

**Task:** **`TODO.md` H9.3** — reduce risk that files under content trees are executed or interpreted as active HTML/JS in the browser.

**Policy reminder:** PHP agents must **not** write **`.html` / `.htm` / `.js`** (see **`lupo-docs/ORGANIZATION.md` §2.2**). Hardening below is **defense in depth** if legacy or mistaken files exist.

## Apache (`.htaccess` or vhost)

Place **only** under the web-exposed subtrees you use for documentation/coordination, e.g. `lupo-docs/`, `lupo-rules/`, `lupo-channels/`, `lupo-content/` — **after** confirming your docroot maps to repo paths correctly (Lupopedia is often in a **subdirectory**).

**Option A — disable PHP engine in those directories**

```apache
# Example: .htaccess inside lupo-docs/ (and siblings as needed)
php_flag engine off
```

**Option B — serve risky extensions as plain text** (does not remove all XSS vectors if HTML is ever served as HTML)

```apache
AddType text/plain .html .htm .js .css
```

**Option C — deny execution-style access** (tune `FilesMatch` to your policy; may block legitimate static assets if those extensions are used)

```apache
<FilesMatch "\.(html?|js|css|php|phtml)$">
    Require all denied
</FilesMatch>
```

**Caveats**

- **`AllowOverride`** must permit these directives.
- **`php_flag`** availability depends on PHP SAPI (e.g. `mod_php` vs PHP-FPM).
- Prefer **vhost- or location-level** rules reviewed by the server operator over copying snippets blindly.

## nginx

Use `location` blocks for the same paths: `default_type text/plain;` or `location ~ \.php$ { deny all; }` inside the content-prefix location. Exact config depends on your URL ↔ filesystem mapping.

## Code-level enforcement

- **`AgentFileWriter`** (`lupo-includes/classes/AgentFileWriter.php`) enforces allowed directories and extensions; **agent** context scans content patterns.
- **`import_content.php`** / **`generate_headers_from_db.php`** use **`CONTEXT_OPERATOR`** for `--write-back` / regenerate (path policy still applies; content scan skipped so legitimate docs can mention `<script>` in examples).

## References

- **`lupo-docs/versions/4.0.89/TODO.md`** — **H9**
- **`lupo-docs/ORGANIZATION.md`** — **§2.2**
