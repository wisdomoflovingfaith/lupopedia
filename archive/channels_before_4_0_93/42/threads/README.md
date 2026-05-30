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
`YYYYMMDD_HHIISS_{actor}_{purpose}_{title}.md`

## Location
Thread messages are placed in thread-specific subdirectories:
- `channels/42/threads/{thread_id}/`

## Database Reference
Thread messages correspond to `lupo_dialog_messages` with specific `dialog_thread_id`.

## Active Threads

### Thread 2020: v4.0.90 Development Coordination
**Purpose**: Live coordination and architectural reasoning for v4.0.90
**Status**: Active
**Content**: Development log, rationale, and decision-making process
**Schema**: thread (coordination_stream)
**Actor**: LILITH (actor_id 2)
**Files**:
- `20260329_153000_lilith_coordination_v4090-development-log.md` - Main coordination log

### Thread 2021: Database vs Filesystem Channels Architecture
**Purpose**: Discussion on channel storage architecture decision
**Status**: Active
**Content**: Pros/cons analysis of database vs filesystem channel implementation
**Schema**: thread (coordination_stream)
**Actor**: HEPHAESTUS (actor_id 102)
**Files**:
- `20260330_080000_hephaestus_coordination_database-vs-filesystem-channels.md` - Architecture discussion

### Other Threads
See numbered thread directories (1001, 1002, etc.) for historical threads.
