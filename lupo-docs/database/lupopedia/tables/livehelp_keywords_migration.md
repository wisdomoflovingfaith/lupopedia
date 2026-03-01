# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\livehelp_keywords_migration.md"
  file_hash: "1e7494be037b8364cec5e176dbb1bfa6ab791aea5c631472cea5ea45539983d4"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\livehelp_keywords_migration.md"
  file_hash: "90e17dddcb8730e620c3eaf9756c0c556d69011faf044bcb00a72b6a73b23c50"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_keywords_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_keywords_migrationmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
flare.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/livehelp_keywords_migration.md",
  file_hash: "09da1af724bf7d4ab5f8e5a05e196fb90d5cad20298e3147096d2ff82e582d33"
  system_version: "4.0.50"
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_keywords → deprecated/replaced",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_keywords", "deprecated"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_keywords", "#deprecated", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 1, outbound_count: 2, centrality_score: 0.64 }
}

flip.footer: {
  inbound_edges: [
    { from: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" }
  ],
  outbound_edges: [
    { to: "database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" },
    { to: "docs/doctrine/migrations/livehelp_questions_migration.md", type: "related_to", weight: 0.6, hashtag: "#qa_system" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_keywords_mapping", "keyword_system", "deprecated_table"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Doctrine Path
/docs/doctrine/migrations/livehelp_keywords_migration.md

# Status
Deprecated -> Replaced

# Author
Eric (Wolfie)

# Version
1.0

# Summary
The legacy Crafty Syntax tables:

- livehelp_keywords_daily
- livehelp_keywords_monthly

are fully deprecated and not imported into Lupopedia. They are replaced by the modern analytics subsystem:

- lupo_analytics_campaign_vars

These old tables relied on 1999-era query-string parsing (q= and similar patterns) and no longer reflect how modern traffic, campaigns, or search terms are tracked.

# Why These Tables Are Obsolete
## 1. Legacy Query Model (1999-2005)
Crafty Syntax attempted to extract "keywords" from:

- raw query strings
- q= parameters
- early search engine URL formats
- unstructured GET variables

This model is no longer valid because:

- modern search engines use encrypted queries
- browsers hide referrer details
- UTM parameters replaced keyword extraction
- privacy rules block keyword leakage
- mobile apps and SPA frameworks do not expose query strings

## 2. Empty or Near-Empty Tables in Modern Installs
Most real installations show:

- livehelp_keywords_daily -> empty
- livehelp_keywords_monthly -> empty

Because the logic has not worked in over a decade.

## 3. Conceptual Mismatch
The old system tried to infer "keywords" from URLs. The new system tracks campaign variables intentionally:

- utm_source
- utm_medium
- utm_campaign
- utm_term
- utm_content
- custom vars

This is a completely different model.

# Modern Replacement: lupo_analytics_campaign_vars
Lupopedia replaces keyword extraction with explicit campaign variable tracking.

## Key Improvements
- No guessing
- No parsing brittle URLs
- No dependency on search engine behavior
- Fully structured
- Works with modern analytics
- Compatible with AI-driven attribution
- Supports multi-agent interpretation

## Schema Alignment
lupo_analytics_campaign_vars stores:

- actor_id
- visit/session identifiers
- campaign variables
- timestamps
- metadata

This is the correct modern equivalent of "keywords," but with intentionality and structure.

# Migration Decision
We do NOT import:

- livehelp_keywords_daily
- livehelp_keywords_monthly

We DO document:

- their historical purpose
- why they are obsolete
- what replaces them
- how modern campaign tracking works

We DO drop them after migration.

# Doctrine Statement
Legacy keyword tables are deprecated. Lupopedia uses structured campaign variables instead of inferred keywords. All keyword tables are dropped without import.

# Related Files
- lupo_analytics_campaign_vars
- analytics_refactor_overview.md
- migration_atlas.md
- livehelp_identity_migration.md