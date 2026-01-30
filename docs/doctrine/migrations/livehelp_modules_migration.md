# Migration Note: livehelp_modules
# Status: DROPPED
# Replacement: lupo_modules (predefined module registry)

# 1. Summary
livehelp_modules was Crafty Syntax's early attempt at a "module system," but it was not a real modular architecture. It stored:

a module ID

a module name

a file path

...and nothing else.

It was used only to populate the "Modules/Tabs" section of the old admin UI.
It had no lifecycle, no versioning, no metadata, no routing, and no schema.

Lupopedia replaces this entirely with a real module registry:

Code
lupo_modules
which contains:

predefined module IDs

module types

versioning

config JSON

lifecycle fields

actor ownership

federation awareness

Because Lupopedia already defines the canonical module list, the legacy table is not imported and is dropped after migration.

# 2. What the Legacy Table Did
Crafty Syntax used livehelp_modules to:

show which "tabs" were available in the admin UI

enable/disable certain features

load module PHP files

It was not:

a dependency system

a plugin system

a package registry

a module lifecycle manager

It was essentially a UI menu list.

Legacy module IDs (for historical reference only)
1 -> Crafty Syntax (Live Help)

2 -> Leads

3 -> Questions & Answers

These IDs are preserved in Lupopedia's predefined module registry.

# 3. Why It's Dropped
Lupopedia's module system is:

predefined

versioned

typed

documented

schema-aware

doctrine-aligned

The legacy table:

contains no durable data

contains no configuration

contains no metadata

is redundant with lupo_modules

is not used by any modern subsystem

Therefore:

no fields are imported

no mapping is created

the table is dropped

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert table for safe reading
Code
ALTER TABLE livehelp_modules ENGINE=InnoDB;
ALTER TABLE livehelp_modules CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- No import
There is:

no INSERT

no SELECT

no mapping

Step 4 -- Drop after migration
Removed once the migration completes.

# 5. Mapping Summary
Legacy -> New
Code
livehelp_modules -> DROPPED
Replacement
Code
lupo_modules (predefined module registry)
Fields preserved
None.

# 6. Doctrine Notes
This migration is a perfect example of:

Replacing a placeholder with a real subsystem
Crafty Syntax's module table was a UI artifact.
Lupopedia's module registry is a real architectural component.

The Slope Principle
We do not attempt to "import" or "interpret" the legacy module list.
We rely on Lupopedia's canonical module definitions.

# 7. Final Decision
Code
livehelp_modules -> DROPPED (no import)
Module definitions come from lupo_modules.
Legacy table removed.
