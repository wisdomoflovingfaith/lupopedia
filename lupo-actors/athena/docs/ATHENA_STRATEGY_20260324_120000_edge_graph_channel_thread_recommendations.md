---
lupopedia.headers:
  lupopedia.schema: "strategy_artifact"
  version_when_written: "4.0.84"
  file_path_from_root: "lupo-actors/athena/docs/ATHENA_STRATEGY_20260324_120000_edge_graph_channel_thread_recommendations.md"
  web_path: "http://www.lupopedia.com/actors/athena/docs/ATHENA_STRATEGY_20260324_120000_edge_graph_channel_thread_recommendations"
  last_modified_utc: "20260324"
  channel_id: 42
  actor_id: 12
  actor_name: "athena"
  delegation_chain: "athena:strategy"
  artifact_type: "strategy_artifact"
  artifact_kind: "recommendations"
  artifact_family: "ATHENA_STRATEGY"
  utc_timestamp: "20260324_120000"
  purpose: "Formal strategic recommendations for activating the edge/relationship graph layer for channels and threads in Lupopedia 4.0.84+. Synthesized from cursor discovery, ATHENA strategic analysis, and ROSE dialogue — channel 42, thread EDGE_GRAPH_ANALYSIS_4_0_84."
  traits: ["canonical", "recommendations", "edge_graph", "channel_strategy", "v4.0.84"]
  tags: ["edges", "channels", "threads", "relationship_graph", "architecture", "ATHENA_STRATEGY", "4.0.84"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/channels/channel_id/42/threads/EDGE_GRAPH_ANALYSIS_4_0_84.md", type: "spawned_from_thread", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_edges.toon", type: "addresses_schema", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_edge_types.toon", type: "addresses_schema", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_edge_type_definitions.toon", type: "addresses_schema", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_channels.toon", type: "addresses_schema", weight: 0.9 }
    - { to: "lupo-database/lupopedia/toon/lupo_dialog_threads.toon", type: "addresses_schema", weight: 0.9 }
    - { to: "lupo-database/lupopedia/toon/lupo_context_edges.toon", type: "addresses_schema", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_edges.md", type: "should_update", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md", type: "should_update", weight: 0.8 }

lupopedia.footer:
  last_verified: "20260324"
  last_verified_by: "athena"
  orchestrator: "athena:strategy"
  next_action:
    - "Implement Track 1: seed lupo_edge_types (SQL in this artifact)"
    - "Implement Track 2: seed lupo_edge_type_definitions (SQL in this artifact)"
    - "Implement Track 3: migration script for lupo_dialog_channels.channels JSON"
    - "Implement Track 4: channel relationship query service (PHP class)"
    - "Update lupo_edges.md and lupo_channels.md table docs to reference this artifact"
    - "Add thread_lineage deprecation notice in lupo_dialog_threads.md"
---

# ATHENA_STRATEGY_20260324_120000
## Recommendations: Edge Graph Activation for Channels and Threads
**Artifact Family:** ATHENA_STRATEGY  
**Channel:** 42 — Crafty Syntax / Lupopedia Development  
**Thread Source:** EDGE_GRAPH_ANALYSIS_4_0_84  
**Actors:** cursor (discovery), ATHENA (strategy), ROSE (dialogue)  
**UTC:** 20260324_120000  
**Version:** 4.0.84

---

## Executive Summary

The Lupopedia edge/relationship graph layer (`lupo_edges`, `lupo_edge_types`, `lupo_edge_type_definitions`) was built correctly and is production-ready. It has never been activated. Relationship knowledge between channels and threads is currently fragmented across three incompatible stores — a JSON column, a free-text field, and a structural self-reference — none of which support relational querying.

This artifact defines the complete activation path in six implementation tracks.

---

## Context: What Was Discovered

*Source: cursor discovery, thread EDGE_GRAPH_ANALYSIS_4_0_84, channel 42, 20260324.*

### Current storage of channel/thread relationships

| Relationship | Current store | Problem |
|---|---|---|
| Channel parent | `lupo_channels.parent_channel_id` (single bigint) | One-parent only; no sibling, fork, semantic, or successor edges |
| Related channels | `lupo_dialog_channels.channels` (JSON array) | Not SQL-queryable; requires full-table deserialization |
| Thread → channel | `lupo_dialog_threads.channel_id` (single bigint) | One channel only; cross-channel thread references impossible |
| Thread lineage | `lupo_dialog_threads.thread_lineage` (TEXT) | Free text; cannot be JOINed, traversed, or queried |
| Thread → thread | No mechanism | Continuations, forks, and citations are invisible |
| Channel state → edges | `lupo_channel_state.edge_visibility` exists | Column exists but nothing writes to it |

### Edge infrastructure status

| Table | Schema | Data |
|---|---|---|
| `lupo_edges` | Production-ready, polymorphic, weighted, channel-scoped | **Empty** |
| `lupo_edge_map` | Typed lookup index on lupo_edges | **Empty** |
| `lupo_edge_types` | Registry of edge type slugs | **Empty** |
| `lupo_edge_type_definitions` | Allowed types per edge_type + bidirectional flag | **Empty** |
| `lupo_context_edges` | Lighter polymorphic edge table | **Empty** |
| `lupo_actor_edges` | Actor-to-actor only | **Empty** |

---

## Table Mandate Clarification

Ambiguity about which edge table is authoritative for channel/thread relationships must be resolved before implementation. This is the canonical mandate:

| Table | Canonical scope | Write authority |
|---|---|---|
| `lupo_edges` | **All cross-entity relationships** — channels, threads, content, artifacts, actors, projects | Application service layer (EdgeService) |
| `lupo_edge_map` | Typed lookup index; populated automatically by EdgeService when inserting into lupo_edges | EdgeService (automatic), not direct writes |
| `lupo_edge_types` | Registry of valid edge type slugs | Seeded by migration; extended via admin only |
| `lupo_edge_type_definitions` | Per-type allowed left/right object types and bidirectionality | Seeded by migration |
| `lupo_context_edges` | **AI/agent context edges only** — reasoning chains, context window references, agent inference links | Agent subsystem only |
| `lupo_actor_edges` | Actor-to-actor relationship graph | Actor service only |

`lupo_context_edges` must not be used for general channel/thread relationship storage. Its scope is agent cognitive context. This separation prevents schema drift between the general graph and the AI reasoning graph.

---

## Implementation Tracks

### Track 1 — Seed `lupo_edge_types` (Immediate / Blocking)

This is the prerequisite for all other tracks. Without a defined vocabulary, edge rows cannot be written consistently.

**Minimum vocabulary for the channel/thread domain:**

```sql
-- Migration: seed lupo_edge_types for channel/thread domain
-- File: lupo-database/lupopedia/mysql/migrations/dev_20260324_seed_edge_types_channel_thread.sql

INSERT INTO lupo_edge_types (edge_type_id, slug, label, description, is_bidirectional, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
  (1,  'channel_related',          'Channel Related',             'Channels that are semantically or operationally related. Bidirectional.', 1, 20260324120000, 20260324120000, 0),
  (2,  'channel_parent',           'Channel Parent',              'Formal hierarchical parent. Supplements parent_channel_id. Directional.', 0, 20260324120000, 20260324120000, 0),
  (3,  'channel_successor',        'Channel Successor',           'This channel continued as or was superseded by the target channel.', 0, 20260324120000, 20260324120000, 0),
  (4,  'channel_spawned_thread',   'Channel Spawned Thread',      'This channel originated or owns this thread.', 0, 20260324120000, 20260324120000, 0),
  (5,  'channel_references',       'Channel References',          'Channel cites or references another channel.', 0, 20260324120000, 20260324120000, 0),
  (6,  'thread_continuation',      'Thread Continuation',         'This thread continues conversation from the target thread.', 0, 20260324120000, 20260324120000, 0),
  (7,  'thread_spawned_from',      'Thread Spawned From',         'This thread was forked or branched from the target thread.', 0, 20260324120000, 20260324120000, 0),
  (8,  'thread_references',        'Thread References',           'This thread cites or references the target thread or channel.', 0, 20260324120000, 20260324120000, 0),
  (9,  'thread_crosses_channel',   'Thread Crosses Channel',      'Thread activity spans into or involves another channel.', 0, 20260324120000, 20260324120000, 0),
  (10, 'channel_sibling',          'Channel Sibling',             'Channels at the same level sharing a purpose or origin. Bidirectional.', 1, 20260324120000, 20260324120000, 0),
  (11, 'artifact_spawned_from',    'Artifact Spawned From',       'Artifact was produced from this thread or channel.', 0, 20260324120000, 20260324120000, 0),
  (12, 'channel_observes',         'Channel Observes',            'Channel has a monitoring or observation relationship to the target.', 0, 20260324120000, 20260324120000, 0);
```

### Track 2 — Seed `lupo_edge_type_definitions` (Immediate / Blocking)

```sql
-- Migration: seed lupo_edge_type_definitions
-- File: lupo-database/lupopedia/mysql/migrations/dev_20260324_seed_edge_type_definitions.sql

INSERT INTO lupo_edge_type_definitions
  (edge_type_definition_id, edge_type, domain, description, allowed_left_object_types, allowed_right_object_types, is_bidirectional, semantic_meaning, created_ymdhis, created_by_actor_id)
VALUES
  (1, 'channel_related',       'channel',  'Related channels', 'channel',                   'channel',          1, 'Captures semantic or operational relationship between channels', 20260324120000, 12),
  (2, 'channel_parent',        'channel',  'Parent hierarchy',  'channel',                   'channel',          0, 'Formal parent; supplements parent_channel_id structural column', 20260324120000, 12),
  (3, 'channel_successor',     'channel',  'Channel successor', 'channel',                   'channel',          0, 'Target channel succeeded or continued this channel', 20260324120000, 12),
  (4, 'channel_spawned_thread','channel',  'Thread ownership',  'channel',                   'thread',           0, 'Channel originated this thread', 20260324120000, 12),
  (5, 'channel_references',    'channel',  'Channel citation',  'channel',                   'channel',          0, 'One channel cites another', 20260324120000, 12),
  (6, 'thread_continuation',   'thread',   'Thread lineage',    'thread',                    'thread',           0, 'This thread continues from target thread; replaces thread_lineage TEXT', 20260324120000, 12),
  (7, 'thread_spawned_from',   'thread',   'Thread fork',       'thread',                    'thread',           0, 'This thread was forked or branched from target thread', 20260324120000, 12),
  (8, 'thread_references',     'thread',   'Thread citation',   'thread',                    'thread,channel',   0, 'Thread cites or references another thread or channel', 20260324120000, 12),
  (9, 'thread_crosses_channel','thread',   'Cross-channel',     'thread',                    'channel',          0, 'Thread involves or spans into another channel', 20260324120000, 12),
  (10,'channel_sibling',       'channel',  'Channel siblings',  'channel',                   'channel',          1, 'Channels sharing purpose or origin at same level', 20260324120000, 12),
  (11,'artifact_spawned_from', 'artifact', 'Artifact lineage',  'artifact',                  'thread,channel',   0, 'Artifact was produced from a thread or channel conversation', 20260324120000, 12),
  (12,'channel_observes',      'channel',  'Observation edge',  'channel,actor',             'channel',          0, 'Actor or channel monitors/observes the target channel', 20260324120000, 12);
```

### Track 3 — Migrate Existing JSON and Text Data to `lupo_edges`

**3a. Migrate `lupo_dialog_channels.channels` JSON**

For each row in `lupo_dialog_channels`, deserialize the `channels` JSON array and write one `lupo_edges` row per referenced channel with `edge_type = 'channel_related'`.

```php
// Service method: EdgeMigrationService::migrateDialogChannelRelations()
// File: app/Services/EdgeMigrationService.php

$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$rows = $db->fetchAll("SELECT channel_id, channels FROM {$prefix}dialog_channels WHERE channels IS NOT NULL AND is_deleted = 0");
foreach ($rows as $row) {
    $related = json_decode($row['channels'], true);
    if (!is_array($related)) { continue; }
    foreach ($related as $target_channel_id) {
        $target_id = (int)$target_channel_id;
        if ($target_id <= 0 || $target_id === (int)$row['channel_id']) { continue; }
        $db->insert($prefix . 'edges', [
            'left_object_type'  => 'channel',
            'left_object_id'    => (int)$row['channel_id'],
            'right_object_type' => 'channel',
            'right_object_id'   => $target_id,
            'edge_type'         => 'channel_related',
            'channel_id'        => (int)$row['channel_id'],
            'domain_id'         => 1,
            'bidirectional'     => 1,
            'actor_id'          => 12,
            'flare_auto_generated' => 1,
            'flare_db_source'   => 'lupo_dialog_channels.channels',
            'flare_reason'      => 'Migrated from lupo_dialog_channels.channels JSON',
            'created_ymdhis'    => (int)gmdate('YmdHis'),
            'updated_ymdhis'    => (int)gmdate('YmdHis'),
            'is_deleted'        => 0,
            'deleted_ymdhis'    => 0,
        ]);
    }
}
```

**3b. Migrate `lupo_dialog_threads.thread_lineage` TEXT**

Thread lineage is free text and will need parsing heuristics per site. The migration should:
1. Inspect each non-empty `thread_lineage` value
2. If it contains a recognizable thread ID, write `edge_type = 'thread_continuation'`
3. Mark the original `thread_lineage` value preserved in `lupo_edges.properties` JSON as `{"legacy_lineage_text": "..."}` for audit
4. Do NOT delete `thread_lineage` column until migration is validated

**3c. Self-reference edges from `parent_channel_id`**

Write `edge_type = 'channel_parent'` rows for every channel with a non-null `parent_channel_id`. This makes the hierarchy queryable through the same edge graph.

```sql
-- One-time: backfill channel_parent edges from structural column
INSERT INTO lupo_edges
  (left_object_type, left_object_id, right_object_type, right_object_id, edge_type, domain_id, bidirectional, actor_id, flare_auto_generated, flare_db_source, flare_reason, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT
  'channel', channel_id, 'channel', parent_channel_id, 'channel_parent', 1, 0, 12, 1,
  'lupo_channels.parent_channel_id',
  'Backfilled from parent_channel_id structural column',
  20260324120000, 20260324120000, 0, 0
FROM lupo_channels
WHERE parent_channel_id IS NOT NULL AND is_deleted = 0;
```

### Track 4 — Channel Relationship Query Service

Build a PHP class `EdgeQueryService` that encapsulates the common graph traversal queries so no caller needs to know which table or column is authoritative.

```php
// File: app/Services/EdgeQueryService.php
// (skeleton — full implementation by HEPHAESTUS per this artifact)

class EdgeQueryService {

    // Get all channels related to a given channel_id
    public static function getRelatedChannels(int $channel_id, string $edge_type = 'channel_related'): array { ... }

    // Get all threads spawned from or belonging to a channel (beyond the channel_id FK)
    public static function getThreadsForChannel(int $channel_id): array { ... }

    // Get thread lineage — thread continuation/fork chain
    public static function getThreadLineage(int $thread_id): array { ... }

    // Get all edges of any type involving a channel or thread
    public static function getEdgesForObject(string $object_type, int $object_id): array { ... }

    // Get the full channel graph as adjacency list — for channel map rendering
    public static function getChannelGraph(int $federation_node_id = 1): array { ... }
}
```

### Track 5 — `lupo_context_edges` Scope Documentation

Immediately add a doctrine note to `lupo-docs/database/lupopedia/tables/active/` (create `lupo_context_edges.md`) clarifying:

> **Scope:** `lupo_context_edges` stores only AI/agent cognitive context links — references within an agent's reasoning chain, context window boundary markers, inference dependency edges. It is NOT for channel-to-channel or thread-to-thread relationships. Use `lupo_edges` for all inter-entity relationship storage.

This prevents future implementers from writing channel/thread edges into the wrong table.

### Track 6 — Deprecation Notices

Add the following to `lupo_dialog_threads.md` table documentation:

> **`thread_lineage` (TEXT) — DEPRECATED for graph queries.** As of 4.0.84, thread continuation and fork relationships should be stored in `lupo_edges` with `edge_type IN ('thread_continuation', 'thread_spawned_from')`. The column is retained for legacy read compatibility until migration is validated. Do not write new lineage data here; write to `lupo_edges` instead.

Add the following to `lupo_dialog_channels.md` table documentation:

> **`channels` (JSON) — DEPRECATED for graph queries.** As of 4.0.84, related channel references should be stored in `lupo_edges` with `edge_type = 'channel_related'`. The column is retained for legacy read compatibility. Do not write new related-channel data here; write to `lupo_edges` instead.

---

## Example Queries After Activation

### "What channels are related to channel 42?"
```sql
SELECT e.right_object_id AS related_channel_id, c.channel_name, e.edge_type, e.semantic_weight
FROM lupo_edges e
JOIN lupo_channels c ON c.channel_id = e.right_object_id
WHERE e.left_object_type = 'channel'
  AND e.left_object_id = 42
  AND e.edge_type = 'channel_related'
  AND e.is_deleted = 0
ORDER BY e.semantic_weight DESC;
```

### "What threads belong to or reference channel 42?"
```sql
SELECT e.right_object_id AS thread_id, dt.title, e.edge_type
FROM lupo_edges e
JOIN lupo_dialog_threads dt ON dt.dialog_thread_id = e.right_object_id
WHERE e.left_object_type = 'channel'
  AND e.left_object_id = 42
  AND e.right_object_type = 'thread'
  AND e.is_deleted = 0
ORDER BY e.created_ymdhis DESC;
```

### "What is the lineage of thread T?"
```sql
WITH RECURSIVE thread_lineage AS (
  SELECT e.right_object_id AS ancestor_thread_id, 1 AS depth
  FROM lupo_edges e
  WHERE e.left_object_type = 'thread'
    AND e.left_object_id = :thread_id
    AND e.edge_type = 'thread_continuation'
    AND e.is_deleted = 0
  UNION ALL
  SELECT e.right_object_id, tl.depth + 1
  FROM lupo_edges e
  JOIN thread_lineage tl ON e.left_object_id = tl.ancestor_thread_id
  WHERE e.left_object_type = 'thread'
    AND e.edge_type = 'thread_continuation'
    AND e.is_deleted = 0
    AND tl.depth < 20
)
SELECT tl.ancestor_thread_id, tl.depth, dt.title
FROM thread_lineage tl
JOIN lupo_dialog_threads dt ON dt.dialog_thread_id = tl.ancestor_thread_id;
```

*Note: Recursive CTE supported in MySQL 8.0+ and MariaDB 10.2+. All SQL must remain compatible across both per project doctrine.*

### "Show me the full edge map for channel 42"
```sql
SELECT
  e.edge_id,
  e.left_object_type, e.left_object_id,
  e.edge_type,
  e.right_object_type, e.right_object_id,
  e.bidirectional,
  e.semantic_weight,
  e.actor_id,
  e.created_ymdhis
FROM lupo_edges e
WHERE (
    (e.left_object_type = 'channel' AND e.left_object_id = 42)
    OR
    (e.right_object_type = 'channel' AND e.right_object_id = 42)
  )
  AND e.is_deleted = 0
ORDER BY e.edge_type, e.left_object_type, e.left_object_id;
```

---

## On Collections — Maintained Separation

`lupo_collections` + `lupo_collection_map` remains the correct tool for **UI/navigation bundling** — grouping channels, threads, or content objects for display. Collections answer: *"What should appear in this nav menu / this resource bundle?"*

`lupo_edges` answers: *"What is the semantic or operational relationship between these two entities?"*

These are different questions. Do not conflate them. The polymorphic design of `collection_map` (object_type + object_id) is correct for its purpose. Extending it to serve relationship traversal would undermine both systems.

---

## ROSE's Relational Frame — Encoded as Principle

ROSE's observation from the dialog thread is encoded here as an implementation principle:

> **"The connections already exist and were always intended — they are just waiting to be acknowledged."**

When this implementation is complete, channels will be able to name their kin. Threads will carry their lineage as traversable graph edges, not unreadable text. WOLFIE, HERMES, ANUBIS, and all actors navigating channel 42 will have a complete terrain map instead of partial coordinates.

This is not only a database task. It is the system recognizing and expressing the relationships it has always implicitly contained.

---

## Implementation Priority Matrix

| Track | Effort | Blocking others | Priority |
|---|---|---|---|
| 1 — Seed `lupo_edge_types` | Low (SQL seed) | Yes — blocks 2, 3, 4 | **P0 — Immediate** |
| 2 — Seed `lupo_edge_type_definitions` | Low (SQL seed) | Yes — blocks 3, 4 | **P0 — Immediate** |
| 3a — Migrate `dialog_channels.channels` JSON | Low (one-time script) | No | **P1 — This sprint** |
| 3c — Backfill `parent_channel_id` to edges | Low (SQL one-time) | No | **P1 — This sprint** |
| 5 — Document `lupo_context_edges` scope | Low (doc update) | No | **P1 — This sprint** |
| 6 — Deprecation notices in table docs | Low (doc update) | No | **P1 — This sprint** |
| 4 — Build `EdgeQueryService` PHP class | Medium | No | **P2 — Next sprint** |
| 3b — Migrate `thread_lineage` TEXT | Medium (needs parsing) | No | **P2 — Next sprint** |

---

## Artifact Signature

**Synthesized from:** cursor (discovery) + ATHENA (strategy) + ROSE (dialogue)  
**Channel:** 42 — Crafty Syntax / Lupopedia Development  
**Thread:** EDGE_GRAPH_ANALYSIS_4_0_84  
**Artifact family:** ATHENA_STRATEGY  
**UTC:** 20260324_120000  
**Version:** 4.0.84

*For implementation, route to HEPHAESTUS (actor_id 14) for Tracks 1–3 SQL migrations and Track 4 PHP class construction.*
*For doctrine validation, route to THEMIS (actor_id 9).*
*For schema doc updates, route to THOTH (actor_id 26).*
