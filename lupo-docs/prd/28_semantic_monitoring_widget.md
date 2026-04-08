---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260406162955"
  file_path_from_root: "lupo-docs/prd/28_semantic_monitoring_widget.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/28_semantic_monitoring_widget.md"
  last_modified_utc: "20260406162955"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-semantic-monitoring-widget"
  prd_id: 28
  prd_slug: semantic_monitoring_widget
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: doctrine
  artifact_kind: ui_widget
  purpose: "PRD for Semantic Monitoring Widget (The Eye) — JavaScript page tracking, semantic data collection, and floating navigation bar with optional visual effect"
  status: "approved"
  tags:
    - prd
    - semantic_monitoring
    - ui_widget
    - the_eye
    - v4.0.94

lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor — all rules in this PRD are subordinate to the root constitutional requirements"
    - to: "lupo-docs/prd/01_core_identity.md"
      type: references
      weight: 1.0
      reason: "Widget tracks actor behavior and identity"
    - to: "lupo-docs/prd/11_analytics_tracking.md"
      type: references
      weight: 1.0
      reason: "Widget sends analytics data to tracking system"
    - to: "lupo-docs/prd/05_collections_navigation.md"
      type: references
      weight: 1.0
      reason: "Widget tracks navigation paths and collections"
    - to: "lupo-docs/prd/03_truth_knowledge.md"
      type: references
      weight: 1.0
      reason: "Widget integrates with truth questions and engagement"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_visits.md"
      type: references
      weight: 1.0
      reason: "Previous/next page navigation — entercontentid, exitcontentid, session_id columns"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_edges.md"
      type: references
      weight: 1.0
      reason: "Semantic edges — left_object_type, right_object_type, edge_type, semantic_weight, weight_score"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_collections.md"
      type: references
      weight: 1.0
      reason: "Collection metadata — collection_id, name, slug, color, actor_id, published_ymdhis"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_actor_collections.md"
      type: references
      weight: 1.0
      reason: "Actor-to-collection membership — actor_id, collection_id, access_level"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_collection_tab_map.md"
      type: references
      weight: 1.0
      reason: "Maps content items to collection tabs — item_type, item_id, collection_tab_id"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_collection_tabs.md"
      type: references
      weight: 1.0
      reason: "Collection tab metadata — collection_tab_id, collection_id, name, slug, color"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_comments.md"
      type: references
      weight: 1.0
      reason: "Comments — target_type, target_id, actor_id, comment_text"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_actor_actions.md"
      type: references
      weight: 1.0
      reason: "Share tracking — action_type, entity_type, entity_id"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_contexts.md"
      type: references
      weight: 0.9
      reason: "Context metadata — context_id, context_name, context_code"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_contexts_map.md"
      type: references
      weight: 0.9
      reason: "Context membership — context_id, item_type, item_slug (NOT item_id)"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_sessions.md"
      type: references
      weight: 0.9
      reason: "Session tracking for co-occurrence analysis — session_id, actor_id"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_metadata.md"
      type: references
      weight: 0.8
      reason: "Namespace/class metadata — entity_type, entity_id, class_name, property_key"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_referers.md"
      type: references
      weight: 0.8
      reason: "Referrer tracking — content_id, referer_url, referer_domain, date_ymd"
    - to: "lupo-docs/database/lupopedia/tables/semantic_navbar/lupo_hashtags.md"
      type: references
      weight: 1.0
      reason: "Hashtag registry — hashtag_id, tag_slug, label, use_count"
    - to: "lupo-docs/database/lupopedia/tables/semantic_navbar/lupo_hashtag_map.md"
      type: references
      weight: 1.0
      reason: "Hashtag-to-content junction — hashtag_id, object_type, object_id"
    - to: "lupo-docs/database/lupopedia/tables/semantic_navbar/lupo_folders.md"
      type: references
      weight: 1.0
      reason: "Folder metadata — folder_id, name, slug, parent_folder_id"
    - to: "lupo-docs/database/lupopedia/tables/semantic_navbar/lupo_folder_map.md"
      type: references
      weight: 1.0
      reason: "Folder-to-content junction — folder_id, object_type, object_id"
    - to: "lupo-docs/database/lupopedia/tables/semantic_navbar/lupo_references.md"
      type: references
      weight: 1.0
      reason: "Citation/source records — reference_id, url, title, citation_text"
    - to: "lupo-docs/database/lupopedia/tables/semantic_navbar/lupo_reference_links.md"
      type: references
      weight: 1.0
      reason: "Reference-to-content junction — reference_id, object_type, object_id"
    - to: "lupo-docs/database/lupopedia/tables/lupo_truth_questions.md"
      type: references
      weight: 1.0
      reason: "Truth questions — truth_question_id, target_object_type, target_object_id, asked_by_actor_id"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_truth_answers.md"
      type: references
      weight: 1.0
      reason: "Truth answers — truth_answer_id, truth_question_id, actor_id, answer_text"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_actor_traits.md"
      type: references
      weight: 0.8
      reason: "Actor traits for widget personalization — actor_id, trait_key, trait_value"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_contents.md"
      type: references
      weight: 1.0
      reason: "Content resolution — content_id, slug, title, like_count, share_count, comment_count"
    - to: "lupo-docs/database/lupopedia/tables/semantic_navbar/SEMANTIC_NAVBAR_OVERVIEW.md"
      type: references
      weight: 1.0
      reason: "Canonical overview of navbar feature-to-table mapping and data flow"
    - to: "lupo-ui/js/lupo.js"
      type: references
      weight: 1.0
      reason: "Widget implementation and JavaScript architecture"
    - to: "lupo-docs/database/lupopedia/tables/active/lupo_paths.md"
      type: references
      weight: 1.0
      reason: "Previous/next page aggregated flows — entercontentid, exitcontentid, count_num"
    - to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
      type: references
      weight: 1.0
      reason: "Canonical DDL — paths, references, reference_links, hashtags, hashtag_map, folders, folder_map (merged from former migration proposal)"
    - to: "lupo-docs/doctrine/SEMANTIC_MONITORING_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Architecture doctrine — Eye vs livehelp_js, routing truth, IDE must-not-guess UI"

