-- Migration: Add lupo_actor_traits for intrinsic actor constraints (4.0.69)
-- Purpose: Actor-scoped traits only; channel roles remain in lupo_actor_channel_roles.
-- Doctrine: No FK; BIGINT UTC timestamps; explicit IDs; soft delete.
-- Reference: docs/status/DESIGN_NOTE_LUPO_ACTOR_TRAITS_4.0.69.md

CREATE TABLE IF NOT EXISTS lupo_actor_traits (
  actor_trait_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  trait_key varchar(128) NOT NULL,
  trait_value varchar(512) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  metadata text DEFAULT NULL,
  PRIMARY KEY (actor_trait_id)
);

CREATE INDEX lupo_actor_traits_idx_actor ON lupo_actor_traits (actor_id);
CREATE INDEX lupo_actor_traits_idx_actor_key ON lupo_actor_traits (actor_id, trait_key);
CREATE INDEX lupo_actor_traits_idx_trait_key ON lupo_actor_traits (trait_key);
CREATE INDEX lupo_actor_traits_idx_is_deleted ON lupo_actor_traits (is_deleted);

-- Log migration (idempotent)
INSERT IGNORE INTO lupo_schema_migrations (version, name, applied_ymdhis)
VALUES ('20260311', 'lupo_actor_traits_4.0.69', 20260311120000);
