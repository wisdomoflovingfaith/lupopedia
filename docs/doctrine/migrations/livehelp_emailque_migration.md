---
wolfie.headers: {
  file_path_from_root: "docs/doctrine/migrations/livehelp_emailque_migration.md",
  system_version: "4.0.39",
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_emailque → dropped (mail subsystem handles delivery)",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_emailque", "dropped"],
  hashtags: ["#migration", "#crafty_syntax", "#livehelp_emailque", "#email", "#dropped", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 1, outbound_count: 2, centrality_score: 0.62 }
}

flip.footer: {
  inbound_edges: [
    { from: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" }
  ],
  outbound_edges: [
    { to: "database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" },
    { to: "docs/doctrine/migrations/livehelp_emails_migration.md", type: "related_to", weight: 0.6, hashtag: "#email_system" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_emailque_mapping", "email_queue", "dropped_table", "delivery_logging"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}
---

# Doctrine Path
/docs/doctrine/migrations/livehelp_emailque_migration.md

# Migration Note: livehelp_emailque

# Status
DROPPED

# Replacement
None (delivery logging removed; sending handled by mail subsystem)

# 1. What the Legacy Table Did
livehelp_emailque was Crafty Syntax's email delivery queue, used to track:

- which message was sent
- to which lead
- at what time
- with what subject/body
- using the old mail() function

It was not a message store.
It was not a CRM table.
It was not a communication log in the modern sense.

It existed because Crafty Syntax had:

- no asynchronous mailer
- no job queue
- no delivery tracking
- no retry logic
- no external mail subsystem

So it wrote a row for every attempted send.

Important:
This table was tightly coupled to the deprecated mail()-based delivery pipeline, which Lupopedia does not use.

# 2. Why It's Dropped
Lupopedia's architecture replaces this entirely:

## a. Email sending is handled by the global mail subsystem
- SMTP
- API mailers
- retry logic
- logging
- error handling
- queueing

None of this uses a table like livehelp_emailque.

## b. Delivery logs are not stored in the Crafty Syntax module
If delivery logging is needed, it belongs in:

- the system mailer logs
- or lupo_crm_lead_message_sends (CRM module)

But this migration script does not touch CRM, so the table is out of scope.

## c. The legacy table contains no durable business data
It only stored:

- transient delivery attempts
- ephemeral send metadata
- rows that were never meant to be permanent

There is nothing meaningful to import.

# 3. Migration Behavior (as seen in the SQL)
Step 1 - Convert table for safe reading
Code
ALTER TABLE livehelp_emailque ENGINE=InnoDB;
ALTER TABLE livehelp_emailque CONVERT TO utf8mb4;
This ensures the table can be SELECTed if needed during migration.

Step 2 - Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
This signals that the table is not part of the new schema.

Step 3 - No INSERT, no SELECT
This is the key signal:

- no data is imported
- no mapping is created
- the table is only retained temporarily for safety

Step 4 - Table is dropped after migration
No further references exist.

# 4. Mapping Summary
Legacy -> New
Code
livehelp_emailque -> DROPPED
Replacement
None in this script.

If CRM delivery logging is needed, it belongs in:

Code
lupo_crm_lead_message_sends
...but that table is outside the scope of this migration.

# 5. Doctrine Notes
This is a classic example of legacy infrastructure leaking into the database.

Crafty Syntax used a table to simulate a mail queue. Lupopedia uses:

- a real mail subsystem
- real queueing
- real delivery logs
- real retry logic

The correct mapping is:

- Legacy email queue -> removed
- Legacy delivery logs -> not imported
- Modern delivery tracking -> handled by system mailer or CRM module

# 6. Final Decision
Code
livehelp_emailque -> DROPPED
No data imported.
No replacement in this migration.
Legacy delivery queue removed.
