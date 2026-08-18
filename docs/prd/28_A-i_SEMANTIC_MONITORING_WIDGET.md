---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/28_A-i_SEMANTIC_MONITORING_WIDGET.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/28_A-i_SEMANTIC_MONITORING_WIDGET.md
  status: active
  when_updated: '20260817092400'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/28_semantic_monitoring_widget.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/semantic-monitoring-widget
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_28_A-i
  title: 'PRD: Semantic Monitoring Widget (The Eye) -- v4.0.99'
  summary: null
---
# PRD: Semantic Monitoring Widget (The Eye) -- v4.0.99

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## Purpose

Provides comprehensive semantic monitoring, page tracking, and user interaction analysis through "The Eye" widget. Enables Lupopedia to understand user behavior, track navigation patterns, and maintain semantic context across the entire site experience.

**Constitutional compliance:** All components follow `docs/prd/00_root_constitutional_system_requirements.md`. No foreign keys, no triggers, BIGINT timestamps, `IdGenerator::generate()` for all PKs, soft delete everywhere.

**Cross-link (engagement):** When memory nodes and other graph artifacts are mirrored into **`lupo_contents`** for likes, comments, and shares, the Eye and related semantic surfaces MAY join on **`content_id`** and path data per **PRD 50 sec. 4.17** and **PRD 16** sec. 4.2 field **14** -- without changing visit-path obligations in **SILENT_HARVEST** / ethics doctrine.

**Cross-link (path / referrer graph):** Co-viewed transitions MAY read **`lupo_paths`** (content-id aggregates) and/or **`lupo_paths_daily`** / **`lupo_paths_monthly`** when URL pairs and **`from_content_id` / `to_content_id`** are populated (**PRD 51 sec. 4.6**). Referrer surfaces MAY read **`lupo_referers`**, **`lupo_referers_daily`**, and the raw stream **`lupo_referers_raw`** before rollup. Full analytics layering is specified in **PRD 51 sec. 4.3-sec. 4.6** and **PRD 11**; optional projection into **`lupo_memory_edges`** remains per **PRD 51 sec. 6.4**. Copy and scope MUST follow **`docs/doctrine/SILENT_HARVEST_DOCTRINE.md`** and **PRD 34**.

## Department 1 -- Domain Root Installation Context

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

## Auth User -> Actor Selection

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

**Product Lineage Note:** Crafty Syntax, Sales Syntax, White Label Syntax, and Black Label Syntax are branding forks of the same author and the same underlying system. They are one family, not separate products. All four converge into Lupopedia OS. Legacy Eye / live-help embeds that still use those brand names are this same family.

- The widget monitors:
  - page enter/exit events
  - visitor navigation paths
  - next/previous page predictions
  - color identity for the current page (when known)
  - parent/child lineage for the current page (when known)
  - active Collection (`collection_name`) and Collection Selector state
- The widget provides a floating navigation bar with:
  - comments
  - likes
  - shares
  - color identity badge
  - lineage indicators and actions
- The widget can launch a "collections" top floating nav bar (blue Collection Selector plus green content tabs).
- Collections group related pages into dropdown menus. A Color Group includes `collection_name` and represents that Collection.

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

## Schema -- canonical install (present)

**Status:** Required tables are **present** in **`database/lupopedia/mysql/install/install_new_lupopedia.sql`** (single canonical schema for 4.0.x). Runtime names use `LUPO_TABLE_PREFIX` (typically `lupo_`); install DDL uses the `{{prefix}}` placeholder.

| Widget / PRD name | `CREATE TABLE` in install SQL |
|-------------------|------------------------------|
| `lupo_paths` | `{{prefix}}paths` |
| `lupo_references` | `{{prefix}}references` |
| `lupo_reference_links` | `{{prefix}}reference_links` |
| `lupo_hashtags` | `{{prefix}}hashtags` |
| `lupo_hashtag_map` | `{{prefix}}hashtag_map` |
| `lupo_folders` | `{{prefix}}folders` |
| `lupo_folder_map` | `{{prefix}}folder_map` |

**Historical note:** `database/lupopedia/mysql/migrations/add_semantic_navbar_tables_20260401.sql` was a **proposal**; its shapes were **consolidated into** `install_new_lupopedia.sql`. Fresh installs do **not** run that migration separately.

After any future DDL edit to install SQL, regenerate TOONs when your workflow requires it:

