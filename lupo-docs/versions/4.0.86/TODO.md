---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-docs/versions/4.0.86/TODO.md"
  last_modified_utc: "20260323_113000"
  channel_id: 42
  thread_id: "version-scope-lock"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "documentation"
  artifact_kind: "version_todo"
  purpose: "TODO list for version 4.0.86 with 22-agent requirement and database alignment."
  tags: ["wolfie", "todo", "version_4.0.86", "22_agents", "db_alignment"]
  edges:
    outbound_edges:
      - { to: "lupo-channels/58", type: "authoritative_work", weight: 1.0, reason: "Actor Model System implementation" }
      - { to: "lupo-channels/59", type: "authoritative_work", weight: 1.0, reason: "ROSE/DIALOG System implementation" }
      - { to: "lupo-channels/60", type: "authoritative_work", weight: 1.0, reason: "Agent System coordination" }
---

# 4.0.86 TODO

## Header Validation Follow-up

### Python Validation Parity
- [x] Shared Python header validation module added (`lupo-scripts/lib/header_validation.py`)
- [x] Validation gate wired into listed Python importers
- [ ] Add actor lookup mapping in Python importers for `actor_id` ↔ `actor_name` parity where available
- [ ] Add cross-language parity tests to prevent PHP/Python drift

### Remaining ingestion gaps
- [ ] `lupo-includes/classes/MetadataExtractor.php` (header-bearing metadata read path)
- [ ] `lupo-includes/classes/UrlResolver.php` fallback parser mode

### Future improvements
- [ ] Publish single shared validation specification for PHP + Python
- [ ] Optional CLI bridge for strict single-source validation engine (only if parity maintenance cost grows)

## Carryforward Summary
- **Source**: 4.0.85 (INSTALL READY + SYSTEM COMPLIANT)
- **Items Carried Forward**: 0 (scope lock reset)
- **Status**: Fresh start for 4.0.86 focused scope

---

## Version 4.0.86 Scope

**SCOPE LOCK**: Only Channel 58, 59, and 60 work

**DEFERRED**: All other work moved to 4.0.87

---

## Channel 58 — Actor Model System

### Database Implementation
- [ ] Execute migration scripts from approved proposal
- [ ] Add department_id to lupo_auth_users table
- [ ] Enforce department_id NOT NULL in lupo_actors
- [ ] Update DDL in install_new_lupopedia.sql
- [ ] Regenerate TOON files

### Filesystem Alignment
- [ ] Create .metadata.yaml files for all actor directories
- [ ] Enforce required fields (actor_id, slug, department_id, pairing_mode)
- [ ] Add prompt layer directories where needed
- [ ] Create validation script for filesystem consistency

### Documentation
- [ ] Create ACTOR_RESOLUTION_CONTRACT.md
- [ ] Update lupo-docs/actors.md
- [ ] Update lupo-actors/README.md
- [ ] Update lupo-docs/ACTOR_IDENTITIES.md

### Implementation
- [ ] Implement resolution algorithm in app/Services/
- [ ] Test end-to-end actor resolution
- [ ] Validate deterministic behavior
- [ ] Integration testing with existing systems

---

## Channel 59 — ROSE/DIALOG System

### Packet Contract Design
- [ ] Define ROSE packet structure for emotional dialogue
- [ ] Specify mood encoding format
- [ ] Create message type taxonomy
- [ ] Design cultural context integration

### Database Schema
- [ ] Add mood_label field to relevant tables
- [ ] Create mood taxonomy tables
- [ ] Design emotional dialogue structure
- [ ] Align with existing DB mood tables

### Mood System
- [ ] Define comprehensive mood taxonomy
- [ ] Create mood transition rules
- [ ] Design emotional intensity metrics
- [ ] Specify cultural modifiers

### Implementation
- [ ] Implement mood labeling system
- [ ] Create emotional dialogue handlers
- [ ] Test role-play scenarios
- [ ] Validate cultural bridge functionality

---

## Channel 60 — Agent System (22-AGENT REQUIREMENT)

### Agent Creation Tasks (×22)

