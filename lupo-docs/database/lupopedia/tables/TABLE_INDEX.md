| Table | Category | Agent | Description | Status | Existing Migration Ref |
|---|---|---|---|---|---|
| lupo_collections | active | JetBrains IDE | Collection containers used to organize content and navigation structures. | active | MIGRATION_MAPPING_REFERENCE.md, livehelp_qa_migration.md |
| lupo_collection_tabs | active | JetBrains IDE | Tab-level grouping structure within collections. | active | MIGRATION_MAPPING_REFERENCE.md, livehelp_qa_migration.md |
| lupo_collection_tab_map | active | JetBrains IDE | Mapping table that links tab entries to content or other item types. | active | none found in migration docs scanned |
| lupo_collection_tab_paths | active | JetBrains IDE | Materialized path records for collection-tab hierarchy traversal. | active | none found in migration docs scanned |
| lupo_contents | active | JetBrains IDE | Primary content records for knowledge and documentation entities. | active | none found in migration docs scanned |
| lupo_departments | active | JetBrains IDE | Department registry for organizational scoping. | active | MIGRATION_MAPPING_REFERENCE.md, livehelp_departments_migration.md |
| lupo_department_roles | active | JetBrains IDE | Department-scoped actor role assignments. | active | MIGRATION_MAPPING_REFERENCE.md, livehelp_users_migration.md, operator_to_roles_migration.md |
| lupo_department_metadata | active | JetBrains IDE | Structured metadata payload per department. | active | MIGRATION_MAPPING_REFERENCE.md, livehelp_departments_migration.md |
| lupo_modules | active | JetBrains IDE | Module registry and configuration records. | active | MIGRATION_MAPPING_REFERENCE.md, livehelp_config_migration.md, livehelp_modules_dep_migration.md, livehelp_modules_migration.md |
| lupo_help_topics | active | JetBrains IDE | Help topic records for support/knowledge navigation. | active | none found in migration docs scanned |
| lupo_help_tree | active | JetBrains IDE | Hierarchical help navigation tree structure. | active | none found in migration docs scanned |
| lupo_truth_knowledge | active | JetBrains IDE | Knowledge graph truth entities (questions, answers, evidence, sources, relations). | active | none found in migration docs scanned |
| lupo_truth_answers | active | JetBrains IDE | Answer records linked to truth-question entities. | active | MIGRATION_MAPPING_REFERENCE.md, livehelp_qa_migration.md |
| lupo_artifacts | active | JetBrains IDE | Artifact storage records for structured content payloads. | active | none found in migration docs scanned |
| lupo_artifact_chunks | active | JetBrains IDE | Chunked payload segments for artifact bodies. | active | none found in migration docs scanned |
| lupo_reference_objects | deprecated | JetBrains IDE | Reference object catalog table documented previously, but absent from current TOON and install schema. | deprecated | none found |
| lupo_reference_cited_by | deprecated | JetBrains IDE | Reference citation-link table documented previously, but absent from current TOON and install schema. | deprecated | none found |
| lupo_modules_departments | deprecated | JetBrains IDE | Module-to-department mapping table has existing docs, but no current TOON or install schema definition. | deprecated | none found |
| lupo_federation_nodes | active | Antigravity | Federated node registry for sync and messaging. | active | none found |
| lupo_federation_categories | active | Antigravity | Categories for federated nodes. | active | none found |
| lupo_federation_category_map | active | Antigravity | Mapping links for federation nodes to categories. | active | none found |
| lupo_anubis_log | active | Antigravity | File-backed persistence logging and audit. | active | none found |
| lupo_anubis_events | active | Antigravity | ANUBIS system event records. | active | none found |
| lupo_anubis_queue | active | Antigravity | Work queue for filesystem ingestion tasks. | active | none found |
| lupo_anubis_processing_log | active | Antigravity | Processing log for ANUBIS tasks. | active | none found |
| lupo_anubis_quarantine | active | Antigravity | Storage tracking for quarantined files. | active | none found |
| lupo_anubis_recovery_attempts| active | Antigravity | Tracking strategies for ingestion recovery. | active | none found |
| lupo_anubis_redirects | active | Antigravity | ID mapping/redirects for ingested files. | active | none found |
| lupo_uploads | active | Antigravity | Binary file upload tracking. | active | none found |
| lupo_channel_files | active | Antigravity | File-to-channel association records. | active | none found |
| lupo_agent_files | active | Antigravity | Agent-owned file registry. | active | none found |
| lupo_registry_open | active | Antigravity | Registry synchronization / reconciliation tracking. | active | none found |
| lupo_multi_agent_critique_sync| active | Antigravity | Consensus tracking for multi-agent critiques. | active | none found |
| lupo_federated_trust | deprecated | Antigravity | Legacy federation trust table (stale). | deprecated | none found |
| lupo_federation_discovery | deprecated | Antigravity | Legacy discovery table (replaced by nodes). | deprecated | none found |
| lupo_anubis_mirrored | deprecated | Antigravity | Legacy mirrored status table. | deprecated | none found |
| lupo_anubis_orphaned | deprecated | Antigravity | Legacy orphan tracking table. | deprecated | none found |
| lupo_anubis_revised | deprecated | Antigravity | Legacy revision tracking table. | deprecated | none found |
| lupo_anubis_deletion_log | deprecated | Antigravity | Legacy deletion logging table. | deprecated | none found |
| lupo_flip_artifacts | deprecated | Antigravity | Legacy artifact table (replaced by artifacts). | deprecated | none found |
| lupo_registry_import | deprecated | Antigravity | Legacy registry import table. | deprecated | none found |
| lupo_paths_summary | active | Antigravity | Summary metrics for visitor paths and usage frequency. | active | 20260312_authoritative_semantic_navbar_rebuild.sql |
| lupo_reference_map | active | Antigravity | Explicit mapping table for references to objects. | active | 20260312_authoritative_semantic_navbar_rebuild.sql |
| lupo_collection_links | active | Antigravity | Explicit link objects within collections. | active | 20260312_authoritative_semantic_navbar_rebuild.sql |
| lupo_collection_map | active | Antigravity | Mapping collections to multiple objects (contexts). | active | 20260312_authoritative_semantic_navbar_rebuild.sql |
| lupo_edge_types | active | Antigravity | Definitions for semantic edges (e.g., 'refutes'). | active | 20260312_authoritative_semantic_navbar_rebuild.sql |
| lupo_edge_map | active | Antigravity | Mapping edges between objects in the semantic graph. | active | 20260312_authoritative_semantic_navbar_rebuild.sql |
| lupo_questions | active | Antigravity | Semantic questions registry (Q/A). | active | 20260312_authoritative_semantic_navbar_rebuild.sql |
| lupo_answers | active | Antigravity | Semantic answer records linked to questions. | active | 20260312_authoritative_semantic_navbar_rebuild.sql |
| lupo_question_map | active | Antigravity | Mapping questions to objects or contexts. | active | 20260312_authoritative_semantic_navbar_rebuild.sql |
| lupo_hashtags | active | Windsurf | Hashtag registry with standardized slugs and labels. | active | install_new_lupopedia.sql |
| lupo_hashtag_map | active | Antigravity | Mapping hashtags to objects/contents. | active | install_new_lupopedia.sql |
| lupo_folders | active | Antigravity | Folder registry for organizing items. | active | install_new_lupopedia.sql |
| lupo_folder_map | active | Antigravity | Mapping folders to objects/contents. | active | install_new_lupopedia.sql |
| lupo_references | active | Antigravity | Citations and references registry. | active | install_new_lupopedia.sql |
| lupo_reference_links | active | Antigravity | Mapping of references back to objects. | active | install_new_lupopedia.sql |

## Discrepancies For KIRO
- **Domain Coverage:** Antigravity domain (Federation/Anubis) documentation consolidated into `active/` and `deprecated/` subdirectories. 100% active table coverage confirmed.
- **Duplicate cleanup:** Flat legacy docs in root `tables/` have been processed; active canonical docs moved to `active/`, stale legacy docs moved to `deprecated/`, and migration docs moved to `migrations/`.
- **System Version:** Index now aligns with version 4.0.71 state.