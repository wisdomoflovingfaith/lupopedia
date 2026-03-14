# Planned Tables — Implementation Review and Optimization Plan

**Purpose:** Before adding any of the 55 planned tables from `future_features_lupopedia.sql` into the main install, review each (or each group) for: (1) consolidation with existing tables, (2) schema/doctrine alignment, (3) optimization and deferral.  
**Related:** [PLANNED_TABLES_NOT_CREATED_REPORT.md](PLANNED_TABLES_NOT_CREATED_REPORT.md)

---

## 1. Objectives

1. **Avoid duplicate concepts** — Prefer extending or reusing existing tables instead of adding new ones where the use case overlaps.
2. **Doctrine compliance** — No FKs, triggers, or DB-side logic; BIGINT UTC YmdHis timestamps; PK naming `<table>_id`; reserved-ID handling where applicable.
3. **Reduce table count** — Combine or drop planned tables that are redundant with install tables or with each other.
4. **Prioritize by need** — Only promote to install when a concrete feature or migration path requires the table.

---

## 2. Implementation Review Workflow

For each planned table (or logical group):

| Step | Action | Output |
|------|--------|--------|
| **2.1** | Check if an **existing install table** already covers the same data or use case (e.g. `lupo_metadata`, `lupo_edges`, `lupo_audit_log`, `lupo_artifact_chunks`). | “Keep as planned” / “Merge into X” / “Drop from plan” |
| **2.2** | Check if **another planned table** can be merged (e.g. multiple gov_* tables → one with type/discriminator). | Consolidation note |
| **2.3** | Validate **future_features DDL** against doctrine (no FK/trigger/UNSIGNED; timestamps BIGINT YmdHis; PK name). | Fix list for future_features_lupopedia.sql |
| **2.4** | Decide **priority**: Required for 4.0.x / Required for 4.1.0 / Defer / Drop. | Priority label |
| **2.5** | If “merge into existing,” document the mapping (columns → existing table) and any migration for existing future_features references. | Mapping / migration note |

---

## 3. Suggested Group Reviews

### 3.1 Actor / identity (6 tables)

- **lupo_actor_aliases**, **lupo_aliases** — Consider merging into one “aliases” table with `alias_type` or `entity_type` (actor vs generic), or storing in `lupo_metadata` / actor properties.
- **lupo_actor_object_edges**, **lupo_actor_truth_edges** — Compare with `lupo_edges` and `lupo_actor_edges`; consider extending existing edge tables with `edge_type` / `source` instead of new tables.
- **lupo_actor_persona_relationships**, **lupo_actor_relationship_rules** — Evaluate whether `lupo_actor_edges` + metadata or a single “actor_relationships” table can cover both.

### 3.2 ANUBIS (4 tables)

- **lupo_anubis_deletion_log**, **lupo_anubis_mirrored**, **lupo_anubis_orphaned**, **lupo_anubis_revised** — Compare with existing `lupo_anubis_log`, `lupo_anubis_events`, `lupo_anubis_queue`, `lupo_anubis_processing_log`, `lupo_anubis_quarantine`. Consider one “anubis_audit” or “anubis_operations” table with `operation_type` instead of four separate tables.

### 3.3 Governance / gov_* (8 tables)

- **lupo_gov_events**, **lupo_gov_event_actor_edges**, **lupo_gov_event_conflicts**, **lupo_gov_event_dependencies**, **lupo_gov_event_references**, **lupo_gov_timeline_nodes**, **lupo_gov_valuations** — Strong candidate for consolidation: one or two tables (e.g. `lupo_gov_events` + `lupo_gov_event_relations` with relation_type) instead of seven.

### 3.4 Emotional / persona (6 tables)

- **lupo_emotional_constellations**, **lupo_emotional_stars**, **lupo_emotional_translations**, **lupo_entity_properties**, **lupo_persona_dialogue_patterns**, **lupo_persona_profiles** — Check overlap with `lupo_actor_moods`, `lupo_metadata`, and existing persona/actor columns; consider JSON in `lupo_metadata` or a single “persona_*” table.

### 3.5 Content / references / search (7 tables)

- **lupo_comments**, **lupo_document_embeddings**, **lupo_documentation_frameworks**, **lupo_legacy_content_mapping**, **lupo_reference_cited_by**, **lupo_reference_objects**, **lupo_search_index** — `lupo_contents` and `lupo_metadata` may cover comments and references; document_embeddings and search_index may be 4.1.0 or external (e.g. search service). Clarify ownership and defer if not needed for 4.0.x.

### 3.6 Federation / FLARE (2 tables)

- **lupo_federated_trust**, **lupo_federation_discovery** — Align with `lupo_federation_nodes`, `lupo_federation_categories`, `lupo_federation_category_map`. Consider extending those or one “federation_*” table.
- **lupo_flare_headers** — Deprecated in favor of LUPOPEDIA HEADERS; consider **drop from plan** or mark as legacy-only.

### 3.7 Logging / sessions / tasks (5 tables)

- **lupo_unified_log** — May supersede or complement `lupo_audit_log`, `lupo_anubis_log`; define clear scope (e.g. “all events” vs “audit only”) and whether to merge into one install table.
- **lupo_session_recovery** — Compare with `lupo_sessions`; may be a subset or extension (e.g. recovery_metadata in sessions or a small recovery table).
- **lupo_task_assignments**, **lupo_task_dependencies** — If no `lupo_tasks` in install yet, decide if tasks are in scope for 4.0.x; if yes, consider one `lupo_tasks` table with JSON for dependencies/assignments or two minimal tables.

### 3.8 Misc / ops (remaining)

- **lupo_tldnr**, **lupo_system_health_snapshots**, **lupo_temporal_coherence_snapshots** — Likely defer or drop unless a concrete 4.0.x feature needs them.
- **lupo_hashtags**, **lupo_interface_translations**, **lupo_human_history_meta**, **lupo_llm_performance**, **lupo_metrics_archive_legacy** — Review per feature; many can stay in future_features until required.
- **lupo_mood_assignments**, **lupo_mood_registry** — Overlap with `lupo_actor_moods`; consider one mood model.
- **lupo_modules_departments**, **lupo_pack_role_registry**, **lupo_registry_import**, **lupo_orchestrator_rules**, **lupo_hotfix_registry**, **lupo_kapu_***, **lupo_channel_boot_log** — Review against existing modules, channels, and boot tables; consolidate or defer.

---

## 4. Execution Checklist

- [ ] Run group reviews (3.1–3.8); for each table: keep / merge / defer / drop.
- [ ] Update `future_features_lupopedia.sql`: remove or merge DDL for dropped/merged tables; fix doctrine violations.
- [ ] Document consolidation decisions in this file or in `lupo-docs/database/lupopedia/tables/active/planning/`.
- [ ] For any table promoted to install: add to `install_new_lupopedia.sql`, regenerate TOONs, update REQUIRED_TABLES and doctrine docs.
- [ ] Re-run `generate_toon_files.py` after any install change; confirm count (145 + promoted only).

---

## 5. References

- **Planned tables report:** [PLANNED_TABLES_NOT_CREATED_REPORT.md](PLANNED_TABLES_NOT_CREATED_REPORT.md)
- **Future-features doctrine:** `lupo-rules/root/required-tables-future-features-doctrine.md`
- **Schema source:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`, `future_features_lupopedia.sql`
- **Planning TOONs:** `lupo-docs/database/lupopedia/tables/active/planning/`
- **LUPOPEDIA HEADERS (replaces FLARE):** `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`
