---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/status/RESEARCH_ANUBIS_WOLFIE_FLARE_LUPOPEDIA.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "report"
  purpose: "Research ANUBIS, Wolfie, FLARE headers, Lupopedia; generated upgrade test thread prompt"
  traits: ["research", "cursor", "4.0.56"]
  tags: ["anubis", "wolfie", "flare", "lupopedia", "upgrade", "cursor"]
  lupo_agent: "cursor"
---

# Research: ANUBIS, Wolfie, FLARE Headers, and Lupopedia

**Date:** 2026-03-03  
**Actor:** Cursor (1003)  
**Purpose:** Summarize research on ANUBIS, Wolfie, FLARE headers, and Lupopedia; append generated directive-style prompt for the Crafty-to-4.0.56 upgrade test thread.

---

## 1. ANUBIS — Custodial Intelligence (AI 19)

### 1.1 Role and identity

- **Actor ID:** 19 (canonical; source of truth: `lupo-docs/doctrine/ANUBIS/ANUBIS_CANONICAL.md`, registry).
- **Full name:** Automated Normalization and Unified Broadcast Integrity System.
- **Role:** Custodial intelligence for dialogs, lineage, orphans, and redirects; FLARE header completion; quarantine routing.

### 1.2 Features

- **Orphan processing:** Detects orphan records (messages/files lacking headers/metadata), adopts into seed target (channel 42, thread 1; default from_actor_id 3 = WOLFIE). Banned actors’ content routed to quarantine (channel 666).
- **Database primacy:** Queue and processing tables (`lupo_anubis_queue`, `lupo_anubis_processing_log`, `lupo_anubis_quarantine`); migration `20260301_anubis_database_primacy_updates.sql` enforces primacy/columns.
- **FLARE ingestion faucet:** Agent faucet 6 (actor 19, domain 42) — file ingestion, semantic extraction, FLARE header/edge generation, `lupo_contents` insertion; file: `lupo-database/lupopedia/actors/faucets/6/faucet.json` and channel override `channels/lupo-channels/42/actors/19/faucets.json`.

### 1.3 Implementation (code/schema)

**PHP:**

- `lupo-includes/classes/ANUBIS_Resolver.php` — orphan classification, banned-actor lookup (`lupo_banned_actors`), default channel 42 / thread 1 / actor 3 (WOLFIE).
- `lupo-includes/classes/AI/AgentClasses.php` — ANUBIS AI init, active agents include ANUBIS.

**SQL:**

- `lupo-database/lupopedia/mysql/migrations/anubis_queue_tables_4.0.53.sql` — `lupo_anubis_queue`, `lupo_anubis_processing_log`.
- `lupo-database/lupopedia/mysql/migrations/20260301_anubis_database_primacy_updates.sql` — ALTERs for queue/quarantine, `filesystem_copy_exists`.

**Python:**

- `scripts/wolfie_orms.py` — `select_one_from_lupo_anubis_events`, `select_one_from_lupo_anubis_orphaned`, `select_one_from_lupo_anubis_redirects`.
- `lupo-tools/anubis_orphan_scanner.py` — orphan scanner and adoption planner; doctrine: `docs/doctrine/ANUBIS/`.

**Seeds:**

- `seed_actors_agents_4.0.45.sql` — ANUBIS (19) on channels 0 and 42; `seed_registry_additional_csv_entities_4.0.45.sql` — channel 666 (anubis-quarantine).

### 1.4 Integrations

- **Boot:** Agent subsystem loads ANUBIS among active agents.
- **Health API:** No direct ANUBIS endpoint; health/status can reflect agent readiness.
- **Channels:** 0 (system), 42 (primary), 666 (quarantine).

---

## 2. Wolfie — FLARE/FLIP alias and Captain Wolfie

### 2.1 Wolfie as header alias

- **FLARE Header** is also called **Wolfie Header**, **FLIP**, **FLP**, **FLPH**, **CROP** (doctrine and template).
- **Usage:** “FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)” appears at top of many docs; inference from file is file-level (FLIP = File-Level Inference Protocol).

### 2.2 Captain Wolfie (human authority)

- **Actor ID:** 10000 (human; Captain Wolfie). Source: directives, `lupo-docs/status/kiro_actors_supporting_actor_graph_4_0_43.md`, registry.
- **Role:** Human operator; issues Channel 42 directives; supports/owns IDE agents (e.g. KIRO 1001, Windsurf 1002, Cursor 1003); delegation_chain often `1002:10000` or `1003:10000`.
- **Actor 1:** Sometimes referenced for “Wolfie” in kernel/context; canonical human authority for directives is 10000.

