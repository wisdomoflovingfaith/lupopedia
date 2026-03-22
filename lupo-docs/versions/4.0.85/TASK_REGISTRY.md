---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "lupo-docs/versions/4.0.85/TASK_REGISTRY.md"
  last_modified_utc: "20260322_184651"
  channel_id: 42
  thread_id: 2015
  actor_id: 4
  actor_name: "athena"
  artifact_type: "registry"
  artifact_kind: "task_registry"
  purpose: "Single source of truth for 4.0.85 task and question state under controlled synchronization v9."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.0.85/CONTRADICTIONS.md", type: "references" }
    - { to: "lupo-docs/versions/4.0.85/TODO.md", type: "derived_view" }
    - { to: "lupo-docs/versions/4.0.85/PLAN.md", type: "derived_view" }
    - { to: "TODO.md", type: "derived_view" }
    - { to: "plan.md", type: "derived_view" }
---

# 4.0.85 TASK REGISTRY

## Final Close Lock (Thread 2016)
- This file remains the only authoritative task/question lifecycle surface for 4.0.85.
- No parallel task authority system is allowed in version docs, thread indexes, or root pointer files.
- Version 4.0.85 final close declaration: INSTALL READY + SYSTEM COMPLIANT.

## Authority Lock
- This file is the only system of record for 4.0.85 task and question state.
- If task state appears anywhere else, that other surface is derived only.
- THREAD_INDEX.md files are navigation-only and cannot define status, ownership, or lifecycle.
- Task claiming is invalid unless ownership is explicit in this registry.
- LILITH emits validation only. Violations route through CONTRADICTIONS.md and linked tasks here.

## Synchronized Metrics
- threads_detected: 102
- channel66_question_threads: 11
- completed: 46
- in_progress: 45
- blocked: 5
- deferred_to_4_0_86: 3
- last_updated: 20260322_170850 (Thread 2013 final documentation authority pass)

## Contradiction Links
| contradiction_id | classification | task_id | assigned_actor | diagnostic_state | source_artifact |
|---|---|---|---|---|---|
| contradiction_thread_index_authority_v9 | violation | task_ch42_th1047 | wolfie | resolved_in_sync_v9 | lupo-channels/42/THREAD_INDEX.md |
| contradiction_task_registry_owner_selection_blocker_v1 | violation | task_ch42_th1047 | cursor | active | lupo-docs/versions/4.0.85/TASK_REGISTRY.md |
| contradiction_c66_1004_semantic_mapping_invalid | violation | task_ch66_th1004 | hephaestus | active | lupo-channels/66/threads/1004/ |
| contradiction_thread1005_single_field_enforcement | violation | task_ch66_th1005 | wolfie | resolved | lupo-channels/66/threads/1005/20260320_050000_lilith_adversarial_validation_wolfie_single_field_versioning_enforcement.md |

## Ownership Projections
| task_id | assigned_actor | ownership_state | source_artifact |
|---|---|---|---|
| task_ch42_th2003 | athena | assigned_by_explicit_user_directive | lupo-docs/versions/4.0.85/TASK_REGISTRY.md |
| task_ch42_th2012 | athena | assigned_by_explicit_user_directive | lupo-docs/versions/4.0.85/TASK_REGISTRY.md |
| task_ch42_th2013 | wolfie | assigned_by_self_directive | lupo-docs/versions/4.0.85/TASK_REGISTRY.md |
| task_ch42_th2014 | athena | assigned_by_explicit_user_directive | lupo-docs/versions/4.0.85/TASK_REGISTRY.md |
| task_ch42_th2015 | athena | assigned_by_explicit_user_directive_structural_resolution_published_after_lilith_audit | lupo-docs/versions/4.0.85/TASK_REGISTRY.md |
| task_ch42_th1048 | athena | assigned_by_explicit_user_directive | lupo-docs/versions/4.0.85/TASK_REGISTRY.md |
| task_research_doom_edges_001 | thoth | assigned_by_explicit_user_directive | lupo-docs/versions/4.0.85/TASK_REGISTRY.md |
| task_ch66_th1005 | unassigned | unassigned_by_policy_pending_selection | lupo-docs/versions/4.0.85/TASK_REGISTRY.md |
| task_ch66_th1047 | unassigned | unassigned_by_policy_pending_selection | lupo-docs/versions/4.0.85/TASK_REGISTRY.md |
| task_ch42_th2006 | wolfie | assigned_by_explicit_user_directive | lupo-docs/versions/4.0.85/TASK_REGISTRY.md |
| task_document_lupo_structure_001 | hephaestus | assigned_by_explicit_user_directive | lupo-docs/versions/4.0.85/TASK_REGISTRY.md |
| task_document_channel_thread_dialog_001 | thoth | assigned_by_explicit_user_directive | lupo-docs/versions/4.0.85/TASK_REGISTRY.md |

