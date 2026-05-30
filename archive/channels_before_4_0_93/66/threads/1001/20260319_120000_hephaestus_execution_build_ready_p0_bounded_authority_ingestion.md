---
lupopedia.headers:
  lupopedia.version: 4.0.80
  lupopedia.schema: thread
  system_version: 4.0.80
  file_path_from_root: channels/66/threads/1001/20260319_120000_hephaestus_execution_build_ready_p0_bounded_authority_ingestion.md
  web_path: http://www.lupopedia.com/channels/66/threads/1001/20260319_120000_hephaestus_execution_build_ready_p0_bounded_authority_ingestion.md
  last_modified_utc: '20260324182605'
  channel_id: 66
  thread_id: 1001
  actor_id: 3
  actor_name: hephaestus
  delegation_chain: hephaestus:root
  artifact_type: thread
  artifact_kind: implementation_execution_plan
  purpose: Build-ready execution artifact for Thread 1001 Channel 66 P0 bounded-authority
    LUPOPEDIA HEADERS ingestion (validators + TOON checks + deterministic lupo_metadata
    projection + concurrency conflict + fixtures/tests).
  tags:
  - channel66
  - thread1001
  - implementation_execution
  - p0_ingestion
  - bounded_authority
  - toon_validation
  - deterministic_projection
  - concurrent_edit_detection
  message_type: implementation_execution_plan
  when_updated: '20260324182605'
lupopedia.edges:
  comment: static
  outbound_edges:
  - to: channels/66/threads/1001/20260319_000000_hephaestus_p0_header_ingestion_design_channel66.md
    type: references
    weight: 0.9
    reason: Base P0 ingestion scope and lupo_metadata row model.
  - to: channels/66/threads/1001/20260319_010000_hephaestus_p0_ingestion_design_revised_bounded_authority.md
    type: derived_from
    weight: 1.0
    reason: Revised P0 bounded authority flow (TOON validation, field preservation,
      P0 vs P1 separation).
  - to: channels/66/threads/1001/20260319_020000_lilith_implementation_gate_revised_p0_ingestion_design.md
    type: constrains
    weight: 1.0
    reason: Approved safety constraints for first-pass implementation.
  - to: channels/66/threads/1001/20260319_030000_hephaestus_implementation_plan_revised_p0_bounded_authority_ingestion.md
    type: references
    weight: 0.9
    reason: Recommended execution order and unit-test requirements.
  - to: channels/66/threads/1001/20260319_090000_wolfie_header_version_compatibility_matrix_thread1001.md
    type: implements
    weight: 1.0
    reason: Locked header version compatibility decisions for P0 reject/warn.
  - to: channels/66/threads/1002/20260319_060000_wolfie_closure_bounded_header_authority_thread1002.md
    type: constrains
    weight: 1.0
    reason: Bounded authority hierarchy is locked; implement P0 consequences without
      reopening architecture.
  - to: docs/doctrine/LUPOPEDIA_HEADERS/README.md
    type: references
    weight: 0.95
    reason: Headers declare artifact; DB declares world; collections/namespace not
      involved; row model.
  - to: docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md
    type: references
    weight: 0.95
    reason: Validator/tooling expectations and existing CLI validator contracts.
  - to: docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md
    type: references
    weight: 0.95
    reason: Canonical file format, block order expectations, and required header fields.
  - to: docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md
    type: references
    weight: 0.95
    reason: Canonical root -> blocks -> properties storage model for lupo_metadata.
  - to: rules/root/toon-source-of-truth.md
    type: references
    weight: 1.0
    reason: Install SQL / TOON hierarchy as structural truth for TOON validation.
lupopedia.footer:
  version: 4.0.80
  last_verified: '20260324182605'
  last_verified_by: cursor
  orchestrator: hephaestus
  next_action:
  - 'HEPHAESTUS/tool owner: implement Thread 1001 Channel 66 P0 bounded-authority
    ingestion pipeline code and tests, strictly following this artifact.'
  - 'Optional: LILITH pass only if TOON parsing/column validation behavior mismatches
    expected `.toon` JSON structure during implementation.'
  last_verified_by_actor_id: 102
---

