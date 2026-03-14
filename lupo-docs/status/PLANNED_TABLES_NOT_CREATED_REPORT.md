# Planned Tables Not Created — Report

**Generated:** 2026-03-14  
**Source:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (created) vs `future_features_lupopedia.sql` (planned)  
**Context:** `generate_toon_files.py` generated **145** TOONs (tables in live DB / install). KIRO’s historical count of **222** tables referred to a broader TOON inventory (e.g. legacy `livehelp_*` + planning). This report lists only **planned-but-not-created** tables: those in `future_features_lupopedia.sql` that are not in the current install.

---

## Summary

| Metric | Count |
|--------|--------|
| Tables in install (created) | 145 |
| Tables in future_features (planned) | 55 |
| **Planned tables not created** | **55** |

All 55 tables in `future_features_lupopedia.sql` are **planned** and **not** in the current install. They are not created by the standard install or by `generate_toon_files.py` (which reflects the live DB).

---

## Planned Tables Not Created (55)

Canonical list from `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`. Per-table planning docs exist under `lupo-docs/database/lupopedia/tables/active/planning/` (e.g. `table_<name>.toon.md`).

### By domain

**Actor / identity**
- `lupo_actor_aliases`
- `lupo_actor_object_edges`
- `lupo_actor_persona_relationships`
- `lupo_actor_relationship_rules`
- `lupo_actor_truth_edges`
- `lupo_aliases`

**ANUBIS / audit**
- `lupo_anubis_deletion_log`
- `lupo_anubis_mirrored`
- `lupo_anubis_orphaned`
- `lupo_anubis_revised`

**Analytics / referers**
- `lupo_analytics_referers_periods`

**Channel / boot**
- `lupo_channel_boot_log`

**Content / docs / search**
- `lupo_comments`
- `lupo_document_embeddings`
- `lupo_documentation_frameworks`
- `lupo_legacy_content_mapping`
- `lupo_reference_cited_by`
- `lupo_reference_objects`
- `lupo_search_index`

**Emotional / persona**
- `lupo_emotional_constellations`
- `lupo_emotional_stars`
- `lupo_emotional_translations`
- `lupo_entity_properties`
- `lupo_persona_dialogue_patterns`
- `lupo_persona_profiles`

**Federation / governance**
- `lupo_federated_trust`
- `lupo_federation_discovery`
- `lupo_flare_headers` (legacy FLIP/FLARE; see LUPOPEDIA HEADERS doctrine)
- `lupo_gov_event_actor_edges`
- `lupo_gov_event_conflicts`
- `lupo_gov_event_dependencies`
- `lupo_gov_event_references`
- `lupo_gov_events`
- `lupo_gov_timeline_nodes`
- `lupo_gov_valuations`

**Misc / ops**
- `lupo_hashtags`
- `lupo_hotfix_registry`
- `lupo_human_history_meta`
- `lupo_interface_translations`
- `lupo_kapu_events`
- `lupo_kapu_restoration_paths`
- `lupo_llm_performance`
- `lupo_metrics_archive_legacy`
- `lupo_modules_departments`
- `lupo_mood_assignments`
- `lupo_mood_registry`
- `lupo_pack_role_registry`
- `lupo_registry_import`
- `lupo_orchestrator_rules`
- `lupo_session_recovery`
- `lupo_task_assignments`
- `lupo_task_dependencies`
- `lupo_temporal_coherence_snapshots`
- `lupo_tldnr`
- `lupo_system_health_snapshots`
- `lupo_unified_log`

---

## References

- **Install (created):** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- **Planned (not run at install):** `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`
- **Planning docs:** `lupo-docs/database/lupopedia/tables/active/planning/`
- **Required-tables doctrine:** `lupo-rules/root/required-tables-future-features-doctrine.md` — future-features tables stay out of install until required.
