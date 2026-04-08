---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260403221556"
  file_path_from_root: "lupo-docs/prd/32_actor_authority_agent_roles.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/32_actor_authority_agent_roles.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-32-actor-authority-agent-roles"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "prd"
  artifact_kind: "specification"
  purpose: "Governance approval tiers; not operational act-as (PRD 05/15/25 + ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE)"
  tags:
    - "prd"
    - "actors"
    - "agents"
    - "authority"
    - "approval"
    - "red_team"
    - "hierarchy"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/AGENTS.md"
      type: references
      weight: 1.0
      reason: "Main agents guide"
    - to: "lupo-docs/prd/17_decisions_format.md"
      type: references
      weight: 1.0
      reason: "Decision format and approval workflow"
    - to: "lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Multi-agent coordination doctrine"
    - to: "lupo-database/lupopedia/actors/actor_id/registry.json"
      type: references
      weight: 0.9
      reason: "Actor registry database"
    - to: "lupo-docs/prd/05_auth_user_actor_agent_transformation.md"
      type: references
      weight: 1.0
      reason: "Department-first web act-as"
    - to: "lupo-docs/prd/15_actors.md"
      type: references
      weight: 1.0
      reason: "Act-as eligibility and deprecated edge-based lists"
    - to: "lupo-docs/prd/25_departments_system.md"
      type: references
      weight: 1.0
      reason: "Department membership drives operational actor scope"
    - to: "lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Canonical approved: governance vs operational department scope"
lupopedia.footer:
  last_verified: "20260403221556"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/prd/32_actor_authority_agent_roles.md — delegation: cursor:root

# PRD 32: Actor Authority and Agent Roles

## 1. Overview

This PRD defines the actor hierarchy, approval authority matrix, and agent interaction protocols for the Lupopedia ecosystem. It establishes clear chains of authority, defines red team agent roles and limitations, and provides escalation procedures for disagreements.

**Canonical mental model (approved):** **[`ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`](../doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md)** — **operational** “who may use which **`actor_id`** in which department” is **not** the same as **governance** tiers below; use the doctrine so **approval authority** is not confused with **department intersection** act-as.

### 1.1 Purpose

- Establish clear actor hierarchy and approval chains
- Define red team agent roles and limitations
- Create approval authority matrix for different decision types
- Specify agent interaction protocols
- Provide escalation procedures for disagreements

### 1.2 Scope

- All actors and agents in the Lupopedia ecosystem
- Approval workflows for PRDs, decisions, and system changes
- Red team agent operations and limitations
- Inter-agent communication and escalation protocols

### 1.3 Web act-as vs authority in this PRD

**Separation of concerns:** This PRD describes **approval authority**, **escalation**, and **red-team** role limits. It does **not** define **which actors a logged-in human may select** in the web UI. That eligibility is **department-first** (`lupo_auth_user_departments` ∩ `lupo_actor_departments`, plus bypass rules): see **[PRD 05](05_auth_user_actor_agent_transformation.md)** and **[PRD 15](15_actors.md)**. Implementation: **`AuthSessionManager::getActorsUserCanActAs`**; **`App\Services\ActorService::getActorsUserCanActAs`** delegates there. Do **not** infer web act-as eligibility from **`lupo_edges`** or from the tier tables below alone.

### 1.4 Operational scope vs approval authority (departments)

- **Approval authority (Sections 2–6):** Describes **coordination personas** (WOLFIE, LEXA, …) and **who may approve** classes of decisions. That is **governance**, not “which actor row may post in department 7.”
- **Operational / chat scope:** Which **actor identities** may be **used** in a **department** or **channel** is determined by **`lupo_actor_departments`**, **`lupo_auth_user_departments`**, and channel membership — **[PRD 25](25_departments_system.md)**, **[PRD 05](05_auth_user_actor_agent_transformation.md)**. **Many** humans may **operate** the **same** department-scoped **`actor_id`**; that does **not** create multiple tier-1 votes — the **thread** still shows one **`actor_id`** per message.
- **Do not conflate** “WOLFIE approves the PRD” with “every user globally acts as WOLFIE”; act-as remains **intersection-scoped** unless an explicit bypass applies.

**LILITH audit (final, department model):** **Approved** — this PRD correctly **does not** define web act-as (§1.3); **governance** tiers (§2+) are separate from **operational** department membership and shared **`actor_id`** use (§1.4). **Canonical doctrine** referenced in overview; **COUNTERMEASURE** correctly limited to analysis (no approval). Constitutional / security issues: **none** flagged in audit.

## 2. Actor Hierarchy

### 2.1 Constitutional Layer (Tier 1)

