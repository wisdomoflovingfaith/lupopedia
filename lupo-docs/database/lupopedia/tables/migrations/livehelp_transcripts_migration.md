# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\livehelp_transcripts_migration.md"
  file_hash: "0803ff559665bd80c647af7f5753c6367a7bc6adc1fd500460e1b095f72d17b0"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
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
  file_path_from_root: "lupo-docs\database\lupopedia\tables\livehelp_transcripts_migration.md"
  file_hash: "1f41c94cf1c2a7b5a0797b96d540e174ad83cec400d33c3b59fcebd5d8ec04f4"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_transcripts_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_transcripts_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "lupo-docs/database/lupopedia/tables/livehelp_transcripts_migration.md",
  file_hash: "9436959d9350c004a5f7145a8f9e5fe203b0ac0a649b79dc96bf8644ef634db8"
  system_version: "4.0.50"
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_transcripts → lupo_dialog_threads/lupo_dialog_messages",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_transcripts", "imported"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_transcripts", "#dialog", "#imported", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 3, outbound_count: 4, centrality_score: 0.85 }
}

flip.footer: {
  inbound_edges: [
    { from: "lupo-database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 1.0, hashtag: "#migration" },
    { from: "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" },
    { from: "lupo-docs/doctrine/migrations/livehelp_messages_migration.md", type: "references", weight: 0.8, hashtag: "#related" }
  ],
  outbound_edges: [
    { to: "lupo-docs/doctrine/database/dialog_threads.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "lupo-docs/doctrine/database/dialog_messages.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.8, hashtag: "#source" },
    { to: "test_dialog_migration.php", type: "tested_by", weight: 0.7, hashtag: "#testing" }
  ],
  referenced_by_actors: [1001, 1002, 10000],
  references: {
    by_files: ["lupo-database/migrations/import_from_old_crafty_syntax.sql", "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 1002, 10000]
  },
  semantic_tags: ["livehelp_transcripts_mapping", "dialog_threads", "dialog_messages", "chat_history"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_transcripts
# Status: IMPORTED -> DROPPED
# Replacement:
#
# lupo_dialog_threads
#
# lupo_dialog_messages

# 1. Summary
livehelp_transcripts was Crafty Syntax's legacy table for storing entire chat transcripts as single rows.
Each row contained:

the entire transcript text

the start and end timestamps

the operator and visitor metadata (embedded in the transcript)

the session ID

the department

the email

the operator list

the serialized session data

This table was a flat, monolithic transcript store, not a message-level dialog system.

Lupopedia replaces this with the Dialog System, which separates:

threads (lupo_dialog_threads)

messages (lupo_dialog_messages)

message bodies (message_body)

metadata

mood frameworks

lifecycle fields

Each legacy transcript becomes:

one dialog thread

one dialog message containing the full transcript text

The legacy table is imported and then dropped.

# 2. What the Legacy Table Actually Did
Each row in livehelp_transcripts represented:

a full chat session

stored as a single text blob

with no message boundaries

no actor separation

no metadata normalization

no lifecycle fields

no soft-delete

no threading model

Fields included:

recno -> primary key

transcript -> full chat text

starttime / endtime -> timestamps

sessionid -> legacy session

department -> routing

email -> visitor email

operators -> comma-separated operator list

sessiondata -> serialized PHP session data

This table was a historical artifact, not a modern dialog model.

# 3. Why Lupopedia Uses Threads + Messages
Lupopedia's dialog system is:

actor-aware

message-level

federated

mood-aware

multi-agent compatible

lifecycle-aware

Legacy transcripts cannot be decomposed into individual messages without unreliable heuristics.

Therefore:

Each legacy transcript becomes:
one thread

one message containing the full transcript text

This preserves historical data without fabricating message boundaries.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_transcripts ENGINE=InnoDB;
ALTER TABLE livehelp_transcripts CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Clear dialog threads
Code
TRUNCATE lupo_dialog_threads;
Step 4 -- Import threads
Code
INSERT INTO lupo_dialog_threads (...)
SELECT
    recno,
    1,
    1,
    1,
    CONCAT(recno, ' import from crafty syntax'),
    NULL,
    starttime,
    endtime
FROM livehelp_transcripts;
This creates one thread per transcript.

Step 5 -- Clear dialog messages
Code
TRUNCATE lupo_dialog_messages;
Step 6 -- Import messages
Code
INSERT INTO lupo_dialog_messages (...)
SELECT
    recno,
    recno,
    1,
    1,
    1,
    CONCAT('Imported transcript #', recno),
    transcript,
    'text',
    NULL,
    NULL,
    'western_analytical',
    1.00,
    starttime,
    endtime,
    0,
    NULL
FROM livehelp_transcripts;
This creates one message per thread, containing the full transcript.

# 5. Mapping Summary
Legacy -> New
Legacy Field	New Field	Notes
recno	dialog_thread_id	preserved
recno	dialog_message_id	preserved
transcript	message_body	preserved
starttime	created_ymdhis	preserved
endtime	updated_ymdhis	preserved
-	summary_text	synthesized
-	message_text	synthesized
-	mood_framework	'western_analytical'
-	weight	1.00
Dropped fields (preserved elsewhere if needed)
sessionid

department

email

operators

sessiondata

These are not part of the dialog model and are not durable.

# 6. Doctrine Notes
This migration is a perfect example of:

Preserving historical data without fabricating structure
We keep:

the transcript text

the timestamps

the identity of the transcript

We do not attempt to:

split transcripts into messages

infer actors

reconstruct message boundaries

rebuild session state

Modernizing the dialog model
We add:

threads

messages

lifecycle fields

mood frameworks

soft-delete

The Slope Principle
We preserve what exists.
We do not invent what never existed.

# 7. Final Decision
Code
livehelp_transcripts -> IMPORTED -> DROPPED
Each transcript becomes one dialog thread + one dialog message.
