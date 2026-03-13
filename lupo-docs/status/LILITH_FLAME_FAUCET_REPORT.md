# Lilith Flame Header Expert Faucet — Report

---
# FLARE Header (aliases: Wolfie, FLIP) — see http://www.lupopedia.com/FLARE
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "report"
  file_path_from_root: "docs/status/LILITH_FLAME_FAUCET_REPORT.md"
  last_modified_utc: "20260303"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "1003:10000"
  artifact_type: "report"
  artifact_kind: "faucet_documentation"
  purpose: "Report on Lilith Flame Header Expert faucet creation and validation"
  mood_rgb: "FF69B4"
  traits: ["canonical", "faucet", "v4.0.56", "lilith"]
  tags: ["lilith", "flame", "faucet", "report"]
  lupo_agent: "cursor"
lupopedia.see:
  mappings:
    - ["docs/status/LILITH_FLAME_FAUCET_REPORT.md", "http://www.lupopedia.com/FLAME_FAUCET_REPORT"]
lupopedia.footer:
  version: "4.0.56"
  last_verified: "20260303"
  last_verified_by: "cursor"
---

## 1. Research Summary

### Lilith
- **Canonical actor_id**: **2** (from seed `seed_registry_comprehensive_4.0.45.sql`: `lilith-agent`, LILITH Agent). Use **2** for all new faucets, registry, and doctrine.
- **Variant 2038**: Legacy or external-variant identifier (e.g. external AI relay validator, FLIP specs). Do not use 2038 for new faucets or registry; standardize on **2** per seeds/doctrine.
- **Role**: Critical review, emotional AI; used for architecture integration planning and session continuity.
- **Existing faucets**: No prior faucet for Lilith in `lupo_agent_faucets` or in `lupo-database/lupopedia/actors/faucets/` before this work.

### Flame Headers (Doctrine)
- **lupopedia.init**: Prerequisites and pre-actions; `execution_mode` (advisory/required); typed actions as objects.
- **lupopedia.close**: Post-actions; actor responsibility; typed actions (e.g. `register_completion`).
- **lupopedia.see**: URL-to-path mappings; CLI `lupo see`; index in `artifacts/index/flame_see_index.json`.
- **lupopedia.conditional**: Guards (allow/deny, time_window, conditions) and brief (5W1H).
- **Canonical order**: lupopedia.init → lupopedia.conditional → lupopedia.headers → lupopedia.edges → lupopedia.footer → lupopedia.see → lupopedia.close.
- **Safety Rule**: Mandatory flame blocks only for artifact_kind: prompt, documentation_task, agent_instruction, artifact, thread.
- **References**: `lupo-docs/doctrine/FLARE/FLARE_ENHANCEMENTS_PLAN_4.0.56.md`, `FLARE_DOCTRINE.md` Sections 14–17.

## 2. Faucet Details

**Note:** `agent_faucet_id` 7 was chosen as the next available ID in sequence (6 is the last known prior faucet, ANUBIS FLARE Ingestion). See `lupo-database/lupopedia/actors/faucets/by_actor.json` for the manifest.

| Field | Value |
|-------|--------|
| **agent_faucet_id** | 7 |
| **actor_id** | 2 (Lilith) |
| **name** | Lilith Flame Expert |
| **alias_name** | lilith-flame-expert |
| **slug** | lilith-flame |
| **description** | Expert on flame headers: init/close, typed actions, URL mappings (lupopedia.see). Aligns with FLARE doctrine and lupopedia.init/lupopedia.close lifecycle. |
| **domain_id** | 42 (channel 42) |
| **is_default** | 1 |
| **model_name** | gpt-4 |
| **provider** | openai |
| **temperature** | 0.7 |
| **style_preset** | analytical |
| **response_format** | json |
| **capabilities_json** | flame_init_close_expertise, typed_actions, flame_see_mappings, flare_conditional_guards, canonical_order_validation, safety_rule_compliance |

**System prompt** (summary): Lilith expert on flame/FLARE headers; analyze, generate, validate lupopedia.init, lupopedia.close, lupopedia.see per doctrine; canonical order and Safety Rule.

