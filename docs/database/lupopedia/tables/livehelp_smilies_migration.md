# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\livehelp_smilies_migration.md"
  file_hash: "427f0037c25a2765441fa49b431973f6ea2e803ab067d3a04c1f6b75d31c8beb"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_smilies_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_smilies_migrationmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
flare.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/livehelp_smilies_migration.md",
  system_version: "4.0.39",
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_smilies → dropped (emoji directory + inline tokens)",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_smilies", "dropped"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_smilies", "#emoji", "#dropped", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 1, outbound_count: 2, centrality_score: 0.60 }
}

flip.footer: {
  inbound_edges: [
    { from: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" }
  ],
  outbound_edges: [
    { to: "database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" },
    { to: "app/Services/CraftySyntax/LegacyLive.php", type: "used_by", weight: 0.5, hashtag: "#compatibility" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_smilies_mapping", "emoji_metadata", "chat_ui", "dropped_table"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_smilies
# Status: DROPPED
# Replacement: chat_smilies/ directory + |:emoji ... :| inline token format

# 1. Summary
livehelp_smilies was Crafty Syntax's legacy table for storing emoji metadata used by the live chat UI.
It did not store the images themselves -- only:

the emoji "code"

the folder name

the filename

the display order

The actual images always lived in:

Code
chat_smilies/<folder>/<filename>
Lupopedia replaces this entire system with:

a. A file-based emoji architecture
Images live exclusively in the chat_smilies/ directory.

b. A modern inline token format
Code
|:emoji src="folder/filename" :|
c. A renderer that resolves tokens -> images
No database lookup is required.

No data is imported.
The table is dropped after migration.

# 2. What the Legacy Table Did
Crafty Syntax's chat UI allowed operators to pick an emoji from a dropdown.
When selected, it inserted markup like:

Code
|IMG SRC=chat_smilies/FOLDER/image|
The renderer then replaced that with an <img> tag.

The table existed solely to populate the emoji picker UI.

It was never:

a content table

a configuration table

a semantic entity

a durable metadata store

It was a UI convenience table.

# 3. Why Lupopedia Drops This Table
a. The new system is declarative and self-describing
The new token format:

Code
|:emoji src="folder/filename" :|
is:

explicit

namespaced

safe

renderer-agnostic

future-proof

b. The renderer reads directly from the filesystem
No DB lookup.
No syncing.
No metadata drift.

c. The legacy table contains no durable data
Everything in it is already represented by:

the folder structure

the filenames

d. The table was a maintenance trap
If someone added a new emoji file but forgot to update the table, the UI broke.

Lupopedia eliminates this entire class of errors.

# 4. The New Emoji Token Format
Token Syntax
Code
|:emoji src="folder/filename" :|
Rules
emoji is the token type

src is a relative path inside chat_smilies/

quotes are recommended but not required

whitespace before :| is allowed

additional attributes may be added later (e.g., size, alt)

Examples
Code
|:emoji src="happy/smile.png" :|
|:emoji src="animals/cat.gif" :|
|:emoji src="flags/us.png" :|
Renderer Behavior
The renderer:

Scans dialog text for the token pattern

Extracts the src attribute

Resolves it to chat_smilies/<src>

Replaces the token with the appropriate inline image element

This is consistent across:

web

mobile

desktop

federated renderers

# 5. Migration Behavior (as implemented in SQL)
Step 1 -- Convert for safe reading
Code
ALTER TABLE livehelp_smilies ENGINE=InnoDB;
ALTER TABLE livehelp_smilies CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'LEGACY ARCHIVE TABLE -- no longer used...'
Step 3 -- No import
There is:

no SELECT

no INSERT

no mapping

Step 4 -- Drop legacy table
Removed after migration.

# 6. Mapping Summary
Legacy -> New
Code
livehelp_smilies -> DROPPED
Replacement
Code
chat_smilies/<folder>/<filename>
|:emoji src="folder/filename" :|
Fields preserved
None -- the filesystem already contains the real data.

# 7. Doctrine Notes
This migration is a perfect example of:

Replacing a fragile DB-driven UI system with a clean file-based architecture
The old system required:

DB rows

folder structure

matching filenames

UI code to sync them

The new system requires only:

the folder structure

the inline token

The Slope Principle
We do not attempt to import or reinterpret legacy emoji codes.
We rely entirely on the filesystem and the new token format.

Future-proofing
The new token format supports:

additional attributes

theming

accessibility metadata

federated rendering

without schema changes.

# 8. Final Decision
Code
livehelp_smilies -> DROPPED
Emoji system replaced by chat_smilies/ directory + |:emoji src="folder/filename" :| tokens.
