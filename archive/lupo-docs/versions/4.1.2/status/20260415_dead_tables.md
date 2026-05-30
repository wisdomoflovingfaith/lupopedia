---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-docs/versions/4.1.2/status/20260415_dead_tables.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.2/status/20260415_dead_tables.md"
  status: "active"
  when_updated: "20260415233000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/version-4-1-2-dead-tables-20260415.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/4_1_2_status_dead_tables_20260415"
  artifact_type: status
  artifact_kind: report
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: 8067324253853516193
  content_slug: "version-4-1-2-dead-tables-20260415"
  default_collection_id: null
  lupopedia.schema: status
  title: "4.1.2 dead table scan (2026-04-15)"
  summary: "Install SQL table extraction and code-reference audit. Ejected 41 orphan tables from blueprints and metadata. Preserved dialog_pending_tasks and votes."
---
# Dead Table Audit & Ejection Report — 2026-04-15

## Scope and method

- Extracted all table definitions from `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`.
- Scanned code references across `lupo-scripts/**`, `lupo-includes/**`, and root `*.php`.
- Result: `41` tables confirmed dead and ejected.

## Ejected Tables [EJECTED/DELETED]

The following tables have been removed from `install_new_lupopedia.sql` and their corresponding `.json`/`.toon` metadata files have been purged:

1.  [EJECTED/DELETED] `actor_filesystem`
2.  [EJECTED/DELETED] `actor_sync_state`
3.  [EJECTED/DELETED] `actor_pairing`
4.  [EJECTED/DELETED] `actor_relationships`
5.  [EJECTED/DELETED] `actor_apps`
6.  [EJECTED/DELETED] `agent_performance_stats`
7.  [EJECTED/DELETED] `agent_tools`
8.  [EJECTED/DELETED] `agent_boundaries`
9.  [EJECTED/DELETED] `actor_versions`
10. [EJECTED/DELETED] `agent_definition_versions`
11. [EJECTED/DELETED] `actor_runtime_state`
12. [EJECTED/DELETED] `actor_runtime_events`
13. [EJECTED/DELETED] `faucet_rules`
14. [EJECTED/DELETED] `pairing_rules`
15. [EJECTED/DELETED] `department_capabilities`
16. [EJECTED/DELETED] `identity_layers`
17. [EJECTED/DELETED] `identity_context`
18. [EJECTED/DELETED] `agent_memory_config`
19. [EJECTED/DELETED] `anubis_log`
20. [EJECTED/DELETED] `two_factor_audit`
21. [EJECTED/DELETED] `magic_link_tokens`
22. [EJECTED/DELETED] `auth_rate_limits`
23. [EJECTED/DELETED] `channel_escalations`
24. [EJECTED/DELETED] `channel_escalation_rules`
25. [EJECTED/DELETED] `channel_state`
26. [EJECTED/DELETED] `legacy_content_mapping`
27. [EJECTED/DELETED] `channel_departments`
28. [EJECTED/DELETED] `dialog_recent_files`
29. [EJECTED/DELETED] `emotional_frameworks`
30. [EJECTED/DELETED] `help_tree`
31. [EJECTED/DELETED] `semantic_index`
32. [EJECTED/DELETED] `truth_context_map`
33. [EJECTED/DELETED] `truth_followers`
34. [EJECTED/DELETED] `paths_summary`
35. [EJECTED/DELETED] `reference_map`
36. [EJECTED/DELETED] `collection_links`
37. [EJECTED/DELETED] `world_registry`
38. [EJECTED/DELETED] `actor_skills`
39. [EJECTED/DELETED] `actor_tools`
40. [EJECTED/DELETED] `actor_prompts`
41. [EJECTED/DELETED] `actor_training`

## Preserved Tables (Active Planning)

- `dialog_pending_tasks` (Orchestration Layer P0)
- `votes` (Polymorphic engagement model)

## Verification

- `install_new_lupopedia.sql` cleaned.
- `lupo-database/lupopedia/json/*.json` purged.
- `lupo-database/lupopedia/toon/*.toon` purged.
- Final table count in blueprints: **142**.
