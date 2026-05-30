---
lupopedia.headers:
  lupopedia.schema: documentation
  version_when_written: "4.0.93"
  file_path_from_root: "docs/versions/4.0.93/TODO.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/TODO.md"
  when_updated: "20260403120000"
  channel_id: 42
  thread_id: "todo-backlog"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "todo"
  artifact_kind: "master_backlog"
  purpose: "Frozen record of 4.0.93 completed work only; open backlog lives in 4.0.94/TODO.md"
  tags:
  - "todo"
  - "master"
  - "4.0.93"
  - "frozen"
lupopedia.edges:
  outbound_edges:
    - to: "docs/versions/4.0.93/PLAN.md"
      type: references
      weight: 1.0
      reason: Overall plan for 4.0.93
    - to: "docs/versions/4.0.93/WHAT_TO_DO_NEXT_SESSION.md"
      type: references
      weight: 1.0
      reason: Session handoff document
    - to: "docs/versions/4.0.93/README.md"
      type: references
      weight: 1.0
      reason: Frozen version readme and naming convention
    - to: "docs/versions/4.0.93/CHANGELOG.md"
      type: references
      weight: 1.0
      reason: Version changelog
    - to: "docs/versions/4.0.94/TODO.md"
      type: references
      weight: 1.0
      reason: Active backlog after freeze
    - to: "docs/versions/4.0.93/DATABASE_AUDIT_SUMMARY.md"
      type: references
      weight: 1.0
      reason: Database audit summary
lupopedia.footer:
  last_verified: "20260403120000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"

