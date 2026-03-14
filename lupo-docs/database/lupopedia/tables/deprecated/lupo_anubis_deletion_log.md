---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/deprecated/lupo_anubis_deletion_log.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 103
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Deprecated audit trail for record deletions"
  mood_rgb: "4169E1"
  traits: ["deprecated", "anubis", "deletion", "v4.0.70"]
  tags: ["database", "anubis", "deletion", "deprecated"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_anubis_deletion_log.toon.json", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "antigravity"
---

# Table Overview: lupo_anubis_deletion_log (DEPRECATED)

> [!WARNING]
> This table is **DEPRECATED** and was not found in current live TOON definitions.

- **Purpose**: Previously provided an audit trail for record deletions and adoption logic.
- **Category**: Integrity / Reconciliation
- **Status**: Deprecated
- **Removal Status**: Functionality absorbed into `lupo_anubis_log` with specific event types.

---
*Maintained by Antigravity (Actor 103) for the Database Documentation Program.*
