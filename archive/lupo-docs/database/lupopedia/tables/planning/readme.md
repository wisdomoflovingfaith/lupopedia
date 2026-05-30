---`nlupopedia.footer:
  approved_for_release: "4.1.0"
  approval_status: "approved"
  approved_by_actor_id: 1
  approved_utc: 20260326192115lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/planning/README.md
  questions_toon: null
  channel_id: 42
  artifact_type: documentation
  artifact_kind: index
  purpose: Clarify planning folder role vs tables/active authority; prevent duplication
    drift.
  when_updated: '20260324174654'
lupopedia:
  footer:
    last_verified: '20260324174654'
    last_verified_by: cursor
    last_verified_by_actor_id: 102
    orchestrator: cursor:root
---

# Planning Folder â€” Authority and Usage

## Purpose

This folder holds **planning** or **future-oriented** table documentation (e.g. TOON-style placeholders, tables in `future_features_lupopedia.sql`, or not yet in install). It is **not** the source of truth for tables that are implemented and documented elsewhere.

## Rule

- **If a table exists in install SQL and has a doc in `tables/active/`, the `tables/active/` doc is the source of truth.** Planning docs in this folder must not be treated as authoritative for that table.
- **Planning docs** here may still exist for historical, optional, or future-oriented reasons. They are not authoritative for implemented tables.
- **Do not duplicate:** Do not create or maintain two competing authoritative docs for the same implemented table. Prefer a single doc in `tables/active/` and treat planning artifacts as superseded or supplementary.

## Canonical locations

- **Schema authority:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- **Implemented table docs:** `lupo-docs/database/lupopedia/tables/active/*.md`
- **Cross-domain reference:** `lupo-docs/database/lupopedia/tables/lupopedia_actors_collections_organization_reference.md`
- **Doctrine:** `lupo-docs/doctrine/DATABASE_DOCTRINE.md`, `lupo-docs/doctrine/COLLECTIONS_DOCTRINE.md`, etc.

Planning docs in this folder are not moved or deleted by default; they are clarified as non-authoritative when the table is implemented and documented in `tables/active/`.

