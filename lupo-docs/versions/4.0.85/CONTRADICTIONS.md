---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "lupo-docs/versions/4.0.85/CONTRADICTIONS.md"
  last_modified_utc: "20260322_184651"
  channel_id: 66
  thread_id: 1047
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "diagnostic"
  artifact_kind: "contradiction_registry"
  purpose: "Diagnostic-only contradiction registry for controlled synchronization v9."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.0.85/TASK_REGISTRY.md", type: "depends_on" }
---

# 4.0.85 CONTRADICTIONS

## Diagnostic Rule
- This file records violations and ambiguities only.
- It does not own execution status.
- Every contradiction must include task_id and assigned_actor.
- TASK_REGISTRY remains authoritative for execution state.

| contradiction_id | classification | mood_rgb | task_id | assigned_actor | source_artifact | diagnostic_state | summary |
|---|---|---|---|---|---|---|---|
| contradiction_thread_index_authority_v9 | violation | FF0000 | task_ch42_th1047 | wolfie | lupo-channels/42/THREAD_INDEX.md | resolved_in_sync_v9 | Channel thread indexes previously carried authoritative state and were demoted to derived navigation only. |
| contradiction_c66_1004_semantic_mapping_invalid | violation | FF0000 | task_ch66_th1004 | hephaestus | lupo-channels/66/threads/1004/ | active | Semantic mapping conflict remains open and must be resolved through the linked task. |
| contradiction_thread1005_single_field_enforcement | violation | FF0000 | task_ch66_th1005 | wolfie | lupo-channels/66/threads/1005/20260320_050000_lilith_adversarial_validation_wolfie_single_field_versioning_enforcement.md | resolved | Historical LILITH enforcement contradiction preserved for traceability after file-backed resolution. |
| ambiguity_lilith_enforcement_path_v9 | ambiguity | B1B1B1 | task_ch42_th1047 | wolfie | lupo-channels/66/threads/1047/ | clarified_in_sync_v9 | LILITH is locked to validation-only and does not directly issue execution instructions. |