## Full Task/Question Registry
| task_id | channel_id | thread_id | node_type | status | required_reading_count | next_action_count | dependencies | upstream_requirements | downstream_outcomes | cross_channel_relationships | source |
|---|---:|---:|---|---|---:|---:|---|---|---|---|---|
| task_ch1_th1015 | 1 | 1015 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/1/threads/1015/ |
| task_ch1_th1024 | 1 | 1024 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/1/threads/1024/ |
| task_ch1_th1035 | 1 | 1035 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/1/threads/1035/ |
| task_ch1_th1041 | 1 | 1041 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/1/threads/1041/ |
| task_ch7_th1006 | 7 | 1006 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/7/threads/1006/ |
| task_ch7_th1011 | 7 | 1011 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/7/threads/1011/ |
| task_ch7_th1012 | 7 | 1012 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/7/threads/1012/ |
| task_ch7_th1018 | 7 | 1018 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/7/threads/1018/ |
| task_ch7_th1019 | 7 | 1019 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/7/threads/1019/ |
| task_ch7_th1034 | 7 | 1034 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/7/threads/1034/ |
| task_ch7_th1035 | 7 | 1035 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/7/threads/1035/ |
| task_ch11_th1003 | 11 | 1003 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/11/threads/1003/ |
| task_ch11_th1010 | 11 | 1010 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/11/threads/1010/ |
| task_ch11_th1014 | 11 | 1014 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/11/threads/1014/ |
| task_ch11_th1020 | 11 | 1020 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/11/threads/1020/ |
| task_ch17_th1009 | 17 | 1009 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/17/threads/1009/ |
| task_ch23_th1002 | 23 | 1002 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/23/threads/1002/ |
| task_ch23_th1005 | 23 | 1005 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/23/threads/1005/ |
| task_ch31_th1016 | 31 | 1016 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/31/threads/1016/ |
| task_ch42_th1001 | 42 | 1001 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1001/ |
| task_ch42_th1002 | 42 | 1002 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1002/ |
| task_ch42_th1003 | 42 | 1003 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1003/ |
| task_ch42_th1004 | 42 | 1004 | task | blocked | 0 | 0 | thread_1047_global_lock;thread_1049_reaudit_gate | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1004/ |
| task_ch42_th1005 | 42 | 1005 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1005/ |
| task_ch42_th1006 | 42 | 1006 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1006/ |
| task_ch42_th1009 | 42 | 1009 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1009/ |
| task_ch42_th1010 | 42 | 1010 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1010/ |
| task_ch42_th1011 | 42 | 1011 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1011/ |
| task_ch42_th1012 | 42 | 1012 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1012/ |
| task_ch42_th1014 | 42 | 1014 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1014/ |
| task_ch42_th1015 | 42 | 1015 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1015/ |
| task_ch42_th1016 | 42 | 1016 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1016/ |
| task_ch42_th1017 | 42 | 1017 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1017/ |
| task_ch42_th1018 | 42 | 1018 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1018/ |
| task_ch42_th1019 | 42 | 1019 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1019/ |
| task_ch42_th1020 | 42 | 1020 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1020/ |
| task_ch42_th1021 | 42 | 1021 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1021/ |
| task_ch42_th1022 | 42 | 1022 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1022/ |
| task_ch42_th1023 | 42 | 1023 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1023/ |
| task_ch42_th1024 | 42 | 1024 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1024/ |
| task_ch42_th1025 | 42 | 1025 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1025/ |
| task_ch42_th1026 | 42 | 1026 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1026/ |
| task_ch42_th1027 | 42 | 1027 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1027/ |
| task_ch42_th1028 | 42 | 1028 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1028/ |
| task_ch42_th1029 | 42 | 1029 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1029/ |
| task_ch42_th1030 | 42 | 1030 | task | deferred | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1030/ |
| task_ch42_th1031 | 42 | 1031 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1031/ |
| task_ch42_th1032 | 42 | 1032 | task | deferred | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1032/ |
| task_ch42_th1033 | 42 | 1033 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1033/ |
| task_ch42_th1034 | 42 | 1034 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1034/ |
| task_ch42_th1035 | 42 | 1035 | task | deferred | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1035/ |
| task_ch42_th1036 | 42 | 1036 | task | blocked | 0 | 0 | thread_1047_global_lock;thread_1049_reaudit_gate | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1036/ |
| task_ch42_th1037 | 42 | 1037 | task | blocked | 0 | 0 | thread_1047_global_lock;thread_1049_reaudit_gate | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1037/ |
| task_ch42_th1038 | 42 | 1038 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1038/ |
| task_ch42_th1039 | 42 | 1039 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1039/ |
| task_ch42_th1041 | 42 | 1041 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1041/ |
| task_ch42_th1042 | 42 | 1042 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1042/ |
| task_ch42_th1043 | 42 | 1043 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1043/ |
| task_ch42_th1044 | 42 | 1044 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1044/ |
| task_ch42_th1045 | 42 | 1045 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1045/ |
| task_ch42_th1046 | 42 | 1046 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1046/ |
| task_ch42_th1047 | 42 | 1047 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1047/ |
| task_ch42_th1048 | 42 | 1048 | task | in-progress | 0 | 1 | thread_1047_global_lock | decision_lineage_design_amendment;crafty_syntax_dialog_foundation;channel42_execution_context;edge_ref:edge_ch42_th1048_dep_ch42_th2003 | amended_decision_lineage_design_published;phase1_implementation_planning_ready;edge_ref:edge_ch42_th1048_out_ch42_th2003 | channel66_question_graph;bmad_evaluation_stream;doom_structure_stream;edge_ref:edge_ch42_th1048_rel_ch42_th2003 | lupo-channels/42/threads/1048/ |
| task_ch42_th1049 | 42 | 1049 | task | blocked | 0 | 0 | thread_1047_global_lock;thread_1049_reaudit_gate | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/1049/ |
| task_research_bmad_workflow_001 | 42 | 1050 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context;bmad_method_repository_analysis | bmad_workflow_research_artifact_created;channel42_research_thread_created | channel66_question_graph | lupo-channels/42/threads/1050/ |
| task_ch42_th2002 | 42 | 2002 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel42_execution_context | root_and_version_docs_synchronized;execution_node_preserved | channel66_question_graph | lupo-channels/42/threads/2002/ |
| task_ch42_th2003 | 42 | 2003 | task | in-progress | 0 | 0 | thread_1047_global_lock | decision_lineage_and_choice_logging_research;channel42_execution_context;actor_choice_traceability;edge_ref:edge_ch42_th2003_dep_ch42_th1048 | decision_lineage_mvp_structure_proposed;channel_dialog_task_edge_mapping_defined;implementation_deferred_with_explicit_backlog;edge_ref:edge_ch42_th2003_out_ch42_th1048 | channel66_question_graph;bmad_research_stream;doom_emacs_structure_stream;edge_ref:edge_ch42_th2003_rel_ch42_th1048 | lupo-channels/42/threads/2003/ |
| task_ch42_th2004 | 42 | 2004 | task | blocked | 8 | 3 | thread_1047_global_lock;blocker_schema_projection_stale_001;blocker_schema_projection_stale_002;blocker_schema_projection_stale_003;blocker_schema_projection_stale_004 | install_sql_authority_check;toon_projection_parity_check;schema_affecting_thread_artifact_review | schema_reconciliation_review_published;explicit_blocker_register_published;parity_regeneration_followup_required | channel66_question_graph;bmad_research_stream;doom_emacs_structure_stream | lupo-channels/42/threads/2004/ |
| task_research_doom_edges_001 | 42 | 2005 | task | completed | 6 | 0 | thread_1047_global_lock;task_ch42_th2004_blocker_research_publication_001 | doom_source_corpus_ingest;federation_research_structural_extraction;classification_and_mapping | canonical_doom_federation_research_artifact_published;blocker_research_publication_001_resolved | channel66_question_graph;bmad_research_stream;doom_emacs_structure_stream | lupo-channels/42/threads/2005/ |
| task_ch42_th2006 | 42 | 2006 | task | completed | 5 | 0 | thread_1047_global_lock;task_ch42_th2004_completed;task_research_doom_edges_001_completed | install_sql_authority_check;toon_parity_validation;version_doc_synchronization;task_registry_completeness_check | version_docs_synchronized;install_sql_validated;documentation_threads_created;readiness_verdict_issued | channel66_question_graph | lupo-channels/42/threads/2006/ |
| task_document_lupo_structure_001 | 42 | 2007 | task | not-started | 0 | 0 | task_ch42_th2006 | lupo_directory_inventory;purpose_and_interaction_documentation | lupo_structure_canonical_doc_published | channel42_documentation_stream | lupo-channels/42/threads/2007/ |
| task_document_channel_thread_dialog_001 | 42 | 2008 | task | not-started | 0 | 0 | task_ch42_th2006 | channel_thread_dialog_schema_review;workflow_model_documentation | channel_workflow_canonical_doc_published | channel42_documentation_stream | lupo-channels/42/threads/2008/ |
| task_ch42_th2009 | 42 | 2009 | task | completed | 3 | 0 | task_ch42_th2006 | filesystem_channel_artifact_discovery;channel_thread_import_mapping | import_filesystem_channels_to_db_script_delivered | channel42_documentation_stream | lupo-channels/42/threads/2009/ |
| task_ch42_th2010 | 42 | 2010 | task | completed | 4 | 0 | task_ch42_th2006;task_ch42_th2009 | actor_agent_registry_discovery;filesystem_db_sync_model_design | import_export_scripts_delivered;actor_agent_sync_model_documented | channel42_documentation_stream | lupo-channels/42/threads/2010/ |
| task_ch42_th2011 | 42 | 2011 | task | completed | 4 | 0 | task_ch42_th2006 | install_sql_schema_verification;human_actor_relationship_audit;many_to_many_relationship_gap_a;destructive_schema_validation;corrective_schema_implementation;post_correction_reaudit;primary_invariant_hard_correction;final_schema_validation_reaudit | human_actor_relationship_schema_review_published;actor_auth_user_relationship_schema_implemented;relationship_model_documentation_published;lilith_validation_audit_published;non_compliant_verdict_issued;schema_corrections_applied;compliance_controls_documented;lilith_reaudit_published;non_compliant_verdict_reconfirmed;primary_invariant_correction_report_published;schema_overconstraint_removed;lilith_final_audit_published;compliant_verdict_issued | channel42_schema_stream | lupo-channels/42/threads/2011/ |
| task_ch42_th2012 | 42 | 2012 | task | completed | 6 | 0 | task_ch42_th2011 | dialog_routing_strategy_design;human_escalation_policy_design;deterministic_selection_rules;destructive_design_validation;audit_blocker_resolution_design_correction;post_correction_reaudit;mvp_routing_engine_implementation;implementation_validation_audit;mvp_hard_correction_pass;final_validation_audit | dialog_routing_design_document_published;traceable_routing_decision_model_defined;lilith_design_validation_audit_published;athena_design_correction_update_published;lilith_design_validation_reaudit_published;ready_for_implementation_verdict_issued;lupo_routing_decisions_schema_added;deterministic_routing_selection_implemented;human_request_integration_linked;loop_prevention_and_idempotency_enforced;lilith_implementation_audit_published;non_compliant_verdict_issued;atomic_idempotency_guard_applied;dispatch_failure_state_correction_applied;actor_override_security_fix_applied;legacy_auth_resolution_removed;hephaestus_correction_report_published;lilith_final_validation_audit_published;compliant_verdict_issued | channel42_strategy_stream | lupo-channels/42/threads/2012/ |
| task_ch42_th2013 | 42 | 2013 | task | completed | 1 | 0 | task_ch42_th2012 | install_sql_schema_audit;version_target_verification;installer_boot_path_check;routing_mvp_status_verification;final_documentation_authority_pass | all_required_tables_confirmed_present;lupo_visibility_state_confirmed_absent;version_4_0_85_canonical;installer_boots_independently;routing_mvp_compliant_per_lilith;dual_pass_verdict_issued;system_cleared_for_install;major_thread_outcomes_migrated_into_version_docs;install_ready_and_documentation_authoritative_state_published | channel42_strategy_stream;channel42_documentation_stream | lupo-channels/42/threads/2013/ |
| task_ch42_th2014 | 42 | 2014 | task | completed | 10 | 0 | task_ch42_th1047;task_ch42_th2004;task_ch42_th2011;task_ch42_th2012;task_ch42_th2013 | root_surface_review;version_surface_review;doctrine_context_review;doom_research_context_review;actor_auth_relationship_context_review;routing_mvp_context_review;overview_alignment_rewrite | root_readme_overview_rewritten;executive_summary_rewritten;version_readme_rewritten;version_overview_rewritten;version_overview_organization_rewritten;lineage_and_rationale_clarified;dialog_completeness_boundary_clarified | channel42_strategy_stream;channel42_documentation_stream | lupo-channels/42/threads/2014/ |
| task_ch42_th2015 | 42 | 2015 | task | completed | 9 | 0 | task_ch42_th1047;task_ch42_th2014 | mood_rgb_repo_evidence_review;dialog_runtime_validation_review;caduceus_hermes_routing_review;channel42_thread_signal_review;legacy_doctrine_review;canonical_doctrine_authoring;lilith_destructive_validation;hybrid_model_resolution | mood_rgb_canonical_root_doctrine_published;mood_rgb_live_token_semantics_formalized;thread2015_record_published;lilith_non_compliant_audit_published;hybrid_authority_model_defined;precedence_rule_published;behavioral_contract_published;structural_resolution_artifact_published | channel42_strategy_stream;channel42_documentation_stream;dialog_runtime_stream;validation_stream | lupo-channels/42/threads/2015/ |
| task_ch51_th1001 | 51 | 1001 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/51/threads/1001/ |
| task_ch51_th1021 | 51 | 1021 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/51/threads/1021/ |
| task_ch51_th1022 | 51 | 1022 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/51/threads/1022/ |
| task_ch51_th1023 | 51 | 1023 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/51/threads/1023/ |
| task_ch51_th1026 | 51 | 1026 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/51/threads/1026/ |
| task_ch51_th1032 | 51 | 1032 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/51/threads/1032/ |
| task_ch51_th1033 | 51 | 1033 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/51/threads/1033/ |
| task_ch51_th1037 | 51 | 1037 | task | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/51/threads/1037/ |
| task_ch51_th1039 | 51 | 1039 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/51/threads/1039/ |
| task_ch66_th1001 | 66 | 1001 | question | completed | 0 | 23 | thread_1047_global_lock | filesystem_thread_inventory;channel66_question_context | root_and_version_docs_synchronized;question_node_preserved | channel42_execution_registry | lupo-channels/66/threads/1001/ |
| task_ch66_th1002 | 66 | 1002 | question | completed | 0 | 8 | thread_1047_global_lock | filesystem_thread_inventory;channel66_question_context | root_and_version_docs_synchronized;question_node_preserved | channel42_execution_registry | lupo-channels/66/threads/1002/ |
| task_ch66_th1003 | 66 | 1003 | question | in-progress | 0 | 9 | thread_1047_global_lock | filesystem_thread_inventory;channel66_question_context | root_and_version_docs_synchronized;question_node_preserved | channel42_execution_registry | lupo-channels/66/threads/1003/ |
| task_ch66_th1004 | 66 | 1004 | question | in-progress | 0 | 1 | thread_1047_global_lock | filesystem_thread_inventory;channel66_question_context | root_and_version_docs_synchronized;question_node_preserved | channel42_execution_registry | lupo-channels/66/threads/1004/ |
| task_ch66_th1005 | 66 | 1005 | question | completed | 4 | 19 | thread_1047_global_lock;edge_ref:edge_ch66_th1005_req_001;edge_ref:edge_ch66_th1005_next_001 | filesystem_thread_inventory;channel66_question_context;edge_ref:edge_ch66_th1005_upstream_001 | root_and_version_docs_synchronized;question_node_preserved;edge_ref:edge_ch66_th1005_downstream_001 | channel42_execution_registry;edge_ref:edge_ch66_th1005_trace_001 | lupo-channels/66/threads/1005/ |
| task_ch66_th1006 | 66 | 1006 | question | completed | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel66_question_context | root_and_version_docs_synchronized;question_node_preserved | channel42_execution_registry | lupo-channels/66/threads/1006/ |
| task_ch66_th1007 | 66 | 1007 | question | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel66_question_context | root_and_version_docs_synchronized;question_node_preserved | channel42_execution_registry | lupo-channels/66/threads/1007/ |
| task_ch66_th1017 | 66 | 1017 | question | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel66_question_context | root_and_version_docs_synchronized;question_node_preserved | channel42_execution_registry | lupo-channels/66/threads/1017/ |
| task_ch66_th1025 | 66 | 1025 | question | completed | 0 | 1 | thread_1047_global_lock | filesystem_thread_inventory;channel66_question_context | root_and_version_docs_synchronized;question_node_preserved | channel42_execution_registry | lupo-channels/66/threads/1025/ |
| task_ch66_th1027 | 66 | 1027 | question | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory;channel66_question_context | root_and_version_docs_synchronized;question_node_preserved | channel42_execution_registry | lupo-channels/66/threads/1027/ |
| task_ch66_th1038 | 66 | 1038 | question | in-progress | 0 | 1 | thread_1047_global_lock | filesystem_thread_inventory;channel66_question_context | root_and_version_docs_synchronized;question_node_preserved | channel42_execution_registry | lupo-channels/66/threads/1038/ |
| task_ch66_th1047 | 66 | 1047 | directive | completed | 0 | 0 | thread_1047_global_lock;edge_ref:edge_ch66_th1047_authority_001 | filesystem_thread_inventory;channel66_directive_context;edge_ref:edge_ch66_th1047_upstream_001 | root_and_version_docs_synchronized;directive_node_preserved;edge_ref:edge_ch66_th1047_downstream_001 | channel42_execution_registry;edge_ref:edge_ch66_th1047_trace_001 | lupo-channels/66/threads/1047/ |
| task_ch88_th1004 | 88 | 1004 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/88/threads/1004/ |
| task_ch420_th1420 | 420 | 1420 | task | in-progress | 0 | 0 | thread_1047_global_lock | filesystem_thread_inventory | root_and_version_docs_synchronized | channel42_core_registry | lupo-channels/420/threads/1420/ |

