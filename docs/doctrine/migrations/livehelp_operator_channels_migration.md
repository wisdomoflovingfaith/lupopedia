# Migration Note: livehelp_operator_channels
# Status: DROPPED
# Replacement: None (functionality absorbed into Lupopedia's channel + thread + presence system)

# 1. Summary
livehelp_operator_channels was one of the most confusing tables in Crafty Syntax.
It attempted to track:

which operator was on

which channel

talking to which user

with which background colors

and when the session started

But the column names were misleading, overlapping, and contradictory:

user_id

userid

channel

ser_id

statusof

bgcolor, txtcolor, channelcolor, txtcolor_alt

Even the original author (you!) had to reverse-engineer what each field meant.

Lupopedia replaces this entire subsystem with a clean, explicit architecture:

lupo_channels

lupo_channel_membership

lupo_dialog_threads

lupo_actor_presence

metadata_json for UI colors

Because the legacy table contains no durable business data, and because its meaning is fully absorbed into modern structures, it is dropped.

# 2. What the Legacy Table Actually Did
This table was a runtime routing table, not a durable record.

It stored:

Operator -> Channel assignment
which operator was currently "on" a channel

which visitor they were talking to

which ephemeral channel ID was active

User vs Operator confusion
The table had two different "user" columns:

user_id -> the operator

userid -> the visitor

This naming collision caused endless confusion.

Color fields
Crafty Syntax stored UI colors directly in this table:

background color

text color

alternate text color

channel color

These were purely UI artifacts.

Ephemeral lifecycle
When the chat ended:

the channel was deleted

the operator mapping was deleted

the color data was lost

the table was wiped

This table was never meant to store history.

# 3. Why It's Dropped in Lupopedia
a. The table contains no durable data
Everything in it was:

ephemeral

UI-only

runtime state

overwritten constantly

b. The meaning is fully replaced by modern architecture
Lupopedia uses:

lupo_channels -> real channels

lupo_dialog_threads -> real threads

lupo_actor_presence -> operator presence

lupo_channel_membership -> who is on what channel

metadata_json -> UI colors

c. The color system is replaced
Lupopedia uses:

predefined channel colors

thread-level metadata

no per-operator color overrides

d. The table was dangerously confusing
The naming collision (user_id vs userid) alone is enough reason to drop it.

Future developers should never see this table again.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert table for safe reading
Code
ALTER TABLE livehelp_operator_channels ENGINE=InnoDB;
ALTER TABLE livehelp_operator_channels CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- No import
There is:

no INSERT

no SELECT

no mapping

Step 4 -- Drop legacy table
Removed after migration.

# 5. Mapping Summary
Legacy -> New
Code
livehelp_operator_channels -> DROPPED
Replacement
Code
lupo_channels
lupo_channel_membership
lupo_dialog_threads
lupo_actor_presence
metadata_json (for UI colors)
Fields preserved
None -- all meaningful behavior is replaced.

# 6. Doctrine Notes
This migration is a perfect example of:

Replacing a confusing legacy artifact with a clean architecture
The old table mixed:

routing

presence

UI colors

operator assignment

visitor assignment

...into one messy structure.

Lupopedia separates these concerns cleanly.

The Slope Principle
We do not attempt to interpret or import legacy routing state.
We rely on the modern channel/thread/presence system.

# 7. Final Decision
Code
livehelp_operator_channels -> DROPPED (no import)
All functionality replaced by Lupopedia's channel, thread, and presence system.
