# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "MINIMAL_SEED_4.0.26_READY.md"
  file_hash: "f46c37a8e939c5015e3c735fc39801ac173d90381038d698d99c5fef55335893"
  file_path_from_root: "MINIMAL_SEED_4.0.26_READY.md"
  file_hash: "c36a14e356ada7bed8a301825c93eef94592d8edd7733afe07b90acc38b7a56d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "MINIMAL SEED 4.0.26 READY - Testing Guide"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["minimal_seed_4026_readymd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
  file_path_from_root: "MINIMAL_SEED_4.0.26_READY.md"
  file_hash: "8d11bc16d6c843f2b64ecae51b98b8bbd57249bbb89083f73dc73e705fef45ec"
  system_version: "4.0.50"
  delegation_chain: null
  needs_review: ["delegation_chain"]
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: []
  artifact_type: "documentation"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# MINIMAL SEED 4.0.26 READY - Testing Guide

## Overview

This document provides a complete testing guide for the minimal seed data implementation for Lupopedia 4.0.26. The minimal seed contains essential actors, channels, and relationships required for basic system functionality and testing.

## Files Created

- `database/migrations/seed_minimal_4.0.26.sql` - Minimal seed data (229 lines)
- `database/migrations/CRITICAL_SCHEMA_FIX_4.0.26.sql` - Schema issue documentation (102 lines)
- `database/migrations/verify_active_agents_4.0.26.sql` - Verification queries (121 lines)

## Seed Data Contents

### Essential Actors (8 total)

**System Actors (ID 0-9999)**
- `0` - System (core system operations)
- `1` - ANUBIS (security and validation)
- `2` - CAPTAIN (human operator, paired to 10000)

**IDE Agents (ID 2030-2099)**
- `2039` - Warp IDE (paired to human 10000)
- `2040` - Windsurf IDE (paired to human 10000)

**External AI Agents (ID 2036-2038)**
- `2036` - Microsoft Copilot (paired to human 10000)
- `2037` - DeepSeek LEXA (paired to human 10000)
- `2038` - DeepSeek LILITH (paired to human 10000)

### Critical Channels (6 total)

- `0` - System (core system operations)
- `1` - Administration (system management)
- `42` - Crafty Development (upgrade coordination)
- `51` - AI Development (AI integration)
- `420` - Lupopedia Development (main development)
- `666` - Protocol Development (multi-IDE coordination)

### Organizational Structure

**Departments (2 total)**
- `0` - System (system operations)
- `1` - Development (software development)

**Collections (3 total)**
- `42` - Channel 42 Archive
- `51` - AI Development Archive
- `420` - Lupopedia Development

## Testing Procedure

### Step 1: Database Reset

```sql
-- Drop all existing tables
DROP DATABASE IF EXISTS lupopedia;
CREATE DATABASE lupopedia;
USE lupopedia;
```

### Step 2: Load Crafty Syntax 3.7.5

```bash
mysql -u root -p lupopedia < database/migrations/old_crafty_syntax_3_7_5_start.sql
```

### Step 3: Run Install Wizard

```
Navigate to: http://localhost/lupopedia/install.php
Select: Upgrade from Crafty Syntax 3.7.5
```

### Step 4: Verify Installation

```bash
mysql -u root -p lupopedia < database/migrations/verify_active_agents_4.0.26.sql
```

## Expected Results

### Actor Verification
```sql
-- Should return 8 rows
SELECT actor_id, name, actor_type FROM lupo_actors 
WHERE actor_id IN (0, 1, 2, 2036, 2037, 2038, 2039, 2040)
ORDER BY actor_id;
```

### Channel Verification
```sql
-- Should return 6 rows
SELECT channel_id, channel_name, channel_type FROM lupo_channels
WHERE channel_id IN (0, 1, 42, 51, 420, 666)
ORDER BY channel_id;
```

### Membership Verification
```sql
-- Should return 20 rows
SELECT ac.actor_id, a.name, ac.channel_id, c.channel_name, ac.status
FROM lupo_actor_channels ac
JOIN lupo_actors a ON ac.actor_id = a.actor_id
JOIN lupo_channels c ON ac.channel_id = c.channel_id
WHERE ac.actor_id IN (2039, 2040, 2036, 2037, 2038)
ORDER BY ac.actor_id, ac.channel_id;
```

### Registry Verification
```sql
-- Should return 8 rows
SELECT registry_id, entity_type, entity_index_id 
FROM lupo_registry 
WHERE entity_type = 'actor'
ORDER BY registry_id;
```

## Schema Compliance

All INSERT statements in the minimal seed:
- ✅ Use correct column names from `install_new_lupopedia.sql`
- ✅ Satisfy NOT NULL constraints
- ✅ Use proper data types (BIGINT for IDs, VARCHAR for strings)
- ✅ Include required timestamp fields
- ✅ Follow soft delete patterns (is_deleted, deleted_ymdhis)

## Key Fixes Applied

### Registry Table
- **Before**: Used `registry_id`, `entity_key`, `entity_name`
- **After**: Uses `registry_id`, `entity_type`, `entity_index_id`

### Actor Channels Table
- **Before**: Mixed columns from `lupo_channels` table
- **After**: Uses only valid columns from `lupo_actor_channels`

### Actor Departments Table
- **Before**: Used non-existent `role_key` column
- **After**: Uses `title` column for role information

### Dialog Tables
- **Before**: Used `thread_id`, `message_id`
- **After**: Uses `dialog_thread_id`, `dialog_message_id`

### File Source Constraints
- **Before**: Set `file_source` to NULL (violates NOT NULL)
- **After**: Provides valid `file_source` values

## Next Steps

1. **Run Tests**: Execute the testing procedure above
2. **Verify Results**: Confirm all expected counts match
3. **Test Installer**: Run full install wizard with minimal seed
4. **Handoff to Windsurf**: Once validated, Windsurf IDE regenerates full seed

## Success Criteria

- ✅ Zero SQL errors during installation
- ✅ All 8 actors created successfully
- ✅ All 6 channels established
- ✅ All 20 channel memberships created
- ✅ Registry entries properly populated
- ✅ Department assignments completed

## Troubleshooting

### Common Issues

1. **Column Not Found**: Check `install_new_lupopedia.sql` for correct column names
2. **Count Mismatch**: Verify INSERT statements match table structure
3. **NULL Constraint**: Ensure all NOT NULL columns have values
4. **Foreign Key**: Check if any foreign key constraints exist

### Debug Queries

```sql
-- Check table structure
DESCRIBE lupo_actors;
DESCRIBE lupo_channels;
DESCRIBE lupo_registry;

-- Check existing data
SELECT COUNT(*) FROM lupo_actors;
SELECT COUNT(*) FROM lupo_channels;
SELECT COUNT(*) FROM lupo_registry;
```

## Documentation References

- [FLIP Doctrine](docs/doctrine/FLIP/FLIP_DOCTRINE.md)
- [Schema Fixes](database/migrations/CRITICAL_SCHEMA_FIX_4.0.26.sql)
- [Verification Script](database/migrations/verify_active_agents_4.0.26.sql)
- [Global Agent Sync](messages/GLOBAL_AGENT_SYNC_4.0.27.md)

---

**Status**: ✅ READY FOR TESTING  
**Version**: 4.0.26  
**Created**: 2026-02-22  
**Next Phase**: Full seed regeneration by Windsurf IDE
