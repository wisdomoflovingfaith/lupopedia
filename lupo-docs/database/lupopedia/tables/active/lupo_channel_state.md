---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_channel_state.md"
  web_path: "[lupo_channel_state](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_channel_state)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "channels"
  purpose: "Channel state and metadata; tracks channel configuration, status, and operational parameters"
  tags: ["database", "table", "channels"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_channel_state table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=1 python_hits=1"
  outbound_edges:
    - { to: "database.table.lupo_channel_state", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-bin/initialize_system.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "analyze_unused_tables.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "wolfie"
---
# file: lupo_channel_state — session: L-LUPO-ROOT-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_channel_state

# Table: lupo_channel_state

Canonical table for **channel state and metadata management**. Tracks channel configuration, status, and operational parameters across the Lupopedia ecosystem.

## Purpose

- Store channel configuration and operational parameters
- Track channel status and health metrics
- Support channel-specific settings and preferences
- Enable channel lifecycle management
- Provide audit trail for channel state changes

## Schema (install SQL authority)

| Column | Type | Description |
|--------|------|-------------|
| channel_state_id | bigint NOT NULL | Primary key; **application-supplied** (no AUTO_INCREMENT). |
| channel_id | bigint NOT NULL | Channel this state belongs to. |
| state_key | varchar(128) NOT NULL | Configuration key or state parameter name. |
| state_value | text DEFAULT NULL | Value associated with the state key. |
| state_type | varchar(64) NOT NULL | Type of state value (config, status, metric, etc.). |
| updated_by_actor_id | bigint DEFAULT NULL | Actor who last updated this state. |
| federation_node_id | bigint NOT NULL DEFAULT 1 | Federation node that created this state. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when state was created. |
| updated_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when state was last updated. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT NULL | UTC timestamp when state was deleted. |

## Indexes

- `PRIMARY KEY (channel_state_id)`
- `INDEX lupo_channel_state_idx_channel` ON `lupo_channel_state` (`channel_id`)
- `INDEX lupo_channel_state_idx_key` ON `lupo_channel_state` (`state_key`)
- `INDEX lupo_channel_state_idx_type` ON `lupo_channel_state` (`state_type`)
- `INDEX lupo_channel_state_idx_updated` ON `lupo_channel_state` (`updated_ymdhis`, `is_deleted`)

## Where This Table Is Used

### Core System Usage

- **Channel initialization** - Default state setup during channel creation
- **System monitoring** - Channel health and status tracking
- **Configuration management** - Dynamic channel parameter storage
- **Audit logging** - State change tracking and history

### Integration Points

- **Channel controllers** - State management for channel operations
- **Administrative interfaces** - Channel configuration and monitoring
- **Federation sync** - Cross-node state synchronization
- **Analytics systems** - Channel performance and usage metrics

## State Types

- `config` - Configuration parameters and settings
- `status` - Channel operational status and health
- `metric` - Performance metrics and statistics
- `metadata` - Additional channel information and tags

## Namespace

- **Domain:** Channels
- **Subdomain:** Channel Management
- **Related Tables:** `lupo_channels`, `lupo_actor_channels`, `lupo_channel_members`
---
# file: lupo_channel_state ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_channel_state
# Table: lupo_channel_state

Purpose: Auto-generated documentation for lupo_channel_state from TOON schema.
Type: database_table
Status: production_ready
Volume: unknown

## 1. Overview
- Key responsibilities: schema reference, storage, and lookup for this table.
- System role: persists data for the Lupopedia database subsystem.
- Importance: enables data integrity and downstream features tied to this table.

## 2. Schema Reference
Primary Key: channel_state_id
Field Categories: see full field list below.

### All Fields
| Column | Type | Notes |
|---|---|---|
| channel_state_id | bigint NOT NULL | from TOON |
| channel_id | bigint NOT NULL | from TOON |
| active_actors_json | json | from TOON |
| speaker_actors_json | json | from TOON |
| observer_actors_json | json | from TOON |
| layers_enabled_json | json | from TOON |
| operational_mode | varchar(32) | from TOON |
| emotional_state_json | json | from TOON |
| mood_framework | varchar(32) NOT NULL DEFAULT 'western_analytical' | from TOON |
| recent_topics_json | json | from TOON |
| semantic_weight | float DEFAULT 0 | from TOON |
| trend_score | float DEFAULT 0 | from TOON |
| last_activity_ymdhis | bigint | from TOON |
| context_vector | blob | from TOON |
| routing_rules | varchar(32) | from TOON |
| edge_visibility | varchar(32) | from TOON |
| retention_policy | varchar(32) | from TOON |
| decay_policy | varchar(32) | from TOON |
| archive_flag | tinyint DEFAULT 0 | from TOON |
| metadata_json | json | from TOON |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | from TOON |
| updated_ymdhis | bigint NOT NULL | from TOON |

## 3. Relationships and Dependencies
- Primary relationships: not specified in TOON relationships array.
- Referencing tables: unknown (use edge suggester tool).
- Integration points: see outbound edges in FLARE footer.

## 4. Indexes and Performance
Primary Indexes:
- channel_state_id
Performance Indexes:
- lupo_channel_state_idx_channel_id
Index Strategy: derived from TOON index definitions.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_channel_state WHERE channel_state_id = :id;
SELECT COUNT(*) AS total FROM lupo_channel_state WHERE is_deleted = 0;
SELECT * FROM lupo_channel_state ORDER BY channel_state_id DESC LIMIT 25;
UPDATE lupo_channel_state SET updated_ymdhis = :ts WHERE channel_state_id = :id;
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
