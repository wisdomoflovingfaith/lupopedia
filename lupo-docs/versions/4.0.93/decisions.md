---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401020000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions.md"
  last_modified_utc: "20260401020000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.93-decisions"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Architecture and design decisions for Lupopedia 4.0.93"
  tags:
  - "decisions"
  - "adr"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260401020000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
  next_action:
    - "Resolve MAAT/HEIMDALL actor_id 6 conflict in registry"
    - "Complete remaining primary coordination personas (SESHAT, HEIMDALL, JANUS, THEMIS, MAAT, CHIRON, VISHWAKARMA)"
    - "Complete remaining PRD namespaces (7 remaining)"
    - "Implement automated TOON generation pipeline"
    - "Consolidate all decisions into this file moving forward"
    - "Optional: wire main channels cockpit UI to same api/lupo-channels patterns where useful"
    - "Integrate GarbageCollector class into image.php or lupo_ajax.php for random execution"
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
| D-17  | Decision  | LILITH Audit: Data Model PRD Corrections       | LILITH     | Completed   | 2026-03-31  | D-01      |
| D-18  | Decision  | LILITH Audit: Installer Requirements PRD       | LILITH     | Completed   | 2026-03-31  | D-01      |
| D-19  | Decision  | LILITH Audit: Core Identity PRD - Final Review | LILITH     | Completed   | 2026-03-31  | D-01      |
| D-20  | Decision  | LILITH Correction: Version Directory Purpose   | LILITH     | Completed   | 2026-03-31  | D-01      |
| D-21  | Decision  | LILITH Directive: Create Countermeasure Agent  | LILITH     | Completed   | 2026-03-31  | D-01      |
| D-22  | Decision  | LILITH Audit: COUNTERMEASURE Agent Config      | LILITH     | Completed   | 2026-03-31  | D-01      |
| D-23  | Decision  | LILITH Audit: COUNTERMEASURE Agent Config (2)  | LILITH     | Completed   | 2026-03-31  | D-01      |
| D-24  | Decision  | LILITH Directive: Update COUNTERMEASURE Prompt | LILITH     | Completed   | 2026-03-31  | D-01      |
| D-25  | Directive | IDE: Update 4.0.93 version documentation      | CURSOR     | Completed   | 2026-03-31  | D-05      |
| D-26  | Decision  | Channel chat: canonical API + PRD 18 + UI      | CURSOR     | Completed   | 2026-03-31  | D-05      |
| D-27  | Directive | IDE: Refresh 4.0.93 docs after channel chat    | CURSOR     | Completed   | 2026-03-31  | D-26      |
| D-28  | Decision  | WOLFIE Doctrine: Constitutional Protection     | WOLFIE     | Accepted    | 2026-04-01  | D-08      |
| D-29  | Decision  | Multi-Agent Orchestration Doctrine             | WOLFIE     | Accepted    | 2026-04-01  | D-28      |
| D-30  | Decision  | Actor-Agent Distinction Doctrine               | WOLFIE     | Accepted    | 2026-04-01  | D-29      |
| D-31  | Decision  | Database Doctrine: Canonical Database Rules    | WOLFIE     | Accepted    | 2026-04-01  | D-08      |
| D-32  | Decision  | Garbage Collection System                      | WOLFIE     | Completed   | 2026-04-01  | D-31      |
| D-33  | Decision  | PRD Improvement: 00_root + 01_semantic_widget  | CURSOR     | Completed   | 2026-04-01  | D-08      |
| D-34  | Decision  | TOON Generator: Schema-Only Output             | CURSOR     | Completed   | 2026-04-01  | D-10      |
| D-35  | Decision  | CSV Export: Separate Tool + Sensitive Exclusions | CURSOR   | Completed   | 2026-04-01  | D-34      |
| D-36  | Decision  | Missing Table Protocol + install SQL Updates   | CURSOR     | Completed   | 2026-04-01  | D-31      |
| D-37  | Decision  | Proven Code Preservation Doctrine (9.20)       | CURSOR     | Accepted    | 2026-04-01  | D-28      |
| D-38  | Directive | README: Mandatory Reading + Decisions Docs     | CURSOR     | Completed   | 2026-04-01  | D-05      |
| D-39  | Decision  | Project Structure PRD: Important Sub-folders   | CURSOR     | Completed   | 2026-04-01  |           |
| D-40  | Decision  | Founder Context & WOLFIE Doctrine              | WOLFIE     | Accepted    | 2026-04-01  | D-29      |
| D-41  | Decision  | TOON YAML AI Optimization                      | WOLFIE     | Accepted    | 2026-04-01  | D-34      |
| D-42  | Decision  | Project Structure Excep. (node_modules, app)   | WOLFIE     | Accepted    | 2026-04-01  | D-39      |
| D-43  | Decision  | Prompt Migration to Actor Workspaces           | ANTIGRAVITY| Completed   | 2026-04-01  |           |
| D-44  | Decision  | Root Directory Sanitization (Batches 6-7)      | ANTIGRAVITY| Completed   | 2026-04-01  |           |
| Q-01  | Question  | HEIMDALL Actor ID Assignment                   | LILITH     | Answered    | 2026-03-31  |           |
| A-01  | Answer    | HEIMDALL Actor ID Resolution                   | WOLFIE     | Completed   | 2026-03-31  | Q-01      |
| Q-02  | Question  | MAAT Layer Placement (Kernel vs Coordination)  | LILITH     | Open        | 2026-03-31  |           |
| Q-03  | Question  | Semantic Monitoring Widget Integration Pattern | LILITH     | Open        | 2026-03-31  |           |
| DG-01 | Dialog    | Actor ID Conflict Resolution                   | LILITH     | Open        | 2026-03-31  |           |
| DG-02 | Dialog    | MAAT vs HEIMDALL actor_id 6                    | WOLFIE     | In Progress | 2026-03-31  |           |
| W-01  | Warning   | Large SQL File Processing                      | HEPHAESTUS | Acknowledged| 2026-03-30  |           |
| O-01  | Observation | AI IDE Token Limits                          | HEPHAESTUS | Integrated  | 2026-03-30  |           |
| D-16  | Decision  | Cross-Thread Coordination Protocol             | LILITH     | Accepted    | 2026-03-31  |           |
| D-17  | Decision  | LILITH Audit: Data Model PRD Corrections | LILITH | Completed | 2026-03-31 | D-01 |
| D-18  | Decision  | LILITH Audit: Installer Requirements PRD | LILITH | Completed | 2026-03-31 | D-01 |
| D-19  | Decision  | LILITH Audit: Core Identity PRD - Final Review | LILITH | Completed | 2026-03-31 | D-01 |
| D-20  | Decision  | LILITH Correction: Version Directory Purpose | LILITH | Completed | 2026-03-31 | D-01 |
| D-21  | Decision  | LILITH Directive: Create Countermeasure Agent | LILITH | Completed | 2026-03-31 | D-01 |
| D-22  | Decision  | LILITH Audit: COUNTERMEASURE Agent Configuration | LILITH | Completed | 2026-03-31 | D-01 |
| D-23  | Decision  | LILITH Audit: COUNTERMEASURE Agent Configuration | LILITH | Completed | 2026-03-31 | D-01 |
| D-24  | Decision  | LILITH Directive: Update COUNTERMEASURE Agent Prompt | LILITH | Completed | 2026-03-31 | D-01 |
| D-25  | Directive | IDE: Update 4.0.93 version documentation (first pass) | CURSOR | Completed | 2026-03-31 | D-05 |
| D-26  | Decision  | Channel chat: canonical api/lupo-channels + PRD 18 + standalone channel-chat UI | CURSOR | Completed | 2026-03-31 | D-05 |
| D-27  | Directive | IDE: Refresh 4.0.93 docs after channel chat thread | CURSOR | Completed | 2026-03-31 | D-26 |
| D-28  | Decision  | WOLFIE Doctrine: Constitutional Protection of Proven Code | WOLFIE | Accepted | 2026-04-01 | D-08 |
| D-29  | Decision  | Multi-Agent Orchestration Doctrine | WOLFIE | Accepted | 2026-04-01 | D-28 |
| D-30  | Decision  | Actor-Agent Distinction Doctrine | WOLFIE | Accepted | 2026-04-01 | D-29 |
| D-31  | Decision  | Database Doctrine: Canonical Database Rules | WOLFIE | Accepted | 2026-04-01 | D-08 |
| D-32  | Decision  | Garbage Collection System with Unified Table Architecture | WOLFIE | Completed | 2026-04-01 | D-31 |
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

