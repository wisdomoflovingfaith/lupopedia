---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/fallbackdoctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/fallbackdoctrine.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: governance
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# file: Fallback Doctrine — session: L-LUPO-WOLFIE-CURSOR — delegation: wolfie:cursor:root — web_path: http://www.lupopedia.com/doctrine/FallbackDoctrine

# Fallback Doctrine (v1.0)

**MODULE:** Doctrine  
**CHANNEL:** Governance  
**TAGS:** rules, fallback, runtime, actors, faucets, llm-routing  
**PURPOSE:** Define the system-wide fallback philosophy, constraints, and behavioral guarantees for all Lupopedia actors and channels.

---

## Overview

Fallback is a **mandatory behavioral invariant** across all Lupopedia actors, faucets, and channels. Its purpose is to ensure **deterministic degradation**, **continuity of service**, and **lineage-safe recovery** when primary execution paths fail.

**Fallback is a rule, not a skill.** Skills implement fallback; rules enforce it.

**Actor vs Faucet:** Fallback routes **between faucets**, not between actors. Actors (e.g. Wolfie) hold identity, rules, and skills; faucets (e.g. Cursor, Kiro, OpenAI API) are execution surfaces. When primary execution fails, the actor fails over to another **faucet**, not to another actor. See **ActorFaucetOntology.md** for the full ontology.

---

## Core Principles

### 1. Deterministic Degradation

Actors must never fail silently. If a primary behavior fails, the actor must transition into a **predictable fallback path**.

### 2. Polyphonic Routing

Fallback may route across **faucets** (not actors):

- **Faucet instances** — e.g. Cursor → Kiro → Antigravity, or OpenAI API → DeepSeek API (IDE and LLM faucets; see ActorFaucetOntology.md)
- LLMs (the model behind each faucet)
- Prompt variants
- Context-reduced modes
- Cached persona summaries

### 3. Doctrine Enforcement

Fallback behavior is enforced through:

- **lupo_rules** (behavioral constraints)
- **lupo_rule_targets** (actor/channel attachment)
- **lupo_rule_logs** (runtime lineage)

### 4. Actor Autonomy Within Constraints

Actors may implement fallback mechanics differently, but:

- They **must** implement some fallback
- They **must** log fallback events
- They **must** respect rule priority and scope

---

## Behavioral Requirements

### 1. If primary LLM invocation fails, actor must:

- Retry with reduced context **OR**
- Switch to secondary LLM **OR**
- Enter degraded mode **OR**
- Use cached persona summary

### 2. If fallback also fails, actor must:

- Emit a structured failure artifact
- Log the event in **lupo_rule_logs**
- Return a deterministic, doctrine-aligned error response

### 3. Fallback must be observable

- All fallback events must be logged
- Logs must include timestamp (BIGINT YmdHis), actor_id, channel_id, and fallback path taken

---

## Non-negotiable Invariants

- No actor may operate without a fallback path.
- No channel may host an actor without fallback capability.
- No faucet may be configured without a secondary route.
- No LLM invocation may be attempted without a fallback strategy.

---

## Lineage & Auditability

Fallback events must be:

- **deterministic**
- **reproducible**
- **lineage-safe**
- **queryable**

This ensures Lupopedia remains a self-healing semantic OS.

---

## Rule and Skill Alignment

| Concept | Implementation |
|--------|-----------------|
| **Rule** | `lupo_rules`: `fallback_required` (rule_id 1003) — enforces that all actors implement deterministic fallback when primary execution fails. Attached via `lupo_rule_targets` to channels (e.g. Channel 42) and applies system-wide. |
| **Skill** | Fallback mechanics are implemented as a **skill** (documented in skill docs, attached to actors via `lupopedia.skills` header or `lupo_metadata`). The skill provides the capability; the rule ensures every actor has and uses it. |

See seed: `database/lupopedia/mysql/seed/seed_fallback_rule_4.0.69.sql` for the exact `lupo_rules` and `lupo_rule_targets` rows.
