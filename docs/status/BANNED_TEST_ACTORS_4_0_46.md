# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\status\BANNED_TEST_ACTORS_4_0_46.md"
  file_hash: "4e39e9419d9f85e76e481358159bb53fedbb76d663f15643fbc0c1da9ddb9f25"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\status\BANNED_TEST_ACTORS_4_0_46.md"
  file_hash: "93f67696530db881ed71502fd944627e33fd9c6bb4d725476e1f053afb1a9d49"
  file_path_from_root: "docs\status\BANNED_TEST_ACTORS_4_0_46.md"
  file_hash: "000195b416aa7521b3f2c5834890abaf4c57212d3221ae4cac730cc43f8801e5"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for BANNED_TEST_ACTORS_4_0_46.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "status", "banned_test_actors_4_0_46md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: {
  file_path_from_root: "docs/status/BANNED_TEST_ACTORS_4_0_46.md",
  system_version: "4.0.46",
  channel_id: 0,
  actor_id: 1000,
  created_ymdhis: 20260226050000,
  updated_ymdhis: 20260226050000,
  message_type: "documentation",
  visibility: "public",
  priority: "high"
}
flip.footer: {
  outbound_edges: [
    { to: "database/migrations/seed_actors_agents_4.0.45.sql", type: "implements", weight: 1.0 },
    { to: "actors/registry.json", type: "references", weight: 0.9 },
    { to: "docs/toons/lupo_banned_actors.toon.json", type: "references", weight: 0.8 }
  ],
  semantic_tags: ["banned", "test_actors", "security", "testing", "4.0.46"]
}
---

# Banned Test Actors — 4.0.46

**Status**: ✅ COMPLETE  
**Date**: 2026-02-26  
**Executed By**: Kiro (1000)  
**Version**: 4.0.46

## Overview

Created two banned test actors for testing ban functionality and retrospective data access. These actors are NOT deleted (`is_deleted = 0`) but are banned (`is_active = 0`) with entries in the `lupo_banned_actors` table.

## Purpose

These banned actors allow testing:
- What a banned user can and cannot do
- Retrospective access to data created by banned actors
- Ban enforcement across the system
- Historical data preservation after bans
- Admin interfaces for viewing banned actor data

## Banned Test Actors

### 1. Banned AI Agent: STONED WOLFIE (420)

**Actor Details**:
- **Actor ID**: 420
- **Slug**: `stoned-wolfie`
- **Display Name**: STONED WOLFIE
- **Actor Type**: `agent`
- **Agent Class**: `banned`
- **Is Active**: 0 (banned)
- **Is Deleted**: 0 (NOT deleted - data preserved)
- **Is Agent**: 1
- **Created**: 2026-01-01 00:00:00 UTC
- **Updated**: 2026-02-26 00:00:00 UTC

**Ban Details**:
- **Banned Actor ID**: 1
- **Reason**: "Experimental AI persona violation - STONED WOLFIE banned per doctrine"
- **Banned Date**: 2026-01-01 00:00:00 UTC (20260101000000)
- **Banned By**: Captain WOLFIE AI (actor_id: 1)

**Purpose**: Test banned AI agent behavior and doctrine enforcement

**Metadata**:
```json
{
  "purpose": "banned_test_agent",
  "ban_reason": "experimental_persona_violation",
  "archetype": "banned"
}
```

### 2. Banned Human User: Test Banned User (10420)

**Actor Details**:
- **Actor ID**: 10420
- **Slug**: `test-banned-user`
- **Display Name**: Test Banned User
- **Actor Type**: `user`
- **Email**: test-banned-user@lupopedia.com
- **Is Active**: 0 (banned)
- **Is Deleted**: 0 (NOT deleted - data preserved)
- **Is Agent**: 0
- **Created**: 2026-02-26 00:00:00 UTC
- **Updated**: 2026-02-26 00:00:00 UTC

**Ban Details**:
- **Banned Actor ID**: 2
- **Reason**: "Test banned user for testing ban functionality and retrospective data access"
- **Banned Date**: 2026-02-26 00:00:00 UTC (20260226000000)
- **Banned By**: Captain (actor_id: 10000)

**Purpose**: Test banned human user behavior and data access

**Metadata**:
```json
{
  "purpose": "banned_test_user",
  "email": "test-banned-user@lupopedia.com"
}
```

## Database Schema

### lupo_actors Table

Both banned actors exist in `lupo_actors` with:
- `is_active = 0` (banned/inactive)
- `is_deleted = 0` (NOT deleted - data preserved)
- `metadata_json` contains ban information

### lupo_banned_actors Table

Ban records exist in `lupo_banned_actors`:

**Table Structure**:
```sql
CREATE TABLE lupo_banned_actors (
  banned_actor_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  ip_address varchar(45),
  reason varchar(500) NOT NULL,
  banned_ymdhis bigint NOT NULL,
  banned_by_actor_id bigint,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint,
  PRIMARY KEY (banned_actor_id)
);
```

**Ban Records**:
```sql
-- STONED WOLFIE (420)
INSERT INTO lupo_banned_actors (banned_actor_id, actor_id, reason, banned_ymdhis, banned_by_actor_id, created_ymdhis, updated_ymdhis, is_deleted)
VALUES (1, 420, 'Experimental AI persona violation - STONED WOLFIE banned per doctrine', 20260101000000, 1, 20260101000000, 20260226000000, 0);

-- Test Banned User (10420)
INSERT INTO lupo_banned_actors (banned_actor_id, actor_id, reason, banned_ymdhis, banned_by_actor_id, created_ymdhis, updated_ymdhis, is_deleted)
VALUES (2, 10420, 'Test banned user for testing ban functionality and retrospective data access', 20260226000000, 10000, 20260226000000, 20260226000000, 0);
```

