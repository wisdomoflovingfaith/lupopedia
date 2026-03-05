-- ============================================================================
-- ADD TASKS SCHEMA FOR LUPOPEDIA 4.0.45
-- ============================================================================
-- Purpose: Add task management tables to support offline task system
-- Run after: install_new_lupopedia.sql
-- ============================================================================

-- ============================================================================
-- PART 1: TASK TYPES REGISTRY
-- ============================================================================

CREATE TABLE lupo_task_types (
  type_id BIGINT NOT NULL PRIMARY KEY,
  type_key VARCHAR(64) NOT NULL UNIQUE,
  type_name VARCHAR(255) NOT NULL,
  description TEXT,
  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,
  is_deleted TINYINT NOT NULL DEFAULT 0
);

CREATE INDEX lupo_task_types_idx_type_key ON lupo_task_types (type_key);

-- ============================================================================
-- PART 2: TASK STATUSES REGISTRY
-- ============================================================================

CREATE TABLE lupo_task_statuses (
  status_id BIGINT NOT NULL PRIMARY KEY,
  status_key VARCHAR(64) NOT NULL UNIQUE,
  status_name VARCHAR(255) NOT NULL,
  description TEXT,
  is_terminal TINYINT NOT NULL DEFAULT 0,
  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,
  is_deleted TINYINT NOT NULL DEFAULT 0
);

CREATE INDEX lupo_task_statuses_idx_status_key ON lupo_task_statuses (status_key);
CREATE INDEX lupo_task_statuses_idx_is_terminal ON lupo_task_statuses (is_terminal);

-- ============================================================================
-- PART 3: TASK PRIORITIES REGISTRY
-- ============================================================================

CREATE TABLE lupo_task_priorities (
  priority_id BIGINT NOT NULL PRIMARY KEY,
  priority_key VARCHAR(64) NOT NULL UNIQUE,
  priority_name VARCHAR(255) NOT NULL,
  priority_level INT NOT NULL,
  description TEXT,
  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,
  is_deleted TINYINT NOT NULL DEFAULT 0
);

CREATE INDEX lupo_task_priorities_idx_priority_key ON lupo_task_priorities (priority_key);
CREATE INDEX lupo_task_priorities_idx_priority_level ON lupo_task_priorities (priority_level);

-- ============================================================================
-- PART 4: CORE TASKS TABLE
-- ============================================================================

CREATE TABLE lupo_tasks (
  task_id BIGINT NOT NULL PRIMARY KEY,
  task_key VARCHAR(64) NOT NULL,
  channel_id BIGINT NOT NULL,
  owner_actor_id BIGINT NOT NULL,
  task_type_id BIGINT NOT NULL,
  status_id BIGINT NOT NULL,
  priority_id BIGINT NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  prompt_path VARCHAR(512) DEFAULT NULL,
  acting_as_actor_id BIGINT DEFAULT NULL,
  estimated_duration_seconds INT DEFAULT NULL,
  actual_duration_seconds INT DEFAULT NULL,
  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,
  started_ymdhis BIGINT DEFAULT NULL,
  completed_ymdhis BIGINT DEFAULT NULL,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  deleted_ymdhis BIGINT DEFAULT NULL,
  metadata_json TEXT
);

CREATE UNIQUE INDEX lupo_tasks_uniq_task_key_per_channel ON lupo_tasks (task_key, channel_id);
CREATE INDEX lupo_tasks_idx_channel_id ON lupo_tasks (channel_id);
CREATE INDEX lupo_tasks_idx_owner_actor_id ON lupo_tasks (owner_actor_id);
CREATE INDEX lupo_tasks_idx_status_id ON lupo_tasks (status_id);
CREATE INDEX lupo_tasks_idx_priority_id ON lupo_tasks (priority_id);
CREATE INDEX lupo_tasks_idx_created_ymdhis ON lupo_tasks (created_ymdhis);
CREATE INDEX lupo_tasks_idx_acting_as_actor_id ON lupo_tasks (acting_as_actor_id);

-- ============================================================================
-- PART 5: TASK ASSIGNMENTS
-- ============================================================================

CREATE TABLE lupo_task_assignments (
  assignment_id BIGINT NOT NULL PRIMARY KEY,
  task_id BIGINT NOT NULL,
  actor_id BIGINT NOT NULL,
  assignment_type VARCHAR(32) NOT NULL DEFAULT 'assigned',
  assigned_by_actor_id BIGINT NOT NULL,
  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  deleted_ymdhis BIGINT DEFAULT NULL
);

CREATE INDEX lupo_task_assignments_idx_task_id ON lupo_task_assignments (task_id);
CREATE INDEX lupo_task_assignments_idx_actor_id ON lupo_task_assignments (actor_id);
CREATE INDEX lupo_task_assignments_idx_assignment_type ON lupo_task_assignments (assignment_type);

-- ============================================================================
-- PART 6: TASK DEPENDENCIES
-- ============================================================================

CREATE TABLE lupo_task_dependencies (
  dependency_id BIGINT NOT NULL PRIMARY KEY,
  task_id BIGINT NOT NULL,
  depends_on_task_id BIGINT NOT NULL,
  dependency_type VARCHAR(32) NOT NULL DEFAULT 'blocks',
  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  deleted_ymdhis BIGINT DEFAULT NULL
);

CREATE INDEX lupo_task_dependencies_idx_task_id ON lupo_task_dependencies (task_id);
CREATE INDEX lupo_task_dependencies_idx_depends_on_task_id ON lupo_task_dependencies (depends_on_task_id);
CREATE INDEX lupo_task_dependencies_idx_dependency_type ON lupo_task_dependencies (dependency_type);

-- ============================================================================
-- PART 7: TASK EVENTS (AUDIT LOG)
-- ============================================================================

CREATE TABLE lupo_task_events (
  event_id BIGINT NOT NULL PRIMARY KEY,
  task_id BIGINT NOT NULL,
  actor_id BIGINT NOT NULL,
  event_type VARCHAR(64) NOT NULL,
  old_value TEXT,
  new_value TEXT,
  notes TEXT,
  created_ymdhis BIGINT NOT NULL
);

CREATE INDEX lupo_task_events_idx_task_id ON lupo_task_events (task_id);
CREATE INDEX lupo_task_events_idx_actor_id ON lupo_task_events (actor_id);
CREATE INDEX lupo_task_events_idx_event_type ON lupo_task_events (event_type);
CREATE INDEX lupo_task_events_idx_created_ymdhis ON lupo_task_events (created_ymdhis);

-- ============================================================================
-- END OF TASKS SCHEMA
-- ============================================================================
