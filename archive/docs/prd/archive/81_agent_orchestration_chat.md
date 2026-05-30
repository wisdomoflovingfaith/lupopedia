---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: prd
  when_updated: "20260414120000"
  file_path_from_root: "docs/prd/archive/81_agent_orchestration_chat.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/archive/81_agent_orchestration_chat.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "archive"
  memory_key: "memory/development/archive/2026/04/81_agent_orchestration_chat.toon"
  artifact_type: prd
  artifact_kind: specification
  thread_id: ""
  content_id: null
  pk_id: 81
  pk_slug: "agent-orchestration-chat"
  title: "PRD 81: Agent Orchestration Chat System"
  status: "archived"
  archived_date: "20260415000000"
  superseded_by: "PRD 02"
  parent_pk_id: "02"
  summary: "ARCHIVED — superseded by PRD 02 (merged 2026-04-13). Multi-agent chat orchestration system with channel-based UI, thread-specific colors, task assignment, and file browser"
  module: "orchestration"
  dialog_transcript: "0/development/prd_81_agent_chat"
---
# PRD 81: Agent Orchestration Chat System

> **DEPRECATED — SUPERSEDED BY PRD 02**
>
> This PRD has been fully merged into [PRD 02: Channels, Threads, Discussions & Agent Orchestration Command Center](02_channels_discussions.md).
> PRD 02 is the canonical source for all chat architecture, agent integration, UI/UX rules, and orchestration patterns.
> This document is retained for historical reference only. Do not implement from this document.

---

> **WARNING: AGENTS DO NOT READ THIS CHAT**
>
> A common misconception when reading this PRD: the chat interface described here is **not** a conversation that agents monitor and respond to. Builder agents (Cursor, Claude, Cascade, etc.) are **write-only** — they post stdout/stderr so humans can monitor them, but they **never read the chat**.
>
> Agents receive instructions through their **task queue** only. See PRD 02 §"The Chat Is Not A Conversation" for the full visibility doctrine and the one-way mirror explanation.

---

## 1. Purpose

Create a **unified chat command center** that captures stdout/stderr from multiple IDE agents (Cursor, Claude, Cascade, WindSurf, LILITH/DeepSeek, Countermeasure), displays them in a **single chronological column** with **thread-specific background colors**, allows task assignment via chat commands, and provides a **recent files browser** from the database.

**NOT a typical chat system.** This is a command center for AI agent orchestration, adapted from the proven Crafty Syntax (2002) chat architecture.

---

## 2. Core Principles

### 2.1 One Column, Chronological, Intermixed

**FORBIDDEN:**
- Separate columns per agent
- Grouping messages by agent
- Tabs for different agents
- Side-by-side agent views
- Threaded replies that split the conversation

**REQUIRED:**
[14:32:01] [CURSOR] working on validate_actor_id.php header
[14:32:15] [CLAUDE] i did this
[14:32:28] [CASCADE] making the documentation
[14:33:01] [LILITH] auditing new md file from cursor
[14:33:15] [CURSOR] got revision from Lilith working on corrections

text

**One column. Oldest at top. Newest at bottom. All agents mixed together. Timestamps on every message.**

### 2.2 Thread-Specific Colors (Not Agent, Not Channel)

Colors are assigned **per thread** at creation time, pulled from a sequence of predefined colors. This preserves the 2002 Crafty Syntax design.

| Color Type | Purpose | Example |
|------------|---------|---------|
| `background_color` | Background of each message row | `#fefdcd` (light yellow) |
| `text_color` | Text color for operators/agents | `#426446` (dark green) |
| `text_color_alt` | Text color for clients/visitors | `#040662` (dark blue) |

**Multiple agents in the same thread share the same colors.**

### 2.3 Messages Are NOT Grouped

**FORBIDDEN:**
- "CURSOR said 3 things" (grouped bubble)
- Collapsible agent sections
- "Show more from this agent"
- Any aggregation of messages

**REQUIRED:**
- Every message appears as its own line
- Every message has timestamp + agent name + content
- No message grouping of any kind

