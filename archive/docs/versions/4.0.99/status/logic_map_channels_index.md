---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: documentation
  when_updated: "20260413130000"
  file_path_from_root: "docs/versions/4.0.99/status/logic_map_channels_index.md"
  web_path: "https://www.lupopedia.com/lupopedia/logic_map_channels_index"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "memory/development/canonical/1026/04/logic-map-channels-index-toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: "channels-discussions"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: "Logic Map: Channels/Index.php Overhaul (PRD 02 Implementation)"
  status: "active"
  parent_pk_id: ""
  summary: "High-fidelity tactical mapping for channels/index.php rewrite to comply with PRD 02 dual-purpose command center doctrine. Includes prefix-safe queries, one-column UI logic, asset protection, task parser, and ambiguity resolution."
  module: null
  dialog_transcript: "0/development/channels-index-logic-map"
---

# Logic Map: Channels/Index.php Overhaul (PRD 02 Implementation)

**Mission**: Transform `channels/index.php` from customer support chat to canonical dual-purpose command center.

**References**:
- PRD 02: `docs/prd/02_channels_discussions.md`
- Truth Anchor: `memory/development/canonical/1026/04/readme-wtf-md.toon`
- Table JSONs: `database/lupopedia/json/`

---

## 1. Prefix-Safe Query Blueprint

### 1.1 Critical: Load Table Prefix
```php
<?php
// At the top of channels/index.php
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
?>
```

### 1.2 Core Queries (All use :prefix_ placeholder)

#### Get Messages for Current Thread
```sql
SELECT m.dialog_message_id, m.message_text, m.created_ymdhis, 
       m.from_actor_id, m.to_actor_id, m.message_type,
       a.actor_name, a.name as display_name,
       t.bg_color, t.text_color, t.text_color_alt
FROM :prefix_dialog_messages m
LEFT JOIN :prefix_actors a ON m.from_actor_id = a.actor_id
LEFT JOIN :prefix_dialog_threads t ON m.dialog_thread_id = t.dialog_thread_id
WHERE m.dialog_thread_id = :thread_id 
  AND m.is_deleted = 0
ORDER BY m.created_ymdhis ASC
LIMIT 500
```

#### Get Thread Info
```sql
SELECT dialog_thread_id, title, thread_key, bg_color, text_color, 
       text_color_alt, status, last_message_ymdhis
FROM :prefix_dialog_threads
WHERE channel_id = :channel_id 
  AND is_deleted = 0
  AND visibility_status = 'active'
ORDER BY last_message_ymdhis DESC
```

#### Get Recent Files
```sql
SELECT file_path_from_root, accessed_ymdhis, file_size
FROM :prefix_dialog_recent_files
WHERE accessed_by_actor_id = :actor_id 
  AND is_deleted = 0
ORDER BY accessed_ymdhis DESC
LIMIT 20
```

#### Get Pending Tasks
```sql
SELECT task_id, task_description, assigned_to_actor_id, 
       created_ymdhis, status, priority
FROM :prefix_dialog_pending_tasks
WHERE assigned_to_actor_id = :actor_id 
  AND status = 'pending'
ORDER BY priority DESC, created_ymdhis ASC
LIMIT 50
```

#### Insert New Message
```sql
INSERT INTO :prefix_dialog_messages 
(dialog_message_id, dialog_thread_id, channel_id, channel_key, 
 from_actor_id, to_actor_id, message_text, message_type, 
 created_ymdhis, updated_ymdhis, is_deleted)
VALUES (:dialog_message_id, :dialog_thread_id, :channel_id, :channel_key,
        :from_actor_id, :to_actor_id, :message_text, :message_type,
        :created_ymdhis, :updated_ymdhis, 0)
```

---

## 2. One-Column Logic Flow

