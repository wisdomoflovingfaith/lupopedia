---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/UPGRADE_POLICY_DOCTRINE.md"
  web_path: "[web_path](http://www.lupopedia.com/doctrine/UPGRADE_POLICY)"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: policy
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: Upgrade Policy Doctrine — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/UPGRADE_POLICY

# Upgrade Policy Doctrine (4.0.x)

## Core Policy Statement

**For all Lupopedia 4.0.x versions, there is NO Lupopedia → Lupopedia upgrade path.**

Every installation must be one of:

1. **Fresh install** of Lupopedia 4.0.x directly
2. **Upgrade from Crafty Syntax 3.7.5** to Lupopedia 4.0.x

Lupopedia → Lupopedia upgrades (e.g., 4.0.1 → 4.0.2, 4.0.76 → 4.0.77) are **not supported** until after 4.1.0.

## Rationale

- 4.0.x is the stabilization track from Crafty Syntax migration.
- Schema changes are consolidated into `install_new_lupopedia.sql` (and seed files); there is no migration chain between 4.0.x versions.
- All installations are treated as either new or from the Crafty 3.7.5 baseline.

## Future Change

**Starting with 4.1.0**, Lupopedia will support version-to-version upgrades. Until then, all 4.0.x deployments must follow the policy above.

## Implications

- Migration files in `database/.../mysql/migrations/` (or equivalent) are for **development use only**.
- Do **not** rely on migrations for production upgrades in 4.0.x.
- Always use **fresh install** or **Crafty Syntax 3.7.5 upgrade** path.
- Schema drift between 4.0.x versions is not supported; the canonical schema is whatever is in the current `install_new_lupopedia.sql` and seed set.

## References

- Single-install doctrine: `.cursor/rules/single-install-no-4.0-upgrade-doctrine.mdc`
- Install SQL: `database/lupopedia/mysql/install/install_new_lupopedia.sql`
- Version history: [docs/version.md](../version.md)
