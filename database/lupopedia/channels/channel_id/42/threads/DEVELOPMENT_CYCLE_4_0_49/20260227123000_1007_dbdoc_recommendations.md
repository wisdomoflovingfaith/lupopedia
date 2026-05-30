# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "database/lupopedia/channels/channel_id/42/threads/DEVELOPMENT_CYCLE_4_0_49/20260227123000_1007_dbdoc_recommendations.md"
  file_hash: "b0884b3116130000806cc234ace58128c20a1166fbbc523453417da71972d5ff"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  file_path_from_root: "channels/42/threads/DEVELOPMENT_CYCLE_4_0_49/20260227123000_1007_dbdoc_recommendations.md"
  file_hash: "eb0c34def8f46c592da97e018c4a2ac3e79bb9149c13ee4a6fa1d15a87c033c0"
  system_version: "4.0.50"
  channel_id: 42
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "report"
  purpose: "DBDOC review and recommended database alterations based on TOONs"
  dialog_message: "Recommended next step: validate missing TOONs for listed tables and prioritize schema consistency fixes."
  mood_vector: "4B0082"
  artifact_kind: "database_review"
  traits: ["canonical", "development", "dbdoc"]
  tags: ["dbdoc", "schema", "recommendations", "4.0.49"]
  lupo_agent: "codex-ide"

  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  outbound_edges:
    - { to: "channels/42/threads/DEVELOPMENT_CYCLE_4_0_49/20260227100800_10000_1001_version_4_0_49_initialized.md", type: "references", weight: 0.8, reason: "4.0.49 cycle thread" }
    - { to: "docs/toons/", type: "references", weight: 0.7, reason: "TOON schema source" }
    - { to: "docs/database/lupopedia/tables/", type: "references", weight: 0.7, reason: "table documentation" }
  semantic_tags: ["dbdoc", "schema", "recommendations", "channel_42"]
  last_verified: "20260227"
  last_verified_by: "codex-ide"
---

# DBDOC Review and Schema Recommendations (4.0.49)

**Scope:** Review TOON coverage for tables listed in the DBDOC task brief and recommend schema alterations to improve Lupopedia.

**Summary**
- All TOON `table_name` entries are documented under `docs/database/lupopedia/tables/` (216/216 by `table_name`).
- Several tables in the priority list do not have TOON files present, so they cannot be reviewed yet.
- The main schema consistency gaps are missing `updated_ymdhis` and missing soft-delete fields on a few operational tables, plus inconsistent federation node field naming.

**Tables In Priority List With NO TOON File (Not Reviewable Yet)**
- `lupo_tags`
- `lupo_categories`
- `lupo_agent_capabilities`
- `lupo_webhooks`
- `lupo_dialog_participants`
- `lupo_dialog_attachments`
- `lupo_user_preferences`
- `lupo_user_sessions`
- `lupo_media`
- `lupo_attachments`
- `lupo_thumbnails`

**Observed TOON Coverage (Reviewed)**
- `lupo_document_embeddings`
- `lupo_collections`
- `lupo_search_index`
- `lupo_agents`
- `lupo_agent_heartbeats`
- `lupo_agent_tool_calls`
- `lupo_api_tokens`
- `lupo_analytics_visits`
- `lupo_federation_nodes`
- `lupo_auth_users`
- `lupo_dialog_threads`

**Recommended Alterations (Based on TOON Fields)**
- Standardize federation node naming:
  - `lupo_collections` and `lupo_analytics_visits` use `federations_node_id` while other tables use `federation_node_id`.
  - Recommendation: rename to `federation_node_id` for consistency; update indexes and docs accordingly.
- Add `updated_ymdhis` where missing:
  - `lupo_document_embeddings`, `lupo_agent_heartbeats`, `lupo_agent_tool_calls`, `lupo_api_tokens`.
  - Rationale: consistent update timestamps across operational tables.
- Add soft-delete fields where missing (doctrine alignment):
  - `lupo_agent_tool_calls`, `lupo_api_tokens`, `lupo_analytics_visits` lack `is_deleted` and `deleted_ymdhis`.
  - Recommendation: add `is_deleted TINYINT DEFAULT 0` and `deleted_ymdhis BIGINT DEFAULT 0`.
- Add operational cleanup markers for high-volume log tables:
  - `lupo_agent_tool_calls`: add `archived_ymdhis` or `purged_ymdhis` for retention policies.
  - `lupo_analytics_visits`: add `archived_ymdhis` or `purged_ymdhis` to support data retention windows.
- Add index coverage for common lookups:
  - `lupo_agents`: consider index on `api_key_id` if API key linkage is frequently queried.
  - `lupo_agent_tool_calls`: consider composite index on `(agent_id, created_ymdhis)` for time-range queries by agent.
  - `lupo_api_tokens`: consider composite index on `(actor_id, is_active)` for active-token lookups.

**Notes**
- All recommendations require TOON updates first, then DDL update in `database/migrations/install_new_lupopedia.sql`, then a dev migration per doctrine.
- No foreign keys, triggers, or computed columns should be introduced.

**Next Actions**
- Confirm missing TOON files for the listed tables and either add TOONs or update the priority list.
- If approved, implement the above schema adjustments through the TOON ? install schema ? dev migration pipeline.
