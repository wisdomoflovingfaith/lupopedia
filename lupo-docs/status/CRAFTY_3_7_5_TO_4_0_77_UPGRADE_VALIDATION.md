---
lupopedia.init:
  required_reading:
    - path: "lupo-docs/doctrine/UPGRADE_POLICY_DOCTRINE.md"
      reason: "4.0.x upgrade policy: fresh install or Crafty 3.7.5 only"

lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "documentation"
  system_version: "4.0.77"
  file_path_from_root: "lupo-docs/status/CRAFTY_3_7_5_TO_4_0_77_UPGRADE_VALIDATION.md"
  web_path: "[web_path](http://www.lupopedia.com/status/CRAFTY_3_7_5_TO_4_0_77_UPGRADE_VALIDATION)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "status"
  artifact_kind: "validation_report"
  purpose: "Status of Crafty Syntax 3.7.5 → Lupopedia 4.0.77 upgrade path validation"
  tags: ["upgrade", "validation", "4.0.77", "crafty_syntax"]

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Re-run validation after future install SQL or seed changes; update this artifact with results"
---
# file: Crafty 3.7.5 → 4.0.77 Upgrade Validation — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/status/CRAFTY_3_7_5_TO_4_0_77_UPGRADE_VALIDATION

# Crafty Syntax 3.7.5 → Lupopedia 4.0.77 Upgrade Validation

## Status

**Validation:** **Performed (2026-03-16).**

Manual run: dropped all tables, loaded Crafty Syntax 3.7.5 baseline, ran Lupopedia install, regenerated TOONs with `python lupo-scripts/generate_toon_files.py`. **161 tables** produced; upgrade path to 4.0.77 confirmed. TABLE_COUNT_DOCTRINE and README updated to 161. See CHANGELOG.md § "Upgrade validation run and table count (4.0.77)".

## Intended procedure (4.0.x policy)

Per [UPGRADE_POLICY_DOCTRINE.md](../doctrine/UPGRADE_POLICY_DOCTRINE.md), the only supported upgrade path for 4.0.x is:

1. **Crafty Syntax 3.7.5 baseline** — Load original Crafty 3.7.5 schema and data (e.g. from `old_crafty_syntax_3_7_5_start.sql` or equivalent).
2. **Run Lupopedia install** — Execute `install.php` (or equivalent) to apply Lupopedia 4.0.77 schema and seed.
3. **Validate** — Confirm tables exist, key data present, no fatal errors.

There is **no** Lupopedia→Lupopedia upgrade (e.g. 4.0.76 → 4.0.77); that path is not supported until 4.1.0.

## When validation is run — evidence to record

- **Environment:** PHP version, MySQL/MariaDB version, OS.
- **Exact steps:** Commands run (e.g. drop tables, load Crafty baseline, run install.php).
- **Output:** Full or summarized command output; any errors and remediation.
- **Database state:** e.g. `SHOW TABLES`; `SELECT COUNT(*)` from key tables if relevant.
- **Timestamp:** When the run was performed.
- **Actor:** Who (or which agent) ran it.

## References

- [UPGRADE_POLICY_DOCTRINE.md](../doctrine/UPGRADE_POLICY_DOCTRINE.md)
- [CHANGELOG.md](../../CHANGELOG.md) (4.0.77 section)
- Install SQL: `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
