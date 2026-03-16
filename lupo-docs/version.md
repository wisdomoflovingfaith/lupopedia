# file: Lupopedia Version History — session: L-LUPO-ANTIGRAVITY — delegation: antigravity:cursor:captain  — web_path: http://www.lupopedia.com/docs/version
---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/version.md"
  last_modified_utc: "20260316"
  system_version: "4.0.79"
  purpose: "Version history and upgrade notes for Lupopedia"
  traits: ["versioning", "v4.0.79", "multi-agent", "evolution"]
  tags: ["version", "changelog", "upgrade"]
---

# Lupopedia version history

Current version: **4.0.79**  
Date: 2026-03-16

## Summary of changes (4.0.79)

- **Version bump:** Post–4.0.78 release. Active development version. All canonical version markers and atoms updated to 4.0.79. Unfinished work from 4.0.78 carried forward: remaining Top 50 operational table documentation, bounded header/namespace cleanup (TABLE_INDEX.md missing headers, Top 50 scope). See [lupo-docs/versions/4.0.79/PLAN.md](versions/4.0.79/PLAN.md) and [TODO.md](versions/4.0.79/TODO.md). No Lupopedia→Lupopedia upgrade path before 4.1.0.

## Summary of changes (4.0.78) — Released 2026-03-16

- **Released and tagged.** Top 50 reframing; 25 table docs completed (lupo_actors, lupo_channels, lupo_contents, lupo_sessions, lupo_comments, lupo_uploads, lupo_visits, lupo_dialog_messages, lupo_agent_faucets, lupo_actor_apps, lupo_channel_departments, lupo_edge_type_definitions, lupo_analytics_visits, lupo_audit_log, lupo_system_logs, lupo_metadata, lupo_atoms, lupo_collections, lupo_departments, lupo_registry, lupo_modules, lupo_federation_nodes, lupo_auth_users). Namespace doctrine, validator, audit, and cleanup. See CHANGELOG.md for full 4.0.78 record.

## Summary of changes (4.0.77) — Released 2026-03-16

- **Released and tagged.** Constitutional root rules, LUPOPEDIA_HEADERS enhancements, Bayesian Decision foundation, Zencoder/Windsurf/Cursor table documentation initiative with stop line, header tooling (export/import/validate), Crafty 3.7.5 → 4.0.77 upgrade validation. See CHANGELOG.md for full 4.0.77 record.

## Summary of changes (4.0.76)

- **Released and tagged.** Project System schema, application, testing; Windsurf review final completion; upgrade guide; production-ready. All canonical version markers and atoms were 4.0.76. Recurring install/upgrade validation continues under 4.0.77.

## Summary of changes (4.0.75)

- **Released and finalized.** Version bump, rules and governance updates, multi-agent propagation, schema-reference continuity, ONBOARDING, Safe DB Operations (DB009). Fresh install and Crafty 3.7.5 upgrade validation were performed during the 4.0.75 cycle; continued repeated validation is carried forward to v4.0.76.

## Summary of changes (4.0.74)

- **Documentation consolidation & architecture clarification:** 12-table install expansion, lupo_projects + seed_projects.sql wired, path normalization (lupo-* prefix; legacy/ exception), image paths (lupo-images/), table count 159, TOON/docs reconciliation. Pushed to GitHub as 4.0.74.

## Summary of changes (4.0.73)

- **Consolidated Version Transition:** Successfully finalized version 4.0.72 and initialized 4.0.73. 
- **Full Upgrade Path Validation:** Validated Crafty Syntax 3.7.5 to Lupopedia 4.0.73 upgrade path with 0 errors.
- **Task Consolidation:** All pending and active tasks from previous versions (4.0.69-4.0.72) have been consolidated into the 4.0.73 cycle.
- **Semantic Navbar Backend Rebuild:** Authority audit and implementation of semantic navigation backend (SQL, API, and core logic).

## Summary of changes (4.0.72)

- **Version bump:** Updated all canonical version markers and initialized the 4.0.73 initialization repo.
- **Next Action Doctrine:** Implemented `next_action` in `lupopedia.footer` as a core requirement for all documentation files.

