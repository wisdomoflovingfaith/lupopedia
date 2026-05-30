---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/decision_model.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/decision_model.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: design
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# file: DECISION_MODEL — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/DECISION_MODEL

# Decision Model Doctrine (4.0.87)

## Summary

**Decisions live in conversation history. Not in dedicated decision tables.**

As of 4.0.87, Lupopedia no longer maintains dedicated database tables for tracking decisions, evidence, or reasoning chains. The tables `lupo_decisions`, `lupo_decision_edges`, `lupo_decision_evidence`, and `lupo_decision_influences` have been removed from the install schema and all runtime code has been deleted.

## Why

The Bayesian Decision Tracking system modeled reasoning as structured rows in a relational database. This approach was misaligned with how decisions actually occur in Lupopedia: through conversation, channel activity, and artifact creation. Decisions are:

- non-linear
- context-dependent
- embedded in the flow of channel threads
- not reducible to probability rows

Maintaining a parallel "decision database" created a split system where the real record of what happened (channels + threads) and an artificial summary (decision tables) coexisted without synchronization. The artificial summary was never reliable; channels always held the real history.

## The New Model

```
DECISIONS LIVE IN CONVERSATION HISTORY

Channels hold:       what was proposed and discussed
Threads hold:        the reasoning process
Artifacts hold:      what was decided and why
Edges hold:          relationships between decisions and other objects

ROSE reads these to understand:

  - what was decided
  - why it was decided
  - what influenced it

The database remains:

  storage
  indexing
  structure

NOT a reasoning engine.
```

## Where Decisions Are Recorded

| Type | Location | Example |
|------|----------|---------|
| Design decisions | Channel thread artifact | `20260325_...wolfie_decision_*.md` |
| Architecture choices | `docs/doctrine/` | This file |
| Per-version product decisions | `docs/versions/<version>/PLAN.md` and `TODO.md` | Checked-off items with rationale |
| Coordination decisions | Channel 42 threads | WOLFIE directive artifacts |
| Review decisions | Channel thread artifacts | SESHAT / LILITH review artifacts |

## ROSE as Interpretation Layer

ROSE (actor_id: see registry) is the canonical interpretation layer for decision context. ROSE reads channel threads to understand:

- the emotional and relational context of decisions
- what was chosen and what was released
- how actors came to agreement or disagreement

ROSE does **not** require a decision table to do this. ROSE reads **conversation artifacts** directly.

## What the Database Stores

The database stores indexed structure:

- `lupo_channels` — channel definitions
- `lupo_channel_messages` / `lupo_threads` — message and thread records
- `lupo_metadata` — property/value store for arbitrary entity metadata
- `lupo_edges` — relationship graph (actor→actor, content→content, artifact→artifact)
- `lupo_artifacts` — artifact registry

Decision context may be encoded as metadata (`lupo_metadata` records keyed to artifacts or threads), but there is no standalone decision tracking table.

## Deprecated Components (4.0.87)

The following were removed:

| Component | Type | Status |
|-----------|------|--------|
| `lupo_decisions` | Table (install SQL) | Removed in 4.0.87 |
| `lupo_decision_edges` | Table (install SQL) | Removed in 4.0.87 |
| `lupo_decision_evidence` | Table (install SQL) | Removed in 4.0.87 |
| `lupo_decision_influences` | Table (install SQL) | Removed in 4.0.87 |
| `BayesianDecisionService.php` | PHP service class | Deleted in 4.0.87 |
| `decisions-api.php` | REST API controller | Deleted in 4.0.87 |
| `bayesian_decision_service_test.php` | Unit test | Deleted in 4.0.87 |
| `BAYESIAN_DECISION_DOCTRINE.md` | Doctrine document | Superseded; marked DEPRECATED |

## Constraint

**Do not recreate decision-tracking tables.** If future work requires persisting decision metadata, use `lupo_metadata` with appropriate `entity_type` and `property_key` values scoped to the relevant artifact or thread. Never create a new `lupo_decisions`-style table.

The correct structure already exists:

```
channels → threads → artifacts → edges
```

That is the decision history.
