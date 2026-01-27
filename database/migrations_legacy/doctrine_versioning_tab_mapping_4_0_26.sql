-- ======================================================================
-- DOCTRINE VERSIONING TAB MAPPING - Version 4.0.26
-- Maps versioning-related doctrine content files to doctrine.versioning tab (tab 21)
--
-- Mappings:
--   doctrine-versioning-doctrine → doctrine.versioning (tab 21)
--   doctrine-version-doctrine → doctrine.versioning (tab 21)
--   doctrine-timestamp-doctrine → doctrine.versioning (tab 21)
--
-- This migration handles existing mappings by updating them if they exist,
-- or inserting new ones if they don't (idempotent).
-- Uses three-step pattern: soft-delete on other tabs → update on target → insert if not exists
-- ======================================================================

-- Map doctrine-versioning-doctrine to doctrine.versioning (tab 21)
-- First, soft-delete any existing mappings on OTHER tabs (to handle duplicates)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-versioning-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 21
  AND ctm.is_deleted = 0;

-- Update existing mapping on tab 21 if it exists
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 1,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-versioning-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 21;

-- Insert only if no active mapping to tab 21 exists
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
    21 AS collection_tab_id,  -- doctrine.versioning
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    1 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-versioning-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 21
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 21);

-- Map doctrine-version-doctrine to doctrine.versioning (tab 21)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-version-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 21
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 2,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-version-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 21;

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
    21 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    2 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-version-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 21
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 21);

-- Map doctrine-timestamp-doctrine to doctrine.versioning (tab 21)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-timestamp-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 21
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 3,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-timestamp-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 21;

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
    21 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    3 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-timestamp-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 21
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 21);

-- ======================================================================
-- VERIFICATION QUERY
-- Run this after migration to verify mappings:
--
-- SELECT cm.item_id, c.slug AS content_slug, ct.slug AS tab_slug, cm.sort_order
-- FROM lupo_collection_tab_map cm
-- JOIN lupo_contents c ON cm.item_id = c.content_id
-- JOIN lupo_collection_tabs ct ON cm.collection_tab_id = ct.collection_tab_id
-- WHERE ct.slug = 'doctrine.versioning'
--   AND c.slug IN (
--     'doctrine-versioning-doctrine',
--     'doctrine-version-doctrine',
--     'doctrine-timestamp-doctrine'
--   )
--   AND cm.is_deleted = 0
-- ORDER BY cm.sort_order;
-- ======================================================================
