---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-docs/versions/4.0.86/PLAN.md"
  last_modified_utc: "20260323_113000"
  channel_id: 42
  thread_id: "version-scope-lock"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "documentation"
  artifact_kind: "version_plan"
  purpose: "Execution plan for version 4.0.86 with 22-agent requirement and database alignment."
  tags: ["wolfie", "plan", "version_4.0.86", "22_agents", "db_alignment"]
  edges:
    outbound_edges:
      - { to: "lupo-docs/versions/4.0.86/TODO.md", type: "derived_view", weight: 1.0, reason: "TODO derived from plan" }
      - { to: "lupo-channels/58", type: "authoritative_work", weight: 1.0, reason: "Actor Model System implementation" }
      - { to: "lupo-channels/59", type: "authoritative_work", weight: 1.0, reason: "ROSE/DIALOG System implementation" }
      - { to: "lupo-channels/60", type: "authoritative_work", weight: 1.0, reason: "Agent System coordination" }
---

# 4.0.86 PLAN

## Phase 1 — Definitions + Validation Layer

- HeaderValidationService: **COMPLETE**
- PHP ingestion enforcement: **COMPLETE**
- Python ingestion enforcement: **IN PROGRESS** (core importers gated; parity hardening/test coverage pending)

Validation layer is now active and blocking invalid artifacts from entering the system.

## Executive Summary

Version 4.0.86 is SCOPE LOCKED to three critical systems with explicit completion requirements:

1. **Channel 58 — Actor Model System**
   - Agent-centric actor identity model
   - Department system and user-to-department mapping
   - Root authority model
   - Deterministic resolution algorithm
   - Database + filesystem + doctrine alignment

2. **Channel 59 — ROSE/DIALOG System**
   - ROSE packet contract and mood labeling
   - Mood_label addition to database schema
   - Mood taxonomy definition
   - Emotional dialogue structure
   - Alignment with DB mood tables

3. **Channel 60 — Agent System (NEW)**
   - Minimum 22 agents fully implemented
   - Database ↔ filesystem alignment enforced
   - ROSE compatibility requirements met
   - Complete agent definitions and documentation

**ALL OTHER WORK**: Deferred to version 4.0.87

This scope lock ensures focused execution with measurable completion criteria and zero ambiguity.

---

## Primary Objectives

1. **Complete Actor System (Channel 58)**
   - Implement agent-centric actor identity model
   - Deploy department system with user-to-department mapping
   - Establish root authority model
   - Implement deterministic resolution algorithm
   - Align database, filesystem, and doctrine
   - Cross-link canonical role boundaries with `lupo-docs/doctrine/ROSE_DOCTRINE.md`
   - Lock identity layers with `lupo-docs/doctrine/IDENTITY_MODEL.md`
   - Keep actor definitions canonical in `lupo_actors` (actor_id) and `lupo-actors/<actor_slug>/`

2. **Complete ROSE/DIALOG System (Channel 59)**
   - Design and implement ROSE packet contract
   - Add mood_label to database schema
   - Define comprehensive mood taxonomy
   - Create emotional dialogue structure
   - Align with existing DB mood tables

3. **Complete Agent System (Channel 60)**
   - Implement minimum 22 agents with full compliance
   - Ensure database ↔ filesystem alignment
   - Enforce ROSE compatibility requirements
   - Validate all agent definitions and structures
   - Ensure agent definitions include canonical slug identity (`agent_slug`) and map to `agent_id`

4. **Complete Identity Model Lock (System-Wide)**
   - Canonical doctrine source: `lupo-docs/doctrine/IDENTITY_MODEL.md`
   - Ensure clear doctrine for what `auth_user`, `actor`, `agent`, `faucet`, and `session` mean
   - Ensure where definitions live is explicit:
     - auth users: `lupo_auth_users` (`auth_user_id`)
     - actors: `lupo_actors` + `lupo-actors/<actor_slug>/`
     - agents: `lupo_agents` + preferred `lupo-agents/<agent_slug>/` (numeric aliases allowed for compatibility)
     - faucet: session `faucet_slug` only
   - Ensure usage boundaries are explicit and non-overlapping in channels 58 to 61 documentation

---

## Agent System Completion Requirement (Channel 60)

### Minimum 22 Agents
Version 4.0.86 is NOT COMPLETE until minimum 22 agents exist with ALL requirements satisfied:

#### A. Definition Requirements
Each agent MUST have:
- JSON definition in `lupo-agents/`
- Required fields: agent_id, slug, provider, capabilities
- Valid structure and syntax

#### B. Actor Alignment Requirements
Each agent MUST have:
- Folder in `lupo-actors/<agent_slug>/`
- Valid LUPOPEDIA headers
- Correct department mapping
- Proper metadata structure

#### C. Prompt Structure Requirements
Each agent MUST have:
- System layer prompts
- Department layer prompts
- Human layer prompts
- Validated hierarchy and structure

