-- Optional / future-features tables moved from install_new_lupopedia.sql (v4.0.57).
-- Do NOT run during standard install; run only if these features are enabled.
-- Canonical install: install_new_lupopedia.sql only. No FKs, no triggers, BIGINT timestamps.
-- See lupo-docs/versions/REQUIRED_TABLES_4.0.21.md (optional → future-features).

-- =============================================================================
-- lupo_aliases (optional; moved 4.0.57)
-- =============================================================================
CREATE TABLE lupo_aliases (
  alias_id int NOT NULL,
  slug varchar(255) NOT NULL,
  alias varchar(255) NOT NULL,
  alias_type varchar(50) DEFAULT 'semantic',
  created_at bigint,
  PRIMARY KEY (alias_id)
);

CREATE UNIQUE INDEX lupo_aliases_uniq_alias ON lupo_aliases (alias);
CREATE INDEX lupo_aliases_idx_slug ON lupo_aliases (slug);

-- =============================================================================
-- lupo_anubis_orphaned (optional; moved 4.0.57)
-- =============================================================================
CREATE TABLE lupo_anubis_orphaned (
  anubis_orphaned_id bigint NOT NULL,
  table_name varchar(255) NOT NULL,
  orphan_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  reason varchar(255) NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (anubis_orphaned_id)
);

-- =============================================================================
-- lupo_tldnr (optional; moved 4.0.57)
-- =============================================================================
CREATE TABLE lupo_tldnr (
  tldnr_id bigint NOT NULL,
  slug varchar(255) NOT NULL,
  title varchar(255) NOT NULL,
  content_text text NOT NULL,
  topic_type varchar(100) DEFAULT NULL,
  topic_reference varchar(255) DEFAULT NULL,
  system_version varchar(20) DEFAULT NULL,
  category varchar(100) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (tldnr_id)
);

CREATE UNIQUE INDEX lupo_tldnr_uniq_slug ON lupo_tldnr (slug);
CREATE INDEX lupo_tldnr_idx_topic_type ON lupo_tldnr (topic_type);
CREATE INDEX lupo_tldnr_idx_topic_reference ON lupo_tldnr (topic_reference);
CREATE INDEX lupo_tldnr_idx_category ON lupo_tldnr (category);
CREATE INDEX lupo_tldnr_idx_system_version ON lupo_tldnr (system_version);
CREATE INDEX lupo_tldnr_idx_is_deleted ON lupo_tldnr (is_deleted);
CREATE INDEX lupo_tldnr_idx_created ON lupo_tldnr (created_ymdhis);

-- =============================================================================
-- lupo_temporal_coherence_snapshots (optional; unused at runtime; moved from install)
-- =============================================================================
CREATE TABLE lupo_temporal_coherence_snapshots (
  snapshot_id bigint NOT NULL,
  utc_anchor bigint NOT NULL,
  observation_latency_ms int NOT NULL DEFAULT 0,
  recursion_depth tinyint NOT NULL DEFAULT 0,
  self_awareness_score decimal(3,2) DEFAULT NULL,
  timestamp_integrity varchar(32) NOT NULL DEFAULT 'unknown',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (snapshot_id)
);

CREATE INDEX lupo_temporal_coherence_snapshots_idx_created_ymdhis ON lupo_temporal_coherence_snapshots (created_ymdhis);
CREATE INDEX lupo_temporal_coherence_snapshots_idx_utc_anchor ON lupo_temporal_coherence_snapshots (utc_anchor);
CREATE INDEX lupo_temporal_coherence_snapshots_idx_is_deleted ON lupo_temporal_coherence_snapshots (is_deleted);

-- =============================================================================
-- lupo_system_health_snapshots (removed from install; not used; no temporal snapshots)
-- =============================================================================
CREATE TABLE lupo_system_health_snapshots (
  health_id bigint NOT NULL,
  table_count int NOT NULL,
  table_ceiling int NOT NULL,
  schema_state varchar(64) NOT NULL DEFAULT 'unknown',
  sync_integrity varchar(32) NOT NULL DEFAULT 'unknown',
  emotional_r decimal(3,2) DEFAULT NULL,
  emotional_g decimal(3,2) DEFAULT NULL,
  emotional_b decimal(3,2) DEFAULT NULL,
  emotional_t decimal(3,2) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (health_id)
);

CREATE INDEX lupo_system_health_snapshots_idx_created_ymdhis ON lupo_system_health_snapshots (created_ymdhis);
CREATE INDEX lupo_system_health_snapshots_idx_table_count ON lupo_system_health_snapshots (table_count);
CREATE INDEX lupo_system_health_snapshots_idx_is_deleted ON lupo_system_health_snapshots (is_deleted);