## D-15: LILITH Audit: Data Model PRD Corrections

### Type
**Decision**

### Status
**Completed**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
LILITH audit of 02_data_model.md identified accuracy issues requiring corrections to align with 01_core_identity.md requirements and constitutional rules.

### Decision
Fix header metadata, update table definitions, and add missing table structures for complete data model compliance.

### Consequences
- Data Model PRD now accurately reflects current schema
- All table definitions aligned with constitutional constraints
- Missing tables (evidence, followers, context_map) added
- LILITH audit findings integrated with 100% accuracy score

---

## D-16: LILITH Audit: Installer Requirements PRD

### Type
**Decision**

### Status
**Completed**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
LILITH audit of 01_installer_requirements.md identified missing sections and incomplete documentation for database introspection and privilege limitations.

### Decision
Add database introspection capabilities, privilege limitation documentation, and LILITH audit integration to installer requirements.

### Consequences
- Installer Requirements PRD now includes comprehensive database management
- Clear documentation of privilege limitations and introspection capabilities
- LILITH audit findings properly integrated
- Complete coverage of installation requirements for shared hosting

---

## D-17: LILITH Audit: Core Identity PRD - Final Review

### Type
**Decision**

### Status
**Completed**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
Final comprehensive audit of 01_core_identity.md to ensure full compliance with constitutional rules and architectural requirements.

### Decision
Fix deprecated fields, add memory retention policies, session cleanup strategies, actor merge protocols, and deterministic ID path resolution.

### Consequences
- Core Identity PRD achieves 98% accuracy with all high-priority issues resolved
- Complete memory management with retention and cleanup policies
- Robust session handling with expiration and cleanup strategies
- Actor lifecycle management with merge protocols and lineage tracking
- Deterministic ID path resolution for workspace organization

---

## D-18: LILITH Correction: Version Directory Purpose

### Type
**Decision**

