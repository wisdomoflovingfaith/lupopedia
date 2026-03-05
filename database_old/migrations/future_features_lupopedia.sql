-- Future features / non-required tables for Lupopedia.
-- Do NOT run this file during install or upgrade from Crafty Syntax 3.7.5.
-- Tables here are NOT referenced in import_from_old_crafty_syntax.sql and are not
-- required by active PHP, wizard, seed, or runtime. They are reserved for
-- future development. Canonical install: install_new_lupopedia.sql only.
-- Doctrine: no FKs, no triggers, BIGINT timestamps, no UNSIGNED, no display widths.
-- See docs/REQUIRED_TABLES_4.0.21.md for required vs future table lists.

-- =============================================================================
-- lupo_integration_test_results (future: integration testing)
-- =============================================================================
CREATE TABLE lupo_integration_test_results (
  test_result_id bigint NOT NULL,
  test_suite varchar(64) NOT NULL,
  test_case varchar(128) NOT NULL,
  expected_result varchar(255) DEFAULT NULL,
  actual_result varchar(255) DEFAULT NULL,
  status varchar(64) NOT NULL,
  error_message text,
  execution_time_ms int DEFAULT NULL,
  created_ymdhis bigint NOT NULL,
  PRIMARY KEY (test_result_id)
);

-- =============================================================================
-- lupo_memory_debug_log (future: memory debug tooling)
-- =============================================================================
CREATE TABLE lupo_memory_debug_log (
  memory_debug_log_id bigint NOT NULL,
  event_type varchar(64) NOT NULL,
  details text NOT NULL,
  created_ymdhis bigint NOT NULL,
  PRIMARY KEY (memory_debug_log_id)
);

CREATE INDEX lupo_memory_debug_log_idx_type_created ON lupo_memory_debug_log (event_type, created_ymdhis);

-- =============================================================================
-- lupo_narrative_fragments (future: narrative/agent fragments)
-- =============================================================================
CREATE TABLE lupo_narrative_fragments (
  narrative_fragment_id bigint NOT NULL,
  agent_id bigint DEFAULT NULL,
  fragment_type varchar(100) DEFAULT NULL,
  title varchar(255) DEFAULT NULL,
  fragment_text text NOT NULL,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (narrative_fragment_id)
);

CREATE INDEX lupo_narrative_fragments_idx_agent ON lupo_narrative_fragments (agent_id);
CREATE INDEX lupo_narrative_fragments_idx_type ON lupo_narrative_fragments (fragment_type);
CREATE INDEX lupo_narrative_fragments_idx_created ON lupo_narrative_fragments (created_ymdhis);

-- =============================================================================
-- lupo_test_performance_metrics (future: performance testing)
-- =============================================================================
CREATE TABLE lupo_test_performance_metrics (
  test_id bigint NOT NULL,
  test_category varchar(64) NOT NULL,
  test_name varchar(128) NOT NULL,
  execution_time_ms int NOT NULL,
  memory_usage_mb decimal(10,2) DEFAULT NULL,
  cpu_usage_percent decimal(5,2) DEFAULT NULL,
  success_rate decimal(5,2) DEFAULT NULL,
  error_count int DEFAULT '0',
  created_ymdhis bigint NOT NULL,
  PRIMARY KEY (test_id)
);
