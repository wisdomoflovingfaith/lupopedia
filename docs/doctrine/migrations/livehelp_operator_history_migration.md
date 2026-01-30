# Migration Note: livehelp_operator_history
# Status: IMPORTED -> DROPPED
# Replacement: lupo_audit_log

# 1. Summary
livehelp_operator_history was Crafty Syntax's primitive audit log for operator actions.
It recorded:

which operator performed an action

on which channel

at what time

with which transcript (if any)

session metadata (session ID, total time)

This table is one of the few legacy logs that contains durable historical data, so it is imported into Lupopedia's unified audit system:

Code
lupo_audit_log
After import, the legacy table is dropped.

# 2. What the Legacy Table Did
Each row represented a single operator event:

id -> primary key

opid -> operator ID

channel -> channel ID

action -> event type (string)

sessionid -> session identifier

totaltime -> duration of the session

transcriptid -> ID of the transcript (if chat ended)

dateof -> timestamp (YYYYMMDDHHMMSS)

This table powered:

operator activity logs

session duration tracking

transcript linkage

admin reporting

It was a real audit trail, even if minimal.

# 3. Why It Maps Cleanly to Lupopedia
Lupopedia's lupo_audit_log is the modern, structured equivalent:

audit_log_id preserves the legacy primary key

entity_type = 'actor' because these events are operator-initiated

entity_id = opid

channel_id = channel

event_type = action

table_name and table_id link to dialog threads when applicable

payload_json stores session metadata

lifecycle fields are added

This is a clean, lossless migration.

Doctrine decisions reflected in your SQL:
Transcript linkage is only set when transcriptid > 0

Session metadata is preserved in JSON

created_ymdhis and updated_ymdhis both use dateof

No attempt is made to reinterpret or normalize legacy action strings

This preserves the historical meaning without distortion.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_operator_history ENGINE=InnoDB;
ALTER TABLE livehelp_operator_history CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Import into new audit log
Code
TRUNCATE lupo_audit_log;

INSERT INTO lupo_audit_log (...)
SELECT
    id,
    channel,
    'actor',
    opid,
    action,
    CASE WHEN transcriptid > 0 THEN 'lupo_dialog_threads' END,
    CASE WHEN transcriptid > 0 THEN transcriptid END,
    JSON_OBJECT('sessionid', sessionid, 'totaltime', totaltime),
    dateof,
    dateof,
    0,
    NULL
FROM livehelp_operator_history;
Step 4 -- Drop legacy table
Removed after migration.

# 5. Mapping Summary
Legacy -> New
Code
id            -> audit_log_id
channel       -> channel_id
opid          -> entity_id
action        -> event_type
transcriptid  -> table_id (when > 0)
sessionid     -> payload_json.sessionid
totaltime     -> payload_json.totaltime
dateof        -> created_ymdhis, updated_ymdhis
Added fields
Code
entity_type = 'actor'
table_name = 'lupo_dialog_threads' (when transcriptid > 0)
is_deleted = 0
deleted_ymdhis = NULL
Dropped fields
None -- all meaningful legacy fields are preserved.

# 6. Doctrine Notes
This migration is a perfect example of:

Unifying legacy logs into a modern audit system
Crafty Syntax had scattered logging across multiple tables.
Lupopedia consolidates all audit events into a single, structured table.

Preserving historical meaning
We keep:

operator identity

channel context

event type

transcript linkage

session metadata

timestamps

Modernizing structure
We add:

lifecycle fields

soft-delete

entity typing

JSON metadata

The Slope Principle
We do not reinterpret legacy action strings.
We preserve them exactly as they were recorded.

# 7. Final Decision
Code
livehelp_operator_history -> IMPORTED -> DROPPED
All operator audit events preserved in lupo_audit_log.
