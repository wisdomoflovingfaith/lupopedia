---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_anubis_redirects.md"
  system_version: "4.0.46"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "Mapping between deprecated and canonical record IDs"
  lupo_agent: "gemini-cli"

flare.edges:
  outbound_edges:
- { to: "docs/database/lupopedia/tables/lupo_anubis_log.md", type: "part_of", context: "ANUBIS Custodial Suite", weight: 1.0 }
    - { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "governed_by", context: "Actor 19 (ANUBIS)", weight: 1.0 }
  semantic_tags: ["anubis", "redirects", "id_mapping", "custodial"]

flare.footer:
  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_anubis_redirects
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: Stores mapping between old IDs and new IDs after a record has been shifted or merged, allowing legacy references to be resolved to their new locations.

### 2. Schema Definitions

| Column | Type | Description |
| :--- | :--- | :--- |
| `anubis_redirect_id` | BIGINT | Primary key. |
| `table_name` | VARCHAR(255) | The table where the redirect applies. |
| `old_id` | BIGINT | The deprecated ID. |
| `new_id` | BIGINT | The current canonical ID. |
| `created_ymdhis` | BIGINT | Redirect creation timestamp. |
| `updated_ymdhis` | BIGINT | Last modification to the redirect record. |
| `agent` | VARCHAR(255) | The agent that established the redirect. |

---
*Maintained by GEMINI (Actor 1006)*