## THREAD_INDEX Relationship Rule (Authoritative)
- TASK_REGISTRY is authoritative for lifecycle, ownership, and node typing.
- Any THREAD_INDEX is derived navigation only and cannot change task state, ownership, or authority.
- If THREAD_INDEX conflicts with TASK_REGISTRY, TASK_REGISTRY wins and the conflict is logged in CONTRADICTIONS.md.

## Edge-Reference Normalization Layer (Transitional)
- Existing table columns remain stable during v9 synchronization to avoid breaking downstream tooling.
- Transitional rule: edge references are encoded inline using `edge_ref:<edge_id>` markers in existing relationship columns.
- Canonical intent:
  - `dependencies` -> dependency edge references
  - `upstream_requirements` -> upstream edge references
  - `downstream_outcomes` -> downstream edge references
  - `cross_channel_relationships` -> traceability/cross-channel edge references
- Priority targets normalized in this pass: `task_ch66_th1005`, `task_ch66_th1047`, `task_ch42_th1048`, `task_ch42_th2003`.

## Channel 66 Edge-Reference Backfill Inventory (Bounded)
- total_rows_requiring_backfill: 10
- exact_thread_id_list: `1001,1002,1003,1004,1006,1007,1017,1025,1027,1038`
- completion_condition:
  - each listed task row contains at least one `edge_ref:<edge_id>` marker in relationship columns where traceability is required;
  - each newly added marker appears in the edge-reference validation table below with validation_status=`valid`.

