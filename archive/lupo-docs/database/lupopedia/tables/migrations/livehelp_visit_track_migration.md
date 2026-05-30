# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/migrations/livehelp_visit_track_migration.md"
  file_hash: "3adcc69edaa62b418c0e68e94682bcae05cda86c31264756b4bce8ebae400d63"
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
  file_path_from_root: "lupo-docs\database\lupopedia\tables\livehelp_visit_track_migration.md"
  file_hash: "7dbf03a74ba14eca0c93ab23ff7a2106c0ada90e0f8510651c35fc0c174a7ef6"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_visit_track_migration.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_visit_track_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "lupo-docs/database/lupopedia/tables/livehelp_visit_track_migration.md",
  file_hash: "1f0c3dcfdc493f63e39f66477df36f2f1cdb25822aa387fcdc05c5047f4b1e49"
  system_version: "4.0.50"
  channel_id: 42,
  mood_vector: "8B4513",
  purpose: "Migration doctrine for livehelp_visit_track/visits_daily/monthly â†’ lupo_visits",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_visits", "imported"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_visits", "#analytics", "#imported", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.72 }
}

flip.footer: {
  inbound_edges: [
    { from: "lupo-database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 0.9, hashtag: "#migration" },
    { from: "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" }
  ],
  outbound_edges: [
    { to: "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" },
    { to: "lupo-docs/doctrine/migrations/livehelp_paths_firsts_migration.md", type: "related_to", weight: 0.6, hashtag: "#analytics" },
    { to: "lupo-docs/doctrine/migrations/livehelp_referers_daily_migration.md", type: "related_to", weight: 0.6, hashtag: "#analytics" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["lupo-database/migrations/import_from_old_crafty_syntax.sql", "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_visits_mapping", "page_visits", "analytics", "imported"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_visit_track, livehelp_visits_daily, livehelp_visits_monthly
# Status:
#
# livehelp_visit_track -> DROPPED (ephemeral)
#
# livehelp_visits_daily -> IMPORTED -> DROPPED
#
# livehelp_visits_monthly -> IMPORTED -> DROPPED
# Replacement: lupo_visits

# 1. Summary
Crafty Syntax used three different tables to track page visits:

1. livehelp_visit_track
Ephemeral, per-session, rolling page-view tracker.
Not durable. Not analytics. Not imported.

2. livehelp_visits_daily
Aggregated daily visit counts.
No real URLs (only pageurl strings).
Imported into lupo_visits.

3. livehelp_visits_monthly
Aggregated monthly visit counts.
Contains real URLs.
Imported into lupo_visits.

Lupopedia replaces all three with:

Code
lupo_visits
A single, normalized analytics table.

# 2. What the Legacy Tables Actually Did
A. livehelp_visit_track (ephemeral)
This table recorded:

active session page hits

temporary routing

raw referrer strings

"whendone" timestamps

It was never meant to be persisted.
It was a runtime scratchpad, not analytics.

B. livehelp_visits_daily
This table stored:

pageurl (not always a real URL)

parentrec (legacy tree structure)

levelvisits + directvisits

level (depth)

department

dateof (YYYYMMDD)

It was a daily counter, not a real referer model.

C. livehelp_visits_monthly
Same fields as daily, but:

pageurl is usually a real URL

data is aggregated monthly

This is the only table with reliable URLs.

# 3. Why Lupopedia Uses a Unified Table
lupo_visits provides:

normalized URL fields

domain/path extraction

actor linkage (future)

content linkage (future)

lifecycle fields

JSON metadata for legacy fields

a single analytics pipeline

Legacy data is preserved only as metadata, because:

daily table URLs are unreliable

parentrec trees are inconsistent

visit counts are not trustworthy

actor/content linkage cannot be reconstructed

This is the safest, most doctrine-aligned approach.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert all legacy tables for safe reading
Code
ALTER TABLE livehelp_visit_track ENGINE=InnoDB;
ALTER TABLE livehelp_visits_daily ENGINE=InnoDB;
ALTER TABLE livehelp_visits_monthly ENGINE=InnoDB;
Step 2 -- Mark all tables as deprecated
livehelp_visit_track is explicitly marked as ephemeral.

livehelp_visits_daily and livehelp_visits_monthly are marked as imported -> safe to delete.

Step 3 -- Clear the unified table
Code
TRUNCATE lupo_visits;
Step 4 -- Import daily data
Daily table rows become:

page_url = r.pageurl

page_domain extracted from URL

page_path extracted from URL

content_id = 1 (placeholder)

actor_id = 1 (placeholder)

all legacy fields preserved in metadata_json

Step 5 -- Import monthly data
Monthly table rows become:

same mapping as daily

monthly data appended to the same table

This merges daily + monthly into one unified analytics model.

# 5. Why content_id = 1 and actor_id = 1
These are intentional placeholders.

Legacy Crafty Syntax analytics had:

no concept of content IDs

no concept of actors

no way to link visits to content or users

Lupopedia will later:

resolve URLs -> content IDs

resolve sessions -> actors

normalize analytics

The migration preserves the data without pretending to know what it means.

# 6. Mapping Summary
Legacy -> New
Legacy Field	lupo_visits Field	Notes
pageurl	page_url	preserved
dateof	date_ymd	preserved
levelvisits + directvisits	visits	preserved
level	depth	preserved
parentrec	metadata_json	preserved
department	metadata_json	preserved
livehelp_id	metadata_json	preserved
Added fields
Code
content_id = 1 (placeholder)
actor_id = 1 (placeholder)
page_domain = extracted
page_path = extracted
Dropped fields
None -- all legacy fields are preserved in metadata.

# 7. Doctrine Notes
This migration is a perfect example of:

Separating ephemeral session data from durable analytics
livehelp_visit_track is dropped because it was never analytics.

Unifying inconsistent legacy structures
Daily and monthly tables are merged into one normalized table.

Preserving historical data without misrepresenting it
We keep:

visit counts

depth

dates

legacy metadata

We do not attempt to:

reconstruct missing URLs

rebuild broken parent trees

infer content IDs

infer actors

The Slope Principle
We preserve what exists.
We do not fabricate what does not.

# 8. Final Decision
Code
livehelp_visit_track   -> DROPPED (ephemeral)
livehelp_visits_daily  -> IMPORTED -> DROPPED
livehelp_visits_monthly -> IMPORTED -> DROPPED

All legacy visit analytics preserved in lupo_visits.metadata_json.