# file: HEPHAESTUS Build-Ready Execution - Thread 1001 P0 Bounded-Authority Ingestion (Channel 66) - session: L-LUPO-ROOT-HEPHAESTUS - delegation: hephaestus:root - web_path: http://www.lupopedia.com/channels/66/threads/1001/20260319_360000_hephaestus_execution_build_ready_p0_bounded_authority_ingestion

## 1. Execution Verdict
Thread 1001 is ready to move from approved planning into concrete implementation execution for the first P0 bounded-authority build pass.

This artifact converts the locked P0 design into an implementable execution plan: ordered work units, exact module touchpoints, and fully specified contracts for validator behavior, TOON structural validation, deterministic lupo_metadata projection, concurrent-edit conflict handling, and the fixture/test suite requirements needed to prove correctness.

## 2. First-Pass Implementation Boundary
First-pass WILL implement (Channel 66, Thread 1001 only):
1. File discovery restricted to `channels/66/**/*.md` with deterministic lexicographic ordering.
2. LUPOPEDIA HEADERS YAML/front-matter extraction (first YAML block only) and YAML parsing (`yaml_parse()`; parse failures must produce explicit P0 reject/conflict state).
3. Structural validation gate for LUPOPEDIA HEADERS:
   - ensure `lupopedia.headers` exists
   - ensure required header fields exist
   - enforce identity-line / `file_path_from_root` relationship (must match actual repo-relative file path)
4. TOON/schema validation as bounded-authority P0:
   - load required TOON schema files from `database/lupopedia/toon/`
   - verify required projection columns exist in the loaded TOON inputs
5. Locked header version compatibility decisions:
   - apply `20260319_090000_wolfie_header_version_compatibility_matrix_thread1001.md` exactly
6. Field preservation classification (Thread 1002 categories) before DB projection:
   - lossless vs semantic-equivalence vs lossy/display-only vs never-projected
7. Deterministic and idempotent DB projection into `lupo_metadata`:
   - deterministic `entity_type` + `entity_id`
   - replace semantics (soft-delete then insert) for ingested/warn paths
8. Concurrent edit detection (P1 conflict):
   - capture `filemtime` after read
   - re-check before any authoritative DB write
9. Explicit per-file outcomes persisted to DB:
   - `ingested` vs `rejected` vs `conflict_flagged`
   - reject_type / warning_codes / conflict_type
10. JSONL append-only logging:
   - one log record per processed file

First-pass will NOT implement:
1. Production UI changes or runtime UI refactors.
2. Any global/multi-channel rollout (only Channel 66, Thread 1001).
3. `lupo_edges` projection (edges inside `lupo_metadata` only; lupo_edges deferred).
4. DB -> YAML export, round-trip generation, or "perfect reconstruction" tests (P2).
5. Advanced merge/conflict resolution strategies beyond conflict flagging for concurrent edits.
6. Incremental skip beyond safe full-run semantics (no "smart skip" unless explicitly implemented as a safe optimization and tested in P0 cache invalidation scenario).

## 3. Exact Module / File Touchpoints
Create:
1. `scripts/ingest_channel66_headers_bounded_authority.php`
   - CLI runner for the P0 first-pass ingestion pipeline.
2. `includes/classes/Channel66HeaderIngester.php`
   - Orchestrates per-file pipeline: discover -> parse -> structural validate -> bounded authority validate -> field preservation -> projection -> concurrency check -> logging.
3. `includes/classes/BoundedHeaderAuthorityValidator.php`
   - Owns:
     - P0 structural required field validation
     - locked version compatibility matrix enforcement
     - TOON/schema structural conflict detection (reject)
     - actor registry existence validation (reject)
4. `includes/classes/ToonSchemaCache.php`
   - Loads and caches TOON JSON schema inputs by `(toon_path, mtime)`.
   - Enforces cache invalidation on TOON mtime change.
5. `includes/classes/HeaderFieldPreservationMatrix.php`
   - Implements Thread 1002 field categories + exact mapping rules for property_key encoding:
     - never-projected omitted from authoritative projection
     - lossy/display-only stored under `display__<field_name>`
     - semantic-equivalence stored under original field name with allowed normalization
6. `includes/classes/Channel66HeaderProjection.php`
   - Deterministic entity identity + `lupo_metadata` tree payload assembly:
     - root -> block rows -> property rows
     - optional edges-as-metadata child rows under `lupopedia.edges` block
     - minimal root-only state projections for reject/conflict outcomes
