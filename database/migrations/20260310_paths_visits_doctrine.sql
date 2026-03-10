-- One-time migration: PATHS and VISITS doctrine (4.0.68).
-- Paths = aggregated flows (year/month/day, count). Visits = raw per-event logs (session/actor/instance, is_processed).
-- gc.php aggregates unprocessed visits into paths. No FKs; BIGINT timestamps (YmdHis).
-- WARNING: Drops existing analytics/visits tables. Crafty import script may need updating to map into new schema.

-- Drop old analytics and visits tables (order for FK safety; none here)
DROP TABLE IF EXISTS lupo_analytics_visits_daily;
DROP TABLE IF EXISTS lupo_analytics_visits_monthly;
DROP TABLE IF EXISTS lupo_analytics_visits;
DROP TABLE IF EXISTS lupo_analytics_paths;
DROP TABLE IF EXISTS lupo_visits;

-- lupo_paths: aggregated navigation flows (low-volume). Populated by gc.php from visits.
CREATE TABLE lupo_paths (
  path_id bigint NOT NULL AUTO_INCREMENT,
  entercontentid bigint DEFAULT NULL,
  exitcontentid bigint DEFAULT NULL,
  enter_table varchar(255) DEFAULT NULL,
  exit_table varchar(255) DEFAULT NULL,
  year_num int DEFAULT NULL,
  month_num int DEFAULT NULL,
  day_num int DEFAULT NULL,
  count_num int NOT NULL DEFAULT 0,
  transition_type varchar(64) DEFAULT NULL,
  transition_metadata text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (path_id)
);
CREATE INDEX lupo_paths_idx_enter_exit ON lupo_paths (entercontentid, exitcontentid);
CREATE INDEX lupo_paths_idx_ymd ON lupo_paths (year_num, month_num, day_num);
CREATE INDEX lupo_paths_idx_transition ON lupo_paths (transition_type);
CREATE INDEX lupo_paths_idx_created ON lupo_paths (created_ymdhis);
CREATE INDEX lupo_paths_idx_is_deleted ON lupo_paths (is_deleted);

-- lupo_visits: raw per-event navigation logs (high-volume, append-only). gc.php marks is_processed when aggregated.
CREATE TABLE lupo_visits (
  visit_id bigint NOT NULL AUTO_INCREMENT,
  session_id bigint DEFAULT NULL,
  actor_id bigint DEFAULT NULL,
  instance_id bigint DEFAULT NULL,
  path_url text,
  entercontentid bigint DEFAULT NULL,
  exitcontentid bigint DEFAULT NULL,
  enter_table varchar(255) DEFAULT NULL,
  exit_table varchar(255) DEFAULT NULL,
  transition_type varchar(64) DEFAULT NULL,
  transition_metadata text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_processed tinyint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (visit_id)
);
CREATE INDEX lupo_visits_idx_session ON lupo_visits (session_id);
CREATE INDEX lupo_visits_idx_actor ON lupo_visits (actor_id);
CREATE INDEX lupo_visits_idx_created ON lupo_visits (created_ymdhis);
CREATE INDEX lupo_visits_idx_is_processed ON lupo_visits (is_processed);
CREATE INDEX lupo_visits_idx_is_deleted ON lupo_visits (is_deleted);
CREATE INDEX lupo_visits_idx_enter_exit ON lupo_visits (entercontentid, exitcontentid);
