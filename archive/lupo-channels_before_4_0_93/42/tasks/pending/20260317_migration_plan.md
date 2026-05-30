---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "task"
  system_version: "4.0.80"
  file_path_from_root: "lupo-channels/42/tasks/pending/20260317_migration_plan.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/tasks/pending/20260317_migration_plan.md"
  questions_toon: null
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cascade"
  delegation_chain: "wolfie:root"
  artifact_type: "task"
  artifact_kind: "migration_plan"
  purpose: "Migration plan for moving from status-based to channel-based coordination"
  tags: ["migration", "channel_coordination", "status_files", "4.0.81"]
---

# Migration Plan: Status-Based to Channel-Based Coordination

**Task ID**: 20260317_MIGRATION_001  
**Assigned To**: HERMES (actor_id 102)  
**Verification By**: LILITH (actor_id 2)  
**Target Completion**: 4.0.81  
**Status**: PENDING

## Executive Summary

Migrate all coordination artifacts from `lupo-docs/status/` to appropriate channel directories in `lupo-channels/42/`. This migration eliminates architectural redundancy and implements proper channel-based coordination.

## Migration Scope

### Source Directory
- `lupo-docs/status/` - All existing status artifacts

### Target Directories
- `lupo-channels/42/broadcasts/` - System-wide announcements
- `lupo-channels/42/threads/{thread_id}/` - Threaded conversations
- `lupo-channels/42/direct/{actor_id}/` - Direct messages
- `lupo-channels/42/tasks/` - Task tracking artifacts
- `lupo-channels/42/content/` - Shared content and resources

## Migration Categories

### 1. WOLFIE Directives
**Source**: `lupo-docs/status/WOLFIE_DIRECTIVE_*.md`  
**Target**: `lupo-channels/42/broadcasts/`  
**Rationale**: Directives are system-wide announcements

### 2. Enforcement Artifacts
**Source**: `lupo-docs/status/LEXA_ENFORCEMENT_*.md`  
**Target**: `lupo-channels/42/broadcasts/`  
**Rationale**: Enforcement affects all channel members

### 3. Custody Reports
**Source**: `lupo-docs/status/ANUBIS_CUSTODY_*.md`  
**Target**: `lupo-channels/42/threads/{thread_id}/`  
**Rationale**: Custody operations are focused conversations

### 4. Security Artifacts
**Source**: `lupo-docs/status/HEIMDALL_SECURITY_*.md`  
**Target**: `lupo-channels/42/broadcasts/`  
**Rationale**: Security alerts affect all members

### 5. Review Artifacts
**Source**: `lupo-docs/status/SESHAT_REVIEW_*.md`  
**Target**: `lupo-channels/42/threads/{thread_id}/`  
**Rationale**: Reviews are focused conversations

### 6. Strategy Artifacts
**Source**: `lupo-docs/status/ATHENA_STRATEGY_*.md`  
**Target**: `lupo-channels/42/content/`  
**Rationale**: Strategy is shared content

### 7. Balance Artifacts
**Source**: `lupo-docs/status/MAAT_BALANCE_*.md`  
**Target**: `lupo-channels/42/threads/{thread_id}/`  
**Rationale**: Balance discussions are conversations

### 8. Compliance Artifacts
**Source**: `lupo-docs/status/THEMIS_COMPLIANCE_*.md`  
**Target**: `lupo-channels/42/content/`  
**Rationale**: Compliance is shared reference

### 9. Analysis Artifacts
**Source**: `lupo-docs/status/THOTH_ANALYSIS_*.md`  
**Target**: `lupo-channels/42/content/`  
**Rationale**: Analysis is shared content

### 10. Transition Artifacts
**Source**: `lupo-docs/status/JANUS_TRANSITION_*.md`  
**Target**: `lupo-channels/42/threads/{thread_id}/`  
**Rationale**: Transitions are focused conversations

### 11. Dialogue Artifacts
**Source**: `lupo-docs/status/ROSE_DIALOGUE_*.md`  
**Target**: `lupo-channels/42/threads/{thread_id}/`  
**Rationale**: Dialogues are conversations

