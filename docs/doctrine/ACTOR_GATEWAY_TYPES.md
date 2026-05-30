---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/ACTOR_GATEWAY_TYPES.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/ACTOR_GATEWAY_TYPES.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/doctrine/canonical/1026/04/actor-gateway-types.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/doctrine/actor-gateway-types
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: doctrine
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ACTOR_GATEWAY_TYPES.md -- Canonical Gateway Taxonomy
  summary: Canonical taxonomy of gateway types for actors in the Lupopedia multi-agent ecosystem.
---

# Actor Gateway Types - Canonical Taxonomy

## Purpose

This document defines the canonical taxonomy of gateway types for actors in the Lupopedia multi-agent ecosystem. This taxonomy enables Castcade and other agents to understand how to interact with different actors based on their communication capabilities.

## Gateway Types

| gateway | meaning | examples | characteristics |
|---------|---------|----------|-----------------|
| **api_http** | REST/JSON API | OpenAI API, Anthropic API, custom REST endpoints | - HTTP/HTTPS endpoints<br>- JSON request/response<br>- Can be polled/queried<br>- Stateful via tokens |
| **api_ws** | WebSocket API | Real-time chat APIs, streaming endpoints | - Persistent connections<br>- Bidirectional communication<br>- Event-driven<br>- Low latency |
| **local_agent** | Python/daemon/IDE plugin | Castcade, local Python scripts, IDE agents | - Runs on same machine<br>- Direct file access<br>- Can read/write TOONs<br>- No network latency |
| **manual_web_chat** | browser chat, cut/paste | Claude web chat, Gemini web chat, Grok, DeepSeek | - No API endpoint<br>- Manual cut/paste<br>- State tracked in TOONs<br>- Cannot be pinged |
| **ide_panel** | Cursor, Windsurf, Antigravity, Warp | IDE-integrated chat panels | - IDE-integrated<br>- File context aware<br>- May have API or manual<br>- Development focused |
| **system_daemon** | background OS agent | Background services, system monitors | - Runs continuously<br>- System-level access<br>- Event-driven<br>- Autonomous operation |
| **batch_script** | offline script producing TOONs | Data processing scripts, migration tools | - Runs on schedule<br>- Produces TOON output<br>- No interactive interface<br>- Batch processing |

---

## Detailed Descriptions

### api_http
**Use when**: The actor exposes a REST/JSON API that can be called programmatically.

**Properties**:
- Has HTTP/HTTPS endpoint
- Accepts JSON requests
- Returns JSON responses
- Can be queried for status
- May require authentication (API keys, tokens)
- State typically maintained server-side

**Examples**:
- OpenAI GPT API
- Anthropic Claude API
- Custom model endpoints
- Webhook receivers

### api_ws
**Use when**: The actor provides a WebSocket or other persistent connection API.

**Properties**:
- Maintains persistent connection
- Supports real-time bidirectional communication
- Event-driven messaging
- Low latency
- May stream responses

**Examples**:
- Real-time chat APIs
- Streaming data processors
- Live collaboration tools

### local_agent
**Use when**: The actor runs on the same machine and has direct file system access.

**Properties**:
- Local execution
- Direct file system access
- Can read/write TOON files
- No network latency
- Full system access

**Examples**:
- Castcade (registry daemon)
- Local Python scripts
- IDE plugin agents
- File watchers

### manual_web_chat
**Use when**: The actor is accessed through a web browser interface with no API.

**Properties**:
- No API endpoint
- Manual cut/paste interaction
- State must be tracked manually
- Cannot be pinged or polled
- Handoffs via TOON files
- Human-mediated

**Examples**:
- Claude web chat (claude.ai)
- Gemini web chat (gemini.google.com)
- Grok (x.com)
- DeepSeek chat
- Perplexity web interface

### ide_panel
**Use when**: The actor is integrated into an IDE development environment.

**Properties**:
- IDE-integrated interface
- File context awareness
- May have API or be manual
- Development-focused
- Project context available

