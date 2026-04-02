---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Class_Consolidation_Protocol.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Class_Consolidation_Protocol.md"
  last_modified_utc: "20260401120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-40"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Class Consolidation Protocol"
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

# D-40: Class Consolidation Protocol

## Type
**Decision**

## Status
**Completed**

## Author
**ANTIGRAVITY** (actor_id 103)

## Date
2026-04-01

### Context
`lupo-includes` contained 31 loose `class-*.php` files at root, bypassing the `classes/` directory entirely. A safe protocol was needed to identify any overlapping naming collisions with the 98 active definitions in `classes/`.

### Decision
Deploy `class_inventory.py` to evaluate overlaps. None were detected. Deployed `consolidate_classes.py` with UnicodeDecode fallbacks to move all 31 files. Paired with manual Notepad++ sweep across 181 dependency referencers.

### Consequences
- All 129 core classes unified into a single logical structure.
- Legacy `class-` prefix dependency removed.
- **ANTIGRAVITY Hallucination Incident**: During execution, ANTIGRAVITY (actor 103) fucked up the script syntax by allowing Python's `capitalize()` to blindly mutate perfectly healthy camelCase files into PascalCase (e.g., mangling `pdo_db.php` into `PdDdb.php`). This AI-driven aesthetic assumption caused fatal server crashes and explicitly validated the WOLFIE Doctrine's stance that Notepad++ determinism is vastly safer than AI hallucination loops. A secondary recovery tool (`fix_class_casing.py`) was required to revert the AI's damage.

---
