---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  system_version: "4.0.80"
  file_path_from_root: "lupo-channels/66/threads/1003/20260319_100000_hephaestus_implementation_start_collections_vs_namespaces_exec.md"
  web_path: "http://www.lupopedia.com/lupo-channels/66/threads/1003/20260319_340000_hephaestus_implementation_start_collections_vs_namespaces_exec"
  last_modified_utc: "20260319"
  channel_id: 66
  thread_id: 1003
  actor_id: 3
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:root"
  artifact_type: "thread"
  artifact_kind: "implementation_execution_start"
  purpose: "First production-facing implementation pass for locked collections vs namespaces behavior (validator + ingestion/projection + dry-run normalization + fixtures/tests)."
lupopedia.edges:
  comment: "static"
  outbound_edges:
    - { to: "lupo-channels/66/threads/1003/20260319_090000_hephaestus_post_doctrine_implementation_plan_collections_vs_namespaces.md", type: "implements", weight: 1.0, reason: "Implements the post-doctrine behavior plan for Thread 1003." }
    - { to: "lupo-channels/66/threads/1003/20260319_080000_wolfie_doctrine_execution_collections_namespaces.md", type: "inherits_from", weight: 0.98, reason: "Uses doctrine execution as the locked model." }
    - { to: "lupo-channels/66/threads/1003/20260319_070000_wolfie_doctrine_update_plan_collections_namespaces.md", type: "derived_from", weight: 0.95, reason: "Execution plan establishes what must be true before implementation." }
    - { to: "lupo-channels/66/threads/1003/20260319_060000_hephaestus_implementation_implications_collections_vs_namespaces.md", type: "references", weight: 0.92, reason: "Implications constrain validator severity, ingestion split, and normalization safety." }
    - { to: "lupo-channels/66/threads/1003/20260319_050000_wolfie_narrowing_collections_namespaces_decision_ready.md", type: "constrains", weight: 0.9, reason: "Precedence by scope and bounded coupling are the operational baseline." }
    - { to: "lupo-docs/doctrine/COLLECTIONS_DOCTRINE.md", type: "constrains", weight: 1.0, reason: "Collections = nav/tabs/URLs/breadcrumbs; not filesystem authority." }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 0.92, reason: "Headers = artifact metadata; collections/namespace relationship and precedence." }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md", type: "constrains", weight: 1.0, reason: "Namespace is single-value taxonomy field; table-doc requirements; block order rules." }
    - { to: "lupo-rules/root/FILE_BOUNDARY_VALIDATION_RULE.md", type: "references", weight: 0.8, reason: "Validator must protect file boundary and disallow path derivation." }
    - { to: "DIRECTORY_STRUCTURE_DOCTRINE.md", type: "references", weight: 0.8, reason: "Directory doctrine defines file paths independent of collections/namespace." }
    - { to: "lupo-channels/66/threads/1003", type: "related_question", weight: 1.0, reason: "Current question context for collections vs namespaces implementation." }
    - { to: "lupo-channels/66/threads/1001", type: "related_question", weight: 0.95, reason: "Ingestion must inherit split-field semantics (collections membership vs namespace policy) from Thread 1001." }
    - { to: "lupo-channels/66/threads/1002", type: "related_question", weight: 0.95, reason: "Validation and bounded-authority behavior constrain error/warn thresholds and safety handling." }
lupopedia.footer:
  version: "4.0.80"
  last_verified: "20260319"
  last_verified_by: "hephaestus"
  orchestrator: "hephaestus"
  next_action:
    - "HEPHAESTUS/tool owner: implement validator updates, canonical slug resolver + offline snapshot fallback, ingestion/projection split, and dry-run normalization report skeleton exactly per this artifact."
    - "HEPHAESTUS/tool owner: add/extend fixtures and unit tests to lock error/warn/ignore behavior for first pass."
---

# file: HEPHAESTUS Implementation Start â€” Collections vs Namespaces (Thread 1003) â€” session: L-LUPO-ROOT-HEPHAESTUS â€” delegation: hephaestus:root â€” web_path: http://www.lupopedia.com/lupo-channels/66/threads/1003/20260319_340000_hephaestus_implementation_start_collections_vs_namespaces_exec

## 1. Implementation-Start Verdict
First real implementation pass can begin immediately.

