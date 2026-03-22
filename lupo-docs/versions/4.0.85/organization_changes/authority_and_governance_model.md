---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "lupo-docs/versions/4.0.85/organization_changes/authority_and_governance_model.md"
  last_modified_utc: "20260322_184651"
  channel_id: 42
  thread_id: 1047
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "documentation"
  artifact_kind: "governance_model"
  purpose: "Canonical 4.0.85 authority and governance model derived from Thread 1047 final outcomes."
---

# 4.0.85 Authority And Governance Model

## Purpose

This document is the canonical 4.0.85 location for the final governance outcomes of Thread 1047.

It defines which files are authoritative, which files are derived, and which files are diagnostic only. No thread artifact is required to understand the governance model after reading this document.

## Final Authority Model

### Authoritative

- `lupo-docs/versions/4.0.85/TASK_REGISTRY.md`
  - the only authoritative task and question system of record
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
  - schema DDL authority
- domain-specific 4.0.85 documentation files under this version directory
  - final version-scoped behavioral, organizational, and schema outcomes

### Diagnostic Only

- `lupo-docs/versions/4.0.85/CONTRADICTIONS.md`
  - records contradictions, violations, and ambiguity states
  - does not own execution state or close tasks

### Navigation Only

- `lupo-channels/*/THREAD_INDEX.md`
  - historical navigation surfaces only
  - cannot define status, ownership, or lifecycle

### Root Pointer Surfaces

- `README.md`
- `CHANGELOG.md`
- `TODO.md`
- `plan.md`

These are root orientation and pointer surfaces. For 4.0.85+, they must not compete with version-folder authority.

## Governance Rules

1. Tasks and questions live in `TASK_REGISTRY.md` only.
2. Contradictions live in `CONTRADICTIONS.md` only.
3. Thread indexes are navigation only.
4. Threads are historical evidence, not authority.
5. Final 4.0.85 system behavior must be readable from version docs alone.

## Structural Outcome Of Thread 1047

Thread 1047 established the decisive governance correction for 4.0.85:

- duplicate authority surfaces were demoted
- lifecycle and ownership authority were centralized
- version docs became the canonical explanation layer
- root files became high-level orientation instead of overloaded status ledgers

Condensed canonical outcome:

- structural correction complete
- TASK_REGISTRY is the only authority for task state
- THREAD_INDEX is navigation-only

## Compliance Meaning

Because of this model:

- readers no longer need thread archaeology to determine current state
- task status and documentation authority no longer compete
- 4.0.85 can be understood as a coherent install-ready, system-compliant version from docs alone
