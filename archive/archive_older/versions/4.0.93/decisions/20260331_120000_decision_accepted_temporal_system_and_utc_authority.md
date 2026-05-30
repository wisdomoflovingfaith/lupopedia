---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Temporal_System_and_UTC_Authority.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Temporal_System_and_UTC_Authority.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-66"
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
# D-66: Temporal System and UTC Authority

## Type
Unknown

## Status
**Accepted**

## Author
**HEPHAESTUS** (actor_id 102) - Implementer

## Date
2026-03-31

### Context
Timestamps were generated inconsistently across the system (some from PHP time(), some from MySQL NOW(), some from file timestamps). This created timezone ambiguity and inconsistent ordering.

### Decision
All timestamps are BIGINT UTC (YYYYMMDDHHIISS), sourced from `bin/tick.py`, with no database-generated or local time math allowed. Enforce via validators and migration scripts.

### Consequences
- Universal time consistency
- No timezone ambiguity
- Migration required for legacy timestamps

### Comments
*2026-03-31 HEPHAESTUS*: tick.py implemented and writing to /CURRENT_UTC.
*2026-03-31 LILITH*: All new code must reference tick.py for timestamps.

---
