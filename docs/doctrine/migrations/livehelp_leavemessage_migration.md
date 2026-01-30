# Migration Note: livehelp_leavemessage
# Status: IMPORTED -> DROPPED
# Replacement: lupo_crafty_syntax_leave_message

# 1. Summary
livehelp_leavemessage was Crafty Syntax's table for storing offline "Leave a Message" form submissions. When no operator was online, visitors could fill out a form that captured:

email

subject

department

session data

a delimited blob of form fields

This was the offline inbox for Crafty Syntax.

Lupopedia replaces this with a structured table inside the Crafty Syntax module:

Code
lupo_crafty_syntax_leave_message
The legacy table is imported and then dropped.

# 2. What the Legacy Table Did
Each row represented a single offline message submission. Fields included:

email -- visitor's email

subject -- subject line

department -- department ID

sessiondata -- serialized session info

deliminated -- raw form data blob

dateof -- timestamp (YYYYMMDDHHMMSS)

Notably missing in the legacy table:

no phone

no name

no message body (Crafty Syntax stored it in the delimited blob)

no IP address

no user agent

no lifecycle fields

no assignment

no status beyond "new"

It was a minimal, early-2000s form handler.

# 3. Why It Maps to lupo_crafty_syntax_leave_message
The new table provides:

lifecycle fields

assignment

status

soft-delete

structured metadata

compatibility with the Crafty Syntax module

The migration preserves all meaningful legacy data while giving it a modern structure.

Doctrine decisions reflected in your SQL:
phone and name are set to NULL because the legacy form didn't collect them

message is set to NULL because Crafty Syntax stored the message body inside deliminated

priority = 2 (default offline priority)

status = 'new'

assigned_to = NULL

ip_address and user_agent are NULL because the legacy table didn't store them

created_ymdhis = dateof

updated_ymdhis = now()

This is exactly the right interpretation.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_leavemessage ENGINE=InnoDB;
ALTER TABLE livehelp_leavemessage CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Import into new table
Code
TRUNCATE lupo_crafty_syntax_leave_message;

INSERT INTO lupo_crafty_syntax_leave_message (...)
SELECT
    id,
    department,
    email,
    NULL,
    NULL,
    subject,
    NULL,
    2,
    sessiondata,
    deliminated,
    NULL,
    NULL,
    'new',
    NULL,
    dateof,
    <now>,
    0,
    NULL
FROM livehelp_leavemessage;
Step 4 -- Drop legacy table
Removed after migration.

# 5. Mapping Summary
Legacy -> New
Code
id            -> crafty_syntax_leave_message_id
department    -> department_id
email         -> email
subject       -> subject
sessiondata   -> session_data
deliminated   -> form_data
dateof        -> created_ymdhis
(now)         -> updated_ymdhis
Added fields
Code
phone = NULL
name = NULL
message = NULL
priority = 2
status = 'new'
assigned_to = NULL
ip_address = NULL
user_agent = NULL
is_deleted = 0
deleted_ymdhis = NULL
Dropped fields
None -- all meaningful legacy fields are preserved.

# 6. Doctrine Notes
This migration is a perfect example of:

Preserving intent, not structure
The legacy table was a minimal offline inbox.
The new table is a structured CRM-like record.

We preserve:

the submission

the department

the subject

the email

the raw form data

the session context

the timestamp

We add:

lifecycle fields

assignment

status

soft-delete

The Slope Principle
We do not attempt to parse or normalize the legacy deliminated blob.
We store it as form_data and allow the module to interpret it later.

# 7. Final Decision
Code
livehelp_leavemessage -> IMPORTED -> DROPPED
All offline messages preserved in lupo_crafty_syntax_leave_message.
Legacy table removed after migration.
