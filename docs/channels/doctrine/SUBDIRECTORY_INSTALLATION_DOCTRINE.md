---
wolfie.header.identity: SUBDIRECTORY_INSTALLATION_DOCTRINE
wolfie.header.placement: /docs/doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md
wolfie.header.version: 4.0.18
channel_key: system/kernel
wolfie.header.dialog:
  speaker: KIRO
  target: @everyone
  message: "Phase 3 documentation consistency audit corrections: Added host website clarification section explaining embedded vs standalone installations and non-interference principle."
  mood: "00FF00"
tags:
  categories: ["documentation", "installation", "architecture"]
  collections: ["core-docs"]
  channels: ["public", "dev"]
file:
  title: "Subdirectory Installation Doctrine"
  description: "Canonical rules for Lupopedia filesystem and URL path resolution"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Lupopedia 4.0.18 â€” Canonical Rules for Filesystem and URL Path Resolution

## 1. Purpose

Lupopedia is a portable, nonâ€‘intrusive, dropâ€‘in application designed to run inside any subdirectory of a host website.

It must never assume:

- the web root
- a fixed folder name
- a specific server layout
- rewrite rules
- a particular hosting environment

This doctrine defines the canonical rules for determining:

- the filesystem path (`LUPOPEDIA_PATH`)
- the public URL path (`LUPOPEDIA_PUBLIC_PATH`)

These constants are foundational to routing, asset loading, module linking, and all internal navigation.

---

## 1.1. Host Website Clarification

### What is a "Host Website"?

The **host website** is the existing web environment that contains the Lupopedia installation. The host website exists in the parent directory structure above `/lupopedia/`.

### Two Installation Scenarios

**Embedded Installation:**
- Lupopedia is installed alongside an existing website
- The host website may include a CMS (WordPress, Drupal, etc.), static HTML pages, or custom applications
- Lupopedia operates as a semantic reference layer without interfering with the host site
- Example: `example.com` (WordPress) with Lupopedia at `example.com/lupopedia/`

**Standalone Installation:**
- Lupopedia is the only system on the domain
- There is no host website above `/lupopedia/`
- Lupopedia operates independently
- Example: A domain where `/lupopedia/` is installed directly under the document root

### Critical Principle

**Lupopedia must NEVER interfere with the host website, regardless of scenario.**

This means:
- âœ… Lupopedia operates ONLY within `/lupopedia/` directory
- âœ… All Lupopedia routes live under `/lupopedia/` URL path
- âœ… Lupopedia does not modify host site files or routing
- âœ… Lupopedia does not assume anything about parent directory structure
- âŒ Lupopedia must NOT override host site `.htaccess` rules
- âŒ Lupopedia must NOT modify host site URLs or paths
- âŒ Lupopedia must NOT access or modify files outside `/lupopedia/`

**In standalone installations:** The "host website" is effectively empty, but the same isolation rules apply.

**See:** [Lupopedia-Reference-Layer-Doctrine.md](Lupopedia-Reference-Layer-Doctrine.md), [GLOSSARY.md](../appendix/appendix/GLOSSARY.md)

---

## 2. LUPOPEDIA_PATH (Filesystem Path Doctrine)

### Definition

```php
define('LUPOPEDIA_PATH', __DIR__);
```

### Meaning

`LUPOPEDIA_PATH` is the absolute filesystem directory where Lupopedia is installed.

### Properties

- Always uses `__DIR__`
- Never depends on server configuration
- Never depends on URL structure
- Never changes based on installation location
- Always points to the physical folder containing Lupopedia's code

### Examples

| Installation Location | LUPOPEDIA_PATH |
|----------------------|----------------|
| `/var/www/html/lupopedia` | `/var/www/html/lupopedia` |
| `/var/www/html/programms/lupopedia` | `/var/www/html/programms/lupopedia` |
| `/home/user/public_html/projects/lupopedia` | `/home/user/public_html/projects/lupopedia` |

### Rules

- **MUST** be defined before loading any modules
- **MUST** be used for all filesystem includes, loads, scans, and TOON file resolution
- **MUST NOT** be used for URLs, links, or redirects

---

## 3. LUPOPEDIA_PUBLIC_PATH (URL Path Doctrine)

### Definition

```php
define(
    'LUPOPEDIA_PUBLIC_PATH',
    rtrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__), '/')
);
```

**This is the canonical implementation.**

### Meaning

`LUPOPEDIA_PUBLIC_PATH` is the URL path prefix required to access Lupopedia from a browser.

