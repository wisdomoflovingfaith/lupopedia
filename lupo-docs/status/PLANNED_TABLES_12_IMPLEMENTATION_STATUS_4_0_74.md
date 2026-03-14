# 12-table install expansion — implementation status (v4.0.74)

**Directive:** 20260314 Cursor planned-tables implementation directive  
**Completed:** 2026-03-14  
**Authority:** wolfie:root (directive); Cursor (implementation)

---

## Summary

The approved **12-table subset** from `future_features_lupopedia.sql` has been implemented in the install path for Lupopedia v4.0.74. No deferred tables were added; all other planned tables remain in `future_features_lupopedia.sql`.

---

## Tables added to install

| # | Table | Purpose (from directive) |
|---|--------|---------------------------|
| 1 | `lupo_aliases` | Generic slug/alias mapping for routing and redirects |
| 2 | `lupo_legacy_content_mapping` | Legacy URL → semantic URL mapping (Crafty migration) |
| 3 | `lupo_reference_objects` | Canonical reference/citation objects |
| 4 | `lupo_reference_cited_by` | Content ↔ reference linkage |
| 5 | `lupo_search_index` | In-database search index |
| 6 | `lupo_documentation_frameworks` | Documentation governance / LUPOPEDIA HEADERS alignment |
| 7 | `lupo_federated_trust` | Trust/capabilities between federation nodes |
| 8 | `lupo_federation_discovery` | Discovered federation instances |
| 9 | `lupo_unified_log` | Consolidated log table (multiple log types) |
| 10 | `lupo_anubis_operations` | Unified ANUBIS audit (operation_type discriminator) |
| 11 | `lupo_system_health_snapshots` | System/schema health snapshots |
| 12 | `lupo_hotfix_registry` | Registry of applied hotfixes |

---

## Deliverables completed

| Deliverable | Status | Location |
|-------------|--------|----------|
| Updated install SQL | Done | `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` |
| future_features cleanup | Done | 12 tables annotated as "MOVED TO install v4.0.74" in `future_features_lupopedia.sql` |
| One-time migration | Done | `lupo-database/lupopedia/mysql/migrations/migration_20260314_12_table_install_expansion_v4_0_74.sql` |
| Table count doctrine | Updated | `lupo-docs/doctrine/TABLE_COUNT_DOCTRINE.md` (112 tables) |
| Migrations README | Updated | `lupo-database/lupopedia/mysql/migrations/README.md` |
| CHANGELOG | Updated | `CHANGELOG.md` (12-table subsection under 4.0.74) |
| Status report | Done | This file |

---

## Install table count

| Stage | Count | Notes |
|-------|--------|------|
| After 12-table expansion | **159** | `generate_toon_from_sql.py` generated 159 TOONs from install file. |
| Projected Crafty upgrade context (159 + 34 legacy) | 193 | Under advisory 199 ceiling. |

*Canonical count: number of `CREATE TABLE` definitions in `install_new_lupopedia.sql` (one line is commented `-- CREATE TABLE`).*

---

## Deferred tables (unchanged)

All other tables in `future_features_lupopedia.sql` remain **deferred**, including:

- `lupo_document_embeddings`, `lupo_interface_translations`, `lupo_session_recovery`, `lupo_channel_boot_log`, `lupo_registry_import`, `lupo_modules_departments`, `lupo_persona_profiles`, `lupo_actor_aliases`
- All `gov_*` tables; actor rule/truth/persona suites; emotional/constellation/translation suites; task suite; kapu, mood, pack role, llm performance, analytics period tables; human history meta; metrics archive legacy; tldnr; etc.

---

## Doctrine compliance

- No foreign keys, triggers, procedures, or views added.
- Timestamps use BIGINT UTC `YmdHis` where applicable.
- Primary keys follow project naming (`*_id`).
- `lupo_aliases`: `created_at` normalized to `created_ymdhis`; `alias_id` to bigint.
- `lupo_unified_log`: `log_type`/`log_level` stored as varchar (no ENUM) for portability; `actor_id`/`channel_id` as bigint.
- `lupo_hotfix_registry`: `hotfix_id` and `applied_by_actor_id` as bigint.

---

## Validation

- **Fresh install:** Run `install_new_lupopedia.sql` (and seed) to confirm all 12 tables are created.
- **Crafty 3.7.5 upgrade:** Unchanged; upgrade path remains install + seed after Crafty import.
- **Existing Lupopedia install:** Run the one-time migration once to add the 12 tables without re-running the full install.

---

## References

- Directive: `lupo-prompts/cursor/20260314_cursor_planned_tables_implementation_directive_4_0_74.md` (or equivalent path).
- Planned tables report: `lupo-docs/status/PLANNED_TABLES_REPORT_AND_CANDIDATES_4.0.74.md`
- Required-tables doctrine: `lupo-rules/root/required-tables-future-features-doctrine.md`
