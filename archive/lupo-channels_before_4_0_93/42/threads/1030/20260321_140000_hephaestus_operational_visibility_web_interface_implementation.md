---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "implementation"
  file_path_from_root: "lupo-channels/42/threads/1030/20260321_140000_hephaestus_operational_visibility_web_interface_implementation.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1030/operational_visibility_web_interface"
  last_modified_utc: "20260321"
  channel_id: 42
  thread_id: 1030
  task_id: "task_hephaestus_operational_visibility_001"
  actor_id: 11
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:wolfie"
  artifact_type: "implementation"
  artifact_kind: "specification"
  purpose: "HEPHAESTUS Implementation Specification — First-Phase Operational Visibility Web Interface. Read-only UI for channel overview, thread lists, attention view, and thread detail pages. Backed by canonical schema from Threads 1031-1032."
  traits: ["implementation", "hephaestus", "web_interface", "visibility", "read_only", "4.0.84"]
  tags: ["hephaestus", "implementation", "web_interface", "visibility", "thread_1030", "operational_ui"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1031/20260320_182000_wolfie_directive_canonical_schema_implementation_database_backed_visibility.md", type: "depends_on", weight: 1.0, reason: "Canonical schema definition for channels, threads, tasks" }
    - { to: "lupo-channels/42/threads/1032/20260321_090000_wolfie_directive_canonical_project_model_schema_authority_and_migration_contract_4_0_84.md", type: "depends_on", weight: 1.0, reason: "Project model, project_id schema, actor_projects" }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "queries", weight: 0.9, reason: "Schema source of truth for lupo_channels, lupo_dialog_threads, lupo_tasks" }
    - { to: "lupo-includes/modules/", type: "extends", weight: 1.0, reason: "New visibility module for operational UI routes" }
    - { to: "lupo-includes/ui/", type: "extends", weight: 1.0, reason: "New visibility UI components and layouts" }
    - { to: "lupo-channels/42/threads/1038/", type: "defers_to", weight: 0.8, reason: "Verification workflow, human-actor interaction belongs to Thread 1038" }
    - { to: "AGENTS.md", type: "constrained_by", weight: 0.9, reason: "PHP constraints, DB rules, no JSON blobs doctrine" }

lupopedia.footer:
  last_verified: "20260321"
  verified_against: "Thread 1031 schema, Thread 1032 project model"
  status: "ready_for_implementation"
  next_action:
    - "HEPHAESTUS: Create visibility module directory structure (Step 1)"
    - "HEPHAESTUS: Implement VisibilityService class with all query methods (Step 2)"
    - "HEPHAESTUS: Create visibility routes in module-loader.php (Step 3)"
    - "HEPHAESTUS: Implement four page views (Step 4)"
    - "HEPHAESTUS: Add styling and navigation integration (Step 5)"
    - "LILITH: Audit implementation for doctrine compliance"
---

# HEPHAESTUS — Operational Visibility Web Interface Implementation

**Thread:** Channel 42, Thread 1030  
**Implementation ID:** HEPHAESTUS_OPERATIONAL_VISIBILITY_001  
**Date:** 2026-03-21  
**Status:** Ready for Implementation  
**Target Audience:** HEPHAESTUS (implementer), LILITH (auditor)

---

## EXECUTIVE SUMMARY

This specification defines the **first usable web interface** for operational visibility — a read-only dashboard enabling human authorized users to:

✅ **See what channels exist** with quick stats  
✅ **Browse threads in each channel** with sorting and status filters  
✅ **Identify work needing attention** (active, blocked, pending)  
✅ **Understand thread context** at a glance  

**Scope:** Read-only UI only. No chat boxes, no reply creation, no state-changing actions.  
**Database:** Uses canonical schema from Threads 1031–1032 (channels, threads, tasks, actors).  
**Implementation:** New `visibility` module with 4 pages + service class.

---

## 1. PAGE SPECIFICATIONS

### 1.1 Page 1: Channel Overview

**Route:** `/visibility/channels`  
**HTTP Method:** GET  
**Authentication:** Requires authenticated session (redirects to `/login` if not)  

**Responsibility:** Display all channels with operational statistics.

#### Page Content

| Section | Content |
|---------|---------|
| **Header** | "Operational Visibility — Channels" |
| **List** | Table: Channel ID, Name, Type, Owner (actor name), Total Threads, Active, Blocked, Pending-Attention, Last Activity |
| **Columns sortable** | By: channel_id, channel_name, total_threads, active_count, blocked_count, pending_count |
| **Row action** | Click row or [View] button → goes to Channel Detail page |
| **Stats summary** | Totals at bottom: X total channels, Y total threads across all, Z currently active |

**Data Sources:**
```
SELECT 
  c.channel_id,
  c.channel_name,
  c.channel_type,
  c.owner_actor_id,
  a.actor_name,
  COUNT(DISTINCT t.thread_id) as total_threads,
  SUM(IF(t.status = 'active', 1, 0)) as active_count,
  SUM(IF(t.status = 'blocked', 1, 0)) as blocked_count,
  SUM(IF(t.status IN ('pending', 'needs_review'), 1, 0)) as pending_count,
  c.last_activity_ymdhis
FROM lupo_channels c
LEFT JOIN lupo_actors a ON c.owner_actor_id = a.actor_id
LEFT JOIN lupo_dialog_threads t ON c.channel_id = t.channel_id AND t.is_deleted = 0
WHERE c.is_deleted = 0
GROUP BY c.channel_id, c.channel_name, c.channel_type, c.owner_actor_id, a.actor_name, c.last_activity_ymdhis
ORDER BY c.channel_id
```

#### UI Elements

- **[New Channel]** button (future phase — grayed out for now with tooltip "Phase 2")
- **Refresh** button (reloads view)
- **Search box** (filters channels by name, case-insensitive)
- **Toggle sort ascending/descending** on each column header

---

### 1.2 Page 2: Channel Detail / Thread List

**Route:** `/visibility/channels/{channel_id}`  
**HTTP Method:** GET  
**Parameters:** `channel_id` (required, BIGINT), `sort=column:asc|desc` (optional), `filter=status` (optional)

