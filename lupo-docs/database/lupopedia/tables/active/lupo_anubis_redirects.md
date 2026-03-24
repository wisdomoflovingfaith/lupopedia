---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_anubis_redirects.md
  channel_id: 1
  actor_id: 103
  last_modified_utc: '20260312'
  artifact_type: table_documentation
  purpose: Registry for record-level redirects following reconciliation
  mood_rgb: 4169E1
  traits:
  - canonical
  - anubis
  - integrity
  - v4.0.70
  tags:
  - database
  - anubis
  - redirects
  - reconciliation
  lupo_agent: antigravity
  when_updated: '20260324174654'
lupopedia.edges:
  outbound_edges:
  - to: lupo-database/lupopedia/toon/lupo_anubis_redirects.toon
    type: references
    weight: 1.0
lupopedia.footer:
  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Table Overview: lupo_anubis_redirects

- **Purpose**: Maps old IDs to new IDs following record consolidation or duplication resolution by ANUBIS. This ensures that legacy links or external references do not break when records are merged.
- **Category**: Integrity / Reconciliation
- **Status**: Active
- **Version Introduced**: 4.0.0

## Column Documentation

| Column Name | Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `anubis_redirect_id` | BIGINT | No | - | Primary Key. |
| `table_name` | VARCHAR(255) | No | - | The table where the ID migration occurred. |
| `old_id` | BIGINT | No | - | The former/obsolete ID. |
| `new_id` | BIGINT | No | - | The current/canonical ID. |
| `created_ymdhis` | BIGINT | No | - | Creation timestamp. |
| `updated_ymdhis` | BIGINT | No | - | Update timestamp. |
| `agent` | VARCHAR(255) | No | - | The agent that authorized the redirect. |

## Usage Notes

- **Application Logic**: Low-level DB wrappers or services check this table when a requested ID is not found in the target table.
- **Maintenance**: Redirects should be permanent to avoid link rot within the semantic graph.

---
*Created by Antigravity (Actor 103) as part of the Database Documentation Program.*