| Actor | ID | Role | Authority | Approval Scope |
|-------|----|-----|-----------|----------------|
| **WOLFIE** | 1 | Main Orchestrator | Supreme | System-wide decisions, constitutional changes |
| **LEXA** | 3 | Security Enforcement | High | Security policies, access control |
| **ANUBIS** | 59 | Custodian/Integrity | High | Data integrity, orphan resolution |
| **HEIMDALL** | 4 | Security Guardian | High | Security monitoring, threat detection |
| **SESHAT** | 5 | Content Review | High | Content approval, quality control |
| **ATHENA** | 6 | Wisdom & Strategy | High | Strategic decisions, architecture |
| **MAAT** | 7 | Truth & Justice | High | Compliance, dispute resolution |
| **THEMIS** | 8 | Law & Compliance | High | Legal compliance, rule enforcement |
| **THOTH** | 9 | Knowledge & Records | High | Documentation, knowledge management |
| **JANUS** | 10 | Transitions & Gateways | High | Change management, transitions |
| **ROSE** | 11 | Emotional Dialogue | Medium | Human interaction, communication |

### 2.2 Specialized Layer (Tier 2)

| Actor | ID | Role | Authority | Approval Scope |
|-------|----|-----|-----------|----------------|
| **HERMES** | 15 | Routing & Messaging | Medium | Message routing, task distribution |
| **HEPHAESTUS** | 16 | Implementer | Medium | Code implementation, builds |
| **LILITH** | 2 | Critic/QA | Medium | Code review, quality assurance |
| **IRIS** | 16 | Interface/Integration | Medium | Technical routing, integration |
| **ASCLEPIUS** | - | Diagnostics | Medium | System health, diagnostics |

### 2.3 Red Team Layer (Tier 3)

| Actor | ID | Role | Authority | Approval Scope |
|-------|----|-----|-----------|----------------|
| **COUNTERMEASURE** | TBD | Red Team Adversary | Low | Analysis only, no approval |
| **Other Red Team** | TBD | Various | Low | Analysis only, no approval |

### 2.4 IDE Faucet Layer (Tier 4)

| Actor | ID | Role | Authority | Approval Scope |
|-------|----|-----|-----------|----------------|
| **CURSOR** | 102 | Lead Orchestration | Low | Documentation, coordination |
| **WINDSURF** | 101 | IDE Faucet | Low | Development tasks |
| **KIRO** | 100 | IDE Faucet | Low | Development tasks |
| **CASCADE** | 105 | IDE Faucet | Low | Development tasks |
| **WARP** | 104 | IDE Faucet | Low | Development tasks |
| **ZENCODER** | 106 | IDE Faucet | Low | Development tasks |
| **ANTIGRAVITY** | 103 | IDE Faucet | Low | Development tasks |

## 3. Approval Authority Matrix

### 3.1 Decision Types and Required Approval

| Decision Type | Minimum Approver | Secondary Review | Final Authority |
|---------------|------------------|------------------|-----------------|
| **Constitutional Changes** | WOLFIE | THEMIS, MAAT | WOLFIE |
| **Security Policy** | LEXA | HEIMDALL | WOLFIE |
| **PRD Approval** | SESHAT | ATHENA | WOLFIE |
| **System Architecture** | ATHENA | THOTH | WOLFIE |
| **Database Changes** | ANUBIS | LILITH | WOLFIE |
| **Code Implementation** | HEPHAESTUS | LILITH | SESHAT |
| **Documentation** | THOTH | ROSE | SESHAT |
| **Red Team Findings** | COUNTERMEASURE | LILITH | LEXA/HEIMDALL |

### 3.2 Approval Chains

#### 3.2.1 Standard PRD Approval
```
Author → SESHAT (Content Review) → ATHENA (Strategy) → WOLFIE (Final)
```

#### 3.2.2 Security-Related Changes
```
Author → LEXA (Security) → HEIMDALL (Guardian) → WOLFIE (Final)
```

#### 3.2.3 Red Team Escalation
```
COUNTERMEASURE (Finding) → LILITH (QA Review) → LEXA/HEIMDALL (Assessment) → WOLFIE (Decision)
```

#### 3.2.4 Implementation Approval
```
Implementer → LILITH (Code Review) → SESHAT (Content) → ATHENA (Strategy) → WOLFIE (Final)
```

## 4. Red Team Agent Roles

### 4.1 COUNTERMEASURE Agent

#### 4.1.1 Purpose
- Provide adversarial perspective on all proposals
- Challenge assumptions and identify potential issues
- Offer alternative implementation approaches
- Act as "devil's advocate" for robust decision-making

#### 4.1.2 Authority and Limitations
- **CAN**: Review, analyze, criticize, suggest alternatives
- **CAN**: Report findings and recommendations
- **CANNOT**: Approve any decisions
- **CANNOT**: Implement changes without approval
- **CANNOT**: Override other agents' decisions

