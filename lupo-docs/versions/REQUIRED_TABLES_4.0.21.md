# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\REQUIRED_TABLES_4.0.21.md"
  file_hash: "b000338a2c7ad46631f65ef6b8738556bfeb8a42105362e2d721b48e8a77e76c"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\REQUIRED_TABLES_4.0.21.md"
  file_hash: "09e2835bf4abdcee96c21bab632c11a85b30ff9cc2809bddde5742075c5f8685"
  file_path_from_root: "lupo-docs\REQUIRED_TABLES_4.0.21.md"
  file_hash: "14f533c725cd043831aa8cc9f45a86cf7bef81c70dcdad1a3c3fef105d252db5"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Required Tables for Lupopedia 4.0.21 (Patch-Only)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "required_tables_4021md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Required Tables for Lupopedia 4.0.21 (Patch-Only)

**Version:** 4.0.21 (patch-only; no 4.1.x in this document)  
**Canonical install:** `lupo-database/migrations/install_new_lupopedia.sql`  
**Future-features definitions:** `lupo-database/migrations/future_features_lupopedia.sql`  
**TOON source:** `lupo-docs/toons/*.toon.json` (schema oracle; generated from install)  
**Audit authority:** lupo-docs/audits/4.0.21_SCHEMA_VALIDATION_PHASE1_AUDIT.md, lupo-docs/audits/4.0.21_SCHEMA_VALIDATION_PHASE2_AUDIT.md  
**Upgrade path:** Crafty Syntax 3.7.5 → Lupopedia 4.0.x (ONLY supported path)

---

## Doctrine: Required vs Optional vs Future Features

- **Required tables** = All tables referenced in `import_from_old_crafty_syntax.sql` **plus** all tables used by active PHP (repositories, services, controllers, models), the wizard/installer, seed logic, and runtime features that are actually implemented. Required tables are created only by `install_new_lupopedia.sql`. No required table may be removed or moved into `future_features_lupopedia.sql`.
- **Optional tables** = Tables in `install_new_lupopedia.sql` that are not required by importer or core runtime; may be considered for future_features in a later pass.
- **Future features tables** = Tables whose definitions live **only** in `future_features_lupopedia.sql`; they are **not** created by `install_new_lupopedia.sql`.
- **Importer protection:** No table that appears in `import_from_old_crafty_syntax.sql` may ever be removed or moved to `future_features_lupopedia.sql`.

---

## Session, Roles, and Scope

- **Session table:** `{prefix}sessions`. The table `{prefix}sessions` is obsolete and has been removed from the install.
- **Roles (3-layer model):** (1) Channel roles (`{prefix}actor_channel_roles`: captain, administrator, monitor); (2) Department roles (`{prefix}department_roles`); (3) System roles (department_id = 0). Resolution order: channel → department → system.
- **Organizational scope:** The sole organizational unit is the **department**. Use `{prefix}departments` and `{prefix}actor_departments`. Department 0 is reserved (system department); not user-selectable.

---

## Required Crafty Syntax Compatibility Tables (Importer)

These tables are targets of `import_from_old_crafty_syntax.sql`. They **must** remain in `install_new_lupopedia.sql` and must never be moved to future_features.

| Table | Classification |
|-------|----------------|
| lupo_actor_departments | required / importer |
| lupo_actor_reply_templates | required / importer |
| lupo_audit_log | required / importer |
| lupo_auth_users | required / importer |
| lupo_collection_tabs | required / importer |
| lupo_collections | required / importer |
| lupo_crafty_syntax_auto_invite | required / importer |
| lupo_crafty_syntax_chat_questions | required / importer |
| lupo_crafty_syntax_layer_invites | required / importer |
| lupo_crafty_syntax_leave_message | required / importer |
| lupo_crm_lead_messages | required / importer |
| lupo_crm_leads | required / importer |
| lupo_department_metadata | required / importer |
| lupo_departments | required / importer + seed |
| lupo_dialog_messages | required / importer |
| lupo_dialog_threads | required / importer |
| lupo_federation_nodes | required / importer |
| lupo_modules | required / importer (UPDATE) + seed |
| lupo_truth_answers | required / importer |
| lupo_truth_questions | required / importer |
| lupo_analytics_paths | required / importer |
| lupo_referers | required / importer |
| lupo_visits | required / importer |
| lupo_analytics_visits_daily | required / importer |
| lupo_analytics_visits_monthly | required / importer |

