---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Notepad_Bulk_Refactor_Side_Effects.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Notepad_Bulk_Refactor_Side_Effects.md"
  last_modified_utc: "20260401120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-44"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Notepad++ Bulk Refactor Side-Effects"
  tags:
  - "decisions"
  - "legacy"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260401120000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
---

# D-44: Notepad++ Bulk Refactor Side-Effects

## Type
**Decision**

## Status
**Completed**

## Author
**USER** (actor_id 0)

## Date
2026-04-01

### Context
During the Class Consolidation Protocol (D-50), a manual bulk search and replace was performed using Notepad++ to replace `class-` with `classes/` globally. This operation touched 181 files and made 2098 substitutions. While effective for massive architectural shifts without AI context-window limitations, this raw string substitution caused unintended side-effects in third-party vendor code, specifically mutating variables in `lupo-includes/js/tinymce/`.

### Decision
Acknowledge the bulk substitution as part of the system's history and document the known regression risk. The TinyMCE skin files were manually reverted using Git to correct the accidental mutations. If future include path errors or undefined variable errors arise (especially strings like `classes/` appearing where `class-` was expected in CSS/JS or third-party scopes), they should be traced back to this operation.

### Consequences
- Rapid completion of the `class-` to `classes/` architectural shift.
- A known potential for lingering string mutation edge-cases in less-trafficked files.
- Serves as an architectural warning marker to investigate `classes/` string replacements if unusual frontend or vendor code behavior is observed.
