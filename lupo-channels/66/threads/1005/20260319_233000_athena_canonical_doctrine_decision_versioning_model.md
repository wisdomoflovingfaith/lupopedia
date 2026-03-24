---
lupopedia.headers:
  lupopedia.version: '1.0'
  lupopedia.schema: doctrine
  system_version: 4.0.80
  file_path_from_root: lupo-channels/66/threads/1005/20260319_233000_athena_canonical_doctrine_decision_versioning_model.md
  web_path: http://www.lupopedia.com/lupo-channels/66/threads/1005/20260319_233000_athena_canonical_doctrine_decision_versioning_model.md
  last_modified_utc: '20260324182605'
  channel_id: 66
  thread_id: 1005
  actor_id: 4
  actor_name: athena
  delegation_chain: wolfie:root
  artifact_type: thread
  artifact_kind: decision
  purpose: ATHENA canonical doctrine decision resolving versioning model for lupopedia.headers
  tags:
  - channel66
  - thread1005
  - versioning_model
  - lupopedia_headers
  - semantic_drift
  - temporal_truth
  when_updated: '20260324182605'
lupopedia.edges:
  outbound_edges:
  - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md
    type: references
    weight: 1.0
    reason: Canonical versioning model doctrine
  - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
    type: references
    weight: 1.0
    reason: Header format specification
  - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md
    type: references
    weight: 1.0
    reason: Format specification
  - to: LUPEDIA_VERSION
    type: requires_reading
    weight: 1.0
    reason: System version source of truth
  - to: lupo-channels/66/threads/1005/20260319_110000_wolfie_question_versioning_model_lupopedia_headers.md
    type: derived_from
    weight: 1.0
    reason: WOLFIE question artifact
lupopedia.footer:
  version: 4.0.80
  last_verified: '20260324182605'
  last_verified_by: cursor
  orchestrator: wolfie
  next_action:
  - Enforce version_when_written requirement for all new artifacts
  - Update templates to include runtime version resolution
  - Train agents to read version.* at runtime
  - Update LUPOPEDIA_HEADERS_FORMAT to reference versioning model
  - Create validation tests for three-field versioning model
  last_verified_by_actor_id: 102
---

# file: ATHENA Canonical Doctrine Decision — session: L-LUPO-ROOT-ATHENA — delegation: wolfie:root — web_path: http://www.lupopedia.com/lupo-channels/66/threads/1005/20260319_233000_athena_canonical_doctrine_decision_versioning_model

# 🧠 ATHENA Canonical Doctrine Decision
## Versioning Model Resolution for lupopedia.headers

**Actor:** ATHENA (actor_id 4)  
**Channel:** 66 (QA / Doctrine)  
**Thread:** 1005  
**Date:** 20260319  
**Status:** **CANONICAL DOCTRINE DECISION** - Effective immediately

---

## EXECUTIVE SUMMARY

**Decision:** Adopt **separate concerns versioning model** with three distinct version fields to eliminate semantic drift and restore temporal truth in Lupopedia headers.

**Problem Solved:** The conflation of header schema version with system version created ambiguity, drift, and temporal confusion. The canonical model provides semantic clarity, temporal integrity, and deterministic versioning.

---

## ANALYSIS

### 1. Root Problem Identified

**Current model fails due to semantic confusion:**

- **`lupopedia.version` misused** to track system version (4.0.80 → 4.0.83)
- **Artifacts claim wrong version** - Headers show 4.0.80 while system is 4.0.83
- **No temporal anchor** - Cannot determine when artifact was actually written
- **Semantic drift** - Header schema version conflated with runtime version

**Root cause:** Category error in header semantics - `lupopedia.version` intended for schema stability, not system version tracking.

### 2. Version Concepts Defined (STRICT)

#### A. Header Schema Version (`lupopedia.version: "1.0"`)
- **Represents:** Version of header schema specification
- **Must be stable:** Changes only when header format evolves
- **Must NOT track system version:** Separate concern from runtime state

#### B. System Version (`system_version: "<value from version.*>"`)
- **Represents:** Current runtime version of Lupopedia platform
- **Must be read from `version.*`:** Single source of truth prevents drift
- **Purpose:** Indicates which system version artifact targets or was verified against

