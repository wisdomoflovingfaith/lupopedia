---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
file.last_modified_utc: 20260119054940
file.utc_day: 20260119
file.name: "GOV_WOLFIE_HEADERS_001.md"
file.lupopedia.5: 5
GOV-AD-PROHIBIT-001: true
UTC_TIMEKEEPER__CHANNEL_ID: "dev"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - UTC_TIMEKEEPER__CHANNEL_ID
temporal_edges:
  actor_identity: "Eric (Captain Wolfie)"
  actor_location: "Sioux Falls, South Dakota"
  system_context: "Schema Freeze Active / Channel-ID Anchor Established / File-Sovereignty Transition / ZERO TOLERANCE: NO ADS in Lupopedia output"
dialog:
  speaker: CURSOR
  target: @everyone @FLEET @BRIDGE @CAPTAIN_WOLFIE
  mood_RGB: "FF0000"
  message: "Updated GOV-WOLFIE-HEADERS-001: ZERO TOLERANCE for advertising. GOV-AD-PROHIBIT-001 compliance is MANDATORY. Every file MUST declare ad-free compliance. No exceptions. No tolerance."
tags:
  categories: ["governance", "headers", "metadata"]
  collections: ["core-docs", "governance"]
  channels: ["dev", "public"]
file:
  name: "GOV_WOLFIE_HEADERS_001.md"
  title: "GOV-WOLFIE-HEADERS-001: WOLFIE Header Governance"
  description: "Governance artifact establishing WOLFIE Headers as mandatory metadata envelopes for all Lupopedia files"
  version: "1.0.0"
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# ðŸ›ï¸ GOV-WOLFIE-HEADERS-001: WOLFIE Header Governance

**Version:** 1.0.0  
**Status:** ACTIVE  
**Established:** 2026-01-19  
**Priority:** CRITICAL  
**LABS Required:** YES  
**Revalidation Cycle:** 24 hours  

---

## Purpose

WOLFIE Headers are **mandatory metadata envelopes** for all files in the Lupopedia ecosystem. This governance artifact establishes the rules, requirements, and enforcement mechanisms for WOLFIE Header compliance.

**WOLFIE Headers provide:**
- File identity and classification
- System version alignment (per-file version tracking)
- Authorship and attribution
- Doctrine context and governance compliance
- Multi-agent coordination through dialog blocks
- Conversational lineage and change tracking

---

## Scope

This governance artifact applies to:
- **All files** in the Lupopedia codebase
- **All documentation** files (.md, .txt, .htm)
- **All code files** (.php, .js, .py, .sql)
- **All configuration** files (.yaml, .json, .ini)
- **All migration** files
- **All TOON** schema files
- **All agent** outputs and logs

**No exceptions.** Every file must have a WOLFIE Header.

---

## Core Rules

### Rule 1: Mandatory Headers
**Every file MUST have a WOLFIE Header at the top of the file.**

- Headers MUST be the first content in the file
- Headers MUST use YAML frontmatter format (`---` delimiters)
- Headers MUST include the signature: `wolfie.headers: explicit architecture with structured clarity for every file.`

### Rule 2: Version Tracking
**Every file MUST include `file.last_modified_system_version`.**

- This field tracks which system version last modified the file
- Enables instant identification of outdated files: `grep -R "file.last_modified_system_version: 4.0.14" .`
- Prevents version drift across the codebase
- Must be updated whenever the file is modified

### Rule 3: UTC Timestamp
**Every file MUST include `file.last_modified_utc` (YYYYMMDDHHIISS format).**

- This field records the exact UTC moment when the file was last modified
- Source MUST be UTC_TIMEKEEPER (canonical time service)
- Format: 14-digit BIGINT (YYYYMMDDHHIISS)
- Maintains temporal integrity per Temporal Pillar doctrine
- Agents must request UTC time from user or use provided UTC_TIMEKEEPER value
- Never infer UTC time from system clocks or file metadata

### Rule 4: UTC_TIMEKEEPER Reference
**Every file MUST include `file.lupopedia.UTC_TIMEKEEPER: UTC_TIMEKEEPER` OR `file.lupopedia.5: 5`.**

