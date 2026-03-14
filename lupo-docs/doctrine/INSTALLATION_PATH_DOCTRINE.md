# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\INSTALLATION_PATH_DOCTRINE.md"
  file_hash: "b47ea55c821216487813e75cbb17151818d6724b168bea3afe82df262d74ebf9"
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
  file_path_from_root: "lupo-docs\doctrine\INSTALLATION_PATH_DOCTRINE.md"
  file_hash: "115db056ec91a2fa2923479a92866e9b3dfce572fd42278fa91b667d766c1182"
  file_path_from_root: "lupo-docs\doctrine\INSTALLATION_PATH_DOCTRINE.md"
  file_hash: "2670d817735c1e13915f7dbfddf074294cf0d058b6dca862dececfda640974e6"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for INSTALLATION_PATH_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "installation_path_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: lupo-docs/doctrine/INSTALLATION_PATH_DOCTRINE.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260217232500"
channel_id: 0   # System Kernel channel – foundational doctrine
tags: ["installation", "path", "doctrine", "flip"]
mood_rgb: "B0E0E6"
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: lupo-docs/doctrine/INSTALLATION_PATH_DOCTRINE.md
---

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
7. **There is no `/public` folder.** The project root is the document root for all generated files; all public-facing PHP endpoints (index.php, livehelp_js.php, api files, etc.) live directly in the project root. Never generate or reference `/public`, `/public_html`, or `/www`.

---

## Canonical sources

All routing, asset URLs, includes, and module paths **must** derive from values defined in **`lupopedia-config.php`** (or from `index.php` before config is loaded):

| Purpose | Use |
|--------|-----|
| **Filesystem path** (app root) | `LUPOPEDIA_PATH` or `LUPOPEDIA_ABSPATH` |
| **Public URL path** (browser) | `LUPOPEDIA_PUBLIC_PATH` or `$base` |

- **`LUPOPEDIA_PATH`** — Full filesystem path to the Lupopedia install directory (project root).
- **`LUPOPEDIA_PUBLIC_PATH`** — URL path from web root to the install (e.g. `/lupopedia` or `/support`). No trailing slash in config; normalize with `rtrim($base, '/')` when building URLs.
- **`$base`** — Same as `LUPOPEDIA_PUBLIC_PATH` in lupo-views/controllers; use for links and asset `href`/`src`.

---

## Refactoring

If any code hardcodes a folder name, a public path, or a filesystem path, it must be refactored to use the configuration values above.

---

## Refusal

Agents must **refuse** any future request that violates this doctrine and must propose a compliant alternative.

---

*See also: [Subdirectory Installation Doctrine](SUBDIRECTORY_INSTALLATION_DOCTRINE.md) in `lupo-docs/channels/doctrine/`.*
