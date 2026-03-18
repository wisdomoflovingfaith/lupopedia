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
