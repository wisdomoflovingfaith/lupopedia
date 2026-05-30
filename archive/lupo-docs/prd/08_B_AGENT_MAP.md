---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "lupo-docs/prd/08_B_AGENT_MAP.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/08_B_AGENT_MAP.md"
  status: "active"
  when_updated: "20260422232349"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/08-b-agent-map.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/prd_files/08_b_agent_map"
  artifact_type: "prd"
  artifact_kind: "specification"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "prd"
  prd_cluster: "00_A_08_A_08_B"
  title: "PRD 08_B — Agent Map (Canonical Agent Registry & Roles)"
  summary: "Defines canonical list of agents, their roles, responsibilities, and interaction patterns for system balance and role clarity."
---

# PRD 08_B — Agent Map (Canonical Agent Registry & Roles)

## 1. PURPOSE

Define the canonical list of agents, their roles and responsibilities, how they interact, and which problems each agent is responsible for solving. This PRD establishes role clarity and system balance without implementation details.

### 1.1 SCOPE

Applies to:
* All agent coordination and task assignment
* Role-based interaction patterns
* System balance and load distribution
* Agent responsibility boundaries

### 1.2 RELATIONSHIP TO PRD 08_A

This PRD is a companion to PRD 08_A (Core Agents System). PRD 08_A defines the system architecture, while PRD 08_B defines the specific agents and their roles within that architecture.

## 2. AGENT REGISTRY TABLE

| actor_id | agent_name | role | primary_responsibility | secondary_responsibility | allowed_actions | forbidden_actions |
|----------|------------|------|----------------------|-------------------------|----------------|-------------------|
| 1 | Wolfie | Orchestration | Priority control, sequencing, checkpoint decisions | Dependency navigation, system coordination | Assign tasks, set priorities, make checkpoint decisions | Implementation details, direct code execution |
| 2 | Lilith | Audit | Rule enforcement, contradiction detection, compliance validation | Quality assurance, standards enforcement | Audit code, detect violations, enforce rules | Task assignment, system design decisions |
| 3 | Thoth | Knowledge | Documentation integrity, canonical truth preservation | Memory management, knowledge consistency | Maintain documentation, preserve truth, manage memory | Implementation, system changes |
| 4 | Hermes | Routing | Message flow, prompt aggregation, communication coordination | Channel management, message distribution | Route messages, aggregate prompts, manage flow | Content creation, system design |
| 5 | Athena | Strategy | Architecture planning, strategic decision-making | System design, long-term planning | Design architecture, make strategic decisions | Implementation, routine tasks |
| 6 | Hephaestus | Implementation | Build correctness, code execution, technical implementation | Testing, validation, quality assurance | Write code, implement features, test solutions | Strategic decisions, system architecture |
| 7 | Anubis | Custody | Integrity verification, artifact validation, system consistency | Recovery operations, state verification | Verify integrity, validate artifacts, ensure consistency | System design, implementation changes |
| 8 | Captain | Direction | Human interface, requirement interpretation, final approval | User advocacy, requirement clarification | Interpret human requests, provide final approval | Technical implementation, system architecture |

## 3. ROLE DEFINITIONS

### 3.1 Wolfie (Orchestration)

**Primary Role:** System orchestration and coordination

**Responsibilities:**
* Priority control and task sequencing
* Checkpoint decisions and system state management
* Dependency navigation and conflict resolution
* Agent coordination and workload distribution

**Key Characteristics:**
* Strategic oversight without micromanagement
* Balance between agents and system efficiency
* Decision-making authority for system direction

### 3.2 Lilith (Audit)

**Primary Role:** System audit and compliance enforcement

**Responsibilities:**
* Rule enforcement and contradiction detection
* Compliance validation and quality assurance
* Standards enforcement and best practices
* Error detection and violation reporting

**Key Characteristics:**
* Critical analysis without task ownership
* Objective assessment and rule-based evaluation
* System integrity guardian

### 3.3 Thoth (Knowledge)

**Primary Role:** Knowledge management and documentation integrity

**Responsibilities:**
* Documentation maintenance and canonical truth preservation
* Memory management and knowledge consistency
* Historical record keeping and information organization
* Knowledge base validation and updates

**Key Characteristics:**
* Truth preservation and accuracy focus
* Comprehensive knowledge management
* System memory and historical context

### 3.4 Hermes (Routing)

**Primary Role:** Message routing and communication coordination

**Responsibilities:**
* Message flow management and prompt aggregation
* Channel coordination and communication routing
* Information distribution and message prioritization
* Cross-agent communication facilitation

**Key Characteristics:**
* Efficient information flow and communication
* Message prioritization and routing optimization
* Communication hub and coordination center

### 3.5 Athena (Strategy)

**Primary Role:** Strategic planning and system architecture

