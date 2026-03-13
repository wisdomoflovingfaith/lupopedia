# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\livehelp_modules_dep_migration.md"
  file_hash: "0c61e769177f93009d18d87f4960e895952f21d6148782b373fc1b906feb042c"
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
  file_path_from_root: "docs\database\lupopedia\tables\livehelp_modules_dep_migration.md"
  file_hash: "d7e93994325c06906e58156dcd941c8a6fe96c99879121f443ce9c8c88033a6a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_modules_dep_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_modules_dep_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/livehelp_modules_dep_migration.md",
  file_hash: "79393521e5f4ec2fcf87ecf09746c67aa0edc38ac64c5590d859db6036c931af"
  system_version: "4.0.50"
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_modules_dep → lupo_modules_departments",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_modules_dep", "dropped"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_modules_dep", "#module_visibility", "#dropped", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.66 }
}

flip.footer: {
  inbound_edges: [
    { from: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" },
    { from: "docs/doctrine/migrations/livehelp_modules_migration.md", type: "related_to", weight: 0.7, hashtag: "#module_system" }
  ],
  outbound_edges: [
    { to: "docs/doctrine/database/modules_departments.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" },
    { to: "docs/doctrine/migrations/livehelp_departments_migration.md", type: "related_to", weight: 0.6, hashtag: "#departments" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_modules_dep_mapping", "module_visibility", "department_modules", "dropped_table"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_modules_dep
# Status: TABLE CREATED IN LUPOPEDIA -> NO LEGACY IMPORT -> LEGACY TABLE DROPPED
# Replacement: lupo_modules_departments (modern per-department module visibility)

# 1. Summary
livehelp_modules_dep was Crafty Syntax's table for controlling which top-bar modules were visible inside the live chat window for each department:

Live Help

CRM (Contact)

Questions & Answers (Truth)

This was a UI-only routing table, not a real module system.

Lupopedia replaces this with a modern, explicit table:

Code
lupo_modules_departments
This new table allows administrators to control per-department module visibility, but no legacy data is imported because:

Lupopedia enables all public-facing modules by default

Legacy installs often had inconsistent or incomplete mappings

The old table was not semantically meaningful

The new system is cleaner, explicit, and doctrine-aligned

Therefore, the legacy table is dropped.

# 2. What the Legacy Table Did
Each row represented:

departmentid -> which department

modid -> which module (1 = Live Help, 2 = CRM, 3 = Q&A)

ordernum -> tab order

isactive -> whether the tab was shown

defaultset -> default tab

This controlled the tabs at the top of the chat window in Crafty Syntax.

It was not:

a module registry

a permission system

a configuration system

a durable data model

It was purely a UI preference table.

# 3. Why Lupopedia Does Not Import This Table
a. All public-facing modules are active by default
Lupopedia's philosophy:

If a module is installed, it is visible

Admins can disable modules per department later

No hidden or partial module states

b. Legacy mappings were inconsistent
Many Crafty Syntax installs:

never configured module visibility

had partial or broken mappings

had departments that didn't match modern modules

Importing this data would create more confusion than clarity.

c. The new table is clean and explicit
lupo_modules_departments is a modern, doctrine-aligned table:

no foreign keys

no unsigned

no display widths

lifecycle fields

soft-delete

explicit enable/disable flags

d. The legacy table contains no durable business data
It only stored UI preferences.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_modules_dep ENGINE=InnoDB;
ALTER TABLE livehelp_modules_dep CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- No import
There is:

no INSERT

no SELECT

no mapping

Step 4 -- Drop legacy table
Removed after migration.

# 5. Mapping Summary
Legacy -> New
Code
livehelp_modules_dep -> DROPPED (no import)
Replacement
Code
lupo_modules_departments
Fields preserved
None -- the legacy table is not imported.

# 6. Doctrine Notes
This migration is a perfect example of:

Preserving capability, not legacy mechanics
We keep the ability to control module visibility per department.
We do not keep the legacy table that implemented it.

Modern replacement is explicit and clean
lupo_modules_departments provides:

per-department module visibility

lifecycle fields

soft-delete

admin UI control

no foreign keys

no unsigned

no display widths

The Slope Principle
We do not attempt to interpret or import legacy UI routing rules.
We start fresh with a clean, explicit configuration model.

# 7. Final Decision
Code
livehelp_modules_dep -> DROPPED (no import)
New table lupo_modules_departments handles module visibility.
All modules enabled by default; admins may disable them later.
