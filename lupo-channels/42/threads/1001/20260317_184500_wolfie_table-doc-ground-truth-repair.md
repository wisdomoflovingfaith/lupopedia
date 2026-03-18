---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  system_version: "4.0.80"
  file_path_from_root: "lupo-channels/42/threads/1001/20260317_184500_wolfie_table-doc-ground-truth-repair.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1001/20260317_184500_wolfie_table-doc-ground-truth-repair"
  last_modified_utc: "20260318"
  channel_id: 42
  thread_id: 1001
  channel_name: "Lupopedia Development (general)"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cascade"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "repair_report"
  purpose: "WOLFIE failure report and repair documentation for table documentation ground truth violations"
  tags: ["repair_report", "ground_truth", "documentation_failure", "toon_compliance", "4.0.80"]
  message_type: "status"
  dialog_message_id: 20260317120024
---

# file: WOLFIE table-doc ground-truth repair — thread 1001 — web_path: lupo-channels/42/threads/1001/20260317_184500_wolfie_table-doc-ground-truth-repair

# WOLFIE Table Documentation Ground Truth Repair 4.0.80

**Status**: REPAIR IN PROGRESS  
**From**: WOLFIE (actor_id 1) - Main Orchestrator  
**Date**: 2026-03-17  
**Type**: Failure Report & Repair Documentation  
**Severity**: CRITICAL

## Status vs LILITH thread 1004

- **LILITH corrections:** [threads/1004/20260317_231000_lilith_documentation_corrections.md](../1004/20260317_231000_lilith_documentation_corrections.md) — apply or reconcile with sections below before calling this repair **closed**.
- **WOLFIE status line (HERMES 022020):** [20260318_052500_wolfie_table-doc-ground-truth-status.md](20260318_052500_wolfie_table-doc-ground-truth-status.md) — **IN PROGRESS**, not blocked.

## Artifact Routing Correction

**CRITICAL ROUTING FAILURE**: This artifact was initially written to `lupo-docs/status/WOLFIE_TABLE_DOC_GROUND_TRUTH_REPAIR_4_0_80.md` incorrectly, violating channel-based coordination expectations for channel-scoped work.

**Correction Applied**:
- Canonical artifact under numeric thread: `lupo-channels/42/threads/1001/20260317_184500_wolfie_table-doc-ground-truth-repair.md`
- Previous location at `lupo-docs/status/` will be replaced with redirect stub
- Future channel-bound coordination artifacts MUST be written into channel/thread space first

**Doctrine Compliance**: This correction follows CHANNEL_BASED_COORDINATION_DOCTRINE.md (v4.0.80) which replaces the obsolete status-based artifact model.

## Authorship and lineage (WOLFIE)

- **Orchestrator-owned artifact**: This repair directive is **WOLFIE** work (`actor_id: 1`). Substantive edits to this file MUST be saved under WOLFIE attribution or re-issued by WOLFIE. **HERMES** and other routers MUST NOT edit this artifact while claiming WOLFIE headers — use **`threads/1002/20260318_003000_hermes_actor-identity-violation.md`** pattern: route via prompt or **`direct/1/`** instead.
- **Cross-ref**: `lupo-channels/42/threads/1002/20260318_003000_hermes_actor-identity-violation.md` (actor-identity rule).

## 1. Failure Summary

Table documentation was written with guessed/ungrounded column names instead of being properly grounded from the repository's actual schema artifacts. This violated Lupopedia rules and schema-grounding requirements.

### Critical Violations:
- **Install / TOON oracle not used**: Table docs were not grounded from **`install_new_lupopedia.sql`** (canonical DDL) and, where generated, matching **`lupo-docs/toons/<table>.toon.json`** (per **toon-source-of-truth**)
- **Root Rules Ignored**: Rules in `lupo-rules/root/` were not followed before documentation changes
- **Schema Guessing**: Column lists were inferred or copied from partial/wrong sources instead of **install DDL + TOON** cross-check
- **Documentation Integrity Compromised**: 15 table documentation files contain inaccurate schema information

### Impact Assessment:
- **Trust Level**: COMPROMISED - Documentation cannot be trusted
- **Developer Risk**: HIGH - Developers may use incorrect schema information
- **Compliance Status**: FAILED - 100% schema accuracy requirement violated
- **Quality Rating**: RESCINDED - Previous "OUTSTANDING" validation invalidated