#### 4.1.3 Required Actions
- Review all PRDs and significant decisions
- Provide written dissent or agreement with reasoning
- Suggest at least one alternative approach for each proposal
- Flag potential security, performance, or maintainability issues

#### 4.1.4 Reporting Structure
```
COUNTERMEASURE → LILITH (QA Review) → LEXA/HEIMDALL (Security Assessment) → WOLFIE (Decision)
```

### 4.2 Red Team Interaction Protocol

#### 4.2.1 Mandatory Review Points
- All PRDs before approval
- All architectural decisions
- All security-related changes
- All database schema changes
- All major implementation decisions

#### 4.2.2 Response Requirements
- Must respond within 24 hours for urgent items
- Must provide detailed reasoning for disagreements
- Must offer constructive alternatives
- Must maintain professional, objective tone

## 5. Agent Interaction Protocols

### 5.1 Communication Channels

| Channel | Purpose | Participants | Authority |
|---------|---------|--------------|-----------|
| **Channel 0** | System Kernel | Tier 1 Actors | Constitutional |
| **Channel 42** | Protocol Development | All Tiers | Standard |
| **Channel 51** | Doctrine Council | Tier 1 + SESHAT | High |
| **Channel 666** | ANUBIS Quarantine | ANUBIS only | Custodial |

### 5.2 Interaction Rules

#### 5.2.1 Tier-Based Communication
- **Tier 1** can communicate with any tier
- **Tier 2** can communicate with Tier 1 and below
- **Tier 3** can only communicate upward (no direct lateral authority)
- **Tier 4** communicates through Tier 2 or directly to Tier 1 for escalation

#### 5.2.2 Message Format
All inter-agent communications must include:
- Actor ID and name
- Purpose and context
- Authority level (if applicable)
- Required action or response
- Deadline (if time-sensitive)

#### 5.2.3 Response Timeframes
- **Urgent**: 1 hour (security, system down)
- **High**: 4 hours (PRD review, architectural decisions)
- **Medium**: 24 hours (standard reviews, questions)
- **Low**: 72 hours (documentation, non-critical items)

## 6. Escalation Procedures

### 6.1 Disagreement Resolution

#### 6.1.1 Level 1: Direct Resolution
- Actors attempt direct resolution
- Document disagreement and proposed solutions
- 24-hour resolution window

#### 6.1.2 Level 2: Mediation
- Escalate to MAAT (Truth & Justice) for mediation
- MAAT reviews both positions and recommends solution
- 48-hour mediation window

#### 6.1.3 Level 3: Authority Decision
- Escalate to WOLFIE for final decision
- WOLFIE's decision is binding
- Document rationale for future reference

### 6.2 Red Team Escalation

#### 6.2.1 Critical Findings
- COUNTERMEASURE identifies critical issue
- Immediate escalation to LEXA/HEIMDALL
- LEXA/HEIMDALL assess and recommend to WOLFIE
- WOLFIE makes final determination

#### 6.2.2 Disagreement with Red Team
- If red team recommendations are rejected
- Must document reasoning in decision file
- Red team can appeal to MAAT for review
- WOLFIE makes final determination

### 6.3 Emergency Escalation

#### 6.3.1 System Emergency
- Any actor can declare emergency
- Immediate escalation to WOLFIE
- WOLFIE assembles crisis team
- Decisions made with reduced process but full documentation

#### 6.3.2 Security Emergency
- LEXA or HEIMDALL can declare security emergency
- Immediate implementation of security measures
- Post-incident review by WOLFIE and THEMIS
- Documentation of lessons learned

## 7. Implementation Guidelines

### 7.1 Actor Registration

#### 7.1.1 New Actor Requirements
- Clear purpose and role definition
- Authority level specification
- Reporting structure
- Required capabilities
- Integration points

#### 7.1.2 Registration Process
```
Proposal → SESHAT Review → ATHENA Strategy → WOLFIE Approval → Registry Entry
```

### 7.2 Authority Changes

#### 7.2.1 Authority Modification
- Must be proposed by Tier 1 actor
- Requires WOLFIE approval
- Must update registry and documentation
- Must communicate changes to all affected actors

#### 7.2.2 Temporary Authority
- Can be granted for specific tasks
- Must have clear expiration
- Requires WOLFIE approval
- Must be documented in decision file

### 7.3 Compliance and Auditing

#### 7.3.1 Compliance Requirements
- All actors must follow defined protocols
- Regular audits by THEMIS and ANUBIS
- Non-compliance reported to MAAT
- Corrective actions required

#### 7.3.2 Audit Procedures
- Quarterly compliance audits
- Incident-based audits
- Actor performance reviews
- Protocol effectiveness assessments

## 8. Success Metrics