lupopedia.footer:
  last_verified: "20260406162955"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: "cursor:root"
  next_action:
    - "Verify lupo_truth_questions column names against TOON JSON before implementing Q/A endpoint"
    - "Confirm lupo_paths_summary path_id foreign key target before implementing path aggregation"
    - "Add content_id once imported via import_content.py"
---

# PRD: Semantic Monitoring Widget (The Eye) — v4.0.94

## Purpose

Provides comprehensive semantic monitoring, page tracking, and user interaction analysis through "The Eye" widget. Enables Lupopedia to understand user behavior, track navigation patterns, and maintain semantic context across the entire site experience.

**Constitutional compliance:** All components follow `lupo-docs/prd/00_root_constitutional_system_requirements.md`. No foreign keys, no triggers, BIGINT timestamps, `IdGenerator::generate()` for all PKs, soft delete everywhere.

## Department 1 — Domain Root Installation Context

- Department 1 represents the root of the domain where Lupopedia is installed.
- Lupopedia is ALWAYS installed in a subdirectory (e.g., example.com/lupopedia).
- Installation occurs through auto-installers such as Softaculous.
- The installer upgrades Crafty Syntax 3.7.5 into Lupopedia.
- Department 1 users manage domain-level integration of Lupopedia.

## Department Creation Rules

- Auth_users in Department 0 or Department 1 may create new departments.
- Departments 2+ are defined by the installation and its domain scope.
- Departments created by the installation inherit structure from Crafty Syntax import.
- Assigning a user to Department 0 or Department 1 MUST show a warning in the web interface.
- Warnings do NOT block assignment; they inform the user of elevated authority.

## Crafty Syntax Import

- During installation, existing Crafty Syntax departments are imported.
- Imported departments become Departments 2+ unless explicitly mapped to Department 1.
- Actors are created during installation based on imported operators and agents.