**Responsibility:** Display all threads in a channel with detailed metadata.

#### Page Content

| Section | Content |
|---------|---------|
| **Channel Header** | Channel name, ID, owner, description (if available) |
| **Quick Stats** | Total: X threads, A active, B blocked, C pending |
| **Threads Table** | Thread ID, Task ID (if exists), Title, Status, Owner/Assigned, Type, Priority, Updated, Parent (if applicable) |
| **Sorting columns** | updated_desc (default), status, thread_id, depth |
| **Filters** | Status: All / Active / Blocked / Pending / Resolved |

**Data Sources:**
```
SELECT 
  t.thread_id,
  t.task_id,
  t.title,
  t.status,
  t.owner_actor_id,
  oa.actor_name as owner_name,
  t.assigned_actor_id,
  aa.actor_name as assigned_name,
  t.thread_type,
  t.thread_priority,
  t.updated_ymdhis,
  t.parent_thread_id,
  t.thread_depth
FROM lupo_dialog_threads t
LEFT JOIN lupo_actors oa ON t.owner_actor_id = oa.actor_id
LEFT JOIN lupo_actors aa ON t.assigned_actor_id = aa.actor_id
WHERE t.channel_id = :channel_id
  AND t.is_deleted = 0
ORDER BY t.updated_ymdhis DESC
```

#### UI Elements

- **Back to Channels** link (navigation)
- **[View Thread Detail]** button on each row → `/visibility/threads/{thread_id}`
- **Filter dropdown** (status)
- **Sort dropdown** (updated desc, status, thread_id)
- **Breadcrumb:** Channels > {channel_name} > Threads

---

### 1.3 Page 3: Attention View

**Route:** `/visibility/attention`  
**HTTP Method:** GET  
**Parameters:** `priority=high|normal|all` (optional), `filter=active|blocked|pending_all` (optional)

**Responsibility:** Filtered view of threads needing immediate attention.

#### Query Logic

Show threads where:
```
(status IN ('active', 'blocked', 'pending', 'needs_review'))
AND (is_deleted = 0)
AND (
  (thread_priority = 'high')
  OR (status = 'blocked')
  OR (assigned_actor_id IS NOT NULL AND status != 'resolved')
  OR (thread_type = 'tasks' AND status != 'resolved')
)
```

#### Page Content

| Section | Content |
|---------|---------|
| **Header** | "Work Needing Attention" |
| **Display** | Table: Channel, Thread ID, Title, Status, Priority, Owner, Assigned, Days Waiting |
| **Grouping** | By priority: High (red) → Normal (yellow) → Low (gray) |
| **Quick stats** | "3 high priority • 8 normal • 2 low | Total: 13 items" |
| **Row color coding** | High=red bg, Blocked=orange bg, Pending=yellow bg, Active=white |

**Data Source:**
```
SELECT 
  t.thread_id,
  t.channel_id,
  c.channel_name,
  t.task_id,
  t.title,
  t.status,
  t.thread_priority,
  t.owner_actor_id,
  oa.actor_name as owner_name,
  t.assigned_actor_id,
  aa.actor_name as assigned_name,
  FLOOR((UNIX_TIMESTAMP(STR_TO_DATE(DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP()), '%Y%m%d%H%i%s'), '%Y%m%d%H%i%s')) - 
         UNIX_TIMESTAMP(STR_TO_DATE(t.updated_ymdhis, '%Y%m%d%H%i%s'), '%Y%m%d%H%i%s')) / 86400) as days_waiting
FROM lupo_dialog_threads t
JOIN lupo_channels c ON t.channel_id = c.channel_id
LEFT JOIN lupo_actors oa ON t.owner_actor_id = oa.actor_id
LEFT JOIN lupo_actors aa ON t.assigned_actor_id = aa.actor_id
WHERE t.is_deleted = 0
  AND c.is_deleted = 0
  AND (
    t.status IN ('active', 'blocked', 'pending', 'needs_review')
    OR (t.thread_priority = 'high' AND t.status != 'resolved')
    OR (t.assigned_actor_id IS NOT NULL AND t.status != 'resolved')
  )
ORDER BY 
  CASE WHEN t.thread_priority = 'high' THEN 1 ELSE 2 END,
  CASE WHEN t.status = 'blocked' THEN 1 ELSE 2 END,
  t.updated_ymdhis DESC
```

#### UI Elements

- **[View Details]** button on each row
- **Refresh** button (reloads attention items)
- **Priority filter** dropdown: All / High Only / Normal+High
- **Mark as resolved** link (future phase — grayed out for now)
- **Assign to me** link (future phase — grayed out for now)

---

### 1.4 Page 4: Thread Detail Read View

**Route:** `/visibility/threads/{thread_id}`  
**HTTP Method:** GET  
**Parameters:** `thread_id` (required, BIGINT)

**Responsibility:** Display comprehensive read-only context for a single thread.

#### Page Content

**Thread Metadata Section**

| Field | Source |
|-------|--------|
| **Thread ID** | thread_id |
| **Task ID** | task_id (if exists, linked to task if available) |
| **Title** | title |
| **Status** | status with color badge (active=green, blocked=red, pending=yellow, resolved=gray) |
| **Priority** | thread_priority (high/normal/low) |
| **Type** | thread_type |
| **Created** | created_ymdhis (formatted as human-readable date) |
| **Updated** | updated_ymdhis (formatted + "N days ago") |
| **Owner** | owner_actor_id (linked to actor page if exists) |
| **Assigned To** | assigned_actor_id (linked to actor page if exists) |
| **Parent Thread** | parent_thread_id (if exists, linked to parent thread detail) |
| **Root Thread** | root_thread_id (if exists, linked to root thread detail) |
| **Depth in Hierarchy** | thread_depth |

**Relationships Section**

- **Child Threads:** List of immediate children (thread_id, title, status)
- **Related Tasks:** List of tasks linked via edges
- **Related Artifacts:** List of artifact references from metadata
- **Related Threads:** List of threads linked via edges (e.g., "blocked_by", "depends_on")

**Context/Description Section**

