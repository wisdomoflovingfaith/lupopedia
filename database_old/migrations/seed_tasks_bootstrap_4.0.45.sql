-- ============================================================================
-- SEED TASKS BOOTSTRAP FOR LUPOPEDIA 4.0.45
-- ============================================================================
-- Purpose: Seed task types, statuses, and priorities
-- Run after: add_tasks_schema_4.0.45.sql
-- ============================================================================

SET @now = 20260225000000;

-- ============================================================================
-- PART 1: TASK TYPES
-- ============================================================================

INSERT INTO lupo_task_types (type_id, type_key, type_name, description, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(1, 'database_operation', 'Database Operation', 'Database-related tasks (install, migration, seeding)', @now, @now, 0),
(2, 'content_normalization', 'Content Normalization', 'Content cleanup and standardization tasks', @now, @now, 0),
(3, 'governance', 'Governance', 'Policy and governance tasks', @now, @now, 0),
(4, 'integration', 'Integration', 'System integration tasks', @now, @now, 0),
(5, 'validation', 'Validation', 'Validation and verification tasks', @now, @now, 0),
(6, 'analysis', 'Analysis', 'Analysis and research tasks', @now, @now, 0),
(7, 'infrastructure', 'Infrastructure', 'Infrastructure setup and configuration', @now, @now, 0),
(8, 'documentation', 'Documentation', 'Documentation creation and updates', @now, @now, 0);

-- ============================================================================
-- PART 2: TASK STATUSES
-- ============================================================================

INSERT INTO lupo_task_statuses (status_id, status_key, status_name, description, is_terminal, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(1, 'pending', 'Pending', 'Task is waiting to start', 0, @now, @now, 0),
(2, 'active', 'Active', 'Task is currently in progress', 0, @now, @now, 0),
(3, 'blocked', 'Blocked', 'Task is blocked by dependencies', 0, @now, @now, 0),
(4, 'completed', 'Completed', 'Task is finished successfully', 1, @now, @now, 0),
(5, 'archived', 'Archived', 'Task is archived', 1, @now, @now, 0),
(6, 'cancelled', 'Cancelled', 'Task was cancelled', 1, @now, @now, 0);

-- ============================================================================
-- PART 3: TASK PRIORITIES
-- ============================================================================

INSERT INTO lupo_task_priorities (priority_id, priority_key, priority_name, priority_level, description, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(1, 'critical', 'Critical', 1, 'Highest priority - blocks all other work', @now, @now, 0),
(2, 'high', 'High', 2, 'High priority - should be done soon', @now, @now, 0),
(3, 'normal', 'Normal', 3, 'Normal priority', @now, @now, 0),
(4, 'low', 'Low', 4, 'Low priority - nice to have', @now, @now, 0);

-- ============================================================================
-- END OF TASKS BOOTSTRAP SEEDING
-- ============================================================================