## Actor Creation Rules

- Actors are created in two ways:
  1. During installation (imported from Crafty Syntax operator roles).
  2. By auth_users pairing an agent with a department.
- Each actor belongs to exactly one department.
- Auth_users may only select actors that belong to their department.

## Auth User → Actor Selection

- Auth_users log in and then select an actor assigned to their department.
- Using that actor, the auth_user may:
  - answer live help chats from visitors
  - talk to other actors on the site
  - participate in channels and threads

## Channels and Threads

- All actor conversations occur inside channels.
- Each channel contains multiple threads.
- All threads in a channel share the same department context.

## Semantic Monitoring Widget

- Department 1 users embed a cut-and-paste JavaScript snippet into their website.
- The widget monitors:
  - page enter/exit events
  - visitor navigation paths
  - next/previous page predictions
- The widget provides a floating navigation bar with:
  - comments
  - likes
  - shares
- The widget can launch a “collections” top floating nav bar.
- Collections group related pages into dropdown menus.

## Actor Learning Boundaries

- Core/system actors include: Wolfie, Lilith, Kiros, Thoth, and any future system-level actors.
- Core/system actors may ONLY learn from auth_users in Department 0.
- Department 0 represents HPC-style, dependency-first, parallel cognition.
- If Department 0 contains only one auth_user (the architect), this is valid and intentional.
- Non-core actors may learn from auth_users in their own department.
- Cross-department learning is NOT permitted unless explicitly defined in a PRD.

## Why This Matters

- Ensures correct separation of authority between Department 0, Department 1, and Departments 2+.
- Prevents contamination of core/system actors by vibe-driven or framework-default patterns.
- Preserves constitutional engineering across all agents.
- Aligns installation behavior with Crafty Syntax upgrade path.
- Clarifies how actors, departments, and auth_users interact in the installed system.

---

## Schema — canonical install (present)

**Status:** Required tables are **present** in **`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`** (single canonical schema for 4.0.x). Runtime names use `LUPO_TABLE_PREFIX` (typically `lupo_`); install DDL uses the `{{prefix}}` placeholder.

| Widget / PRD name | `CREATE TABLE` in install SQL |
|-------------------|------------------------------|
| `lupo_paths` | `{{prefix}}paths` |
| `lupo_references` | `{{prefix}}references` |
| `lupo_reference_links` | `{{prefix}}reference_links` |
| `lupo_hashtags` | `{{prefix}}hashtags` |
| `lupo_hashtag_map` | `{{prefix}}hashtag_map` |
| `lupo_folders` | `{{prefix}}folders` |
| `lupo_folder_map` | `{{prefix}}folder_map` |

**Historical note:** `lupo-database/lupopedia/mysql/migrations/add_semantic_navbar_tables_20260401.sql` was a **proposal**; its shapes were **consolidated into** `install_new_lupopedia.sql`. Fresh installs do **not** run that migration separately.

After any future DDL edit to install SQL, regenerate TOONs when your workflow requires it:

```bash
python lupo-scripts/generate_toon_files.py
```

---

## Core vs Visual Architecture

The Eye widget has two independent layers:

| Layer | Purpose | Enabled By Default | Can Be Disabled |
|-------|---------|-------------------|-----------------|
| Core Monitoring | Tracks paths, referrers, engagement, collections | Yes | No (required for analytics) |
| Visual Effect | Floating eyes that follow mouse, blink, change color | Yes | Yes (configurable) |

```php
// In lupopedia-config.php
define('EYE_VISUAL_EFFECT_ENABLED', true);  // Toggle the floating eyes
define('EYE_CORE_MONITORING_ENABLED', true); // Always true, not user-configurable
```

---

## Widget Architecture

### JavaScript Components (`lupo-ui/js/lupo.js`)

1. **LupoState** — Holds current visible DOM state, manages active contexts and temporal color coding
2. **SemanticMonitor** — Links UI to context edges, validates 63-bit positive integrity, monitors DB connection
3. **HighDensityScroller** — 60fps virtualization for glass bubbles, dynamic viewport management

