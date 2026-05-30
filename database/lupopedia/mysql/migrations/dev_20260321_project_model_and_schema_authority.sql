-- Migration: dev_20260321_project_model_and_schema_authority
-- Applies: project_id to all required tables; lupo_actor_projects creation;
--          lupo_atoms identity extension; lupo_metadata project scoping
-- Run once. Do not re-run.

-- Step 1: backfill nullable project_id on lupo_channels before NOT NULL enforcement
UPDATE lupo_channels SET project_id = 0 WHERE project_id IS NULL;
ALTER TABLE lupo_channels MODIFY COLUMN project_id BIGINT NOT NULL DEFAULT 0;
CREATE INDEX IF NOT EXISTS lupo_channels_idx_project_id ON lupo_channels (project_id);

-- Step 2: add project_id to lupo_dialog_threads
ALTER TABLE lupo_dialog_threads ADD COLUMN project_id BIGINT NOT NULL DEFAULT 0;
CREATE INDEX IF NOT EXISTS lupo_dialog_threads_idx_project_id ON lupo_dialog_threads (project_id);

-- Step 3: add project_id to lupo_tasks
ALTER TABLE lupo_tasks ADD COLUMN project_id BIGINT NOT NULL DEFAULT 0;
CREATE INDEX IF NOT EXISTS lupo_tasks_idx_project_id ON lupo_tasks (project_id);

-- Step 4: add project_id to lupo_edges
ALTER TABLE lupo_edges ADD COLUMN project_id BIGINT NOT NULL DEFAULT 0;
CREATE INDEX IF NOT EXISTS lupo_edges_idx_project_id ON lupo_edges (project_id);

-- Step 5: add project_id to lupo_metadata
ALTER TABLE lupo_metadata ADD COLUMN project_id BIGINT NOT NULL DEFAULT 0;
CREATE INDEX IF NOT EXISTS lupo_metadata_idx_project_id ON lupo_metadata (project_id);

-- Step 6: add project_id, namespace, atom_path to lupo_atoms
ALTER TABLE lupo_atoms ADD COLUMN project_id BIGINT NOT NULL DEFAULT 0;
ALTER TABLE lupo_atoms ADD COLUMN namespace VARCHAR(128) NOT NULL DEFAULT '';
ALTER TABLE lupo_atoms ADD COLUMN atom_path VARCHAR(512) NOT NULL DEFAULT '';
CREATE UNIQUE INDEX IF NOT EXISTS lupo_atoms_uniq_project_namespace_path
    ON lupo_atoms (project_id, namespace, atom_path);
CREATE INDEX IF NOT EXISTS lupo_atoms_idx_project_id ON lupo_atoms (project_id);

-- Step 7: create lupo_actor_projects
CREATE TABLE IF NOT EXISTS lupo_actor_projects (
  actor_project_id BIGINT NOT NULL,
  actor_id BIGINT NOT NULL,
  project_id BIGINT NOT NULL,
  role VARCHAR(64) NOT NULL DEFAULT 'member',
  created_ymdhis BIGINT NOT NULL DEFAULT 0,
  updated_ymdhis BIGINT NOT NULL DEFAULT 0,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  deleted_ymdhis BIGINT NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_project_id)
);
CREATE UNIQUE INDEX IF NOT EXISTS lupo_actor_projects_uniq_actor_project
    ON lupo_actor_projects (actor_id, project_id, is_deleted);
CREATE INDEX IF NOT EXISTS lupo_actor_projects_idx_actor_id
    ON lupo_actor_projects (actor_id);
CREATE INDEX IF NOT EXISTS lupo_actor_projects_idx_project_id
    ON lupo_actor_projects (project_id);
CREATE INDEX IF NOT EXISTS lupo_actor_projects_idx_role
    ON lupo_actor_projects (role);
CREATE INDEX IF NOT EXISTS lupo_actor_projects_idx_created
    ON lupo_actor_projects (created_ymdhis);
CREATE INDEX IF NOT EXISTS lupo_actor_projects_idx_updated
    ON lupo_actor_projects (updated_ymdhis);
CREATE INDEX IF NOT EXISTS lupo_actor_projects_idx_deleted
    ON lupo_actor_projects (is_deleted);

-- Step 8: visibility extension backfills (Thread 1031 / 1032 resolution)
ALTER TABLE lupo_channels ADD COLUMN IF NOT EXISTS visibility_status VARCHAR(32) NOT NULL DEFAULT 'active';
ALTER TABLE lupo_channels ADD COLUMN IF NOT EXISTS owner_actor_id BIGINT NOT NULL DEFAULT 1;
ALTER TABLE lupo_channels ADD COLUMN IF NOT EXISTS access_level VARCHAR(32) NOT NULL DEFAULT 'public';
ALTER TABLE lupo_channels ADD COLUMN IF NOT EXISTS last_activity_ymdhis BIGINT NOT NULL DEFAULT 0;
CREATE INDEX IF NOT EXISTS lupo_channels_idx_visibility_status ON lupo_channels (visibility_status);
CREATE INDEX IF NOT EXISTS lupo_channels_idx_owner_actor_id ON lupo_channels (owner_actor_id);
CREATE INDEX IF NOT EXISTS lupo_channels_idx_access_level ON lupo_channels (access_level);
CREATE INDEX IF NOT EXISTS lupo_channels_idx_last_activity ON lupo_channels (last_activity_ymdhis);

ALTER TABLE lupo_dialog_threads ADD COLUMN IF NOT EXISTS visibility_status VARCHAR(32) NOT NULL DEFAULT 'active';
ALTER TABLE lupo_dialog_threads ADD COLUMN IF NOT EXISTS owner_actor_id BIGINT NOT NULL DEFAULT 1;
ALTER TABLE lupo_dialog_threads ADD COLUMN IF NOT EXISTS assigned_actor_id BIGINT DEFAULT NULL;
ALTER TABLE lupo_dialog_threads ADD COLUMN IF NOT EXISTS thread_type VARCHAR(32) NOT NULL DEFAULT 'discussion';
ALTER TABLE lupo_dialog_threads ADD COLUMN IF NOT EXISTS thread_priority VARCHAR(32) NOT NULL DEFAULT 'normal';
CREATE INDEX IF NOT EXISTS lupo_dialog_threads_idx_visibility_status ON lupo_dialog_threads (visibility_status);
CREATE INDEX IF NOT EXISTS lupo_dialog_threads_idx_owner_actor_id ON lupo_dialog_threads (owner_actor_id);
CREATE INDEX IF NOT EXISTS lupo_dialog_threads_idx_assigned_actor_id ON lupo_dialog_threads (assigned_actor_id);
CREATE INDEX IF NOT EXISTS lupo_dialog_threads_idx_thread_type ON lupo_dialog_threads (thread_type);
CREATE INDEX IF NOT EXISTS lupo_dialog_threads_idx_thread_priority ON lupo_dialog_threads (thread_priority);

ALTER TABLE lupo_tasks ADD COLUMN IF NOT EXISTS visibility_status VARCHAR(32) NOT NULL DEFAULT 'active';
CREATE INDEX IF NOT EXISTS lupo_tasks_idx_visibility_status ON lupo_tasks (visibility_status);
