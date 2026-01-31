# Lupopedia Identity and Channel Architecture

This document describes the Semantic OS identity layer built on three core tables:
`lupo_channels`, `lupo_actors`, and `lupo_actor_channels`. It explains what each
table represents, why the recent inserts exist, how memberships are established,

## 1) Channel Layer: `lupo_channels`

The `lupo_channels` table defines all communication spaces used by the Semantic OS.
Each channel row includes:

- `channel_number` (unique numeric identifier)
- `channel_key` and `channel_slug` (stable string identifiers)
- `channel_type` (currently `chat_room`)
- `description`
- kernel flag (`is_kernel`)
- timestamps and metadata fields

Channels 5100-5130 were inserted in two batches:

- 5100-5115: core system channels
- 5116-5130: extended system and experimental channels

### Channel Numbering Clarifications

Lupopedia currently contains approximately 222 channels, but the channel_number values are not sequential and do not form a contiguous numeric range. High channel numbers such as 5100 are valid and intentional.

The channel_number field is a logical identifier, not a bounded index. Channel numbers may be:

- sparse
- non-linear
- grouped by subsystem
- allocated in batches for architectural clarity

The total number of channels is what matters, not the numeric range they occupy. No doctrine requires channels to fall within any specific numeric interval. Future contributors should treat channel_number as a semantic identifier rather than a sequential counter.

These channels cover:

- kernel logs
- doctrine
- semantic routing
- emotional metadata
- agent training
- system events
- recovery and debugging
- experimental sandboxes

## 2) Actor Layer: `lupo_actors`

The `lupo_actors` table defines system identities. Three system actors were inserted
with `actor_source_type = 'system'`, active status, and no soft deletion:

**WOLFIE**

- `actor_type = 'ai_agent'`
- Role: Founder-Architect
- Purpose: system design, doctrine, kernel alignment
- Metadata includes default channels

**LILITH**

- `actor_type = 'ai_agent'`
- Role: Counter-Agent
- Purpose: stress-testing, contradiction detection, emotional interrogation
- Metadata includes default channels

**LUPOPEDIA**

- `actor_type = 'service'`
- Role: Semantic OS
- Purpose: system events, routing, kernel operations
- Metadata includes default channels

These names are system identifiers. Any mythic interpretation is metadata only.

## 3) Membership Layer: `lupo_actor_channels`

The `lupo_actor_channels` table binds actors to channels. Each membership row
includes:

- `actor_id`
- `channel_id`
- `status` (`A` = active)
- UI colors and notification preferences
- timestamps
- soft-delete fields

The membership script:

1. queries actor IDs
2. queries channel IDs
3. sets variables
4. inserts memberships via `INSERT IGNORE`

Current assignments:

- **WOLFIE:** 7 channels (architecture, kernel, doctrine, orchestrator)
- **LILITH:** 5 channels (emotional, inversion, stress-testing)
- **LUPOPEDIA:** 8 channels (system events, routing, kernel logs, recovery)

## 4) Required Execution Order

This order guarantees referential integrity and deterministic setup:

1. Insert system actors
2. Query actor IDs
3. Insert channel batches
4. Query channel IDs
5. Insert actor-channel memberships

## 5) Design Principles

- Idempotent migrations using `INSERT IGNORE`
- Separation of concerns: channels, actors, memberships
- Metadata-driven routing: actors store default channels in metadata
- Extensibility: add actors or channels without modifying existing rows
- Schema-aligned: inserts follow the current TOON schema

## 6) Extension and Maintenance Guidance

**When adding channels:**

- allocate a new `channel_number` with clear intent
- set `channel_key`, `channel_slug`, and `description` explicitly
- keep inserts idempotent (`INSERT IGNORE`)

**When adding actors:**

- define `actor_type` and `actor_source_type`
- include default channel metadata
- keep the actor active and non-deleted unless intentionally retired

**When adding memberships:**

- resolve actor and channel IDs first
- insert using `INSERT IGNORE`
- keep `status = 'A'` for active memberships

Schema changes must follow the canonical changelog and TOON workflow.
