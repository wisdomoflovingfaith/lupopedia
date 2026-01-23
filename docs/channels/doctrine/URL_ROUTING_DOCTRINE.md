---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.18
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  message: "Phase 2 documentation consistency audit corrections: Added explicit slug lookup priority (content > collection tab > atom > 404), conflict resolution rules, collision examples, and namespace prevention strategies."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "routing", "url-routing"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Project Root Model for Cursor
  - URL Routing Overview
  - Slug Extraction
  - Slug Lookup Priority
  - Conflict Resolution Rules
  - Collision Examples
  - Content Rendering
  - Forbidden Behaviors
  - Implementation Example
  - Related Documentation
file:
  title: "URL Routing Doctrine"
  description: "Canonical rules for how Lupopedia handles incoming HTTP requests, extracts slugs from URLs, and routes to appropriate reference entries, collection views, or semantic pages."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# URL Routing Doctrine

> **âš ï¸ DISAMBIGUATION NOTE:**  
> This file documents **HTTP URL ROUTING** (slug extraction and database lookup) â€” how Lupopedia handles incoming HTTP requests.  
> For **AGENT ROUTING** (HERMES/CADUCEUS message routing to AI agents), see [AGENT_ROUTING_DOCTRINE.md](AGENT_ROUTING_DOCTRINE.md).

This doctrine defines how Lupopedia handles incoming HTTP requests, extracts slugs from URLs, and routes to appropriate content. This is **URL routing** (HTTP request handling), distinct from **agent routing** (HERMES/CADUCEUS message routing to AI agents).

---

## Canonical Routing Doctrine (New Standard)

### A. Global Content Route (canonical URL)

**Route:** `/lupopedia/content/<slug>`

**Rules:**
- `<slug>` resolves to `lupo_contents.slug`
- This is the primary, canonical URL for all content
- No collection prefix, no numeric IDs
- Must return the content record and its metadata
- Must 301-redirect legacy URLs to this canonical form

**Controller:** `ContentController::showBySlug($slug)`

### B. Truth Lookup Route (semantic ontology)

**Route:** `/lupopedia/truth/<who|what|where|when|why|how>/<slug>`

**Rules:**
- `<slug>` resolves to content_id
- The first segment resolves to a semantic dimension
- Controller returns filtered edges or semantic interpretations
- Must support JSON and HTML output
- Must not modify database schema

**Controller:** `TruthController::lookup($dimension, $slug)`

**Valid Dimensions:** `who`, `what`, `where`, `when`, `why`, `how`

### C. Edge Traversal Route (graph API)

**Preferred:** `/lupopedia/edge/<slug>`

**Acceptable fallback:** `/lupopedia/edge/id/<content_id>`

**Rules:**
- Returns incoming + outgoing edges
- Includes edge types, related content, and metadata
- Must not modify schema
- Must not require collection context

**Controller:** `EdgeController::edgesForSlug($slug)`

---

## Legacy Route Handling

### Legacy Route (Deprecated)

**Route:** `/lupopedia/collection/<id>/content/<slug>`

**Requirements:**
- Keep functional temporarily
- Add 301 redirect to `/lupopedia/content/<slug>`
- Do NOT remove the legacy controller yet
- Mark legacy route as deprecated in doctrine

**Redirect Logic:**
1. Extract `<slug>` from legacy URL
2. Perform 301 redirect to canonical `/lupopedia/content/<slug>`
3. Log redirect for analytics

---

## Controller Responsibilities

### ContentController
- `showBySlug($slug)` - Primary content display
- Handle 301 redirects from legacy URLs
- Return content with metadata and related edges

### TruthController  
- `lookup($dimension, $slug)` - Semantic dimension filtering
- Validate dimension against allowed list
- Support both JSON and HTML output formats
- Filter edges by semantic dimension

### EdgeController
- `edgesForSlug($slug)` - Graph traversal API
- Return incoming and outgoing edges
- Include edge metadata and related content
- Support both slug and ID-based lookups

---

## Routing Precedence

1. **Canonical routes first** (highest priority)
2. **Truth lookup routes** (second priority)  
3. **Edge traversal routes** (third priority)
4. **Legacy routes** (lowest priority - redirect only)

