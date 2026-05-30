> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

﻿# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/database/lupopedia/tables/migrations/livehelp_channels_migration.md"
  file_hash: "0b21b36c1993925cb2f6eddedf496f77bf9985dbf4b0746ceaa31b9b886e134f"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  namespace: "legacy"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\livehelp_channels_migration.md"
  file_hash: "a791c48ff873873bc6087f0e578dfd052b668ccb9cb667c5daff6fd080d97009"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_channels_migration.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_channels_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/livehelp_channels_migration.md",
  file_hash: "8777e78f77dba432bc836a09e9d29b7226616d7e962d006ab2fb2e84a2ddc918"
  system_version: "4.0.50"
  channel_id: 42,
  mood_vector: "8B4513",
  purpose: "Migration doctrine for livehelp_channels â†’ dropped (replaced by real channel/thread model)",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_channels", "dropped"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_channels", "#dropped", "#channels", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.72 }
}

flip.footer: {
  inbound_edges: [
    { from: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" },
    { from: "database/migrations/import_from_old_crafty_syntax.sql", type: "documents", weight: 0.7, hashtag: "#migration" }
  ],
  outbound_edges: [
    { to: "docs/database/lupopedia/tables/active/lupo_channels.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "docs/database/lupopedia/tables/active/lupo_dialog_threads.md", type: "documents", weight: 0.9, hashtag: "#thread_model" },
    { to: "database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_channels_mapping", "dropped_table", "channel_model", "thread_model"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Doctrine Path
/docs/doctrine/migrations/livehelp_channels_migration.md

# Migration Note: livehelp_channels

# Status
DROPPED

# Replacement
None (concept replaced by Lupopedia's real channel/thread model)

# 1. What the Legacy Table Did
livehelp_channels in Crafty Syntax was not a real channel system.

It was:

- a temporary operator workspace
- created when an operator went online
- destroyed when the operator went offline
- used only to group threads the operator was handling simultaneously
- never visible to visitors
- never persisted as a meaningful entity

Key behaviors:

- Each operator had exactly one channel.
- This was their "workspace tab."
- Visitors were not on a channel.
- They only joined a channel after opening a chat.
- Multiple visitors could be on the same operator channel, each in their own thread.
- Operators saw all threads at once, each with its own background color.
- Visitors only saw messages addressed to them, not the other threads.

Example mapping from the UI:

- "support" is on one operator channel
- "Devin" and "eric" are both attached to that same channel
- but each has their own thread
- and each sees only their own conversation

This was a UI grouping mechanism, not a routing or identity mechanism.

# 2. Why It's Dropped
Lupopedia's architecture replaces this entirely:

## a. Channels are now real semantic entities
- persistent
- routable
- multi-actor
- multi-agent
- with metadata, lifecycle, and awareness

## b. Threads are first-class objects
Each visitor -> their own dialog_thread.

## c. Operators no longer need a "workspace channel"
The UI can show multiple threads without requiring a fake channel row in the database.

## d. The legacy table has no meaningful data
Because:

- channels were ephemeral
- deleted when operator logged out
- contained no durable information
- only existed to support the 2010 UI

There is nothing to import.

# 3. Migration Decision
Code
livehelp_channels -> DROPPED
No data imported.
No fields preserved.
No metadata carried forward.

Replacement Concepts:

- Operator workspace -> handled by UI, not DB
- Visitor grouping -> handled by lupo_dialog_threads
- Thread colors -> stored in thread metadata
- Channel semantics -> handled by lupo_channels (real channels)

# 4. Doctrine Notes
This is a classic example of legacy UI architecture leaking into the database.

Crafty Syntax treated "operator workspace tabs" as database channels. Lupopedia treats channels as semantic communication spaces, not UI constructs.

The correct mapping is:

- Legacy operator channel -> UI concept only
- Legacy visitor thread -> lupo_dialog_threads
- Legacy thread colors -> metadata_json on threads
- Legacy channel table -> dropped

# 5. Cross-Links
- livehelp_operator_channels_migration.md
- livehelp_messages_migration.md
- lupo_dialog_threads doctrine
- lupo_channels doctrine

