---
# LUPOPEDIA Header — see http://www.lupopedia.com/status/kiro_review
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "review"
  file_path_from_root: "docs/status/kiro_review.md"
  web_path: "http://www.lupopedia.com/status/kiro_review"
  last_modified_utc: "20260310"
  system_version: "4.0.68"
  channel_id: 42
  actor_id: 1001
  actor_name: "kiro"
  delegation_chain: "kiro:antigravity:lilith:cursor:captain"
  artifact_type: "review"
  artifact_kind: "findings"
  purpose: "KIRO JetBrains review of Cursor's v4.0.68 implementation"
  mood_rgb: "4169E1"

lupopedia.footer:
  version: "4.0.68"
  last_verified: "20260310"
  last_verified_by: "kiro"
---
Review Content
# KIRO Review — Lupopedia v4.0.68

Reviewer: KIRO  
Date: 20260310  
Implementation by: Cursor

Executive Summary
The 4.0.68 implementation is partially complete: core files and major structures (rules tables, skills files, paths/visits schema, rule engine classes) are present and mostly doctrine-aligned at DDL level.  
However, multiple high-impact gaps were verified: installer/seed drift vs changelog claims, rule-evaluation runtime gaps, and false-positive logic in `information_schema` enforcement.  
As implemented, some advertised 4.0.68 behavior is not actually activated end-to-end without additional fixes.

---

Detailed Review

### Rules system (tables, seed, engine, CLI)

Files reviewed:
- CHANGELOG.md
- database/migrations/20260310_create_rules_tables.sql
- lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
- lupo-database/lupopedia/mysql/seed/seed_rules_doctrine_4.0.68.sql
- lupo-includes/classes/RuleEngine.php
- lupo-includes/classes/RuleEvaluator.php
- lupo-bin/lupo.php

Database Integrity
- Verified `lupo_rules`, `lupo_rule_targets`, `lupo_rule_logs` exist in migration/install SQL.
- Verified no foreign keys/triggers in DDL for these tables.
- Verified BIGINT timestamp columns are used.
- Verified explicit `rule_target_id` seed inserts (1-6) in seed file.

Performance
- `RuleEngine::checkInformationSchemaViolations()` recursively scans all PHP files under `lupo-includes` on evaluation; expensive for frequent CLI/runtime checks ([lupo-includes/classes/RuleEngine.php:148](/C:/ServBay/www/servbay/lupopedia/lupo-includes/classes/RuleEngine.php:148)).

Stability
- `lupo.php rules --evaluate` uses `RuleEngine`, not `RuleEvaluator`, so schema checks and dedicated information-schema checks advertised in changelog are not wired into CLI path ([lupo-bin/lupo.php:429](/C:/ServBay/www/servbay/lupopedia/lupo-bin/lupo.php:429), [lupo-includes/classes/RuleEvaluator.php:53](/C:/ServBay/www/servbay/lupopedia/lupo-includes/classes/RuleEvaluator.php:53)).
- `RuleEvaluator` is present but no runtime call site was found via repository search.

Issues found
- High: Runtime/CLI does not execute `RuleEvaluator` schema pipeline as documented.
- High: `checkInformationSchemaViolations()` flags its own validator files because they contain the literal forbidden patterns in comments/strings; this makes the rule self-failing by design ([lupo-includes/classes/RuleEngine.php:141](/C:/ServBay/www/servbay/lupopedia/lupo-includes/classes/RuleEngine.php:141), [lupo-includes/classes/RuleEngine.php:153](/C:/ServBay/www/servbay/lupopedia/lupo-includes/classes/RuleEngine.php:153)).
- Medium: `lupo_rule_logs` lacks soft-delete columns despite global soft-delete doctrine ([database/migrations/20260310_create_rules_tables.sql:42](/C:/ServBay/www/servbay/lupopedia/database/migrations/20260310_create_rules_tables.sql:42), [lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql:3154](/C:/ServBay/www/servbay/lupopedia/lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql:3154)).

Recommendations
- Route CLI `rules --evaluate` through `RuleEvaluator` for database target handling.
- Exclude known validator files, comments, or rule-definition files from forbidden pattern scan.
- Add `is_deleted/deleted_ymdhis` to `lupo_rule_logs` or document explicit exception.

### Skills system (service, files, metadata)

Files reviewed:
- lupo-includes/classes/SkillService.php
- lupo-bin/lupo.php
- lupo-skills/README.md
- lupo-skills/lupopedia-headers/README.md
- lupo-skills/uploads/README.md
- lupo-channels/42/content/federation_node_id/0/SKILLS.md
- lupo-database/lupopedia/mysql/seed/seed_skills_4.0.68.sql

Database Integrity
- Skills metadata seed exists and uses explicit metadata IDs (10201-10205).
- BIGINT timestamp style present in seed (`@now` integer format).

