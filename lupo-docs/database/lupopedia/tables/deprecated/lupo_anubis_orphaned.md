---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/deprecated/lupo_anubis_orphaned.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 103
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Deprecated quarantine buffer for data fragments"
  mood_rgb: "4169E1"
  traits: ["deprecated", "anubis", "v4.0.70"]
  tags: ["database", "anubis", "orphaned", "deprecated"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_anubis_orphaned.toon.json", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "antigravity"
---

# Table Overview: lupo_anubis_orphaned (DEPRECATED)

> [!WARNING]
> This table is **DEPRECATED** and was not found in current live TOON definitions.

- **Purpose**: Previously served as a quarantine buffer for data fragments awaiting parent assignment.
- **Category**: Remediation / Reconciliation
- **Status**: Deprecated
- **Removal Status**: Functionality merged into the unified `lupo_anubis_quarantine` table.

---
*Maintained by Antigravity (Actor 103) for the Database Documentation Program.*