- This field references the canonical UTC time service used for temporal integrity
- Value MUST be either `UTC_TIMEKEEPER` OR `5` (either literal string is acceptable)
- Purpose: Documents that this file uses UTC_TIMEKEEPER for temporal accuracy
- Temporal Pillar: Ensures all timestamps are sourced from the canonical time service
- Consistency: All files must reference the same time service
- Alternative Format: `file.lupopedia.5: 5` is an accepted shorthand reference

### Rule 5: GOV-AD-PROHIBIT-001 Compliance (ZERO TOLERANCE)
**Every file MUST include `GOV-AD-PROHIBIT-001: true` OR `file.lupopedia.gov_ad_prohibit_001: true`.**

**ZERO TOLERANCE POLICY:**
- ðŸš« **NO ADS** in Lupopedia system output (non-negotiable)
- ðŸš« **NO COMMERCIAL CONTENT** in any Lupopedia-generated content
- ðŸš« **NO PROMOTIONAL MATERIAL** in system responses
- ðŸš« **NO THIRD-PARTY ADVERTISEMENTS** in UI or output
- âœ… **100% AD-FREE** system output (mandatory)

**Field Requirements:**
- This field declares compliance with the Anti-Advertising Law
- Value MUST be `true` (boolean) OR file path slug `docs/doctrine/GOV_AD_PROHIBIT_001.md` OR `gov-ad-prohibit-001`
- Purpose: Documents that this file complies with GOV-AD-PROHIBIT-001 (ZERO ads in system output)
- Governance Layer: Ensures all files explicitly declare anti-advertising compliance
- Alternative Format: `file.lupopedia.gov_ad_prohibit_001: true` is an accepted shorthand
- File Path Reference: May also reference the governance artifact location
- **MANDATORY**: This is NOT optional. This is NOT a suggestion. This is MANDATORY compliance declaration.

