---
lupopedia.headers:
  lupopedia.version: "4.0.89"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/versions/4.0.89/legacy_research/feature_mapping_matrix.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.89/legacy_research/feature_mapping_matrix.md"
  last_modified_utc: "20260328120000"
  system_version: "4.0.89"
  channel_id: 42
  thread_id: "4.0.89-legacy-research"
  actor_id: 23
  actor_name: "thoth"
  delegation_chain: "wolfie:thoth"
  artifact_type: "documentation"
  artifact_kind: "feature_mapping"
  purpose: "Mapping of Crafty Syntax 3.7.5 features to Lupopedia implementation requirements"
  mood_rgb: "666666"
  traits: ["legacy_research", "crafty_syntax", "feature_mapping", "4.0.89"]
  tags: ["4.0.89", "legacy", "crafty_syntax", "features", "mapping"]

lupopedia.edges:
  outbound_edges:
    - { to: "feature_inventory.md", type: "builds_on", weight: 1.0, reason: "Feature inventory provides mapping source" }
    - { to: "lupo-docs/versions/4.0.89/crafty_syntax_backlog.md", type: "informs", weight: 1.0, reason: "Backlog updated with mapping requirements" }
    - { to: "lupo-docs/versions/4.0.89/lupopedia_js_spec.md", type: "informs", weight: 1.0, reason: "JavaScript requirements from feature mapping" }
    - { to: "implementation_requirements.md", type: "creates", weight: 1.0, reason: "Implementation requirements derived from mapping" }
    - { to: "integration_planning.md", type: "creates", weight: 1.0, reason: "Integration planning based on mapping analysis" }
    - { to: "migration_strategies.md", type: "creates", weight: 1.0, reason: "Migration strategies based on feature analysis" }

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
    - "Complete detailed analysis for Priority 0 and Priority 1 features"
    - "Map implementation approach for each feature"
    - "Identify integration points with existing systems"
    - "Update crafty_syntax_backlog.md with specific requirements"
---
