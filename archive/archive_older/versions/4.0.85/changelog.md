---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260322184651"
  file_path_from_root: "docs/versions/4.0.85/CHANGELOG.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.85/CHANGELOG.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: derived_view
  thread_id: 1047
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# 4.0.85 CHANGELOG

> Derived summary. See TASK_REGISTRY for task-level change tracking.

## 4.0.85 Changes

### Coordination Model
- TASK_REGISTRY established as single source of truth for all task and question state (Thread 1047)
- THREAD_INDEX files demoted to navigation-only (no task state authority)
- CONTRADICTIONS.md established as diagnostic-only violation and ambiguity registry
- Edge-reference normalization layer added to TASK_REGISTRY (transitional v9)
- Final governance model consolidated into version-scoped authority documentation

### Schema / TOON
- TOON parity fully restored: 166 install tables = 166 TOON files (Thread 2004)
- Stale TOON `lupo_visibility_state` removed (table does not exist in install SQL)
- New TOONs generated: lupo_thread_metadata, lupo_human_requests, lupo_human_request_context, lupo_human_request_responses, lupo_decision_evidence
- Corrected TOONs: lupo_actors (missing column), lupo_channels (extra stale columns), lupo_dialog_threads (extra stale columns), lupo_tasks (extra stale columns)

### Relationship / Routing / Doctrine
- Many-to-many actor/auth_user support model corrected and documented as canonical (Thread 2011)
- Dialog routing MVP implemented, corrected, and validated as COMPLIANT (Thread 2012)
- Dual PASS install-readiness verdict issued for schema and runtime system (Thread 2013)
- mood_vector doctrine resolved into hybrid authoritative-token plus vector-only routing model (Thread 2015)

### Installer Version Correction
- Install surface version drift corrected: installer display now resolves dynamically from canonical atom version source (`GLOBAL_CURRENT_LUPOPEDIA_VERSION`)
- Removed hardcoded installer/runtime fallback values that could force stale versions during standalone install entry
- Confirmed installer renders 4.0.85 and no active hardcoded 4.0.74 remains in version-indicator PHP paths

### Research (Classified â€” Not Applied)
- Doom Emacs structural patterns researched and classified (Thread 2005 â€” THOTH)
- BMAD method workflow patterns researched and classified (Thread 1050 â€” THOTH)
- Decision lineage design developed (Threads 1048, 2003 â€” ATHENA) â€” deferred to 4.0.86

### Documentation (Thread 2006)
- README.md created for docs/versions/4.0.85/
- All version documentation stubs populated with current system state
- Install SQL confirmed correct for DROP + full INSTALL cycle
- Two documentation task threads created: 2007 (lupo structure) and 2008 (workflow model)

### Final Documentation Consolidation
- Major thread outcomes migrated into version-scoped domain files
- Channel 42 canonical summary added as a non-thread final-state system summary
- 4.0.85 now readable as install-ready and system-compliant without thread archaeology