```bash
python scripts/generate_toon_files.py
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

### JavaScript Components (`ui/js/lupo.js`)

1. **LupoState** -- Holds current visible DOM state, manages active contexts and temporal color coding
2. **SemanticMonitor** -- Links UI to context edges, validates 63-bit positive integrity, monitors DB connection
3. **HighDensityScroller** -- 60fps virtualization for glass bubbles, dynamic viewport management

---

## Floating Navigation Bar -- The Eye Command Center

Fixed bar at bottom-right. Each icon shows a count badge and opens a dropdown.

### Bar Icons

| # | Icon | Label | Primary Table | Key Columns |
|---|------|-------|---------------|-------------|
| 1 | [Prev] | Previous Pages | `lupo_paths` | `exitcontentid`, `count_num` |
| 2 | [Ref] | Referencing Pages | `lupo_edges` | `right_object_id`, `edge_type='references'`, `semantic_weight` |
| 3 | [Tags] | Hashtags/Tags | `lupo_hashtags` + `lupo_hashtag_map` | `hashtag_id`, `object_type`, `object_id` |
| 4 | [Shares] | Shares | `lupo_actor_actions` | `action_type='share'`, `entity_type`, `entity_id` |
| 5 | [Inbound] | Inbound Links | `lupo_edges` | `right_object_id`, `edge_type='links_to'` |
| 6 | [Classes] | Namespaces/Classes | `lupo_metadata` | `entity_type`, `entity_id`, `class_name` |
| 7 | [Folders] | Folders | `lupo_folders` + `lupo_folder_map` | `folder_id`, `object_type`, `object_id` |
| 8 | [Next] | Next Pages | `lupo_paths` | `entercontentid`, `count_num` |
| 9 | [Comments] | Comments | `lupo_comments` | `target_type`, `target_id` |
| 10 | [Q&A] | Questions | `lupo_truth_questions` + `lupo_truth_answers` | `target_object_type`, `target_object_id` |
| 11 | [Edges] | All Edges | `lupo_edges` | `left_object_id` OR `right_object_id` |
| 12 | [Live] | Live Help | chat system | online status |
| 13 | [Memory] | Memory Edges | `lupo_memory_edges` | `from_memory_node_id`, `to_memory_node_id`, `edge_type`, `edge_status`, `edge_context`, `edge_direction`, `weight_hundredths` |
| 14 | [Color] | Color Identity | Lupopedia OS color registry (PRD 90 / PRD 01_B) | GroupColor, ColorName, HEX6 when known |
| 15 | [Lineage] | Lineage | Lupopedia OS lineage records | parent URL, child URLs, change type, change intent |

### Verified Query Patterns

All queries below use confirmed column names from TOON JSON / table docs. All use `DatabaseFactory::getConnection()` and `LUPO_TABLE_PREFIX`.

**Contexts:** The bar no longer includes a **Contexts** icon. Former `lupo_contexts_map` lookups are superseded for this widget by **semantic edges** (`lupo_edges` with appropriate `edge_type` / metadata) per the edge-first context model. For **IDE and memory-graph** workloads, the *scoped attention* role of the old Contexts concept is specified as the **Focus Manifest** (**PRD 52**) ??? a runtime filter over **`lupo_memory_edges`**, not a return to static context tables.

#### 1. Previous Pages ([Prev])
Source: `lupo_paths` -- confirmed columns: `exitcontentid`, `entercontentid`, `count_num`, `is_deleted`

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

#### 2. Referencing Pages ([Ref])
Source: `lupo_edges` -- confirmed columns: `right_object_type`, `right_object_id`, `edge_type`, `semantic_weight`, `is_deleted`

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

#### 3. Hashtags/Tags ([Tags])
Source: `lupo_hashtags` + `lupo_hashtag_map` -- confirmed columns: `hashtag_id`, `tag_slug`, `label`, `object_type`, `object_id`, `is_deleted`

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

#### 4. Shares ([Shares])
Source: `lupo_actor_actions` -- confirmed columns: `action_type`, `entity_type`, `entity_id`, `actor_id`, `created_ymdhis`

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

#### 5. Inbound Links ([Inbound])
Source: `lupo_edges` -- confirmed columns: `left_object_type`, `left_object_id`, `right_object_type`, `right_object_id`, `edge_type`, `is_deleted`

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

#### 6. Namespaces/Classes ([Classes])
Source: `lupo_metadata` -- confirmed columns: `entity_type`, `entity_id`, `class_name`, `property_key`, `property_value`, `is_deleted`

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

#### 7. Folders ([Folders])
Source: `lupo_folders` + `lupo_folder_map` -- confirmed columns: `folder_id`, `name`, `slug`, `object_type`, `object_id`, `is_deleted`

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

#### 8. Next Pages ([Next])
Source: `lupo_paths` -- confirmed columns: `entercontentid`, `exitcontentid`, `count_num`, `is_deleted`

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

#### 9. Comments ([Comments])
Source: `lupo_comments` -- confirmed columns: `target_type`, `target_id`, `actor_id`, `comment_text`, `created_ymdhis`, `is_deleted`

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

#### 10. Questions ([Q&A])
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

#### 11. All Edges ([Edges])
Source: `lupo_edges` -- confirmed columns: `left_object_type`, `left_object_id`, `right_object_type`, `right_object_id`, `edge_type`, `is_deleted`

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

#### 13. Memory Edges ([Memory])
**Purpose:** Show memory graph edges connected to the current content. This bridges the Semantic Widget with the unified memory graph from **PRD 38**.

**Source:** `lupo_memory_nodes` + `lupo_memory_edges` (**PRD 38** sec. 5).

**Resolution steps:**

1. Resolve the memory node by **`memory_key`** (canonical file path) and/or **`memory_node_id`**. When **PRD 38** / **PRD 50** unify identifiers, the caller MAY pass the same BIGINT for both content and memory node; otherwise resolve **`memory_node_id`** from metadata or a prior lookup -- do not assume `content_id` always equals `memory_node_id`.

   ```sql
   SELECT memory_node_id, memory_type, memory_key, status, context
   FROM {$prefix}memory_nodes
   WHERE (memory_key = :file_path OR memory_node_id = :memory_node_id)
     AND is_deleted = 0
   LIMIT 1;
   ```

2. If found, query outgoing edges:

   ```sql
   SELECT
       me.to_memory_node_id,
       me.edge_type,
       me.edge_context,
       me.edge_status,
       me.edge_direction,
       me.review_reason,
       me.weight_hundredths,
       mn2.memory_key AS to_memory_key,
       mn2.memory_type AS to_memory_type
   FROM {$prefix}memory_edges me
   JOIN {$prefix}memory_nodes mn2 ON mn2.memory_node_id = me.to_memory_node_id
   WHERE me.from_memory_node_id = :node_id
     AND me.is_deleted = 0
   ORDER BY me.edge_type, me.weight_hundredths DESC;
   ```

3. Query incoming edges:

   ```sql
   SELECT
       me.from_memory_node_id,
       me.edge_type,
       me.edge_context,
       me.edge_status,
       me.edge_direction,
       me.review_reason,
       me.weight_hundredths,
       mn1.memory_key AS from_memory_key,
       mn1.memory_type AS from_memory_type
   FROM {$prefix}memory_edges me
   JOIN {$prefix}memory_nodes mn1 ON mn1.memory_node_id = me.from_memory_node_id
   WHERE me.to_memory_node_id = :node_id
     AND me.is_deleted = 0
   ORDER BY me.edge_type, me.weight_hundredths DESC;
   ```

**Display rules:**

| Edge Status | Visual Indicator | Action |
|-------------|------------------|--------|
| `supported` | Green checkmark | Normal display |
| `staging` | Yellow dot | Italicized, "needs verification" note |
| `needs_review` | Red exclamation | Highlight, show `review_reason` |

**TOON alignment:** If install DDL uses **`unsupported`** (or other values) instead of **`staging`** for provisional edges, map **`unsupported`** to the same yellow / provisional UI until product vocabulary is unified in schema and docs.

**Edge direction display:**

- `unidirectional`: Arrow -> one way
- `bidirectional`: Double arrow <->

**API Endpoint:** `GET /api/page/memory-edges?page_id={id}` (fallback query form; path segment allowed when rewrites exist -- see **API routing** below).

**Note on table coexistence:** `lupo_edges` (global semantic graph) and `lupo_memory_edges` (memory-native graph) are **not interchangeable**. For edges that belong to the unified memory graph (**PRD 38**), use `lupo_memory_edges`. For cross-object semantic relationships (e.g., `memory_node` <-> `actor`, `channel`, `content`), use `lupo_edges` with `object_type = 'memory_node'` per **PRD 37** sec. 2.0. The widget **MUST** query the correct table based on the edge's purpose.

#### 14. Create Memory Node from File

**Purpose:** Allow actors to create a memory node for a file that does not have one yet, directly from the Semantic Widget.

**Trigger:** When a file has no associated memory node, the widget shows a **Create Memory Node** control.

**API Endpoint:** `POST /api/memory/node`

**Request:**

```json
{
  "file_path": "docs/prd/50_agent_coordination_protocol.md",
  "actor_id": 102,
  "memory_type": "prd",
  "context": "specification"
}
```

**Response:**

```json
{
  "status": "ok",
  "memory_node_id": "102604111200001234",
  "memory_key": "memory/development/canonical/1026/04/50-agent-coordination-protocol.toon"
}
```

**After creation:** Widget refreshes to show the new node and its (initially empty) edges.

---

## Collections System -- Two-Tier Architecture

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

### Color Groups and Collections (unified)

A Color Group is not only a color identity. It also represents a **Collection**: a named set of webpages, artifacts, or semantic nodes. Color Groups and Collections are unified concepts in the Lupopedia OS.

A Color Group stores:

- `color_group` (GroupColor family)
- `color_nickname` (ColorName)
- `collection_name` (new field; binds the Color Group to a Collection)
- lineage metadata (parent/child URLs, change type, change intent, explanation)
- semantic identity metadata (handshake, HEX6 when known, node/slug context)

**How Collections work in the UI**

- The **blue dropdown** is the **Collection Selector**.
- Each entry is a **Collection Name**.
- Selecting a Collection sets the active Collection and **changes all semantic menus**.
- The **green top-level tabs** (for example `[Content A]`, `[Content B]`) become **multi-level drop menus** populated with items from that Collection.
- Those menus are sourced from **`lupo_collection_tabs`** filtered by the selected Collection.
- Collection rows live in **`lupo_collections`**. Tab rows live in **`lupo_collection_tabs`**. Item membership uses **`lupo_collection_tab_map`**. Full table doctrine is **PRD 73**.

`collection_name` on the Color Group MUST match the Collection identity in `lupo_collections` (`collection_name` / `name` / `slug` as that table is documented). Do not invent a second collection namespace.

This subsection is **PRD-level**. It does **not** add install SQL. Until color-registry storage exists, empty/pending `collection_name` is valid. HEX6 MUST NOT be guessed.

### Tier 2: Emergent Collections (Light Green)

Discovered from session co-occurrence analysis via `lupo_visits` (`session_id`, `entercontentid`, `exitcontentid`). See `docs/prd/73_collections_navigation.md` for full emergent collection doctrine.

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

**Memory-aware eye behavior:** When **`edge_status = 'needs_review'`** on any **`lupo_memory_edges`** row connected to the current content (after **section 13** resolution), the eye **MAY** change color or pulse to signal that actor attention is needed.

---

## API Endpoints

| Icon | Endpoint | Primary Table |
|------|----------|---------------|
| [Prev] | `GET /api/page/previous-pages` | `lupo_paths` |
| [Ref] | `GET /api/page/referencing-pages` | `lupo_edges` |
| [Tags] | `GET /api/page/tags` | `lupo_hashtags`, `lupo_hashtag_map` |
| [Shares] | `GET /api/page/shares` | `lupo_actor_actions` |
| [Inbound] | `GET /api/page/inbound-links` | `lupo_edges` |
| [Classes] | `GET /api/page/namespaces` | `lupo_metadata` |
| [Folders] | `GET /api/page/folders` | `lupo_folders`, `lupo_folder_map` |
| [Next] | `GET /api/page/next-pages` | `lupo_paths` |
| [Comments] | `GET /api/page/comments` | `lupo_comments` |
| [Q&A] | `GET /api/page/questions` | `lupo_truth_questions`, `lupo_truth_answers` |
| [Edges] | `GET /api/page/edges` | `lupo_edges` |
| [Live] | `GET /api/chat/status` | chat system |
| [Memory] | `GET /api/page/memory-edges` | `lupo_memory_edges`, `lupo_memory_nodes` |
| [Memory] | `POST /api/memory/node` | `lupo_memory_nodes` |

### API routing: clean URLs vs query parameters (constitutional)

**PRD 00 sec. 2** and **sec. 9.5** require that **no core API depends on `mod_rewrite`.** Handlers **must** accept identifiers via **query parameters** (and may also accept **PATH_INFO** or path segments when rewrites are present).

| Mode | When it applies | Example (under `LUPOPEDIA_PUBLIC_PATH`) |
|------|-----------------|----------------------------------------|
| **Clean URL** | `mod_rewrite` + `.htaccess` (or stack equivalent) | `GET .../api/page/tags/123` (path segment = `content_id`) |
| **Fallback (required)** | Always -- shared hosting without `AllowOverride`, Nginx, IIS, etc. | `GET .../api/page/tags?page_id=123` (or `index.php?route=...` + documented params) |

**Server behavior (normative for implementers):**

1. Resolve **`content_id` / `page_id`** from **`PATH_INFO`**, rewrite-captured path segments, or **query string** -- in a **fixed precedence** documented per endpoint (typically prefer path when present, else query).
2. **Do not** require pretty URLs for correctness; **do not** fail closed when only the fallback form is available.

**Table note:** The "Endpoint" column above uses **slash notation** for readability; **query-parameter equivalents** for the same operations are **mandatory** unless an explicit exception is **APPROVED** in a decision artifact.

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

## Color Identity and Lineage in The Eye

This section is **PRD-level only**. It does **not** add install SQL, new tables, or guessed HEX6 values. Color doctrine is **PRD 90**. Color registry tables are **PRD 01_B** (planning). Local domain content coloring is **not** the Color Registry homepage; that homepage declares **cross-domain lineage**. The Eye shows both: the current page's color identity (when known) and its lineage (when known).

**Artifact lineage widgets exist separately from The Eye.** Licensed-artifact embeds (music first, CC-BY first license) are **PRD 92**. The Eye MUST NOT load inside an artifact embed. Webpage lineage stays here; artifact remix/attribution lineage stays in PRD 92.

**Data source:** All color and lineage metadata comes from the Lupopedia OS. The Eye **MUST** request it through **`lupopedia_js.php`** (see **PRD 04**). The widget MUST NOT query the database from the browser. Until registry/lineage storage is implemented, the widget MUST render an empty or pending state rather than inventing values.

**HEX6 rules (normative):** HEX6 is six digits with **no** `#` in storage. HEX5 is not a color. Color is **not** a LUP KEY token. The widget MUST NOT guess HEX6. If HEX6 is unknown, display `pending` (or equivalent) and still show GroupColor / ColorName when those are known.

