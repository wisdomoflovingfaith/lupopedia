-- ======================================================================
-- DOCTRINE SQL TAB MAPPING - Version 4.0.25
-- Maps SQL-related doctrine content files to doctrine.sql tab (tab 11)
--
-- Mappings:
--   doctrine-sql-refactor-mapping-doctrine → doctrine.sql (tab 11)
--   doctrine-sql-rewrite-doctrine → doctrine.sql (tab 11)
--   doctrine-sql-type-doctrine → doctrine.sql (tab 11)
--   doctrine-no-foreign-keys-doctrine → doctrine.sql (tab 11)
--   doctrine-no-stored-procedures-doctrine → doctrine.sql (tab 11)
--   doctrine-no-triggers-doctrine → doctrine.sql (tab 11)
--   doctrine-pdo-conversion-doctrine → doctrine.sql (tab 11)
--
-- This migration handles existing mappings by updating them if they exist,
-- or inserting new ones if they don't (idempotent).
-- ======================================================================

-- Map doctrine-sql-refactor-mapping-doctrine to doctrine.sql (tab 11)
-- First, soft-delete any existing mappings on OTHER tabs (to handle duplicates)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-sql-refactor-mapping-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 11
  AND ctm.is_deleted = 0;

-- Update existing mapping on tab 11 if it exists
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 1,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-sql-refactor-mapping-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 11;

-- Insert only if no active mapping to tab 11 exists
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
    11 AS collection_tab_id,  -- doctrine.sql
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    1 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-sql-refactor-mapping-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 11
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 11);

-- Map doctrine-sql-rewrite-doctrine to doctrine.sql (tab 11)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-sql-rewrite-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 11
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 2,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-sql-rewrite-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 11;

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
    11 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    2 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-sql-rewrite-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 11
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 11);

-- Map doctrine-sql-type-doctrine to doctrine.sql (tab 11)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-sql-type-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 11
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 3,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-sql-type-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 11;

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
    11 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    3 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-sql-type-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 11
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 11);

-- Map doctrine-no-foreign-keys-doctrine to doctrine.sql (tab 11)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-no-foreign-keys-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 11
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 4,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-no-foreign-keys-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 11;

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
    11 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    4 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-no-foreign-keys-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 11
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 11);

-- Map doctrine-no-stored-procedures-doctrine to doctrine.sql (tab 11)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-no-stored-procedures-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 11
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 5,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-no-stored-procedures-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 11;

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
    11 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    5 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-no-stored-procedures-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 11
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 11);

-- Map doctrine-no-triggers-doctrine to doctrine.sql (tab 11)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-no-triggers-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 11
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 6,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-no-triggers-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 11;

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
    11 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    6 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-no-triggers-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 11
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 11);

-- Map doctrine-pdo-conversion-doctrine to doctrine.sql (tab 11)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-pdo-conversion-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 11
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 7,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-pdo-conversion-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 11;

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
    11 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    7 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-pdo-conversion-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 11
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 11);

-- Verify mappings
SELECT 
    ctm.collection_tab_map_id,
    ct.slug AS tab_slug,
    c.slug AS content_slug,
    ctm.item_id AS content_id,
    ctm.sort_order,
    ctm.is_deleted
FROM lupo_collection_tab_map ctm
JOIN lupo_collection_tabs ct ON ctm.collection_tab_id = ct.collection_tab_id
JOIN lupo_contents c ON ctm.item_id = c.content_id
WHERE c.slug IN (
    'doctrine-sql-refactor-mapping-doctrine',
    'doctrine-sql-rewrite-doctrine',
    'doctrine-sql-type-doctrine',
    'doctrine-no-foreign-keys-doctrine',
    'doctrine-no-stored-procedures-doctrine',
    'doctrine-no-triggers-doctrine',
    'doctrine-pdo-conversion-doctrine'
)
ORDER BY ctm.sort_order, c.slug;
