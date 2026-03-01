# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\channels\doctrine\WEB_ROUTING_DOCTRINE_4_0_18.md"
  file_hash: "b0f5f7fc0e6d23cd644ed5cc591e2ec6cd3579f85805d5587edd76943047d6d6"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\doctrine\WEB_ROUTING_DOCTRINE_4_0_18.md"
  file_hash: "b8410769a316d7307788f61c8292058982ecc1e408f089780cc114969813e84e"
  file_path_from_root: "docs\channels\doctrine\WEB_ROUTING_DOCTRINE_4_0_18.md"
  file_hash: "629480bf11fe9881a99cbbce1b11663a89eefcf09a46d8fd0a864fef86c9a0bd"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for WEB_ROUTING_DOCTRINE_4_0_18.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "web_routing_doctrine_4_0_18md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
# FLIP Header
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/channels/doctrine/WEB_ROUTING_DOCTRINE_4_0_18.md
file.last_modified_system_version: "4.0.17"
file.last_modified_utc: "20260218000000"
# channel_id: 51 (Doctrine Council)
---
# Web Routing Doctrine — 4.0.18 (Planned)

**Status:** Planning only. No implementation in 4.0.17.  
**Audience:** Contributors and agents.  
**Purpose:** Define the 4.0.18 scope for runtime web path resolution and routing, building on the 4.0.17 Web Path Header Extension.

---

## 1. Context

4.0.17 added the **Web Path Header Extension**: optional `web` block in FLIP headers (canonical, aliases, slug, slug_encoding, base_path, url_pattern). Metadata is exported in `exports/flip_headers.csv` and defined in `docs/channels/doctrine/UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md` §6.1. No runtime routing was implemented in 4.0.17.

4.0.18 will implement **runtime routing** and **web path resolution** based on this metadata.

---

## 2. Planned Scope (4.0.18)

### 2.1 UrlResolver (runtime component)

- **Load web metadata** using a three-tier source hierarchy: (1) **Ideal:** database — query `lupo_contents` or related tables by `content_id` for web metadata (e.g. stored or derivable from FLIP web block); (2) **Fallback 1:** `exports/flip_headers.csv` (from `flip_header_audit.py`); (3) **Fallback 2:** direct parsing of `.md` FLIP headers from the filesystem. Log a warning when using either fallback.
- **Normalize** slugs and encodings (underscore, plus, percent) per FLIP web block.
- **Resolve** canonical vs alias paths: given a request path, determine the canonical content and file path.
- **Redirect vs serve:** Optional behavior for alias paths (e.g. 302 to canonical or serve same content).
- **Multi-segment base paths:** Support when doctrine allows (e.g. `/{base}/{slug}` with multi-segment base).

### 2.2 PHP router integration

- **Wildcard route(s)** for `/{base}/{slug}` (or equivalent pattern) that invoke the resolver.
- **Smart 404:** When no match, optionally suggest “Did you mean…” using slug similarity (auth-aware; no leakage of private paths).

### 2.3 Server-side rewrite rules

- **Apache / Nginx** rules that funnel relevant requests to the PHP router (e.g. `/doctrine/…`, `/docs/…`) so that one entry point handles path resolution.

### 2.4 Caching and invalidation

- **Cache** resolver output (path → content_id / file path) with a defined TTL or invalidation trigger.
- **Invalidate** on `flip_headers.csv` change or when the FLIP header generator / installer runs.

### 2.5 Testing plan

- **Unit:** Resolver returns correct canonical path and content_id for known paths and aliases; slug normalization (underscore, plus, percent) matches spec.
- **Integration:** Wildcard route serves or redirects for CSV-backed paths; 404 for unknown paths; smart 404 suggestions (auth-aware) when enabled.
- **Regression:** Fresh install and seed; run flip_header_audit.py or update CSV; confirm resolver/cache sees new rows after invalidation.

---

## 3. Out of Scope for 4.0.17

- No UrlResolver implementation.
- No new PHP routes for web path resolution.
- No server rewrite rules.
- No caching layer for resolver.

4.0.17 is documentation, metadata, seeding, and governance only.

---

## 4. Implementation notes (Boundary-Keeper)

The following are canonical for 4.0.18 implementation. Read this section before coding.

### 4.1 Version bump (T1)

Update **all** locations per `docs/doctrine/VERSIONING_DOCTRINE.md`. After bump, add a **4.0.18 (in progress)** section to CHANGELOG.md and move the current "Planned" content from the 4.0.17 entry into that section.

### 4.2 UrlResolver (T2)

- **Source hierarchy (3 tiers):** (1) **Ideal:** database — query `lupo_contents` (or related tables) by `content_id` for web metadata (parse YAML/web block if stored as text); (2) **Fallback 1:** load `exports/flip_headers.csv` (e.g. via fgetcsv or equivalent); (3) **Fallback 2:** parse `.md` FLIP headers directly from the filesystem. **Log a warning** whenever a fallback is used (DB miss/fail, or CSV missing).
- **Normalization:** Handle `_`, `+`, `%20`, and mixed case per the `slug_encoding` field in the web block.
- **Alias resolution:** If the request path matches an alias, decide **redirect vs serve** from config or per-file setting (to be defined in this doctrine or a config atom).

### 4.3 Smart 404 (T4)

- **Auth-aware:** Do not suggest private paths to unauthenticated users.
- **Similarity:** Use Levenshtein distance on normalized slugs.
- **Scope:** Limit suggestions to a reasonable subset (e.g. last 100 accessed, or index by first character) to avoid cost and information leakage.

### 4.4 Ban at Gate (T7 — optional)

Implement **only when explicitly requested**.

- Check against `lupo_banned_actors` (or a cached list) in the router.
- Return **403** with a reference to `docs/doctrine/SESSION_DOCTRINE.md`.
- Log the event to **lupo_ban_log** as previously specified (see SESSION_DOCTRINE and schema).

### 4.5 Caching (T6)

- **Cache key:** Normalized request path.
- **Cache value:** `array('content_id' => X, 'file_path' => Y, 'canonical' => Z)` (or equivalent structure).
- **Invalidate on:**
  - `flip_headers.csv` change (e.g. `filemtime` or file hash)
  - After `flip_header_audit.py` run
  - After installer/seed update
- **TTL:** Default 1 hour; configurable (e.g. via config/global_atoms or a routing config).

### 4.6 Testing (T8)

- **Unit:** Resolver with mocked CSV; slug normalization edge cases (underscore, plus, percent, case).
- **Integration:** Wildcard route; alias redirect vs serve; smart 404 suggestions (auth-aware).
- **Regression:** Fresh install + seed; CSV update; cache invalidation observed.

### 4.7 Recommended task order

1. **T1** — Version bump (immediate; no routing code).
2. **T2** — UrlResolver (core component).
3. **T5** — Server rewrite rules (Apache/Nginx; minimal).
4. **T3** — PHP router wildcard (integrates resolver).
5. **T6** — Caching (performance).
6. **T4** — Smart 404 (UX enhancement).
7. **T8** — Testing (iterative; can start earlier).
8. **T7** — Ban at Gate (optional; only if requested).

### 4.8 Environment (localhost testing)

- **4.0.18 testing is on localhost only** — no external web exposure during development.
- Use **relative paths** and **local config** (e.g. `http://localhost/` or project base path); avoid hard-coded production URLs or external dependencies.
- Resolver, rewrites, and cache should work with a local vhost (e.g. `http://localhost/lupopedia/`). Optional: verbose or debug logging when `HTTP_HOST` indicates localhost.

---

*End of Web Routing Doctrine 4.0.18 (planned).*