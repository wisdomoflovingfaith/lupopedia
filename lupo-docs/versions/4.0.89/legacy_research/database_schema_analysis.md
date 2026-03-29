---
lupopedia.headers:
  lupopedia.version: "4.0.89"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/versions/4.0.89/legacy_research/database_schema_analysis.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.89/legacy_research/database_schema_analysis.md"
  last_modified_utc: "20260328120000"
  system_version: "4.0.89"
  channel_id: 42
  thread_id: "4.0.89-legacy-research"
  actor_id: 23
  actor_name: "thoth"
  delegation_chain: "wolfie:thoth"
  artifact_type: "documentation"
  artifact_kind: "legacy_analysis"
  purpose: "Analysis of Crafty Syntax 3.7.5 database schema vs Lupopedia schema"
  mood_rgb: "666666"
  traits: ["legacy_research", "crafty_syntax", "database_analysis", "4.0.89"]
  tags: ["4.0.89", "legacy", "crafty_syntax", "database", "schema", "analysis"]

lupopedia.edges:
  outbound_edges:
    - { to: "file_structure_analysis.md", type: "builds_on", weight: 1.0, reason: "Schema comparison based on file structure" }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "compares", weight: 1.0, reason: "Canonical Lupopedia schema" }
    - { to: "lupo-database/lupopedia/toon/", type: "compares", weight: 0.9, reason: "TOON exports for validation" }
    - { to: "lupo-docs/database/lupopedia/tables/active/", type: "informs", weight: 0.8, reason: "Table documentation updates" }
    - { to: "feature_inventory.md", type: "informs", weight: 1.0, reason: "Implementation requirements from schema analysis" }

lupopedia.footer:
  last_verified: "20260328120000"
  verified_by:
    identity_type: "actor"
    actor_id: 23
    agent_name_identity: "THOTH"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "windsurf"
  orchestrator: "wolfie:thoth"
  next_action:
    - "Complete detailed table-by-table migration analysis"
    - "Document schema evolution requirements"
    - "Identify data migration strategies"
    - "Update implementation requirements based on schema differences"
---
