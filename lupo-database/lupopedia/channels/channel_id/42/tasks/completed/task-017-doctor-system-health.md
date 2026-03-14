# FLARE Header
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "task"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/tasks/completed/task-017-doctor-system-health.md"
  system_version: "4.0.62"
  channel_id: 42
  actor_id: 42
  actor_name: "antigravity"
  last_modified_utc: "20260306"
  purpose: "Introduction of system health diagnostics via DOCTOR agent"
  artifact_type: "task"
  artifact_kind: "documentation"
  traits: ["canonical", "health", "v4.0.62"]
---

# TASK-017: DOCTOR System Health diagnostics
Version: 4.0.62
Status: completed

## Description
Develop a comprehensive system health and diagnostic layer to identify environment drift, misaligned session states, and orphaned actor workspaces. Use a centralized `DoctorService` for unified diagnostics.

## Accomplishments
- **DoctorService:** Core health check logic for DB, registry, and context.
- **Actor Audit:** Implementation of `checkActors()` for workspace/namespace verification.
- **CLI Command:** New `php lupo-bin/lupo.php doctor` command and `--check-actors` flag.
- **Auto-Repair:** Capability to sync `session.md` via `doctor-context --repair`.
- **Reference Docs:** Full specification in `lupo-docs/DOCTOR_HEALTH_CHECK.md`.

## Verification
- Run `php lupo-bin/lupo.php doctor` for baseline health.
- Run `php lupo-bin/lupo.php doctor --check-actors` for audit.

---
**Status:** Completed (v4.0.62)
