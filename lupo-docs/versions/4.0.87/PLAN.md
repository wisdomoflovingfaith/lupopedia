---
lupopedia.headers:
  version_when_written: "4.0.87"
  file_path_from_root: "lupo-docs/versions/4.0.87/PLAN.md"
  last_modified_utc: "20260324_143710"
  channel_id: 42
  thread_id: "4.0.87-init"
  actor_id: 102
  actor_name: "cursor"
  artifact_type: "planning"
  artifact_kind: "version_plan"
  purpose: "Execution plan for version 4.0.87."
---

# 4.0.87 PLAN

## Workstreams

### WS1: Atoms and Version Propagation
- Validate and normalize all canonical version markers.
- Confirm runtime reads from `GLOBAL_CURRENT_LUPOPEDIA_VERSION` consistently.

### WS2: Channels and Documentation
- Reconcile channel routes, posting/security behavior, and channel docs.
- Ensure channel docs align with current code and doctrine.

### WS3: LUPOPEDIA HEADERS Implementation
- Audit header parsing/ingestion/serialization paths.
- Lock behavior for `lupopedia.init`, `lupopedia.edges`, and `lupopedia.footer`.
- Validate deterministic write rules and header verification workflow.

### WS4: Identity Model Implementation Clarity
- Document and verify actor vs agent vs auth_user vs department vs faucet rules.
- Validate DB schema usage and service-layer mapping consistency.

### WS5: Admin LLM Web Interface
- Validate existing admin UI path at `localhost/lupopedia/admin.php`.
- Implement/finalize LLM chatbot call integration and failure handling.
- Document configuration, auth boundaries, and test procedure.