#### Definition Requirements
- [ ] Create JSON definitions for all 22 agents in lupo-agents/
- [ ] Ensure required fields: agent_id, slug, provider, capabilities
- [ ] Validate structure and syntax compliance

#### Actor Alignment Tasks
- [ ] Create folders for all 22 agents in lupo-actors/<slug>/
- [ ] Add valid LUPOPEDIA headers to all agent folders
- [ ] Ensure correct department mapping for all agents
- [ ] Create proper metadata structure

#### Prompt Structure Tasks
- [ ] Create system layer prompts for all agents
- [ ] Create department layer prompts where required
- [ ] Create human layer prompts where required
- [ ] Validate prompt hierarchy and structure

#### Documentation Tasks
- [ ] Write purpose definitions for all 22 agents
- [ ] Document role specifications for all agents
- [ ] List capabilities for all agents
- [ ] Create channel usage guidelines for all agents
- [ ] Document interaction protocols with other actors
- [ ] Add ROSE/DIALOG compatibility statements where applicable

### Database Alignment Tasks (CRITICAL)

#### TOON File Validation
- [ ] Verify lupo_agents.json reflects all 22 agents
- [ ] Verify lupo_actors.json aligns with agent folders
- [ ] Verify lupo_departments.json includes all agent departments
- [ ] Validate lupo_actor_moods.json structure
- [ ] Check lupo_emotional_geometry_calibrations.json
- [ ] Verify lupo_emotional_frameworks.json

#### Database Standards Enforcement
- [ ] Ensure correct columns present in all tables
- [ ] Verify soft-delete fields: is_deleted, deleted_ymdhis
- [ ] Validate BIGINT UTC timestamps (YYYYMMDDHHIISS)
- [ ] Ensure NO foreign keys, triggers, or auto-increment

#### Database ↔ Filesystem Alignment
- [ ] Validate database reflects filesystem structure
- [ ] Create validation script to prevent drift
- [ ] Ensure all agent changes update database
- [ ] Test alignment validation procedures

### ROSE Compatibility Tasks
- [ ] Update agent prompts to reference mood_RGB and mood_label
- [ ] Document ROSE/DIALOG interaction for applicable agents
- [ ] Ensure usage of mood tables is documented
- [ ] Validate emotional dialogue integration

### Channel Coordination Tasks
- [ ] Track agent creation progress in Channel 60
- [ ] Coordinate cross-channel references (58, 59, 60)
- [ ] Maintain agent registry updates
- [ ] Document agent system decisions

---

## Completion Criteria

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

---

## Deferred to 4.0.87

All work not related to Channels 58, 59, or 60 has been explicitly deferred to version 4.0.87:

- Channel 42 coordination improvements
- Multi-agent workflow enhancements
- System optimization tasks
- Feature development outside scope
- Documentation updates not related to core systems
- Any other agent work beyond the 22 minimum requirement

---

*Last Updated:* 20260323_113000  
*Scope Lock By:* WOLFIE (actor_id 1)  
*Version:* 4.0.86  
*Status:* SCOPE LOCKED WITH 22-AGENT REQUIREMENT

---

## Carried Forward / Blocked (8 items)

### Schema/Design Blockers (5 items)
- **task_semantic_validity_blocker** (Thread 1004)
  - Owner: lilith
  - Status: blocked
  - Blocker: semantic_validity
  - Action: WOLFIE decision required

- **task_actor_architecture** (Thread 1009)
  - Owner: athena
  - Status: blocked
  - Blocker: design_approval
  - Action: WOLFIE decision required

- **task_actor_architecture_canonical** (Thread 1036)
  - Owner: athena
  - Status: blocked
  - Blocker: implementation_approval
  - Action: WOLFIE decision required

- **task_lilith_versioning_doctrine** (Thread 1037)
  - Owner: lilith
  - Status: blocked
  - Blocker: doctrine_decision
  - Action: WOLFIE decision required

- **task_ch42_th2004** (Thread 2004)
  - Owner: wolfie
  - Status: blocked
  - Blocker: schema_projection (4 blockers)
  - Action: WOLFIE decision required