---

## Error Handling

### 404 Handling
- Content not found: Return 404 with suggested alternatives
- Invalid truth dimension: Return 400 with valid dimensions list
- Edge not found: Return 404 with content suggestions

### Redirect Handling
- Legacy URLs: 301 redirect to canonical form
- Slug collisions: 301 redirect to primary content
- Collection-based URLs: 301 redirect to content-only URLs

---

## Lookup Rules

### Slug Resolution Priority
1. **Direct content slug match** (`lupo_contents.slug`)
2. **Truth dimension + slug** (semantic lookup)
3. **Edge traversal** (graph-based lookup)
4. **Legacy collection/slug** (redirect only)
5. **404 not found**

### Database Queries (No Schema Modifications)
```sql
-- Content lookup
SELECT * FROM lupo_contents WHERE slug = ? AND is_deleted = 0

-- Truth dimension lookup  
SELECT e.*, c.* FROM lupo_edges e
JOIN lupo_contents c ON (e.left_object_id = c.content_id OR e.right_object_id = c.content_id)
WHERE c.slug = ? AND e.edge_type LIKE ?

-- Edge traversal
SELECT * FROM lupo_edges 
WHERE left_object_id = ? OR right_object_id = ?
```

---

## Implementation Requirements

### DO NOT
- Modify schema
- Modify migrations  
- Modify version numbers
- Modify doctrine outside routing
- Reorganize files

### MUST
- Add new route patterns to router
- Implement controller methods
- Add lookup helper functions
- Update routing doctrine
- Ensure proper redirect handling

---

## Project Root Model for Cursor

**You are working inside the `/lupopedia/` directory.**  
This directory is the Lupopedia SYSTEM directory.  
You must treat `/lupopedia/` as the root of the Lupopedia application.

### Critical Assumptions

**IMPORTANT:**
- You do **NOT** know and should **NOT** care what exists above `/lupopedia/`.
- The parent directory structure will be different on every installation.
- Do **NOT** assume `/lupopedia/` is the webroot.
- Do **NOT** assume anything about the host website.

---

## Routing Doctrine

Lupopedia is always accessed through URLs of the form:

```
https://whateverwebsite.com/lupopedia/{slug}
```

### Your Job

1. **Read the `{slug}` from the request.**
   - Extract the slug from the URL path after `/lupopedia/`
   - The slug is the opaque identifier (URL path as seen in browser)
   - Slugs are NOT module names or filesystem paths

2. **Look up the slug in the Lupopedia database tables.**
   - Query `lupo_content` table for matching slugs
   - Query `lupo_collection_tabs` for collection navigation
   - Query `lupo_atoms` for semantic references
   - Query other relevant tables based on content type

3. **Render the appropriate content.**
   - Reference entry (semantic pointer to host site page)
   - Collection view (navigation tabs and structure)
   - Semantic page (atom-based content)
   - Module-specific content (TRUTH, Crafty Syntax, etc.)

---

## What You Must NOT Do

You must **NOT**:

- âŒ Attempt to resolve or modify any files outside `/lupopedia/`.
- âŒ Assume any specific directory structure above `/lupopedia/`.
- âŒ Rewrite host-site URLs.
- âŒ Interfere with the host website in any way.
- âŒ Treat slugs as filesystem paths.
- âŒ Resolve slugs to disk locations.
- âŒ Modify slugs from their stored form.

---

## Core Principle

**Lupopedia is a self-contained semantic OS living inside a subdirectory.**  
All logic must operate **ONLY** within `/lupopedia/`.

---

## URL Structure

### Standard Pattern

```
https://{host-domain}/{lupopedia-path}/{slug}
```

**Examples:**
- `https://example.com/lupopedia/who/captain_wolfie`
- `https://mysite.org/lupopedia/collection/3/tab/misc-doctrine`
- `https://whatever.com/programs/lupopedia/truth/what-is-lupopedia`

### Slug Extraction

The slug is everything after the Lupopedia public path:

