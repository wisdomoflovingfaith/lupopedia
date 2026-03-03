# TASK-014: Full Channels Recursive Migration
Version: 4.0.55
Status: planned

## Description
Wholesale, recursive move of the existing `lupo-channels/` tree into the new `lupo-database/lupopedia/channels/` directory. This task is the core of the fallback system's channel-truth establishment.

## Step-by-Step Migration Plan
1. **Backup**: Execute a full recursive copy of `lupo-channels/` to a temporary backup directory.
2. **Directory Creation**: Ensure `lupo-database/lupopedia/channels/` exists as a parent.
3. **Move**: Physically move `lupo-channels/*` to the new path, preserving all internal folder structure (e.g., `/tasks/`, `/plans/`, `/threads/`, `/collections/`, etc. for each channel).
4. **Link Audit**: Scan internal Markdown links and update them as needed. If links are relative, they should remain valid. If they are based on common constants, ensured they are updated to reflect the new `LUPO_CHANNELS_DIR` in TASK-011.
5. **Codebase Search-and-Replace**: Perform a global scan for hardcoded `lupo-channels/` strings and replace them with `LUPO_CHANNELS_DIR` or the new path.

## List of Directories to be Moved
- `lupo-channels/0/` (Recursive)
- `lupo-channels/42/` (Recursive - This includes tasks, plans, threads, collections)
- `lupo-channels/66/` (Recursive)
- ... (All other channels)

## Dependencies
- TASK-011: Config Constants Update

## Proposed Config Snippet
- Covered by TASK-011.

## Migration Notes
- Ensure path consistency and update automated scripts that reference channel paths.