### 2.1 Core Display Loop (Monospace Chronological)
```php
<?php
// Main message display - ONE COLUMN ONLY
echo '<div class="chat-feed" style="font-family: monospace; background: #1a1b1e; color: #fff; padding: 15px; height: calc(100vh - 200px); overflow-y: auto;">';

$messages = get_thread_messages($thread_id); // Using query above

while ($row = $messages->fetch()) {
    // Format timestamp
    $timestamp = date('H:i:s', strtotime($row['created_ymdhis']));
    
    // Get actor name
    $actor_name = !empty($row['display_name']) ? $row['display_name'] : $row['actor_name'];
    
    // Thread-specific colors (NOT agent colors)
    $bg_color = '#' . $row['bg_color'];
    $text_color = '#' . $row['text_color'];
    
    // Special styling for THOTH alerts
    if ($row['from_actor_id'] == 26) { // THOTH
        $bg_color = '#8B0000'; // Dark red
        $text_color = '#FFD700'; // Gold
    }
    
    // Output: [HH:MM:SS] [ACTOR_NAME] message_text
    echo '<div style="background: ' . $bg_color . '; color: ' . $text_color . '; padding: 4px 8px; margin: 2px 0; white-space: pre-wrap;">';
    echo '[' . $timestamp . '] [' . strtoupper($actor_name) . '] ' . htmlspecialchars($row['message_text']);
    echo '</div>';
}

echo '</div>';
?>
```

### 2.2 Forbidden Patterns (NEVER IMPLEMENT)
```php
// XXX: FORBIDDEN - Do NOT use chat bubbles
// echo '<div class="chat-bubble">';

// XXX: FORBIDDEN - Do NOT group by agent
// if ($last_actor_id != $row['from_actor_id']) { }

// XXX: FORBIDDEN - Do NOT use separate columns
// echo '<div class="agent-column">';
```

---

## 3. Asset Protection Map (DO NOT MODIFY)

### 3.1 Protected CSS Sections
The following sections in current `channels/index.php` handle "Liquid Design" and MUST BE PRESERVED:

#### Lines 90-119: Grid Layout Foundation
```css
.channel-live-wrapper {
    display: grid;
    grid-template-columns: 1fr 250px;
    grid-template-rows: 1fr auto;
    gap: 15px;
    height: calc(100vh - 150px);
}
/* DO NOT MODIFY - This is the liquid design container */
```

#### Lines 204-216: Status Dot Animation
```css
.status-dot {
    width: 10px;
    height: 10px;
    background: #28a745;
    border-radius: 50%;
}
/* DO NOT MODIFY - Part of liquid design aesthetic */
```

### 3.2 Protected HTML Structure
#### Lines 219-295: Wrapper Structure
```html
<div class="channel-live-wrapper">
    <!-- Panel 1: Main Thread Feed -->
    <div class="channel-feed">
        <!-- CONTENT WILL BE REPLACED BUT CONTAINER STAYS -->
    </div>
    <!-- Panel 2: Controls -->
    <div class="channel-controls">
        <!-- PRESERVE THIS STRUCTURE -->
    </div>
    <!-- Panel 3: Actor List -->
    <div class="channel-actor-list">
        <!-- PRESERVE THIS STRUCTURE -->
    </div>
</div>
```

---

## 4. Task Assignment Parser

### 4.1 Regex Pattern
```php
<?php
function parse_task_assignment($message_text) {
    // Pattern: [task] who: ACTOR what: DESCRIPTION
    $pattern = '/\[task\]\s+who:\s*([A-Z_]+)\s+what:\s*(.+)/i';
    
    if (preg_match($pattern, $message_text, $matches)) {
        return [
            'agent' => strtoupper($matches[1]),
            'task' => trim($matches[2]),
            'raw' => $message_text
        ];
    }
    
    return null;
}

// Usage example:
$message = "[task] who: CURSOR what: Fix the header validation issue";
$task = parse_task_assignment($message);
if ($task) {
    // Create task in lupo_dialog_pending_tasks
    create_pending_task($task['agent'], $task['task']);
}
?>
```

