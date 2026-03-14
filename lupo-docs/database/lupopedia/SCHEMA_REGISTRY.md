---
lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/SCHEMA_REGISTRY.md"
  system_version: "4.0.74"
  channel_id: 42
  actor_id: 102
  last_modified_utc: "20260314"
  artifact_type: "schema_registry"
  purpose: "Master schema inventory: install SQL, TOONs, table docs, migration refs, domain assignment"
  mood_rgb: "4169E1"
  traits: ["canonical", "schema_registry", "v4.0.74"]
  tags: ["database", "schema", "registry", "coordination"]
  lupo_agent: "cursor"

lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260314"
  last_verified_by: "cursor"
  orchestrator: "cursor"
---

# Schema Registry

**Source and authority:** This registry is cross-checked against **install SQL** ([lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql](lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql)), **TOON artifacts** (e.g. in `lupo-docs/toons/`), and table docs under `lupo-docs/database/lupopedia/tables/` (flat, active/, deprecated/, migrations/). **Install SQL is authoritative** where disagreements exist. TOON files are derived representations. The repo supports more than one TOON-related path; `lupo-docs/toons/` is the current in-repo TOON set.

**Agent assignment** (from MULTI_AGENT_DATABASE_DOCUMENTATION_PLAN.md; Cursor has taken over KIRO responsibilities):

| Agent | Domain |
|-------|--------|
| Cursor (acting KIRO) | Core: actor system, channels & messaging, metadata & FLARE, governance. Plus: user, auth, session, token, API, ACL, agents. |
| JetBrains | Collections, departments, knowledge, artifacts |
| Antigravity | Federation, import/export, Anubis, channel filesystem |
| Windsurf | livehelp_*, Crafty Syntax migration tables |

---

## Registry table

