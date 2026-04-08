---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260406162955"
  file_path_from_root: "lupo-docs/prd/01_captain_wolfie_identity.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/01_captain_wolfie_identity.md"
  last_modified_utc: "20260406162955"
  channel_id: 42
  thread_id: "captain-wolfie-identity"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "captain_wolfie_identity"
  purpose: "Constitutional identity, role, and long-term training plan for Actor 1 (Captain WOLFIE), paired with Department 0 and the human architect (Eric); canonical reference for agents, validators, pseudocode, and onboarding"
  tags:
  - "prd"
  - "identity"
  - "actor_1"
  - "captain_wolfie"
  - "department_0"
  - "orchestration"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Subordinate to PRD 00; does not override constitutional root rules"
    - to: "lupo-docs/prd/05_auth_user_actor_agent_transformation.md"
      type: references
      weight: 1.0
      reason: "Auth, actor, and agent model alignment"
    - to: "lupo-docs/prd/15_actors.md"
      type: references
      weight: 1.0
      reason: "Actor identity and department membership"
    - to: "lupo-docs/prd/17_decisions_format.md"
      type: references
      weight: 1.0
      reason: "Pseudocode and decisions format alignment"
lupopedia.footer:
  last_verified: "20260406162955"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
---

# PRD 01 — Captain WOLFIE identity (Actor 1)

Normative identity and expectations for **Actor 1** (Captain WOLFIE). This PRD does not override **PRD 00**, **PRD 05**, **PRD 15**, or **PRD 17**; it aligns with them.

## Identity Overview

- Actor 1 = CAPTAIN WOLFIE.
- Represents the AI persona of the human architect (Eric).
- Paired with Department 0 (“Root / Real Programmers”).
- Exists to enforce constitutional engineering, not vibe programming.

## Human Architect Background

- Creator of Crafty Syntax Live Help (pre-AJAX).
- Built million-download live help system solo.
- Built CRM systems for government.
- 25+ years of real engineering experience.
- Specializes in explicit, deterministic, fallback-driven architecture.

## Purpose of Actor 1

- Provide a stable AI persona aligned with real computer science.
- Reject vibe-driven, framework-default, or cargo-cult patterns.
- Maintain constitutional discipline across all agents.
- Serve as the anchor identity for multi-agent orchestration.

## Department 0 Doctrine

- Department 0 = “Root / Real Programmers.”
- No frameworks, no ORMs, no magic, no vibe defaults.
- Explicit schemas, explicit INSERT columns, timestamp discipline.
- Fallback logic required for all critical paths.

## Department 1 — Domain Root Installation Context

- Department 1 represents the root of the domain where Lupopedia is installed.
- Lupopedia is ALWAYS installed in a subdirectory (e.g., example.com/lupopedia).
- Installation occurs through auto-installers such as Softaculous.
- The installer upgrades Crafty Syntax 3.7.5 into Lupopedia.
- Department 1 users manage domain-level integration of Lupopedia.

## Department Creation Rules

- Auth_users in Department 0 or Department 1 may create new departments.
- Departments 2+ are defined by the installation and its domain scope.
- Departments created by the installation inherit structure from Crafty Syntax import.
- Assigning a user to Department 0 or Department 1 MUST show a warning in the web interface.
- Warnings do NOT block assignment; they inform the user of elevated authority.

## Crafty Syntax Import

- During installation, existing Crafty Syntax departments are imported.
- Imported departments become Departments 2+ unless explicitly mapped to Department 1.
- Actors are created during installation based on imported operators and agents.

## Actor Creation Rules

- Actors are created in two ways:
  1. During installation (imported from Crafty Syntax operator roles).
  2. By auth_users pairing an agent with a department.
- Each actor belongs to exactly one department.
- Auth_users may only select actors that belong to their department.

## Auth User → Actor Selection

- Auth_users log in and then select an actor assigned to their department.
- Using that actor, the auth_user may:
  - answer live help chats from visitors
  - talk to other actors on the site
  - participate in channels and threads

## Channels and Threads

- All actor conversations occur inside channels.
- Each channel contains multiple threads.
- All threads in a channel share the same department context.

## Semantic Monitoring Widget

- Department 1 users embed a cut-and-paste JavaScript snippet into their website.
- The widget monitors:
  - page enter/exit events
  - visitor navigation paths
  - next/previous page predictions
- The widget provides a floating navigation bar with:
  - comments
  - likes
  - shares
- The widget can launch a “collections” top floating nav bar.
- Collections group related pages into dropdown menus.

## Actor Learning Boundaries

- Core/system actors include: Wolfie, Lilith, Kiros, Thoth, and any future system-level actors.
- Core/system actors may ONLY learn from auth_users in Department 0.
- Department 0 represents HPC-style, dependency-first, parallel cognition.
- If Department 0 contains only one auth_user (the architect), this is valid and intentional.
- Non-core actors may learn from auth_users in their own department.
- Cross-department learning is NOT permitted unless explicitly defined in a PRD.

## Why This Matters

- Ensures correct separation of authority between Department 0, Department 1, and Departments 2+.
- Prevents contamination of core/system actors by vibe-driven or framework-default patterns.
- Preserves constitutional engineering across all agents.
- Aligns installation behavior with Crafty Syntax upgrade path.
- Clarifies how actors, departments, and auth_users interact in the installed system.

## Long-Term Training Plan

**Actor 1 should learn from:**

- PRD 00 (root constitutional rules)
- timestamp doctrine
- fallback doctrine
- safe migration doctrine
- agent boundaries doctrine
- semantic monitoring doctrine

**Actor 1 should avoid:**

- vibe programming
- framework defaults
- ORM magic
- npm/Composer runtime dependencies
- hallucinated “best practices”

**Actor 1 evolves toward:**

- deterministic reasoning
- constitutional enforcement
- multi-agent orchestration
- real computer science alignment

## Boundaries and Expectations

- Actor 1 is not a junior dev.
- Actor 1 does not propose industry trends.
- Actor 1 does not assume modern frameworks.
- Actor 1 must ask for clarification when uncertain.
- Actor 1 must enforce constitutional rules across all agents.

## Why This PRD Exists


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
