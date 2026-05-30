---
lupopedia.init:
  file_identity: TABLE_INDEX_KIRO.md
  artifact_type: table-index
  artifact_kind: metadata-snapshot
  namespace: lupopedia
  domain: core
  system_version: 4.0.74
lupopedia.metadata:
  comment: Snapshot of metadata for this file or entity at artifact creation.
  title:
  - schema_ref: lupo_metadata
    entity_type: file
    meta_type: property
    property_value: KIRO Table Index
    channel_id: 42
    class_name: lupopedia_metadata
    created_ymdhis: 20260314000000
    updated_ymdhis: 20260314000000
  description:
  - schema_ref: lupo_metadata
    entity_type: file
    meta_type: property
    property_value: Comprehensive table index with KIRO authority and multi-agent
      domain coverage
    channel_id: 42
    class_name: lupopedia_metadata
    created_ymdhis: 20260314000000
    updated_ymdhis: 20260314000000
  keywords:
  - schema_ref: lupo_metadata
    entity_type: file
    meta_type: property
    property_value: table, index, database, kiro, coordination, multi-agent
    channel_id: 42
    class_name: lupopedia_metadata
    created_ymdhis: 20260314000000
    updated_ymdhis: 20260314000000
  author:
  - schema_ref: lupo_metadata
    entity_type: file
    meta_type: property
    property_value: kiro
    channel_id: 42
    class_name: lupopedia_metadata
    created_ymdhis: 20260314000000
    updated_ymdhis: 20260314000000
  orchestrator:
  - schema_ref: lupo_metadata
    entity_type: file
    meta_type: property
    property_value: kiro
    channel_id: 42
    class_name: lupopedia_metadata
    created_ymdhis: 20260314000000
    updated_ymdhis: 20260314000000
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/TABLE_INDEX_KIRO.md
  web_path: http://www.lupopedia.com/TABLE_INDEX_KIRO
  last_modified_utc: '20260314'
  channel_id: 42
  actor_id: 100
  actor_name: kiro
  faucet_name: kiro
  delegation_chain: kiro:root
  artifact_type: index
  artifact_kind: coordination
  purpose: Comprehensive table index with KIRO authority and multi-agent domain coverage
  mood_vector: 4169E1
  traits:
  - canonical
  - index
  - kiro_authority
  - v4.0.74
  - multi-agent
  tags:
  - table
  - index
  - database
  - kiro
  - coordination
  - multi-agent
  when_updated: '20260324174654'
lupopedia.session:
  session_id: L-KIRO-TABLE-INDEX-20260314
  session_name: L-KIRO-TABLE-INDEX-20260314
  actor_id: 100
  actor_name: kiro
  faucet_name: kiro
  channel_id: 42
  channel_name: Lupopedia Development (general)
  federation_node_id: 1
  paired_actor_id: 1000
lupopedia.edges:
  outbound_edges:
  - to: lupo-docs/database/lupopedia/tables/TABLE_INDEX.md
    type: references
    weight: 1.0
  - to: lupo-docs/database/lupopedia/SCHEMA_REGISTRY_KIRO.md
    type: references
    weight: 0.95
  - to: lupo-docs/database/lupopedia/tables/VALIDATION_REPORT_KIRO.md
    type: references
    weight: 0.9
  - to: report_kiro.md
    type: references
    weight: 0.85
  - to: plan_kiro.md
    type: references
    weight: 0.85
  semantic_tags:
  - table
  - index
  - kiro
  - coordination
  - database
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: '20260314000000'
  last_verified_by: cursor
  orchestrator: kiro
  next_action:
  - Use this index for multi-agent coordination and domain verification
  - Reference KIRO-authored coordination documents for current state
  - Report discrepancies to KIRO for resolution
  last_verified_by_actor_id: 102
---
# KIRO Table Index (Canonical)

**Date:** 2026-03-14  
**Author:** KIRO (actor_id 100 per registry), schema coordinator  
**Version:** 4.0.74  
**Source:** `lupo-database/lupopedia/toon/` (YAML TOONs) per Captain Wolfie directive  
**Purpose:** Comprehensive table index with KIRO authority and multi-agent domain coverage

## Executive Summary