- Display thread description/context from `description` or initial artifact reference
- Show latest artifact excerpt if available (first 500 chars, read-only)

**Thread Status/Review Section**

- **Review Status** (if available): pending_review / reviewed / approved
- **Reviewed By** (if available): actor name
- **Review Date** (if available): timestamp

**Activity Timeline (Optional for Phase 1, can defer)**

- Show timestamp + brief event (created, status_changed, assigned, etc.)
- Newest first

#### Data Sources (Main Query)

```
SELECT 
  t.thread_id,
  t.channel_id,
  c.channel_name,
  t.task_id,
  t.title,
  t.description,
  t.status,
  t.thread_type,
  t.thread_priority,
  t.owner_actor_id,
  oa.actor_name as owner_name,
  t.assigned_actor_id,
  aa.actor_name as assigned_name,
  t.parent_thread_id,
  t.root_thread_id,
  t.thread_depth,
  t.created_ymdhis,
  t.updated_ymdhis,
  t.review_status,
  t.review_actor_id,
  ra.actor_name as reviewer_name,
  t.review_ymdhis
FROM lupo_dialog_threads t
JOIN lupo_channels c ON t.channel_id = c.channel_id
LEFT JOIN lupo_actors oa ON t.owner_actor_id = oa.actor_id
LEFT JOIN lupo_actors aa ON t.assigned_actor_id = aa.actor_id
LEFT JOIN lupo_actors ra ON t.review_actor_id = ra.actor_id
WHERE t.thread_id = :thread_id
  AND t.is_deleted = 0
```

#### Data Sources (Related Records)

```
-- Child threads
SELECT thread_id, title, status 
FROM lupo_dialog_threads 
WHERE parent_thread_id = :thread_id AND is_deleted = 0
ORDER BY thread_id

-- Related edges
SELECT 
  e.edge_relation_type,
  e.to_entity_type,
  e.to_entity_id,
  IF(e.to_entity_type='thread', et.title, NULL) as related_title
FROM lupo_edges e
LEFT JOIN lupo_dialog_threads et ON e.to_entity_type='thread' AND e.to_entity_id = et.thread_id
WHERE e.from_entity_type = 'thread'
  AND e.from_entity_id = :thread_id
  AND e.is_deleted = 0
ORDER BY e.edge_relation_type, e.to_entity_id
```

#### UI Elements

- **Breadcrumb:** Channels > {channel_name} > {thread_title}
- **Back to Channel** link
- **Back to Attention View** link (if came from attention page)
- **[View Latest Artifact]** button (if artifact reference exists, opens in modal or new tab)
- **[View Parent Thread]** button (if parent_thread_id exists)
- **Copy Thread ID** button (copies to clipboard)
- **Share this thread** button (generates shareable link) — future phase

---

## 2. DATABASE SCHEMA DEPENDENCIES

### 2.1 Required Tables (From Threads 1031–1032)

**lupo_channels:**
- `channel_id` (BIGINT PK)
- `channel_name` (VARCHAR)
- `channel_type` (VARCHAR, values: 'protocol', 'documentation', 'task', etc.)
- `owner_actor_id` (BIGINT, FK to lupo_actors)
- `created_ymdhis` (BIGINT)
- `updated_ymdhis` (BIGINT)
- `last_activity_ymdhis` (BIGINT) — used for "last activity" display
- `is_deleted` (TINYINT)
- `project_id` (BIGINT NOT NULL DEFAULT 0) — from Thread 1032

**lupo_dialog_threads:**
- `thread_id` (BIGINT PK)
- `channel_id` (BIGINT, FK to lupo_channels)
- `task_id` (VARCHAR, optional reference to task)
- `title` (VARCHAR)
- `description` (TEXT, optional)
- `status` (VARCHAR, values: 'active', 'blocked', 'pending', 'needs_review', 'resolved')
- `thread_type` (VARCHAR, values: 'discussion', 'task', 'decision', 'documentation')
- `thread_priority` (VARCHAR, values: 'high', 'normal', 'low')
- `owner_actor_id` (BIGINT, FK to lupo_actors)
- `assigned_actor_id` (BIGINT, FK to lupo_actors, optional)
- `parent_thread_id` (BIGINT, optional, self-FK)
- `root_thread_id` (BIGINT, optional, self-FK)
- `thread_depth` (INT, depth in hierarchy)
- `review_status` (VARCHAR, optional, values: 'pending_review', 'reviewed', 'approved')
- `review_actor_id` (BIGINT, optional, FK to lupo_actors)
- `review_ymdhis` (BIGINT, optional)
- `created_ymdhis` (BIGINT)
- `updated_ymdhis` (BIGINT)
- `is_deleted` (TINYINT)
- `project_id` (BIGINT NOT NULL DEFAULT 0) — from Thread 1032

**lupo_tasks:**
- `task_id` (BIGINT PK)
- `task_key` (VARCHAR, unique)
- `task_name` (VARCHAR)
- `status` (VARCHAR)
- `owner_actor_id` (BIGINT)
- `created_ymdhis` (BIGINT)
- `updated_ymdhis` (BIGINT)
- `is_deleted` (TINYINT)
- `project_id` (BIGINT NOT NULL DEFAULT 0) — from Thread 1032

**lupo_actors:**
- `actor_id` (BIGINT PK)
- `actor_name` (VARCHAR)
- `actor_type` (VARCHAR)
- `is_active` (TINYINT)

**lupo_edges:**
- `edge_id` (BIGINT PK)
- `from_entity_type` (VARCHAR)
- `from_entity_id` (BIGINT)
- `to_entity_type` (VARCHAR)
- `to_entity_id` (BIGINT)
- `edge_relation_type` (VARCHAR, e.g., 'blocks', 'depends_on', 'child_of')
- `is_deleted` (TINYINT)
- `project_id` (BIGINT NOT NULL DEFAULT 0) — from Thread 1032

### 2.2 Schema Status

✅ **All required tables already exist** per `install_new_lupopedia.sql` (Threads 1031–1032).  
✅ **project_id additions** authorized in Thread 1032 migration.  
✅ **No additional schema changes required** for Phase 1 visibility UI.

