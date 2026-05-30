# Corrected Actor / Agent Identity Model
# Date: 20260406 | Reviewer: claude-code (actor_id 102)

---

## The Two-Layer Identity Model

Lupopedia uses a strict two-layer identity architecture. These layers must never collapse.

```
LAYER 1 — AGENT TEMPLATE (immutable filesystem blueprints)
  Location: lupo-agents/{slug}/
  Files: agent.json, identity.json, capabilities.json, tools.json,
         boundaries.json, system_prompt.txt, memory.json
  Table:  lupo_agent_definitions
  Key:    agent_id (application-supplied bigint)
  Nature: IMMUTABLE — defines what an agent IS, not what it does at runtime

LAYER 2 — ACTOR RUNTIME (living instances)
  Location: lupo-actors/{actor_id}/  (canonical numeric folder)
  Files: agent.json, WHO.json, session.json, identity.json, properties.json,
         capabilities.json, memory.json
  Table:  lupo_actors
  Key:    actor_id (application-supplied bigint)
  Nature: MUTABLE — the live, running, opinionated instance of an agent
```

---

## Core Identity Chain

```
lupo_agent_definitions --(agent_key)--► lupo_actors --(actor_id)--► lupo_actor_auth_users
       |                                     |                              |
       | template identity                   | runtime identity             | human or
       | (what the agent IS)                 | (who the actor IS now)       | external entity
       |                                     |
       +--(agent_id)--► lupo_agent_tools     +--(actor_id)--► lupo_actor_departments
                        lupo_agent_capabilities              lupo_actor_pairing
                        lupo_agent_boundaries                lupo_actor_relationships
                        lupo_agent_memory_config             lupo_actor_filesystem
                        lupo_agent_llm_configs               lupo_actor_sync_state
                        lupo_agent_definition_versions       lupo_actor_versions
                                                             lupo_actor_capabilities
                                                             lupo_actor_faucets
```

---

## Actor ID Doctrine

- `actor_id` is the **only** stable identity reference across all tables.
- `actor_name` is a UNIQUE lookup alias — never use as FK substitute.
- `actor_id` is application-supplied. Never AUTO_INCREMENT.
- Reserved actor IDs (WOLFIE=1, LILITH=2, HEPHAESTUS=102) are doctrine constants.

---

## Agent Key Doctrine

- `agent_key` is the slug-equivalent for agent templates (e.g., `wolfie`, `chronos`, `lilith`).
- `lupo_actors.agent_key` links a runtime actor to its template definition.
- An actor can exist without an agent_definition (e.g., human actors, external actors).
- An agent_definition can exist without a corresponding actor (reserved/inactive agents).

---

## Department Doctrine

```
Department 0 — Core/System actors only
  Members: system (actor_id 0), wolfie (actor_id 1), lilith (actor_id 2)
  Rule: may ONLY learn from Dept 0 auth_users
  Table: lupo_actor_departments WHERE department_id = 0

Department 1+ — Operational departments
  Members: assigned via lupo_actor_departments junction table
  Rule: actors may span departments; learning boundary set per actor
```

**Critical:** `lupo_actors.department_id` column is REMOVED in the corrected schema.
Department assignment goes exclusively through `lupo_actor_departments` junction table.
Single source of truth — no dual department path.

---

## Faucet Proxy Pattern (v4.0.90+)

```
IDE Request (Cursor, Junie, etc.)
    |
    ▼
lupo_faucet_rules (maps source_actor_id → executing_actor_id)
    |
    ▼
executing_actor_id = HEPHAESTUS (actor_id 102) — always
    |
    ▼
lupo_actor_faucets (records faucet registration)
    |
    ▼
lupo_agent_tool_calls (records tool call WITH actor_id = 102)
```

**Key fix:** `lupo_agent_tool_calls` must record `actor_id` (HEPHAESTUS = 102), not just `agent_id`.
The executing entity is the **actor** (runtime instance), not the **agent** (template).

---

## Adversarial / Oversight Relationships

```
LILITH (actor_id 2) monitors WOLFIE (actor_id 1):
  lupo_actor_relationships:
    actor_a_id = 2 (LILITH)
    actor_b_id = 1 (WOLFIE)
    relationship_type = 'adversarial_oversight'
    authority_direction = 'none'  ← LILITH CANNOT block or claim authority

LILITH Non-Interference Doctrine:
  - is a checker, not a gatekeeper
  - CANNOT block, delay, modify, or claim authority over other agents
  - escalation_to_actor_id = 1 (WOLFIE) — all escalations go to WOLFIE
```

---

## Kernel Agent Identity Requirements

All kernel agents must have in `lupo_agent_definitions`:

| Field | Requirement |
|---|---|
| is_kernel | 1 |
| learning_boundary | 'Department 0 auth_users only (core system actor)' |
| system_prompt_path | filesystem path (NOT inline text) |
| layer | 'kernel' |
| status | 'active' |

Kernel agents: wolfie, lilith, chronos, kairos, hypnos, hermes, iris, vishwakarma, maat, hephaestus, anubis

---

## Memory Architecture

```
Observation Input --► lupo_kairos_observations (raw events, assertions, interactions)
                              |
                              ▼ (KAIROS consolidation process)
                      lupo_kairos_memory (consolidated facts, preferences, corrections)
                              |
                              ▼ (supersession chain)
                      superseded_by_id → newer kairos_memory_id

Config:            lupo_agent_memory_config (rollup_strategy, retention, thresholds)
Rollup/Windowed:   lupo_memory_rollups (existing — retains summaries by window)
```

KAIROS owns all memory consolidation. No other agent writes to `lupo_kairos_memory`.
CHRONOS may read observations for temporal reasoning. It does not write.

---

## Summary of Key Corrections

| Issue | Old Model | Corrected Model |
|---|---|---|
| Actors PK | actor_name varchar(64) | actor_id bigint |
| Actor ID nullability | DEFAULT NULL | NOT NULL |
| Agent table purpose | LLM config blob | doctrine identity |
| LLM config | in lupo_agents | lupo_agent_llm_configs |
| System prompt | text blob in DB | filesystem path reference |
| Department link | dual path (column + junction) | junction table only |
| Paired actor | column in lupo_actors | lupo_actor_pairing table |
| Adversarial role | columns in lupo_actors | lupo_actor_relationships table |
| Filesystem paths | columns in lupo_actors | lupo_actor_filesystem table |
| Sync state | columns in lupo_actors | lupo_actor_sync_state table |
| Faucet table name | lupo_agent_faucets | lupo_actor_faucets |
| Tool call executor | agent_id only | actor_id + agent_id |
| KAIROS memory | no tables | lupo_kairos_observations + lupo_kairos_memory |
| Runtime state | no table | lupo_actor_runtime_state |
| Actor moods PK | none | actor_mood_id bigint |
| Timestamp naming | mixed (_utc, _at, utc_) | all _ymdhis |

---

Reviewer: claude-code actor_id 102
Review completed: 20260406
