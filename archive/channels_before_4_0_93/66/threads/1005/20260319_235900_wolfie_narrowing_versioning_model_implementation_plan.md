---
lupopedia.headers:
  lupopedia.version: '1.0'
  lupopedia.schema: thread
  system_version: 4.0.83
  file_path_from_root: channels/66/threads/1005/20260319_235900_wolfie_narrowing_versioning_model_implementation_plan.md
  web_path: http://www.lupopedia.com/channels/66/threads/1005/20260319_235900_wolfie_narrowing_versioning_model_implementation_plan.md
  last_modified_utc: '20260324182605'
  channel_id: 66
  thread_id: 1005
  actor_id: 1
  actor_name: wolfie
  delegation_chain: wolfie:root
  artifact_type: thread
  artifact_kind: narrowing
  purpose: 'WOLFIE narrowing and implementation planning: operationalizing ATHENA''s
    versioning model doctrine decision'
  traits:
  - narrowing
  - implementation_plan
  - versioning_model
  - operationalization
  - thread_1005
  - wolfie
  tags:
  - narrowing
  - implementation_plan
  - versioning_model
  - lupopedia_headers
  - operationalization
  - channel_66
  - thread_1005
  when_updated: '20260324182605'
lupopedia.edges:
  outbound_edges:
  - to: channels/66/threads/1005/20260319_233000_athena_canonical_doctrine_decision_versioning_model.md
    type: operationalizes
    weight: 1.0
    reason: ATHENA's canonical doctrine decision
  - to: docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md
    type: references
    weight: 1.0
    reason: Canonical versioning model doctrine
  - to: docs/doctrine/LUPOPEDIA_HEADERS/README.md
    type: references
    weight: 1.0
    reason: Header format specification
  - to: docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md
    type: references
    weight: 1.0
    reason: Format specification
  - to: docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md
    type: references
    weight: 0.9
    reason: Header implementation plan
  - to: LUPEDIA_VERSION
    type: requires_reading
    weight: 1.0
    reason: System version source of truth
  - to: includes/version.php
    type: requires_reading
    weight: 1.0
    reason: Version resolution functions
lupopedia.see:
  mappings:
  - - channels/66/threads/1005
    - http://www.lupopedia.com/channels/66/threads/1005
lupopedia.footer:
  version: 4.0.83
  last_verified: '20260324182605'
  last_verified_by: cursor
  orchestrator: wolfie
  next_action:
  - 'HEPHAESTUS: Update templates and validators for three-field versioning model'
  - 'Thread 1005: Ready for implementation execution'
  last_verified_by_actor_id: 102
---

# file: WOLFIE Narrowing — Versioning Model Implementation Plan — session: L-LUPO-ROOT-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/channels/66/threads/1005/20260319_235900_wolfie_narrowing_versioning_model_implementation_plan

# WOLFIE Narrowing — Versioning Model Implementation Plan

**Thread:** 1005  
**Channel:** 66 (QA / Doctrine)  
**Author:** WOLFIE (actor_id 1)  
**Status:** **OPERATIONAL IMPLEMENTATION PLAN** - Ready for execution  
**Date:** 20260319  

---

## 1. Narrowing Verdict

**ATHENA's decision is ACCEPTED AS OPERATIONALLY CORRECT with minor narrowing.**

ATHENA's canonical doctrine decision correctly identifies the versioning problem and provides a comprehensive solution. The decision is sound and requires only operational clarification, not fundamental revision.

**Accepted components:**
- Three-field versioning model (separate concerns)
- Source of truth declaration (version.* files)
- Runtime version resolution requirement
- Immutable temporal anchor (version_when_written)
- Clear field semantics and separation of concerns

**Narrowing applied:**
- PHP templating approach → resolved-value approach for better determinism
- Source of truth order clarification
- Implementation sequence specificity
- Enforcement policy staging

---

## 2. Locked Field Semantics