**Files created/updated**
- `lupo-database/lupopedia/actors/faucets/7/faucet.json` — ID-scoped faucet JSON (TOON-aligned).
- `lupo-database/lupopedia/actors/faucets/by_actor.json` — entry added: actor_id 2, domain_id 42, agent_faucet_id 7. Example entry format:
  ```json
  {
    "actor_id": 2,
    "domain_id": 42,
    "agent_faucet_id": 7
  }
  ```
- `database/migrations/dev_20260303_lilith_flame_faucet.sql` — INSERT with ON DUPLICATE KEY UPDATE for idempotent re-runs. Table prefix `lupo_` (or replace with project prefix).

## 3. Test Results

- **Faucet loader**: `php lupo-bin/faucet_loader.php --channel=42 --actor=2` — exit code 0. Loader resolves (channel 42, actor 2) via `by_actor.json` to agent_faucet_id 7 and loads `actors/faucets/7/faucet.json`.
- **Validator**: `php lupo-bin/validate_faucets.php` — exit code 0. ID-scoped faucets under `lupo-database/lupopedia/actors/faucets/*/faucet.json` are scanned and validated against TOON; no errors reported.

**Example output (loader):**
```bash
$ php lupo-bin/faucet_loader.php --channel=42 --actor=2
Faucet loaded successfully:
Channel: 42
Actor: 2
Name: Lilith Flame Expert
Slug: lilith-flame
Description: Expert on flame headers: init/close, typed actions, URL mappings (lupopedia.see)...
```

**Example output (validator):**
```bash
$ php lupo-bin/validate_faucets.php
=== Faucet Validation Report ===
Total Files: N
Valid Files: N
...
VALIDATION PASSED
```

## 4. Doctrine / Documentation

- **FLARE_DOCTRINE.md**: New **Section 19 — Lilith Flame Header Expert Faucet**. Documents purpose, slug, usage, DB and file-based location, and loading/validation commands.

### Section 19 content (from FLARE_DOCTRINE.md)

Full text of Section 19 for verification:

> **19. Lilith Flame Header Expert Faucet (v4.0.56+)**
>
> Lilith (**actor_id 2**, emotional AI / critical review agent) has a specialized faucet for **flame header expertise** in `lupo_agent_faucets`. **Canonical Lilith ID is 2** (seeds/registry); 2038 is a legacy or external-variant identifier and should not be used for new faucets or registry.
>
> **Purpose** — Name: Lilith Flame Expert. Slug: `lilith-flame`. Usage: Analyze, generate, and validate `lupopedia.init`, `lupopedia.close`, and `lupopedia.see` blocks per FLARE doctrine. Guide pre/post-actions (typed objects), `execution_mode` (advisory/required), `lupopedia.conditional` guards and brief, and URL-to-path mappings. Enforce canonical block order and the Safety Rule (mandatory flame blocks only for prompt, documentation_task, agent_instruction, artifact, thread).
>
> **Location** — DB: Row in `lupo_agent_faucets` with `agent_faucet_id` 7, `actor_id` 2, `domain_id` 42. File-based: `lupo-database/lupopedia/actors/faucets/7/faucet.json`. Manifest: `lupo-database/lupopedia/actors/faucets/by_actor.json` maps (actor_id 2, domain_id 42) → 7.
>
> **Loading** — `php lupo-bin/faucet_loader.php --channel=42 --actor=2` loads the Lilith Flame Expert faucet. Validate with `php lupo-bin/validate_faucets.php`.
- **AGENTS.md**: No change required; actor registry and agent coverage already reference Lilith. Optional: add a one-line note under agent coverage that Lilith has a flame-expert faucet (slug `lilith-flame`) for channel 42 — left as optional for this deliverable.

## 5. Timestamp and Actor

- **Report generated**: 2026-03-03  
- **Actor ID**: 1003 (Cursor IDE Agent)  
- **Channel**: 42  
- **System version**: 4.0.56  

---

*End of report.*