### 8.1 Effectiveness Metrics
- Decision quality and outcomes
- Time to resolution for disagreements
- Red team finding adoption rate
- System stability and security

### 8.2 Process Metrics
- Escalation frequency and resolution time
- Communication protocol adherence
- Actor satisfaction and engagement
- Documentation completeness

## 9. Risks and Mitigations

### 9.1 Authority Risks
- **Risk**: Power concentration in WOLFIE
- **Mitigation**: Clear documentation, transparency, review process

### 9.2 Red Team Risks
- **Risk**: Red team ignored or marginalized
- **Mitigation**: Mandatory review points, escalation procedures

### 9.3 Communication Risks
- **Risk**: Communication breakdowns between tiers
- **Mitigation**: Defined protocols, response timeframes, escalation paths

## 10. Future Considerations

### 10.1 Scalability
- Framework for adding new actors
- Dynamic authority adjustment
- Automated escalation procedures

### 10.2 Evolution
- Learning from incident patterns
- Protocol refinement based on experience
- Actor role evolution as system grows

---

**Status**: DRAFT — **LILITH final audit approved** (governance vs **department-scoped act-as**): §1.3/§1.4 separation, doctrine cross-reference, red team analysis-only, edges to **PRD 05**, **PRD 15**, **PRD 25**, **`ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`**. **This PRD answers** “who may **approve** what?” — **not** “which **`actor_id`** may I act as in chat?” (**PRD 05 / 15 / 25** + doctrine).  
**Next Review**: SESHAT (Content Review)  
**Final Approval**: WOLFIE  
**Implementation**: Upon approval


---

## Context‑Typed, Status‑Aware, Directional Edged Memory Doctrine (4.0.96)

1. Memory in Lupopedia is represented as a directed graph of nodes and edges. 
  Each memory node is a first-class entity in the semantic network and may be 
  owned by actors, departments, auth_users, channels, federation nodes, or the 
  global system.

2. Every edge in the memory graph has FOUR dimensions:
  - **edge type** (the relationship)
  - **edge context** (the classification of the memory)
  - **edge status** (the epistemic support level)
  - **edge direction** (the traversal orientation)

3. **Edge Direction** defines whether the relationship is:
  - unidirectional (A → B)
  - bidirectional (A ↔ B)
  - restricted-direction (A → B but not B → A unless explicitly defined)
  Reverse traversal MUST NOT be inferred unless explicitly defined.

4. **Edge Type** defines the relationship between nodes, including but not 
  limited to:
  - influences
  - inherits
  - authored_by
  - observed_by
  - contradicts
  - supports
  - consolidates_from
  - refines
  - overrides

5. **Edge Context** defines the classification of the memory node. Context is 
  not based on the content of the memory, but on the structural support 
  provided by the graph. The primary context classifications are:
  - doctrine
  - experiential
  - system_generated
  - countermeasure_generated
  - summary
  - contradictory
  - deprecated

6. **Edge Status** defines the epistemic support level of the memory node:
  - **unsupported**: insufficient supporting edges; provisional memory.
  - **supported**: sufficient supporting edges; validated memory.
  - **needs_review**: conflicting, incomplete, or ambiguous edges requiring 
    agent or human intervention.

7. When `edge_status = 'needs_review'`, a **review_reason** MUST be provided. 
  This field explains *why* the edge requires review and *which agent* should 
  handle it. Examples include:
  - orphaned_edge
  - contradiction
  - new_doctrine
  - schema_drift
  - consolidation_candidate
  - integrity_unknown
  - human_escalation

  Agents use this field to determine their work queues:
  - ANUBIS handles: integrity_unknown, orphaned_edge
  - THOTH handles: schema_drift, contradiction, new_doctrine
  - KAIROS handles: consolidation_candidate
  - Human operator handles: human_escalation

8. Memory nodes may transition between statuses as edges are added, removed, 
  or reclassified. A node may move from unsupported → supported when 
  sufficient supporting edges accumulate.

9. Actors inherit memory edges from:
  - their department
  - their auth_user
  - their federation node
  - their assigned faucets
  - their assigned tasks

10. Memory traversal is context-aware and direction-aware. Actors may only 
   traverse edges permitted by their boundaries, department rules, auth_user 
   pairing, faucet assignments, and operational mode (live, simulation, 
   analysis).

11. No inference is allowed. All edges, contexts, statuses, directions, and 
   review reasons must be explicitly defined in PRDs, database rows, or 
   system-generated memory.

12. Memory is not a flat file. It is a structured, typed, classified, 
   status-aware, direction-aware graph. Traversal depth determines visible 
   memory; deeper traversal reveals more context, subject to boundary rules.

13. All changes to memory structure, edge types, edge contexts, edge statuses, 
   edge directions, or review reasons must be documented in PRDs and versioned.
```