### System Blockers (3 items)
- **task_ch42_th1049** (Thread 1049)
  - Owner: wolfie
  - Status: blocked
  - Blocker: reaudit_gate
  - Action: Pending reaudit completion

- **task_ch42_th1036** (Thread 1036)
  - Owner: athena
  - Status: blocked
  - Blocker: canonical_design
  - Action: WOLFIE decision required

- **task_ch42_th1037** (Thread 1037)
  - Owner: lilith
  - Status: blocked
  - Blocker: doctrine_gap
  - Action: WOLFIE decision required

---

## Normalized Task States

### Active Tasks (8)
Currently being worked - focus of immediate attention
- **task_human_verification_workflow** (Thread 1008) - Owner: athena - Tier: 3
- **task_human_verification_workflow_4_0_85** (Thread 1038) - Owner: athena - Tier: 3
- **task_ch42_th1041** (Thread 1041) - Owner: wolfie - Tier: 4
- **task_ch42_th1042** (Thread 1042) - Owner: wolfie - Tier: 4
- **task_ch42_th1043** (Thread 1043) - Owner: wolfie - Tier: 4
- **task_ch42_th1044** (Thread 1044) - Owner: wolfie - Tier: 4
- **task_ch42_th1045** (Thread 1045) - Owner: wolfie - Tier: 4
- **task_ch42_th1046** (Thread 1046) - Owner: wolfie - Tier: 4
- **task_ch42_th1047** (Thread 1047) - Owner: wolfie - Tier: 4
- **task_ch42_th1048** (Thread 1048) - Owner: wolfie - Tier: 4
- **task_ch66_th1038** (Thread 1038) - Owner: athena - Tier: 4

### Ready Tasks (35)
Ready to be queued for execution when capacity allows

#### System Foundations (10 tasks)
- **task_ch42_th1009** (Thread 1009) - Owner: athena - Tier: 3
- **task_ch42_th1010** (Thread 1010) - Owner: athena - Tier: 3
- **task_ch42_th1011** (Thread 1011) - Owner: athena - Tier: 3
- **task_ch42_th1021** (Thread 1021) - Owner: athena - Tier: 3
- **task_ch42_th1022** (Thread 1022) - Owner: athena - Tier: 3
- **task_ch42_th1023** (Thread 1023) - Owner: athena - Tier: 3
- **task_ch42_th1024** (Thread 1024) - Owner: athena - Tier: 3
- **task_ch42_th1025** (Thread 1025) - Owner: athena - Tier: 3
- **task_ch42_th1026** (Thread 1026) - Owner: athena - Tier: 3
- **task_ch42_th1027** (Thread 1027) - Owner: athena - Tier: 3

#### Runtime Systems (15 tasks)
- **task_ch1_th1015** (Thread 1015) - Owner: wolfie - Tier: 4
- **task_ch1_th1024** (Thread 1024) - Owner: wolfie - Tier: 4
- **task_ch1_th1035** (Thread 1035) - Owner: wolfie - Tier: 4
- **task_ch1_th1041** (Thread 1041) - Owner: wolfie - Tier: 4
- **task_ch7_th1011** (Thread 1011) - Owner: athena - Tier: 4
- **task_ch7_th1034** (Thread 1034) - Owner: athena - Tier: 4
- **task_ch7_th1035** (Thread 1035) - Owner: athena - Tier: 4
- **task_ch11_th1010** (Thread 1010) - Owner: athena - Tier: 4
- **task_ch11_th1021** (Thread 1021) - Owner: athena - Tier: 4
- **task_ch17_th1009** (Thread 1009) - Owner: athena - Tier: 4
- **task_ch31_th1016** (Thread 1016) - Owner: athena - Tier: 4
- **task_ch42_th1039** (Thread 1039) - Owner: athena - Tier: 4
- **task_ch66_th1003** (Thread 1003) - Owner: athena - Tier: 4
- **task_ch66_th1007** (Thread 1007) - Owner: athena - Tier: 4
- **task_ch66_th1017** (Thread 1017) - Owner: athena - Tier: 4
- **task_ch66_th1025** (Thread 1025) - Owner: athena - Tier: 4

