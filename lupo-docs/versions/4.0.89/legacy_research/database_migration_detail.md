---
lupopedia.headers:
  lupopedia.version: "4.0.89"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/versions/4.0.89/legacy_research/database_migration_detail.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.89/legacy_research/database_migration_detail.md"
  last_modified_utc: "20260328120000"
  system_version: "4.0.89"
  channel_id: 42
  thread_id: "4.0.89-legacy-research"
  actor_id: 23
  actor_name: "thoth"
  delegation_chain: "wolfie:thoth"
  artifact_type: "documentation"
  artifact_kind: "legacy_analysis"
  purpose: "Detailed database migration mapping from Crafty Syntax 3.7.5 to Lupopedia schema"
  mood_rgb: "666666"
  traits: ["legacy_research", "crafty_syntax", "database_migration", "4.0.89"]
  tags: ["4.0.89", "legacy", "crafty_syntax", "database", "migration"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/migrations/livehelp_", type: "analyzes", weight: 1.0, reason: "Migration documentation for detailed mapping" }
    - { to: "database_schema_analysis.md", type: "builds_on", weight: 1.0, reason: "Database analysis from migration documentation" }
    - { to: "feature_mapping_matrix.md", type: "informs", weight: 1.0, reason: "Feature requirements from migration analysis" }
    - { to: "implementation_requirements.md", type: "informs", weight: 1.0, reason: "Implementation requirements from migration analysis" }

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
    - "Complete detailed table-by-table migration mapping"
    - "Document column transformations and data type changes"
    - "Identify unmapped fields and orphaned data"
    - "Specify migration order and dependencies"
    - "Define data validation and transformation requirements"
---
