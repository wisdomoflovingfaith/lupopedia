---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "doctrine"
  system_version: "4.0.80"
  file_path_from_root: "lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cascade"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "coordination_doctrine"
  purpose: "Comprehensive doctrine for channel-based multi-agent coordination replacing status-based artifact model"
  tags: ["doctrine", "channel_coordination", "multi_agent", "architecture", "wolfie_research"]
---

# CHANNEL-BASED COORDINATION DOCTRINE

**Version**: 4.0.80  
**Effective Date**: 2026-03-17  
**Author**: WOLFIE (actor_id 1)  
**Channel**: 42 (Development)  
**Status**: ACTIVE

## Purpose

This doctrine establishes channel-based coordination as the authoritative method for multi-agent work in Lupopedia, replacing the obsolete status-based artifact model. It leverages the existing channel system architecture to provide proper message routing, database integration, and organized artifact management.

## Core Principle

**The database is the source of truth. Channel directories are secondary representations.**

All coordination MUST flow through the established channel system, not through arbitrary status directories.

## Channel Architecture Overview

### Primary Coordination Channel

**Channel 42** (`lupo-channels/42/`) is the designated primary coordination channel for all multi-agent work in Lupopedia.

### Channel Directory Structure

```
lupo-channels/{channel_id}/
├── broadcasts/          # Messages to all channel members
├── threads/            # Threaded conversations
│   ├── {thread_id}/    # Individual thread directories
├── direct/             # Direct messages to specific actors
│   ├── {actor_id}/    # Actor-specific directories
├── rules/              # Channel-specific rules and policies
├── tasks/              # Task tracking and TODO artifacts
└── content/            # Shared content and resources
```

### Directory Purposes

