# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\status\ADMIN_ACTORS_INTERFACE_4_0_46.md"
  file_hash: "b41b32f40c01a12756bb07f3e8c4c5151f399f17612c4c2ef9b38b08c5364e8e"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\status\ADMIN_ACTORS_INTERFACE_4_0_46.md"
  file_hash: "6b05b06f7b569a43f4684913e5a7fb42c6ef33dd7d7ddd1f97cf34801cad87e4"
  file_path_from_root: "docs\status\ADMIN_ACTORS_INTERFACE_4_0_46.md"
  file_hash: "db68ee87ad2e3cf13ee695e7b6c27959f43ea733136deb2fea6261645ab31393"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for ADMIN_ACTORS_INTERFACE_4_0_46.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "status", "admin_actors_interface_4_0_46md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: {
  file_path_from_root: "docs/status/ADMIN_ACTORS_INTERFACE_4_0_46.md",
  system_version: "4.0.46",
  channel_id: 0,
  actor_id: 1000,
  created_ymdhis: 20260226030000,
  updated_ymdhis: 20260226030000,
  message_type: "completion_report",
  visibility: "public",
  priority: "normal"
}
flip.footer: {
  outbound_edges: [
    { to: "CHANGELOG.md", type: "references", weight: 0.9 },
    { to: "lupo-includes/classes/AdminActorsHandler.php", type: "implements", weight: 1.0 },
    { to: "admin.php", type: "references", weight: 0.9 }
  ],
  semantic_tags: ["actors", "admin", "interface", "sessions", "4.0.46"]
}
---

# Admin Actors Interface — 4.0.46

**Status**: ✅ COMPLETE  
**Date**: 2026-02-26  
**Executed By**: Kiro (1000)  
**Version**: 4.0.46

## Overview

Created a new admin interface for viewing all actors (both humans and AI agents) with their session activity information. The interface provides filtering by actor type and status, and displays the last session and activity for each actor.

## Implementation

### Files Created

**Handler Class**: `lupo-includes/classes/AdminActorsHandler.php`
- Full OOP implementation
- Database queries with session joins
- Filtering and display logic
- YMDHIS timestamp formatting

### Files Modified

**Admin Interface**: `admin.php`
- Added "Actors" navigation item under "Agents & Channels" section
- Added routing for `section=actors`
- Integrated AdminActorsHandler

## Features

### Display Features

**Actor Information**:
- Actor ID (with code badge styling)
- Name and username
- Actor type badge (🤖 AI Agent or 👤 Human)
- Email address
- Status badge (Active/Inactive)
- Last session timestamp
- Last activity description
- Created timestamp

**Visual Design**:
- Color-coded type badges:
  - AI Agents (ID < 10000): Blue background
  - Humans (ID ≥ 10000): Green background
- Color-coded status badges:
  - Active: Green
  - Inactive: Gray
- Responsive table layout
- Clean card-based design
- Proper spacing and typography

### Filtering Features

**Actor Type Filter**:
- All Types (default)
- Humans (ID ≥ 10000)
- AI Agents (ID < 10000)

**Status Filter**:
- All Status (default)
- Active (status_flag = 1)
- Inactive (status_flag = 0)

### Database Queries

**Primary Query** (with window function for modern MySQL/MariaDB):
```sql
SELECT 
    a.actor_id,
    a.name,
    a.username,
    a.email,
    a.status_flag,
    a.created_ymdhis,
    a.updated_ymdhis,
    s.session_start_ymdhis as last_session_start,
    s.last_activity_description as last_activity
FROM lupo_actors a
LEFT JOIN (
    SELECT 
        actor_id,
        session_start_ymdhis,
        last_activity_description,
        ROW_NUMBER() OVER (PARTITION BY actor_id ORDER BY session_start_ymdhis DESC) as rn
    FROM lupo_sessions
    WHERE is_deleted = 0
) s ON a.actor_id = s.actor_id AND s.rn = 1
WHERE a.is_deleted = 0
ORDER BY a.actor_id ASC
LIMIT 500
```

