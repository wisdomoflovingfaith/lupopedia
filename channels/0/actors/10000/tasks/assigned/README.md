# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\channels\0\actors\10000\tasks\assigned\README.md"
  file_hash: "0ac1ce5a7613104168592d30330adeb08669e4666f0c840cf8ff972492184309"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\0\actors\10000\tasks\assigned\README.md"
  file_hash: "c8ee527a11e3f42f3f20253817887b5860bce4237699f39937c5d11b6ca90cd0"
  file_path_from_root: "channels\0\actors\10000\tasks\assigned\README.md"
  file_hash: "07bcbf31701d764a3e934df5ee46ae1450e49e0b5fe58b85f999145165eec7e2"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Assigned Tasks for Captain (10000)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "actors", "10000", "tasks", "assigned"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Assigned Tasks for Captain (10000)

This directory contains references to tasks assigned to Captain (10000) in Channel 0.

Tasks are centrally managed in `/channels/0/tasks/` and referenced here for actor-specific views.

## Active Tasks

- [db_reset_and_install.md](../../../../tasks/active/db_reset_and_install.md) - Database Reset and Fresh Install (CRITICAL)
- [registry_lock.md](../../../../tasks/active/registry_lock.md) - Registry Lock and Validation (HIGH)
- [installer_integration.md](../../../../tasks/active/installer_integration.md) - Installer Integration and Testing (MEDIUM)

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