### 12. Other Status Files
**Source**: All other `lupo-docs/status/*.md` files  
**Target**: Determine by content analysis  
**Rationale**: Categorize based on purpose and audience

## Migration Process

### Step 1: Inventory (HERMES)
1. List all files in `lupo-docs/status/`
2. Categorize each file by type
3. Create migration mapping table
4. Assign thread IDs for conversation artifacts

### Step 2: File Migration (HERMES)
1. For each file:
   - Update filename to match channel convention
   - Add/update `lupopedia.headers` with channel metadata
   - Move to appropriate target directory
   - Create corresponding database record (if possible)

### Step 3: Validation (LILITH)
1. Verify all files migrated successfully
2. Check filename convention compliance
3. Validate metadata completeness
4. Confirm no files left in source directory

### Step 4: Cleanup (WOLFIE)
1. Remove empty `lupo-docs/status/` directory
2. Update any remaining references
3. Archive migration artifacts
4. Close migration task

## Filename Conversion

### Current Format
`WOLFIE_DIRECTIVE_MULTI_AGENT_REWRITE_4_0_80.md`

### Target Format
`20260317_143000_wolfie_directive_multi_agent_rewrite.md`

### Conversion Rules
1. Extract date from file content or use current date
2. Use actor name from filename prefix
3. Determine message type from filename pattern
4. Create purpose from remaining filename content
5. Ensure hyphenated format for purpose

## Metadata Requirements

All migrated files MUST include:

```yaml
lupopedia.headers:
  channel_id: 42
  actor_id: [actor_number]
  actor_name: [actor_name]
  dialog_message_id: [generated_or_null]
  dialog_thread_id: [thread_id_or_null]
  to_actor_id: [null_for_broadcasts]
  message_type: [broadcast/direct/thread]
  purpose: [migration_from_status_based]
```

## Thread ID Assignment

For conversation artifacts, assign thread IDs:

- **Thread 1001**: Research & Development
- **Thread 1002**: Security & Compliance
- **Thread 1003**: Architecture & Strategy
- **Thread 1004**: Content & Quality
- **Thread 1005**: Support & Human Factors

## Database Integration

Where possible, create corresponding database records:

```sql
INSERT INTO lupo_dialog_messages (
    dialog_message_id, dialog_thread_id, channel_id, from_actor_id,
    message_text, message_type, metadata_json, created_ymdhis
) VALUES (
    [message_id], [thread_id], 42, [actor_id],
    [file_content], [message_type], [metadata_json], [timestamp]
);
```

## Risk Mitigation

### Backup Strategy
1. Create full backup of `lupo-docs/status/` before migration
2. Maintain migration log with all actions
3. Preserve original file timestamps

### Rollback Plan
1. If migration fails, restore from backup
2. Document failure reasons
3. Update migration plan and retry

### Quality Assurance
1. Spot-check migrated files for content integrity
2. Verify metadata completeness
3. Test database record creation

## Success Criteria

### Migration Completeness
- ✅ All files moved from `lupo-docs/status/`
- ✅ No remaining status file references
- ✅ All files follow channel naming convention
- ✅ All files have proper metadata

### System Integration
- ✅ Database records created where possible
- ✅ Channel directory structure complete
- ✅ README files in all directories
- ✅ Example artifacts created

### Documentation
- ✅ Migration log complete
- ✅ Mapping table documented
- ✅ Lessons learned recorded

## Timeline

| Phase | Duration | Owner | Status |
|-------|----------|-------|--------|
| **Inventory** | 4 hours | HERMES | PENDING |
| **Migration** | 8 hours | HERMES | PENDING |
| **Validation** | 4 hours | LILITH | PENDING |
| **Cleanup** | 2 hours | WOLFIE | PENDING |
| **Total** | 18 hours | - | PENDING |

## Next Steps

1. **HERMES**: Begin inventory of status files
2. **HERMES**: Execute migration according to plan
3. **LILITH**: Validate migration completeness
4. **WOLFIE**: Approve and close migration

---

**Migration Status**: 🔄 PENDING  
**Last Updated**: 2026-03-17  
**Next Review**: Upon inventory completion