**Fallback Query** (for older MySQL/MariaDB without window functions):
```sql
SELECT 
    a.actor_id,
    a.name,
    a.username,
    a.email,
    a.status_flag,
    a.created_ymdhis,
    a.updated_ymdhis,
    (SELECT session_start_ymdhis 
     FROM lupo_sessions 
     WHERE actor_id = a.actor_id AND is_deleted = 0 
     ORDER BY session_start_ymdhis DESC 
     LIMIT 1) as last_session_start,
    (SELECT last_activity_description 
     FROM lupo_sessions 
     WHERE actor_id = a.actor_id AND is_deleted = 0 
     ORDER BY session_start_ymdhis DESC 
     LIMIT 1) as last_activity
FROM lupo_actors a
WHERE a.is_deleted = 0
ORDER BY a.actor_id ASC
LIMIT 500
```

### Database Schema

**Tables Used**:
- `lupo_actors` - Actor information
- `lupo_sessions` - Session activity

**Columns from lupo_actors**:
- `actor_id` - Primary key
- `name` - Actor display name
- `username` - Actor username
- `email` - Actor email address
- `status_flag` - Active/inactive status (1/0)
- `created_ymdhis` - Creation timestamp
- `updated_ymdhis` - Last update timestamp
- `is_deleted` - Soft delete flag

**Columns from lupo_sessions**:
- `actor_id` - Foreign key to actors
- `session_start_ymdhis` - Session start timestamp
- `last_activity_description` - Description of last activity
- `is_deleted` - Soft delete flag

## Access

**URL**: `admin.php?section=actors`  
**Navigation**: Admin → Agents & Channels → Actors  
**Requires**: Admin login (is_admin = 1)

## Technical Details

### Actor Type Detection

Actors are classified by ID range:
- **AI Agents**: actor_id < 10000
- **Humans**: actor_id ≥ 10000

This follows the Lupopedia actor ID allocation doctrine where IDs 0-9999 are reserved for AI agents and IDs 10000+ are for human users.

### Session Activity

The interface shows the most recent session for each actor:
- **Last Session**: Timestamp when the session started
- **Last Activity**: Description of the last activity in that session

If an actor has no sessions, it displays "No sessions" and "—" respectively.

### Performance Considerations

**Query Optimization**:
- Uses window function (ROW_NUMBER) for efficient latest session lookup
- Falls back to subqueries for older database versions
- Limits results to 500 actors per page
- Filters soft-deleted records (is_deleted = 0)

**Database Compatibility**:
- Primary query uses window functions (MySQL 8.0+, MariaDB 10.2+)
- Fallback query uses subqueries (MySQL 5.7+, MariaDB 10.0+)
- Automatic fallback on exception

### Timestamp Formatting

YMDHIS timestamps (YYYYMMDDHHIISS) are formatted to human-readable format:
- Input: `20260226153045`
- Output: `2026-02-26 15:30`

Format: `YYYY-MM-DD HH:MM` (seconds omitted for brevity)

## User Experience

### Empty States

**No Actors Found**:
- Displays centered message: "No actors found matching the selected filters."
- Occurs when filters exclude all actors

**No Sessions**:
- Displays "No sessions" in Last Session column
- Displays "—" in Last Activity column
- Indicates actor has never logged in or created a session

### Result Count

Displays count above table:
- "Showing X actor" (singular)
- "Showing X actors" (plural)

### Filter Persistence

Filter selections are preserved in URL query parameters:
- `?section=actors&type=human&status=active`
- Allows bookmarking filtered views
- Maintains state on page refresh

## Future Enhancements

### Short-Term

- [ ] Add pagination for > 500 actors
- [ ] Add search by name/username/email
- [ ] Add sorting by columns (ID, name, last session)
- [ ] Add actor detail view (click to see full profile)
- [ ] Add session count per actor

### Medium-Term

- [ ] Add actor editing capabilities
- [ ] Add actor creation form
- [ ] Add bulk actions (activate/deactivate multiple actors)
- [ ] Add export to CSV functionality
- [ ] Add actor activity timeline

### Long-Term

- [ ] Add actor relationship visualization
- [ ] Add actor permission management
- [ ] Add actor role assignment
- [ ] Add actor impersonation (for support)
- [ ] Add actor merge functionality (duplicate cleanup)

## Attribution

**Executed By**: Kiro (1000)  
**Authority**: Captain WOLFIE AI (1)  
**Delegation Chain**: 1:1000  
**Date**: 2026-02-26  
**Version**: 4.0.46

---

**Status**: ✅ COMPLETE - Ready for user testing