Performance
- `SkillService` repeatedly reads/regex-parses markdown files per actor cache miss; acceptable now but may become expensive at larger actor counts ([lupo-includes/classes/SkillService.php:102](/C:/ServBay/www/servbay/lupopedia/lupo-includes/classes/SkillService.php:102), [lupo-includes/classes/SkillService.php:118](/C:/ServBay/www/servbay/lupopedia/lupo-includes/classes/SkillService.php:118)).

Stability
- `actorIdToSlug()` uses a static inline map, conflicting with actor-registry-as-canonical doctrine and risking drift ([lupo-includes/classes/SkillService.php:65](/C:/ServBay/www/servbay/lupopedia/lupo-includes/classes/SkillService.php:65)).

Issues found
- Medium: Inline actor ID map should resolve from registry, not hardcoded list.

Recommendations
- Replace static slug map with registry-backed resolution.

### Paths and visits consolidation

Files reviewed:
- database/migrations/20260310_paths_visits_doctrine.sql
- lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
- lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql

Database Integrity
- Verified new `lupo_visits` and `lupo_paths` schemas in migration and install SQL.
- Verified no foreign keys/triggers in these sections.
- Verified index coverage exists on session/actor/created/is_processed/is_deleted and enter/exit columns.

Performance
- Indexes are present for expected filter columns in both tables.

Stability
- Import mappings for daily/monthly legacy visits/paths are explicit and consistent with new column layout.

Issues found
- No blocking issue found in these specific 4.0.68 DDL/import sections.

Recommendations
- Consider adding composite indexes for high-frequency query combinations once real production query patterns are measured.

### TOON-based validation and no-information-schema rule

Files reviewed:
- lupo-includes/classes/ToonValidator.php
- lupo-includes/classes/RuleEvaluator.php
- lupo-channels/42/content/federation_node_id/0/NO_INFORMATION_SCHEMA_RULE.md
- lupo-database/lupopedia/mysql/seed/seed_rules_doctrine_4.0.68.sql

Database Integrity
- Rule 1002 exists and is attached to channel 42 in seed.

Performance
- `SHOW TRIGGERS` and per-table `SHOW CREATE TABLE` scale with table count; acceptable for diagnostic mode, not ideal for high-frequency runtime path.

Stability
- `checkAutoIncrement()` marks all AUTO_INCREMENT as violations, which conflicts with the documented nuance (registry-backed tables only) and will over-report ([lupo-includes/classes/ToonValidator.php:138](/C:/ServBay/www/servbay/lupopedia/lupo-includes/classes/ToonValidator.php:138)).
- Trigger count is global and copied per table, so one trigger can mark every table result as trigger-violating ([lupo-includes/classes/ToonValidator.php:152](/C:/ServBay/www/servbay/lupopedia/lupo-includes/classes/ToonValidator.php:152), [lupo-includes/classes/ToonValidator.php:159](/C:/ServBay/www/servbay/lupopedia/lupo-includes/classes/ToonValidator.php:159)).

Issues found
- High: Validator logic can produce broad false positives for auto-increment and trigger reporting.

Recommendations
- Scope AUTO_INCREMENT validation to registry-backed tables only.
- Report triggers as global DB finding, not duplicated per table.

### Metadata and seed consistency

Files reviewed:
- CHANGELOG.md
- lupo-database/lupopedia/mysql/seed/seed_lupo_metadata_changelog_headers_4.0.68.sql
- install.php

Database Integrity
- Seed file exists for changelog header metadata.

Performance
- N/A

Stability
- Seed content does not match current changelog header values: still seeds old values (e.g., `system_version 4.0.67`, actor `1006`, legacy purpose/traits) ([seed_lupo_metadata_changelog_headers_4.0.68.sql:25](/C:/ServBay/www/servbay/lupopedia/lupo-database/lupopedia/mysql/seed/seed_lupo_metadata_changelog_headers_4.0.68.sql:25), [seed_lupo_metadata_changelog_headers_4.0.68.sql:29](/C:/ServBay/www/servbay/lupopedia/lupo-database/lupopedia/mysql/seed/seed_lupo_metadata_changelog_headers_4.0.68.sql:29), [CHANGELOG.md:67](/C:/ServBay/www/servbay/lupopedia/CHANGELOG.md:67)).
- Installer does not execute 4.0.68 seed files (`seed_rules_doctrine_4.0.68.sql`, `seed_skills_4.0.68.sql`, `seed_lupo_metadata_changelog_headers_4.0.68.sql`, `seed_actor_1_cursor_rules_4.0.68.sql`) in new or upgrade flow; it mainly runs older 4.0.45/4.0.57 seeds ([install.php:567](/C:/ServBay/www/servbay/lupopedia/install.php:567), [install.php:568](/C:/ServBay/www/servbay/lupopedia/install.php:568), [install.php:652](/C:/ServBay/www/servbay/lupopedia/install.php:652)).

