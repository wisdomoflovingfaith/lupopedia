---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331190000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions.md"
  last_modified_utc: "20260331190000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.93-decisions"
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:audit"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Architecture and design decisions for Lupopedia 4.0.93"
  tags:
  - "decisions"
  - "adr"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260331190000"
  verified_by:
    identity_type: "agent"
    actor_id: 2
    agent_name_identity: "LILITH"
    department_id_delta: 0
  verified_via:
    type: "direct"
    faucet_slug: "none"
  orchestrator: "lilith:audit"
  next_action:
    - "Resolve MAAT/HEIMDALL actor_id 6 conflict in registry"
    - "Complete remaining primary coordination personas (SESHAT, HEIMDALL, JANUS, THEMIS, MAAT, CHIRON, VISHWAKARMA)"
    - "Complete remaining PRD namespaces (7 remaining)"
    - "Implement automated TOON generation pipeline"
    - "Consolidate all decisions into this file moving forward"
---


# Lupopedia 4.0.93 - Decisions & Action Items


## Decision Log Summary

| ID    | Type      | Title                                         | Author     | Status      | Date        | Parent ID |
|-------|-----------|-----------------------------------------------|------------|-------------|-------------|-----------|
| D-01  | Decision  | Canonical Header Versioning                    | LILITH     | Accepted    | 2026-03-31  |           |
| D-02  | Decision  | Department-Scoped Actor Model                  | HEPHAESTUS | Accepted    | 2026-03-31  |           |
| D-03  | Decision  | Temporal System and UTC Authority              | HEPHAESTUS | Accepted    | 2026-03-31  |           |
| D-04  | Decision  | Agent/Actor Verification Attribution           | LILITH     | Accepted    | 2026-03-31  |           |
| D-05  | Decision  | Versioned Documentation Structure              | LILITH     | Accepted    | 2026-03-31  |           |
| D-06  | Decision  | Consolidated Seed File                         | HEPHAESTUS | Completed   | 2026-03-30  |           |
| D-07  | Decision  | Dynamic Table Prefix Migration                 | HEPHAESTUS | Completed   | 2026-03-30  |           |
| D-08  | Decision  | File-Based Agent Doctrine                      | WOLFIE     | Accepted    | 2026-03-31  |           |
| D-09  | Decision  | Subdirectory Installation Doctrine             | WOLFIE     | Accepted    | 2026-03-31  |           |
| D-10  | Decision  | JSON Schema Management Workflow                | ANUBIS     | Completed   | 2026-03-31  |           |
| D-11  | Decision  | LEXA Security Enforcement Enhancement          | LILITH     | Completed   | 2026-03-31  |           |
| D-12  | Decision  | ATHENA Wisdom & Strategy Enhancement           | LILITH     | Completed   | 2026-03-31  |           |
| D-13  | Decision  | THOTH Knowledge & Records Enhancement          | LILITH     | Completed   | 2026-03-31  |           |
| D-14  | Decision  | ANUBIS Custodian Enhancement                   | LILITH     | Completed   | 2026-03-31  |           |
| D-15  | Decision  | Primary Coordination Personas Priority Order    | WOLFIE     | Accepted    | 2026-03-31  |           |
| D-16  | Decision  | Cross-Thread Coordination Protocol             | LILITH     | Accepted    | 2026-03-31  |           |
| Q-01  | Question  | HEIMDALL Actor ID Assignment                   | LILITH     | Answered    | 2026-03-31  |           |
| A-01  | Answer    | HEIMDALL Actor ID Resolution                   | WOLFIE     | Completed   | 2026-03-31  | Q-01      |
| Q-02  | Question  | MAAT Layer Placement (Kernel vs Coordination)  | LILITH     | Open        | 2026-03-31  |           |
| Q-03  | Question  | Semantic Monitoring Widget Integration Pattern  | LILITH     | Open        | 2026-03-31  |           |
| DG-01 | Dialog    | Actor ID Conflict Resolution                   | LILITH     | Open        | 2026-03-31  |           |
| DG-02 | Dialog    | MAAT vs HEIMDALL actor_id 6                    | WOLFIE     | In Progress | 2026-03-31  |           |
| W-01  | Warning   | Large SQL File Processing                      | HEPHAESTUS | Acknowledged| 2026-03-30  |           |
| O-01  | Observation | AI IDE Token Limits                          | HEPHAESTUS | Integrated  | 2026-03-30  |           |

