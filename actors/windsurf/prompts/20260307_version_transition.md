---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/windsurf/prompts/20260307_version_transition.md
  web_path: https://www.lupopedia.com/lupopedia/actors/windsurf/prompts/20260307_version_transition.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: directive
  artifact_kind: version_management
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: directive
  prd_cluster: null
  title: null
  summary: null
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
   - Include system status post-push: Run `php bin/lupo.php doctor` and embed results.

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