Handshake display (when identity exists): `lupopedia poweredby [GroupColor] [ColorName]`.

### Color Identity Display

The Eye MUST be able to show, for the current page:

- Color Group (GroupColor family: BLACK, GOLD, WHITE, and the rest of the PRD 90 base register)
- Color Nickname (ColorName)
- Collection Name (`collection_name`; the Collection this Color Group represents)
- Color Hex / symbolic color: HEX6 when known; otherwise pending. Symbolic color is the GroupColor family, not a guessed hex.
- A small color badge in the widget (use HEX6 only when known; otherwise a neutral badge plus GroupColor label)

### Lineage Display

The Eye MUST be able to show, for the current page:

- Parent URL (if one exists)
- Child URLs (if any)
- A **View Lineage Tree** link
- A **Declare Child Page** action
- A **Find References** action

Lineage is a parent-to-child relationship between URLs (including across domains). Change type, change intent, and optional change explanation belong to the lineage record (Color Registry form). The Eye MAY surface those fields when present.

**Declare Child Page** MUST open the Color Registry homepage (`index.php`) with `?parent=` set to the current page path **relative to the domain root**, not relative to `/lupopedia/`. Example: page `https://example.com/meaningoflife.htm` on an install at `https://example.com/lupopedia/` opens `https://example.com/lupopedia/?parent=meaningoflife.htm`, which prefills Parent URL as `https://example.com/meaningoflife.htm`.

