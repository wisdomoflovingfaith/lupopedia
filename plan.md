---
lupopedia.init:
  required_reading:
    - path: "lupo-docs/INIT_README.md"
      reason: "Prerequisites and 'Before You Read This File'"
    - path: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      reason: "Header format and block order"
    - path: "AGENTS.md"
      reason: "Agent/faucet distinction and lead orchestration"
  required_context:
    - "LUPOPEDIA HEADERS are the bridge between files and database—see lupopedia.edges"
    - "Cursor (actor_id 102) is lead orchestrator; other IDE faucets (Kiro, Windsurf, Codex, Antigravity) submit plans via their own files"
    - "This root plan consolidates; faucet-specific plans remain authoritative for their domains"

lupopedia.actor_references:
  comment: "Actor IDs per lupo-database/lupopedia/actors/actor_id/registry.json"
  cursor: 102
  wolfie: 1
  kiro: 100
  windsurf: 101
  antigravity: 103
  warp: 104
  cascade: 105
  codex: "TBD — JetBrains/Codex not in registry; see plan_codex.md"

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."
  title:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Lupopedia Consolidated Implementation Plan", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260314000000, updated_ymdhis: 20260314000000 }
  description:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Consolidated implementation plan from Kiro, Windsurf, and Codex faucet plans; lead orchestration by Cursor.", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260314000000, updated_ymdhis: 20260314000000 }
  author:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "cursor", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260314000000, updated_ymdhis: 20260314000000 }
  orchestrator:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "cursor", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260314000000, updated_ymdhis: 20260314000000 }

lupopedia.headers:
  lupopedia.version: "4.0.75"
  lupopedia.schema: "plan"
  file_path_from_root: "plan.md"
  web_path: "http://www.lupopedia.com/plan"
  last_modified_utc: "20260314"
  system_version: "4.0.75"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  faucet_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "implementation-plan"
  artifact_kind: "consolidated"
  purpose: "Root consolidated implementation plan; synthesizes plan_kiro, plan_windsurf, plan_codex"

lupopedia.edges:
  comment: "Snapshot of outbound edges for plan.md at artifact creation."
  outbound_edges:
    - { to: "report.md", type: "references", weight: 1.0 }
    - { to: "README.md", type: "references", weight: 0.95 }
    - { to: "CHANGELOG.md", type: "references", weight: 0.9 }
    - { to: "KIRO_CHANGES_and_report.md", type: "references", weight: 0.88 }
    - { to: "plan_kiro.md", type: "references", weight: 0.85 }
    - { to: "plan_windsurf.md", type: "references", weight: 0.85 }
    - { to: "plan_codex.md", type: "references", weight: 0.85 }
    - { to: "lupo-docs/database/lupopedia/SCHEMA_REGISTRY.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md", type: "references", weight: 0.85 }
    - { to: "lupo-prompts/cursor/20260314_cursor_execute_plan_4_0_74.md", type: "implements", weight: 0.9 }
    - { to: "lupo-prompts/cursor/20260314_cursor_pass3_toon_seed_cleanup_4_0_74.md", type: "implements", weight: 0.9 }
    - { to: "lupo-prompts/cursor/20260315_cursor_p1_execution_4_0_74.md", type: "implements", weight: 0.9 }
    - { to: "lupo-docs/status/FOLDER_RENAME_AUDIT_4_0_74.md", type: "references", weight: 0.85 }
    - { to: "lupo-docs/doctrine/TABLE_COUNT_DOCTRINE.md", type: "references", weight: 0.85 }
    - { to: "lupo-docs/status/CURSOR_ROOT_DOCS_RECONCILIATION_4_0_74.md", type: "references", weight: 0.85 }
  semantic_tags: ["plan", "implementation", "consolidated", "cursor_lead"]

lupopedia.footer:
  version: "4.0.75"
  last_verified: "20260314"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Captain/Wolfie: run upgrade path test (drop all tables → load Crafty 3.7.5 → upgrade to 4.0.75)"
    - "Coordinate with Kiro, Windsurf, Codex, Antigravity on domain ownership"
    - "Merge approved faucet-plan items into this plan as phases complete"
    - "Reconcile TOON output path if needed (install SQL remains authoritative; TOONs are derived)"

