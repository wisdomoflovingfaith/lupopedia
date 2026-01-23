---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.18
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  message: "Created SAMPLE_REFERENCE_ENTRY.md with correct and incorrect examples of reference entries, slug usage, database lookup, and rendering flow. Phase 2 documentation consistency audit correction."
  mood: "00FF00"
tags:
  categories: ["documentation", "examples", "reference-entry"]
  collections: ["core-docs", "examples"]
  channels: ["dev", "public"]
file:
  title: "Sample Reference Entry Examples"
  description: "Correct and incorrect examples of reference entries, slug usage, database lookup, and rendering flow"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Sample Reference Entry Examples

This document provides concrete examples of how Lupopedia handles reference entries, demonstrating correct and incorrect patterns for slug usage, database lookup, and content rendering.

---

## Overview

A **reference entry** in Lupopedia is a semantic pointer to content on the host website. It is NOT stored content â€” it is metadata describing a page that exists elsewhere.

**Key Concepts:**
- **Slug** = Opaque identifier (URL path as seen in browser)
- **Reference Entry** = Database record pointing to host site content
- **Rendering** = Displaying the reference entry with semantic metadata

---

## Example 1: âœ… CORRECT Reference Entry

### Scenario
A user visits: `https://example.com/lupopedia/who/captain-wolfie`

### Step 1: Slug Extraction

```php
// Request URI from browser
$request_uri = '/lupopedia/who/captain-wolfie';

// Extract slug (everything after /lupopedia/)
$public_path = '/lupopedia';
$slug = str_replace($public_path, '', $request_uri);
$slug = trim($slug, '/');

// Result: $slug = 'who/captain-wolfie'
```

âœ… **CORRECT:** Slug is extracted from URL path, not resolved to filesystem.

### Step 2: Database Lookup

```sql
SELECT 
    content_id,
    slug,
    title,
    description,
    host_url,
    collection_id,
    created_ymdhis,
    modified_ymdhis,
    deleted_ymdhis
FROM lupo_content
WHERE slug = 'who/captain-wolfie'
AND deleted_ymdhis = 0
LIMIT 1;
```

**Result:**
```
content_id: 42
slug: who/captain-wolfie
title: Captain Wolfie
description: AI embodiment of the creator
host_url: https://example.com/about/wolfie
collection_id: 1
created_ymdhis: 20260115120000
modified_ymdhis: 20260115120000
deleted_ymdhis: 0
```

âœ… **CORRECT:** Database stores the slug as-is, plus metadata about the host site page.

### Step 3: Load Semantic Edges

```sql
SELECT 
    a.atom_name,
    a.atom_type,
    ca.weight,
    ca.relationship_type
FROM lupo_content_atoms ca
JOIN lupo_atoms a ON ca.atom_id = a.atom_id
WHERE ca.content_id = 42
AND ca.deleted_ymdhis = 0
AND a.deleted_ymdhis = 0
ORDER BY ca.weight DESC;
```

**Result:**
```
atom_name: creator
atom_type: person
weight: 1.0
relationship_type: is_about

atom_name: ai_agent
atom_type: concept
weight: 0.8
relationship_type: has_role
```

âœ… **CORRECT:** Semantic edges connect the reference entry to atoms in the knowledge graph.

### Step 4: Render Reference Entry

```php
function render_reference_entry($content) {
    echo '<div class="reference-entry">';
    echo '<h1>' . htmlspecialchars($content['title']) . '</h1>';
    echo '<p class="description">' . htmlspecialchars($content['description']) . '</p>';
    
    // Link to the actual host site page
    echo '<p class="host-link">';
    echo 'View on host site: ';
    echo '<a href="' . htmlspecialchars($content['host_url']) . '">';
    echo htmlspecialchars($content['host_url']);
    echo '</a>';
    echo '</p>';
    
    // Display semantic relationships
    $edges = load_semantic_edges($content['content_id']);
    if (!empty($edges)) {
        echo '<div class="semantic-edges">';
        echo '<h2>Related Concepts</h2>';
        echo '<ul>';
        foreach ($edges as $edge) {
            echo '<li>';
            echo htmlspecialchars($edge['atom_name']);
            echo ' (' . htmlspecialchars($edge['relationship_type']) . ')';
            echo '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }
    
    echo '</div>';
}
```

âœ… **CORRECT:** Renders metadata and semantic relationships, links to host site page.

---

## Example 2: âŒ INCORRECT Reference Entry (Filesystem Resolution)

### Scenario
A user visits: `https://example.com/lupopedia/who/captain-wolfie`

### âŒ WRONG: Attempting Filesystem Resolution

```php
// âŒ WRONG: Trying to resolve slug to filesystem path
$slug = 'who/captain-wolfie';
$filesystem_path = LUPOPEDIA_PATH . '/' . $slug . '.php';

if (file_exists($filesystem_path)) {
    include $filesystem_path;
}
```

**Why This Is Wrong:**
- Slugs are NOT filesystem paths
- Slugs are opaque identifiers for database lookup
- This violates URL_ROUTING_DOCTRINE.md
- This breaks the reference layer model