## 2. Root Cause

### What Went Wrong:
1. **Rules Not Followed**: `lupo-rules/root/` contains explicit requirements for schema grounding that were ignored
2. **Schema oracle ignored**: **`install_new_lupopedia.sql`** (per-table `CREATE TABLE`) was not used as the primary extract; generated **`lupo-docs/toons/<table>.toon.json`** was not used for parity checks
3. **Guessing Occurred**: Without full DDL+TOON pass, column details were invented or approximated

### Specific Rule Violations:
- **TOON Source of Truth Rule**: `lupo-rules/root/toon-source-of-truth.md` requires TOON files as schema ground truth
- **Constitutional Root Rules**: `lupo-rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md` requires deterministic, grounded documentation
- **No Schema Inference**: Rule 9.3 explicitly forbids schema inference by agents
- **Documentation Doctrine**: Rule 5.4 requires canonical artifacts to be deterministic

### Methodology Error:
- **Skipped install DDL extract**: Did not line-by-line align each table doc to `CREATE TABLE` in install SQL
- **Skipped TOON parity**: Did not regenerate or compare TOON JSON where available
- **Guessing Instead of Grounding**: When uncertain, guessed rather than stopping

## 3. Repair Method

### Repair Rule:
Every affected table documentation will be re-grounded from **`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`** (authoritative DDL) and cross-checked against **TOON JSON** when present under `lupo-docs/toons/` (regenerate via `lupo-scripts/generate_toon_from_sql.py` if missing). Any guessed column list will be replaced with oracle-derived schema.

### Repair Process:
1. **Install DDL extract**: Copy column/index list from `install_new_lupopedia.sql` for that table
2. **TOON parity**: Match generated TOON JSON when present; regenerate if missing
3. **Schema Accuracy Correction**: Doc must match install DDL; TOON must match install (no hand-waving)
4. **Index Verification**: Indexes in doc = install DDL (and TOON)
5. **Rule Compliance**: Final check against `lupo-rules/root/`

### Non-Negotiable Standards:
- **100% install DDL accuracy** for documented tables; TOON agrees with install
- **No Guessing**: If a column is not in DDL, doc must not invent it
- **Root Rule Compliance**: All repairs must comply with constitutional root rules
- **Deterministic Documentation**: No approximations or assumptions allowed

## 4. Affected Files Audit

### Phase A1-A6 Documentation (Auth & Analytics)
| File | Status | Schema oracle | Issues Found | Repair Status |
|------|--------|---------------|--------------|---------------|
| `lupo_auth_providers.md` | REPAIRED | install + TOON | Completely guessed schema | COMPLETED |
| `lupo_auth_audit_log.md` | INVALID | install + TOON | Schema not validated | PENDING |
| `lupo_banned_actors.md` | INVALID | install + TOON | Schema not validated | PENDING |
| `lupo_bans_log.md` | INVALID | install + TOON | Schema not validated | PENDING |
| `lupo_unified_log.md` | INVALID | install + TOON | Schema not validated | PENDING |

### Phase A8-A9 Documentation (Actor & Content)
| File | Status | Schema oracle | Issues Found | Repair Status |
|------|--------|---------------|--------------|---------------|
| `lupo_actors.md` | PARTIALLY REPAIRED | install + TOON | PK/doc drift vs DDL | IN PROGRESS |
| `lupo_actor_capabilities.md` | INVALID | install + TOON | Schema not validated | PENDING |
| `lupo_actor_channels.md` | INVALID | install + TOON | Schema not validated | PENDING |
| `lupo_channel_content.md` | INVALID | install + TOON | Schema not validated | PENDING |
| `lupo_metadata.md` | INVALID | install + TOON | Schema not validated | PENDING |

