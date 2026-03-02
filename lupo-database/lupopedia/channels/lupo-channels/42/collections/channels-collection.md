# Collection: Channels (Fallback Migration)

This collection documents the full move of all channel-related artifacts into the `lupo-database/` directory.

## Migration Path
Original Path: `lupo-channels/` (All files and subfolders)
Fallback Path: `lupo-database/lupopedia/channels/` (Recursive preservation)

## Key Subfolder Mapping
The entire tree is moved, keeping internal relationships intact:
- `lupo-database/lupopedia/channels/<id>/tasks/`: All MD tasks.
- `lupo-database/lupopedia/channels/<id>/plans/`: All MD plans.
- `lupo-database/lupopedia/channels/<id>/threads/`: All MD threads.
- `lupo-database/lupopedia/channels/<id>/collections/`: Channel-specific object collections.
- `lupo-database/lupopedia/channels/<id>/metadata/`: FLARE routing and relationship maps.

## Risks & Mitigations
- **Path Breakage**: Fixed-string paths like `lupo-channels/42/tasks/task-001.md` will break.
- **Solution**: These MUST be updated to either relative links `tasks/task-001.md` or use the new `LUPO_CHANNELS_DIR` constant for resolution.

## Table Mappings
- `lupo_channels`
- `lupo_channel_state`
- `lupo_channel_content`
- `lupo_broadcasts`
- `lupo_unified_log` (channel-specific logs)

## Version
Created as part of Phase 2 for version 4.0.55.