âœ… **CORRECT APPROACH:** Always look up slugs in the database, never resolve to filesystem.

---

## Example 3: âŒ INCORRECT Reference Entry (Modifying Slugs)

### âŒ WRONG: Normalizing or Rewriting Slugs

```php
// âŒ WRONG: Modifying the slug
$slug = 'who/captain-wolfie';
$normalized_slug = strtolower(str_replace('-', '_', $slug));
// Result: 'who/captain_wolfie'

// âŒ WRONG: Looking up modified slug
$content = lookup_content_by_slug($normalized_slug);
```

**Why This Is Wrong:**
- Slugs must be stored and looked up exactly as received
- Normalization breaks the semantic identity
- This violates CSLH-URL-Semantics.md
- Original URLs must be preserved

âœ… **CORRECT APPROACH:** Store and lookup slugs exactly as they appear in the browser URL.

---

## Example 4: âœ… CORRECT Slug Storage (Crafty Syntax Migration)

### Scenario
Migrating Crafty Syntax `pageurl` to Lupopedia `slug`

### Crafty Syntax Data

```sql
-- Original Crafty Syntax table
SELECT pageurl FROM livehelp_paths_monthly WHERE recno = 12345;
```

**Result:**
```
pageurl: https://wordpress.com/reader/blogs/10822809/posts/54283
```

### Migration to Lupopedia

```sql
-- âœ… CORRECT: Store URL exactly as-is
INSERT INTO lupo_content (
    slug,
    title,
    host_url,
    created_ymdhis,
    modified_ymdhis,
    deleted_ymdhis
) VALUES (
    'https://wordpress.com/reader/blogs/10822809/posts/54283',
    'WordPress Reader Post',
    'https://wordpress.com/reader/blogs/10822809/posts/54283',
    20260115120000,
    20260115120000,
    0
);
```

âœ… **CORRECT:** URL stored exactly as it appeared in Crafty Syntax, no modification.

---

## Example 5: âœ… CORRECT Rendering Flow (Complete)

### Complete Request-to-Response Flow

```php
<?php
// 1. Extract slug from request
$request_uri = $_SERVER['REQUEST_URI'];
$slug = extract_slug_from_uri($request_uri);

// 2. Look up in database (priority order)
$content = lookup_content_by_slug($slug);

if ($content) {
    // Found content entry
    render_reference_entry($content);
    exit;
}

// 3. Try collection tab lookup
$tab = lookup_collection_tab_by_slug($slug);

if ($tab) {
    // Found collection tab
    render_collection_view($tab);
    exit;
}

// 4. Try atom lookup
$atom = lookup_atom_by_name($slug);

if ($atom) {
    // Found atom
    render_semantic_page($atom);
    exit;
}

// 5. Not found
render_404($slug);
```

âœ… **CORRECT:** Follows lookup priority, never touches filesystem.

---

## Example 6: âŒ INCORRECT Rendering (Storing Content)

### âŒ WRONG: Treating Reference Entry as Stored Content

```php
// âŒ WRONG: Trying to load content from Lupopedia
function render_reference_entry($content) {
    $content_file = LUPOPEDIA_PATH . '/lupo-content/' . $content['slug'] . '.html';
    
    if (file_exists($content_file)) {
        echo file_get_contents($content_file);
    }
}
```

**Why This Is Wrong:**
- Reference entries don't store content in Lupopedia
- Content lives on the host website
- Lupopedia only stores metadata and semantic relationships
- This violates Lupopedia-Reference-Layer-Doctrine.md

âœ… **CORRECT APPROACH:** Render metadata and link to host site page, don't store content.

---

## Summary: Correct vs Incorrect Patterns

### âœ… CORRECT Patterns

1. **Slug Extraction:** Extract from URL path after `/lupopedia/`
2. **Database Lookup:** Query `lupo_content` table with exact slug
3. **No Filesystem Resolution:** Never resolve slugs to disk paths
4. **Exact Storage:** Store slugs exactly as received, no normalization
5. **Reference Layer:** Render metadata and link to host site
6. **Semantic Edges:** Load and display relationships from knowledge graph

### âŒ INCORRECT Patterns

1. **Filesystem Resolution:** Treating slugs as file paths
2. **Slug Modification:** Normalizing, rewriting, or changing slugs
3. **Content Storage:** Storing actual content in Lupopedia
4. **Module Assumptions:** Treating slugs as module names
5. **Path Assumptions:** Assuming directory structure from slugs
6. **Host Site Interference:** Modifying or replacing host site content

---

## Related Documentation

- [URL_ROUTING_DOCTRINE.md](../../doctrine/URL_ROUTING_DOCTRINE.md) - HTTP URL routing rules
- [CSLH-URL-Semantics.md](../../doctrine/CSLH-URL-Semantics.md) - Crafty Syntax URL semantics
- [Lupopedia-Reference-Layer-Doctrine.md](../../doctrine/Lupopedia-Reference-Layer-Doctrine.md) - Reference layer principles
- [SUBDIRECTORY_INSTALLATION_DOCTRINE.md](../../doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md) - Path handling rules

---

**These examples are canonical and binding for all Lupopedia implementations.**