7. `includes/classes/Channel66IngestionLogger.php`
   - Appends one JSONL record per file to `logs/admin/`.

Update (optional but strongly recommended for discoverability):
8. `bin/lupo.php`
   - Add a discoverable CLI dispatch path that runs `scripts/ingest_channel66_headers_bounded_authority.php` for Channel 66 Thread 1001.
   - Must not change production behavior outside this explicit new subcommand/path.

Tests + fixtures:
9. `tests/unit/channel66_bounded_authority_ingestion_p0_test.php`
   - Runs the ingestion runner against fixture root(s).
   - Asserts DB projection shape and reject/warn/conflict outcomes.
10. Fixture directory:
   - `tests/fixtures/channel66_ingestion/thread1001/`
   - Required fixture headers (exact families) listed in section 8 below.
11. TOON fixture variants used for TOON conflict + cache invalidation tests:
   - create test TOON directories under the fixture root
   - use runner's `--toon-dir` hook to point to those dirs (do not alter canonical TOONs).

## 4. Execution Work Units (Ordered, Build-Ready)
Work Unit 1: CLI runner & run configuration
- Objective: Parse arguments and build a run configuration object for Channel 66 Thread 1001.
- Touched: `scripts/ingest_channel66_headers_bounded_authority.php`
- Success condition:
  - runner supports at minimum:
    - `--thread-id=1001` (default 1001)
    - `--mode=p0` (default p0; other modes may be rejected with a clear message)
    - `--scope-root=<path>` (test hook; defaults to repo root)
    - `--toon-dir=<path>` (test hook; defaults to `database/lupopedia/toon/`)
    - `--edges=metadata_only` (default; `lupo_edges` write must be absent)

Work Unit 2: Deterministic file discovery (Channel 66 only)
- Objective: Enumerate only `channels/66/**/*.md` candidates and sort deterministically.
- Touched: `Channel66HeaderIngester.php`
- Success condition:
  - ordering is lexicographic by repo-relative path
  - only files with candidate header delimiters (first YAML front-matter opener `---`) are processed; others are ignored.

Work Unit 3: LUPOPEDIA HEADERS extraction + YAML parsing
- Objective: Extract the first YAML front-matter block and parse it via `yaml_parse()`.
- Touched: `Channel66HeaderIngester.php` (+ helper methods inside it or called from validator)
- Success condition:
  - malformed YAML produces outcome `rejected` with `reject_type=malformed_yaml`
  - parse_error_message is present in the minimal projection payload (root-only state).

Work Unit 4: P0 structural validation gate (required fields + identity binding)
- Objective: Enforce that `lupopedia.headers` exists and that all P0-required header identity fields exist; bind identity to filesystem location.
- Touched: `BoundedHeaderAuthorityValidator.php`
- Success condition:
  - missing required fields yields outcome `rejected` with:
    - `reject_type=structural_validation_failure`
    - minimal root-only projection (no authoritative block trees)
  - if `file_path_from_root` does not match the actual repo-relative path of the file, reject with the same structural_validation_failure outcome.

Work Unit 5: Locked version compatibility matrix enforcement (P0 vs P1 separation)
- Objective: Apply the WOLFIE locked matrix to `lupopedia.version` vs `system_version`.
- Touched: `BoundedHeaderAuthorityValidator.php`
- Success condition:
  - scenarios map to the exact outcomes and warning injection rules in section 5 below
  - malformed/missing versions reject with `reject_type=version_incompatible`.

Work Unit 6: TOON/schema validation (bounded authority structural conflict)
- Objective: Validate required TOON structural inputs exist and contain required projection columns.
- Touched: `ToonSchemaCache.php` + `BoundedHeaderAuthorityValidator.php`
- Success condition:
  - load `lupo_metadata.toon` from `--toon-dir`
  - verify required columns list exists
  - any missing/invalid TOON required schema input yields outcome `rejected` with:
    - `reject_type=toon_conflict`
    - `toon_error_code=<missing_toon_or_missing_required_column>`
  - caching is safe:
    - cache invalidates when TOON mtime changes within the same test process