**Color this Page** is local content coloring (live help Content / OS content tools). It MUST NOT treat the Color Registry homepage as the place to color the installed domain.

### Actions

The Eye MUST expose these actions (enabled when the operator/session is allowed; otherwise hide or disable):

| Action | Meaning |
|--------|---------|
| Color this Page | Open local content coloring (live help Content). Does not color via the lineage homepage. |
| Declare Child Page | Open Color Registry with `?parent=` for the current page path. |
| View Lineage | Open lineage tree / lineage panel for the current page. |
| Copy Page | Copy the current page URL (and, when known, handshake text). |
| Edit Page | Open the local content editor for this page when it is an OS content artifact. |
| Share Page | Existing share action (`lupo_actor_actions` / [Shares] icon). |
| Find References | Existing referencing-pages lookup ([Ref] icon). |

### Payload shape (from `lupopedia_js.php`)

The Eye MUST accept a metadata object (names may be nested under `color` / `lineage`). Unknown fields MUST be ignored. Missing identity MUST be represented as empty/pending, not invented.

```text
color.group_color
color.color_name
color.collection_name
color.hex6          (six digits or empty; never guessed)
color.handshake
lineage.parent_url
lineage.child_urls[]
lineage.change_type
lineage.change_intent
lineage.change_explanation
actions.color_this_page_url
actions.declare_child_page_url
actions.view_lineage_url
actions.edit_page_url
```