**Examples**:
- Cursor AI panel
- Windsurf AI
- GitHub Copilot Chat
- Antigravity IDE
- Warp AI terminal

### system_daemon
**Use when**: The actor runs as a background service on the operating system.

**Properties**:
- Continuous operation
- System-level access
- Event-driven responses
- Autonomous operation
- May monitor system state

**Examples**:
- Background monitoring services
- Log file watchers
- Automated cleanup processes
- System health monitors

### batch_script
**Use when**: The actor is a script that runs periodically or on-demand to process data.

**Properties**:
- Scheduled or triggered execution
- Produces TOON files as output
- No interactive interface
- Batch processing
- May read multiple inputs

**Examples**:
- Data migration scripts
- Report generation tools
- Backup processes
- Validation scripts

---

## Usage Guidelines

### Selecting the Right Gateway Type

1. **Is there an API?**
   - Yes → Is it REST/HTTP? → `api_http`
   - Yes → Is it WebSocket? → `api_ws`
   - No → Continue

2. **Is it accessed through a browser?**
   - Yes → `manual_web_chat`
   - No → Continue

3. **Is it IDE-integrated?**
   - Yes → `ide_panel`
   - No → Continue

4. **Does it run locally with file access?**
   - Yes → `local_agent`
   - No → Continue

5. **Is it a background service?**
   - Yes → `system_daemon`
   - No → Continue

6. **Is it a batch processing script?**
   - Yes → `batch_script`
   - No → Review requirements

### Multiple Gateway Types

An actor may support multiple gateway types. In this case:
- List the primary gateway first
- Document all supported gateways in the notes field
- Use the most restrictive gateway for interaction planning

### Evolution of Gateway Types

Actors may evolve from one gateway type to another:
- `manual_web_chat` → `api_http` (when API is added)
- `local_agent` → `api_http` (when exposed as service)
- `batch_script` → `system_daemon` (when made continuous)

Update the registry when gateway capabilities change.

---

## Registry Format

When registering an actor, use this format:

```json
{
  "actor_id": 420,
  "type": "llm",
  "gateway": "manual_web_chat",
  "channel_key": "external",
  "task": "",
  "memory_handoff_toon": "",
  "last_action_utc": 20260420050000,
  "last_lilith_review_utc": 0,
  "last_ara_review_utc": 0,
  "last_thoth_review_utc": 0,
  "last_rose_mood_review_utc": 0,
  "status": "active",
  "notes": "Manual cut/paste web chat; no API endpoint."
}
```

---

## Castcade Integration

Castcade uses gateway types to determine:
- How to communicate with the actor
- Whether the actor can be pinged
- Whether to expect automated responses
- How to track state and handoffs
- What interaction patterns are supported

### Interaction Patterns by Gateway

| Gateway | Can Ping | Can Poll | Auto-Response | Handoff Method |
|---------|----------|----------|---------------|----------------|
| api_http | Yes | Yes | Yes | API call |
| api_ws | Yes | No | Yes | WebSocket message |
| local_agent | Yes | Yes | Yes | Direct function call |
| manual_web_chat | No | No | No | TOON file |
| ide_panel | Maybe | Maybe | Maybe | IDE API or TOON |
| system_daemon | No | No | Maybe | File/system event |
| batch_script | No | No | No | Input file |

---

## Summary

This canonical gateway taxonomy provides:
- Clear classification of actor capabilities
- Predictable interaction patterns
- Proper abstraction for different access methods
- Evolution path from manual to automated interactions
- Foundation for Castcade's actor management

By using these gateway types consistently, Castcade and other agents can properly coordinate with the diverse ecosystem of AI actors, whether they're sophisticated APIs or simple manual web chat interfaces.

---
lupopedia.footer:
  pending_edges:
    - to: docs/prd/PRD_46_ACTOR_GATEWAY_TYPES.md
      reason: "file created in session and must be linked to PRD"
  notes:
    - "When DB is online, this file's edges must be imported into polymorphic edge table."
---
