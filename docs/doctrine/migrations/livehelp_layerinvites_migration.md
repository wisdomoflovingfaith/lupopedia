# Migration Note: livehelp_layerinvites
# Status: IMPORTED -> DROPPED
# Replacement: lupo_crafty_syntax_layer_invites (compatibility table)

# 1. Summary
livehelp_layerinvites was Crafty Syntax's system for DHTML "layer invites" -- floating image overlays that appeared on the website to invite visitors to open live chat.

These were implemented using:

absolutely positioned GIF/PNG images

HTML <map> imagemaps

JavaScript actions like openLiveHelp() and closeDHTML()

per-department branding

per-operator ownership

This system predates modern widgets and was a UI hack from the early 2000s.

Lupopedia does not use this mechanism, but the Crafty Syntax module still needs to read legacy invites for compatibility. Therefore the table is imported, normalized, and then the legacy table is dropped.

# 2. What the Legacy Table Did
Each row defined:

name -> internal layer name

imagename -> filename of the floating image

imagemap -> HTML <map> defining clickable regions

department -> department name (not ID)

user -> operator ID

no lifecycle fields

no counters

no timestamps

The invites were displayed:

when an operator was online

based on department

using JavaScript injected into the page

This was a UI-only feature, not a semantic routing or analytics system.

# 3. Why It's Imported
Even though the system is deprecated, the Crafty Syntax module in Lupopedia needs to:

display legacy invites

allow admins to view/edit them

preserve historical behavior for upgraded installations

Therefore, the migration imports the data into a clean, normalized table:

Code
lupo_crafty_syntax_layer_invites
This table is scoped to the Crafty Syntax module and does not affect the rest of Lupopedia.

# 4. Why the Legacy Table Is Dropped
After import:

all meaningful data is preserved

the legacy table structure is obsolete

the new table contains lifecycle fields

the new table is domain-scoped

the legacy JavaScript invite system is deprecated

The old table is no longer needed.

# 5. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_layerinvites ENGINE=InnoDB;
ALTER TABLE livehelp_layerinvites CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Import into new table
Code
TRUNCATE lupo_crafty_syntax_layer_invites;

INSERT INTO lupo_crafty_syntax_layer_invites (...)
SELECT
    name,
    imagename,
    imagemap,
    department,
    user,
    1 AS is_active,
    0 AS display_count,
    0 AS click_count,
    <timestamp>,
    <timestamp>,
    0 AS is_deleted,
    NULL
FROM livehelp_layerinvites;
Step 4 -- Drop legacy table
Removed after migration.

# 6. Mapping Summary
Legacy -> New
Code
name          -> layer_name
imagename     -> image_name
imagemap      -> image_map
department    -> department_name
user          -> user_id
Added fields
Code
is_active
display_count
click_count
created_ymdhis
updated_ymdhis
is_deleted
deleted_ymdhis
Dropped fields
None -- all legacy fields are preserved.

# 7. Doctrine Notes
This migration is a classic example of:

Preserving compatibility without carrying forward obsolete behavior
The legacy DHTML invite system is:

outdated

not responsive

not accessible

not compatible with modern browsers

But the data is preserved so upgraded installations behave as expected.

Modern replacement
Lupopedia uses:

widget-based invites

agent-aware routing

structured metadata

modern UI components

The legacy invites are maintained only for backward compatibility.

# 8. Final Decision
Code
livehelp_layerinvites -> IMPORTED -> DROPPED
Data preserved in lupo_crafty_syntax_layer_invites.
Legacy DHTML invite system deprecated.
