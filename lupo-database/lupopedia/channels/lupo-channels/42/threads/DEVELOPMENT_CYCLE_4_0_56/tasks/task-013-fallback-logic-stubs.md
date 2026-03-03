# TASK-013: Fallback Logic Stubs Implementation
Version: 4.0.55
Status: planned

## Description
Develop the core PHP logic for the fallback database system. This includes creating stubs for conditional file-based read/write (Markdown and CSV) when the primary PDO/SQL connection is unavailable. The system will leverage the new `lupo-database/` structure for persistence.

## Proposed logic
```php
function lupo_db_query($sql, $params = array()) {
    try {
        $db = DatabaseFactory::getConnection();
        return $db->fetchAll($sql, $params);
    } catch (Exception $e) {
        // Fallback to File-Based Database
        return lupo_fallback_read($sql, $params);
    }
}

function lupo_fallback_read($sql, $params) {
    // Logic to parse table from SQL, then read from lupo-database/lupopedia/channels/*.md or csv
    // ... implementation stub
}
```

## Proposed Structure
- `lupo-includes/fallback_db_logic.php`: Core fallback functions
- `lupo-includes/classes/FallbackDB.php`: Object-oriented fallback interface

## Dependencies
- TASK-010: Fallback Database Planning
- TASK-012: Directory Migration
- TASK-014: Full Channels Recursive Migration

## List of Files to be Created/Modified
- `lupo-includes/fallback_db_logic.php`
- `lupo-includes/bootstrap.php` (update to include logic)
- `lupo-includes/classes/FallbackDB.php`
