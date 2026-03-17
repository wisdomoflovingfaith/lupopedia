---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_decision_evidence.md"
  web_path: "[lupo_decision_evidence](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_decision_evidence)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Evidence tracking for Bayesian decision updates; stores raw evidence items with likelihoods and confidence scores"
  tags: ["database", "table", "core"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_decision_evidence table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=1 python_hits=0"
  outbound_edges:
    - { to: "database.table.lupo_decision_evidence", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-database/lupopedia/content/lupo-app/Services/BayesianDecisionService.php", type: "USED_IN_PHP", weight: 0.9 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "wolfie"
---
# file: lupo_decision_evidence — session: L-LUPO-ROOT-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_decision_evidence

# Table: lupo_decision_evidence

Canonical table for **evidence tracking in Bayesian decision updates**. Stores raw evidence items with likelihoods, confidence scores, and metadata for posterior probability calculations.

## Purpose

- Store raw evidence items that influence decisions
- Support evidence likelihood calculations and confidence scoring
- Enable evidence aggregation for Bayesian updates
- Provide audit trail for decision reasoning
- Support multiple evidence types per decision

## Schema (install SQL authority)

| Column | Type | Description |
|--------|------|-------------|
| decision_evidence_id | bigint NOT NULL | Primary key; **application-supplied** (no AUTO_INCREMENT). |
| decision_id | bigint NOT NULL | Decision this evidence relates to. |
| channel_id | bigint NOT NULL | Channel this evidence belongs to. |
| project_id | bigint DEFAULT 0 | Project this evidence belongs to (0 = global). |
| evidence_type | varchar(64) NOT NULL | Type of evidence (user_action, sensor_data, expert_opinion, etc.). |
| evidence_source | varchar(255) NOT NULL | Source of evidence (user_id, system, external_api, etc.). |
| evidence_value | text DEFAULT NULL | Raw evidence data or content. |
| likelihood | decimal(10,6) DEFAULT NULL | Likelihood of this evidence given hypothesis. |
| confidence | decimal(10,6) DEFAULT NULL | Confidence score in this evidence (0.0 to 1.0). |
| federation_node_id | bigint NOT NULL DEFAULT 1 | Federation node that created this evidence. |
| status | varchar(32) NOT NULL DEFAULT 'active' | Evidence status (active, reviewed, rejected). |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when evidence was recorded. |
| updated_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when evidence was last updated. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT NULL | UTC timestamp when evidence was deleted. |

## Indexes

- `PRIMARY KEY (decision_evidence_id)`
- `INDEX lupo_decision_evidence_idx_decision` ON `lupo_decision_evidence` (`decision_id`)
- `INDEX lupo_decision_evidence_idx_channel` ON `lupo_decision_evidence` (`channel_id`)
- `INDEX lupo_decision_evidence_idx_status` ON `lupo_decision_evidence` (`status`)
- `INDEX lupo_decision_evidence_idx_is_deleted` ON `lupo_decision_evidence` (`is_deleted`)

## Where This Table Is Used

### Core System Usage

- **BayesianDecisionService** - Evidence recording and retrieval for decision updates
- **Decision inference engine** - Evidence aggregation for posterior calculations
- **Audit systems** - Evidence trail for decision reasoning
- **Analytics** - Evidence pattern analysis and confidence scoring

### Integration Points

- **Decision workflows** - Evidence captured during decision lifecycle
- **User interactions** - User actions recorded as evidence
- **System events** - System observations stored as evidence
- **External APIs** - External data ingested as evidence

## Evidence Status Values

- `active` - Evidence currently active and usable in calculations
- `reviewed` - Evidence has been reviewed and validated
- `rejected` - Evidence deemed invalid or unreliable
- `deprecated` - Evidence superseded by newer evidence

## Evidence Types

- `user_action` - User-initiated actions or choices
- `sensor_data` - Automated sensor or monitoring data
- `expert_opinion` - Human expert assessment or judgment
- `system_event` - System-generated events or observations
- `external_api` - Data from external systems or APIs

## Namespace

- **Domain:** Core
- **Subdomain:** Decision Management
- **Related Tables:** `lupo_decisions`, `lupo_decision_influences`, `lupo_decision_updates`
