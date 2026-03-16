---
lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "database_table"
  system_version: "4.0.77"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_decision_edges.md"
  web_path: "[web_path](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_decision_edges)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "table_documentation"
  artifact_kind: "table"
  purpose: "Documentation for lupo_decision_edges table — Bayesian Decision Tracking edges; required channel_id and project_id (4.0.77)"
  tags: ["database", "bayesian", "decisions", "edges", "4.0.77"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_decision_edges table doc at creation."
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_decision_edges.toon.json", type: "schema_reference", weight: 0.95 }
    - { to: "lupo-docs/doctrine/BAYESIAN_DECISION_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_decisions.md", type: "references", weight: 0.85 }

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Keep aligned with install SQL and TOON; see SCHEMA_CANONICAL_SOURCES.md"
---
# file: lupo_decision_edges — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root

# lupo_decision_edges

Edges between decision nodes (e.g. leads_to, contradicts, influences). Every row is scoped by `channel_id` and `project_id` (required). Added 4.0.77.

## Purpose

- Store directed edges between decisions (source_decision_id → target_decision_id) with edge_type and optional probability.
- Support scoped lookups by channel and project.

## Schema (install SQL authority)

| Column | Type | Description |
|--------|------|-------------|
| source_decision_id | bigint NOT NULL | Source decision (part of PK). |
| target_decision_id | bigint NOT NULL | Target decision (part of PK). |
| edge_type | varchar(50) NOT NULL | Edge type (part of PK). |
| channel_id | bigint NOT NULL | **Required** scope. |
| project_id | bigint NOT NULL | **Required** scope. |
| probability | decimal(4,3) DEFAULT NULL | Edge probability. |
| session_id | bigint DEFAULT NULL | Session context. |
| federation_node_id | bigint NOT NULL DEFAULT 1 | Federation node. |
| created_ymdhis | bigint NOT NULL | Created timestamp (YYYYMMDDHHIISS UTC). |
| created_by_actor_id | bigint NOT NULL | Creator actor. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT NULL | Soft delete timestamp. |

**Primary key:** (source_decision_id, target_decision_id, edge_type)  
**Indexes:** target, probability, session, **channel_id**, **project_id**

## Doctrine notes

- **Scope:** channel_id and project_id required; see BAYESIAN_DECISION_DOCTRINE.md §2.
- **No foreign keys** — logical references only.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC; set in application.

## Related

- **lupo_decisions** — decision nodes.
- **lupo_decision_influences** — influence relationships.

---

*Cursor IDE (actor_id 102) — table doc 2026-03-16*
