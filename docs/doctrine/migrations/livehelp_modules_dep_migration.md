# Migration Note: livehelp_modules_dep
# Status: TABLE CREATED IN LUPOPEDIA -> NO LEGACY IMPORT -> LEGACY TABLE DROPPED
# Replacement: lupo_modules_departments (modern per-department module visibility)

# 1. Summary
livehelp_modules_dep was Crafty Syntax's table for controlling which top-bar modules were visible inside the live chat window for each department:

Live Help

CRM (Contact)

Questions & Answers (Truth)

This was a UI-only routing table, not a real module system.

Lupopedia replaces this with a modern, explicit table:

Code
lupo_modules_departments
This new table allows administrators to control per-department module visibility, but no legacy data is imported because:

Lupopedia enables all public-facing modules by default

Legacy installs often had inconsistent or incomplete mappings

The old table was not semantically meaningful

The new system is cleaner, explicit, and doctrine-aligned

Therefore, the legacy table is dropped.

# 2. What the Legacy Table Did
Each row represented:

departmentid -> which department

modid -> which module (1 = Live Help, 2 = CRM, 3 = Q&A)

ordernum -> tab order

isactive -> whether the tab was shown

defaultset -> default tab

This controlled the tabs at the top of the chat window in Crafty Syntax.

It was not:

a module registry

a permission system

a configuration system

a durable data model

It was purely a UI preference table.

# 3. Why Lupopedia Does Not Import This Table
a. All public-facing modules are active by default
Lupopedia's philosophy:

If a module is installed, it is visible

Admins can disable modules per department later

No hidden or partial module states

b. Legacy mappings were inconsistent
Many Crafty Syntax installs:

never configured module visibility

had partial or broken mappings

had departments that didn't match modern modules

Importing this data would create more confusion than clarity.

c. The new table is clean and explicit
lupo_modules_departments is a modern, doctrine-aligned table:

no foreign keys

no unsigned

no display widths

lifecycle fields

soft-delete

explicit enable/disable flags

d. The legacy table contains no durable business data
It only stored UI preferences.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_modules_dep ENGINE=InnoDB;
ALTER TABLE livehelp_modules_dep CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- No import
There is:

no INSERT

no SELECT

no mapping

Step 4 -- Drop legacy table
Removed after migration.

# 5. Mapping Summary
Legacy -> New
Code
livehelp_modules_dep -> DROPPED (no import)
Replacement
Code
lupo_modules_departments
Fields preserved
None -- the legacy table is not imported.

# 6. Doctrine Notes
This migration is a perfect example of:

Preserving capability, not legacy mechanics
We keep the ability to control module visibility per department.
We do not keep the legacy table that implemented it.

Modern replacement is explicit and clean
lupo_modules_departments provides:

per-department module visibility

lifecycle fields

soft-delete

admin UI control

no foreign keys

no unsigned

no display widths

The Slope Principle
We do not attempt to interpret or import legacy UI routing rules.
We start fresh with a clean, explicit configuration model.

# 7. Final Decision
Code
livehelp_modules_dep -> DROPPED (no import)
New table lupo_modules_departments handles module visibility.
All modules enabled by default; admins may disable them later.
