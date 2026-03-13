# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\livehelp_emails_migration.md"
  file_hash: "42b4c46208a599965ff0e2771deed852ae9db59fffdd0a134580faed0f253e8e"
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
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "docs\database\lupopedia\tables\livehelp_emails_migration.md"
  file_hash: "2bae7274b994f8a4835dea7733faec29b09f5a3a974d1190ec28aafa9da8cbb0"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_emails_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_emails_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/livehelp_emails_migration.md",
  file_hash: "5cf1a9585af4035834c862e6df6a8822afae78cb62a1841d3b75a8dbff7f51e6"
  system_version: "4.0.50"
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_emails → lupo_crm_lead_messages (broadcast lead)",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_emails", "imported"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_emails", "#crm", "#imported", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.70 }
}

flip.footer: {
  inbound_edges: [
    { from: "database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 0.9, hashtag: "#migration" },
    { from: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" }
  ],
  outbound_edges: [
    { to: "docs/doctrine/database/crm_lead_messages.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" },
    { to: "docs/doctrine/migrations/livehelp_emailque_migration.md", type: "related_to", weight: 0.6, hashtag: "#email_system" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["database/migrations/import_from_old_crafty_syntax.sql", "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_emails_mapping", "outbound_email", "broadcast_messages", "imported"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Doctrine Path
/docs/doctrine/migrations/livehelp_emails_migration.md

# Migration Note: livehelp_emails

# Status
IMPORTED -> DROPPED

# Replacement
lupo_crm_lead_messages (broadcast lead)

# 1. What the Legacy Table Did
livehelp_emails was Crafty Syntax's outbound email content table. It stored:

- the sender's email address
- the subject line
- the body text
- optional notes
- a timestamp (implicit)

This table represented messages sent by operators to the entire lead database, not one-to-one emails. It was the backend for the "Email the entire database" broadcast feature.

Key characteristics:
Every row was a broadcast email.

There was no lead_id column.
There was no actor_id or sender identity beyond fromemail.
It was not a CRM table - it was a legacy mass-mail feature.

# 2. Why It's Imported Into CRM
Lupopedia's CRM subsystem (lupo_crm_lead_messages) is the correct home for:

- message content
- sender metadata
- timestamps
- notes

Even though Crafty Syntax did not have a CRM, the closest modern equivalent is a CRM lead message.

Important Doctrine Decision
Because Crafty Syntax broadcast emails had no per-lead targeting, all imported messages are assigned to:

Code
lead_id = 1
This represents the broadcast lead, a special system lead that groups all legacy mass-mail messages.

# 3. Why the Legacy Table Is Dropped
After import:

- all meaningful data is preserved
- the table contains no additional metadata
- the table structure is obsolete
- the broadcast feature is replaced by CRM messaging

There is no reason to keep the legacy table.

# 4. Migration Behavior (as seen in the SQL)
Step 1 - Convert table for safe reading
Code
ALTER TABLE livehelp_emails ENGINE=InnoDB;
ALTER TABLE livehelp_emails CONVERT TO utf8mb4;

Step 2 - Mark as deprecated
Code
COMMENT = 'DEPRECATED...'

Step 3 - Import into CRM
Code
TRUNCATE lupo_crm_lead_messages;

INSERT INTO lupo_crm_lead_messages (...)
SELECT
    id AS crm_lead_message_id,
    1 AS lead_id,
    fromemail,
    subject,
    bodyof,
    notes,
    NULL AS actor_id,
    <timestamp>,
    <timestamp>
FROM livehelp_emails;

Step 4 - Table is dropped after migration
No further references exist.

# 5. Mapping Summary
Legacy -> New
Code
livehelp_emails.id           -> lupo_crm_lead_messages.crm_lead_message_id
livehelp_emails.fromemail    -> lupo_crm_lead_messages.from_email
livehelp_emails.subject      -> lupo_crm_lead_messages.subject
livehelp_emails.bodyof       -> lupo_crm_lead_messages.body_text
livehelp_emails.notes        -> lupo_crm_lead_messages.notes
(implicit)                   -> created_ymdhis / updated_ymdhis
(no legacy lead_id)          -> lead_id = 1 (broadcast)
(no legacy actor_id)         -> actor_id = NULL

Fields dropped
None - all meaningful fields are preserved.

Replacement
lupo_crm_lead_messages becomes the canonical store for legacy broadcast emails.

# 6. Doctrine Notes
This migration is a good example of:

Preserving meaning, not structure
The legacy table was not a CRM table, but its semantic meaning (broadcast messages) maps cleanly into the CRM subsystem.

The Slope Principle
Instead of forcing a new broadcast system immediately, we:

- preserve the legacy messages
- assign them to a special broadcast lead
- allow the new CRM module to interpret them later

This keeps the migration gentle and reversible.

# 7. Final Decision
Code
livehelp_emails -> IMPORTED -> DROPPED
All message content preserved in lupo_crm_lead_messages.
Assigned to broadcast lead (lead_id = 1).
Legacy table removed after migration.
