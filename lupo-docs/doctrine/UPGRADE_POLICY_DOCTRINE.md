---
lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "doctrine"
  system_version: "4.0.77"
  file_path_from_root: "lupo-docs/doctrine/UPGRADE_POLICY_DOCTRINE.md"
  web_path: "[web_path](http://www.lupopedia.com/doctrine/UPGRADE_POLICY)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "policy"
  purpose: "Canonical upgrade policy for Lupopedia 4.0.x — no Lupopedia→Lupopedia upgrade until 4.1.0"
  tags: ["upgrade", "policy", "4.0.x", "crafty_syntax", "install"]

lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Reference this doctrine in INSTALL.md, version READMEs, and CHANGELOG upgrade notes"
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

- Migration files in `lupo-database/.../mysql/migrations/` (or equivalent) are for **development use only**.
- Do **not** rely on migrations for production upgrades in 4.0.x.
- Always use **fresh install** or **Crafty Syntax 3.7.5 upgrade** path.
- Schema drift between 4.0.x versions is not supported; the canonical schema is whatever is in the current `install_new_lupopedia.sql` and seed set.

## References

- Single-install doctrine: `.cursor/rules/single-install-no-4.0-upgrade-doctrine.mdc`
- Install SQL: `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- Version history: [lupo-docs/version.md](../version.md)
