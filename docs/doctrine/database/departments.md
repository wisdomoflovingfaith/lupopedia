---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/database/departments.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
---

# lupo_departments

**Purpose:** **Department identity**: core fields for each department (id, name, description, type, default_actor_id, federation_node_id, settings). Department_id 0 is reserved for “system”; department_id 1 is the general default. UI, branding, and behavior settings that were in legacy department tables live in **lupo_department_metadata** (JSON).

**Schema:** See `docs/toons/lupo_departments.toon.json`. Primary key: `department_id`. No AUTO_INCREMENT for reserved IDs (0, 1); installer/seed set them explicitly.

---

## Use and need

- **Routing and grouping:** Channels and actors are associated with departments via lupo_actor_departments and (for channels) channel–department linkage. Department roles (lupo_department_roles) grant department-scoped permissions.
- **System department:** department_id = 0 is reserved, not user-selectable; used for global/system admin context.
- **Default department:** department_id = 1 is the general default when no department is specified.
- **Metadata:** Legacy UI colors, images, timeouts, and toggles are in lupo_department_metadata (one row per department, JSON), not in this table.

---

## Mapping from Crafty Syntax

**Legacy table:** `livehelp_departments` (one table held both identity and many UI/behavior fields).

**Migration:** `docs/doctrine/migrations/livehelp_departments_migration.md`, `import_from_old_crafty_syntax.sql`.

- **Split:** Core identity → **lupo_departments** (department_id, name, description, department_type, default_actor_id, federation_node_id, lifecycle). UI/behavior/branding → **lupo_department_metadata** (JSON).
- **Reserved rows:** Install/seed ensure department_id 0 (System) and 1 (default) exist; import may overwrite or skip depending on legacy recno.
- **Result:** livehelp_departments → IMPORTED → SPLIT → DROPPED.