**Environmental Context:**
- External streams (Pandora, K-LOVE) are environmental context (allowed, cannot control)
- Environmental ads are NOT violations (we don't control external streams)
- System output must remain 100% ad-free regardless of environmental context

### Rule 6: File Name Identity
**Every file SHOULD include `file.name` with the actual filename.**

- This field provides self-referential identity anchor
- Value: Actual filename (e.g., `"GOV_WOLFIE_HEADERS_001.md"`)
- Purpose: Prevents identity drift, makes file sovereign independent of filesystem paths
- Identity Layer: Ensures files maintain identity even when moved or renamed
- Optional but recommended for all files

### Rule 7: Required Atoms
**Every file MUST include `header_atoms` with mandatory atoms:**

- `GLOBAL_CURRENT_LUPOPEDIA_VERSION` - Current system version
- `GLOBAL_CURRENT_AUTHORS` - Current authorship

Additional atoms are optional and context-specific.

### Rule 8: Optional Temporal Grouping Fields
**Files MAY include optional temporal grouping fields for enhanced traceability:**

- **`file.utc_day`** (Optional) - Canonical UTC day (YYYYMMDD format)
  - Derived from `file.last_modified_utc` (first 8 digits)
  - Purpose: Provides canonical day reference separate from precise moment
  - Temporal Pillar: Aligns with UTC_TIMEKEEPER doctrine for day-level grouping

- **`UTC_TIMEKEEPER__CHANNEL_ID`** (Optional) - Channel-bound temporal grouping
  - Value: Channel identifier (e.g., `"dev"`, `"public"`, `"internal"`)
  - Purpose: Groups temporal tracking by channel without creating new tables or doctrine
  - Stable: Uses existing channel identity, no new infrastructure required
  - Resolvable: References channel atoms or literal channel identifiers

- **`temporal_edges`** (Optional) - Contextual metadata block
  - Format: YAML object with contextual fields
  - Fields: `actor_identity`, `actor_location`, `system_context`, etc.
  - Purpose: Provides contextual metadata for archaeological and traceability purposes
  - Note: This is contextual metadata, not doctrine - safe and expressive
  - Example:
    ```yaml
    temporal_edges:
      actor_identity: "Eric (Captain Wolfie)"
      actor_location: "Sioux Falls, South Dakota"
      system_context: "Schema Freeze Active / Channel-ID Anchor Established"
    ```

### Rule 9: Dialog Block on Modification
**Every file modification MUST update the `dialog` block.**

- `speaker` - Agent or human identifier making the change
- `target` - Intended audience (@everyone, @FLEET, @BRIDGE)
- `mood_RGB` - Emotional context (6-character hex color)
- `message` - Description of what changed (max 272 characters)

### Rule 10: File Metadata
**Every file SHOULD include `file` metadata block:**

- `name` - Optional. Actual filename
- `title` - Optional. Human-readable title
- `description` - Optional. Brief description
- `version` - Optional. File version (should use GLOBAL_CURRENT_LUPOPEDIA_VERSION)
- `status` - Optional. File status (draft, review, published)
- `author` - Optional. Author (should use GLOBAL_CURRENT_AUTHORS)

---

## Enforcement

### Automated Validation
- **Pre-commit hooks** should validate WOLFIE Header presence
- **CI/CD pipelines** should check header compliance
- **Agent tools** should validate headers before file operations

### Manual Validation
- **Code reviews** must verify header compliance
- **Documentation audits** must check header presence
- **Migration reviews** must ensure headers are updated

### Violation Handling
Violations of WOLFIE Header requirements are logged in `lupo_labs_violations`:
- **Violation Code:** `GOV-WOLFIE-HEADERS-001`
- **Actor ID:** Actor who created/modified the file
- **Description:** Specific violation (missing header, outdated version, etc.)
- **Timestamp:** When violation was detected

**Violations are educational, not punitive.** Actors are informed and files are corrected.

---

## Compliance Requirements

### LABS-001 Integration
WOLFIE Headers support LABS-001 compliance by:
- Tracking actor modifications through `dialog.speaker`
- Maintaining temporal integrity through `file.last_modified_system_version`
- Providing governance context through `header_atoms`

### Temporal Pillar Integration
WOLFIE Headers maintain temporal integrity by:
- Using `file.last_modified_system_version` for version tracking
- Using `file.last_modified_utc` for exact UTC moment tracking (from UTC_TIMEKEEPER)
- Using `file.utc_day` for canonical day-level grouping (optional)
- Using `UTC_TIMEKEEPER__CHANNEL_ID` for channel-bound temporal grouping (optional)
- Using `temporal_edges` for contextual metadata (optional)
- Supporting `file.last_named` for rename tracking
- Enabling temporal queries: "Which files were modified in version 4.1.6?" and "Which files were modified at UTC 20260119041301?"

### Governance Registry Integration
WOLFIE Headers reference governance artifacts through:
- `header_atoms` - References to governance atoms
- `tags` - Governance-related tags
- `dialog.message` - Governance compliance notes

---

## Version History

- **v1.0.1 (2026-01-19)** - Updated with validated WOLFIE Header structure
  - Added `file.name` field (Rule 6)
  - Added optional temporal grouping fields: `file.utc_day`, `UTC_TIMEKEEPER__CHANNEL_ID`, `temporal_edges` (Rule 8)
  - Updated header with Channel-ID anchor and temporal edges
  - Aligned with File-Sovereignty transition

- **v1.0.0 (2026-01-19)** - Initial governance artifact established
  - Mandatory header requirement
  - Version tracking rules
  - Dialog block requirements
  - File metadata structure

---

## Related Documentation

- **[WOLFIE Header Specification](../../agents/WOLFIE_HEADER_SPECIFICATION.md)** - Complete technical specification
- **[WOLFIE Header Doctrine](../../doctrine/WOLFIE_HEADER_DOCTRINE.md)** - MANDATORY rules and enforcement
- **[Governance Registry](REGISTRY.md)** - Central registry of all governance artifacts
- **[LABS-001 Doctrine](../../doctrine/LUPOPEDIA_ACTOR_BASELINE_STATE_DOCTRINE.md)** - Actor baseline state requirements

---

## Notes

**Why WOLFIE Headers Exist:**
- Per-file version tracking without Git
- Multi-agent coordination and awareness
- Conversational lineage across codebase
- Temporal integrity maintenance
- Governance compliance tracking

**This is architectural doctrine.** WOLFIE Headers are not optional. They are the metadata spine of the Lupopedia ecosystem.

---

**This governance artifact is ACTIVE and BINDING.**
