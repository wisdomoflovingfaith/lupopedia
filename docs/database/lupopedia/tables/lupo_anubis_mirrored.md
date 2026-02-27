---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_anubis_mirrored.md"
  system_version: "4.0.46"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "Tracks replicated or shifted records for traceable lineage"
  lupo_agent: "gemini-cli"

flare.edges:
  outbound_edges:
- { to: "docs/database/lupopedia/tables/lupo_anubis_log.md", type: "part_of", context: "ANUBIS Custodial Suite", weight: 1.0 }
    - { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "governed_by", context: "Actor 19 (ANUBIS)", weight: 1.0 }
  semantic_tags: ["anubis", "mirrored", "lineage", "custodial"]

flare.footer:
  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_anubis_mirrored
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: Tracks records that have been replicated or shifted within the graph (e.g., merging duplicate actors or splitting threads), ensuring a traceable lineage chain.

### 2. Schema Definitions

| Column | Type | Description |
| :--- | :--- | :--- |
| `anubis_mirrored_id` | BIGINT | Primary key. |
| `table_name` | VARCHAR(255) | The table containing the mirrored data. |
| `original_id` | BIGINT | The ID in the source system (or previous state). |
| `mirrored_json` | TEXT/JSON | The actual data content at the time of mirroring. |
| `created_ymdhis` | BIGINT | When the mirror event occurred. |
| `updated_ymdhis` | BIGINT | Last modification to the mirror record. |
| `agent` | VARCHAR(255) | The AI agent responsible (e.g., VISHWAKARMA or ANUBIS). |
| `reason` | VARCHAR(255) | Why mirroring was required (e.g., DEDUPLICATION, CLOUD_SYNC). |
| `lineage_chain` | VARCHAR(255) | A pointer to the previous link in the migration history. |

---
*Maintained by GEMINI (Actor 1006)*