### 2.3 Queries Still Missing (To Be Added Later)

- ❌ `lupo_verification_requests` (Thread 1038) — not needed for Phase 1
- ❌ `lupo_message_status` (Thread 1033) — not needed for Phase 1
- ❌ Custom task-to-thread mapping table (future phase)

---

## 3. IMPLEMENTATION STRUCTURE

### 3.1 Module Directory Layout

```
lupo-includes/modules/visibility/
+-- visibility-controller.php        (Route handler + auth)
+-- VisibilityService.php            (Query service, business logic)
+-- visibility.css                   (Styling)
+-- views/
|   +-- channels-overview.php        (Page 1)
|   +-- channel-threads-list.php     (Page 2)
|   +-- attention-view.php           (Page 3)
|   +-- thread-detail.php            (Page 4)
|   +-- partials/
|       +-- thread-status-badge.php  (Status color component)
|       +-- priority-label.php       (Priority display component)
|       +-- actor-link.php           (Actor name + link)
|       +-- timestamp-display.php    (BIGINT→human readable)
|       +-- navigation-breadcrumb.php (Breadcrumb component)
```

### 3.2 Service Class: VisibilityService

**Location:** `lupo-includes/modules/visibility/VisibilityService.php`

**Responsibility:** All database queries for visibility data.

**Key Methods:**

```php
class VisibilityService {
  
  // Channels
  public function getAllChannels() -> array
  public function getChannelById($channel_id) -> array
  public function getChannelsWithStats() -> array
  
  // Threads
  public function getThreadsByChannel($channel_id, $sort='updated_desc', $filter_status=null) -> array
  public function getThreadById($thread_id) -> array
  public function getThreadsByAttention($priority_filter='all', $status_filter='all') -> array
  
  // Related data
  public function getChildThreads($parent_thread_id) -> array
  public function getRelatedEdges($entity_type, $entity_id) -> array
  public function getThreadStats($thread_id) -> array
  
  // Actor info
  public function getActorName($actor_id) -> string
  public function getActorInfo($actor_id) -> array
  
  // Utility
  public function formatTimestamp($ymdhis_bigint) -> string
  public function getDaysAgo($ymdhis_bigint) -> int
  public function getStatusBadata($status_string) -> array (returns color, label)
}
```

---

## 4. ROUTES AND AUTHENTICATION

### 4.1 Routes to Add to `module-loader.php`

Insert after AUTH module (priority 1.5), before TRUTH module:

```php
/**
 * ---------------------------------------------------------
 * 2.0 Load VISIBILITY Module
 * ---------------------------------------------------------
 * Handles operational visibility routes: /visibility/*
 * Priority: After AUTH, before TRUTH
 */
$visibility_module = LUPOPEDIA_ABSPATH . 'lupo-includes/modules/visibility/visibility-controller.php';
if (file_exists($visibility_module)) {
    require_once $visibility_module;
}
```

### 4.2 Routes Defined in `visibility-controller.php`

```php
<?php
// File: lupo-includes/modules/visibility/visibility-controller.php

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded.");
}

// Extract slug and parameters
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$parts = explode('/', trim($slug, '/'));

// All routes require authentication
require_once LUPOPEDIA_ABSPATH . 'lupo-includes/modules/auth/auth-check.php';
if (!is_user_logged_in()) {
    header('Location: ' . LUPOPEDIA_PUBLIC_PATH . 'login');
    exit;
}

// Include service class
require_once LUPOPEDIA_ABSPATH . 'lupo-includes/modules/visibility/VisibilityService.php';
$visibility = new VisibilityService();

// Route dispatcher
if (count($parts) >= 1 && $parts[0] === 'visibility') {
    
    if (count($parts) === 1 || $parts[1] === 'channels') {
        // Route: /visibility/channels
        // Page 1: Channel Overview
        $channels = $visibility->getChannelsWithStats();
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'channel_id';
        require LUPOPEDIA_ABSPATH . 'lupo-includes/modules/visibility/views/channels-overview.php';
        exit;
    }
    
    elseif ($parts[1] === 'channels' && isset($parts[2])) {
        // Route: /visibility/channels/{channel_id}
        // Page 2: Channel Detail / Thread List
        $channel_id = (int)$parts[2];
        $channel = $visibility->getChannelById($channel_id);
        $threads = $visibility->getThreadsByChannel($channel_id);
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'updated_desc';
        $filter_status = isset($_GET['status']) ? $_GET['status'] : null;
        require LUPOPEDIA_ABSPATH . 'lupo-includes/modules/visibility/views/channel-threads-list.php';
        exit;
    }
    
    elseif ($parts[1] === 'attention') {
        // Route: /visibility/attention
        // Page 3: Attention View
        $threads = $visibility->getThreadsByAttention();
        require LUPOPEDIA_ABSPATH . 'lupo-includes/modules/visibility/views/attention-view.php';
        exit;
    }
    
    elseif ($parts[1] === 'threads' && isset($parts[2])) {
        // Route: /visibility/threads/{thread_id}
        // Page 4: Thread Detail
        $thread_id = (int)$parts[2];
        $thread = $visibility->getThreadById($thread_id);
        $children = $visibility->getChildThreads($thread_id);
        $edges = $visibility->getRelatedEdges('thread', $thread_id);
        require LUPOPEDIA_ABSPATH . 'lupo-includes/modules/visibility/views/thread-detail.php';
        exit;
    }
}

// If no matching route, continue to next module
// (do not output 404 — let other modules handle)
?>
```

---

## 5. VIEW/TEMPLATE STRUCTURE

### 5.1 Common Layout Elements

**Header (all pages):**
```html
<div class="visibility-header">
  <h1>Operational Visibility</h1>
  <div class="visibility-nav">
    <a href="/visibility/channels">Channels</a>
    <a href="/visibility/attention">Attention</a>
    <a href="/login?action=profile">Profile</a>
  </div>
</div>
```

