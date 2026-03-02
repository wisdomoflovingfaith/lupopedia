# TASK-011: Config Constants Update for Folder Migration
Version: 4.0.55
Status: planned

## Description
Update `lupopedia-config.php` to include the `LUPO_DATABASE_DIR` constant and all its related sub-constants. This change is required before migrating the folders to ensure the system can resolve correct paths. After the move, all references to channel-related paths must reflect the new location under `lupo-database/lupopedia/channels/`.

## Proposed Config Snippets
```php
// Existing or New Primary Constant
define('LUPO_DATABASE_DIR', LUPO_PREFIX . 'database');

// Updated Sub-Constants
define('LUPO_CHANNELS_DIR', LUPO_DATABASE_DIR . '/lupopedia/channels');
define('LUPO_ACTORS_DIR', LUPO_DATABASE_DIR . '/lupopedia/actors');
define('LUPO_CONTENT_DIR', LUPO_DATABASE_DIR . '/lupopedia/content');
define('LUPO_COLLECTIONS_DIR', LUPO_DATABASE_DIR . '/lupopedia/collections');
define('LUPO_ATOMS_DIR', LUPO_DATABASE_DIR . '/lupopedia/atoms');
define('LUPO_CONTENTS_DIR', LUPO_DATABASE_DIR . '/lupopedia/contents');
```

## Dependencies
- TASK-010: Fallback Database Planning
- TASK-014: Full Channels Recursive Migration

## Files to be Updated
- `lupopedia-config.php`
- `lupo-includes/bootstrap.php`