```php
// Example: https://example.com/lupopedia/who/captain_wolfie
// LUPOPEDIA_PUBLIC_PATH = '/lupopedia'
// slug = 'who/captain_wolfie'

$request_uri = $_SERVER['REQUEST_URI'];
$public_path = LUPOPEDIA_PUBLIC_PATH; // e.g., '/lupopedia'
$slug = str_replace($public_path, '', $request_uri);
$slug = trim($slug, '/');
```

---

## Slug Lookup Priority

When a slug is extracted from the URL, Lupopedia performs lookups in the following priority order:

### Priority 1: Content Lookup

```sql
SELECT * FROM lupo_content 
WHERE slug = :slug 
AND deleted_ymdhis = 0
LIMIT 1;
```

**Purpose:** Find reference entries (semantic pointers to host site pages)

**When This Matches:** The slug corresponds to a content item in the knowledge graph

**Result:** Render reference entry with semantic metadata and link to host site page

---

### Priority 2: Collection Tab Lookup

```sql
SELECT * FROM lupo_collection_tabs 
WHERE slug = :slug 
AND collection_id = :collection_id
AND deleted_ymdhis = 0
LIMIT 1;
```

**Purpose:** Find navigation tabs within collections

**When This Matches:** The slug corresponds to a collection navigation structure

**Result:** Render collection view with tab hierarchy and content

---

### Priority 3: Atom Lookup

```sql
SELECT * FROM lupo_atoms 
WHERE atom_name = :slug
AND deleted_ymdhis = 0
LIMIT 1;
```

**Purpose:** Find semantic atoms (concepts, entities, categories)

**When This Matches:** The slug corresponds to a semantic atom in the knowledge graph

**Result:** Render semantic page showing atom relationships and connected content

---

### Priority 4: 404 Not Found

If no match is found in any of the above lookups, return a 404 error.

---

## Conflict Resolution Rules

### Rule 1: First Match Wins

Lookups are performed in priority order. The first successful match determines the response.

**Example:**
- If a slug matches both a content entry AND an atom, the content entry wins (Priority 1)
- If a slug matches both a collection tab AND an atom, the collection tab wins (Priority 2)

### Rule 2: Slug Uniqueness by Type

Slugs should be unique within each type:
- Content slugs should be unique in `lupo_content`
- Collection tab slugs should be unique within a collection in `lupo_collection_tabs`
- Atom names should be unique in `lupo_atoms`

### Rule 3: Namespace Collision Prevention

To prevent collisions between types, use namespacing conventions:

**Content Slugs:**
```
who/captain-wolfie
what/lupopedia
where/honolulu
```

**Collection Tab Slugs:**
```
collection/1/tab/who
collection/1/tab/what
```

**Atom Names:**
```
atom:creator
atom:ai_agent
atom:semantic_os
```

### Rule 4: Explicit Collision Handling

If a collision occurs (same slug in multiple tables):

1. **Log the collision** for administrator review
2. **Follow priority order** (content > collection tab > atom)
3. **Provide admin tools** to rename conflicting slugs
4. **Document the collision** in system logs

---

## Collision Examples

### Example 1: Content vs Atom Collision

**Scenario:**
- Content slug: `creator`
- Atom name: `creator`

**Resolution:**
- Priority 1 (content) wins
- User sees reference entry for content
- Atom is accessible via alternate path: `/lupopedia/atom/creator`

**Prevention:**
- Use namespaced atom names: `atom:creator`
- Use descriptive content slugs: `who/creator`

---

### Example 2: Collection Tab vs Content Collision

**Scenario:**
- Content slug: `about`
- Collection tab slug: `about`

**Resolution:**
- Priority 1 (content) wins
- User sees reference entry for content
- Collection tab is accessible via explicit path: `/lupopedia/collection/1/tab/about`

**Prevention:**
- Use collection-prefixed tab slugs: `collection/1/tab/about`
- Use descriptive content slugs: `pages/about`

---

### Example 3: No Collision (Different Namespaces)

**Scenario:**
- Content slug: `who/captain-wolfie`
- Atom name: `atom:captain_wolfie`
- Collection tab slug: `collection/1/tab/who`

**Resolution:**
- No collision â€” all slugs are distinct
- Each is accessible via its natural path
- System operates normally

âœ… **BEST PRACTICE:** Use namespacing conventions to prevent collisions.

---

## Content Rendering

### Reference Entry

