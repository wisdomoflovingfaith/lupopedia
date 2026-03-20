---
lupopedia.init:
  required_reading:
    - path: "AGENTS.md"
      reason: "Canonical agent coordination and faucet/orchestrator boundaries."
    - path: "CHANGELOG.md"
      reason: "Most complete execution history and 4.0.84 active scope."
    - path: "TODO.md"
      reason: "Live coordination queue and active blockers."
    - path: "plan.md"
      reason: "Dependency-ordered execution roadmap."
    - path: "README.md"
      reason: "Root project contract and active doctrine framing."
    - path: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md"
      reason: "Canonical serialization, block order, and baseline rewrite rules."
    - path: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md"
      reason: "DB<->YAML mapping model and canonical block ordering."
    - path: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md"
      reason: "Optional block semantics and precedence rules."
    - path: "lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md"
      reason: "Mandatory rewrite-on-write baseline for pre-4.0.84 headers."
  required_context:
    - "Lupopedia 4.0.84 is the active line; no Lupopedia-to-Lupopedia upgrade path until 4.1.0."
    - "Header serialization keys in files must be lupopedia.* only; conceptual Lupopedia.* remains prose-only."
    - "Schema/table names and columns must be TOON-grounded from canonical JSON/SQL sources."

lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "documentation"
  file_path_from_root: "HANDOFF_TO_WINDSURF.md"
  web_path: "http://www.lupopedia.com/HANDOFF_TO_WINDSURF"
  last_modified_utc: "20260320"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "handoff"
  artifact_kind: "status_report"
  title: "Cursor to Windsurf Handoff"
  purpose: "Comprehensive handoff of active 4.0.84 work, current state, risks, and dependency-ordered takeover plan."
  tags: ["handoff", "windsurf", "cursor", "4.0.84", "status", "takeover"]

lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  session_name: "Cursor Root Handoff Session"
  actor_id: 102
  actor_name: "cursor"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  federation_node_id: 1
  context_source: "ide_runtime"
  department_id: 0
  thread_id: 1001
  agent_name: "cursor"
  actor_type: "agent"
  actor_nature: "ide"
  human_actor_name: "root"
  paired_actor_id: 10000
  embedded_session_snapshot: true

lupopedia.actor_references:
  comment: "Actor IDs per canonical registry and AGENTS guidance."
  cursor: 102
  windsurf: 101
  wolfie: 1
  lilith: 2
  hephaestus: 3
  athena: 4
  hermes: 15

lupopedia.edges:
  comment: "Snapshot of relevant coordination, doctrine, and implementation edges for Windsurf takeover."
  meta: "Thread continuation and ownership transfer from Cursor to Windsurf at 4.0.84."
  outbound_edges:
    documentation:
      - { to: "AGENTS.md", type: "references", weight: 1.0, reason: "Agent identity and coordination model." }
      - { to: "README.md", type: "references", weight: 1.0, reason: "Root project status and constraints." }
      - { to: "CHANGELOG.md", type: "references", weight: 1.0, reason: "Canonical execution record." }
      - { to: "TODO.md", type: "references", weight: 1.0, reason: "Live task registry and blockers." }
      - { to: "plan.md", type: "references", weight: 1.0, reason: "Dependency-ordered execution plan." }
      - { to: "directives.md", type: "references", weight: 0.9, reason: "Root directive continuity and baseline rewrite alignment." }
    doctrine:
      - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 1.0, reason: "Canonical headers doctrine index." }
      - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md", type: "references", weight: 1.0, reason: "Serialization and field requirements." }
      - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md", type: "references", weight: 1.0, reason: "DB<->YAML mapping and block model." }
      - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md", type: "references", weight: 0.95, reason: "Optional block normalization and precedence." }
      - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md", type: "references", weight: 0.9, reason: "Validator/tooling behavior and caveats." }
      - { to: "lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md", type: "references", weight: 1.0, reason: "Mandatory 4.0.84 baseline rewrite policy." }
    implementation:
      - { to: "lupo-scripts/import_content.py", type: "implements", weight: 1.0, reason: "Deterministic content ingestion with doctrine-safe DB logic." }
      - { to: "lupo-scripts/db_config.py", type: "references", weight: 0.9, reason: "Config-backed DB connection helper used by Python scripts." }
      - { to: "lupo-database/lupopedia/json/lupo_contents.json", type: "schema_reference", weight: 1.0, reason: "Canonical lupo_contents column source used for script alignment." }
      - { to: "lupo-database/lupopedia/json/lupo_metadata.json", type: "schema_reference", weight: 1.0, reason: "Canonical lupo_metadata column source used for mapping work." }
  semantic_tags: ["handoff", "ownership_transfer", "doctrine", "scripts", "schema", "4.0.84"]

lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Windsurf validates import_content.py against doctrine and sample fixtures."
    - "Windsurf implements generate_headers_from_db.py using TOON-grounded schema and deterministic mapping."
    - "Windsurf updates CHANGELOG/TODO/plan after takeover execution checkpoints."

lupopedia.next_actions:
  next_actions:
    - "Confirm repository state and preserve current dirty worktree without reverting unrelated changes."
    - "Run focused verification of import_content.py behavior: mismatch warnings, strict timestamp failures, update path correctness."
    - "Implement generate_headers_from_db.py with file-path/content-id resolution and deterministic block reconstruction."
    - "Add dry-run review output and fail-loud conflict handling for DB record mismatches and ambiguous metadata."
    - "Document Windsurf takeover progress in CHANGELOG.md, TODO.md, and plan.md with doctrine-aligned language."
---
# file: Cursor to Windsurf Handoff — session: Cursor Root Handoff Session — delegation: cursor:root — web_path: http://www.lupopedia.com/HANDOFF_TO_WINDSURF

