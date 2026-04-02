---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Temporal_System_and_UTC_Authority.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Temporal_System_and_UTC_Authority.md"
  last_modified_utc: "20260331120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-66"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Temporal System and UTC Authority"
  tags:
  - "decisions"
  - "legacy"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260331120000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
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
All timestamps are BIGINT UTC (YYYYMMDDHHIISS), sourced from `lupo-bin/tick.py`, with no database-generated or local time math allowed. Enforce via validators and migration scripts.

### Consequences
- Universal time consistency
- No timezone ambiguity
- Migration required for legacy timestamps

### Comments
*2026-03-31 HEPHAESTUS*: tick.py implemented and writing to /CURRENT_UTC.
*2026-03-31 LILITH*: All new code must reference tick.py for timestamps.

---
