---
lupopedia.headers:
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  lupopedia.version: "1.0"
  lupopedia.schema: "index"
  file_path_from_root: "lupo-rules/cursor/README.md"
  web_path: "http://www.lupopedia.com/rules/cursor"
  last_modified_utc: "20260310"
  system_version: "4.0.68"
  artifact_type: "index"
  artifact_kind: "cursor_rules"
  purpose: "Index of Cursor rule .md files derived from .cursor/rules/*.mdc; attached to actor_id 1 in lupo_metadata"
  tags: ["cursor", "rules", "doctrine", "actor_1"]
lupopedia.footer:
  version: "4.0.68"
  last_verified: "20260310"
  last_verified_by: "wolfie"
---
# file: Cursor rules index — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/cursor

# Cursor Rules (lupo-rules/cursor)

These rule files mirror the content of `.cursor/rules/*.mdc` with LUPOPEDIA headers. Actor 1 (WOLFIE) has all of them attached in `lupo_metadata` via seed `seed_actor_1_cursor_rules_4.0.68.sql`.

## Rule files

| Slug | Rule .md | Source .mdc |
|------|----------|-------------|
| php-5-3-compatibility | [php-5-3-compatibility.md](php-5-3-compatibility.md) | .cursor/rules/php-5-3-compatibility.mdc |
| no-laravel-no-middleware | [no-laravel-no-middleware.md](no-laravel-no-middleware.md) | .cursor/rules/no-laravel-no-middleware.mdc |
| pdo-db-database-access-doctrine | [pdo-db-database-access-doctrine.md](pdo-db-database-access-doctrine.md) | .cursor/rules/pdo-db-database-access-doctrine.mdc |
| migration-doctrine | [migration-doctrine.md](migration-doctrine.md) | .cursor/rules/migration-doctrine.mdc |
| database-logic-prohibition-doctrine | [database-logic-prohibition-doctrine.md](database-logic-prohibition-doctrine.md) | .cursor/rules/database-logic-prohibition-doctrine.mdc |
| flip-doctrine (→ LUPOPEDIA HEADERS) | [flip-doctrine.md](flip-doctrine.md) | .cursor/rules/flip-doctrine.mdc — redirects to LUPOPEDIA HEADERS doctrine |
| toon-source-of-truth | [toon-source-of-truth.md](toon-source-of-truth.md) | .cursor/rules/toon-source-of-truth.mdc |
| reserved-id-doctrine | [reserved-id-doctrine.md](reserved-id-doctrine.md) | .cursor/rules/reserved-id-doctrine.mdc |
| versioning-doctrine-single-source | [versioning-doctrine-single-source.md](versioning-doctrine-single-source.md) | .cursor/rules/versioning-doctrine-single-source.mdc |
| pk-reference-naming-doctrine | [pk-reference-naming-doctrine.md](pk-reference-naming-doctrine.md) | .cursor/rules/pk-reference-naming-doctrine.mdc |
| required-tables-future-features-doctrine | [required-tables-future-features-doctrine.md](required-tables-future-features-doctrine.md) | .cursor/rules/required-tables-future-features-doctrine.mdc |
| wheeler-reverse20-ban | [wheeler-reverse20-ban.md](wheeler-reverse20-ban.md) | .cursor/rules/wheeler-reverse20-ban.mdc |
| stoned-wolfie-schrodinger-ban | [stoned-wolfie-schrodinger-ban.md](stoned-wolfie-schrodinger-ban.md) | .cursor/rules/stoned-wolfie-schrodinger-ban.mdc |
| quantum-state-uncertainty-ban | [quantum-state-uncertainty-ban.md](quantum-state-uncertainty-ban.md) | .cursor/rules/quantum-state-uncertainty-ban.mdc |
| experimental-ai-artifact-ban | [experimental-ai-artifact-ban.md](experimental-ai-artifact-ban.md) | .cursor/rules/experimental-ai-artifact-ban.mdc |
| single-install-no-4.0-upgrade-doctrine | [single-install-no-4.0-upgrade-doctrine.md](single-install-no-4.0-upgrade-doctrine.md) | .cursor/rules/single-install-no-4.0-upgrade-doctrine.mdc |

## Seed (actor_id 1)

Run after install/other seeds as needed:

- `lupo-database/lupopedia/mysql/seed/seed_actor_1_cursor_rules_4.0.68.sql`

Each row inserts into `lupo_metadata` with `entity_type='actor'`, `entity_id=1`, `meta_type='cursor_rule'`, `property_key=<slug>`, `property_value` JSON with `path` (lupo-rules/cursor/*.md) and `source_path` (.cursor/rules/*.mdc). metadata_id 10301–10316.
