# Migration Note: livehelp_identity_daily & livehelp_identity_monthly
# Status: PARTIALLY IMPORTED -> DROPPED
# Replacement: Anonymous actors (lupo_actors) + Lupopedia identity helper subsystem

# 1. Summary
Crafty Syntax used two tables to track anonymous visitor identity:

livehelp_identity_daily

livehelp_identity_monthly

These tables attempted to guess whether a visitor was the same person using:

cookieid

IP class-C matching

user agent hashing

"re-cookie" logic

visit counters

daily/monthly aggregation

This system was built in the early 2000s and is obsolete, unreliable, and incompatible with modern privacy and identity models.

Lupopedia replaces this entire subsystem with a real actor model and structured identity helpers.

# 2. What the Legacy Tables Did
a. Anonymous visitor continuity
Crafty Syntax tried to maintain continuity for non-logged-in visitors by:

reusing cookieid

reassigning cookieid based on IP + user agent

incrementing visit counters

tracking monthly/daily activity

b. Analytics and operator UI
Operators could see:

how many times a visitor returned

how long they stayed

whether they were "known" or "new"

This was a UI feature, not a durable identity system.

c. Two aggregation windows
daily -> per-day records

monthly -> per-month records

Both stored the same conceptual data.

# 3. Why These Tables Are Obsolete
a. Fingerprinting is removed
Lupopedia does not use:

IP matching

user agent heuristics

re-cookie logic

class-C blocks

hostname lookups

These are privacy-unsafe and unreliable.

b. Identity is now actor-based
Every visitor -- authenticated or not -- becomes an actor_id.

c. Analytics are handled by modern subsystems
Daily/monthly identity tables are replaced by:

lupo_analytics_*

session metadata

event streams

d. Only meaningful continuity is preserved
The only legacy data worth preserving is:

cookieid

visit count

month

Everything else is dropped.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert tables for safe reading
Both tables are converted to InnoDB + utf8mb4.

Step 2 -- Mark as deprecated
Both tables receive a DEPRECATED comment.

Step 3 -- Import continuity from monthly table
livehelp_identity_monthly is used to create anonymous actors:

actor_type = 'anonymous'

slug = 'anon-<id>'

name = 'Anonymous Visitor <id>'

timestamps derived from the month (dateof)

metadata JSON containing:

legacy_cookieid

legacy_visit_count

legacy_month

Step 4 -- Drop both tables
After import, both tables are removed.

# 5. Mapping Summary
Legacy -> New
Code
livehelp_identity_monthly.id           -> actor_source_id
livehelp_identity_monthly.cookieid     -> metadata.legacy_cookieid
livehelp_identity_monthly.uservisits   -> metadata.legacy_visit_count
livehelp_identity_monthly.dateof       -> metadata.legacy_month
Dropped fields
Code
ipaddress
useragent
identity
groupidentity
groupusername
seconds
username
Replacement
Code
anonymous actors in lupo_actors
identity helper subsystem
session metadata
analytics subsystem
6. Doctrine Notes
This migration preserves meaning, not mechanics.

We keep:

the fact that a visitor existed

the fact that they returned

the fact that they had a cookieid

We discard:

fingerprinting

IP matching

user agent heuristics

daily/monthly aggregation

This follows the Slope Principle: preserve continuity without carrying forward unsafe or obsolete behavior.

7. Final Decision
Code
livehelp_identity_daily   -> DROPPED (no import)
livehelp_identity_monthly -> PARTIALLY IMPORTED -> DROPPED
Anonymous actors created from monthly continuity data.
Legacy fingerprinting system removed.
