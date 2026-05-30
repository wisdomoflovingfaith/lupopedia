---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/anubis/rules/README.md
  web_path: https://www.lupopedia.com/lupopedia/actors/anubis/rules/README.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: index
  artifact_kind: root_rules
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: index
  prd_cluster: null
  title: null
  summary: null
---
# file: Root rules index — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root

# Root Rules (rules/root)

## 🧱 Constitutional Root Rules (PRIMARY)

**[LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md](LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md)** - The single source of absolute, non-negotiable constitutional rules for all IDE agents, external actors, generators, and automated subsystems.

**This constitutional document supersedes all fragmented root rules below.**

## Legacy Fragmented Rules (Superseded)

The following fragmented rule files are preserved for reference but are superseded by the constitutional document above:

| Slug | Rule .md | Source .mdc | Status |
|------|----------|-------------|--------|
| php-7-4-compatibility | [php-7-4-compatibility.md](php-7-4-compatibility.md) | .cursor/rules/php-7-4-compatibility.mdc | ✅ Active (ARC003) |
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

The constitutional rules must be propagated to all IDE agent environments (.cursor, .idea, .kiro, .windsurf, .cascade) via the rule transformer. For canonical agent identity and propagation targets, see [docs/doctrine/AGENT_REGISTRY.md](../../../docs/doctrine/AGENT_REGISTRY.md).

```bash
php scripts/propagate_agent_rules.php
```

Run this after editing the constitutional document so that agents correctly generate their `.json`/`.xml`/`.mdc` environments.

## Migration Path

1. **Immediate:** Use constitutional document for all new agent work
2. **Phase-out:** Gradually remove fragmented rules from agent configurations
3. **Cleanup:** Archive fragmented rules after constitutional adoption is verified
