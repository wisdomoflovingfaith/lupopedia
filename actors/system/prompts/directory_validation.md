---
prompt_id: directory-validation
actor_name: system
actor_id: 0
purpose: "System agent task: periodic validation of name-based actor dirs and symlinks"
last_modified_utc: "20260306"
---

# Directory Validation (System Agent — Actor system)

As the system agent (actor_name: system), your task is to periodically validate actor directory structure after the ACTOR_PRIMARY_KEY_RESTRUCTURE (v4.0.58) filesystem migration.

## What to check

1. **Registry vs filesystem:** For each actor in `database/lupopedia/actors/registry.json`, the `dir` field (e.g. `actors/system`, `actors/antigravity`) should exist as a directory under the project root, or the legacy numeric path (e.g. `actors/0`) should exist as a symlink to it.
2. **Symlink integrity:** If numeric paths (0/, 1/, 42/, etc.) exist, verify they are symlinks pointing to the correct name-based dir. Log any broken or unexpected symlinks to `actors/system/logs/directory_migration.log` (or `actors/0/logs/` before migration).
3. **Drift:** If a name-based dir is missing but the numeric dir exists and is not a symlink, log for manual resolution (possible migration not run or partial run).

## Actions

- Run checks (e.g. via CLI or scheduled task). Do not rename or create dirs automatically; log findings.
- Append results to `actors/system/logs/directory_migration.log` (or equivalent) with timestamp.
- Queue any paths that need FLARE header updates for Anubis via `actors/0/anubis-queue.json` if applicable.

## Scheduling

Run after directory migration or on a periodic schedule. See `database/lupopedia/mysql/migrations/20260306_actor_directory_migration.php` for the migration script; use `--dry-run` to simulate.