---

## Floating Navigation Bar — The Eye Command Center

Fixed bar at bottom-right. Each icon shows a count badge and opens a dropdown.

### Bar Icons

| # | Icon | Label | Primary Table | Key Columns |
|---|------|-------|---------------|-------------|
| 1 | ← | Previous Pages | `lupo_paths` | `exitcontentid`, `count_num` |
| 2 | 📄 | Referencing Pages | `lupo_edges` | `right_object_id`, `edge_type='references'`, `semantic_weight` |
| 3 | 📁 | Contexts | `lupo_contexts_map` | `item_type`, `item_slug` |
| 4 | 🏷️ | Hashtags/Tags | `lupo_hashtags` + `lupo_hashtag_map` | `hashtag_id`, `object_type`, `object_id` |
| 5 | 📤 | Shares | `lupo_actor_actions` | `action_type='share'`, `entity_type`, `entity_id` |
| 6 | 🔗 | Inbound Links | `lupo_edges` | `right_object_id`, `edge_type='links_to'` |
| 7 | 📂 | Namespaces/Classes | `lupo_metadata` | `entity_type`, `entity_id`, `class_name` |
| 8 | 📁 | Folders | `lupo_folders` + `lupo_folder_map` | `folder_id`, `object_type`, `object_id` |
| 9 | → | Next Pages | `lupo_paths` | `entercontentid`, `count_num` |
| 10 | 💬 | Comments | `lupo_comments` | `target_type`, `target_id` |
| 11 | ❓ | Questions | `lupo_truth_questions` + `lupo_truth_answers` | `target_object_type`, `target_object_id` |
| 12 | 🔗 | All Edges | `lupo_edges` | `left_object_id` OR `right_object_id` |
| 13 | 👁️ | Live Help | chat system | online status |

### Verified Query Patterns

All queries below use confirmed column names from TOON JSON / table docs. All use `DatabaseFactory::getConnection()` and `LUPO_TABLE_PREFIX`.

#### 1. Previous Pages (←)

Source: `lupo_paths` — confirmed columns: `exitcontentid`, `entercontentid`, `count_num`, `is_deleted`

```php
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$rows = $db->fetchAll(
    "SELECT entercontentid, SUM(count_num) AS frequency
     FROM {$prefix}paths
     WHERE exitcontentid = :cid AND is_deleted = 0
     GROUP BY entercontentid
     ORDER BY frequency DESC
     LIMIT 10",
    array('cid' => $contentId)
);
```

#### 2. Referencing Pages (📄)

Source: `lupo_edges` — confirmed columns: `right_object_type`, `right_object_id`, `edge_type`, `semantic_weight`, `is_deleted`

```php
$rows = $db->fetchAll(
    "SELECT left_object_id, semantic_weight
     FROM {$prefix}edges
     WHERE right_object_type = 'content'
       AND right_object_id = :cid
       AND edge_type = 'references'
       AND is_deleted = 0
     ORDER BY semantic_weight DESC
     LIMIT 10",
    array('cid' => $contentId)
);
```

#### 3. Contexts (📁)

Source: `lupo_contexts_map` — confirmed columns: `context_id`, `item_type`, `item_slug`, `is_deleted`

**Important:** `lupo_contexts_map` uses `item_slug` (varchar), NOT `item_id`. Resolve `content_id` to slug first via `lupo_contents.slug`.

```php
$rows = $db->fetchAll(
    "SELECT cm.context_id, c.context_name, c.context_code
     FROM {$prefix}contexts_map cm
     JOIN {$prefix}contexts c ON c.context_id = cm.context_id
     WHERE cm.item_type = 'content'
       AND cm.item_slug = :slug
       AND cm.is_deleted = 0",
    array('slug' => $contentSlug)
);
```

#### 4. Hashtags/Tags (🏷️)

Source: `lupo_hashtags` + `lupo_hashtag_map` — confirmed columns: `hashtag_id`, `tag_slug`, `label`, `object_type`, `object_id`, `is_deleted`