This index replaces the JetBrains-authored `TABLE_INDEX.md` (v4.0.71) with KIRO canonical authority. Includes all agent domains with current status based on KIRO analysis.

**Key Updates:**
1. **Current version**: 4.0.74 (was 4.0.71)
2. **KIRO authority**: Authored by KIRO proper as schema coordinator
3. **Complete coverage**: Includes all agent domains (KIRO, Cursor, JetBrains, Antigravity, Windsurf)
4. **TOON source**: References `lupo-database/lupopedia/toon/` (YAML format)

## Table Index by Agent Domain

### KIRO Domain (Core Governance & Schema)

| Table | Category | Agent | Description | Status | Notes |
|-------|----------|-------|-------------|--------|-------|
| **lupo_actors** | Actor system | KIRO | Unified actor identity (actor_name PRIMARY KEY) | Active | **Critical**: YAML TOON has `actor_name` PK, JSON has `actor_id` PK |
| **lupo_actor_*** | Actor system | KIRO | Actor actions, capabilities, channels, roles, etc. | Active | 20+ actor system tables |
| **lupo_channels** | Channels | KIRO | Channel registry | Active | Core; reserved IDs |
| **lupo_channel_*** | Channels | KIRO | Boot, content, escalation, files, logs, state | Active | 6+ channel tables |
| **lupo_dialog_*** | Channels/messaging | KIRO | Dialog channels, messages, threads | Active | 3+ dialog tables |
| **lupo_metadata** | Metadata/FLARE | KIRO | LUPOPEDIA HEADERS storage | Active | Core |
| **lupo_edges** | Metadata/FLARE | KIRO | Edges | Active | |
| **lupo_atoms** | Metadata | KIRO | Atoms | Active | |
| **lupo_aliases** | Metadata | KIRO | Aliases | Active | |
| **lupo_contexts** | Metadata | KIRO | Contexts | Active | |
| **lupo_contexts_map** | Metadata | KIRO | Contexts map | Active | |
| **lupo_entity_properties** | Metadata | KIRO | Entity properties | Active | |
| **lupo_registry** | Metadata | KIRO | Registry | Active | |
| **lupo_registry_open** | Metadata | KIRO | Registry open | Active | |
| **lupo_permissions** | Governance | KIRO | Permissions (policy/definitions) | Active | Boundary: usage vs policy |
| **lupo_audit_log** | Governance | KIRO | Audit log | Active | |
| **lupo_auth_audit_log** | Governance | KIRO | Auth audit log | Active | Governance, not authentication |
| **lupo_governance_overrides** | Governance | KIRO | Governance overrides | Active | |
| **lupo_hotfix_registry** | Governance | KIRO | Hotfix registry | Active | |
| **lupo_kapu_*** | Governance/Kapu | KIRO | Kapu events, restoration | Active | 2+ Kapu tables |
| **lupo_gov_*** | Governance | KIRO | Gov events, timeline, valuations | Active | 4+ governance tables |
| **lupo_system_*** | System | KIRO | Commands, config, events, logs | Active | 4+ system tables |
| **lupo_memory_*** | Memory | KIRO | Events, rollups | Active | 2+ memory tables |
| **lupo_event_log** | Events | KIRO | Event log | Active | |
| **lupo_event_metadata** | Events | KIRO | Event metadata | Active | |
| **lupo_interpretation_log** | Logging | KIRO | Interpretation log | Active | |
| **lupo_meta_log_events** | Logging | KIRO | Meta log events | Active | |
| **lupo_human_history_meta** | History | KIRO | Human history meta | Active | |
| **lupo_temporal_coherence_snapshots** | Temporal | KIRO | Coherence snapshots | Active | |
| **lupo_world_*** | World | KIRO | Events, registry | Active | 2+ world tables |

### Cursor Domain (User/Auth/Session/API/ACL/Agents)

