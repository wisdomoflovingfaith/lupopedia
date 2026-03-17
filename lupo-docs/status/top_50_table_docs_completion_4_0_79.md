---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  Lupopedia.version_written: "4.0.79"
  lupopedia.schema: "status_report"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/status/top_50_table_docs_completion_4_0_79.md"
  web_path: "http://www.lupopedia.com/status/top_50_table_docs_completion_4_0_79"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "status"
  artifact_kind: "completion_report"
  purpose: "Completion report: Top 50 table docs + grounded edges + TABLE_INDEX completion for 4.0.79"
  tags: ["top_50", "table_docs", "edges", "table_index", "4.0.79"]
lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: top_50_table_docs_completion_4_0_79 ? web_path: http://www.lupopedia.com/status/top_50_table_docs_completion_4_0_79

# Top 50 Table Docs Completion (4.0.79)

Captain directive source: `lupo-docs/status/what_needs_to_be_done_to_finish_4_0_79.md`.

## 1. Top 50 table list (exact set = 50)

| # | Table | Doc status | Edges (PHP/Python hits) |
|---:|------|-----------|------------------------|
| 1 | lupo_actors | existed and updated | php=33 / py=12 |
| 2 | lupo_actor_channels | existed and updated | php=8 / py=4 |
| 3 | lupo_actor_departments | existed and updated | php=3 / py=3 |
| 4 | lupo_agents | existed and updated | php=5 / py=4 |
| 5 | lupo_agent_faucets | existed and updated | php=3 / py=2 |
| 6 | lupo_analytics_visits | existed and updated | php=2 / py=2 |
| 7 | lupo_atoms | existed and updated | php=0 / py=3 |
| 8 | lupo_audit_log | existed and updated | php=1 / py=2 |
| 9 | lupo_auth_providers | existed and updated | php=0 / py=1 |
| 10 | lupo_auth_users | existed and updated | php=16 / py=3 |
| 11 | lupo_banned_actors | existed and updated | php=3 / py=3 |
| 12 | lupo_bans_log | existed and updated | php=2 / py=0 |
| 13 | lupo_channels | existed and updated | php=17 / py=8 |
| 14 | lupo_collections | existed and updated | php=0 / py=1 |
| 15 | lupo_comments | existed and updated | php=0 / py=1 |
| 16 | lupo_contents | existed and updated | php=15 / py=11 |
| 17 | lupo_departments | existed and updated | php=8 / py=1 |
| 18 | lupo_dialog_messages | existed and updated | php=23 / py=7 |
| 19 | lupo_federation_nodes | existed and updated | php=2 / py=4 |
| 20 | lupo_metadata | existed and updated | php=4 / py=2 |
| 21 | lupo_modules | existed and updated | php=2 / py=1 |
| 22 | lupo_registry | existed and updated | php=4 / py=7 |
| 23 | lupo_sessions | existed and updated | php=26 / py=4 |
| 24 | lupo_system_logs | existed and updated | php=0 / py=1 |
| 25 | lupo_uploads | existed and updated | php=1 / py=0 |
| 26 | lupo_visits | existed and updated | php=3 / py=2 |
| 27 | lupo_actor_apps | existed and updated | php=0 / py=0 |
| 28 | lupo_analytics_campaign_vars | existed and updated | php=1 / py=1 |
| 29 | lupo_auth_audit_log | existed and updated | php=2 / py=0 |
| 30 | lupo_channel_departments | existed and updated | php=0 / py=0 |
| 31 | lupo_edge_type_definitions | existed and updated | php=0 / py=0 |
| 32 | lupo_channel_content | existed and updated | php=1 / py=1 |
| 33 | lupo_legacy_content_mapping | existed and updated | php=0 / py=0 |
| 34 | lupo_artifacts | existed and updated | php=2 / py=2 |
| 35 | lupo_artifact_chunks | existed and updated | php=1 / py=0 |
| 36 | lupo_cip_analytics | existed and updated | php=3 / py=0 |
| 37 | lupo_actor_capabilities | existed and updated | php=1 / py=1 |
| 38 | lupo_actor_actions | existed and updated | php=2 / py=1 |
| 39 | lupo_actor_channel_roles | existed and updated | php=15 / py=3 |
| 40 | lupo_actor_traits | created in this pass | php=0 / py=1 |
| 41 | lupo_action_authorization | created in this pass | php=1 / py=1 |
| 42 | lupo_channel_state | existed and updated | php=1 / py=1 |
| 43 | lupo_dialog_threads | existed and updated | php=9 / py=5 |
| 44 | lupo_help_topics | existed and updated | php=1 / py=1 |
| 45 | lupo_permissions | existed and updated | php=3 / py=1 |
| 46 | lupo_search_index | existed and updated | php=0 / py=0 |
| 47 | lupo_semantic_index | existed and updated | php=0 / py=1 |
| 48 | lupo_tasks | existed and updated | php=4 / py=1 |
| 49 | lupo_truth_knowledge | existed and updated | php=1 / py=1 |
| 50 | lupo_unified_log | created in this pass | php=0 / py=0 |

