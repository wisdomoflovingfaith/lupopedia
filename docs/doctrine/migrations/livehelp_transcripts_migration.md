# Migration Note: livehelp_transcripts
# Status: IMPORTED -> DROPPED
# Replacement:
#
# lupo_dialog_threads
#
# lupo_dialog_messages

# 1. Summary
livehelp_transcripts was Crafty Syntax's legacy table for storing entire chat transcripts as single rows.
Each row contained:

the entire transcript text

the start and end timestamps

the operator and visitor metadata (embedded in the transcript)

the session ID

the department

the email

the operator list

the serialized session data

This table was a flat, monolithic transcript store, not a message-level dialog system.

Lupopedia replaces this with the Dialog System, which separates:

threads (lupo_dialog_threads)

messages (lupo_dialog_messages)

message bodies (message_body)

metadata

mood frameworks

lifecycle fields

Each legacy transcript becomes:

one dialog thread

one dialog message containing the full transcript text

The legacy table is imported and then dropped.

# 2. What the Legacy Table Actually Did
Each row in livehelp_transcripts represented:

a full chat session

stored as a single text blob

with no message boundaries

no actor separation

no metadata normalization

no lifecycle fields

no soft-delete

no threading model

Fields included:

recno -> primary key

transcript -> full chat text

starttime / endtime -> timestamps

sessionid -> legacy session

department -> routing

email -> visitor email

operators -> comma-separated operator list

sessiondata -> serialized PHP session data

This table was a historical artifact, not a modern dialog model.

# 3. Why Lupopedia Uses Threads + Messages
Lupopedia's dialog system is:

actor-aware

message-level

federated

mood-aware

multi-agent compatible

lifecycle-aware

Legacy transcripts cannot be decomposed into individual messages without unreliable heuristics.

Therefore:

Each legacy transcript becomes:
one thread

one message containing the full transcript text

This preserves historical data without fabricating message boundaries.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_transcripts ENGINE=InnoDB;
ALTER TABLE livehelp_transcripts CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Clear dialog threads
Code
TRUNCATE lupo_dialog_threads;
Step 4 -- Import threads
Code
INSERT INTO lupo_dialog_threads (...)
SELECT
    recno,
    1,
    1,
    1,
    CONCAT(recno, ' import from crafty syntax'),
    NULL,
    starttime,
    endtime
FROM livehelp_transcripts;
This creates one thread per transcript.

Step 5 -- Clear dialog messages
Code
TRUNCATE lupo_dialog_messages;
Step 6 -- Import messages
Code
INSERT INTO lupo_dialog_messages (...)
SELECT
    recno,
    recno,
    1,
    1,
    1,
    CONCAT('Imported transcript #', recno),
    transcript,
    'text',
    NULL,
    NULL,
    'western_analytical',
    1.00,
    starttime,
    endtime,
    0,
    NULL
FROM livehelp_transcripts;
This creates one message per thread, containing the full transcript.

# 5. Mapping Summary
Legacy -> New
Legacy Field	New Field	Notes
recno	dialog_thread_id	preserved
recno	dialog_message_id	preserved
transcript	message_body	preserved
starttime	created_ymdhis	preserved
endtime	updated_ymdhis	preserved
-	summary_text	synthesized
-	message_text	synthesized
-	mood_framework	'western_analytical'
-	weight	1.00
Dropped fields (preserved elsewhere if needed)
sessionid

department

email

operators

sessiondata

These are not part of the dialog model and are not durable.

# 6. Doctrine Notes
This migration is a perfect example of:

Preserving historical data without fabricating structure
We keep:

the transcript text

the timestamps

the identity of the transcript

We do not attempt to:

split transcripts into messages

infer actors

reconstruct message boundaries

rebuild session state

Modernizing the dialog model
We add:

threads

messages

lifecycle fields

mood frameworks

soft-delete

The Slope Principle
We preserve what exists.
We do not invent what never existed.

# 7. Final Decision
Code
livehelp_transcripts -> IMPORTED -> DROPPED
Each transcript becomes one dialog thread + one dialog message.
