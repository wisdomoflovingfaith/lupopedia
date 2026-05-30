---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: report.md
  web_path: https://www.lupopedia.com/lupopedia/report.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: analysis-report
  artifact_kind: consolidated
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: report
  prd_cluster: null
  title: null
  summary: null
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
