# Migration Note: livehelp_sessions
# Status: DROPPED
# Replacement: lupo_sessions (deterministic, actor-aware session engine)

# 1. Summary
livehelp_sessions was Crafty Syntax's legacy table for storing ephemeral visitor session data.
It attempted to track:

visitor session IDs

operator assignments

timestamps

browser/cookie-based identifiers

temporary routing state

This table was never durable, never reliable, and never meant to survive a restart.

Lupopedia replaces this entire subsystem with:

Code
lupo_sessions
...a deterministic, actor-aware, device-aware, federated session engine that does not rely on legacy Crafty Syntax session mechanics.

No data is imported.
The table is dropped after migration.

# 2. What the Legacy Table Actually Did
Crafty Syntax stored all session state in this table, including:

temporary session IDs

operator/visitor routing

timestamps

browser cookie identifiers

ephemeral chat state

This table was:

overwritten constantly

cleared when chats ended

inconsistent across browsers

dependent on PHP session behavior

not actor-aware

not device-aware

not federated

not repairable

It was essentially a runtime scratchpad, not a real data model.

# 3. Why Lupopedia Drops This Table
a. The legacy session model is fundamentally incompatible
Crafty Syntax sessions were:

non-deterministic

tied to browser cookies

not linked to actors

not linked to devices

not replayable

not durable

Lupopedia sessions are:

deterministic

actor-aware

device-aware

federated

repairable

replayable

b. The table contains no durable business data
Everything in it was:

ephemeral

runtime state

not historically meaningful

c. The new session engine replaces the entire concept
lupo_sessions provides:

actor identity

device fingerprint

federations node ID

temporal windows

session replay

multi-agent compatibility

d. Migration would be meaningless
There is no way to map:

Crafty Syntax session IDs -> Lupopedia session IDs

cookie-based identifiers -> actor/device identities

ephemeral routing -> deterministic session state

So the table is dropped.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_sessions ENGINE=InnoDB;
ALTER TABLE livehelp_sessions CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- No import
There is:

no SELECT

no INSERT

no mapping

Step 4 -- Drop legacy table
Removed after migration.

# 5. Mapping Summary
Legacy -> New
Code
livehelp_sessions -> DROPPED
Replacement
Code
lupo_sessions
Fields preserved
None -- the legacy table contains no durable data.

# 6. Doctrine Notes
This migration is a perfect example of:

Replacing a broken legacy subsystem with a modern architecture
The old session model was:

fragile

inconsistent

not actor-aware

not device-aware

not federated

The new model is:

deterministic

explicit

actor-centric

device-aware

replayable

multi-agent compatible

The Slope Principle
We do not attempt to import or reinterpret ephemeral runtime state.
We start fresh with a clean, deterministic session engine.

# 7. Final Decision
Code
livehelp_sessions -> DROPPED
All session handling is performed by lupo_sessions.
