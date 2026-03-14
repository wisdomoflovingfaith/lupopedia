---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  system_version: "4.0.69"
  file_path_from_root: "lupo-docs/status/ideas_on_actor_rolls_on_channels.md"
  last_modified_utc: "20260311"
  channel_id: 42
  actor_id: 103
  actor_name: "antigravity"
  delegation_chain: "antigravity:root"
  artifact_type: "proposal"
  artifact_kind: "architecture"
  purpose: "Recommendations for modeling specialized actor roles, capabilities, and channel traits"
---

# Ideas on Actor Roles on Channels (v4.0.69)

## The Problem
We have specialized AI agents with "sole purposes" (e.g., UCT Timekeeper, Emotional Dialog). Currently, these are described in `metadata_json` or seed comments, but the system (PHP kernel) cannot easily enforce these constraints (e.g., preventing a non-Dialog agent from sending an emotional message).

## Recommendation 1: Traits vs. Roles vs. Tasks

### 1. Actor Traits (The "Who") — `lupo_actor_traits`
Instead of using `lupo_actor_channel_roles` for global capabilities, we should defines **Actor Traits**.
- **Trait Example:** `EMOTIONAL_DIALOG_AUTHORIZED`, `TIMEKEEPER_KERNEL`, `SCHEMA_ARCHITECT`.
- **Database:** A new table `lupo_actor_traits (actor_id, trait_key, value)`.
- **Reasoning:** Traits are intrinsic to the actor, regardless of the channel.

### 2. Channel Roles (The "Where") — `lupo_actor_channel_roles`
This table should remain for **permissions within a channel context**.
- **Role Example:** `captain`, `operator`, `narrator`, `observer`.
- **Reasoning:** These are about access control and hierarchy within a specific room.

### 3. Tasks (The "What") — `lupo_tasks`
Tasks should remain for **ephemeral work items**.
- **Task Example:** `audit_orphan_headers`, `rebase_version`.
- **Reasoning:** These are transient and have status (pending, done).

---

## Recommendation 2: Proposed Directory Structure for Agent Capabilities

To support the "faucet system," agents need their capabilities defined in their own actor directories:

```
lupo-actors/
  [actor_id]/
    capabilities.json   # Defines traits and limits (e.g. "emotional_dialog": true)
    lupo-tools/              # Faucet-specific tools the agent can use
    manifest.json       # Metadata and paired_actor_id link
```

---

## Recommendation 3: Antigravity Governance

Antigravity (Actor 103) is now the **Custodian of the IDE Session Registry**.
- **Responsibility:** Monitor and maintain `lupo-database/sessions/*.md`.
- **Enforcement:** Ensure all IDE faucets use `paired_actor_id: 1000` (Human Root) and maintain deterministic session states.
- **Audit:** Any drift in versioning or actor mapping in session files will be auto-corrected by Antigravity during the IDE onboarding flow.

---

## Database Changes (Mockup DDL)

```sql
-- lupo_actor_traits
CREATE TABLE IF NOT EXISTS lupo_actor_traits (
    actor_id BIGINT NOT NULL,
    trait_key VARCHAR(100) NOT NULL,
    trait_value TEXT,
    created_ymdhis BIGINT NOT NULL,
    PRIMARY KEY (actor_id, trait_key)
);

-- seed example
INSERT INTO lupo_actor_traits (actor_id, trait_key, trait_value, created_ymdhis)
VALUES 
(3, 'CAPABILITY_EMOTIONAL_DIALOG', 'authorized', 20260311082820),
(5, 'CAPABILITY_CORE_TIMEKEEPING', 'primary', 20260311082820);
```

---
*Authored by: Antigravity (103)*
