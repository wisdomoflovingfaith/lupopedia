# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/database/lupopedia/tables/migrations/livehelp_referers_daily_migration.md"
  file_hash: "c712f27dcc0de4da2d69656c57b392d98ed7986ca203cca6f64da50afc22adc8"
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
  file_path_from_root: "docs\database\lupopedia\tables\livehelp_referers_daily_migration.md"
  file_hash: "c2f0b211c8cb3492db16dca3c70a8e5fd2926a00f9a395dede9187f33f2ad661"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_referers_daily_migration.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_referers_daily_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/livehelp_referers_daily_migration.md",
  file_hash: "6006d32c84a8c61131e11e6f1f982892e97c6c7a61733913224663fe4449555b"
  system_version: "4.0.50"
  channel_id: 42,
  mood_vector: "8B4513",
  purpose: "Migration doctrine for livehelp_referers_daily/monthly â†’ lupo_referers",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_referers", "imported"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_referers", "#analytics", "#imported", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.68 }
}

flip.footer: {
  inbound_edges: [
    { from: "database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 0.9, hashtag: "#migration" },
    { from: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" }
  ],
  outbound_edges: [
    { to: "database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" },
    { to: "docs/doctrine/migrations/livehelp_paths_firsts_migration.md", type: "related_to", weight: 0.6, hashtag: "#analytics" },
    { to: "docs/doctrine/migrations/livehelp_visit_track_migration.md", type: "related_to", weight: 0.6, hashtag: "#analytics" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["database/migrations/import_from_old_crafty_syntax.sql", "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_referers_mapping", "referer_analytics", "traffic_sources", "imported"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_referers_daily & livehelp_referers_monthly
# Status: IMPORTED -> DROPPED
# Replacement: lupo_referers

# 1. Summary
Crafty Syntax stored referer analytics in two separate tables:

livehelp_referers_daily

livehelp_referers_monthly

These tables attempted to track:

page-to-page navigation

visit depth

daily/monthly visit counts

parent/child page relationships

department routing

legacy "livehelp_id" tracking

The data was inconsistent, incomplete, and often corrupted across installs.

Lupopedia replaces this entire subsystem with:

Code
lupo_referers
A single, normalized, doctrine-aligned analytics table.

Legacy data is imported only as metadata, not as first-class referer URLs, because the old system did not store real URLs in the daily table.

Both legacy tables are dropped after migration.

# 2. What the Legacy Tables Actually Did
livehelp_referers_daily
This table did not store referer URLs.
It stored:

pageurl -> the page being visited

parentrec -> the parent page's record ID

levelvisits -> visits from internal navigation

directvisits -> direct hits

level -> depth in the navigation tree

department -> legacy routing

livehelp_id -> legacy analytics ID

dateof -> YYYYMMDD date

This table was essentially a page tree counter, not a referer table.

livehelp_referers_monthly
This table stored:

the same fields as daily

plus a real URL in pageurl

This is the only table that contained actual referer URLs.

Problems with the legacy system
inconsistent schemas

missing URLs in daily table

broken parent/child relationships

corrupted visit counts

no normalization

no actor or content linkage

no domain/path extraction

The system was not salvageable as a modern analytics model.

# 3. Why Lupopedia Uses a Unified Table
lupo_referers provides:

normalized URL fields

domain/path extraction

actor linkage

content linkage

lifecycle fields

JSON metadata for legacy fields

a single analytics pipeline

Legacy data is preserved only as metadata, because:

daily table has no URLs

monthly table URLs are inconsistent

parent/child relationships are not reliable

visit counts are not trustworthy

This is the safest, most doctrine-aligned approach.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy tables for safe reading
Code
ALTER TABLE livehelp_referers_daily ENGINE=InnoDB;
ALTER TABLE livehelp_referers_daily CONVERT TO utf8mb4;

ALTER TABLE livehelp_referers_monthly ENGINE=InnoDB;
ALTER TABLE livehelp_referers_monthly CONVERT TO utf8mb4;
Step 2 -- Mark both tables as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Clear the unified table
Code
TRUNCATE lupo_referers;
Step 4 -- Import daily data (no URLs available)
Daily table rows become:

referer_url = NULL

referer_domain = NULL

referer_path = NULL

all legacy fields preserved in metadata_json

This preserves the historical counts without misrepresenting missing URLs.

Step 5 -- Import monthly data (URLs available)
Monthly table rows become:

referer_url = pageurl

referer_domain extracted via SUBSTRING_INDEX

referer_path extracted via SUBSTRING

all legacy fields preserved in metadata_json

This preserves the only real URL data Crafty Syntax ever stored.

# 5. Mapping Summary
Legacy -> New
Legacy Field	lupo_referers Field	Notes
pageurl	referer_url (monthly only)	daily table has NULL
dateof	date_ymd	preserved
levelvisits + directvisits	visits	preserved
level	depth	preserved
parentrec	metadata_json	preserved
department	metadata_json	preserved
livehelp_id	metadata_json	preserved
Added fields
Code
content_id = 0
actor_id = 0
referer_content_id = 0
Dropped fields
None -- all legacy fields are preserved in metadata.

# 6. Doctrine Notes
This migration is a perfect example of:

Preserving historical analytics without misrepresenting them
We keep:

visit counts

depth

dates

legacy metadata

We do not pretend the legacy system was a real referer model.

Unifying inconsistent legacy structures
Daily and monthly tables are merged into one normalized table.

The Slope Principle
We do not attempt to:

reconstruct missing URLs

rebuild broken parent/child trees

infer content IDs

infer actors

infer domains from non-URLs

We preserve what exists and discard what cannot be trusted.

# 7. Final Decision
Code
livehelp_referers_daily   -> IMPORTED -> DROPPED
livehelp_referers_monthly -> IMPORTED -> DROPPED
All legacy analytics preserved in lupo_referers.metadata_json.

