---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "docs/DOCTOR_HEALTH_CHECK.md"
  web_path: "http://www.lupopedia.com/docs/DOCTOR_HEALTH_CHECK"
  last_modified_utc: "20260307"
  system_version: "4.0.62"
  channel_id: 42
  artifact_type: "documentation"
  artifact_kind: "reference"
  purpose: "Reference for lupo doctor (lupo_doctor_health_check) and related health checks"
  traits: ["doctor", "health", "cli", "v4.0.62"]
  tags: ["doctor", "health", "cli", "reference"]
---

# DOCTOR health check — full reference

This document describes the **`lupo doctor`** command and the **`lupo_doctor_health_check()`** function: what they check, how to run them, and how they relate to the DOCTOR actor and `doctor-context`.

---

## 1. Overview

**Command:** `php lupo-bin/lupo.php doctor` [options]

**Behavior:** When the DOCTOR actor scripts are present (`lupo-agents/1009/doctor.php`), the CLI runs that script. Otherwise it runs the built-in **`lupo_doctor_health_check()`** function.

**Purpose:** Quick system health: database, registry file, session file, and (when ContextKernel exists) context/identity drift. Optional actor consistency check with `--check-actors`.

---

## 2. What gets checked

| Check | Description | Path / source |
|-------|-------------|----------------|
| **Database** | Connection alive | `$db->fetchRow('SELECT 1')` |
| **Registry** | File exists and is readable | `{LUPO_DATABASE_DIR}/lupopedia/actors/registry.json` (or `actor_id/registry.json` in some setups) |
| **Session file** | Exists and readable | `{LUPO_DATABASE_DIR}/session.md` |
| **Context kernel** | No identity drift | `ContextKernel::validate()` (split-brain, pairing issues) |
| **Actors (optional)** | Workspace/namespace consistency | With `--check-actors`, `DoctorService::checkActors()` (DB table `lupo_actors`) |

---

## 3. Invocation

```bash
# Basic health check
php lupo-bin/lupo.php doctor

# With optional actor consistency check (workspace paths, namespaces)
php lupo-bin/lupo.php doctor --check-actors
```

**Parameters (internal):** `lupo_doctor_health_check($abspath, $db, $table_prefix = '', $state_file = '')`. The CLI passes `ABSPATH`, the global DB connection, `LUPO_TABLE_PREFIX` (or `lupo_`), and the path to `.lupo_actor`. Defaults are applied when `table_prefix` or `state_file` are omitted.

---

## 4. Output format

- **Header:** `Lupopedia doctor — health check`
- **Separator:** `--------------------------------------------------`
- **Lines:** `[OK]`, `[WARN]`, `[FAIL]`, or `[SKIP]` plus a short message.
- **Context kernel issues:** If any are found, each is listed with `*` and a follow-up line suggests: `Run: php lupo-bin/lupo.php doctor-context [--repair]`
- **Summary:** `Summary: N system(s) OK[, M issue(s)].`

Exit code is not currently set by the function; the script exits 0.

---

## 5. Paths and config

| Item | Default / source |
|------|-------------------|
| **LUPO_DATABASE_DIR** | `lupo-database` (from config or bootstrap) |
| **Registry** | `$abspath . $db_dir . '/lupopedia/actors/registry.json'` |
| **Session file** | `$abspath . $db_dir . '/session.md'` |
| **State file** | `$abspath . '.lupo_actor'` |
| **Table prefix** | `LUPO_TABLE_PREFIX` or `lupo_` |

If your registry lives under `lupopedia/actors/actor_id/registry.json`, ensure that path is used or that a symlink/copy exists where the health check looks.

---

## 6. Relation to DOCTOR actor and doctor-context

| Command | Role |
|---------|------|
| **`lupo doctor`** | High-level health: DB, registry, session file, kernel validation; optional `--check-actors`. No repair. |
| **`lupo doctor-context`** | Identity stack only: session file, DB session, registry, resolved context; surfaces drift. |
| **`lupo doctor-context --repair`** | Same as above, plus overwrites `session.md` with kernel/DB when conflict or drift is detected. |

When `lupo-agents/1009/doctor.php` exists, `lupo doctor` is handled by the DOCTOR actor script instead of `lupo_doctor_health_check()`. The actor can use `DoctorService` and may produce different output; the **checks** above describe the built-in fallback.

---

## 7. When to use

- **First-time setup / after install:** Run `lupo doctor` to confirm DB, registry, and session file.
- **After editing session.md or switching identity:** Run `lupo doctor` then `lupo doctor-context` to confirm no drift.
- **When whoami/context look wrong:** Run `lupo doctor`; if context kernel reports issues, run `lupo doctor-context [--repair]`.
- **Actor/workspace checks:** Run `lupo doctor --check-actors` when debugging actor directories or namespaces.

---

## 8. Troubleshooting

| Symptom | Action |
|---------|--------|
| `[FAIL] Database` | Check DB credentials in config; ensure MySQL/MariaDB is running. |
| `[WARN] Registry missing` | Create or fix path to registry JSON; ensure `lupopedia/actors/` (or `actor_id/`) layout matches. |
| `[WARN] Session file missing` | Optional for CLI; create `session.md` under LUPO_DATABASE_DIR if you want file-based fallback. |
| `[WARN] Context kernel: N issue(s)` | Run `lupo doctor-context` for details; use `--repair` to sync `session.md` to kernel/DB. |
| `[FAIL] Actor: ...` (with `--check-actors`) | Fix `lupo_actors` workspace_path or php_namespace, or create missing workspace directories. |

---

## 9. Related docs

- [CLI.md](CLI.md) — doctor and doctor-context commands
- [HELP.md](HELP.md) — Documentation hub
- [TASK_STATUS_REFERENCE.md](TASK_STATUS_REFERENCE.md) — Task statuses and paths
- [prompts/lilith/20260306_doctor_sql_queries.md](../prompts/lilith/20260306_doctor_sql_queries.md) — SQL for the same data DOCTOR uses
- [lupo-agents/1009/](../lupo-agents/1009/) — DOCTOR actor (1009) when used for `lupo doctor`
