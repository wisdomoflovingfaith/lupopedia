---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_channel_content.md"
  web_path: "[lupo_channel_content](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_channel_content)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "channels"
  purpose: "lupo_channel_content"
  tags: ["database", "table", "channels"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_channel_content table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=1 python_hits=1"
  outbound_edges:
    - { to: "database.table.lupo_channel_content", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-bin/initialize_system.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "analyze_unused_tables.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_channel_content ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_channel_content
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

lupopedia.conditional:
  guards:
    execution_mode: "advisory"
    allow:
      actor_ids: [0, 1004]
      agent_names: ["system", "antigravity"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-11T00:00:00Z"
    conditions:
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents"]
    what:
      artifact_type: "guide"
      objective: "lupo_channel_content"
    where:
      repo_paths: ["lupo-docs\database\lupopedia\tables\lupo_channel_content.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T10:08:33Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs\database\lupopedia\tables\lupo_channel_content.md"
  file_hash: "e4e0a52442c7673cf7c21a49ba6c594928e45cd9a0391c45a3762fbb40b9f00c"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "lupo_channel_content"
  namespace: "channels"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-docs", "database", "lupopedia", "tables", "lupo_channel_contentmd"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-docs\database\lupopedia\tables\lupo_channel_content.md", "http://www.lupopedia.com/LUPO_CHANNEL_CONTENT"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004

**Table Created**: 2026-03-01  
**Lead Agent**: Windsurf (1002)  
**Version**: 4.0.52  
**Status**: ✅ DOCUMENTED
