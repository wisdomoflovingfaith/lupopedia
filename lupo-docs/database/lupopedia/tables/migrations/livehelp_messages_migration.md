# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/migrations/livehelp_messages_migration.md"
  file_hash: "5df74e713f76318ba2147d476e1ac81dc7b341df7264a38a5618e778100ac576"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  namespace: "legacy"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\database\lupopedia\tables\livehelp_messages_migration.md"
  file_hash: "52e5b235ad1e4e5062dbc572aba67c3e322a179b85660cf4adce71a9ae613e67"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_messages_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_messages_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "lupo-docs/database/lupopedia/tables/livehelp_messages_migration.md",
  file_hash: "db84d64950ded819d9999f547695f248607c1e344feb7a46e4b3812bf1fa46b8"
  system_version: "4.0.50"
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_messages → dropped (transcripts from livehelp_transcripts)",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_messages", "dropped"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_messages", "#dropped", "#transcripts", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.70 }
}

flip.footer: {
  inbound_edges: [
    { from: "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" },
    { from: "lupo-docs/doctrine/migrations/livehelp_transcripts_migration.md", type: "references", weight: 0.8, hashtag: "#related" }
  ],
  outbound_edges: [
    { to: "lupo-docs/database/lupopedia/tables/active/lupo_dialog_messages.md", type: "documents", weight: 0.9, hashtag: "#target_table" },
    { to: "lupo-docs/doctrine/migrations/livehelp_transcripts_migration.md", type: "references", weight: 0.8, hashtag: "#durable_data" },
    { to: "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_messages_mapping", "ephemeral_messages", "dropped_table", "transcript_source"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_messages
# Status: DROPPED
# Replacement: None (durable transcripts come from livehelp_transcripts)

# 1. Summary
livehelp_messages was not the Crafty Syntax transcript table.
It stored only the individual messages of an active chat session, and only until the chat ended.

When the chat closed:

Crafty Syntax collapsed all messages into a single transcript blob
-> stored in livehelp_transcripts

Then it cleared livehelp_messages
-> leaving it empty in all normal installations

Therefore:

This table contains no durable historical data

Nothing needs to be imported

Lupopedia's dialog system replaces this entirely

# 2. What the Legacy Table Actually Did
Despite its name, livehelp_messages was a temporary message buffer.

It existed to support:

operator UI refresh

visitor UI refresh

typing indicators

message routing

per-message display before transcript collapse

Lifecycle:
Visitor + operator exchange messages

Messages accumulate in livehelp_messages

When chat ends:

Crafty Syntax concatenates all messages into one giant text blob

Stores that blob in livehelp_transcripts

Deletes all rows from livehelp_messages

This is why the table is almost always empty.

# 3. Why It's Dropped in Lupopedia
Lupopedia uses a real dialog system:

lupo_dialog_threads

lupo_dialog_messages

lupo_dialog_message_bodies

lupo_channels

These tables:

store every message atomically

preserve full transcripts

support multi-actor messaging

support metadata

support channel/thread separation

There is no conceptual mapping from the ephemeral buffer to the new system.

The only durable data -- the transcript -- comes from livehelp_transcripts, not this table.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert table for safe reading
Code
ALTER TABLE livehelp_messages ENGINE=InnoDB;
ALTER TABLE livehelp_messages CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- No import
There is:

no INSERT

no SELECT

no mapping

Because the table contains no durable data.

Step 4 -- Drop after migration
Removed once the migration completes.

# 5. Mapping Summary
Legacy -> New
Code
livehelp_messages -> DROPPED
Replacement
Code
lupo_dialog_threads
lupo_dialog_messages
lupo_dialog_message_bodies
Durable transcripts come from
Code
livehelp_transcripts -> dialog_message_bodies + dialog_messages
6. Doctrine Notes
This migration is a perfect example of:

Preserving meaning, not mechanics
We preserve:

the final transcript (from livehelp_transcripts)

We do not preserve:

the ephemeral per-message buffer

temporary routing state

UI refresh artifacts

The Slope Principle
We do not attempt to reconstruct missing per-message history.
We rely on the final transcript blob as the canonical legacy record.

7. Final Decision
Code
livehelp_messages -> DROPPED (no import)
Durable transcripts come from livehelp_transcripts.
Ephemeral message buffer removed.
