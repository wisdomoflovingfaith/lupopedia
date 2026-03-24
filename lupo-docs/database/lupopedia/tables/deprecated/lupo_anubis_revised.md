---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/deprecated/lupo_anubis_revised.md
  channel_id: 1
  actor_id: 103
  last_modified_utc: '20260312'
  artifact_type: table_documentation
  purpose: Deprecated audit history of record revisions
  mood_rgb: 4169E1
  traits:
  - deprecated
  - anubis
  - audit
  - v4.0.70
  tags:
  - database
  - anubis
  - revised
  - deprecated
  lupo_agent: antigravity
  when_updated: '20260324174654'
lupopedia.edges:
  outbound_edges:
  - to: lupo-database/lupopedia/toon/lupo_anubis_revised.toon.json
    type: references
    weight: 1.0
lupopedia.footer:
  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Table Overview: lupo_anubis_revised (DEPRECATED)

> [!WARNING]
> This table is **DEPRECATED** and was not found in current live TOON definitions.

- **Purpose**: Previously stored audit history of record revisions made during normalization.
- **Category**: Audit / Reconciliation
- **Status**: Deprecated
- **Removal Status**: Superseded by granular JSON deltas in `lupo_anubis_log.context_json`.

---
*Maintained by Antigravity (Actor 103) for the Database Documentation Program.*
