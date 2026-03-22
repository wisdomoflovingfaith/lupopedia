---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/versions/4.0.84/database_changes/README.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.84/database_changes"
  last_modified_utc: "20260320"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "documentation"
  artifact_kind: "database_changes"
  title: "Database Changes - Version 4.0.84"
  purpose: "Documentation of database-related changes in version 4.0.84"
  tags: ["version", "4.0.84", "database", "changes", "schema"]
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Monitor database consistency with single-field versioning model"
    - "Validate TOON schema integration"
---

# file: Database Changes - Version 4.0.84

## Summary

Version 4.0.84 includes **significant canonical schema interventions** (via Channel 42 Threads 1031 and 1032). The focus was enforcing project model constraints and restructuring unapproved visibility DDL definitions.

## Database Changes

### Schema Changes
- **lupo_actor_projects** - New table installed for global system project scope mappings.
- **Project IDs introduced globally** - `project_id` added natively to `lupo_channels`, `lupo_dialog_threads`, `lupo_tasks`, `lupo_edges`, `lupo_metadata`, and `lupo_atoms`.
- **Visibility parameters** - `visibility_status` appended to threads, tasks, edges, channels.
- **Granular visibility control** - Additional fields defined for `owner_actor_id`, `access_level`, `assigned_actor_id`
- **Tables Rejected:** `lupo_visibility_state` (purged by WOLFIE governance).

### Data Changes
- **Metadata consistency** - Version fields in `lupo_metadata` may need cleanup
- **Content versioning** - Ensure `version_when_written` is used consistently
- **System Backfills** - Default visibility settings applied (e.g. `visibility_status`='active', `project_id`=0 where null).

### Migration Scripts
- **dev_20260321_project_model_and_schema_authority.sql** - Formal path to evolve instances to capture Thread 1031 visibility and Thread 1032 project constraints safely.

## Impact Analysis

### Positive Impact
- **Reduced complexity** - Single version field eliminates confusion
- **Consistent versioning** - All artifacts use same version field pattern
- **Cleaner metadata** - Removed deprecated version fields

### Risk Assessment
- **Low risk** - No schema changes
- **Backward compatible** - Existing data remains valid
- **No downtime** - No database operations required

## Related Files

- [LUPOPEDIA_HEADERS_FORMAT.md](../../../doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md)
- [VERSIONING_DOCTRINE.md](../../../doctrine/VERSIONING_DOCTRINE.md)
- TOON Schema Files

## Validation

### Pre-deployment Checks
- [x] Verify no schema changes needed
- [x] Confirm existing data compatibility
- [x] Validate version field usage patterns

### Post-deployment Checks
- [ ] Monitor database consistency
- [ ] Verify metadata cleanup success
- [ ] Validate version resolution accuracy

---

*Last updated: 2026-03-20*