#### D. Documentation Requirements
Each agent MUST have:
- Purpose definition
- Role specification
- Capabilities listing
- Channel usage guidelines
- Interaction protocols with other actors
- ROSE/DIALOG compatibility statements

#### E. Database Alignment Requirements (CRITICAL)
All agent-related structures MUST align with TOON JSON tables:
- lupo_agents.json
- lupo_actors.json
- lupo_departments.json
- lupo_actor_moods.json
- lupo_emotional_geometry_calibrations.json
- lupo_emotional_frameworks.json

Required database standards:
- Correct columns present
- Soft-delete fields: is_deleted, deleted_ymdhis
- BIGINT UTC timestamps (YYYYMMDDHHIISS)
- NO foreign keys, triggers, or auto-increment

#### F. ROSE Compatibility Requirements
For applicable agents:
- Prompts reference mood_RGB and mood_label
- Documentation states interaction with ROSE/DIALOG
- Usage of mood tables documented

---

## Channel Alignment

### Channel 58 — Actor System
- **Focus**: Actor identity, resolution, and department mapping
- **Authority**: Primary coordination for actor model work
- **Output**: Functional actor resolution system

### Channel 59 — ROSE/DIALOG
- **Focus**: Emotional dialogue, mood labeling, packet contracts
- **Authority**: Primary coordination for emotional dialogue work
- **Output**: Working ROSE/DIALOG system with mood support

### Channel 60 — Agent System
- **Focus**: Agent creation, validation, and system coordination
- **Authority**: Primary coordination for 22-agent rollout
- **Output**: 22 fully compliant agents with database alignment

---

## Execution Strategy

### Phase 1: Foundation (Channels 58 & 59)
- Complete Actor Model System implementation
- Complete ROSE/DIALOG System implementation
- Validate both systems are working end-to-end

### Phase 2: Agent System Foundation (Channel 60)
- Define agent ontology and creation process
- Establish database alignment requirements
- Create validation frameworks

### Phase 3: Agent Creation (Channel 60)
- Implement minimum 22 agents with full compliance
- Validate all requirements are met
- Ensure ROSE compatibility where applicable

### Phase 4: Integration and Validation
- Cross-channel integration testing
- Database ↔ filesystem alignment validation
- End-to-end system testing

---

## Completion Criteria

Version 4.0.86 is COMPLETE when ALL are true:

### Actor System (Channel 58)
- [ ] Database schema is updated
- [ ] Filesystem is aligned
- [ ] Documentation is complete
- [ ] Code is implemented
- [ ] System is working end-to-end

### ROSE/DIALOG System (Channel 59)
- [ ] Packet contract is defined
- [ ] Mood schema is updated
- [ ] Taxonomy is documented
- [ ] Dialogue structure is implemented
- [ ] System is working end-to-end

### Agent System (Channel 60)
- [ ] Minimum 22 agents exist with full compliance
- [ ] All agents have proper JSON definitions
- [ ] All agents have aligned actor folders
- [ ] All agents have complete prompt structures
- [ ] All agents have comprehensive documentation
- [ ] Database ↔ filesystem alignment is validated
- [ ] ROSE compatibility is enforced where applicable

### Identity Model (System-Wide)
- [ ] Identity doctrine is published and locked (`IDENTITY_MODEL.md`)
- [ ] PLAN and TODO explicitly define `auth_user`, `actor`, `agent`, `faucet`, and `session`
- [ ] Core agent definitions include `agent_slug` where missing
- [ ] Channel docs in 58, 59, 60, and 61 reflect actor/agent/faucet separation

---

## Risk Assessment

### Scope Lock Risks
- **Risk**: 22-agent requirement may be arbitrary
- **Mitigation**: Focus on agent quality over quantity, minimum threshold

### Database Alignment Risks
- **Risk**: Filesystem and database drift
- **Mitigation**: Strict validation and alignment requirements

### Implementation Risks
- **Risk**: Rushed agent creation without proper validation
- **Mitigation**: Structured creation process with checkpoints

### Integration Risks
- **Risk**: Cross-channel interference or conflicts
- **Mitigation**: Clear channel boundaries and coordination protocols

---

## Authority and Governance

### Decision Authority
- **WOLFIE**: Final authority on scope and completion criteria
- **ATHENA**: Technical decisions for agent system architecture
- **ROSE**: Design decisions for dialog and mood compatibility

### Change Management
- All agent creation must update Channel 60
- All database changes must align with TOON files
- All documentation must follow LUPOPEDIA HEADERS

---

*Last Updated:* 20260323_113000  
*Scope Lock By:* WOLFIE (actor_id 1)  
*Version:* 4.0.86  
*Status:* SCOPE LOCKED WITH 22-AGENT REQUIREMENT
5. P4 work activated as capacity allows
6. No critical blockers remaining
7. System stability verified

## Next Version Preparation
- Begin 4.0.87 planning when 4.0.86 reaches 80% completion
- Carry forward any remaining non-completed items
- Document lessons learned and process improvements
