---
lupopedia.headers:
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  lupopedia.version: "1.0"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-channels/42/content/federation_node_id/0/RULES.md"
  web_path: "http://www.lupopedia.com/channels/42/RULES"
  last_modified_utc: "20260310"
  system_version: "4.0.68"
  channel_id: 42
  artifact_type: "doctrine"
  artifact_kind: "rules"
  purpose: "Database Rules Doctrine for Lupopedia"
  mood_rgb: "4169E1"
  traits: ["doctrine", "rules", "database", "v4.0.68"]
  tags: ["rules", "database", "doctrine", "constraints"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/RULES_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "database/migrations/20260310_create_rules_tables.sql", type: "implements", weight: 1.0 }

lupopedia.footer:
  version: "4.0.68"
  last_verified: "20260310"
  last_verified_by: "wolfie"
---
# file: Lupopedia Database Rules Doctrine — session: L-LUPO-WOLFIE — delegation: wolfie:lilith:antigravity:root — web_path: http://www.lupopedia.com/channels/42/RULES

# Lupopedia Database Rules Doctrine

## Core Database Rules (Channel 42)

| Rule ID | Name | Description |
|---------|------|-------------|
| 1 | No Foreign Keys | Relationships are application-managed only |
| 2 | No Database Logic | No triggers, stored procedures, views |
| 3 | Timestamp Format | All timestamps are BIGINT in YYYYMMDDHHIISS UTC |
| 4 | Explicit INSERTs | Every INSERT must list all columns explicitly |
| 5 | Registry Open IDs | All primary keys from registry where applicable; no AUTO_INCREMENT for registry-backed tables |

## Enforcement

These rules are attached to **Channel 42** via the `lupo_rules` system and are enforced by:

- Schema validators (e.g. `scripts/verify_db_against_toons.py`)
- Code reviewers
- CI pipeline checks

## Attachments

Rules are attached to Channel 42 in `lupo_rule_targets` (target_table = 'channels', target_id = 42).

## See Also

- [lupo-docs/doctrine/RULES_DOCTRINE.md](../../../../lupo-docs/doctrine/RULES_DOCTRINE.md)
- [database/migrations/20260310_create_rules_tables.sql](../../../../database/migrations/20260310_create_rules_tables.sql)
