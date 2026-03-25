---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_decision_influences.md
  web_path: '[web_path](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_decision_influences)'
  last_modified_utc: '20260325'
  channel_id: 42
  actor_id: 102
  artifact_type: table_documentation
  artifact_kind: table
  status: DEPRECATED
  deprecated_in_version: '4.0.87'
  deprecated_reason: >-
    Bayesian decision tracking removed. Decision history is now represented
    through channels, threads, and artifacts. See lupo-docs/doctrine/DECISION_MODEL.md.
  purpose: "Documentation for lupo_decision_influences table \u2014 Bayesian Decision\
    \ Tracking influences; required channel_id and project_id (4.0.77)"
  tags:
  - database
  - bayesian
  - decisions
  - influences
  - 4.0.77
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_decision_influences table doc at creation.
  outbound_edges:
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: lupo-database/lupopedia/toon/lupo_decision_influences.toon.json
    type: schema_reference
    weight: 0.95
  - to: lupo-docs/doctrine/BAYESIAN_DECISION_DOCTRINE.md
    type: references
    weight: 0.9
  - to: lupo-docs/database/lupopedia/tables/active/lupo_decisions.md
    type: references
    weight: 0.85
lupopedia.footer:
  last_verified: '20260316000000'
  last_verified_by: cursor
  orchestrator: cursor
  next_action:
  - Keep aligned with install SQL and TOON; see SCHEMA_CANONICAL_SOURCES.md
  last_verified_by_actor_id: 102
---
# file: lupo_decision_influences — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root

> **DEPRECATED (4.0.87):** This table has been removed. Decision history is now represented through channels, threads, and artifacts. ROSE interprets decision context from conversation history. See [DECISION_MODEL.md](../../doctrine/DECISION_MODEL.md).

# lupo_decision_influences

Influence relationships between decisions (e.g. causal, informational, constraint). Every row is scoped by `channel_id` and `project_id` (required). Added 4.0.77.

## Purpose

- Store which decision influences which (decision_id, influencing_decision_id, influence_type) with optional weight.
- Support scoped lookups by channel and project.

## Schema (install SQL authority)

| Column | Type | Description |
|--------|------|-------------|
| decision_id | bigint NOT NULL | Decision being influenced (part of PK). |
| influencing_decision_id | bigint NOT NULL | Influencing decision (part of PK). |
| influence_type | varchar(50) NOT NULL | Influence type (part of PK). |
| channel_id | bigint NOT NULL | **Required** scope. |
| project_id | bigint NOT NULL | **Required** scope. |
| weight | decimal(4,3) DEFAULT NULL | Influence weight. |
| session_id | bigint DEFAULT NULL | Session context. |
| federation_node_id | bigint NOT NULL DEFAULT 1 | Federation node. |
| created_ymdhis | bigint NOT NULL | Created timestamp (YYYYMMDDHHIISS UTC). |
| created_by_actor_id | bigint NOT NULL | Creator actor. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT NULL | Soft delete timestamp. |

**Primary key:** (decision_id, influencing_decision_id, influence_type)  
**Indexes:** influencing_decision_id, weight, **channel_id**, **project_id**

## Doctrine notes

- **Scope:** channel_id and project_id required; see BAYESIAN_DECISION_DOCTRINE.md §2.
- **No foreign keys** — logical references only.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC; set in application.

## Related

- **lupo_decisions** — decision nodes.
- **lupo_decision_edges** — structural edges between decisions.

---

*Cursor IDE (actor_id 102) — table doc 2026-03-16*
