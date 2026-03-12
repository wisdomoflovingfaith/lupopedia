---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/deprecated/lupo_anubis_mirrored.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 103
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Deprecated record-level lineage mirroring table"
  mood_rgb: "4169E1"
  traits: ["deprecated", "anubis", "lineage", "v4.0.70"]
  tags: ["database", "anubis", "mirrored", "deprecated"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_anubis_mirrored.toon.json", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "antigravity"
---

# Table Overview: lupo_anubis_mirrored (DEPRECATED)

> [!WARNING]
> This table is **DEPRECATED** and was not found in current live TOON definitions. It was part of the early ANUBIS custodial suite.

- **Purpose**: Previously tracked replicated or shifted records for traceable lineage within the semantic graph.
- **Category**: Import / Reconciliation
- **Status**: Deprecated
- **Removal Status**: Superseded by `lupo_anubis_redirects` and enhanced audit logging in version 4.0.57.

---
*Maintained by Antigravity (Actor 103) for the Database Documentation Program.*
