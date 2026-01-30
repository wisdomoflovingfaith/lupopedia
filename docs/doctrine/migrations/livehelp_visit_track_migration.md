# Migration Note: livehelp_visit_track, livehelp_visits_daily, livehelp_visits_monthly
# Status:
#
# livehelp_visit_track -> DROPPED (ephemeral)
#
# livehelp_visits_daily -> IMPORTED -> DROPPED
#
# livehelp_visits_monthly -> IMPORTED -> DROPPED
# Replacement: lupo_unified_visits

# 1. Summary
Crafty Syntax used three different tables to track page visits:

1. livehelp_visit_track
Ephemeral, per-session, rolling page-view tracker.
Not durable. Not analytics. Not imported.

2. livehelp_visits_daily
Aggregated daily visit counts.
No real URLs (only pageurl strings).
Imported into lupo_unified_visits.

3. livehelp_visits_monthly
Aggregated monthly visit counts.
Contains real URLs.
Imported into lupo_unified_visits.

Lupopedia replaces all three with:

Code
lupo_unified_visits
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
lupo_unified_visits provides:

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
TRUNCATE lupo_unified_visits;
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
Legacy Field	lupo_unified_visits Field	Notes
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

All legacy visit analytics preserved in lupo_unified_visits.metadata_json.