## Completed (4.0.93)
[//]: # (LILITH Directive: TODO update for 4.0.93, 2026-04-02)

## 4.0.93 freeze (completed 2026-04-02)
- [x] `docs/versions/4.0.93/decisions/THREAD_INDEX.md` (existing)
- [x] `docs/versions/4.0.93/questions/THREAD_INDEX.md`
- [x] `docs/versions/4.0.93/answers/THREAD_INDEX.md`
- [x] `docs/versions/4.0.93/comments/THREAD_INDEX.md`
- [x] `README.md` in version folder (freeze + naming convention)
- [x] `edges.md` updated for frozen core PRDs and 4.0.94 working PRDs

## Carried to 4.0.94 (see `docs/versions/4.0.94/TODO.md`)

All open tasks from this file were merged into **`docs/versions/4.0.94/TODO.md`** (deduplicated) on 2026-04-03. See **Open Work → 4.0.94 Only** below.

## Historical completions (4.0.93 era)

[x] **PRD 26 Final Corrections (2026-04-02):** Fixed constitutional violations identified by COUNTERMEASURE - implemented deterministic ID generation, numeric-only identifiers, tooling requirements for parent_edges_ref, added version compatibility mapping
[x] **Documentation Architecture & 5W1H Framework (2026-04-02):** Created comprehensive documentation architecture doctrine, established 5W1H framework as universal thinking pattern, created version edges.md for relationship mapping
[x] **PRD 26 Five-Layer Architecture (2026-04-02):** Created and approved PRD defining five-layer documentation architecture (WHAT, HOW, WHY, WHO, WHERE), corrected header/footer per LILITH audit
[x] **PRD 16 Header Updates (2026-04-02):** Added author/verifier distinction, deprecated actor_name, implemented conditional field requirements per artifact type
[x] **Universal Validator Enhancement (2026-04-02):** Added author field support with legacy compatibility, implemented conditional validation rules
[x] **DOCUMENTATION_ARCHITECTURE Doctrine (2026-04-02):** Created canonical guide explaining 5W1H across all documentation types, headers, edges, content, and threads
[x] **Version edges.md Creation (2026-04-02):** Created relationship mapping file for version 4.0.93 documentation components
[x] **PRD 30 Development Guide & Decision Documentation (2026-04-02):** Fixed naming conventions, removed embedded WHERE instructions, clarified decision contexts without overriding other PRDs
[x] **Decision Documentation Framework (2026-04-02):** Established clear distinction between PRD-scoped decisions (implementations/{id}_{slug}/decisions/) and version-scoped decisions (versions/{version}/decisions/)
[x] **Context System Attempt (2026-04-02):** Created PRD 31 for context system framework
[x] **COUNTERMEASURE Review (2026-04-02):** LILITH correctly identified parallel classification system conflict
[x] **PRD 31 Rejection (2026-04-02):** Rejected parallel classification system, maintained architectural simplicity
[x] **Database Cleanup (2026-04-02):** Removed contexts, contexts_map, and hotfix_registry tables from install schema
[x] **PRD alignment + constitutional edges (2026-04-01, Cursor thread):** `00_root` updated (schema JSON, agent_key, §5.5–5.6, §9.9/9.16/9.18); `PRD_AGENT_DEFINITION_MODEL.md` deprecated for layout; `07` reserved-ID clarification; `01_core_identity` / `15_actors` actor ID text; `08_actors` superseded; `05_auth` canonical `lupo_actor_auth_users`; installer `BIGINT` wording; PRD header paths fixed; `19`/`18` json edges; **all** `docs/prd/*.md` have constitutional anchor edge except `00_root`; version `decisions`/`PLAN`/`TODO`/`CHANGELOG` updated.
[x] PRD 17_decisions_format.md created
[x] context_id field and context directory implemented
[x] All grouped PRDs and agent/actor/lease/temporal/header doctrines updated
[x] Validator and scripts updated for context_id
[x] LILITH Audit: Data Model PRD Corrections
[x] LILITH Audit: Installer Requirements PRD
[x] LILITH Audit: Core Identity PRD (Final Review)
[x] LILITH Correction: Version Directory Purpose
[x] LILITH Directive: Create Countermeasure Agent
[x] LILITH Audit: COUNTERMEASURE Agent Configuration
[x] LILITH Directive: Update COUNTERMEASURE Agent Prompt
[x] Channel chat PRD 18 and implementation
[x] WOLFIE Doctrine created and integrated
[x] Multi-Agent Orchestration Doctrine created
[x] Actor-Agent Distinction Doctrine created
[x] Database Doctrine created and integrated
[x] Garbage Collection System PRD created
[x] GarbageCollector class implemented
[x] GC CLI script created
[x] GC Doctrine created
[x] LILITH Audits & COUNTERMEASURE Development (March 2026)
  - [x] LILITH Audit: Data Model PRD Corrections
  - [x] LILITH Audit: Installer Requirements PRD
  - [x] LILITH Audit: Core Identity PRD (Final Review)
  - [x] LILITH Correction: Version Directory Purpose
  - [x] LILITH Directive: Create Countermeasure Agent
  - [x] LILITH Audit: COUNTERMEASURE Agent Configuration (2x)
  - [x] LILITH Directive: Update COUNTERMEASURE Agent Prompt
  - [x] LILITH Audit: Installer Requirements PRD
  - [x] LILITH Audit: Core Identity PRD (Final Review)
[x] All versioned docs and PRDs cross-referenced and LILITH-audited
- [x] HEPHAESTUS identity doctrine and Faucet Proxy Pattern adopted (Actor 102)
- [x] Channel 42 thread structure standardized and all coordination migrated
- [x] LILITH agent definitions consolidated and adversarial audit enabled
- [x] Option A split-table architecture for Truth Management System formalized
- [x] Legacy FLIP/FLARE documentation removed
- [x] Truth system documentation completed
- [x] Database doctrine finalized (catch-and-retry, no UNSIGNED)
- [x] JS Nervous System (State Mirror, Scroller, Monitor, Glass UI) implemented and tested
- [x] Git hook issues documented and workarounds in place
- [x] Hybrid-Mirror Architecture implemented: Database-first with filesystem archival mirrors
- [x] LUPOPEDIA_HEADERS updated with context_id field and channel-to-context lifecycle
- [x] Database table organization context created
- [x] Channel architecture discussion thread created with WOLFIE synthesis (Option B+)
- [x] **LILITH "Source of Truth" Protocol implemented with Toon guardrail**
- [x] **4.0.93 PRD files updated with actual database schema and migration mapping**
- [x] **Subdirectory installation doctrine established for Semantic Monitoring Widget**
- [x] **Temporal Anchor & UTC Timestamp Policy enforced (tick.py, bin/temporal_anchor.json, UTC-only)**
- [x] **ID Generation Directive Compliance**: IdGenerator.php updated with YYYYMMDDHHIISS + random suffix format; 63-bit signed-safe BIGINTs; test suite created
- [x] **Full Database Audit**: Comprehensive audit of all 171 tables completed; 5 doctrine violations; 48 missing documentation; all PRDs updated with lupopedia.edges
- [x] **PRD Edge Integration**: All PRD files now include lupopedia.edges sections linking to table definitions and related documentation
- [x] **Grouped PRD Structure**: Complete 14-namespace PRD architecture created in `docs/prd/`; 100% PRD coverage achieved (14/14 files, 171 tables); maintenance burden reduced by 92%. All new core identity tables are included and documented.
- [x] **Consolidated install seed + installer alignment (2026-03-30):** Runtime seed is `install/seed_lupopedia_4_1_0.sql` (rebuild via `scripts/build_consolidated_seed_4_1_0.py`); root `install.php` loads only this after `install_new_lupopedia.sql`; `InstallWizardSqlRunner::applyTablePrefixToSql()` applies `{{prefix}}`; original seeds retained under `database/lupopedia/mysql/seed/`.
- [x] **Installer verification (read-only, 2026-03-30):** Confirmed wizard paths, load order, import gating, and consolidated/import `{{prefix}}` usage; documented in CHANGELOG (Minor) and `WHAT_TO_DO_NEXT.md` §14.
- [x] **Installer runtime seed cleanup (2026-03-31):** Removed remaining per-file post-seed execution from `install.php`; runtime path is schema + `install/seed_lupopedia_4_1_0.sql` (+ Crafty import on upgrade only).
- [x] **Obsolete legacy seed files removed (2026-03-31):** Deleted non-runtime legacy seed files that were no longer used by installer flow.
- [x] **Runtime actor deterministic IDs + sharded workspace path (2026-03-31):** Actor creation paths now use YmdHis+4 IDs and `actors/YYYY/MM/actor_id`; resolver/helpers preserve legacy flat path fallback.
- [x] **Channel chat (2026-03-31, Cursor + Cascade thread):** PRD 18 aligned; `channels-api.php` extended (`format=buffer|image`, `thread_id`, `dialog_thread_id`, digit redirect); `channel.php` + `channel-chat/` routes; `chat-display.js` / `chat-display-legacy.js` / `chat-display.css`; `channels/{id}/thread/{id}` slug; operator-supplied digit GIFs in `ui/images/`; implementation documentation created with LUPOPEDIA headers.
- [x] **WOLFIE Doctrine (2026-04-01):** Constitutional doctrine created as root-level rule; Five Pillars established; binding rules W-01 through W-05 against framework bloat; root README updated; constitutional requirements PRD updated; 1999-era code protected from forced modernization.
- [x] **Multi-Agent Orchestration Doctrine (2026-04-01):** Created comprehensive doctrine documenting cascade workflow; meta-agent loop established (LILITH refines prompts for internal swarm); scale documented (10+ IDEs, 50+ agents); dogfooding principle established - system building itself using its own coordination architecture.
- [x] **Actor-Agent Distinction Doctrine (2026-04-01):** Created canonical distinction doctrine; updated all PRDs (01_core_identity.md, 07_agents_faucets.md, 15_actors.md); added Rule W-06 to WOLFIE Doctrine; clarified agents are immutable templates, actors are learning instances; documented workspace structures and creation flows.
- [x] **Root Sanitization & PRD Backfill (2026-04-01, Antigravity thread):** WOLFIE Doctrine updated with accurate HPC/Notepad++ logic. Created ASCLEPIUS health check and CLI Interface PRDs. Formally exempted `node_modules` and `app` from prefix rules. Dismantled `prompts/` to localized actor workspaces. Executed Batches 6 & 7 to migrate 19 loose root files into pristine constitutional mappings (`rules/`, `config/`, etc.) and fixed corrupted `.gitignore`.
- [x] **Universal Header Validator Enhancement (2026-04-02):** Updated `validate_lupopedia_headers_universal.py` to support new author structure and conditional field requirements
- [x] **PRD 16 Artifact Type and Kind Taxonomy (2026-04-02):** Added comprehensive taxonomy definitions with 8 artifact types and conditional required fields
- [x] **LUPOPEDIA_HEADERS Documentation Update (2026-04-02):** Updated all documentation to align with PRD 16 and validator changes

### New Tasks (from documentation thread, 2026-04)
- [x] Archive docs/status/ directory
- [x] Port any unique architectural content from archived files to canonical PRDs
- [x] Created `20_federation_intake_doctrine.md`
- [x] Created `21_thread_graduation_doctrine.md`
- [x] Update project structure PRD with `federation_nodes/`
- [x] Create `.cursorrules` injection boundary strategy (`compile_agent_rules.py`)
- [x] Implement thread lifecycle automation and bootstrap scripts (`archive_stale_threads.py`, `bootstrap_thread_manifests.py`)
- [x] Defoliate 13 dead `includes/` directories into `archive/`.
- [x] Run Conflict Resolution Protocol and consolidate 31 loose PHP classes.
- [x] Inject LILITH Notepad Justification into `WOLFIE_DOCTRINE.md`.

### LILITH "Absolute-Root" Mandate (v4.0.93) — reference

**RULE [93.PATH_PURITY]**: All Markdown links must use absolute paths starting from repository root.

**Markdown Purity Enforcement**
- **Leading /**: Only legal anchor for repo-wide stability
- **No ~ or @ aliases**: Markdown does not support relative shortcuts
- **No ../ navigation**: IDE strictly forbidden from using relative paths
- **Fixed Repository Addresses**: If file exists in repo, its address is fixed relative to / root

**Softaculous Compatibility**
- **Web URL**: Includes subdirectory (e.g., /lupopedia/)
- **Internal Documentation**: Links remain relative to repository root
- **Cross-Installation Stability**: Docs work regardless of where folder is placed

**IDE Enforcement**
- **RULE [93.PATH_PURITY]**: The IDE is now strictly forbidden from using ../../
- **CORRECT**: `[Link](/docs/versions/4.0.93/prd/01_monitor.md)`
- **FORBIDDEN**: `[Link](../../../prd/01_monitor.md)` or `[Link](~/docs/...)`

| Document | Pathing Strategy | Status |
|----------|------------------|---------|
| README.md | Absolute Root (/) | UPDATED |
| TODO.md | Absolute Root (/) | UPDATED |
| prd/*.md | Absolute Root (/) | QUEUED FOR REWRITE (see 4.0.94) |

### Additional completed items (certification thread, chat, toons, Kiro thread)

- [x] **Minimal channel message UI + API fallbacks (2026-03-31):** Standalone `channel.php` / `channel-chat/` using `api/channels/.../messages` with `format=buffer` / `format=image` (see CHANGELOG). Implementation documented in `docs/implementations/channel-chat.md`. Does not replace full `/channels/` cockpit.
- [x] **March 2026: Table/Emoji System Overhaul** — Remove obsolete channel boot/smilies tables from install SQL; emoji via `::img|foldername|filename::` and `emoji/` (see EMOJI_AND_SMILIES doctrine).
- [x] **RULE [93.PROTECT_TOONS]:** Toon guardrail enforced (schema in install SQL; `generate_toon_files.py` after changes).
- [x] **LILITH "Source of Truth" Protocol (PRD data model / JS foundation / semantic widget PRDs updated).**
- [x] **Kiro thread (2026-04-01):** 00_root YAML and §9.9/9.18/9.19/9.20; semantic monitoring widget rewrite; `generate_toon_files.py` schema-only; `export_table_data_csv.py`; `.gitignore` csv; `lupo_paths` table doc; root README/decisions/project structure PRD updates.

## Open Work → 4.0.94 Only

All active development tasks, checkboxes, and backlog items have been moved to:

**`docs/versions/4.0.94/TODO.md`**

This file (`4.0.93/TODO.md`) exists only as a frozen record of what was completed or carried forward during the 4.0.93 freeze. **No new checkboxes or tasks belong here.**
