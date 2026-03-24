---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_crafty_syntax_auto_invite.md
  channel_id: 42
  actor_id: 101
  delegation_chain: 101:10000
  artifact_type: documentation
  artifact_kind: database_table
  purpose: Active Lupopedia table for Crafty Syntax auto-invite functionality - replacement
    for livehelp_autoinvite
  mood_rgb: 4169E1
  traits:
  - canonical
  - v4.0.69
  - active
  - crafty_syntax
  tags:
  - database
  - table
  - lupo
  - crafty_syntax
  - auto_invite
  - active
  agent_name_identity: Windsurf IDE Agent
  lupo_agent: windsurf
  when_updated: '20260324174654'
lupopedia.edges:
  outbound_edges:
  - to: migrations/livehelp_autoinvite.md
    type: migrated_from
    weight: 1.0
  - to: lupo_departments.md
    type: references
    weight: 0.8
  - to: lupo_actors.md
    type: references
    weight: 0.7
  semantic_tags:
  - auto_invite
  - crafty_syntax_integration
  - chat_rules
  - visitor_behavior
lupopedia.footer:
  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# lupo_crafty_syntax_auto_invite

## Table Overview

**Purpose:** Active Lupopedia table for configuring automatic chat invitation rules and messages based on visitor behavior, page visits, and time thresholds. This table replaces the legacy `livehelp_autoinvite` table with updated schema and Lupopedia integration.

**Category:** Active (Crafty Syntax Integration)

**Status:** Active - Primary table for auto-invite functionality in Lupopedia 4.0.x

**Version Introduced:** Lupopedia 4.0.0

**Migration Notes:** Migrated from `livehelp_autoinvite` table. Data migration preserves existing auto-invite configurations while updating to new schema format.

**Migration References:** 
- `migrations/livehelp_autoinvite.md` (legacy source)
- `lupo-docs/doctrine/migrations/livehelp_migrations_readme.md`

## Column Documentation

| Column | Type | Null | Default | Description |
|--------|------|------|---------|-------------|
| crafty_syntax_auto_invite_id | bigint NOT NULL auto_increment | NO | - | Primary key for auto-invite rule |
| is_offline | tinyint | NO | 0 | Whether rule applies when operators are offline (0=No, 1=Yes) |
| is_active | tinyint | NO | 0 | Whether this auto-invite rule is active (0=No, 1=Yes) |
| department_id | bigint | NO | 0 | Department ID this rule applies to (0=All departments) |
| message | mediumtext | YES | NULL | Auto-invite message text to display to visitors |
| page_url | varchar(500) | YES | NULL | URL pattern or page identifier for targeting |
| visits | int | NO | 0 | Minimum number of page visits before triggering |
| referrer_url | varchar(500) | YES | NULL | Referrer pattern for targeting specific traffic sources |
| invite_type | varchar(50) | YES | NULL | Type of trigger condition for invitation |
| trigger_seconds | int | NO | 0 | Time in seconds visitor must be on page before invitation |
| operator_user_id | bigint | NO | 0 | Operator user ID this rule is associated with (0=System/global) |
| show_socialpane | tinyint | NO | 0 | Whether to show in social pane (0=No, 1=Yes) |
| exclude_mobile | tinyint | NO | 0 | Exclude mobile visitors from this rule (0=No, 1=Yes) |
| only_mobile | tinyint | NO | 0 | Apply rule only to mobile visitors (0=No, 1=Yes) |
| created_ymdhis | bigint | NO | 20250101000000 | UTC timestamp when rule was created (YYYYMMDDHHIISS format) |
| updated_ymdhis | bigint | NO | 20250101000000 | UTC timestamp when rule was last updated (YYYYMMDDHHIISS format) |
| is_deleted | tinyint | NO | 0 | Soft delete flag (0=Active, 1=Deleted) |
| deleted_ymdhis | bigint | YES | NULL | UTC timestamp when rule was deleted (YYYYMMDDHHIISS format) |

## Relationships

**Foreign Keys (Application-Managed):**
- `department_id` references `lupo_departments.department_id` (application-managed relationship)
- `operator_user_id` references `lupo_actors.actor_id` (application-managed relationship)

**Inbound References:**
- Referenced by chat session logic for invitation triggering
- Referenced by visitor tracking systems for rule evaluation
- Referenced by Crafty Syntax compatibility layer

**Join Patterns:**
```sql
-- Get active auto-invite rules for a department
SELECT * FROM lupo_crafty_syntax_auto_invite 
WHERE is_active = 1 
AND is_deleted = 0
AND (department_id = 0 OR department_id = :dept_id)
AND is_offline = :offline_status;

-- Get rules applicable to specific page
SELECT * FROM lupo_crafty_syntax_auto_invite 
WHERE page_url LIKE :page_pattern 
AND is_active = 1 
AND is_deleted = 0;

-- Get rules for specific operator
SELECT * FROM lupo_crafty_syntax_auto_invite 
WHERE operator_user_id = :operator_id 
AND is_active = 1 
AND is_deleted = 0;
```

## Usage Notes

**Migration Notes:**
- Direct migration from `livehelp_autoinvite` table
- Boolean columns normalized from char(1) to tinyint
- Added Lupopedia standard timestamps and soft delete functionality
- Column names updated for consistency with Lupopedia naming conventions
- URL fields expanded to varchar(500) for modern web addresses

**Compatibility Notes:**
- Maintains compatibility with Crafty Syntax 3.7.5 auto-invite logic
- Enhanced with Lupopedia actor and department integration
- Improved indexing for better performance with large rule sets
- Supports both global and department-specific invitation rules

**Warnings:**
- Always check `is_deleted = 0` when querying active rules
- Use `is_active = 1` for enabled rules only
- Timestamps are in UTC YYYYMMDDHHIISS format, not Unix timestamps
- Department and operator relationships are application-managed, not enforced by database constraints

**Future Considerations:**
- Consider adding rule priority ordering for conflicting rules
- Potential integration with AI-driven invitation timing
- May extend support for additional visitor behavior triggers
- Could add A/B testing functionality for invitation messages

**Historical Changes:**
- Migrated from Crafty Syntax `livehelp_autoinvite` in Lupopedia 4.0.0
- Boolean columns standardized from char(1) to tinyint
- Added comprehensive indexing for performance optimization
- Integrated with Lupopedia department and actor management systems
- Enhanced URL field lengths for modern web application support
