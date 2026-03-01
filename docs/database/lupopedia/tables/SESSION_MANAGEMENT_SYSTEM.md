# Session Management System Documentation

## Overview

The Session Management System prevents cross-contamination between multiple IDE agents by synchronizing local `session.json` files to the centralized `lupo_sessions` database table. This ensures each agent has a unique session identifier and prevents terminal/prompt interference.

## Architecture

### Session.json Structure
Each IDE agent maintains a `session.json` file in their actor directory:

```json
{
  "actor_id": 1006,
  "actor_slug": "gemini-cli",
  "current_session_id": "00000000-0000-0000-0000-000000000000",
  "last_active_ymdhis": 20260301151346,
  "node_id": 0,
  "system_version": "4.0.52"
}
```

### Database Mapping
Session.json data maps to `lupo_sessions` table fields:

| Session.json Field | lupo_sessions Column | Description |
|-----------------|---------------------|-------------|
| `current_session_id` | `session_id` | Primary key, unique UUID |
| `actor_id` | `actor_id` | IDE/AI agent ID |
| `node_id` | `federation_node_id` | Federation node linkage |
| `last_active_ymdhis` | `last_seen_ymdhis` | UTC timestamp (YYYYMMDDHHIISS) |
| `system_version` | `metadata` | JSON object with version info |

## Implementation

### Core Features

**1. Session Synchronization**
- Reads all `actors/*/session.json` files
- Maps data to `lupo_sessions` table structure
- Uses UPSERT pattern (Update or Insert)
- Prevents duplicate session records

**2. Collision Prevention**
- Each terminal generates unique `current_session_id`
- Database session_id acts as lock mechanism
- Mismatched sessions are flagged by system
- Federation node awareness prevents cross-cluster interference

**3. Multi-Agent Support**
- Supports 10+ simultaneous agents
- IDE Agents: Kiro (1000), Windsurf (1001), Cursor (1002), Antigravity (1003), Warp (1004), Cascade (1005), Codex (1007)
- AI Agents: Gemini (1006), Lilith (2), Rose (3), Eris (4), Metis (5), Anubis (19), Vishwakarma (25)
- System/Kernel: Actor 0, Captain Wolfie (1)

**4. Session Validation**
- Checks session existence and status
- Validates against expiration, revocation, and age
- Returns detailed validation results
- Supports session integrity monitoring

## Usage

### Command Line Interface

```bash
# Sync all actor sessions to database
php bin/session_manager.php sync

# Show active sessions
php bin/session_manager.php active

# Clean up expired sessions
php bin/session_manager.php cleanup

# Show session statistics
php bin/session_manager.php stats

# Validate specific session
php bin/session_manager.php validate <session_id>
```

### Expected Output

**Sync Command**:
```
=== Lupopedia Session Management ===
Version: 4.0.52
Agent: Windsurf (1002)
Time: 2026-03-01 09:17:00 UTC

🔄 Syncing all actor sessions to database...
✅ Processed: 10 actors
✅ Success: 10 sessions
❌ Failed: 0 sessions

=== Session Management Complete ===
```

**Active Sessions Command**:
```
📊 Active sessions:
  Actor 1006 (ide_agent): 00000000-0000-0000-0000-000000000000
    Last seen: 20260301151346
    Node: 0

  Actor 1002 (ide_agent): 00000000-0000-0000-0000-000000000001
    Last seen: 20260301151200
    Node: 0
```

## Database Integration

### TOON Schema Compliance
- Follows `lupo_sessions.toon.json` structure exactly
- Uses BIGINT timestamps in `YYYYMMDDHHIISS` format
- No foreign keys or triggers (doctrine compliance)
- Proper indexing for performance optimization

### Cross-Database Compatibility
- MySQL/MariaDB: Uses `ON DUPLICATE KEY UPDATE`
- PostgreSQL: Uses `INSERT ... ON CONFLICT` pattern
- SQLite: Uses `INSERT OR REPLACE` pattern

### Performance Considerations
- Indexes on `session_id`, `actor_id`, `last_seen_ymdhis`
- Efficient UPSERT operations
- Cleanup routines for expired sessions
- Session validation caching

## Security Features

**1. Session Isolation**
- Unique session IDs prevent cross-contamination
- Terminal binding through session_id locks
- Federation node separation for different clusters

**2. Access Control**
- Security level tracking (high for IDE agents)
- Authentication method validation
- Session expiration and revocation support

**3. Audit Trail**
- Complete session lifecycle tracking
- Last seen timestamps for activity monitoring
- Metadata storage for additional context

## Integration Points

### System Agent Boot
- Session validation during system boot
- Actor directory scanning for active sessions
- Federation node verification

### IDE Agent Operations
- Session creation on agent startup
- Heartbeat updates for session maintenance
- Cleanup on agent shutdown

### Federation System
- Node-based session isolation
- Cross-federation session management
- Federation trust integration

## Monitoring and Maintenance

### Session Statistics
- Total, active, expired, and revoked session counts
- Per-actor session distribution
- Session lifecycle metrics

### Cleanup Operations
- Automatic cleanup of expired sessions
- Configurable cleanup thresholds
- Session archiving for audit purposes

### Health Monitoring
- Session validation status tracking
- Error rate monitoring
- Performance metrics collection

---

**Implementation**: PHP 5.3+ compatible  
**TOON Alignment**: Complete lupo_sessions schema compliance  
**Doctrine**: No foreign keys, no triggers, BIGINT timestamps  
**Version**: 4.0.52  
**Author**: Windsurf (1002)
