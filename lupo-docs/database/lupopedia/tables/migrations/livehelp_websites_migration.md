# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/migrations/livehelp_websites_migration.md"
  file_hash: "28829e26924d61ab4ba1a8cd4963b6c82b6ca320d31b86a0554d11674a05928d"
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
  file_path_from_root: "lupo-docs\database\lupopedia\tables\livehelp_websites_migration.md"
  file_hash: "bc299d13c3f1813e9a145fa9f95268899c5f63204f9112f3c7ffdbb41a53dd6f"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_websites_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_websites_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "lupo-docs/database/lupopedia/tables/livehelp_websites_migration.md",
  file_hash: "1b6286928a4190f080a047453a15987173055fc2d9bab88524039bf551fe57e3"
  system_version: "4.0.50"
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_websites → lupo_federation_nodes",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_websites", "imported"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_websites", "#federation", "#imported", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.72 }
}

flip.footer: {
  inbound_edges: [
    { from: "lupo-database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 1.0, hashtag: "#migration" },
    { from: "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" }
  ],
  outbound_edges: [
    { to: "lupo-docs/database/lupopedia/tables/active/lupo_federation_nodes.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" },
    { to: "app/Services/CraftySyntax/LegacyAdmin.php", type: "used_by", weight: 0.6, hashtag: "#compatibility" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["lupo-database/migrations/import_from_old_crafty_syntax.sql", "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_websites_mapping", "federation_registry", "multi_site", "imported"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_websites
# Status: IMPORTED -> DROPPED
# Replacement: lupo_federation_nodes
# Purpose: Seed the federation registry with legacy Crafty Syntax "websites"

# 1. Summary
livehelp_websites was Crafty Syntax's table for storing remote LiveHelp installations that were part of a multi-site deployment.
Each row represented:

a remote installation URL

a human-readable name

a default department for routing

a legacy livehelp_id

In Lupopedia, these become federation nodes -- the modern, doctrine-aligned representation of remote or peer installations.

The legacy table is imported into:

Code
lupo_federation_nodes
...and then dropped.

# 2. What the Legacy Table Actually Did
Each row in livehelp_websites represented:

Legacy Field	Meaning
id	Primary key
site_name	Human-readable name of the remote node
site_url	Base URL of the remote node
defaultdepartment	Legacy routing target
livehelp_id	Legacy federation identifier
This table powered:

cross-site routing

multi-site chat deployments

remote operator handoff

legacy federation logic

It was a primitive federation registry, but the concept was sound.

# 3. Why Lupopedia Uses lupo_federation_nodes
Lupopedia's federation model is:

URL-based

metadata-driven

lifecycle-aware

doctrine-aligned

extensible

safe for distributed systems

lupo_federation_nodes provides:

node_base_url

node_name

meta_json for legacy fields

lifecycle fields

soft-delete

trust levels

sync timestamps

This is a modern, durable, federated architecture.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_websites ENGINE=InnoDB;
ALTER TABLE livehelp_websites CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Add missing column to lupo_federation_nodes
Legacy Crafty Syntax stored a defaultdepartment, but the new table did not originally have a place for it.

We add:

Code
ALTER TABLE lupo_federation_nodes
    ADD COLUMN default_department_id BIGINT NULL AFTER node_base_url;
This preserves legacy routing without polluting the new schema.

Step 4 -- Import federation nodes
Code
INSERT INTO lupo_federation_nodes (
    federation_node_id,
    node_name,
    node_base_url,
    default_department_id,
    meta_json,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    id,
    site_name,
    site_url,
    defaultdepartment,
    JSON_OBJECT(
        'legacy_livehelp_id', livehelp_id
    ),
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S'),
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S'),
    0,
    NULL
FROM livehelp_websites;
This preserves:

node identity

node URL

default department

legacy federation ID

# 5. Mapping Summary
Legacy -> New
Legacy Field	New Field	Notes
id	federation_node_id	preserved
site_name	node_name	preserved
site_url	node_base_url	preserved
defaultdepartment	default_department_id	new column
livehelp_id	meta_json	preserved as metadata
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

Preserving legacy federation intent
Crafty Syntax had a primitive federation model.
Lupopedia formalizes it.

Modernizing the federation registry
We add:

lifecycle fields

metadata JSON

trust levels

sync timestamps

soft-delete

The Slope Principle
We preserve:

URLs

names

default routing

legacy IDs

We do not attempt to reinterpret:

routing semantics

department logic

legacy federation behavior

These belong to higher-level systems.

# 7. Final Decision
Code
livehelp_websites -> IMPORTED -> DROPPED
Legacy multi-site entries preserved as federation nodes.
