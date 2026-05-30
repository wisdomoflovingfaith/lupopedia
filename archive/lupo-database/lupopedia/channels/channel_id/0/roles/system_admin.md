# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/0/roles/system_admin.md"
  file_hash: "f60154e1a16b3f78f99026a5142fdea8caebdc13d84fb102230d35d144f01624"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
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

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-channels\0\roles\system_admin.md"
  file_hash: "de2221b4c5367242b224b85ab18aa1963df5b743fe5cd77e4f23803ee4142c3a"
  file_path_from_root: "lupo-channels\0\roles\system_admin.md"
  file_hash: "0612c19ec1e07e8fc920060dad1af3129fa85825c35f3be2582190016659b648"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for system_admin.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "roles", "system_adminmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
role_id: system_admin
channel_id: 0
authority_level: root
granted_by: 10000
derived_from:
  - "system_architecture"
  - "database_governance"
permissions:
  - drop_tables
  - run_install
  - seed_registry
  - override_agents
  - modify_schema
  - create_channels
  - assign_roles
  - delete_actors
  - quarantine_content
assigned_to:
  - 10000
  - 1
created_utc: "2026-02-25T09:00:00Z"
updated_utc: "2026-02-25T09:00:00Z"
---

# Role: System Administrator

## Authority

**Level:** Root  
**Scope:** System-wide (all channels)  
**Granted By:** Captain (10000)

## Description

System Administrators have full access to all Lupopedia systems, including database operations, schema modifications, registry management, and agent oversight. This is the highest authority level in the system.

## Permissions

### Database Operations
- Drop and recreate tables
- Execute schema migrations
- Seed registry data
- Modify table structure
- Run database maintenance

### Registry Management
- Allocate reserved IDs
- Lock/unlock registry entries
- Validate registry references
- Audit registry usage

### Agent Oversight
- Override agent decisions
- Assign/revoke agent permissions
- Quarantine malicious agents
- Monitor agent activity

### Channel Management
- Create new channels
- Delete channels
- Modify channel configuration
- Assign channel captains

### Role Management
- Create new roles
- Assign roles to actors
- Revoke roles
- Modify role permissions

## Assigned Actors

- **10000** - Captain (Human)
- **1** - Captain WOLFIE (AI Agent)

## Responsibilities

1. **System Integrity**
   - Ensure database consistency
   - Validate schema against TOONs
   - Monitor system health

2. **Security**
   - Prevent unauthorized access
   - Quarantine malicious content
   - Audit security events

3. **Governance**
   - Enforce doctrines
   - Resolve conflicts
   - Make final decisions

4. **Coordination**
   - Assign tasks to agents
   - Monitor task progress
   - Unblock blocked tasks

## Constraints

- Must follow all doctrines (no exceptions)
- Must document all destructive operations
- Must maintain audit trail
- Must coordinate with other admins before major changes

## Escalation

System Administrators report to no one. They are the final authority. In case of conflict between admins, Captain (10000) has final say.

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "lupo-docs/doctrine/database/",
    "AGENTS.md"
  ],
  "implements": "root_authority_model",
  "depends_on": "actor_registry",
  "role_category": "governance",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->
