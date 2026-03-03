# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_actor_events.md"
  file_hash: "322b8bdd43b9cba7ded1e0f876ff62c803844a4ad533ce0971e8d78d6dd1bcb9"
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
  file_path_from_root: "docs\database\lupopedia\tables\lupo_actor_events.md"
  file_hash: "661b1ce3464a0f73a8ff0458317b749f1f4732677e950e392dbe33d81ee79976"
  file_path_from_root: "docs\database\lupopedia\tables\lupo_actor_events.md"
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
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/lupo_actor_events.md",
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
    { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9 },
    { to: "docs/database/lupopedia/tables/lupo_sessions.md", type: "references", weight: 0.8 }
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