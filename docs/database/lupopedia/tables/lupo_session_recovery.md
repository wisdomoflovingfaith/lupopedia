# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_session_recovery.md"
  file_hash: "b072e53f0d89ddb397272535f4b87eddb681a96772ec921750770cb2423ba8c9"
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
  file_path_from_root: "docs\database\lupopedia\tables\lupo_session_recovery.md"
  file_hash: "1f1ef333c666288bade42afffc32893202c059ca4b600c359eacc3cd8bd78ab7"
  file_path_from_root: "docs\database\lupopedia\tables\lupo_session_recovery.md"
  file_hash: "d871026d0416ce8de5ccdbe1718cc643c81ca6ed762bd9eaa04c083185420633"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for lupo_session_recovery.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "lupo_session_recoverymd"]
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
  file_path_from_root: "docs/database/lupopedia/tables/lupo_session_recovery.md",
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
  traits: ["canonical", "session_persistence", "high_availability"],
  tags: ["database", "sessions", "recovery", "actors", "state"]
}
flip.footer: {
  outbound_edges: [
    { to: "docs/toons/lupo_session_recovery.toon.json", type: "schema_reference", weight: 1.0 },
    { to: "docs/database/lupopedia/tables/lupo_sessions.md", type: "references", weight: 0.9 },
    { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.8 }
  ],
  semantic_tags: ["state_snapshots", "disaster_recovery", "ide_persistence"]
}
---

# 🔁 Table: lupo_session_recovery

**Purpose:** Persistent storage of actor session states, allowing for seamless recovery after a restart or crash.  
**Type:** Runtime Persistence Table  
**Status:** ✅ Production Ready  
**Volume:** Medium (one record per active session)

---

## 🎯 **Overview**

The `lupo_session_recovery` table implements "Session Continuity Across Restarts" as requested in Lilith's (2038) review. It allows an IDE agent or a human user to resume exactly where they left off by storing periodic snapshots of their environment, current files, and task context.

### **Key Responsibilities**
- **State Snapping:** Periodically saves the actor's working memory and task state.
- **Resilient Reconnection:** Provides the metadata needed to reconstruct a session after a timeout or system failure.
- **Context Persistence:** Stores active file pointers, partially completed tool outputs, and in-flight variables.
- **Failure Threshold Enforcement:** Tracks recovery attempts to prevent infinite crash loops.

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`recovery_id`** (BIGINT) - Unique recovery point identifier.

### **Core Session Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `actor_id` | BIGINT | The owner of the session | |
| `session_id` | VARCHAR(255) | Canonical session ID | Links to `lupo_sessions` |
| `session_data` | JSON | Basic session metadata | Browser, OS, client info |
| `state_snapshot` | JSON | The core working memory | Files, tasks, variables |

### **Recovery Control**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `context_data` | JSON | NULL | Environmental variables and flags |
| `last_activity_ymdhis` | BIGINT | 0 | Last sign of life from the session |
| `recovery_attempts` | INT | 0 | Count of restoration attempts |
| `max_recovery_attempts` | INT | 3 | Limit before requiring manual reset |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Actor Identity:** `actor_id` → `lupo_actors.actor_id`
- **Main Session Table:** `session_id` → `lupo_sessions.session_id`

### **Filesystem Integration**
- This table acts as the live database buffer for `actors/<id>/state/sessions.json`.
- In high-security modes, `state_snapshot` may be encrypted using the actor's credential keys.

---

## 🚀 **Usage Patterns**

### **Session Restoration**
Retrieving the latest valid state for an actor returning after a disconnect.

```sql
SELECT state_snapshot, context_data
FROM lupo_session_recovery
WHERE actor_id = 10000 
  AND recovery_attempts < max_recovery_attempts
  AND is_deleted = 0
ORDER BY last_activity_ymdhis DESC
LIMIT 1;
```

### **Heartbeat Update**
The system updates the recovery point during every major tool call or state change.

```sql
UPDATE lupo_session_recovery 
SET state_snapshot = :new_state, last_activity_ymdhis = :now
WHERE session_id = :current_sid;
```

---

## 🛡️ **Security & Privacy**

- **Ephemeral Data:** Unlike `lupo_actor_history`, data in this table is often short-lived and should be purged after successful session termination.
- **IP Sensitivity:** The `session_data` JSON includes the IP address of the establishing connection for security verification.
- **Anonymization:** If an actor requests "Incognito Mode", `state_snapshot` is minimized to only the bare essentials for task completion.

---

*This documentation is part of the v4.0.48 Session Persistence framework.*