# Cursor to Windsurf Handoff (4.0.84)

## Where the project is now

- Lupopedia is in active `4.0.84` development with doctrine-heavy stabilization and channel 66/88 semantic validation still active.
- LUPOPEDIA HEADERS doctrine was significantly tightened: single canonical version field (`version_when_written`), baseline rewrite-on-write behavior, and explicit glue-layer mapping rules.
- Root docs were updated to reflect current doctrine and active work status; this includes recent updates to `CHANGELOG.md`, `TODO.md`, and `plan.md`.
- `import_content.py` is implemented and now doctrine-hardened, but broader DB-driven header regeneration is not yet complete.
- `generate_headers_from_db.py` is still pending implementation (scope prepared, schema confirmed from TOON JSON sources).

## What Cursor completed in this thread

## 1) Header baseline enforcement pass

- Per request, top-level Markdown in repo root and `lupo-docs/` root were aligned to LUPOPEDIA `4.0.84` baseline behavior.
- Files with existing front matter had header version fields aligned to `version_when_written: "4.0.84"`.
- Three `lupo-docs/` root files lacking front matter were given explicit LUPOPEDIA headers plus identity lines:
  - `lupo-docs/ACTOR_IDENTITIES.md`
  - `lupo-docs/CLOUDFLARE_INTEGRATION.md`
  - `lupo-docs/TOON_REFERENCE.md`

## 2) `import_content.py` doctrine hardening

Cursor implemented the requested non-final fixes:

- Removed MySQL-specific `ON DUPLICATE KEY UPDATE`.
- Replaced with application-layer flow:
  - `SELECT` by `content_id`
  - `UPDATE` if exists
  - `INSERT` if not
- Tightened `last_modified_utc` behavior:
  - present-but-invalid now fails loudly
  - missing/null still defaults deterministically to current UTC YmdHis
- Success output now occurs only after:
  - DB commit
  - file rewrite success
- Re-import update path now updates deterministic file-derived columns (not only body/content):
  - `title`, `slug`, `file_path_from_root`,
  - `file_last_modified_system_version`, `file_last_modified_utc`,
  - `channel_id`, `actor_id`,
  - `updated_ymdhis`, plus `body` and `content`
- Added mismatch warning when existing header `content_id` differs from deterministic recompute.
- Kept explicit TOON-driven column handling aligned to `lupo_contents`.

## 3) Handoff documentation updates

- `CHANGELOG.md`, `TODO.md`, and `plan.md` were updated to reflect:
  - root/lupo-docs header baseline alignment work
  - `import_content.py` hardening changes
  - in-progress status for `generate_headers_from_db.py`

## Known risks and caveats Windsurf should validate first

- Large repository-wide doc edits occurred; avoid broad reformat churn while taking over.
- Some legacy docs still contain old FLARE-era content conventions; do not assume uniform doctrine compliance outside the specific paths already normalized.
- `generate_headers_from_db.py` is not yet created; make no assumption that DB->YAML export path exists.
- Metadata linkage (`lupo_metadata`) may vary historically by `entity_type` usage; resolver logic must be deterministic and fail-loud on ambiguous matches.

## Windsurf takeover plan (dependency-ordered)

## Phase 1: Stabilize and verify current script state

Completion criteria:
- `import_content.py` behavior is validated on at least one known artifact in dry-run and non-dry-run modes.
- Failure modes are confirmed deterministic (invalid `last_modified_utc`, mismatched header `content_id`, missing required fields).

Actions:
- Review `import_content.py` and run dry-run checks on representative files.
- Confirm DB read/write path still uses explicit column set from `lupo_contents` TOON source.
- Confirm no vendor-specific upsert behavior remains.

## Phase 2: Implement `generate_headers_from_db.py`

Completion criteria:
- New script exists at `lupo-scripts/generate_headers_from_db.py`.
- Supports `--file-path`, `--content-id`, both, and `--dry-run`.
- Uses deterministic resolution and fail-loud mismatch behavior.

Actions:
- Resolve artifact from `lupo_contents` by:
  - both args: verify same row
  - content-only: derive file path from row
  - path-only: exact row lookup
- Query `lupo_metadata` deterministically using content-linked scope and ordered fallback rules.
- Build canonical `lupopedia.*` blocks in fixed order.
- Omit `lupopedia.session` by default.
- Normalize legacy `lupopedia.close` to `lupopedia.next_actions` in output by default.

## Phase 3: Deterministic YAML generation and file merge/write

Completion criteria:
- Front matter output is deterministic (stable block/key order).
- File rewrite preserves body and identity-line structure.
- Non-existent file creation path works with DB-provided body fallback.

Actions:
- Emit exactly one front matter block (`---` / YAML / `---`).
- Preserve existing body when file exists.
- Generate identity line in ordinary form (no session by default).
- Ensure URLs are plain scalar strings (no markdown link syntax in YAML).

## Phase 4: Closeout and continuity

Completion criteria:
- Script behavior documented in root project tracking.
- Handoff complete and Windsurf is active maintainer for this workstream.

Actions:
- Update `CHANGELOG.md`, `TODO.md`, and `plan.md` with execution evidence.
- Add/refresh relevant tests or fixture checks for deterministic output.
- Keep doctrine references current if any mapping assumptions are formalized during implementation.

## Immediate takeover command intent

Windsurf should take ownership of:

1. `lupo-scripts/generate_headers_from_db.py` implementation (primary).
2. Follow-up verification and polishing for `lupo-scripts/import_content.py` (secondary).
3. Status bookkeeping in root docs (`CHANGELOG.md`, `TODO.md`, `plan.md`) as milestones close.

This handoff is complete from Cursor’s side; Windsurf can continue directly from the active 4.0.84 scope above.

