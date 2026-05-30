---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "docs/versions/4.0.77/PLAN.md"
  web_path: "[web_path](http://www.lupopedia.com/versions/4.0.77/PLAN)"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: plan
  artifact_kind: version_plan
  thread_id: ""
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
# file: Version 4.0.77 Plan — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/versions/4.0.77/PLAN

# Lupopedia 4.0.77 — Remaining Implementation Plan

## Phase 0 — Cross-agent coordination (4.0.77)

- **0.1 Coordination protocol and location**
  - Coordination artifacts for 4.0.77 live under: `database/coordination/4.0.77/`
  - Each shared task has a handshake file describing ownership, validator, status, timestamps, and notes.

- **0.2 Task ownership matrix (initial)**
  - 1.1–1.4 (header doctrine / init discipline / snapshot semantics / scoped header fixes): **Owner = cursor (102)**, **Validator = antigravity (103)**, handshake `header-doctrine.status`
  - 2.1 (header validator): **Owner = cursor (102)**, **Validator = windsurf (101)**, handshake `header-validator.status`
  - 2.2 (header export): **Owner = cursor (102)**, **Validator = windsurf (101)**, handshake `header-export.status`
  - 2.3 (header import): **Owner = cursor (102)**, **Validator = windsurf (101)**, handshake `header-import.status`
  - 3.1–3.4 (Bayesian foundation alignment / TOON consistency / acceptance criteria): **Owner = windsurf (101)**, **Validator = cursor (102)**, handshake `bayesian-foundation.status`
  - 4.1–4.3 (upgrade validation reporting): **Owner = cursor (102)**, **Validator = antigravity (103)**, handshake `upgrade-validation.status`
  - 5.x (final doctrine / planning / changelog truth alignment): **Owner = cursor (102)**, **Validator = antigravity (103)**, handshake `truth-alignment.status`

- **0.3 Handshake file structure**
  - Handshake files are simple YAML documents with fields: `task_id`, `task_name`, `status`, `owner`, `validator`, `started_by`, `completed_by`, `validated_by`, `blocked_by_task_id`, `notes`.
  - Status vocabulary: `not-started`, `in-progress`, `complete`, `blocked`, `validated`.

- **0.4 Workflow rules**
  - No agent starts a shared task without marking the handshake file `in-progress`.
  - No agent marks a task `complete` without adding notes.
  - Validators must explicitly set `status: validated` and fill `validated_by`.
  - Blocked tasks must set `status: blocked` and populate `blocked_by_task_id`.
  - Conflicts or deadlocks escalate in channel 42 via existing governance (LILITH / Captain) and are reflected in `notes`.

## Phase 1 — LUPOPEDIA HEADERS enforcement and doctrine alignment

- **1.1 Header doctrine review and consolidation**
  - Verify that `LUPOPEDIA_HEADERS_PLAN.md`, `LUPOPEDIA_HEADERS_FORMAT.md`, `LUPOPEDIA_HEADERS/README.md`, `OPTIONAL_BLOCKS.md`, and `LUPOPEDIA_HEADERS_AND_METADATA_BRIDGE.md` fully describe:
    - Canonical block order (including `lupopedia.routing`, `lupopedia.session`, `lupopedia.edges`, `lupopedia.engagement`, `lupopedia.footer`, `lupopedia.see`, `lupopedia.next_actions`).
    - Snapshot semantics for `lupopedia.edges` and `lupopedia.engagement` (mandatory `comment`, recommended `meta`).
    - Correct separation of `lupopedia.headers` (artifact metadata) vs `lupopedia.session` (runtime context).

- **1.2 `lupopedia.init` discipline**
  - Enforce the rule that `lupopedia.init` is used only for **required_reading** / **required_context** and *not* for file metadata.
  - Identify Markdown artifacts that still misuse `lupopedia.init` for metadata and schedule migrations to move those fields into `lupopedia.headers` or `lupopedia.metadata`.

- **1.3 Minimal header presence for new/edited files**
  - Ensure all new or recently edited Markdown files in 4.0.77 have:
    - A `lupopedia.init` block with at least a comment or minimal required_reading list.
    - A `lupopedia.headers` block with required core fields (version, schema, file_path_from_root, web_path, last_modified_utc, system_version, channel_id, actor_id, delegation_chain, artifact_type, artifact_kind, purpose).
  - Defer bulk migration of legacy files to a separate targeted pass (documented in TODO.md).

## Phase 2 — Header generation and sync tooling

- **2.0 File-based export/import (done 4.0.77)**  
  - `headers export` and `headers import` in lupo.php; `export_lupopedia_headers.php`, `import_lupopedia_headers.php`. Operate on files only; round-trip documented in `tests/fixtures/headers/README.md`. **lupo_metadata** (DB ↔ YAML) sync remains below.

