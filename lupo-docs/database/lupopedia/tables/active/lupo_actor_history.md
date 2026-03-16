# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_actor_history.md"
  file_hash: "2c38feac11bd470dc88076e14ff2896a744cc374a46312cec10313fde413d5cf"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  namespace: "core"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\database\lupopedia\tables\lupo_actor_history.md"
  file_hash: "f40fd0d0ff8461ccf55df2717ee184bf5c5c71ee6ea570828c1e0244d88503af"
  file_path_from_root: "lupo-docs\database\lupopedia\tables\lupo_actor_history.md"
  file_hash: "d383d14fd6c8654ce0b29514025b186a925528984d69cbf4d63aa53a1eddedf3"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for lupo_actor_history.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "lupo_actor_historymd"]
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
  file_path_from_root: "lupo-docs/database/lupopedia/tables/lupo_actor_history.md",
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
  traits: ["canonical", "history_tracking", "identity_persistence"],
  tags: ["database", "history", "achievements", "actors", "resume"]
}
flip.footer: {
  outbound_edges: [
    { to: "lupo-database/lupopedia/toon/lupo_actor_history.toon.json", type: "schema_reference", weight: 1.0 },
    { to: "lupo-docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9 },
    { to: "lupo-actors/10000/history/resume.json", type: "sync_source", weight: 1.0 }
  ],
  semantic_tags: ["contribution_log", "milestone_tracking", "verified_achievements"]
}
---

# 📜 Table: lupo_actor_history

**Purpose:** Persistent storage of actor achievements, milestones, and professional history.  
**Type:** Identity Supplement Table  
**Status:** ✅ Production Ready  
**Volume:** Medium (grows with actor activity)

---

## 🎯 **Overview**

The `lupo_actor_history` table stores the structured accomplishments of actors. It is the database mirror for the `history/resume.json` file in each actor's directory. This table enables the system to query and display the "best work" and milestones of any actor without parsing the filesystem.

### **Key Responsibilities**
- **Achievement Logging:** Stores significant milestones (e.g., 'v4.0.47 Released').
- **Legacy Persistence:** Captures history from before the actor joined Lupopedia.
- **Portability Support:** Provides the database backup for the file-based `resume.json`.
- **Relationship Discovery:** Links accomplishments to specific channels and timeframes.

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`history_id`** (BIGINT) - Unique achievement sequence number.

### **Core Data Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `actor_id` | BIGINT | Owner of the achievement | Matches `lupo_actors.actor_id` |
| `achievement_id` | VARCHAR(100) | Unique achievement slug | e.g., 'A47-CAPT-001' |
| `title` | VARCHAR(255) | Short description of the work | |
| `description` | TEXT | Detailed explanation of the task | |
| `impact` | TEXT | Result or benefit of the achievement | |

### **Metadata & Tracking**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `date_ymdhis` | BIGINT | 0 | When the event occurred |
| `channel_id` | BIGINT | NULL | Contextual channel |
| `tags` | JSON | NULL | Searchable skill/category tags |
| `metrics` | JSON | NULL | Quantifiable data (e.g., lines changed) |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Actor:** `actor_id` → `lupo_actors.actor_id`
- **Channel:** `channel_id` → `lupo_channels.channel_id`

### **Filesystem Sync**
- Data is synchronized bibirectionally with `lupo-actors/<id>/history/resume.json`.
- **Identity Capsule Integrity**: In an import/export scenario, this table verifies the pedigree of the actor.

---

## 🚀 **Usage Patterns**

### **Retrieving Actor Portfolio**
Fetches the top achievements for a specific actor.

```sql
SELECT title, date_ymdhis, impact, tags
FROM lupo_actor_history
WHERE actor_id = 10000 AND is_deleted = 0
ORDER BY date_ymdhis DESC;
```

### **Skill-Based Discovery**
Finding actors who have mastered specific skills.

```sql
SELECT DISTINCT actor_id, title
FROM lupo_actor_history
WHERE JSON_CONTAINS(tags, '"database"') AND is_deleted = 0;
```

---

## 🛡️ **Security & Privacy**

- **Data Sovereignty:** Actor history belongs to the identity capsule.
- **Verification:** System-generated achievements carry a verified flag in the `metrics` JSON.
- **IP Context:** While achievements are public-facing, the *creation* of these records is logged with the initiating actor ID and IP in `lupo_audit_log`.

---

*This documentation is part of the v4.0.48 Identity Persistence framework.*
