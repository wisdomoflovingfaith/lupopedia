---
lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "database_table"
  system_version: "4.0.77"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_decisions.md"
  web_path: "[web_path](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_decisions)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "table_documentation"
  artifact_kind: "table"
  purpose: "Documentation for lupo_decisions table — Bayesian Decision Tracking; required channel_id and project_id scope (4.0.77)"
  tags: ["database", "bayesian", "decisions", "4.0.77"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_decisions table doc at creation."
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_decisions.toon.json", type: "schema_reference", weight: 0.95 }
    - { to: "lupo-docs/doctrine/BAYESIAN_DECISION_DOCTRINE.md", type: "references", weight: 0.9 }

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Keep aligned with install SQL and TOON; see SCHEMA_CANONICAL_SOURCES.md"
---
# file: lupo_decisions — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root

# lupo_decisions

Canonical table for **Bayesian Decision Tracking** decision nodes. Every row is scoped by `channel_id` and `project_id` (required). Added 4.0.77; schema foundation only; engine and integrations deferred.

## Purpose

- Store decision nodes (actor, channel, project, session, type, status, probability, parent/root, depth).
- Support scoped queries via indexes on channel, project, and time.
- Logical reference to state snapshots in `lupo_metadata` via `state_snapshot_id`. No foreign keys; application enforces scope.

## Schema (install SQL authority)

| Column | Type | Description |
|--------|------|-------------|
| decision_id | bigint NOT NULL | Primary key; **application-supplied** (no AUTO_INCREMENT). |
| actor_id | bigint NOT NULL | Actor making the decision. |
| channel_id | bigint NOT NULL | **Required** scope. |
| project_id | bigint NOT NULL | **Required** scope. |
| session_id | bigint NOT NULL | Session context. |
| root_decision_id | bigint DEFAULT NULL | Root of decision tree. |
| parent_decision_id | bigint DEFAULT NULL | Parent decision. |
| depth | int NOT NULL DEFAULT 0 | Depth in tree. |
| decision_type | varchar(50) NOT NULL | Type code. |
| decision_status | varchar(32) NOT NULL | Status. |
| decision_key | varchar(255) DEFAULT NULL | Optional key. |
| probability | decimal(4,3) DEFAULT NULL | Probability value. |
| probability_lower | decimal(4,3) DEFAULT NULL | Lower bound. |
| probability_upper | decimal(4,3) DEFAULT NULL | Upper bound. |
| probability_model | varchar(64) DEFAULT NULL | Model identifier. |
| state_snapshot_id | bigint DEFAULT NULL | Logical ref to lupo_metadata (decision_state). |
| federation_node_id | bigint NOT NULL DEFAULT 1 | Federation node. |
| origin_decision_id | bigint DEFAULT NULL | Origin when federated. |
| created_ymdhis | bigint NOT NULL | Created timestamp (YYYYMMDDHHIISS UTC). |
| created_by_actor_id | bigint NOT NULL | Creator actor. |
| updated_ymdhis | bigint DEFAULT NULL | Updated timestamp. |
| abandoned_ymdhis | bigint DEFAULT NULL | Abandoned timestamp. |
| pruned_ymdhis | bigint DEFAULT NULL | Pruned timestamp. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT NULL | Soft delete timestamp. |

**Primary key:** decision_id  
**Indexes:** actor_time, session_time, root_depth, parent, status, probability, federation, **channel_time**, **project_time**, **channel_project_time**

## Doctrine notes

- **Scope:** Every decision must have `channel_id` and `project_id`; queries omitting them are invalid for production. See BAYESIAN_DECISION_DOCTRINE.md §2.
- **No foreign keys** — all references logical; application enforces.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC; set in application.
- **ID allocation:** decision_id is explicit; use registry/allocator pattern.

## Related

- **lupo_decision_edges** — edges between decisions (source/target).
- **lupo_decision_influences** — influence relationships.
- **BayesianDecisionService** — minimal scaffold in `lupo-database/lupopedia/content/lupo-app/Services/BayesianDecisionService.php`; requires channel_id and project_id in `recordDecision()`.

---

*Cursor IDE (actor_id 102) — table doc 2026-03-16*
