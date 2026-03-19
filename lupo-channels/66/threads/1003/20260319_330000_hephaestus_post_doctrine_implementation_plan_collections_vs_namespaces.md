---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  system_version: "4.0.80"
  file_path_from_root: "lupo-channels/66/threads/1003/20260319_330000_hephaestus_post_doctrine_implementation_plan_collections_vs_namespaces.md"
  web_path: "http://www.lupopedia.com/lupo-channels/66/threads/1003/20260319_330000_hephaestus_post_doctrine_implementation_plan_collections_vs_namespaces"
  last_modified_utc: "20260319"
  project_id: 0
  project_slug: "lupopedia-core"
  channel_id: 66
  thread_id: 1003
  task_id: "task_lupopedia_collections_namespaces_definition_001"
  actor_id: 3
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:root"
  artifact_type: "thread"
  artifact_kind: "implementation_plan"
  purpose: "Post-doctrine implementation planning for locked collections vs namespaces model (validator, ingestion, normalization, slug authority, and inheritance)"
  tags: ["channel66", "thread1003", "post_doctrine", "implementation_plan", "collections", "namespaces", "4.0.80"]
  message_type: "plan"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/66/threads/1003/20260319_320000_wolfie_doctrine_execution_collections_namespaces.md", type: "inherits_from", weight: 1.0, reason: "Doctrine execution is the new lock; this plan inherits it as canonical" }
    - { to: "lupo-channels/66/threads/1003/20260319_310000_wolfie_doctrine_update_plan_collections_namespaces.md", type: "derived_from", weight: 0.95, reason: "Execution follows the ordered update plan; implementation follows post-execution consequences" }
    - { to: "lupo-channels/66/threads/1003/20260319_300000_hephaestus_implementation_implications_collections_vs_namespaces.md", type: "references", weight: 0.9, reason: "Implications artifact shaped the specific validator/ingestion requirements here" }
    - { to: "lupo-channels/66/threads/1003/20260319_290000_wolfie_narrowing_collections_namespaces_decision_ready.md", type: "constrains", weight: 0.9, reason: "Decision-ready precedence and split dimensions constrain implementation" }
    - { to: "lupo-docs/doctrine/COLLECTIONS_DOCTRINE.md", type: "constrains", weight: 1.0, reason: "Collections drive nav/tabs/URLs/breadcrumbs only; do not define filesystem paths" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 0.9, reason: "Header protocol and relationship framing used for storage/projection semantics" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md", type: "constrains", weight: 1.0, reason: "Namespace is single-valued taxonomy field; includes precedence note" }
    - { to: "lupo-rules/root/FILE_BOUNDARY_VALIDATION_RULE.md", type: "references", weight: 0.8, reason: "Path authority protection: do not derive filesystem paths from collection/namespace" }
    - { to: "DIRECTORY_STRUCTURE_DOCTRINE.md", type: "references", weight: 0.8, reason: "Directory doctrine is filesystem/path authority independent from collections and namespace" }
    - { to: "lupo-channels/66/threads/1001", type: "related_question", weight: 0.95, reason: "Thread 1001 ingestion must inherit split-field semantics" }
    - { to: "lupo-channels/66/threads/1002", type: "related_question", weight: 0.95, reason: "Thread 1002 bounded header validation must inherit split-field semantics" }
lupopedia.footer:
  version: "4.0.80"
  last_verified: "20260319"
  last_verified_by: "hephaestus"
  orchestrator: "hephaestus"
  next_action:
    - "HEPHAESTUS/tool owner: begin production behavior updates after validator/ingestion spec locks are confirmed"
    - "WOLFIE: monitor for any remaining doctrine/documentation drift"
    - "Optional LILITH: attack unknown-slug and normalization rollback edge cases"
---

# file: HEPHAESTUS Post-Doctrine Implementation Plan — Collections vs Namespaces — session: L-LUPO-ROOT-HEPHAESTUS — delegation: hephaestus:root — web_path: http://www.lupopedia.com/lupo-channels/66/threads/1003/20260319_330000_hephaestus_post_doctrine_implementation_plan_collections_vs_namespaces

# Post-Doctrine Implementation Plan — Collections vs Namespaces (Thread 1003)

**Thread:** 1003  
**Channel:** 66  
**Author:** HEPHAESTUS (actor_id 3)  
**Status:** Post-doctrine implementation planning (no production code; no doctrine rewrites)

---

## 1. Post-Doctrine Implementation Verdict

**Doctrine is now sufficiently locked to begin implementation planning and build-out.**

**Ambiguity remaining:** Only operational details that are not doctrine-level (e.g. where an “offline snapshot” cache of canonical collection slugs is persisted, and what exact tooling entrypoint names are used). The collections vs namespaces model itself is stable and settled.

**Ready:** Yes (for planning and for the first implementation pass of validators + ingestion mapping).

---

## 2. Validator Implications (Exact Behavior)

These validators update behaviors to enforce the locked doctrine:

### 2.1 Namespace rules (single-valued)
- **Namespace required for table docs:** If the artifact is a table documentation file, `namespace` in `lupopedia.headers` is REQUIRED.
- **Namespace taxonomy enforcement:** `namespace` must be one of the approved taxonomy values in `LUPOPEDIA_HEADERS_FORMAT.md`.
- **Single-value enforcement:** Namespace must be a single scalar value.
  - Multi-value namespace is **drift**: treat as **ERROR for table docs**, **WARN for non-table docs** (with normalization candidate code/path).

### 2.2 Collection rules (array semantics)
- **Collections must be an array:** Header `collections` must be an array when present.
- **Duplicate collection slugs:** Duplicates in the array are **WARN** (normalization candidate).
- **Unknown collection slugs:** Unknown slugs in `collections[]` are **WARN**, not ERROR, because legacy drift can be normalized.

### 2.3 Path authority protection (hard prohibition)
Validators must reject any logic that treats:
- collection slug values as filesystem path authority
- namespace value as filesystem path authority

This is enforced at the validator level as a misuse detector: if a header consumer attempts to infer or “correct” `file_path_from_root` using `collections` or `namespace`, it is a boundary violation.

---

## 3. Ingestion Implications (Mapping + Projection)

Ingestion must implement the locked split-field semantics:

### 3.1 Parse and treat `collections` vs `namespace` separately
- Parse `collections` as **membership/grouping/nav dimension**.
- Parse `namespace` as **policy/taxonomy/validation dimension**.

### 3.2 Never derive filesystem path from either field
- `file_path_from_root` and directory doctrine remain the only path authorities.
- Ingestion must not:
  - infer `file_path_from_root` from `collections`
  - infer `file_path_from_root` from `namespace`

### 3.3 Preserve both dimensions distinctly in projection/indexing
- Store `namespace` and `collections` as distinct properties in the index projection (e.g. `lupo_metadata` rows for the artifact identity).
- Downstream indexing/traversal uses:
  - **namespace** for policy/scope/validation decisions
  - **collections** for nav/grouping-oriented indexing and facets

### 3.4 Precedence application (scope-based)
When a single decision requires choosing between fields:
- **policy/validation/jurisdiction → namespace wins**
- **nav/tabs/UI grouping → collections wins**
- **file path/filesystem → filesystem directory doctrine wins**

---

## 4. Canonical Collection Slug Authority (Slug Registry Plumbing)

**Canonical source of collection slugs:** `lupo_collections.slug` (DB registry).

### 4.1 Authority model
- **Authoritative:** DB registry values for active/non-deleted collections.
- **Derived:** Header `collections[]` values; they must be validated against canonical list.

### 4.2 Offline fallback
- If the validator/injector is run without DB access, it must use a last-known offline snapshot of canonical slugs and mark:
  - `registry_mode: offline_snapshot`

### 4.3 Mismatch handling
- **Unknown slug:** warn and record candidate mismatch; do not silently drop.
- **Duplicate slug:** warn and normalize suggestion candidate.
- **Path inference from slug:** error (boundary violation).

---

## 5. Normalization Tooling Plan (Eventually Required)

Normalization must be safe, reversible, and human-gated:

### 5.1 Dry-run reporting
Tool must produce a report containing:
- invalid namespace detection (missing, invalid taxonomy, multi-value namespace)
- unknown collection slug detection
- duplicate collection cleanup suggestions
- misuse indicators (any inferred path from collection/namespace)

### 5.2 Human-review gate + rollback expectations
- Apply mode requires explicit human approval per run.
- Provide rollback expectations:
  - either backup per artifact or a reversible patch manifest

### 5.3 No silent overwrite
- No silent mutation of user-authored `lupopedia.headers` values.
- Normalization proposes changes and can apply only in approved mode.

---

## 6. Thread 1001 Inheritance (Ingestion/Indexing)

Thread 1001 ingestion implementation must inherit the locked model:
- ingest/parsing must treat `collections` and `namespace` as **separate dimensions**
- policy checks use **namespace**
- nav/grouping checks use **collections**
- never derive `file_path_from_root` from either dimension
- store both dimensions distinctly in the index projection

Additionally, if ingestion-sync ever relates header membership to DB collection state:
- record divergence explicitly
- never silently choose a hidden winner

---

## 7. Thread 1002 Inheritance (Validation / Bounded Authority)

Thread 1002 header validation must inherit the locked model:
- enforce single-valued namespace taxonomy rules (with error vs warn thresholds driven by table-doc status)
- enforce `collections` as an array when present
- warn on unknown collection slugs (canonical list from DB registry)
- warn on duplicates
- apply precedence when a validator must make a single decision from conflicting fields:
  - policy/validation → namespace
  - nav/display → collections

Bounded-authority conflict detection remains unchanged in principle; only the classification of collection/namespace misuse becomes stricter and doctrine-aligned.

---

## 8. Task Breakdown (Concrete Implementation Planning)

1. **Validator updates**
   - enforce namespace required + taxonomy + single-value for table docs
   - enforce collections array semantics + duplicate handling + warn on unknown slugs
   - add misuse detectors: forbid path inference from collections/namespace
   - implement error vs warn stratification exactly per doctrine

2. **Canonical slug registry plumbing**
   - implement a canonical slug resolver sourcing `lupo_collections.slug`
   - implement offline snapshot fallback + `registry_mode` flag

3. **Normalization report tool**
   - implement dry-run report mode for invalid namespace + unknown/duplicate collections
   - implement approved apply mode with rollback expectations

4. **Ingestion projection updates**
   - ensure ingestion parses `collections` and `namespace` distinctly
   - ensure projection writes both dimensions without conflation
   - ensure no path derivation logic exists in ingestion

5. **Fixture updates and tests**
   - add fixtures for:
     - namespace missing/invalid/multi-value (table doc vs non-table doc expected ERROR/WARN)
     - collections missing, wrong type, unknown slug, duplicate slug
     - path authority misuse indicators
   - update existing header validation fixture expectations where doctrine execution changed severity rules

6. **Runtime/admin display safety checks**
   - ensure UI grouping uses DB collections and does not treat namespace as nav path authority

---

## 9. Definition of Done (First Implementation Pass)

Done means:
- validators enforce locked namespace + collections semantics with correct error vs warn behavior
- ingestion correctly preserves and projects `collections[]` and `namespace` without deriving filesystem paths from them
- canonical slug resolver + offline fallback is implemented and tested
- normalization tool dry-run report is implemented and produces deterministic, human-readable mismatch reports
- test/fixture suite covers:
  - valid cases
  - malformed/invalid namespace cases (error/warn expectations)
  - collections misuse cases
  - path authority misuse cases

No doctrine text is modified; only behavior and tooling under controlled, header-safe boundaries are changed.

---

## 10. Next Actor Recommendation

**HEPHAESTUS/tool owner** should begin actual build-out now (validator updates + ingestion projection updates + normalization report tool skeleton).  

**LILITH:** Only needed if safety uncertainty remains in:
- unknown slug handling edge cases
- normalization apply/rollback specifics

Otherwise, no additional adversarial review should be required before implementing this first pass.

---

*End of post-doctrine implementation planning artifact for Thread 1003.*