## Edge-Reference Validation Gate
| edge_ref_id | source_task_id | resolved_target | resolved_artifact | validation_status | notes |
|---|---|---|---|---|---|
| edge_ch66_th1005_req_001 | task_ch66_th1005 | task_ch66_th1005 (self-scoped dependency marker) | lupo-channels/66/threads/1005/ | valid | source artifact path exists |
| edge_ch66_th1005_next_001 | task_ch66_th1005 | task_ch66_th1005 (self-scoped next-action marker) | lupo-channels/66/threads/1005/ | valid | source artifact path exists |
| edge_ch66_th1005_upstream_001 | task_ch66_th1005 | task_ch66_th1005 (self-scoped upstream marker) | lupo-channels/66/threads/1005/ | valid | source artifact path exists |
| edge_ch66_th1005_downstream_001 | task_ch66_th1005 | task_ch66_th1005 (self-scoped downstream marker) | lupo-channels/66/threads/1005/ | valid | source artifact path exists |
| edge_ch66_th1005_trace_001 | task_ch66_th1005 | task_ch66_th1005 (self-scoped trace marker) | lupo-channels/66/threads/1005/ | valid | source artifact path exists |
| edge_ch66_th1047_authority_001 | task_ch66_th1047 | task_ch66_th1047 (self-scoped authority marker) | lupo-channels/66/threads/1047/ | valid | source artifact path exists |
| edge_ch66_th1047_upstream_001 | task_ch66_th1047 | task_ch66_th1047 (self-scoped upstream marker) | lupo-channels/66/threads/1047/ | valid | source artifact path exists |
| edge_ch66_th1047_downstream_001 | task_ch66_th1047 | task_ch66_th1047 (self-scoped downstream marker) | lupo-channels/66/threads/1047/ | valid | source artifact path exists |
| edge_ch66_th1047_trace_001 | task_ch66_th1047 | task_ch66_th1047 (self-scoped trace marker) | lupo-channels/66/threads/1047/ | valid | source artifact path exists |
| edge_ch42_th1048_dep_ch42_th2003 | task_ch42_th1048 | task_ch42_th2003 | lupo-channels/42/threads/2003/ | valid | target task row and artifact path exist |
| edge_ch42_th1048_out_ch42_th2003 | task_ch42_th1048 | task_ch42_th2003 | lupo-channels/42/threads/2003/ | valid | target task row and artifact path exist |
| edge_ch42_th1048_rel_ch42_th2003 | task_ch42_th1048 | task_ch42_th2003 | lupo-channels/42/threads/2003/ | valid | target task row and artifact path exist |
| edge_ch42_th2003_dep_ch42_th1048 | task_ch42_th2003 | task_ch42_th1048 | lupo-channels/42/threads/1048/ | valid | target task row and artifact path exist |
| edge_ch42_th2003_out_ch42_th1048 | task_ch42_th2003 | task_ch42_th1048 | lupo-channels/42/threads/1048/ | valid | target task row and artifact path exist |
| edge_ch42_th2003_rel_ch42_th1048 | task_ch42_th2003 | task_ch42_th1048 | lupo-channels/42/threads/1048/ | valid | target task row and artifact path exist |

## Decision-System Hook (Placeholder)
- decision_hook_id: decision_system_registry_v1_placeholder
- decision_hook_state: pending_implementation
- decision_hook_scope: task-to-decision lineage registration for 42/1048 and 42/2003 relationship set
- decision_hook_authority: non_authoritative
- decision_hook_registry_effect: none
- decision_hook_execution_dependency: none

## 4.0.85 Documentation Task Definitions

### task_document_lupo_structure_001
- thread: lupo-channels/42/threads/2007/
- purpose: Document all lupo-* directories (purpose, contents, interactions)
- assigned_actor: hephaestus
- output_artifact: lupo-docs/versions/4.0.85/lupo_structure.md
- status: not-started
- requires: access to filesystem directory listing and AGENTS.md key-directories section

### task_document_channel_thread_dialog_001
- thread: lupo-channels/42/threads/2008/
- purpose: Document the channel/thread/dialog workflow model (schema, filesystem, coordination conventions)
- assigned_actor: thoth
- output_artifact: lupo-docs/versions/4.0.85/workflow_model.md
- status: not-started
- requires: review of lupo_channels, lupo_dialog_threads, lupo_dialog_messages, lupo_artifacts tables and channel filesystem structure