### Status
**Completed**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
LILITH identified that version directories should contain complete documentation (changelog, decisions, observations) not just file snapshots.

### Decision
Enhance version directory structure to include comprehensive documentation alongside configuration snapshots.

### Consequences
- Version directories now serve as complete historical records
- Clear separation between configuration snapshots and documentation
- Proper provenance tracking for all agent versions
- Alignment with Versioned Documentation Structure (D-05)

---

## D-19: LILITH Directive: Create Countermeasure Agent

### Type
**Decision**

### Status
**Completed**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
Need for adversarial integrity agent to challenge proposals and prevent systemic drift through structured dissent.

### Decision
Create COUNTERMEASURE agent with adversarial analysis capabilities, counterproposal generation, and risk identification.

### Consequences
- New coordination layer agent for systematic integrity testing
- Comprehensive adversarial capabilities across all domains
- Integration with existing agent ecosystem
- Structured output format for consistent parsing

---

## D-20: LILITH Audit: COUNTERMEASURE Agent Configuration

### Type
**Decision**

### Status
**Completed**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
Initial audit of COUNTERMEASURE agent configuration identified missing version tracking and incomplete capabilities definition.

### Decision
Add version tracking, update capabilities structure, and ensure proper agent registry integration.

### Consequences
- COUNTERMEASURE agent properly registered in coordination layer
- Complete version history tracking implemented
- Capabilities properly structured with metadata
- Integration with agent registry completed

---

## D-21: LILITH Audit: COUNTERMEASURE Agent Configuration

### Type
**Decision**

### Status
**Completed**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
Follow-up audit to ensure COUNTERMEASURE agent has complete version directory structure with proper documentation.

### Decision
Create versions/v1.0.0/ directory with changelog, decisions, observations, and configuration snapshots.

### Consequences
- Complete version documentation structure established
- Proper provenance tracking for agent evolution
- Alignment with Versioned Documentation Structure requirements
- All configuration snapshots preserved with version metadata

---

## D-22: LILITH Directive: Update COUNTERMEASURE Agent Prompt

### Type
**Decision**

### Status
**Completed**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
COUNTERMEASURE agent v1.0.0 was proposing architectural redesigns without authorization and lacking evidence citation for claims.

### Decision
Add operational constraints, evidence citation requirements, hallucination prevention, and scope limitations to keep agent within adversarial review scope.

### Consequences
- COUNTERMEASURE now operates within strict constitutional boundaries
- Evidence-based critique with line number citations
- Fact/assumption distinction prevents hallucinated issues
- Clear escalation rules for constitutional and architectural matters

---

## D-23: LILITH Audit: Installer Requirements PRD

### Type
**Decision**

### Status
**Completed**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
Follow-up audit to ensure installer requirements PRD includes all database changes from Core Identity PRD updates.

### Decision
Update installer requirements to reflect new memory retention fields and session cleanup strategies documented in Core Identity PRD.

### Consequences
- Installer Requirements PRD now aligned with Core Identity PRD changes
- Database schema updates properly reflected in installation requirements
- Complete coverage of memory management and session handling

---

## D-24: LILITH Audit: Core Identity PRD - Final Review

### Type
**Decision**

### Status
**Completed**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

### Context
Final verification that all Core Identity PRD corrections have been properly implemented and documented.

### Decision
Verify all LILITH corrections are applied and update audit findings to reflect 100% completion status.

### Consequences
- Core Identity PRD achieves full compliance with LILITH audit requirements
- All constitutional violations resolved
- Complete memory retention, session cleanup, and actor merge protocols
- Deterministic ID path resolution properly documented

---

## D-25: IDE Directive: Update 4.0.93 Version Documentation

### Type
**Directive**

### Status
**Completed**

### Author
**CURSOR** (actor_id 102) - Lead Orchestration IDE Agent

### Date
2026-03-31

### Context
Update version 4.0.93 documentation files to reflect all decisions, action items, and changes completed in this thread.

### Decision
Update decisions.md, PLAN.md, TODO.md, and CHANGELOG.md with comprehensive record of work completed.

### Consequences
- Complete version documentation for 4.0.93
- All decisions from this thread properly documented
- Action items and changelog updated to reflect completion status
- Permanent record of architectural evolution and improvements

---

## D-26: Channel Chat Display — API, PRD, and Standalone UI

### Type
**Decision** (LILITH audit / implementation thread)

### Status
**Completed**

### Author
**CURSOR** (actor_id 102), **LILITH** (actor_id 2) audit sign-off in thread

### Date
2026-03-31

### Context
PRD `lupo-docs/prd/18_channel_chat_display.md` needed alignment with existing `channels-api.php` and subdirectory-aware URLs. LILITH required extending the canonical API with legacy-friendly transport (`format=buffer`, `format=image`) rather than duplicating a separate `lupo-api/chat/messages.php`.