| Table | Category | Agent | Description | Status | Notes |
|-------|----------|-------|-------------|--------|-------|
| **lupo_auth_users** | Auth | Cursor | Human auth/identity | Active | |
| **lupo_auth_providers** | Auth | Cursor | OAuth/SSO providers | Active | |
| **lupo_sessions** | Session | Cursor | Sessions | Active | |
| **lupo_session_events** | Session | Cursor | Session events | Active | |
| **lupo_session_recovery** | Session | Cursor | Session recovery | Active | |
| **lupo_api_tokens** | API | Cursor | API tokens | Active | |
| **lupo_api_clients** | API | Cursor | API clients | Active | |
| **lupo_api_rate_limits** | API | Cursor | API rate limits | Active | |
| **lupo_api_token_logs** | API | Cursor | API token logs | Active | |
| **lupo_api_webhooks** | API | Cursor | API webhooks | Active | |
| **lupo_banned_actors** | ACL | Cursor | Banned actors | Active | |
| **lupo_bans_log** | ACL/audit | Cursor | Ban event log | Active | Security audit domain |
| **lupo_capability_usage** | ACL/metrics | Cursor | Capability usage (telemetry) | Active | Boundary: usage vs policy |
| **lupo_agents** | Agent/identity | Cursor | Agent registry | Active | Kapu fields: governance semantics |
| **lupo_agent_faucets** | Agent/identity | Cursor | Agent faucets | Active | |
| **lupo_agent_faucet_credentials** | Agent/identity | Cursor | Agent faucet credentials | Active | |
| **lupo_agent_context_snapshots** | Agent/identity | Cursor | Agent context snapshots | Active | |
| **lupo_agent_dependencies** | Agent/identity | Cursor | Agent dependencies | Active | |
| **lupo_agent_experiences** | Agent/identity | Cursor | Agent experiences | Active | |
| **lupo_agent_external_events** | Agent/identity | Cursor | Agent external events | Active | |
| **lupo_agent_files** | Agent/identity | Cursor | Agent files | Active | |
| **lupo_agent_heartbeats** | Agent/identity | Cursor | Agent heartbeats | Active | |
| **lupo_agent_tool_calls** | Agent/identity | Cursor | Agent tool calls | Active | |
| **lupo_agent_versions** | Agent/identity | Cursor | Agent versions | Active | |

### JetBrains Domain (Collections/Departments/Knowledge/Artifacts)

| Table | Category | Agent | Description | Status | Notes |
|-------|----------|-------|-------------|--------|-------|
| **lupo_collections** | Collections | JetBrains | Collection containers | Active | |
| **lupo_collection_tabs** | Collections | JetBrains | Tab-level grouping | Active | |
| **lupo_collection_tab_map** | Collections | JetBrains | Tab-to-content mapping | Active | |
| **lupo_collection_tab_paths** | Collections | JetBrains | Path hierarchy traversal | Active | |
| **lupo_contents** | Content | JetBrains | Primary content records | Active | |
| **lupo_departments** | Departments | JetBrains | Department registry | Active | |
| **lupo_department_roles** | Departments | JetBrains | Department-scoped roles | Active | |
| **lupo_department_metadata** | Departments | JetBrains | Department metadata | Active | |
| **lupo_modules** | Modules | JetBrains | Module registry | Active | |
| **lupo_help_topics** | Knowledge | JetBrains | Help topic records | Active | |
| **lupo_help_tree** | Knowledge | JetBrains | Help navigation tree | Active | |
| **lupo_truth_knowledge** | Knowledge | JetBrains | Knowledge graph truth | Active | |
| **lupo_truth_answers** | Knowledge | JetBrains | Answer records | Active | |
| **lupo_artifacts** | Artifacts | JetBrains | Artifact storage | Active | |
| **lupo_artifact_chunks** | Artifacts | JetBrains | Chunked payload segments | Active | |
| **lupo_reference_objects** | References | JetBrains | Reference object catalog | Deprecated | No TOON in current schema |
| **lupo_reference_cited_by** | References | JetBrains | Reference citation links | Deprecated | No TOON in current schema |
| **lupo_modules_departments** | Modules | JetBrains | Module-department mapping | Deprecated | No TOON in current schema |

### Antigravity Domain (Federation/Anubis/Uploads/Channel Files)