---

## Required Lupopedia Core Tables (in install_new_lupopedia.sql)

All tables in this section are in `install_new_lupopedia.sql` and have TOON files in `lupo-docs/toons/`. Schema is defined by TOONs (generated from install). Count: 198 tables in install (Phase 1 + Phase 2); four tables are in future_features only.

**Required** = importer targets (above) plus tables used by seed, wizard, or active PHP/runtime. The following list is the full set of tables that have TOONs and are in the install file; classification (required vs optional) is per Phase 1/Phase 2 audits.

- All Phase 1 tables (81): session, actor/auth/agent, content — see lupo-docs/audits/4.0.21_SCHEMA_VALIDATION_PHASE1_AUDIT.md.
- All Phase 2 tables (117): analytics, api, anubis, atoms, audit_log, bans_log, calibration, cip, contexts, crafty_syntax, crm, department_metadata, departments, doctrine_*, emotional_*, entity_properties, event_*, federation_*, gov_*, governance_overrides, help_*, hotfix_registry, human_history_meta, interface_translations, interpretation_log, kapu_*, labs_*, legacy_content_mapping, memory_*, meta_log_events, metrics_archive_legacy, modules, modules_departments, mood_*, multi_agent_critique_sync, notifications, pack_role_registry, persona_*, reference_*, relationships, search_*, semantic_*, system_*, tab_events, temporal_coherence_snapshots, tldnr, truth_*, *, user_comments, world_* — see lupo-docs/audits/4.0.21_SCHEMA_VALIDATION_PHASE2_AUDIT.md.

*(Explicit enumeration matches the TOON filenames in lupo-docs/toons/; no table that is only in future_features_lupopedia.sql is listed here.)*

---

## Future Features Tables (in future_features_lupopedia.sql only)

These tables are **not** created by `install_new_lupopedia.sql`. Their definitions live in `lupo-database/migrations/future_features_lupopedia.sql` and/or `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql` (v4.0.57+).

| Table | Classification |
|-------|----------------|
| lupo_integration_test_results | future-features |
| lupo_memory_debug_log | future-features |
| lupo_narrative_fragments | future-features |
| lupo_test_performance_metrics | future-features |
| lupo_aliases | future-features (moved from install 4.0.57) |
| lupo_anubis_orphaned | future-features (moved from install 4.0.57) |
| lupo_tldnr | future-features (moved from install 4.0.57) |

---

## Optional Tables (in install_new_lupopedia.sql)

These tables remain in `install_new_lupopedia.sql` but are optional (not required by importer or core runtime). They may be considered for a later move to `future_features_lupopedia.sql` if desired. (lupo_analytics_visits_daily and lupo_analytics_visits_monthly are importer targets and are required.)

- lupo_analytics_referers_periods
- lupo_analytics_visits_periods
- lupo_anubis_mirrored
- lupo_anubis_redirects
- lupo_anubis_revised
- lupo_gov_event_actor_edges
- lupo_gov_event_conflicts
- lupo_gov_event_dependencies
- lupo_gov_event_references
- lupo_gov_events
- lupo_gov_timeline_nodes
- lupo_gov_valuations
- lupo_governance_overrides
- lupo_hotfix_registry
- lupo_legacy_content_mapping
- lupo_multi_agent_critique_sync
- lupo_pack_role_registry
- lupo_persona_dialogue_patterns
- lupo_persona_profiles

---

## Classification summary (4.0.21)

| Classification | Description |
|----------------|-------------|
| **required** | In install; referenced by importer and/or seed/wizard/runtime. Must not be moved to future_features. |
| **optional** | In install; not required by importer or core runtime; may be moved to future_features later. |
| **runtime-only** | Populated only at runtime; no seed or importer. |
| **importer-only** | Populated only by import_from_old_crafty_syntax.sql (except where also seeded). |
| **future-features** | Defined only in future_features_lupopedia.sql; not created by install. |

---

*See also: `lupo-docs/audits/4.0.21_SCHEMA_VALIDATION_PHASE1_AUDIT.md`, `lupo-docs/audits/4.0.21_SCHEMA_VALIDATION_PHASE2_AUDIT.md`, `lupo-docs/audits/FUTURE_FEATURES_AND_REQUIRED_TABLES_ALIGNMENT_SUMMARY.md`.*