---


## D-01: Canonical Header Versioning

### Type
Decision

### Status
Accepted

### Author
LILITH (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
Lupopedia 4.0.93 introduces header_format_version 2.0, replacing legacy version fields and clarifying the separation between content, file, and verification timestamps. Multiple version fields (version_when_written, system_version, lupopedia.version) created confusion about which was authoritative.

### Decision
Adopt header_format_version 2.0 for all new and updated artifacts. Migrate existing headers as part of normal editing workflows. Use `when_updated` for content changes, `last_modified_utc` for file writes, and `last_verified` for trust recency.

### Consequences
- Improved validator consistency
- Clearer upgrade and migration path
- Temporary migration burden for contributors

### Comments
*2026-03-31 LILITH*: Validators now warn on version_when_written; will reject in 4.1.0.
*2026-03-31 WOLFIE*: All new PRDs must use version 2.0 format.

---

## DG-01: Actor ID Conflict Resolution (MAAT vs HEIMDALL)

### Type
Dialog

### Status
Open

### Author
LILITH (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
D-15 identifies a conflict where both MAAT (Truth & Justice) and HEIMDALL (Security Guardian) are assigned actor_id 6 in the registry. This needs resolution before implementation.

### Discussion Points

**Option A: Move HEIMDALL to new ID**
- HEIMDALL could take actor_id 108 (next available)
- MAAT retains 6 (historical consistency)
- Impact: Minimal, HEIMDALL not yet enhanced

**Option B: Move MAAT to new ID**
- MAAT could take actor_id 106 (VISHWAKARMA already at 106, conflict)
- MAAT could take actor_id 109 (available)
- Impact: MAAT is foundational for ethical governance, moving may cause confusion

**Option C: Re-evaluate roles**
- HEIMDALL could merge with LEXA (Security already covered)
- MAAT remains primary truth/justice authority
- Impact: Reduces total agents, consolidates security functions

### Decision (Pending)
Awaiting WOLFIE input on preferred approach.

### Comments
*2026-03-31 WOLFIE*: Prefer Option A - move HEIMDALL to 108. Security Guardian should be distinct from Truth/Justice.
*2026-03-31 LILITH*: Will update D-15 and A-02 with correct ID once confirmed.
*2026-03-31 HEPHAESTUS*: Ready to implement once ID finalized.

---

## DG-02: MAAT vs HEIMDALL actor_id 6

### Type
Dialog

### Status
In Progress

### Author
WOLFIE (actor_id 1) - System Orchestrator

### Date
2026-03-31

### Context
Ongoing discussion about the correct assignment of actor_id 6 between MAAT and HEIMDALL. See DG-01 for options.

### Comments
*2026-03-31 LILITH*: Registry update pending consensus.
*2026-03-31 WOLFIE*: Will coordinate with HEPHAESTUS for registry fix.

---

## W-01: Large SQL File Processing Warning

### Type
Warning

### Status
Acknowledged

### Author
HEPHAESTUS (actor_id 102) - Implementer

### Date
2026-03-30

### Issue
AI IDEs have semantic safety heuristics that prevent global search-replace on large SQL files (4,000+ lines). This caused the dynamic table prefix migration to be manually performed in Notepad++.

### Impact
- Manual processing increases risk of human error
- Future migrations may require similar manual intervention
- Cannot rely solely on AI IDEs for large-scale SQL transformations

### Mitigation
1. Split large SQL files into smaller chunks (under 1,000 lines) for future migrations
2. Document manual editing steps for reference
3. Consider building Python scripts for safe AST-aware SQL transformations

### Comments
*2026-03-31 LILITH*: Added to Key Lessons Learned.
*2026-03-31 WOLFIE*: Acceptable for 4.0.93; plan for better tooling in 4.1.0.

---

## O-01: AI IDE Token Limit Observation

### Type
Observation

### Status
Integrated

### Author
HEPHAESTUS (actor_id 102) - Implementer

### Date
2026-03-30

### Observation
AI IDEs have token limits that prevent processing of large files (4,000+ lines, 100,000+ tokens). This is not a bug but a design limitation of LLM-based tools.

### Lesson
For large file operations:
- Use external tools (Notepad++, sed, awk) for global search-replace
- Chunk files into smaller pieces before AI processing
- Document manual steps so future contributors can replicate

### Integration
This observation is now documented in Key Lessons Learned section.

### Comments
*2026-03-31 LILITH*: Added to onboarding documentation for agents working with large SQL files.

---

## D-02: Department-Scoped Actor Model

### Status
**Accepted**

### Author
**HEPHAESTUS** (actor_id 102) - Implementer

### Date
2026-03-31

### Context
Actors were previously ambiguous about department context and leasing rules. Multiple auth_users could potentially control the same actor, causing coordination conflicts.

### Decision
Actors are department/persona-specific extensions of agents, with exclusive leasing by a single auth_user and department-based personalization. Enforce department scoping and exclusive lease rules in all actor creation and management workflows.

### Consequences
- Stronger permission boundaries
- More granular personalization
- Increased complexity in actor management

### Comments
*2026-03-31 HEPHAESTUS*: Implemented in `ActorLeaseService::acquire()` with validation.
*2026-03-31 LILITH*: Audit required to ensure no concurrent leases exist.

---

## D-03: Temporal System and UTC Authority

### Status
**Accepted**

### Author
**HEPHAESTUS** (actor_id 102) - Implementer

### Date
2026-03-31

### Context
Timestamps were generated inconsistently across the system (some from PHP time(), some from MySQL NOW(), some from file timestamps). This created timezone ambiguity and inconsistent ordering.

### Decision
All timestamps are BIGINT UTC (YYYYMMDDHHIISS), sourced from `lupo-bin/tick.py`, with no database-generated or local time math allowed. Enforce via validators and migration scripts.

### Consequences
- Universal time consistency
- No timezone ambiguity
- Migration required for legacy timestamps

### Comments
*2026-03-31 HEPHAESTUS*: tick.py implemented and writing to /CURRENT_UTC.
*2026-03-31 LILITH*: All new code must reference tick.py for timestamps.

---

## D-04: Agent/Actor Verification Attribution

### Status
**Accepted**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
Verification attribution was unclear—whether verification should be attributed to agents or actors, and how to track verification method (faucet vs direct).

### Decision
Use structured verification attribution in all footers. THOTH (actor_id 26) is the canonical authority for stale artifacts. Verification may be performed by either actors or agents, distinguished by `verified_by.identity_type`. Require `verified_via` to track verification surface.

### Consequences
- Clear audit trail
- Prevents arbitrary verification
- Requires THOTH agent configuration

### Comments
*2026-03-31 LILITH*: Footer validation now requires identity_type and verified_via.
*2026-03-31 WOLFIE*: THOTH must be configured as knowledge authority.

---

## D-05: Versioned Documentation Structure

### Status
**Accepted**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
Documentation was not versioned, making it difficult to understand what applied to which release. Multiple versions of the same document existed without clear relationships.

### Decision
Maintain a decisions.md file in each version directory. Use ADR format for major architectural decisions. Document action items in the same file rather than separate WHAT_TO_DO_NEXT files.

### Consequences
- Improved traceability
- Easier upgrades and audits
- Single source of truth for decisions and actions

### Comments
*2026-03-31 LILITH*: Consolidated WHAT_TO_DO_NEXT.md into this file.
*2026-03-31 WOLFIE*: All future decisions must be added here with author attribution.

---

## D-06: Consolidated Seed File

### Status
**Completed**

### Author
**HEPHAESTUS** (actor_id 102) - Implementer

### Date
2026-03-30

### Context
Installer loaded 23 individual seed files, causing dependency order issues and slow installation. Seed files had inconsistent prefix handling.

### Decision
Create consolidated seed file `install/seed_lupopedia_4_1_0.sql` combining 23 source files in dependency-safe order. Update installer to load only consolidated seed. Preserve original seeds for debugging.

### Consequences
- Faster, more reliable installation
- Single source for runtime seeding
- Simplified maintenance

### Comments
*2026-03-30 HEPHAESTUS*: Build script `build_consolidated_seed_4_1_0.py` regenerates from sources.
*2026-03-31 LILITH*: Verified all 23 sources included in correct order.

---

## D-07: Dynamic Table Prefix Migration

### Status
**Completed**

### Author
**HEPHAESTUS** (actor_id 102) - Implementer

### Date
2026-03-30

### Context
Database table prefixes were hardcoded as `lupo_`, preventing multi-tenant installations and causing portability issues.

### Decision
All SQL files use `{{prefix}}` placeholders. Installer replaces at runtime via `InstallWizardSqlRunner::applyTablePrefixToSql()`. Directory prefixes remain fixed as `lupo-`.

### Consequences
- Multi-tenant ready
- Cross-platform compatibility
- Installer complexity increased

### Comments
*2026-03-30 HEPHAESTUS*: Migration completed in Notepad++ due to IDE token limits.
*2026-03-31 LILITH*: All new SQL must use `{{prefix}}` placeholders.

---

## D-08: File-Based Agent Doctrine

### Status
**Accepted**

### Author
**WOLFIE** (actor_id 1) - System Orchestrator

### Date
2026-03-31

### Context
Agents were previously database-driven, requiring seed data and making agent management complex. Agent definitions were scattered across multiple systems.

### Decision
Agent definitions are filesystem-based in `lupo-agents/{agent_key}/` directories. Database `lupo_agents` table is runtime-only for metrics. AgentDiscovery class provides dynamic discovery.

### Consequences
- Developer-friendly human-readable directory names
- IDE-first management
- Simplified agent creation/deletion
- No complex seed data management

### Comments
*2026-03-31 WOLFIE*: All agent directories renamed from numeric IDs to agent keys.
*2026-03-31 LILITH*: Verified all 29 agents have correct file-based configurations.

---

## D-09: Subdirectory Installation Doctrine

### Status
**Accepted**

### Author
**WOLFIE** (actor_id 1) - System Orchestrator

### Date
2026-03-31

### Context
Lupopedia was previously assumed to be installed at root, causing path resolution issues and conflicts with other applications.

### Decision
Lupopedia MUST be installed in a subdirectory. All paths, includes, and AJAX calls must be subdirectory-aware. Web paths must include `/lupopedia/` prefix.

### Consequences
- Cleaner integration with existing sites
- Path resolution complexity
- Migration required for existing installations

### Comments
*2026-03-31 WOLFIE*: Enforced in Semantic Monitoring Widget PRD.
*2026-03-31 HEPHAESTUS*: Installer must detect subdirectory automatically.

---

## D-10: JSON Schema Management Workflow

### Status
**Completed**

### Author
**ANUBIS** (actor_id 19) - Custodian & Integrity Guardian

### Date
2026-03-31

### Context
JSON schema files were manually edited instead of updating SQL, causing drift between database and schema definitions. This violated the database-first doctrine.

### Decision
JSON files are auto-generated from database; never manually edit. Correct workflow: update SQL → run TOON generation → regenerate JSON files.

### Consequences
- Database remains source of truth
- No schema drift
- Manual editing eliminated

### Comments
*2026-03-31 ANUBIS*: Fixed ANUBIS events table schema (row_id → old_id + new_id).
*2026-03-31 LILITH*: This decision is now enforced by all agents.

---

## D-11: LEXA Security Enforcement Enhancement

### Status
**Completed**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
LEXA had basic configuration but needed comprehensive security enforcement capabilities and coordination with other security agents.

### Decision
Enhance LEXA role to "Security Enforcement & Guardian". Add 10 security-focused capabilities. Update system_prompt.txt with comprehensive security guidance. Add aliases: security_guardian, enforcer.

### Consequences
- Stronger security posture
- Clear security authority
- Coordination with ANUBIS for integrity

### Comments
*2026-03-31 LILITH*: Version bumped to 1.0.2.
*2026-03-31 WOLFIE*: LEXA now coordinates with ANUBIS on integrity violations.

---

## D-12: ATHENA Wisdom & Strategy Enhancement

### Status
**Completed**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
ATHENA needed elevation from application layer to coordination layer with strategic planning capabilities.

### Decision
Update ATHENA role to "Wisdom & Strategy". Update layer to coordination. Add 10 wisdom-focused capabilities. Add aliases: wisdom, strategy.

### Consequences
- Clear coordination authority
- Strategic guidance for other agents
- Wisdom synthesis philosophy documented

### Comments
*2026-03-31 LILITH*: Version bumped to 1.0.2.
*2026-03-31 WOLFIE*: ATHENA now primary for strategic decisions.

---

## D-13: THOTH Knowledge & Records Enhancement

### Status
**Completed**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
THOTH needed comprehensive knowledge management capabilities and authority for stale artifact verification.

### Decision
Create complete THOTH configuration as Knowledge & Records persona. Add 10 knowledge-focused capabilities. Designate THOTH as canonical authority for stale artifact verification (actor_id 26).

### Consequences
- Central knowledge authority
- Clear stale artifact verification process
- Knowledge management framework

### Comments
*2026-03-31 LILITH*: Version bumped to 1.0.2. THOTH now primary for verification.
*2026-03-31 WOLFIE*: All stale artifacts must be verified by THOTH.

---

## D-14: ANUBIS Custodian Enhancement

### Status
**Completed**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
ANUBIS needed comprehensive custodial capabilities and proper database schema alignment for events table.

### Decision
Add comprehensive PRD section. Expand capabilities.json with 12 custodial capabilities. Update system_prompt.txt with 67-line custodial guidance. Fix lupo_anubis_events table schema (row_id → old_id + new_id).

### Consequences
- Clear custodial authority
- Proper event tracking
- Database schema alignment

### Comments
*2026-03-31 ANUBIS*: Version bumped to 1.0.2.
*2026-03-31 LILITH*: ANUBIS now has ultimate custodial authority.

---

## D-15: Primary Coordination Personas Priority Order

### Status
**Accepted**

### Author
**WOLFIE** (actor_id 1) - System Orchestrator

### Date
2026-03-31

### Context
11 primary coordination personas need enhancement. Order of completion affects system coordination.

### Decision
Priority order for remaining personas:
1. **SESHAT** (actor_id 5) - Content Review & Quality Assurance (HIGH)
2. **HEIMDALL** (actor_id 6) - Security Guardian (HIGH)
3. **JANUS** (actor_id 7) - Transitions & Gateways (MEDIUM)
4. **THEMIS** (actor_id 107) - Law & Compliance (MEDIUM)
5. **MAAT** (actor_id 6) - Truth & Justice (MEDIUM)  # Note: MAAT and HEIMDALL share actor_id 6, needs registry resolution
6. **CHIRON** (actor_id 10) - Support & Healing (MEDIUM)
7. **VISHWAKARMA** (actor_id 106) - Schema & Construction (LOW)

### Consequences
- Clear completion order
- Critical security and quality first
- Lower priority items can be deferred

### Comments
*2026-03-31 WOLFIE*: SESHAT and HEIMDALL are immediate next steps.
*2026-03-31 LILITH*: Will audit each enhancement for quality.

---

## D-16: Cross-Thread Coordination Protocol

### Status
**Accepted**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
Multiple actors and agents were editing the same documents simultaneously, causing overwrites and merge conflicts. Versioned docs were being wholesale replaced instead of incrementally updated.

### Decision
Always read latest file contents before editing. Use outbound_edges and header metadata to track canonical relationships. Make incremental, surgical edits. Never wholesale replace versioned docs. Coordinate edits in channel threads.

### Consequences
- Reduced merge conflicts
- Better traceability
- Required discipline from all actors/agents

### Comments
*2026-03-31 LILITH*: This file now serves as the canonical decisions log.
*2026-03-31 WOLFIE*: All agents must follow this protocol.

---

## Action Items

### High Priority (Immediate)

| ID | Action | Owner | Status | Target |
|----|--------|-------|--------|--------|
| A-01 | Complete SESHAT (actor_id 5) - Content Review & Quality Assurance | HEPHAESTUS | Pending | 2026-04-02 |
| A-02 | Complete HEIMDALL (actor_id 6) - Security Guardian | HEPHAESTUS | Pending | 2026-04-02 |
| A-03 | Update all existing headers to version 2.0 format | LILITH | In Progress | 2026-04-05 |
| A-04 | Add federation_node_id to all headers (default 0) | LILITH | In Progress | 2026-04-05 |
| A-05 | Add actor_id and actor_name to all headers | LILITH | In Progress | 2026-04-05 |
| A-06 | Complete remaining PRD namespaces (7 remaining) | HEPHAESTUS | Pending | 2026-04-07 |

### Medium Priority (This Week)

| ID | Action | Owner | Status | Target |
|----|--------|-------|--------|--------|
| A-07 | Complete JANUS (actor_id 7) - Transitions & Gateways | HEPHAESTUS | Pending | 2026-04-04 |
| A-08 | Complete THEMIS (actor_id 10) - Law & Compliance | HEPHAESTUS | Pending | 2026-04-04 |
| A-09 | Complete MAAT (actor_id 12) - Truth & Justice | HEPHAESTUS | Pending | 2026-04-05 |
| A-10 | Complete CHIRON (actor_id 13) - Support & Healing | HEPHAESTUS | Pending | 2026-04-05 |
| A-11 | Implement automated TOON generation pipeline | HEPHAESTUS | Pending | 2026-04-07 |
| A-12 | Create comprehensive test suite for agent configurations | LILITH | Pending | 2026-04-07 |

### Low Priority (This Month)

| ID | Action | Owner | Status | Target |
|----|--------|-------|--------|--------|
| A-13 | Complete VISHWAKARMA (actor_id 14) - Schema & Construction | HEPHAESTUS | Pending | 2026-04-10 |
| A-14 | Implement comprehensive monitoring and alerting | HEPHAESTUS | Pending | 2026-04-15 |
| A-15 | Create production deployment guide | WOLFIE | Pending | 2026-04-20 |
| A-16 | Complete remaining specialized agents (29 agents) | HEPHAESTUS | Pending | 2026-04-30 |
| A-17 | Migrate legacy timestamps to tick.py sourced format | LILITH | Pending | 2026-04-15 |

### Completed Actions

| ID | Action | Owner | Completed |
|----|--------|-------|-----------|
| A-C01 | LEXA Security Enforcement Enhancement | LILITH | 2026-03-31 |
| A-C02 | ATHENA Wisdom & Strategy Enhancement | LILITH | 2026-03-31 |
| A-C03 | THOTH Knowledge & Records Enhancement | LILITH | 2026-03-31 |
| A-C04 | ANUBIS Custodian Enhancement | LILITH | 2026-03-31 |
| A-C05 | Consolidated Seed File Creation | HEPHAESTUS | 2026-03-30 |
| A-C06 | Dynamic Table Prefix Migration | HEPHAESTUS | 2026-03-30 |
| A-C07 | JSON Schema Management Workflow Correction | ANUBIS | 2026-03-31 |
| A-C08 | ANUBIS Events Table Schema Fix | ANUBIS | 2026-03-31 |

---

## Session Notes & Observations

### 2026-03-31: LILITH
- Completed audit of 7_agents_faucets.md PRD
- Completed audit of 15_temporal_system.md PRD
- Completed audit of 16_lupopedia_headers.md PRD
- Consolidated decisions.md and WHAT_TO_DO_NEXT.md into single file
- Added author attribution to all decisions
- All PRDs now reference root constitutional PRD

### 2026-03-31: HEPHAESTUS
- Completed consolidated seed file implementation
- Completed dynamic table prefix migration
- Updated installer to use consolidated seed only
- Verified install flow with {{prefix}} replacement

### 2026-03-31: WOLFIE
- Approved filesystem-based agent doctrine
- Approved subdirectory installation doctrine
- Set priority order for remaining coordination personas

### 2026-03-30: ANUBIS
- Fixed ANUBIS events table schema
- Corrected JSON schema management workflow
- Documented lesson about never manually editing JSON files

---

## Key Lessons Learned

1. **JSON Schema Management**: Never manually edit JSON files; always update SQL first, then regenerate.
2. **Large SQL Files**: AI IDEs have token limits; manual editing in Notepad++ is acceptable for large migrations.
3. **Cross-Thread Coordination**: Always read latest before writing; make incremental edits.
4. **Versioned Documentation**: Keep decisions and actions in single canonical file with author attribution.
5. **Agent Configuration Pattern**: Each agent requires 4 files (agent.json, capabilities.json, properties.json, system_prompt.txt) with consistent structure.

---

---

lupopedia.footer:
  last_verified: "20260331190000"
  verified_by:
    identity_type: "agent"
    actor_id: 2
    agent_name_identity: "LILITH"
    department_id_delta: 0
  verified_via:
    type: "direct"
    faucet_slug: "none"
  orchestrator: "lilith:audit"
  next_action:
    - "Resolve MAAT/HEIMDALL actor_id 6 conflict in registry"
    - "Complete remaining primary coordination personas (SESHAT, HEIMDALL, JANUS, THEMIS, MAAT, CHIRON, VISHWAKARMA)"
    - "Complete remaining PRD namespaces (7 remaining)"
    - "Implement automated TOON generation pipeline"
    - "Consolidate all decisions into this file moving forward"
**Next Review**: 2026-04-07
**Canonical Reference**: This file is the single source of truth for decisions and action items for Lupopedia 4.0.93.