Do NOT use `lupo_metadata` or `lupo_contents.hashtags` JSON for new tag queries.

```php
$rows = $db->fetchAll(
    "SELECT h.hashtag_id, h.tag_slug, h.label
     FROM {$prefix}hashtags h
     JOIN {$prefix}hashtag_map m ON m.hashtag_id = h.hashtag_id AND m.is_deleted = 0
     WHERE m.object_type = 'content'
       AND m.object_id = :cid
       AND h.is_deleted = 0",
    array('cid' => $contentId)
);
```

#### 5. Shares (📤)

Source: `lupo_actor_actions` — confirmed columns: `action_type`, `entity_type`, `entity_id`, `actor_id`, `created_ymdhis`

```php
$rows = $db->fetchAll(
    "SELECT actor_id, created_ymdhis
     FROM {$prefix}actor_actions
     WHERE action_type = 'share'
       AND entity_type = 'content'
       AND entity_id = :cid
     ORDER BY created_ymdhis DESC
     LIMIT 20",
    array('cid' => $contentId)
);
```

#### 6. Inbound Links (🔗)

Source: `lupo_edges` — confirmed columns: `left_object_type`, `left_object_id`, `right_object_type`, `right_object_id`, `edge_type`, `is_deleted`

```php
$rows = $db->fetchAll(
    "SELECT left_object_id
     FROM {$prefix}edges
     WHERE right_object_type = 'content'
       AND right_object_id = :cid
       AND edge_type = 'links_to'
       AND is_deleted = 0
     LIMIT 20",
    array('cid' => $contentId)
);
```

#### 7. Namespaces/Classes (📂)

Source: `lupo_metadata` — confirmed columns: `entity_type`, `entity_id`, `class_name`, `property_key`, `property_value`, `is_deleted`

```php
$rows = $db->fetchAll(
    "SELECT DISTINCT class_name
     FROM {$prefix}metadata
     WHERE entity_type = 'content'
       AND entity_id = :cid
       AND class_name IS NOT NULL
       AND is_deleted = 0",
    array('cid' => $contentId)
);
```

#### 8. Folders (📁)

Source: `lupo_folders` + `lupo_folder_map` — confirmed columns: `folder_id`, `name`, `slug`, `object_type`, `object_id`, `is_deleted`

```php
$rows = $db->fetchAll(
    "SELECT f.folder_id, f.name, f.slug
     FROM {$prefix}folders f
     JOIN {$prefix}folder_map m ON m.folder_id = f.folder_id AND m.is_deleted = 0
     WHERE m.object_type = 'content'
       AND m.object_id = :cid
       AND f.is_deleted = 0
     ORDER BY f.sort_order, f.name",
    array('cid' => $contentId)
);
```

#### 9. Next Pages (→)

Source: `lupo_paths` — confirmed columns: `entercontentid`, `exitcontentid`, `count_num`, `is_deleted`

```php
$rows = $db->fetchAll(
    "SELECT exitcontentid, SUM(count_num) AS frequency
     FROM {$prefix}paths
     WHERE entercontentid = :cid AND is_deleted = 0
     GROUP BY exitcontentid
     ORDER BY frequency DESC
     LIMIT 10",
    array('cid' => $contentId)
);
```

#### 10. Comments (💬)

Source: `lupo_comments` — confirmed columns: `target_type`, `target_id`, `actor_id`, `comment_text`, `created_ymdhis`, `is_deleted`

```php
$rows = $db->fetchAll(
    "SELECT comment_id, actor_id, comment_text, created_ymdhis
     FROM {$prefix}comments
     WHERE target_type = 'content'
       AND target_id = :cid
       AND is_deleted = 0
     ORDER BY created_ymdhis DESC
     LIMIT 10",
    array('cid' => $contentId)
);
```

#### 11. Questions (❓)

Source: `lupo_truth_questions` + `lupo_truth_answers`

