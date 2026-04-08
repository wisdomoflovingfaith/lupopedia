---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260403221336"
  file_path_from_root: "lupo-docs/prd/02_channels_discussions.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/02_channels_discussions.md"
  last_modified_utc: "20260403221336"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-channels-structure"
  actor_id: 102
  actor_name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "prd"
  artifact_kind: "specification"
  purpose: "Channels/threads/messages schema and paths; actor-first attribution; aligns with ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE"
  tags:
    - "prd"
    - "channels"
    - "discussions"
    - "database"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/prd/17_decisions_format.md"
      type: references
      weight: 1.0
      reason: "Decision format specification"
    - to: "lupo-docs/versions/4.0.93/KIRO_WORK_SUMMARY.md"
      type: references
      weight: 1.0
      reason: "Discussions can reference truth items"
    - to: "lupo-docs/prd/01_core_identity.md"
      type: references
      weight: 1.0
      reason: "Channels depend on identity system"
    - to: "lupo-docs/prd/03_truth_knowledge.md"
      type: references
      weight: 1.0
      reason: "Discussions can reference truth items"
    - to: "lupo-docs/versions/4.0.93/KIRO_WORK_SUMMARY.md"
      type: references
      weight: 1.0
      reason: "Kiro's substantial contributions to 4.0.93"
    - to: "lupo-docs/prd/05_auth_user_actor_agent_transformation.md"
      type: references
      weight: 1.0
      reason: "actor_id vs auth_user; visitor chat; department act-as"
    - to: "lupo-docs/prd/15_actors.md"
      type: references
      weight: 1.0
      reason: "Actors belong to departments; shared actor identity"
    - to: "lupo-docs/prd/18_channel_chat_display.md"
      type: references
      weight: 0.95
      reason: "Chat UI attribution to actor_id"
    - to: "lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "LILITH audit: canonical joins; shared actor_id; department policy"
lupopedia.footer:
  last_verified: "20260403221336"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
---

# PRD: Channels, Threads, and Discussions Database Tables

## Channel Directory Structure (4.0.93+)

Channels are organized on disk by federation node and human-readable keys:

```
lupo-channels/
└── {federation_node_id}/
    └── {channel_key}/
        └── {thread_key}/
            ├── decisions/
            │   └── YYYYMMDD_HHIISS_DECISION_title.md
            ├── questions/
            │   └── YYYYMMDD_HHIISS_QUESTION_title.md
            ├── answers/
            │   └── YYYYMMDD_HHIISS_ANSWER_title.md
            └── comments/
                └── YYYYMMDD_HHIISS_COMMENT_title.md
```

**Example:**
```
lupo-channels/
└── 0/
    └── development/
        └── header-format-discussion/
            ├── decisions/
            │   └── 20260402_120000_DECISION_adopt_version_2.md
            ├── questions/
            │   └── 20260402_100000_QUESTION_should_we_use_context_id.md
            ├── answers/
            │   └── 20260402_110000_ANSWER_yes_use_context_id.md
            └── comments/
                └── 20260402_115000_COMMENT_great_solution.md
```

### Channel Index

`lupo-channels/channel_index.md` maps channel keys to metadata:

```markdown
| federation_node_id | channel_key | channel_name | purpose |
|--------------------|-------------|--------------|---------|
| 0 | development | Protocol Development | Core development discussions |
| 0 | security | Security | Security and compliance |
| 0 | governance | Governance | Rules and policies |
| 0 | architecture | Architecture | System design |
```

## Thread Identification Systems

### Database Threads (Web Application)
- **Format**: Numeric auto-increment IDs
- **Example**: Thread ID 1038 in `lupo_dialog_threads` table
- **Purpose**: High-volume message streams, web-based discussions
- **Storage**: MySQL database with full metadata
- **Access / filesystem mirror (numeric):** `lupo-channels/{channel_id}/threads/{dialog_thread_id}/` — must match `lupo_channels` / `lupo_dialog_threads` IDs for API-validated posts (see `CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md`).

