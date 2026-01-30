# Migration Note: livehelp_leads
# Status: IMPORTED -> DROPPED
# Replacement: lupo_crm_leads

# 1. Summary
livehelp_leads was Crafty Syntax's primitive "lead capture" table. It stored:

email

phone

first/last name

source (referrer or form)

status (open/closed)

a free-form data blob

the date the lead was created

This table powered:

the "Leads" tab in the old admin panel

basic CRM-like behavior

operator follow-ups

email broadcasts (via livehelp_emails)

Lupopedia replaces this with a real CRM subsystem, so the legacy table is imported and then dropped.

# 2. What the Legacy Table Did
Each row represented a single lead submission from:

the "Leave a Message" form

the "Email Us" form

the offline chat form

custom embedded forms

Fields included:

email, phone

firstname, lastname

source (string)

status (string)

data (free-form serialized array or text)

date_entered (YYYYMMDDHHMMSS)

There were no lifecycle fields, no assignment, no scoring, and no structured metadata.

# 3. Why It Maps to Lupopedia CRM
Lupopedia's CRM subsystem (lupo_crm_leads) provides:

structured lead records

lifecycle timestamps

assignment

scoring

metadata JSON

soft-delete

actor linkage

This is the correct modern home for Crafty Syntax lead data.

Doctrine decision
All legacy leads are imported as-is, with:

lead_score = 0

assigned_to = NULL

lead_data = data (raw legacy blob)

created_ymdhis = date_entered

updated_ymdhis = now()

This preserves meaning without forcing premature normalization.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert table for safe reading
Code
ALTER TABLE livehelp_leads ENGINE=InnoDB;
ALTER TABLE livehelp_leads CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Import into CRM
Code
TRUNCATE lupo_crm_leads;

INSERT INTO lupo_crm_leads (...)
SELECT
    id,
    email,
    phone,
    firstname,
    lastname,
    source,
    status,
    0,
    NULL,
    data,
    date_entered,
    <now>,
    0,
    NULL
FROM livehelp_leads;
Step 4 -- Drop legacy table
Removed after migration.

# 5. Mapping Summary
Legacy -> New
Code
id            -> crm_lead_id
email         -> email
phone         -> phone
firstname     -> first_name
lastname      -> last_name
source        -> source
status        -> status
data          -> lead_data
date_entered  -> created_ymdhis
(now)         -> updated_ymdhis
Added fields
Code
lead_score = 0
assigned_to = NULL
is_deleted = 0
deleted_ymdhis = NULL
Dropped fields
None -- all legacy fields are preserved.

# 6. Doctrine Notes
This migration is a perfect example of:

Preserving business data while modernizing structure
The legacy table contained real customer information.
We preserve:

identity

contact info

source

status

raw metadata

We add:

lifecycle fields

scoring

assignment

soft-delete

The Slope Principle
We do not attempt to normalize the legacy data blob.
We store it as lead_data and allow the CRM module to interpret it later.

This keeps the migration gentle and reversible.

# 7. Final Decision
Code
livehelp_leads -> IMPORTED -> DROPPED
All lead data preserved in lupo_crm_leads.
Legacy table removed after migration.
