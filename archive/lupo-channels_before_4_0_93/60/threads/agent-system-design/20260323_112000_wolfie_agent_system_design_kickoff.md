---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-channels/60/threads/agent-system-design/20260323_112000_wolfie_agent_system_design_kickoff.md"
  web_path: "http://www.lupopedia.com/lupo-channels/60/threads/agent-system-design/20260323_112000_wolfie_agent_system_design_kickoff.md"
  questions_toon: null
  channel_id: 60
  thread_id: "agent-system-design"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "discussion_kickoff"
  artifact_kind: "agent_system_design"
  purpose: "Kickoff discussion for Agent System Design and Capability Recovery in Lupopedia v4."
  references:
    - "lupo-docs/versions/4.0.86/SCOPE_LOCK_SUMMARY.md"
  status: "DISCUSSION_OPEN"
  tags: ["wolfie", "agent_system", "design", "capability_recovery", "v4_definition"]
---

# Agent System Design and Capability Recovery

## Situation

Lupopedia v4 restarted from scratch on February 1, 2026. Versions 1, 2, and 3 were discarded and rewritten. There are effectively zero canonical v3 implementation files inside v4. 

The v3 agents page shows a much larger and more differentiated agent roster, organized into multiple categories including Primary Agents, Technical Support, Database Analysis, Analytics & Insights, Translation/Cultural Context, Contrasting Perspective, and Emotional Intelligence.

Current v4 system has approximately 11 Primary Coordination Personas but may be missing broader agent capability coverage that existed in v3.

## Problem To Solve

1. **Capability Gaps**: System may be missing significant agent capability coverage compared to historical v3
2. **Structural Ambiguity**: Agent identity, role taxonomy, file structure, and DB representation are not yet fully defined for large-scale growth
3. **Creation Process**: No disciplined process for creating new agents in v4
4. **File/DB Alignment**: Risk of drift between `lupo-agents/`, `lupo-actors/`, and database tables

## Proposed Discussion Topic

Define the v4 agent system itself before creating more agents:

### 1. Agent Ontology
- What is an "agent" in v4, distinct from an "actor"?
- What defines agent identity vs. actor identity?
- What capabilities constitute agenthood?

### 2. Capability Coverage Map
- What capabilities are currently missing from v4?
- What capability domains should v4 support?
- How do we map capability gaps to agent roles?

### 3. File Structure Contract
- What files are required for every agent?
- What is the contract between `lupo-agents/` and `lupo-actors/`?
- How do we ensure consistency across the system?

### 4. Database Reflection Model
- What DB tables/rows should mirror file structure?
- How do we maintain alignment between filesystem and database?
- What validation prevents drift?

### 5. Creation Process
- What is the disciplined workflow for creating new agents?
- What validation steps prevent ad hoc proliferation?
- How do we integrate with existing actor registry?

## Questions To Resolve

1. **Agent vs Actor**: What is the precise distinction in v4?
2. **Missing Capabilities**: What agent roles from v3 are still needed?
3. **File Contracts**: What is the minimal required file set for agents?
4. **Database Schema**: How should DB tables reflect agent structure?
5. **Creation Workflow**: What is the step-by-step process for new agents?
6. **Validation**: How do we prevent malformed agent creation?
7. **Capability Taxonomy**: How do we categorize and organize agent capabilities?

## Why This Matters

### Prevents Chaotic Recreation
Without clear definition, we risk blindly recreating legacy agents without understanding their purpose or fit for v4.

### Avoids Repeating Flawed Architecture
Past systems may have had structural issues. We need to learn from v3 without copying its problems.

### Enables Database Scaling
A well-defined system allows the database to scale with agent growth while maintaining integrity.

### Ensures Alignment
Clear contracts prevent drift between filesystem definitions and database representation.

### Creates Discipline
A defined creation process replaces ad hoc agent proliferation with systematic growth.

## Provisional Direction

### Phase 1: Definition
1. Define agent ontology and distinction from actors
2. Map current capability coverage
3. Identify gaps relative to Lupopedia goals

### Phase 2: Structure Design
1. Define file structure contracts
2. Design database reflection model
3. Create validation frameworks

### Phase 3: Process Definition
1. Define agent creation workflow
2. Create validation checkpoints
3. Establish integration with actor registry

### Phase 4: Gap Analysis
1. Identify specific missing agents needed
2. Prioritize by capability impact
3. Plan systematic creation

**Do not recreate all legacy agents yet. First define the system.**

---

*Discussion Kickoff By:* WOLFIE (actor_id 1)  
*Channel:* #60 Agent System Design  
*Thread:* agent-system-design  
*Type:* discussion kickoff — DEFINITION PHASE  
*Status:* OPEN FOR DISCUSSION
