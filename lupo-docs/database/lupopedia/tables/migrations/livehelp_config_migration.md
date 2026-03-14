# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\livehelp_config_migration.md"
  file_hash: "e0a6e7e0bd63ad3b351ad6df8739f70e42fa9199f63a81479607ec292c196395"
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
  file_path_from_root: "lupo-docs\database\lupopedia\tables\livehelp_config_migration.md"
  file_hash: "56721f82d7f168c7caee0cbdfe4f61e360be23f666dacf705727aa5b45e672c7"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_config_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_config_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "lupo-docs/database/lupopedia/tables/livehelp_config_migration.md",
  file_hash: "7fa19918e1e129e82c0cc60ea6cd87d1600088737b72c716bef818c69d5ca4e8"
  system_version: "4.0.50"
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_config → lupo_modules.config_json (module_id = 1)",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_config", "partially_imported"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_config", "#configuration", "#modules", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.76 }
}

flip.footer: {
  inbound_edges: [
    { from: "lupo-database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 0.9, hashtag: "#migration" },
    { from: "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" }
  ],
  outbound_edges: [
    { to: "lupo-docs/doctrine/database/modules.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "app/Services/CraftyConfigTransformer.php", type: "used_by", weight: 0.9, hashtag: "#transformer" },
    { to: "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["lupo-database/migrations/import_from_old_crafty_syntax.sql", "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_config_mapping", "global_configuration", "module_config", "partially_imported"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Doctrine Path
/docs/doctrine/migrations/livehelp_config_migration.md

# Migration Note: livehelp_config

# Status
PARTIALLY IMPORTED -> DROPPED

# Replacement
lupo_modules.config_json (module_id = 1)

# 1. What the Legacy Table Did
livehelp_config was the global configuration table for Crafty Syntax Live Help. It stored:

- UI settings
- tracking flags
- admin refresh intervals
- chat behavior toggles
- SMTP settings
- theme and color options
- directory/search/game toggles
- analytics limits
- cookie behavior
- operator timeouts
- and dozens of other global flags

It was effectively the entire system settings registry for the old platform.

Important:
Crafty Syntax assumed exactly one row in this table. All settings were global, not per-module.

# 2. Why It's Dropped
Lupopedia replaces this with:

## a. Module-scoped configuration
Each module has:

Code
lupo_modules.config_json
This is:

- structured
- typed
- versioned
- namespaced
- future-proof
- compatible with AI agents
- compatible with doctrine-driven configuration

## b. No more global "everything in one row" settings
The monolithic config table is obsolete.

## c. Many legacy settings no longer apply
Examples:

- showgames
- showdirectory
- keywordtrack
- reftracking
- maxoldhits
- colorscheme
- floatxy

These were UI hacks or analytics hacks from 2003-2011.

## d. SMTP settings are now part of the system-wide mailer
Not module-specific.

## e. Cookie behavior is replaced by the identity helper
No more rememberusers, usecookies, matchip, etc.

# 3. Migration Behavior (as seen in the SQL)
Step 1 - Convert table for safe reading
Code
ALTER TABLE livehelp_config ENGINE=InnoDB;
ALTER TABLE livehelp_config CONVERT TO utf8mb4;
This ensures the table can be SELECTed safely during migration.

Step 2 - Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
This signals that the table is not part of the new schema.

Step 3 - Extract the single row into JSON
The SQL block:

- SELECTs the first and only row
- Converts all fields into a JSON object
- Inserts that JSON into lupo_modules.config_json
- Targets module_id = 1 (Crafty Syntax module)

This preserves:

- all legacy settings
- in a structured, readable, future-proof format
- without needing the old table

Step 4 - Table is dropped after migration
No further references exist.

# 4. Mapping Summary
Legacy -> New
Code
livehelp_config.* -> lupo_modules.config_json (module_id = 1)
Fields preserved
All fields are preserved as JSON keys.

Fields transformed
None - values are copied verbatim.

Fields dropped
None - but many will be ignored by the new module.

Replacement
lupo_modules.config_json becomes the canonical configuration store.

# 5. Doctrine Notes
This migration is a perfect example of:

The Slope Principle
Instead of rewriting or reinterpreting legacy settings, we:

- preserve them as JSON
- store them in the module
- allow the new module to interpret them gradually
- avoid breaking legacy behavior
- avoid forcing premature decisions

This is a gentle slope, not a staircase.

Human-in-loop relevance
Operators and admins will eventually configure the new module through:

- UI settings
- module metadata
- doctrine files

But the legacy settings remain available for reference.

# 6. Final Decision
Code
livehelp_config -> PARTIALLY IMPORTED -> DROPPED
All fields preserved as JSON in lupo_modules.config_json.
Table removed after migration.
