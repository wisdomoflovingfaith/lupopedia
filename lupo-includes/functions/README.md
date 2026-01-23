---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  mood_RGB: "0066FF"
  message: "Added WOLFIE header to Functions README. Enforced Dialog Thread Mapping Rule - linked to changelog_dialog.md as no dedicated dialog thread exists."
tags:
  categories: ["documentation", "functions", "php"]
  collections: ["core-docs"]
  channels: ["dev"]
in_this_file_we_have:
  - Functions Directory Overview
  - Directory Structure Documentation
  - Function Documentation Guidelines
file:
  title: "Functions Directory"
  description: "Documentation for PHP functions that handle business logic and data processing"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Functions Directory

This directory contains PHP functions that handle business logic and data processing.

## Directory Structure

Functions are organized by purpose. Each function file should be well-documented with:
- Purpose and description
- Parameter types and descriptions
- Return value types and descriptions
- Usage examples
- Related files and dependencies

## Function Files

### render-saved-collections.php

**Purpose:** Renders saved collections navigation data structure.

**Function:** `render_saved_collections($userId)`

**Description:**
Loads data from the database and builds nested arrays for the saved collections navigation. Queries `lupo_collections`, `lupo_collection_tabs`, and `lupo_collection_tab_map` to build the data structure needed by the component template.

**IMPORTANT:** Uses `lupo_permissions` (polymorphic permissions table) as the ONLY source of truth for access control. Only returns collections the user has permission to read (via user_id or group_id). Uses `target_type='collection'` and `target_id` to identify collection permissions. The `user_id` and `group_id` fields in `lupo_collections` are metadata only, not access control.

**Parameters:**
- `$userId` (int) - The current user ID

**Returns:**
- `array` - Array structure for the component template, keyed by collection type

**Data Structure:**
```php
[
    'collections' => [
        'type' => [
            'type' => string,
            'count' => int,
            'tabs' => [
                [
                    'id' => int,
                    'tab_name' => string,
                    'sort_order' => int,
                    'children' => array,
                    'item_count' => int
                ]
            ]
        ]
    ]
]
```

**Database Tables Used:**
- `lupo_permissions` - Polymorphic access control table (REQUIRED, uses target_type='collection' and target_id)
- `lupo_actor_group_membership` - User group memberships
- `lupo_collections` - Collection metadata
- `lupo_collection_tabs` - Tab definitions
- `lupo_collection_tab_map` - Tab content mapping (polymorphic)
- `lupo_content` - Content titles for content items

**Helper Functions:**
- `load_tab_children($db, $tabId)` - Loads children of a tab recursively
- `count_tab_items($db, $tabId)` - Counts items in a tab recursively

**Related Files:**
- Component: `/lupo-includes/ui/components/saved-collections-nav.php`
- Usage: Called from `header.php`

**Access Control:**
- Checks `lupo_permissions` (polymorphic table) for user-based permissions (target_type='collection')
- Checks `lupo_permissions` for group-based permissions (via `lupo_actor_group_membership`, target_type='collection')
- Only returns collections user has permission to read
- Does NOT rely on `user_id` or `group_id` in `lupo_collections` for access control

## Function Development Rules

1. Functions handle business logic and database queries
2. Functions return data arrays, never HTML
3. All database queries must use PDO prepared statements
4. Functions must be well-documented with docblocks
5. Access control must use `lupo_permissions` (polymorphic table) with `target_type='collection'` for collections
6. Functions should be reusable and not tightly coupled to specific templates