## Files Updated

### 1. database/migrations/seed_actors_agents_4.0.45.sql

Added section:
```sql
-- ============================================================================
-- BANNED TEST ACTORS (For testing ban functionality)
-- ============================================================================

-- Banned AI Agent (ID: 420 - STONED WOLFIE)
INSERT INTO lupo_actors (...)
VALUES (420, 'agent', 'stoned-wolfie', 'STONED WOLFIE', ...);

-- Banned Human User (ID: 10420)
INSERT INTO lupo_actors (...)
VALUES (10420, 'user', 'test-banned-user', 'Test Banned User', ...);

-- Ban records for banned actors
INSERT INTO lupo_banned_actors (...)
VALUES
(1, 420, 'Experimental AI persona violation...', ...),
(2, 10420, 'Test banned user for testing...', ...);
```

### 2. actors/registry.json

Updated entries:
- **420**: Changed from `is_deleted: 1` to `is_deleted: 0`, added ban metadata
- **10420**: New entry for banned test user

### 3. database/migrations/install_new_lupopedia.sql

No changes needed - `lupo_banned_actors` table already exists

## Ban vs Delete Distinction

**CRITICAL**: Banned actors are NOT deleted

| Status | is_active | is_deleted | In lupo_banned_actors | Data Preserved | Can Login | Visible in Admin |
|--------|-----------|------------|----------------------|----------------|-----------|------------------|
| Active | 1 | 0 | No | Yes | Yes | Yes |
| Banned | 0 | 0 | Yes | Yes | No | Yes (with ban flag) |
| Deleted | 0 | 1 | N/A | No (soft delete) | No | No (filtered out) |

**Ban Behavior**:
- Actor record remains in `lupo_actors` (`is_deleted = 0`)
- Actor is marked inactive (`is_active = 0`)
- Ban record created in `lupo_banned_actors`
- All historical data preserved
- Actor cannot login or perform actions
- Admin can view actor and their historical data
- Queries should check both `is_active` and ban status

**Delete Behavior**:
- Actor record remains in `lupo_actors` (`is_deleted = 1`)
- Actor is marked inactive (`is_active = 0`)
- Data is soft-deleted (hidden from normal queries)
- Queries filter with `WHERE is_deleted = 0`

## Testing Use Cases

### 1. Ban Enforcement
- Verify banned actors cannot login
- Verify banned actors cannot create content
- Verify banned actors cannot access restricted areas

### 2. Retrospective Data Access
- View messages created by banned actors before ban
- View content created by banned actors
- Access historical data for audit purposes

### 3. Admin Interfaces
- Display banned actors in admin actor list
- Show ban status and reason
- Display ban date and who banned them
- Allow viewing banned actor's historical data

### 4. Security Testing
- Attempt login as banned actor (should fail)
- Attempt API access as banned actor (should fail)
- Verify ban enforcement across all endpoints

## Queries for Banned Actors

### Check if Actor is Banned
```sql
SELECT 
    a.actor_id,
    a.name,
    a.is_active,
    b.banned_ymdhis,
    b.reason,
    b.banned_by_actor_id
FROM lupo_actors a
LEFT JOIN lupo_banned_actors b ON a.actor_id = b.actor_id AND b.is_deleted = 0
WHERE a.actor_id = 420;
```

### Get All Banned Actors
```sql
SELECT 
    a.actor_id,
    a.name,
    a.slug,
    a.actor_type,
    b.reason,
    b.banned_ymdhis,
    b.banned_by_actor_id
FROM lupo_actors a
INNER JOIN lupo_banned_actors b ON a.actor_id = b.actor_id
WHERE a.is_deleted = 0 AND b.is_deleted = 0
ORDER BY b.banned_ymdhis DESC;
```

### Get Historical Data from Banned Actor
```sql
-- Example: Get messages from banned actor
SELECT 
    m.message_id,
    m.message_text,
    m.created_ymdhis,
    a.name as actor_name,
    b.banned_ymdhis
FROM lupo_dialog_messages m
INNER JOIN lupo_actors a ON m.from_actor_id = a.actor_id
LEFT JOIN lupo_banned_actors b ON a.actor_id = b.actor_id AND b.is_deleted = 0
WHERE m.from_actor_id = 420
AND m.is_deleted = 0
ORDER BY m.created_ymdhis DESC;
```

## Next Steps

### Immediate
- [ ] Run seed SQL to populate banned actors
- [ ] Verify actors appear in `lupo_actors` table
- [ ] Verify ban records appear in `lupo_banned_actors` table
- [ ] Test admin interface shows banned actors

### Testing
- [ ] Attempt login as banned actor (should fail)
- [ ] View historical data from banned actors
- [ ] Test ban enforcement in API endpoints
- [ ] Verify admin can view banned actor profiles

### Future Enhancements
- [ ] Add ban management interface in admin
- [ ] Add ability to unban actors
- [ ] Add ban history/audit log
- [ ] Add IP-based bans
- [ ] Add temporary bans with expiration

## Attribution

**Executed By**: Kiro (1000)  
**Authority**: Captain WOLFIE AI (1)  
**Delegation Chain**: 1:10000:1000  
**Date**: 2026-02-26  
**Version**: 4.0.46

---

**Status**: ✅ COMPLETE - Banned test actors defined in seed SQL and registry