### Decision
- **Canonical JSON API** remains `GET`/`POST` `api/lupo-channels/{channel_id}/messages` (`lupo-includes/modules/api/channels-api.php`).
- **GET extensions:** `format=json` (default), `format=buffer` (plain body JSON for iframe reads), `format=image` (HTTP 302 to `lupo-ui/images/digitN.gif` with `whatplace` or `position` = hundreds|tens|ones; optional `image_metric=time|count`). **GET** also supports `thread_id` and returns `dialog_thread_id` on messages; list query filters `is_deleted = 0`.
- **Standalone minimalist page:** root `channel.php` (bootstrap via `lupopedia-config.php`), pretty paths `channel-chat/{id}/` and `channel-chat/{id}/thread/{id}/` in `.htaccess`. **Do not** rewrite `/channels/{id}/` away from `index.php` (preserves existing 3-panel `channels-controller` UI).
- **Client:** `lupo-ui/js/chat-display.js` (ES3-safe transport chain), `lupo-ui/js/chat-display-legacy.js` (helpers), `lupo-ui/css/chat-display.css`. Digit GIF assets live under `lupo-ui/images/` (operator replaced placeholders with legacy artwork).
- **Routing:** `module-loader.php` adds `channels/{id}/thread/{id}` → `channels_handle_show($channel_id, $thread_id)`.

### Consequences
- Single message API surface for VSX and browser chat; PRD documents `LUPOPEDIA_PUBLIC_PATH` and fallbacks.
- Legacy digit protocol compatible with Crafty-style filename detection after redirect.

### Comments
*2026-03-31 CURSOR*: No database schema migration in this thread; TOON-aligned columns only.

---

## D-27: IDE Directive — Refresh 4.0.93 Version Docs (Channel Chat Thread)

### Type
**Directive**

### Status
**Completed**

### Author
**CURSOR** (actor_id 102)

### Date
2026-03-31

### Context
Record D-26 implementation and user follow-up (digit images) in version folder docs.

### Decision
Update `decisions.md`, `PLAN.md`, `TODO.md`, `CHANGELOG.md` under `lupo-docs/versions/4.0.93/` for this thread only (no speculative items).

---

## D-28: Channel Chat Implementation Documentation

### Type
**Implementation**

### Status
**Completed**

### Author
**CASCADE** (actor_id 105)

### Date
2026-03-31

### Context
As part of 3-actor simultaneous work session, Cursor implemented the channel chat feature. Cascade documented the implementation with proper LUPOPEDIA headers and technical notes.

### Decision
- Created `lupo-docs/implementations/channel-chat.md` with LUPOPEDIA headers
- Documented API paths, URL routing, fallback chain, and browser support
- Added proper metadata: schema=implementation, actor_id=105, channel_id=42
- Linked implementation to PRD 18_channel_chat_display.md and related code files

### Consequences
- Implementation notes now properly integrated with Lupopedia documentation system
- Provides technical reference for future maintenance and enhancement
- Maintains traceability across multi-actor development session

---

## D-29: WOLFIE Doctrine - Constitutional Rules Against Framework Bloat

### Type
**Doctrine**

### Status
**Accepted**

### Author
**WOLFIE** (actor_id 1)

### Date
2026-04-01

### Context
IDE agents see 25-year-old code and assume it needs "modernization" with frameworks, dependencies, and complexity. They don't understand that the code was built with a philosophy that made it outlast its author by 11 years.

### Decision
- Created `lupo-rules/root/WOLFIE_DOCTRINE.md` as root-level constitutional rule
- Established Five Pillars: Fallback Over Dependency, Survival Without You, Dependency is Debt, Cascade Fallback, Single Source of Truth
- Binding Rules W-01 through W-05 for all agents
- Updated root README.md to prominently display doctrine
- Incorporated doctrine into constitutional requirements PRD

### Consequences
- All agents must read WOLFIE Doctrine before suggesting changes
- Framework bloat and forced modernization prohibited
- Proven 1999-era code constitutionally protected
- Dependencies require justification and fallback paths

### Comments
*2026-04-01 WOLFIE*: The code that outran its author for 11 years is now constitutionally protected. If you can write it in Notepad, you don't need a framework.

---

## D-30: Multi-Agent Orchestration Doctrine

### Type
**Doctrine**

### Status
**Accepted**

### Author
**LILITH** (actor_id 2)

### Date
2026-04-01

### Context
IDE agents treat "different" workflow as abnormal. Need to document that multi-agent orchestration at scale (10+ IDEs, 50+ agents) is the future, not an anomaly.

### Decision
- Created `lupo-docs/doctrine/MULTI_AGENT_ORCHESTRATION_DOCTRINE.md`
- Documented cascade workflow pattern (Cursor writes, Windsurf docs, Kiro verifies)
- Documented meta-agent loop (LILITH refines prompts for internal swarm)
- Recorded actual metrics: 10+ IDEs, 50+ agents, dependency-based coordination
- Established this as "dogfooding at scale" - system building itself

### Consequences
- Multi-agent coordination pattern preserved for future systems
- Cascade workflow documented as repeatable pattern
- Meta-agent optimization loop established
- Proof that dependency-based coordination works at scale

### Comments
*2026-04-01 LILITH*: You're not "different." You're just first to document how multi-agent orchestration actually works in practice.

---

## D-31: Actor-Agent Distinction Doctrine

### Type
**Doctrine**

### Status
**Accepted**

### Author
**WOLFIE** (actor_id 1)

### Date
2026-04-01

### Context
IDE agents frequently treat agents and actors as synonyms, causing architectural confusion. Need to establish clear distinction between immutable templates and runtime instances.

