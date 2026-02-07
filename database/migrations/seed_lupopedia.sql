-- FILE: database/migrations/seed_lupopedia.sql
-- TYPE: sql
-- Purpose: Seed data for a fresh Lupopedia installation. Run after install_new_lupopedia.sql.
-- Separate from install (schema only) and import_from_old_crafty_syntax (legacy import).
-- No Crafty Syntax data. No schema definitions. No migration logic.

-- =============================================================================
-- SEED LUPOPEDIA — FRESH INSTALL SEED DATA
-- =============================================================================
--
-- This file will contain:
--
--   • Registry table seed rows
--     (e.g. agent registry, pack role registry, channel roles, etc.)
--
--   • Row 0 node
--     (kernel / system identity row where applicable)
--
--   • Row 0 collection
--     (kernel collection identity where applicable)
--
--   • Additional seed atoms to be added later
--     (atoms, system config, or other bootstrap rows as defined by doctrine)
--
-- Rules:
--   • Do NOT mix install, import, or seed responsibilities in this file.
--   • Do NOT include Crafty Syntax data (use import_from_old_crafty_syntax.sql).
--   • Do NOT include schema (use install_new_lupopedia.sql).
--   • INSERT statements will be added here as seed requirements are defined.
--
-- =============================================================================

-- -----------------------------------------------------------------------------
-- Row 0: Federation node (lupopedia.com, theme default)
-- -----------------------------------------------------------------------------
-- Some DBs do not allow INSERT with primary key 0 (e.g. SERIAL). We insert with
-- a temporary id, then UPDATE to set federation_node_id = 0.
-- DB-agnostic: MySQL and PostgreSQL.

INSERT INTO lupo_federation_nodes (
  federation_node_id,
  node_base_url,
  default_department_id,
  node_name,
  node_description,
  node_contact,
  meta_json,
  content_count,
  atom_count,
  hashtag_count,
  actor_count,
  last_sync_ymdhis,
  trust_level,
  status,
  is_deleted,
  deleted_ymdhis,
  created_ymdhis,
  updated_ymdhis,
  active_theme_slug
) VALUES (
  999999,
  'https://lupopedia.com',
  NULL,
  'Lupopedia',
  NULL,
  NULL,
  NULL,
  0,
  0,
  0,
  0,
  0,
  0,
  1,
  0,
  0,
  20260101000000,
  20260101000000,
  'default'
);

UPDATE lupo_federation_nodes SET federation_node_id = 0 WHERE federation_node_id = 999999;

