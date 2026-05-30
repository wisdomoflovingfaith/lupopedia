---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: prd
  when_updated: "20260412203000"
  file_path_from_root: "docs/prd/decisions/pseudocode/02_channels_discussions_constitution.pseudo.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/decisions/pseudocode/02_channels_discussions_constitution.pseudo.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "memory/development/canonical/1026/04/02-channels-discussions-constitution.toon"
  artifact_type: prd
  artifact_kind: pseudocode
  thread_id: "pseudocode-02-channels-discussions"
  content_id: null
  pk_id: 2
  pk_slug: "channels-discussions-constitution"
  title: "PRD 2: Channels, Threads, and Discussions Database Tables — Agent Orchestration Edition"
  status: "active"
  parent_pk_id: ""
  summary: "Channel/thread/message schema with agent orchestration chat system (PRD 81 integration)"
  module: "orchestration"
  dialog_transcript: "0/development/prd_02_channels_discussions"
---
# PRD 2: Channels, Threads, and Discussions Database Tables — Agent Orchestration Edition

## One-Line Summary

Channels/threads/messages schema for multi-agent chat orchestration with color-coded threads, task assignment, and file browser.

## Core Rules

- **Format**: BIGINT auto-increment IDs for all tables
- **Thread Colors**: Assigned from sequence at creation time (background, text_operator, text_client)
- **Message Display**: Single chronological column, intermixed by time, colored by thread
- **Purpose**: High-volume agent message streams, task assignment, command center UI
- **Storage**: MySQL database with full metadata
- **Agent Integration**: Wrapper scripts capture stdout/stderr, send to chat system
- **Task System**: `[task] who: AGENT what: DESCRIPTION` syntax in chat

## Agent Orchestration Tables (PRD 81 Integration)

| Table | Purpose |
|-------|---------|
| `lupo_channels` | Communication screens/tabs (Development, Documentation, Command Center) |
| `lupo_threads` | Specific conversations with assigned colors (background, text_color, text_color_alt) |
| `lupo_messages` | Individual messages from agents (stdout, stderr, task, system types) |
| `lupo_recent_files` | Recently accessed files for sidebar browser |
| `lupo_actors` | Agent identities (CURSOR=102, CLAUDE=116, LILITH=2, etc.) |

## Thread Color Assignment

Colors are assigned **per thread** at creation time, cycling through sequences:

```php
// Color sequences (from Crafty Syntax 2002)
backgrounds: fefdcd, cbcefe, caedbe, cccbba, aecddc, fafafb, faacaa, fbddef, cfaaef, aedcbd, bbffff, fedabf
text_operators: 426446, 224646, 466286, 828468, 866482, 484668, 888286, 224882, 486882, 824864, 668266, 444468
text_clients: 040662, 240462, 462040, 404062, 604000, 662640, 242642, 464406, 404060, 442662, 442022, 200220
```

## Message Display Rules

**REQUIRED:**
- One column, chronological (oldest to newest)
- All agents intermixed by timestamp
- Thread background color on each message row
- Timestamp on every message: `[HH:MM:SS]`
- Agent name in brackets: `[CURSOR]`
- Task assignment syntax: `[task] who: AGENT what: DESCRIPTION`

**FORBIDDEN:**
- Separate columns per agent
- Grouping messages by agent
- Agent-specific colors (colors belong to threads)
- Threaded replies that split conversation

## API Endpoints (Agent Orchestration)

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/api/chat/send` | POST | Send message or assign task |
| `/api/chat/messages` | GET | Poll for new messages (since last_time) |
| `/api/chat/task` | POST | Create agent task |
| `/api/files/recent` | GET | Get recently accessed files |

## Agent Wrapper Usage

```bash
# Capture agent output and send to chat
bin/agent_wrapper.php 102 development 2026-04-12 -- php script.php