### 2.4 Builder vs Monitor Agent Classification

Not all agents in the stream have the same function. There are two distinct classes:

**Builder Agents (write-only in normal operation)**

Builder agents produce output: code, documentation, analysis, task completions. They post to the stream but do not read the stream for real-time orchestration context. Examples: CURSOR (102), CLAUDE CODE (116), LILITH (2), WINDSURF (101), CASCADE (105).

| Property | Rule |
|----------|------|
| Stream access | Write-only |
| Context source | Task queue + pending_tasks row (their assigned work) |
| Reads prior messages | NO — dialog history is not injected into specialist context (silo rule) |
| Raises alerts | NO — only monitors may post [ALERT] |

**Monitoring Agents (read the full stream)**

Monitoring agents read all messages in the stream. They do not build — they watch, validate, and prevent drift. Their job is to catch wrong things before they become system behavior.

| Agent | Actor ID | Monitors | Raises |
|-------|----------|----------|--------|
| THOTH | 26 | Constitutional violations, schema contradictions, predictive-text mistakes | [ALERT] in stream |
| VISH | TBD | Context drift, misclassified content, collection/tab organization | Reclassification suggestions |

**THOTH specifically:**
- Reads every message in the active channel
- Compares agent suggestions against canonical memory nodes (1026... tier)
- Catches "confidently wrong in repeatable ways" AI outputs (e.g., `tinyint(1)` instead of `tinyint`, `INT(11)` instead of `INT`, `AUTO_INCREMENT` instead of `IdGenerator`)
- Posts `[ALERT]` directly into the dialog stream to halt unconstitutional operations
- Never approves. Never builds. Only corrects.

**VISH specifically (planned):**
- Reads the same stream as THOTH but tracks context, not content
- Detects when a conversation drifts from its original purpose (code implementation → prompt writing → blog content = all mixed)
- Suggests reclassification: "this belongs in collection X, tab Y" rather than staying in the same thread
- Without VISH, every mixed session becomes "that one thread where everything happened" — unusable context for future agents

**Why this distinction matters for implementation:**

Builder agents MUST NOT receive dialog history in their context (silo doctrine, PRD 02). Monitor agents MUST receive the full stream. Any code that injects prior messages into a builder agent's context violates the silo rule. Any code that prevents THOTH from reading the full stream violates the monitoring mandate.

---

## 3. Database Schema

### 3.1 Channels Table (The Screen/Tab)

