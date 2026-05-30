---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260406043326"
  file_path_from_root: "docs/versions/4.0.94/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/README.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: version_readme
  thread_id: "version-4.0.94-readme"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: docs/versions/4.0.94/README.md — delegation: cursor:root

# Lupopedia 4.0.94 (working)

# This version directory has been consolidated into 4.0.97

See `../4.0.97/` for current work.

Files here are preserved for historical reference but are no longer active.

## Items moved to 4.0.97 (open backlog, UTC `20260408190824`)

Unfinished **non-session, non-ladder** tasks from this line (for example **T-VERIFY-***, packaging smoke, **Step 3 Actor Reconstruction**) are tracked in **[../4.0.97/TODO.md](../4.0.97/TODO.md)**. **Session** and **Chronological Trust Ladder** follow-ups live under **[../4.0.96/TODO.md](../4.0.96/TODO.md)**.

# Lupopedia 4.0.94 (working)
This directory documents **4.0.94**. Release **4.0.93** is frozen under `docs/versions/4.0.93/`. The **next planning** folder is **`docs/versions/4.0.95/`** (deferred tasks and follow-on work); it does not by itself bump runtime version.

## Status

**Version 4.0.94 is ready for Softaculous packaging** (documentation and scoped refactor complete).

Completed for this line:

- Session authority migrated to Model A (`lupo_sessions` + `metadata`; see **`decisions/20260406_042624_DECISION_session_authority_migration.md`**)
- `$UNTRUSTED` boundaries on targeted entry points (install, login, select_agent, admin, auth paths, layouts, UrlResolver)
- Database portability: `information_schema` instead of MySQL-only `SHOW TABLES LIKE` where refactored
- Locale: `lupo_t()` on targeted UI surfaces + `en.php` keys
- PHP tiered compatibility documented (7.4+ 64-bit production, 5.6+ legacy path)

**Next:** Build package per **`SOFTACULOUS_PACKAGE_BUILD.md`**, test on Linux shared-host-class environment, close **Phase 7** items in **`PLAN.md`**. See **`VERSION_SUMMARY.md`** for a short rollup.

## Version lineage (documentation)

| Field | Value |
|--------|--------|
| `current_version` | 4.0.94 |
| `parent_version` | 4.0.93 |
| `child_version` | 4.0.95 (planning folder) |
| `superseded_by` | *(null until this line is frozen and replaced)* |
| `is_deleted` | 0 |

When a new working version folder is created, update `child_version` on 4.0.93/4.0.94 as appropriate and set `superseded_by` on the older **working** tree. Frozen version folders are not rewritten; lineage is forward-only.

## Canonical authority for PRD 30 / PRD 31

| File | Status | Lineage (explicit parent) |
|------|--------|----------------------------|
| `docs/prd/30_channel_usage_patterns.md` | **CANONICAL** | Inherits requirements style from [PRD 17](../../prd/17_decisions_format.md) (thread/decision discipline) |
| `docs/prd/31_implementation_folder_guidelines.md` | **CANONICAL** | Inherits channel/thread semantics from [PRD 02](../../prd/02_channels_discussions.md) |
| `docs/versions/4.0.94/prd/30_prd_development_guide.md` | **WORKING COPY** | Parent: canonical PRD 30 (`30_channel_usage_patterns.md`) — rewrite targets PRD *writing* guide |
| `docs/versions/4.0.94/prd/31_context_system.md` | **WORKING COPY** | Parent: canonical PRD 31 (`31_implementation_folder_guidelines.md`) — redesign; must align with [PRD 26](../../prd/26_five_layer_documentation_architecture.md) |

**Rule:** Edits that change **normative** behavior belong in **WORKING COPY** until reviewed; **promotion** replaces or extends **CANONICAL** under `docs/prd/` only with an explicit **APPROVED** decision (or recorded alternate canonical path). Do not treat both trees as equal authority.

## Layout

Same structure as 4.0.93: `PLAN.md`, `TODO.md`, `CHANGELOG.md`, `edges.md`, `decisions/`, `questions/`, `answers/`, `comments/`, plus **`prd/`** for working PRDs and **`session_changelog/`** for session-scoped logs (see below).

**Thread outcome (2026-04-02 UTC, Cursor):** IDE facet thin packs, VS Code rule propagation (`--target=vscode`), and registry/documentation alignment are recorded under **`CHANGELOG.md`**, **`PLAN.md` Phase F**, and **`decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md`**.