lupopedia.next_actions:
  next_actions:
    - "Captain/Wolfie: test upgrade path — drop all DB tables, load Crafty Syntax 3.7.5 install, run Lupopedia installer to upgrade to 4.0.75; record results in plan.md and report.md"
    - "Coordinate with Kiro, Windsurf, Codex, Antigravity on domain ownership"
    - "Merge approved faucet-plan items into this plan as phases complete"
    - "Align lupopedia.init and lupopedia.next_actions usage across repo (P1)"
    - "Follow-up: run generate_toon_files.py when DB available (P1 Task 2)"
---
# file: plan — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/plan

# Lupopedia Consolidated Implementation Plan

**Lead orchestration:** Cursor IDE (actor_id 102)  
**Supporting actor:** Wolfie (actor_id 1)  
**Version:** 4.0.75  
**Source:** Consolidated from `plan_kiro.md`, `plan_windsurf.md`, `plan_codex.md`

This is the **root** implementation plan. Faucet-specific plans remain in `plan_kiro.md`, `plan_windsurf.md`, `plan_codex.md` (and `plan_antigravity.md` if present). Cursor as lead orchestration maintains this file and merges approved items from faucet plans.

---

## 4.0.75 Cursor rules propagation (verified)

- **Cursor rule propagation:** `lupo-scripts/propagate_agent_rules.php --target=cursor` writes `.cursor/lupopedia_rules.json` (with `source_path`, `slug` per rule) and `.cursor/rules/<slug>.mdc`. All 15 canonical root rules propagated. `.cursor/README.md` documents source, command, and validation. Enforcement test: `php lupo-tests/unit/cursor_rules_enforcement.php`.

## 4.0.75 Antigravity rules propagation hardening (verified)

- **Root rule review:** Verified 15 canonical rules inside `lupo-rules/root/`. No `.google` target directory justified natively within the repository; validated existing `.kiro`, `.cursor`, and `.windsurf` scopes.
- **Shared Propagation Hardening:** Standardized `source_path`, `slug`, `category`, and `status` variables securely into `.kiro/lupopedia_rules.json` and `.windsurf/lupopedia_rules.json`.
- **Kiro Gap Fixed:** Successfully established unimplemented `.kiro/rules/*.md` LUPOPEDIA HEADERS writing behavior requested in Kiro's design instructions via `write_kiro_outputs()` in `lupo-scripts/propagate_agent_rules.php`.
- **Validation Execution:** Instituted strict structural validation using `php lupo-tests/unit/kiro_rules_enforcement.php`. All agents generated deterministic files properly matching target contexts.
- **TOON Output Consolidated:** Re-wired `.lupo-scripts/generate_toon_from_sql.py` away from the drifting `lupo-docs/toons/` to universally point straight to the operational documentation directory natively at `lupo-database/lupopedia/toon`. Removed `lupo-docs/toons/` logic completely. 
- **Database .htaccess Protections:** Generated robust shared-host appropriate blocking rules wrapped in standard Apache 2.2/2.4 context blocking all naked HTTP requests to internal SQL, JSON, and structure components within `lupo-database/` implicitly protecting children generated targets natively.

## 4.0.74 implemented (verified)

- **lupo_projects:** In install SQL; `seed_projects.sql` created and wired into installer (bootstrap, upgrade run, new-install run, main seed loop). Table doc and SCHEMA_REGISTRY updated.
- **12-table install expansion:** All 12 approved tables (aliases, legacy_content_mapping, reference_objects, reference_cited_by, search_index, documentation_frameworks, federated_trust, federation_discovery, unified_log, anubis_operations, system_health_snapshots, hotfix_registry) in install SQL; one-time migration for existing installs; future_features cleaned/annotated. Canonical install table count: **159** (see TABLE_COUNT_DOCTRINE).
- **Path/prefix normalization:** Root directories use `lupo-*` prefix; **`legacy/` is the intentional exception** (legacy read-only code; not renamed). Empty root `scripts/` removed. Script and doc references use `lupo-scripts/`, `lupo-docs/`, `lupo-database/`.
- **Advisory table-count doctrine:** TABLE_COUNT_DOCTRINE and SCHEMA_REGISTRY state install SQL is authoritative; table ceiling is advisory only. TOON count = install-SQL-derived (159); other TOON file counts may include planning/deprecated paths.
- **Installer:** Post-install background command uses `lupo-scripts/import_channels_and_artifacts.py`. Install SQL and seeds run from `LUPO_MYSQL_DIR` (lupo-database/lupopedia/mysql).

---

## P0 (Immediate)