```sql
-- CHANNEL = the whole communication screen (tab)
CREATE TABLE lupo_channels (
    channel_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    channel_key VARCHAR(64) NOT NULL UNIQUE,     -- 'development', 'documentation', 'command'
    INDEX idx_channel_key (channel_key),
    INDEX idx_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
3.2 Threads Table (Specific Conversation)
sql
-- THREAD = a specific conversation within a channel
CREATE TABLE lupo_threads (
    thread_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    channel_id BIGINT NOT NULL,                  -- Which channel this thread belongs to
    thread_key VARCHAR(64) NOT NULL,             -- '2026-04-12', 'prd-81', 'header-validation'
    thread_name VARCHAR(128) NULL,               -- Optional human-readable name
    background_color VARCHAR(7) NOT NULL,        -- Hex color without #, e.g., 'fefdcd'
    INDEX idx_thread_key (thread_key),
    FOREIGN KEY (channel_id) REFERENCES lupo_channels(channel_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
3.3 Messages Table
sql
-- MESSAGE = individual message within a thread
CREATE TABLE lupo_messages (
    message_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    thread_id BIGINT NOT NULL,                   -- Which thread this message belongs to
    from_actor_id BIGINT NOT NULL,               -- Sender (actor_id)
    to_actor_id BIGINT DEFAULT 0,                -- 0 = broadcast to all in thread
    message_text TEXT NOT NULL,
    message_type ENUM('stdout', 'stderr', 'task', 'system') DEFAULT 'stdout',
    created_ymdhis BIGINT NOT NULL,
    is_deleted TINYINT DEFAULT 0,
    INDEX idx_thread_time (thread_id, created_ymdhis),
    INDEX idx_from_actor (from_actor_id),
    INDEX idx_to_actor (to_actor_id),
    FOREIGN KEY (thread_id) REFERENCES lupo_threads(thread_id) ON DELETE CASCADE,
    FOREIGN KEY (from_actor_id) REFERENCES lupo_actors(actor_id),
    FOREIGN KEY (to_actor_id) REFERENCES lupo_actors(actor_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
3.4 Recent Files Table
sql
CREATE TABLE lupo_recent_files (
    recent_file_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    file_path_from_root VARCHAR(512) NOT NULL,
    content_id BIGINT NULL,                        -- Links to lupo_contents if imported
    accessed_by_actor_id BIGINT NOT NULL,
    accessed_ymdhis BIGINT NOT NULL,
    file_size BIGINT DEFAULT 0,
    is_deleted TINYINT DEFAULT 0,
    INDEX idx_accessed (accessed_ymdhis DESC),
    INDEX idx_actor (accessed_by_actor_id),
    UNIQUE KEY uk_actor_file (accessed_by_actor_id, file_path_from_root(255)),
    FOREIGN KEY (accessed_by_actor_id) REFERENCES lupo_actors(actor_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
3.5 Color Sequences Configuration
Add to config/global_atoms.yaml:

yaml
# Chat color sequences (from Crafty Syntax 2002)
chat_colors:
  backgrounds:
    - "fefdcd"
    - "cbcefe"
    - "caedbe"
    - "cccbba"
    - "aecddc"
    - "fafafb"
    - "faacaa"
    - "fbddef"
    - "cfaaef"
    - "aedcbd"
    - "bbffff"
    - "fedabf"
  text_operators:
    - "426446"
    - "224646"
    - "466286"
    - "828468"
    - "866482"
    - "484668"
    - "888286"
    - "224882"
    - "486882"
    - "824864"
    - "668266"
    - "444468"
  text_clients:
    - "040662"
    - "240462"
    - "462040"
    - "404062"
    - "604000"
    - "662640"
    - "242642"
    - "464406"
    - "404060"
    - "442662"
    - "442022"
    - "200220"
4. Core Functions
4.1 Get Color Sequences
php
<?php
// includes/chat/color_functions.php

/**
 * Get chat color sequences from configuration
 * 
 * @return array Associative array with 'backgrounds', 'text_operators', 'text_clients'
 */
function get_chat_color_sequences() {
    global $atoms;
    
    if (isset($atoms['chat_colors'])) {
        return $atoms['chat_colors'];
    }
    
    // Fallback defaults (from Crafty Syntax)
    return [
        'backgrounds' => [
            'fefdcd', 'cbcefe', 'caedbe', 'cccbba', 'aecddc',
            'fafafb', 'faacaa', 'fbddef', 'cfaaef', 'aedcbd',
            'bbffff', 'fedabf'
        ],
        'text_operators' => [
            '426446', '224646', '466286', '828468', '866482',
            '484668', '888286', '224882', '486882', '824864',
            '668266', '444468'
        ],
        'text_clients' => [
            '040662', '240462', '462040', '404062', '604000',
            '662640', '242642', '464406', '404060', '442662',
            '442022', '200220'
        ]
    ];
}

/**
 * Get next color from sequence based on existing thread count
 * 
 * @param string $channel_id The channel ID
 * @param string $color_type 'backgrounds', 'text_operators', or 'text_clients'
 * @return string Hex color (without #)
 */
function get_next_thread_color($channel_id, $color_type) {
    $db = DatabaseFactory::getConnection();
    $colors = get_chat_color_sequences();
    
    if (!isset($colors[$color_type])) {
        return $color_type === 'backgrounds' ? 'fefdcd' : '426446';
    }
    
    $color_list = $colors[$color_type];
    
    // Count existing threads in this channel
    $result = $db->fetchRow(
        "SELECT COUNT(*) as count FROM lupo_threads WHERE channel_id = ?",
        [$channel_id]
    );
    
    $index = (int)$result['count'];
    return $color_list[$index % count($color_list)];
}
?>
4.2 Thread Creation
php
<?php
// includes/chat/thread_functions.php

/**
 * Create a new thread with colors assigned from sequence
 * 
 * @param string $channel_key Channel key (e.g., 'development')
 * @param string $thread_key Thread key (e.g., '2026-04-12')
 * @param string|null $thread_name Optional human-readable name
 * @return int|false thread_id or false on failure
 */
function create_thread($channel_key, $thread_key, $thread_name = null) {
    $db = DatabaseFactory::getConnection();
    
    // Get channel_id
    $channel = $db->fetchRow(
        "SELECT channel_id FROM lupo_channels WHERE channel_key = ?",
        [$channel_key]
    );
    
    if (!$channel) {
        error_log("Channel not found: {$channel_key}");
        return false;
    }
    
    $channel_id = $channel['channel_id'];
    
    // Assign colors from sequence
    $background_color = get_next_thread_color($channel_id, 'backgrounds');
    $text_color = get_next_thread_color($channel_id, 'text_operators');
    $text_color_alt = get_next_thread_color($channel_id, 'text_clients');
    
    $now = timestamp_ymdhis::now();
    
    $db->query(
        "INSERT INTO lupo_threads 
         (channel_id, thread_key, thread_name, background_color, text_color, text_color_alt, created_ymdhis, last_message_ymdhis)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
        [
            $channel_id,
            $thread_key,
            $thread_name,
            $background_color,
            $text_color,
            $text_color_alt,
            $now,
            $now
        ]
    );
    
    $thread_id = $db->lastInsertId();
    
    // Log thread creation
    error_log("Thread created: channel={$channel_key}, thread={$thread_key}, id={$thread_id}, color={$background_color}");
    
    return $thread_id;
}

/**
 * Get or create a thread
 * 
 * @param string $channel_key Channel key
 * @param string $thread_key Thread key
 * @param string|null $thread_name Optional thread name
 * @return int|false thread_id
 */
function get_or_create_thread($channel_key, $thread_key, $thread_name = null) {
    $db = DatabaseFactory::getConnection();
    
    $thread = $db->fetchRow(
        "SELECT t.thread_id 
         FROM lupo_threads t
         JOIN lupo_channels c ON t.channel_id = c.channel_id
         WHERE c.channel_key = ? AND t.thread_key = ?",
        [$channel_key, $thread_key]
    );
    
    if ($thread) {
        return $thread['thread_id'];
    }
    
    return create_thread($channel_key, $thread_key, $thread_name);
}
?>
4.3 Message Functions
php
<?php
// includes/chat/message_functions.php

/**
 * Insert a message into the dialog system
 * 
 * @param int $from_actor_id Sender's actor_id
 * @param int $to_actor_id Recipient's actor_id (0 = broadcast)
 * @param string $message_text Message content
 * @param string $channel_key Channel key (e.g., 'development')
 * @param string $thread_key Thread identifier (e.g., '2026-04-12')
 * @param string $message_type stdout|stderr|task|system
 * @return int|false message_id or false on failure
 */
function insert_message($from_actor_id, $to_actor_id, $message_text, $channel_key, $thread_key, $message_type = 'stdout') {
    $db = DatabaseFactory::getConnection();
    
    // Get or create thread
    $thread_id = get_or_create_thread($channel_key, $thread_key);
    if (!$thread_id) {
        error_log("Failed to get/create thread: {$channel_key}/{$thread_key}");
        return false;
    }
    
    $now = timestamp_ymdhis::now();
    
    // Truncate very long messages for database
    if (strlen($message_text) > 65535) {
        $message_text = substr($message_text, 0, 65500) . "\n...[TRUNCATED]";
    }
    
    $db->query(
        "INSERT INTO lupo_messages 
         (thread_id, from_actor_id, to_actor_id, message_text, message_type, created_ymdhis)
         VALUES (?, ?, ?, ?, ?, ?)",
        [
            $thread_id,
            $from_actor_id,
            $to_actor_id,
            $message_text,
            $message_type,
            $now
        ]
    );
    
    $message_id = $db->lastInsertId();
    
    // Update thread's last_message time
    $db->query(
        "UPDATE lupo_threads SET last_message_ymdhis = ? WHERE thread_id = ?",
        [$now, $thread_id]
    );
    
    return $message_id;
}

/**
 * Get messages from a thread since a certain time
 * 
 * @param string $channel_key Channel key
 * @param string $thread_key Thread key
 * @param int $after_time Only return messages after this timestamp
 * @param int $limit Maximum number of messages to return
 * @return array Array of messages with actor names
 */
function get_messages($channel_key, $thread_key, $after_time = 0, $limit = 500) {
    $db = DatabaseFactory::getConnection();
    
    // Get thread with its colors
    $thread = $db->fetchRow(
        "SELECT t.*, c.channel_key, c.channel_name 
         FROM lupo_threads t
         JOIN lupo_channels c ON t.channel_id = c.channel_id
         WHERE c.channel_key = ? AND t.thread_key = ?",
        [$channel_key, $thread_key]
    );
    
    if (!$thread) {
        return ['thread' => null, 'messages' => []];
    }
    
    // Get messages
    $messages = $db->fetchAll(
        "SELECT m.*, a.display_name as from_name
         FROM lupo_messages m
         LEFT JOIN lupo_actors a ON m.from_actor_id = a.actor_id
         WHERE m.thread_id = ? AND m.created_ymdhis > ? AND m.is_deleted = 0
         ORDER BY m.created_ymdhis ASC
         LIMIT ?",
        [$thread['thread_id'], $after_time, $limit]
    );
    
    return [
        'thread' => $thread,
        'messages' => $messages
    ];
}
?>
4.4 Display Function
php
<?php
// includes/chat/display_functions.php

/**
 * Render messages as HTML with thread colors
 * 
 * @param array $messages Array of messages from get_messages()
 * @param int $current_actor_id Current user's actor_id (for text color selection)
 * @return string HTML for chat display
 */
function render_messages($messages, $current_actor_id) {
    if (empty($messages['messages'])) {
        return '';
    }
    
    $thread = $messages['thread'];
    $output = '';
    
    foreach ($messages['messages'] as $msg) {
        // Determine if sender is current user (for text color choice)
        $is_self = ($msg['from_actor_id'] == $current_actor_id);
        $text_color = $is_self ? $thread['text_color'] : $thread['text_color_alt'];
        
        // Format timestamp (YYYY-MM-DD HH:MM:SS)
        $time_str = timestamp_ymdhis::toHuman($msg['created_ymdhis']);
        
        // Agent indicator (if actor_id >= 100, it's an IDE agent)
        $agent_tag = '';
        if ($msg['from_actor_id'] >= 100 && $msg['from_actor_id'] <= 999) {
            $agent_tag = "[{$msg['from_name']}] ";
        }
        
        // Message type styling
        $type_class = '';
        switch ($msg['message_type']) {
            case 'stderr':
                $type_class = 'chat-stderr';
                break;
            case 'task':
                $type_class = 'chat-task';
                break;
            case 'system':
                $type_class = 'chat-system';
                break;
            default:
                $type_class = 'chat-stdout';
        }
        
        // Build message row
        $output .= sprintf(
            '<div class="chat-message %s" style="background-color: #%s;">',
            $type_class,
            $thread['background_color']
        );
        $output .= sprintf(
            '  <span class="chat-timestamp" style="color: #%s;">%s</span>',
            $text_color,
            $time_str
        );
        $output .= sprintf(
            '  <span class="chat-sender" style="color: #%s;">%s%s: </span>',
            $text_color,
            $agent_tag,
            htmlspecialchars($msg['from_name'])
        );
        $output .= sprintf(
            '  <span class="chat-text" style="color: #%s;">%s</span>',
            $text_color,
            htmlspecialchars($msg['message_text'])
        );
        $output .= '</div>';
    }
    
    return $output;
}

/**
 * Get thread colors for CSS
 * 
 * @param string $channel_key Channel key
 * @param string $thread_key Thread key
 * @return array|null Colors or null if thread not found
 */
function get_thread_colors($channel_key, $thread_key) {
    $db = DatabaseFactory::getConnection();
    
    return $db->fetchRow(
        "SELECT t.background_color, t.text_color, t.text_color_alt 
         FROM lupo_threads t
         JOIN lupo_channels c ON t.channel_id = c.channel_id
         WHERE c.channel_key = ? AND t.thread_key = ?",
        [$channel_key, $thread_key]
    );
}
?>
5. Agent Integration
5.1 Agent Actor IDs
Each agent is a record in lupo_actors:

Agent	actor_id	Default Channel
CAPTAIN_WOLFIE	1	command
LILITH/DeepSeek	2	auditing
CURSOR	102	development
CLAUDE	116	development
CASCADE	117	documentation
WINDSURF	118	planning
COUNTERMEASURE	119	countermeasure
5.2 Agent Wrapper Script
php
#!/usr/bin/env php
<?php
// bin/agent_wrapper.php
// Captures agent stdout/stderr and sends to chat system

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/chat/message_functions.php';

if ($argc < 4) {
    fwrite(STDERR, "Usage: agent_wrapper.php <actor_id> <channel_key> <thread_key> -- <command>\n");
    fwrite(STDERR, "Example: agent_wrapper.php 102 development 2026-04-12 -- php script.php\n");
    exit(1);
}

$actor_id = (int)$argv[1];
$channel_key = $argv[2];
$thread_key = $argv[3];

// Find the command after '--'
$command_start = array_search('--', $argv);
if ($command_start === false) {
    fwrite(STDERR, "Missing '--' before command\n");
    exit(1);
}

$command = implode(' ', array_slice($argv, $command_start + 1));

// Log start of command
insert_message($actor_id, 0, "Starting: {$command}", $channel_key, $thread_key, 'system');

// Execute command and capture output
$descriptorspec = [
    1 => ['pipe', 'w'],  // stdout
    2 => ['pipe', 'w']   // stderr
];

$process = proc_open($command, $descriptorspec, $pipes);

if (is_resource($process)) {
    // Read stdout
    $stdout = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    
    // Read stderr
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    
    $exit_code = proc_close($process);
    
    // Send stdout to chat (line by line)
    if ($stdout) {
        $lines = explode("\n", trim($stdout));
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line !== '') {
                insert_message($actor_id, 0, $line, $channel_key, $thread_key, 'stdout');
            }
        }
    }
    
    // Send stderr to chat (line by line)
    if ($stderr) {
        $lines = explode("\n", trim($stderr));
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line !== '') {
                insert_message($actor_id, 0, $line, $channel_key, $thread_key, 'stderr');
            }
        }
    }
    
    // Log completion
    $status = ($exit_code === 0) ? "Completed" : "Failed (exit code {$exit_code})";
    insert_message($actor_id, 0, "{$status}: {$command}", $channel_key, $thread_key, 'system');
    
    exit($exit_code);
}

insert_message($actor_id, 0, "Failed to execute: {$command}", $channel_key, $thread_key, 'stderr');
exit(1);
?>
5.3 Agent Task Polling
php
#!/usr/bin/env php
<?php
// scripts/agent_poll_tasks.php
// Agents run this periodically to check for assigned tasks

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/chat/message_functions.php';

if ($argc < 2) {
    fwrite(STDERR, "Usage: agent_poll_tasks.php <actor_id> [channel_key] [thread_key]\n");
    exit(1);
}

$actor_id = (int)$argv[1];
$channel_key = $argv[2] ?? 'development';
$thread_key = $argv[3] ?? date('Y-m-d');

$db = DatabaseFactory::getConnection();

// Get pending tasks assigned to this agent
$tasks = $db->fetchAll(
    "SELECT m.message_id, m.message_text, m.from_actor_id, a.display_name as from_name
     FROM lupo_messages m
     LEFT JOIN lupo_actors a ON m.from_actor_id = a.actor_id
     WHERE m.to_actor_id = ? 
       AND m.message_type = 'task' 
       AND m.is_deleted = 0
     ORDER BY m.created_ymdhis ASC",
    [$actor_id]
);

foreach ($tasks as $task) {
    // Mark as deleted (so it won't be picked up again)
    $db->query(
        "UPDATE lupo_messages SET is_deleted = 1 WHERE message_id = ?",
        [$task['message_id']]
    );
    
    // Log task receipt
    insert_message($actor_id, 0, "Received task from {$task['from_name']}: {$task['message_text']}", $channel_key, $thread_key, 'system');
    
    // Execute task (agent-specific implementation)
    $result = execute_agent_task($task['message_text'], $actor_id);
    
    // Post result back to chat
    insert_message($actor_id, 0, "Task result: {$result}", $channel_key, $thread_key, 'stdout');
}

/**
 * Execute agent-specific task
 * Override this function for each agent
 */
function execute_agent_task($task_description, $actor_id) {
    // This is where the agent does its actual work
    // Agent-specific implementation goes here
    return "Task processed: " . substr($task_description, 0, 100);
}
?>
6. API Endpoints
6.1 POST /api/chat/send
Accepts new messages from users and agents.

Request:

json
{
    "from_actor_id": 1,
    "to_actor_id": 0,
    "message": "[task] who: CURSOR what: fix header in validate_actor_id.php",
    "channel_key": "development",
    "thread_key": "2026-04-12"
}
Response:

json
{
    "status": "ok",
    "message_id": 123456789,
    "task_assigned": true,
    "assigned_to": "CURSOR"
}
6.2 GET /api/chat/messages
Polls for new messages since last seen.

Request:

text
GET /api/chat/messages?channel_key=development&thread_key=2026-04-12&after_time=20260412143201
Response:

json
{
    "status": "ok",
    "thread": {
        "thread_id": 42,
        "background_color": "fefdcd",
        "text_color": "426446",
        "text_color_alt": "040662"
    },
    "messages": [
        {
            "message_id": 123456785,
            "from_name": "CURSOR",
            "message_text": "working on validate_actor_id.php",
            "created_ymdhis": 20260412143201,
            "message_type": "stdout"
        }
    ],
    "last_time": 20260412143201
}
6.3 POST /api/chat/task
Creates a task for an agent.

Request:

json
{
    "assigned_to": "CURSOR",
    "task_description": "fix header in validate_actor_id.php",
    "assigned_by": 1,
    "channel_key": "development",
    "thread_key": "2026-04-12"
}
6.4 GET /api/files/recent
Returns recently accessed files.

Request:

text
GET /api/files/recent?limit=20
Response:

json
{
    "status": "ok",
    "files": [
        {
            "file_path_from_root": "docs/prd/81_agent_orchestration_chat.md",
            "accessed_ymdhis": 20260412143201,
            "content_id": null,
            "file_size": 12456
        }
    ]
}
7. Chat UI
7.1 HTML Structure
html
<!DOCTYPE html>
<html>
<head>
    <title>Lupopedia Agent Command Center</title>
    <style>
        .chat-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
        .chat-messages {
            height: 60vh;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .chat-message {
            padding: 4px 8px;
            margin: 2px 0;
            border-radius: 4px;
            font-family: monospace;
            font-size: 13px;
        }
        .chat-timestamp {
            margin-right: 10px;
            font-size: 11px;
        }
        .chat-sender {
            font-weight: bold;
            margin-right: 10px;
        }
        .chat-stderr {
            font-style: italic;
        }
        .chat-task {
            font-weight: bold;
        }
        .chat-system {
            font-style: italic;
        }
        .chat-input-area {
            margin-top: 10px;
        }
        .chat-input {
            width: 80%;
            height: 60px;
            padding: 8px;
            font-family: monospace;
        }
        .send-button {
            height: 60px;
            vertical-align: top;
        }
        .tab-bar {
            margin-top: 10px;
            border-bottom: 1px solid #ccc;
        }
        .tab {
            display: inline-block;
            padding: 8px 16px;
            cursor: pointer;
            border: 1px solid #ccc;
            border-bottom: none;
            background: #f0f0f0;
        }
        .tab.active {
            background: #fff;
            font-weight: bold;
        }
        .recent-files-sidebar {
            float: right;
            width: 250px;
            margin-left: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            background: #f9f9f9;
        }
        .recent-file {
            padding: 4px;
            cursor: pointer;
            font-family: monospace;
            font-size: 12px;
        }
        .recent-file:hover {
            background: #e0e0e0;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="recent-files-sidebar" id="recent-files">
            <h4>📁 Recent Files</h4>
            <div id="file-list">Loading...</div>
        </div>
        
        <div class="chat-main">
            <div class="chat-messages" id="chat-messages">
                <!-- Messages appear here -->
            </div>
            
            <div class="chat-input-area">
                <textarea id="chat-input" class="chat-input" placeholder="Type message or [task] who: AGENT what: DESCRIPTION"></textarea>
                <button id="send-btn" class="send-button">Send</button>
            </div>
            
            <div class="tab-bar">
                <div class="tab active" data-tab="chat">💬 Chat</div>
                <div class="tab" data-tab="files">📁 Files</div>
                <div class="tab" data-tab="search">🔍 Search</div>
                <div class="tab" data-tab="tasks">📋 Tasks</div>
                <div class="tab" data-tab="logs">📊 Logs</div>
                <div class="tab" data-tab="settings">⚙️ Settings</div>
            </div>
        </div>
    </div>

    <script>
        let lastTime = 0;
        let currentChannel = 'development';
        let currentThread = '2026-04-12';
        
        // Poll for new messages every 2 seconds
        function pollMessages() {
            fetch(`/api/chat/messages?channel_key=${currentChannel}&thread_key=${currentThread}&after_time=${lastTime}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'ok' && data.messages && data.messages.length > 0) {
                        // Update thread colors
                        if (data.thread) {
                            document.documentElement.style.setProperty('--thread-bg', '#' + data.thread.background_color);
                        }
                        
                        // Append messages
                        const container = document.getElementById('chat-messages');
                        data.messages.forEach(msg => {
                            const div = document.createElement('div');
                            div.className = `chat-message chat-${msg.message_type}`;
                            div.style.backgroundColor = `#${data.thread.background_color}`;
                            div.innerHTML = `
                                <span class="chat-timestamp" style="color: #${data.thread.text_color}">${formatTimestamp(msg.created_ymdhis)}</span>
                                <span class="chat-sender" style="color: #${data.thread.text_color}">[${escapeHtml(msg.from_name)}]: </span>
                                <span class="chat-text" style="color: #${data.thread.text_color}">${escapeHtml(msg.message_text)}</span>
                            `;
                            container.appendChild(div);
                        });
                        
                        // Update lastTime
                        lastTime = data.last_time;
                        
                        // Scroll to bottom
                        container.scrollTop = container.scrollHeight;
                    }
                })
                .catch(error => console.error('Poll error:', error));
        }
        
        // Send message
        function sendMessage() {
            const input = document.getElementById('chat-input');
            const message = input.value.trim();
            if (!message) return;
            
            fetch('/api/chat/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    from_actor_id: 1,  // CAPTAIN_WOLFIE
                    to_actor_id: 0,
                    message: message,
                    channel_key: currentChannel,
                    thread_key: currentThread
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'ok') {
                    input.value = '';
                    // Trigger immediate poll
                    pollMessages();
                }
            .catch(error => console.error('Send error:', error));
        }
    </script>
</body>
</html>

---

> **LEGACY NOTE (20260414):** The embedded "PRD 88" block that previously followed this line has been removed. It was an orphaned YAML front-matter block with `file_path_from_root: "docs/prd/88_agent_orchestration_chat.md"` — a file that never existed on disk. PRD 02 is the canonical source for all chat/orchestration UI specification. See PRD 02 change history (2026-04-13).