Work Unit 7: Actor registry existence validation
- Objective: Ensure `actor_id` references exist in the canonical actor registry.
- Touched: `BoundedHeaderAuthorityValidator.php`
- Success condition:
  - unknown actor_id yields outcome `rejected` with reject_type `unknown_actor_id` (or a deterministic equivalent reject_type code defined during implementation that is asserted by tests).

Work Unit 8: Field preservation classification + property_key encoding
- Objective: Apply Thread 1002 field preservation categories to parsed header fields before payload generation.
- Touched: `HeaderFieldPreservationMatrix.php`
- Success condition:
  - lossless fields appear under original property_key (no display__ prefix)
  - semantic-equivalence fields appear under original property_key (with allowed normalization)
  - lossy/display-only fields appear under `display__<field_name>`
  - never-projected elements do not create authoritative DB rows

Work Unit 9: Deterministic entity identity + lupo_metadata tree payload generation
- Objective: Compute deterministic identity and assemble the canonical root->block->property tree (and optional edges-as-metadata).
- Touched: `Channel66HeaderProjection.php`
- Success condition:
  - ingested/warn paths always produce the same entity_id for the same `file_path_from_root`
  - projection uses replace semantics:
    - soft-delete prior rows for `(entity_type='channel66_artifact', entity_id, domain_id IS NULL)`
    - then insert new root->blocks->properties

Work Unit 10: Concurrent edit detection before authoritative write
- Objective: Detect mtime change and prevent silent overwrite.
- Touched: `Channel66HeaderIngester.php` + `Channel66HeaderProjection.php`
- Success condition:
  - if filemtime changes after read and before write:
    - outcome is `conflict_flagged`
    - authoritative block/property trees are NOT overwritten
    - minimal root-only conflict state is written with:
      - `validation_status=conflict_flagged`
      - `conflict_type=concurrent_edit`
      - `conflict_reason=file_mtime_changed`

Work Unit 11: JSONL logging per file outcome
- Objective: Append-only log record for each file with structured outcome codes.
- Touched: `Channel66IngestionLogger.php`
- Success condition:
  - each processed fixture results in exactly one log record
  - log record includes outcome + outcome codes and the file_path_from_root identifier.

Work Unit 12: Fixture-based unit test harness
- Objective: Implement DB assertions for every fixture family.
- Touched: `tests/unit/channel66_bounded_authority_ingestion_p0_test.php`
- Success condition:
  - all fixture assertions pass
  - tests cover cache invalidation behavior for TOON schema reload.

## 5. Validator Contract (Exact Reject/Warn/Ignore)
Definitions:
- Table docs scope does not apply for Channel 66 artifacts. Namespace/table-doc rules still exist in general validators, but first-pass validator contract for Channel 66 is defined here.
- P0 means first-pass is safe only if P0 conflicts reject before DB projection.

Required outcomes:
1. Malformed YAML
   - Outcome: REJECT
   - Persist:
     - `validation_status=rejected`
     - `reject_type=malformed_yaml`
     - `parse_error=1`
     - `parse_error_message=...`
2. Missing required header fields (structural validation)
   - Outcome: REJECT
   - Persist:
     - `validation_status=rejected`
     - `reject_type=structural_validation_failure`
     - `validation_warnings=[...]` containing missing keys list
3. Incompatible header version (matrix REJECT)
   - Outcome: REJECT
   - Persist:
     - `validation_status=rejected`
     - `reject_type=version_incompatible`
     - `version_scenario=<matrix scenario code>`
4. Deprecated but allowed versions (matrix WARN)
   - Outcome: WARN but proceed ingestion
   - Persist:
     - full authoritative projection
     - `validation_status=ingested`
     - `warning_codes=["deprecated_version_minor_newer"]`
5. TOON structural conflicts / missing required schema safety inputs
   - Outcome: REJECT
   - Persist:
     - `validation_status=rejected`
     - `reject_type=toon_conflict`
     - `toon_error_code=<missing_toon_or_missing_required_column>`
6. Missing edge targets
   - Outcome: IGNORE (do not reject)
   - Behavior:
     - continue ingestion
     - when projecting each edge child row, store:
       - deterministic `to` normalization
       - `edge_target_verified=0` as a property under the edge child node
