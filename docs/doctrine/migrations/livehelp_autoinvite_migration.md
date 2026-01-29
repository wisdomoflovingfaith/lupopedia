# Migration Note: livehelp_autoinvite
# Status: IMPORTED -> DROPPED
# Replacement: lupo_crafty_syntax_auto_invite

# 1. Summary
livehelp_autoinvite stored Crafty Syntax auto-invite rules used to trigger chat invitations.
The data is imported into a normalized compatibility table and the legacy table is dropped.

# 2. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_autoinvite ENGINE=InnoDB;
ALTER TABLE livehelp_autoinvite CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Import into new table
Code
TRUNCATE lupo_crafty_syntax_auto_invite;

INSERT INTO lupo_crafty_syntax_auto_invite (...)
SELECT
    idnum,
    offline,
    isactive,
    department,
    message,
    page,
    visits,
    referer,
    typeof,
    seconds,
    user_id,
    socialpane,
    excludemobile,
    onlymobile,
    20250101000000,
    20250101000000,
    0,
    NULL
FROM livehelp_autoinvite;
Step 4 -- Drop legacy table
Removed after migration.

# 3. Mapping Summary
Legacy -> New
Code
idnum        -> crafty_syntax_auto_invite_id
offline      -> is_offline
isactive     -> is_active
department   -> department_id
message      -> message
page         -> page_url
visits       -> visits
referer      -> referrer_url
typeof       -> invite_type
seconds      -> trigger_seconds
user_id      -> operator_user_id
socialpane   -> show_socialpane
excludemobile -> exclude_mobile
onlymobile   -> only_mobile
Added fields
Code
created_ymdhis = 20250101000000
updated_ymdhis = 20250101000000
is_deleted = 0
deleted_ymdhis = NULL
