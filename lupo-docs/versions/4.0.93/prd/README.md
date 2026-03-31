# PRD (Product Requirements Documents) — Version 4.0.93

---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  file_path_from_root: /lupo-docs/versions/4.0.93/prd/README.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/prd/README.md
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "prd-index"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "prd"
  artifact_kind: "prd_index"
  purpose: "Index and Guide to 4.0.93 PRDs"
  tags:
  - "prd"
  - "v4.0.93"
  - "index"
  traits:
    - prd
    - v4.0.93
    - doctrine
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.93/prd/01_semantic_monitoring_widget.md"
      type: references
      weight: 1.0
      reason: Semantic Monitoring Widget PRD
    - to: "lupo-docs/versions/4.0.93/prd/02_data_model.md"
      type: references
      weight: 1.0
      reason: Data Model PRD
    - to: "lupo-docs/versions/4.0.93/prd/03_goals_and_success_criteria.md"
      type: references
      weight: 1.0
      reason: Goals and Success Criteria PRD
    - to: "lupo-docs/versions/4.0.93/prd/04_lupopedia_js_foundation.md"
      type: references
      weight: 1.0
      reason: JS Foundation PRD
    - to: "lupo-docs/versions/4.0.93/prd/05_auth_user_actor_agent_transformation.md"
      type: references
      weight: 1.0
      reason: Auth User Actor Transformation PRD
    - to: "lupo-database/lupopedia/json/lupo_contexts.json"
      type: references
      weight: 1.0
      reason: Contexts table definition
    - to: "lupo-database/lupopedia/json/lupo_edges.json"
      type: references
      weight: 1.0
      reason: Edges table definition
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"


## Temporal Anchor & UTC Timestamp Policy (4.0.93+)

All Lupopedia header timestamps (`last_modified_utc` in `lupopedia.headers`) must be synchronized to real UTC, never local time or a timezone. The IDE and all header writers must reference the canonical anchor file:

- `lupo-bin/temporal_anchor.json`

This file is updated by:
- [`lupo-bin/tick.py`](../../../lupo-bin/tick.py) — see [lupo-docs/lupo-bin/TICK_PY.md](../../../lupo-docs/lupo-bin/TICK_PY.md)

**tick.py** is a required utility script that updates the anchor file with the current UTC time in `YYYYMMDDHHMMSS` format. The IDE must call this script after every session or major write to ensure all header timestamps are synchronized to real UTC. See the [tick.py documentation](../../../lupo-docs/lupo-bin/TICK_PY.md) for usage and policy.

This folder contains the product requirements and planning documents carried over and updated from 4.0.88. All new PRD work for 4.0.90 should be added here.

## 🚨 Identity Model & Permission Rule Alignment (4.0.93)

The canonical agent→actor→auth_user leasing model and permission rules are now enforced for all operational identity and access control. See:

- [ACTOR_LEASING_DOCTRINE.md](/lupo-docs/doctrine/ACTOR_LEASING_DOCTRINE.md)
- [ACTOR_TEMPLATE_MODEL.md](/lupo-docs/doctrine/ACTOR_TEMPLATE_MODEL.md)
- [ACTOR_INSTANCE_MODEL.md](/lupo-docs/doctrine/ACTOR_INSTANCE_MODEL.md)
- [ACTOR_LEASE_SESSION_MODEL.md](/lupo-docs/doctrine/ACTOR_LEASE_SESSION_MODEL.md)
- [05_auth_user_actor_agent_transformation.md](/lupo-docs/versions/4.0.93/prd/05_auth_user_actor_agent_transformation.md)

All PRDs and implementation must reference and comply with these docs. See also: [lupo_actor_auth_users.md](/lupo-docs/database/lupopedia/tables/active/lupo_actor_auth_users.md) (deprecated).
