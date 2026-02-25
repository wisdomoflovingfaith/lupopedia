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
    "docs/doctrine/database/",
    "AGENTS.md"
  ],
  "implements": "root_authority_model",
  "depends_on": "actor_registry",
  "role_category": "governance",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->
