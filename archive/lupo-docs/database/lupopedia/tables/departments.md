---
lupopedia.headers:
  file_path_from_root: "lupo-docs/database/lupopedia/tables/departments.md"
  file_hash: "fd8699a89b465a2522e53a5075d1d72f90429389d5d49acf399e4ab88d1e3ef2"
  system_version: "4.0.50"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "Core identity and configuration for system departments"
  lupo_agent: "gemini-cli"

lupopedia.edges:
  file_path_from_root: "lupo-docs\database\lupopedia\tables\departments.md"
  outbound_edges:
- { to: "lupo-docs/database/lupopedia/tables/actor_departments.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/federation_nodes.md", type: "references", weight: 0.7 }
    - { to: "lupo-database/lupopedia/toon/lupo_departments.toon.json", type: "schema_reference", weight: 1.0 }
  semantic_tags: ["departments", "organization", "structure", "routing"]

  delegation_chain: null
  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_departments
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: **Department identity**: core fields for each department (id, name, description, type, default_actor_id, federation_node_id, settings). Department_id 0 is reserved for â€œsystemâ€; department_id 1 is the general default. UI, branding, and behavior settings that were in legacy department tables live in **lupo_department_metadata** (JSON).

**Schema:** See `lupo-database/lupopedia/toon/lupo_departments.toon.json`. Primary key: `department_id`. No AUTO_INCREMENT for reserved IDs (0, 1); installer/seed set them explicitly.

### 2. Core Workflows

- **Routing and grouping:** Channels and actors are associated with departments via lupo_actor_departments and (for channels) channelâ€“department linkage. Department roles (lupo_department_roles) grant department-scoped permissions.
- **System department:** department_id = 0 is reserved, not user-selectable; used for global/system admin context.
- **Default department:** department_id = 1 is the general default when no department is specified.
- **Metadata:** Legacy UI colors, images, timeouts, and toggles are in lupo_department_metadata (one row per department, JSON), not in this table.

### 3. Mapping from Crafty Syntax

**Legacy table:** `livehelp_departments` (one table held both identity and many UI/behavior fields).

**Migration:** `lupo-docs/doctrine/migrations/livehelp_departments_migration.md`, `import_from_old_crafty_syntax.sql`.

- **Split:** Core identity â†’ **lupo_departments** (department_id, name, description, department_type, default_actor_id, federation_node_id, lifecycle). UI/behavior/branding â†’ **lupo_department_metadata** (JSON).
- **Reserved rows:** Install/seed ensure department_id 0 (System) and 1 (default) exist; import may overwrite or skip depending on legacy recno.
- **Result:** livehelp_departments â†’ IMPORTED â†’ SPLIT â†’ DROPPED.

---
*Maintained by GEMINI (Actor 1006)*

