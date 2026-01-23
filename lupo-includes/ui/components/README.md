---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.19
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  mood_RGB: "0066FF"
  message: "Added WOLFIE header to UI Components README. Enforced Dialog Thread Mapping Rule - linked to changelog_dialog.md as no dedicated dialog thread exists."
tags:
  categories: ["documentation", "ui", "components"]
  collections: ["core-docs"]
  channels: ["dev"]
in_this_file_we_have:
  - UI Components Directory Overview
  - Directory Structure Documentation
  - Component Organization Guidelines
file:
  title: "UI Components Directory"
  description: "Documentation for reusable UI component templates in Lupopedia"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# UI Components Directory

This directory contains reusable UI component templates for Lupopedia.

## Directory Structure

- `components/` - Individual UI component templates
- `layouts/` - Page layout templates
- `templates/` - Template files for various UI elements

## Component Templates

### topbar.php

Top Navigation Bar component.

**Purpose:** Provides global top navigation bar with Lupopedia modules and user profile dropdown.

**Usage:**
```php
// In main_layout.php (automatically included)
// Variables initialized before inclusion:
$currentUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$isUserLoggedIn = ($currentUserId > 0);
include LUPO_UI_PATH . '/components/topbar.php';
```

**Parameters:**
- `$currentUserId` - Current user ID (integer)
- `$isUserLoggedIn` - Boolean indicating if user is logged in

**Features:**
- Lupopedia modules navigation (Home, Q/A, Content, Collections, Users, Agents)
- User profile dropdown with messages, profile, history, settings, admin links
- Messages icon with badge count
- Login/Register links for non-logged-in users
- Keyboard shortcuts and accessibility support

**Related Files:**
- Layout: `/lupo-includes/ui/layouts/main_layout.php`
- CSS: Included in component (inline styles)
- JavaScript: Toggle functions defined in component

---

### footer.php

Footer component with semantic navigation bar.

**Purpose:** Global footer with semantic navigation icons and Crafty Syntax live help integration.

**Usage:**
```php
// In main_layout.php (automatically included)
// Variables passed from render_main_layout():
$content = [...]; // Current content row
$prevContent = [...]; // Previous content (optional)
$nextContent = [...]; // Next content (optional)
$contentReferences = [...]; // Array of reference content
$contentLinks = [...]; // Array of linked content
$contentTags = [...]; // Array of tag strings
include LUPO_UI_PATH . '/components/footer.php';
```

**Parameters:**
- `$content` - Current content row (array)
- `$prevContent` - Previous content in sequence (array or null)
- `$nextContent` - Next content in sequence (array or null)
- `$contentReferences` - Array of reference content data
- `$contentLinks` - Array of linked content data
- `$contentTags` - Array of tag strings
- `$isUserLoggedIn` - Boolean for like/share functionality

**Features:**
- Semantic navigation icons: Previous Page, References, Context, Tags, Share, Like, Links, Collection, Next Page, Help
- Keyboard shortcuts (Alt+Arrow keys, Alt+R/C/T/S/L/H)
- Badge counts for references, tags, and links
- Previous/next content navigation with slug-based routing
- Disabled states for unavailable navigation
- Integrates with semantic panel component

**Related Files:**
- Layout: `/lupo-includes/ui/layouts/main_layout.php`
- Semantic Panel: `/lupo-includes/ui/components/semantic_panel.php`
- CSS: Included in component (inline styles)
- JavaScript: Navigation functions defined in component

---

### content_outline.php

Content Outline component (right panel).

**Purpose:** Right-side panel displaying content outline/table of contents extracted from HTML headers.

**Usage:**
```php
// In main_layout.php (automatically included)
// Variables passed from render_main_layout():
$content = [...]; // Current content row with content_sections field
$contentSections = [...]; // Optional: pre-parsed sections array
include LUPO_UI_PATH . '/components/content_outline.php';
```

**Parameters:**
- `$content` - Current content row with `content_sections` field
- `$contentSections` - Optional: pre-parsed sections array

**Features:**
- Parses `content_sections` from database or extracts from HTML headers
- Hierarchical section navigation with smooth scrolling
- Active section highlighting based on scroll position
- Collapsible panel with toggle button
- Responsive (hidden on small screens)

**Related Files:**
- Layout: `/lupo-includes/ui/layouts/main_layout.php`
- CSS: Included in component (inline styles)
- JavaScript: Scroll tracking and navigation functions defined in component

---

### semantic_panel.php

Semantic Panel component (sliding panel).

**Purpose:** Sliding panel displaying semantic metadata triggered by footer navigation buttons.

**Usage:**
```php
// In main_layout.php (automatically included)
// Variables passed from render_main_layout():
$content = [...]; // Current content row
$contentReferences = [...]; // Array of reference content
$contentLinks = [...]; // Array of linked content
$contentTags = [...]; // Array of tag strings
$semanticContext = [...]; // Semantic context from ConnectionsService
$contentCollection = [...]; // Collection data (optional)
include LUPO_UI_PATH . '/components/semantic_panel.php';
```

**Parameters:**
- `$content` - Current content row
- `$contentReferences` - Array of reference content data
- `$contentLinks` - Array of linked content data
- `$contentTags` - Array of tag strings
- `$semanticContext` - Semantic context array (atoms, parents, children, siblings, related_content)
- `$contentCollection` - Collection data (array or null)

