# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\livehelp_departments_migration.md"
  file_hash: "b6c89b5062c049b265bdb6c0d8cc282e4124e9edaa8a1b5a215afddbd97cf611"
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
  file_path_from_root: "lupo-docs\database\lupopedia\tables\livehelp_departments_migration.md"
  file_hash: "19905e29c5a2c895af72c9cf57353f7341b463f522005578c44e653cbffa3611"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_departments_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_departments_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "lupo-docs/database/lupopedia/tables/livehelp_departments_migration.md",
  file_hash: "eb3e4a2fb7bf357782c96bad7fb4b69ca5fa549c1c1caa8d979f8b590e775819"
  system_version: "4.0.50"
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_departments → lupo_departments/lupo_department_metadata",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_departments", "imported"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_departments", "#departments", "#imported", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 3, outbound_count: 4, centrality_score: 0.82 }
}

flip.footer: {
  inbound_edges: [
    { from: "lupo-database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 1.0, hashtag: "#migration" },
    { from: "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" },
    { from: "install.php", type: "uses", weight: 0.7, hashtag: "#installer" }
  ],
  outbound_edges: [
    { to: "lupo-docs/doctrine/database/departments.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "lupo-docs/doctrine/migrations/livehelp_operator_departments_migration.md", type: "references", weight: 0.9, hashtag: "#related" },
    { to: "lupo-docs/doctrine/migrations/operator_to_roles_migration.md", type: "references", weight: 0.8, hashtag: "#permissions" },
    { to: "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" }
  ],
  referenced_by_actors: [1001, 1002, 10000],
  references: {
    by_files: ["lupo-database/migrations/import_from_old_crafty_syntax.sql", "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 1002, 10000]
  },
  semantic_tags: ["livehelp_departments_mapping", "department_system", "imported_split", "routing"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Doctrine Path
/docs/doctrine/migrations/livehelp_departments_migration.md

# Migration Note: livehelp_departments

# Status
IMPORTED -> SPLIT -> DROPPED

# Replacement
- lupo_departments
- lupo_department_metadata

# 1. What the Legacy Table Did
livehelp_departments was Crafty Syntax's attempt at a "department" system, but in practice it was:

- a routing group for operators
- a branding container for images, colors, and themes
- a behavior configuration bucket (timeouts, invites, email settings)
- a UI customization store
- a language selector
- a website-scoped grouping mechanism

All of this was jammed into a single table with dozens of unrelated columns.

Key characteristics:
Each row represented a "department" in name only.

Many fields were UI-only (colors, backgrounds, images).
Some fields were behavior toggles (timeout, require name).
Some fields were branding (online/offline images).
Some fields were routing (website -> federation node).
Some fields were analytics leftovers.

It was a kitchen-sink table, not a clean domain object.

# 2. Why It's Split in Lupopedia
Lupopedia separates concerns cleanly:

## a. Core department identity -> lupo_departments
This table stores:

- department_id
- federation_node_id
- name
- description
- department_type
- default_actor_id
- lifecycle fields

This is the semantic department.

## b. All legacy UI/behavior settings -> lupo_department_metadata
Everything that was:

- UI
- branding
- colors
- images
- toggles
- behavior flags
- theme settings

is moved into a single JSON metadata object.

This preserves the legacy meaning without polluting the core schema
