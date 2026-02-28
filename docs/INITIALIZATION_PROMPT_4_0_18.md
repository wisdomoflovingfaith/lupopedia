# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\INITIALIZATION_PROMPT_4_0_18.md"
  file_hash: "1770392bc4d4428cd0b8b0944363349afa1315815ea0a3fd5e450a0147b7e9c4"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for INITIALIZATION_PROMPT_4_0_18.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "initialization_prompt_4_0_18md"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
# FLIP Header
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/INITIALIZATION_PROMPT_4_0_18.md
file.last_modified_system_version: "4.0.17"
file.last_modified_utc: "20260218000000"
# channel_id: 51 (Doctrine Council)
---
# Initialization Prompt for New Cursor Thread — Lupopedia 4.0.18

**Purpose:** Paste the content below (from "---" to "END OF PROMPT") into a **new** Cursor thread to begin development on Lupopedia 4.0.18. This prompt does NOT perform any version bump or implement any code; it equips the next thread with doctrine, scope, and the first actionable tasks.

---

## Paste from here into new Cursor thread

---

You are starting development on **Lupopedia version 4.0.18**. This is an initialization prompt only. Do not modify any files until you receive explicit instructions.

**Date context:** Today's date is 2026-02-19. Use this for version bumps, CHANGELOG entries, and any date-sensitive operations unless instructed otherwise.

---

### 1. CONTEXT FROM 4.0.17 (FINAL)

4.0.17 is **complete and pushed**. It delivered:

- **Web Path Header Extension (metadata only):** Optional `web:` block in FLIP headers (canonical, aliases, slug, slug_encoding, base_path, url_pattern). Implemented in `docs/channels/doctrine/UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md` §6.1, `exports/flip_headers.csv` (web_* columns + type row), `scripts/flip_header_audit.py` (path_to_web + web block), and a comment in `seed_lupopedia.sql`. **No database schema changes.** Web path metadata is exported, not stored in `lupo_contents`.
- **Provenance verified:** 38 files at 4.0.16; NOTE_HEADER_VERSION_AND_MERGE.md and 4.0.17_DIAGNOSTICS_AND_COMPATIBILITY.md at 4.0.17.
- **Doctrine and seed:** SESSION_DOCTRINE.md, VERSIONING_DOCTRINE.md, NOTE_HEADER_VERSION_AND_MERGE.md, 4.0.17_DIAGNOSTICS_AND_COMPATIBILITY.md; content_id 5038, dialog message 63, channel 51 message_count = 2.

**Nothing in this prompt repeats 4.0.17 work.** All 4.0.17 tasks are done.

---

### 2. 4.0.18 SCOPE (AUTHORITATIVE SOURCES)

4.0.18 implements **runtime web path resolution and routing** and **Ban at Gate** (router-level persona ban enforcement). Scope is defined in:

- **docs/channels/doctrine/ROADMAP_4_0_18.md** — No deferred items from 4.0.17; 4.0.18 scope = runtime web path resolution, UrlResolver, PHP router integration, server rewrites, caching, Ban at Gate.
- **docs/channels/doctrine/WEB_ROUTING_DOCTRINE_4_0_18.md** — Full planning: UrlResolver (§2.1), PHP router wildcard (§2.2), server rewrite rules (§2.3), caching and invalidation (§2.4), testing plan (§2.5). **§4** contains Boundary-Keeper implementation notes (source of truth, cache shape, invalidation, Smart 404, Ban at Gate, recommended task order).

**You MUST read both files before implementing any 4.0.18 feature.** Do not add 4.0.19 or later scope.

**Environment:** Testing for 4.0.18 is on **localhost only** — no external web exposure. Use relative paths, local config (e.g. `http://localhost/`), and avoid hard external dependencies. Resolver, rewrites, and cache must work with a local vhost (e.g. `http://localhost/lupopedia/`). See WEB_ROUTING_DOCTRINE_4_0_18.md §4.8.

---

### 3. VERSION BUMP (4.0.17 → 4.0.18)

When explicitly instructed to bump to 4.0.18, update the version string in **all** locations listed in `docs/doctrine/VERSIONING_DOCTRINE.md` (canonical versioning checklist). Typical locations:

- config/global_atoms.yaml (version, versions.lupopedia, GLOBAL_CURRENT_LUPOPEDIA_VERSION, last_updated)
- lupo-includes/version.php (docblock, fallbacks, LUPOPEDIA_VERSION_DATE)
- install.php, lupo-includes/functions/load_atoms.php (fallbacks)
- install_wizard_classes.php (docblock)
- database/migrations/seed_lupopedia.sql (@lupo_version, @lupo_version_code)
- docs/doctrine/VERSIONING_DOCTRINE.md (canonical current version)

After bump, add a **4.0.18 (in progress)** section to CHANGELOG.md and move the current "Planned" content from the 4.0.17 entry into it.

Patch-only. No major/minor until auto-installer release cycle.

---

### 4. DOCTRINE TO LOAD BEFORE ANY 4.0.18 IMPLEMENTATION