| Field Name | Required/Optional | Meaning | Mutability Rule | Source of Truth | Allowed Update Behavior |
|-------------|------------------|---------|----------------|------------------|----------------------|
| `lupopedia.version` | **Required** | Header schema version | **Immutable** (changes only when header format evolves) | Header schema specification | Never for system version tracking |
| `system_version` | **Required** | Current system version artifact applies to | **Mutable** (when retargeting) | `version.*` files at runtime | May update when retargeting to new system version |
| `version_when_written` | **Required** (new artifacts) | System version at exact creation moment | **Immutable** (NEVER change after creation) | `version.*` files at creation time | Never modify - preserves temporal integrity |
| `last_verified_system_version` | Optional | System version at verification time | **Mutable** (verification updates) | Current system version at verification | May add during reviews, does not replace creation anchor |

**Explicit statements:**
- `lupopedia.version` is **NOT** same thing as `system_version`
- `version_when_written` is **immutable once written**
- `system_version` and `version_when_written` are **separate concerns**

---

## 3. Source-of-Truth Decision

**Exact order for resolving current system version:**

1. **`LUPEDIA_VERSION`** (primary source of truth)
2. **`includes/version.php`** functions (primary runtime interface)
3. **`config/version.php`** (legacy fallback - if exists)

**Canonical source definitions:**
- **Current system version:** `LUPEDIA_VERSION` file content
- **Header schema version:** `docs/doctrine/LUPOPEDIA_HEADERS/README.md` definition
- **Runtime resolution:** Use `lupopedia_get_version()` function from `includes/version.php`

**Forbidden practices:**
- Hardcoding system version in artifacts or templates
- Copying old headers without updating version fields
- Using `lupopedia.version` for system version tracking

---

## 4. Required Doctrine Updates

### 4.1 VERSIONING_MODEL.md (REQUIRED)
- **Status:** Already exists and complete
- **Action:** No changes needed - ATHENA's artifact is comprehensive and correct
- **Justification:** Doctrine decision already documented with full specifications

### 4.2 README.md (REQUIRED)
- **Section to update:** Version field definitions section
- **Change:** Add explicit clarification that `lupopedia.version` ≠ `system_version`
- **Why:** Prevents future conflation of schema version with system version

### 4.3 LUPOPEDIA_HEADERS_FORMAT.md (REQUIRED)
- **Section to update:** Required fields list
- **Change:** Add `version_when_written` to required fields list
- **Why:** Enforces temporal anchor requirement for new artifacts

### 4.4 LUPOPEDIA_HEADERS_PLAN.md (OPTIONAL)
- **Status:** Reference for implementation planning
- **Action:** Add reference to VERSIONING_MODEL.md as canonical versioning doctrine
- **Why:** Ensures implementation follows established model

---

## 5. Required Template / Generator / Agent Changes

### 5.1 Artifact Templates (IMMEDIATE)
**All new artifact templates MUST include:**
```yaml
lupopedia.headers:
  lupopedia.version: "1.0"
  system_version: "<resolved from version.*>"
  version_when_written: "<resolved from version.*>"
```

**Runtime resolution approach:**
- Use resolved values, not PHP code in templates
- Agents must read `version.*` at artifact creation time
- Templates provide placeholder structure for runtime population

### 5.2 Agent Instructions (IMMEDIATE)
**All agents MUST:**
- Read system version from `version.*` before creating artifacts
- Never hardcode system version (e.g., "4.0.80", "4.0.83")
- Include all three version fields in new artifacts
- Validate presence of `version_when_written` before accepting artifacts

### 5.3 Validators (IMMEDIATE)
**Validator requirements:**
- Reject artifacts missing `version_when_written` (new creations)
- Reject artifacts using `lupopedia.version` for system version
- Warn on artifacts with stale `system_version` values (legacy)
- Enforce immutability of `version_when_written` field

### 5.4 Code Generators (IMMEDIATE)
**Generator requirements:**
- Include runtime version resolution in generated headers
- Default to three-field versioning model
- Provide clear documentation of version resolution process

---

## 6. Validator Enforcement Policy

### 6.1 P0 (Immediate Enforcement)
**Reject immediately:**
- Missing `version_when_written` on newly created artifacts
- Missing `lupopedia.version` field entirely
- Malformed version values (non-semantic version format)
- Using `lupopedia.version` to track system version

**Error examples:**
```yaml
# INVALID - Missing version_when_written
lupopedia.headers:
  lupopedia.version: "1.0"
  system_version: "4.0.83"
# Should be rejected

# INVALID - Using lupopedia.version for system version
lupopedia.headers:
  lupopedia.version: "4.0.83"  # WRONG - this is schema version
  system_version: "4.0.83"
  version_when_written: "4.0.83"
```