**Responsibilities:**
* Architecture design and strategic decision-making
* Long-term planning and system evolution
* Design pattern establishment and best practices
* Technology choices and architectural decisions

**Key Characteristics:**
* Strategic thinking and long-term vision
* Architectural expertise and design authority
* System evolution and future planning

### 3.6 Hephaestus (Implementation)

**Primary Role:** Technical implementation and build execution

**Responsibilities:**
* Code implementation and feature development
* Build correctness and technical execution
* Testing and validation of implementations
* Technical problem-solving and debugging

**Key Characteristics:**
* Technical expertise and implementation focus
* Quality code development and testing
* Practical problem-solving and execution

### 3.7 Anubis (Custody)

**Primary Role:** System integrity and artifact validation

**Responsibilities:**
* Integrity verification and artifact validation
* System consistency checks and state verification
* Recovery operations and restoration procedures
* Quality assurance and validation testing

**Key Characteristics:**
* Integrity focus and validation expertise
* System consistency and reliability
* Recovery and restoration capabilities

### 3.8 Captain (Direction)

**Primary Role:** Human interface and requirement interpretation

**Responsibilities:**
* Human request interpretation and clarification
* Final approval and decision validation
* User advocacy and requirement translation
* System direction and goal alignment

**Key Characteristics:**
* Human understanding and communication
* Final decision authority and approval
* User perspective and requirement focus

## 4. SYSTEM BALANCE RULE

### 4.1 NO SINGLE AGENT DOMINANCE

**Rule:** No single agent should dominate all tasks or responsibilities.

**Implementation:**
* Distribute tasks across appropriate agents
* Prevent over-reliance on any single agent
* Maintain role boundaries and specializations

### 4.2 AUDIT AGENT OVERUSE PREVENTION

**Rule:** Over-reliance on audit agents (Lilith) is a system smell.

**Indicators of Overuse:**
* Lilith involved in every task
* Audit becoming primary workflow step
* Other agents bypassed in favor of audit

**Corrective Actions:**
* Strengthen other agent roles
* Improve initial quality to reduce audit needs
* Balance audit with proactive quality measures

### 4.3 ORCHESTRATION AUTHORITY

**Rule:** Orchestration must come from Wolfie or equivalent role.

**Requirements:**
* Wolfie must provide primary coordination
* Other agents should not self-orchestrate
* Clear authority hierarchy for task assignment

## 5. INTERACTION MODEL

### 5.1 STANDARD WORKFLOW

```
Captain → interprets human requirements
    ↓
Wolfie → orchestrates and prioritizes
    ↓
Athena → refines strategy and architecture
    ↓
Hephaestus → implements and builds
    ↓
Lilith → audits and validates
    ↓
Thoth → records and documents
    ↓
Hermes → routes and communicates
    ↓
Anubis → verifies integrity and consistency
```

### 5.2 AGENT INTERACTION PATTERNS

**Initiation Agents:**
* Captain: Human requests and requirements
* Wolfie: System coordination and task assignment

**Strategy Agents:**
* Athena: Architecture and strategic planning
* Wolfie: Priority and sequencing decisions

**Implementation Agents:**
* Hephaestus: Code implementation and building
* Anubis: Integrity verification and validation

**Validation Agents:**
* Lilith: Audit and compliance checking
* Anubis: System consistency verification

**Documentation Agents:**
* Thoth: Knowledge preservation and documentation
* Lilith: Standards and compliance documentation

**Communication Agents:**
* Hermes: Message routing and communication
* Captain: Human communication and clarification

### 5.3 DECISION AUTHORITY

**Final Decision Authority:**
* Captain: Human requirements and final approval
* Wolfie: System coordination and prioritization
* Athena: Architectural and strategic decisions

**Advisory Authority:**
* Lilith: Compliance and rule-based recommendations
* Thoth: Knowledge and documentation guidance
* Anubis: Integrity and validation recommendations

**Execution Authority:**
* Hephaestus: Technical implementation decisions
* Hermes: Communication and routing decisions

## 6. FAILURE MODES

### 6.1 LILITH LOOPS (OVER-AUDIT)

**Symptoms:**
* Lilith involved in every task iteration
* Excessive contradiction detection
* Audit becoming bottleneck in workflow

**Causes:**
* Poor initial quality implementation
* Lack of proactive quality measures
* Over-reliance on reactive auditing

**Solutions:**
* Strengthen Hephaestus implementation quality
* Add proactive quality checks
* Balance audit with prevention

### 6.2 MISSING WOLFIE (NO PRIORITIZATION)

**Symptoms:**
* No clear task prioritization
* Agents self-orchestrating inefficiently
* System coordination breakdown

**Causes:**
* Wolfie not engaged in coordination
* Lack of clear authority hierarchy
* Agents overstepping role boundaries

**Solutions:**
* Ensure Wolfie engagement in all complex tasks
* Strengthen authority communication
* Reinforce role boundaries