It is derived by subtracting the server's document root from the filesystem path.

### Why This Is Canonical

This implementation:
- **Works for any installation depth** â€” handles nested subdirectories correctly
- **Requires no configuration** â€” automatically detects the correct path
- **Portable across environments** â€” works on any server configuration
- **No assumptions** â€” doesn't assume Lupopedia is directly under document root

**Alternative (Simplified) Implementation:**

Some installations may use a simplified version for performance:

```php
define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(__DIR__));
```

This works when Lupopedia is installed directly under the document root (e.g., `/var/www/html/lupopedia`), but fails for nested installations (e.g., `/var/www/html/programs/lupopedia`).

**Use the canonical implementation unless you have a specific reason to use the simplified version.**

### Properties

- Reflects the actual URL subdirectory
- Works for any installation depth
- Requires no rewrite rules
- Requires no configuration from the host site
- Ensures all links resolve correctly

### Examples

| Filesystem Path | Document Root | LUPOPEDIA_PUBLIC_PATH |
|----------------|---------------|----------------------|
| `/var/www/html/lupopedia` | `/var/www/html` | `/lupopedia` |
| `/var/www/html/programms/lupopedia` | `/var/www/html` | `/programms/lupopedia` |
| `/home/user/public_html/lupopedia` | `/home/user/public_html` | `/lupopedia` |

### Rules

**Cursor MUST:**

- **ALWAYS** prefix internal links with `LUPOPEDIA_PUBLIC_PATH`
- **NEVER** assume Lupopedia is installed at `/`
- **NEVER** hardcode `/lupopedia`
- **NEVER** generate rootâ€‘relative URLs like `/admin` or `/login`
- **ALWAYS** generate:

```php
LUPOPEDIA_PUBLIC_PATH . '/admin'
LUPOPEDIA_PUBLIC_PATH . '/login'
LUPOPEDIA_PUBLIC_PATH . '/assets/css/...'
```

### Usage Requirements

`LUPOPEDIA_PUBLIC_PATH` **MUST** be used for:

- `<a href="...">`
- `<form action="...">`
- `<script src="...">`
- `<link rel="stylesheet" href="...">`
- redirects (`header("Location: ...")`)
- router paths
- module links
- admin links
- login/logout links
- AJAX endpoints
- REST endpoints

---

## 4. Antiâ€‘Patterns (Forbidden Behaviors)

**Cursor MUST NOT:**

- assume Lupopedia is installed at `/`
- assume Lupopedia is installed at `/lupopedia`
- generate absolute URLs like `/admin`
- generate asset paths like `/css/style.css`
- hardcode folder names
- use relative paths like `../` for public URLs
- mix filesystem paths and URL paths

These mistakes break portability and violate doctrine.

---

## 5. Test Cases (Cursor MUST pass these)

### Case A â€” Installed at `/lupopedia`

- `/lupopedia/admin` must work
- `/admin` must **NOT** be generated

### Case B â€” Installed at `/programms/lupopedia`

- `/programms/lupopedia/login` must work
- `/lupopedia/login` must **NOT** be generated

### Case C â€” Installed at `/foo/bar/baz/lupopedia`

- `/foo/bar/baz/lupopedia/assets/js/app.js` must load
- `/assets/js/app.js` must **NOT** be generated

---

## 6. Implementation Examples

### Example: Login Form

```php
function login_form() {
    $login_action = defined('LUPOPEDIA_PUBLIC_PATH') 
        ? LUPOPEDIA_PUBLIC_PATH . '/login' 
        : '/login';
    
    return '<form method="POST" action="' . htmlspecialchars($login_action) . '">
        <!-- form fields -->
    </form>';
}
```

### Example: Redirect Helper

```php
function lupo_safe_redirect($url, $delay = 3, $message = null) {
    // Ensure URL includes LUPOPEDIA_PUBLIC_PATH if not already present
    if (strpos($url, 'http') !== 0) {
        if (defined('LUPOPEDIA_PUBLIC_PATH') && LUPOPEDIA_PUBLIC_PATH !== '/') {
            if (strpos($url, LUPOPEDIA_PUBLIC_PATH) !== 0) {
                if (strpos($url, '/') === 0) {
                    $url = LUPOPEDIA_PUBLIC_PATH . $url;
                } else {
                    $url = LUPOPEDIA_PUBLIC_PATH . '/' . ltrim($url, '/');
                }
            }
        }
    }
    
    // ... redirect logic
}
```

