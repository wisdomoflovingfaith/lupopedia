---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  system_version: "4.0.80"
  file_path_from_root: "lupo-channels/66/threads/1001/20260319_100000_hephaestus_implementation_start_p0_bounded_authority_ingestion_exec.md"
  web_path: "http://www.lupopedia.com/lupo-channels/66/threads/1001/20260319_340000_hephaestus_implementation_start_p0_bounded_authority_ingestion_exec"
  last_modified_utc: "20260319"
  channel_id: 66
  thread_id: 1001
  actor_id: 3
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:root"
  artifact_type: "thread"
  artifact_kind: "implementation_execution_start"
  purpose: "Implementation-start artifact: first-pass build-out of Channel 66 Thread 1001 P0 bounded-authority header ingestion (TOON validation, field preservation, deterministic lupo_metadata projection, and concurrency-safe writes)."
  traits: ["implementation_start", "p0_ingestion", "bounded_authority", "channel66", "thread1001", "validation", "projection"]
  tags: ["bounded_authority", "compatibility_matrix", "toon_validation", "field_preservation", "deterministic_projection", "concurrent_edit_detection"]
  message_type: "implementation_start"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/66/threads/1001/20260319_090000_wolfie_header_version_compatibility_matrix_thread1001.md", type: "implements", weight: 1.0, reason: "Exact header version compatibility rules used by bounded-authority P0 validation" }
    - { to: "lupo-channels/66/threads/1001/20260319_030000_hephaestus_implementation_plan_revised_p0_bounded_authority_ingestion.md", type: "derived_from", weight: 1.0, reason: "Execution scope and build order converted from the approved implementation plan" }
    - { to: "lupo-channels/66/threads/1001/20260319_020000_lilith_implementation_gate_revised_p0_ingestion_design.md", type: "constrains", weight: 0.95, reason: "P0 safety and reject/warn/concurrency semantics gate constraints" }
    - { to: "lupo-channels/66/threads/1001/20260319_010000_hephaestus_p0_ingestion_design_revised_bounded_authority.md", type: "references", weight: 0.9, reason: "Revised P0 ingestion design with bounded header authority layers (TOON, field matrix, conflict detection)" }
    - { to: "lupo-channels/66/threads/1001/20260319_000000_hephaestus_p0_header_ingestion_design_channel66.md", type: "references", weight: 0.8, reason: "Baseline Channel 66 filesystem-only ingestion scope and metadata-only indexing assumption" }
    - { to: "lupo-channels/66/threads/1002/20260319_020000_wolfie_response_lilith_attack_authority_hierarchy_revision.md", type: "constrains", weight: 0.9, reason: "Authority hierarchy precedence (install SQL and TOON domains, header boundedness, and conflict resolution priority)" }
    - { to: "lupo-channels/66/threads/1002/20260319_040000_hephaestus_implementation_evidence_bounded_header_authority.md", type: "references", weight: 0.95, reason: "Implementation evidence: conflict detection P0 requirements, field preservation matrix, and safe performance strategy" }
    - { to: "lupo-channels/66/threads/1002/20260319_050000_lilith_implementation_gate_hephaestus_bounded_authority.md", type: "implements", weight: 1.0, reason: "Bounded header authority implementation gate constraints applied to this first pass" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 1.0, reason: "Headers declare artifact; DB declares the world; relationship to collections/namespaces" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md", type: "references", weight: 0.95, reason: "Accepted LUPOPEDIA HEADERS validator behavior and required field/block ordering semantics" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md", type: "constrains", weight: 0.95, reason: "File format and required header fields (minimum required identity for P0 rejection rules)" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md", type: "references", weight: 0.9, reason: "Canonical row-based storage model for lupo_metadata (root -> block -> properties, repeating edge children)" }
    - { to: "lupo-rules/root/toon-source-of-truth.md", type: "defends", weight: 1.0, reason: "TOON files as structural schema truth derived from canonical install SQL" }
    - { to: "lupo-channels/66/threads/1001", type: "related_question", weight: 1.0, reason: "Thread 1001 context for Channel 66 filesystem-only question indexing" }
    - { to: "lupo-channels/66/threads/1002", type: "related_question", weight: 0.95, reason: "Bounded header authority constraints constrain version compatibility and TOON validation semantics" }

lupopedia.footer:
  version: "4.0.80"
  last_verified: "20260319"
  last_verified_by: "hephaestus"
  orchestrator: "hephaestus"
  next_action:
    - "HEPHAESTUS/tool owner: implement the first-pass bounded-authority ingestion runner and validators exactly per this artifact"
    - "Thread 1001: run fixture suite and confirm deterministic P0 outcomes (ingested/rejected/conflict_flagged)"
---

# file: HEPHAESTUS Implementation Start — P0 Bounded-Authority Ingestion (First Pass) — session: L-LUPO-ROOT-HEPHAESTUS — delegation: hephaestus:root — web_path: http://www.lupopedia.com/lupo-channels/66/threads/1001/20260319_340000_hephaestus_implementation_start_p0_bounded_authority_ingestion_exec

## 1. Implementation-Start Verdict
Implementation can begin without ambiguity for this first pass.

Rationale: Thread 1001 has (a) an approved revised bounded-authority P0 ingestion design (250000), (b) an approved implementation-gate review that confirms reject/warn/concurrency placement (260000), and (c) a locked header version compatibility matrix with exact accept/warn/reject outcomes for P0 and P1 version scenarios (330000).

This artifact converts those into a narrow, implementable build-out that only targets Channel 66 and only performs deterministic `lupo_metadata` projection (edges stored inside `lupo_metadata`; `lupo_edges` projection is deferred).

## 2. First-Pass Build Scope
This first pass is explicitly limited to the following pipeline and outputs.

Included:
1. File discovery (Channel 66 only)
   - Scan only `lupo-channels/66/**/*.md`.
   - Determinism: sort file paths lexicographically and process in that order.
2. YAML/front-matter parse (LUPOPEDIA HEADERS block extraction)
   - For each `.md` candidate, extract the first YAML front-matter block delimited by the first opening `---` line and its closing `---` line.
   - Parse YAML into a structured PHP array using `yaml_parse($yaml)` when available; if not available, reject as malformed (`yaml_parse_unavailable`).
   - Preserve parse failure details for error-state projection.
3. Structural validation (P0 gate)
   - Reuse baseline checks by calling `php lupo-bin/lupo.php headers validate <path>` or by directly calling `validate_lupopedia_headers($path)` when available.
   - Then apply P0-specific required fields enforcement (per `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md` minimum required identity fields):
     - `lupopedia.version`, `lupopedia.schema`, `file_path_from_root`, `web_path`, `last_modified_utc`, `system_version`,
     - `channel_id`, `actor_id`, `delegation_chain`, `artifact_type`, `artifact_kind`, `purpose`.
   - Canonical YAML block order enforcement is done at the string/parse stage (do not rely on YAML reordering).
4. Version compatibility validation (locked matrix)
   - Apply Thread 1001 compatibility matrix from `20260319_090000_wolfie_header_version_compatibility_matrix_thread1001.md`.
   - Outcomes must be exactly:
     - ACCEPT => proceed as P0-valid
     - WARN (minor version newer) => proceed but mark P1 warning
     - REJECT (major mismatch, missing/malformed versions) => P0 reject
5. TOON/schema validation (structural truth enforcement)
   - Load TOON schemas from canonical directory `lupo-database/lupopedia/toon/`.
   - Required for first pass:
     - Load and parse `lupo_metadata.toon`.
   - Validate that the TOON schema contains the columns required by the projection logic:
     - `metadata_id`, `entity_type`, `entity_id`, `domain_id`, `meta_type`, `property_key`, `property_value`,
       `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`,
       `channel_id`, `parent_metadata_id`, `class_name`, `schema_ref`.
   - If TOON load fails or required columns are missing: P0 reject for every file (run-level schema safety), with a deterministic error code.
   - TOON caching is allowed and required for performance (cache keyed by TOON file mtime within a single run).
6. Field preservation matrix application (Thread 1002)
   - Apply the Thread 1002 matrix to keys inside `lupopedia.headers`.
   - Projection encoding (deterministic, collision-safe):
     - Lossless fields: store property rows with `property_key` equal to the original field name.
     - Semantic-equivalence fields: store property rows with `property_key` equal to the original field name, with safe normalization allowed (e.g. tags sort/dedup, last_modified_utc canonical string).
     - Lossy/display-only fields: store with `property_key = "display__" . <original_field_name>`.
     - Never-projected elements: omitted from DB projection (no property rows created for those).
   - Non-`lupopedia.headers` blocks in scope for first pass:
     - `lupopedia.edges` (optional but expected by edge convention): store edge children inside `lupo_metadata` (not `lupo_edges`).
     - `lupopedia.footer` and `lupopedia.session`: store fields as display-only properties (prefixed `display__footer__` and `display__session__` respectively) to preserve information without treating it as authoritative.
7. Deterministic `lupo_metadata` projection (idempotent replace)
   - Compute per-file identity:
     - `entity_type = 'channel66_artifact'`.
     - `entity_id = deterministic_bigint_hash(file_path_from_root)` with the first-pass algorithm:
       - `entity_id = hexdec(substr(md5($file_path_from_root), 0, 15))` and ensure non-negative integer.
   - Idempotent replace semantics per file:
     - Soft-delete all existing `lupo_metadata` rows for `(entity_type='channel66_artifact', entity_id=:eid, domain_id IS NULL)` by setting `is_deleted=1` and `updated_ymdhis=now`.
     - Insert:
       - Root node row,
       - Block rows,
       - Property rows,
       - Repeating edge child nodes and their field properties.
   - All timestamps come from PHP: `gmdate('YmdHis')`.
8. Concurrent edit detection (P1 conflict; no silent overwrite)
   - Capture `filemtime` (or equivalent stable mtime) immediately after reading Step 1/2 content.
   - Immediately before DB write (after all validations pass), re-check current `filemtime`.
   - If mtime changed:
     - Do not overwrite authoritative rows for that file.
     - Insert a minimal conflict/error state projection (root row plus `validation_status=conflict_flagged`, `conflict_type=concurrent_edit` and conflict details as properties).
9. Logging / outcome reporting (required)
   - For each file, record a structured outcome:
     - `outcome`: `ingested` | `rejected` | `conflict_flagged`
     - `reject_type` or `warning_codes` or `conflict_type`
     - `validation_status_detail` (short code, not free-form narrative)
   - Logging destination for first pass:
     - Append JSONL to `lupo-logs/admin/` (existing directory).
     - Filename format: `YYYYMMDDHHIISS_channel66_thread1001_ingest_p0_bounded_authority.jsonl`.
   - Always print a concise summary to stdout on completion.

Not included in first pass:
 - DB -> YAML export (round-trip not implemented)
 - `lupo_edges` projection (optional and deferred)
 - Header vs DB divergence detection (P1) beyond concurrent edit detection
 - Cross-channel support beyond Channel 66
 - Incremental validation skip beyond full-run behavior (safe full re-run only)

## 3. Exact Module / File Touchpoints
The first pass creates the following code touchpoints (new files) and updates one CLI touchpoint (optional but recommended).

Create:
1. `lupo-scripts/ingest_channel66_headers_bounded_authority.php`
   - CLI entrypoint that runs the first-pass P0 bounded-authority ingestion.
   - Flags (first pass):
     - `--thread-id=1001` (default 1001)
     - `--mode=p0` (default p0)
     - `--edges=metadata_only` (default) with `--edges=lupo_metadata` synonym
     - `--scope-root=<path>` (test hook; default repo root)
     - `--toon-dir=<path>` (test hook; default `lupo-database/lupopedia/toon/`)
2. `lupo-includes/classes/Channel66HeaderIngester.php`
   - Owns orchestration of steps: discovery -> parse -> structural validation -> bounded-authority validation -> projection -> logging.
   - Has small “replace projection” methods to keep per-file logic deterministic.
3. `lupo-includes/classes/BoundedHeaderAuthorityValidator.php`
   - Implements:
     - P0 structural required fields checks
     - Locked compatibility-matrix version checks
     - TOON schema presence/column validation
     - Actor registry existence check (actor_id present -> must exist)
4. `lupo-includes/classes/ToonSchemaCache.php`
   - Loads `*.toon` files from canonical TOON directory and caches parsed schema signatures by (path, mtime).
   - Guarantees cache invalidation when TOON mtime changes within a run.
5. `lupo-includes/classes/HeaderFieldPreservationMatrix.php`
   - Implements Thread 1002 preservation matrix:
     - lossless / semantic-equivalence / lossy/display-only / never-projected classification
     - deterministic encoding rules for `property_key` names (including `display__` prefixes)
6. `lupo-includes/classes/Channel66HeaderProjection.php`
   - Implements deterministic lupo_metadata projection:
     - soft-delete replace semantics
     - row/tree construction (root -> blocks -> properties -> repeating edge nodes/children)
     - metadata_id allocation strategy within transaction
7. `lupo-includes/classes/Channel66IngestionLogger.php`
   - JSONL append-only logger for per-file outcomes.
   - Builds log record fields required by tests.

Update (optional, but recommended for discoverability):
8. `lupo-bin/lupo.php`
   - Add a new `headers` subcommand that dispatches to `ingest_channel66_headers_bounded_authority.php` (or at minimum document the direct script invocation).

Fixtures:
 - `lupo-tests/fixtures/channel66_ingestion/thread1001/`
   - Create fixtures for each reject/warn/conflict scenario listed in the test plan below.

Tests:
 - `lupo-tests/unit/channel66_bounded_authority_ingestion_p0_test.php` (new)
   - A plain PHP script that runs the ingestion runner in test mode against the fixtures root and asserts expected outcomes by querying `lupo_metadata`.

## 4. Execution Order
First-pass implementation must follow this exact order so that failures are deterministic and authority checks cannot be bypassed.

1. CLI argument parsing and run configuration
   - Determine scope root, toon dir, thread id, mode, and edges mode.
2. Discover files (deterministic scan list)
3. For each file: extract YAML front matter and parse YAML
4. Structural validation gate (P0 required fields)
   - Required blocks/fields missing => reject
   - Canonical block order violated (when detectable) => reject
5. Version compatibility validation (locked matrix)
   - ACCEPT => proceed
   - WARN => proceed and attach warning code(s)
   - REJECT => reject
6. Actor registry validation
   - If actor_id is present in header: must exist in canonical registry
   - Missing/unknown actor_id => reject (P0) for this first pass
7. TOON/schema validation (structural truth for projection safety)
   - Load `lupo_metadata.toon`, validate required columns exist
   - Cache TOON loads by (path, mtime)
   - Missing/inconsistent TOON => reject
8. Header vs TOON structural checks (bounded authority)
   - First pass scope for this check:
     - Validate that keys used by the projection mapping are safe to store (field preservation classification must be applied to all header keys present).
   - If classification cannot complete deterministically => reject.
9. Field preservation matrix application
   - Encode property keys using `display__` prefixes for lossy/display-only fields.
10. Concurrent edit detection immediately before DB write
   - Re-check mtime; if changed => conflict_flagged state projection (minimal) and skip authoritative content projection.
11. DB projection replace semantics
   - Soft-delete existing rows for the entity
   - Insert root/block/property/edge nodes and children
12. Logging and per-file outcome status emission
13. After processing all files: print summary and exit with non-zero only on hard runner failures (not per-file rejects).

## 5. Compatibility Matrix Implementation
This first pass must apply Thread 1001 compatibility matrix exactly as locked by `20260319_090000_wolfie_header_version_compatibility_matrix_thread1001.md`.

Input fields read:
 - `lupopedia.version` from `lupopedia.headers`
 - `system_version` from `lupopedia.headers`

Parsing:
 - Both must parse as `major.minor.patch` semantic versions.
 - Any malformed version string => REJECT.

Matrix rules (exact decisions):
1. ACCEPT:
   - `lupopedia.version == system_version == 4.0.80`
   - Minor version older:
     - header 4.0.79 vs system 4.0.80 => ACCEPT (proceed, no warning required)
2. WARN (P1):
   - Minor version newer:
     - header 4.0.81 vs system 4.0.80 => WARN (mark warning code; proceed with projection)
3. REJECT (P0):
   - Major version older:
     - header 4.0.7x vs system 4.0.80 => REJECT
   - Major version newer:
     - header 4.1.x vs system 4.0.80 => REJECT
   - Missing version fields => REJECT
   - Malformed version format => REJECT

What is logged:
 - Always log the scenario as one of: `accept_current`, `accept_minor_older`, `warn_minor_newer`, `reject_major_mismatch`, `reject_missing_version`, `reject_malformed_version`.
 - For WARN: add warning code to metadata:
   - `validation_warnings` property containing `deprecated_version_minor_newer`.

## 6. Reject / Warn / Conflict Handling
First-pass behavior must be deterministic and must not silently skip failures.

Outcome definitions:
 - `ingested`: full P0 pass and DB projection performed
 - `rejected`: P0 failure; only minimal error/conflict root state projected
 - `conflict_flagged`: concurrent edit detected; authoritative projection not overwritten

Exact handling rules:
1. Malformed YAML
   - Reject.
   - DB projection:
     - Root row only plus:
       - `validation_status = rejected`
       - `reject_type = malformed_yaml`
       - `parse_error = 1`
       - `parse_error_message = <short error code or sanitized message>`
2. Missing required fields (structural validation)
   - Reject.
   - Root row only plus:
     - `validation_status = rejected`
     - `reject_type = structural_validation_failure`
     - `validation_warnings` containing the missing keys list
3. Incompatible versions (compatibility matrix REJECT)
   - Reject.
   - Root row only plus:
     - `validation_status = rejected`
     - `reject_type = version_incompatible`
     - `version_scenario = <scenario code from matrix>`
4. Deprecated but allowed versions (compatibility matrix WARN)
   - Warn but proceed.
   - Full projection performed.
   - Add:
     - `validation_status = ingested`
     - `warning_codes = ["deprecated_version_minor_newer"]`
5. TOON structural conflicts / missing schema safety inputs
   - Reject.
   - Root row only plus:
     - `validation_status = rejected`
     - `reject_type = toon_conflict`
     - `toon_error_code = <missing_toon_or_missing_required_column>`
6. Missing edge targets
   - Continue ingestion; missing targets must not block P0.
   - When writing edge children into `lupo_metadata`:
     - deterministically store `to` as the normalized path
     - store `edge_target_verified = 0` (as a property under the edge child node)
7. Concurrent edits (detected via mtime check)
   - Conflict_flagged.
   - No authoritative overwrite of prior successful content.
   - DB projection:
     - Soft-delete replace is NOT performed for authoritative content.
     - Instead, insert/replace minimal root row plus:
       - `validation_status = conflict_flagged`
       - `conflict_type = concurrent_edit`
       - `conflict_reason = file_mtime_changed`

DB divergence flags:
 - Out of scope for first pass (defer to P1).

## 7. Test / Fixture Plan
Testing goal: prove first-pass determinism and correct reject/warn/conflict outcomes with fixture-driven coverage.

Test harness assumptions:
 - Tests run from repo root with PHP available.
 - The ingestion runner supports `--scope-root` to point at a temporary directory containing fixture files under `lupo-channels/66/`.
 - The ingestion runner supports `--toon-dir` to point at a temporary TOON set for TOON conflict tests.
 - Tests query `lupo_metadata` for the deterministic `entity_id` and assert on:
   - existence of root-only vs full block nodes
   - presence/absence of `display__` prefixed properties
   - presence of warning codes and conflict flags
   - edge_target_verified flags

Fixtures to create:
1. Valid ingest (P0 success)
   - `valid_ingest_thread1001.md`
2. Malformed YAML reject
   - `malformed_yaml_thread1001.md`
3. Missing required fields reject
   - `missing_required_field_thread1001.md`
4. Incompatible versions reject (matrix REJECT)
   - `incompatible_version_thread1001.md` (e.g. major mismatch)
5. Deprecated version warn (matrix WARN)
   - `deprecated_minor_newer_version_thread1001.md` (e.g. header 4.0.81 system 4.0.80)
6. TOON conflict reject (projection safety)
   - `toon_missing_column_thread1001.md` plus a temporary TOON directory missing a required `lupo_metadata.toon` column.
7. Missing edge target continues
   - `missing_edge_target_thread1001.md` with `lupopedia.edges.outbound_edges` pointing `to: "lupo-channels/66/threads/1001/DOES_NOT_EXIST.md"`.
8. Concurrent edit conflict_flagged
   - `concurrent_edit_thread1001.md` and a test runner that modifies the fixture file mtime during ingestion just before DB write.
9. Preservation matrix behavior
   - `field_preservation_matrix_thread1001.md` containing:
     - one lossless field (e.g. `file_path_from_root`)
     - one semantic field (e.g. `tags`)
     - one lossy/display-only field (e.g. `actor_name`)
   - Assertions:
     - lossless stored under original key
     - semantic stored under original key (normalized allowed)
     - lossy stored under `display__actor_name`
     - never-projected elements do not create any `lupo_metadata` rows beyond the defined categories.
10. Cache invalidation (P0 safe optimization)
   - Create two TOON fixture directories with different required-column sets and run ingestion twice without restarting test process to verify cache invalidation behavior changes outcomes (first should pass, second should reject).

Test steps (per fixture):
1. Copy fixture directory to a temp test root.
2. Run ingestion runner with:
   - `--scope-root=<temp_root>`
   - `--thread-id=1001`
   - `--mode=p0`
   - `--toon-dir=<toon_dir_or_fixture>`
3. Compute deterministic `entity_id` in the test (using the same formula documented in this artifact).
4. Query `lupo_metadata` by:
   - `entity_type='channel66_artifact' AND entity_id=:eid AND is_deleted=0`
5. Assert:
   - For valid ingest: root + `lupopedia.headers` block + expected properties exist.
   - For malformed/missing/version/toon reject: only root + `validation_status` and `reject_type` (no authoritative block property trees).
   - For deprecated version warn: full projection exists and `deprecated_version_minor_newer` warning code exists.
   - For missing edge target: edge exists and `edge_target_verified=0`.
   - For concurrent edit: `conflict_flagged` state exists and authoritative content rows are not overwritten.
   - For field preservation: lossy properties appear with `display__` prefix and not in lossless keys.

## 8. Explicit Deferred Scope
The following is explicitly NOT in scope for this first-pass build-out.

Deferred:
- `lupo_edges` projection
  - First pass stores edges in `lupo_metadata` only.
- DB->YAML export and true round-trip fidelity tests.
- Header vs DB divergence detection beyond concurrent edit (P1).
- Incremental validation skip (hash/mtime skip) beyond safe full-run.
- Advanced concurrent-edit resolution (merge strategies).
- Any Channel other than Channel 66.
- Collection/namespace normalization integration (Thread 1003 impacts) beyond what is required for parsing/safety in this pipeline.

## 9. Definition of Done
First implementation pass is complete when all of the following are true:
1. Running the ingestion runner for Channel 66 Thread 1001 produces deterministic outcomes across re-runs:
   - same fixtures => same `entity_id` and same presence/absence of authoritative properties
   - no duplicate authoritative roots and blocks
2. All P0 failure scenarios in the fixture suite produce `rejected` outcome with deterministic `reject_type` values and minimal root-only projections.
3. Deprecated minor version WARN produces full projection plus warning codes, never a reject.
4. TOON schema safety checks are enforced before any DB projection for a file; missing/invalid TOON required inputs lead to deterministic reject.
5. Concurrent edit detection prevents silent overwrite:
   - conflict_flagged state is recorded
   - authoritative content is not overwritten for that file.
6. Field preservation matrix behavior is visible in DB:
   - lossless and semantic-equivalence fields are stored under original keys
   - lossy/display-only fields appear under `display__` prefixed keys
   - never-projected elements do not appear as authoritative DB rows
7. Logs:
   - append-only JSONL records are written to `lupo-logs/admin/` per run
   - each file has an outcome code recorded.

## 10. Next Actor Recommendation
HEPHAESTUS/tool owner should proceed to build-out now (the runner + validators + projection layers, plus the fixture-based unit test script) strictly following this artifact and the locked compatibility matrix rules.

LILITH review is not required for a new gate pass unless a safety uncertainty is discovered while implementing TOON loading/parsing (e.g. if TOON parsing format differs from expected text `.toon` structure).
