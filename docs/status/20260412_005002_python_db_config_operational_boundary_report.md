---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/status/20260412_005002_python_db_config_operational_boundary_report.md
  web_path: https://www.lupopedia.com/lupopedia/docs/status/20260412_005002_python_db_config_operational_boundary_report.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: python-db-config-operational-boundary-20260412
  lupopedia.schema: documentation
  prd_cluster: null
  title: Python DB config operational boundary — session report (2026-04-12 00:50 UTC)
  summary: 'Troubles, observations, and learnings from hardening Python database configuration: fail-loud lupopedia-config.php resolution, removal of LUPO_DB_* and installer-style fallbacks.'
---
# Python DB config operational boundary — session report

**Packed UTC anchor:** `20260412005002` (from `python bin/tick.py` for this batch). **Human-visible:** **2026-04-12 00:50:02 UTC** (hour **00**).

## WHO / WHAT

| Role | Actor | Role in this work |
|------|-------|-------------------|
| Orchestrator | **WOLFIE** (actor_id **1**) | Directed fail-loud Python DB configuration and stricter installer vs tooling boundary. |
| Implementer | **Cursor IDE Agent** (actor_id **102**) | Edited `scripts/db_config.py`, `scripts/lib/db_connection.py`, dependent scripts under `scripts/` and `tools/`, and doctrine `PYTHON_DB_CONFIG_AND_SECRETS_4.0.99.md`; appended `docs/versions/4.0.98/CHANGELOG.md`. |

## WHERE it applies

- **Canonical resolver:** `scripts/db_config.py` (`resolve_lupopedia_config_path`, `load_db_config`, `get_table_prefix`, `LupopediaConfigError`).
- **Shared connection surface:** `scripts/lib/db_connection.py` (`get_connection_params`, `merge_connection_params_with_args`).
- **Callers:** DB-touching Python under `scripts/` and selected `tools/` (importers, validators, memory writer, edge queries, etc.).
- **Doctrine:** `docs/doctrine/PYTHON_DB_CONFIG_AND_SECRETS_4.0.99.md`.
- **Receipt:** `docs/versions/4.0.98/CHANGELOG.md` (session entry **2026-04-12 00:50 UTC**).

## Troubles encountered

1. **Shell ergonomics on Windows:** `cd /d … && python …` failed under **PowerShell** (`&&` not a valid separator in this environment). **Fix:** use `Set-Location …; python …` (or separate commands).
2. **`add_lupopedia_header_to_file.py` broken:** Invoking the header bootstrap script failed with **SyntaxError** — an import used a path with **hyphens** (`scripts.lib…`), which is not valid Python. **Impact:** this status file was **hand-authored** with a full LUPOPEDIA HEADERS envelope instead of generated. **Follow-up:** repair the import to a valid package path (for example `lupo_scripts.lib…` if the package layout supports it, or a relative import strategy documented in tooling).
3. **CI and automation assumptions:** Any job that previously injected credentials only via **`LUPO_DB_*`** without placing a resolvable **`lupopedia-config.php`** (or setting **`LUPOPEDIA_CONFIG`** / **`DOCUMENT_ROOT`** so resolution succeeds) will now **fail fast**. That is intentional but must be documented in pipeline READMEs.

## Observations

- **Surface area:** Many small scripts independently opened MySQL; consolidating on **`load_db_config()`** / **`get_connection_params()`** reduces duplicate “localhost + guess” patterns and makes policy changes one place to edit.
- **Table prefix:** Callers that need `lupo_` semantics should use **`get_table_prefix()`** from the same resolved PHP file, not ad hoc env reads, so prefix and credentials stay aligned with runtime PHP.
- **Empty password:** Allowed when the PHP file legitimately sets an empty DB password; “missing config” is about **resolution and parseability**, not forcing non-empty secrets in dev.

## What we learned

1. **Python is not the installer.** Absence of **`lupopedia-config.php`** during a **PHP/web** install flow must **not** be mirrored as a second credential path in Python (no parallel **`LUPO_DB_*`** tier for tooling).
2. **Fail loud beats silent guess.** Guessed hosts and database names hide mis-deployed trees and leak the illusion that tooling “works” when it is pointed at the wrong place.
3. **Documentation batch timing:** **`tick.py`** should run **once** per editing batch; **`echo_anchor_utc.py`** reuses the same packed UTC for multiple files — avoids drift between CHANGELOG and status headers.
4. **Tooling debt blocks automation:** A broken header script blocks scalable compliance; fixing it is higher leverage than hand-fixing each new file.

This output complies with Lupopedia Constitutional Root Rules.
