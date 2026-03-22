# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/migrations/livehelp_autoinvite_migration.md"
  file_hash: "47530f39b8cf08abee1d8ccf91447ba376b65958268918c2dc548bf6b4137363"
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
  file_path_from_root: "lupo-docs\database\lupopedia\tables\livehelp_autoinvite_migration.md"
  file_hash: "5c485d1a05c5c3f5e99872127bceaccbd501dcd7e68a6947a329979b5839ce0d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_autoinvite_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_autoinvite_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "lupo-docs/database/lupopedia/tables/livehelp_autoinvite_migration.md",
  file_hash: "c3b2eb68db60489e02856717d1a5a7212122dbaf347ca126016aff48a6bcd5b5"
  system_version: "4.0.50"
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_autoinvite → lupo_crafty_syntax_auto_invite",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_autoinvite", "imported"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_autoinvite", "#auto_invite", "#imported", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.72 }
}

flip.footer: {
  inbound_edges: [
    { from: "lupo-database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 1.0, hashtag: "#migration" },
    { from: "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" }
  ],
  outbound_edges: [
    { to: "lupo-docs/database/lupopedia/tables/active/lupo_crafty_syntax_auto_invite.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.8, hashtag: "#source" },
    { to: "app/Services/CraftySyntax/LegacyAdmin.php", type: "used_by", weight: 0.6, hashtag: "#compatibility" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["lupo-database/migrations/import_from_old_crafty_syntax.sql", "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_autoinvite_mapping", "auto_invite_rules", "compatibility_table", "imported"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_autoinvite
# Status: IMPORTED -> DROPPED
# Replacement: lupo_crafty_syntax_auto_invite

# 1. Summary
livehelp_autoinvite stored Crafty Syntax auto-invite rules used to trigger chat invitations.
The data is imported into a normalized compatibility table and the legacy table is dropped.

# 2. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_autoinvite ENGINE=InnoDB;
ALTER TABLE livehelp_autoinvite CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Import into new table
Code
TRUNCATE lupo_crafty_syntax_auto_invite;

INSERT INTO lupo_crafty_syntax_auto_invite (...)
SELECT
    idnum,
    offline,
    isactive,
    department,
    message,
    page,
    visits,
    referer,
    typeof,
    seconds,
    user_id,
    socialpane,
    excludemobile,
    onlymobile,
    20250101000000,
    20250101000000,
    0,
    NULL
FROM livehelp_autoinvite;
Step 4 -- Drop legacy table
Removed after migration.

# 3. Mapping Summary
Legacy -> New
Code
idnum        -> crafty_syntax_auto_invite_id
offline      -> is_offline
isactive     -> is_active
department   -> department_id
message      -> message
page         -> page_url
visits       -> visits
referer      -> referrer_url
typeof       -> invite_type
seconds      -> trigger_seconds
user_id      -> operator_user_id
socialpane   -> show_socialpane
excludemobile -> exclude_mobile
onlymobile   -> only_mobile
Added fields
Code
created_ymdhis = 20250101000000
updated_ymdhis = 20250101000000
is_deleted = 0
deleted_ymdhis = NULL
