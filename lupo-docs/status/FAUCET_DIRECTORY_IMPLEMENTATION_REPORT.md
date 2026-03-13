---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/status/FAUCET_DIRECTORY_IMPLEMENTATION_REPORT.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "report"
  purpose: "Implementation report for new faucet directory structure (actors/faucets/<id>/)"
  traits: ["implementation", "faucets", "cursor"]
  tags: ["faucets", "implementation", "4.0.56"]
  lupo_agent: "cursor"
---

# Faucet Directory Implementation Report

**Date:** 2026-03-03  
**Actor:** Cursor (1003)  
**Reference:** docs/status/LUPO_AGENT_FAUCETS_RESEARCH_REPORT.md  

---

## 1. Changes Made

### 1.1 New Directory Structure and Pilot

- **Created:** `lupo-database/lupopedia/actors/faucets/`
- **Created:** `lupo-database/lupopedia/actors/faucets/6/faucet.json` — single faucet object for ANUBIS FLARE Ingestion (agent_faucet_id 6, actor_id 19, domain_id 42). JSON matches TOON schema (capabilities_json as string, safety_json as object).
- **Created:** `lupo-database/lupopedia/actors/faucets/by_actor.json` — manifest mapping (actor_id, domain_id) → agent_faucet_id. Single entry: actor 19, domain 42 → 6.

### 1.2 FaucetLoader.php (lupo-bin/faucet_loader.php)

- **Base path:** Resolved from `LUPO_DATABASE_DIR` (if set) or `LUPOPEDIA_PATH`/`dirname(__DIR__)` so paths are not CWD-dependent. Base = `.../lupopedia` under lupo-database.
- **LUPOPEDIA_PATH:** Set to `dirname(__DIR__)` when not defined (CLI from lupo-bin).
- **TOON path:** Load from `base_path/toon/lupo_agent_faucets.toon.json` with fallback to `lupo-docs/toons/...`.
- **Resolution order:** (1) Per-actor override: `base_path/channels/lupo-channels/<channel_id>/actors/<actor_id>/faucets.json`. (2) Channel-wide: `base_path/channels/lupo-channels/<channel_id>/faucets.json`. (3) ID-scoped: resolve `agent_faucet_id` via `by_actor.json` or DB (`SELECT agent_faucet_id FROM lupo_agent_faucets WHERE actor_id = ? AND domain_id = ?`), then load `base_path/actors/faucets/<agent_faucet_id>/faucet.json`.
- **Helpers:** `resolveAgentFaucetId()`, `loadChannelWideAndGetActor()`. `loadAndValidate()` extended to accept optional `actor_id` and to handle wrapper format `{ "faucets": [ {...} ] }` (extract matching or first faucet).
- **PHP 5.3:** Use `array()` instead of `[]`; no short array syntax.
- Duplicate `<?php` at top of file removed.

### 1.3 validate_faucets.php (lupo-bin/validate_faucets.php)

- **LUPOPEDIA_PATH** and **base_path** added (same resolution as loader).
- **TOON path:** Prefer `base_path/toon/lupo_agent_faucets.toon.json`, then `lupo-docs/toons/...`, then `docs/toons/...`.
- **scanIdScopedFaucets():** Scans `base_path/actors/faucets/` for subdirs; each with `faucet.json` is validated as type `id-scoped`.
- **validateFile():** When type is `id-scoped`, content is treated as single faucet object and passed to `validateSingleFaucet()`.
- **validateAll():** Calls `scanDirectory('channels')` then `scanIdScopedFaucets()`.
- **Stats:** Added `id_scoped_faucets` count. Per-actor file handling: if root has `faucets` array, use first element for validation.

### 1.4 faucet_integrity_audit.php (lupo-bin/faucet_integrity_audit.php)

- **LUPOPEDIA_PATH** and **base_path** added.
- **loadAllFaucets():** Channel scan uses `LUPOPEDIA_PATH . '/channels'` (no longer assumes CWD). Added scan of `base_path/actors/faucets/<id>/faucet.json`; each valid JSON with `agent_faucet_id` is appended to `all_faucets` with type `id_scoped`. Channels dir is optional (no throw if missing).

### 1.5 Doctrine / Documentation

- **lupo-docs/archive/v4.0.52_windsurf_reports/windsurf_agent_faucets_explanation.md:** Appended section **"ID-Scoped Faucet Directories (4.0.56)"** — path, manifest, precedence, loader behavior, validation, pilot faucet 6.

### 1.6 wolfie_orms.py (scripts/wolfie_orms.py)

- **select_one_from_lupo_agent_faucets():** Replaced legacy columns (`agent_id`, `faucet_type`, `faucet_data`, `is_deleted`) with current schema: `actor_id`, `name`, `alias_name`, `slug`, `description`, `style_preset`, `model_name`, `provider`, `temperature`, `top_p`, `max_tokens`, `presence_penalty`, `frequency_penalty`, `system_prompt`, `safety_json`, `response_format`, `capabilities_json`, `is_default`, `domain_id`, `created_ymdhis`, `updated_ymdhis`, `deleted_ymdhis`. Comment added noting schema alignment and legacy column removal.

---

## 2. Verification

- **Paths:** Loader and validator use base path derived from `LUPOPEDIA_PATH` or `LUPO_DATABASE_DIR`; ID-scoped file path is `lupo-database/lupopedia/actors/faucets/6/faucet.json` under repo.
- **ANUBIS faucet:** For (channel_id=42, actor_id=19), `resolveAgentFaucetId` returns 6 from `by_actor.json`; loader will load `actors/faucets/6/faucet.json` when no channel override exists.
- **Validation:** `validate_faucets.php` will scan `actors/faucets/` and validate `6/faucet.json` as single faucet; required fields and types match TOON (capabilities_json string, safety_json array/object).
- **CLI test (recommended):** From repo root, run: `php lupo-bin/faucet_loader.php --channel=42 --actor=19` (requires config loaded or bootstrap compatible with CLI). Run: `php lupo-bin/validate_faucets.php` to validate all faucet files including id-scoped.

---

## 3. Files Created

| Path | Description |
|------|-------------|
| lupo-database/lupopedia/actors/faucets/6/faucet.json | ANUBIS FLARE Ingestion faucet (agent_faucet_id 6) |
| lupo-database/lupopedia/actors/faucets/by_actor.json | Manifest (actor_id, domain_id) → agent_faucet_id |
| docs/status/FAUCET_DIRECTORY_IMPLEMENTATION_REPORT.md | This report |

---

## 4. Files Updated

| Path | Description |
|------|-------------|
| lupo-bin/faucet_loader.php | Base path, ID-scoped lookup, by_actor + DB resolution, channel paths under base |
| lupo-bin/validate_faucets.php | Base path, canonical TOON, scanIdScopedFaucets, id-scoped validation |
| lupo-bin/faucet_integrity_audit.php | Base path, load id-scoped faucets into all_faucets |
| lupo-docs/archive/v4.0.52_windsurf_reports/windsurf_agent_faucets_explanation.md | ID-scoped section and precedence |
| scripts/wolfie_orms.py | select_one_from_lupo_agent_faucets schema alignment |

---

## 5. Timestamp and Actor

- **Report generated:** 2026-03-03  
- **Actor ID:** 1003 (Cursor IDE Agent)  
- **System version:** 4.0.56  

---

*End of report.*
