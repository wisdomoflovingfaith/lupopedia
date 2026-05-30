---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/PYTHON_DB_CONFIG_AND_SECRETS_4.0.99.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/PYTHON_DB_CONFIG_AND_SECRETS_4.0.99.md"
  status: "active"
  when_updated: "20260412004702"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/python-db-config-secrets-4099.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/python-db-config-secrets"
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: "Python DB config and secrets discipline (4.0.99)"
  summary: "Python tooling requires resolved lupopedia-config.php; no LUPO_DB_* bypass; no installer-style fallbacks; operational vs PHP install boundary."
---
# Python DB config and secrets discipline (4.0.99)

**In Lupopedia, missing config is a hard failure, not a reason to guess.**

## Installer vs Python (binding)

The **absence** of **lupopedia-config.php** may be **valid only** during the **PHP / web installation** flow. That exception **does not** apply to **Python**.

Python under **`scripts/`**, **`tools/`**, and **`bin/`** is **operational tooling**, not installer authority. If **lupopedia-config.php** is **missing** or **cannot be safely resolved**, Python **must fail loudly and stop**. Do **not** add installer-style fallback behavior (no parallel env credential store, no “continue without config”).

## Purpose

Lupopedia stores **database credentials** in **lupopedia-config.php** (prefer above docroot). **`.py` files may be web-readable** on misconfigured hosts; they must not embed passwords or become a second secret store.

## Fail-loud rule

- **`scripts/db_config.py`** — **`load_db_config()`** / **`resolve_lupopedia_config_path()`** raise **`LupopediaConfigError`** if the config file is missing or required keys are absent. **No** credential fallbacks.
- **`scripts/lib/db_connection.py`** — **`get_connection_params()`** is **`load_db_config()`** only. **No** **`LUPO_DB_*`** tier for Python.
- **`merge_connection_params_with_args(args)`** — merges canonical file-derived params with non-`None` CLI overrides only; empty user/database after merge is an error.

## Canonical Python configuration (authoritative)

| Layer | Location | Role |
|-------|----------|------|
| **Resolver + parser** | **`scripts/db_config.py`** | **`resolve_lupopedia_config_path()`**, **`load_db_config()`**, **`get_table_prefix()`**. Env **`LUPOPEDIA_CONFIG`**, **`DOCUMENT_ROOT`** / **`LUPOPEDIA_DOCUMENT_ROOT`**. |
| **Thin wrapper** | **`scripts/lib/db_connection.py`** | **`get_connection_params()`** → **`load_db_config()`**; **`merge_connection_params_with_args()`** for importer CLIs. |
| **CLI overrides** | Importers / exporters | **`--host`**, **`--user`**, etc. default to **`None`**; values still originate from the resolved PHP config unless explicitly overridden on the command line. |

**API tokens for HTTP** (e.g. **`bin/transcript.py`**) — operator-supplied; not a substitute for **`lupopedia-config.php`** for MySQL tooling.

## Audit summary (2026-04-12)

Historical list of files remediated for hardcoded secrets and non-canonical loaders remains in git history; current rule: **resolved PHP config file only** for DB parameters and table prefix in Python (except explicit CLI overrides for host/user/password/database/port on top of that file).

### NEEDS REVIEW / follow-ups

- **`scripts/migrate_filesystem_to_db.py`** — **`migrate_channel_files`** had a **pre-existing** truncated body; restored by mirroring **`migrate_agent_files`**. Unrelated to secrets.
- **Machine-local paths** in some legacy scripts — **not secrets**; portability is separate.

## Rules for future Python

1. **Never** commit `password='...'`, API keys, or tokens in `.py`.
2. **Use** `from lib.db_connection import get_connection_params` or `from db_config import load_db_config` — both require a **resolved lupopedia-config.php**.
3. **Never** add **`LUPO_DB_*`** (or similar) env tiers for Python DB credentials.
4. **Never** add silent localhost / empty-user DB defaults on config miss.
5. **Document** **`LUPOPEDIA_CONFIG`** / **`DOCUMENT_ROOT`** next to the script entrypoint when operators run off-server layouts.
6. Treat **web-served** `.py` as public; see **`lupopedia_quick_reference.md`**.

## Cross-references

- **`lupopedia_quick_reference.md`** — Webroot execution model; secrets boundary.
- **`scripts/db_config.py`** — Resolver and **`load_db_config()`**.
- **`scripts/lib/db_connection.py`** — **`get_connection_params()`**, **`merge_connection_params_with_args()`**.
