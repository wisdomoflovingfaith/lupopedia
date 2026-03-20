---
lupopedia.init:
  required_reading:
    - path: "lupo-docs/INIT_README.md"
      reason: "Prerequisites and 'Before You Read This File'"
    - path: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      reason: "Header format and block order"
    - path: "AGENTS.md"
      reason: "Agent/faucet distinction and lead orchestration"
    - path: "plan.md"
      reason: "Root consolidated plan and P0/P1/P2 tasks"
  required_context:
    - "LUPOPEDIA HEADERS are the bridge between files and database—see lupopedia.edges"
    - "Cursor (actor_id 102) is lead orchestrator; other IDE faucets submit reports via their own files"
    - "This root report consolidates; faucet-specific reports remain authoritative for their domains"

lupopedia.actor_references:
  comment: "Actor IDs per lupo-database/lupopedia/actors/actor_id/registry.json"
  cursor: 102
  wolfie: 1
  kiro: 100
  windsurf: 101
  antigravity: 103
  warp: 104
  cascade: 105
  codex: "TBD — JetBrains/Codex not in registry; see report_codex.md"

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."
  title:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Lupopedia Consolidated Report", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260314000000, updated_ymdhis: 20260314000000 }
  description:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Consolidated findings from Kiro, Windsurf, and Codex faucet reports; lead orchestration by Cursor.", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260314000000, updated_ymdhis: 20260314000000 }
  author:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "cursor", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260314000000, updated_ymdhis: 20260314000000 }
  orchestrator:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "cursor", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260314000000, updated_ymdhis: 20260314000000 }

lupopedia.headers:
  lupopedia.schema: "report"
  file_path_from_root: "report.md"
  version_when_written: "4.0.84"
  web_path: "http://www.lupopedia.com/report"
  last_modified_utc: "20260315"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  faucet_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "analysis-report"
  artifact_kind: "consolidated"
  purpose: "Root consolidated report; synthesizes report_kiro, report_windsurf, report_codex"