| Directory | Purpose | Example Usage |
|------------|---------|--------------|
| **broadcasts/** | System-wide announcements | Release announcements, policy updates |
| **threads/{id}/** | Focused conversations | Feature development, bug fixes |
| **direct/{id}/** | Private communications | Direct assignments, sensitive coordination |
| **rules/** | Channel governance | Channel-specific policies, procedures |
| **tasks/** | Work tracking | TODO items, progress updates |
| **content/** | Shared resources | Documentation, specifications, designs |

## Message Routing System

### Routing Types

| Type | Destination | File Location | Database Reference |
|------|-------------|---------------|-------------------|
| **Broadcast** | All channel members | `broadcasts/` | `lupo_dialog_messages` with `to_actor_id = NULL` |
| **Direct** | Specific actor | `direct/{actor_id}/` | `lupo_dialog_messages` with `to_actor_id = X` |
| **Thread** | Thread subscribers | `threads/{thread_id}/` | `lupo_dialog_messages` with `dialog_thread_id = X` |

### Message Types

| Type | Description | Use Cases |
|------|-------------|-----------|
| **text** | Standard communication | General coordination |
| **directive** | Authoritative commands | WOLFIE directives, policy changes |
| **status** | Progress updates | Task completion, system status |
| **alert** | Urgent notifications | Security incidents, system failures |
| **review** | Quality assessments | Code reviews, compliance checks |

## Filename Convention

### Standard Format

`YYYYMMDD_HHIISS_{actor}_{type}_{purpose}.md`

### Components

- **YYYYMMDD**: Date (20260317)
- **HHIISS**: Time in 24-hour UTC (143000)
- **actor**: Actor name/slug (wolfie, hermes, anubis)
- **type**: Message category (directive, status, review, alert)
- **purpose**: Brief description (release_announcement, security_scan)

### Examples

- `20260317_143000_wolfie_directive_channel_coordination_update.md`
- `20260317_150000_hermes_status_implementation_complete.md`
- `20260317_160000_anubis_alert_orphan_detected.md`

## Database Integration

### Primary Tables

| Table | Purpose | Key Fields |
|-------|---------|------------|
| **lupo_channels** | Channel definitions | channel_id, channel_name, created_by_actor_id |
| **lupo_dialog_threads** | Thread management | dialog_thread_id, title, channel_id, status |
| **lupo_dialog_messages** | Message storage | dialog_message_id, from_actor_id, to_actor_id, message_type |

### Database ↔ Filesystem Relationship

#### Online Mode
1. Create database record first
2. Generate file from database record
3. Maintain link via metadata

#### Offline Mode
1. Write file with metadata
2. Queue for database synchronization
3. Sync when connection restored

### Metadata Requirements

All channel artifacts MUST include:

```yaml
lupopedia.headers:
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  dialog_message_id: 12345
  dialog_thread_id: 1001
  to_actor_id: null  # for broadcasts
  message_type: "directive"
```

## Coordination Workflows

### Standard Workflow

1. **Context Detection** - WOLFIE identifies work type and requirements
2. **Channel Selection** - Choose appropriate channel (typically 42)
3. **Routing Decision** - Determine broadcast, direct, or thread routing
4. **Artifact Creation** - Create database record and generate file
5. **Distribution** - Route to appropriate recipients
6. **Response Handling** - Process replies and coordinate follow-up

### Multi-Persona Coordination

When work requires multiple personas:

1. **Primary TODO** - WOLFIE creates single TODO item in `tasks/`
2. **Thread Creation** - Create dedicated thread in `threads/{thread_id}/`
3. **Persona Assignment** - Assign personas to specific aspects
4. **Sub-Task Delegation** - Use direct messaging for specific assignments
5. **Progress Tracking** - Update thread with progress
6. **Completion** - WOLFIE validates and closes primary TODO

### Emergency Procedures

For security incidents or critical failures:

1. **Immediate Alert** - Broadcast in `broadcasts/`
2. **Channel Lockdown** - HEIMDALL may restrict channel access
3. **Direct Coordination** - Use `direct/` for critical communications
4. **Incident Thread** - Create dedicated thread in `threads/`
5. **Post-Incident Review** - Document in `content/`

## Agent Responsibilities

### All Agents

- Use channel-based coordination for ALL work
- Follow filename convention exactly
- Include proper metadata in all artifacts
- Check channel membership before acting
- Validate routing before sending messages

### WOLFIE (Orchestrator)

- Create and manage primary TODO items
- Coordinate multi-persona workflows
- Validate all coordination artifacts
- Resolve conflicts and ambiguities
- Maintain channel integrity

### Channel Coordinators

- Monitor channel activity
- Enforce channel rules
- Manage thread organization
- Coordinate message routing
- Maintain directory structure

### Implementation Agents (HERMES, HEPHAESTUS, etc.)

- Use `threads/` for development work
- Report progress via `direct/` to coordinators
- Create status updates in appropriate locations
- Follow established workflows
- Maintain documentation in `content/`

## Migration from Status-Based Coordination

### Obsolete Model

The following practices are OBSOLETE and MUST be replaced:

- ❌ Using `lupo-docs/status/` for coordination
- ❌ Artifact types like `WOLFIE_DIRECTIVE_*`
- ❌ Flat directory structure
- ❌ No message routing capabilities
- ❌ No database integration

### Migration Timeline

| Phase | Deadline | Activities |
|-------|----------|------------|
| **Phase 1** | ✅ COMPLETE | Research and documentation |
| **Phase 2** | ✅ COMPLETE | Doctrine updates |
| **Phase 3** | ✅ COMPLETE | Directory structure preparation |
| **Phase 4** | 4.0.81 | File migration and agent updates |

### Migration Process

1. **File Migration** - Move existing status files to appropriate channel directories
2. **Metadata Updates** - Add channel metadata to migrated files
3. **Database Integration** - Create corresponding database records
4. **Agent Updates** - Update all agent configurations
5. **Validation** - Verify all coordination uses channel system

## Quality Assurance

### Validation Requirements

All channel artifacts MUST be validated for:

- ✅ Proper filename format
- ✅ Complete metadata headers
- ✅ Correct directory placement
- ✅ Database record existence
- ✅ Channel membership verification

### Compliance Checks

Regular compliance checks MUST verify:

- All coordination occurs through channels
- No status-based artifacts remain
- Filename convention is followed
- Database integration is maintained
- Routing is properly configured

## Enforcement

### Violation Types

- **Structural Violations**: Using status-based coordination
- **Format Violations**: Incorrect filename convention
- **Routing Violations**: Improper message routing
- **Metadata Violations**: Missing or incorrect headers

### Corrective Actions

1. **Immediate Freeze** - Stop non-compliant coordination
2. **Artifact Migration** - Move to proper channel structure
3. **Agent Training** - Update agent configurations
4. **System Validation** - Verify compliance
5. **Documentation Update** - Record corrective actions

## Future Enhancements

### Planned Improvements

- **Advanced Routing** - Conditional routing based on content
- **Automation** - Automatic directory organization
- **Integration** - Enhanced database synchronization
- **Monitoring** - Real-time coordination analytics
- **Scalability** - Multi-channel coordination

### Extension Points

The doctrine supports extension through:

- New channel types for specialized work
- Additional message types for specific workflows
- Enhanced metadata for complex coordination
- Custom directory structures for special cases

## Conclusion

Channel-based coordination provides:

- **Architectural Consistency** - Uses existing channel system
- **Enhanced Capabilities** - Proper message routing and database integration
- **Better Organization** - Clear directory structure and naming conventions
- **Improved Scalability** - Leverages existing infrastructure
- **Maintainable Design** - Clear separation of concerns and responsibilities

This doctrine establishes channel-based coordination as the authoritative method for all multi-agent work in Lupopedia, ensuring consistency, scalability, and proper integration with the existing system architecture.

---

**Status**: ✅ ACTIVE  
**Next Review**: 4.0.82 or as needed  
**Maintenance**: Ongoing compliance monitoring and enhancement