**Features:**
- Panel types: References, Context, Tags, Links, Collection
- Slides up from bottom (above footer nav bar)
- Dynamic content loading based on panel type
- Keyboard shortcuts (Escape to close)
- Click-outside to close functionality
- Integrates with footer semantic navigation bar

**Related Files:**
- Layout: `/lupo-includes/ui/layouts/main_layout.php`
- Footer: `/lupo-includes/ui/components/footer.php`
- CSS: Included in component (inline styles)
- JavaScript: Panel toggle and content rendering functions defined in component

---

### semantic_map.php

Semantic Map component (left panel).

**Purpose:** Left-side panel visualizing semantic relationships and atom hierarchy.

**Usage:**
```php
// In main_layout.php (automatically included)
// Variables passed from render_main_layout():
$content = [...]; // Current content row
$semanticContext = [...]; // Semantic context from ConnectionsService
include LUPO_UI_PATH . '/components/semantic_map.php';
```

**Parameters:**
- `$content` - Current content row
- `$semanticContext` - Semantic context array with:
  - `atoms` - Direct atom connections
  - `parents` - Parent atoms (hierarchical)
  - `children` - Child atoms (hierarchical)
  - `siblings` - Sibling atoms
  - `related_content` - Related content via shared atoms
  - `edge_type_summary` - Summary of edge types

**Features:**
- Displays atom hierarchy (parents, children, siblings)
- Shows direct atom connections with weights
- Edge type summary display
- Color-coded relationship types (parent=green, direct=blue, child=cyan, sibling=yellow)
- Collapsible panel with toggle button
- Responsive (hidden on small screens)

**Related Files:**
- Layout: `/lupo-includes/ui/layouts/main_layout.php`
- ConnectionsService: `/lupo-includes/class-ConnectionsService.php`
- CSS: Included in component (inline styles)
- JavaScript: Panel toggle function defined in component

---

### saved-collections-nav.php

Saved Collections Navigation component.

**Purpose:** Renders the saved collections navigation HTML structure.

**Usage:**
```php
// In renderer function or page template
$collectionsData = render_saved_collections($userId);
$isUserLoggedIn = true;
include(LUPO_INCLUDES_DIR . '/ui/components/saved-collections-nav.php');
```

**Parameters:**
- `$collectionsData` - Array structure from `render_saved_collections()` function
- `$isUserLoggedIn` - Boolean indicating if user is logged in

**Features:**
- Dynamically renders collections based on user permissions
- Supports nested tabs with recursive rendering
- Preserves existing HTML structure, CSS classes, and JavaScript function names
- Handles aria attributes and data attributes for accessibility

**Rendering Logic:**
- Receives data arrays from the renderer (no database queries)
- Outputs HTML only (no business logic)
- Uses recursive function for nested tab structures
- Maintains exact HTML structure from original implementation

**Related Files:**
- Renderer: `/lupo-includes/functions/render-saved-collections.php`
- CSS: Included in main header styles
- JavaScript: Toggle functions defined in header.php

---

### collections_dropdown.php

Collections Dropdown component.

**Purpose:** Renders a dropdown menu for selecting and managing user collections. Lists collections accessible to the current user and triggers tab loading when a collection is selected.

**Usage:**
```php
// In topbar.php or main_layout.php
$collection_id = 3; // Current collection ID (optional)
$isUserLoggedIn = true; // User login state
include LUPO_UI_PATH . '/components/collections_dropdown.php';
```

**Parameters:**
- `$currentCollectionId` - Currently selected collection ID (int or null)
- `$isUserLoggedIn` - Boolean indicating if user is logged in (optional, defaults to session check)

**Features:**
- Lists user collections via AJAX from `api/list_user_collections.php`
- Highlights currently selected collection
- Includes Save/Load/Edit actions as dropdown menu options
- Triggers tab loading when collection is selected via `loadCollectionTabs()`
- Styled to match topbar navigation links
- Login-gated actions (Save/Load/Edit require login)

**JavaScript Functions:**
- `selectCollection(collectionId, collectionName)` - Selects a collection and loads its tabs
- `loadCollectionTabs(collectionId, collectionName)` - Loads tabs for selected collection
- `checkLoginAndSave()` - Opens save collection modal (requires login)
- `checkLoginAndLoad()` - Opens load collection modal (requires login)
- `checkLoginAndEdit()` - Opens edit collection page (requires login)

**Related Files:**
- API: `/api/list_user_collections.php` - Returns user collections
- API: `/api/load_collection_tabs.php` - Returns tabs for a collection
- Layout: `/lupo-includes/ui/layouts/main_layout.php` - Contains modal and collection management functions
- Topbar: `/lupo-includes/ui/components/topbar.php` - Includes this component

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION - Created for UI layout template integration

---

## Component Development Rules

1. Components receive data arrays from renderer functions
2. Components output HTML only - no database queries
3. Preserve existing HTML structure, CSS classes, and JavaScript function names
4. Maintain aria attributes and data attributes for accessibility
5. Use recursion for nested structures
6. Keep components focused on presentation only
