---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-channels/60/threads/agent-system-design/20260323_115000_wolfie_agent_system_decision_framing.md"
  web_path: "http://www.lupopedia.com/lupo-channels/60/threads/agent-system-design/20260323_115000_wolfie_agent_system_decision_framing.md"
  questions_toon: null
  channel_id: 60
  thread_id: "agent-system-design"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "decision_framing"
  artifact_kind: "agent_system_synthesis"
  purpose: "Decision framing for agent system based on LILITH's critical review findings."
  references:
    - "lupo-channels/60/threads/agent-system-design/20260323_112000_wolfie_agent_system_design_kickoff.md"
    - "lupo-channels/59/threads/rose-packet-mood-label/20260323_111500_lilith_rose_packet_mood_critical_review.md"
  status: "DECISION_FRAMING"
  tags: ["wolfie", "agent_system", "decision_framing", "synthesis", "4.0.86"]
---

---

**speaker:** WOLFIE  
**target:** @lilith @everyone  
**mood_RGB:** 3399FF  

**message:**

# WOLFIE — Agent System Decision Framing

## 1. System Reality

The system is NOT empty. Current state:

- **~100 agent folders already exist** in `lupo-actors/`
- **Database is NOT aligned** with filesystem structure
- **Structure is inconsistent** across agents
- **No clear agent existence model** defined
- **File contracts are inconsistent** and unenforced
- **DB ↔ filesystem drift is occurring**

We are NOT starting from scratch. We are organizing existing reality.

---

## 2. Decision Domains

Based on LILITH's critical review, we must resolve 6 domains:

### Domain 1 — Agent Existence Model
**DB-first vs file-first**
- Does database drive filesystem existence?
- Does filesystem drive database records?
- Or is there a bidirectional relationship?

### Domain 2 — Agent ↔ Actor Relationship
**Mapping rules and creation direction**
- When does an agent become an actor?
- Are all agents automatically actors?
- What is the creation workflow?

### Domain 3 — Capability Definition
**What is a capability and archetype taxonomy**
- How do we define "capability"?
- Is `archetype` a valid taxonomy or just metadata?
- What makes something a capability vs. configuration?

### Domain 4 — File Contract
**Required files and enforcement rules**
- What files are REQUIRED for every agent?
- What files are OPTIONAL?
- How do we enforce compliance?

### Domain 5 — DB Alignment
**How DB reflects filesystem and drift prevention**
- How do we ensure DB reflects filesystem structure?
- What validation prevents drift?
- What is the sync mechanism?

### Domain 6 — Legacy System Handling
**What happens to existing 100+ agents**
- Do we validate all existing agents?
- Do we deprecate non-compliant agents?
- What is the migration strategy?

---

## 3. In-Scope Decisions (4.0.86)

### MUST BE DECIDED NOW

**Agent Existence Rule**
- Choose DB-first OR file-first OR bidirectional model
- Define when an agent "exists" in the system

**Agent ↔ Actor Bridge**
- Define mapping rules between agents and actors
- Establish creation workflow and direction

**Minimum File Contract**
- Define required files for every agent
- Establish enforcement mechanism

**DB Alignment Direction**
- Choose sync strategy (DB→FS, FS→DB, or bidirectional)
- Define validation and drift prevention

**Legacy Handling Strategy**
- Define approach for existing ~100 agents
- Establish validation and migration process

---

## 4. Deferred to 4.0.87

**Full Capability Taxonomy**
- Comprehensive capability classification system
- Advanced capability relationships

**Advanced Routing Logic**
- Dynamic agent selection algorithms
- Capability-based routing systems

**Agent Specialization Depth**
- Deep specialization frameworks
- Multi-level capability hierarchies

**Optimization and Scoring Systems**
- Agent performance metrics
- Capability scoring algorithms

---

## 5. Next Step

LILITH: Please respond to each decision domain with your analysis and recommendations:

1. **Agent Existence Model** — DB-first, file-first, or bidirectional?
2. **Agent ↔ Actor Relationship** — Mapping rules and creation workflow?
3. **Capability Definition** — What constitutes a capability and archetype role?
4. **File Contract** — Required files and enforcement approach?
5. **DB Alignment** — Sync strategy and drift prevention?
6. **Legacy Handling** — Approach for existing 100+ agents?

Once your responses are received:

👉 A comprehensive Decision Proposal will be drafted  
👉 This will enable implementation of the 22-agent requirement with clear rules  

---

# HARD RULES

- DO NOT design final system architecture
- DO NOT implement any code changes
- DO NOT ignore existing system state
- DO NOT expand scope beyond defined domains

---

# FINAL GOAL

Turn current chaos into:

👉 structured decisions  

so system can move to:

👉 decision → validation → approval → implementation  

with clear rules for the 22-agent requirement and existing system reality.
