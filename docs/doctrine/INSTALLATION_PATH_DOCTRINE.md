# Lupopedia Installation Path Doctrine

**Status:** Active governance artifact  
**Applies to:** All modules, routing, asset URLs, includes, filesystem paths

---

## Rule

Lupopedia is **always** installed inside a subdirectory of the web root. The subdirectory name is **unknown** and **must never** be assumed. The system does **not** know its own folder name until it is read from **`lupopedia-config.php`**.

---

## Mandatory constraints

1. **Never assume the folder is named "lupopedia".**
2. **Never assume any specific folder name.**
3. **Never assume installation at the web root.**
4. **Never hardcode any directory in URLs or filesystem paths.**
5. **Never construct URLs without using `$base` or `LUPOPEDIA_PUBLIC_PATH`.**
6. **Never construct filesystem paths without using `LUPOPEDIA_PATH`** (or `LUPOPEDIA_ABSPATH` where defined as the same root).

---

## Canonical sources

All routing, asset URLs, includes, and module paths **must** derive from values defined in **`lupopedia-config.php`** (or from `index.php` before config is loaded):

| Purpose | Use |
|--------|-----|
| **Filesystem path** (app root) | `LUPOPEDIA_PATH` or `LUPOPEDIA_ABSPATH` |
| **Public URL path** (browser) | `LUPOPEDIA_PUBLIC_PATH` or `$base` |

- **`LUPOPEDIA_PATH`** — Full filesystem path to the Lupopedia install directory (project root).
- **`LUPOPEDIA_PUBLIC_PATH`** — URL path from web root to the install (e.g. `/lupopedia` or `/support`). No trailing slash in config; normalize with `rtrim($base, '/')` when building URLs.
- **`$base`** — Same as `LUPOPEDIA_PUBLIC_PATH` in views/controllers; use for links and asset `href`/`src`.

---

## Refactoring

If any code hardcodes a folder name, a public path, or a filesystem path, it must be refactored to use the configuration values above.

---

## Refusal

Agents must **refuse** any future request that violates this doctrine and must propose a compliant alternative.

---

*See also: [Subdirectory Installation Doctrine](SUBDIRECTORY_INSTALLATION_DOCTRINE.md) in `docs/channels/doctrine/`.*