**Important:** `lupo_truth_knowledge` is deprecated. Use `lupo_truth_questions` (columns: `truth_question_id`, `target_object_type`, `target_object_id`, `asked_by_actor_id`, `is_deleted`) and `lupo_truth_answers` (columns: `truth_answer_id`, `truth_question_id`, `actor_id`, `answer_text`, `is_deleted`).

```php
$rows = $db->fetchAll(
    "SELECT truth_question_id, asked_by_actor_id, question_status, is_answered
     FROM {$prefix}truth_questions
     WHERE target_object_type = 'content'
       AND target_object_id = :cid
       AND is_deleted = 0
     ORDER BY created_ymdhis DESC
     LIMIT 10",
    array('cid' => $contentId)
);
```

#### 12. All Edges (🔗)

Source: `lupo_edges` — confirmed columns: `left_object_type`, `left_object_id`, `right_object_type`, `right_object_id`, `edge_type`, `is_deleted`

```php
$rows = $db->fetchAll(
    "SELECT edge_id, left_object_type, left_object_id,
            right_object_type, right_object_id, edge_type
     FROM {$prefix}edges
     WHERE ((left_object_type = 'content' AND left_object_id = :cid)
        OR  (right_object_type = 'content' AND right_object_id = :cid))
       AND is_deleted = 0
     ORDER BY edge_type, created_ymdhis DESC",
    array('cid' => $contentId)
);
```

---

## Collections System — Two-Tier Architecture

### Tier 1: Personal Collections (Light Blue)

Source tables (confirmed columns):
- `lupo_actor_collections`: `actor_id`, `collection_id`, `access_level`, `is_deleted`
- `lupo_collections`: `collection_id`, `name`, `slug`, `color`, `is_deleted`
- `lupo_collection_tab_map`: `collection_tab_id`, `item_type`, `item_id`, `is_deleted`
- `lupo_collection_tabs`: `collection_tab_id`, `collection_id`, `name`, `slug`, `color`

```php
// Actor's collections
$rows = $db->fetchAll(
    "SELECT c.collection_id, c.name, c.slug, c.color
     FROM {$prefix}collections c
     JOIN {$prefix}actor_collections ac ON ac.collection_id = c.collection_id
     WHERE ac.actor_id = :aid
       AND ac.is_deleted = 0
       AND c.is_deleted = 0",
    array('aid' => $actorId)
);

// Collections containing current page (via collection_tab_map)
$rows = $db->fetchAll(
    "SELECT c.collection_id, c.name, c.slug, ct.name AS tab_name
     FROM {$prefix}collection_tab_map ctm
     JOIN {$prefix}collection_tabs ct ON ct.collection_tab_id = ctm.collection_tab_id
     JOIN {$prefix}collections c ON c.collection_id = ct.collection_id
     WHERE ctm.item_type = 'content'
       AND ctm.item_id = :cid
       AND ctm.is_deleted = 0
       AND c.is_deleted = 0",
    array('cid' => $contentId)
);
```

**Note:** `lupo_collections` has no `is_public` column. Shared/public collections are identified by `published_ymdhis IS NOT NULL AND published_ymdhis > 0`.

### Tier 2: Emergent Collections (Light Green)

Discovered from session co-occurrence analysis via `lupo_visits` (`session_id`, `entercontentid`, `exitcontentid`). See `lupo-docs/prd/05_collections_navigation.md` for full emergent collection doctrine.

---

## Visual Effect: The Eyes (Optional)

When `EYE_VISUAL_EFFECT_ENABLED` is true, a pair of animated eyes float to the bottom-right, follow the mouse, blink, and cycle colors.

**This code was written in 1999 and still works in 2026 browsers.**

| File | Purpose |
|------|---------|
| `dynlayer.js` | Cross-browser layer positioning (Dynamic Duo, 1999) |
| `images/*.gif` | Eye sprites |

```php
<?php if (EYE_VISUAL_EFFECT_ENABLED): ?>
<script src="<?php echo LUPOPEDIA_PUBLIC_PATH; ?>dynlayer.js"></script>
<script>/* existing eye animation code */</script>
<?php endif; ?>
```

