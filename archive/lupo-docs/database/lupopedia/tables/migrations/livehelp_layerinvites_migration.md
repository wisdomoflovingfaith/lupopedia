# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/migrations/livehelp_layerinvites_migration.md"
  file_hash: "2ce54d921c05c75650e8db18904923314404001b016eaab04f406d47c948e68d"
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
  file_path_from_root: "lupo-docs\database\lupopedia\tables\livehelp_layerinvites_migration.md"
  file_hash: "dc2f15003ab8318a9efb6053fc9afffcef096e82fb8e064f7de5de55a74e9978"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_layerinvites_migration.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_layerinvites_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "lupo-docs/database/lupopedia/tables/livehelp_layerinvites_migration.md",
  file_hash: "56b297e20cf0be02c5b77a0de9fb79ecd29333a9568f92c68d7649390f1b6815"
  system_version: "4.0.50"
  channel_id: 42,
  mood_vector: "8B4513",
  purpose: "Migration doctrine for livehelp_layerinvites â†’ lupo_crafty_syntax_layer_invites",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_layerinvites", "imported"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_layerinvites", "#layer_invites", "#imported", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.68 }
}

flip.footer: {
  inbound_edges: [
    { from: "lupo-database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 0.9, hashtag: "#migration" },
    { from: "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" }
  ],
  outbound_edges: [
    { to: "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" },
    { to: "lupo-docs/doctrine/migrations/livehelp_autoinvite_migration.md", type: "related_to", weight: 0.6, hashtag: "#invite_system" },
    { to: "app/Services/CraftySyntax/LegacyAdmin.php", type: "used_by", weight: 0.5, hashtag: "#compatibility" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["lupo-database/migrations/import_from_old_crafty_syntax.sql", "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_layerinvites_mapping", "dhtml_invites", "floating_overlays", "compatibility_table"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_layerinvites
# Status: IMPORTED -> DROPPED
# Replacement: lupo_crafty_syntax_layer_invites (compatibility table)

# 1. Summary
livehelp_layerinvites was Crafty Syntax's system for DHTML "layer invites" -- floating image overlays that appeared on the website to invite visitors to open live chat.

These were implemented using:

absolutely positioned GIF/PNG images

HTML <map> imagemaps

JavaScript actions like openLiveHelp() and closeDHTML()

per-department branding

per-operator ownership

This system predates modern widgets and was a UI hack from the early 2000s.

Lupopedia does not use this mechanism, but the Crafty Syntax module still needs to read legacy invites for compatibility. Therefore the table is imported, normalized, and then the legacy table is dropped.

# 2. What the Legacy Table Did
Each row defined:

name -> internal layer name

imagename -> filename of the floating image

imagemap -> HTML <map> defining clickable regions

department -> department name (not ID)

user -> operator ID

no lifecycle fields

no counters

no timestamps

The invites were displayed:

when an operator was online

based on department

using JavaScript injected into the page

This was a UI-only feature, not a semantic routing or analytics system.

# 3. Why It's Imported
Even though the system is deprecated, the Crafty Syntax module in Lupopedia needs to:

display legacy invites

allow admins to view/edit them

preserve historical behavior for upgraded installations

Therefore, the migration imports the data into a clean, normalized table:

Code
lupo_crafty_syntax_layer_invites
This table is scoped to the Crafty Syntax module and does not affect the rest of Lupopedia.

# 4. Why the Legacy Table Is Dropped
After import:

all meaningful data is preserved

the legacy table structure is obsolete

the new table contains lifecycle fields

the new table is domain-scoped

the legacy JavaScript invite system is deprecated

The old table is no longer needed.

# 5. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_layerinvites ENGINE=InnoDB;
ALTER TABLE livehelp_layerinvites CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Import into new table
Code
TRUNCATE lupo_crafty_syntax_layer_invites;

INSERT INTO lupo_crafty_syntax_layer_invites (...)
SELECT
    name,
    imagename,
    imagemap,
    department,
    user,
    1 AS is_active,
    0 AS display_count,
    0 AS click_count,
    <timestamp>,
    <timestamp>,
    0 AS is_deleted,
    NULL
FROM livehelp_layerinvites;
Step 4 -- Drop legacy table
Removed after migration.

# 6. Mapping Summary
Legacy -> New
Code
name          -> layer_name
imagename     -> image_name
imagemap      -> image_map
department    -> department_name
user          -> user_id
Added fields
Code
is_active
display_count
click_count
created_ymdhis
updated_ymdhis
is_deleted
deleted_ymdhis
Dropped fields
None -- all legacy fields are preserved.

# 7. Doctrine Notes
This migration is a classic example of:

Preserving compatibility without carrying forward obsolete behavior
The legacy DHTML invite system is:

outdated

not responsive

not accessible

not compatible with modern browsers

But the data is preserved so upgraded installations behave as expected.

Modern replacement
Lupopedia uses:

widget-based invites

agent-aware routing

structured metadata

modern UI components

The legacy invites are maintained only for backward compatibility.

# 8. Final Decision
Code
livehelp_layerinvites -> IMPORTED -> DROPPED
Data preserved in lupo_crafty_syntax_layer_invites.
Legacy DHTML invite system deprecated.