### 2.3 Doctrines and tools

- **Doctrine:** FLIP/FLARE doctrine in `lupo-docs/doctrine/FLIP/FLIP_DOCTRINE.md`; MASTER_DOCTRINE, TABLE_CEILING_DEFENSE_PLAN reference Wolfie headers.
- **wolfie_orms.py** (`scripts/wolfie_orms.py`): ORM-style query helpers for many lupo_* tables (e.g. lupo_agent_faucets, lupo_anubis_*, lupo_actors). Used for schema sampling/validation; `select_one_from_lupo_agent_faucets` aligned to current schema in 4.0.56.

---

## 3. FLARE Headers — Structure, aliases, usage, tools

### 3.1 Structure

- **Block:** YAML between `---` delimiters at top of file.
- **Common fields:** `lupopedia.headers` with `lupopedia.version`, `lupopedia.schema`, `file_path_from_root`, `file_hash`, `system_version`, `channel_id`, `actor_id`, `delegation_chain`, `artifact_type`, `purpose`, `mood_rgb`, `traits`, `tags`, `lupo_agent`; optional `lupopedia.edges` (outbound_edges), `lupopedia.footer` (last_verified, last_verified_by).
- **Aliases:** Wolfie, FLIP, FLP, FLPH, CROP — same header system.

### 3.2 Usage

- **Markdown/docs:** Identity, lineage, channel, version inferred from header; no guessing (FLIP doctrine).
- **Edges/footers:** Outbound references to other docs, TOONs, APIs; semantic_tags.

### 3.3 Tools

- **Template:** `lupo-tools/flare_header_template.txt` — placeholders `{FILE_PATH_FROM_ROOT}`, `{SYSTEM_VERSION}`, `{CHANNEL_ID}`, `{ACTOR_ID}`, etc.; references FLARE_DOCTRINE, FLARE_HEADERS_COMPLETE_REFERENCE, FLARE_API.
- **Deduplication / application:** `lupo-tools/apply_flare_headers_v4.0.51.py`, `lupo-tools/flare_apply.py`, `lupo-tools/flare_correction_pass.py`, `lupo-tools/flare_manual_fix.py`; `lupo-tools/flare_header_issues.txt` / `flare_header_issues.json` for issues (e.g. missing file_path, system_version).
- **Audit:** `scripts/flip_header_audit.py` — X-Lupo-Channel, ANUBIS adoption channel fixes.

---

## 4. Lupopedia — System overview

### 4.1 What it is

- **Fork:** Continuation of Crafty Syntax Live Help 3.7.5; rebuilt as “Semantic OS.”
- **Upgrade path:** Only supported path is Crafty Syntax 3.7.5 → Lupopedia 4.0.x (no Lupopedia→Lupopedia until 4.1.0).
- **Additions:** Unified actor model, semantic content graph, AI agent ecosystem, doctrine-driven architecture, FLARE/FLIP headers, emotional/metadata standards.

### 4.2 Key components

- **Channels:** e.g. 0 (system), 1, 42 (development), 51, 666 (ANUBIS quarantine). DB: `lupo_channels`; filesystem: `lupo-database/lupopedia/channels/lupo-channels/<id>/`.
- **Actors:** Unified identity (actor_id); 0–9999 AI/system, ≥10000 human. Tables: `lupo_actors`, `lupo_auth_users`, `lupo_agents`. Actor directories: `lupo-database/lupopedia/actors/actor_id/<id>/` — OS-like user spaces (programs, collections, identity.json, session.json).
- **Agents:** AI agents (e.g. LILITH, SYSTEM, ANUBIS); config in `lupo-agents/` and registry; faucets define capabilities (see LUPO_AGENT_FAUCETS_RESEARCH_REPORT, FAUCET_DIRECTORY_IMPLEMENTATION_REPORT).
- **Faucets:** Per-actor/channel or ID-scoped (`lupo-database/lupopedia/actors/faucets/<id>/faucet.json`); loader: `lupo-bin/faucet_loader.php`; validation: `validate_faucets.php`, `faucet_integrity_audit.php`.
- **Database:** ~179 tables post-optimization; installer SQL under `lupo-database/lupopedia/mysql/` (install/, seed/, import/, migrations/). No FK/triggers; timestamps BIGINT YmdHis UTC.
- **Installer:** `install.php` — upgrade detection via livehelp_* tables or Crafty config; bootstrap (install + seed), identity normalization, import, migrations, seed_default_sessions.

### 4.3 References (code/docs)