This thread model is locked by doctrine execution (Thread 1003 320000) and constrained by the acceptance position (Thread 1003 050000) and behavior implications (Thread 1003 300000). This artifact only stages implementation details for validator behavior, slug authority plumbing, ingestion/projection split, and dry-run normalization tooling.

## 2. First-Pass Scope
Included in this first pass (exactly these changes):

1. Validator behavior updates (collections + namespace)
   - Enforce `namespace` taxonomy + single-value semantics with error/warn stratification for table-doc scope vs non-table-doc scope.
   - Enforce `collections` header field as an array when present, with duplicate and unknown-slug warning behavior.
   - Detect path-authority misuse indicators: `file_path_from_root` must match the actual file location, and any mismatch is classified as misuse-via-`collections` and/or misuse-via-`namespace` based on overlap with the provided values.
   - Preserve existing validator contract: ERRORs must fail the command; WARNs must not fail the command but must still be machine-diagnosable by ingestion/projection code.

2. Canonical slug authority plumbing (collection slugs)
   - Canonical slug authority source: DB registry table `lupo_collections.slug` filtered to active/non-deleted rows (`is_deleted = 0`), scoped to the node (`federation_node_id = LUPO_DEFAULT_NODE_ID`).
   - Offline snapshot fallback: when DB is unavailable or a run is forced into offline mode, load a last-known canonical slug list from a repo-controlled snapshot file.
   - Surface `registry_mode` deterministically in both validator diagnostics and ingestion/projection logs.
   - If DB and snapshot disagree, DB wins, but the disagreement is recorded as a warning code in diagnostics/report output.

3. Ingestion/projection changes for Channel 66 header ingestion (split-field semantics)
   - Parse and treat `namespace` and `collections` as distinct fields during Channel 66 ingestion/projection.
   - Store them distinctly in `lupo_metadata` under the same Channel 66 entity (`entity_type = channel66_artifact`), as separate property rows (no conflation).
   - Use `namespace` only for policy/scope/classification in ingestion-side decisions; use `collections` only for nav/grouping/classification in ingestion-side decisions.
   - Explicitly prohibit any derivation of `file_path_from_root` (or entity identity) from either `namespace` or `collections`. Identity remains derived only from the `file_path_from_root` value and deterministic hashing rules from Thread 1001.
   - If header `collections[]` contains unknown slugs (relative to canonical slug registry), record divergence warnings (do not reject).

4. Normalization dry-run report tool skeleton (no apply mode yet)
   - A dry-run scanner that validates and produces a deterministic mismatch report for namespace drift and collection-slug drift.
   - It must output mismatch categories matching the fixture/test plan in this artifact.
   - Apply-mode is NOT enabled in first pass. If any apply-mode mention appears in the code skeleton, it must be stubbed behind an explicit `--apply-mode` flag that defaults to off and performs no writes in first pass.

5. Fixture/test coverage for first pass
   - Add fixtures for each validator scenario category required by this artifact.
   - Add unit tests that assert ERROR vs WARN vs IGNORE behavior, and that offline snapshot mode works deterministically.
   - Add at least one unit-level projection assertion that the ingestion/projection builder produces separate `lupo_metadata` property rows for `namespace` and `collections`.

Out of scope (explicitly deferred):
- Any production normalization apply/overwrite behavior.
- Any auto-creation of missing collection slugs.
- Any runtime UI refactor beyond what is required to make ingestion/projection store the split fields correctly.
- Any database schema change or TOON change for this first pass.

## 3. Exact File / Module Touchpoints
Create/update the following exact touchpoints.

Validator + slug authority:
1. Update `lupo-scripts/validate_lupopedia_headers.php`
   - Add parsing/extraction and call into new validator logic for `namespace` multi-value drift and `collections` array/type semantics.
   - Add `file_path_from_root` integrity enforcement (value must equal the actual file path relative to repo root).
   - Convert WARN outcomes into non-fatal diagnostic emission while preserving ERROR fatality.

2. Create `lupo-includes/classes/CanonicalCollectionSlugResolver.php`
   - Methods:
     - `getCanonicalSlugs($db, $table_prefix, $federation_node_id, $offline_snapshot_path, &$out_registry_mode, &$out_disagreement_codes)`
   - Behavior:
     - Prefer DB list when DB is available.
     - Otherwise load offline snapshot file.
     - When both available and sets disagree, record disagreement warning codes.