### Tracking

Viewing color identity, viewing lineage, declaring a child page, and referencing a parent page are analytics events. See **PRD 11**.

## Cross-References

- **PRD 73: Collections Navigation** -- `lupo_collections`, `lupo_collection_tabs`; Color Group `collection_name` binds to a Collection.
- **PRD 92: Artifact Lineage Widget** -- generalized artifact lineage embed (CC-BY music first surface); not The Eye.
- **PRD 90: Color Identity Doctrine** -- GroupColor, ColorName, HEX6, handshake, color is not a KEY token.
- **PRD 01_B: Color registry tables** -- planning for stored identity (no DDL from this Eye section).
- **PRD 04: Lupopedia JS Foundation** -- `lupopedia_js.php` fetches color + lineage metadata and passes it to The Eye.
- **PRD 21: Semantic Navbar** -- navbar MAY show color identity when relevant.
- **PRD 11: Analytics Tracking** -- color identity viewed, lineage viewed, child page created, parent page referenced.
- **PRD 33: Softaculous / embed contract** -- Eye color + lineage indicators are part of the `lupopedia_js.php` embed.
- **PRD 38: Memory Unification** -- memory nodes and edges schema (`lupo_memory_nodes`, `lupo_memory_edges`).
- **PRD 50: Agent Coordination Protocol** -- chat interface, collections bridge, recently created panel.
- **PRD 51: Memory Graph as Source of Truth** -- header inference from memory, path/referrer aggregation.

