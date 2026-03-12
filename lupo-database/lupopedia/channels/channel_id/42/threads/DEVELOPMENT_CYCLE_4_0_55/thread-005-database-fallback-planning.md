# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

lupopedia.conditional:
  guards:
    execution_mode: "advisory"
    allow:
      actor_ids: [0, 1004]
      agent_names: ["system", "antigravity"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-11T00:00:00Z"
    conditions:
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents"]
    what:
      artifact_type: "guide"
      objective: "Thread: Fallback Database Planning"
    where:
      repo_paths: ["lupo-database\lupopedia\channels\lupo-channels\42\threads\DEVELOPMENT_CYCLE_4_0_55\thread-005-database-fallback-planning.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T10:08:32Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database\lupopedia\channels\lupo-channels\42\threads\DEVELOPMENT_CYCLE_4_0_55\thread-005-database-fallback-planning.md"
  file_hash: "551f5f53c40873183550771491b57e256546bfe3440682b9fd461f632dc3db26"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Thread: Fallback Database Planning"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "threads"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-database\lupopedia\channels\lupo-channels\42\threads\DEVELOPMENT_CYCLE_4_0_55\thread-005-database-fallback-planning.md", "http://www.lupopedia.com/THREAD-005-DATABASE-FALLBACK-PLANNING"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# Thread: Fallback Database Planning
Channel: 43 (Decision Source) -> Channel 42 (Implementation)
Version: 4.0.55

## Overview
Based on communication from channel 43, the decision has been made to introduce a `lupo-database/` directory to serve as a high-resolution, file-based fallback for the SQL database. This system will utilize Markdown, CSV, and YAML formatted files to ensure performance and accessibility even when the primary DB is offline.

## Decisions Made
- **Primary Migration**: `lupo-channels/`, `lupo-actors/`, and `lupo-content/` will move to `lupo-database/lupopedia/`.
- **Primary Config**: `LUPO_DATABASE_DIR` will be added to `lupopedia-config.php`.
- **Logical Mapping**: All 210+ remaining tables will have a corresponding file-based path.
- **Fallback Read/Write**: The system will conditionally read from the fallback folders if the primary PDO connection fails.
- **Search-and-Replace Audit**: A full codebase audit will be needed to replace hardcoded paths and update constants to the new nested directory structure.

## Next Steps (M3 Milestone)
- Perform the directory migration.
- Update `lupopedia-config.php`.
- Finalize the fallback logic stubs.