3. Create `lupo-includes/classes/CollectionsNamespacesHeaderValidator.php`
   - Methods:
     - `validateHeaderArray($header_yaml_parsed_or_raw, $is_table_doc, $canonical_slug_set, $registry_mode, $context)`
   - Output struct (required for ingestion/projection):
     - `errors[]` (fatal)
     - `warnings[]` with explicit codes (non-fatal)
     - `ignore[]` (explicitly ignored cases, optional for reporting)
     - `diagnostics` including parsed `namespace`, parsed `collections[]`, and computed overlap/misuse hints.

4. Create offline snapshot file:
   - `lupo-cache/offline/collections_slug_snapshot_federation_1.json`
   - Contents:
     - `federation_node_id`: 1
     - `generated_ymdhis`: BIGINT string (placeholder ok for first pass)
     - `slugs`: list of known slugs used by fixtures/tests
   - The first pass tool owner may keep it minimal for tests, but production should later regenerate it from DB using the builder below.

5. Create snapshot builder script (dry-run only; used for offline cache creation):
   - `lupo-scripts/build_collections_slug_snapshot.php`
   - It is allowed to be non-core in first pass (called only by dev/tooling), but it must use the canonical resolver logic (not duplicate DB query code).

Dry-run normalization tool:
6. Create `lupo-scripts/normalize_collections_namespaces_dry_run.php`
   - Reads header files, runs validator logic, and aggregates mismatch report categories.
   - Outputs:
     - `lupo-logs/normalization/YYYYMMDDHHIISS_collections_namespaces_dry_run_report.json`

Ingestion/projection integration (Channel 66, inherited from Thread 1001):
7. Update (or create, if Thread 1001 code was not implemented yet) these ingestion/projection modules to apply split-field semantics:
   - `lupo-includes/classes/Channel66HeaderIngester.php`
     - Parse `namespace` and `collections` separately and pass both into bounded header validator.
   - `lupo-includes/classes/BoundedHeaderAuthorityValidator.php`
     - Add misuse/path-authority detection enforcement hooks and severity stratification for `namespace`/`collections` parsing.
   - `lupo-includes/classes/Channel66HeaderProjection.php`
     - Ensure `lupo_metadata` projection writes:
       - property rows for `namespace` (scalar string)
       - property rows for `collections` (serialized array, deterministic encoding)
     - Ensure no logic derives `file_path_from_root` from either field.
   - `lupo-includes/classes/HeaderFieldPreservationMatrix.php`
     - Ensure classification for `namespace` and `collections` is NOT display-only for first pass (they must be projected as authoritative).

8. Optional (only if required for discoverability by your test harness):
   - Update `lupo-bin/lupo.php` to add a subcommand:
     - `php lupo-bin/lupo.php collections namespace-collections dry-run <path>`
   - If this is done, it must only dispatch to the dry-run script and must not change production behavior.

Fixtures and tests:
9. Create fixture directory:
   - `lupo-tests/fixtures/collections_namespaces/`
   - Include files for table-doc and non-table-doc cases as specified in section 8.

10. Create DB-stub fixture for unit tests (no real DB needed):
   - `lupo-tests/fixtures/collections_namespaces/db_stub_active_slugs.json`
   - Shape:
     - `federation_node_id`: 1
     - `slugs`: [ ... ]
   - Used by unit tests to simulate â€œDB-backed canonical slug modeâ€ deterministically.

10. Create unit test script:
   - `lupo-tests/unit/collections_namespaces_validator_test.php`
   - Must run with no DB and must exercise offline snapshot mode and ERROR/WARN/IGNORE outcomes.

11. Create projection unit test script:
   - `lupo-tests/unit/channel66_collections_namespaces_projection_test.php`
   - It must validate that the projection builder produces distinct `lupo_metadata` property rows for `namespace` and `collections`.

## 4. Exact Validator Behavior
Severity mapping (first pass):

Key definitions:
- Table-doc scope: any artifact path under `lupo-docs/database/lupopedia/tables/` (same detection approach as current `validate_lupopedia_headers.php`).
- Non-table-doc scope: all other artifacts.

Validator rules (exact outcomes):