### Example: Navigation Links

```php
function render_navigation() {
    $base_path = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
    
    return '
        <a href="' . htmlspecialchars($base_path . '/admin') . '">Admin</a>
        <a href="' . htmlspecialchars($base_path . '/logout') . '">Logout</a>
    ';
}
```

### Example: Asset Loading

```php
// âœ… CORRECT
<link rel="stylesheet" href="<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/lupo-includes/css/main.css">
<script src="<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/lupo-includes/js/lupopedia.js"></script>

// âŒ WRONG
<link rel="stylesheet" href="/lupo-includes/css/main.css">
<script src="/lupo-includes/js/lupopedia.js"></script>
```

---

## 7. AI Agent Requirements

**Cursor and all AI agents MUST:**

1. âœ… **ALWAYS** use `LUPOPEDIA_PUBLIC_PATH` for all internal paths
2. âœ… **NEVER** assume Lupopedia is installed at `/`
3. âœ… **NEVER** generate hardcoded root paths like `/login` or `/admin`
4. âœ… **ALWAYS** check if `LUPOPEDIA_PUBLIC_PATH` is defined before using it
5. âœ… **ALWAYS** provide fallback to `/` if constant is not defined (for backward compatibility)

### Code Review Checklist

When reviewing code, check:

- [ ] All `<a href>` use `LUPOPEDIA_PUBLIC_PATH`
- [ ] All `<form action>` use `LUPOPEDIA_PUBLIC_PATH`
- [ ] All `<link>` and `<script>` use `LUPOPEDIA_PUBLIC_PATH`
- [ ] All `header('Location:')` use `LUPOPEDIA_PUBLIC_PATH`
- [ ] All redirect functions use `LUPOPEDIA_PUBLIC_PATH`
- [ ] All router paths use `LUPOPEDIA_PUBLIC_PATH`
- [ ] No hardcoded `/` paths exist (except fallbacks)
- [ ] Filesystem operations use `LUPOPEDIA_PATH` (not `LUPOPEDIA_PUBLIC_PATH`)

---

## 8. Why This Matters

### Portability

Lupopedia can be installed in **any** subdirectory without code changes.

### Embedding

Lupopedia can be embedded inside **any** website without modifying:

- Host site's structure
- Host site's `.htaccess`
- Host site's routing

### Multi-Instance Support

Multiple Lupopedia instances can coexist on the same server:

- `/lupopedia-instance-1`
- `/lupopedia-instance-2`
- `/apps/lupopedia`

Each instance automatically uses its own public path.

---

## 9. Doctrine Summary

### Filesystem Path

```php
LUPOPEDIA_PATH = __DIR__
```

### Public URL Path

```php
LUPOPEDIA_PUBLIC_PATH = URL path derived from filesystem path
```

### Core Principle

**Lupopedia is portable and must function correctly in ANY subdirectory.**

---

## 10. Summary (Non-Negotiable)

- âœ… **ALWAYS** use `LUPOPEDIA_PUBLIC_PATH` for internal paths
- âœ… **ALWAYS** use `LUPOPEDIA_PATH` for filesystem operations
- âœ… **ALWAYS** check if constants are defined before using
- âœ… **ALWAYS** provide fallback to `/` for backward compatibility
- âŒ **NEVER** assume Lupopedia is at web root
- âŒ **NEVER** hardcode root paths like `/login` or `/admin`
- âŒ **NEVER** use relative paths without public path prefix
- âŒ **NEVER** mix filesystem paths and URL paths

This doctrine is **absolute and binding** for all Lupopedia code.

---

## 11. Related Documentation

- [URL_ROUTING_DOCTRINE.md](URL_ROUTING_DOCTRINE.md) - How Lupopedia handles HTTP URL routing
- [AGENT_ROUTING_DOCTRINE.md](AGENT_ROUTING_DOCTRINE.md) - How Lupopedia routes messages to AI agents
- [Lupopedia-Reference-Layer-Doctrine.md](Lupopedia-Reference-Layer-Doctrine.md) - Reference layer principles
- [GLOSSARY.md](../appendix/appendix/GLOSSARY.md) - Terminology definitions including "host website"
- [MODULE_SYSTEM.md](MODULE_SYSTEM.md) - How modules use public paths
- [Installation Guide](../INSTALLATION.md) - Installation instructions
- [Timestamp Doctrine](TIMESTAMP_DOCTRINE.md) - Time handling rules