## Implementation Checklist

Before writing any PHP for this widget:

- [x] Confirm semantic navbar / path tables exist in `install_new_lupopedia.sql` (see **Schema -- canonical install** above).
- [ ] Regenerate TOON files after **new** DDL changes: `python scripts/generate_toon_files.py`
- [ ] Read table doc for every table before writing any query
- [ ] Confirm `lupo_truth_questions` column names from `docs/database/lupopedia/tables/lupo_truth_questions.md`
- [ ] Use `DatabaseFactory::getConnection()` -- never `new PDO()` or `mysqli_*`
- [ ] Use `LUPO_TABLE_PREFIX . 'tablename'` -- never hardcode `lupo_`
- [ ] Use `IdGenerator::generate()` for all new PKs -- never `AUTO_INCREMENT` or `null`
- [ ] All timestamps via `gmdate('YmdHis')` -- never `time()` or `date()`
- [ ] All queries include `AND is_deleted = 0`
- [ ] Memory edge queries implemented in Semantic Widget (**section 13**)
- [ ] **Create Memory Node** control and **`POST /api/memory/node`** handler
- [ ] Edge status visual indicators (`supported` / provisional including `staging` or `unsupported` / `needs_review`)
- [ ] Bidirectional vs unidirectional arrow display for **`edge_direction`**
- [ ] Integration with **`lupo_memory_edges`** (distinct from **`lupo_edges`** per **section 13** note)
- [ ] Color identity badge (GroupColor, ColorName, collection_name, HEX6 when known; never guess HEX6)
- [ ] Collection Selector (blue dropdown) repopulates green semantic tabs from lupo_collection_tabs
- [ ] Lineage panel (parent URL, child URLs, View Lineage Tree, Declare Child Page)
- [ ] `lupopedia_js.php` color + lineage metadata payload (PRD 04); empty/pending until storage exists


---

## Context-Typed, Status-Aware, Directional Edged Memory Doctrine (4.0.96)

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
  - unidirectional (A -> B)
  - bidirectional (A <-> B)
  - restricted-direction (A -> B but not B -> A unless explicitly defined)
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
  or reclassified. A node may move from unsupported -> supported when 
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