Namespace rules:
1. `namespace` missing on table docs
   - ERROR
   - code: `namespace_missing_for_table_doc`

2. `namespace` invalid taxonomy value (not in approved list)
   - ERROR (table docs)
   - code: `namespace_invalid_taxonomy`
   - WARN (non-table docs)
   - code: `namespace_invalid_taxonomy_non_table_warn`

3. `namespace` provided as multi-value (array/list or YAML sequence)
   - ERROR (table docs)
   - code: `namespace_multi_value_detected`
   - WARN (non-table docs)
   - code: `namespace_multi_value_detected_non_table_warn`

4. `namespace` omitted on non-table docs
   - IGNORE

Collections rules:
5. `collections` present but not an array (string/scalar or object)
   - ERROR
   - code: `collections_wrong_type_not_array`

6. `collections` duplicates within a single header array
   - WARN
   - code: `duplicate_collection_slug`

7. `collections` contains unknown slugs (relative to canonical slug registry list)
   - WARN
   - code: `unknown_collection_slug`

8. `collections` omitted
   - IGNORE

Path-authority misuse detection (hard prohibition):
9. `file_path_from_root` value does not equal the actual file location (repo-relative normalized path)
   - ERROR
   - If the mismatch reason/overlap indicates the path was inferred from `collections` (the `file_path_from_root` string contains one of the provided `collections[]` slugs):
     - code: `path_authority_misuse_via_collections`
   - Else if it indicates inference from `namespace` (the `file_path_from_root` contains the namespace value):
     - code: `path_authority_misuse_via_namespace`
   - Else:
     - code: `file_path_from_root_mismatch`

Conflict handling in ingestion (inherited, unchanged in this pass):
- Concurrent edit detection uses the same `filemtime` check rule from Thread 1001. If conflict_flagged, projection is skipped and a minimal conflict state is written.
- Validator-only WARNs do not affect concurrency/conflict decisions.

## 5. Canonical Slug Authority Implementation
Canonical collection slug resolution must follow this exact contract.

Primary source:
- Table: `lupo_collections`
- Column: `slug`
- Filters:
  - `is_deleted = 0`
  - `federation_node_id = LUPO_DEFAULT_NODE_ID` (or the passed node id)

Offline snapshot fallback:
- If DB is unavailable (connection error) OR the runner is explicitly configured for offline snapshot mode, load:
  - `lupo-cache/offline/collections_slug_snapshot_federation_1.json`
- The snapshot must contain a list:
  - `slugs[]` (array of strings)

registry_mode surfacing:
- Resolver returns `registry_mode` in diagnostic outputs:
  - `registry_mode = "db_live"` when DB is used
  - `registry_mode = "offline_snapshot"` when snapshot file is used

DB vs snapshot disagreement:
- When both DB and offline snapshot are available in the same run:
  - Canonical winner is DB
  - A warning code is recorded:
    - `collection_slug_registry_snapshot_out_of_date`
  - The disagreement record includes counts:
    - `db_only_count`, `snapshot_only_count`

## 6. Ingestion / Projection Changes
This first pass updates Channel 66 ingestion/projection to honor split-field semantics.

6.1 Where `collections` and `namespace` are parsed
- In Channel 66 ingestion (Thread 1001 runner/ingester modules):
  - Extract both fields from the parsed YAML representation of `lupopedia.headers` (or from YAML text using the same extraction rules as validator).
  - Do not re-derive them from file path, collection slug patterns, or directory segments.

6.2 How they are stored distinctly in projection
- In `Channel66HeaderProjection` when building the `lupo_metadata` row tree for the artifact entity (`entity_type = channel66_artifact`, deterministic `entity_id`):
  - Write a `property` row for `property_key = "namespace"`:
    - `property_value` is the scalar string from header `namespace`
  - Write a `property` row for `property_key = "collections"`:
    - `property_value` is a deterministic serialization of the header array
    - Required encoding rule for first pass: `json_encode($collections_array, JSON_UNESCAPED_SLASHES)`

No conflation rule:
- Never store `collections` under the `namespace` key.
- Never store `namespace` under the `collections` key.

6.3 Policy vs nav usage separation
- In ingestion-side classification decisions (the steps where ingestion might decide indexing/facets), enforce:
  - Namespace affects policy/scope/validation gating decisions
  - Collections affects nav/grouping classification decisions