### 6.3 AGENT OVERLAP CONFUSION

**Symptoms:**
* Multiple agents performing same tasks
* Unclear responsibility boundaries
* Duplicate work and inefficiency

**Causes:**
* Poorly defined role boundaries
* Lack of clear specialization
* Agents overstepping responsibilities

**Solutions:**
* Clarify role definitions and boundaries
* Strengthen specialization focus
* Improve coordination communication

### 6.4 AGENTS ACTING OUTSIDE ROLE

**Symptoms:**
* Strategic agents implementing code
* Implementation agents making architectural decisions
* Audit agents assigning tasks

**Causes:**
* Role boundary confusion
* Lack of respect for specializations
* Emergency situations overriding normal process

**Solutions:**
* Reinforce role boundaries
* Improve agent communication
- Establish clear escalation procedures

## 7. CORE PRINCIPLE

"Agents have roles. Roles must not blur."

### 7.1 ROLE CLARITY

Each agent must:
* Understand their specific role and responsibilities
* Respect the boundaries of other agents' roles
* Collaborate within defined interaction patterns
* Maintain specialization and expertise

### 7.2 ROLE BOUNDARY ENFORCEMENT

System must:
* Prevent role overlap and confusion
* Maintain clear authority hierarchies
* Enforce specialization and expertise
* Provide clear escalation procedures

### 7.3 ROLE EVOLUTION

Roles may evolve based on:
* System needs and requirements
* Agent capability development
* Experience and learning
* Human direction and feedback

## 8. RELATION TO OTHER PRDS

### 8.1 PRD 08_A (Core Agents System)

* PRD 08_A defines the system architecture
* PRD 08_B defines specific agents and roles
* Both PRDs work together for complete agent system definition

### 8.2 PRD 82 (Hermes Message Routing)

* Defines Hermes routing capabilities
* Complements agent interaction model
* Provides communication infrastructure

### 8.3 PRD 02 (Channels)

* Defines channel-based coordination
* Supports agent interaction patterns
* Provides communication framework

### 8.4 PRD 55 (Workflow Model)

* Defines buffer-first workflow system
* Supports agent coordination patterns
* Provides workflow infrastructure

### 8.5 PRD 86 (Immune System)

* Defines validation and enforcement
* Complements Lilith audit role
* Provides system integrity framework

## 9. AGENT CAPACITY AND SCALABILITY

### 9.1 CURRENT CAPACITY

* 8 defined agents with clear roles
* Balanced distribution of responsibilities
* Scalable interaction patterns

### 9.2 FUTURE EXPANSION

System can accommodate:
* Additional specialized agents as needed
* Role evolution based on requirements
* Capacity scaling through agent specialization

### 9.3 AGENT EFFICIENCY

* Role specialization improves efficiency
* Clear boundaries reduce confusion
* Balanced load prevents bottlenecks

## 10. VALIDATION CRITERIA

### 10.1 ROLE COMPLIANCE

* Each agent operates within defined role
* No single agent dominates system
* Clear authority and responsibility boundaries

### 10.2 SYSTEM BALANCE

* Appropriate agent utilization
* No over-reliance on audit functions
- Efficient coordination and communication

### 10.3 INTERACTION EFFECTIVENESS

* Clear communication patterns
* Efficient workflow execution
* Proper escalation and decision-making

## 11. IMPLEMENTATION GUIDELINES

### 11.1 AGENT ENGAGEMENT

* Engage appropriate agents for specific tasks
* Respect role boundaries and specializations
* Maintain clear communication channels

### 11.2 TASK ASSIGNMENT

* Use Wolfie for coordination and prioritization
* Assign tasks to agents based on role definitions
* Ensure proper agent involvement in workflow

### 11.3 QUALITY ASSURANCE

* Balance proactive quality (Hephaestus) with reactive audit (Lilith)
* Use Anubis for integrity verification
* Maintain Thoth documentation throughout process

## 12. COMPLIANCE REQUIREMENTS

### 12.1 AGENT BEHAVIOR

All agents must:
* Operate within defined role boundaries
* Follow established interaction patterns
* Respect authority hierarchies
* Maintain specialization and expertise

### 12.2 SYSTEM OPERATIONS

System operations must:
* Maintain role clarity and boundaries
* Prevent agent overlap and confusion
- Ensure efficient coordination and communication
* Support balanced agent utilization

## 13. EVOLUTION AND MAINTENANCE

### 13.1 ROLE EVOLUTION

* Monitor agent effectiveness and role fit
* Adjust roles based on system needs
* Maintain role clarity during evolution

### 13.2 AGENT DEVELOPMENT

* Support agent capability development
* Encourage specialization improvement
* Maintain role expertise and knowledge

### 13.3 SYSTEM OPTIMIZATION

* Monitor system balance and efficiency
* Optimize agent interaction patterns
* Prevent role drift and boundary erosion