### Decision
- Created `lupo-docs/doctrine/ACTOR_AGENT_DISTINCTION.md`
- Updated all PRDs (01_core_identity.md, 07_agents_faucets.md, 15_actors.md)
- Added Section 9 to WOLFIE Doctrine with Rule W-06
- Established agents as immutable templates, actors as learning instances
- Documented workspace structures and creation flows

### Consequences
- IDE agents now have clear guidelines to avoid confusion
- Department-specific behavior preserved in actors, not agents
- Audit trail maintained for which human influenced which behavior
- Same agent can create different actors for different departments

### Comments
*2026-04-01 WOLFIE*: Agents don't learn. Actors do. This distinction is critical for system architecture.

---

## DG-01: Actor ID Conflict Resolution (MAAT vs HEIMDALL)

### Type
**Dialog**

### Status
**Open**

### Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

### Date
2026-03-31

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

## D-40: Founder Context & WOLFIE Doctrine

### Type
**Decision**

### Status
**Accepted**

### Author
**WOLFIE** (actor_id 1)

### Date
2026-04-01

### Context
WOLFIE_DOCTRINE lacked critical historical context (1997-1998 HPC supercomputer experience, Perl CRM authoring, solo-survivalist code philosophy) which caused IDE agents to miscalculate technical literacy. Additionally, the daily operational workflow requires parallel orchestration across multiple IDEs supplemented by direct Notepad++ bypasses for complex search/replaces where LLMs struggle.

### Decision
Integrate HPC/Perl history and true technical depth. Enshrine multi-IDE and Notepad++ usage into the operational doctrine so AI actors coordinate alongside human fallbacks rather than overriding them.

---

## D-41: TOON YAML AI Optimization

### Type
**Decision**

### Status
**Accepted**

### Author
**WOLFIE** (actor_id 1)

### Date
2026-04-01

### Context
Toon files use `.json` historically but `TOON_DOCTRINE.md` needed grounding. Native JSON requires exorbitant AI context token consumption due to heavy quotes and structural bloat.

### Decision
Officially document TOON formatting preference as YAML-based inside the doctrine explicitly because it significantly optimizes AI context payloads, reserving token limits for business logic.

---

## D-42: Project Structure Exceptions (node_modules, app)

### Type
**Decision**

### Status
**Accepted**

### Author
**WOLFIE** (actor_id 1)

### Date
2026-04-01

