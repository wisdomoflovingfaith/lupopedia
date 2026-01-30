# Migration Note: livehelp_messages
# Status: DROPPED
# Replacement: None (durable transcripts come from livehelp_transcripts)

# 1. Summary
livehelp_messages was not the Crafty Syntax transcript table.
It stored only the individual messages of an active chat session, and only until the chat ended.

When the chat closed:

Crafty Syntax collapsed all messages into a single transcript blob
-> stored in livehelp_transcripts

Then it cleared livehelp_messages
-> leaving it empty in all normal installations

Therefore:

This table contains no durable historical data

Nothing needs to be imported

Lupopedia's dialog system replaces this entirely

# 2. What the Legacy Table Actually Did
Despite its name, livehelp_messages was a temporary message buffer.

It existed to support:

operator UI refresh

visitor UI refresh

typing indicators

message routing

per-message display before transcript collapse

Lifecycle:
Visitor + operator exchange messages

Messages accumulate in livehelp_messages

When chat ends:

Crafty Syntax concatenates all messages into one giant text blob

Stores that blob in livehelp_transcripts

Deletes all rows from livehelp_messages

This is why the table is almost always empty.

# 3. Why It's Dropped in Lupopedia
Lupopedia uses a real dialog system:

lupo_dialog_threads

lupo_dialog_messages

lupo_dialog_message_bodies

lupo_channels

These tables:

store every message atomically

preserve full transcripts

support multi-actor messaging

support metadata

support channel/thread separation

There is no conceptual mapping from the ephemeral buffer to the new system.

The only durable data -- the transcript -- comes from livehelp_transcripts, not this table.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert table for safe reading
Code
ALTER TABLE livehelp_messages ENGINE=InnoDB;
ALTER TABLE livehelp_messages CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- No import
There is:

no INSERT

no SELECT

no mapping

Because the table contains no durable data.

Step 4 -- Drop after migration
Removed once the migration completes.

# 5. Mapping Summary
Legacy -> New
Code
livehelp_messages -> DROPPED
Replacement
Code
lupo_dialog_threads
lupo_dialog_messages
lupo_dialog_message_bodies
Durable transcripts come from
Code
livehelp_transcripts -> dialog_message_bodies + dialog_messages
6. Doctrine Notes
This migration is a perfect example of:

Preserving meaning, not mechanics
We preserve:

the final transcript (from livehelp_transcripts)

We do not preserve:

the ephemeral per-message buffer

temporary routing state

UI refresh artifacts

The Slope Principle
We do not attempt to reconstruct missing per-message history.
We rely on the final transcript blob as the canonical legacy record.

7. Final Decision
Code
livehelp_messages -> DROPPED (no import)
Durable transcripts come from livehelp_transcripts.
Ephemeral message buffer removed.
