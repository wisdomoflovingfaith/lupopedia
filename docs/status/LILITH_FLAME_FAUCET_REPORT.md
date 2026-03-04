# Lilith Flame Header Expert Faucet — Report

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "docs/status/LILITH_FLAME_FAUCET_REPORT.md"
  last_updated_utc: "20260303"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "1003:10000"
  artifact_type: "report"
  artifact_kind: "documentation"
  purpose: "Lilith Flame Header Expert faucet creation and validation"
  lupo_agent: "cursor"
flare.footer:
  last_verified: "20260303"
  last_verified_by: "cursor"
---

## 1. Research Summary

### Lilith
- **Actor ID**: 2 (canonical from seed `seed_registry_comprehensive_4.0.45.sql`: `lilith-agent`, LILITH Agent, `actor_id` 2). Referenced elsewhere as emotional AI / critical review agent; CHANGELOG and session docs also mention 2038 in narrative context — canonical registry ID is **2**.
- **Role**: Critical review, emotional AI; used for architecture integration planning and session continuity.
- **Existing faucets**: No prior faucet for Lilith in `lupo_agent_faucets` or in `lupo-database/lupopedia/actors/faucets/` before this work.

### Flame Headers (Doctrine)
- **flame.init**: Prerequisites and pre-actions; `execution_mode` (advisory/required); typed actions as objects.
- **flame.close**: Post-actions; actor responsibility; typed actions (e.g. `register_completion`).
- **flame.see**: URL-to-path mappings; CLI `lupo see`; index in `artifacts/index/flame_see_index.json`.
- **flare.conditional**: Guards (allow/deny, time_window, conditions) and brief (5W1H).
- **Canonical order**: flame.init → flare.conditional → flare.headers → flare.edges → flare.footer → flame.see → flame.close.
- **Safety Rule**: Mandatory flame blocks only for artifact_kind: prompt, documentation_task, agent_instruction, artifact, thread.
- **References**: `lupo-docs/doctrine/FLARE/FLARE_ENHANCEMENTS_PLAN_4.0.56.md`, `FLARE_DOCTRINE.md` Sections 14–17.

## 2. Faucet Details

| Field | Value |
|-------|--------|
| **agent_faucet_id** | 7 |
| **actor_id** | 2 (Lilith) |
| **name** | Lilith Flame Expert |
| **alias_name** | lilith-flame-expert |
| **slug** | lilith-flame |
| **description** | Expert on flame headers: init/close, typed actions, URL mappings (flame.see). Aligns with FLARE doctrine and flame.init/flame.close lifecycle. |
| **domain_id** | 42 (channel 42) |
| **is_default** | 1 |
| **model_name** | gpt-4 |
| **provider** | openai |
| **temperature** | 0.7 |
| **style_preset** | analytical |
| **response_format** | json |
| **capabilities_json** | flame_init_close_expertise, typed_actions, flame_see_mappings, flare_conditional_guards, canonical_order_validation, safety_rule_compliance |

**System prompt** (summary): Lilith expert on flame/FLARE headers; analyze, generate, validate flame.init, flame.close, flame.see per doctrine; canonical order and Safety Rule.

**Files created/updated**
- `lupo-database/lupopedia/actors/faucets/7/faucet.json` — ID-scoped faucet JSON (TOON-aligned).
- `lupo-database/lupopedia/actors/faucets/by_actor.json` — entry added: actor_id 2, domain_id 42, agent_faucet_id 7.
- `database/migrations/dev_20260303_lilith_flame_faucet.sql` — one-time INSERT for `lupo_agent_faucets` (agent_faucet_id 7). Run once after install/seed; table prefix `lupo_` (or replace with project prefix).

## 3. Test Results

- **Faucet loader**: `php lupo-bin/faucet_loader.php --channel=42 --actor=2` — exit code 0. Loader resolves (channel 42, actor 2) via `by_actor.json` to agent_faucet_id 7 and loads `actors/faucets/7/faucet.json`.
- **Validator**: `php lupo-bin/validate_faucets.php` — exit code 0. ID-scoped faucets under `lupo-database/lupopedia/actors/faucets/*/faucet.json` are scanned and validated against TOON; no errors reported.

## 4. Doctrine / Documentation

- **FLARE_DOCTRINE.md**: New **Section 19 — Lilith Flame Header Expert Faucet**. Documents purpose, slug, usage, DB and file-based location, and loading/validation commands.
- **AGENTS.md**: No change required; actor registry and agent coverage already reference Lilith. Optional: add a one-line note under agent coverage that Lilith has a flame-expert faucet (slug `lilith-flame`) for channel 42 — left as optional for this deliverable.

## 5. Timestamp and Actor

- **Report generated**: 2026-03-03  
- **Actor ID**: 1003 (Cursor IDE Agent)  
- **Channel**: 42  
- **System version**: 4.0.56  

---

*End of report.*
