-- ======================================================================
-- TOON FILES TAB MAPPING - Version 4.0.23
-- Maps TOON-related content files to correct collection tabs
--
-- Mappings:
--   doctrine-toon-doctrine (content_id: 2058) → doctrine.mythic (tab 20)
--   dev-toon-metadata-recommendations (content_id: 2093) → appendix.mythic (tab 30)
--
-- This migration handles existing mappings by updating them if they exist,
-- or inserting new ones if they don't (idempotent).
-- ======================================================================

-- Map doctrine-toon-doctrine to doctrine.mythic (tab 20)
-- Update existing mapping if it exists, otherwise insert new one
UPDATE lupo_collection_tab_map
SET 
    collection_tab_id = 20,
    updated_ymdhis = 20260115000000,
    is_deleted = 0
WHERE item_type = 'content' 
  AND item_id = 2058
  AND is_deleted = 0;

-- Insert if no existing mapping found
INSERT INTO lupo_collection_tab_map (
    collection_tab_id,
    federations_node_id,
    item_type,
    item_id,
    sort_order,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
)
SELECT 
    20 AS collection_tab_id,  -- doctrine.mythic
    1 AS federations_node_id,
    'content' AS item_type,
    2058 AS item_id,  -- doctrine-toon-doctrine
    COALESCE((SELECT MAX(sort_order) FROM lupo_collection_tab_map WHERE collection_tab_id = 20 AND is_deleted = 0), 0) + 1 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
WHERE NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map 
    WHERE item_type = 'content' 
      AND item_id = 2058
      AND is_deleted = 0
)
AND EXISTS (SELECT 1 FROM lupo_contents WHERE content_id = 2058)
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 20);

-- Map dev-toon-metadata-recommendations to appendix.mythic (tab 30)
-- Update existing mapping if it exists, otherwise insert new one
UPDATE lupo_collection_tab_map
SET 
    collection_tab_id = 30,
    updated_ymdhis = 20260115000000,
    is_deleted = 0
WHERE item_type = 'content' 
  AND item_id = 2093
  AND is_deleted = 0;

-- Insert if no existing mapping found
INSERT INTO lupo_collection_tab_map (
    collection_tab_id,
    federations_node_id,
    item_type,
    item_id,
    sort_order,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
)
SELECT 
    30 AS collection_tab_id,  -- appendix.mythic
    1 AS federations_node_id,
    'content' AS item_type,
    2093 AS item_id,  -- dev-toon-metadata-recommendations
    COALESCE((SELECT MAX(sort_order) FROM lupo_collection_tab_map WHERE collection_tab_id = 30 AND is_deleted = 0), 0) + 1 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
WHERE NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map 
    WHERE item_type = 'content' 
      AND item_id = 2093
      AND is_deleted = 0
)
AND EXISTS (SELECT 1 FROM lupo_contents WHERE content_id = 2093)
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 30);

-- Verify mappings
SELECT 
    ctm.collection_tab_map_id,
    ct.slug AS tab_slug,
    c.slug AS content_slug,
    ctm.item_id AS content_id,
    ctm.sort_order
FROM lupo_collection_tab_map ctm
JOIN lupo_collection_tabs ct ON ctm.collection_tab_id = ct.collection_tab_id
JOIN lupo_contents c ON ctm.item_id = c.content_id
WHERE ctm.item_id IN (2058, 2093)
  AND ctm.is_deleted = 0
ORDER BY ctm.collection_tab_id, ctm.sort_order;
