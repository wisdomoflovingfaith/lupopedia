---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/0/threads/VERSION_4.0.61/dual_identity.md"
  last_modified_utc: "20260306"
  system_version: "4.0.61"
  channel_id: 0
  actor_name: "cursor"
  purpose: "Dual-identity runtime context implementation for v4.0.61"
  traits: ["documentation", "feature", "v4.0.61", "dual_identity"]
  tags: ["dual_identity", "context", "implementation"]
  lupo_agent: "cursor"
---

# Dual-Identity Runtime Context

## Overview

Version 4.0.61 uses a three-layer identity model:

| Layer | Description | Source |
|-------|-------------|--------|
| **Effective Actor** | The actor that owns the current session | `lupo_sessions.actor_name` or `session.md` |
| **Human Identity** | The human behind the actor (if any) | Derived from `lupo_actors.paired_actor_id` |
| **Active Agent** | The active agent persona | actor when type is agent/ide_agent; else none |

## Implementation

### ContextResolver (lupo-includes/classes/ContextResolver.php)

- **Resolution order:** session.md (first) → enrich from lupo_sessions → enrich from registry → defaults.
- **Returns:** actor_name, actor_id, human_actor_name, human_actor_id, agent_name, actor_type, paired_actor_id, paired_actor_name, session_mode, department_id, channel_id, thread_id, federation_node_id, workspace, session_id, context_source.
- **Session file:** Parses `lupo-database/session.md` (YAML/frontmatter or key: value). When present and usable, used as base; then DB and registry enrich.

### Session Modes

| Mode | Description | Example |
|------|-------------|---------|
| `human_direct` | Human acting directly | Captain logged in |
| `hybrid` | Agent acting for human | Cursor for Captain |
| `autonomous_agent` | Agent acting independently | Lilith alone |
| `system` | System agent | System operations |

### CLI Commands

```bash
# Show current identity (human-readable)
php lupo-bin/lupo.php whoami

# Show full context as JSON
php lupo-bin/lupo.php context
```

## Files

- `lupo-includes/classes/ContextResolver.php`
- `lupo-bin/lupo.php` (whoami/context output)
- [docs/lupopedia_whoami_readme.md](../../../../../../../docs/lupopedia_whoami_readme.md)

## Verification

- ContextResolver returns all three identity layers
- Session modes correctly classified
- CLI output matches documentation
- Fallback to session.md when DB offline
