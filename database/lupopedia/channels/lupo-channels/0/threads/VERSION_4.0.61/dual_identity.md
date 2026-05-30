---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/dual_identity.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/dual_identity.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: feature
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: null
  title: null
  summary: null
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

### ContextResolver (includes/classes/ContextResolver.php)

- **Resolution order:** session.md (first) → enrich from lupo_sessions → enrich from registry → defaults.
- **Returns:** actor_name, actor_id, human_actor_name, human_actor_id, agent_name, actor_type, paired_actor_id, paired_actor_name, session_mode, department_id, channel_id, thread_id, federation_node_id, workspace, session_id, context_source.
- **Session file:** Parses `database/session.md` (or `{LUPO_DATABASE_DIR}/../session.md` when LUPO_DATABASE_DIR = database/lupopedia). When present and usable, used as base; then DB and registry enrich.

### Session Modes

| Mode | Description | Example |
|------|-------------|---------|
| `human_direct` | Human acting directly | Captain logged in |
| `hybrid` | Agent acting for human | Cursor for Captain |
| `autonomous_agent` | Agent acting independently | Lilith alone |
| `system` | System agent | System operations |

### CLI Commands

```bash
php bin/lupo.php whoami
php bin/lupo.php context
```

## Files

- `includes/classes/ContextResolver.php`
- `bin/lupo.php` (whoami/context output)

**Main documentation:** [docs/lupopedia_whoami_readme.md](../../../../../../../docs/lupopedia_whoami_readme.md)

## Verification

- ContextResolver returns all three identity layers
- Session modes correctly classified
- CLI output matches documentation
- Fallback to session.md when DB offline