## 2. Edge population summary (grounded repo search)

- **Tables with USED_IN_PHP hits:** 37 / 50
- **Tables with USED_IN_PYTHON hits:** 39 / 50
- **Tables with no PHP literal refs found:** 13
- **Tables with no Python literal refs found:** 11

**No PHP refs found (literal table-name search):** lupo_atoms, lupo_auth_providers, lupo_collections, lupo_comments, lupo_system_logs, lupo_actor_apps, lupo_channel_departments, lupo_edge_type_definitions, lupo_legacy_content_mapping, lupo_actor_traits, lupo_search_index, lupo_semantic_index, lupo_unified_log

**No Python refs found (literal table-name search):** lupo_bans_log, lupo_uploads, lupo_actor_apps, lupo_auth_audit_log, lupo_channel_departments, lupo_edge_type_definitions, lupo_legacy_content_mapping, lupo_artifact_chunks, lupo_cip_analytics, lupo_search_index, lupo_unified_log

Notes:
- Edges are **grounded by literal table-name matches** in `*.php` and `*.py`. This is intentionally conservative (no inferred/indirect usage).
- Each table doc includes `DEFINES_SCHEMA_FOR` and `schema_reference` plus `USED_IN_*` edges (or explicit ?no refs found? placeholders).
- Edges are a snapshot and are not guaranteed exhaustive; re-scan when code moves.

## 3. Header/namespace/domain validation summary

- **All 50 docs** were normalized to a **single** LUPOPEDIA HEADERS YAML block at file start (no duplicate frontmatter blocks).
- `lupopedia.version` and `system_version` are **4.0.79** across all 50.
- Each doc has `artifact_type: table_documentation`, `artifact_kind: table`, and a non-empty `namespace` consistent with table purpose (auth/content/channels/analytics/core/truth).

## 4. TABLE_INDEX completion

- `lupo-docs/database/lupopedia/tables/TABLE_INDEX.md` already had a table index body; this pass ensured it has a valid LUPOPEDIA HEADERS block (completed earlier) and appended a **Top 50 completion (4.0.79)** section containing any Top 50 tables not already listed in the index.

## 5. Notes / doctrine ambiguities

- Some historically ?Top 50? candidates mentioned in earlier planning (`lupo_content_versions`, `lupo_content_revisions`, `lupo_content_tags`, `lupo_content_collections`, `lupo_analytics_events`, `lupo_actor_events`) were **not present in install_new_lupopedia.sql** in this repo state. Per doctrine, no schema was invented; the completed Top 50 set is grounded in existing table docs and install-backed system-critical tables.
- Two tables in this Top 50 set (`lupo_analytics_visits`, `lupo_system_logs`) are present in docs but were not found as `CREATE TABLE` statements in install_new_lupopedia.sql; this is flagged for follow-up schema/doctrine alignment.

## 6. Files created in this pass

- `lupo-docs/database/lupopedia/tables/active/lupo_action_authorization.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_actor_traits.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_unified_log.md`
