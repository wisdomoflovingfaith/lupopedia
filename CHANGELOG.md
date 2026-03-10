---
flame.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

flare.conditional:
  guards:
    execution_mode: "required"
    allow:
      actor_ids: [0]
      agent_names: ["system"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-10T00:00:00Z"
    conditions:
      - type: env_var_equals
        key: "LUPO_ENV"
        value: "prod"
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents", "maintainers"]
    what:
      artifact_type: "prompt"
      objective: "Patch flare_validate to enforce flame blocks for targeted artifact_kind"
    where:
      repo_paths:
        - "lupo-tools/flare_validate.py"
        - "lupo-tools/flare_apply.py"
      runtime_scope: "cli"
      channels:
        primary_channel_id: 42
        report_channel_id: 0
    when:
      urgency: "high"
      effective_utc: "2026-03-04T06:00:00Z"
      expires_utc: "2026-03-11T06:00:00Z"
    why:
      rationale: "Prevent unsafe agent execution and avoid legacy migration overhead"
      risks:
        - "Validator drift across agents"
        - "Channel 0 flooding if actor routing defaults wrong"
    how:
      method: "Implement schema + validator enforcement + template update"
      success_criteria:
        - "flare_validate enforces canonical order and targeted mandatory rule"
        - "flare_apply emits typed action envelopes"

flare.headers:
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "CHANGELOG.md"
  file_hash: "to_be_generated"
  system_version: "4.0.68"
  channel_id: 1
  last_modified_utc: "20260310"
  delegation_chain: "antigravity:cursor:captain"
  arity: "high"
  artifact_type: "changelog"
  artifact_kind: "history"
  purpose: "Canonical version history for Lupopedia with LUPOPEDIA HEADERS protocol; rules, skills, paths/visits, TOON-based validation, Cursor rules for actor 1, single-install doctrine, and FLIP/FLARE replaced by LUPOPEDIA HEADERS."
  dialog_message: "Version 4.0.68: Rules and skills systems; paths/visits; no information_schema (TOON/SHOW only); Cursor rules in lupo-rules/cursor and actor 1 metadata; single-install no 4.0 upgrade doctrine; LUPOPEDIA HEADERS doc updates; project root atom."
  mood_rgb: "4169E1"
  traits: ["canonical", "comprehensive", "v4.0.68"]
  tags: ["changelog", "versions", "releases", "history", "lupopedia-headers", "federation"]

flare.edges:
  outbound_edges:
    - { to: "docs/AGENT_INVENTORY.md", type: "references", weight: 0.8 }
    - { to: "docs/doctrine/AGENT_REGISTRY_DOCTRINE.md", type: "references", weight: 0.7 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md", type: "references", weight: 0.9 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "references", weight: 0.7 }
    - { to: "docs/database/lupopedia/tables/lupo_channel_content.md", type: "references", weight: 0.7 }
    - { to: "tools/flare_header_template.txt", type: "references", weight: 0.8 }
    - { to: "CHANGELOG_ARCHIVE.md", type: "references", weight: 0.9, reason: "Historical changelog entries for versions 4.0.67 and earlier" }
    - { to: "docs/status/CHANNEL_42_LEAD_REVIEW_4_0_55.md", type: "references", weight: 0.8 }
  semantic_tags: ["changelog", "versions", "releases", "history", "flare", "federation"]

flare.footer:
  last_verified: "20260310"
  last_verified_by: "wolfie"
  archive_note: "For historical changelog entries from version 4.0.67 and earlier, see CHANGELOG_ARCHIVE.md"

flame.see:
  mappings:
    - ["CHANGELOG.md", "http://www.lupopedia.com/CHANGELOG"]

flame.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---
# file: Lupopedia CHANGELOG — session: L-LUPO-ANTIGRAVITY — delegation: antigravity:cursor:captain — web_path: http://www.lupopedia.com/CHANGELOG
 

This document tracks version history, focusing on key changes, task migrations, and optimizations. Entries are in reverse chronological order.

**Archive Note:** For historical changelog entries from version 4.0.67 and earlier, see [CHANGELOG_ARCHIVE.md](CHANGELOG_ARCHIVE.md).

## [4.0.68] — Channels Web Interface, Rules, Skills, Uploads (2026-03-10)

**Theme:** Channels web interface hardening; rules system; skills system with uploads skill; LUPOPEDIA header format; metadata seeds.

### Summary

- **Channels Web Interface:** Active development of the web interface for channels management (`http://domainname.com/<lupopedia-sub-folder>/channels/`). This version cycle focuses on completing and hardening the channels web UI and aligning it with doctrine/schema.
- **Rules system:** `lupo_rules`, `lupo_rule_targets`, `lupo_rule_logs`; RuleEngine and RuleEvaluator; CLI and Channel 42 RULES.
- **Skills system:** Skills doctrine, SkillService, lupopedia-headers and uploads skills; actor and channel-level skill declarations; CLI.
- **Header format:** Canonical format is `---` first, then YAML blocks, then `---`, then `# file: ...` as the first content line (not identity line at top of file).
- **Metadata seeds:** CHANGELOG headers and skills metadata in `lupo_metadata`; rule targets use explicit `rule_target_id` in seed.

### Rules system (4.0.68)

- **Database:** `lupo_rules`, `lupo_rule_targets`, `lupo_rule_logs` (migration `database/migrations/20260310_create_rules_tables.sql`; install in `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`). Rule IDs explicit; targets/logs use AUTO_INCREMENT for their PKs.
- **Seed:** `lupo-database/lupopedia/mysql/seed/seed_rules_doctrine_4.0.68.sql` — five core database rules and attachments to Channel 42; **explicit `rule_target_id`** (1–5) in INSERTs to satisfy schema (no default value).
- **Channel 42:** `lupo-channels/42/content/federation_node_id/0/RULES.md` — database rules doctrine for Channel 42.
- **Engine:** `lupo-includes/classes/RuleEngine.php`, `lupo-includes/classes/RuleEvaluator.php`.
- **CLI:** `php lupo-bin/lupo.php rules --check [target_table] [target_id]`, `rules --evaluate [target_table] [target_id] [context_json]`.
- **Docs:** `lupo-docs/doctrine/RULES_DOCTRINE.md`; `docs/HELP.md` (rules commands and Rules system section).

### Rule files (4.0.68)

| File | Purpose |
|------|---------|
| `lupo-rules/skills/lupopedia-headers.md` | Skill rule: Lupopedia Headers, min_proficiency intermediate (LUPOPEDIA header format). |

### Skills system (4.0.68)

- **Doctrine:** `lupo-docs/doctrine/SKILLS_DOCTRINE.md` — `lupopedia.skills` header, directory structure (`lupo-skills/`, actor `skills/*.md`), proficiency levels, **header format** (`---` first, then YAML, then `# file: ...` as first content line).
- **SkillService:** `lupo-includes/classes/SkillService.php` — getActorDir (id/slug), getActorSkills, hasSkill (min proficiency), getSkillDetails; parse `lupopedia.skills` from profile and `skills/*.md`.
- **Seed:** `lupo-database/lupopedia/mysql/seed/seed_skills_4.0.68.sql` — skill metadata and actor–skill attachment in `lupo_metadata` (metadata_id 10201–10205).
- **CLI:** `php lupo-bin/lupo.php skills --actor [actor_id]`, `skills --check [actor_id] <skill_name> [min_proficiency]`; skills command does not require DB.
- **Docs:** `docs/HELP.md` (skills commands and Skills system subsection).

### Skill files (4.0.68)

| File | Purpose |
|------|---------|
| `lupo-skills/README.md` | Skills index (lupopedia-headers, uploads). |
| `lupo-skills/lupopedia-headers/README.md` | Lupopedia Headers skill: format, blocks, proficiency levels, usage. |
| `lupo-skills/lupopedia-headers/examples/basic-header.md` | Basic LUPOPEDIA header example. |
| `lupo-skills/uploads/README.md` | **Uploads skill:** canonical entities, upload layout (`/lupopedia/uploads/<entity>/<YYYY>/<MM>/<sha>.<ext>`), auth_users namespace, date partitioning, hash naming, optional micro-sharding, schema notes. |
| `lupo-actors/1/skills/lupopedia-headers.md` | Actor 1 (WOLFIE) — Lupopedia Headers skill at master. |
| `lupo-actors/wolfie/skills/lupopedia-headers.md` | WOLFIE — Lupopedia Headers skill (same, slug path). |
| `lupo-channels/42/content/federation_node_id/0/SKILLS.md` | **Channel 42 skills:** everyone on channel has **uploads** skill (intermediate); `lupopedia.skills` declares uploads for channel scope 42. |

### Header format correction (4.0.68)

- **Canonical format:** First line of file = `---`; then YAML blocks (e.g. `flare.headers`, `flare.footer`); then closing `---`; then `# file: {title} — session: ... — delegation: ... — web_path: ...` as the first content line. The identity line is **not** at the very top of the file.
- **Updated to this format:** `lupo-docs/doctrine/SKILLS_DOCTRINE.md`, `lupo-skills/README.md`, `lupo-actors/wolfie/skills/lupopedia-headers.md`, `lupo-actors/1/skills/lupopedia-headers.md`; doctrine and examples in `lupo-skills/lupopedia-headers/README.md` and `examples/basic-header.md` describe and demonstrate it.

### Metadata and other seeds (4.0.68)

- **CHANGELOG headers in lupo_metadata:** `lupo-database/lupopedia/mysql/seed/seed_lupo_metadata_changelog_headers_4.0.68.sql` — root + flare.headers + flare.footer block rows for CHANGELOG.md (entity_type `lupopedia_header`, entity_id 1; metadata_id 10001–10021).
- **Skills metadata:** `lupo-database/lupopedia/mysql/seed/seed_skills_4.0.68.sql` — skill “lupopedia-headers” and attachment to Actor 1 in `lupo_metadata`.

### Paths and visits (4.0.68) — doctrine-aligned consolidation

- **Design:** Paths = aggregated navigation flows (low-volume); visits = raw per-event logs (high-volume, append-only). gc.php aggregates unprocessed visits into paths; then marks visits as is_processed. No session/actor/instance on paths; visits are session- and actor-aware.
- **Removed tables:** lupo_analytics_visits, lupo_analytics_visits_daily, lupo_analytics_visits_monthly, lupo_analytics_paths; previous lupo_visits (content_id/page_url/date_ymd style) replaced.
- **lupo_paths:** path_id, entercontentid, exitcontentid, enter_table, exit_table, year_num, month_num, day_num, count_num, transition_type, transition_metadata, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis.
- **lupo_visits:** visit_id, session_id, actor_id, instance_id, path_url, entercontentid, exitcontentid, enter_table, exit_table, transition_type, transition_metadata, created_ymdhis, is_processed, is_deleted, deleted_ymdhis.
- **Install:** install_new_lupopedia.sql updated. **Migration:** database/migrations/20260310_paths_visits_doctrine.sql (one-time). **Crafty import:** import_from_old_crafty_syntax.sql updated for new lupo_visits/lupo_paths schema (visits_daily/monthly to lupo_visits as synthetic rows; paths_firsts/monthly to lupo_paths).

### v4.0.68 review fixes (TOON-based validation, no information_schema)

- **No information_schema:** Lupopedia runs on shared hosting where `information_schema` may not be accessible. All schema validation uses **SHOW TABLES**, **SHOW CREATE TABLE**, and **TOON files** only.
- **Rule 1002 — No Information Schema Queries:** New constraint rule attached to Channel 42. Forbidden patterns: `information_schema`, `INFORMATION_SCHEMA`. Allowed: SHOW TABLES, SHOW CREATE TABLE, TOON files. Document: `lupo-channels/42/content/federation_node_id/0/NO_INFORMATION_SCHEMA_RULE.md`. Seed: rule_id 1002 and rule_target_id 6 in `seed_rules_doctrine_4.0.68.sql`.
- **ToonValidator.php:** `lupo-includes/classes/ToonValidator.php` — getDatabaseTables (SHOW TABLES), getTableStructure (SHOW CREATE TABLE), loadToonFile (lupo-docs/toons/*.toon.json), checkForeignKeys/checkTriggers/checkTimestampColumns/checkAutoIncrement by parsing DDL; validateDatabase() returns per-table results. No information_schema usage.
- **RuleEngine:** checkInformationSchemaViolations() scans lupo-includes PHP files for forbidden patterns; constraint rule with forbidden_patterns triggers this check in evaluateRule().
- **RuleEvaluator:** Uses ToonValidator for checkDatabaseSchema(); checkInformationSchemaUsage() delegates to RuleEngine. For evaluateRules('database', 0) adds results['schema'] and results['information_schema'].
- **Rule file format:** `lupo-rules/skills/lupopedia-headers.rule` renamed to `lupopedia-headers.md` with LUPOPEDIA header format (`---` first, then YAML, then `# file: ...`).
- **Header format fixes:** `lupo-docs/doctrine/RULES_DOCTRINE.md` and `lupo-channels/42/content/federation_node_id/0/RULES.md` updated so first line is `---`, then YAML, then `---`, then `# file: ...` as first content line.
- **Version:** LUPEDIA_VERSION and lupo.php fallbacks set to 4.0.68.

### Cursor rules for actor 1 (4.0.68)

- **lupo-rules/cursor/:** Rule .md files derived from `.cursor/rules/*.mdc` with LUPOPEDIA headers — php-5-3-compatibility, no-laravel-no-middleware, pdo-db-database-access-doctrine, migration-doctrine, database-logic-prohibition-doctrine, flip-doctrine (redirects to LUPOPEDIA HEADERS), toon-source-of-truth, reserved-id-doctrine, versioning-doctrine-single-source, pk-reference-naming-doctrine, required-tables-future-features-doctrine, wheeler-reverse20-ban, stoned-wolfie-schrodinger-ban, quantum-state-uncertainty-ban, experimental-ai-artifact-ban, single-install-no-4.0-upgrade-doctrine.
- **flip-doctrine:** Content replaced with redirect to LUPOPEDIA HEADERS doctrine (README, FORMAT, PLAN, VALIDATORS_AND_TOOLING); describes storage in `lupo_metadata` and writing headers to the file.
- **Seed:** `seed_actor_1_cursor_rules_4.0.68.sql` — inserts into `lupo_metadata` for entity_type='actor', entity_id=1, meta_type='cursor_rule', property_key=slug (16 rules), metadata_id 10301–10316. Each row has path (lupo-rules/cursor/*.md) and source_path (.cursor/rules/*.mdc).
- **README:** `lupo-rules/cursor/README.md` — index of all Cursor rules and seed reference.

### Single-install no 4.0 upgrade doctrine (4.0.68)

- **Rule:** No Lupopedia→Lupopedia upgrade until 4.1.0; all 4.0.x from Crafty Syntax 3.7.5 only. All database changes in install SQL + main seed; consolidate 4.0.x migrations into install; no backwards compatibility between 4.0.x versions.
- **Files:** `.cursor/rules/single-install-no-4.0-upgrade-doctrine.mdc`, `lupo-rules/cursor/single-install-no-4.0-upgrade-doctrine.md`; seed row for actor 1 (metadata_id 10316).

### LUPOPEDIA HEADERS documentation updates (4.0.68)

- **AGENTS.md:** First line and prose updated from FLARE/FLIP to LUPOPEDIA HEADERS; outbound_edges to LUPOPEDIA_HEADERS/README.md; "FLIP Headers" section renamed to "LUPOPEDIA HEADERS" with note on `lupo_metadata` storage and writing headers to the file.
- **docs/HELP.md:** "FLARE protocol" section renamed to "LUPOPEDIA HEADERS protocol"; table now links to LUPOPEDIA_HEADERS/README.md, LUPOPEDIA_HEADERS_FORMAT.md, VALIDATORS_AND_TOOLING.md (database and writing to file).
- **CHANGELOG.md:** purpose and outbound_edges updated to LUPOPEDIA HEADERS doctrine (no FLARE doctrine references).

### Project root atom (4.0.68)

- **lupo-config/global_atoms.yaml:** Added `LUPOPEDIA_PROJECT_ROOT` (e.g. `C:/ServBay/www/servbay/lupopedia`) for path resolution; paths in file_path_from_root, see_also_from_root, and outbound_edges are relative to project root.
- **NO_INFORMATION_SCHEMA_RULE.md:** See Also links fixed to correct relative paths (four levels up); added `see_also_from_root` in YAML and note to resolve with LUPOPEDIA_PROJECT_ROOT.

### 4.0.68 reconciliation (Cursor directive 20260310)

- **Installer seed alignment:** `install.php` now runs 4.0.68 seeds in order after base seeds: `seed_rules_doctrine_4.0.68.sql`, `seed_skills_4.0.68.sql`, `seed_lupo_metadata_changelog_headers_4.0.68.sql`, `seed_actor_1_cursor_rules_4.0.68.sql`. Seeds run in bootstrap (upgrade), new-install, and post–content-seed paths; each file is run only if present (idempotent).
- **Rule evaluation pipeline:** CLI `rules --evaluate` uses `RuleEvaluator` (not `RuleEngine` directly). Full pipeline: CLI → RuleEvaluator → RuleEngine → validators. For target `database`/`0`, schema and information_schema checks are appended to results and printed. Invalid `rule_script` (JSON decode failure) is reported with rule name and error (no silent pass).
- **information_schema scanner:** `RuleEngine::checkInformationSchemaViolations()` excludes files whose path or basename contains `RuleEngine`, `RuleEvaluator`, or `ToonValidator`. Comment text is stripped (single-line `//` and block `/* */`) before scanning so matches in comments do not cause violations.
- **ToonValidator:** AUTO_INCREMENT no longer reported as a per-table violation (doctrine: allowed; app should normally supply explicit IDs). Triggers reported once globally (`_triggers_global`) instead of per table. DDL regex checks use comment-stripped SQL to reduce false positives. `RuleEvaluator::checkDatabaseSchema()` uses global trigger count only.
- **CHANGELOG metadata seed:** `seed_lupo_metadata_changelog_headers_4.0.68.sql` updated to match current CHANGELOG: system_version 4.0.68, last_modified_utc 20260310, actor_id 1, purpose/traits/tags/lupo_agent/last_verified aligned with file headers.
- **SkillService:** Actor slug resolution uses DB (`lupo_actors.slug`) when available, then filesystem registry (`lupo-database/lupopedia/actors/registry.json`) by actor_id (dir segment or slug/actor_name), then static fallback. Parser for `lupopedia.skills` tolerates `\r\n`, optional spaces around colons, and quoted/unquoted values.
- **Paths/visits:** Schema confirmed in `install_new_lupopedia.sql` (lupo_visits, lupo_paths with indexes); import and migration unchanged.

### Files created or modified in 4.0.68 (summary)

**Migrations / install / seeds**

- `database/migrations/20260310_create_rules_tables.sql`
- `database/migrations/20260310_paths_visits_doctrine.sql` (paths/visits consolidation; drops old analytics tables, creates lupo_paths + lupo_visits)
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (rules tables, PATHS/VISITS doctrine: lupo_visits raw + lupo_paths aggregated; removed lupo_analytics_* and old lupo_visits)
- `lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` (paths/visits: map to lupo_visits and lupo_paths; removed analytics_visits_daily/monthly and analytics_paths)
- `lupo-database/lupopedia/mysql/seed/seed_rules_doctrine_4.0.68.sql` (rule 1002 No Information Schema + rule_target_id 6)
- `lupo-database/lupopedia/mysql/seed/seed_skills_4.0.68.sql`
- `lupo-database/lupopedia/mysql/seed/seed_lupo_metadata_changelog_headers_4.0.68.sql`
- `lupo-database/lupopedia/mysql/seed/seed_actor_1_cursor_rules_4.0.68.sql` (actor 1 cursor rules in lupo_metadata, metadata_id 10301–10316)

**Rule files**

- `lupo-rules/skills/lupopedia-headers.md`
- `lupo-channels/42/content/federation_node_id/0/NO_INFORMATION_SCHEMA_RULE.md` (rule 1002: no information_schema; TOON/SHOW only)
- `lupo-rules/cursor/*.md` (16 rule files: doctrine + ban rules from .cursor/rules, including flip-doctrine → LUPOPEDIA HEADERS redirect, single-install-no-4.0-upgrade-doctrine)
- `lupo-rules/cursor/README.md` (index of Cursor rules and seed)

**Skill files**

- `lupo-skills/README.md`
- `lupo-skills/lupopedia-headers/README.md`
- `lupo-skills/lupopedia-headers/examples/basic-header.md`
- `lupo-skills/uploads/README.md`
- `lupo-actors/1/skills/lupopedia-headers.md`
- `lupo-actors/wolfie/skills/lupopedia-headers.md`
- `lupo-channels/42/content/federation_node_id/0/SKILLS.md`

**Channel 42 content**

- `lupo-channels/42/content/federation_node_id/0/RULES.md`
- `lupo-channels/42/content/federation_node_id/0/NO_INFORMATION_SCHEMA_RULE.md`

**PHP**

- `install.php` (4.0.68 seeds in bootstrap, new-install, and post–content-seed paths; idempotent)
- `lupo-includes/classes/RuleEngine.php` (checkInformationSchemaViolations: exclude validator/rule files, strip comments; invalid rule_script reporting with rule name and json_last_error_msg)
- `lupo-includes/classes/RuleEvaluator.php` (ToonValidator; checkDatabaseSchema; checkInformationSchemaUsage; schema/triggers from _triggers_global)
- `lupo-includes/classes/ToonValidator.php` (SHOW TABLES / SHOW CREATE TABLE / TOON; stripSqlComments; no per-table AUTO_INCREMENT/triggers; _triggers_global)
- `lupo-includes/classes/SkillService.php` (actorIdToSlug: DB then registry.json then static map; parseSkillsFromContent: whitespace and line-ending tolerant)
- `lupo-bin/lupo.php` (rules: RuleEvaluator; --evaluate prints schema and information_schema; version 4.0.68)

**Doctrine / docs**

- `lupo-docs/doctrine/RULES_DOCTRINE.md`
- `lupo-docs/doctrine/SKILLS_DOCTRINE.md`
- `docs/HELP.md` (rules and skills CLI, Rules system, Skills system, LUPOPEDIA HEADERS protocol section)
- `AGENTS.md` (LUPOPEDIA HEADERS; FLIP Headers → LUPOPEDIA HEADERS section; doctrine links)
- `lupo-config/global_atoms.yaml` (LUPOPEDIA_PROJECT_ROOT atom)
- `.cursor/rules/flip-doctrine.mdc` (replaced with LUPOPEDIA HEADERS redirect)
- `.cursor/rules/single-install-no-4.0-upgrade-doctrine.mdc` (new)
- `docs/status/cursor_4_0_68_reconciliation_report.md` (4.0.68 reconciliation: installer, rules, validators, seeds, SkillService, changelog)

---
 