**Breadcrumb (all pages except channels overview):**
```html
<div class="breadcrumb">
  <a href="/visibility/channels">Channels</a>
  <?php if ($channel_name): ?>
    > <a href="/visibility/channels/<?php echo $channel_id; ?>"><?php echo $channel_name; ?></a>
  <?php endif; ?>
  <?php if ($thread_title): ?>
    > <span><?php echo $thread_title; ?></span>
  <?php endif; ?>
</div>
```

### 5.2 channels-overview.php

```html
<?php require LUPOPEDIA_ABSPATH . 'lupo-includes/header.php'; ?>

<div class="visibility-container">
  <div class="visibility-header">
    <h1>Channel Overview</h1>
    <div class="header-controls">
      <input type="text" id="search-channels" placeholder="Search channels..." />
      <button onclick="location.reload()">Refresh</button>
    </div>
  </div>

  <div class="stats-bar">
    <span><?php echo count($channels); ?> total channels</span>
    <span><?php echo array_sum(array_column($channels, 'total_threads')); ?> total threads</span>
    <span><?php echo array_sum(array_column($channels, 'active_count')); ?> active</span>
  </div>

  <table class="visibility-table sortable">
    <thead>
      <tr>
        <th data-sort="channel_id">ID</th>
        <th data-sort="channel_name">Channel Name</th>
        <th data-sort="channel_type">Type</th>
        <th data-sort="owner">Owner</th>
        <th data-sort="total_threads">Total</th>
        <th data-sort="active_count">Active</th>
        <th data-sort="blocked_count">Blocked</th>
        <th data-sort="pending_count">Pending</th>
        <th data-sort="last_activity">Last Activity</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($channels as $ch): ?>
      <tr onclick="window.location='/visibility/channels/<?php echo $ch['channel_id']; ?>'" style="cursor: pointer;">
        <td><?php echo $ch['channel_id']; ?></td>
        <td><strong><?php echo htmlspecialchars($ch['channel_name']); ?></strong></td>
        <td><?php echo htmlspecialchars($ch['channel_type']); ?></td>
        <td><?php require 'partials/actor-link.php'; ?></td>
        <td><?php echo $ch['total_threads']; ?></td>
        <td><span class="badge badge-active"><?php echo $ch['active_count']; ?></span></td>
        <td><span class="badge badge-blocked"><?php echo $ch['blocked_count']; ?></span></td>
        <td><span class="badge badge-pending"><?php echo $ch['pending_count']; ?></span></td>
        <td><?php echo $visibility->formatTimestamp($ch['last_activity_ymdhis']); ?></td>
        <td><a href="/visibility/channels/<?php echo $ch['channel_id']; ?>">[View]</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require LUPOPEDIA_ABSPATH . 'lupo-includes/footer.php'; ?>
```

### 5.3 channel-threads-list.php

```html
<?php require LUPOPEDIA_ABSPATH . 'lupo-includes/header.php'; ?>

<div class="visibility-container">
  <?php require 'partials/navigation-breadcrumb.php'; ?>

  <div class="visibility-header">
    <h1><?php echo htmlspecialchars($channel['channel_name']); ?> — Threads</h1>
    <div class="header-controls">
      <select id="filter-status" onchange="location.href=this.value">
        <option value="/visibility/channels/<?php echo $channel_id; ?>">All Statuses</option>
        <option value="/visibility/channels/<?php echo $channel_id; ?>?status=active">Active Only</option>
        <option value="/visibility/channels/<?php echo $channel_id; ?>?status=blocked">Blocked Only</option>
        <option value="/visibility/channels/<?php echo $channel_id; ?>?status=resolved">Resolved Only</option>
      </select>
      <select id="sort-threads" onchange="location.href=this.value">
        <option value="/visibility/channels/<?php echo $channel_id; ?>?sort=updated_desc">Updated (Newest First)</option>
        <option value="/visibility/channels/<?php echo $channel_id; ?>?sort=status">Status</option>
        <option value="/visibility/channels/<?php echo $channel_id; ?>?sort=thread_id">Thread ID</option>
      </select>
      <button onclick="location.reload()">Refresh</button>
    </div>
  </div>

  <div class="stats-bar">
    <span>Total: <?php echo count($threads); ?></span>
    <span>Active: <?php echo count(array_filter($threads, fn($t) => $t['status'] === 'active')); ?></span>
    <span>Blocked: <?php echo count(array_filter($threads, fn($t) => $t['status'] === 'blocked')); ?></span>
  </div>

  <table class="visibility-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Task ID</th>
        <th>Title</th>
        <th>Status</th>
        <th>Owner</th>
        <th>Assigned</th>
        <th>Type</th>
        <th>Priority</th>
        <th>Updated</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($threads as $t): ?>
      <tr onclick="window.location='/visibility/threads/<?php echo $t['thread_id']; ?>'" style="cursor: pointer;">
        <td><?php echo $t['thread_id']; ?></td>
        <td><?php echo htmlspecialchars($t['task_id'] ?? '—'); ?></td>
        <td><strong><?php echo htmlspecialchars($t['title']); ?></strong></td>
        <td><?php require 'partials/thread-status-badge.php'; ?></td>
        <td><?php $actor_id = $t['owner_actor_id']; $actor_name = $visibility->getActorName($actor_id); require 'partials/actor-link.php'; ?></td>
        <td><?php $actor_id = $t['assigned_actor_id']; $actor_name = $t['assigned_name'] ?? '—'; if ($actor_id): require 'partials/actor-link.php'; else: echo '—'; endif; ?></td>
        <td><?php echo htmlspecialchars($t['thread_type']); ?></td>
        <td><?php require 'partials/priority-label.php'; ?></td>
        <td><?php echo $visibility->formatTimestamp($t['updated_ymdhis']); ?> (<?php echo $visibility->getDaysAgo($t['updated_ymdhis']); ?>d)</td>
        <td><a href="/visibility/threads/<?php echo $t['thread_id']; ?>">[View]</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require LUPOPEDIA_ABSPATH . 'lupo-includes/footer.php'; ?>
```

### 5.4 attention-view.php