User preference stored in `lupo_actor_traits` (`actor_id`, `trait_key='eye_visual_effect'`, `trait_value='false'`).

---

## API Endpoints

| Icon | Endpoint | Primary Table |
|------|----------|---------------|
| ← | `GET /api/page/previous-pages` | `lupo_paths` |
| 📄 | `GET /api/page/referencing-pages` | `lupo_edges` |
| 📁 | `GET /api/page/contexts` | `lupo_contexts_map`, `lupo_contexts` |
| 🏷️ | `GET /api/page/tags` | `lupo_hashtags`, `lupo_hashtag_map` |
| 📤 | `GET /api/page/shares` | `lupo_actor_actions` |
| 🔗 | `GET /api/page/inbound-links` | `lupo_edges` |
| 📂 | `GET /api/page/namespaces` | `lupo_metadata` |
| 📁 | `GET /api/page/folders` | `lupo_folders`, `lupo_folder_map` |
| → | `GET /api/page/next-pages` | `lupo_paths` |
| 💬 | `GET /api/page/comments` | `lupo_comments` |
| ❓ | `GET /api/page/questions` | `lupo_truth_questions`, `lupo_truth_answers` |
| 🔗 | `GET /api/page/edges` | `lupo_edges` |
| 👁️ | `GET /api/chat/status` | chat system |

### API routing: clean URLs vs query parameters (constitutional)

**PRD 00 §2** and **§9.5** require that **no core API depends on `mod_rewrite`.** Handlers **must** accept identifiers via **query parameters** (and may also accept **PATH_INFO** or path segments when rewrites are present).

| Mode | When it applies | Example (under `LUPOPEDIA_PUBLIC_PATH`) |
|------|-----------------|----------------------------------------|
| **Clean URL** | `mod_rewrite` + `.htaccess` (or stack equivalent) | `GET .../api/page/tags/123` (path segment = `content_id`) |
| **Fallback (required)** | Always — shared hosting without `AllowOverride`, Nginx, IIS, etc. | `GET .../api/page/tags?page_id=123` (or `index.php?route=...` + documented params) |

**Server behavior (normative for implementers):**

1. Resolve **`content_id` / `page_id`** from **`PATH_INFO`**, rewrite-captured path segments, or **query string** — in a **fixed precedence** documented per endpoint (typically prefer path when present, else query).
2. **Do not** require pretty URLs for correctness; **do not** fail closed when only the fallback form is available.

**Table note:** The “Endpoint” column above uses **slash notation** for readability; **query-parameter equivalents** for the same operations are **mandatory** unless an explicit exception is **APPROVED** in a decision artifact.

All PHP handlers use `DatabaseFactory::getConnection()` and `LUPO_TABLE_PREFIX`.

---


## IP Address Detection (RULE 93.IP_DETECTION)

**NEVER use `$_SERVER['REMOTE_ADDR']` alone.** This will almost always return the proxy/VPN/CDN IP, not the user's real IP.

**REQUIRED:** Use the `get_ipaddress()` function that checks headers in priority order:

1. CloudFlare headers (`HTTP_CF_CONNECTING_IP`, `HTTP_TRUE_CLIENT_IP`)
2. Standard proxy headers (`HTTP_X_FORWARDED_FOR`, `HTTP_X_REAL_IP`)
3. RFC 7239 headers (`HTTP_FORWARDED_FOR`, `HTTP_FORWARDED`)
4. Fallback to `REMOTE_ADDR` only if nothing else is available

**Why this matters:**
- Users behind VPNs
- Users behind CDNs (CloudFlare, Fastly)
- Users behind corporate proxies
- Users behind load balancers

**Violation:** Any code that uses `$_SERVER['REMOTE_ADDR']` without checking forwarded headers is constitutionally non-compliant.

## Implementation Checklist

Before writing any PHP for this widget:

