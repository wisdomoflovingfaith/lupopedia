---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: docs/database/lupopedia/tables/deprecated/lupo_anubis_mirrored.md
  channel_id: 1
  actor_id: 103
  questions_toon: null
  artifact_type: table_documentation
  purpose: Deprecated record-level lineage mirroring table
  mood_vector: 4169E1
  traits:
  - deprecated
  - anubis
  - lineage
  - v4.0.70
  tags:
  - database
  - anubis
  - mirrored
  - deprecated
  lupo_agent: antigravity
  when_updated: '20260324174654'
lupopedia.edges:
  outbound_edges:
  - to: database/lupopedia/toon/lupo_anubis_mirrored.toon.json
    type: references
    weight: 1.0
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
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