7. Concurrent edits (mtime changed)
   - Outcome: CONFLICT_FLAGGED
   - Persist:
     - minimal root-only projection
     - `validation_status=conflict_flagged`
     - `conflict_type=concurrent_edit`
     - `conflict_reason=file_mtime_changed`

## 6. Projection Contract (Exact lupo_metadata Write Behavior)
Target table:
- `lupo_metadata` only (P0 target; `lupo_edges` deferred).

Entity identity:
1. `entity_type = 'channel66_artifact'` (fixed).
2. `entity_id = hexdec(substr(md5($file_path_from_root), 0, 15))` (deterministic, non-negative).

Replace semantics:
1. For ingested/warn projections:
   - Soft-delete all existing authoritative `lupo_metadata` rows for the entity:
     - `entity_type='channel66_artifact'`
     - `entity_id=:eid`
     - `domain_id IS NULL`
   - Set:
     - `is_deleted=1`
     - `updated_ymdhis = gmdate('YmdHis')`
     - (and set `deleted_ymdhis` as appropriate during implementation)
2. Then insert the new tree:
   - root node
   - block nodes
   - property nodes

Row tree structure:
1. Root:
   - `class_name='lupopedia_header_root'`
   - `meta_type='lupopedia_header'`
   - `property_key='__root__'`
   - `property_value='1'`
2. Block rows:
   - one block row per `lupopedia.<block_name>` found/used in projection (including `lupopedia.headers`, and optionally `lupopedia.edges`, `lupopedia.footer`, `lupopedia.session` if present)
   - each block row:
     - `class_name='lupopedia_block'`
     - `meta_type='block'`
     - `property_key=<block_name>`
     - `parent_metadata_id=<root metadata_id>`
3. Property rows:
   - one property row per projected field
   - each property row:
     - `class_name='lupopedia_property'`
     - `meta_type='block'`
     - `property_key=<encoded-key>`
     - `property_value=<serialized-value>`
     - `parent_metadata_id=<block row metadata_id>`
4. `property_value` serialization rules (P0 first pass):
   - scalar values (string/int/bool) => store as string
   - YAML arrays/sequences => store as deterministic JSON string via `json_encode($value, JSON_UNESCAPED_SLASHES)`
   - YAML maps/objects => store as deterministic JSON string via `json_encode($value, JSON_UNESCAPED_SLASHES)` (object key order follows PHP/json_encode behavior; tests must use fixtures that avoid non-deterministic map key order where feasible)
4. Edge child structure (when `lupopedia.edges` exists):
   - Under the `lupopedia.edges` block:
     - each edge is stored as a repeating child node:
       - `class_name='lupopedia_edge'`
       - with property rows for at minimum: `to`, `type`, `weight`, and optional `reason`, `edge_category`
     - `edge_target_verified=0` when target path does not exist in filesystem for P0.

Field preservation encoding rules:
1. Lossless fields:
   - store under property_key exactly as the original header field name.
2. Semantic-equivalence fields:
   - store under original field name; normalization is allowed before storing (e.g., tags sorting/dedup).
3. Lossy/display-only fields:
   - store under `property_key = "display__" . <original_field_name>`.
4. Never-projected items:
   - omitted from authoritative projection (no property rows created).

Minimal projections:
1. Reject cases (malformed_yaml / structural_validation_failure / version_incompatible / toon_conflict):
   - Soft-delete replace semantics MUST still run for the entity (so no stale authoritative blocks remain):
     - set `is_deleted=1` for all existing `lupo_metadata` rows for `(entity_type, entity_id, domain_id IS NULL)`
   - Insert:
     - one root row
     - plus property rows whose `parent_metadata_id` is the root metadata_id (no additional block rows)
   - Required root-level properties for rejection:
     - `validation_status` = `rejected`
     - `reject_type` = one of:
       - `malformed_yaml`
       - `structural_validation_failure`
       - `version_incompatible`
       - `toon_conflict`
     - plus any reject-specific properties:
       - `parse_error` / `parse_error_message` (malformed_yaml)
       - `validation_warnings` (structural_validation_failure)
       - `version_scenario` (version_incompatible)
       - `toon_error_code` (toon_conflict)
