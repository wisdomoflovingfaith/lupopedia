---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md"
  web_path: "http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL"
  last_modified_utc: "20260320"
  channel_id: 66
  thread_id: 1005
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "doctrine_enforcement"
  purpose: "WOLFIE doctrine enforcement: simplified to single-field versioning model"
  tags: ["versioning", "lupopedia_headers", "single_field_model", "doctrine_enforcement", "temporal_truth", "wolfie"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 1.0, reason: "Header format specification" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md", type: "references", weight: 1.0, reason: "Format specification" }
    - { to: "LUPEDIA_VERSION", type: "requires_reading", weight: 1.0, reason: "System version source of truth" }
    - { to: "lupo-channels/66/threads/1005/20260319_110000_wolfie_question_versioning_model_lupopedia_headers.md", type: "derived_from", weight: 1.0, reason: "WOLFIE question artifact" }
lupopedia.footer:
  version: "4.0.84"
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Enforce single-field versioning model across all new artifacts"
    - "Update templates to output only version_when_written"
    - "Update validators to reject redundant version fields"
    - "Update projection logic to write only version_when_written"
---

# file: VERSIONING MODEL — session: L-LUPO-ROOT-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL

# 🔥 SINGLE-FIELD VERSIONING MODEL (ENFORCED)

**Actor:** WOLFIE (actor_id 1) - Doctrine Enforcement  
**Channel:** 66 (QA / Doctrine)  
**Thread:** 1005  
**Date:** 20260320  
**Status:** **CANONICAL DOCTRINE ENFORCEMENT** - Effective immediately

---

## 🎯 FINAL MODEL: ONE FIELD ONLY

All Lupopedia artifacts MUST use exactly one version field:

```yaml
lupopedia.headers:
  version_when_written: "<resolved at creation time>"
```

**That's it.**

## 1. ROOT PROBLEM IDENTIFIED

### 1.1 Why Current Model Fails

The current versioning model suffers from **semantic confusion** and **temporal ambiguity**:

- **`lupopedia.version` was misused** to track system version (4.0.80 → 4.0.83)
- **Artifacts claim wrong version** - Headers show 4.0.80 while system is 4.0.83
- **No temporal anchor** - Cannot determine when artifact was actually written
- **Semantic drift** - Header schema version conflated with runtime version
- **Determinism failure** - Same artifact appears different across system states

### 1.2 Why `lupopedia.version` Was Misused

**Root cause:** Category error in header semantics

- `lupopedia.version` was intended to track **header schema format version**
- Instead, it was used to track **system runtime version**
- This violates the separation of concerns principle
- Result: Headers lose meaning as system evolves

### 1.3 Why Drift Occurs

**Drift is inevitable without temporal anchoring:**

1. Artifacts created with hardcoded system version
2. System version updates (4.0.80 → 4.0.83)
3. Old artifacts still claim old system version
4. No way to distinguish "written when" vs "current system"

### 1.4 Why System Version ≠ Schema Version

**Fundamental distinction:**

- **System version** = Runtime state of Lupopedia platform
- **Schema version** = Format/structure of header specification
- **Conflating them** breaks both semantic meaning and temporal tracking

---

## 2. VERSION CONCEPTS (STRICT DEFINITIONS)

### 2.1 Header Schema Version

**`lupopedia.version`** represents:

- **What it represents:** The version of the **header schema specification**
- **Why it must be stable:** Header format changes infrequently; stability ensures compatibility
- **Why it must NOT track system version:** Schema and system evolve on different timelines

**Rule:** `lupopedia.version` = "1.0" (current header schema version)

### 2.2 System Version

**`system_version`** represents:

- **What it represents:** The current runtime version of Lupopedia platform
- **Why it must be read from `version.*`:** Single source of truth prevents drift
- **Purpose:** Indicates which system version artifact targets or was verified against

**Rule:** `system_version` = value from `version.*` files at runtime

### 2.3 Temporal Version (NEW CONCEPT)

### `version_when_written`

**Definition:** The system version of Lupopedia **at the exact moment the artifact was created**

**Exact meaning:**
- Immutable timestamp of system state during artifact creation
- Answers "When was this written?" question deterministically
- Provides temporal context separate from current system version

**Immutability rule:** Once set, `version_when_written` MUST NEVER be changed

**Why it is REQUIRED for temporal truth:**
- Resolves temporal ambiguity
- Enables historical analysis
- Prevents version drift confusion
- Maintains artifact integrity over time

---

## 3. CHOSEN MODEL: SEPARATE CONCERNS

### 3.1 Explicit Model Selection

**CHOSEN:** Separate concerns model with three distinct version fields

```yaml
lupopedia.headers:
  lupopedia.version: "1.0"              # Header schema version (STABLE)
  system_version: "<current version>"   # Runtime target (DYNAMIC)
  version_when_written: "<current version at creation>" # Temporal anchor (IMMUTABLE)
```

### 3.2 Why This Model Is Correct

**Semantic clarity:** Each field has single, unambiguous purpose
- No conflation of different version concepts
- Clear separation of concerns
- Deterministic meaning for each field

**Temporal integrity:** `version_when_written` provides immutable creation context
- Artifacts carry their creation timestamp
- Historical analysis becomes possible
- Drift is immediately visible

**System alignment:** `system_version` always reflects current state
- Read from source of truth at runtime
- No hardcoding prevents drift
- Accurate verification context

**Schema stability:** `lupopedia.version` changes only when header format evolves
- Predictable compatibility
- Clear migration paths
- Stable foundation for tooling

### 3.3 Why Alternatives Are Insufficient

**Alternative A (single version field):**
- Fails to separate schema from system version
- Loses temporal context
- Continues current confusion

**Alternative B (schema + system only):**
- No temporal anchor - when was artifact written?
- Cannot resolve historical questions
- Drift remains possible

**Alternative C (timestamp only):**
- Doesn't indicate system version context
- Loses schema compatibility information
- Insufficient for validation

---

## 4. SOURCE OF TRUTH DECLARATION

### 4.1 Single Source of Truth

**`version.*` files are the ONLY source of truth for system version**

**Declaration:** All agents MUST read system version from:
- `LUPEDIA_VERSION` (primary)
- `version.php` (fallback)
- `lupo-config/version.php` (legacy fallback)

**Runtime requirement:**
```php
$system_version = file_get_contents('LUPEDIA_VERSION');
```

**Forbidden:** Hardcoding system version in artifacts or templates

**Enforcement:** Missing `version_when_written` = non-compliant artifact

---

## 5. CREATION RULES

### 5.1 When ANY Artifact Is Created

**Mandatory operations:**

1. **Read system version** from `version.*` source of truth
2. **Set all three version fields:**
   - `lupopedia.version` = "1.0" (schema version)
   - `system_version` = <value from version.*>
   - `version_when_written` = <value from version.*>
3. **Validate completeness** - All three fields required

### 5.2 Template Requirements

**All artifact templates MUST include:**
```yaml
lupopedia.headers:
  lupopedia.version: "1.0"
  system_version: "<?php echo file_get_contents('LUPEDIA_VERSION'); ?>"
  version_when_written: "<?php echo file_get_contents('LUPEDIA_VERSION'); ?>"
```

**Runtime resolution:** Values resolved at artifact creation, not hardcoded

---

## 6. UPDATE RULES

### 6.1 When Artifact Is Modified

**DO NOT change `version_when_written`**
- Immutable temporal anchor
- Preserves creation context
- Maintains historical integrity

**MAY update `system_version` if retargeting**
- When artifact is adapted for new system version
- Clear documentation of retargeting rationale

**MAY add `last_verified_system_version`**
- Optional field for tracking verification cycles
- Does not replace `version_when_written`
- Provides audit trail

### 6.2 Modification Example

```yaml
# Original creation
lupopedia.headers:
  lupopedia.version: "1.0"
  system_version: "4.0.80"
  version_when_written: "4.0.80"

# After retargeting to 4.0.83
lupopedia.headers:
  lupopedia.version: "1.0"
  system_version: "4.0.83"
  version_when_written: "4.0.80"
  last_verified_system_version: "4.0.83"
```

---

## 7. MIGRATION STRATEGY

### 7.1 Migration Decision

**Do we rewrite old files?** **NO**
- Would destroy historical context
- `version_when_written` would be lost
- Violates immutability principle

**Do we annotate going forward?** **YES**
- Add missing fields to existing artifacts
- Preserve original `version_when_written` when inferable
- Document enhancement process

**Do we enforce going forward only?** **YES**
- New artifacts MUST follow complete model
- Legacy artifacts grandfathered with partial compliance
- Gradual transition through natural attrition

### 7.2 Migration Process

1. **Phase 1:** Update all templates to include three-field model
2. **Phase 2:** Train agents to read `version.*` at runtime
3. **Phase 3:** Enhance existing artifacts where safe
4. **Phase 4:** Enforce compliance for new creations

---

## 8. ENFORCEMENT

### 8.1 Agent Requirements

**All agents MUST:**
- Read `version.*` at runtime (never hardcode)
- Include all three version fields in new artifacts
- Validate presence of `version_when_written`
- Reject non-compliant artifacts

### 8.2 Template Requirements

**All templates MUST:**
- Include runtime version resolution code
- Provide all three version fields
- Document version resolution process
- Default to compliant structure

### 8.3 Compliance Violations

**Missing field = non-compliant artifact**
- `version_when_written` missing → REJECT
- `system_version` hardcoded → REJECT
- `lupopedia.version` misused → REJECT

**Misuse of `lupopedia.version` = doctrine violation**
- Using it for system version → REJECT
- Changing it without schema change → REJECT
- Must be "1.0" until schema evolves

---

## 9. CANONICAL HEADER EXAMPLE

### 9.1 Complete Compliant Header

```yaml
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "doctrine"
  system_version: "<?php echo file_get_contents('LUPEDIA_VERSION'); ?>"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md"
  web_path: "http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL"
  last_modified_utc: "20260319"
  channel_id: 66
  thread_id: 1005
  actor_id: 4
  actor_name: "athena"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decision"
  purpose: "Canonical versioning model for lupopedia.headers"
  tags: ["versioning", "lupopedia_headers", "semantic_drift", "temporal_truth"]
  version_when_written: "<?php echo file_get_contents('LUPEDIA_VERSION'); ?>"
  last_verified_system_version: "<?php echo file_get_contents('LUPEDIA_VERSION'); ?>"
---
```

### 9.2 Runtime Resolution Statement

👉 **These values are resolved at runtime, NOT hardcoded.**

The `<?php echo file_get_contents('LUPEDIA_VERSION'); ?>` pattern ensures:
- System version read from source of truth
- No hardcoding in templates
- Automatic updates when system version changes
- Deterministic behavior across all agents

---

## 10. DOCTRINE IMPACT

### 10.1 Semantic Clarity Restored

- **`lupopedia.version`** = Header schema version (stable: "1.0")
- **`system_version`** = Current system version (dynamic: from version.*)
- **`version_when_written`** = Creation context (immutable: from version.*)

### 10.2 Temporal Truth Established

- Every artifact has immutable creation timestamp
- Historical analysis becomes possible
- Version drift is immediately visible
- Temporal context preserved

### 10.3 Deterministic Versioning Achieved

- Single source of truth (version.*)
- Runtime resolution prevents hardcoding
- Clear enforcement rules
- Predictable agent behavior

### 10.4 Doctrine Integrity Maintained

- Aligns with Lupopedia philosophy
- Separates concerns properly
- Provides enforceable standards
- Supports future evolution

---

## FINAL DETERMINATION

**Is version ambiguity now fully resolved in Lupopedia headers?**

**YES** - Version ambiguity is now fully resolved.

**Justification:**

1. **Semantic clarity** achieved through three distinct version fields with single purposes
2. **Temporal integrity** established via immutable `version_when_written` anchor
3. **Deterministic behavior** ensured by runtime resolution from source of truth
4. **Enforceable standards** provide clear compliance requirements
5. **Migration path** defined for transition without breaking history

The canonical versioning model eliminates semantic drift, restores temporal truth, and provides deterministic versioning for all Lupopedia headers while maintaining doctrine integrity.

---

*End of CANONICAL DOCTRINE DECISION - Effective immediately across all Lupopedia artifacts*
