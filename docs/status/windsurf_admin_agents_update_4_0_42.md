# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\status\windsurf_admin_agents_update_4_0_42.md"
  file_hash: "7242f4bcb4d2c5e3adbe0a55a654000f299e040b5aa82a21099805e4713a18eb"
  file_path_from_root: "docs\status\windsurf_admin_agents_update_4_0_42.md"
  file_hash: "f59062bd525f43852522d692c9aaf61a7049e4eba772bcb7f25280a94de549be"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Admin Agents Page Update - Version 4.0.42"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "status", "windsurf_admin_agents_update_4_0_42md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Admin Agents Page Update - Version 4.0.42

## Overview
The Admin Agents section has been updated to provide a comprehensive listing of all actor-based agents, including IDE detection and real-time operational metrics. This replaces the legacy `lupo_agents` listing with a unified view pulling from `lupo_actors` and the `lupo_registry`.

## Backend Changes

### 1. Unified Agent Discovery
Implemented logic to identify agents based on multiple criteria:
- **Actor Type**: `actor_type = 'agent'` in `lupo_actors`.
- **Legacy Registry**: `entity_type = 'agent'` in `lupo_registry`.
- **Sovereign Markers**: `is_agent = 1` in `lupo_actors` (added as part of this update).
- **Metadata Inspection**: Identification via `agent_role: ide` or `is_agent: true` in `metadata_json`.

### 2. IDE Agent Detection
Hardcoded recognition for canonical IDE agents:
- **KIRO** (1001)
- **Windsurf** (1002)
- **Antigravity** (1003)
- Dynamic detection for any agent with `agent_role = 'ide'` in its registry metadata.

### 3. Operational Metrics
Real-time stats are now calculated per-agent:
- **Actions (24h)**: Count of messages in `lupo_dialog_messages` within the last 24 hours.
- **Threads**: Total unique threads participated in.
- **Tickets**: Unified count of tickets opened or commented on (from `lupo_tickets` and `lupo_ticket_messages`).

## Schema & Installer Updates

### 1. lupo_actors Enhancements
Added `is_agent` column to `lupo_actors` to support direct agent flagging.
```sql
ALTER TABLE lupo_actors ADD is_agent TINYINT NOT NULL DEFAULT 0;
```

### 2. Primary Query
The `AdminAgentsHandler` now uses the following unified query:
```sql
SELECT 
    a.actor_id,
    a.name AS agent_name,
    a.actor_type AS agent_type,
    a.is_active,
    a.created_ymdhis,
    (SELECT MAX(created_ymdhis) FROM lupo_dialog_messages WHERE from_actor_id = a.actor_id) as last_active_ymdhis,
    (SELECT COUNT(*) FROM lupo_dialog_messages WHERE from_actor_id = a.actor_id AND created_ymdhis >= '20260223074100') as actions_24h,
    (SELECT COUNT(DISTINCT thread_id) FROM lupo_dialog_messages WHERE from_actor_id = a.actor_id) as thread_count,
    (SELECT COUNT(DISTINCT ticket_id) FROM (
        SELECT ticket_id FROM lupo_tickets WHERE actor_id = a.actor_id
        UNION ALL
        SELECT ticket_id FROM lupo_ticket_messages WHERE actor_id = a.actor_id
    ) AS ticket_activity) as ticket_count,
    r.metadata_json as registry_metadata
FROM lupo_actors a
LEFT JOIN lupo_registry r ON (r.entity_type = 'actor' AND r.entity_index_id = a.actor_id)
WHERE a.actor_type = 'agent'
   OR a.actor_id IN (1001, 1002, 1003)
   OR a.is_agent = 1
   OR r.entity_type = 'agent'
   OR (r.entity_type = 'actor' AND r.metadata_json LIKE '%"agent_role":%')
ORDER BY a.actor_id ASC
LIMIT 1000
```

## UI Improvements
- **IDE Badges**: Distinct visual markers for IDE vs Non-IDE agents.
- **Status Indicators**: Dynamic status (Active, Dormant, Archived) based on last activity timestamp.
- **Unified Action Links**: Direct access to agent-specific threads and tickets.