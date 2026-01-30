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
