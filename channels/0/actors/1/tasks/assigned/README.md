# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\0\actors\1\tasks\assigned\README.md"
  file_hash: "6fec2e48f6c187dc41d94ca5701e9c81e97661b5f573604c75f16f4556fc287a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Assigned Tasks for Captain WOLFIE (1)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "actors", "1", "tasks", "assigned"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Assigned Tasks for Captain WOLFIE (1)

This directory contains references to tasks assigned to Captain WOLFIE (1) in Channel 0.

Tasks are centrally managed in `/channels/0/tasks/` and referenced here for actor-specific views.

## Active Tasks

- [registry_lock.md](../../../../tasks/active/registry_lock.md) - Registry Lock and Validation (HIGH)

## How to Use

1. Read task files from central location: `/channels/0/tasks/active/`
2. Update task status in central location (not here)
3. This directory is a view only - do not create tasks here

## Task Status Legend

- **CRITICAL** - Blocking all other work
- **HIGH** - Important, should be done soon
- **MEDIUM** - Normal priority
- **LOW** - Nice to have

## Notes

All tasks are managed centrally to ensure shared state and prevent conflicts. Actor task views are for convenience only.
