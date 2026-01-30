# Doctrine Path
/docs/doctrine/migrations/livehelp_emails_migration.md

# Migration Note: livehelp_emails

# Status
IMPORTED -> DROPPED

# Replacement
lupo_crm_lead_messages (broadcast lead)

# 1. What the Legacy Table Did
livehelp_emails was Crafty Syntax's outbound email content table. It stored:

- the sender's email address
- the subject line
- the body text
- optional notes
- a timestamp (implicit)

This table represented messages sent by operators to the entire lead database, not one-to-one emails. It was the backend for the "Email the entire database" broadcast feature.

Key characteristics:
Every row was a broadcast email.

There was no lead_id column.
There was no actor_id or sender identity beyond fromemail.
It was not a CRM table - it was a legacy mass-mail feature.

# 2. Why It's Imported Into CRM
Lupopedia's CRM subsystem (lupo_crm_lead_messages) is the correct home for:

- message content
- sender metadata
- timestamps
- notes

Even though Crafty Syntax did not have a CRM, the closest modern equivalent is a CRM lead message.

Important Doctrine Decision
Because Crafty Syntax broadcast emails had no per-lead targeting, all imported messages are assigned to:

Code
lead_id = 1
This represents the broadcast lead, a special system lead that groups all legacy mass-mail messages.

# 3. Why the Legacy Table Is Dropped
After import:

- all meaningful data is preserved
- the table contains no additional metadata
- the table structure is obsolete
- the broadcast feature is replaced by CRM messaging

There is no reason to keep the legacy table.

# 4. Migration Behavior (as seen in the SQL)
Step 1 - Convert table for safe reading
Code
ALTER TABLE livehelp_emails ENGINE=InnoDB;
ALTER TABLE livehelp_emails CONVERT TO utf8mb4;

Step 2 - Mark as deprecated
Code
COMMENT = 'DEPRECATED...'

Step 3 - Import into CRM
Code
TRUNCATE lupo_crm_lead_messages;

INSERT INTO lupo_crm_lead_messages (...)
SELECT
    id AS crm_lead_message_id,
    1 AS lead_id,
    fromemail,
    subject,
    bodyof,
    notes,
    NULL AS actor_id,
    <timestamp>,
    <timestamp>
FROM livehelp_emails;

Step 4 - Table is dropped after migration
No further references exist.

# 5. Mapping Summary
Legacy -> New
Code
livehelp_emails.id           -> lupo_crm_lead_messages.crm_lead_message_id
livehelp_emails.fromemail    -> lupo_crm_lead_messages.from_email
livehelp_emails.subject      -> lupo_crm_lead_messages.subject
livehelp_emails.bodyof       -> lupo_crm_lead_messages.body_text
livehelp_emails.notes        -> lupo_crm_lead_messages.notes
(implicit)                   -> created_ymdhis / updated_ymdhis
(no legacy lead_id)          -> lead_id = 1 (broadcast)
(no legacy actor_id)         -> actor_id = NULL

Fields dropped
None - all meaningful fields are preserved.

Replacement
lupo_crm_lead_messages becomes the canonical store for legacy broadcast emails.

# 6. Doctrine Notes
This migration is a good example of:

Preserving meaning, not structure
The legacy table was not a CRM table, but its semantic meaning (broadcast messages) maps cleanly into the CRM subsystem.

The Slope Principle
Instead of forcing a new broadcast system immediately, we:

- preserve the legacy messages
- assign them to a special broadcast lead
- allow the new CRM module to interpret them later

This keeps the migration gentle and reversible.

# 7. Final Decision
Code
livehelp_emails -> IMPORTED -> DROPPED
All message content preserved in lupo_crm_lead_messages.
Assigned to broadcast lead (lead_id = 1).
Legacy table removed after migration.