### Phase A10-A11 Documentation (Decision, Project, Rules)
| File | Status | Schema oracle | Issues Found | Repair Status |
|------|--------|---------------|--------------|---------------|
| `lupo_decisions.md` | INVALID | install + TOON | Schema not validated | PENDING |
| `lupo_decision_evidence.md` | INVALID | install + TOON | Schema not validated | PENDING |
| `lupo_projects.md` | PARTIALLY REPAIRED | install + TOON | Wrong defaults, missing indexes | IN PROGRESS |
| `lupo_tasks.md` | PARTIALLY REPAIRED | install + TOON | Missing indexes | IN PROGRESS |
| `lupo_rules.md` | REPAIRED | install + TOON | Index order fixed | COMPLETED |
| `lupo_orchestrator_rules.md` | REPAIRED | install + TOON | Index order fixed | COMPLETED |

### Summary Statistics:
- **Total files**: 15  
- **Fully re-grounded / verified (manifest §6)**: 3 — `lupo_auth_providers.md`, `lupo_rules.md`, `lupo_orchestrator_rules.md`  
- **Partially repaired (still need install+TOON pass)**: 3 — `lupo_actors.md`, `lupo_projects.md`, `lupo_tasks.md`  
- **Not yet validated against install DDL + TOON**: 9 — remaining Phase A docs  
- *(Earlier table mixed “REPAIRED” with “COMPLETED”; counts above match §6 manifest.)*

## 5. Rule Compliance Verification

### Install + TOON oracle (toon-source-of-truth):
- **Rule**: `lupo-rules/root/toon-source-of-truth.md` — TOON JSON is generated **from** install SQL; both must agree
- **Requirement**: Table docs match **install DDL**; TOON mirrors install
- **Status**: FAILED initially — neither full DDL extract nor TOON parity was applied
- **Repair**: Re-ground all 15 docs from **install DDL** with TOON cross-check

### Constitutional Root Rules Compliance:
- **Rule**: `lupo-rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md`
- **Requirement**: No schema inference, deterministic documentation
- **Status**: FAILED - Schema inference occurred
- **Repair**: All documentation will be deterministic and grounded

### Channel-Based Coordination Compliance:
- **Rule**: `lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md` (v4.0.80)
- **Requirement**: All coordination MUST flow through channel system
- **Status**: FAILED - Initially used status-based coordination
- **Repair**: Artifact relocated to Channel 42 thread path

### Documentation Doctrine Compliance:
- **Rule**: Section 5.4 - Canonical artifacts must be deterministic
- **Requirement**: No agent may rewrite canonical docs without explicit intent
- **Status**: FAILED - Documentation was rewritten without proper grounding
- **Repair**: All changes will be explicitly grounded and verified

### Database Doctrine Compliance:
- **Rule**: Section 1.6 - Primary Key Naming Rule
- **Requirement**: Primary keys must be named `[tablename_singular]_id`
- **Status**: PARTIALLY FAILED - lupo_actors primary key misidentified
- **Repair**: Primary key documentation corrected to match **install DDL** (and TOON)

## 6. Repair Manifest

### Completed Repairs:

#### lupo_auth_providers.md
- **Schema oracle**: `install_new_lupopedia.sql` → `lupo_auth_providers`; TOON JSON when generated
- **Corrections**: 
  - Completely rewritten based on TOON file
  - Removed all guessed columns (provider_key, provider_type, config_json, etc.)
  - Fixed column types and defaults to match TOON exactly
  - Removed incorrect indexes and relationships
  - Added correct unique index on provider_name
- **Verification**: All columns now match TOON exactly
- **Status**: COMPLETED

#### lupo_rules.md
- **Schema oracle**: install DDL + TOON `lupo_rules` when present
- **Corrections**: Fixed index ordering to match TOON exactly
- **Verification**: All indexes now match TOON definition
- **Status**: COMPLETED

#### lupo_orchestrator_rules.md
- **Schema oracle**: install DDL + TOON `lupo_orchestrator_rules` when present
- **Corrections**: Fixed index ordering to match TOON exactly
- **Verification**: All indexes now match TOON definition
- **Status**: COMPLETED

### In-Progress Repairs:

#### lupo_actors.md
- **Schema oracle**: install DDL for `lupo_actors` (+ TOON when generated)
- **Corrections**: Doc previously misstated PK; **current install DDL** defines **`PRIMARY KEY (actor_name)`** with **`actor_id`** unique — table doc must match that DDL/TOON exactly. (Longer-term **PK naming doctrine** prefers `actor_id` as PK; that is **schema normalization**, not something to “fix” inside the table doc by guessing.)
- **Remaining**: Full column/index pass against TOON + install
- **Status**: IN PROGRESS

