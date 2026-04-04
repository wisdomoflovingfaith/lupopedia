---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  version_when_written: "4.0.94"
  when_updated: "20260404161001"
  file_path_from_root: "lupo-docs/versions/4.0.94/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/README.md"
  last_modified_utc: "20260404161001"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-readme"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "version_readme"
  purpose: "Working version folder for Lupopedia 4.0.94 development"
  tags:
  - "version"
  - "4.0.94"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.93/README.md"
      type: references
      weight: 1.0
      reason: "Previous frozen release"
    - to: "lupo-docs/versions/4.0.94/PLAN.md"
      type: references
      weight: 1.0
      reason: "Current plan"
    - to: "lupo-docs/versions/4.0.94/prd/"
      type: references
      weight: 0.95
      reason: "Working PRDs (30, 31)"
    - to: "lupo-docs/prd/29_project_structure.md"
      type: references
      weight: 0.9
      reason: "Project layout including lupo-channels vs archive"
    - to: "lupo-docs/versions/4.0.94/decisions/20260404_200000_DECISION_APPROVED_documentation_coordination_channel_semantic_mood_rgb.md"
      type: references
      weight: 1.0
      reason: "APPROVED 5W1H decision for this release-line documentation work"
    - to: "lupo-docs/versions/4.0.94/session_changelog/README.md"
      type: references
      weight: 0.95
      reason: "Session-scoped deterministic changelog convention (no calendar-day aggregation)"
    - to: "lupo-docs/versions/4.0.94/decisions/20260402_225223_DECISION_APPROVED_cursor_thread_identity_temporal_docs.md"
      type: references
      weight: 1.0
      reason: "APPROVED Cursor thread — identity + temporal + version sync scope"
    - to: "lupo-docs/versions/4.0.94/decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md"
      type: references
      weight: 1.0
      reason: "APPROVED IDE facet packs + VS Code propagation + registry doc alignment"
    - to: "lupo-docs/versions/4.0.94/decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md"
      type: references
      weight: 1.0
      reason: "APPROVED PRD 33 Softaculous / 4.1.0 gate documentation + version sync"
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: references
      weight: 1.0
      reason: "Gate PRD (status approved)"
    - to: "lupo-docs/versions/4.0.94/decisions/20260403_025155_DECISION_APPROVED_prd31_lilith_final_audit_version_sync.md"
      type: references
      weight: 1.0
      reason: "APPROVED PRD 31 LILITH final audit + version sync"
    - to: "lupo-docs/versions/4.0.94/decisions/20260403_140552_DECISION_APPROVED_doctrine_audit_mobile_separation_docs.md"
      type: references
      weight: 1.0
      reason: "APPROVED doctrine audit + mobile/workflow documentation (5W1H)"
    - to: "lupo-docs/versions/4.0.94/WHAT_TO_WORK_ON_NEXT_SESSION.md"
      type: references
      weight: 0.95
      reason: "Next-session prioritized backlog"
    - to: "lupo-docs/versions/4.0.94/decisions/20260404_161001_DECISION_APPROVED_service_agent_architecture_and_softaculous_auto_installer_docs.md"
      type: references
      weight: 1.0
      reason: "APPROVED receipt — service agents + PRD 00 §5 + Softaculous auto-installer docs"
lupopedia.footer:
  last_verified: "20260404161001"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/README.md — delegation: cursor:root

# Lupopedia 4.0.94 (working)

This is the **active** version documentation directory. Release **4.0.93** is frozen under `lupo-docs/versions/4.0.93/`.

## Version lineage (documentation)

| Field | Value |
|--------|--------|
| `current_version` | 4.0.94 |
| `parent_version` | 4.0.93 |
| `child_version` | *(none yet)* |
| `superseded_by` | *(null until 4.0.95 or next line exists)* |
| `is_deleted` | 0 |

When a new working version folder is created, update `child_version` on 4.0.93/4.0.94 as appropriate and set `superseded_by` on the older **working** tree. Frozen version folders are not rewritten; lineage is forward-only.

## Canonical authority for PRD 30 / PRD 31

| File | Status | Lineage (explicit parent) |
|------|--------|----------------------------|
| `lupo-docs/prd/30_channel_usage_patterns.md` | **CANONICAL** | Inherits requirements style from [PRD 17](../../prd/17_decisions_format.md) (thread/decision discipline) |
| `lupo-docs/prd/31_implementation_folder_guidelines.md` | **CANONICAL** | Inherits channel/thread semantics from [PRD 02](../../prd/02_channels_discussions.md) |
| `lupo-docs/versions/4.0.94/prd/30_prd_development_guide.md` | **WORKING COPY** | Parent: canonical PRD 30 (`30_channel_usage_patterns.md`) — rewrite targets PRD *writing* guide |
| `lupo-docs/versions/4.0.94/prd/31_context_system.md` | **WORKING COPY** | Parent: canonical PRD 31 (`31_implementation_folder_guidelines.md`) — redesign; must align with [PRD 26](../../prd/26_five_layer_documentation_architecture.md) |

**Rule:** Edits that change **normative** behavior belong in **WORKING COPY** until reviewed; **promotion** replaces or extends **CANONICAL** under `lupo-docs/prd/` only with an explicit **APPROVED** decision (or recorded alternate canonical path). Do not treat both trees as equal authority.

## Layout

Same structure as 4.0.93: `PLAN.md`, `TODO.md`, `CHANGELOG.md`, `edges.md`, `decisions/`, `questions/`, `answers/`, `comments/`, plus **`prd/`** for working PRDs and **`session_changelog/`** for session-scoped logs (see below).

**Thread outcome (2026-04-02 UTC, Cursor):** IDE facet thin packs, VS Code rule propagation (`--target=vscode`), and registry/documentation alignment are recorded under **`CHANGELOG.md`**, **`PLAN.md` Phase F**, and **`decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md`**.

**Thread outcome (2026-04-03 UTC, Cursor):** **PRD 33** Softaculous / **4.1.0** gate — **`lupopedia.headers.status: approved`**; **`CHANGELOG.md`** (top entry), **`PLAN.md`** Phase D, **`TODO.md`** (doc vs execution), **`edges.md`**, and **`decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md`** (+ Q/A/C **`022544`–`022546`**). Implementation hub: **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/`**.

**Thread outcome (2026-04-03 UTC, Cursor, later pass):** **PRD 31** — **LILITH** final audit merged into canonical **`lupo-docs/prd/31_implementation_folder_guidelines.md`** (header stamps **`20260403024822`**); **`CHANGELOG.md`** (new top entry), **`PLAN.md`** Phase C row **C-FW-4**, **`TODO.md`** (handoff + completed sync line), **`edges.md`**, **`WHAT_TO_WORK_ON_NEXT_SESSION.md`**, **`decisions/20260403_025155_DECISION_APPROVED_prd31_lilith_final_audit_version_sync.md`** (+ Q/A/C **`025156`–`025158`**).

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

- **Archive:** `lupo-channels_before_4_0_93/` — legacy channel files (read-only reference). It is **not** a full migration target; use **new** threads under `lupo-channels/` for documentation-system work and organization.
- **Layout PRD:** `lupo-docs/prd/29_project_structure.md` — top-level directory map (includes the archive row).
- **Channel PRD:** `lupo-docs/prd/02_channels_discussions.md` — threads, discussions, coordination semantics.
