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

- **Load web metadata** from `flip_headers.csv` with fallback to FLIP header parsing when CSV is missing or stale.
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

*End of Web Routing Doctrine 4.0.18 (planned).*