### 4.2 Task Creation Logic
```php
<?php
function create_pending_task($assigned_to_actor, $task_description) {
    global $prefix;
    
    $task_id = IdGenerator::generate('lupo_dialog_pending_tasks');
    $now = timestamp_ymdhis::now();
    $actor_id = get_current_actor_id();
    
    $sql = "INSERT INTO :prefix_dialog_pending_tasks 
            (task_id, task_description, assigned_to_actor_id, 
             created_by_actor_id, created_ymdhis, status, priority)
            VALUES (:task_id, :task_description, :assigned_to_actor_id,
                    :created_by_actor_id, :created_ymdhis, 'pending', 'normal')";
    
    // Execute with proper parameters
}
?>
```

---

## 5. Implementation Strategy

### 5.1 Phase 1: UI Rewrite (Critical)
1. Replace chat bubble HTML with monospace one-column display
2. Remove agent grouping logic
3. Implement thread-specific color styling
4. Add THOTH alert special handling

### 5.2 Phase 2: Database Integration
1. Add prefix loading
2. Implement message queries
3. Add thread creation/get-or-create logic
4. Integrate recent files and pending tasks

### 5.3 Phase 3: Agent Features
1. Add task assignment parser
2. Implement agent wrapper polling
3. Add private message support (to_actor_id)
4. Security hardening

---

## 6. Open Questions & Ambiguities

### 6.1 Table Name Discrepancy
**Issue**: PRD 02 shows `lupo_dialog_recent_files` with `AUTO_INCREMENT` but doctrine forbids `AUTO_INCREMENT`.
**Resolution**: Use `IdGenerator::generate()` for `recent_file_id` instead of `AUTO_INCREMENT`.

### 6.2 Color Assignment Logic
**Issue**: PRD 02 mentions `global_atoms.yaml` for color sequences but doesn't specify the exact format.
**Resolution**: Implement fallback to predefined color array if `global_atoms.yaml` not found.

### 6.3 Visitor → Actor Mapping
**Issue**: Current code has visitor session handling that must be preserved.
**Resolution**: Keep existing visitor logic but integrate with new thread system.

### 6.4 THOTH Styling
**Issue**: PRD 02 says THOTH messages should be "styled distinctly" but doesn't specify colors.
**Resolution**: Use dark red background (#8B0000) with gold text (#FFD700) for THOTH alerts.

---

## 7. Strict Constraints Checklist

### 7.1 Shared Hosting Compliance
- [ ] No Composer dependencies
- [ ] No Node.js build tools
- [ ] Pure PHP + PDO only
- [ ] Hand-coded CSS/JS

### 7.2 Database Doctrine Compliance
- [ ] No foreign keys
- [ ] No triggers/stored procedures
- [ ] BIGINT timestamps only
- [ ] IdGenerator for all IDs
- [ ] Soft delete everywhere

### 7.3 UI Doctrine Compliance
- [ ] One column only
- [ ] No chat bubbles
- [ ] No agent grouping
- [ ] Monospace font
- [ ] Timestamps on every message

### 7.4 ASCII Only
- [ ] No emojis in output
- [ ] No Unicode box drawing
- [ ] ASCII-only error messages

---

## 8. Validation Checklist

Before declaring implementation complete:

1. [ ] All queries use `:prefix_` placeholder
2. [ ] One-column display renders correctly
3. [ ] Thread colors apply from database
4. [ ] Task parser creates pending tasks
5. [ ] Recent files sidebar populated
6. [ ] THOTH alerts styled distinctly
7. [ ] No chat bubbles anywhere
8. [ ] Agent output appears inline
9. [ ] Private messages work (to_actor_id)
10. [ ] Preserved liquid design assets

---

**Status**: Ready for implementation by Claude Code (Actor 116)
**Priority**: Critical - This is the foundation for the entire dual-purpose command center
**Dependencies**: PRD 02 (canonical), README_WTF.toon (truth anchor)
