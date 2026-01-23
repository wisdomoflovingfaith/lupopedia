-- Doctrine Versioning Tab Mapping - Version 4.0.26
-- Maps versioning doctrine content to doctrine.versioning tab (tab 21)
-- Uses safe three-step pattern for unique constraint handling

USE lupopedia;

-- Step 1: Soft-delete existing mappings for these content items
UPDATE lupo_collection_tab_map 
SET is_deleted = 1, deleted_ymdhis = 20260115172300, updated_ymdhis = 20260115172300
WHERE item_type = 'content'
AND item_id IN (
    (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-versioning-doctrine'),
    (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-version-doctrine'),
    (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-timestamp-doctrine')
);

-- Step 2: Update existing entries to target tab if they exist and aren't deleted
UPDATE lupo_collection_tab_map 
SET collection_tab_id = (SELECT collection_tab_id FROM lupo_collection_tabs WHERE slug = 'doctrine.versioning'),
    sort_order = CASE 
        WHEN (SELECT slug FROM lupo_contents WHERE content_id = item_id) = 'doctrine-versioning-doctrine' THEN 1
        WHEN (SELECT slug FROM lupo_contents WHERE content_id = item_id) = 'doctrine-version-doctrine' THEN 2
        WHEN (SELECT slug FROM lupo_contents WHERE content_id = item_id) = 'doctrine-timestamp-doctrine' THEN 3
    END,
    is_deleted = 0, deleted_ymdhis = NULL, updated_ymdhis = 20260115172300
WHERE item_type = 'content'
AND item_id IN (
    (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-versioning-doctrine'),
    (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-version-doctrine'),
    (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-timestamp-doctrine')
)
AND is_deleted = 0;

-- Step 3: Insert new mappings for any content items not already mapped
INSERT INTO lupo_collection_tab_map 
(collection_tab_id, federations_node_id, item_type, item_id, sort_order, properties, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT 
    t.collection_tab_id,
    1,
    'content',
    c.content_id,
    CASE 
        WHEN c.slug = 'doctrine-versioning-doctrine' THEN 1
        WHEN c.slug = 'doctrine-version-doctrine' THEN 2
        WHEN c.slug = 'doctrine-timestamp-doctrine' THEN 3
    END,
    NULL,
    20260115172300,
    20260115172300,
    0,
    NULL
FROM lupo_contents c
CROSS JOIN lupo_collection_tabs t
WHERE c.slug IN ('doctrine-versioning-doctrine', 'doctrine-version-doctrine', 'doctrine-timestamp-doctrine')
AND t.slug = 'doctrine.versioning'
AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map m 
    WHERE m.item_type = 'content' 
    AND m.item_id = c.content_id 
    AND m.is_deleted = 0
);
