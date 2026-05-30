---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "channels/42/threads/2008/THREAD_INDEX.md"
  questions_toon: null
  channel_id: 42
  thread_id: 2008
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "index"
  artifact_kind: "thread_index"
  purpose: "Navigation index for Thread 2008 — channel/thread/dialog workflow documentation task."
---

# Thread 2008 — Channel / Thread / Dialog Workflow Documentation

- **thread_status**: not-started
- **task_id**: task_document_channel_thread_dialog_001
- **assigned_actor**: thoth
- **thread_name**: channel_thread_dialog_workflow_documentation
- **artifact_count**: 0
- **last_modified_utc**: 20260322_144500

## Purpose

Produce canonical documentation of the Lupopedia workflow model:

- How channels work (types, membership, roles, posting rules)
- How threads work (lifecycle, artifact rules, THREAD_INDEX vs TASK_REGISTRY authority)
- How dialog works (lupo_dialog_threads, lupo_dialog_messages, dialog_channels)
- How actors and auth_users interact (actor model, auth_users for login, session flow)
- How status artifacts are used (implementation_report, contradiction, directive types)
- How edges connect everything (edge types, edge_map, relationship semantics)

## Scope

Documentation must cover the live schema tables:
- lupo_channels, lupo_channel_state, lupo_actor_channels, lupo_actor_channel_roles
- lupo_dialog_threads, lupo_dialog_messages, lupo_dialog_channels
- lupo_actors, lupo_auth_users, lupo_sessions
- lupo_edges, lupo_edge_types, lupo_edge_type_definitions, lupo_edge_map, lupo_actor_edges
- lupo_artifacts, lupo_artifact_chunks

And the filesystem conventions:
- channels/{id}/threads/{thread_id}/ artifact storage
- THREAD_INDEX.md navigation-only convention
- TASK_REGISTRY as single source of truth

## Output Artifact

Canonical docs file to be placed at: `docs/versions/4.0.85/workflow_model.md` (or equivalent)

## Next Action

Assign to thoth. Pending execution in a new session.
