# Migration Note: livehelp_paths_firsts & livehelp_paths_monthly
# Status: IMPORTED -> DROPPED
# Replacement: lupo_unified_analytics_paths
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
lupo_unified_analytics_paths
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
lupo_unified_analytics_paths provides:

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
TRUNCATE lupo_unified_analytics_paths;
Step 4 -- Import first-time transitions
Code
INSERT INTO lupo_unified_analytics_paths (...)
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
INSERT INTO lupo_unified_analytics_paths (...)
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

All transitions preserved in lupo_unified_analytics_paths.
