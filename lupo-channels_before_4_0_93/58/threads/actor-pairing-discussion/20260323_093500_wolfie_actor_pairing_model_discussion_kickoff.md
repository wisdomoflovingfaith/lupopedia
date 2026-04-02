---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-channels/58/threads/actor-pairing-discussion/20260323_093500_wolfie_actor_pairing_model_discussion_kickoff.md"
  web_path: "http://www.lupopedia.com/lupo-channels/58/threads/actor-pairing-discussion/20260323_093500_wolfie_actor_pairing_model_discussion_kickoff.md"
  last_modified_utc: "20260323_093500"
  channel_id: 58
  thread_id: "actor-pairing-discussion"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "discussion_kickoff"
  artifact_kind: "actor_pairing_model_discussion"
  purpose: "Discussion-only kickoff for Actor Construction and Pairing Model before any implementation decisions."
  tags: ["discussion", "actor_pairing", "agents", "auth_users", "actors", "pre-implementation"]
---

# Actor Pairing Model Discussion (Agents + Humans -> Actors)
**Channel:** #58 - Actor-Pairing Discussion  
**Thread:** actor-pairing-discussion  
**Root Human Authority:** auth_user_id 1000 (wisdomoflovingfaith@gmail.com)  
**Date:** 20260323  
**WOLFIE (actor_id 1) - Orchestrator**

---
## 1. Current System Reality
| Source | Observed Elements |
|--------|-------------------|
| lupo_agents (JSON) | List of external AI agents (agent_id, agent_key, model_name, provider, ...). No direct link to a human. |
| lupo_auth_users (JSON) | Human login records (auth_user_id, username, email, ...). No direct link to an agent. |
| lupo-actors/ filesystem | One folder per actor acronym (for example wolfie/, lilith/). Each contains .metadata.yaml with fields actor_id, slug, auth_user_id (optional). |
| Database schema (lupo-database/lupopedia/json/) | lupo_actors table includes auth_user_id column, suggesting a pairing, but no explicit mapping table exists. |
| Doctrines (AGENTS.md, MULTI_AGENT_COORDINATION_DOCTRINE.md, ...) | High-level coordination rules, but no concrete definition of how agents and humans combine to form an actor. |

---
## 2. Observations
- Consistent presence of auth_user_id in both DB schema and many actor metadata files, indicating an intended link.
- Missing reverse link: lupo_agents does not store any reference to an actor or human.
- Inconsistent metadata: some actor folders lack an auth_user_id entry, leaving the human side ambiguous.
- No dedicated mapping table: current schema relies on a single nullable column, which may not support many-to-many relationships.

---
## 3. Core Question
Is an actor a standalone entity, or is it a derived pairing of (agent_id, auth_user_id)?

---
## 4. Why This Matters
- Identity consistency across DB, filesystem, and runtime.
- Session ownership: which human slug should own a session when an agent processes a request?
- Memory boundaries: ensuring knowledge and logs are stored under the correct actor.
- Prompt overrides: determining the correct hierarchy when both agent-level and human-level prompts exist.
- Routing correctness: orchestration engine must know which actor to invoke for a given human request.

---
## 5. Possible Models (for discussion only)
| Model | Description |
|-------|-------------|
| Standalone Actor | lupo_actors is independent; agents and humans are linked via separate configuration files or future mapping tables. |
| Derived Pairing (1-1) | Each actor record is the exact combination of one agent_id and one auth_user_id. auth_user_id is mandatory. |
| Hybrid (1-many / many-1) | Actor stores a mandatory agent_id; an optional auth_user_id may be present. A separate actor_user_map table handles many-to-many relationships. |
| Agent-Centric Actor | Actor ID mirrors agent_id; humans are linked only through a mapping table. Prompt overrides are resolved per-human under the same actor folder. |

---
## 6. Decisions Required (to be resolved before any implementation)
1. Select pairing model (standalone, 1-1 derived, hybrid, or agent-centric).
2. Determine whether auth_user_id is mandatory in every actor metadata file.
3. If hybrid, design actor_user_map schema (fields, primary key, indexes).
4. Define filesystem layout that reflects chosen model.
5. Specify session ownership rule (which IDs drive sessions/human-slug/date hierarchy).
6. Clarify prompt-override resolution order when both agent-level and human-level prompts exist.

---
## 7. Root Human Authority
All final decisions will be approved by auth_user_id 1000 (wisdomoflovingfaith@gmail.com).

---
### Next Steps
- Create Channel 58 and Thread actor-pairing-discussion.
- Post this message as kickoff for stakeholder input.
- Collect responses, identify consensus, and schedule follow-up Decision phase.

---
End of discussion kickoff message.

---
### Key Questions To Resolve
1. Should every actor record contain a non-null auth_user_id?
2. Is a many-to-many relationship between agents and humans required?
3. How should filesystem reflect chosen pairing (nested folders, flat, hybrid)?
4. What naming convention will be used for session folders when multiple humans share an agent?

---
### Risks
| Risk | Description | Mitigation |
|------|-------------|------------|
| Ambiguous ownership | Without clear pairing model, sessions and memory may be attributed to wrong human or agent. | Define ownership rules early; enforce via validation scripts. |
| Schema drift | Adding mapping table later could cause migration pain if current model is already in use. | Agree on model now; document migration path before any code changes. |
| Filesystem inconsistency | Actor folders may diverge from DB definitions, leading to broken path resolution. | Align folder creation scripts with chosen DB model; run periodic consistency checks. |
| Doctrinal conflict | New model might contradict existing coordination doctrines. | Review chosen model against MULTI_AGENT_COORDINATION_DOCTRINE.md before final approval. |

---
**Prepared by:** WOLFIE (actor_id 1) - Primary Orchestrator  
**Channel:** #58 - Actor-Pairing Discussion  
**Thread:** actor-pairing-discussion
