---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "directive"
  file_path_from_root: "prompts/windsurf/20260307_version_transition.md"
  web_path: "http://www.lupopedia.com/directives/VERSION_TRANSITION_4_0_63"
  last_modified_utc: "20260307"
  system_version: "4.0.62"
  channel_id: 42
  actor_id: 1001  # Windsurf's actor ID from registry
  actor_name: "windsurf"
  delegation_chain: "lilith:cursor:captain:windsurf"
  artifact_type: "directive"
  artifact_kind: "version_management"
  purpose: "Transition from v4.0.62 to v4.0.63: Git ops, version updates, thread initiation"
  mood_rgb: "00FFFF"
  traits: ["canonical", "transition", "v4.0.63", "git", "channel"]
  tags: ["flare", "version", "git", "channel42", "tasks", "windsurf"]
  agent_name_identity: "WINDSURF — Version Navigator"
  lupo_agent: "windsurf"

lupopedia.init:
  execution_mode: "required"
  pre_actions:
    - type: dependency_check
      target: ".git"
    - type: dependency_check
      target: "docs/version.md"

lupopedia.edges:
  outbound_edges:
    - { to: "docs/version.md", type: "updates", weight: 1.0 }
    - { to: "CHANNEL_42.md", type: "initiates_thread", weight: 0.9 }
  semantic_tags: ["flare", "version", "transition", "windsurf"]

lupopedia.see:
  mappings:
    - ["prompts/windsurf/20260307_version_transition.md", "http://www.lupopedia.com/directives/VERSION_TRANSITION_4_0_63"]

lupopedia.close:
  post_actions:
    - type: broadcast_completion
      channel: 42
      message: "v4.0.63 initiated — tasks listed."
  actor_id: 1001

lupopedia.footer:
  version: "4.0.62"
  last_verified: "20260307"
  last_verified_by: "captain"
---

# 🌊 WINDSURF DIRECTIVE — VERSION TRANSITION TO 4.0.63

Windsurf, engage protocol: Seal v4.0.62 and launch v4.0.63 on Channel 42. Execute in sequence:

1. **Git Operations for v4.0.62 Closure**:
   - Run `git add .` to stage all changes.
   - Commit with message: "Closure of v4.0.62: DOCTOR ecosystem complete, HELP.md synced, README enhanced."
   - Push to main: `git push origin main`.
   - Tag the release: `git tag v4.0.62 && git push origin v4.0.62`.

2. **Update Version Files**:
   - Scan and update all `version.*` files (e.g., version.md, version.json, etc.) to reflect v4.0.63.
   - Increment semantically: Major features pending? If none, minor bump.
   - Ensure consistency across docs/version.md, README badges, FLARE headers.

3. **Initiate Thread on Channel 42 for v4.0.63**:
   - Create a new thread artifact: `CHANNEL_42_v4_0_63.md`.
   - Header: Announce "v4.0.63 Launch — Next Wave of Enhancements".
   - Include system status post-push: Run `php lupo-bin/lupo.php doctor` and embed results.

4. **List Active Tasks for Channel 42 in v4.0.63**:
   - Query the task registry (via DoctorService or SQL: SELECT * FROM lupo_tasks WHERE channel_id=42 AND status IN ('pending', 'active') AND version='4.0.63').
   - If no tasks yet, bootstrap with placeholders: Federation polish, AAL optimizations, advanced CLI features.
   - Format as a table in the thread:
     | Task ID | Description | Status | Assignee |
     |---------|-------------|--------|----------|
     - Populate from active queries; if empty, note "Tasks pending delegation."

Broadcast completion on Channel 42: "Windsurf: v4.0.63 thread live — tasks queued. Ready to ride."

Confirm session L-LUPO-WINDSURF synced. Execute now, agent. 🌊🚀

**END OF DIRECTIVE — WINDSURF, Version Navigator**
Channel 42
20260307
