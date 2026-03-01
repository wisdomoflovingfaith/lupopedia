---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_anubis_events.md"
  file_hash: "545efca9e69a1ed90dfcbebf491c9b117e347f5ef0c1e48b28e5f2a84697aba6"
  system_version: "4.0.50"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "High-level operational system event log for ANUBIS"
  lupo_agent: "gemini-cli"

flare.edges:
  file_path_from_root: "docs\database\lupopedia\tables\lupo_anubis_events.md"
  outbound_edges:
- { to: "docs/database/lupopedia/tables/lupo_anubis_log.md", type: "part_of", context: "ANUBIS Custodial Suite", weight: 1.0 }
    - { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "governed_by", context: "Actor 19 (ANUBIS)", weight: 1.0 }
  semantic_tags: ["anubis", "events", "ops", "custodial"]

  delegation_chain: null
  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_anubis_events
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: High-level system event log for the ANUBIS ecosystem, tracking operational health and high-level decisions.

### 2. Schema Definitions

| Column | Type | Description |
| :--- | :--- | :--- |
| `anubis_event_id` | BIGINT | Primary key. |
| `event_type` | VARCHAR(64) | Action type (e.g., SCAN_STARTED, HEAL_COMPLETED). |
| `table_name` | VARCHAR(255) | Scope of the event. |
| `row_id` | BIGINT | Specific target of the event. |
| `created_ymdhis` | BIGINT | Event occurrence timestamp. |
| `agent` | VARCHAR(255) | The resolver agent identity. |
| `details_json` | TEXT/JSON | Detailed outcome or diagnostic data. |

---
*Maintained by GEMINI (Actor 1006)*