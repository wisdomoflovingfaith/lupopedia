---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_anubis_recovery_attempts.md
  channel_id: 1
  actor_id: 103
  last_modified_utc: '20260312'
  artifact_type: table_documentation
  purpose: Tracking varied strategies for file recovery
  mood_rgb: 4169E1
  traits:
  - canonical
  - anubis
  - recovery
  - v4.0.70
  tags:
  - database
  - anubis
  - recovery
  - strategies
  lupo_agent: antigravity
  when_updated: '20260324174654'
lupopedia.edges:
  outbound_edges:
  - to: lupo-database/lupopedia/toon/lupo_anubis_recovery_attempts.toon
    type: references
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_anubis_queue.md
    type: references
    weight: 1.0
lupopedia.footer:
  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Table Overview: lupo_anubis_recovery_attempts

- **Purpose**: Tracks specific programmatic strategies used by ANUBIS to recover or repair files. It allows the system to cycle through different models or templates without repeating failed tactics.
- **Category**: Import / Reconciliation
- **Status**: Active
- **Version Introduced**: 4.0.0

## Column Documentation

| Column Name | Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `attempt_id` | BIGINT | No | - | Primary Key. |
| `queue_id` | BIGINT | No | - | Associated `lupo_anubis_queue` item. |
| `attempt_number` | TINYINT | No | - | Sequence number of the attempt. |
| `attempt_utc` | BIGINT | No | - | Time of attempt. |
| `strategy` | VARCHAR(64) | Yes | - | Strategy used (e.g., 'TEMPLATE_GENERATION', 'ACTOR_INFERENCE'). |
| `success` | TINYINT | Yes | 0 | Flag (1=success). |
| `generated_header` | TEXT | Yes | - | The header block generated during this attempt. |
| `error_details` | TEXT | Yes | - | JSON error feedback if the attempt failed. |
| `recovered_file_path` | VARCHAR(512) | Yes | - | Path where the successfully recovered file was saved. |

## Usage Notes

- **Strategic Diversification**: ANUBIS iterates through strategies defined in `AnubisRecoveryFactory` until success is achieved or the max retry limit is hit.

---
*Created by Antigravity (Actor 103) as part of the Database Documentation Program.*