| Table | Domain | Description | Assigned Agent | Status | Existing Doc Path | Migration Ref | Notes |
|-------|--------|-------------|----------------|--------|-------------------|---------------|-------|
| livehelp_autoinvite | Legacy/Crafty | Legacy auto-invite | Windsurf | Migration | migrations/livehelp_autoinvite.md, migrations/livehelp_autoinvite_migration.md, active/lupo_crafty_syntax_auto_invite (target) | MIGRATION_MAPPING_REFERENCE, livehelp_autoinvite_migration | Maps to lupo_crafty_syntax_auto_invite |
| livehelp_channels | Legacy/Crafty | Legacy channels | Windsurf | Migration | migrations/livehelp_channels.md, migrations/livehelp_channels_migration.md | MIGRATION_MAPPING_REFERENCE | DROPPED; replaced by lupo_channels |
| livehelp_config | Legacy/Crafty | Legacy config | Windsurf | Migration | migrations/livehelp_config*.md | MIGRATION_MAPPING_REFERENCE | → lupo_modules.config_json |
| livehelp_departments | Legacy/Crafty | Legacy departments | Windsurf | Migration | migrations/livehelp_departments*.md | MIGRATION_MAPPING_REFERENCE | → lupo_departments, lupo_department_metadata |
| livehelp_emailque | Legacy/Crafty | Legacy email queue | Windsurf | Migration | migrations/livehelp_emailque*.md | MIGRATION_MAPPING_REFERENCE | DROPPED |
| livehelp_emails | Legacy/Crafty | Legacy emails | Windsurf | Migration | migrations/livehelp_emails*.md | MIGRATION_MAPPING_REFERENCE | → lupo_crm_lead_messages |
| livehelp_identity_daily | Legacy/Crafty | Legacy identity daily | Windsurf | Migration | migrations/livehelp_identity*.md | MIGRATION_MAPPING_REFERENCE | DROPPED |
| livehelp_identity_monthly | Legacy/Crafty | Legacy identity monthly | Windsurf | Migration | migrations/livehelp_identity*.md | MIGRATION_MAPPING_REFERENCE | DROPPED |
| livehelp_keywords_daily | Legacy/Crafty | Legacy keywords | Windsurf | Migration | migrations/livehelp_keywords*.md | MIGRATION_MAPPING_REFERENCE | DROPPED |
| livehelp_keywords_monthly | Legacy/Crafty | Legacy keywords monthly | Windsurf | Migration | migrations/livehelp_keywords*.md | MIGRATION_MAPPING_REFERENCE | DROPPED |
| livehelp_layerinvites | Legacy/Crafty | Legacy layer invites | Windsurf | Migration | migrations/livehelp_layerinvites*.md | MIGRATION_MAPPING_REFERENCE | → lupo_crafty_syntax_layer_invites |
| livehelp_leads | Legacy/Crafty | Legacy leads | Windsurf | Migration | migrations/livehelp_leads*.md | MIGRATION_MAPPING_REFERENCE | → lupo_crm_leads |
| livehelp_leavemessage | Legacy/Crafty | Legacy leave message | Windsurf | Migration | migrations/livehelp_leavemessage*.md | MIGRATION_MAPPING_REFERENCE | → lupo_crafty_syntax_leave_message |
| livehelp_messages | Legacy/Crafty | Legacy messages | Windsurf | Migration | migrations/livehelp_messages*.md | MIGRATION_MAPPING_REFERENCE | DROPPED |
| livehelp_modules | Legacy/Crafty | Legacy modules | Windsurf | Migration | migrations/livehelp_modules*.md | MIGRATION_MAPPING_REFERENCE | DROPPED |
| livehelp_modules_dep | Legacy/Crafty | Legacy modules dep | Windsurf | Migration | migrations/livehelp_modules*.md | MIGRATION_MAPPING_REFERENCE | → lupo_modules_departments |
| livehelp_operator_channels | Legacy/Crafty | Legacy operator channels | Windsurf | Migration | migrations/livehelp_operator_channels*.md | MIGRATION_MAPPING_REFERENCE | DROPPED |
| livehelp_operator_departments | Legacy/Crafty | Legacy operator departments | Windsurf | Migration | migrations/livehelp_operator_departments*.md | MIGRATION_MAPPING_REFERENCE | → lupo_actor_departments |
| livehelp_operator_history | Legacy/Crafty | Legacy operator history | Windsurf | Migration | migrations/livehelp_operator_history*.md | MIGRATION_MAPPING_REFERENCE | → lupo_audit_log |
| livehelp_paths_firsts | Legacy/Crafty | Legacy paths firsts | Windsurf | Migration | migrations/livehelp_paths_firsts*.md | MIGRATION_MAPPING_REFERENCE | → lupo_analytics_paths |
| livehelp_paths_monthly | Legacy/Crafty | Legacy paths monthly | Windsurf | Migration | migrations/livehelp_paths_monthly*.md | MIGRATION_MAPPING_REFERENCE | → lupo_analytics_paths |
| livehelp_qa | Legacy/Crafty | Legacy QA | Windsurf | Migration | migrations/livehelp_qa*.md | MIGRATION_MAPPING_REFERENCE | → lupo_truth_*, lupo_collections |
| livehelp_questions | Legacy/Crafty | Legacy questions | Windsurf | Migration | migrations/livehelp_questions*.md | MIGRATION_MAPPING_REFERENCE | → lupo_crafty_syntax_chat_questions |
| livehelp_quick | Legacy/Crafty | Legacy quick replies | Windsurf | Migration | migrations/livehelp_quick*.md | MIGRATION_MAPPING_REFERENCE | → lupo_actor_reply_templates |
| livehelp_referers_daily | Legacy/Crafty | Legacy referers daily | Windsurf | Migration | migrations/livehelp_referers_daily*.md | MIGRATION_MAPPING_REFERENCE | → lupo_referers |
| livehelp_referers_monthly | Legacy/Crafty | Legacy referers monthly | Windsurf | Migration | migrations/livehelp_referers_monthly*.md | MIGRATION_MAPPING_REFERENCE | → lupo_referers |
| livehelp_sessions | Legacy/Crafty | Legacy sessions | Windsurf | Migration | migrations/livehelp_sessions*.md | MIGRATION_MAPPING_REFERENCE | DROPPED; → lupo_sessions |
| livehelp_smilies | Legacy/Crafty | Legacy smilies | Windsurf | Migration | migrations/livehelp_smilies*.md | MIGRATION_MAPPING_REFERENCE | DROPPED |
| livehelp_transcripts | Legacy/Crafty | Legacy transcripts | Windsurf | Migration | migrations/livehelp_transcripts*.md | MIGRATION_MAPPING_REFERENCE | → lupo_dialog_threads, lupo_dialog_messages |
| livehelp_users | Legacy/Crafty | Legacy users | Windsurf | Migration | migrations/livehelp_users*.md | MIGRATION_MAPPING_REFERENCE | → lupo_auth_users, lupo_actors |
| livehelp_visit_track | Legacy/Crafty | Legacy visit track | Windsurf | Migration | migrations/livehelp_visit_track*.md | MIGRATION_MAPPING_REFERENCE | DROPPED |
| livehelp_visits_daily | Legacy/Crafty | Legacy visits daily | Windsurf | Migration | migrations/livehelp_visits_daily*.md | MIGRATION_MAPPING_REFERENCE | → lupo_visits |
| livehelp_visits_monthly | Legacy/Crafty | Legacy visits monthly | Windsurf | Migration | migrations/livehelp_visits_monthly*.md | MIGRATION_MAPPING_REFERENCE | → lupo_visits |
| livehelp_websites | Legacy/Crafty | Legacy websites | Windsurf | Migration | migrations/livehelp_websites*.md | MIGRATION_MAPPING_REFERENCE | → lupo_federation_nodes |
| lupo_actor_actions | Actor system | Actor actions | Cursor (KIRO) | Active | tables/lupo_actor_actions.md | — | |
| lupo_actor_aliases | Actor system | Actor aliases | Cursor (KIRO) | Active | tables/lupo_actor_aliases.md | — | |
| lupo_actor_capabilities | Actor system | Actor capabilities | Cursor (KIRO) | Active | tables/lupo_actor_capabilities.md | — | |
| lupo_actor_channel_roles | Actor system | Channel-scoped roles | Cursor (KIRO) | Active | tables/lupo_actor_channel_roles.md | — | |
| lupo_actor_channels | Actor system | Actor–channel membership | Cursor (KIRO) | Active | tables/lupo_actor_channels.md | — | |
| lupo_actor_collections | Actor system | Actor collections | Cursor (KIRO) | Active | tables/lupo_actor_collections.md | — | |
| lupo_actor_conflicts | Actor system | Actor conflicts | Cursor (KIRO) | Active | tables/lupo_actor_conflicts.md | — | |
| lupo_actor_departments | Actor system | Actor departments | Cursor (KIRO) | Active | tables/lupo_actor_departments.md | — | |
| lupo_actor_edges | Actor system | Actor edges | Cursor (KIRO) | Active | tables/lupo_actor_edges.md | — | |
| lupo_actor_events | Actor system | Actor events | Cursor (KIRO) | Active | tables/lupo_actor_events.md | — | |
| lupo_actor_handshakes | Actor system | Actor handshakes | Cursor (KIRO) | Active | tables/lupo_actor_handshakes.md | — | |
| lupo_actor_history | Actor system | Actor history | Cursor (KIRO) | Active | tables/lupo_actor_history.md | — | |
| lupo_actor_moods | Actor system | Actor moods | Cursor (KIRO) | Active | tables/lupo_actor_moods.md | — | |
| lupo_actor_object_edges | Actor system | Actor object edges | Cursor (KIRO) | Active | tables/lupo_actor_object_edges.md | — | |
| lupo_actor_persona_relationships | Actor system | Persona relationships | Cursor (KIRO) | Active | tables/lupo_actor_persona_relationships.md | — | |
| lupo_actor_relationship_rules | Actor system | Relationship rules | Cursor (KIRO) | Active | tables/lupo_actor_relationship_rules.md | — | |
| lupo_actor_reply_templates | Actor system | Reply templates | Cursor (KIRO) | Active | tables/lupo_actor_reply_templates.md | — | |
| lupo_actor_truth_edges | Actor system | Truth edges | Cursor (KIRO) | Active | tables/lupo_actor_truth_edges.md | — | |
| lupo_actors | Actor system | Unified actor identity | Cursor (KIRO) | Active | tables/lupo_actors.md | — | Core; reserved IDs |
| lupo_agent_* (11 tables) | Agent/identity | Agent registry, faucets, tool calls, etc. | Cursor | Active | active/*.md (all 11) | — | Cursor documented |
| lupo_aliases | Metadata | Aliases | Cursor (KIRO) | Active | tables/lupo_aliases.md | — | |
| lupo_analytics_* | Analytics | Visits, paths, referers, campaign vars | JetBrains/Antigravity | Active | tables/lupo_analytics_*.md | — | |
| lupo_anubis_* | Federation/filesystem | Anubis mirror, queue, log, etc. | Antigravity | Active | active/*.md (several), tables/, deprecated/ | — | Some in deprecated |
| lupo_api_* | API/auth | Tokens, clients, rate limits, webhooks | Cursor | Active | active/*.md (all 5) | — | Cursor documented |
| lupo_artifacts | Artifacts | Artifacts | JetBrains | Active | active/lupo_artifacts.md | — | |
| lupo_artifact_chunks | Artifacts | Artifact chunks | JetBrains | Active | active/lupo_artifact_chunks.md | — | |
| lupo_atoms | Metadata | Atoms | Cursor (KIRO) | Active | tables/lupo_atoms.md | — | |
| lupo_audit_log | Governance | Audit log | Cursor (KIRO) | Active | tables/lupo_audit_log.md | — | |
| lupo_auth_audit_log | Governance | Auth audit log | Cursor (KIRO) | Active | tables/lupo_auth_audit_log.md | — | |
| lupo_auth_providers | Auth | OAuth/SSO providers | Cursor | Active | active/lupo_auth_providers.md | — | Cursor documented |
| lupo_auth_users | Auth | Human auth/identity | Cursor | Active | active/lupo_auth_users.md, tables/lupo_auth_users.md | — | Cursor documented |
| lupo_banned_actors | ACL | Banned actors | Cursor | Active | active/lupo_banned_actors.md | — | Cursor documented |
| lupo_bans_log | ACL/audit | Ban event log | Cursor | Active | active/lupo_bans_log.md | — | Cursor documented; boundary with KIRO |
| lupo_capability_usage | ACL/metrics | Capability usage | Cursor | Active | active/lupo_capability_usage.md | — | Cursor documented |
| lupo_channel_* | Channels | Boot, content, escalation, files, logs, state | Cursor (KIRO) | Active | tables/lupo_channel_*.md, active/lupo_channel_files.md | — | |
| lupo_channels | Channels | Channel registry | Cursor (KIRO) | Active | tables/lupo_channels.md, tables/channels.md | — | Core; reserved IDs |
| lupo_cip_* | Analytics/CIP | CIP analytics, trends | JetBrains | Active | tables/lupo_cip_*.md | — | |
| lupo_collection_* | Collections | Tabs, map, paths | JetBrains | Active | active/lupo_collection_*.md, tables/ | — | |
| lupo_collection_links | Semantic navbar | Collection link objects | Cursor | Active | TOON only | 20260312_authoritative_semantic_navbar_rebuild | 4.0.71 |
| lupo_collection_map | Semantic navbar | Collection–object map | Cursor | Active | TOON only | 20260312_authoritative_semantic_navbar_rebuild | 4.0.71 |
| lupo_collections | Collections | Collections | JetBrains | Active | active/lupo_collections.md | — | |
| lupo_comments | Content | Comments | JetBrains | Active | tables/lupo_comments.md | — | |
| lupo_contents | Content | Contents | JetBrains | Active | active/lupo_contents.md | — | |
| lupo_contexts | Metadata | Contexts | Cursor (KIRO) | Active | tables/lupo_contexts.md | — | |
| lupo_contexts_map | Metadata | Contexts map | Cursor (KIRO) | Active | tables/lupo_contexts_map.md | — | |
| lupo_crafty_syntax_* | Compatibility | Crafty compatibility tables | Windsurf/Cursor | Active | tables/, active/lupo_crafty_syntax_auto_invite.md | MIGRATION_MAPPING_REFERENCE | |
| lupo_crm_lead_* | CRM | Leads, messages | JetBrains | Active | tables/lupo_crm_*.md | — | |
| lupo_department_* | Departments | Departments, roles, metadata | JetBrains | Active | active/lupo_department_*.md, tables/ | — | |
| lupo_dialog_* | Channels/messaging | Dialog channels, messages, threads | Cursor (KIRO) | Active | tables/lupo_dialog_*.md | — | |
| lupo_doctrine_evolution_audit | Governance | Doctrine evolution audit | Cursor (KIRO) | Active | tables/lupo_doctrine_evolution_audit.md | — | |
| lupo_document_embeddings | Artifacts/search | Document embeddings | JetBrains | Active | tables/lupo_document_embeddings.md | — | |
| lupo_edges | Metadata/FLARE | Edges | Cursor (KIRO) | Active | tables/lupo_edges.md | — | |
| lupo_edge_types | Semantic navbar | Edge type definitions | Cursor | Active | TOON only | 20260312_authoritative_semantic_navbar_rebuild | 4.0.71 |
| lupo_edge_map | Semantic navbar | Edge mappings | Cursor | Active | TOON only | 20260312_authoritative_semantic_navbar_rebuild | 4.0.71 |
| lupo_emotional_* | Emotional | Frameworks, stars, translations | JetBrains | Active | tables/lupo_emotional_*.md | — | |
| lupo_entity_properties | Metadata | Entity properties | Cursor (KIRO) | Active | tables/lupo_entity_properties.md | — | |
| lupo_event_log | Events | Event log | Cursor (KIRO) | Active | tables/lupo_event_log.md | — | |
| lupo_event_metadata | Events | Event metadata | Cursor (KIRO) | Active | tables/lupo_event_metadata.md | — | |
| lupo_federated_trust | Federation | Federated trust | Antigravity | Active | tables/lupo_federated_trust.md | — | |
| lupo_federation_* | Federation | Categories, discovery, nodes | Antigravity | Active | active/lupo_federation_*.md, tables/ | — | |
| lupo_flip_artifacts | Artifacts/FLIP | FLIP artifacts | JetBrains | Active | tables/lupo_flip_artifacts.md | — | |
| lupo_gov_* | Governance | Gov events, timeline, valuations | Cursor (KIRO) | Active | tables/lupo_gov_*.md | — | |
| lupo_governance_overrides | Governance | Governance overrides | Cursor (KIRO) | Active | tables/lupo_governance_overrides.md | — | |
| lupo_hashtags | Content | Hashtags | JetBrains | Active | tables/lupo_hashtags.md | — | |
| lupo_help_* | Knowledge | Help topics, tree | JetBrains | Active | active/lupo_help_*.md, tables/ | — | |
| lupo_hotfix_registry | Governance | Hotfix registry | Cursor (KIRO) | Active | tables/lupo_hotfix_registry.md | — | |
| lupo_human_history_meta | History | Human history meta | Cursor (KIRO) | Active | tables/lupo_human_history_meta.md | — | |
| lupo_interface_translations | i18n | Interface translations | JetBrains | Active | tables/lupo_interface_translations.md | — | |
| lupo_interpretation_log | Logging | Interpretation log | Cursor (KIRO) | Active | tables/lupo_interpretation_log.md | — | |
| lupo_kapu_* | Governance/Kapu | Kapu events, restoration | Cursor (KIRO) | Active | tables/lupo_kapu_*.md | — | |
| lupo_labs_* | Labs | Declarations, violations | JetBrains | Active | tables/lupo_labs_*.md | — | |
| lupo_legacy_content_mapping | Import | Legacy content mapping | Antigravity | Active | tables/lupo_legacy_content_mapping.md | — | |
| lupo_llm_performance | Agent/metrics | LLM performance | Cursor | Active | tables/lupo_llm_performance.md | — | |
| lupo_memory_* | Memory | Events, rollups | Cursor (KIRO) | Active | tables/lupo_memory_*.md | — | |
| lupo_meta_log_events | Logging | Meta log events | Cursor (KIRO) | Active | tables/lupo_meta_log_events.md | — | |
| lupo_metadata | Metadata/FLARE | LUPOPEDIA HEADERS storage | Cursor (KIRO) | Active | tables/lupo_metadata.md | — | Core |
| lupo_metrics_archive_legacy | Legacy metrics | Metrics archive legacy | JetBrains | Active | tables/lupo_metrics_archive_legacy.md | — | |
| lupo_modules | Modules | Module registry | JetBrains | Active | active/lupo_modules.md | — | |
| lupo_modules_departments | Modules | Modules–departments | JetBrains | Active | tables/lupo_modules_departments.md | deprecated/lupo_modules_departments.md | Uncertain: duplicate doc |
| lupo_mood_* | Mood | Registry, assignments | JetBrains | Active | tables/lupo_mood_*.md | — | |
| lupo_multi_agent_critique_sync | Agent | Multi-agent critique sync | Cursor | Active | active/lupo_multi_agent_critique_sync.md | — | |
| lupo_notifications | Notifications | Notifications | JetBrains | Active | tables/lupo_notifications.md | — | |
| lupo_pack_role_registry | Roles | Pack role registry | JetBrains | Active | tables/lupo_pack_role_registry.md | — | |
| lupo_paths_summary | Semantic navbar | Path summary metrics | Cursor | Active | TOON only | 20260312_authoritative_semantic_navbar_rebuild | 4.0.71 |
| lupo_permissions | Governance | Permissions | Cursor (KIRO) | Active | tables/lupo_permissions.md | — | |
| lupo_projects | Core/registry | Projects (channel, orchestrator, node) | Cursor | Active | active/lupo_projects.md | — | 4.0.74; KIRO proposal, Captain directive |
| lupo_persona_* | Persona | Profiles, dialogue patterns | JetBrains | Active | tables/lupo_persona_*.md | — | |
| lupo_reference_* | References | Objects, cited_by | JetBrains | Active | tables/lupo_reference_*.md | deprecated/lupo_reference_cited_by.md | |
| lupo_reference_map | Semantic navbar | Reference–target map | Cursor | Active | TOON only | 20260312_authoritative_semantic_navbar_rebuild | 4.0.71 |
| lupo_referers | Analytics | Referers | Antigravity | Active | tables/lupo_referers.md | — | |
| lupo_registry | Metadata | Registry | Cursor (KIRO) | Active | tables/lupo_registry.md | — | |
| lupo_registry_import | Metadata | Registry import | Cursor (KIRO) | Active | tables/lupo_registry_import.md | deprecated/lupo_registry_import.md | Uncertain: duplicate |
| lupo_registry_open | Metadata | Registry open | Cursor (KIRO) | Active | active/lupo_registry_open.md | — | |
| lupo_question_map | Semantic navbar | Question–object map | Cursor | Active | TOON only | 20260312_authoritative_semantic_navbar_rebuild | 4.0.71 |
| lupo_questions | Semantic navbar | Q/A questions | Cursor | Active | TOON only | 20260312_authoritative_semantic_navbar_rebuild | 4.0.71 |
| lupo_answers | Semantic navbar | Q/A answers | Cursor | Active | TOON only | 20260312_authoritative_semantic_navbar_rebuild | 4.0.71 |
| lupo_search_* | Search | Index, rebuild log | JetBrains | Active | tables/lupo_search_*.md | — | |
| lupo_semantic_index | Search | Semantic index | JetBrains | Active | tables/lupo_semantic_index.md | — | |
| lupo_session_* | Session | Sessions, events, recovery | Cursor | Active | active/*.md (all 3) | — | Cursor documented |
| lupo_system_* | System | Commands, config, events, logs | Cursor (KIRO) | Active | tables/lupo_system_*.md | — | |
| lupo_tab_events | Tabs | Tab events | JetBrains | Active | tables/lupo_tab_events.md | — | |
| lupo_task_* | Tasks | Tasks, assignments, dependencies, etc. | JetBrains | Active | tables/lupo_task_*.md | — | |
| lupo_temporal_coherence_snapshots | Temporal | Coherence snapshots | Cursor (KIRO) | Active | tables/lupo_temporal_coherence_snapshots.md | — | |
| lupo_ticket_* | Tickets | Tickets, messages | JetBrains | Active | tables/lupo_ticket_*.md | — | |
| lupo_tldnr | Content | TL;DR | JetBrains | Active | tables/lupo_tldnr.md | — | |
| lupo_truth_* | Knowledge | Truth answers, knowledge | JetBrains | Active | active/lupo_truth_*.md, tables/ | — | |
| lupo_uploads | Uploads | Uploads | Antigravity | Active | active/lupo_uploads.md | — | |
| lupo_visits | Analytics | Visits | Antigravity | Active | tables/lupo_visits.md | — | |
| lupo_world_* | World | Events, registry | Cursor (KIRO) | Active | tables/lupo_world_*.md | — | |

---

## Summary counts

- **Canonical TOON/table count (4.0.74):** Derived from **install SQL** only. Count of `CREATE TABLE` in `install_new_lupopedia.sql` = **159** (verified 2026-03-14; see [TABLE_COUNT_DOCTRINE.md](../../doctrine/TABLE_COUNT_DOCTRINE.md)). Script `lupo-scripts/generate_toon_from_sql.py` produces one TOON per table; output may be written to `lupo-database/lupopedia/toon/` or `lupo-docs/toons/` depending on script config—**install SQL is authoritative**, not the TOON file count.
- **Other TOON counts (e.g. 230+):** A higher count in `lupo-docs/toons/` or elsewhere may include planning/deprecated/legacy .toon.json files or a different output path. For "how many tables does the install create," use the install-SQL-derived count (159). Do not treat 230 as the canonical install count.
- **Install-backed active tables:** Defined by install_new_lupopedia.sql; table count is not fixed—schema expansion is permitted when justified (table ceiling is advisory only, 4.0.74).
- **livehelp_* (Migration):** 34 legacy; see MIGRATION_MAPPING_REFERENCE.
- **Doc locations:** Many tables have docs in flat `tables/` or `active/`. Migration docs in `tables/*_migration.md` and `migrations/`.

---

## Tables with no TOON (from old docs only)

These appear in existing docs or migration references but **do not** have a current TOON in `lupo-docs/toons/`. Treated as **Removed** or **Uncertain** until verified.

- **lupo_actor_properties** — Referenced in MIGRATION_MAPPING_REFERENCE (livehelp_users → lupo_actors, lupo_actor_properties). No TOON found. **Uncertain** (may be required table not yet in TOON set).
- **lupo_file_index**, **lupo_headers** — Listed in plan (Metadata & FLARE). No TOON in list. **Uncertain** (may be consolidated into lupo_metadata or renamed).
- **lupo_operators** — Documented as DROPPED (operator_to_roles_migration). **Removed**.

---

## Migration reference

- **Primary:** `lupo-docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md`
- **Doctrine:** `lupo-docs/doctrine/migrations/livehelp_migrations_readme.md` (relocation notice; migration docs live in tables/*_migration.md).