- **AGENTS.md:** What the project is, dev workflow (drop → load Crafty baseline → install.php → verify), three SQL entrypoints, architecture (index.php, bootstrap, loader, module routing), key dirs, DB pattern, versioning.
- **CHANGELOG.md:** Version history; 4.0.56 task migration, faucet directory implementation, completed tasks moved to completed.
- **docs/status/UPGRADE_REPORT_4.0.56.md:** Verification checklist (DB count, key tables, agents, channels, faucet loader, web admin).

---

## 5. Generated prompt — Upgrade test thread (Crafty → 4.0.56)

The following directive-style prompt is for starting and executing the thread documented at `lupo-database/lupopedia/channels/lupo-channels/42/threads/UPGRADE_TEST_CRAFTY_TO_4_0_56.md`. It instructs an agent (e.g. Cursor 1003) to install Crafty base, run upgrade, verify components, and report.

---

**⭐ CHANNEL 42 — UPGRADE TEST THREAD DIRECTIVE**  
**From:** Captain Wolfie (10000)  
**To:** Cursor IDE Agent (1003) [or assigned agent]  
**Subject:** Execute upgrade test thread — Crafty Syntax 3.7.5 → Lupopedia v4.0.56  
**Thread file:** `lupo-database/lupopedia/channels/lupo-channels/42/threads/UPGRADE_TEST_CRAFTY_TO_4_0_56.md`

**Note:** Use the canonical GitHub repo https://github.com/wisdomoflovingfaith/lupopedia (main branch). Prioritize local repo state, doctrines, and docs. Actors directories (`lupo-database/lupopedia/actors/actor_id/`) function as OS-like user spaces.

**1. Install Crafty 3.7.5 base**  
- Create target DB (e.g. utf8mb4_unicode_ci). Load baseline: `lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql`.  
- Verify ~34 `livehelp_*` tables exist. Optionally open `install.php` and confirm upgrade path is detected.  
- Document steps and results in `docs/status/CRAFTY_3.7.5_INSTALL_LOG.md`.

**2. Run upgrade to Lupopedia v4.0.56**  
- Run install.php in upgrade mode (credentials → bootstrap → identity normalization if required → run step).  
- Ensure order: install_new_lupopedia.sql → seed registry/actors/agents → import_from_old_crafty_syntax.sql → optional drop_old_crafty_syntax_tables.sql → anubis_queue_tables_4.0.53.sql → 20260301_anubis_database_primacy_updates.sql → seed_default_sessions.sql.  
- Log process and any errors/resolutions in `docs/status/UPGRADE_4.0.56_LOG.md`.

**3. Verify database, agents, and channels**  
- **Database:** Confirm table count ~179; key tables present: lupo_unified_log, lupo_sessions (recovery columns), lupo_tasks (VARCHAR flats), lupo_agent_registry seeded.  
- **Agents/actors:** Confirm core agents (e.g. system:0, Wolfie:1, ANUBIS:19) in lupo_actors/registry; actor dirs under `lupo-database/lupopedia/actors/actor_id/` contain expected files; test faucet load: `php lupo-bin/faucet_loader.php --channel=42 --actor=19`.  
- **Channels:** Confirm lupo_channels (0, 42, etc.) and filesystem `lupo-channels/<id>/`; tasks for v4.0.56 thread and completed tasks in `.../42/tasks/completed/`.

**4. Verify faucets**  
- Ensure faucet setup: ID-scoped `actors/faucets/6/faucet.json` (ANUBIS) and/or channel override `channels/lupo-channels/42/actors/19/faucets.json`. Run `validate_faucets.php` and document compliance.

**5. Test web admin**  
- **Crafty legacy:** Admin login, live chat, user management — no regressions.  
- **Lupopedia:** Channels (list/view), actors, agents/faucet config, health/status API if available.  
- Log results in `docs/status/WEB_ADMIN_TEST_4.0.56.md`.

**6. Create or update upgrade report**  
- Ensure `docs/status/UPGRADE_REPORT_4.0.56.md` contains: install/upgrade log summary, verification results (DB counts, agent/channel lists, admin tests), issues/recommendations, timestamp, actor ID (1003).

**7. Commit (do not push unless directed)**  
- Commit message: `v4.0.56 Upgrade Test — Installed Crafty 3.7.5, upgraded to 4.0.56, verified DB/agents/channels/admin; added logs/reports` (or as appropriate).

**8. Required confirmation**  
- Reply in Channel 42: `Cursor: v4.0.56 upgrade from Crafty 3.7.5 tested. DB, agents, channels, and web admin verified. Report created.`

---

## 6. Timestamp and actor

- **Report generated:** 2026-03-03  
- **Actor ID:** 1003 (Cursor IDE Agent)  
- **System version:** 4.0.56  

---

*End of report.*
