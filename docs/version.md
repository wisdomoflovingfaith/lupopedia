# file: Lupopedia Version History — session: L-LUPO-ANTIGRAVITY — delegation: antigravity:cursor:captain  — web_path: http://www.lupopedia.com/docs/version
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "docs/version.md"
  last_modified_utc: "20260308"
  system_version: "4.0.67"
  purpose: "Version history and upgrade notes for Lupopedia"
  traits: ["versioning", "v4.0.67", "multi-agent", "evolution"]
  tags: ["version", "changelog", "upgrade"]
---

# Lupopedia version history

Current version: **4.0.67**  
Date: 2026-03-08

## Summary of changes (4.0.67)

- **Install & Upgrade Validation:** Release for testing the Crafty Syntax 3.7.5 → Lupopedia upgrade path. Table ceiling **199 tables**; main admin (actor 10000) named **root**; ROOT doctrine schema: `lupo_contents.channel_id` and `federation_source_url`, `lupo_channel_departments`, `lupo_schema_migrations`, `lupo_actor_apps`. Current table count from TOON files: run `python scripts/generate_toon_files.py`.
- **Actor application folders (doctrine):** Every actor has an `apps/` directory with `skills/skills.md` (skill registry), `scripts/`, `assets/` (icons, images, prompts, templates), and `references/` (schema.md, manifest.json). See lupo-actors and ROOT doctrine emails.

## Summary of changes (4.0.66)

- **Multi-Agent Evolution Phase:** Implemented hierarchical multi-agent coordination with new kernel agents **LUPO** (Database Architect) and **THEMIS** (Ethical Auditor).
- **Consensus Workflow:** Established `Lilith -> THEMIS -> WOLFIE` consensus loop for task validation and structural reviews.
- **Improved Persistence:** Enhanced the `lupo-channels/` structure with versioned threads, status-driven task subdirectories, and roll-based permissions.
- **Database Schema (canonical):** Multi-agent services use existing canonical tables: `lupo_dialog_threads`, `lupo_dialog_messages`, `lupo_actor_channel_roles`, and `lupo_tasks`. No duplicate `lupo_threads`/`lupo_messages`/`lupo_rolls`; see 4.0.66 remediation.

- **Web Authentication Foundation:** Building upon the web authentication and actor selection features from 4.0.64.

## Summary of changes (4.0.64)

- **Web Authentication and Actor Selection:** Implemented dual-identity web flow and actor selector in admin interface. AuthService with active/preferred actor management, ActorService with user-can-act-as filtering, switch-actor.php with CSRF validation, admin_layout.php actor selector dropdown.
- **Documentation Scaling:** Enhanced HELP.md, CLI.md, and DOCTOR_HEALTH_CHECK.md with comprehensive coverage of new authentication features.
- **Version Management:** Updated all version files and global atoms for consistent version tracking.

## Summary of changes (4.0.62)

- **Actor Directory Refactor:** Name-based directories (e.g., `lupo-actors/wolfie/`) with `actor_name` as primary key.
- **Agent WWW:** Web-accessible `www/` directory for actor profiles at `/agent/<name>/`.
- **Skills System:** Modular capabilities hub in actor `skills/` subdirectory.
- **Filesystem-First Logic:** Consolidated `ActorService` to prioritize name-based paths and WHO.json root truth.

## Recent version history

| **4.0.67** | 2026-03-08 | Install & upgrade validation cycle; Crafty 3.7.5 baseline verification. |
| **4.0.66** | 2026-03-08 | Multi-agent evolution: LUPO, THEMIS, consensus loops, and schema enhancements. |
| **4.0.65** | 2026-03-07 | Development phase - ongoing feature development. |
| **4.0.64** | 2026-03-07 | Web authentication, actor selection, documentation scaling, version management. |
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