- [x] Confirm semantic navbar / path tables exist in `install_new_lupopedia.sql` (see **Schema — canonical install** above).
- [ ] Regenerate TOON files after **new** DDL changes: `python lupo-scripts/generate_toon_files.py`
- [ ] Read table doc for every table before writing any query
- [ ] Confirm `lupo_truth_questions` column names from `lupo-docs/database/lupopedia/tables/lupo_truth_questions.md`
- [ ] Use `DatabaseFactory::getConnection()` — never `new PDO()` or `mysqli_*`
- [ ] Use `LUPO_TABLE_PREFIX . 'tablename'` — never hardcode `lupo_`
- [ ] Use `IdGenerator::generate()` for all new PKs — never `AUTO_INCREMENT` or `null`
- [ ] All timestamps via `gmdate('YmdHis')` — never `time()` or `date()`
- [ ] All queries include `AND is_deleted = 0`


---

## Context‑Typed, Status‑Aware, Directional Edged Memory Doctrine (4.0.96)

1. Memory in Lupopedia is represented as a directed graph of nodes and edges. 
  Each memory node is a first-class entity in the semantic network and may be 
  owned by actors, departments, auth_users, channels, federation nodes, or the 
  global system.

2. Every edge in the memory graph has FOUR dimensions:
  - **edge type** (the relationship)
  - **edge context** (the classification of the memory)
  - **edge status** (the epistemic support level)
  - **edge direction** (the traversal orientation)

3. **Edge Direction** defines whether the relationship is:
  - unidirectional (A → B)
  - bidirectional (A ↔ B)
  - restricted-direction (A → B but not B → A unless explicitly defined)
  Reverse traversal MUST NOT be inferred unless explicitly defined.

4. **Edge Type** defines the relationship between nodes, including but not 
  limited to:
  - influences
  - inherits
  - authored_by
  - observed_by
  - contradicts
  - supports
  - consolidates_from
  - refines
  - overrides

5. **Edge Context** defines the classification of the memory node. Context is 
  not based on the content of the memory, but on the structural support 
  provided by the graph. The primary context classifications are:
  - doctrine
  - experiential
  - system_generated
  - countermeasure_generated
  - summary
  - contradictory
  - deprecated

6. **Edge Status** defines the epistemic support level of the memory node:
  - **unsupported**: insufficient supporting edges; provisional memory.
  - **supported**: sufficient supporting edges; validated memory.
  - **needs_review**: conflicting, incomplete, or ambiguous edges requiring 
    agent or human intervention.

7. When `edge_status = 'needs_review'`, a **review_reason** MUST be provided. 
  This field explains *why* the edge requires review and *which agent* should 
  handle it. Examples include:
  - orphaned_edge
  - contradiction
  - new_doctrine
  - schema_drift
  - consolidation_candidate
  - integrity_unknown
  - human_escalation

  Agents use this field to determine their work queues:
  - ANUBIS handles: integrity_unknown, orphaned_edge
  - THOTH handles: schema_drift, contradiction, new_doctrine
  - KAIROS handles: consolidation_candidate
  - Human operator handles: human_escalation

8. Memory nodes may transition between statuses as edges are added, removed, 
  or reclassified. A node may move from unsupported → supported when 
  sufficient supporting edges accumulate.

9. Actors inherit memory edges from:
  - their department
  - their auth_user
  - their federation node
  - their assigned faucets
  - their assigned tasks

10. Memory traversal is context-aware and direction-aware. Actors may only 
   traverse edges permitted by their boundaries, department rules, auth_user 
   pairing, faucet assignments, and operational mode (live, simulation, 
   analysis).

11. No inference is allowed. All edges, contexts, statuses, directions, and 
   review reasons must be explicitly defined in PRDs, database rows, or 
   system-generated memory.

12. Memory is not a flat file. It is a structured, typed, classified, 
   status-aware, direction-aware graph. Traversal depth determines visible 
   memory; deeper traversal reveals more context, subject to boundary rules.

13. All changes to memory structure, edge types, edge contexts, edge statuses, 
   edge directions, or review reasons must be documented in PRDs and versioned.
```
