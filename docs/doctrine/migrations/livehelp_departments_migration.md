# Doctrine Path
/docs/doctrine/migrations/livehelp_departments_migration.md

# Migration Note: livehelp_departments

# Status
IMPORTED -> SPLIT -> DROPPED

# Replacement
- lupo_departments
- lupo_department_metadata

# 1. What the Legacy Table Did
livehelp_departments was Crafty Syntax's attempt at a "department" system, but in practice it was:

- a routing group for operators
- a branding container for images, colors, and themes
- a behavior configuration bucket (timeouts, invites, email settings)
- a UI customization store
- a language selector
- a website-scoped grouping mechanism

All of this was jammed into a single table with dozens of unrelated columns.

Key characteristics:
Each row represented a "department" in name only.

Many fields were UI-only (colors, backgrounds, images).
Some fields were behavior toggles (timeout, require name).
Some fields were branding (online/offline images).
Some fields were routing (website -> federation node).
Some fields were analytics leftovers.

It was a kitchen-sink table, not a clean domain object.

# 2. Why It's Split in Lupopedia
Lupopedia separates concerns cleanly:

## a. Core department identity -> lupo_departments
This table stores:

- department_id
- federation_node_id
- name
- description
- department_type
- default_actor_id
- lifecycle fields

This is the semantic department.

## b. All legacy UI/behavior settings -> lupo_department_metadata
Everything that was:

- UI
- branding
- colors
- images
- toggles
- behavior flags
- theme settings

is moved into a single JSON metadata object.

This preserves the legacy meaning without polluting the core schema
