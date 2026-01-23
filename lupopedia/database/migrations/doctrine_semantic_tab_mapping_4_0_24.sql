-- ======================================================================
-- DOCTRINE SEMANTIC TAB MAPPING - Version 4.0.24
-- Maps semantic doctrine content files to doctrine.semantic tab (tab 17)
--
-- Mappings:
--   doctrine-atomization-doctrine → doctrine.semantic (tab 17)
--   doctrine-atom-resolution-specification → doctrine.semantic (tab 17)
--   doctrine-global-atoms-doctrine → doctrine.semantic (tab 17)
--   doctrine-ingestion-doctrine → doctrine.semantic (tab 17)
--   doctrine-semantic-graph-doctrine → doctrine.semantic (tab 17)
--   doctrine-as-above-so-below → doctrine.semantic (tab 17)
--   doctrine-reflective-emotional-geometry-doctrine → doctrine.semantic (tab 17)
--   doctrine-mood-rgb-doctrine → doctrine.semantic (tab 17)
--   doctrine-emotional-agent-range → doctrine.semantic (tab 17)
--
-- This migration handles existing mappings by updating them if they exist,
-- or inserting new ones if they don't (idempotent).
-- ======================================================================

-- Map doctrine-atomization-doctrine to doctrine.semantic (tab 17)
-- First, soft-delete any existing mappings on OTHER tabs (to handle duplicates)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-atomization-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 17
  AND ctm.is_deleted = 0;

-- Update existing mapping on tab 17 if it exists
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 1,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-atomization-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 17;

-- Insert only if no active mapping to tab 17 exists
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
    17 AS collection_tab_id,  -- doctrine.semantic
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    1 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-atomization-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 17
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 17);

-- Map doctrine-atom-resolution-specification to doctrine.semantic (tab 17)
-- First, soft-delete any existing mappings on OTHER tabs (to handle duplicates)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-atom-resolution-specification'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 17
  AND ctm.is_deleted = 0;

-- Update existing mapping on tab 17 if it exists
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 2,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-atom-resolution-specification'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 17;

-- Insert only if no active mapping to tab 17 exists
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
    17 AS collection_tab_id,  -- doctrine.semantic
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    2 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-atom-resolution-specification'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 17
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 17);

-- Map doctrine-global-atoms-doctrine to doctrine.semantic (tab 17)
-- First, soft-delete any existing mappings on OTHER tabs (to handle duplicates)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-global-atoms-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 17
  AND ctm.is_deleted = 0;

-- Update existing mapping on tab 17 if it exists
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 3,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-global-atoms-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 17;

-- Insert only if no active mapping to tab 17 exists
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
    17 AS collection_tab_id,  -- doctrine.semantic
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    3 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-global-atoms-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 17
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 17);

-- Map doctrine-ingestion-doctrine to doctrine.semantic (tab 17)
-- First, soft-delete any existing mappings on OTHER tabs (to handle duplicates)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-ingestion-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 17
  AND ctm.is_deleted = 0;

-- Update existing mapping on tab 17 if it exists
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 4,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-ingestion-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 17;

-- Insert only if no active mapping to tab 17 exists
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
    17 AS collection_tab_id,  -- doctrine.semantic
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    4 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-ingestion-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 17
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 17);

-- Map doctrine-semantic-graph-doctrine to doctrine.semantic (tab 17)
-- First, soft-delete any existing mappings on OTHER tabs (to handle duplicates)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-semantic-graph-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 17
  AND ctm.is_deleted = 0;

-- Update existing mapping on tab 17 if it exists
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 5,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-semantic-graph-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 17;

-- Insert only if no active mapping to tab 17 exists
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
    17 AS collection_tab_id,  -- doctrine.semantic
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    5 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-semantic-graph-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 17
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 17);

-- Map doctrine-as-above-so-below to doctrine.semantic (tab 17)
-- First, soft-delete any existing mappings on OTHER tabs (to handle duplicates)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-as-above-so-below'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 17
  AND ctm.is_deleted = 0;

-- Update existing mapping on tab 17 if it exists
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 6,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-as-above-so-below'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 17;

-- Insert only if no active mapping to tab 17 exists
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
    17 AS collection_tab_id,  -- doctrine.semantic
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    6 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-as-above-so-below'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 17
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 17);

-- Map doctrine-reflective-emotional-geometry-doctrine to doctrine.semantic (tab 17)
-- First, soft-delete any existing mappings on OTHER tabs (to handle duplicates)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-reflective-emotional-geometry-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 17
  AND ctm.is_deleted = 0;

-- Update existing mapping on tab 17 if it exists
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 7,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-reflective-emotional-geometry-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 17;

-- Insert only if no active mapping to tab 17 exists
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
    17 AS collection_tab_id,  -- doctrine.semantic
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    7 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-reflective-emotional-geometry-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 17
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 17);

-- Map doctrine-mood-rgb-doctrine to doctrine.semantic (tab 17)
-- First, soft-delete any existing mappings on OTHER tabs (to handle duplicates)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-mood-rgb-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 17
  AND ctm.is_deleted = 0;

-- Update existing mapping on tab 17 if it exists
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 8,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-mood-rgb-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 17;

-- Insert only if no active mapping to tab 17 exists
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
    17 AS collection_tab_id,  -- doctrine.semantic
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    8 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-mood-rgb-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 17
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 17);

-- Map doctrine-emotional-agent-range to doctrine.semantic (tab 17)
-- First, soft-delete any existing mappings on OTHER tabs (to handle duplicates)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-emotional-agent-range'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 17
  AND ctm.is_deleted = 0;

-- Update existing mapping on tab 17 if it exists
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 9,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-emotional-agent-range'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 17;

-- Insert only if no active mapping to tab 17 exists
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
    17 AS collection_tab_id,  -- doctrine.semantic
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    9 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-emotional-agent-range'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 17
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 17);

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
    'doctrine-atomization-doctrine',
    'doctrine-atom-resolution-specification',
    'doctrine-global-atoms-doctrine',
    'doctrine-ingestion-doctrine',
    'doctrine-semantic-graph-doctrine',
    'doctrine-as-above-so-below',
    'doctrine-reflective-emotional-geometry-doctrine',
    'doctrine-mood-rgb-doctrine',
    'doctrine-emotional-agent-range'
)
ORDER BY ctm.sort_order, c.slug;
