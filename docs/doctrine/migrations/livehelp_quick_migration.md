# Migration Note: livehelp_quick
# Status: IMPORTED -> DROPPED
# Replacement: lupo_actor_reply_templates

# 1. Summary
livehelp_quick was Crafty Syntax's table for operator quick replies -- short canned messages operators could insert into chats.

Lupopedia replaces this with the unified template system:

Code
lupo_actor_reply_templates
This table supports:

human operators

AI agents

system personas

reusable reply templates across modules

The legacy table is imported and then dropped.

# 2. What the Legacy Table Did
Each row represented a single quick reply:

id -> primary key

user -> operator ID

name -> template key

message -> template text

typeof -> usage context (e.g., chat, CRM, etc.)

This table powered:

canned chat responses

operator shortcuts

basic templating

It was simple, but it had a clear meaning.

# 3. Why It Maps Cleanly to Lupopedia
Lupopedia's lupo_actor_reply_templates is the modern, doctrine-aligned successor:

actor_reply_template_id preserves the legacy ID

actor_id preserves operator ownership

template_key preserves the name

template_text preserves the message

usage_context preserves the legacy typeof

lifecycle fields are added

soft-delete is added

This is a clean, lossless migration.

Why TRUNCATE is correct
Quick replies are:

configuration, not content

not historical

not federated

not user-generated

not meant to merge with anything

So the migration resets the table and repopulates it cleanly.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_quick ENGINE=InnoDB;
ALTER TABLE livehelp_quick CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Import into new table
Code
TRUNCATE lupo_actor_reply_templates;

INSERT INTO lupo_actor_reply_templates (...)
SELECT
    id,
    user,
    name,
    message,
    typeof,
    20250101000000,
    20250101000000,
    0,
    NULL
FROM livehelp_quick;
Step 4 -- Drop legacy table
Removed after migration.

# 5. Mapping Summary
Legacy -> New
Code
id       -> actor_reply_template_id
user     -> actor_id
name     -> template_key
message  -> template_text
typeof   -> usage_context
Added fields
Code
created_ymdhis = 20250101000000
updated_ymdhis = 20250101000000
is_deleted = 0
deleted_ymdhis = NULL
Dropped fields
None -- all meaningful legacy fields are preserved.

# 6. Doctrine Notes
This migration is a perfect example of:

Unifying legacy subsystems
Crafty Syntax had separate tables for:

quick replies

AI replies

operator templates

Lupopedia merges them into one unified system.

Preserving intent
We keep:

template ownership

template key

template text

usage context

Modernizing structure
We add:

lifecycle fields

soft-delete

consistent naming

doctrine-aligned schema

The Slope Principle
We do not reinterpret:

template keys

usage contexts

operator ownership

We preserve them exactly as they were.

# 7. Final Decision
Code
livehelp_quick -> IMPORTED -> DROPPED
Quick replies preserved in lupo_actor_reply_templates.
