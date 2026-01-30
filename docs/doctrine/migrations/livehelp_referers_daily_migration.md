# Migration Note: livehelp_referers_daily & livehelp_referers_monthly
# Status: IMPORTED -> DROPPED
# Replacement: lupo_unified_referers

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
lupo_unified_referers
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
lupo_unified_referers provides:

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
TRUNCATE lupo_unified_referers;
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
Legacy Field	lupo_unified_referers Field	Notes
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
All legacy analytics preserved in lupo_unified_referers.metadata_json.
