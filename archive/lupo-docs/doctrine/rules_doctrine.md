---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/RULES_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/RULES_DOCTRINE"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: canonical
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: null
  title: "Rules System Doctrine"
  summary: ""
---
# file: Rules System Doctrine — session: L-LUPO-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/RULES_DOCTRINE

# Rules System Doctrine

**Version:** 4.0.68  
**Status:** ACTIVE  
**Purpose:** Define the rules system for governance, permissions, and constraints.

## Tables

### lupo_rules

Canonical registry of all rules. Primary key `rule_id` is explicit (no AUTO_INCREMENT); IDs are allocated from seed or registry.

| Column | Type | Description |
|--------|------|-------------|
| rule_id | bigint | Primary key |
| rule_name | varchar(255) | Human-readable name |
| rule_description | text | Detailed description |
| rule_type | varchar(64) | 'behavior', 'permission', 'constraint', 'governance' |
| rule_script | text | JSON defining rule logic |
| rule_version | bigint | Version number |
| created_ymdhis | bigint | Creation timestamp (YYYYMMDDHHIISS UTC) |
| updated_ymdhis | bigint | Last update |
| is_deleted | tinyint | Soft delete flag |
| deleted_ymdhis | bigint | When deleted (nullable) |

### lupo_rule_targets

Polyphonic attachment of rules to any node (actors, channels, departments, etc.). No foreign keys; application-managed.

| Column | Type | Description |
|--------|------|-------------|
| rule_target_id | bigint | Primary key (AUTO_INCREMENT) |
| rule_id | bigint | Rule being attached |
| target_table | varchar(255) | Entity type ('channels', 'actors', 'departments', etc.) |
| target_id | bigint | Entity ID |
| applied_by_actor_id | bigint | Who applied the rule (nullable) |
| priority | int | Precedence (lower = higher priority) |
| created_ymdhis | bigint | Creation timestamp |
| updated_ymdhis | bigint | Last update |
| is_deleted | tinyint | Soft delete flag |
| deleted_ymdhis | bigint | When deleted (nullable) |

### lupo_rule_logs

Audit trail of rule evaluation.

| Column | Type | Description |
|--------|------|-------------|
| rule_log_id | bigint | Primary key (AUTO_INCREMENT) |
| rule_id | bigint | Rule evaluated |
| target_table | varchar(255) | Entity type |
| target_id | bigint | Entity ID |
| actor_id | bigint | Who triggered the rule |
| instance_id | bigint | Evaluating instance (default 0) |
| event_type | varchar(64) | 'evaluated', 'blocked', 'allowed', 'error' |
| event_details | text | JSON or text context |
| created_ymdhis | bigint | Evaluation timestamp |

## Rule types

| Type | Description | Example |
|------|-------------|---------|
| behavior | How actors must behave | "Respond using PACK dialect" |
| permission | Who can do what | "Only actors with skill X may post" |
| constraint | System limits | "No foreign keys in schema" |
| governance | Content requirements | "All content must include header" |

## Attachment patterns

| Target | target_table | Example |
|--------|--------------|---------|
| Channel | channels | Rule applies to channel 42 |
| Actor | actors | Rule applies to specific actor |
| Department | departments | Rule applies to department |
| Global DB | database | Use target_id 0 for global database rules |

## Doctrine alignment

- No foreign keys; relationships are application-managed.
- All timestamps are BIGINT in YYYYMMDDHHIISS UTC.
- No triggers or stored procedures.
- Explicit column lists in INSERTs (see seed file).

## CLI

- List rules: `php lupo-bin/lupo.php rules --check [target_table] [target_id]`
- Evaluate: `php lupo-bin/lupo.php rules --evaluate [target_table] [target_id] [context_json]`
- Example: `php lupo-bin/lupo.php rules --check channels 42`

## See also

- [lupo-channels/42/content/federation_node_id/0/RULES.md](../../lupo-channels/42/content/federation_node_id/0/RULES.md) — Channel 42 rules with LUPOPEDIA header
- lupo-database/migrations/20260310_create_rules_tables.sql