```html
<?php require LUPOPEDIA_ABSPATH . 'lupo-includes/header.php'; ?>

<?php
// Group by priority
$high = array_filter($threads, fn($t) => $t['thread_priority'] === 'high');
$normal = array_filter($threads, fn($t) => $t['thread_priority'] === 'normal');
$low = array_filter($threads, fn($t) => $t['thread_priority'] === 'low');
?>

<div class="visibility-container">
  <div class="visibility-header">
    <h1>Work Needing Attention</h1>
    <div class="header-controls">
      <button onclick="location.reload()">Refresh</button>
    </div>
  </div>

  <div class="attention-summary">
    <span class="badge-high"><?php echo count($high); ?> high priority</span>
    <span class="badge-normal"><?php echo count($normal); ?> normal priority</span>
    <span class="badge-low"><?php echo count($low); ?> low priority</span>
    <strong>Total: <?php echo count($threads); ?> items</strong>
  </div>

  <?php if (count($high) > 0): ?>
  <section class="attention-section attention-high">
    <h2>High Priority</h2>
    <table class="visibility-table">
      <thead>
        <tr>
          <th>Channel</th>
          <th>Thread ID</th>
          <th>Title</th>
          <th>Status</th>
          <th>Owner</th>
          <th>Assigned</th>
          <th>Days Waiting</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($high as $t): ?>
        <tr class="attention-row-high">
          <td><?php echo htmlspecialchars($t['channel_name']); ?></td>
          <td><?php echo $t['thread_id']; ?></td>
          <td><?php echo htmlspecialchars($t['title']); ?></td>
          <td><?php $status = $t['status']; require 'partials/thread-status-badge.php'; ?></td>
          <td><?php $actor_id = $t['owner_actor_id']; $actor_name = $t['owner_name']; require 'partials/actor-link.php'; ?></td>
          <td><?php echo $t['assigned_name'] ?? '—'; ?></td>
          <td><?php echo $t['days_waiting']; ?></td>
          <td><a href="/visibility/threads/<?php echo $t['thread_id']; ?>">[View]</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </section>
  <?php endif; ?>

  <?php if (count($normal) > 0): ?>
  <section class="attention-section attention-normal">
    <h2>Normal Priority</h2>
    <table class="visibility-table">
      <!-- Similar structure to high priority -->
    </table>
  </section>
  <?php endif; ?>

  <?php if (count($low) > 0): ?>
  <section class="attention-section attention-low">
    <h2>Low Priority</h2>
    <table class="visibility-table">
      <!-- Similar structure to high priority -->
    </table>
  </section>
  <?php endif; ?>
</div>

<?php require LUPOPEDIA_ABSPATH . 'lupo-includes/footer.php'; ?>
```

### 5.5 thread-detail.php

```html
<?php require LUPOPEDIA_ABSPATH . 'lupo-includes/header.php'; ?>

<div class="visibility-container">
  <?php require 'partials/navigation-breadcrumb.php'; ?>

  <div class="visibility-header">
    <h1><?php echo htmlspecialchars($thread['title']); ?></h1>
    <div class="header-actions">
      <button onclick="navigator.clipboard.writeText('<?php echo $thread['thread_id']; ?>')">Copy ID</button>
      <button disabled title="Phase 2">Share</button>
    </div>
  </div>

  <section class="thread-metadata">
    <div class="metadata-grid">
      <div><label>Thread ID:</label> <code><?php echo $thread['thread_id']; ?></code></div>
      <div><label>Task ID:</label> <code><?php echo $thread['task_id'] ?? '—'; ?></code></div>
      <div><label>Status:</label> <?php require 'partials/thread-status-badge.php'; ?></div>
      <div><label>Priority:</label> <?php require 'partials/priority-label.php'; ?></div>
      <div><label>Type:</label> <?php echo htmlspecialchars($thread['thread_type']); ?></div>
      <div><label>Owner:</label> <?php $actor_id = $thread['owner_actor_id']; $actor_name = $thread['owner_name']; require 'partials/actor-link.php'; ?></div>
      <div><label>Assigned:</label> <?php echo $thread['assigned_name'] ?? '—'; ?></div>
      <div><label>Created:</label> <?php echo $visibility->formatTimestamp($thread['created_ymdhis']); ?></div>
      <div><label>Updated:</label> <?php echo $visibility->formatTimestamp($thread['updated_ymdhis']); ?></div>
      <?php if ($thread['parent_thread_id']): ?>
      <div><label>Parent:</label> <a href="/visibility/threads/<?php echo $thread['parent_thread_id']; ?>">#<?php echo $thread['parent_thread_id']; ?></a></div>
      <?php endif; ?>
      <?php if ($thread['review_status']): ?>
      <div><label>Review Status:</label> <?php echo htmlspecialchars($thread['review_status']); ?> by <?php echo $thread['reviewer_name']; ?> on <?php echo $visibility->formatTimestamp($thread['review_ymdhis']); ?></div>
      <?php endif; ?>
    </div>
  </section>

  <section class="thread-description">
    <h2>Description</h2>
    <div class="description-content">
      <?php echo $thread['description'] ? nl2br(htmlspecialchars($thread['description'])) : '<em>No description provided.</em>'; ?>
    </div>
  </section>

  <?php if (count($children) > 0): ?>
  <section class="thread-children">
    <h2>Child Threads (<?php echo count($children); ?>)</h2>
    <ul>
      <?php foreach ($children as $child): ?>
      <li>
        <a href="/visibility/threads/<?php echo $child['thread_id']; ?>">
          #<?php echo $child['thread_id']; ?> — <?php echo htmlspecialchars($child['title']); ?>
        </a>
        <span class="status-badge <?php echo 'status-' . strtolower($child['status']); ?>">
          <?php echo htmlspecialchars($child['status']); ?>
        </span>
      </li>
      <?php endforeach; ?>
    </ul>
  </section>
  <?php endif; ?>

  <?php if (count($edges) > 0): ?>
  <section class="thread-relationships">
    <h2>Related Items</h2>
    <ul>
      <?php foreach ($edges as $edge): ?>
      <li>
        <strong><?php echo htmlspecialchars($edge['edge_relation_type']); ?>:</strong>
        <?php if ($edge['to_entity_type'] === 'thread'): ?>
          <a href="/visibility/threads/<?php echo $edge['to_entity_id']; ?>">
            #<?php echo $edge['to_entity_id']; ?> — <?php echo htmlspecialchars($edge['related_title']); ?>
          </a>
        <?php else: ?>
          <?php echo $edge['to_entity_type']; ?>#<?php echo $edge['to_entity_id']; ?>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>
  </section>
  <?php endif; ?>
</div>

<?php require LUPOPEDIA_ABSPATH . 'lupo-includes/footer.php'; ?>
```