-- =============================================================================
-- Batch move 2026-03-09: tables not in minimal_tables.md and not referenced by active PHP/PY
-- =============================================================================
-- =============================================================================
-- lupo_actor_aliases (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE `lupo_actor_aliases` (
    `alias_id` BIGINT NOT NULL AUTO_INCREMENT,
    `actor_id` BIGINT NOT NULL,
    `alias_name` VARCHAR(255) NOT NULL,
    `created_ymdhis` BIGINT NOT NULL,
    `updated_ymdhis` BIGINT NOT NULL,
    PRIMARY KEY (`alias_id`)
);

-- =============================================================================
-- lupo_actor_object_edges (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_actor_object_edges (
  actor_object_edge_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  target_table varchar(100) NOT NULL,
  target_id bigint NOT NULL,
  edge_type varchar(50) NOT NULL,
  properties_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (actor_object_edge_id)
);
CREATE INDEX lupo_actor_object_edges_idx_actor_edge_type ON lupo_actor_object_edges (actor_id, edge_type);
CREATE INDEX lupo_actor_object_edges_idx_target_lookup ON lupo_actor_object_edges (target_table, target_id);

-- =============================================================================
-- lupo_actor_persona_relationships (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_actor_persona_relationships (
  relationship_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  persona_id bigint NOT NULL,
  relationship_type varchar(100) NOT NULL,
  relationship_strength decimal(5,2) DEFAULT NULL,
  relationship_context json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (relationship_id)
);
CREATE INDEX lupo_actor_persona_relationships_idx_persona_id ON lupo_actor_persona_relationships (persona_id);
CREATE INDEX lupo_actor_persona_relationships_idx_relationship_type ON lupo_actor_persona_relationships (relationship_type);

-- =============================================================================
-- lupo_actor_relationship_rules (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_actor_relationship_rules (
    rule_id BIGINT NOT NULL,
    source_actor_id BIGINT NOT NULL,
    target_actor_id BIGINT NOT NULL,
    relationship_type VARCHAR(100) NOT NULL,
    rule_type VARCHAR(50) NOT NULL,
    conditions JSON,
    actions JSON,
    weight FLOAT DEFAULT 1,
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT
);
CREATE INDEX lupo_actor_relationship_rules_idx_relationship_type ON lupo_actor_relationship_rules(relationship_type);
CREATE INDEX lupo_actor_relationship_rules_idx_rule_type ON lupo_actor_relationship_rules(rule_type);
CREATE INDEX lupo_actor_relationship_rules_idx_is_deleted ON lupo_actor_relationship_rules(is_deleted);

-- =============================================================================
-- lupo_actor_truth_edges (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_actor_truth_edges (
  actor_truth_edge_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  truth_item_id bigint NOT NULL,
  edge_type varchar(64) NOT NULL,
  properties_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (actor_truth_edge_id)
);
CREATE INDEX lupo_actor_truth_edges_idx_actor_edge_type ON lupo_actor_truth_edges (actor_id, edge_type);
CREATE INDEX lupo_actor_truth_edges_idx_truth_item ON lupo_actor_truth_edges (truth_item_id);

-- =============================================================================
-- lupo_analytics_referers_periods (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_analytics_referers_periods (
  analytics_referers_period_id bigint NOT NULL,
  content_id bigint NOT NULL DEFAULT '0',
  url_path varchar(500) NOT NULL DEFAULT '',
  referer_content_id bigint NOT NULL DEFAULT '0',
  referer_url_path varchar(500) NOT NULL DEFAULT '',
  parent_id bigint NOT NULL DEFAULT '0',
  level int NOT NULL DEFAULT '1',
  department_id bigint NOT NULL DEFAULT '1',
  period_type varchar(64) NOT NULL,
  period_date bigint NOT NULL,
  visits int NOT NULL DEFAULT '0',
  direct_visits int NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (analytics_referers_period_id)
);
CREATE INDEX lupo_analytics_referers_periods_idx_period_date ON lupo_analytics_referers_periods (period_date);
CREATE INDEX lupo_analytics_referers_periods_idx_content ON lupo_analytics_referers_periods (content_id, period_date);
CREATE INDEX lupo_analytics_referers_periods_idx_referer ON lupo_analytics_referers_periods (referer_content_id, period_date);
CREATE INDEX lupo_analytics_referers_periods_idx_department ON lupo_analytics_referers_periods (department_id, period_date);
CREATE INDEX lupo_analytics_referers_periods_idx_level ON lupo_analytics_referers_periods (level, period_date);

-- =============================================================================
-- lupo_anubis_deletion_log (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_anubis_deletion_log (
  anubis_deletion_id bigint NOT NULL,
  table_name varchar(255) NOT NULL,
  record_id bigint NOT NULL,
  deleted_ymdhis bigint NOT NULL,
  deletion_type varchar(64) NOT NULL,
  replacement_table varchar(255) DEFAULT NULL,
  replacement_id bigint DEFAULT NULL,
  anubis_operator varchar(255) NOT NULL,
  context_json json DEFAULT NULL,
  notes text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (anubis_deletion_id)
);
CREATE INDEX lupo_anubis_deletion_log_idx_deleted_time ON lupo_anubis_deletion_log (deleted_ymdhis);

-- =============================================================================
-- lupo_anubis_mirrored (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_anubis_mirrored (
  anubis_mirrored_id bigint NOT NULL,
  table_name varchar(255) NOT NULL,
  original_id bigint NOT NULL,
  mirrored_json text NOT NULL,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  agent varchar(255) NOT NULL,
  reason varchar(255) NOT NULL,
  lineage_chain varchar(255) DEFAULT NULL,
  PRIMARY KEY (anubis_mirrored_id)
);

-- =============================================================================
-- lupo_anubis_revised (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_anubis_revised (
  anubis_revised_id bigint NOT NULL,
  table_name varchar(255) NOT NULL,
  row_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  agent varchar(255) NOT NULL,
  revision_json text NOT NULL,
  PRIMARY KEY (anubis_revised_id)
);

-- =============================================================================
-- lupo_channel_boot_log (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_channel_boot_log (
  boot_id bigint NOT NULL,
  actor_id bigint DEFAULT NULL,
  session_id varchar(64) DEFAULT NULL,
  boot_start_time bigint,
  boot_end_time bigint,
  boot_status varchar(64) NOT NULL DEFAULT 'started',
  channels_loaded int NOT NULL DEFAULT '0',
  total_channels int NOT NULL DEFAULT '0',
  error_details json DEFAULT NULL,
  performance_metrics json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (boot_id)
);
CREATE INDEX lupo_channel_boot_log_idx_boot_status_time ON lupo_channel_boot_log (boot_status, boot_start_time);

-- =============================================================================
-- lupo_comments (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_comments (
  comment_id bigint NOT NULL,
  domain_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  target_table varchar(100) NOT NULL DEFAULT 'lupo_contents',
  target_id bigint NOT NULL,
  parent_comment_id bigint DEFAULT NULL,
  comment_text text NOT NULL,
  status varchar(64) NOT NULL DEFAULT 'approved',
  user_agent varchar(255) DEFAULT NULL,
  ip_hash char(64) DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (comment_id)
);
CREATE INDEX lupo_comments_idx_actor_id ON lupo_comments (actor_id);
CREATE INDEX lupo_comments_idx_target ON lupo_comments (target_table, target_id);
CREATE INDEX lupo_comments_idx_parent ON lupo_comments (parent_comment_id);
CREATE INDEX lupo_comments_idx_status ON lupo_comments (status);
CREATE INDEX lupo_comments_idx_is_deleted ON lupo_comments (is_deleted);
CREATE INDEX lupo_comments_idx_ip_hash ON lupo_comments (ip_hash);
CREATE INDEX lupo_comments_idx_created_ymdhis ON lupo_comments (created_ymdhis);
CREATE INDEX lupo_comments_idx_updated_ymdhis ON lupo_comments (updated_ymdhis);

-- =============================================================================
-- lupo_document_embeddings (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_document_embeddings (
  document_embedding_id bigint NOT NULL,
  chunk_id bigint NOT NULL,
  embedding_json json NOT NULL,
  embedding_model varchar(128) NOT NULL,
  embedding_version varchar(64) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (document_embedding_id)
);
CREATE INDEX lupo_document_embeddings_embedding_model ON lupo_document_embeddings (embedding_model);

-- =============================================================================
-- lupo_emotional_constellations (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_emotional_constellations (
  constellation_id char(26) NOT NULL,
  framework_name varchar(255) NOT NULL,
  cultural_origin varchar(255) DEFAULT NULL,
  description text,
  stars json NOT NULL,
  is_canonical tinyint NOT NULL DEFAULT '0',
  canonical_for_culture varchar(255) DEFAULT NULL,
  created_ymdhis bigint DEFAULT NULL,
  deprecated_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (constellation_id)
);

-- =============================================================================
-- lupo_emotional_stars (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_emotional_stars (
  star_id char(26) NOT NULL,
  experience_hash char(64) DEFAULT NULL,
  experience_text text NOT NULL,
  cultural_context json DEFAULT NULL,
  embodied_sensation json DEFAULT NULL,
  created_by bigint DEFAULT NULL,
  created_in_context bigint DEFAULT NULL,
  first_observed_ymdhis bigint DEFAULT NULL,
  observation_count int NOT NULL DEFAULT '1',
  PRIMARY KEY (star_id)
);

-- =============================================================================
-- lupo_emotional_translations (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_emotional_translations (
  translation_id bigint NOT NULL,
  source_framework varchar(32) NOT NULL,
  source_state text NOT NULL,
  target_framework varchar(32) NOT NULL,
  target_state text NOT NULL,
  loss_score decimal(3,2) NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  last_used_ymdhis bigint NOT NULL,
  PRIMARY KEY (translation_id)
);

-- =============================================================================
-- lupo_entity_properties (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_entity_properties (
  entity_property_id bigint NOT NULL,
  entity_type varchar(64) NOT NULL,
  entity_id bigint NOT NULL,
  domain_id bigint NOT NULL DEFAULT '1',
  property_key varchar(100) NOT NULL,
  property_value text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (entity_property_id)
);
CREATE INDEX lupo_entity_properties_idx_entity ON lupo_entity_properties (entity_type, entity_id);
CREATE INDEX lupo_entity_properties_idx_domain ON lupo_entity_properties (domain_id);
CREATE INDEX lupo_entity_properties_idx_property_key ON lupo_entity_properties (property_key);
CREATE INDEX lupo_entity_properties_idx_created ON lupo_entity_properties (created_ymdhis);
CREATE INDEX lupo_entity_properties_idx_updated ON lupo_entity_properties (updated_ymdhis);
CREATE INDEX lupo_entity_properties_idx_is_deleted ON lupo_entity_properties (is_deleted);

-- =============================================================================
-- lupo_federated_trust (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_federated_trust (
    trust_id BIGINT NOT NULL,
    source_node_id BIGINT NOT NULL,
    target_node_id BIGINT NOT NULL,
    trust_level FLOAT DEFAULT 0.5,
    trust_type VARCHAR(50) NOT NULL,
    capabilities JSON,
    restrictions JSON,
    last_verified_ymdhis BIGINT DEFAULT 0,
    verification_method VARCHAR(100),
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT
);
CREATE INDEX lupo_federated_trust_idx_trust_type ON lupo_federated_trust(trust_type);
CREATE INDEX lupo_federated_trust_idx_last_verified ON lupo_federated_trust(last_verified_ymdhis);
CREATE INDEX lupo_federated_trust_idx_is_deleted ON lupo_federated_trust(is_deleted);

-- =============================================================================
-- lupo_federation_discovery (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_federation_discovery (
  federation_discovery_id bigint NOT NULL,
  domain varchar(255) NOT NULL,
  install_url varchar(500) DEFAULT NULL,
  is_lupopedia tinyint NOT NULL DEFAULT '0',
  last_seen_ymdhis bigint DEFAULT NULL,
  first_seen_ymdhis bigint DEFAULT NULL,
  hashtag_count bigint DEFAULT '0',
  question_count bigint DEFAULT '0',
  atom_count bigint DEFAULT '0',
  context_count bigint DEFAULT '0',
  collection_count bigint DEFAULT '0',
  keywords varchar(500) DEFAULT NULL,
  description text,
  import_hashtags tinyint NOT NULL DEFAULT '0',
  import_questions tinyint NOT NULL DEFAULT '0',
  import_atoms tinyint NOT NULL DEFAULT '0',
  import_contexts tinyint NOT NULL DEFAULT '0',
  import_collections tinyint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (federation_discovery_id)
);

-- =============================================================================
-- lupo_flare_headers (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_flare_headers (
  content_id bigint NOT NULL,
  flare_version varchar(20) DEFAULT NULL,
  flare_schema varchar(50) DEFAULT NULL,
  file_path_from_root text,
  web_path text,
  last_modified_utc varchar(14) DEFAULT NULL,
  system_version varchar(20) DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  actor_id bigint DEFAULT NULL,
  delegation_chain varchar(255) DEFAULT NULL,
  artifact_type varchar(50) DEFAULT NULL,
  artifact_kind varchar(50) DEFAULT NULL,
  purpose text,
  mood_rgb varchar(6) DEFAULT NULL,
  traits json DEFAULT NULL,
  tags json DEFAULT NULL,
  lupo_agent varchar(50) DEFAULT NULL,
  agent_name_identity varchar(255) DEFAULT NULL,
  PRIMARY KEY (content_id)
);
CREATE INDEX lupo_flare_headers_idx_actor_id ON lupo_flare_headers (actor_id);

-- =============================================================================
-- lupo_gov_event_actor_edges (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_gov_event_actor_edges (
  edge_id bigint NOT NULL,
  gov_event_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  edge_type varchar(100) NOT NULL,
  edge_properties text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (edge_id)
);
CREATE INDEX lupo_gov_event_actor_edges_idx_gov_event ON lupo_gov_event_actor_edges (gov_event_id);
CREATE INDEX lupo_gov_event_actor_edges_idx_actor ON lupo_gov_event_actor_edges (actor_id);
CREATE INDEX lupo_gov_event_actor_edges_idx_edge_type ON lupo_gov_event_actor_edges (edge_type);
CREATE INDEX lupo_gov_event_actor_edges_idx_created_ymdhis ON lupo_gov_event_actor_edges (created_ymdhis);
CREATE INDEX lupo_gov_event_actor_edges_idx_is_deleted ON lupo_gov_event_actor_edges (is_deleted);

-- =============================================================================
-- lupo_gov_event_conflicts (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_gov_event_conflicts (
  gov_event_conflict_id bigint NOT NULL,
  gov_event_id bigint NOT NULL,
  conflicts_with_event_id bigint NOT NULL,
  conflict_type varchar(50) NOT NULL,
  severity varchar(20) NOT NULL,
  notes text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (gov_event_conflict_id)
);
CREATE INDEX lupo_gov_event_conflicts_idx_conflicts_with_event_id ON lupo_gov_event_conflicts (conflicts_with_event_id);

-- =============================================================================
-- lupo_gov_event_dependencies (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_gov_event_dependencies (
  gov_event_dependency_id bigint NOT NULL,
  gov_event_id bigint NOT NULL,
  depends_on_event_id bigint NOT NULL,
  dependency_type varchar(50) NOT NULL,
  notes text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (gov_event_dependency_id)
);
CREATE INDEX lupo_gov_event_dependencies_idx_depends_on_event_id ON lupo_gov_event_dependencies (depends_on_event_id);

-- =============================================================================
-- lupo_gov_event_references (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_gov_event_references (
  reference_id bigint NOT NULL,
  gov_event_id bigint NOT NULL,
  reference_type varchar(100) NOT NULL,
  reference_title varchar(255) NOT NULL,
  reference_url varchar(1000) DEFAULT NULL,
  reference_content text,
  order_sequence int NOT NULL DEFAULT '0',
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (reference_id)
);
CREATE INDEX lupo_gov_event_references_idx_reference_type ON lupo_gov_event_references (reference_type);
CREATE INDEX lupo_gov_event_references_idx_order_sequence ON lupo_gov_event_references (order_sequence);
CREATE INDEX lupo_gov_event_references_idx_created_ymdhis ON lupo_gov_event_references (created_ymdhis);
CREATE INDEX lupo_gov_event_references_idx_is_deleted ON lupo_gov_event_references (is_deleted);

-- =============================================================================
-- lupo_gov_events (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_gov_events (
  gov_event_id bigint NOT NULL,
  utc_group_id bigint NOT NULL,
  semantic_utc_version varchar(50) NOT NULL,
  canonical_path varchar(500) NOT NULL,
  event_type varchar(100) NOT NULL,
  title varchar(255) NOT NULL,
  directive_block text,
  tldr_summary text,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (gov_event_id)
);
CREATE INDEX lupo_gov_events_idx_utc_group ON lupo_gov_events (utc_group_id);
CREATE INDEX lupo_gov_events_idx_semantic_version ON lupo_gov_events (semantic_utc_version);
CREATE INDEX lupo_gov_events_idx_event_type ON lupo_gov_events (event_type);
CREATE INDEX lupo_gov_events_idx_created_ymdhis ON lupo_gov_events (created_ymdhis);
CREATE INDEX lupo_gov_events_idx_is_active ON lupo_gov_events (is_active);
CREATE INDEX lupo_gov_events_idx_is_deleted ON lupo_gov_events (is_deleted);

-- =============================================================================
-- lupo_gov_timeline_nodes (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_gov_timeline_nodes (
  timeline_node_id bigint NOT NULL,
  gov_event_id bigint NOT NULL,
  node_type varchar(100) NOT NULL,
  node_title varchar(255) NOT NULL,
  node_description text,
  node_timestamp bigint NOT NULL,
  parent_node_id bigint DEFAULT NULL,
  order_sequence int NOT NULL DEFAULT '0',
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (timeline_node_id)
);
CREATE INDEX lupo_gov_timeline_nodes_idx_node_type ON lupo_gov_timeline_nodes (node_type);
CREATE INDEX lupo_gov_timeline_nodes_idx_node_timestamp ON lupo_gov_timeline_nodes (node_timestamp);
CREATE INDEX lupo_gov_timeline_nodes_idx_parent_node ON lupo_gov_timeline_nodes (parent_node_id);
CREATE INDEX lupo_gov_timeline_nodes_idx_order_sequence ON lupo_gov_timeline_nodes (order_sequence);
CREATE INDEX lupo_gov_timeline_nodes_idx_created_ymdhis ON lupo_gov_timeline_nodes (created_ymdhis);
CREATE INDEX lupo_gov_timeline_nodes_idx_is_deleted ON lupo_gov_timeline_nodes (is_deleted);

-- =============================================================================
-- lupo_gov_valuations (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_gov_valuations (
  valuation_id bigint NOT NULL,
  gov_event_id bigint NOT NULL,
  valuation_type varchar(100) NOT NULL,
  valuation_metric varchar(255) NOT NULL,
  valuation_value decimal(20,8) DEFAULT NULL,
  valuation_text text,
  valuation_currency varchar(10) DEFAULT NULL,
  valuation_unit varchar(50) DEFAULT NULL,
  confidence_score decimal(5,4) DEFAULT NULL,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (valuation_id)
);
CREATE INDEX lupo_gov_valuations_idx_valuation_type ON lupo_gov_valuations (valuation_type);
CREATE INDEX lupo_gov_valuations_idx_valuation_metric ON lupo_gov_valuations (valuation_metric);
CREATE INDEX lupo_gov_valuations_idx_created_ymdhis ON lupo_gov_valuations (created_ymdhis);
CREATE INDEX lupo_gov_valuations_idx_is_deleted ON lupo_gov_valuations (is_deleted);

-- =============================================================================
-- lupo_hashtags (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_hashtags (
  hashtag_id bigint NOT NULL,
  hashtag_slug varchar(255) NOT NULL,
  description text,
  meta_json json DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT '0',
  updated_ymdhis bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (hashtag_id)
);
CREATE INDEX lupo_hashtags_idx_is_deleted ON lupo_hashtags (is_deleted);

-- =============================================================================
-- lupo_hotfix_registry (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_hotfix_registry (
  hotfix_id int NOT NULL,
  hotfix_version varchar(20) NOT NULL,
  applied_ymdhis bigint NOT NULL,
  applied_by_actor_id int DEFAULT NULL,
  description text,
  metadata_json json DEFAULT NULL,
  PRIMARY KEY (hotfix_id)
);

-- =============================================================================
-- lupo_human_history_meta (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_human_history_meta (
  meta_id bigint NOT NULL,
  event_key varchar(255) NOT NULL,
  tensor_mapping varchar(32) NOT NULL,
  philosophical_reference varchar(255) NOT NULL,
  system_impact text NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (meta_id)
);

-- =============================================================================
-- lupo_interface_translations (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_interface_translations (
  interface_translation_id bigint NOT NULL,
  language_code varchar(8) NOT NULL,
  translation_key varchar(128) NOT NULL,
  translation_text text NOT NULL,
  context varchar(64) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  created_by bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  version int DEFAULT '1',
  is_approved tinyint DEFAULT '0',
  approved_by bigint DEFAULT NULL,
  PRIMARY KEY (interface_translation_id)
);
CREATE INDEX lupo_interface_translations_idx_created ON lupo_interface_translations (created_ymdhis);
CREATE INDEX lupo_interface_translations_idx_updated ON lupo_interface_translations (updated_ymdhis);
CREATE INDEX lupo_interface_translations_idx_deleted ON lupo_interface_translations (is_deleted);
CREATE INDEX lupo_interface_translations_idx_approved ON lupo_interface_translations (is_approved);

-- =============================================================================
-- lupo_kapu_events (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_kapu_events (
  kapu_id bigint NOT NULL,
  agent_id varchar(255) DEFAULT NULL,
  imposed_by_actor_id varchar(255) DEFAULT NULL,
  kapu_type varchar(64) DEFAULT NULL,
  restrictions json DEFAULT NULL,
  restoration_plan json DEFAULT NULL,
  kapakai_level decimal(3,2) DEFAULT NULL,
  review_schedule json DEFAULT NULL,
  accepted_at bigint DEFAULT NULL,
  appealed_at bigint DEFAULT NULL,
  active tinyint DEFAULT '1',
  created_at bigint DEFAULT NULL,
  PRIMARY KEY (kapu_id)
);

-- =============================================================================
-- lupo_kapu_restoration_paths (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_kapu_restoration_paths (
  path_id bigint NOT NULL,
  agent_id varchar(255) DEFAULT NULL,
  kapu_reason_code varchar(100) DEFAULT NULL,
  learning_modules json DEFAULT NULL,
  emotional_targets json DEFAULT NULL,
  restoration_rituals json DEFAULT NULL,
  kapu_companion_agent_id varchar(255) DEFAULT NULL,
  completed_at bigint DEFAULT NULL,
  PRIMARY KEY (path_id)
);

-- =============================================================================
-- lupo_legacy_content_mapping (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_legacy_content_mapping (
  mapping_id bigint NOT NULL,
  legacy_url varchar(255) NOT NULL,
  semantic_url varchar(255) NOT NULL,
  content_type varchar(64) NOT NULL,
  content_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (mapping_id)
);
CREATE INDEX lupo_legacy_content_mapping_idx_semantic_url ON lupo_legacy_content_mapping (semantic_url);
CREATE INDEX lupo_legacy_content_mapping_idx_content_type ON lupo_legacy_content_mapping (content_type);
CREATE INDEX lupo_legacy_content_mapping_idx_content_id ON lupo_legacy_content_mapping (content_id);
CREATE INDEX lupo_legacy_content_mapping_idx_is_active ON lupo_legacy_content_mapping (is_active);
CREATE INDEX lupo_legacy_content_mapping_idx_created ON lupo_legacy_content_mapping (created_ymdhis);
CREATE INDEX lupo_legacy_content_mapping_idx_created_ymdhis ON lupo_legacy_content_mapping (created_ymdhis, is_active);

-- =============================================================================
-- lupo_llm_performance (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_llm_performance (
    performance_id BIGINT NOT NULL,
    actor_id BIGINT NOT NULL,
    llm_module VARCHAR(100) NOT NULL,
    provider VARCHAR(50),
    total_tokens BIGINT DEFAULT 0,
    avg_response_time_ms INT DEFAULT 0,
    success_rate FLOAT DEFAULT 1,
    cost_per_1k_tokens DECIMAL(10,4) DEFAULT 0.0000,
    quality_score FLOAT DEFAULT 1,
    last_used_ymdhis BIGINT DEFAULT 0,
    performance_data JSON,
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT
);
CREATE INDEX lupo_llm_performance_idx_provider ON lupo_llm_performance(provider);
CREATE INDEX lupo_llm_performance_idx_last_used ON lupo_llm_performance(last_used_ymdhis);
CREATE INDEX lupo_llm_performance_idx_is_deleted ON lupo_llm_performance(is_deleted);

-- =============================================================================
-- lupo_metrics_archive_legacy (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_metrics_archive_legacy (
  metric_id int NOT NULL,
  metric_key varchar(255) NOT NULL,
  metric_value varchar(255) DEFAULT NULL,
  recorded_at bigint,
  PRIMARY KEY (metric_id)
);

-- =============================================================================
-- lupo_modules_departments (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_modules_departments (
  module_department_id bigint NOT NULL,
  module_id bigint NOT NULL,
  department_id bigint NOT NULL,
  is_enabled tinyint NOT NULL DEFAULT '1',
  sort_order int DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (module_department_id)
);

-- =============================================================================
-- lupo_mood_assignments (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_mood_assignments (
  mood_assignment_id bigint NOT NULL,
  table_name varchar(128) NOT NULL,
  row_id bigint NOT NULL,
  mood_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (mood_assignment_id)
);
CREATE INDEX lupo_mood_assignments_idx_assignment_mood ON lupo_mood_assignments (mood_id);

-- =============================================================================
-- lupo_mood_registry (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_mood_registry (
  mood_id bigint NOT NULL,
  mood_type varchar(64) NOT NULL,
  mood_variant varchar(64) DEFAULT NULL,
  mood_rgb char(6) NOT NULL,
  description text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (mood_id)
);
CREATE INDEX lupo_mood_registry_idx_mood_rgb ON lupo_mood_registry (mood_rgb);

-- =============================================================================
-- lupo_pack_role_registry (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_pack_role_registry (
  pack_role_registry_id bigint NOT NULL,
  agent_id bigint NOT NULL,
  role_key varchar(255) NOT NULL,
  discovery_method text NOT NULL,
  behavior text NOT NULL,
  reason text NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (pack_role_registry_id)
);
CREATE INDEX lupo_pack_role_registry_idx_agent_id ON lupo_pack_role_registry (agent_id);
CREATE INDEX lupo_pack_role_registry_idx_role_key ON lupo_pack_role_registry (role_key);

-- =============================================================================
-- lupo_persona_dialogue_patterns (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_persona_dialogue_patterns (
  pattern_id bigint NOT NULL,
  persona_id bigint NOT NULL,
  pattern_type varchar(100) NOT NULL,
  pattern_name varchar(255) NOT NULL,
  pattern_triggers json DEFAULT NULL,
  pattern_responses json DEFAULT NULL,
  pattern_context json DEFAULT NULL,
  pattern_frequency decimal(5,2) DEFAULT NULL,
  pattern_confidence decimal(5,2) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (pattern_id)
);
CREATE INDEX lupo_persona_dialogue_patterns_idx_pattern_type ON lupo_persona_dialogue_patterns (pattern_type);
CREATE INDEX lupo_persona_dialogue_patterns_idx_pattern_name ON lupo_persona_dialogue_patterns (pattern_name);

-- =============================================================================
-- lupo_persona_profiles (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_persona_profiles (
  persona_id bigint NOT NULL,
  persona_name varchar(255) NOT NULL,
  persona_type varchar(100) NOT NULL,
  persona_description text,
  persona_traits json DEFAULT NULL,
  persona_preferences json DEFAULT NULL,
  persona_capabilities json DEFAULT NULL,
  persona_voice_style varchar(100) DEFAULT NULL,
  persona_interaction_style varchar(100) DEFAULT NULL,
  persona_emotional_profile json DEFAULT NULL,
  persona_knowledge_domains json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (persona_id)
);
CREATE INDEX lupo_persona_profiles_idx_persona_name ON lupo_persona_profiles (persona_name);
CREATE INDEX lupo_persona_profiles_idx_is_active ON lupo_persona_profiles (is_active);

-- =============================================================================
-- lupo_reference_cited_by (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_reference_cited_by (
  reference_cited_by_id bigint NOT NULL,
  reference_object_id bigint NOT NULL,
  content_id bigint NOT NULL,
  section_anchor_slug varchar(255) DEFAULT NULL,
  section_order int NOT NULL DEFAULT '0',
  reference_type varchar(50) NOT NULL,
  raw_reference text,
  meta_json json DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT '0',
  updated_ymdhis bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (reference_cited_by_id)
);
CREATE INDEX lupo_reference_cited_by_idx_content_id ON lupo_reference_cited_by (content_id);
CREATE INDEX lupo_reference_cited_by_idx_section_anchor ON lupo_reference_cited_by (section_anchor_slug);
CREATE INDEX lupo_reference_cited_by_idx_reference_type ON lupo_reference_cited_by (reference_type);
CREATE INDEX lupo_reference_cited_by_idx_is_deleted ON lupo_reference_cited_by (is_deleted);

-- =============================================================================
-- lupo_reference_objects (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_reference_objects (
  reference_object_id bigint NOT NULL,
  object_type varchar(50) NOT NULL,
  object_slug varchar(255) NOT NULL,
  object_label varchar(255) DEFAULT NULL,
  meta_json json DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT '0',
  updated_ymdhis bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (reference_object_id)
);
CREATE INDEX lupo_reference_objects_idx_object_slug ON lupo_reference_objects (object_slug);
CREATE INDEX lupo_reference_objects_idx_type_slug ON lupo_reference_objects (object_type, object_slug);
CREATE INDEX lupo_reference_objects_idx_is_deleted ON lupo_reference_objects (is_deleted);

-- =============================================================================
-- lupo_registry_import (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_registry_import (
  import_registry_id bigint NOT NULL AUTO_INCREMENT,
  entity_type varchar(50) NOT NULL,
  entity_index_id bigint NOT NULL,
  source_federation_node_id bigint NOT NULL,
  imported_at bigint NOT NULL,
  resolved_to_local_id bigint NOT NULL DEFAULT 0,
  notes text,
  PRIMARY KEY (import_registry_id)
);
CREATE INDEX idx_import_entity_index_id ON lupo_registry_import (entity_index_id);
CREATE INDEX idx_import_source_node ON lupo_registry_import (source_federation_node_id);
CREATE INDEX idx_import_resolved_local_id ON lupo_registry_import (resolved_to_local_id);

-- =============================================================================
-- lupo_search_index (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_search_index (
  search_index_id bigint NOT NULL,
  domain_id bigint NOT NULL,
  entity_type varchar(50) NOT NULL,
  entity_id bigint NOT NULL,
  title_text text,
  body_text text,
  keywords_text text,
  search_metadata text,
  relevance_score float DEFAULT '1',
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (search_index_id)
);
CREATE INDEX lupo_search_index_idx_domain_type ON lupo_search_index (domain_id, entity_type);
CREATE INDEX lupo_search_index_idx_entity_reference ON lupo_search_index (entity_type, entity_id);
CREATE INDEX lupo_search_index_idx_updated ON lupo_search_index (updated_ymdhis);
CREATE INDEX lupo_search_index_idx_is_deleted ON lupo_search_index (is_deleted);
CREATE INDEX lupo_search_index_idx_relevance ON lupo_search_index (relevance_score);

-- =============================================================================
-- lupo_session_recovery (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_session_recovery (
    recovery_id BIGINT NOT NULL,
    actor_id BIGINT NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    session_data JSON,
    state_snapshot JSON,
    context_data JSON,
    last_activity_ymdhis BIGINT DEFAULT 0,
    recovery_attempts INT DEFAULT 0,
    max_recovery_attempts INT DEFAULT 3,
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT
);
CREATE INDEX lupo_session_recovery_idx_session_id ON lupo_session_recovery(session_id);
CREATE INDEX lupo_session_recovery_idx_last_activity ON lupo_session_recovery(last_activity_ymdhis);
CREATE INDEX lupo_session_recovery_idx_is_deleted ON lupo_session_recovery(is_deleted);

-- =============================================================================
-- lupo_task_assignments (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_task_assignments (
  assignment_id bigint NOT NULL,
  task_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  assignment_type varchar(32) NOT NULL DEFAULT 'assigned',
  assigned_by_actor_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (assignment_id)
);
CREATE INDEX lupo_task_assignments_idx_actor_id ON lupo_task_assignments (actor_id);
CREATE INDEX lupo_task_assignments_idx_assignment_type ON lupo_task_assignments (assignment_type);
CREATE INDEX lupo_task_assignments_idx_is_deleted ON lupo_task_assignments (is_deleted);

-- =============================================================================
-- lupo_task_dependencies (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_task_dependencies (
  dependency_id bigint NOT NULL,
  task_id bigint NOT NULL,
  depends_on_task_id bigint NOT NULL,
  dependency_type varchar(32) NOT NULL DEFAULT 'blocks',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (dependency_id)
);
CREATE INDEX lupo_task_dependencies_idx_depends_on_task_id ON lupo_task_dependencies (depends_on_task_id);
CREATE INDEX lupo_task_dependencies_idx_dependency_type ON lupo_task_dependencies (dependency_type);
CREATE INDEX lupo_task_dependencies_idx_is_deleted ON lupo_task_dependencies (is_deleted);

-- =============================================================================
-- lupo_unified_log (moved from install_new_lupopedia.sql on 2026-03-09 by rule: not in minimal_tables and not used by PHP/PY)
-- =============================================================================
CREATE TABLE lupo_unified_log (
  log_id bigint NOT NULL AUTO_INCREMENT,
  log_type enum('anubis_deletion','anubis_general','anubis_processing','audit','auth_audit','bans','channel_boot','event','interpretation','search_rebuild') NOT NULL,
  log_level enum('debug','info','warning','error','critical') DEFAULT 'info',
  log_message text NOT NULL,
  log_context json,
  actor_id int,
  channel_id int,
  session_id varchar(128),
  ip_address varchar(45),
  user_agent text,
  created_ymdhis bigint NOT NULL,
  PRIMARY KEY (log_id)
);
CREATE INDEX idx_actor_log ON lupo_unified_log (actor_id, log_type);
CREATE INDEX idx_channel_log ON lupo_unified_log (channel_id, log_type);
CREATE INDEX idx_log_type_created ON lupo_unified_log (log_type, created_ymdhis);
CREATE INDEX idx_session_log ON lupo_unified_log (session_id, log_type);
CREATE INDEX lupo_unified_log_idx_actor_id ON lupo_unified_log (actor_id);
CREATE INDEX lupo_unified_log_idx_channel_id ON lupo_unified_log (channel_id);
CREATE INDEX lupo_unified_log_idx_created_ymdhis ON lupo_unified_log (created_ymdhis);
CREATE INDEX lupo_unified_log_idx_log_level ON lupo_unified_log (log_level);
CREATE INDEX lupo_unified_log_idx_log_type ON lupo_unified_log (log_type);
CREATE INDEX lupo_unified_log_idx_session_id ON lupo_unified_log (session_id);


-- Additional moved index statements (2026-03-09 cleanup)
CREATE INDEX lupo_gov_event_conflicts_idx_gov_event_id ON lupo_gov_event_conflicts (gov_event_id);
CREATE INDEX lupo_gov_event_dependencies_idx_gov_event_id ON lupo_gov_event_dependencies (gov_event_id);
CREATE INDEX lupo_gov_event_references_idx_gov_event ON lupo_gov_event_references (gov_event_id);
CREATE INDEX lupo_gov_timeline_nodes_idx_gov_event ON lupo_gov_timeline_nodes (gov_event_id);
CREATE INDEX lupo_gov_valuations_idx_gov_event ON lupo_gov_valuations (gov_event_id);
CREATE INDEX lupo_hashtags_idx_hashtag_slug ON lupo_hashtags (hashtag_slug);
CREATE UNIQUE INDEX lupo_gov_event_actor_edges_unique_gov_event_actor_edge ON lupo_gov_event_actor_edges (gov_event_id, actor_id, edge_type);

-- Additional moved index statements (2026-03-09 cleanup pass 4)
CREATE INDEX idx_import_entity_type ON lupo_registry_import (entity_type);
CREATE INDEX lupo_actor_persona_relationships_idx_actor_id ON lupo_actor_persona_relationships (actor_id);
CREATE INDEX lupo_actor_relationship_rules_idx_source_target ON lupo_actor_relationship_rules(source_actor_id, target_actor_id);
CREATE INDEX lupo_anubis_deletion_log_idx_table_record ON lupo_anubis_deletion_log (table_name, record_id);
CREATE INDEX lupo_channel_boot_log_idx_actor_session ON lupo_channel_boot_log (actor_id, session_id);
CREATE INDEX lupo_comments_idx_domain_id ON lupo_comments (domain_id);
CREATE INDEX lupo_document_embeddings_chunk_id ON lupo_document_embeddings (chunk_id);
CREATE INDEX lupo_federated_trust_idx_source_target ON lupo_federated_trust(source_node_id, target_node_id);
CREATE INDEX lupo_federation_discovery_idx_domain ON lupo_federation_discovery (domain);
CREATE INDEX lupo_flare_headers_idx_channel_id ON lupo_flare_headers (channel_id);
CREATE INDEX lupo_llm_performance_idx_actor_module ON lupo_llm_performance(actor_id, llm_module);
CREATE INDEX lupo_mood_assignments_idx_assignment_target ON lupo_mood_assignments (table_name, row_id);
CREATE INDEX lupo_mood_registry_idx_mood_type ON lupo_mood_registry (mood_type);
CREATE INDEX lupo_persona_dialogue_patterns_idx_persona_id ON lupo_persona_dialogue_patterns (persona_id);
CREATE INDEX lupo_persona_profiles_idx_persona_type ON lupo_persona_profiles (persona_type);
CREATE INDEX lupo_reference_cited_by_idx_reference_object ON lupo_reference_cited_by (reference_object_id);
CREATE INDEX lupo_reference_objects_idx_object_type ON lupo_reference_objects (object_type);
CREATE INDEX lupo_session_recovery_idx_actor_id ON lupo_session_recovery(actor_id);
CREATE INDEX lupo_task_assignments_idx_task_id ON lupo_task_assignments (task_id);
CREATE INDEX lupo_task_dependencies_idx_task_id ON lupo_task_dependencies (task_id);
CREATE UNIQUE INDEX lupo_actor_object_edges_uniq_actor_target_type ON lupo_actor_object_edges (actor_id, target_table, target_id, edge_type);
CREATE UNIQUE INDEX lupo_actor_truth_edges_uniq_actor_truth_type ON lupo_actor_truth_edges (actor_id, truth_item_id, edge_type);
CREATE UNIQUE INDEX lupo_analytics_referers_periods_uq_referer_period ON lupo_analytics_referers_periods (content_id, referer_content_id, period_type, period_date);
CREATE UNIQUE INDEX lupo_entity_properties_unique_entity_domain_property ON lupo_entity_properties (entity_type, entity_id, domain_id, property_key);
CREATE UNIQUE INDEX lupo_gov_events_unique_canonical_path ON lupo_gov_events (canonical_path);
CREATE UNIQUE INDEX lupo_interface_translations_unq_language_key ON lupo_interface_translations (language_code, translation_key);
CREATE UNIQUE INDEX lupo_legacy_content_mapping_uk_legacy_url ON lupo_legacy_content_mapping (legacy_url);
CREATE UNIQUE INDEX lupo_modules_departments_uniq_mod_dept ON lupo_modules_departments (module_id, department_id);
CREATE UNIQUE INDEX lupo_pack_role_registry_unique_agent_role ON lupo_pack_role_registry (agent_id);
CREATE UNIQUE INDEX lupo_search_index_unique_entity ON lupo_search_index (domain_id, entity_type, entity_id);

-- =============================================================================
-- lupo_documentation_frameworks (Synthesized Documentation Framework, 4.0.71)
-- COLLECTION: active, CHANNEL: ide, NAMESPACE: lupopedia.tables.documentation_frameworks.
-- Stores framework metadata; edges to agents/channels/collections via lupo_edges (application-level).
-- No FKs per doctrine. Run only if documentation framework feature is enabled.
-- =============================================================================
CREATE TABLE lupo_documentation_frameworks (
  documentation_framework_id bigint NOT NULL,
  framework_key varchar(64) NOT NULL,
  framework_name varchar(255) NOT NULL,
  class_type varchar(64) NOT NULL DEFAULT 'documentation',
  namespace_key varchar(255) NOT NULL,
  channel_id bigint NOT NULL DEFAULT 1,
  collection_key varchar(64) NOT NULL DEFAULT 'active',
  orchestrator_actor_id bigint DEFAULT NULL,
  facet_slug varchar(64) DEFAULT NULL,
  agent_key varchar(64) DEFAULT NULL,
  role_key varchar(64) DEFAULT NULL,
  task_scope varchar(255) DEFAULT NULL,
  database_table varchar(255) DEFAULT NULL,
  runtime_min_php varchar(20) DEFAULT '5.6',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  properties_json json DEFAULT NULL,
  PRIMARY KEY (documentation_framework_id)
);
CREATE UNIQUE INDEX lupo_documentation_frameworks_uniq_key ON lupo_documentation_frameworks (framework_key);
CREATE INDEX lupo_documentation_frameworks_idx_namespace ON lupo_documentation_frameworks (namespace_key);
CREATE INDEX lupo_documentation_frameworks_idx_channel ON lupo_documentation_frameworks (channel_id);
CREATE INDEX lupo_documentation_frameworks_idx_collection ON lupo_documentation_frameworks (collection_key);
CREATE INDEX lupo_documentation_frameworks_idx_class ON lupo_documentation_frameworks (class_type);
CREATE INDEX lupo_documentation_frameworks_idx_is_deleted ON lupo_documentation_frameworks (is_deleted);
CREATE INDEX lupo_documentation_frameworks_idx_created ON lupo_documentation_frameworks (created_ymdhis);

-- =============================================================================
-- lupo_orchestrator_rules (optional; v4.0.73 — orchestrator rule storage)
-- =============================================================================
CREATE TABLE lupo_orchestrator_rules (
  rule_id bigint NOT NULL AUTO_INCREMENT,
  rule_slug varchar(128) NOT NULL,
  orchestrator_actor varchar(64) NOT NULL,
  rule_set_version varchar(32) NOT NULL,
  applies_to_json text NOT NULL,
  enforcement_level varchar(32) NOT NULL DEFAULT 'strict',
  rule_content text NOT NULL,
  checksum varchar(64) NOT NULL,
  is_active tinyint NOT NULL DEFAULT 1,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (rule_id)
);
CREATE UNIQUE INDEX lupo_orchestrator_rules_uniq_slug ON lupo_orchestrator_rules (rule_slug);
CREATE INDEX lupo_orchestrator_rules_idx_actor_version ON lupo_orchestrator_rules (orchestrator_actor, rule_set_version);
CREATE INDEX lupo_orchestrator_rules_idx_active ON lupo_orchestrator_rules (is_active);
CREATE INDEX lupo_orchestrator_rules_idx_updated ON lupo_orchestrator_rules (updated_ymdhis);
