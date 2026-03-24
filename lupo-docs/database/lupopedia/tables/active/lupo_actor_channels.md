---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_actor_channels.md
  web_path: '[lupo_actor_channels](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_channels)'
  last_modified_utc: '20260317'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: core
  purpose: Documentation file with LUPOPEDIA HEADERS applied
  tags:
  - database
  - table
  - core
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_actor_channels table doc at 4.0.79 (grounded
    by repo search; non-exhaustive).
  meta: php_hits=8 python_hits=4
  outbound_edges:
  - to: database.table.lupo_actor_channels
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: debug_captain.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-includes/classes/AgentAwarenessLayer.php
    type: USED_IN_PHP
    weight: 0.9
  - to: lupo-includes/classes/ContentChannelActorResolver.php
    type: USED_IN_PHP
    weight: 0.9
  - to: lupo-includes/modules/api/channels-api.php
    type: USED_IN_PHP
    weight: 0.9
  - to: lupo-includes/modules/channels/ChannelsController.php
    type: USED_IN_PHP
    weight: 0.9
  - to: lupo-includes/modules/channels/channels-controller.php
    type: USED_IN_PHP
    weight: 0.9
  - to: lupo-scripts/verify_grounded_architecture.php
    type: USED_IN_PHP
    weight: 0.7
  - to: lupo-tests/unit/channel_api_security_test.php
    type: USED_IN_PHP
    weight: 0.7
  - to: analyze_unused_tables.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/rebuild_schema_from_toons.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/wolfie_orms.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-tools/anubis_orphan_scanner.py
    type: USED_IN_PYTHON
    weight: 0.5
lupopedia.footer:
  last_verified: '20260317000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_actor_channels ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_channels
# Table: lupo_actor_channels

Purpose: Auto-generated documentation for lupo_actor_channels from TOON schema.
Type: database_table
Status: production_ready
Volume: unknown

## 1. Overview
- Key responsibilities: schema reference, storage, and lookup for this table.
- System role: persists data for the Lupopedia database subsystem.
- Importance: enables data integrity and downstream features tied to this table.

## 2. Schema Reference
Primary Key: actor_channel_id
Field Categories: see full field list below.

### All Fields
| Column | Type | Notes |
|---|---|---|
| actor_channel_id | bigint NOT NULL | from TOON |
| actor_id | bigint NOT NULL | from TOON |
| created_by_actor_id | bigint NOT NULL DEFAULT 0 | from TOON |
| channel_id | bigint NOT NULL | from TOON |
| status | char(1) NOT NULL DEFAULT 'A' | from TOON |
| start_date | bigint | from TOON |
| channel_color | varchar(6) NOT NULL DEFAULT 'F7FAFF' | from TOON |
| last_read_ymdhis | bigint | from TOON |
| muted_until_ymdhis | bigint | from TOON |
| preferences_json | json | from TOON |
| dialog_output_file | varchar(500) | from TOON |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | from TOON |
| updated_ymdhis | bigint NOT NULL | from TOON |
| is_deleted | tinyint NOT NULL DEFAULT 0 | from TOON |
| deleted_ymdhis | bigint | from TOON |

## 3. Relationships and Dependencies
- Primary relationships: not specified in TOON relationships array.
- Referencing tables: unknown (use edge suggester tool).
- Integration points: see outbound edges in FLARE footer.

## 4. Indexes and Performance
Primary Indexes:
- actor_channel_id
Performance Indexes:
- lupo_actor_channels_idx_actor
- lupo_actor_channels_idx_channel
- lupo_actor_channels_idx_created
- lupo_actor_channels_idx_deleted
- lupo_actor_channels_idx_status
- lupo_actor_channels_idx_updated
- lupo_actor_channels_unq_actor_channel
Index Strategy: derived from TOON index definitions.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_actor_channels WHERE actor_channel_id = :id;
SELECT COUNT(*) AS total FROM lupo_actor_channels WHERE is_deleted = 0;
SELECT * FROM lupo_actor_channels ORDER BY actor_channel_id DESC LIMIT 25;
UPDATE lupo_actor_channels SET updated_ymdhis = :ts WHERE actor_channel_id = :id;
```
Best Practices: always filter soft deletes where applicable.
Anti-Patterns: avoid full table scans on large datasets.

## 6. Performance Considerations
- High-volume operations: dependent on feature usage.
- Optimization tips: rely on existing indexes; add new indexes only with TOON updates.
- Scaling considerations: paginate reads and batch writes.

## 7. Data Integrity
- Constraints: see NOT NULL and DEFAULT values in TOON fields.
- Validation rules: enforced at application layer.
- Soft delete: use is_deleted/deleted_ymdhis if present.

## 8. Common Issues and Solutions
- Performance issues: add missing indexes via schema update.
- Data consistency: ensure foreign key relationships are enforced in application logic.
- Troubleshooting: compare against TOON schema for mismatches.

## 9. Future Enhancements
- Enrich relationships with discovered edges.
- Add usage-specific examples once feature usage is known.
