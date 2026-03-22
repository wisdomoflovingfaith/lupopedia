---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  system_version: "4.0.80"
  file_path_from_root: "lupo-channels/66/threads/1001/20260319_030000_hephaestus_implementation_plan_revised_p0_bounded_authority_ingestion.md"
  web_path: "http://www.lupopedia.com/lupo-channels/66/threads/1001/20260319_030000_hephaestus_implementation_plan_revised_p0_bounded_authority_ingestion"
  last_modified_utc: "20260319"
  project_id: 0
  project_slug: "lupopedia-core"
  channel_id: 66
  thread_id: 1001
  task_id: "task_channel66_system_audit_review_001"
  actor_id: 3
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:root"
  artifact_type: "thread"
  artifact_kind: "implementation_plan"
  purpose: "Implementation plan for approved revised Thread 1001 P0 header ingestion design with bounded header authority (TOON validation, field preservation, deterministic projection)"
  tags: ["channel66", "thread1001", "implementation_plan", "bounded_authority", "ingestion", "toon_validation", "4.0.80"]
  message_type: "plan"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/66/threads/1001/20260319_010000_hephaestus_p0_ingestion_design_revised_bounded_authority.md", type: "implements", weight: 1.0, reason: "Plan implements approved revised P0 ingestion design" }
    - { to: "lupo-channels/66/threads/1001/20260319_020000_lilith_implementation_gate_revised_p0_ingestion_design.md", type: "constrains", weight: 1.0, reason: "Thread 1001 P0 ingestion must follow Lilith gate requirements" }
    - { to: "lupo-channels/66/threads/1001/20260319_000000_hephaestus_p0_header_ingestion_design_channel66.md", type: "references", weight: 0.8, reason: "Original P0 design as baseline; revised areas overridden" }
    - { to: "lupo-channels/66/threads/1002/20260319_050000_lilith_implementation_gate_hephaestus_bounded_authority.md", type: "constrains", weight: 0.95, reason: "Bounded header authority gate constrains validation and reject/warn semantics" }
    - { to: "lupo-channels/66/threads/1002/20260319_040000_hephaestus_implementation_evidence_bounded_header_authority.md", type: "references", weight: 0.95, reason: "Implementation evidence for conflict detection, field matrix, and performance strategy" }
    - { to: "lupo-channels/66/threads/1002/20260319_020000_wolfie_response_lilith_attack_authority_hierarchy_revision.md", type: "constrains", weight: 0.9, reason: "Authority hierarchy precedence (TOON > header > DB) constrains implementation" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 0.9, reason: "Header storage model and authority boundaries" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md", type: "references", weight: 0.9, reason: "Validator expectations and import/export tooling constraints" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md", type: "constrains", weight: 0.9, reason: "Canonical file order and namespace validation policy" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md", type: "references", weight: 0.8, reason: "Row-based metadata model: root→block→property; edges as repeating structures" }
    - { to: "lupo-rules/root/toon-source-of-truth.md", type: "defends", weight: 0.95, reason: "TOON derived from install SQL and treated as structural truth" }
    - { to: "lupo-channels/66/threads/1001/20260319_200000_wolfie_audit_channel66_system_phase1_thread_repost.md", type: "related_question", weight: 0.7, reason: "Current thread question context" }
    - { to: "lupo-channels/66/threads/1002/20260319_000000_wolfie_question_lupopedia_headers_source_of_truth.md", type: "related_question", weight: 0.75, reason: "Thread 1002 bounded authority question context constraining ingestion" }
lupopedia.footer:
  version: "4.0.80"
  last_verified: "20260319"
  last_verified_by: "hephaestus"
  orchestrator: "hephaestus"
  next_action:
    - "WOLFIE: confirm any missing compatibility-matrix details needed for bounded authority P0 implementation"
    - "HEPHAESTUS/tool owner: begin build-out of the P0 ingestion validator + projection pipeline"
---

# file: HEPHAESTUS Implementation Plan — Revised P0 Bounded Authority Ingestion — session: L-LUPO-ROOT-HEPHAESTUS — delegation: hephaestus:root — web_path: http://www.lupopedia.com/lupo-channels/66/threads/1001/20260319_030000_hephaestus_implementation_plan_revised_p0_bounded_authority_ingestion

# HEPHAESTUS Implementation Plan — Thread 1001 (Channel 66) — Revised P0 Ingestion

**Thread:** 1001  
**Channel:** 66  
**Author:** HEPHAESTUS (actor_id 3)  
**Status:** Implementation planning artifact only; no production code.  

---

## 1. Implementation Planning Verdict

**Ready:** Yes for implementation planning.  
**Complexity:** **Medium**

**Why medium:** The plan must build an end-to-end ingestion pipeline that (1) parses and structurally validates LUPOPEDIA HEADERS, (2) performs bounded-authority P0 conflict detection via TOON/schema comparison, (3) applies the field preservation matrix before DB projection, and (4) enforces deterministic idempotent replace semantics with concurrent-edit safety checks.

---

## 2. Workstream Breakdown

1. **File discovery / parsing**
   - Deterministic enumeration of `lupo-channels/66/**/*.md`
   - Robust LUPOPEDIA HEADERS extraction (first YAML block only) with parse error capture
   - Stateless per-file parse output structure (no global hidden state)

2. **Structural validation**
   - Reuse existing validator behavior where safe (`php lupo.php headers validate` / `lupo-scripts/validate_lupopedia_headers.php`) for baseline checks
   - Extend with “artifact-type-aware” required block/field enforcement (P0 reject behavior)
   - Canonical block order enforcement (warn vs reject rules as per P0/P1 separation)

3. **Bounded-authority validation**
   - Implement P0 conflict detection: header vs TOON/schema structural conflicts (reject)
   - Implement header version compatibility validation (reject incompatible, warn deprecated)
   - Implement P1 separation checks: header vs DB divergence and concurrent-edit flags (warn/flag)

4. **TOON/schema comparison**
   - Load TOON schema from in-repo TOON set (`lupo-database/lupopedia/toon/*.toon.json`) as structural truth
   - Extract searchable schema facts: tables/columns required for projection/mapping checks
   - Implement TOON caching + batch validation grouping by referenced schema files

5. **Field classification (lossless / semantic / lossy / never-projected)**
   - Encode Thread 1002 preservation matrix as ingestion mapping policy
   - Apply classification before generating `lupo_metadata` row payloads
   - Ensure lossless fields are projected exactly; semantic-equivalence can be normalized; lossy fields are stored as display metadata only; never-projected items omitted from authoritative projection

6. **DB projection**
   - Deterministic `entity_type/entity_id` derivation from `file_path_from_root`
   - Idempotent replace semantics for `lupo_metadata` (no duplicate roots per file)
   - Optional lupo_edges projection as a second-phase switch (P0 may remain metadata-only, but plan supports it as a toggle)
   - No DB-side logic; explicit timestamps set in application code

7. **Conflict handling / audit flags**
   - Concurrent edit detection before DB write (abort write or skip overwrite; flag conflict)
   - Structured validation outcomes: `ingested`, `rejected`, `conflict_flagged`, `warnings_present`
   - Persist conflict state into metadata payloads (so downstream indexers can filter without guessing)
   - Logging of conflict type and identifier for operator review

8. **Caching / performance layer**
   - TOON caching: key by TOON file mtime; invalidate on TOON changes
   - Batch validation: group headers by referenced TOON/table(s)
   - Incremental validation (P1): skip unchanged files via hash/mtime, but keep a full re-run mode for correctness

9. **CLI / entrypoint integration**
   - Add an invocation path to run Channel 66 header ingestion with P0 bounded-authority checks
   - Provide flags for `--channel=66`, `--thread=1001`, `--mode=p0|p1|full`, and `--edges=on|off`

10. **Test fixtures**
   - Fixtures for: valid ingestion, malformed YAML reject, required field rejection, TOON schema conflict reject, version incompatibility reject, concurrent-edit flagging, missing edge target handling, preservation-matrix behavior, cache invalidation behavior
   - Fixtures must be LUPOPEDIA-HEADERS compliant (canonical file order) even when intentionally “invalid” for specific fields

---

## 3. Execution Order (Recommended)

1. **Parser output structure**: implement extraction of header blocks into a structured in-memory representation.
2. **Structural validation (P0 gate)**: enforce required blocks/fields and canonical block order with explicit reject outcomes.
3. **TOON loader + caching skeleton**: load TOON JSON schemas, verify they are present/parseable, build a cache key strategy.
4. **Header vs TOON P0 conflict detection**: implement structural conflict checks and ensure P0 rejects before any DB projection.
5. **Version compatibility checking**: implement reject/warn logic and make the compatibility matrix configurable (or explicitly defined) for the run.
6. **Field classification + payload generation**: map parsed header fields into `lupo_metadata` row payloads using Thread 1002 preservation matrix.
7. **Concurrent edit detection + DB replace projection**: enforce “check-before-write”; execute idempotent replace semantics for lupo_metadata.
8. **Conflict outcome persistence + logging**: ensure each file results in an explicit, inspectable status and conflict flags.
9. **Batching + incremental validation (P1)**: add performance improvements without weakening P0 safety.
10. **Testing**: add fixture-based tests around each earlier step; ensure “P0 reject” and “P1 warn” paths are both covered.

**Justification:** Parser + structural validation must be correct before schema checks; TOON caching must exist before repeated validation; field classification must happen before DB projection; concurrency checks must happen immediately before DB writes; then performance and tests can be expanded safely.

---

## 4. File / Module Touchpoints

Likely touches (some are new modules/classes to add):

1. **Existing baseline validator**
   - `lupo-scripts/validate_lupopedia_headers.php` for structural checks (reuse/extend)
   - `lupo-bin/lupo.php` for CLI dispatch location (add a new subcommand for ingestion runner)

2. **New bounded-authority ingestion runner (new)**
   - `lupo-scripts/ingest_channel66_headers_bounded_authority.php` (CLI entry) or a `lupo-bin` subcommand that calls into it

3. **New ingestion core (new)**
   - `lupo-includes/classes/Channel66HeaderIngester.php` (or equivalent)
   - `lupo-includes/classes/BoundedHeaderAuthorityValidator.php`
   - `lupo-includes/classes/ToonSchemaCache.php`
   - `lupo-includes/classes/HeaderFieldPreservationMatrix.php`
   - `lupo-includes/classes/Channel66HeaderProjection.php`

4. **TOON loading helper (new or shared)**
   - A helper to load `lupo-database/lupopedia/toon/<table>.toon.json` and extract:
     - required schema facts for structural conflict checks
     - presence of columns referenced by projection mapping

5. **Logging / audit output (new)**
   - A small logger that prints run summary to stdout and writes a deterministic log file (or channel-thread-local log under `lupo-tools/`), including per-file outcome (`ok/reject/conflict/warn`).

6. **Test fixtures**
   - `lupo-tests/fixtures/channel66_ingestion/` (new fixture directory)
   - PHP test runners under `lupo-tests/unit/` or a targeted runner script (project uses “plain php scripts executed directly”)

---

## 5. P0 / P1 / P2 Implementation Scope

### P0 (first safe implementation pass)
- Parse + structural validation for Channel 66 LUPOPEDIA-HEADERS artifacts
- Bounded-authority P0 conflict detection:
  - header vs TOON/schema structural conflicts → **reject**
  - header version compatibility:
    - incompatible → **reject**
    - deprecated → **warn** (P1 category behavior)
- Field classification matrix applied before DB projection:
  - lossless vs semantic-equivalence vs lossy vs never-projected enforced
- Deterministic DB projection into `lupo_metadata` using idempotent replace semantics
- Concurrent edit detection check immediately before DB write
- TOON caching + batch validation (performance improvements that do not weaken P0 checks)

### P1 (soon after P0)
- Header vs DB divergence detection (warn/flag only)
- Incremental validation (skip unchanged files) with safe full re-run mode
- Optional lupo_edges projection (if used by indexing) once metadata ingestion is stable

### P2 (future hardening)
- Round-trip/exports (DB → YAML) consistency testing against field matrix
- Advanced conflict/merge strategies beyond flagging
- Wider Channel support (beyond Channel 66) if doctrine allows

---

## 6. Test Strategy (Must Pass Before “Done”)

1. **Valid header ingestion**
   - A known-good Channel 66 markdown fixture ingests and produces deterministic `lupo_metadata` payloads.

2. **Malformed YAML rejection**
   - YAML parse errors must result in P0 reject with explicit parse_error state; no projection tree.

3. **Required field rejection**
   - Missing required `lupopedia.headers` fields must be P0 reject for artifact types that require them.

4. **TOON/schema conflict rejection**
   - Fixtures that request invalid/unknown schema fields in the projection mapping must be rejected at TOON check stage.

5. **Version incompatibility handling**
   - Incompatible header version fails P0 reject; deprecated versions produce P1 warning flags.

6. **Concurrent edit handling**
   - Simulate file mtime change during ingest; assert no overwrite; conflict flagged.

7. **Missing edge target handling**
   - Ingest should continue when `lupopedia.edges` targets cannot be verified; right_object_id must still be deterministic.

8. **Field preservation behavior**
   - Assert lossless fields remain exact.
   - Assert semantic-equivalence fields may normalize.
   - Assert lossy/display-only fields are stored as non-authoritative display metadata.
   - Assert never-projected elements are omitted from authoritative projection.

9. **Cache invalidation behavior**
   - Changing TOON mtime invalidates TOON cache and re-validates; results change accordingly.

---

## 7. Output / Logging Expectations

For each run, the ingestion command should emit:
- **success state**: `ingested` per file
- **reject state**: `rejected` with `reject_type` (parse_error, structural_validation_failure, toon_conflict, version_incompatible)
- **conflict flag state**: `conflict_flagged` with `conflict_type` (concurrent_edit, header_vs_db_divergence in P1)
- **validation warnings**: list of warning codes for P1 warnings (deprecated_version, block_order_warn, etc.)
- **audit trail expectations**:
  - Each file must have an explicit DB-visible or log-visible conflict marker so downstream indexers don’t infer silently.
  - No silent acceptance of structural conflicts.

---

## 8. Blockers / Dependencies

### Blockers to first implementation pass (P0)
- **Compatibility matrix details**: define the exact header version compatibility rules used by bounded authority validation (Thread 1002 evidence references a matrix but doesn’t provide the final values).
- **TOON presence assurance**: confirm in-repo TOON JSON set exists at the expected path (`lupo-database/lupopedia/toon/*.toon.json`) for schema checks during ingestion runs.

### Blockers to full hardening
- Optional: richer multi-actor merge strategies beyond flagging.
- Optional: export/DB→YAML round-trip tests (P2).

### Non-blocking follow-ups
- Optional lupo_edges projection if indexing prefers it over metadata-only traversal (can be post-P0).
- Full Channel expansion if needed later.

---

## 9. Definition of Done (First Pass)

The first implementation pass is “done” when:
- A Channel 66 ingestion run (thread-scoped to 1001) completes with deterministic per-file outcomes.
- All P0 conflict cases are handled as **reject** with explicit conflict state.
- All P0 valid cases produce deterministic `lupo_metadata` replace projections with correct field preservation behavior.
- Concurrent edit detection prevents silent overwrite and flags the conflict.
- TOON caching/batch validation improves performance without weakening P0 checks (validated by cache invalidation tests).
- All tests in the Test Strategy section pass for the P0 fixture set.

---

## 10. Next Actor Recommendation

**Next actor:** **WOLFIE**  
**Reason:** The only remaining non-code dependency called out is the exact header version compatibility matrix used for bounded-authority P0 reject/warn decisions. Once confirmed, implementation build-out can proceed safely.

If WOLFIE confirms compatibility-matrix values, the next build owner is **HEPHAESTUS/tool owner** to implement the runner + validator + projection layers.

If additional safety uncertainty is found during fixture test design, request a targeted **LILITH** pass; otherwise avoid reopening settled architecture.

---

*End of implementation planning artifact for Thread 1001.*

