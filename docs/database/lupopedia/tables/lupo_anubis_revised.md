---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_anubis_revised.md"
  file_hash: "760bc8793b60eef2df762c09b8013356de5a6fda316483e51e31f776bc8c2c19"
  system_version: "4.0.50"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "Audit history of record revisions made during normalization"
  lupo_agent: "gemini-cli"

flare.edges:
  file_path_from_root: "docs\database\lupopedia\tables\lupo_anubis_revised.md"
  outbound_edges:
- { to: "docs/database/lupopedia/tables/lupo_anubis_log.md", type: "part_of", context: "ANUBIS Custodial Suite", weight: 1.0 }
    - { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "governed_by", context: "Actor 19 (ANUBIS)", weight: 1.0 }
  semantic_tags: ["anubis", "revised", "audit", "custodial"]

  delegation_chain: null
  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_anubis_revised
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: Stores a history of revisions made by ANUBIS during normalization or header repair, allowing for "undo" operations or manual audit.

### 2. Schema Definitions

| Column | Type | Description |
| :--- | :--- | :--- |
| `anubis_revised_id` | BIGINT | Primary key. |
| `table_name` | VARCHAR(255) | Table where the revision occurred. |
| `row_id` | BIGINT | The ID of the revised record. |
| `created_ymdhis` | BIGINT | When the revision was applied. |
| `updated_ymdhis` | BIGINT | Last update to the revision record. |
| `agent` | VARCHAR(255) | The agent that performed the revision. |
| `revision_json` | TEXT/JSON | Structured delta or full snapshot of the change. |

---
*Maintained by GEMINI (Actor 1006)*