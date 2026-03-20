---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/versions/4.0.84/doctrine_changes/README.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.84/doctrine_changes"
  last_modified_utc: "20260320"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "documentation"
  artifact_kind: "doctrine_changes"
  title: "Doctrine Changes - Version 4.0.84"
  purpose: "Documentation of doctrine and policy changes in version 4.0.84"
  tags: ["version", "4.0.84", "doctrine", "policy", "LUPOPEDIA_HEADERS"]
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Validate doctrine compliance across all files"
    - "Ensure baseline rewrite rules are understood"
    - "Monitor doctrine adoption"
---

# file: Doctrine Changes - Version 4.0.84

## Summary

Version 4.0.84 implements a **major doctrine cleanup** by enforcing the single-field versioning model and establishing clear baseline rewrite requirements.

## Major Doctrine Changes

### Single-Field Versioning Model
**Effective:** 4.0.84+

**Key Changes:**
- **Only canonical version field:** `version_when_written`
- **Deprecated fields removed:** `lupopedia.version`, `system_version`, `last_verified_system_version`, standalone `version`
- **Baseline rewrite requirement:** Files with pre-4.0.84 headers must be rewritten

**Impact:**
- Simplified version management
- Consistent version field usage
- Clear upgrade path for legacy files

### Baseline Rewrite Rules
**Location:** LUPOPEDIA_HEADERS_FORMAT.md §2.0

**Requirements:**
- Rewrite headers if `version_when_written` < 4.0.84
- Rewrite if deprecated version keys present
- Set `version_when_written` to current system version
- Remove deprecated keys
- Keep other optional fields

**Trigger Conditions:**
- Missing `version_when_written`
- Pre-4.0.84 version number
- Presence of deprecated version keys

## Updated Doctrine Files

### LUPOPEDIA_HEADERS_FORMAT.md
**Changes:**
- Added §2.0 baseline rewrite requirements
- Updated required fields section
- Removed version field from footer
- Updated examples for single-field model

### VERSIONING_DOCTRINE.md
**Changes:**
- Updated to use 4.0.84 single-field model
- Added Section 2 for single-field versioning
- Updated canonical version references
- Documented baseline rewrite requirements

### VERSIONING_MODEL.md
**Changes:**
- Converted to obsolete stub
- Added deprecation notice
- References to current documentation
- Historical preservation only

## Edge Case Analysis

### LILITH Structural Analysis
**Location:** LUPOPEDIA_HEADERS/README.md

**Identified Issues:**
- LILITH-001: Dual-state artifacts (handwritten + database)
- LILITH-002: Grounding evidence verification
- LILITH-003: Collections absence ambiguity
- LILITH-004: Case normalization gap
- LILITH-005: External rule conflict resolution

**Purpose:** Identify structural blind spots for future refinement

## Compliance Requirements

### For New Files
- Must use `version_when_written` only
- Must include `file_path_from_root`
- Must follow canonical block order
- Must not include deprecated version fields

### For Existing Files
- Trigger baseline rewrite on edit if pre-4.0.84
- Remove deprecated version keys
- Update to current version number
- Preserve other optional fields

### For Validators
- Enforce single-field versioning model
- Reject deprecated version keys
- Require baseline rewrite for legacy files
- Validate canonical block order

## Impact Analysis

### Positive Impact
- **Simplified versioning** - Single field eliminates confusion
- **Consistent documentation** - Uniform version handling
- **Clear upgrade path** - Defined rewrite requirements
- **Better validation** - Stricter compliance rules

### Transition Impact
- **Legacy file updates** - Automatic rewrite on edit
- **Tooling updates** - Validators and generators updated
- **Documentation updates** - All references current
- **Training needed** - Team education on new model

## Related Files

- [LUPOPEDIA_HEADERS_FORMAT.md](../../../doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md)
- [VERSIONING_DOCTRINE.md](../../../doctrine/VERSIONING_DOCTRINE.md)
- [LUPOPEDIA_HEADERS README](../../../doctrine/LUPOPEDIA_HEADERS/README.md)
- [Baseline Rewrite Rule](../../../lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md)

## Validation

### Pre-deployment Checks
- [x] Verify doctrine updates complete
- [x] Test baseline rewrite rules
- [x] Validate edge case analysis

### Post-deployment Checks
- [ ] Monitor doctrine compliance
- [ ] Validate baseline rewrite effectiveness
- [ ] Assess team understanding and adoption

---

*Last updated: 2026-03-20*