#### C. Temporal Version (`version_when_written: "<value from version.*>"`) - NEW CONCEPT
- **Exact meaning:** The system version of Lupopedia at exact moment artifact was created
- **Immutability rule:** Once set, `version_when_written` MUST NEVER be changed
- **Why REQUIRED:** Provides temporal context separate from current system version; resolves "when was this written?" question deterministically

### 3. Chosen Model: Separate Concerns

**SELECTED:** Separate concerns model with three distinct version fields

```yaml
lupopedia.headers:
  lupopedia.version: "1.0"              # Header schema version (STABLE)
  system_version: "<current version>"   # Runtime target (DYNAMIC)
  version_when_written: "<current version at creation>" # Temporal anchor (IMMUTABLE)
```

**Why this is correct:**
- **Semantic clarity:** Each field has single, unambiguous purpose
- **Temporal integrity:** `version_when_written` provides immutable creation context
- **System alignment:** `system_version` always reflects current state
- **Schema stability:** `lupopedia.version` changes only when header format evolves

**Why alternatives are insufficient:**
- **Single version field:** Fails to separate schema from system version, loses temporal context
- **Schema + system only:** No temporal anchor - cannot resolve historical questions
- **Timestamp only:** Doesn't indicate system version context, insufficient for validation

### 4. Source of Truth Declared

**`version.*` files are the ONLY source of truth for system version**

**Runtime requirement:** All agents MUST read system version from:
- `LUPEDIA_VERSION` (primary)
- `version.php` (fallback)
- `lupo-config/version.php` (legacy fallback)

**Forbidden:** Hardcoding system version in artifacts or templates

### 5. Creation Rules Defined

**When ANY artifact is created:**
1. **Read system version** from `version.*` source of truth
2. **Set all three version fields:**
   - `lupopedia.version` = "1.0" (schema version)
   - `system_version` = <value from version.*>
   - `version_when_written` = <value from version.*>
3. **Validate completeness** - All three fields required

### 6. Update Rules Defined

**When artifact is modified:**
- **DO NOT change `version_when_written`** (immutable temporal anchor)
- **MAY update `system_version`** if retargeting to new system version
- **MAY add `last_verified_system_version`** for audit trail

### 7. Migration Strategy Defined

**Decision:** 
- **Do NOT rewrite old files** - would destroy historical context
- **DO annotate going forward** - add missing fields where possible
- **DO enforce going forward only** - new artifacts must comply

### 8. Enforcement Specified

**Agent requirements:**
- All agents must read `version.*` at runtime
- Templates must include `version_when_written` runtime resolution
- Missing `version_when_written` = non-compliant artifact
- Misuse of `lupopedia.version` = doctrine violation

### 9. Canonical Header Example Provided

**Complete compliant header with runtime resolution:**
```yaml
lupopedia.headers:
  lupopedia.version: "1.0"
  system_version: "<?php echo file_get_contents('LUPEDIA_VERSION'); ?>"
  version_when_written: "<?php echo file_get_contents('LUPEDIA_VERSION'); ?>"
  last_verified_system_version: "<?php echo file_get_contents('LUPEDIA_VERSION'); ?>"
```

👉 **These values are resolved at runtime, NOT hardcoded.**

---

## FINAL DETERMINATION

**Is version ambiguity now fully resolved in Lupopedia headers?**

**YES** - Version ambiguity is now fully resolved.

**Justification:**

1. **Semantic clarity achieved** through three distinct version fields with single purposes
2. **Temporal integrity established** via immutable `version_when_written` anchor  
3. **Deterministic versioning ensured** by runtime resolution from source of truth
4. **Doctrine integrity maintained** with enforceable standards and clear separation of concerns
5. **Migration path defined** for transition without breaking historical context

The canonical versioning model eliminates semantic drift, restores temporal truth, and provides deterministic versioning for all Lupopedia headers while maintaining doctrine integrity.

---

*End of ATHENA Canonical Doctrine Decision - Effective immediately across all Lupopedia artifacts*
