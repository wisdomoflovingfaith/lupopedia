---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: null
  file_path_from_root: "docs/versions/4.0.85/database_changes/schema_reconciliation_and_toon_state.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: schema_state
  thread_id: 2004
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
# 4.0.85 Schema Reconciliation And TOON State

## Purpose

This document is the canonical 4.0.85 location for the final outcomes of Thread 2004.

It defines the final accepted schema state, the install SQL to TOON relationship, and the boundary between accepted schema and deferred research.

## Final Schema State

- install SQL tables: `166`
- TOON files: `166`
- column-set mismatches: `0`
- column-order mismatches: `0`
- schema authority: `database/lupopedia/mysql/install/install_new_lupopedia.sql`
- TOON status: derived from accepted schema authority; never hand-edited

## Resolved Drift

### Removed stale projection

- `lupo_visibility_state`
  - rejected as canonical schema
  - removed from TOON projections
  - confirmed absent from install SQL

### New accepted TOON files

- `lupo_thread_metadata`
- `lupo_human_requests`
- `lupo_human_request_context`
- `lupo_human_request_responses`
- `lupo_decision_evidence`

### Corrected TOON files

- `lupo_actors`
- `lupo_channels`
- `lupo_dialog_threads`
- `lupo_tasks`

## Accepted 4.0.85 Schema Meaning

The reconciled schema now includes the structures required for:

- actor and auth-user relationship correction
- deterministic routing decision storage
- human request creation and linkage
- decision evidence capture
- thread metadata support

This means the install SQL is now sufficient for the canonical drop-all -> Crafty import -> install.php cycle used in 4.0.85 validation.

## Research Boundary

Doom Emacs research influenced classification and future design thinking, but it did not gain schema authority in 4.0.85.

Binding rule:

- research may inform future changes
- research may not silently alter accepted schema
- only the reconciled install SQL and its derived TOON state define canonical schema behavior in 4.0.85

## Final Outcome

Thread 2004 restored trust in schema discussion.

After reconciliation:

- install SQL and TOONs agree
- rejected tables are absent
- accepted tables are documented
- schema documentation can now describe the system without ambiguity or thread-only caveats

Condensed canonical outcome:

- schema reconciliation complete
- TOON parity locked at 166/166
- Doom research remains classified as research-only
- `lupo_visibility_state` is removed from authoritative schema projections
