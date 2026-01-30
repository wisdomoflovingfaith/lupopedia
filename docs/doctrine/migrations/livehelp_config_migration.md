# Doctrine Path
/docs/doctrine/migrations/livehelp_config_migration.md

# Migration Note: livehelp_config

# Status
PARTIALLY IMPORTED -> DROPPED

# Replacement
lupo_modules.config_json (module_id = 1)

# 1. What the Legacy Table Did
livehelp_config was the global configuration table for Crafty Syntax Live Help. It stored:

- UI settings
- tracking flags
- admin refresh intervals
- chat behavior toggles
- SMTP settings
- theme and color options
- directory/search/game toggles
- analytics limits
- cookie behavior
- operator timeouts
- and dozens of other global flags

It was effectively the entire system settings registry for the old platform.

Important:
Crafty Syntax assumed exactly one row in this table. All settings were global, not per-module.

# 2. Why It's Dropped
Lupopedia replaces this with:

## a. Module-scoped configuration
Each module has:

Code
lupo_modules.config_json
This is:

- structured
- typed
- versioned
- namespaced
- future-proof
- compatible with AI agents
- compatible with doctrine-driven configuration

## b. No more global "everything in one row" settings
The monolithic config table is obsolete.

## c. Many legacy settings no longer apply
Examples:

- showgames
- showdirectory
- keywordtrack
- reftracking
- maxoldhits
- colorscheme
- floatxy

These were UI hacks or analytics hacks from 2003-2011.

## d. SMTP settings are now part of the system-wide mailer
Not module-specific.

## e. Cookie behavior is replaced by the identity helper
No more rememberusers, usecookies, matchip, etc.

# 3. Migration Behavior (as seen in the SQL)
Step 1 - Convert table for safe reading
Code
ALTER TABLE livehelp_config ENGINE=InnoDB;
ALTER TABLE livehelp_config CONVERT TO utf8mb4;
This ensures the table can be SELECTed safely during migration.

Step 2 - Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
This signals that the table is not part of the new schema.

Step 3 - Extract the single row into JSON
The SQL block:

- SELECTs the first and only row
- Converts all fields into a JSON object
- Inserts that JSON into lupo_modules.config_json
- Targets module_id = 1 (Crafty Syntax module)

This preserves:

- all legacy settings
- in a structured, readable, future-proof format
- without needing the old table

Step 4 - Table is dropped after migration
No further references exist.

# 4. Mapping Summary
Legacy -> New
Code
livehelp_config.* -> lupo_modules.config_json (module_id = 1)
Fields preserved
All fields are preserved as JSON keys.

Fields transformed
None - values are copied verbatim.

Fields dropped
None - but many will be ignored by the new module.

Replacement
lupo_modules.config_json becomes the canonical configuration store.

# 5. Doctrine Notes
This migration is a perfect example of:

The Slope Principle
Instead of rewriting or reinterpreting legacy settings, we:

- preserve them as JSON
- store them in the module
- allow the new module to interpret them gradually
- avoid breaking legacy behavior
- avoid forcing premature decisions

This is a gentle slope, not a staircase.

Human-in-loop relevance
Operators and admins will eventually configure the new module through:

- UI settings
- module metadata
- doctrine files

But the legacy settings remain available for reference.

# 6. Final Decision
Code
livehelp_config -> PARTIALLY IMPORTED -> DROPPED
All fields preserved as JSON in lupo_modules.config_json.
Table removed after migration.