Issues found
- High: Changelog claims and seeded metadata/install behavior are out of sync.

Recommendations
- Update/regen changelog-header seed content to current 4.0.68 values.
- Add explicit installer execution for required 4.0.68 seed files.

### Install path verification (fresh, Crafty upgrade, 4.0.67 upgrade)

Files reviewed:
- install.php
- lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
- lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql
- CHANGELOG.md

Database Integrity
- Fresh install and Crafty upgrade flows are coded and reachable.

Performance
- Install flow is SQL-file driven; acceptable for one-time operation.

Stability
- `install.php` explicitly states only two valid states: new install or upgrade from Crafty 3.7.5; no Lupopedia->Lupopedia upgrade ([install.php:62](/C:/ServBay/www/servbay/lupopedia/install.php:62), [install.php:63](/C:/ServBay/www/servbay/lupopedia/install.php:63)).

Issues found
- Medium: Scenario “upgrade from 4.0.67 -> 4.0.68” is not implemented/supported by design, contrary to requested verification matrix requirement.

Recommendations
- Either document this as intentional “not supported” in review matrix, or implement explicit 4.0.x-to-4.0.x upgrade path.

Database Integrity Summary
Table | FKs | Timestamp | Triggers | Indexes
--- | --- | --- | --- | ---
`lupo_rules` | None | BIGINT `*_ymdhis` | None | Present
`lupo_rule_targets` | None | BIGINT `*_ymdhis` | None | Present
`lupo_rule_logs` | None | BIGINT `created_ymdhis` | None | Present
`lupo_paths` | None | BIGINT `created_ymdhis/updated_ymdhis` | None | Present
`lupo_visits` | None | BIGINT `created_ymdhis` | None | Present

Performance Findings

| Concern | Location | Impact | Recommendation |
| --- | --- | --- | --- |
| Recursive full-file scan for forbidden patterns | RuleEngine::checkInformationSchemaViolations | Medium runtime overhead | Cache results, narrow scan scope, run only in explicit audit mode |
| DB ID generation via `MAX()+1` | ChannelService `postMessage()` | Race risk and lock contention under concurrency | Use registry allocator or atomic ID strategy |
| Repeated filesystem reads for task state resolution | TaskService `getTaskData()` | Increased I/O at scale | Cache task status/path lookup or keep canonical DB-first read path |

Stability Findings

| Concern | Location | Impact | Recommendation |
| --- | --- | --- | --- |
| RuleEvaluator not wired into CLI | `lupo-bin/lupo.php` rules command | Schema checks not executed as advertised | Invoke RuleEvaluator for `rules --evaluate` |
| Self-violating information_schema scan | RuleEngine scanner | False failures | Exclude validator/rule files or tokenize SQL-only content |
| Over-broad AUTO_INCREMENT violation logic | ToonValidator | False positives across many valid tables | Restrict check to registry-backed tables |
| Global trigger count copied per table | ToonValidator | Misleading per-table violations | Report trigger finding at DB-level only |
| Installer seed drift from 4.0.68 claims | install.php + seed files | Features absent after install | Add missing 4.0.68 seeds to install path |

Summary of Recommendations

| Priority | Issue | Location | Fix |
| --- | --- | --- | --- |
| P0 | 4.0.68 seeds not executed in installer | install.php | Add explicit `runSqlFile` for rules/skills/changelog/cursor-rule seeds |
| P0 | Rule evaluation path incomplete | lupo-bin/lupo.php + RuleEvaluator | Use RuleEvaluator for CLI evaluate and database checks |
| P1 | False-positive information_schema enforcement | RuleEngine.php | Exclude self-referential files and non-query text |
| P1 | Validator logic over-reports violations | ToonValidator.php | Scope auto-increment and trigger logic correctly |
| P2 | Static actor map drift risk | SkillService.php | Resolve actor slugs from canonical registry |

Doctrine Compliance
Doctrine | Status
--- | ---
No FKs | PASS in reviewed 4.0.68 tables
BIGINT timestamps | PASS in reviewed 4.0.68 tables
No triggers | PASS in reviewed DDL; validator logic needs refinement
Explicit inserts | PASS in reviewed seed files
Registry IDs | PARTIAL (explicit IDs used in seeds; other services still use time/rand or MAX+1 patterns)

Positive Observations

What Cursor implemented well
- Core 4.0.68 artifacts are present and organized (rules/skills files, channel docs, migrations, service classes).
- Rules/paths/visits DDL sections include practical indexes and use BIGINT timestamp pattern consistently.
- Crafty import mapping for paths/visits is explicit and readable.
- Rule seed uses explicit `rule_target_id` values, matching changelog claim.

Next Steps

1. Cursor addresses critical issues (installer seed execution + RuleEvaluator wiring + false-positive rule logic).
2. Re-review after fixes.
3. Update migrations if required.
