> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

# Lupopedia Channel System TL;DR

## Overview
The Lupopedia channel system is a comprehensive framework for managing communication, governance, and content federation across multiple channels. It uses FLARE headers for metadata and integrates with the federation node system. Focus: System Channel 0 (federation node management) under root actor_id 0.

## Channel Architecture

### Core Components
1. **Channels** (`lupo_channels`) - Primary channel definitions and configuration
2. **Content** (`lupo_channel_content`) - Federation node content management
3. **State** (`lupo_channel_state`) - Channel state tracking and management
4. **Logs** (`lupo_channel_logs`) - Comprehensive event logging
5. **Files** (`lupo_channel_files`) - File management and tracking
6. **Escalation** (`lupo_channel_escalations`) - Governance and rule enforcement
7. **Boot Lifecycle** (`lupo_channel_boot_lifecycle`) - Modern channel initialization system

**Root Boot Agent Checklist (v4.0.52)**:
- Verify all tables exist as `database/lupopedia/toon/*.toon.json` (e.g., `lupo_channels.toon.json`).
- Structure validation: Fields, types (e.g., BIGINT timestamps), indexes, no FKs/triggers per TOON schema.
- Version lock: Align to 4.0.52â€”no additions beyond declared schema.
- If missing: Generate TOONs via codex-ide or windsurf.

## FLARE Headers

### Header Structure
```yaml
# LUPOPEDIA HEADERS (replaces FLARE) â€” see http://www.lupopedia.com/FLARE
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/database/lupopedia/tables/CHANNEL_SYSTEM_TLDR.md"
  system_version: "4.0.52"
  file.last_modified_system_version: "4.0.52"  # Added for tracking
  channel_id: 0  # System channel focus
  actor_id: 0  # Root/system actor
  federation_node_id: 0  # For federation content
  web_path: "http://www.lupopedia.com/path"
  last_modified_utc: "20260301120000"  # Using YmdHis format
  delegation_chain: "0:10000"
  artifact_type: "documentation"
  purpose: "Channel operation description"
  namespace: "core"
  mood_vector: "4169E1"
  traits: ["channel", "federation", "v4.0.52"]
  tags: ["channels", "content", "federation"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "database/lupopedia/toon/lupo_channels.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "database/lupopedia/toon/lupo_channel_content.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "database/lupopedia/toon/lupo_channel_state.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "database/lupopedia/toon/lupo_channel_logs.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "database/lupopedia/toon/lupo_channel_files.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "database/lupopedia/toon/lupo_channel_escalations.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "database/lupopedia/toon/lupo_channel_boot_lifecycle.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260301"
  last_verified_by: "windsurf"
---
```

### Required Fields
- **lupopedia.version**: "1.0" - FLARE protocol version
- **lupopedia.schema**: "documentation" - Document type
- **file_path_from_root**: Repository path from root
- **system_version**: "4.0.52" - Current system version
- **channel_id**: 0 for system focus
- **actor_id**: 0 for root/system operations
- **federation_node_id**: 0 for lupopedia.com
- **web_path**: Canonical URL for federation content
- **last_updated_utc**: Timestamp using `gmdate('YmdHis')` 

## Database Integration

### Table Relationships
```
lupo_channels (channel_id=0)
    +-- lupo_channel_content (channel_id=0) - Federation content
    +-- lupo_channel_state (channel_id=0) - State tracking
    +-- lupo_channel_logs (channel_id=0) - Event logging
    +-- lupo_channel_files (channel_id=0) - File management
    +-- lupo_channel_escalations (channel_id=0) - Governance
    +-- lupo_channel_boot_lifecycle (channel_id=0) - Boot management
```

### Key Field Patterns
- **Timestamps**: All use `bigint` with `YYYYMMDDHHIISS` format
- **IDs**: Auto-increment primary keys with descriptive names
- **Status Fields**: `varchar(64)` for flexible status tracking
- **JSON Fields**: `json` for flexible metadata and metrics
- **Soft Deletes**: `is_deleted TINYINT DEFAULT 0` pattern

## Channel Operations (Channel 0 Focus)

### 1. Channel Creation
```sql
INSERT INTO lupo_channels
(channel_id, channel_name, channel_type, created_by_actor_id, created_ymdhis)
VALUES
(0, 'system-channel', 'federation_node', 0, 20260301120000);
```