1. **Canonicalize identity and paths**
   - Resolve actor/agent/faucet IDs from [registry](lupo-database/lupopedia/actors/actor_id/registry.json) only; fix any doc/seed drift (Codex).
   - Fix `lupo-docs/` vs `lupo-docs/` path drift in root and linked docs (Codex).
   - Header key normalization: support legacy `flare.*` read; canonical write as `lupopedia.*` (Codex).

5. **Documentation root (verified)**
   - **lupo-docs/** is the canonical documentation root. No top-level **lupo-docs/** directory exists. Any **lupo-docs/** references in content are path-string drift to fix, not a second valid root.
   - Update internal references in plan files and linked doctrine to use **lupo-docs/** consistently.

2. **TOON and schema authority**
   - **Install SQL** is the canonical schema authority (`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`). **TOON files are derived artifacts**, not source of truth. Where TOONs and install SQL disagree, install SQL wins.
   - The repository supports more than one TOON-related path (e.g. `lupo-docs/toons/*.toon.json`, and DB generator output to `lupo-database/lupopedia/toon/` when run). Unify or document TOON generation/output paths in a follow-up; do not overclaim one location as the single canonical without aligning tooling.
   - Resolve TOON format/location discrepancy (Kiro Phase 1.1); KIRO’s SCHEMA_REGISTRY_KIRO and VALIDATION_REPORT_KIRO are v4.0.74 alternatives — Cursor to decide merge vs keep _kiro variants.

3. **Root documentation**
   - Keep README.md, CHANGELOG.md, plan.md, report.md aligned with doctrine and actual paths (Cursor).

4. **KIRO late submission reviewed**
   - [KIRO_CHANGES_and_report.md](KIRO_CHANGES_and_report.md) lists 10 KIRO-created files; Cursor (lead) reviewed and applied corrections. **KIRO actor_id = 100** per [registry](lupo-database/lupopedia/actors/actor_id/registry.json) (KIRO had used 10000 in error; corrected in KIRO_CHANGES_and_report.md). KIRO domain boundaries (see KIRO_HANDOFF_RESPONSE) accepted for coordination. Any other KIRO-authored file that still has actor_id 10000 should be updated to 100.

---

## P1 (Short-term)

1. **Schema and validation**
   - Single schema inventory artifact: install table count vs TOON count vs migration count (Codex). See [CURSOR_IMPLEMENTATION_REPORT_4_0_74](lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md) § Schema inventory (Pass 3).
   - Markdown link-check pass for root docs (Codex).
   - **Merge process (Pass 3):** Faucet-specific files (`plan_kiro.md`, `report_windsurf.md`, `*_codex`, etc.) remain **authoritative for their domain**. Root canon (`plan.md`, `report.md`, `CHANGELOG.md`, `README.md`, `AGENTS.md`) is maintained by **Cursor as lead**. Merges into root: Cursor (or delegated agent) reviews faucet submissions and copies approved items into root artifacts; root is the single consolidated view. Do not silently overwrite root with a faucet file; always merge with attribution. Faucet files are inputs, not replacements.

2. **Documentation structure (Kiro-led where applicable)**
   - Deduplicate FLARE/LUPOPEDIA HEADERS (single canonical block per file).
   - Domain ownership matrix: clear boundaries for Kiro, Windsurf, Cursor, Antigravity, Codex, Warp, Cascade (Kiro Phase 1.3).

3. **Missing doctrine files (Windsurf Phase 2)**
   - `lupo-docs/doctrine/LUPOPEDIA_HEADERS_AND_METADATA_BRIDGE.md` (exists per CHANGELOG; verify and expand).
   - `lupo-docs/doctrine/FILESYSTEM_OBJECTS_AND_DATABASE_SNAPSHOTS.md` (exists per CHANGELOG; verify).
   - Table ceiling doctrine doc if not already covered.

4. **lupopedia.init alignment**
   - **Doctrine:** `lupopedia.init` = **required reading / required context** before reading the file (not file metadata). See [LUPO_INITIALIZATION_DOCTRINE](lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md) and [LUPOPEDIA_HEADERS_FORMAT](lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md).
   - Migrate existing files that use `lupopedia.init` for artifact_type, file_identity, namespace, domain, system_version: move those to `lupopedia.headers` or `lupopedia.metadata`; put only `required_reading` and `required_context` in `lupopedia.init`. Prefer **path + reason** for required_reading in plan/report files (simple list remains valid).

5. **lupopedia.next_actions (was lupopedia.close)**
   - **Doctrine:** `lupopedia.next_actions` = **suggested next actions** after reading/using the file (like init but for follow-ups). Legacy name: `lupopedia.close`. See [OPTIONAL_BLOCKS](lupo-docs/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md).
   - Use **lupopedia.next_actions** with a `next_actions:` list in new or updated files when you want an explicit "what to do next" block; validators accept **lupopedia.close** for backward compatibility.

6. **Edge snapshot maintenance doctrine**
   - Define when to regenerate **lupopedia.edges** (e.g. after major file moves, when semantic relationships change significantly).
   - Document: "Update lupopedia.edges when the file's semantic relationships change significantly."
   - Consider tooling: e.g. `lupo-bin/update-edges.php` to refresh edges from a manifest or scan.

7. **lupopedia.next_actions backward compatibility**
   - Ensure validators accept both **lupopedia.next_actions** and **lupopedia.close** (already documented in OPTIONAL_BLOCKS).
   - Set deprecation date for **lupopedia.close**: 4.1.0 (when Lupopedia→Lupopedia upgrade and auto-installers land).
   - Update OPTIONAL_BLOCKS.md with deprecation timeline and validator behavior.

---

## P2 (Medium-term)

1. **Coordination and standards**
   - KIRO: coordination rules, documentation standards, validation pipeline (Kiro Phases 3–4).
   - Windsurf: Phase 3–4 (guides, validation, testing).
   - Legacy FLARE naming cleanup in active docs (Codex).

2. **Changelog and evidence**
   - Changelog entry standards requiring file/count evidence where claimed (Codex).

---

## Validation / acceptance criteria

Use these to confirm when each phase is done. Do not guess; verify against registry, install SQL, and repo paths.

| Phase | Criteria |
|-------|----------|
| **P0** | All actor IDs in plan match [registry](lupo-database/lupopedia/actors/actor_id/registry.json); **lupo-docs/** confirmed as canonical doc root (no top-level lupo-docs/); lupopedia.init contains only required_reading and required_context (no file metadata); KIRO-authored files with actor_id 10000 corrected to 100; **lupo_projects** added to install SQL; **table ceiling** is advisory only. |
| **P1** | lupopedia.init alignment complete (path+reason or simple list per doctrine); next_actions/close backward compat and deprecation date in OPTIONAL_BLOCKS; edge snapshot maintenance doctrine documented; domain ownership matrix and merge process defined. |
| **P2** | Coordination rules and validation pipeline in place; changelog evidence standards applied. |

---

## Antigravity delivery (2026-03-14)

Antigravity (actor_id 103) completed a schema refactor for 4.0.7x alignment. Delivered: **lupo_orchestrator_rules** moved into install (canonical); **lupo_comments** and **lupo_hashtags** deduplicated from future_features; **lupo_flare_headers** deprecated (LUPOPEDIA HEADERS canonical); **lupo_anubis_operations** and **lupo_system_health_snapshots** consolidated in future_features; **lupo_metadata** gained **schema_ref** column; TOON generation script output set to lupo-docs/toons (147 TOONs). See [CHANGELOG.md](CHANGELOG.md) § Antigravity schema refactor (2026-03-14). Follow-up: reconcile canonical TOON output path (lupo-docs/toons vs lupo-database/lupopedia/toon) if lead orchestration decides.

---

## Next: Upgrade path test (Captain/Wolfie)

**Planned test:** (1) Drop all database tables. (2) Load a **Crafty Syntax 3.7.5** install (legacy schema and data from `lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql` or equivalent). (3) Run the Lupopedia installer (`install.php`) to **upgrade to 4.0.74**. This validates the only supported upgrade path (Crafty 3.7.5 → Lupopedia 4.0.x), install + seed (including `seed_projects.sql`), and reserved channels. Record results in plan.md and report.md.

---

## Faucet plan references

| Faucet       | File                         | Focus                                                                 |
|-------------|------------------------------|-----------------------------------------------------------------------|
| Kiro        | plan_kiro.md, [KIRO_CHANGES_and_report.md](KIRO_CHANGES_and_report.md) | TOON authority, domain matrix, coordination; late thread summary (10 files); actor_id **100** (registry). |
| Windsurf    | plan_windsurf.md             | README/CHANGELOG corrections, missing docs                            |
| Codex       | plan_codex.md               | P0/P1 remediation backlog, collision-safe flow                        |
| Antigravity | plan_antigravity.md (if present) | Schema refactor 4.0.7x: orchestrator_rules → install; unified ANUBIS ops & system health; lupo_metadata schema_ref; TOON path (see CHANGELOG 2026-03-14). |

---

*Cursor IDE (lead orchestration) — consolidated plan 2026-03-14*
