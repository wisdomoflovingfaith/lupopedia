---
lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "doctrine"
  system_version: "4.0.74"
  file_path_from_root: "lupo-docs/doctrine/AUTH_USERS_ACTORS_AGENTS_FAUCETS_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/AUTH_USERS_ACTORS_AGENTS_FAUCETS_DOCTRINE"
  last_modified_utc: "20260314"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "antigravity"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "identity"
  purpose: "Clarifies the crucial separation between human auth accounts, actor orchestration identities, AI agent metadata, and execution faucets."
  tags: ["identity", "auth_user", "actor", "agent", "faucet", "v4.0.74", "doctrine"]

lupopedia.init:
  orchestrator_actor: "wolfie"
  rule_set_version: "4.0.73+"
  applies_to: ["auth", "identity", "actors"]
  enforcement: strict

lupopedia.edges:
  comment: "Snapshot of outbound edges for identity layer documentation."
  outbound_edges:
    - { to: "lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema", weight: 0.9 }

    - to: "lupo-docs/prd/32_actor_authority_agent_roles.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260314"
  last_verified_by: "antigravity"
  orchestrator: "wolfie"
  next_action:
    - "Validate all codebase references to ensure they use actor_id instead of user_id for operations."
---
# file: Auth Users, Actors, Agents, and Faucets Doctrine — session: L-LUPO-ROOT-ANTIGRAVITY — delegation: wolfie:root (faucet: antigravity) — web_path: http://www.lupopedia.com/doctrine/AUTH_USERS_ACTORS_AGENTS_FAUCETS_DOCTRINE

# Auth Users vs Actors vs Agents vs Faucets Doctrine (v4.0.74)

In Lupopedia, the separation between human authentication, operational orchestration identity, AI parameters, and execution environments is fundamental to the semantic OS. This document solidifies their specific roles.

## 1. Auth Users
**Purpose:** Human authentication and account ownership.
**Storage:** `lupo_auth_users` and related `auth_` tables.
- **Definition:** Auth users represent the physical human beings logging into the system. They have passwords, 2FA, email addresses, and account-level security permissions.
- **Mechanism:** A human logs in and verifying their identity. That identity verifies who they are, but *does not* represent how work is orchestrated globally in channels or tasks. 
- **Rule:** DO NOT use `user_id` or `auth_user_id` for content ownership, metadata tagging, or channel permissions. Operations always happen via the paired `actor_id` (human actors start at 1000).

## 2. Actors
**Purpose:** Orchestration, identity, governance, and collaboration.
**Storage:** `lupo_actors`.
- **Definition:** Actors are the universal, operational identities inside Lupopedia. Every entity—whether human or AI—that participates in a channel, authors an artifact, or executes a task is an actor.
- **Numbering Doctrine:**
  - `0-999`: Reserved for system and primary AI actors (e.g., Wolfie `1`, Lilith `2`).
  - `1000+`: Human actors. When a human account is created in `auth_users`, they receive an `actor_id` >= 1000 in `lupo_actors`.
- **Relationship:** A human operates by pairing to an actor identity (`paired_actor_id`). AI workflows are fully driven by actor identities.
- **Characteristics:** Actors hold `traits` (`lupo_actor_traits`), channel `roles` (`lupo_actor_channel_roles`), and `skills`.

## 3. Agents
**Purpose:** AI behavior configuration and metadata.
**Storage:** `lupo_agents`.
- **Definition:** Agents define *how* an AI actor behaves. They store settings like LLM model names, temperature, prompts, kapu (safety) scores, provider details, and timeout thresholds.
- **Distinction:** An "agent" is not the overarching orchestration identity. The *actor* is the identity; the *agent* is merely the runtime configuration that allows the actor to reason and communicate properly.

## 4. Faucets
**Purpose:** Execution surfaces.
**Storage:** `lupo_agent_faucets`.
- **Definition:** A faucet is the literal software or API endpoint through which an actor executes actions. Examples: **Cursor, Windsurf, Antigravity, Kiro, Codex, JetBrains.**
- **The Core Rule:** **Actors orchestrate. Faucets execute.** If Wolfie (Actor 1) asks Cursor (Faucet 102) to audit a file, Wolfie's identity persists as the orchestrator, but the mechanism tracking the action is the faucet.
- **Traceability:** Session files (e.g., `L-LUPO-ROOT-CURSOR.md` or `L-LUPO-ROOT-ANTIGRAVITY.md`), `lupo_sessions`, and `lupo_dialog_messages` record the `faucet_slug` and `faucet_instance_id` to precisely track which surface executed a task on behalf of an actor.

## Summary Matrix

| Entity | Concept | Real-world Equivalent | Table |
|--------|---------|-----------------------|-------|
| **Auth User** | Authentic Human | Driver's License | `lupo_auth_users` |
| **Actor** | Orchestrator / Worker | Employee Badge | `lupo_actors` |
| **Agent** | AI Configuration | Job Instructions / Tooling | `lupo_agents` |
| **Faucet** | Execution Surface | Computer / Keyboard | `lupo_agent_faucets` |

*Remember:* To find out *who* did something, check the **actor**. To find out *how* it was done, check the **faucet** and **agent**.

## Related Doctrine

- `ACTOR_AGENT_AUTH_USER_MODEL.md` defines the actor-centric admin and web presentation model for 4.0.87.
- `EFFECTIVE_ACTOR_RESOLUTION.md` documents how the server resolves posting actor identity at runtime.