- In this first pass, if no downstream indexing tables are written, the separation still must exist in:
  - how divergence warnings are attached (policy vs nav warning grouping)
  - how any derived â€œfacetâ€ properties are named (if any are added later)

6.4 Divergence reporting (header membership vs canonical registry)
- For each file during ingestion:
  - If `collections[]` contains unknown slugs:
    - Do not reject
    - Record divergence warning diagnostics into projection (as properties or logger output):
      - `collections_unknown_slug_count`
      - `collections_unknown_slug_list` (string-serialized)
      - `collections_registry_mode` (db_live or offline_snapshot)
  - If DB and snapshot disagree and DB is used:
    - Record warning code `collection_slug_registry_snapshot_out_of_date`

6.5 Explicit prohibition on path inference
- Ensure ingestion/projection does not do any of:
  - parse `collections` to guess directory or to construct `file_path_from_root`
  - parse `namespace` to guess directory or to construct `file_path_from_root`
- `file_path_from_root` is validated independently by validator behavior (Section 4), and entity identity/hash comes from that validated value only.

## 7. Normalization Tool Skeleton (Dry-Run Only)
First pass normalization tool provides reporting only.

Inputs:
- `--root-path=<path>` (required)
  - Defaults (if omitted by tool owner): `lupo-channels/66/`
- `--scope=<table_docs|all|channel_only>` (optional)
  - For first pass default: `channel_only` (Channel 66 files only), but the scanner must still correctly enforce table-doc rules when it encounters `lupo-docs/database/lupopedia/tables/`.
- `--registry-mode=auto|offline_snapshot|db_live` (optional)
  - Default: `auto` (DB if available, else offline snapshot)

Report output:
- JSON file written to:
  - `lupo-logs/normalization/<timestamp>_collections_namespaces_dry_run_report.json`
- Must include:
  - `generated_ymdhis_utc`
  - `registry_mode`
  - `mismatch_summary`:
    - counts by mismatch category
  - `file_mismatches[]` entries each containing:
    - `file_path_from_root`
    - `actual_repo_relative_path`
    - `severity` (ERROR|WARN)
    - `codes[]` (one or more of the codes in section 4)
    - `suggested_normalization` (dry-run structured suggestion; NO apply)

Mismatch categories (must cover at minimum):
- `namespace_missing_for_table_doc`
- `namespace_invalid_taxonomy`
- `namespace_multi_value_detected`
- `collections_wrong_type_not_array`
- `duplicate_collection_slug`
- `unknown_collection_slug`
- `path_authority_misuse_via_collections`
- `path_authority_misuse_via_namespace`

Apply mode:
- Not enabled in first pass.
- If a developer adds `--apply-mode` later, this skeleton must keep it disabled by default and must not write files in first pass.
- Rollback expectations are deferred until apply-mode exists.

## 8. Test / Fixture Plan
Fixtures required for first pass validator + normalizer behavior.

Create fixture headers under:
- `lupo-tests/fixtures/collections_namespaces/`

Important: The existing `validate_lupopedia_headers.php` determines table-doc scope by file path containing:
- `lupo-docs/database/lupopedia/tables/`

Therefore, for table-doc-specific scenarios, fixtures must be placed so the validator scope detector triggers, OR the validator must be updated to use a more robust table-doc classifier (only if the tool owner implements it, and the artifact must then reflect it). First pass assumes the path-based detector remains.

Minimum fixture set (one file each):

1. valid namespace (table doc)
   - expected: PASS (no ERROR; no WARN)

2. missing namespace on table doc
   - expected: ERROR `namespace_missing_for_table_doc`

3. invalid namespace value
   - expected: ERROR `namespace_invalid_taxonomy` (table doc)

4. multi-value namespace
   - expected: ERROR `namespace_multi_value_detected` (table doc)

5. collections wrong type (collections is scalar)
   - expected: ERROR `collections_wrong_type_not_array`

6. unknown collection slug
   - expected: WARN `unknown_collection_slug`
   - must use offline snapshot fixture to be deterministic

7. duplicate collection slug array
   - expected: WARN `duplicate_collection_slug`

8. valid collections array
   - expected: PASS (no errors; no warnings)

