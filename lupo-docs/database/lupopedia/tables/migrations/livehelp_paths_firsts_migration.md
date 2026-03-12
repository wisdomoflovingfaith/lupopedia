# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\livehelp_paths_firsts_migration.md"
  file_hash: "7d1bfbf9b73035b5e45052c58296722d193ecaa0090946209fa8a204773af359"
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
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\livehelp_paths_firsts_migration.md"
  file_hash: "10acf08111a1b3bc8f0d7dd7623c62848bec65148f289c8c1d729ade54a11096"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_paths_firsts_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_paths_firsts_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/livehelp_paths_firsts_migration.md",
  file_hash: "9687644124c28f95b15fbb9cf1c53ae5710e973863fe8cf3d45b042391cabcae"
  system_version: "4.0.50"
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_paths_firsts/monthly → lupo_analytics_paths",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_paths", "imported"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_paths", "#analytics", "#imported", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.70 }
}

flip.footer: {
  inbound_edges: [
    { from: "database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 0.9, hashtag: "#migration" },
    { from: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" }
  ],
  outbound_edges: [
    { to: "database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" },
    { to: "docs/doctrine/migrations/livehelp_visit_track_migration.md", type: "related_to", weight: 0.6, hashtag: "#analytics" },
    { to: "docs/doctrine/migrations/livehelp_referers_daily_migration.md", type: "related_to", weight: 0.6, hashtag: "#analytics" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["database/migrations/import_from_old_crafty_syntax.sql", "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_paths_mapping", "page_navigation", "analytics", "imported"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_paths_firsts & livehelp_paths_monthly
# Status: IMPORTED -> DROPPED
# Replacement: lupo_analytics_paths
# Transition Types: first, all

# 1. Summary
Crafty Syntax stored page-to-page navigation analytics in two separate tables:

1. livehelp_paths_firsts
Tracks first-time transitions between pages within a month.

2. livehelp_paths_monthly
Tracks all transitions between pages within a month.

Both tables share the same schema:

visit_recno -> originating page

exit_recno -> destination page

dateof -> YYYYMMDD date

visits -> number of transitions

no metadata

no lifecycle fields

Lupopedia replaces both with a single unified table:

Code
lupo_analytics_paths
The legacy tables are imported and then dropped.

# 2. What the Legacy Tables Actually Did
A. livehelp_paths_firsts
This table recorded:

the first time a visitor moved from page A -> page B

grouped by month

with a count of how many first-time transitions occurred

This was used for:

funnel entry analysis

first-touch attribution

navigation discovery

B. livehelp_paths_monthly
This table recorded:

all transitions from page A -> page B

grouped by month

with a count of total transitions

This was used for:

general navigation analytics

path frequency analysis

monthly traffic summaries

Shared problems
no lifecycle fields

no metadata

no actor/content linkage

no normalization

no soft-delete

no referential integrity

But the core meaning of the data was stable and salvageable.

# 3. Why Lupopedia Uses a Unified Table
lupo_analytics_paths provides:

a single table for all transition types

a transition_type discriminator (first, all, future types)

lifecycle fields

soft-delete

metadata JSON

compatibility with future analytics engines

This allows:

first-time transitions

all transitions

daily/monthly/annual rollups

metadata enrichment

content/actor linkage (future)

The mapping is clean and lossless.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy tables for safe reading
Code
ALTER TABLE livehelp_paths_firsts ENGINE=InnoDB;
ALTER TABLE livehelp_paths_firsts CONVERT TO utf8mb4;

ALTER TABLE livehelp_paths_monthly ENGINE=InnoDB;
ALTER TABLE livehelp_paths_monthly CONVERT TO utf8mb4;
Step 2 -- Mark both tables as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Clear the unified table
Code
TRUNCATE lupo_analytics_paths;
Step 4 -- Import first-time transitions
Code
INSERT INTO lupo_analytics_paths (...)
SELECT
    visit_recno,
    exit_recno,
    LEFT(dateof, 6),
    'first',
    visits,
    NULL,
    CONCAT(dateof, '000000'),
    CONCAT(dateof, '000000'),
    0,
    NULL
FROM livehelp_paths_firsts;
Step 5 -- Import all transitions
Code
INSERT INTO lupo_analytics_paths (...)
SELECT
    visit_recno,
    exit_recno,
    dateof,
    'all',
    visits,
    NULL,
    CONCAT(dateof, '01000000'),
    CONCAT(dateof, '01000000'),
    0,
    NULL
FROM livehelp_paths_monthly;
This preserves:

from -> to transitions

monthly grouping

first-time vs all transitions

visit counts

legacy dates

# 5. Mapping Summary
Legacy -> New
Legacy Field	New Field	Notes
visit_recno	from_page_id	preserved
exit_recno	to_page_id	preserved
dateof	year_month	first 6 chars for first-time transitions
dateof	year_month	full YYYYMMDD for monthly transitions
visits	transition_count	preserved
-	transition_type	'first' or 'all'
-	metadata_json	NULL
-	created_ymdhis	derived
-	updated_ymdhis	derived
Dropped fields
None -- all meaningful fields are preserved.

# 6. Doctrine Notes
This migration is a perfect example of:

Unifying two parallel analytics systems
Crafty Syntax split first-time and all transitions into separate tables.
Lupopedia merges them with a transition_type discriminator.

Preserving historical meaning
We keep:

from -> to transitions

monthly grouping

first-time semantics

visit counts

Modernizing analytics
We add:

lifecycle fields

soft-delete

metadata JSON

unified schema

The Slope Principle
We do not reinterpret:

page IDs

transition semantics

monthly grouping

We preserve the legacy meaning exactly.

# 7. Final Decision
Code
livehelp_paths_firsts  -> IMPORTED -> DROPPED
livehelp_paths_monthly -> IMPORTED -> DROPPED

All transitions preserved in lupo_analytics_paths.