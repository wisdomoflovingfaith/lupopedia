---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/migrations/livehelp_identity_migration.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/migrations/livehelp_identity_migration.md
---

# Migration Note: livehelp_identity_daily & livehelp_identity_monthly
# Status: DROPPED (no import into lupo_actors)
# Replacement: Anonymous visitors exist in lupo_sessions only. No anonymous rows in lupo_actors.

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

b. Identity is now actor- or session-based
Only authenticated users, agents, and system users have rows in lupo_actors. Anonymous visitors do not; they exist in lupo_sessions only.

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

Step 3 -- No import into lupo_actors
Anonymous users are not inserted into lupo_actors. Only authenticated users (lupo_auth_users), agents, and system users have rows in lupo_actors. Anonymous visitors exist in lupo_sessions only. livehelp_identity_monthly and livehelp_identity_daily are not imported; they are converted and deprecated, then dropped after migration.

Step 4 -- Drop both tables
After migration, both tables are removed.

# 5. Mapping Summary
Legacy -> New
Code
livehelp_identity_daily   -> DROPPED (no import)
livehelp_identity_monthly -> DROPPED (no import)
No anonymous actor rows. Anonymous visitors are session-only (lupo_sessions).
Dropped fields (all)
Code
All legacy identity fields (cookieid, ipaddress, useragent, uservisits, dateof, etc.) are not imported.
Replacement
Code
Anonymous visitors: lupo_sessions only (no lupo_actors row).
Identity continuity / analytics: identity helper subsystem, session metadata, analytics subsystem as needed.
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
livehelp_identity_monthly -> DROPPED (no import)
Anonymous users are not in lupo_actors; they exist in sessions only. No anonymous actor range. Legacy fingerprinting and identity tables removed.