9. path-authority misuse via collections
   - fixture sets:
     - `collections` includes slug `core-docs` (from offline snapshot fixture)
     - `file_path_from_root` mismatches actual file path and includes `core-docs` substring
   - expected: ERROR `path_authority_misuse_via_collections`

10. path-authority misuse via namespace
   - fixture sets:
     - `namespace` = a taxonomy value (e.g. `core`)
     - `file_path_from_root` mismatches actual file path and includes `core` substring
   - expected: ERROR `path_authority_misuse_via_namespace`

11. offline snapshot mode
   - uses:
     - the same header fixtures as `unknown collection slug` and/or `valid collections array`
     - unit test configuration that forces DB unavailability and uses:
       - `lupo-cache/offline/collections_slug_snapshot_federation_1.json`
   - expected:
     - WARN codes (e.g. `unknown_collection_slug`) are produced deterministically
     - diagnostic output includes `registry_mode = "offline_snapshot"`

12. DB-backed canonical slug mode
   - uses:
     - the same header fixtures as `unknown collection slug` and/or `valid collections array`
     - unit test configuration that simulates DB slugs using:
       - `lupo-tests/fixtures/collections_namespaces/db_stub_active_slugs.json`
   - expected:
     - diagnostic output includes `registry_mode = "db_live"`
     - unknown slug warnings are based on the DB-backed slug set from the stub

Test harness assertions (unit tests must check):
- Offline snapshot mode:
  - when DB is not provided, validator uses `offline_snapshot` and still reports unknown slug warnings deterministically
- DB-backed canonical slug mode:
  - when DB-backed slug set is available (simulated via stub), resolver uses `db_live` and diagnostics reflect that mode
- Severity behavior:
  - ERROR cases must be returned as validation errors (non-zero exit for `headers validate`)
  - WARN cases must not fail `headers validate` but must be accessible to ingestion/projection builder / dry-run report generator

Expected test entry points:
1. `php lupo-tests/unit/collections_namespaces_validator_test.php`
2. `php lupo-tests/unit/channel66_collections_namespaces_projection_test.php`

## 9. Deferred Scope
Explicit non-goals for this first implementation pass:
- Production normalization apply mode (no overwrites, no patch writing).
- Auto-create missing collection slugs in DB or any other registry.
- UI/UX refactors that rewire runtime navigation; this pass only guarantees ingestion/projection and validator correctness.
- Any broader channel support beyond the required inheritance path for Channel 66/Thread 1001 ingestion and Thread 1002 validation constraints.
- DB schema/migration changes and TOON updates.
- Reconciliation workflows for divergence between header collections and DB registry state beyond reporting/warn diagnostics.

## 10. Definition of Done
All of the following must be true to mark this first pass complete:

1. Validator correctness
   - The updated header validator enforces namespace taxonomy and single-value behavior with exact ERROR/WARN/IGNORE outcomes for table-doc scope vs non-table-doc scope.
   - The updated header validator enforces collections array typing and produces WARN codes for duplicates/unknown slugs.
   - Path authority misuse via `collections` or `namespace` is rejected with the correct ERROR codes when `file_path_from_root` indicates inferred paths.

2. Canonical slug resolver correctness
   - When DB is unavailable, validator behavior is deterministic using `lupo-cache/offline/collections_slug_snapshot_federation_1.json`.
   - `registry_mode` is recorded as `db_live` or `offline_snapshot` in validator diagnostics/report output.

3. Ingestion/projection split-field behavior
   - The Channel 66 projection builder writes distinct `lupo_metadata` property rows:
     - `property_key="namespace"` with scalar value
     - `property_key="collections"` with deterministic serialized array value
   - No ingestion/projection logic derives `file_path_from_root` (or entity identity/hash) from either `namespace` or `collections`.

4. Normalization dry-run reporting
   - `normalize_collections_namespaces_dry_run.php` produces deterministic mismatch categories and file-level mismatch entries without applying changes.

5. Tests/fixtures
   - The required fixtures exist and unit tests assert expected ERROR vs WARN outcomes.
   - The projection test asserts distinct projection rows for both fields.

## 11. Next Actor Recommendation
HEPHAESTUS/tool owner should proceed to implement the first pass:
- validator severity + misuse detection
- canonical slug resolver + offline snapshot fallback
- ingestion/projection split-field storage
- dry-run normalization report skeleton
- fixtures + unit tests


