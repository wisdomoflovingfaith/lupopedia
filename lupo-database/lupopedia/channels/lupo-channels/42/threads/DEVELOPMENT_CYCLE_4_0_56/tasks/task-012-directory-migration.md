# TASK-012: Directory Migration (Actors/Content)
Version: 4.0.55
Status: planned

## Description
Physically move the existing `lupo-actors/`, and `lupo-content/` directories into the new `lupo-database/lupopedia/` structure. This also includes creating the new `collections/`, `atoms/`, and `contents/` subfolders for the fallback database.

## List of Moves/Creations
- Move `lupo-actors/` → `lupo-database/lupopedia/actors/` (Recursive)
- Move `lupo-content/` → `lupo-database/lupopedia/content/` (Recursive)
- Create `lupo-database/lupopedia/collections/`
- Create `lupo-database/lupopedia/atoms/`
- Create `lupo-database/lupopedia/contents/`
- Create `lupo-database/lupopedia/metadata/`

## Proposed Config Snippet
- Already covered by TASK-011.

## Dependencies
- TASK-011: Config Constants Update
- TASK-014: Full Channels Recursive Migration

## Migration Notes
- Ensure path consistency and update any automated scripts (e.g., bin/lupo-cli) that reference these paths directly.
