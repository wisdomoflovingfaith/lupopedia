---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_routing_events.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_routing_events.md
  status: active
  when_updated: '20260513053635'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/lupo_routing_events.toon
  atoms_toon: null
  transcript_jsonl: 0/development/lupo_routing_events_doc
  artifact_type: documentation
  artifact_kind: table
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: documentation
  prd_cluster: null
  title: 'Table: lupo_routing_events'
  summary: Records explicit context handoffs between agents and channels. Captures provenance of routed tasks.
---
> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

# Table: lupo_routing_events

## Purpose
The **Routing Events** table is the source of truth for "what I gave to whom." It records the explicit action of an operator taking a piece of context (a message, a file, or a scratchpad draft) and routing it to a specific agent in a specific channel.

This solves the "Broadcast vs. Directed" problem by creating a formal record of provenance for every task-promoting routing action.

## Schema

### Primary Key
- `routing_id`: bigint NOT NULL

### Columns

| Column | Type Definition | Description |
|---|---|---|
| `routing_id` | `bigint NOT NULL` | Primary key (YYYYMMDDHHIISS or Auto-Inc) |
| `source_message_id` | `bigint NOT NULL` | ID of the message being routed |
| `source_channel_id` | `bigint NOT NULL` | The channel where the context originated |
| `source_actor_id` | `bigint NOT NULL` | The actor who produced the source content |
| `destination_channel_id` | `bigint NOT NULL` | The target channel for the routed content |
| `destination_actor_id` | `bigint NOT NULL` | The specific agent targeted by the route |
| `routing_explanation` | `text` | Operator-provided context/instructions for the handoff |
| `routed_by_actor_id` | `bigint NOT NULL` | The operator persona who performed the route |
| `created_ymdhis` | `bigint NOT NULL` | Timestamp of the routing action (YYYYMMDDHHIISS) |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `PRIMARY` | `routing_id` | yes |
| `idx_created` | `created_ymdhis` | no |
| `idx_destination` | `destination_actor_id`, `created_ymdhis` | no |

## Doctrine
- **Provenance:** Every directed task assignment across channel boundaries MUST create a routing event.
- **Traceability:** Enables "routing history" views to reconstruct the chain of thought across agents.
- **Source of Truth:** Aligns with `database/lupopedia/json/lupo_routing_events.json`.
