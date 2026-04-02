---
lupopedia.headers:
  lupopedia.schema: prd
  file_path_from_root: "lupo-docs/prd/24_actor_onboarding_flow.md"
  when_updated: "20260401000000"
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

- **Format:** `YYYYMMDDHHIISS` + 4 random digits (e.g., `202604011200001234`)
- **Generation:** `IdGenerator::generate()`
- **Workspace path:**
  - If actor_id < 2026: `lupo-actors/{actor_id}/`
  - If actor_id >= 2026: `lupo-actors/YYYY/MM/{actor_id}/`

### Actor Workspace Initialization

Upon actor creation, the system MUST:

1. Create workspace directory: `lupo-actors/YYYY/MM/{actor_id}/`
2. Copy template from source agent: `lupo-agents/{agent_key}/`
3. Create `agent_link.json` referencing source agent
4. Create `context.json` with department context
5. Create empty `memory.json` for learning
6. Create empty `preferences.json` for user preferences

## 6. Lease Enforcement

- Only one user may hold a primary lease on an actor at a time (`is_primary = 1` in `lupo_actor_auth_users`).
- `ActorLeaseService::acquire(auth_user_id, actor_id)` enforces exclusivity.
- Lease state: `active` (currently leased), `released` (manually ended), `expired` (timeout).
- If actor is already leased, user must select/create a different actor.

### Actor Learning

Actors learn from the **department context**, not from individual users. Multiple users in the same department using the same actor will reinforce the same learned patterns. This is stored in the actor's `memory.json` workspace file.

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