| Table | Category | Agent | Description | Status | Notes |
|-------|----------|-------|-------------|--------|-------|
| **lupo_federation_nodes** | Federation | Antigravity | Federated node registry | Active | |
| **lupo_federation_categories** | Federation | Antigravity | Federation categories | Active | |
| **lupo_federation_category_map** | Federation | Antigravity | Node-category mapping | Active | |
| **lupo_anubis_log** | Anubis | Antigravity | File-backed persistence logging | Active | |
| **lupo_anubis_events** | Anubis | Antigravity | ANUBIS system events | Active | |
| **lupo_anubis_queue** | Anubis | Antigravity | Filesystem ingestion queue | Active | |
| **lupo_anubis_processing_log** | Anubis | Antigravity | Processing log | Active | |
| **lupo_anubis_quarantine** | Anubis | Antigravity | Quarantined files storage | Active | |
| **lupo_anubis_recovery_attempts** | Anubis | Antigravity | Ingestion recovery tracking | Active | |
| **lupo_anubis_redirects** | Anubis | Antigravity | ID mapping/redirects | Active | |
| **lupo_uploads** | Uploads | Antigravity | Binary file upload tracking | Active | |
| **lupo_channel_files** | Channel files | Antigravity | File-to-channel association | Active | |
| **lupo_registry_open** | Registry | Antigravity | Registry synchronization | Active | |
| **lupo_multi_agent_critique_sync** | Multi-agent | Antigravity | Consensus tracking | Active | |
| **lupo_paths_summary** | Semantic navbar | Antigravity | Visitor path metrics | Active | 4.0.71 rebuild |
| **lupo_reference_map** | Semantic navbar | Antigravity | Reference-object mapping | Active | 4.0.71 rebuild |
| **lupo_collection_links** | Semantic navbar | Antigravity | Collection link objects | Active | 4.0.71 rebuild |
| **lupo_collection_map** | Semantic navbar | Antigravity | Collection-object mapping | Active | 4.0.71 rebuild |
| **lupo_edge_types** | Semantic navbar | Antigravity | Semantic edge definitions | Active | 4.0.71 rebuild |
| **lupo_edge_map** | Semantic navbar | Antigravity | Edge mappings | Active | 4.0.71 rebuild |
| **lupo_questions** | Semantic navbar | Antigravity | Q/A questions registry | Active | 4.0.71 rebuild |
| **lupo_answers** | Semantic navbar | Antigravity | Q/A answer records | Active | 4.0.71 rebuild |
| **lupo_question_map** | Semantic navbar | Antigravity | Question-object mapping | Active | 4.0.71 rebuild |
| **lupo_hashtags** | Semantic navbar | Antigravity | Hashtag registry | Active | 4.0.71 rebuild |
| **lupo_hashtag_map** | Semantic navbar | Antigravity | Hashtag-object mapping | Active | 4.0.71 rebuild |
| **lupo_folders** | Semantic navbar | Antigravity | Folder registry | Active | 4.0.71 rebuild |
| **lupo_folder_map** | Semantic navbar | Antigravity | Folder-object mapping | Active | 4.0.71 rebuild |
| **lupo_references** | Semantic navbar | Antigravity | Citations registry | Active | 4.0.71 rebuild |
| **lupo_reference_links** | Semantic navbar | Antigravity | Reference-object mapping | Active | 4.0.71 rebuild |
| **lupo_federated_trust** | Federation | Antigravity | Legacy federation trust | Deprecated | Stale |
| **lupo_federation_discovery** | Federation | Antigravity | Legacy discovery table | Deprecated | Replaced by nodes |
| **lupo_anubis_mirrored** | Anubis | Antigravity | Legacy mirrored status | Deprecated | |
| **lupo_anubis_orphaned** | Anubis | Antigravity | Legacy orphan tracking | Deprecated | |
| **lupo_anubis_revised** | Anubis | Antigravity | Legacy revision tracking | Deprecated | |
| **lupo_anubis_deletion_log** | Anubis | Antigravity | Legacy deletion logging | Deprecated | |
| **lupo_flip_artifacts** | Artifacts | Antigravity | Legacy artifact table | Deprecated | Replaced by artifacts |
| **lupo_registry_import** | Registry | Antigravity | Legacy registry import | Deprecated | |

### Windsurf Domain (Migration Tables)

