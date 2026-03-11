-- Migration: Human Actor ID Doctrine (4.0.69).
-- Doctrine: HumanActorIdDoctrine.md — human actors must have actor_id >= 1000.
-- lupo_actors has no AUTO_INCREMENT; IDs come from lupo_registry_open or reserved constants.

-- 1. Verification: ensure no human actors exist below 1000 (run manually or in app; must return empty)
-- SELECT actor_id, actor_name, actor_type FROM lupo_actors WHERE actor_type = 'human' AND actor_id < 1000;

-- 2. Lupopedia does not use AUTO_INCREMENT on lupo_actors; do not run ALTER TABLE ... AUTO_INCREMENT.
-- 3. Lupopedia uses lupo_registry_open (not id_allocator) for ID allocation; ensure human slots are >= 1000.

-- Record that this doctrine is in effect (idempotent)
INSERT IGNORE INTO lupo_schema_migrations (schema_migration_id, version, name, applied_ymdhis)
VALUES (2026031003, '20260310_human_actor_id_doctrine', 'human_actor_id_doctrine', 20260310120000);
