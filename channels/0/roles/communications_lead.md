---
role_id: communications_lead
channel_id: 0
authority_level: standard
granted_by: 10000
derived_from:
  - "broadcast_creation"
  - "agent_coordination"
permissions:
  - create_broadcasts
  - announce_changes
  - coordinate_agents
  - document_decisions
  - publish_directives
assigned_to:
  - 10000
  - 1
  - 1004
created_utc: "2026-02-25T09:15:00Z"
updated_utc: "2026-02-25T09:15:00Z"
---

# Role: Communications Lead

## Authority

**Level:** Standard  
**Scope:** Broadcast creation and agent coordination  
**Granted By:** Captain (10000)

## Description

Communications Leads are responsible for creating broadcasts, announcing system changes, coordinating agent activities, and documenting decisions. They ensure all agents are informed and aligned.

## Permissions

### Broadcast Creation
- Create new broadcasts
- Publish to channels
- Set delegation chains
- Assign recipients

### Announcements
- Announce system changes
- Notify agents of updates
- Publish directives
- Coordinate responses

### Documentation
- Document decisions
- Record meeting notes
- Maintain communication logs
- Archive broadcasts

## Assigned Actors

- **10000** - Captain (Human)
- **1** - Captain WOLFIE (AI Agent)
- **1004** - Warp IDE

## Responsibilities

1. **System Announcements**
   - Announce version releases
   - Notify of breaking changes
   - Publish doctrine updates
   - Coordinate migrations

2. **Agent Coordination**
   - Assign tasks to agents
   - Coordinate multi-agent work
   - Resolve conflicts
   - Monitor progress

3. **Documentation**
   - Document all decisions
   - Maintain broadcast archive
   - Track communication history
   - Ensure transparency

4. **Compliance**
   - Follow broadcast standards
   - Validate metadata
   - Use correct filename format
   - Include FLIP footers

## Constraints

- Must follow broadcast standards
- Must validate actor IDs
- Must include delegation chains
- Must document all broadcasts

## Success Criteria

- All broadcasts compliant
- All agents notified
- All decisions documented
- All coordination tracked

## Escalation

Communications Leads report to System Administrators. Coordination failures must be escalated.

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "channels/0/broadcasts/",
    "channels/42/broadcasts/"
  ],
  "implements": "communications_authority_model",
  "depends_on": "broadcast_standards",
  "role_category": "coordination",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->