### 2. Content Federation
```sql
INSERT INTO lupo_channel_content
(channel_id, federation_node_id, file_path, web_path, metadata_json, created_ymdhis)
VALUES
(0, 0, 'channels/0/content/federation_node_id/0/FLARE.md', 
'http://www.lupopedia.com/FLARE',
'{"description": "Canonical FLARE definition"}', 20260301120000);
```

### 3. Boot Lifecycle
```php
$lifecycle = new ChannelBootLifecycle();
$lifecycleId = $lifecycle->startLifecycle(0, 'system_session', 'full_boot', [0]);

foreach ($channels as $channelId) {  // Focused on 0
    $lifecycle->updateChannelDetail($lifecycleId, $channelId, 'completed', 150, 150);
}

$lifecycle->completeLifecycle($lifecycleId, ['duration_ms' => 45000]);
```

### 4. State Management
```sql
UPDATE lupo_channel_state
SET state_data = '{"active_users": 0, "last_activity": '20260301123000"}'  -- System channel
WHERE channel_id = 0;
```

### 5. Event Logging
```sql
INSERT INTO lupo_channel_logs
(channel_id, actor_id, log_type_id, log_text, created_ymdhis)
VALUES
(0, 0, 1, 'System channel state updated', 20260301123000);
```

### 6. Escalation Operations
```sql
INSERT INTO lupo_channel_escalations
(channel_id, escalation_type, escalated_to_actor_id, escalation_reason, created_ymdhis)
VALUES
(0, 'governance_breach', 10000, 'Channel 0 policy violation', 20260301123000);
```

## Federation Integration

### Node 0 Content
- **FLARE Definition**: `http://www.lupopedia.com/FLARE` 
- **Changelog**: `http://www.lupopedia.com/changelog` 
- **README**: `http://www.lupopedia.com/readme` 
- **Crafty Syntax**: `http://www.lupopedia.com/craftysyntax` 
- **Boot README**: `http://www.lupopedia.com/boot_readme` 

### Channel Types
- **System Channel (0)**: Federation node management (primary focus)
- **Development Channel (42)**: Regular channel operations (secondary)
- **Production Channels**: Live chat and user-facing channels

## Key Points

1. **FLARE Compliance**: All channel files must have proper FLARE headers
2. **Timestamp Format**: Use `gmdate('YmdHis')` for UTC timestamps
3. **Channel Authority**: `lupo_channels` table is the single source of truth
4. **Federation Ready**: All content can be federated via `lupo_channel_content` 
5. **Performance Focus**: Proper indexing on `channel_id` and timestamp fields
6. **Soft Deletes**: Use `is_deleted` pattern instead of physical deletion
7. **TOON Enforcement**: Root boot agent ensures all `database/lupopedia/toon/*.toon.json` exist and match v4.0.52 structure
8. **Actor ID 0**: Represents system agent, not human operator - all system-level operations use this ID

## Quick Reference

| Operation | Table | Command | Example |
|------------|--------|---------|---------|
| Create Channel | `lupo_channels` | `INSERT INTO lupo_channels...` | `channel_id=0, channel_name` |
| Add Content | `lupo_channel_content` | `INSERT INTO lupo_channel_content...` | `channel_id=0, federation_node_id=0, file_path, web_path` |
| Log Event | `lupo_channel_logs` | `INSERT INTO lupo_channel_logs...` | `channel_id=0, log_type_id, log_text` |
| Start Boot | `lupo_channel_boot_lifecycle` | `INSERT INTO lupo_channel_boot_lifecycle...` | `lifecycle_id, channel_id=0, actor_id=0` |
| Update State | `lupo_channel_state` | `UPDATE lupo_channel_state...` | `channel_id=0, state_data` |
| Escalation | `lupo_channel_escalations` | `INSERT INTO lupo_channel_escalations...` | `channel_id=0, escalation_type, escalated_to_actor_id` |

---

**TL;DR Created**: 20260301  
**Lead Agent**: Windsurf (1002)  
**Version**: 4.0.52  
**Status**: âœ… CHANNEL SYSTEM QUICK REFERENCE (Channel 0 Focus)

