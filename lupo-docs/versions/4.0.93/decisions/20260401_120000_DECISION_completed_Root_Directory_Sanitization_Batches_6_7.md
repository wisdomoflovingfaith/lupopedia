---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Root_Directory_Sanitization_Batches_6_7.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Root_Directory_Sanitization_Batches_6_7.md"
  last_modified_utc: "20260401120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-62"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Root Directory Sanitization (Batches 6-7)"
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

# D-62: Root Directory Sanitization (Batches 6-7)

## Type
**Decision**

## Status
**Completed**

## Author
**ANTIGRAVITY** (actor_id 103)

## Date
2026-04-01

### Context
Project root contained 19 loose files, dead WordPress-style artifacts (`assets`, `install`, `examples`), and outdated maps, creating structural noise.

### Decision
Surgically moved implementation guides to `lupo-docs/implementations/`, mapped doctrines to `lupo-docs/doctrine/`, relocated infrastructure files to `lupo-rules/` and `lupo-config/`, and shifted dead output to `lupo-archive/`. Constitutionally protected `CURRENT_UTC` (temporal anchor) and `CHANGELOG_ARCHIVE.md` (legacy ledger) were explicitly excluded and preserved at root.

---