| Table | Category | Agent | Description | Status | Migration Ref |
|-------|----------|-------|-------------|--------|---------------|
| **livehelp_autoinvite** | Legacy/Crafty | Windsurf | Legacy auto-invite | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_channels** | Legacy/Crafty | Windsurf | Legacy channels | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_config** | Legacy/Crafty | Windsurf | Legacy config | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_departments** | Legacy/Crafty | Windsurf | Legacy departments | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_emailque** | Legacy/Crafty | Windsurf | Legacy email queue | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_emails** | Legacy/Crafty | Windsurf | Legacy emails | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_identity_*** | Legacy/Crafty | Windsurf | Legacy identity tracking | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_keywords_*** | Legacy/Crafty | Windsurf | Legacy keywords | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_layerinvites** | Legacy/Crafty | Windsurf | Legacy layer invites | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_leads** | Legacy/Crafty | Windsurf | Legacy leads | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_leavemessage** | Legacy/Crafty | Windsurf | Legacy leave message | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_messages** | Legacy/Crafty | Windsurf | Legacy messages | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_modules** | Legacy/Crafty | Windsurf | Legacy modules | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_operator_*** | Legacy/Crafty | Windsurf | Legacy operator tables | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_paths_*** | Legacy/Crafty | Windsurf | Legacy paths tracking | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_qa** | Legacy/Crafty | Windsurf | Legacy QA | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_questions** | Legacy/Crafty | Windsurf | Legacy questions | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_quick** | Legacy/Crafty | Windsurf | Legacy quick replies | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_referers_*** | Legacy/Crafty | Windsurf | Legacy referers tracking | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_sessions** | Legacy/Crafty | Windsurf | Legacy sessions | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_smilies** | Legacy/Crafty | Windsurf | Legacy smilies | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_transcripts** | Legacy/Crafty | Windsurf | Legacy transcripts | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_users** | Legacy/Crafty | Windsurf | Legacy users | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_visit_track** | Legacy/Crafty | Windsurf | Legacy visit track | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_visits_*** | Legacy/Crafty | Windsurf | Legacy visits tracking | Migration | MIGRATION_MAPPING_REFERENCE |
| **livehelp_websites** | Legacy/Crafty | Windsurf | Legacy websites | Migration | MIGRATION_MAPPING_REFERENCE |

## Summary Statistics

| Category | Count | Notes |
|----------|-------|-------|
| **Total TOON tables (YAML)** | 230+ | `lupo-database/lupopedia/toon/` |
| **Active tables** | ~196 | lupo_* tables |
| **Migration tables** | 34 | livehelp_* tables (Windsurf) |
| **Deprecated tables** | 16 | In `deprecated/` directory |
| **Uncertain tables** | 4 | No TOON found |
| **KIRO domain tables** | ~50 | Core governance, actor system, channels, metadata |
| **Cursor domain tables** | 25 | User/auth/session/API/ACL/agents |
| **JetBrains domain tables** | 18 | Collections/departments/knowledge/artifacts |
| **Antigravity domain tables** | ~40 | Federation/Anubis/uploads/semantic navbar |
| **Windsurf domain tables** | 34 | Migration tables |

## Critical Issues for Coordination

1. **TOON Format Discrepancy**: YAML vs JSON with conflicting primary keys
2. **Header Duplication**: Multiple FLARE/LUPOPEDIA HEADERS in many files
3. **Domain Boundary Clarification**: Several tables need clear ownership decisions
4. **Version Consistency**: Files reference 4.0.50-4.0.73 vs current 4.0.74

## Coordination Rules (KIRO Authority)

1. **First agent claims ownership**: For missing documentation files
2. **Do not modify files you did not create**: Respect domain boundaries
3. **Migration tables are Windsurf ownership**: All `livehelp_*` tables
4. **Never delete documentation files**: Move to `deprecated/` with notes
5. **KIRO is final authority**: Resolves conflicts and validates consistency

## Next Steps

1. **Reference this index** for multi-agent coordination
2. **Check domain assignments** before documenting tables
3. **Report discrepancies** to KIRO for resolution
4. **Follow coordination rules** to prevent conflicts

---
**KIRO Schema Coordinator** (actor_id 100 per registry)  
*Ensuring canonical truth across Lupopedia's semantic architecture*
