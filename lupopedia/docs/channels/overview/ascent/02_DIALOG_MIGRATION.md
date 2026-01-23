---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.50
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
tags:
  categories: ["documentation", "planning"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Big Rock 2: Dialog Migration"
  description: "Migrate dialog system from file-based to database-backed with file fallback"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: not-started
  author: GLOBAL_CURRENT_AUTHORS
---

# Big Rock 2: Dialog Migration

**Status:** Not Started  
**Priority:** High  
**Estimated Duration:** 1 week  
**Target Completion:** Week 2

---

## Objective

Migrate the dialog system from file-based storage (`.md` files) to database-backed storage with file fallback, while preserving all existing dialog content and maintaining backward compatibility.

---

## Why This Matters

### Scalability
- File-based system doesn't scale for production
- Database enables efficient querying
- Better performance for large dialog histories
- Enables real-time dialog features

### Features
- Search and filter dialog entries
- Agent-to-agent communication tracking
- Mood and emotional state analysis
- Timeline visualization
- Cross-reference capabilities

### Reliability
- Database transactions ensure consistency
- Backup and recovery easier
- File fallback provides redundancy
- Version control for dialog content

---

## Current State

### File-Based System
- Dialog entries stored in `.md` files
- YAML inline dialog format
- Manual file management
- Limited querying capability

### Existing Files
- `dialogs/changelog_dialog.md`
- `dialogs/session_2026_01_16_version_4_0_46.md`
- `dialogs/routing_changelog.md`
- `dialogs/humor_context_WOLFIE_LUPOPEDIA.md`
- Additional dialog files in `dialogs/` directory

---

## Target State

### Database-Backed System
- Dialog entries stored in database tables
- File-based fallback for redundancy
- API for dialog operations
- Query and search capabilities

### Hybrid Approach
- Primary storage: Database
- Fallback storage: Files
- Sync mechanism between both
- Graceful degradation if database unavailable

---

## Deliverables

### Database Schema
- [ ] Design dialog tables schema
- [ ] Create migration SQL file
- [ ] Document table relationships
- [ ] Define indexes for performance

### Migration Scripts
- [ ] Parse existing `.md` files
- [ ] Extract dialog entries
- [ ] Insert into database
- [ ] Verify data integrity

### API Layer
- [ ] Create dialog API endpoints
- [ ] Implement CRUD operations
- [ ] Add search and filter
- [ ] Document API usage

### File Fallback
- [ ] Implement fallback mechanism
- [ ] Test database unavailable scenario
- [ ] Sync database to files
- [ ] Document fallback behavior

### Testing
- [ ] Unit tests for migration
- [ ] Integration tests for API
- [ ] Fallback mechanism tests
- [ ] Performance tests

---

## Tasks

### Phase 1: Design (Day 1)
- [ ] Review all existing dialog files
- [ ] Design database schema
- [ ] Define migration strategy
- [ ] Create technical specification

### Phase 2: Implementation (Days 2-3)
- [ ] Create database tables
- [ ] Implement migration scripts
- [ ] Build API layer
- [ ] Implement file fallback

### Phase 3: Migration (Day 4)
- [ ] Run migration on test data
- [ ] Verify data integrity
- [ ] Migrate production dialog files
- [ ] Validate all entries migrated

### Phase 4: Testing (Day 5)
- [ ] Test all API endpoints
- [ ] Test fallback mechanism
- [ ] Performance testing
- [ ] Final validation

---

## Database Schema (Draft)

### Tables

**lupo_dialog_entries:**
- `dialog_entry_id` (BIGINT, PK)
- `dialog_file` (VARCHAR) - Source file
- `speaker` (VARCHAR) - Agent or user
- `target` (VARCHAR) - Recipient
- `mood_RGB` (VARCHAR) - Color code
- `message` (TEXT) - Dialog content
- `timestamp_utc` (BIGINT) - YYYYMMDDHHUUSS
- `system_version` (VARCHAR) - Version at time of entry
- `metadata_json` (TEXT) - Additional metadata

**lupo_dialog_files:**
- `dialog_file_id` (BIGINT, PK)
- `file_path` (VARCHAR) - Relative path
- `file_type` (VARCHAR) - changelog, session, etc.
- `entry_count` (INT) - Number of entries
- `last_sync_utc` (BIGINT) - Last sync timestamp

---

## Migration Strategy

### Step 1: Parse Files
- Read all `.md` files in `dialogs/` directory
- Extract WOLFIE Headers
- Parse dialog entries (YAML inline format)
- Validate data structure

### Step 2: Transform Data
- Convert to database format
- Generate unique IDs
- Normalize timestamps
- Extract metadata

### Step 3: Insert Data
- Insert into database tables
- Maintain referential integrity
- Log migration progress
- Handle errors gracefully

### Step 4: Verify
- Count entries (file vs. database)
- Spot-check random entries
- Verify all speakers/targets
- Validate timestamps

---

## API Endpoints (Draft)

### Create
- `POST /api/dialog/entry` - Create new dialog entry

### Read
- `GET /api/dialog/entries` - List all entries
- `GET /api/dialog/entry/{id}` - Get specific entry
- `GET /api/dialog/search` - Search entries

### Update
- `PUT /api/dialog/entry/{id}` - Update entry

### Delete
- `DELETE /api/dialog/entry/{id}` - Delete entry

### Utility
- `GET /api/dialog/sync` - Sync database to files
- `GET /api/dialog/stats` - Get statistics

---

## File Fallback Mechanism

### Trigger Conditions
- Database connection fails
- Database query timeout
- Database unavailable

### Fallback Behavior
1. Detect database unavailability
2. Switch to file-based mode
3. Read from `.md` files
4. Log fallback event
5. Continue operations

### Recovery
1. Detect database available
2. Sync files to database
3. Switch back to database mode
4. Log recovery event

---

## Success Criteria

Dialog Migration is complete when:
- [ ] All dialog entries migrated to database
- [ ] No data loss during migration
- [ ] API endpoints functional
- [ ] File fallback working
- [ ] Performance acceptable
- [ ] Documentation complete
- [ ] Tests passing

---

## Risks and Mitigation

### Risk: Data Loss
**Mitigation:** Backup all files before migration, verify counts

### Risk: Performance Issues
**Mitigation:** Add indexes, optimize queries, test with large datasets

### Risk: Fallback Complexity
**Mitigation:** Keep fallback simple, test thoroughly

### Risk: Migration Errors
**Mitigation:** Dry-run first, log everything, rollback plan

---

## Related Documentation

- **Dialog Files:** `dialogs/`
- **Dialog System Docs:** `docs/DIALOG_SYSTEM_IMPLEMENTATION_COMPLETE.md`
- **Database Schema:** `database/schema/`
- **API Documentation:** `api/v1/dialog/`

---

*Created: 2026-01-16*  
*Version: 4.0.50*  
*Status: Not started - awaiting Week 2 execution*