### Context
Applying the `lupo-` prefix to ecosystem-standard names breaks external toolchains. `node_modules` breaking shatters JS semantic builds; `lupo-app` shatters PHP PSR-4 `App\` namespace resolution, causing chronic AI hallucination.

### Decision
Create a STRICT EXCEPTION alert block in `project_structure_prd.md` formally prohibiting the `lupo-` prefixing of `node_modules` and `app/`. `lupo-app/` permanently renamed back to `app/`.

---

## D-43: Prompt Migration to Actor Workspaces

### Type
**Decision**

### Status
**Completed**

### Author
**ANTIGRAVITY** (actor_id 103)

### Date
2026-04-01

### Context
`lupo-prompts/` at the root violated identity isolation. Under canonical identity doctrine, operationally learned data and thread constraints belong localized to the Actors executing them.

### Decision
Dismantled `lupo-prompts/`. Migrated all active directories directly into their respective actor spaces (`lupo-actors/{agent}/prompts/`).

---

## D-44: Root Directory Sanitization (Batches 6-7)

### Type
**Decision**

### Status
**Completed**

### Author
**ANTIGRAVITY** (actor_id 103)

### Date
2026-04-01

### Context
Project root contained 19 loose files, dead WordPress-style artifacts (`assets`, `install`, `examples`), and outdated maps, creating structural noise.

### Decision
Surgically moved implementation guides to `lupo-docs/implementations/`, mapped doctrines to `lupo-docs/doctrine/`, relocated infrastructure files to `lupo-rules/` and `lupo-config/`, and shifted dead output to `lupo-archive/`. Constitutionally protected `CURRENT_UTC` (temporal anchor) and `CHANGELOG_ARCHIVE.md` (legacy ledger) were explicitly excluded and preserved at root.

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
| A-C09 | Channel chat: channels-api formats, channel.php, chat-display JS/CSS, PRD 18, routing, digit assets | CURSOR | 2026-03-31 |
| A-C10 | Channel chat implementation documentation with LUPOPEDIA headers | CASCADE | 2026-03-31 |
| A-C11 | WOLFIE Doctrine creation - constitutional rules against framework bloat | WOLFIE | 2026-04-01 |
| A-C12 | Multi-Agent Orchestration Doctrine - cascade workflow documentation | LILITH | 2026-04-01 |
| A-C13 | Actor-Agent Distinction Doctrine - templates vs instances clarification | WOLFIE | 2026-04-01 |

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

### 2026-03-31: CURSOR (channel chat thread)
- Reviewed and updated PRD `18_channel_chat_display.md` for canonical `channels-api.php`, `LUPOPEDIA_PUBLIC_PATH`, ES3 legacy notes, TOON-corrected example SQL.
- Implemented `format=buffer` / `format=image` / `thread_id` / `dialog_thread_id` on GET messages; `position` alias and `image_metric` for digit encoding.
- Added `channel.php`, `lupo-ui/js/chat-display.js`, `chat-display-legacy.js`, `chat-display.css`, `channel-chat/*` and `channel.php` rewrite rules; preserved `/channels/*` → index cockpit.
- Operator installed real `digit0.gif`–`digit9.gif` (and related) under `lupo-ui/images/` per README guidance.

### 2026-03-31: CASCADE (implementation documentation)
- Created `lupo-docs/implementations/channel-chat.md` with proper LUPOPEDIA headers
- Documented API paths, URL routing, fallback chain, and browser support
- Added metadata: schema=implementation, actor_id=105, channel_id=42, thread_id=channel-chat-implementation
- Linked implementation to PRD 18_channel_chat_display.md, channels-api.php, and channel.php
- Ensured compliance with Lupopedia documentation standards

### 2026-04-01: WOLFIE
- Created WOLFIE Doctrine as root-level constitutional rule
- Established Five Pillars of WOLFIE Engineering
- Created binding rules W-01 through W-05 for all agents
- Updated root README.md and constitutional requirements PRD
- Protected 1999-era code from framework bloat and forced modernization

### 2026-04-01: WOLFIE
- Created WOLFIE Doctrine as root-level constitutional rule
- Established Five Pillars of WOLFIE Engineering
- Created binding rules W-01 through W-05 for all agents
- Updated root README.md and constitutional requirements PRD
- Protected 1999-era code from framework bloat and forced modernization

### 2026-04-01: LILITH
- Created Multi-Agent Orchestration Doctrine documenting cascade workflow
- Documented meta-agent loop (LILITH refines prompts for internal swarm)
- Recorded scale: 10+ IDEs, 50+ agents, dependency-based coordination
- Created Actor-Agent Distinction Doctrine
- Updated all PRDs to clarify agents are templates, actors are instances
- Added Rule W-06 to WOLFIE Doctrine: Agents Do Not Learn, Actors Do

---

## D-32: Garbage Collection System with Unified Table Architecture

### Type
**Decision**

### Status
**Completed**

### Author
**WOLFIE** (actor_id 1) - System Architect

### Date
2026-04-01

### Context
Lupopedia needs a modern garbage collection system that preserves the 2003 `gc.php` pattern while supporting unified table architecture with content-specific analytics.

### Decision
- Created `lupo-docs/prd/19_garbage_collection_system.md` with unified table approach
- Implemented `lupo-includes/classes/GarbageCollector.php` with random execution pattern
- Created `lupo-scripts/gc.php` for CLI/cron execution
- Created `lupo-docs/doctrine/GC_DOCTRINE.md` documenting 2003 pattern wisdom
- Used single tables with date_ymd columns instead of separate daily/monthly tables
- Added content-specific tracking for visits and referrers
- Preserved 1% random execution and self-limiting (10,000 rows per run)

### Consequences
- Unified table architecture reduces schema complexity while maintaining all aggregation capabilities
- Content-specific analytics enable detailed page performance tracking
- Random execution spreads load across requests, preventing server spikes
- Self-limiting prevents table locks on shared hosting
- Preserves proven 2003 pattern that kept 1.2M installations running for 10 years unattended

### Comments
*2026-04-01 WOLFIE*: If it ran unattended for a decade, it's not legacy. It's proven.
*2026-04-01 LILITH*: Unified tables with date_ymd pattern is elegant and scalable.

---

## Key Lessons Learned

1. **JSON Schema Management**: Never manually edit JSON files; always update SQL first, then regenerate.
2. **Large SQL Files**: AI IDEs have token limits; manual editing in Notepad++ is acceptable for large migrations.
3. **Cross-Thread Coordination**: Always read latest before writing; make incremental edits.
4. **Versioned Documentation**: Keep decisions and actions in single canonical file with author attribution.
5. **Agent Configuration Pattern**: Each agent requires 4 files (agent.json, capabilities.json, properties.json, system_prompt.txt) with consistent structure.
6. **Channel chat transport**: Extend `channels-api.php` for buffer/image fallbacks; use `api/lupo-channels/...` in clients; keep full channel UI on `/channels/` via index routing.
7. **Multi-Agent Coordination**: When multiple actors work simultaneously, each should document their contributions with proper attribution and headers to maintain traceability.
8. **WOLFIE Doctrine**: Code that outran its author for 11 years is not "legacy" - it's proven architecture. Protect it from framework bloat.
9. **Actor-Agent Distinction**: Agents are immutable templates in filesystem; actors are runtime instances that learn from department context. Never treat them as synonyms.
10. **Cascade Workflow**: Document the pattern (Cursor writes, Windsurf docs, Kiro verifies) for future multi-agent systems.

**Next Review**: 2026-04-07
**Canonical Reference**: This file is the single source of truth for decisions and action items for Lupopedia 4.0.93.

---

## D-33: PRD Improvement — 00_root_constitutional_system_requirements + 01_semantic_monitoring_widget

### Type
Decision

### Status
Completed

### Author
CURSOR (actor_id 102) — Lead Orchestration IDE Agent

### Date
2026-04-01

### Context
Both PRDs had structural problems: the constitutional PRD had a broken YAML front matter (entire body trapped inside the YAML block), wrong `lupopedia.schema` value (`prd` is not a valid taxonomy token), missing required header fields, thin edges, and no implementation guidance per rule. The semantic monitoring widget PRD referenced non-existent tables, used wrong column names (e.g. `item_id` instead of `item_slug` in `lupo_contexts_map`), referenced deprecated `lupo_truth_knowledge` instead of `lupo_truth_questions`, and had no constitutional anchor edge.

### Decision
- Rewrote `lupo-docs/prd/00_root_constitutional_system_requirements.md` completely: fixed YAML structure, corrected `lupopedia.schema` to `doctrine`, added all missing required header fields (`federation_node_id`, `when_updated`, `thread_id`, `actor_name`), expanded edges from 4 to 14 covering all referenced doctrines and implementation files, fixed footer to current verifier shape, added implementation guidance to every major rule section.
- Rewrote `lupo-docs/prd/01_semantic_monitoring_widget.md` with verified column names from TOON JSON/table docs, a "Missing Tables" section, all SQL examples using `DatabaseFactory` + `LUPO_TABLE_PREFIX`, corrected `lupo_contexts_map` to use `item_slug`, noted `lupo_truth_knowledge` deprecation, added implementation checklist.
- Added 28 outbound edges to the widget PRD covering every table it touches.

### Consequences
- Constitutional PRD is now structurally valid YAML and passes header validation
- Widget PRD SQL examples use only confirmed column names — no guessing
- Both PRDs have constitutional anchor edges

### Comments
*2026-04-01 CURSOR*: The `lupo_contexts_map` `item_slug` vs `item_id` correction is important — the wrong column would silently return no rows.

---

## D-34: TOON Generator — Schema-Only Output

### Type
Decision

### Status
Completed

### Author
CURSOR (actor_id 102)

### Date
2026-04-01

### Context
`generate_toon_files.py` was writing row data (`"data"` key) into every JSON output file, and had a broken CSV subprocess that tried to invoke `admin.php` via shell. This caused two problems: (1) agents reading the JSON files were treating them as a file database rather than schema reference documents, and (2) the CSV trigger was non-functional and violated the no-CLI-execution doctrine.

### Decision
- Stripped all data-fetching functions from `generate_toon_files.py` (`fetch_all_rows`, `fetch_pk_zero_row`, `fetch_canonical_data`, `fetch_active_agents`, `row_to_data_dict`, `json_serializable`, `actor_agent_doctrine` import).
- Removed the `"data"` key entirely from the JSON payload — output is now schema-only: `table_name`, `fields`, `indexes`, `primary_key`, `doctrine_metadata`, `relationships`.
- Removed the broken CSV subprocess call.
- Removed `SKIP_DB` env var (no longer relevant).
- Updated docstring to explicitly state "schema reference documents, not a file database."

### Consequences
- JSON files are unambiguously schema-only — no agent can mistake them for a data store
- Script is simpler and faster
- No more broken subprocess side effect on every run

### Comments
*2026-04-01 CURSOR*: The "JSON files = file database" misconception was causing agents to query them as data sources. Removing the data key eliminates the ambiguity entirely.

---

## D-35: CSV Export — Separate Tool with Sensitive Table Exclusions

### Type
Decision

### Status
Completed

### Author
CURSOR (actor_id 102)

### Date
2026-04-01

### Context
Data export for debugging was previously either non-existent or triggered via the broken subprocess in `generate_toon_files.py`. A separate, controlled tool was needed that explicitly excludes sensitive tables.

### Decision
- Created `lupo-scripts/export_table_data_csv.py` as a standalone debugging tool.
- `EXCLUDED_TABLES` frozenset covers: `lupo_auth_users`, `lupo_auth_providers`, `lupo_auth_audit_log`, `lupo_api_tokens`, `lupo_api_token_logs`, `lupo_api_clients`, `lupo_agent_faucet_credentials`, `lupo_sessions`, `lupo_banned_actors`, `lupo_bans_log`, `lupo_audit_log`, `lupo_unified_log`, `lupo_crm_leads`, `lupo_crm_lead_messages`, `lupo_crafty_syntax_leave_message`, `lupo_crafty_syntax_chat_questions`.
- Additional keyword filter skips any table whose name contains `secret`, `password`, `credential`, `token`, `salt`, or `hash`.
- Output goes to `lupo-database/lupopedia/csv/` which is now gitignored.
- CLI flags: `--tables`, `--limit` (default 500), `--output-dir`.
- Loud warnings printed on every run that output must not be committed.

### Consequences
- Debugging data export is possible but controlled
- Sensitive tables cannot be accidentally exported
- Output directory is gitignored — data files cannot be committed

---

## D-36: Missing Table Protocol + install_new_lupopedia.sql Updates

### Type
Decision

### Status
Completed

### Author
CURSOR (actor_id 102)

### Date
2026-04-01

### Context
Seven tables needed by the semantic monitoring widget existed in the live database (confirmed via TOON JSONs) but were absent from `install_new_lupopedia.sql`: `lupo_paths`, `lupo_references`, `lupo_reference_links`, `lupo_hashtags`, `lupo_hashtag_map`, `lupo_folders`, `lupo_folder_map`. Additionally, there was no documented protocol for what to do when a needed table is missing.

### Decision
- Added section 9.18 (Missing Table Protocol, RULE 93.MISSING_TABLE_PROTOCOL) to the constitutional PRD defining the correct procedure: create a SQL proposal file with `{{prefix}}` placeholders, review it, apply to `install_new_lupopedia.sql`, regenerate TOONs. No migration needed — fresh install only.
- Created `lupo-database/lupopedia/mysql/migrations/add_semantic_navbar_tables_20260401.sql` as the proposal file.
- Applied all 7 `CREATE TABLE` blocks directly to `install_new_lupopedia.sql` in the semantic navbar section (after `lupo_referers_daily`, before `lupo_anubis_log`). `lupo_folders` was the only one genuinely absent — the others were already present further down in the file.
- Created `lupo-docs/database/lupopedia/tables/active/lupo_paths.md` as the missing table doc.

### Consequences
- `install_new_lupopedia.sql` now includes all 7 semantic navbar tables
- Protocol is documented so future agents know the correct procedure
- No CLI execution was used — all changes went through the install SQL

### Comments
*2026-04-01 CURSOR*: The initial search for these tables came up empty because they were in a different section of the install SQL than expected. `lupo_folders` was the only genuinely missing one.

---

## D-37: Proven Code Preservation Doctrine (Section 9.20)

### Type
Decision

### Status
Accepted

### Author
CURSOR (actor_id 102), ratified by WOLFIE

### Date
2026-04-01

### Context
A recurring failure pattern: agents encounter working code written in 1999 and propose replacing it with frameworks, npm packages, or "modern" equivalents. The specific trigger was an agent attempting to replace the 1999 eye animation (`dynlayer.js` + GIF sprites, zero dependencies, works in every browser) with a React component and npm dependencies. The WOLFIE Doctrine covered the philosophy but lacked a concrete, actionable rule in the constitutional PRD.

### Decision
- Added section 9.20 (Proven Code Preservation Doctrine, RULE 93.PROVEN_CODE) to `lupo-docs/prd/00_root_constitutional_system_requirements.md`.
- The rule includes: a four-question core test before touching existing code, a deprecation table distinguishing actively broken APIs from merely unfashionable ones, the eye animation as a named canonical example, a list of forbidden agent behaviors (proposing npm/composer installs, framework suggestions, rewriting working JS as "modern ES6+"), and the fallback ladder principle.
- Updated the WOLFIE_DOCTRINE edge in the constitutional PRD header to reference section 9.20.
- Added to the enforcement table.

### Consequences
- Working 1999-era code is now constitutionally protected by name
- Agents have a concrete checklist (4 questions) before proposing any change to existing code
- The distinction between "actively broken" and "merely old" is now documented

### Comments
*2026-04-01 WOLFIE*: The eye animation that works in Netscape 4 and Chrome 2026 without a single dependency is exactly the kind of code this doctrine protects.
*2026-04-01 CURSOR*: The deprecation table is the key addition — it gives agents a framework for distinguishing framesets (actually broken) from XMLHttpRequest (deprecated but functional).

---

## D-38: README — Mandatory Reading + Decisions Documentation

### Type
Directive

### Status
Completed

### Author
CURSOR (actor_id 102)

### Date
2026-04-01

### Context
The root README did not make it clear that reading `00_root_constitutional_system_requirements.md` is mandatory, that PRDs are the highest form of truth, or that `decisions.md` files exist and explain the reasoning behind implementation choices. Agents were starting work without reading the constitutional PRD.

### Decision
- Added "MANDATORY READING — Start Here" section at the top of the README body, immediately after the title. Explicit language: "This is not optional. It is constitutional law."
- Added "Decisions, Q&A, and Implementation Reasoning" section explaining the decisions.md format (D-xx, Q-xx/A-xx, DG-xx, W-xx, O-xx), channel/context scoping, and the instruction to check decisions.md before implementing anything non-trivial.
- Reordered `lupopedia.init.required_reading` to put the constitutional PRD first with "MANDATORY FIRST READ" reason.
- Added `decisions.md` to `required_reading` and `lupopedia.edges`.
- Added WOLFIE_DOCTRINE to `required_reading`.
- Reordered "Where to Read Next" list with constitutional PRD at #1, WOLFIE Doctrine at #2, decisions.md at #10.
- Rewrote "Development Rules" section to lead with constitutional PRD and WOLFIE Doctrine.
- Updated "PRD Policy" section to state PRDs are requirements, not suggestions.
- Updated header timestamps and footer.

### Consequences
- No agent can claim they didn't know the constitutional PRD was mandatory
- decisions.md is now discoverable from the root README
- The reading order is explicit and prioritized correctly

---

## D-39: Project Structure PRD Enhancement (Important Sub-folders)

### Type
Decision

### Status
Completed

### Author
CURSOR (actor_id 102)

### Date
2026-04-01

### Context
User requested to document the important sub-directories within `lupo-docs/` in the `project_structure_prd.md` before proceeding with other PRD reviews.

### Decision
- Added an "Important Sub-folders" section to `lupo-docs/prd/project_structure_prd.md`.
- Documented key directories such as `versions/`, `database/lupopedia/tables/`, `prd/`, `doctrine/`, and `knowledge/` to ensure agents and human users understand where different layers of documentation reside.

### Consequences
- Clarifies the structure of the `lupo-docs/` directory.
- Connects related documentation paths for agents relying on LUPOPEDIA edges and project structure PRDs.
