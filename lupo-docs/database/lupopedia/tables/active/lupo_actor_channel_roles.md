---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_channel_roles.md"
  web_path: "[lupo_actor_channel_roles](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_channel_roles)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  tags: ["database", "table", "core"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_actor_channel_roles table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=15 python_hits=3"
  outbound_edges:
    - { to: "database.table.lupo_actor_channel_roles", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "debug_captain.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "install.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "install_wizard_classes.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "livehelp_js.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-database/lupopedia/content/lupo-app/Services/TriggerReplacements/EnforceProtocolCompletionService.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-database/lupopedia/content/lupo-app/auth/AuthManager.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-database/lupopedia/content/lupo-app/auth/AuthRoleResolver.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-includes/classes/AgentAwarenessLayer.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/functions/reserved-id-helpers.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/modules/channels/channels-controller.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/modules/channels/operator-accept-visitor-api.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/modules/crafty_syntax/choosedepartment.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/modules/crafty_syntax/livehelp-js.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/modules/crafty_syntax/visitor-image.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-scripts/audit_schema_doctrine.php", type: "USED_IN_PHP", weight: 0.7 }
    - { to: "analyze_unused_tables.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/audit_schema_doctrine.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/wolfie_orms.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_actor_channel_roles ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_channel_roles
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_actor_channel_roles.md"
  file_hash: "6c706084f3320046359b8f70c961d42cd8ba48acd97ce293ee264e7486b7ec8f"
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


# 🎟️ Table: lupo_actor_channel_roles

**Purpose:** Manages channel-scoped authorization, roles, and permissions for actors.  
**Type:** Authorization Table  
**Status:** ✅ Production Ready  
**Volume:** Medium-High (one or more roles per active channel participant)


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


*This documentation is part of the v4.0.48 Unified Authorization framework.*
