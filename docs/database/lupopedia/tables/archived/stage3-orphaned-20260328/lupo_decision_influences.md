> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

﻿---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: docs/database/lupopedia/tables/active/lupo_decision_influences.md
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
    through channels, threads, and artifacts. See docs/doctrine/DECISION_MODEL.md.
  purpose: "Documentation for lupo_decision_influences table \u2014 Bayesian Decision\
    \ Tracking influences; required channel_id and project_id (4.0.77)"
  tags:
  - database
  - bayesian
  - decisions
  - influences
  - 4.0.77
  when_updated: '20260513053635'
lupopedia.edges:
  comment: Snapshot of edges for lupo_decision_influences table doc at creation.
  outbound_edges:
  - to: database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: database/lupopedia/toon/lupo_decision_influences.toon.json
    type: schema_reference
    weight: 0.95
  - to: docs/doctrine/BAYESIAN_DECISION_DOCTRINE.md
    type: references
    weight: 0.9
  - to: docs/database/lupopedia/tables/active/lupo_decisions.md
    type: references
    weight: 0.85
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: '20260316000000'
  last_verified_by: cursor
  orchestrator: cursor
  next_action:
  - Keep aligned with install SQL and TOON; see SCHEMA_CANONICAL_SOURCES.md
  last_verified_by_actor_id: 102
---
# file: lupo_decision_influences â€” session: L-LUPO-ROOT-CURSOR â€” delegation: cursor:root

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

- **Scope:** channel_id and project_id required; see BAYESIAN_DECISION_DOCTRINE.md Â§2.
- **No foreign keys** â€” logical references only.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC; set in application.

## Related

- **lupo_decisions** â€” decision nodes.
- **lupo_decision_edges** â€” structural edges between decisions.

---

*Cursor IDE (actor_id 102) â€” table doc 2026-03-16*