### Filesystem Threads (IDE Development)
- **On-disk layout (active — preferred for new markdown threads):** `lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/` with typed subfolders per PRD 17. See **`lupo-docs/prd/29_project_structure.md`**, **`lupo-channels/channel_index.md`**.
- **Format**: `YYYYMMDD_HHIISS_TYPE_TITLE.md` (see **[PRD 17 — Thread filename pattern (authoritative)](17_decisions_format.md#thread-filename-pattern-authoritative)** for per-folder rules: `STATUS` in filenames applies only under `decisions/`, not under `questions/`, `answers/`, or `comments/`.)
- **Example**: `20260402_120000_DECISION_APPROVED_header_format.md` (decisions/); `20260402_130000_QUESTION_header_format.md` (questions/)
- **Purpose**: Structured documentation, decision tracking, PRD development
- **Storage**: Individual markdown files in directories
- **Access**: Direct file system access for IDE agents

### Thread Manifest

Every thread directory MUST contain a `THREAD_MANIFEST.md`:

```yaml
---
thread_key: "header-format-discussion"
channel_key: "development"
federation_node_id: 0
purpose: "Discussion about header format version 2"
start_date: "2026-04-02"
last_activity: "2026-04-02"
status: "active"  # active, concluded, formalized, archived
resolution: ""  # path to resolution document if concluded/formalized
archived_date: ""  # populated on archival
archived_by: ""  # script name or actor
---
```

### Standard Subfolders

Each thread directory should contain standard subfolders for organization:

```
{thread_key}/
├── decisions/
│   └── YYYYMMDD_HHIISS_DECISION_title.md
├── questions/
│   └── YYYYMMDD_HHIISS_QUESTION_title.md
├── answers/
│   └── YYYYMMDD_HHIISS_ANSWER_title.md
└── comments/
    └── YYYYMMDD_HHIISS_COMMENT_title.md
```

Each subfolder contains its own `THREAD_INDEX.md` for tracking contents.

## Overview

**Namespace Purpose:** Manages the core discussion system including channels, threads, messages, and content organization. This namespace enables structured conversations, content moderation, and discussion threading.

**Primary Actors:** 
- Channel moderators (via lupo_channels)
- Discussion participants (via lupo_dialog_messages)
- Thread managers (via lupo_dialog_threads)
- Content curators (via lupo_channel_content)

**Constitutional Compliance:** All tables in this namespace follow Lupopedia constitutional rules:
- NO foreign keys (relationships in application logic)
- NO triggers
- NO stored procedures
- BIGINT timestamps (YYYYMMDDHHIISS UTC)
- Explicit ID generation (application layer)
- Soft delete (is_deleted + deleted_ymdhis)

## Tables in This Namespace

| Table | Purpose | Primary Key | Key Application Relationships |
|-------|---------|-------------|------------------------------|
| `lupo_channels` | Channel definitions and metadata | `channel_id` | Central to all discussion operations |
| `lupo_dialog_threads` | Discussion threads within channels | `thread_id` | Links to `lupo_channels` | Supports human-readable thread_key for channel paths |
| `lupo_dialog_messages` | Individual messages in threads | `message_id` | Links to `lupo_dialog_threads` |
| `lupo_dialog_channels` | Channel-specific dialog configuration | `dialog_channel_id` | Links to `lupo_channels` |
| `lupo_channel_content` | Content associated with channels | `channel_content_id` | Links to `lupo_channels` |
| `lupo_channel_state` | Channel state and status tracking | `channel_state_id` | Links to `lupo_channels` |
| `lupo_channel_departments` | Department assignments for channels | `channel_department_id` | Links to `lupo_channels` and `lupo_departments` |
| `lupo_channel_escalations` | Escalation tracking for channels | `escalation_id` | Links to `lupo_channels` |
| `lupo_channel_escalation_rules` | Rules for channel escalations | `rule_id` | Links to `lupo_channel_escalations` |
| `lupo_channel_files` | File attachments for channels | `channel_file_id` | Links to `lupo_channels` |

### 3. Update `lupo_channels` Table Schema

Add missing columns:

| Column | Type | Description |
|--------|------|-------------|
| `federation_node_id` | BIGINT | Federation node this channel belongs to (0=core) |
| `channel_key` | VARCHAR(64) | Human-readable channel identifier (e.g., 'development') |

**Updated CREATE TABLE:**

```sql
CREATE TABLE lupo_channels (
  channel_id bigint NOT NULL,
  federation_node_id bigint NOT NULL DEFAULT 0,
  channel_key varchar(64) NOT NULL,
  channel_name varchar(255) NOT NULL,
  display_name varchar(255),
  description text,
  channel_type varchar(32) NOT NULL DEFAULT 'public',
  access_level varchar(32) NOT NULL DEFAULT 'open',
  created_by_actor_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  last_activity_ymdhis bigint,
  is_active tinyint NOT NULL DEFAULT 1,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint,
  PRIMARY KEY (channel_id),
  UNIQUE KEY idx_channels_node_key (federation_node_id, channel_key)
);
```

### lupo_dialog_threads
**Purpose**: Discussion threads within channels
**Primary Key**: `thread_id`
**Key Fields**:
- `thread_id` (bigint) - Auto-generated unique identifier
- `thread_key` (varchar(255)) - Human-readable key for channel paths (e.g., "header-format-discussion")
- `title` (varchar(255)) - Thread title
- `channel_id` (bigint) - Links to `lupo_channels`
- `federation_node_id` (bigint) - Federation node scoping

**Key Application Relationships**:
- Links to `lupo_channels` via `channel_id`
- Supports human-readable channel paths: `lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/`
- `thread_key` is auto-populated from title on creation (spaces → hyphens, lowercase)

### `lupo_channels`

**Purpose:** Defines discussion channels with their metadata, access controls, and configuration.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| channel_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| channel_name | VARCHAR(255) | NO |  | Unique channel identifier |
| display_name | VARCHAR(255) | YES | NULL | Human-readable display name |
| description | TEXT | YES | NULL | Channel description |
| channel_type | VARCHAR(32) | NO | 'public' | Type: public, private, system |
| access_level | VARCHAR(32) | NO | 'open' | Access level: open, restricted, private |
| created_by_actor_id | BIGINT | NO |  | Actor who created this channel |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| last_activity_ymdhis | BIGINT | YES | NULL | UTC timestamp of last activity |
| is_active | TINYINT | NO | 1 | Channel active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_channels_name | channel_name | Unique name lookup |
| idx_channels_type | channel_type, is_active | Filter by type and status |
| idx_channels_access | access_level, is_active | Filter by access level |
| idx_channels_created | created_by_actor_id, created_ymdhis | Actor's channels |
| idx_channels_activity | last_activity_ymdhis, is_active | Activity-based queries |

### `lupo_dialog_threads`

**Purpose:** Manages discussion threads within channels, enabling threaded conversations.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| thread_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| channel_id | BIGINT | NO |  | Foreign reference to lupo_channels |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| thread_title | VARCHAR(255) | NO |  | Thread title |
| thread_type | VARCHAR(32) | NO | 'discussion' | Type: discussion, question, announcement |
| status | VARCHAR(32) | NO | 'active' | Thread status |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| last_message_ymdhis | BIGINT | YES | NULL | UTC timestamp of last message |
| message_count | INT | NO | 0 | Total messages in thread |
| is_pinned | TINYINT | NO | 0 | Whether thread is pinned |
| is_locked | TINYINT | NO | 0 | Whether thread is locked |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_threads_channel | channel_id, status, is_deleted | Channel thread lookup |
| idx_threads_actor | actor_id, created_ymdhis | Actor's threads |
| idx_threads_updated | updated_ymdhis, is_deleted | Recently updated threads |

### `lupo_dialog_messages`

**Purpose:** Stores individual messages within discussion threads.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| message_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| thread_id | BIGINT | NO |  | Foreign reference to lupo_dialog_threads |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| message_type | VARCHAR(32) | NO | 'text' | Type: text, image, file, system |
| content | TEXT | NO |  | Message content |
| metadata_json | JSON | YES | NULL | Message metadata (mentions, links, etc.) |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_edited | TINYINT | NO | 0 | Whether message has been edited |
| edit_count | INT | NO | 0 | Number of times edited |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_messages_thread | thread_id, created_ymdhis, is_deleted | Thread message ordering |
| idx_messages_actor | actor_id, created_ymdhis, is_deleted | Actor's messages |
| idx_messages_type | message_type, created_ymdhis | Message type queries |

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 01_core_identity | This → Core | Actor attribution | actor_id columns |
| 02_channels_discussions | This → 03_truth_knowledge | Truth references | Links to truth items in messages |
| 02_channels_discussions | This → 06_content_management | Content references | message_content_id columns |

## State Transitions

| State | Description | Transition To |
|--------|-------------|--------------|
| active | Normal operation | locked, deleted (soft) |
| locked | Thread locked for moderation | active (by moderator), deleted (soft) |
| deleted | Soft-deleted | N/A (can't be restored without explicit action) |

## Security & Privacy

**Message attribution:** Rows reference **`actor_id`** (and API/runtime paths use **`from_actor_id`** where applicable per schema). The **canonical line identity** in a thread is the **actor**, not a bare **`auth_user_id`**. **Multiple** **`auth_users`** may post under the **same** **`actor_id`** when department policy allows — see **[PRD 05](05_auth_user_actor_agent_transformation.md)** and **[PRD 15](15_actors.md)**. **Join-table mental model (approved):** **[`ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`](../doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md)**. Channel-facing display rules: **[PRD 18](18_channel_chat_display.md)**.

Soft delete preserves conversation history for compliance

Content moderation via thread and message status flags

## Testing Requirements

Unit tests for thread creation and message posting

Integration tests for channel-thread-message relationships

Performance tests for message ordering and pagination

Soft delete behavior verification

## Migration Notes

Fresh Install Only - No upgrade path until 4.1.0.

Crafty Syntax Import Mapping:

- livehelp_channels → lupo_channels
- livehelp_messages → lupo_dialog_messages
- livehelp_threads → lupo_dialog_threads

## Usage Patterns

```php
// Create channel
$channelService = new ChannelService();
$channelId = $channelService->create($data);

// Create thread in channel
$threadService = new ThreadService();
$threadId = $threadService->create($channelId, $actorId, $title);

// Post message to thread
$messageService = new MessageService();
$messageId = $messageService->create($threadId, $actorId, $content);


// Soft delete
$service->softDelete($messageId, $currentActorId);

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
