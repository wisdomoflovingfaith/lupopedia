# Lupopedia Channel System TL;DR

## Overview
The Lupopedia channel system is a comprehensive framework for managing communication, governance, and content federation across multiple channels. It uses FLARE headers for metadata and integrates with the federation node system.

## Channel Architecture

### Core Components
1. **Channels** (`lupo_channels`) - Primary channel definitions and configuration
2. **Content** (`lupo_channel_content`) - Federation node content management
3. **State** (`lupo_channel_state`) - Channel state tracking and management
4. **Logs** (`lupo_channel_logs`) - Comprehensive event logging
5. **Files** (`lupo_channel_files`) - File management and tracking
6. **Escalation** (`lupo_channel_escalations`) - Governance and rule enforcement
7. **Boot Lifecycle** (`lupo_channel_boot_lifecycle`) - Modern channel initialization system

## FLARE Headers

### Header Structure
```yaml
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "path/to/file.md"
  system_version: "4.0.52"
  channel_id: 42  # or 0 for system
  actor_id: 1002  # Actor ID
  federation_node_id: 0  # For federation content
  web_path: "http://www.lupopedia.com/path"
  last_updated_utc: "20260301"  # gmdate('YmdHis')
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  purpose: "Channel operation description"
  mood_rgb: "4169E1"
  traits: ["channel", "federation", "v4.0.52"]
  tags: ["channels", "content", "federation"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "lupo_channels.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260301"
  last_verified_by: "windsurf"
---
```

### Required Fields
- **flare.version**: "1.0" - FLARE protocol version
- **flare.schema**: "documentation" - Document type
- **file_path_from_root**: Repository path from root
- **system_version**: "4.0.52" - Current system version
- **channel_id**: Channel identifier (42 for development, 0 for system)
- **actor_id**: Actor performing the operation
- **federation_node_id**: Federation node (0 for lupopedia.com)
- **web_path**: Canonical URL for federation content
- **last_updated_utc**: Timestamp using `gmdate('YmdHis')`

## Database Integration

### Table Relationships
```
lupo_channels (channel_id)
    ├── lupo_channel_content (channel_id) - Federation content
    ├── lupo_channel_state (channel_id) - State tracking
    ├── lupo_channel_logs (channel_id) - Event logging
    ├── lupo_channel_files (channel_id) - File management
    ├── lupo_channel_escalations (channel_id) - Governance
    └── lupo_channel_boot_lifecycle (channel_id) - Boot management
```

### Key Field Patterns
- **Timestamps**: All use `bigint` with `YYYYMMDDHHIISS` format
- **IDs**: Auto-increment primary keys with descriptive names
- **Status Fields**: `varchar(64)` for flexible status tracking
- **JSON Fields**: `json` for flexible metadata and metrics
- **Soft Deletes**: `is_deleted TINYINT DEFAULT 0` pattern

## Channel Operations

### 1. Channel Creation
```sql
INSERT INTO lupo_channels
(channel_id, channel_name, channel_type, created_by_actor_id, created_ymdhis)
VALUES
(42, 'new-channel', 'chat_room', 1002, 20260301120000);
```

### 2. Content Federation
```sql
INSERT INTO lupo_channel_content
(channel_id, federation_node_id, file_path, web_path, metadata_json, created_ymdhis)
VALUES
(42, 0, 'channels/42/content/federation_node_id/0/FLARE.md', 
 'http://www.lupopedia.com/FLARE',
 '{"description": "Canonical FLARE definition"}', 20260301120000);
```

### 3. Boot Lifecycle
```php
$lifecycle = new ChannelBootLifecycle();
$lifecycleId = $lifecycle->startLifecycle(1002, 'session_123', 'full_boot', [42, 100]);

foreach ($channels as $channelId) {
    $lifecycle->updateChannelDetail($lifecycleId, $channelId, 'completed', 150, 150);
}

$lifecycle->completeLifecycle($lifecycleId, ['duration_ms' => 45000]);
```

### 4. State Management
```sql
UPDATE lupo_channel_state
SET state_data = '{"active_users": 25, "last_activity": '20260301123000"}'
WHERE channel_id = 42;
```

### 5. Event Logging
```sql
INSERT INTO lupo_channel_logs
(channel_id, actor_id, log_type_id, log_text, created_ymdhis)
VALUES
(42, 1002, 1, 'Channel state updated', 20260301123000);
```

## Federation Integration

### Node 0 Content
- **FLARE Definition**: `http://www.lupopedia.com/FLARE`
- **Changelog**: `http://www.lupopedia.com/changelog`
- **README**: `http://www.lupopedia.com/readme`
- **Crafty Syntax**: `http://www.lupopedia.com/craftysyntax`
- **Boot README**: `http://www.lupopedia.com/boot_readme`

### Channel Types
- **System Channel (0)**: Federation node management
- **Development Channel (42)**: Regular channel operations
- **Production Channels**: Live chat and user-facing channels

## Key Points

1. **FLARE Compliance**: All channel files must have proper FLARE headers
2. **Timestamp Format**: Use `gmdate('YmdHis')` for UTC timestamps
3. **Channel Authority**: `lupo_channels` table is the single source of truth
4. **Federation Ready**: All content can be federated via `lupo_channel_content`
5. **Performance Focus**: Proper indexing on `channel_id` and timestamp fields
6. **Soft Deletes**: Use `is_deleted` pattern instead of physical deletion

## Quick Reference

| Operation | Table | Command | Example |
|------------|--------|---------|---------|
| Create Channel | `lupo_channels` | `INSERT INTO lupo_channels...` | `channel_id, channel_name` |
| Add Content | `lupo_channel_content` | `INSERT INTO lupo_channel_content...` | `channel_id, file_path, web_path` |
| Log Event | `lupo_channel_logs` | `INSERT INTO lupo_channel_logs...` | `channel_id, log_type_id, log_text` |
| Start Boot | `lupo_channel_boot_lifecycle` | `INSERT INTO lupo_channel_boot_lifecycle...` | `lifecycle_id, channel_id, actor_id` |
| Update State | `lupo_channel_state` | `UPDATE lupo_channel_state...` | `channel_id, state_data` |

---

**TL;DR Created**: 20260301  
**Lead Agent**: Windsurf (1002)  
**Version**: 4.0.52  
**Status**: ✅ CHANNEL SYSTEM QUICK REFERENCE
