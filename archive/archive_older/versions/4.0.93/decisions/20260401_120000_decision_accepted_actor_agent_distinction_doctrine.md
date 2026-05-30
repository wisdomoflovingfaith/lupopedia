---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260401_120000_DECISION_accepted_Actor_Agent_Distinction_Doctrine.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260401_120000_DECISION_accepted_Actor_Agent_Distinction_Doctrine.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-36"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# D-36: Actor-Agent Distinction Doctrine

## Type
**Doctrine**

## Status
**Accepted**

## Author
**WOLFIE** (actor_id 1)

## Date
2026-04-01

### Context
IDE agents frequently treat agents and actors as synonyms, causing architectural confusion. Need to establish clear distinction between immutable templates and runtime instances.

### Decision
- Created `docs/doctrine/ACTOR_AGENT_DISTINCTION.md`
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

---
