---
wolfie.headers: {
  file_path_from_root: "docs/doctrine/migrations/livehelp_quick_migration.md",
  system_version: "4.0.39",
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_quick → lupo_actor_reply_templates",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_quick", "imported"],
  hashtags: ["#migration", "#crafty_syntax", "#livehelp_quick", "#reply_templates", "#imported", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.74 }
}

flip.footer: {
  inbound_edges: [
    { from: "database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 1.0, hashtag: "#migration" },
    { from: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" }
  ],
  outbound_edges: [
    { to: "docs/doctrine/database/actor_reply_templates.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" },
    { to: "app/Services/CraftySyntax/LegacyAdmin.php", type: "used_by", weight: 0.6, hashtag: "#compatibility" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["database/migrations/import_from_old_crafty_syntax.sql", "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_quick_mapping", "quick_replies", "canned_messages", "reply_templates"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}
---

# Migration Note: livehelp_quick
# Status: IMPORTED -> DROPPED
# Replacement: lupo_actor_reply_templates

# 1. Summary
livehelp_quick was Crafty Syntax's table for operator quick replies -- short canned messages operators could insert into chats.

Lupopedia replaces this with the unified template system:

Code
lupo_actor_reply_templates
This table supports:

human operators

AI agents

system personas

reusable reply templates across modules

The legacy table is imported and then dropped.

# 2. What the Legacy Table Did
Each row represented a single quick reply:

id -> primary key

user -> operator ID

name -> template key

message -> template text

typeof -> usage context (e.g., chat, CRM, etc.)

This table powered:

canned chat responses

operator shortcuts

basic templating

It was simple, but it had a clear meaning.

# 3. Why It Maps Cleanly to Lupopedia
Lupopedia's lupo_actor_reply_templates is the modern, doctrine-aligned successor:

actor_reply_template_id preserves the legacy ID

actor_id preserves operator ownership

template_key preserves the name

template_text preserves the message

usage_context preserves the legacy typeof

lifecycle fields are added

soft-delete is added

This is a clean, lossless migration.

Why TRUNCATE is correct
Quick replies are:

configuration, not content

not historical

not federated

not user-generated

not meant to merge with anything

So the migration resets the table and repopulates it cleanly.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_quick ENGINE=InnoDB;
ALTER TABLE livehelp_quick CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Import into new table
Code
TRUNCATE lupo_actor_reply_templates;

INSERT INTO lupo_actor_reply_templates (...)
SELECT
    id,
    user,
    name,
    message,
    typeof,
    20250101000000,
    20250101000000,
    0,
    NULL
FROM livehelp_quick;
Step 4 -- Drop legacy table
Removed after migration.

# 5. Mapping Summary
Legacy -> New
Code
id       -> actor_reply_template_id
user     -> actor_id
name     -> template_key
message  -> template_text
typeof   -> usage_context
Added fields
Code
created_ymdhis = 20250101000000
updated_ymdhis = 20250101000000
is_deleted = 0
deleted_ymdhis = NULL
Dropped fields
None -- all meaningful legacy fields are preserved.

# 6. Doctrine Notes
This migration is a perfect example of:

Unifying legacy subsystems
Crafty Syntax had separate tables for:

quick replies

AI replies

operator templates

Lupopedia merges them into one unified system.

Preserving intent
We keep:

template ownership

template key

template text

usage context

Modernizing structure
We add:

lifecycle fields

soft-delete

consistent naming

doctrine-aligned schema

The Slope Principle
We do not reinterpret:

template keys

usage contexts

operator ownership

We preserve them exactly as they were.

# 7. Final Decision
Code
livehelp_quick -> IMPORTED -> DROPPED
Quick replies preserved in lupo_actor_reply_templates.