2. Concurrent conflict cases:
   - Soft-delete replace semantics MUST NOT delete authoritative block/property content rows created by the last successful ingest.
   - Write strategy:
     - Soft-delete only the existing root rows (class_name = `lupopedia_header_root`) for this entity_id by setting `is_deleted=1`.
     - Do NOT soft-delete any non-root rows (blocks/properties/edges) for the entity.
     - Insert:
       - one new root row
       - plus property rows under the new root metadata_id (no additional block rows)
   - Required root-level properties for conflict:
     - `validation_status` = `conflict_flagged`
     - `conflict_type` = `concurrent_edit`
     - `conflict_reason` = `file_mtime_changed`

## 7. TOON Validation Contract
TOON load source:
1. Canonical path: `database/lupopedia/toon/`
2. Loader must read TOON schema files from the directory passed via runner `--toon-dir`.
3. TOON file extension:
   - implement loader to read `.toon` files (as present in repo) and parse them as JSON.

What is compared (P0):
1. For this first pass, TOON validation focuses on schema safety for the fixed lupo_metadata projection logic.
2. Load and validate `lupo_metadata.toon`:
   - verify it contains required columns (from lupo_metadata.toon):
     - metadata_id
     - entity_type
     - entity_id
     - domain_id
     - meta_type
     - property_key
     - property_value
     - created_ymdhis
     - updated_ymdhis
     - is_deleted
     - deleted_ymdhis
     - channel_id
     - parent_metadata_id
     - class_name
     - schema_ref

Structural conflict definition:
- Reject when:
  - TOON file is missing/unparseable OR
  - any required projection column is missing from the loaded TOON schema.

Caching allowed:
- Allowed optimization:
  - cache loaded TOON schemas in `ToonSchemaCache` keyed by:
    - `toon_path` and `toon_file_mtime`
  - invalidate/reload cache entries when mtime changes within the same process.

Cache invalidation success condition:
- In the cache invalidation fixture test:
  - first run succeeds with TOON variant A
  - then TOON variant B is substituted and its mtime changes
  - second run within the same test process must reflect the new TOON schema and produce a different outcome (reject).

## 8. Test Fixture Contract (Exact Families Required)
Fixture root:
- `tests/fixtures/channel66_ingestion/thread1001/`

Required fixture files (minimum set; exact outcomes asserted by the unit test harness):
1. Valid ingest (P0 success)
   - `valid_ingest_thread1001.md`
   - Expected DB assertions:
     - `lupo_metadata` contains at least one non-deleted root row (entity_type `channel66_artifact`, computed entity_id, is_deleted=0, class_name `lupopedia_header_root`)
     - `validation_status=ingested` exists as a root-level property on the non-deleted root
     - the authoritative `lupopedia.headers` block exists under that root (class_name `lupopedia_block`, property_key `lupopedia.headers`)
     - lossless fixture keys are present under `lupopedia.headers` with property_key exactly matching the header field name (no `display__` prefix)
     - `warning_codes` root-level property is absent (or not present in authoritative root for this run)
2. Malformed YAML reject
   - `malformed_yaml_thread1001.md`
   - Expected DB assertions:
     - authoritative `lupopedia.headers` block is absent under the newly inserted root for the computed entity_id
     - `validation_status=rejected` exists as a root-level property
     - `reject_type=malformed_yaml` exists as a root-level property
     - `parse_error=1` exists as a root-level property
3. Missing required fields structural reject
   - `missing_required_field_thread1001.md`
   - Expected DB assertions:
     - `validation_status=rejected` and `reject_type=structural_validation_failure` exist as root-level properties
     - `validation_warnings` root-level property exists and includes the missing required header keys list
     - authoritative `lupopedia.headers` block is absent under the newly inserted root
4. Incompatible versions reject
   - `incompatible_version_thread1001.md`
   - Expected DB assertions:
     - `validation_status=rejected` and `reject_type=version_incompatible` exist as root-level properties
     - `version_scenario=<matrix code>` exists as a root-level property
     - authoritative `lupopedia.headers` block is absent under the newly inserted root
5. Deprecated but allowed versions warn
   - `deprecated_minor_newer_version_thread1001.md`
   - Expected DB assertions:
     - full authoritative projection exists (root + `lupopedia.headers` block + projected properties)
     - `validation_status=ingested` exists
     - `warning_codes` exists as a root-level property and equals `["deprecated_version_minor_newer"]`
