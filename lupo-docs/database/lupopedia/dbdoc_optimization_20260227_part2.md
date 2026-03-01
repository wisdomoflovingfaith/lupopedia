# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\dbdoc_optimization_20260227_part2.md"
  file_hash: "22c34da8319ba9682512eee648011385e0eb79b2ceea3f10696fa96297f92a19"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/dbdoc_optimization_20260227_part2.md"
  file_hash: "78f7579dcc9ed0885ae3d2e239f1e2641ce63ca107ab1589b32bf5815ce2757e"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "report"
  purpose: "DBDOC batch 2 optimization review for five tables"
  dialog_message: "Recommended next step: approve optimization items, then apply TOON -> install schema -> dev migration workflow."
  mood_rgb: "4B0082"
  artifact_kind: "database_review"
  traits: ["database", "optimization", "dbdoc"]
  tags: ["dbdoc", "optimization", "4.0.49", "batch2"]
  lupo_agent: "codex-ide"

  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  outbound_edges:
    - { to: "docs/database/lupopedia/tables/lupo_analytics_visits.md", type: "references", weight: 0.8, reason: "reviewed table" }
    - { to: "docs/database/lupopedia/tables/lupo_federation_nodes.md", type: "references", weight: 0.8, reason: "reviewed table" }
    - { to: "docs/database/lupopedia/tables/lupo_auth_users.md", type: "references", weight: 0.8, reason: "reviewed table" }
    - { to: "docs/database/lupopedia/tables/lupo_dialog_threads.md", type: "references", weight: 0.8, reason: "reviewed table" }
    - { to: "docs/database/lupopedia/tables/lupo_dialog_messages.md", type: "references", weight: 0.8, reason: "reviewed table" }
  semantic_tags: ["dbdoc", "optimization", "batch2"]
  last_verified: "20260227"
  last_verified_by: "codex-ide"
---

# DBDOC Optimization Review - Batch 2 (20260227)

Scope: lupo_analytics_visits, lupo_federation_nodes, lupo_auth_users, lupo_dialog_threads, lupo_dialog_messages.

## Summary
This report lists optimization and schema adjustments that would improve performance and consistency while respecting doctrine (no foreign keys, no triggers, no stored procedures). All changes must follow TOON update -> install_new_lupopedia.sql -> dev migration workflow.

## Proposed Changes (By Table)

### 1) lupo_analytics_visits
- Add composite index on (federation_node_id, date_ymd) for federation reporting.
- Add composite index on (department_id, date_ymd) for department-level reporting.
- Consider adding archived_ymdhis index for retention purges.

### 2) lupo_federation_nodes
- Add unique index on node_base_url for consistent lookup.
- Add composite index on (status, trust_level) for dashboard filters.
- Consider parent_node_id if hierarchical federation is planned.

### 3) lupo_auth_users
- Add composite index on (email, is_active) to speed auth lookups.
- Add password_updated_ymdhis for rotation policies.
- Add locked_until_ymdhis for lockout workflows.

### 4) lupo_dialog_threads
- Add composite index on (channel_id, status, last_message_ymdhis) for listing.
- Add message_count cache column for faster list rendering.
- Add last_message_id for precise pagination.

### 5) lupo_dialog_messages
- Add composite index on (dialog_thread_id, created_ymdhis) for thread loads.
- Add message_hash for deduplication and idempotency.
- Consider text search indexing via lupo_search_index.

## Notes
- All changes require updated TOON files and a new dev migration.
- Avoid FK constraints; enforce relationships in application code.
- For high-volume tables, prefer composite indexes over ad-hoc queries.

## Next Steps
1. Approve which changes to implement.
2. Update TOON files for selected tables.
3. Update install_new_lupopedia.sql.
4. Create a dev migration SQL file for live databases.