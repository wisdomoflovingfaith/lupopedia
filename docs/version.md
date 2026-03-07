---
# FLARE Header
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "docs/version.md"
  last_modified_utc: "20260306"
  system_version: "4.0.61"
  purpose: "Version history and upgrade notes for Lupopedia"
  traits: ["versioning", "v4.0.61", "context", "cli"]
  tags: ["version", "changelog", "upgrade"]
---

# Lupopedia version history

Current version: **4.0.62**  
Date: 2026-03-06

## Summary of changes (4.0.62)

- **Context Kernel (lupo-includes/classes/ContextKernel.php):** Singleton-based runtime context object centralizing `ContextResolver::resolve()`. Provides `validate()` to detect Split-Brain session conflicts (session.md vs DB) and agent-pairing failures.
- **DOCTOR Actor AI Agent (actor_id 1009):** Registered in `lupo-database/lupopedia/actors/actor_id/registry.json`. Dedicated workspace in `lupo-actors/doctor/` and specialized CLI handlers in `lupo-agents/doctor/` (`doctor.php`, `doctor-context.php`).
- **DoctorService (lupo-includes/classes/DoctorService.php):** Core logic for system-wide health checks and automated `session.md` repair.
- **CLI & Integration Migration:**
    - **lupo-bin/lupo.php:** Migrated `whoami`, `context`, `auth`, `actor-context`, `help`, `doctor`, and `doctor-context` to use **ContextKernel**. Surves **KERNEL ISSUE** warnings for identity drift.
    - **Context Doctor:** `doctor-context --repair` now synchronizes `session.md` metadata with the canonical database/kernel identity.
    - **Antigravity Migration:** `AntigravityContext.php` and `lupo-agents/antigravity/context.php` now consume the kernel for single-source resolution.
- **Task System & Documentation:**
    - **Task Reference:** Created `docs/TASK_STATUS_REFERENCE.md` and `docs/CHANNEL_0_ACTOR_0_TASKS.md`. Formalized six statuses: pending, active, completed, blocked, failed, archived.
- **Version Synchronization:** Executed comprehensive version synchronization for versions 4.0.57-4.0.62 (Windsurf). All missing tags pushed to GitHub. System verified as PRODUCTION READY.

## Recent version history

| Version | Date       | Summary |
|--------|------------|--------|
| **4.0.62** | 2026-03-06 | Context Kernel, DOCTOR actor (1009), Task system documentation, and version synchronization. |
| **4.0.61** | 2026-03-06 | Session-file-first context; version tracking (version.php, version.md, config); help integration. |
| **4.0.60** | 2026-03-06 | Dual-identity runtime context: Effective Actor, Human Identity, Active Agent, session_mode; paired_actor_id from DB; whoami/context output. |
| **4.0.59** | 2026-03-06 | ContextResolver, DialogHeaderValidator, required dialog headers; session.md fallback; whoami/context CLI. |
| **4.0.58** | 2026-03-06 | Whoami/actor_name as primary identity; session binding; whoami readme. |
| **4.0.57** | 2026-03-05 | Migration and optimization; FLARE refinements; database optimization; install/doc seeds. |

## Upgrade notes

- **4.0.56 → 4.0.61:** Only supported upgrade path remains Crafty Syntax 3.7.5 → Lupopedia 4.0.x. No Lupopedia→Lupopedia migration scripts; apply schema/migrations as documented.
- **Session context:** To drive CLI identity from file when DB is down or for a fixed identity, edit `lupo-database/session.md` (YAML frontmatter: actor_name, channel_id, session_id, department_id, thread_id, paired_actor_id, etc.). See `docs/lupopedia_whoami_readme.md`.
- **Version in code:** Use `get_lupo_version()` or `LUPOPEDIA_VERSION`; avoid hardcoding version strings in help/CLI.