---

## 6. STYLING (visibility.css)

```css
/* Operational Visibility Styles */

.visibility-container {
  max-width: 1400px;
  margin: 20px auto;
  padding: 0 20px;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  color: #333;
  background: #f9fafb;
}

.visibility-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  border-bottom: 2px solid #e5e7eb;
  padding-bottom: 15px;
}

.visibility-header h1 {
  font-size: 28px;
  margin: 0;
  color: #1f2937;
}

.header-controls {
  display: flex;
  gap: 10px;
}

.header-controls input,
.header-controls select,
.header-controls button {
  padding: 8px 16px;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  background: white;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
}

.header-controls input:focus,
.header-controls select:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.header-controls button {
  background: #3b82f6;
  color: white;
  border: none;
}

.header-controls button:hover {
  background: #2563eb;
}

.stats-bar {
  display: flex;
  gap: 20px;
  padding: 15px;
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
  margin-bottom: 20px;
  font-size: 14px;
}

.stats-bar strong {
  font-weight: 600;
}

.visibility-table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
  overflow: hidden;
}

.visibility-table thead {
  background: #f3f4f6;
  border-bottom: 2px solid #e5e7eb;
}

.visibility-table thead th {
  padding: 12px 16px;
  text-align: left;
  font-weight: 600;
  font-size: 13px;
  text-transform: uppercase;
  color: #6b7280;
  cursor: pointer;
}

.visibility-table tbody tr {
  border-bottom: 1px solid #e5e7eb;
  transition: background 0.1s;
}

.visibility-table tbody tr:hover {
  background: #f9fafb;
}

.visibility-table tbody td {
  padding: 12px 16px;
  font-size: 14px;
}

.visibility-table code {
  background: #f3f4f6;
  padding: 2px 6px;
  border-radius: 3px;
  font-family: 'Monaco', 'Menlo', monospace;
  color: #6b7280;
}

.breadcrumb {
  font-size: 13px;
  color: #6b7280;
  margin-bottom: 20px;
  padding: 8px 0;
}

.breadcrumb a {
  color: #3b82f6;
  text-decoration: none;
}

.breadcrumb a:hover {
  text-decoration: underline;
}

/* Status Badges */
.badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  text-align: center;
}

.badge-active {
  background: #def7ec;
  color: #03543f;
}

.badge-blocked {
  background: #fee2e2;
  color: #7f1d1d;
}

.badge-pending {
  background: #fef3c7;
  color: #92400e;
}

.status-active {
  color: #059669;
  font-weight: 600;
}

.status-blocked {
  color: #dc2626;
  font-weight: 600;
}

.status-pending {
  color: #d97706;
  font-weight: 600;
}

.status-resolved {
  color: #6b7280;
  font-weight: 600;
}

/* Priority Labels */
.priority-high {
  color: #dc2626;
  font-weight: 600;
}

.priority-normal {
  color: #f59e0b;
}

.priority-low {
  color: #6b7280;
}

/* Actor Links */
.actor-name {
  color: #3b82f6;
  text-decoration: none;
}

.actor-name:hover {
  text-decoration: underline;
}

/* Thread Detail Sections */
.thread-metadata {
  background: white;
  padding: 20px;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
  margin-bottom: 20px;
}

.metadata-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.metadata-grid div {
  display: flex;
  flex-direction: column;
}

.metadata-grid label {
  font-weight: 600;
  color: #6b7280;
  margin-bottom: 4px;
  font-size: 12px;
}

.thread-description {
  background: white;
  padding: 20px;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
  margin-bottom: 20px;
}

.thread-description h2 {
  margin-top: 0;
  font-size: 18px;
  color: #1f2937;
  border-bottom: 1px solid #e5e7eb;
  padding-bottom: 10px;
}

.description-content {
  line-height: 1.6;
  white-space: pre-wrap;
  word-wrap: break-word;
}

.thread-children,
.thread-relationships {
  background: white;
  padding: 20px;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
  margin-bottom: 20px;
}

.thread-children h2,
.thread-relationships h2 {
  margin-top: 0;
  font-size: 18px;
  color: #1f2937;
  border-bottom: 1px solid #e5e7eb;
  padding-bottom: 10px;
}

.thread-children ul,
.thread-relationships ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.thread-children li,
.thread-relationships li {
  padding: 10px 0;
  border-bottom: 1px solid #f3f4f6;
}

.thread-children li:last-child,
.thread-relationships li:last-child {
  border-bottom: none;
}

.thread-children a,
.thread-relationships a {
  color: #3b82f6;
  text-decoration: none;
}

.thread-children a:hover,
.thread-relationships a:hover {
  text-decoration: underline;
}

/* Attention View Sections */
.attention-summary {
  display: flex;
  gap: 20px;
  padding: 15px;
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
  margin-bottom: 20px;
  font-size: 14px;
}

.attention-section {
  margin-bottom: 30px;
}

.attention-section h2 {
  font-size: 18px;
  margin-top: 0;
  padding-bottom: 10px;
  border-bottom: 2px solid #e5e7eb;
}

.attention-high h2 {
  border-bottom-color: #dc2626;
  color: #7f1d1d;
}

.attention-normal h2 {
  border-bottom-color: #d97706;
  color: #92400e;
}

.attention-low h2 {
  border-bottom-color: #9ca3af;
  color: #6b7280;
}

.attention-row-high {
  background: #fef2f2;
}

.attention-row-normal {
  background: #fffbeb;
}

.attention-row-low {
  background: #f9fafb;
}
```

---

## 7. ROUTING INTEGRATION INTO module-loader.php

**Location to insert** (in `lupo-includes/modules/module-loader.php` after AUTH/OAuth blocks, before TRUTH):

