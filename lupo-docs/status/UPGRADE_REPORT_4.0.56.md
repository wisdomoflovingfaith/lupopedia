---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/status/UPGRADE_REPORT_4.0.56.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "report"
  purpose: "Upgrade test report: Crafty 3.7.5 → Lupopedia v4.0.56"
  traits: ["upgrade", "verification", "cursor"]
  tags: ["upgrade", "4.0.56", "cursor", "verification"]
  lupo_agent: "cursor"
---

# Upgrade Report — v4.0.56

**Date:** 2026-03-03  
**Actor:** Cursor (1003)  
**Purpose:** Summarize install/upgrade procedure, verification steps, and test results for upgrading from Crafty Syntax 3.7.5 to Lupopedia v4.0.56.

---

## 1. Install / upgrade logs summary

| Log | Path | Purpose |
|-----|------|---------|
| Crafty 3.7.5 base install | `docs/status/CRAFTY_3.7.5_INSTALL_LOG.md` | Steps to load baseline `old_crafty_syntax_3_7_5_start.sql`, verify livehelp_* tables, optional web check. |
| Upgrade to 4.0.56 | `docs/status/UPGRADE_4.0.56_LOG.md` | install.php upgrade flow: bootstrap (install + seed), identity normalization, import, migrations, seed_default_sessions; order and paths documented. |
| Web admin test | `docs/status/WEB_ADMIN_TEST_4.0.56.md` | Crafty legacy admin and Lupopedia admin (channels, actors, agents, health API) test checklist. |

**Procedure:** Run Crafty baseline SQL → open install.php (upgrade detected) → complete identity normalization if required → run upgrade → verify DB and agents → run web admin tests. Fill log tables when executing locally.

---

## 2. Verification results

### 2.1 Database

- **Table count:** Post-optimization target ~179 tables. Verify with:  
  `SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE();`
- **Key tables present:**
  - `lupo_unified_log`
  - `lupo_sessions` (with recovery columns, e.g. `recovery_attempts`, `recovery_data`)
  - `lupo_tasks` (with VARCHAR flats for task_type, task_status, task_priority)
  - `lupo_agent_registry`, `lupo_actors`, `lupo_channels`
- **Seeded data:** e.g. `SELECT * FROM lupo_agent_registry LIMIT 5;` — expect seeded agents/channels.

| Check | Result (fill when run) |
|-------|------------------------|
| Table count ~179 | |
| lupo_unified_log exists | |
| lupo_sessions recovery columns | |
| lupo_tasks VARCHAR flats | |
| lupo_agent_registry seeded | |

### 2.2 Agents / actors

- **Core agents:** ANUBIS (actor_id 19), LILITH (e.g. 2) — seeded via `seed_actors_agents_4.0.45.sql` and registry seeds.
- **Actors directories:** `lupo-database/lupopedia/actors/actor_id/<id>/` — OS-like user spaces (programs, collections). Verify expected files (e.g. identity.json, session.json, README.md for priority actors).
- **Faucet loading:** From repo root (or with LUPOPEDIA_PATH set):  
  `php lupo-bin/faucet_loader.php --channel=42 --actor=19`  
  Expected: load ANUBIS faucet (id-scoped `actors/faucets/6/faucet.json` or channel override).

| Check | Result (fill when run) |
|-------|------------------------|
| ANUBIS (19) in lupo_actors / registry | |
| Actors dirs present (e.g. 0, 1, 19, 1000, 10000) | |
| Faucet loader (channel=42, actor=19) | |

### 2.3 Channels

- **DB:** `lupo_channels` — key channels 0 (system), 42 (development).
- **Filesystem:** `lupo-database/lupopedia/channels/lupo-channels/<id>/` — content, actors, tasks.
- **Tasks:** v4.0.56 thread tasks in `lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/`; completed tasks in `.../42/tasks/completed/`.

| Check | Result (fill when run) |
|-------|------------------------|
| lupo_channels: 0, 42 | |
| lupo-channels/0, lupo-channels/42 dirs | |
| DEVELOPMENT_CYCLE_4_0_56 tasks migrated | |

---

## 3. Web admin test results

See `docs/status/WEB_ADMIN_TEST_4.0.56.md`. Summarize here when run:

| Area | Pass (Y/N) | Notes |
|------|------------|-------|
| Crafty legacy admin (live chat, users) | | |
| Lupopedia channels / actors / agents | | |
| Health or status API | | |

---

## 4. Issues and recommendations

- **wolfie_orms.py:** `select_one_from_lupo_agent_faucets` updated to current schema in 4.0.56 (actor_id, name, slug, etc.); no drift if that change is in place.
- **Faucet loader:** Requires LUPOPEDIA_PATH or LUPO_DATABASE_DIR when run from CLI; config must be loaded for DB-backed agent_faucet_id resolution.
- **Run locally:** Install/upgrade and verification steps require a live DB and web server; execute on ServBay/local environment and fill log/report tables.

---

## 5. Timestamp and actor

- **Report generated:** 2026-03-03  
- **Actor ID:** 1003 (Cursor IDE Agent)  
- **System version:** 4.0.56  

---

*End of report.*