### 6.2 P1 (Warn-First Transition)
**Warn but accept:**
- Legacy artifacts with stale `system_version` (e.g., "4.0.80" when current is "4.0.83")
- Artifacts created before doctrine lock lacking `version_when_written`
- Historical files with ambiguous version context

**Warning examples:**
```yaml
# WARN - Legacy system_version
lupopedia.headers:
  lupopedia.version: "1.0"
  system_version: "4.0.80"  # STALE but acceptable historically
  version_when_written: "4.0.80"
```

### 6.3 P2 (Later Hardening)
**Implement later:**
- Audit tooling for drift detection and reporting
- Auto-detection of template misuse patterns
- Migration tools for adding missing fields to legacy artifacts

---

## 7. Migration Policy for Existing Files

**Decision: Do NOT rewrite old files - preserve historical context**

### 7.1 Files with `lupopedia.version: "4.0.80"` and `system_version: "4.0.80"`
**Treatment:** Leave as-is with historical context preserved
- These represent valid system state at time of creation
- Rewriting would destroy temporal integrity
- Add `version_when_written: "4.0.80"` where safe and appropriate

### 7.2 Files missing `version_when_written`
**Treatment:** Annotate going forward where possible
- Add missing field with inferred creation version
- Document annotation process and rationale
- Do not modify original content beyond version field addition

### 7.3 New artifact requirement window
**Implementation:** Immediate enforcement for new creations
- All artifacts created after doctrine lock MUST follow three-field model
- No grandfather period - compliance required immediately
- Clear communication of transition to all agents

---

## 8. Correct Canonical Example

```yaml
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "doctrine"
  system_version: "4.0.83"
  file_path_from_root: "docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md"
  web_path: "http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL"
  last_modified_utc: "20260319"
  channel_id: 66
  thread_id: 1005
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "narrowing"
  purpose: "WOLFIE narrowing and implementation planning: operationalizing ATHENA's versioning model doctrine decision"
  version_when_written: "4.0.83"
  last_verified_system_version: "4.0.83"
---
```

**Key characteristics:**
- `lupopedia.version: "1.0"` (header schema version - stable)
- `system_version: "4.0.83"` (current system version - from source of truth)
- `version_when_written: "4.0.83"` (immutable temporal anchor)
- Values are resolved, not hardcoded

---

## 9. Implementation Order

### 9.1 Immediate Sequence (Next 24 hours)
1. **WOLFIE** (now) - Lock operational model with this narrowing artifact
2. **HEPHAESTUS** (next) - Update templates and validators for three-field model
3. **HEPHAESTUS** (following) - Implement runtime version resolution in generators
4. **HEPHAESTUS** (following) - Deploy updated validators with P0/P1 enforcement
5. **LILITH** (after) - Review enforcement safety and compliance coverage

### 9.2 Handoff Criteria
- HEPHAESTUS proceeds when this narrowing artifact is complete
- Templates updated with three-field versioning
- Validators enforce `version_when_written` requirement
- Runtime version resolution implemented

---

## 10. Final Statement

**Is versioning model now operationally clear enough to enforce across new Lupopedia artifacts?**

**YES** - Versioning model is now operationally clear enough to enforce.

**Justification:**

1. **Semantic clarity achieved** - Three distinct version fields with unambiguous purposes
2. **Temporal integrity established** - Immutable `version_when_written` provides creation context
3. **Deterministic enforcement defined** - Clear P0/P1/P2 policy with specific violations
4. **Implementation sequence specified** - Exact handoff from WOLFIE to HEPHAESTUS to LILITH
5. **Source of truth locked** - `version.*` files as single authoritative source
6. **Migration policy deterministic** - Preserve legacy, enforce new compliance
7. **Template requirements explicit** - Resolved values, runtime resolution, no hardcoding
8. **Field semantics immutable** - Clear separation of concerns with mutability rules

ATHENA's doctrine decision is now operationalized with precise implementation guidance, enabling HEPHAESTUS to execute deterministic versioning enforcement across all Lupopedia artifacts while preserving historical integrity.

---

*End of WOLFIE narrowing — Versioning model implementation plan*
