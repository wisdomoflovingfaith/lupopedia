# TASK-010: File/Folder/CSV Database Fallback System Implementation
Version: 4.0.55
Status: planned

## Description
Introduce a file-based fallback database system that allows the application to read and write from Markdown and CSV files when the primary database (PDO/SQL) is unavailable. This ensures the 210+ optimized tables remain accessible as "Root Truth" files in the `lupo-database/` directory.

## Updated Requirements
- Fully relocate `lupo-channels/` and all its subfolders to `lupo-database/lupopedia/channels/`.
- All planning, tasks, collections, plans, and threads must be moved recursively to avoid data loss or structure fragmentation.

## Proposed Config Snippet
```php
define('LUPO_DATABASE_DIR', LUPO_PREFIX . 'database');
```

## Proposed Structure
- `lupo-database/`
  - `lupopedia/`
    - `channels/` (Moved from `lupo-channels/`)
    - `actors/` (Moved from `lupo-actors/`)
    - `content/` (Moved from `lupo-content/`)
    - `collections/` (Integrated Sub-nesting)
    - `atoms/` (New)
    - `contents/` (Backup)

## Dependencies
- TASK-011: Config Constants Update
- TASK-014: Full Channels Recursive Migration

## Files/Directories to be Moved/Created
- `lupo-database/` (directory creation)
- `lupopedia-config.php` (update)
- `lupo-channels/` (wholesale recursive move)