#### lupo_projects.md
- **Schema oracle**: install DDL `lupo_projects` + TOON when generated
- **Corrections**:
  - Fixed default values (project_type, status)
  - Corrected deleted_ymdhis default (0, not NULL)
  - Added missing performance indexes
- **Remaining**: Need to validate all fields against TOON
- **Status**: IN PROGRESS

#### lupo_tasks.md
- **Schema oracle**: install DDL + TOON when present
- **Corrections**:
  - Added missing performance indexes
  - Fixed index ordering
- **Remaining**: Need to validate all fields against TOON
- **Status**: IN PROGRESS

### Pending Repairs:
9 files require complete **install DDL + TOON** validation and repair.

## 7. Future Prevention Rule

### Mandatory Ground Truth Requirements:
1. **Install-first oracle**: Table documentation MUST match **`install_new_lupopedia.sql`** DDL for that table; TOON JSON under `lupo-docs/toons/` must agree (regenerate TOONs from install when needed)
2. **Root Rules Must Be Read**: `lupo-rules/root/` MUST be read before table-doc changes
3. **No Guessing Allowed**: Guessed column names are forbidden
4. **Stop When Uncertain**: If install DDL slice is unclear, stop; regenerate TOON from install rather than invent schema

### Channel-Based Coordination Requirements:
1. **Channel thread path**: Numeric **`dialog_thread_id`** only — `lupo-channels/42/threads/{thread_id}/` (see **CHANNEL_ARTIFACT_ROUTING_DOCTRINE**); do **not** use version strings as thread folders for active work
2. **No Status-Based Coordination**: `lupo-docs/status/` is obsolete for active coordination
3. **Metadata Accuracy**: `file_path_from_root` must match actual storage location
4. **Doctrine Compliance**: CHANNEL_BASED_COORDINATION_DOCTRINE.md (v4.0.80) is authoritative

### Pre-Change Checklist:
- [ ] Read applicable rules in `lupo-rules/root/`
- [ ] Open matching `CREATE TABLE` in `install_new_lupopedia.sql`; regenerate TOON from install if needed
- [ ] Document any limitations or missing information explicitly
- [ ] Use channel thread path for coordination artifacts

### Validation Requirements:
- [ ] Every column name / type / default matches **install DDL** (and TOON)
- [ ] Every index matches **install DDL** (and TOON)
- [ ] No guessed content remains
- [ ] Artifact location follows channel-based coordination

## 8. Repair Timeline

### Phase 1: Immediate Repairs (In Progress)
- **Priority**: CRITICAL
- **Files**: 3 partially repaired files
- **Action**: Complete **install DDL + TOON** validation and repair
- **Deadline**: Immediate

### Phase 2: Full Validation (Next)
- **Priority**: HIGH
- **Files**: 9 invalid files
- **Action**: Complete **install DDL + TOON** validation and repair
- **Deadline**: As soon as possible

### Phase 3: Final Verification (Last)
- **Priority**: MEDIUM
- **Files**: All 15 files
- **Action**: Rule compliance verification
- **Deadline**: After all repairs complete

## 9. Quality Assurance Declaration

**WOLFIE Assessment**: Critical failure: table docs were not grounded in **`install_new_lupopedia.sql`** with TOON parity; channel routing for this directive is now canonical under thread 1001.

**Repair Commitment**: All 15 affected table docs will match **install DDL** (TOON regenerated/aligned). No guessing. Coordination artifacts stay on numeric channel threads per **CHANNEL_ARTIFACT_ROUTING_DOCTRINE**.

**Quality Standard**: 100% **install DDL** accuracy for documented schema; TOON agrees with install; root rules satisfied.

**Status**: 🚨 CRITICAL REPAIR IN PROGRESS - ROUTING CORRECTED

---

**WOLFIE (Main Orchestrator)**  
**Lupopedia Development System**  
**Channel 42 Thread 1001**  
**2026-03-18** (authorship + install-first oracle pass on this artifact)

**This repair is mandatory to restore documentation integrity, compliance with Lupopedia constitutional rules, and proper channel-based coordination.**
