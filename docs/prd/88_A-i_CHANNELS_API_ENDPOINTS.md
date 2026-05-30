---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/88_A-i_CHANNELS_API_ENDPOINTS.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/88_A-i_CHANNELS_API_ENDPOINTS.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/88_channels_api_endpoints.toon
  atoms_toon: null
  transcript_jsonl: 0/development/channels-api-endpoints
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: channels-api-endpoints
  lupopedia.schema: prd
  prd_cluster: 00_A-i_88_A-i
  title: 'PRD 88: Channels API Endpoints'
  summary: Defines the complete API specification for channels, threads, and discussions including message handling, task creation, transcript management, and file tracking.
---
# PRD 88: Channels API Endpoints

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

## Overview

This PRD defines the complete API specification for the channels, threads, and discussions system. It includes endpoints for message handling, task creation, transcript management, and file tracking.

## API Endpoints

### POST /api/chat/send
Accepts new messages from users and agents.

**Request:**
```json
{
    "from_actor_id": 1,
    "to_actor_id": 0,
    "message": "[task] who: CURSOR what: fix header in validate_actor_id.php",
    "channel_key": "development",
    "thread_key": "2026-04-12"
}
```

**Response:**
```json
{
    "status": "ok",
    "message_id": 123456789,
    "task_assigned": true,
    "assigned_to": "CURSOR"
}
```

### GET /api/chat/messages
Polls for new messages since last seen.

**Request:**
```
GET /api/chat/messages?channel_key=development&thread_key=2026-04-12&after_time=20260412143201
```

**Response:**
```json
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
```

### POST /api/chat/task
Creates a task for an agent.

**Request:**
```json
{
    "assigned_to": "CURSOR",
    "task_description": "fix header in validate_actor_id.php",
    "assigned_by": 1,
    "channel_key": "development",
    "thread_key": "2026-04-12"
}
```

### POST /api/transcript/append
Accepts new messages from agents (alternative endpoint for agent scripts).

**Request:**
```json
{
    "actor_id": 102,
    "actor_name": "CURSOR",
    "message": "working on validate_actor_id.php",
    "channel_key": "development",
    "thread_id": "2026-04-12",
    "message_type": "stdout"
}
```

**Response:**
```json
{
    "status": "ok",
    "dialog_message_id": 123456789
}
```

### GET /api/transcript/latest
Polls for new messages since last seen (alternative endpoint).

**Request:**
```
GET /api/transcript/latest?channel_key=development&thread_id=2026-04-12&since_id=123456780&limit=50
```

**Response:**
```json
{
    "messages": [
        {
            "dialog_message_id": 123456785,
            "actor_name": "CURSOR",
            "message_text": "working on validate_actor_id.php",
            "created_ymdhis": 20260412143201,
            "background_color": "#1E88E5",
            "text_color": "#FFFFFF"
        }
    ]
}
```

### POST /api/task/create
Creates a new task (sent by CAPTAIN_WOLFIE via chat).

**Request:**
```json
{
    "assigned_to_actor_id": 102,
    "task_description": "review PRD 81 write open questions to status folder",
    "assigned_by_actor_id": 1
}
```

### GET /api/files/recent
Returns recently accessed files.

**Request:**
```
GET /api/files/recent?limit=20
```

**Response:**
```json
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
```

## Cross-References

- PRD 02 — Channels, Threads, and Discussions (parent document)
- PRD 89 — Task System Integration
- PRD 10 — Tasks, Escalations, Human Requests, and Workflow Management
- PRD 50 — Agent Coordination Protocol & Transcript Feed

---

**STATUS:** ACTIVE  
**EFFECTIVE:** Immediate for 4.0.x  

This output complies with Lupopedia Constitutional Root Rules.
