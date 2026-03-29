---
lupopedia.headers:
  when_updated: "20260328130000"
  lupopedia.schema: "migration"
  file_path_from_root: "lupo-tmp/fix_registry_auto_increment.sql"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-tmp/fix_registry_auto_increment.sql"
  last_modified_utc: "20260328130000"
  channel_id: 42
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "migration"
  artifact_kind: "sql"
  purpose: Fix AUTO_INCREMENT violation in registry tables to comply with database neutrality doctrine
  tags:
  - "4.0.89"
  - "database"
  - "migration"
  - "auto_increment"
  - "registry_fix"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-rules/root/DATABASE_NEUTRAL_SQL_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: Database neutrality doctrine
    - to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
      type: references
      weight: 1.0
      reason: Schema file being fixed
lupopedia.footer:
  last_verified: "20260328130000"
  last_verified_by: "hephaestus"
  last_verified_by_actor_id: 14
  orchestrator: "wolfie:hephaestus"
  next_action:
    - "Apply migration to fix AUTO_INCREMENT"
    - "Update registry tables to use application-layer IDs"
    - "Test migration on both MySQL and PostgreSQL"
---

# Migration: Remove Registry Tables Entirely

## Issue Identified

LILITH audit found `AUTO_INCREMENT` in registry tables, violating database neutrality doctrine.

## WOLFIE DIRECTIVE: Remove Registry Tables

Registry tables are over-engineered. Timestamp-based IDs (`YYYYMMDDHHIISS` + 5-digit sequence) provide:
- Uniqueness without central tracking
- Sortability by timestamp
- Human-readable format
- 9,999 records per second capacity (far beyond realistic needs)

## Fix Script

```sql
-- Remove registry tables entirely
DROP TABLE IF EXISTS lupo_registry_open;
DROP TABLE IF EXISTS lupo_registry;

-- Note: No replacement needed - timestamp-based IDs are self-describing
```

## Application Changes Required

Implement timestamp-based ID generation:

```php
class IdGenerator
{
    private static $last_timestamp = '';
    private static $sequence = 0;
    
    /**
     * Generate a timestamp-based unique ID
     * Format: YYYYMMDDHHIISS + 4-digit sequence
     */
    public static function generate()
    {
        $timestamp = gmdate('YmdHis');
        
        // Reset sequence if timestamp changed
        if ($timestamp !== self::$last_timestamp) {
            self::$last_timestamp = $timestamp;
            self::$sequence = 0;
        }
        
        self::$sequence++;
        
        // Safety check (9,999 per second capacity)
        if (self::$sequence > 9999) {
            // Wait one second and retry
            usleep(1000000);
            return self::generate();
        }
        
        return $timestamp . str_pad(self::$sequence, 4, '0', STR_PAD_LEFT);
    }
}
```

## Execution

1. Run this migration on existing installations
2. Update application code to use `IdGenerator::generate()`
3. Test on both MySQL and PostgreSQL

## Impact

- Removes MySQL-only AUTO_INCREMENT syntax
- Eliminates registry overhead and complexity
- Enables PostgreSQL compatibility
- Simplifies ID generation to timestamp + sequence
- Maintains uniqueness and sortability
- Follows database neutrality doctrine
