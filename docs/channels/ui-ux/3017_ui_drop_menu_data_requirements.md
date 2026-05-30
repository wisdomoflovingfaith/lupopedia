> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/ui-ux/3.0.17-UI_DROP_MENU_DATA_REQUIREMENTS.md"
  file_hash: "5578f4e02eaf142be34a02f62d1ea9af9cf607aabebfbf00f1701b4de852f3f1"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\ui-ux\3.0.17-UI_DROP_MENU_DATA_REQUIREMENTS.md"
  file_hash: "40b52a5fb25944aa825795a9835fd7a497d60c4d4f20440929f94efa3ee15e34"
  file_path_from_root: "docs\channels\ui-ux\3.0.17-UI_DROP_MENU_DATA_REQUIREMENTS.md"
  file_hash: "d04534d1cfadba917a024480c4c159163dc1a5ddd607c7176e473916e497ab54"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 3.0.17-UI_DROP_MENU_DATA_REQUIREMENTS.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "ui-ux", "3017-ui_drop_menu_data_requirementsmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.18
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  mood_vector: "00FF00"
  message: "Created documentation for UI drop-menu data requirements. Documents data structures needed for collection_selector, collection_tabs, and default_tabs components. Includes manual seeding instructions for 3.0.17 UI integration work."
tags:
  categories: ["documentation", "ui", "data"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "UI Drop-Menu Data Requirements"
  description: "Data structure requirements and manual seeding instructions for UI drop-menu components in Lupopedia 3.0.17"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# UI Drop-Menu Data Requirements

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** Published  
**Last Updated:** 2026-01-15

## Overview

This document describes the data requirements for UI drop-menu components in Lupopedia 3.0.17. These components require manually seeded data to function correctly.

## Components

1. **collection_selector.php** - Saved collections navigation bar with dropdown menus
2. **collection_tabs.php** - Tabs dropdown menu (shortcutDropdown)
3. **default_tabs.php** - Contents dropdown menu (content sections)

---

## 1. Collection Selector Component

### Data Source

**Function:** `render_saved_collections($userId)`  
**File:** `includes/functions/render-saved-collections.php`  
**Variable:** `$collectionsData`

### Data Structure

```php
$collectionsData = [
    'who' => [
        'tabs' => [
            [
                'tab_name' => 'WOLFIE',
                'tab_id' => 1,
                'item_count' => 5,
                'children' => [
                    [
                        'item_type' => 'content',
                        'content_id' => 123,
                        'title' => 'Content Title',
                        'item_id' => 123
                    ],
                    [
                        'item_type' => 'tab',
                        'tab_name' => 'Sub Tab',
                        'tab_id' => 2,
                        'children' => []
                    ]
                ]
            ]
        ],
        'count' => 10
    ],
    'what' => [...],
    'where' => [...],
    'when' => [...],
    'why' => [...],
    'how' => [...],
    'do' => [...]
];
```

### Database Tables

- `lupo_saved_collections` - Collections table
- `lupo_saved_collection_items` - Items within collections
- `lupo_collection_tabs` - Tab structure
- `lupo_content` - Content items

### Manual Seeding Requirements

**Collections Data:**
- At least one collection per type (WHO, WHAT, WHERE, WHEN, WHY, HOW, DO)
- Each collection should have at least one tab
- Tabs may have nested sub-tabs
- Tabs may contain content items or links

**Example SQL:**
```sql
-- Insert collection
INSERT INTO lupo_saved_collections (collection_name, collection_type, user_id, created_ymdhis)
VALUES ('System Collection', 'who', 1, 20260115000000);

-- Insert tab
INSERT INTO lupo_collection_tabs (collection_id, tab_name, parent_tab_id, slug, created_ymdhis)
VALUES (1, 'WOLFIE', NULL, 'wolfie', 20260115000000);

-- Insert content item
INSERT INTO lupo_saved_collection_items (collection_id, tab_id, content_id, item_type, created_ymdhis)
VALUES (1, 1, 123, 'content', 20260115000000);
```

---

## 2. Collection Tabs Component

### Data Source

**Function:** `load_collection_tabs($collection_id)`  
**File:** `includes/functions/collection-tabs-loader.php`  
**Variable:** `$tabs_data`

### Data Structure

```php
$tabs_data = [
    'WHO' => [
        '_slug' => 'who',
        'WOLFIE' => [],
        'CAPTAIN' => []
    ],
    'WHAT' => [
        '_slug' => 'what',
        'SOFTWARE' => [],
        'HARDWARE' => []
    ]
];
```

### Database Tables

- `lupo_collection_tabs` - Tab structure
- `lupo_saved_collections` - Collections table

### Manual Seeding Requirements

**Tabs Data:**
- At least one main tab per collection
- Each main tab should have a slug
- Sub-tabs are optional
- Tabs should be organized hierarchically

**Example SQL:**
```sql
-- Insert main tab
INSERT INTO lupo_collection_tabs (collection_id, tab_name, parent_tab_id, slug, created_ymdhis)
VALUES (1, 'WHO', NULL, 'who', 20260115000000);

-- Insert sub-tab
INSERT INTO lupo_collection_tabs (collection_id, tab_name, parent_tab_id, slug, created_ymdhis)
VALUES (1, 'WOLFIE', 1, 'wolfie', 20260115000000);
```

---

## 3. Default Tabs Component

### Data Source

**Variable:** `$content['content_sections']`  
**Format:** JSON array of section IDs

### Data Structure

```php
$content_sections = [
    'news-and-updates',
    'download-crafty-syntax',
    'how-to-use-crafty-syntax',
    'legacy-overview'
];
```

### Database Column

**Table:** `lupo_content`  
**Column:** `content_sections` (JSON)

### Manual Seeding Requirements

**Content Sections:**
- Content must have HTML headers with IDs
- Sections are extracted from headers automatically
- Stored as JSON array in `content_sections` column
- Format: Array of section ID strings

**Example SQL:**
```sql
-- Update content with sections
UPDATE lupo_content
SET content_sections = '["news-and-updates", "download-crafty-syntax", "how-to-use-crafty-syntax"]'
WHERE content_id = 1;
```

**Example HTML:**
```html
<h2 id="news-and-updates">News and Updates</h2>
<h2 id="download-crafty-syntax">Download Crafty Syntax</h2>
<h2 id="how-to-use-crafty-syntax">How to Use Crafty Syntax</h2>
```

---

## Integration Points

### main_layout.php

All three components are included in `main_layout.php`:

```php
// Collection selector
include LUPO_UI_PATH . '/components/collection_selector.php';

// Collection tabs
include LUPO_UI_PATH . '/components/collection_tabs.php';

// Default tabs
include LUPO_UI_PATH . '/components/default_tabs.php';
```

### Variable Initialization

Variables are initialized in `main_layout.php`:

```php
// Collections data
$collectionsData = isset($collectionsData) ? $collectionsData : [];

// Tabs data
$tabs_data = isset($tabs_data) ? $tabs_data : [];
$collection_id = isset($collection_id) ? $collection_id : 0;

// Content sections
$content_sections = isset($content['content_sections']) ? $content['content_sections'] : [];
```

### Controller Integration

Controllers should pass data via `render_main_layout()` metadata:

```php
render_main_layout($page_body, [
    'collectionsData' => $collectionsData,
    'tabs_data' => $tabs_data,
    'collection_id' => $collection_id,
    'content' => $content
]);
```

---

## Testing Checklist

- [ ] Collection selector renders with seeded collections data
- [ ] Collection tabs dropdown displays tabs correctly
- [ ] Default tabs dropdown displays content sections
- [ ] Menus toggle correctly (open/close)
- [ ] Keyboard navigation works (Enter, Space, Escape)
- [ ] Click-outside-to-close works
- [ ] Submenus render correctly
- [ ] Empty states display correctly
- [ ] Data flows correctly from controllers to components

---

## Related Files

- **Components:**
  - `includes/ui/components/collection_selector.php`
  - `includes/ui/components/collection_tabs.php`
  - `includes/ui/components/default_tabs.php`
  - `includes/ui/components/saved-collections-nav.php`

- **Functions:**
  - `includes/functions/render-saved-collections.php`
  - `includes/functions/collection-tabs-loader.php`

- **Layout:**
  - `includes/ui/layouts/main_layout.php`

---

*Last Updated: January 15, 2026*  
*Version: GLOBAL_CURRENT_LUPOPEDIA_VERSION*  
*Status: Published*  
*Author: GLOBAL_CURRENT_AUTHORS*
