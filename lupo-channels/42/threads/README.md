# Threads

Threaded conversations.

## Purpose
Thread messages are part of focused conversations and are used for:
- Feature development discussions
- Bug fix coordination
- Detailed analysis
- Multi-person collaboration

## Format
Follow the standard filename convention:
`YYYYMMDD_HHIISS_{actor}_thread_{purpose}.md`

## Location
Thread messages are placed in thread-specific subdirectories:
- `lupo-channels/42/threads/{thread_id}/`

## Database Reference
Thread messages correspond to `lupo_dialog_messages` with specific `dialog_thread_id`.

## Active Threads

### 20260329-v4090-changelog-discussion
**Purpose**: Live coordination and architectural reasoning for v4.0.90
**Status**: Active
**Content**: Development log, rationale, and decision-making process
**Schema**: thread (coordination_stream)
**Actor**: LILITH (actor_id 2)
**Thread ID**: v4090-changelog-discussion

### Other Threads
See numbered thread directories (1001, 1002, etc.) for historical threads.