- **Channels Web Interface Implementation:** Active development of the web interface for channels management. The relevant implementation is in the installed subdirectory and is accessed from `http://domainname.com/<lupopedia-sub-folder>/channels/`. This version cycle focuses on reviewing, completing, and hardening the channels web UI so it is fully operational and aligned with current doctrine/schema/runtime expectations.
- **Development Phase:** Transitioned to development version 4.0.68 for ongoing channels web interface development.

## Summary of changes (4.0.67)

- **Install & Upgrade Validation:** Release for testing the Crafty Syntax 3.7.5 → Lupopedia upgrade path. Table ceiling **199 tables**; main admin (actor 10000) named **root**; ROOT doctrine schema: `lupo_contents.channel_id` and `federation_source_url`, `lupo_channel_departments`, `lupo_schema_migrations`, `lupo_actor_apps`. Current table count from TOON files: run `python lupo-scripts/generate_toon_files.py`.
- **Actor application folders (doctrine):** Every actor has an `apps/` directory with `skills/skills.md` (skill registry), `lupo-scripts/`, `assets/` (icons, images, prompts, templates), and `references/` (schema.md, manifest.json). See lupo-actors and ROOT doctrine emails.

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

| **4.0.75** | 2026-03-14 | Version bump post–4.0.74 push; no schema changes. |
| **4.0.74** | 2026-03-14 | 12-table expansion, path/image normalization, docs reconciliation; pushed to GitHub. |
| **4.0.73** | 2026-03-12 | Version 4.0.73 initialization, task consolidation, and upgrade path validation. |
| **4.0.72** | 2026-03-12 | Version bump and finalization. |
| **4.0.71** | 2026-03-12 | Synthesized Documentation Framework, semantic navbar backend, Session Model A. |
| **4.0.69** | 2026-03-11 | Actor orchestration, Traits, Authorization, Documentation coherence. |
| **4.0.68** | 2026-03-09 | Channels web interface implementation - active development. |
| **4.0.67** | 2026-03-08 | Install & upgrade validation cycle; Crafty 3.7.5 baseline verification. |
| **4.0.66** | 2026-03-08 | Multi-agent evolution: LUPO, THEMIS, consensus loops, and schema enhancements. |
| **4.0.65** | 2026-03-07 | Development phase - ongoing feature development. |
| **4.0.64** | 2026-03-07 | Web authentication, actor selection, documentation scaling, version management. |
| **4.0.62** | 2026-03-06 | Context Kernel, DOCTOR actor (1009), Task system documentation, and version synchronization. |
| **4.0.61** | 2026-03-06 | Session-file-first context; version tracking (version.php, version.md, config); help integration. |
| **4.0.60** | 2026-03-06 | Dual-identity runtime context: Effective Actor, Human Identity, Active Agent, session_mode; paired_actor_id from DB; whoami/context output. |
| **4.0.59** | 2026-03-06 | ContextResolver, DialogHeaderValidator, required dialog headers; session.md fallback; whoami/context CLI. |
| **4.0.58** | 2026-03-06 | Whoami/actor_name as primary identity; session binding; whoami readme. |
| **4.0.57** | 2026-03-05 | Migration and optimization; FLARE refinements; database optimization; lupo-install/doc seeds. |

## Upgrade notes

- **4.0.56 → 4.0.61:** Only supported upgrade path remains Crafty Syntax 3.7.5 → Lupopedia 4.0.x. No Lupopedia→Lupopedia migration scripts; apply schema/migrations as documented.
- **Session context:** To drive CLI identity from file when DB is down or for a fixed identity, edit `lupo-database/session.md` (YAML frontmatter: actor_name, channel_id, session_id, department_id, thread_id, paired_actor_id, etc.). See `lupo-docs/lupopedia_whoami_readme.md`.
- **Version in code:** Use `get_lupo_version()` or `LUPOPEDIA_VERSION`; avoid hardcoding version strings in help/CLI.