If slug matches a content entry:
- Load content metadata from `lupo_content`
- Load semantic edges from `lupo_content_atoms`
- Render reference entry template
- Display semantic relationships

### Collection View

If slug matches a collection tab:
- Load collection metadata from `lupo_collections`
- Load tab structure from `lupo_collection_tabs`
- Render collection navigation template
- Display tab hierarchy and content

### Semantic Page

If slug matches an atom:
- Load atom metadata from `lupo_atoms`
- Load related content from `lupo_content_atoms`
- Render semantic page template
- Display atom relationships and content

---

## Relationship to Other Doctrine

### Subdirectory Installation Doctrine

This doctrine works in conjunction with [SUBDIRECTORY_INSTALLATION_DOCTRINE.md](SUBDIRECTORY_INSTALLATION_DOCTRINE.md):
- `LUPOPEDIA_PATH` = filesystem path to `/lupopedia/`
- `LUPOPEDIA_PUBLIC_PATH` = URL path prefix (e.g., `/lupopedia`)

### CSLH URL Semantics

Slugs follow the same semantics as Crafty Syntax URLs:
- URLs are web-facing slugs exactly as seen in browser
- URLs must NEVER be resolved to disk
- URLs must NEVER be treated as filesystem paths
- See [CSLH-URL-Semantics.md](CSLH-URL-Semantics.md)

### Reference Layer Doctrine

Lupopedia is a semantic reference layer:
- Content entries are reference-book entries describing host site pages
- Slugs are opaque identifiers, not module names
- System works alongside host website, not instead of it
- See [Lupopedia-Reference-Layer-Doctrine.md](Lupopedia-Reference-Layer-Doctrine.md)

---

## Implementation Example

### Front Controller (`index.php`)

```php
<?php
// Define paths
define('LUPOPEDIA_PATH', __DIR__);
define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(__DIR__));

// Extract slug from request
$request_uri = $_SERVER['REQUEST_URI'];
$slug = str_replace(LUPOPEDIA_PUBLIC_PATH, '', $request_uri);
$slug = trim($slug, '/');

// If no slug, show default/home page
if (empty($slug)) {
    $slug = 'home';
}

// Look up slug in database
$content = lookup_content_by_slug($slug);

if ($content) {
    // Render reference entry
    render_reference_entry($content);
} else {
    // Try collection tab lookup
    $tab = lookup_collection_tab_by_slug($slug);
    if ($tab) {
        render_collection_view($tab);
    } else {
        // Try atom lookup
        $atom = lookup_atom_by_name($slug);
        if ($atom) {
            render_semantic_page($atom);
        } else {
            // 404 - slug not found
            render_404($slug);
        }
    }
}
```

---

## Summary (Non-Negotiable)

### For Cursor and All AI Agents

âœ… **ALWAYS:**
- Treat `/lupopedia/` as the project root
- Extract slugs from URLs after `/lupopedia/`
- Look up slugs in Lupopedia database tables
- Render appropriate content based on lookup results
- Operate ONLY within `/lupopedia/` directory

âŒ **NEVER:**
- Assume anything about parent directory structure
- Resolve slugs to filesystem paths
- Modify host website files or routing
- Interfere with host website in any way
- Treat slugs as module names or file locations

---

## Related Documentation

- [SUBDIRECTORY_INSTALLATION_DOCTRINE.md](SUBDIRECTORY_INSTALLATION_DOCTRINE.md) - Path constants and filesystem/URL resolution
- [CSLH-URL-Semantics.md](CSLH-URL-Semantics.md) - URL semantics (slugs as opaque identifiers)
- [Lupopedia-Reference-Layer-Doctrine.md](Lupopedia-Reference-Layer-Doctrine.md) - Lupopedia as semantic reference layer
- [AGENT_ROUTING_DOCTRINE.md](AGENT_ROUTING_DOCTRINE.md) - Agent message routing (HERMES/CADUCEUS), distinct from URL routing
- [SAMPLE_REFERENCE_ENTRY.md](../appendix/examples/SAMPLE_REFERENCE_ENTRY.md) - Concrete examples of correct and incorrect reference entry patterns

---

This doctrine is **absolute and binding** for all Lupopedia URL routing code.