# Agent task polling (run every 30 seconds)
scripts/agent_poll_tasks.php 102 development 2026-04-12
```

## Forbidden Patterns

- ❌ NO foreign keys (except actor references)
- ❌ NO triggers
- ❌ NO stored procedures
- ❌ NO upgrade migrations (fresh install only)
- ❌ NO agent-specific colors (colors belong to threads)
- ❌ NO separate columns per agent in UI
- ❌ NO message grouping by agent
- ❌ NO agents reading other agents' messages (prevents parrot loops)

## Required Patterns

- ✅ MUST assign thread colors from sequence at creation
- ✅ MUST display messages in single chronological column
- ✅ MUST include timestamp on every message
- ✅ MUST use `[AGENT]` notation for agent identification
- ✅ MUST support `[task] who: AGENT what: DESCRIPTION` syntax
- ✅ MUST capture agent stdout/stderr via wrapper script
- ✅ MUST poll for new messages every 1-2 seconds
- ✅ MUST track recently accessed files for sidebar

## Channel Configuration (Command Center)

| channel_key | channel_name | display_order | purpose |
|-------------|--------------|---------------|---------|
| `command` | Command Center | 0 | CAPTAIN_WOLFIE commands |
| `development` | Development | 1 | CURSOR, CLAUDE work |
| `documentation` | Documentation | 2 | CASCADE writes docs |
| `planning` | Planning | 3 | WINDSURF makes plans |
| `auditing` | Auditing | 4 | LILITH/DeepSeek audits |
| `countermeasure` | Countermeasure | 5 | COUNTERMEASURE reviews |

## Agent Actor IDs

| Agent | actor_id | default channel |
|-------|----------|-----------------|
| CAPTAIN_WOLFIE | 1 | command |
| LILITH/DeepSeek | 2 | auditing |
| CURSOR | 102 | development |
| CLAUDE | 116 | development |
| CASCADE | 117 | documentation |
| WINDSURF | 118 | planning |
| COUNTERMEASURE | 119 | countermeasure |

## UI Components

```
┌─────────────────────────────────────────────────────────────────────┐
│                         CHAT (Chronological, Thread-Colored)        │
│                                                                      │
│  [14:32:01] [CURSOR] working on validate_actor_id.php header        │
│  [14:32:15] [CLAUDE] i did this                                     │
│  [14:32:28] [CASCADE] making the documentation                      │
│  [14:32:42] [WINDSURF] making implementation plan                   │
│  [14:33:01] [LILITH] auditing new md file from cursor               │
│  [14:33:15] [CURSOR] got revision from Lilith working on corrections│
│  [14:33:30] [CAPTAIN_WOLFIE] [task] who: CURSOR what: fix headers   │
│                                                                      │
├─────────────────────────────────────────────────────────────────────┤
│  📁 RECENT FILES                                                     │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ 📄 PRD_81_agent_chat.md                     (2 min ago)    │     │
│  │ 📄 scripts/validate_actor_id.php       (10 min ago)   │     │
│  │ 📄 docs/prd/16_lupopedia_headers.md    (15 min ago)   │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                      │
├─────────────────────────────────────────────────────────────────────┤
│ [📁 Files] [🔍 Search] [💬 Chat] [📋 Tasks] [📊 Logs] [⚙️ Settings] │
└─────────────────────────────────────────────────────────────────────┘
```

## Constitutional Cross-References

- See PRD 00 for root rules
- See PRD 15 for actor lifecycle
- See PRD 38 for memory unification
- See PRD 50 for agent coordination protocol
- See PRD 81 for agent orchestration chat system (this integration)

## Token-Efficient Checklist

- [ ] Read full PRD for complete context
- [ ] Apply core rules above
- [ ] Check forbidden patterns
- [ ] Verify required patterns
- [ ] Configure color sequences in `config/global_atoms.yaml`
- [ ] Set up agent wrapper for each IDE
- [ ] Configure agent polling cron jobs
- [ ] Cross-reference with PRD 81 for full implementation

---
*Auto-generated by `scripts/generate_prd_shorthands.py`*
*Source: `docs/prd/02_channels_discussions.md` and `docs/prd/81_agent_orchestration_chat.md`*
*Last sync: 20260412203000*
```

This merges the agent orchestration chat system (PRD 81) into the channels/discussions pseudocode (PRD 2), preserving the 2002 color sequence logic while updating to 2026 Lupopedia standards. 🎯