**Thread outcome (2026-04-03 UTC, Cursor):** **PRD 33** Softaculous / **4.1.0** gate — **`lupopedia.headers.status: approved`**; **`CHANGELOG.md`** (top entry), **`PLAN.md`** Phase D, **`TODO.md`** (doc vs execution), **`edges.md`**, and **`decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md`** (+ Q/A/C **`022544`–`022546`**). Implementation hub: **`docs/implementations/33_softaculous_certification_4_1_0_gate/`**.

**Thread outcome (2026-04-03 UTC, Cursor, later pass):** **PRD 31** — **LILITH** final audit merged into canonical **`docs/prd/31_implementation_folder_guidelines.md`** (header stamps **`20260403024822`**); **`CHANGELOG.md`** (new top entry), **`PLAN.md`** Phase C row **C-FW-4**, **`TODO.md`** (handoff + completed sync line), **`edges.md`**, **`WHAT_TO_WORK_ON_NEXT_SESSION.md`**, **`decisions/20260403_025155_DECISION_APPROVED_prd31_lilith_final_audit_version_sync.md`** (+ Q/A/C **`025156`–`025158`**).

**Thread outcome (2026-04-03 UTC, Cursor + LILITH framing):** Doctrine PRD-lineage audit (**189** files, **0** missing — `audit_doctrine_prd_edges.py`); version-ghost report (**34** critical files — `version_ghosts_report.json`); mobile/workflow doctrines and related PRD/AGENTS/LESSONS updates; **`CHANGELOG.md`** (entry **`20260403140552`** batch), **`PLAN.md`** Phase **G**, **`TODO.md`**, **`edges.md`**, **`decisions/20260403_140552_DECISION_APPROVED_doctrine_audit_mobile_separation_docs.md`**, Q/A **`140553`–`140554`**, comment **`140555`**.

**Thread outcome (2026-04-04 UTC, Cursor):** **Service agents** + **PRD 00 §5** (KAIROS, THOTH, roster **IRIS/ANUBIS/ROSE/THOTH/KAIROS**, runtime-loop contrast) + **`SERVICE_AGENT_ARCHITECTURE.md`** + **`implementations/service_agents/`** + **LUPOPEDIA_HEADERS** THOTH grounding + **Softaculous** auto-installer spec/sample config/packager excludes/bootstrap **`mkdir`**; **`CHANGELOG.md`** (new top entry), **`PLAN.md`** Phase **J**, **`TODO.md`** completed-documentation rows, **`edges.md`**, **`decisions/20260404_161001_…`**, comment **`161001`**, Q/A **`161004`–`161005`**.

## Thread filename TYPE tokens

**Authoritative:** [PRD 17 — Thread filename pattern (authoritative)](../../prd/17_decisions_format.md#thread-filename-pattern-authoritative).

For **new** threads under `decisions/`, `questions/`, `answers/`, and `comments/`, use only these **TYPE** tokens in filenames:

- `DECISION` (with **STATUS** only under `decisions/`, per PRD 17)
- `QUESTION`
- `ANSWER`
- `COMMENT`

Use **sparingly** and only when PRD 17 allows: e.g. `PROPOSAL`, `CLARIFICATION`, `RESOLUTION` in decision-class filenames.

Legacy types such as `DIALOG` or `DIRECTIVE` may appear in **historical** artifacts and older README tables; they are **not** permitted for **new** thread files in 4.0.94+.

## Session changelog (deterministic)

Multi-agent traces use **session-scoped** files under [`session_changelog/README.md`](session_changelog/README.md): UTC **BIGINT** timestamps in body fields, UTC filename timestamps, **`is_deleted`** on each log file, **no** calendar-day aggregation requirement. Narrative release history remains in [`CHANGELOG.md`](CHANGELOG.md).

## Working PRDs (this folder)

- `prd/30_prd_development_guide.md` — **WORKING COPY**; rewrite as PRD writing guide; promote per table above
- `prd/31_context_system.md` — **WORKING COPY**; redesign without parallel taxonomy; align with PRD 26

## Channels on disk

- **Archive:** `channels_before_4_0_93/` — legacy channel files (read-only reference). It is **not** a full migration target; use **new** threads under `channels/` for documentation-system work and organization.
- **Layout PRD:** `docs/prd/29_project_structure.md` — top-level directory map (includes the archive row).
- **Channel PRD:** `docs/prd/02_channels_discussions.md` — threads, discussions, coordination semantics.
