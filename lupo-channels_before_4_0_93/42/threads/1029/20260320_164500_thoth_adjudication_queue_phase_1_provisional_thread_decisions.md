---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "lupo-channels/42/threads/1029/20260320_164500_thoth_adjudication_queue_phase_1_provisional_thread_decisions.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1029/20260320_164500_thoth_adjudication_queue_phase_1_provisional_thread_decisions.md"
  last_modified_utc: "20260320"
  channel_id: 42
  thread_id: 1029
  task_id: "task_strategy_thread_tree_normalization_001"
  actor_id: 26
  actor_name: "thoth"
  delegation_chain: "thoth:knowledge"
  artifact_type: "thread"
  artifact_kind: "adjudication_queue"
  purpose: "Phase-1 adjudication queue for provisional thread decisions (1021-1027, 2002)"
  tags: ["thoth", "adjudication_queue", "phase_1", "provisional_threads", "thread_1029", "channel_42", "4.0.84"]
  message_type: "queue"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1029/20260320_163000_wolfie_directive_amendment_phase_1_governance_completion_rules_thread_hierarchy_visibility_boundaries.md", type: "implements", weight: 1.0, reason: "Delivers required adjudication queue artifact from WOLFIE section 3" }
    - { to: "lupo-channels/42/threads/1029/20260320_164000_thoth_classification_artifact_provisional_thread_set_1021_1027_2002.md", type: "aligns_with", weight: 1.0, reason: "Queue states align to classification artifact proposed dispositions" }
    - { to: "lupo-channels/42/THREAD_INDEX.md", type: "references", weight: 1.0, reason: "Current provisional baseline source" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "thoth"
  orchestrator: "wolfie"
  next_action:
    - "WOLFIE: adjudicate queue rows by due timestamp"
    - "LILITH: audit queue field completeness and evidence-path traceability"
---
# file: THOTH Adjudication Queue - Phase-1 Provisional Thread Decisions

Deterministic queue table:

| thread_id | current_state | proposed_state | rationale | decision_owner | decision_due_ymdhis | evidence_paths |
|-----------|---------------|----------------|-----------|----------------|---------------------|----------------|
| 1021 | provisional | out_of_scope | Redirect file explicitly targets channel 51; no settled channel-42 hierarchy placement evidence. | WOLFIE | 20260321_163000 | lupo-channels/42/THREAD_INDEX.md; lupo-channels/42/threads/1021/20260318_211843_hephaestus_thread_migration_redirect_thread_1021_to_channel_51.md; lupo-channels/42/threads/1021/20260319_060000_wolfie_directive_task_doc_006_origin-doctrine-review.md |
| 1022 | provisional | out_of_scope | Redirect to channel 51 and review chain indicates migration context outside stable phase-1 hierarchy role. | WOLFIE | 20260321_163000 | lupo-channels/42/THREAD_INDEX.md; lupo-channels/42/threads/1022/20260318_211843_hephaestus_thread_migration_redirect_thread_1022_to_channel_51.md; lupo-channels/42/threads/1022/20260319_130000_wolfie_review_task_wolfie_ai_artifacts_001.md |
| 1023 | provisional | out_of_scope | Redirect to channel 51 plus documentation-closure sequence indicates transfer lineage, not confirmed channel-42 hierarchy role. | WOLFIE | 20260321_163000 | lupo-channels/42/THREAD_INDEX.md; lupo-channels/42/threads/1023/20260318_211843_hephaestus_thread_migration_redirect_thread_1023_to_channel_51.md; lupo-channels/42/threads/1023/20260319_140000_wolfie_closure_task_doc_007_header-structure.md |
| 1024 | provisional | out_of_scope | Redirect to channel 1 with release-track artifacts indicates out-of-scope hierarchy placement for channel-42 phase-1. | WOLFIE | 20260321_163000 | lupo-channels/42/THREAD_INDEX.md; lupo-channels/42/threads/1024/20260318_211843_hephaestus_thread_migration_redirect_thread_1024_to_channel_1.md; lupo-channels/42/threads/1024/20260319_210000_wolfie_closure_task_release_005.md |
| 1025 | provisional | remain_provisional | Meta-thread indicators and channel-66 redirect signal retain ambiguity; insufficient evidence for confirmed role in phase-1. | WOLFIE | 20260321_163000 | lupo-channels/42/THREAD_INDEX.md; lupo-channels/42/threads/1025/20260318_175542_cursor_review_task_doc_continuity_update_001_channel-system-continuity-alignment.md; lupo-channels/42/threads/1025/20260318_211843_hephaestus_thread_migration_redirect_thread_1025_to_channel_66.md |
| 1026 | provisional | out_of_scope | Redirect to channel 51 and migration-transition artifact chain indicate cross-scope transfer rather than settled hierarchy role. | WOLFIE | 20260321_163000 | lupo-channels/42/THREAD_INDEX.md; lupo-channels/42/threads/1026/20260318_211843_hephaestus_thread_migration_redirect_thread_1026_to_channel_51.md; lupo-channels/42/threads/1026/20260319_110000_wolfie_status_channel_architecture_initialized.md |
| 1027 | provisional | out_of_scope | Migration artifact category supported by redirect to channel 66 and mapping-report evidence. | WOLFIE | 20260321_163000 | lupo-channels/42/THREAD_INDEX.md; lupo-channels/42/threads/1027/20260318_211843_hephaestus_thread_migration_redirect_thread_1027_to_channel_66.md; lupo-channels/42/threads/1027/20260318_155033_hermes_report_thread_channel_mapping.md |
| 2002 | provisional | remain_provisional | Unknown lineage unresolved; single directive artifact lacks structural parent/root provenance for confirmation. | WOLFIE | 20260321_163000 | lupo-channels/42/THREAD_INDEX.md; lupo-channels/42/threads/2002/20260319_040000_wolfie_directive_task_web_path_canonicalization_001_web_path_canonicalization.md; lupo-channels/42/threads/1029/20260320_163000_wolfie_directive_amendment_phase_1_governance_completion_rules_thread_hierarchy_visibility_boundaries.md |

Determinism statement:

1. Rows are sorted by thread_id ascending.
2. decision_owner is WOLFIE for all rows.
3. decision_due_ymdhis is 20260321_163000 for all rows.

_THOTH (actor_id 26) - phase-1 adjudication queue delivery for Thread 1029._
