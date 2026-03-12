# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/task-014-channels-full-migration

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
      objective: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
    where:
      repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/task-014-channels-full-migration.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:39:11Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "task"
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/task-014-channels-full-migration.md"
  file_hash: "ae16030752852bac79fab92f814ab1507ce214630283ed8891339718ab2c2b78"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "tasks"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/task-014-channels-full-migration.md", "http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/task-014-channels-full-migration"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---


# TASK-014: Full Channels Recursive Migration
Version: 4.0.55
Status: planned

## Description
Wholesale, recursive move of the existing `lupo-channels/` tree into the new `lupo-database/lupopedia/channels/` directory. This task is the core of the fallback system's channel-truth establishment.

## Step-by-Step Migration Plan
1. **Backup**: Execute a full recursive copy of `lupo-channels/` to a temporary backup directory.
2. **Directory Creation**: Ensure `lupo-database/lupopedia/channels/` exists as a parent.
3. **Move**: Physically move `lupo-channels/*` to the new path, preserving all internal folder structure (e.g., `/tasks/`, `/plans/`, `/threads/`, `/collections/`, etc. for each channel).
4. **Link Audit**: Scan internal Markdown links and update them as needed. If links are relative, they should remain valid. If they are based on common constants, ensured they are updated to reflect the new `LUPO_CHANNELS_DIR` in TASK-011.
5. **Codebase Search-and-Replace**: Perform a global scan for hardcoded `lupo-channels/` strings and replace them with `LUPO_CHANNELS_DIR` or the new path.

## List of Directories to be Moved
- `lupo-channels/0/` (Recursive)
- `lupo-channels/42/` (Recursive - This includes tasks, plans, threads, collections)
- `lupo-channels/66/` (Recursive)
- ... (All other channels)

## Dependencies
- TASK-011: Config Constants Update

## Proposed Config Snippet
- Covered by TASK-011.

## Migration Notes
- Ensure path consistency and update automated scripts that reference channel paths.