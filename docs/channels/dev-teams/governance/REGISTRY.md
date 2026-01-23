---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
file.last_modified_utc: 20260119052907
file.utc_day: 20260119
file.name: "REGISTRY.md"
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
  system_context: "Schema Freeze Active / Channel-ID Anchor Established / File-Sovereignty Transition"
dialog:
  speaker: CURSOR
  target: @everyone @CAPTAIN_WOLFIE @SYSTEM @FLEET
  mood_RGB: "00FF00"
  message: "Updated REGISTRY.md with validated WOLFIE Header structure: file.utc_day, UTC_TIMEKEEPER__CHANNEL_ID, temporal_edges. Channel-ID anchor established. GOV-WOLFIE-HEADERS-001 version updated to 1.0.1."
tags:
  categories: ["documentation", "governance", "registry", "compliance"]
  collections: ["core-docs", "governance"]
  channels: ["dev", "public", "internal", "governance"]
file:
  name: "REGISTRY.md"
  title: "Lupopedia Governance Registry"
  description: "Canonical registry of all governance artifacts, their status, and compliance requirements"
  version: "1.0.0"
  status: published
  author: GLOBAL_CURRENT_AUTHORS
system_context:
  schema_state: "Frozen"
  table_count: 181
  table_ceiling: 181
  table_count_violation: false
  table_count_overage: 0
  database_logic_prohibited: true
  weekend_version_freeze: true
  weekend_freeze_days: [0, 5, 6]
  max_weekend_branches: 2
  governance_active: ["GOV-AD-PROHIBIT-001", "LABS-001", "GOV-WOLFIE-HEADERS-001", "TABLE_COUNT_DOCTRINE", "LIMITS_DOCTRINE"]
  doctrine_mode: "File-Sovereignty"
registry_metadata:
  last_updated_ymdhis: 20260119052907
  total_artifacts: 3
  active_artifacts: 3
  deprecated_artifacts: 0
  compliance_required: true
  validation_cycle: "24h"
---

# ðŸ›ï¸ Lupopedia Governance Registry

## ðŸ“‹ Overview

This registry serves as the **canonical source** for all governance artifacts within Lupopedia. All actors, agents, and modules must reference this registry to determine which governance artifacts are active and binding.

**Registry ID**: `GOV-REGISTRY-001`  
**Status**: ACTIVE & CANONICAL  
**Version**: 1.0.0  
**Temporal Anchor**: 2026-01-19 03:00:00 UTC

---

## ðŸ“Š Registry Index

