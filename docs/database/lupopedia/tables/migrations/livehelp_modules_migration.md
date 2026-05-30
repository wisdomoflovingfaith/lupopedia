# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/database/lupopedia/tables/migrations/livehelp_modules_migration.md"
  file_hash: "8a05d98dcc2f005bb403f3af28a751891b04e3c330fe79d9f6f57f899b1723ed"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  namespace: "legacy"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\livehelp_modules_migration.md"
  file_hash: "ade915b9ed815bb2bfb4d58669eb0bb9b4245023708793b0733d07f7e9c2e1fd"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_modules_migration.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_modules_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/livehelp_modules_migration.md",
  file_hash: "da3fd7b66e1fdb9a5c482872321880d3a6f7d9cd33abd87e6060127b5b9208f0"
  system_version: "4.0.50"
  channel_id: 42,
  mood_vector: "8B4513",
  purpose: "Migration doctrine for livehelp_modules â†’ lupo_modules (predefined registry)",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_modules", "dropped"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_modules", "#module_system", "#dropped", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.72 }
}

flip.footer: {
  inbound_edges: [
    { from: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" },
    { from: "database/migrations/import_from_old_crafty_syntax.sql", type: "documents", weight: 0.7, hashtag: "#migration" }
  ],
  outbound_edges: [
    { to: "docs/doctrine/DATABASE_DOCTRINE.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" },
    { to: "includes/modules/module-loader.php", type: "replaced_by", weight: 0.8, hashtag: "#module_system" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_modules_mapping", "module_registry", "dropped_table", "predefined_modules"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_modules
# Status: DROPPED
# Replacement: lupo_modules (predefined module registry)

# 1. Summary
livehelp_modules was Crafty Syntax's early attempt at a "module system," but it was not a real modular architecture. It stored:

a module ID

a module name

a file path

...and nothing else.

It was used only to populate the "Modules/Tabs" section of the old admin UI.
It had no lifecycle, no versioning, no metadata, no routing, and no schema.

Lupopedia replaces this entirely with a real module registry:

Code
lupo_modules
which contains:

predefined module IDs

module types

versioning

config JSON

lifecycle fields

actor ownership

federation awareness

Because Lupopedia already defines the canonical module list, the legacy table is not imported and is dropped after migration.

# 2. What the Legacy Table Did
Crafty Syntax used livehelp_modules to:

show which "tabs" were available in the admin UI

enable/disable certain features

load module PHP files

It was not:

a dependency system

a plugin system

a package registry

a module lifecycle manager

It was essentially a UI menu list.

Legacy module IDs (for historical reference only)
1 -> Crafty Syntax (Live Help)

2 -> Leads

3 -> Questions & Answers

These IDs are preserved in Lupopedia's predefined module registry.

# 3. Why It's Dropped
Lupopedia's module system is:

predefined

versioned

typed

documented

schema-aware

doctrine-aligned

The legacy table:

contains no durable data

contains no configuration

contains no metadata

is redundant with lupo_modules

is not used by any modern subsystem

Therefore:

no fields are imported

no mapping is created

the table is dropped

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert table for safe reading
Code
ALTER TABLE livehelp_modules ENGINE=InnoDB;
ALTER TABLE livehelp_modules CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- No import
There is:

no INSERT

no SELECT

no mapping

Step 4 -- Drop after migration
Removed once the migration completes.

# 5. Mapping Summary
Legacy -> New
Code
livehelp_modules -> DROPPED
Replacement
Code
lupo_modules (predefined module registry)
Fields preserved
None.

# 6. Doctrine Notes
This migration is a perfect example of:

Replacing a placeholder with a real subsystem
Crafty Syntax's module table was a UI artifact.
Lupopedia's module registry is a real architectural component.

The Slope Principle
We do not attempt to "import" or "interpret" the legacy module list.
We rely on Lupopedia's canonical module definitions.

# 7. Final Decision
Code
livehelp_modules -> DROPPED (no import)
Module definitions come from lupo_modules.
Legacy table removed.

