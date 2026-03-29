# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/migrations/livehelp_leavemessage_migration.md"
  file_hash: "162d56d3f8d7fce6d7d4b0d1fbd9fccef96e14958d021010e3bb2eb92f0428bd"
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

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\database\lupopedia\tables\livehelp_leavemessage_migration.md"
  file_hash: "5f2d5204f298b4360a991e348b2c3692c816a00e29209d75f8e4ff99917f4ac8"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_leavemessage_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_leavemessage_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "lupo-docs/database/lupopedia/tables/livehelp_leavemessage_migration.md",
  file_hash: "1149e1a41d943b301ab6d412863cc9e076e0d192be670b7d0c0c5c11b0006d57"
  system_version: "4.0.50"
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_leavemessage â†’ lupo_crafty_syntax_leave_message",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_leavemessage", "imported"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_leavemessage", "#offline_messages", "#imported", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.70 }
}

flip.footer: {
  inbound_edges: [
    { from: "lupo-database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 1.0, hashtag: "#migration" },
    { from: "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" }
  ],
  outbound_edges: [
    { to: "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.8, hashtag: "#source" },
    { to: "app/Services/CraftySyntax/LegacyAdmin.php", type: "used_by", weight: 0.6, hashtag: "#compatibility" },
    { to: "lupo-docs/database/lupopedia/tables/active/lupo_crm_leads.md", type: "related_to", weight: 0.5, hashtag: "#offline_forms" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["lupo-database/migrations/import_from_old_crafty_syntax.sql", "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_leavemessage_mapping", "offline_forms", "leave_message", "compatibility_table"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_leavemessage
# Status: IMPORTED -> DROPPED
# Replacement: lupo_crafty_syntax_leave_message

# 1. Summary
livehelp_leavemessage was Crafty Syntax's table for storing offline "Leave a Message" form submissions. When no operator was online, visitors could fill out a form that captured:

email

subject

department

session data

a delimited blob of form fields

This was the offline inbox for Crafty Syntax.

Lupopedia replaces this with a structured table inside the Crafty Syntax module:

Code
lupo_crafty_syntax_leave_message
The legacy table is imported and then dropped.

# 2. What the Legacy Table Did
Each row represented a single offline message submission. Fields included:

email -- visitor's email

subject -- subject line

department -- department ID

sessiondata -- serialized session info

deliminated -- raw form data blob

dateof -- timestamp (YYYYMMDDHHMMSS)

Notably missing in the legacy table:

no phone

no name

no message body (Crafty Syntax stored it in the delimited blob)

no IP address

no user agent

no lifecycle fields

no assignment

no status beyond "new"

It was a minimal, early-2000s form handler.

# 3. Why It Maps to lupo_crafty_syntax_leave_message
The new table provides:

lifecycle fields

assignment

status

soft-delete

structured metadata

compatibility with the Crafty Syntax module

The migration preserves all meaningful legacy data while giving it a modern structure.

Doctrine decisions reflected in your SQL:
phone and name are set to NULL because the legacy form didn't collect them

message is set to NULL because Crafty Syntax stored the message body inside deliminated

priority = 2 (default offline priority)

status = 'new'

assigned_to = NULL

ip_address and user_agent are NULL because the legacy table didn't store them

created_ymdhis = dateof

updated_ymdhis = now()

This is exactly the right interpretation.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_leavemessage ENGINE=InnoDB;
ALTER TABLE livehelp_leavemessage CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Import into new table
Code
TRUNCATE lupo_crafty_syntax_leave_message;

INSERT INTO lupo_crafty_syntax_leave_message (...)
SELECT
    id,
    department,
    email,
    NULL,
    NULL,
    subject,
    NULL,
    2,
    sessiondata,
    deliminated,
    NULL,
    NULL,
    'new',
    NULL,
    dateof,
    <now>,
    0,
    NULL
FROM livehelp_leavemessage;
Step 4 -- Drop legacy table
Removed after migration.

# 5. Mapping Summary
Legacy -> New
Code
id            -> crafty_syntax_leave_message_id
department    -> department_id
email         -> email
subject       -> subject
sessiondata   -> session_data
deliminated   -> form_data
dateof        -> created_ymdhis
(now)         -> updated_ymdhis
Added fields
Code
phone = NULL
name = NULL
message = NULL
priority = 2
status = 'new'
assigned_to = NULL
ip_address = NULL
user_agent = NULL
is_deleted = 0
deleted_ymdhis = NULL
Dropped fields
None -- all meaningful legacy fields are preserved.

# 6. Doctrine Notes
This migration is a perfect example of:

Preserving intent, not structure
The legacy table was a minimal offline inbox.
The new table is a structured CRM-like record.

We preserve:

the submission

the department

the subject

the email

the raw form data

the session context

the timestamp

We add:

lifecycle fields

assignment

status

soft-delete

The Slope Principle
We do not attempt to parse or normalize the legacy deliminated blob.
We store it as form_data and allow the module to interpret it later.

# 7. Final Decision
Code
livehelp_leavemessage -> IMPORTED -> DROPPED
All offline messages preserved in lupo_crafty_syntax_leave_message.
Legacy table removed after migration.

