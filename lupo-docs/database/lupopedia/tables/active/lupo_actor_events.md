---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_events.md"
  web_path: "[lupo_actor_events](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_events)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Actor activity and lifecycle events; tracks actor state changes, actions, and system interactions"
  tags: ["database", "table", "core"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_actor_events table doc at 4.0.79 (table not present in install SQL - deferred)."
  meta: "php_hits=0 python_hits=0"
  outbound_edges:
    - { to: "(no_table_in_install_sql)", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "(no_php_refs_found)", type: "USED_IN_PHP", weight: 0.0 }
    - { to: "(no_python_refs_found)", type: "USED_IN_PYTHON", weight: 0.0 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "wolfie"
---
# file: lupo_actor_events — session: L-LUPO-ROOT-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_events

# Table: lupo_actor_events

**DEFERRED - Table not present in install SQL**

This table documentation is deferred because the `lupo_actor_events` table does not exist in the current install SQL schema. The table was planned but not implemented in the current database structure.

## Purpose (Planned)

- Track actor activity and lifecycle events
- Record actor state changes and system interactions
- Provide audit trail for actor behavior
- Support actor analytics and monitoring

## Schema (Planned)

| Column | Type | Description |
|--------|------|-------------|
| actor_event_id | bigint NOT NULL | Primary key; **application-supplied** (no AUTO_INCREMENT). |
| actor_id | bigint NOT NULL | Actor this event relates to. |
| event_type | varchar(64) NOT NULL | Type of event (login, logout, state_change, action, etc.). |
| event_data | text DEFAULT NULL | Event-specific data or metadata. |
| channel_id | bigint DEFAULT NULL | Channel context for the event. |
| project_id | bigint DEFAULT 0 | Project context for the event. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when event occurred. |
| created_by_actor_id | bigint DEFAULT NULL | Actor who triggered this event. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT NULL | UTC timestamp when event was deleted. |

## Status

**DEFERRED** - This table is not implemented in the current schema. Consider for future implementation when actor event tracking is required.

## Namespace

- **Domain:** Core
- **Subdomain:** Actor Management
- **Related Tables:** `lupo_actors`, `lupo_actor_channels`, `lupo_actor_capabilities`

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\database\lupopedia\tables\lupo_actor_events.md"
  file_hash: "661b1ce3464a0f73a8ff0458317b749f1f4732677e950e392dbe33d81ee79976"
  file_path_from_root: "lupo-docs\database\lupopedia\tables\lupo_actor_events.md"
  file_hash: "c5da4e27c1a6c953c52a06cb17914944edcf9b7c171f2da7118c574ba3bc21c5"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for lupo_actor_events.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "lupo_actor_eventsmd"]
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
  file_path_from_root: "lupo-docs/database/lupopedia/tables/lupo_actor_events.md",
  system_version: "4.0.48",
  channel_id: 1,
  actor_id: 1003,
  created_ymdhis: 20260227000000,
  updated_ymdhis: 20260227000000,
  message_type: "table_documentation",
  visibility: "public",
  priority: "high",
  mood_rgb: "4B0082",
  artifact_kind: "table",
  traits: ["canonical", "event_logging", "actor_audit"],
  tags: ["database", "events", "logging", "actors", "audit"]
}
flip.footer: {
  outbound_edges: [
    { to: "lupo-database/lupopedia/toon/lupo_actor_events.toon.json", type: "schema_reference", weight: 1.0 },
    { to: "lupo-docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9 },
    { to: "lupo-docs/database/lupopedia/tables/lupo_sessions.md", type: "references", weight: 0.8 }
  ],
  semantic_tags: ["behavioral_logging", "interaction_history", "security_events"]
}
---

# 🔔 Table: lupo_actor_events

**Purpose:** Comprehensive event log for actor-specific actions, interactions, and state changes.  
**Type:** Logging & Audit Table  
**Status:** ✅ Production Ready  
**Volume:** Very High (append-only event stream)

---

## 🎯 **Overview**

The `lupo_actor_events` table serves as the granular transaction log for everything an actor does within Lupopedia. It differs from the `lupo_audit_log` by being actor-centric rather than resource-centric. This allows for detailed behavioral analysis, interaction reconstruction, and high-frequency event tracking (like tab switches or world interactions).

### **Key Responsibilities**
- **Interaction Logging:** Records cross-actor and cross-system events.
- **Environment Tracking:** Stores context like `tab_id`, `world_id`, and `session_id`.
- **Behavioral Analysis:** Provides the raw stream for analyzing actor patterns and efficiency.
- **Security Auditing:** Tracks sensitive events like 'login_success', 'permission_denied', or 'key_rotation'.

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`actor_event_id`** (BIGINT) - Unique event sequence identifier.

### **Core Identity Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `actor_id` | BIGINT | The actor associated with the event | |
| `event_type` | VARCHAR(100) | Semantic event classification | e.g., 'task_completed' |
| `session_id` | VARCHAR(255) | Canonical session ID | Links to current session |

### **Context & Data**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `tab_id` | VARCHAR(255) | NULL | UI context (for human actors) |
| `world_id` | BIGINT | NULL | Virtual world or domain context |
| `event_data` | JSON | NULL | Granular event payload |
| `created_ymdhis` | BIGINT | 0 | YYYYMMDDHHIISS UTC timestamp |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Actor:** `actor_id` → `lupo_actors.actor_id`
- **Session:** `session_id` → `lupo_sessions.session_id`

### **Event Context**
- **World Mapping:** `world_id` → `lupo_worlds.world_id`
- **State Link:** Events often trigger updates in `lupo_session_recovery`.

---

## 🚀 **Usage Patterns**

### **Reconstructing Actor Timeline**
Retrieving the most recent 10 events for a specific agent.

```sql
SELECT event_type, event_data, created_ymdhis
FROM lupo_actor_events
WHERE actor_id = 1006 -- Gemini
ORDER BY created_ymdhis DESC
LIMIT 10;
```

### **Security Event Audit**
Finding all failed login attempts for a specific human actor.

```sql
SELECT session_id, event_data, created_ymdhis
FROM lupo_actor_events
WHERE event_type = 'auth_failure' 
  AND actor_id = 10000
ORDER BY created_ymdhis DESC;
```

---

## 🛡️ **Security & Privacy**

- **IP Address Tracking:** The `event_data` JSON payload MUST include the initiating IP address for all sensitive auth/security events.
- **Anonymization:** Low-importance behavioral events (e.g., 'tab_focused') may be anonymized or purged after 30 days.
- **Immutability:** This table is append-only. Deletions are forbidden by system doctrine (checked via `is_system` flag in `event_type`).

---

*This documentation is part of the v4.0.48 Actor Behavioral Logging framework.*
