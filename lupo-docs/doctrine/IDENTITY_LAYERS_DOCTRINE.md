---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "doctrine"
  system_version: "4.0.69"
  file_path_from_root: "lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/IDENTITY_LAYERS"
  last_modified_utc: "20260311"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "identity"
  purpose: "Canonical separation of actor, faucet, session, trait, role, and task so documentation and code use consistent terms."
  tags: ["identity", "actor", "faucet", "session", "trait", "role", "task", "4.0.69"]
lupopedia.footer:
  last_verified: "20260311"
  last_verified_by: "cursor"
---
# file: Identity Layers Doctrine — session: L-LUPO-ROOT-CURSOR — delegation: wolfie:root (faucet: cursor) — web_path: http://www.lupopedia.com/doctrine/IDENTITY_LAYERS

# Identity Layers Doctrine (v4.0.69)

This document explicitly separates the identity and context layers used across Lupopedia. Use these definitions to avoid ambiguity between status docs, brainstorms, and code.

---

## 1. Actor

**Definition:** **Actors** are the orchestration identities of Lupopedia. They coordinate and govern work through faucets, sessions, channels, rules, and traits. Stored in `lupo_actors`. Identified by `actor_id` and `actor_name`. **Faucets are execution surfaces, not identities.**

- **Holds:** Rules (via `lupo_rules` / `lupo_rule_targets`), skills (via docs + `lupopedia.skills` / `lupo_metadata`), traits (via `lupo_actor_traits`; see TRAITS_DOCTRINE.md), capabilities (via `lupo_actor_capabilities`). Authorization is checked via TraitEnforcer and `lupo_action_authorization` (AUTHORIZATION_DOCTRINE.md).
- **Does not:** Configure runtime (temperature, model, context window). That is the faucet’s job.
- **Examples:** Wolfie, Lilith, root (human actor 10000).

---

## 2. Faucet

**Definition:** The **execution surface** — environment + LLM + runtime config. Stored in `lupo_agent_faucets`. Has `faucet_class` (e.g. `ide`, `llm`).

- **Controls:** Temperature, model, max tokens, tool access, context window, system prompt, safety envelope.
- **Does not:** Hold identity, rules, or skills; the **actor** does. The actor operates **through** a faucet.
- **Examples:** Cursor IDE, Kiro IDE, Antigravity IDE, Windsurf, Codex, JetBrains, Warp, OpenAI API, DeepSeek API. IDE surfaces are faucets, not actors.

**Reference:** `lupo-docs/doctrine/ActorFaucetOntology.md`. Faucet traceability in sessions and messages: FAUCET_TRACEABILITY_DOCTRINE.md.

---

## 3. Session

**Definition:** **Runtime state** of who is doing what, where. Database: `lupo_sessions`. Portable/runtime: `lupo-database/sessions/*.md` (session files).

- **Contains:** `actor_id`, `paired_actor_id`, `channel_id`, `federation_node_id`, `session_id`, `system_version`, and related context.
- **Purpose:** Deterministic continuity for IDE faucets and web sessions; reconciliation between DB and session files is defined in Session Reconciliation Doctrine.
- **Reference:** `lupo-docs/doctrine/SESSION_RECONCILIATION_DOCTRINE.md`, `lupo-docs/doctrine/LUPOPEDIA_HEADERS/` (session block).

---

## 4. Trait

**Definition:** **Intrinsic constraint or capability marker** for an actor. Stored in `lupo_actor_traits`. Actor-scoped only; not channel-scoped.

- **Purpose:** Mark what an actor *is* or *is allowed* at the identity level (e.g. `EMOTIONAL_DIALOG_AUTHORIZED`, `SCHEMA_ARCHITECT`). Enforced via rules that reference traits.
- **Not:** Channel permissions (those are **roles**), and not skills (those are documented capabilities attached via metadata/skills docs).
- **Reference:** `docs/status/DESIGN_NOTE_LUPO_ACTOR_TRAITS_4.0.69.md`.

---

## 5. Role

**Definition:** **Channel-local permission** for an actor on a channel. Stored in `lupo_actor_channel_roles`.

- **Scope:** Per (actor, channel). Role keys (e.g. `admin`, `member`) define what the actor can do *on that channel*.
- **Not:** Intrinsic actor traits; traits are identity-level and not channel-specific.

---

## 6. Task

**Definition:** A **transient work item** (e.g. development task, governance task). Stored in `lupo_tasks` and/or channel file structure.

- **Scope:** Associated with channel, thread, or project; has lifecycle (pending, active, completed).
- **Not:** Identity, faucet, session, trait, or role.

---

## Summary table

| Layer   | Meaning                    | Primary storage / location              |
|--------|----------------------------|-----------------------------------------|
| Actor  | Identity, rules, skills    | `lupo_actors` + rules/skills/traits     |
| Faucet | Execution surface, LLM    | `lupo_agent_faucets`                    |
| Session| Runtime state              | `lupo_sessions`, `lupo-database/sessions/*.md` |
| Trait  | Intrinsic actor constraint | `lupo_actor_traits`                     |
| Role   | Channel-local permission   | `lupo_actor_channel_roles`              |
| Task   | Transient work item        | `lupo_tasks`, channel tasks             |

---

## References

- `lupo-docs/architecture/cursor_actors_channels_semantic_architecture_4.0.69.md` — canonical architecture.
- `lupo-docs/doctrine/ActorFaucetOntology.md` — actor vs faucet.
- `lupo-docs/doctrine/SESSION_RECONCILIATION_DOCTRINE.md` — session truth and reconciliation.
