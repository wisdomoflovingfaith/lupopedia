-- Seed: lupo_projects (4.0.74). Optional; run after install.
-- Application must supply project_id (no AUTO_INCREMENT); reserved IDs or allocate from registry.
SET @now = 20260314000000;

INSERT INTO lupo_projects (
  project_id,
  project_key,
  project_name,
  project_slug,
  description,
  channel_id,
  orchestrator_id,
  federation_node_id,
  status,
  project_type,
  metadata_json,
  created_ymdhis,
  updated_ymdhis,
  is_deleted,
  deleted_ymdhis
) VALUES
(1, 'lupopedia-core', 'Lupopedia Core', 'lupopedia-core', 'Primary Lupopedia semantic OS instance.', 42, 102, 1, 'active', 'general', NULL, @now, @now, 0, 0),
(2, 'federation-example', 'Federation Example', 'federation-example', 'Example federation node project.', 42, 102, 1, 'active', 'general', NULL, @now, @now, 0, 0);
