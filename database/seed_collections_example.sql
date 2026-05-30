-- =============================================================================
-- seed_collections_example.sql — OPTIONAL demo data for Collections UI
-- =============================================================================
-- Not merged into canonical install/seed. For local/dev when lupo_collections
-- and tabs are empty (Collections dropdown + green tab bar).
--
-- MySQL / MariaDB oriented. Replace table prefix lupo_ if yours differs.
-- Run manually (doctrine: prefer php scripts/safe-migrate.php only for
-- reviewed migrations; this file is a documented paste/run example).
--
-- Canonical tables: lupo_collections, lupo_collection_tabs,
-- lupo_collection_tab_map (there is NO lupo_collection_items).
-- =============================================================================

SET @lupo_ts := 20260409203702;

INSERT INTO lupo_collections (
  collection_id, federation_node_id, actor_id, department_id, name, slug, color, description, sort_order,
  published_ymdhis, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, parent_id, channel_id, is_nav_menu, nav_icon
) VALUES (
  990001, 1, 1, NULL, 'Research Root', 'research-root', '2973e4',
  'Example collection for Collections dropdown and tab chrome.', 10,
  @lupo_ts, @lupo_ts, @lupo_ts, 0, NULL, NULL, NULL, 1, NULL
)
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  description = VALUES(description),
  updated_ymdhis = VALUES(updated_ymdhis),
  is_nav_menu = 1;

INSERT INTO lupo_actor_collections (
  actor_collection_id, actor_id, collection_id, access_level, created_ymdhis, updated_ymdhis,
  is_deleted, deleted_ymdhis, persistent_identity_json, identity_signature, trust_level,
  emotional_geometry_baseline, doctrine_alignment_version
) VALUES (
  990901, 1, 990001, 'read', @lupo_ts, @lupo_ts, 0, NULL, NULL, NULL, 'standard', NULL, '3.0.0'
);

INSERT INTO lupo_collection_tabs (
  collection_tab_id, collection_tab_parent_id, collection_id, federations_node_id, department_id, actor_id,
  sort_order, name, slug, color, description, is_hidden, visibility_rule, tab_type,
  created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis
) VALUES
(990101, NULL, 990001, 1, NULL, 1, 10, 'WHO', 'who', '4caf50', NULL, 0, NULL, NULL, @lupo_ts, @lupo_ts, 1, 0, NULL),
(990102, NULL, 990001, 1, NULL, 1, 20, 'WHAT', 'what', '4caf50', NULL, 0, NULL, NULL, @lupo_ts, @lupo_ts, 1, 0, NULL),
(990103, NULL, 990001, 1, NULL, 1, 30, 'WHERE', 'where', '4caf50', NULL, 0, NULL, NULL, @lupo_ts, @lupo_ts, 1, 0, NULL),
(990104, NULL, 990001, 1, NULL, 1, 40, 'WHEN', 'when', '4caf50', NULL, 0, NULL, NULL, @lupo_ts, @lupo_ts, 1, 0, NULL),
(990105, NULL, 990001, 1, NULL, 1, 50, 'WHY', 'why', '4caf50', NULL, 0, NULL, NULL, @lupo_ts, @lupo_ts, 1, 0, NULL)
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  updated_ymdhis = VALUES(updated_ymdhis),
  is_active = 1;

-- Map first few published content rows into WHO tab (item_type = content, item_id = content_id)
INSERT INTO lupo_collection_tab_map (
  collection_tab_map_id, collection_tab_id, federations_node_id, item_type, item_id, sort_order, properties,
  created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
)
SELECT 991001, 990101, 1, 'content', c.content_id, 10, NULL, @lupo_ts, @lupo_ts, 0, NULL
FROM lupo_contents c
WHERE (c.is_deleted = 0 OR c.is_deleted IS NULL) AND (c.is_active = 1 OR c.is_active IS NULL) AND c.slug IS NOT NULL AND c.slug <> ''
ORDER BY c.content_id ASC
LIMIT 1;

INSERT INTO lupo_collection_tab_map (
  collection_tab_map_id, collection_tab_id, federations_node_id, item_type, item_id, sort_order, properties,
  created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
)
SELECT 991002, 990102, 1, 'content', c.content_id, 10, NULL, @lupo_ts, @lupo_ts, 0, NULL
FROM lupo_contents c
WHERE (c.is_deleted = 0 OR c.is_deleted IS NULL) AND (c.is_active = 1 OR c.is_active IS NULL) AND c.slug IS NOT NULL AND c.slug <> ''
ORDER BY c.content_id ASC
LIMIT 1 OFFSET 1;

INSERT INTO lupo_collection_tab_map (
  collection_tab_map_id, collection_tab_id, federations_node_id, item_type, item_id, sort_order, properties,
  created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
)
SELECT 991003, 990103, 1, 'content', c.content_id, 10, NULL, @lupo_ts, @lupo_ts, 0, NULL
FROM lupo_contents c
WHERE (c.is_deleted = 0 OR c.is_deleted IS NULL) AND (c.is_active = 1 OR c.is_active IS NULL) AND c.slug IS NOT NULL AND c.slug <> ''
ORDER BY c.content_id ASC
LIMIT 1 OFFSET 2;
