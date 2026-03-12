# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\channels\0\actors\1000\tasks\assigned\README.md"
  file_hash: "1ee2d98c437db6b1bc56893a00070d8c5db69f9f9fef43259e1b5ff31cb5d59e"
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
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "channels\0\actors\1000\tasks\assigned\README.md"
  file_hash: "cc0971b00cba354dd5e49899be39424baa148c152aaf2473bf44b970d29d8019"
  file_path_from_root: "channels\0\actors\1000\tasks\assigned\README.md"
  file_hash: "f24bf9f31313e10516302f414cde8faba5d6c705eaed24add8aca5f40c7a1429"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Assigned Tasks for Kiro IDE (1000)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "actors", "1000", "tasks", "assigned"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Assigned Tasks for Kiro IDE (1000)

This directory contains references to tasks assigned to Kiro IDE (1000) in Channel 0.

Tasks are centrally managed in `/channels/0/tasks/` and referenced here for actor-specific views.

## Active Tasks

- [broadcast_normalization.md](../../../../tasks/active/broadcast_normalization.md) - Broadcast Normalization (58 Files) (HIGH)

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