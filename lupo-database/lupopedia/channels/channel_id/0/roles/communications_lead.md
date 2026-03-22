# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/0/roles/communications_lead.md"
  file_hash: "301157ffb6792567db8f9ab8b464af8af8930e4092656e267aaa424f4ad0db78"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
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

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-channels\0\roles\communications_lead.md"
  file_hash: "825c8fa27586560ec75df4ee14e249520a12aeddb0cd0017d7897f3cdb796ea4"
  file_path_from_root: "lupo-channels\0\roles\communications_lead.md"
  file_hash: "f175642d8e28aa6094e11ee300caa2f4289b6be97351ebf1b7ba4f9c103c13ed"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for communications_lead.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "roles", "communications_leadmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

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
    "lupo-channels/0/broadcasts/",
    "lupo-channels/42/broadcasts/"
  ],
  "implements": "communications_authority_model",
  "depends_on": "broadcast_standards",
  "role_category": "coordination",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->
