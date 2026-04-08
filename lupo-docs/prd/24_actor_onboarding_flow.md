---
lupopedia.headers:
  lupopedia.schema: prd
  file_path_from_root: "lupo-docs/prd/24_actor_onboarding_flow.md"
  when_updated: "20260407235921"
  last_modified_utc: "20260407235921"
  purpose: "Defines the complete user onboarding flow: login, agent selection, actor creation, lease acquisition, and channel entry."
  tags: ["prd", "onboarding", "actor", "agent", "lease", "ui", "api"]
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
---

# PRD 24: Actor Onboarding Flow

## 1. Purpose
This PRD defines the end-to-end onboarding flow for a user in Lupopedia, from authentication to active participation in a channel as a leased actor. It unifies and clarifies the user journey, API endpoints, UI pages, and database interactions required for a seamless onboarding experience.

## 2. User Journey

1. **Login** — User authenticates via `[LUPOPEDIA_PUBLIC_PATH]/login.php` (the subdirectory is configurable; see note below).
2. **Department Selection** — User selects department (or inherits from profile).
3. **Agent Selection** — User selects an agent template.
4. **Actor Creation** — System creates actor with deterministic ID and workspace.
5. **Lease Acquisition** — System associates actor with user (exclusive lease).
6. **Channel Entry** — User redirected to channel where actor can participate.

### 2a. Department Selection

After selecting an agent, the user MUST select a department (or be assigned one based on their profile). The actor inherits the department's context and will learn behavior specific to that department.

| Department   | Actor Behavior                                 |
|-------------|-------------------------------------------------|
| Sales       | Persuasive, urgency-driven, deal-focused        |
| Engineering | Analytical, precise, architecture-focused       |
| Support     | Empathetic, patient, solution-focused           |

## 3. UI Pages

**Note:** All URLs are relative to the configured subdirectory, which is set by `LUPOPEDIA_PUBLIC_PATH` in `lupopedia-config.php`. For example, if installed at `example.com/lupopedia/`, the login page is at `/lupopedia/login.php`. The subdirectory name may vary per installation.

| Page                                 | Purpose                                      |
|--------------------------------------|----------------------------------------------|
| `[LUPOPEDIA_PUBLIC_PATH]/login.php`  | User authentication                         |
| `[LUPOPEDIA_PUBLIC_PATH]/select_agent.php` | Agent selection interface                   |
| `[LUPOPEDIA_PUBLIC_PATH]/select-actor.php` | Switch/create actors for logged-in users     |
| `[LUPOPEDIA_PUBLIC_PATH]/channel.php`      | Channel chat interface                      |

## 4. API Endpoints

| Endpoint                      | Method | Purpose                                      |
|-------------------------------|--------|----------------------------------------------|
| `/api/auth/login`             | POST   | Authenticate user, create session            |
| `/api/actors`                 | POST   | Create actor from agent                      |
| `/api/actors/{id}/lease`      | POST   | Acquire lease for actor (exclusive)          |

## 5. Database Interactions

| Table                   | Purpose                                         |
|-------------------------|-------------------------------------------------|
| `lupo_auth_users`       | User credentials and metadata                   |
| `lupo_sessions`         | Active user sessions                            |
| `lupo_agents`           | Agent templates (immutable)                     |
| `lupo_actors`           | Actor instances (runtime)                       |
| `lupo_actor_auth_users` | Actor-user lease relationships (exclusive)      |
| `lupo_dialog_messages`  | Channel messages from actors                    |

### Actor ID Generation

- **Runtime (onboarding / operator-created actors):** **`IdGenerator::generate()`** → **`YYYYMMDDHHIISS` + 4-digit suffix** (e.g. `202604011200001234`). Set **`created_ymdhis`** on **`lupo_actors`** to the **14-digit prefix** of the new **`actor_id`** at insert (same pattern as other generator-backed PKs).
- **Seed / install actors:** Fixed low **`actor_id`** (e.g. WOLFIE **1**, LILITH **2**) from **`install_new_lupopedia.sql`** — **not** produced by **`IdGenerator`** in that seed INSERT. **`created_ymdhis`** on those rows = **install** packed UTC, **`0`** (immemorial), or another documented constant — **independent** of PK shape (PRD 00 §3.2, PRD 01 **`lupo_actors`**, PRD 38 for parallel **`lupo_memory_nodes`** rules).
- **Workspace path:**
  - If actor_id < 2026: `lupo-actors/{actor_id}/`
  - If actor_id >= 2026: `lupo-actors/YYYY/MM/{actor_id}/`

**Constitutional cross-reference:** Dual PK strategy (seed low ids vs runtime **`IdGenerator`**) for **all** seeded tables — **PRD 00 §3.2.1**.

### Actor Workspace Initialization

Upon actor creation, the system MUST:

1. Create workspace directory: `lupo-actors/YYYY/MM/{actor_id}/`
2. Copy template from source agent: `lupo-agents/{agent_key}/`
3. Create `agent_link.json` referencing source agent
4. Create `context.json` with department context
5. Register root memory node in `lupo_memory_nodes`; file stored at `lupo-memory/YYYY/MM/{memory_slug}.json` (4.0.96+; `memory.json` is DEPRECATED)
6. Create empty `preferences.json` for user preferences

## 6. Lease Enforcement

- Only one user may hold a primary lease on an actor at a time (`is_primary = 1` in `lupo_actor_auth_users`).
- `ActorLeaseService::acquire(auth_user_id, actor_id)` enforces exclusivity.
- Lease state: `active` (currently leased), `released` (manually ended), `expired` (timeout).
- If actor is already leased, user must select/create a different actor.

### Actor Learning

Actors learn from the **department context**, not from individual users. Multiple users in the same department using the same actor will reinforce the same learned patterns. Learning is stored as a root memory node at `lupo-memory/YYYY/MM/{memory_slug}.json`, registered in `lupo_memory_nodes`, and linked to the actor via `lupo_edges` (4.0.96+). `memory.json` workspace files are DEPRECATED.

If the same user creates a new actor from the same agent for a different department, that actor will learn separate department-specific patterns.

## 7. Fallbacks & Error Handling

- If agent is unavailable, user is prompted to select another agent.
- If actor creation fails, user is shown an error and can retry.
- If lease cannot be acquired, user is redirected to actor selection.

## 8. Dependencies

- PRD 01: Core Identity
- PRD 07: Agents & Faucets
- PRD 15: Actors
- PRD 18: Channel Chat Display


## 9. Resolved Questions

| Question | Resolution |
|----------|------------|
| Should users be able to lease multiple actors simultaneously? | **No.** Each actor has a single primary user (`is_primary = 1`). Users may create multiple actors (different agents/departments), but each actor is exclusively leased. |
| Should actor creation be rate-limited? | **Yes.** Limit to 5 actors per hour per user to prevent abuse. |
| How are actor workspaces initialized? | See Section 5: Actor Workspace Initialization. |

---

**Status:** Drafted April 1, 2026 — covers all onboarding steps from login to channel entry. To be updated as implementation proceeds.


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
