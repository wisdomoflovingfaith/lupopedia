# Migration Note: livehelp_operator_departments
# Status: IMPORTED -> DROPPED
# Replacement: lupo_actor_departments

# 1. Summary
livehelp_operator_departments was Crafty Syntax's table for mapping operators to departments. It was one of the few legacy tables with a clean, durable meaning:

which operator belonged to which department

optional "title" or role label

no lifecycle fields

no soft-delete

no timestamps

Lupopedia replaces this with the modern, doctrine-aligned table:

Code
lupo_actor_departments
This table supports:

actor -> department membership

lifecycle fields

soft-delete

metadata

consistent naming

clean schema

The legacy table is imported and then dropped.

# 2. What the Legacy Table Did
Each row represented:

recno -> primary key

user_id -> operator ID

department -> department ID

extra -> optional title or role label

This table powered:

operator routing

department-based permissions

department-based chat assignment

admin UI grouping

It was one of the few Crafty Syntax tables with a stable conceptual meaning.

# 3. Why It Maps Cleanly to Lupopedia
Lupopedia's lupo_actor_departments is the direct conceptual successor:

actor_id replaces user_id

department_id is unchanged

title preserves the legacy extra field

lifecycle fields are added

soft-delete is added

timestamps are added

This is a clean, lossless migration.

Doctrine decisions reflected in your SQL:
actor_department_id = recno (preserves legacy primary key)

title = extra (best semantic match)

created_ymdhis and updated_ymdhis set to now

is_deleted = 0

deleted_ymdhis = NULL

No data is lost.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_operator_departments ENGINE=InnoDB;
ALTER TABLE livehelp_operator_departments CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Import into new table
Code
TRUNCATE lupo_actor_departments;

INSERT INTO lupo_actor_departments (...)
SELECT
    recno,
    user_id,
    department,
    extra,
    <now>,
    <now>,
    0,
    NULL
FROM livehelp_operator_departments;
Step 4 -- Drop legacy table
Removed after migration.

# 5. Mapping Summary
Legacy -> New
Code
recno       -> actor_department_id
user_id     -> actor_id
department  -> department_id
extra       -> title
Added fields
Code
created_ymdhis = now
updated_ymdhis = now
is_deleted = 0
deleted_ymdhis = NULL
Dropped fields
None -- all meaningful legacy fields are preserved.

# 6. Doctrine Notes
This migration is a perfect example of:

A clean, durable mapping
Unlike many Crafty Syntax tables, this one had a stable meaning and maps directly into Lupopedia's actor system.

Preserving identity
Keeping recno as actor_department_id ensures:

reversible migration

traceability

heritage-safe imports

Modernizing structure
Lupopedia adds:

lifecycle fields

soft-delete

consistent naming

doctrine-aligned schema

# 7. Final Decision
Code
livehelp_operator_departments -> IMPORTED -> DROPPED
All operator-department mappings preserved in lupo_actor_departments.