Load and apply (in addition to existing project doctrine):

- **Web Routing Doctrine (4.0.18)** — docs/channels/doctrine/WEB_ROUTING_DOCTRINE_4_0_18.md. Defines UrlResolver behavior, router wildcard, alias/redirect, smart 404, rewrite rules, caching/invalidation, testing plan; **§4** = Boundary-Keeper implementation notes.
- **Roadmap 4.0.18** — docs/channels/doctrine/ROADMAP_4_0_18.md. Confirms no deferred 4.0.17 items; summarizes 4.0.18 scope.
- **Session Doctrine** — docs/doctrine/SESSION_DOCTRINE.md. Ban at Gate (router enforcement) is deferred to 4.0.18; implement only when instructed.
- **FLIP / Web Path** — docs/channels/doctrine/UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md §6.1; exports/flip_headers.csv structure (web_canonical, web_aliases, web_slug, web_slug_encoding, web_base_path, web_url_pattern).

Continue to obey: PHP 5.3 compatibility, PDO_DB only, reserved ID doctrine, no database-side logic (no FKs, triggers, DEFAULT CURRENT_TIMESTAMP), FLIP inference from headers only, TOON/install SQL as schema source of truth.

---

### 5. FIRST ACTIONABLE TASKS (4.0.18)

Execute **only** when explicitly instructed. Use the **recommended task order** below; do not skip to later items before earlier ones are verified. For implementation details (source of truth, cache shape, invalidation, Smart 404 auth/Levenshtein, Ban at Gate 403/logging), see **docs/channels/doctrine/WEB_ROUTING_DOCTRINE_4_0_18.md §4**.

**Recommended order:** T1 → T2 → T5 → T3 → T6 → T4 → T8 → T7 (T7 optional, only if requested).

| # | Task | Source | Notes |
|---|------|--------|--------|
| T1 | **Version bump 4.0.17 → 4.0.18** | VERSIONING_DOCTRINE.md §4.1 | Update all locations per checklist. Add CHANGELOG.md "4.0.18 (in progress)" section; move "Planned" content into it. |
| T2 | **UrlResolver (runtime component)** | WEB_ROUTING_DOCTRINE §2.1, §4.2 | Source hierarchy: (1) DB ideal — lupo_contents or related by content_id for web metadata; (2) Fallback: CSV; (3) Fallback: .md parse. Log warnings on fallbacks. Normalize per slug_encoding; resolve canonical/alias; redirect vs serve per config or per-file. |
| T5 | **Server rewrite rules** | WEB_ROUTING_DOCTRINE §2.3 | Apache/Nginx rules funnel e.g. /doctrine/…, /docs/… to PHP router. Minimal; before T3. |
| T3 | **PHP router wildcard** | WEB_ROUTING_DOCTRINE §2.2 | Wildcard `/{base}/{slug}` invoking resolver. No conflict with existing channels or admin routes. |
| T6 | **Caching and invalidation** | WEB_ROUTING_DOCTRINE §2.4, §4.5 | Key: normalized path. Value: content_id, file_path, canonical. Invalidate on CSV change, audit run, installer/seed. TTL default 1h (configurable). |
| T4 | **Smart 404** | WEB_ROUTING_DOCTRINE §2.2, §4.3 | "Did you mean…" via Levenshtein on normalized slugs; auth-aware (no private paths to unauthenticated); limit subset (e.g. last 100 or by first char). |
| T8 | **Testing** | WEB_ROUTING_DOCTRINE §2.5, §4.6 | Unit: resolver + slug normalization. Integration: wildcard, alias redirect/serve, smart 404. Regression: install/seed, CSV update, cache invalidation. |
| T7 | **Ban at Gate (optional)** | ROADMAP, SESSION_DOCTRINE §4.4 | Only when explicitly requested. Check lupo_banned_actors; 403 + SESSION_DOCTRINE ref; log to lupo_ban_log. |

Do **not** implement 4.0.19 or later features. Do **not** re-open 4.0.17 tasks.

---

### 6. OUT OF SCOPE FOR THIS PROMPT

- **4.0.17 tasks** — Already completed. Do not re-validate or re-do.
- **4.0.19+** — No planning or implementation.
- **Code in this prompt** — This document contains no runtime routing code; implementation happens only when the user or a follow-up instruction requests it.

---

### 7. DELIVERABLES CHECKLIST (4.0.18)

When 4.0.18 work is complete (to be verified in a later step):

- Version string 4.0.18 in all canonical locations.
- UrlResolver uses source hierarchy: DB (ideal) → CSV → .md parse; normalizes slugs; resolves canonical/alias; log warnings on fallbacks.
- Wildcard route for web paths; smart 404 (auth-aware) when enabled.
- Caching and invalidation for resolver output.
- Optional: Ban at Gate (router enforcement).
- CHANGELOG.md 4.0.18 section updated with actual changes.
- FLIP headers for any new or edited doctrine files set to file.last_modified_system_version "4.0.18" only when content was changed in 4.0.18.

---

END OF PROMPT

---

*End of Initialization Prompt 4.0.18.*
