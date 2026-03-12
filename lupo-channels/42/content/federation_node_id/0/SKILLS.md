---
lupopedia.headers:
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  lupopedia.version: "1.0"
  lupopedia.schema: "channel-content"
  file_path_from_root: "lupo-channels/42/content/federation_node_id/0/SKILLS.md"
  web_path: "http://www.lupopedia.com/channels/42/SKILLS"
  last_modified_utc: "20260310"
  system_version: "4.0.68"
  channel_id: 42
  artifact_type: "channel-content"
  artifact_kind: "skills"
  purpose: "Channel 42 skills — everyone on this channel has these skills"
  mood_rgb: "4169E1"
  traits: ["channel-42", "skills", "v4.0.68"]
  tags: ["channel-42", "skills", "uploads"]

lupopedia.skills:
  - name: "uploads"
    version: "1.0"
    proficiency: "intermediate"
    path: "lupo-skills/uploads/"
    channel_scope: 42
    applies_to: "everyone on channel 42"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-skills/uploads/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/SKILLS_DOCTRINE.md", type: "references", weight: 0.9 }

lupopedia.footer:
  version: "4.0.68"
  last_verified: "20260310"
  last_verified_by: "wolfie"
---
# file: Channel 42 Skills — session: L-LUPO-WOLFIE — delegation: wolfie:lilith:antigravity:root — web_path: http://www.lupopedia.com/channels/42/SKILLS

## Channel 42 skills

Everyone on **Channel 42** has the following skills by channel scope. Use these when working in this channel.

| Skill | Version | Proficiency | Purpose |
|-------|---------|-------------|---------|
| **uploads** | 1.0 | intermediate | Canonical upload directory layout, date partitioning (YYYY/MM), hash-named files, auth_users namespace; entity namespaces: actors, auth_users, channels, agents, system. |

### Uploads skill (canonical reference)

- **Path:** [lupo-skills/uploads/](../../../../lupo-skills/uploads/)
- **Entities:** actors, auth_users, channels, documents, tasks, uploads (optional: actor_faucets, actor_instances).
- **Layout:** `/lupopedia/uploads/<entity>/<YYYY>/<MM>/<sha256>.<ext>`.
- **auth_users:** Authentication layer; own namespace for human uploads (avatars, documents, preferences, etc.). Mapping auth_user → actors in DB, not filesystem.

See [lupo-skills/uploads/README.md](../../../../lupo-skills/uploads/README.md) for full doctrine.