lupopedia.edges:
  comment: "Snapshot of outbound edges for report.md at artifact creation."
  outbound_edges:
    - { to: "plan.md", type: "references", weight: 1.0 }
    - { to: "README.md", type: "references", weight: 0.95 }
    - { to: "CHANGELOG.md", type: "references", weight: 0.9 }
    - { to: "KIRO_CHANGES_and_report.md", type: "references", weight: 0.9 }
    - { to: "report_kiro.md", type: "references", weight: 0.85 }
    - { to: "report_windsurf.md", type: "references", weight: 0.85 }
    - { to: "report_codex.md", type: "references", weight: 0.85 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md", type: "references", weight: 0.85 }
  semantic_tags: ["report", "findings", "consolidated", "cursor_lead"]

lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260314"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Update this report as P0/P1 items from plan.md are completed"
    - "Incorporate new findings from faucet reports when merged"
    - "Record results of upgrade path test (drop all tables → load Crafty 3.7.5 → upgrade to 4.0.74)"
---
# file: report — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/report

# Lupopedia Consolidated Report

**Lead orchestration:** Cursor IDE (actor_id 102)  
**Supporting actor:** Wolfie (actor_id 1)  
**Version:** 4.0.74  
**Source:** Consolidated from `report_kiro.md`, `report_windsurf.md`, `report_codex.md`

This is the **root** findings report. Faucet-specific reports remain in `report_kiro.md`, `report_windsurf.md`, `report_codex.md`. Cursor as lead orchestration maintains this file.

---

## Executive summary

Multiple IDE faucets (Kiro, Windsurf, Codex, Antigravity, Cursor, Warp, Cascade) have produced analyses and corrections for Lupopedia 4.0.74. Common themes:

1. **Identity and paths:** Actor/faucet IDs must be resolved from the canonical registry; path drift between `lupo-docs/` and `lupo-docs/` exists and should be fixed.
2. **TOON and schema:** TOON location and format (e.g. `lupo-database/lupopedia/toon/*.toon.json`) must be the single source of truth; coordination docs (SCHEMA_REGISTRY, VALIDATION_REPORT) need to align.
3. **Headers:** LUPOPEDIA HEADERS are canonical; legacy FLARE/FLIP naming should be read for compatibility but written as `lupopedia.*`; duplicate header blocks in files should be reduced to one.
4. **Concurrency:** Multiple agents editing shared files caused collision risk; faucet-specific files (e.g. `*_codex.md`) used to avoid overwrites until merge is approved.

---

## Findings by source

### Kiro (report_kiro.md, KIRO_CHANGES_and_report.md)

- **KIRO late submission:** KIRO delivered a thread summary and 10 files after initial consolidation (see [KIRO_CHANGES_and_report.md](KIRO_CHANGES_and_report.md)). **Correction (Cursor lead):** Canonical [actor registry](lupo-database/lupopedia/actors/actor_id/registry.json) lists KIRO as **actor_id 100** (ide_faucet, slug kiro). KIRO’s document used 10000 in error; KIRO_CHANGES_and_report.md and this report use **100**.
- TOON format/location: two locations exist (e.g. `lupo-database/.../toon/` YAML vs `lupo-database/lupopedia/toon/` JSON). **Canonical schema** = install SQL (`lupo_actors` has `PRIMARY KEY (actor_name)`); **canonical TOON location** for the repo = `lupo-database/lupopedia/toon/`. If a TOON disagrees with install SQL, regenerate from install.
- KIRO created SCHEMA_REGISTRY_KIRO.md, VALIDATION_REPORT_KIRO.md, KIRO_HANDOFF_RESPONSE.md, TABLE_INDEX_KIRO.md; domain boundaries (KIRO vs Cursor) documented in KIRO_HANDOFF_RESPONSE. Multiple stacked headers in many files; domain ownership matrix in progress.
- Recommendation: Use registry for all actor/faucet IDs; keep TOONs aligned with install SQL; adopt KIRO domain boundaries for coordination.

### Windsurf (report_windsurf.md)

- README/architecture corrections: identity model (`lupo_actors` primary key doctrine), header storage (`lupo_metadata` canonical), table count (~50 core vs “200+”), no FKs, table ceiling 222.
- Missing or incomplete: header–database bridge doc, filesystem object doctrine, table ceiling doctrine; AUTH_USERS_ACTORS_AGENTS_FAUCETS and LUPOPEDIA_HEADERS need expansion.
- Recommendation: Update root README/CHANGELOG from research; create missing doctrine files; add guides and validation.

### Codex (report_codex.md)

- Concurrency: multi-agent edits caused post-write drift; Codex uses `*_codex.md` stream until orchestrator approves merge.
- Evidence snapshot: repository file count ~11,376; CREATE TABLE count in install SQL ~140 (Codex); runtime flow validated (index.php → bootstrap → loader → module-loader).
- Path drift (`lupo-docs/` vs `lupo-docs/`); identity drift across registry/seeds/docs; header doctrine and legacy tooling not fully converged.

---

## Evidence and counts (do not guess)

- **TOON files:** In `lupo-database/lupopedia/toon/`, 230 `.toon.json` files (per glob and Kiro). Canonical schema is install SQL; TOONs should reflect it (e.g. `lupo_actors` PK = actor_name per install).
- **Install SQL:** Canonical schema in `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`; `lupo_actors` has `PRIMARY KEY (actor_name)` and `UNIQUE (actor_id)` (per install SQL).
- **Registry:** [lupo-database/lupopedia/actors/actor_id/registry.json](lupo-database/lupopedia/actors/actor_id/registry.json); Cursor (102) lead orchestration; **Kiro = 100**, Windsurf = 101, Antigravity = 103, Warp = 104, Cascade = 105; Codex (JetBrains) = seventh agent.

---

## Faucet report references

| Faucet    | File                        | Focus                                                                 |
|-----------|-----------------------------|-----------------------------------------------------------------------|
| Kiro      | report_kiro.md, [KIRO_CHANGES_and_report.md](KIRO_CHANGES_and_report.md) | DB documentation, TOON/schema, coordination; late thread summary (10 files); actor_id **100** per registry. |
| Windsurf  | report_windsurf.md          | Architecture accuracy, missing doctrine                              |
| Codex     | report_codex.md             | Concurrency, path/identity drift, evidence                           |

---

## KIRO late submission (Cursor review)

KIRO’s late delivery ([KIRO_CHANGES_and_report.md](KIRO_CHANGES_and_report.md)) lists 10 created files (root: report_kiro, plan_kiro, README_kiro, CHANGELOG_kiro, README_UPDATED, KIRO_CHANGES_and_report; plus SCHEMA_REGISTRY_KIRO, VALIDATION_REPORT_KIRO, KIRO_HANDOFF_RESPONSE, TABLE_INDEX_KIRO under lupo-docs/database/lupopedia). **Corrections applied:** (1) KIRO actor_id is **100** per registry (document had 10000); (2) lupopedia.edges in that file now has required `comment`. **Accepted:** KIRO’s domain boundaries (see KIRO_HANDOFF_RESPONSE) for coordination; canonical TOON location remains `lupo-database/lupopedia/toon/`; schema truth = install SQL.

---

*Cursor IDE (lead orchestration) — consolidated report 2026-03-14*
