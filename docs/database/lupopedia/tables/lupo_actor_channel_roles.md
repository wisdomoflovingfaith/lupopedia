# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\lupo_actor_channel_roles.md"
  file_hash: "3d6f4b65628211fdc024cf88ccf387a08c4ba50c124dd74750d9c455660ef795"
  file_path_from_root: "docs\database\lupopedia\tables\lupo_actor_channel_roles.md"
  file_hash: "f93afe6d0c7e0bb5205d3cd3b3800f2aea31ea7a93da3ec71a046959c178ff0f"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for lupo_actor_channel_roles.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "lupo_actor_channel_rolesmd"]
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
  file_path_from_root: "docs/database/lupopedia/tables/lupo_actor_channel_roles.md",
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
  traits: ["canonical", "authorization", "channel_governance"],
  tags: ["database", "roles", "permissions", "channels", "actors"]
}
flip.footer: {
  outbound_edges: [
    { to: "docs/toons/lupo_actor_channel_roles.toon.json", type: "schema_reference", weight: 1.0 },
    { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9 },
    { to: "docs/database/lupopedia/tables/lupo_channels.md", type: "references", weight: 0.9 }
  ],
  semantic_tags: ["channel_authorization", "role_hierarchy", "access_control"]
}
---

# 🎟️ Table: lupo_actor_channel_roles

**Purpose:** Manages channel-scoped authorization, roles, and permissions for actors.  
**Type:** Authorization Table  
**Status:** ✅ Production Ready  
**Volume:** Medium-High (one or more roles per active channel participant)

---

## 🎯 **Overview**

The `lupo_actor_channel_roles` table implements the first level of the Lupopedia 3-level permission model (Channel → Department → System). It defines which actors have specific roles (e.g., Captain, Admin, Moderator) within a specific communication channel. This table is critical for controlling agent interaction and human moderation context.

### **Key Responsibilities**
- **Channel Scoping:** Restricts actor authority to specific channels.
- **Role Hierarchy:** Defines standard role keys (Captain, Administrator, Monitor).
- **Handshake Context:** Stores metadata required for agents to "join" a channel securely (e.g., `handshake_metadata_json`).
- **Presence Management:** Tracks when an actor was granted a role and their current status on the channel.

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`actor_channel_role_id`** (BIGINT) - Unique role assignment identifier.

### **Core Identity Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `actor_id` | BIGINT | The actor receiving the role | |
| `channel_id` | BIGINT | The channel the role applies to | |
| `role_key` | VARCHAR(64) | Normalized role name | e.g., 'captain', 'administrator' |

### **Extended Governance**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `handshake_metadata_json` | JSON | Security handshake data | Required for AI agent joins |
| `awareness_snapshot_json`| JSON | Latest state of actor awareness | Per-channel focus data |
| `protocol_completion_status`| VARCHAR(64) | Status of role-boarding | e.g., 'verified' |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Actor:** `actor_id` → `lupo_actors.actor_id`
- **Channel:** `channel_id` → `lupo_channels.channel_id`

### **Resolution Order**
1. **Channel Role** (This table): Highest precedence for channel-specific tasks.
2. **Department Role** (`lupo_actor_departments`): Secondary precedence.
3. **Internal Doctrine**: Fallback for system-level operations.

---

## 🚀 **Usage Patterns**

### **Permission Check**
Verifying if an actor has administrative authority on a channel.

```sql
SELECT role_key 
FROM lupo_actor_channel_roles 
WHERE actor_id = 1006 
  AND channel_id = 42 
  AND role_key IN ('captain', 'administrator')
  AND is_deleted = 0;
```

### **Channel Staff List**
Retrieving all human and agent "staff" for a specific channel.

```sql
SELECT a.name, r.role_key, a.actor_type
FROM lupo_actor_channel_roles r
JOIN lupo_actors a ON r.actor_id = a.actor_id
WHERE r.channel_id = 42 AND r.is_deleted = 0;
```

---

## 🛡️ **Security & Privacy**

### **IP Consistency**
- **Session Linking**: Role checks often cross-reference `lupo_sessions` to ensure the session IP matches the actor's authorized subnet for that role level.
- **Elevation Alerts**: Any attempt to modify a `captain` role is logged as a high-priority security event in `lupo_actor_events` with the initiating IP address.

### **Data Sovereignty**
- Actors can be "de-authorized" from a channel by setting `is_active = 0` or soft-deleting the role record.

---

*This documentation is part of the v4.0.48 Unified Authorization framework.*