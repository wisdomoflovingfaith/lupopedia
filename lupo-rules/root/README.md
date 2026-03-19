---
lupopedia.init:
  orchestrator_actor: "any"
  rule_set_version: "4.0.74+"
  applies_to: ["audit", "code-gen", "db-sync", "migration", "header-sync"]
  enforcement: strict

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."

lupopedia.headers:
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  lupopedia.version: "4.0.74"
  lupopedia.schema: "index"
  file_path_from_root: "lupo-rules/root/README.md"
  web_path: "[web_path](http://www.lupopedia.com/rules/root)"
  last_modified_utc: "20260313"
  system_version: "4.0.74"
  artifact_type: "index"
  artifact_kind: "root_rules"
  purpose: "Index of root rule .md files (all IDE agents and code-writing agents follow these); derived from .cursor/rules/*.mdc; attached to actor_id 1 in lupo_metadata"
  tags: ["root", "rules", "doctrine", "actor_1"]

lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260313"
  last_verified_by: "wolfie"
  orchestrator: "cursor"
  next_action:
    - "Sync rule content to lupo_orchestrator_rules when table is available"
---
# file: Root rules index — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root

# Root Rules (lupo-rules/root)

## 🧱 Constitutional Root Rules (PRIMARY)

**[LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md](LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md)** - The single source of absolute, non-negotiable constitutional rules for all IDE agents, external actors, generators, and automated subsystems.

**This constitutional document supersedes all fragmented root rules below.**

**[CONVERGENCE_DOCTRINE.md](CONVERGENCE_DOCTRINE.md)** - Forced convergence to a single canonical system state; actor identity permanence; no variant actors; banned entities remain addressable by `actor_id` (status is state, not identity).

## Legacy Fragmented Rules (Superseded)

The following fragmented rule files are preserved for reference but are superseded by the constitutional document above:

| Slug | Rule .md | Source .mdc | Status |
|------|----------|-------------|--------|
| php-5-6-compatibility | [php-5-6-compatibility.md](php-5-6-compatibility.md) | .cursor/rules/php-5-6-compatibility.mdc | ❌ Superseded |
| no-laravel-no-middleware | [no-laravel-no-middleware.md](no-laravel-no-middleware.md) | .cursor/rules/no-laravel-no-middleware.mdc | ❌ Superseded |
| pdo-db-database-access-doctrine | [pdo-db-database-access-doctrine.md](pdo-db-database-access-doctrine.md) | .cursor/rules/pdo-db-database-access-doctrine.mdc | ❌ Superseded |
| migration-doctrine | [migration-doctrine.md](migration-doctrine.md) | .cursor/rules/migration-doctrine.mdc | ❌ Superseded |
| database-logic-prohibition-doctrine | [database-logic-prohibition-doctrine.md](database-logic-prohibition-doctrine.md) | .cursor/rules/database-logic-prohibition-doctrine.mdc | ❌ Superseded |
| flip-doctrine (→ LUPOPEDIA HEADERS) | [flip-doctrine.md](flip-doctrine.md) | .cursor/rules/flip-doctrine.mdc — redirects to LUPOPEDIA HEADERS doctrine | ❌ Superseded |
| toon-source-of-truth | [toon-source-of-truth.md](toon-source-of-truth.md) | .cursor/rules/toon-source-of-truth.mdc | ❌ Superseded |
| reserved-id-doctrine | [reserved-id-doctrine.md](reserved-id-doctrine.md) | .cursor/rules/reserved-id-doctrine.mdc | ❌ Superseded |
| versioning-doctrine-single-source | [versioning-doctrine-single-source.md](versioning-doctrine-single-source.md) | .cursor/rules/versioning-doctrine-single-source.mdc | ❌ Superseded |
| pk-reference-naming-doctrine | [pk-reference-naming-doctrine.md](pk-reference-naming-doctrine.md) | .cursor/rules/pk-reference-naming-doctrine.mdc | ❌ Superseded |
| required-tables-future-features-doctrine | [required-tables-future-features-doctrine.md](required-tables-future-features-doctrine.md) | .cursor/rules/required-tables-future-features-doctrine.mdc | ❌ Superseded |
| single-install-no-4.0-upgrade-doctrine | [single-install-no-4.0-upgrade-doctrine.md](single-install-no-4.0-upgrade-doctrine.md) | .cursor/rules/single-install-no-4.0-upgrade-doctrine.mdc | ❌ Superseded |
| task-planning-doctrine | [task-planning-doctrine.md](task-planning-doctrine.md) | .cursor/rules/task-planning-doctrine.mdc | ❌ Superseded |
| safe-database-operations-doctrine | [safe-database-operations-doctrine.md](safe-database-operations-doctrine.md) | .cursor/rules/safe-database-operations-doctrine.mdc | ❌ Superseded |
| channels-federation-offline-session-doctrine | [channels-federation-offline-session-doctrine.md](channels-federation-offline-session-doctrine.md) | .cursor/rules/channels-federation-offline-session-doctrine.mdc | ❌ Superseded |
| database-offline-fallback-import-doctrine | [database-offline-fallback-import-doctrine.md](database-offline-fallback-import-doctrine.md) | .cursor/rules/database-offline-fallback-import-doctrine.mdc | ❌ Superseded |
| ide-agent-identity-actor-pairing-doctrine | [ide-agent-identity-actor-pairing-doctrine.md](ide-agent-identity-actor-pairing-doctrine.md) | .cursor/rules/ide-agent-identity-actor-pairing-doctrine.mdc | ❌ Superseded |

## Seed (actor_id 1)

**Updated seed needed:** The constitutional document requires a new seed entry to replace the 18 fragmented rule entries.

Current seed: `seed_actor_1_cursor_rules_4.0.68.sql` (18 fragmented entries)

**Required new seed:** `seed_actor_1_constitutional_rules_4.0.76.sql` (single constitutional entry)

## Syncing to IDE Agents

The constitutional rules must be propagated to all IDE agent environments (.cursor, .idea, .kiro, .windsurf, .cascade) via the rule transformer. For canonical agent identity and propagation targets, see [lupo-docs/doctrine/AGENT_REGISTRY.md](../../lupo-docs/doctrine/AGENT_REGISTRY.md).

```bash
php lupo-scripts/propagate_agent_rules.php
```

Run this after editing the constitutional document so that agents correctly generate their `.json`/`.xml`/`.mdc` environments.

## Migration Path

1. **Immediate:** Use constitutional document for all new agent work
2. **Phase-out:** Gradually remove fragmented rules from agent configurations
3. **Cleanup:** Archive fragmented rules after constitutional adoption is verified
