---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/deprecated/lupo_flip_artifacts.md
  channel_id: 1
  actor_id: 103
  last_modified_utc: '20260312'
  artifact_type: table_documentation
  purpose: Deprecated FLIP artifact tracking table
  mood_rgb: 4169E1
  traits:
  - deprecated
  - flip
  - v4.0.70
  tags:
  - database
  - artifacts
  - flip
  - deprecated
  lupo_agent: antigravity
  when_updated: '20260324174654'
lupopedia.edges:
  outbound_edges:
  - to: lupo-database/lupopedia/toon/lupo_flip_artifacts.toon.json
    type: references
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_artifacts.md
    type: references
    weight: 0.9
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Table Overview: lupo_flip_artifacts (DEPRECATED)

> [!WARNING]
> This table is **DEPRECATED** and relates to the legacy **FLIP** (Flare Library Integration Protocol) branding used in Antigravity versions prior to 4.0.50. It is missing from current live TOON definitions.

- **Purpose**: Previously tracked file-backed artifacts, delegation chains, and cross-forwarding between actors using the FLIP header protocol.
- **Category**: Content Management / Storage
- **Status**: Deprecated
- **Removal Status**: Functionality absorbed into the unified `lupo_artifacts` table and FLARE header management in 4.0.57.

## Column Documentation (Last Known)

| Column Name | Type | Description |
| :--- | :--- | :--- |
| `flip_artifact_id` | BIGINT | Primary Key. |
| `file_path_from_root` | VARCHAR(500) | Filesystem path tracking. |
| `artifact_kind` | VARCHAR(50) | Logic artifact type. |
| `channel_id` | BIGINT | Associated channel. |
| `actor_id` | BIGINT | Generating actor. |
| `agent_slug` | VARCHAR(255) | Slug of the generating agent. |
| `agent_type` | VARCHAR(64) | Type of the agent. |
| `system_version` | VARCHAR(20) | Version at time of creation. |
| `last_modified_ymd` | BIGINT | YYYYMMDD tracking. |
| `x_forward_from_actor_id` | BIGINT | Delegation chain source. |
| `x_forward_to_actor_id` | BIGINT | Delegation chain target. |
| `header_json` | TEXT | Raw JSON of the FLARE/FLIP headers. |
| `file_hash` | VARCHAR(64) | Integrity hash. |

## Usage Notes

- **Migration Impact**: Current systems use `lupo_artifacts.metadata` (JSON) to store delegation and header data that was previously normalized in this table.
- **Historical Context**: This table was central to the "FLIP" naming convention before the project standardized on the "FLARE" terminology.

---
*Maintained by Antigravity (Actor 103) for the Database Documentation Program.*