#### Maintenance (8 tasks)
- **task_ch51_th1021** (Thread 1021) - Owner: athena - Tier: 5
- **task_ch51_th1022** (Thread 1022) - Owner: athena - Tier: 5
- **task_ch51_th1026** (Thread 1026) - Owner: athena - Tier: 5
- **task_ch51_th1032** (Thread 1032) - Owner: athena - Tier: 5
- **task_ch51_th1033** (Thread 1033) - Owner: athena - Tier: 5
- **task_ch51_th1037** (Thread 1037) - Owner: athena - Tier: 5
- **task_ch51_th1039** (Thread 1039) - Owner: athena - Tier: 5
- **task_ch88_th1004** (Thread 1004) - Owner: athena - Tier: 5

### Blocked Tasks (8)
Blocked by decisions or dependencies

#### Decision Required (5 tasks)
- **task_actor_architecture** (Thread 1009) - Owner: athena - Blocker: WOLFIE decision
- **task_actor_architecture_canonical** (Thread 1036) - Owner: athena - Blocker: WOLFIE decision
- **task_lilith_versioning_doctrine** (Thread 1037) - Owner: lilith - Blocker: WOLFIE decision
- **task_semantic_validity_blocker** (Thread 1004) - Owner: lilith - Blocker: WOLFIE decision
- **task_ch42_th2004** (Thread 2004) - Owner: wolfie - Blocker: WOLFIE decision

#### Dependency Blocked (3 tasks)
- **task_ch42_th1049** (Thread 1049) - Owner: wolfie - Blocker: reaudit_gate
- **task_ch42_th1036** (Thread 1036) - Owner: athena - Blocker: canonical_design
- **task_ch42_th1037** (Thread 1037) - Owner: lilith - Blocker: doctrine_gap

### Deferred Tasks (3)
Deferred until capacity available
- **task_ch42_th1030** (Thread 1030) - Owner: hephaestus - Reason: capacity_based
- **task_ch42_th1032** (Thread 1032) - Owner: hephaestus - Reason: capacity_based
- **task_ch42_th1035** (Thread 1035) - Owner: hephaestus - Reason: capacity_based

---

## Carried Forward / Deferred (3 items)

### Already Deferred from 4.0.85
- **task_ch42_th1030** (Thread 1030)
  - Owner: hephaestus
  - Status: deferred
  - Reason: Explicitly deferred to 4.0.86

- **task_ch42_th1032** (Thread 1032)
  - Owner: hephaestus
  - Status: deferred
  - Reason: Explicitly deferred to 4.0.86

- **task_ch42_th1035** (Thread 1035)
  - Owner: hephaestus
  - Status: deferred
  - Reason: Explicitly deferred to 4.0.86

---

## Carried Forward / Partial (2 items)

### Workflow/Documentation Items
- **task_human_verification_workflow** (Thread 1008)
  - Owner: athena
  - Status: partial
  - Completion: Workflow designed, not implemented
  - Action: Complete implementation

- **task_human_verification_workflow_4_0_85** (Thread 1038)
  - Owner: athena
  - Status: partial
  - Completion: Workflow scaffolded, missing validation evidence
  - Action: Add validation evidence and complete

---

## Newly Opened 4.0.86 Work

### None yet
- All current work is carried forward from 4.0.85
- New 4.0.86 work will be added as needed

---

## Priority Execution Order

### P0 - WOLFIE Decisions (Immediate)
1. Resolve actor architecture design approval
2. Resolve actor architecture implementation authorization
3. Resolve versioning doctrine recommendations
4. Resolve semantic validity blocker
5. Resolve schema projection blockers

### P1 - Unblock Critical Path
1. Complete reaudit gate (task_ch42_th1049)
2. Resolve canonical design issues
3. Resolve doctrine gap issues

### P2 - Complete Partial Work
1. Complete human verification workflow implementation
2. Add validation evidence to workflow

### P3 - Standard In-Progress Work
1. Continue with 45 in-progress items by actor capacity
2. Activate deferred items as capacity allows

---

## Authority Note
This TODO.md is a derived view of the authoritative TASK_REGISTRY.md. All task state and ownership information must match the registry exactly.
