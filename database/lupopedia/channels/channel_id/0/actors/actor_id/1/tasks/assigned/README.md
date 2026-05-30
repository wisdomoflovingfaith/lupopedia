# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "database/lupopedia/channels/channel_id/0/actors/actor_id/1/tasks/assigned/README.md"
  file_hash: "4f8d939596460c3ca7f792024110975d412a03959266b307b43d4c5818735c63"
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
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "channels\0\actors\1\tasks\assigned\README.md"
  file_hash: "9481f6d48dce645e3719dfeccc39f8ab57204f61347bd8d1bcb02e797961b531"
  file_path_from_root: "channels\0\actors\1\tasks\assigned\README.md"
  file_hash: "6fec2e48f6c187dc41d94ca5701e9c81e97661b5f573604c75f16f4556fc287a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Assigned Tasks for Captain WOLFIE (1)"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "actors", "1", "tasks", "assigned"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Assigned Tasks for Captain WOLFIE (1)

This directory contains references to tasks assigned to Captain WOLFIE (1) in Channel 0.

Tasks are centrally managed in `/channels/0/tasks/` and referenced here for actor-specific views.

## Active Tasks

- registry_lock.md - Registry Lock and Validation (HIGH)

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
