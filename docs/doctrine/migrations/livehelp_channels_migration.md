# Doctrine Path
/docs/doctrine/migrations/livehelp_channels_migration.md

# Migration Note: livehelp_channels

# Status
DROPPED

# Replacement
None (concept replaced by Lupopedia's real channel/thread model)

# 1. What the Legacy Table Did
livehelp_channels in Crafty Syntax was not a real channel system.

It was:

- a temporary operator workspace
- created when an operator went online
- destroyed when the operator went offline
- used only to group threads the operator was handling simultaneously
- never visible to visitors
- never persisted as a meaningful entity

Key behaviors:

- Each operator had exactly one channel.
- This was their "workspace tab."
- Visitors were not on a channel.
- They only joined a channel after opening a chat.
- Multiple visitors could be on the same operator channel, each in their own thread.
- Operators saw all threads at once, each with its own background color.
- Visitors only saw messages addressed to them, not the other threads.

Example mapping from the UI:

- "support" is on one operator channel
- "Devin" and "eric" are both attached to that same channel
- but each has their own thread
- and each sees only their own conversation

This was a UI grouping mechanism, not a routing or identity mechanism.

# 2. Why It's Dropped
Lupopedia's architecture replaces this entirely:

## a. Channels are now real semantic entities
- persistent
- routable
- multi-actor
- multi-agent
- with metadata, lifecycle, and awareness

## b. Threads are first-class objects
Each visitor -> their own dialog_thread.

## c. Operators no longer need a "workspace channel"
The UI can show multiple threads without requiring a fake channel row in the database.

## d. The legacy table has no meaningful data
Because:

- channels were ephemeral
- deleted when operator logged out
- contained no durable information
- only existed to support the 2010 UI

There is nothing to import.

# 3. Migration Decision
Code
livehelp_channels -> DROPPED
No data imported.
No fields preserved.
No metadata carried forward.

Replacement Concepts:

- Operator workspace -> handled by UI, not DB
- Visitor grouping -> handled by lupo_dialog_threads
- Thread colors -> stored in thread metadata
- Channel semantics -> handled by lupo_channels (real channels)

# 4. Doctrine Notes
This is a classic example of legacy UI architecture leaking into the database.

Crafty Syntax treated "operator workspace tabs" as database channels. Lupopedia treats channels as semantic communication spaces, not UI constructs.

The correct mapping is:

- Legacy operator channel -> UI concept only
- Legacy visitor thread -> lupo_dialog_threads
- Legacy thread colors -> metadata_json on threads
- Legacy channel table -> dropped

# 5. Cross-Links
- livehelp_operator_channels_migration.md
- livehelp_messages_migration.md
- lupo_dialog_threads doctrine
- lupo_channels doctrine