- **2.1 Header export (DB → YAML)** *(deferred)*
  - Design and implement a PHP or Python tool (e.g. `scripts/export_headers_from_db.php` or `export_headers_from_db.py`) that:
    - Reads header metadata from `lupo_metadata` (+ `lupo_edges` / engagement source where used).
    - Emits canonical LUPOPEDIA HEADERS YAML into target `.md` files (single `---` block, correct block order).
    - Preserves existing body content and identity line.

- **2.2 Header import (YAML → DB)** *(deferred)*
  - Implement the inverse tool (e.g. `scripts/sync_metadata_from_headers.php` referenced in `plan.md`):
    - Parses LUPOPEDIA HEADERS from Markdown files (including grouped `outbound_edges`).
    - Updates `lupo_metadata` and `lupo_edges` rows according to the row-based model (`channel_id`, `parent_metadata_id`, `class_name`).
    - Honors snapshot semantics and does not overwrite live engagement/edges without explicit flags.

- **2.3 Validator and CI wiring**
  - Extend existing validators (PHP or Python) so that:
    - New/modified docs can be checked for header compliance (required blocks/fields, snapshot comments, canonical block order).
    - Grouped `outbound_edges` structures are accepted and normalized.
  - Add a simple CLI entry (e.g. `php bin/lupo.php headers:validate`) for local runs and CI.

## Phase 3 — Bayesian Decision Tracking foundation integration

- **3.1 Doctrine and planning alignment**
  - Ensure `BAYESIAN_DECISION_DOCTRINE.md`, `bayesian_decision_tracking_PLAN.md`, and `bayesian_decision_tracking_TASKS.md`:
    - Clearly state that 4.0.77 includes **required schema + doctrine foundation** in `install_new_lupopedia.sql`.
    - Defer engine, integration, CLI/API, and analytics work to later phases, with explicit dependency ordering.

- **3.2 Engine scaffolding (future phase)**
  - Define minimal interfaces for a decision engine (e.g. `BayesianDecisionEngine` / `BayesianTraversalEngine`) that:
    - Record decisions into `lupo_decisions` with registry-based IDs.
    - Navigate parent/child and influence relationships using `lupo_decision_edges` and `lupo_decision_influences`.
  - Leave detailed implementation and wiring to a subsequent 4.0.77+ pass (tracked in TODO.md).

## Phase 4 — Crafty Syntax 3.7.5 → Lupopedia 4.0.77 validation

- **4.1 Execution of upgrade validation** *(done 2026-03-16)*
  - Run the documented drop → import Crafty 3.7.5 → install Lupopedia 4.0.77 → validate sequence.
  - Recorded in `CRAFTY_3_7_5_TO_4_0_77_UPGRADE_VALIDATION.md`; 161 tables; coordination status validated.

- **4.2 Regression coverage**
  - Ensure that existing upgrade validation scripts and tests (from 4.0.74–4.0.76) are updated where paths or doctrine have changed (e.g. TOON path, future_features, headers).

## Phase 5 — Coordination across IDE agents

- **5.0 Zencoder integration (done 4.0.77)**  
  - Zencoder (actor_id 106) workspace under `actors/zencoder/` and four development table docs (lupo_analytics_campaign_vars, lupo_world_registry, lupo_auth_audit_log, lupo_channel_boot_detail) plus seed_actor_zencoder_4.0.77.sql committed and pushed by Cursor on Zencoder's behalf after git failure. Canonical directories use the **** prefix (actors, docs, database, etc.).

- **5.1 Shared understanding**
  - Use this PLAN and the companion 4.0.77 TODO to coordinate Cursor, Windsurf, Antigravity, and Zencoder work:
    - Cursor: lead orchestration, header/tooling implementation, Bayesian foundation docs.
    - Windsurf: schema/TOON verification and database tooling alignment.
    - Antigravity: governance and enforcement for headers and migrations.

- **5.2 Handoff and reporting**
  - For each completed sub-phase, add or update a concise status artifact under `docs/status/` and reference it from CHANGELOG.md in a follow-up 4.0.77 entry if needed.

- **5.3 Table documentation initiative — 4.0.77 stop line (done)**
  - Cursor continued the Zencoder → Windsurf table-doc workstream: improved lupo_sessions and lupo_contents with 4.0.77 headers and "Where This Table Is Used"; created `docs/status/TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md` defining 4.0.77 accomplishment and 4.0.78 handoff. Remaining table-doc modernization (e.g. lupo_channels, lupo_actors, Priority 2/3 tables, mass header cleanup) deferred to 4.0.78. Pattern and priorities documented in stop-line artifact.

