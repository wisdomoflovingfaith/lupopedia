-- Phase 3: Task System Consolidation
-- Target: Consolidate task lookup tables into lupo_tasks

-- 1. Add VARCHAR columns
ALTER TABLE lupo_tasks ADD COLUMN task_type VARCHAR(64);
ALTER TABLE lupo_tasks ADD COLUMN task_status VARCHAR(64);
ALTER TABLE lupo_tasks ADD COLUMN task_priority VARCHAR(64);

-- 2. Migrate data from lookup tables
-- We use COALESCE to fallback to the ID as string if label not found
UPDATE lupo_tasks t
LEFT JOIN lupo_task_types ty ON t.task_type_id = ty.type_id
SET t.task_type = COALESCE(ty.type_name, CAST(t.task_type_id AS CHAR));

UPDATE lupo_tasks t
LEFT JOIN lupo_task_statuses s ON t.status_id = s.status_id
SET t.task_status = COALESCE(s.status_name, CAST(t.status_id AS CHAR));

UPDATE lupo_tasks t
LEFT JOIN lupo_task_priorities p ON t.priority_id = p.priority_id
SET t.task_priority = COALESCE(p.priority_name, CAST(t.priority_id AS CHAR));

-- 3. Cleanup: Remove ID columns and drop tables
ALTER TABLE lupo_tasks DROP COLUMN task_type_id;
ALTER TABLE lupo_tasks DROP COLUMN status_id;
ALTER TABLE lupo_tasks DROP COLUMN priority_id;

DROP TABLE IF EXISTS lupo_task_types;
DROP TABLE IF EXISTS lupo_task_statuses;
DROP TABLE IF EXISTS lupo_task_priorities;