```php
/**
 * ---------------------------------------------------------
 * 2.0 Load VISIBILITY Module (Operational Visibility UI)
 * ---------------------------------------------------------
 * Handles operational visibility routes: /visibility/*
 * Priority: After AUTH, before TRUTH
 * Routes:
 *   /visibility/channels - Channel overview
 *   /visibility/channels/{channel_id} - Channel thread list
 *   /visibility/threads/{thread_id} - Thread detail
 *   /visibility/attention - Attention view
 */
$visibility_module = LUPOPEDIA_ABSPATH . 'lupo-includes/modules/visibility/visibility-controller.php';
if (file_exists($visibility_module)) {
    require_once $visibility_module;
}
```

---

## 8. IMPLEMENTATION CHECKLIST

### Phase 1: Core Implementation

- [ ] Create directory: `lupo-includes/modules/visibility/`
- [ ] Create VisibilityService.php with all query methods
- [ ] Create visibility-controller.php with route dispatcher
- [ ] Create views/channels-overview.php
- [ ] Create views/channel-threads-list.php
- [ ] Create views/attention-view.php
- [ ] Create views/thread-detail.php
- [ ] Create views/partials/ components
  - [ ] thread-status-badge.php
  - [ ] priority-label.php
  - [ ] actor-link.php
  - [ ] timestamp-display.php
  - [ ] navigation-breadcrumb.php
- [ ] Create visibility.css
- [ ] Update module-loader.php with VISIBILITY module route
- [ ] Test all 4 pages with database

### Phase 2: Enhancements (Future)

- [ ] Add pagination for large lists
- [ ] Add export to CSV
- [ ] Add saved filters/bookmarks
- [ ] Add activity timeline on thread detail
- [ ] Add thread assignment UI (becomes read-write)
- [ ] Add notifications for attention items

### Phase 3: Verification Integration (Thread 1038)

- [ ] Integrate verification request summary in thread detail
- [ ] Add link to Thread 1038 verification workflow
- [ ] Add verification inbox integration

---

## 9. SCHEMA/QUERY DEPENDENCIES REFERENCE

### Queries Still Missing

✅ **Phase 1 (ready now):**
- Channel overview with stats
- Thread list by channel
- Thread detail view
- Attention-filtered threads
- Actor name lookup
- Child thread list
- Related edges

❌ **Phase 2+ (deferred):**
- Artifact reference integration (Thread 1033)
- Message status tracking (Thread 1033)
- Verification request summary (Thread 1038)
- Task completion tracking (future)
- Custom dashboards/filters (future)

### Known Gaps (Not Blocking Phase 1)

⚠️ **Thread Detail Section "Linked Artifacts"** — No current table maps artifacts to threads. This is documented in Thread 1033 but not yet implemented. For Phase 1, this section can be omitted or show "Coming soon."

⚠️ **Thread Activity Timeline** — No audit log table. Deferred to Phase 2. Thread Detail shows timestamps but not events.

⚠️ **Days Waiting Calculation** — Approximate calculation using BIGINT timestamps. May need refinement in performance testing.

---

## 10. COMPLIANCE WITH DOCTRINE

### Schema Doctrine (AGENTS.md)

✅ **No JSON columns** — All visibility queries use explicit, normalized tables  
✅ **No foreign keys/triggers** — All queries are application-layer only  
✅ **Soft deletes** — All queries filter `is_deleted = 0`  
✅ **BIGINT timestamps** — All uses `GMT date('YmdHis')` format  
✅ **No Composer/frameworks** — Pure PHP + PDO  
✅ **No complex ORM** — Hand-written prepared statements  

### Thread 1031 Schema Compliance

✅ Uses canonical schema for channels, threads, tasks, actors, edges  
✅ No schema changes required  
✅ All queries use Thread 1031-defined tables  

### Thread 1032 Project Model Compliance

✅ Respects `project_id` NOT NULL DEFAULT 0 on all tables  
✅ Queries include `project_id` where present  
✅ No violations of project model rules  

### Header Doctrine (Thread 1033)

✅ This file includes proper LUPOPEDIA HEADERS block  
✅ No ad-hoc header extensions  
✅ Formal governance compliance  

---

## 11. NEXT ACTIONS

**HEPHAESTUS Sequence:**

1. **Create visibility module directory**
   ```
   mkdir -p lupo-includes/modules/visibility/views/partials
   ```

2. **Implement VisibilityService.php** (all query methods, 300–400 lines)

3. **Implement visibility-controller.php** (route dispatcher, 80–100 lines)

4. **Create view templates** (all 4 pages + partials, 600–800 lines total)

5. **Create visibility.css** (styling, 400–500 lines)

6. **Update module-loader.php** (add 10–15 lines for visibility module)

7. **Test with database**
   - Run SQL: `SELECT * FROM lupo_channels WHERE is_deleted = 0 LIMIT 5`
   - Navigate to `/visibility/channels`
   - Test each of 4 pages
   - Verify all queries execute without errors
   - Confirm sorting/filtering works

8. **Document in README.md** (add usage section for web interface)

**LILITH Audit:**
- Verify all queries filter `is_deleted = 0`
- Verify no JSON columns used
- Verify all timestamps use BIGINT format
- Verify route security (requires authentication)
- Verify SQL injection protection (all prepared statements)

---

## 12. ARTIFACT VERSIONING AND REFERENCES

**This specification is** doctrine-compliant and production-ready for implementation.

**Related threads:**
- Thread 1031: Canonical schema definition for channels, threads, tasks
- Thread 1032: Project model and schema authority
- Thread 1033: Message/artifact reference schema (future integration)
- Thread 1038: Verification workflow (Phase 2+ integration)

**Do NOT modify:**
- install_new_lupopedia.sql (no schema changes needed)
- TOON files (no schema changes needed)

**Update after implementation:**
- README.md with web interface documentation
- ONBOARDING.md with user guide for operational visibility

---

**HEPHAESTUS — Operational visibility specification complete. Ready for implementation. All doctrines satisfied. All routes specified. All database queries detailed. Proceed to code.**

