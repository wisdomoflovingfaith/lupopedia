#
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
#
## Context‑Typed, Status‑Aware, Directional Edged Memory Doctrine (4.0.95+)

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
   - **unidirectional** (A → B)
   - **bidirectional** (A ↔ B)
   - **restricted-direction** (A → B but not B → A unless explicitly allowed)

   Direction determines which nodes can be reached during traversal.  
   If an edge is not marked as bidirectional, the reverse path MUST NOT be 
   assumed or inferred.

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

   - **doctrine**  
     System-defined, PRD-governed memory that is immutable except through 
     versioned doctrine updates.

   - **experiential**  
     Memory derived from actor or auth_user experience.

   - **system_generated**  
     Memory created by installer, seed, migration, or system processes.

   - **countermeasure_generated**  
     Memory created by the Countermeasure actor for simulation, analysis, or 
     adversarial reasoning.

   - **summary**  
     Memory created by consolidation processes (e.g., Kairos).

   - **contradictory**  
     Memory nodes whose edges conflict with other nodes.

   - **deprecated**  
     Memory nodes marked for removal or archival.

6. **Edge Status** defines the epistemic support level of the memory node. 
   Status is determined by the structure of the edges, not the content of the 
   memory. The primary statuses are:

   - **unsupported**  
     Insufficient supporting edges; provisional memory.

   - **supported**  
     Sufficient supporting edges; validated memory.

   - **needs_review**  
     Conflicting or ambiguous edges requiring human or system review.

7. Memory nodes may transition between statuses as edges are added, removed, 
   or reclassified. A node may move from unsupported → supported when 
   sufficient supporting edges accumulate.

8. Actors inherit memory edges from:
   - their department
   - their auth_user
   - their federation node
   - their assigned faucets
   - their assigned tasks

9. Memory traversal is context-aware and direction-aware. Actors may only 
   traverse edges permitted by their boundaries, department rules, auth_user 
   pairing, faucet assignments, and operational mode (live, simulation, 
   analysis). Examples:
   - Unsupported edges may be traversed only when allowed by actor boundaries.
   - Doctrine edges have highest priority.
   - Auth_user edges depend on pairing rules.
   - Department edges depend on membership.
   - Countermeasure edges are traversable only in simulation or analysis modes.
   - Directional edges MUST be respected; reverse traversal is forbidden unless 
     explicitly defined.

10. No inference is allowed. All edges, contexts, statuses, and directions must 
    be explicitly defined in PRDs, database rows, or system-generated memory. 
    The system must not assume or fabricate edges.

11. Memory is not a flat file. It is a structured, typed, classified, 
    status-aware, and direction-aware graph. Traversal depth determines visible 
    memory; deeper traversal reveals more context, subject to boundary rules.

12. All changes to memory structure, edge types, edge contexts, edge statuses, 
    or edge directions must be documented in PRDs and versioned. No undocumented 
    memory behavior is permitted in Lupopedia.
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  file_path_from_root: "lupo-docs/prd/03_goals_and_success_criteria.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/03_goals_and_success_criteria.md"
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "goals-success-criteria"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "prd"
  artifact_kind: "success_criteria"
  purpose: "PRD for 4.0.93 Goals and Success Criteria v4.0.93"
  tags:
  - "prd"
  - "goals"
  - "success_criteria"
  - "v4.0.93"
  - "doctrine"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/versions/4.0.93/PLAN.md"
      type: references
      weight: 1.0
      reason: Overall plan for 4.0.93
    - to: "lupo-docs/versions/4.0.93/TODO.md"
      type: references
      weight: 1.0
      reason: Task tracking for 4.0.93
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"

# 03. Goals and Success Criteria (Carryover from 4.0.88)

See 4.0.88 for original requirements. Update and extend for 4.0.90 as needed.
