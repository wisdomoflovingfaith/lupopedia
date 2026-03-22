# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/migrations/livehelp_operator_departments_migration.md"
  file_hash: "ba209f68f19c1bc9d73d306203e5dbb5ee73fce2396283077f1dcd41511a44fd"
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
  file_path_from_root: "lupo-docs\database\lupopedia\tables\livehelp_operator_departments_migration.md"
  file_hash: "1a42ce1c2c15a6926fc10a0c7cf0ddba0f5ec8c18dd5412d3cd241adb445f4e8"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_operator_departments_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_operator_departments_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "lupo-docs/database/lupopedia/tables/livehelp_operator_departments_migration.md",
  file_hash: "aaa88fb07ba3caa0a2292c348b45b42d47b3c83a84560094ad4338d33e1f9fb7"
  system_version: "4.0.50"
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_operator_departments → lupo_actor_departments",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_operator_departments", "imported"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_operator_departments", "#actor_departments", "#imported", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 3, outbound_count: 4, centrality_score: 0.80 }
}

flip.footer: {
  inbound_edges: [
    { from: "lupo-database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 1.0, hashtag: "#migration" },
    { from: "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" },
    { from: "lupo-docs/doctrine/migrations/livehelp_departments_migration.md", type: "references", weight: 0.8, hashtag: "#related" }
  ],
  outbound_edges: [
    { to: "lupo-docs/database/lupopedia/tables/active/lupo_actor_departments.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "lupo-docs/doctrine/migrations/livehelp_departments_migration.md", type: "references", weight: 0.9, hashtag: "#related" },
    { to: "lupo-docs/doctrine/migrations/operator_to_roles_migration.md", type: "references", weight: 0.8, hashtag: "#permissions" },
    { to: "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" }
  ],
  referenced_by_actors: [1001, 1002, 10000],
  references: {
    by_files: ["lupo-database/migrations/import_from_old_crafty_syntax.sql", "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 1002, 10000]
  },
  semantic_tags: ["livehelp_operator_departments_mapping", "actor_departments", "department_membership", "imported"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_operator_departments
# Status: IMPORTED -> DROPPED
# Replacement: lupo_actor_departments

# 1. Summary
livehelp_operator_departments was Crafty Syntax's table for mapping operators to departments. It was one of the few legacy tables with a clean, durable meaning:

which operator belonged to which department

optional "title" or role label

no lifecycle fields

no soft-delete

no timestamps

Lupopedia replaces this with the modern, doctrine-aligned table:

Code
lupo_actor_departments
This table supports:

actor -> department membership

lifecycle fields

soft-delete

metadata

consistent naming

clean schema

The legacy table is imported and then dropped.

# 2. What the Legacy Table Did
Each row represented:

recno -> primary key

user_id -> operator ID

department -> department ID

extra -> optional title or role label

This table powered:

operator routing

department-based permissions

department-based chat assignment

admin UI grouping

It was one of the few Crafty Syntax tables with a stable conceptual meaning.

# 3. Why It Maps Cleanly to Lupopedia
Lupopedia's lupo_actor_departments is the direct conceptual successor:

actor_id replaces user_id

department_id is unchanged

title preserves the legacy extra field

lifecycle fields are added

soft-delete is added

timestamps are added

This is a clean, lossless migration.

Doctrine decisions reflected in your SQL:
actor_department_id = recno (preserves legacy primary key)

title = extra (best semantic match)

created_ymdhis and updated_ymdhis set to now

is_deleted = 0

deleted_ymdhis = NULL

No data is lost.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_operator_departments ENGINE=InnoDB;
ALTER TABLE livehelp_operator_departments CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Import into new table
Code
TRUNCATE lupo_actor_departments;

INSERT INTO lupo_actor_departments (...)
SELECT
    recno,
    user_id,
    department,
    extra,
    <now>,
    <now>,
    0,
    NULL
FROM livehelp_operator_departments;
Step 4 -- Drop legacy table
Removed after migration.

# 5. Mapping Summary
Legacy -> New
Code
recno       -> actor_department_id
user_id     -> actor_id
department  -> department_id
extra       -> title
Added fields
Code
created_ymdhis = now
updated_ymdhis = now
is_deleted = 0
deleted_ymdhis = NULL
Dropped fields
None -- all meaningful legacy fields are preserved.

# 6. Doctrine Notes
This migration is a perfect example of:

A clean, durable mapping
Unlike many Crafty Syntax tables, this one had a stable meaning and maps directly into Lupopedia's actor system.

Preserving identity
Keeping recno as actor_department_id ensures:

reversible migration

traceability

heritage-safe imports

Modernizing structure
Lupopedia adds:

lifecycle fields

soft-delete

consistent naming

doctrine-aligned schema

# 7. Final Decision
Code
livehelp_operator_departments -> IMPORTED -> DROPPED
All operator-department mappings preserved in lupo_actor_departments.