| Artifact Code | Title | Version | Status | Established | Priority | LABS Required | Revalidation |
|---------------|-------|---------|--------|-------------|----------|---------------|--------------|
| [GOV-AD-PROHIBIT-001](#gov-ad-prohibit-001) | Anti-Advertising Law | 1.0.0 | **ACTIVE** | 2026-01-18 | Critical | **YES** | 24h |
| [LABS-001](#labs-001) | Lupopedia Actor Baseline State | 1.0.0 | **ACTIVE** | 2026-01-19 | Critical | **YES** | 24h |
| [GOV-WOLFIE-HEADERS-001](#gov-wolfie-headers-001) | WOLFIE Header Governance | 1.0.1 | **ACTIVE** | 2026-01-19 | Critical | **YES** | 24h |

---

## ðŸ“œ Artifact Details

### GOV-AD-PROHIBIT-001
**Anti-Advertising Law**

#### Metadata
- **Code**: GOV-AD-PROHIBIT-001
- **Version**: 1.0.0
- **Status**: ACTIVE & BINDING
- **Established**: 2026-01-18
- **Priority**: Critical
- **Stability**: Stable
- **Location**: `docs/doctrine/GOV_AD_PROHIBIT_001.md`

#### Purpose
Prevent any actor, agent, or subsystem from injecting commercial, promotional, or manipulative advertising into the Lupopedia ecosystem.

#### Scope
- All actors (human + system)
- All agents (kernel + domain)
- All modules (HELP, LIST, LABS, CONTENT, etc.)
- All external bridges (Pandora, Kâ€‘LOVE, RSS, APIs)

#### Core Rules
1. No actor may display, embed, or transmit advertisements
2. No agent may generate or recommend commercial content
3. No module may include sponsored links, affiliate codes, or paid placements
4. External audio/video/text streams must be treated as environmental context only
5. Violations must be logged in `lupo_labs_violations` with violation_code = "GOV-AD-PROHIBIT-001"

#### Environmental Context
- **Bio-to-human interfaces beyond visual and hearing are allowed** as environmental context
- **Exception: Pandora** - External audio stream that cannot be controlled by Lupopedia
- **Critical Distinction**: If it appears in Lupopedia's UI or system output, it's PROHIBITED

#### Enforcement
- Integrated with LABS-001 for baseline behavior governance
- Violations are recorded, not punished
- Actors are educated, not penalized
- System integrity is prioritized over compliance severity

#### LABS Integration
- All actors must acknowledge GOV-AD-PROHIBIT-001 in LABS-001 handshake
- Violations tracked via `lupo_labs_violations` table
- Revalidation required every 24 hours

#### Related Artifacts
- LABS-001 (mandatory pre-interaction protocol)
- Genesis Doctrine (Five Pillars)

---

### LABS-001
**Lupopedia Actor Baseline State**

#### Metadata
- **Code**: LABS-001
- **Version**: 1.0.0
- **Status**: ACTIVE & BINDING
- **Established**: 2026-01-19
- **Priority**: Critical
- **Stability**: Stable
- **Location**: `docs/doctrine/LUPOPEDIA_ACTOR_BASELINE_STATE_DOCTRINE.md`
- **Template**: `docs/templates/LABS_HANDSHAKE_TEMPLATE.md` (v1.1.0 GOV-AD-SYNC)

#### Purpose
Establish mandatory knowledge and declarations required of every actor (human, AI, or system) before participation in Lupopedia.

#### Scope
- All actors (human, AI, system)
- All agents (kernel + domain)
- All modules requiring actor interaction

#### Core Requirements
1. **Temporal Alignment** - UTC timestamp from UTC_TIMEKEEPER
2. **Actor Identity** - Type, identifier, role
3. **Wolf Recognition** - Human architect, Pack leader, System governor, Authority source
4. **Purpose Declaration** - Specific function, scope, limitations
5. **Constraint Awareness** - All applicable governance laws
6. **Prohibited Actions** - Explicit forbidden actions
7. **Task Context** - Current objective and operational context
8. **Truth State** - Known/Assumed/Unknown/Prohibited structure
9. **Governance Compliance** - All applicable governance artifacts
10. **Authority Recognition** - Wolf as governor

#### Validation
- Completeness check (all 10 declarations)
- Temporal accuracy (UTC_TIMEKEEPER source)
- Actor type validation
- Wolf recognition (4 roles required)
- Governance awareness (minimum 3 references)
- Truth state structure validation
- Purpose specificity check
- Authority recognition verification

#### Certificate System
- Unique certificate ID: `LABS-CERT-{UNIQUE_ID}`
- 24-hour revalidation cycle
- Automatic revalidation on context shift
- Revalidation on governance update
- Revalidation after violation remediation

#### Enforcement
- Incomplete declarations â†’ Immediate quarantine
- Inconsistent declarations â†’ Governor notification
- Violations â†’ Truth state recalibration required
- Persistent violations â†’ Potential decommissioning

#### Database Schema
- `lupo_labs_declarations` - Stores declarations and certificates
- `lupo_labs_violations` - Tracks violations for audit

#### Related Artifacts
- GOV-AD-PROHIBIT-001 (integrated into handshake template)
- Genesis Doctrine (Five Pillars)
- UTC_TIMEKEEPER Doctrine (Temporal Integrity)

---

### GOV-WOLFIE-HEADERS-001
**WOLFIE Header Governance**

#### Metadata
- **Code**: GOV-WOLFIE-HEADERS-001
- **Version**: 1.0.1
- **Status**: ACTIVE & BINDING
- **Established**: 2026-01-19
- **Last Updated**: 2026-01-19
- **Priority**: Critical
- **Stability**: Stable
- **Location**: `docs/governance/GOV_WOLFIE_HEADERS_001.md`

#### Purpose
Establish WOLFIE Headers as mandatory metadata envelopes for all files in the Lupopedia ecosystem. Ensures consistent metadata governance, version tracking, and multi-agent coordination.

#### Scope
- All files in the Lupopedia codebase
- All documentation files (.md, .txt, .htm)
- All code files (.php, .js, .py, .sql)
- All configuration files (.yaml, .json, .ini)
- All migration files
- All TOON schema files
- All agent outputs and logs

**No exceptions.** Every file must have a WOLFIE Header.

#### Core Rules
1. **Mandatory Headers** - Every file MUST have a WOLFIE Header at the top
2. **Version Tracking** - Every file MUST include `file.last_modified_system_version`
3. **UTC Timestamp** - Every file MUST include `file.last_modified_utc` (YYYYMMDDHHIISS format from UTC_TIMEKEEPER)
4. **UTC_TIMEKEEPER Reference** - Every file MUST include `file.lupopedia.UTC_TIMEKEEPER` OR `file.lupopedia.5`
5. **GOV-AD-PROHIBIT-001 Compliance** - Every file MUST include `GOV-AD-PROHIBIT-001: true`
6. **File Name Identity** - Every file SHOULD include `file.name` with actual filename
7. **Required Atoms** - Every file MUST include `header_atoms` with GLOBAL_CURRENT_LUPOPEDIA_VERSION and GLOBAL_CURRENT_AUTHORS
8. **Optional Temporal Grouping** - Files MAY include `file.utc_day`, `UTC_TIMEKEEPER__CHANNEL_ID`, and `temporal_edges`
9. **Dialog Block** - Every file modification MUST update the `dialog` block
10. **File Metadata** - Every file SHOULD include `file` metadata block with name, title, description

#### Enforcement
- Automated validation through pre-commit hooks and CI/CD pipelines
- Manual validation through code reviews and documentation audits
- Violations logged in `lupo_labs_violations` with violation_code = "GOV-WOLFIE-HEADERS-001"
- Violations are educational, not punitive

#### LABS Integration
WOLFIE Headers support LABS-001 compliance by:
- Tracking actor modifications through `dialog.speaker`
- Maintaining temporal integrity through `file.last_modified_system_version`
- Providing governance context through `header_atoms`

#### Temporal Pillar Integration
WOLFIE Headers maintain temporal integrity by:
- Using `file.last_modified_system_version` for version tracking
- Using `file.last_modified_utc` for exact UTC moment tracking (from UTC_TIMEKEEPER)
- Using `file.utc_day` for canonical day-level grouping (optional)
- Using `UTC_TIMEKEEPER__CHANNEL_ID` for channel-bound temporal grouping (optional)
- Using `temporal_edges` for contextual metadata (optional)
- Supporting `file.last_named` for rename tracking
- Enabling temporal queries: "Which files were modified in version 4.1.6?" and "Which files were modified at UTC 20260119041301?"

#### Related Artifacts
- LABS-001 (mandatory pre-interaction protocol)
- Temporal Pillar Doctrine (Time is the Spine)
- Metadata Governance Doctrine

---

## ðŸ”„ Registry Maintenance

### Update Protocol
1. **New Artifact Registration**
   - Add entry to Registry Index table
   - Create detailed artifact section
   - Update `registry_metadata.total_artifacts`
   - Update `registry_metadata.last_updated_ymdhis`
   - Notify WOLFIE and SYSTEM agents

2. **Artifact Version Update**
   - Update version in Registry Index
   - Update artifact details section
   - Add version history entry
   - Update `registry_metadata.last_updated_ymdhis`

3. **Artifact Deprecation**
   - Change status to DEPRECATED
   - Update `registry_metadata.deprecated_artifacts`
   - Add deprecation date and reason
   - Maintain artifact details for historical reference

### Validation Cycle
- **Registry Validation**: Every 24 hours
- **Artifact Status Check**: Verify all artifacts are accessible
- **Link Validation**: Verify all artifact links are valid
- **Metadata Sync**: Ensure registry metadata matches artifact count

---

## ðŸ“š Related Documentation

- [Genesis Doctrine](../../doctrine/LUPOPEDIA_GENESIS_DOCTRINE.md) - Foundational principles
- [LABS-001 Doctrine](../../doctrine/LUPOPEDIA_ACTOR_BASELINE_STATE_DOCTRINE.md) - Actor baseline state
- [GOV-AD-PROHIBIT-001](../../doctrine/GOV_AD_PROHIBIT_001.md) - Anti-Advertising Law
- [GOV-WOLFIE-HEADERS-001](GOV_WOLFIE_HEADERS_001.md) - WOLFIE Header Governance
- [LABS Handshake Template](../../developer/templates/LABS_HANDSHAKE_TEMPLATE.md) - Actor onboarding template

---

## ðŸŽ¯ Compliance Requirements

### For Actors
- All actors must complete LABS-001 handshake before system interaction
- All actors must acknowledge all active governance artifacts in LABS declaration
- All actors must revalidate every 24 hours or on context shift

### For Agents
- All agents must reference this registry to determine active governance artifacts
- All agents must enforce governance compliance in their operations
- All agents must log violations to `lupo_labs_violations` table

### For Modules
- All modules must check governance compliance before operations
- All modules must respect environmental context boundaries
- All modules must maintain 100% ad-free output

---

**Registry Version**: 1.0.0  
**Last Updated**: 2026-01-19 05:29:07 UTC  
**Next Validation**: 2026-01-20 05:29:07 UTC  
**Maintained By**: WOLFIE, SYSTEM, CAPTAIN