6. TOON conflict reject (projection safety)
   - `toon_missing_column_thread1001.md`
   - plus a temporary TOON directory missing a required column in `lupo_metadata.toon`
   - Expected DB assertions:
     - `validation_status=rejected` and `reject_type=toon_conflict` exist as root-level properties
     - `toon_error_code` exists as a root-level property (missing toon or missing required column)
     - authoritative `lupopedia.headers` block is absent under the newly inserted root
7. Missing edge target continues
   - `missing_edge_target_thread1001.md`
   - `lupopedia.edges.outbound_edges` points `to: "channels/66/threads/1001/DOES_NOT_EXIST.md"`
   - Expected DB assertions:
     - ingestion succeeds (unless other reject conditions triggered)
     - inside the authoritative `lupopedia.edges` block:
       - each projected outbound edge child exists as a `lupopedia_edge` node
       - each such edge child has property `edge_target_verified=0` when the referenced `to` file is missing
8. Concurrent edit conflict_flagged
   - `concurrent_edit_thread1001.md`
   - plus test harness behavior:
     - test must modify the fixture file mtime after Step 1/2 read but before any DB write
   - Expected DB assertions:
     - `validation_status=conflict_flagged` exists as root-level property on the newly inserted conflict root
     - `conflict_type=concurrent_edit` exists as a root-level property
     - `conflict_reason=file_mtime_changed` exists as a root-level property
     - existing non-root authoritative rows from the last successful ingest remain present (i.e. tests must assert presence of at least one known pre-conflict `lupopedia_property` key with is_deleted=0)
     - for the newly inserted conflict root, there must be no authoritative `lupopedia.headers` block row (conflict root is root-only)
9. Field preservation matrix behavior
   - `field_preservation_matrix_thread1001.md`
   - Required content in header:
     - at least one lossless field (e.g. `file_path_from_root`)
     - one semantic-equivalence field (e.g. `tags`)
     - one lossy/display-only field (e.g. `actor_name`)
     - at least one never-projected element (e.g. YAML comments or formatting-only aspects; implementation should prove that they are not stored as authoritative DB rows)
   - Expected DB assertions:
     - lossless stored under original property_key
     - semantic-equivalence stored under original property_key (with normalization allowed)
     - lossy stored as `display__actor_name` (exact key prefix)
     - never-projected elements do not create extra authoritative property rows
10. Cache invalidation (P0 safe optimization)
   - Provide two TOON fixture variants and run ingestion twice in one test process without restarting:
     - first run uses TOON variant A that satisfies required columns
     - second run uses TOON variant B with a missing required column set
   - Expected:
     - first outcome ingested/warn
     - second outcome reject `toon_conflict`

## 9. Definition of Done
Thread 1001 first-pass implementation execution planning is complete when all of the following are true for the implemented code:
1. The ingestion runner can run end-to-end for Channel 66 Thread 1001 on the fixture root and terminates cleanly.
2. Deterministic outcomes:
   - each fixture produces the expected `validation_status` and outcome codes
   - no duplicate authoritative roots are present for the same deterministic entity_id
3. Correct P0 reject vs P1 warn behavior:
   - malformed_yaml, structural_validation_failure, toon_conflict, version_incompatible must be strict rejects with minimal root-only projections
   - deprecated_minor_newer_version must warn (full projection) and must not reject
4. TOON validation happens before any DB projection:
   - if TOON is invalid/missing required columns, no authoritative content tree is written
5. Concurrent edit detection:
   - concurrent edits result in `conflict_flagged` minimal root-only conflict state
   - authoritative projection is not overwritten for that file's entity identity
6. Field preservation encoding is observable in DB:
   - property_key encoding follows lossless/semantic/lossy/never-projected rules
7. Logs are emitted:
   - append-only JSONL contains one record per fixture file outcome with the required outcome codes.

## 10. Next Handoff
Primary handoff: HEPHAESTUS/tool owner.

Implement:
- the runner + validators + TOON schema cache + preservation matrix + projection builder + JSONL logging, and
- the fixture-based unit test script and the required fixture header set,
strictly according to the contracts above and the locked compatibility matrix.

Optional handoff:
- LILITH only if TOON parsing format (actual `.toon` JSON structure) differs from what the implementation assumes during schema-column extraction, causing uncertainty that cannot be resolved via tests.

