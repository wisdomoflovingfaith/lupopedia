-- ======================================================================
-- DOCTRINE AGENT TAB MAPPING - Version 4.0.26
-- Maps agent-related doctrine content files to doctrine.agent tab (tab 13)
--
-- Mappings:
--   doctrine-agent-classification → doctrine.agent (tab 13)
--   doctrine-agent-routing-doctrine → doctrine.agent (tab 13)
--   doctrine-agent-runtime → doctrine.agent (tab 13)
--   doctrine-agent-prompt-doctrine → doctrine.agent (tab 13)
--   doctrine-agent-lifecycle-doctrine → doctrine.agent (tab 13)
--   doctrine-agent-filesystem-doctrine → doctrine.agent (tab 13)
--   doctrine-kernel-agents → doctrine.agent (tab 13)
--   doctrine-meta-agents → doctrine.agent (tab 13)
--   doctrine-lupopedia-agent-dedicated-slot-ranges → doctrine.agent (tab 13)
--   doctrine-faucet-rules-doctrine → doctrine.agent (tab 13)
--   doctrine-emo-agent-rules → doctrine.agent (tab 13)
--   doctrine-ai-integration-safety-doctrine → doctrine.agent (tab 13)
--   doctrine-ai-uncertainty-expression-doctrine → doctrine.agent (tab 13)
--
-- This migration handles existing mappings by updating them if they exist,
-- or inserting new ones if they don't (idempotent).
-- Uses three-step pattern: soft-delete on other tabs → update on target → insert if not exists
-- ======================================================================

-- Map doctrine-agent-classification to doctrine.agent (tab 13)
-- First, soft-delete any existing mappings on OTHER tabs (to handle duplicates)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-agent-classification'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 13
  AND ctm.is_deleted = 0;

-- Update existing mapping on tab 13 if it exists
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 1,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-agent-classification'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 13;

-- Insert only if no active mapping to tab 13 exists
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
    13 AS collection_tab_id,  -- doctrine.agent
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    1 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-agent-classification'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 13
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 13);

-- Map doctrine-agent-routing-doctrine to doctrine.agent (tab 13)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-agent-routing-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 13
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 2,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-agent-routing-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 13;

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
    13 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    2 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-agent-routing-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 13
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 13);

-- Map doctrine-agent-runtime to doctrine.agent (tab 13)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-agent-runtime'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 13
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 3,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-agent-runtime'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 13;

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
    13 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    3 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-agent-runtime'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 13
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 13);

-- Map doctrine-agent-prompt-doctrine to doctrine.agent (tab 13)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-agent-prompt-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 13
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 4,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-agent-prompt-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 13;

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
    13 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    4 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-agent-prompt-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 13
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 13);

-- Map doctrine-agent-lifecycle-doctrine to doctrine.agent (tab 13)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-agent-lifecycle-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 13
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 5,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-agent-lifecycle-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 13;

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
    13 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    5 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-agent-lifecycle-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 13
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 13);

-- Map doctrine-agent-filesystem-doctrine to doctrine.agent (tab 13)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-agent-filesystem-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 13
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 6,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-agent-filesystem-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 13;

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
    13 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    6 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-agent-filesystem-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 13
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 13);

-- Map doctrine-kernel-agents to doctrine.agent (tab 13)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-kernel-agents'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 13
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 7,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-kernel-agents'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 13;

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
    13 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    7 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-kernel-agents'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 13
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 13);

-- Map doctrine-meta-agents to doctrine.agent (tab 13)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-meta-agents'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 13
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 8,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-meta-agents'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 13;

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
    13 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    8 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-meta-agents'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 13
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 13);

-- Map doctrine-lupopedia-agent-dedicated-slot-ranges to doctrine.agent (tab 13)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-lupopedia-agent-dedicated-slot-ranges'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 13
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 9,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-lupopedia-agent-dedicated-slot-ranges'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 13;

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
    13 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    9 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-lupopedia-agent-dedicated-slot-ranges'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 13
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 13);

-- Map doctrine-faucet-rules-doctrine to doctrine.agent (tab 13)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-faucet-rules-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 13
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 10,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-faucet-rules-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 13;

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
    13 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    10 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-faucet-rules-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 13
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 13);

-- Map doctrine-emo-agent-rules to doctrine.agent (tab 13)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-emo-agent-rules'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 13
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 11,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-emo-agent-rules'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 13;

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
    13 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    11 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-emo-agent-rules'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 13
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 13);

-- Map doctrine-ai-integration-safety-doctrine to doctrine.agent (tab 13)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-ai-integration-safety-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 13
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 12,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-ai-integration-safety-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 13;

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
    13 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    12 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-ai-integration-safety-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 13
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 13);

-- Map doctrine-ai-uncertainty-expression-doctrine to doctrine.agent (tab 13)
UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.is_deleted = 1,
    ctm.updated_ymdhis = 20260115000000
WHERE c.slug = 'doctrine-ai-uncertainty-expression-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id <> 13
  AND ctm.is_deleted = 0;

UPDATE lupo_collection_tab_map ctm
INNER JOIN lupo_contents c ON ctm.item_id = c.content_id
SET 
    ctm.sort_order = 13,
    ctm.updated_ymdhis = 20260115000000,
    ctm.is_deleted = 0
WHERE c.slug = 'doctrine-ai-uncertainty-expression-doctrine'
  AND ctm.item_type = 'content'
  AND ctm.collection_tab_id = 13;

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
    13 AS collection_tab_id,
    1 AS federations_node_id,
    'content' AS item_type,
    c.content_id,
    13 AS sort_order,
    20260115000000 AS created_ymdhis,
    20260115000000 AS updated_ymdhis,
    0 AS is_deleted
FROM lupo_contents c
WHERE c.slug = 'doctrine-ai-uncertainty-expression-doctrine'
  AND NOT EXISTS (
    SELECT 1 FROM lupo_collection_tab_map ctm
    WHERE ctm.item_type = 'content' 
      AND ctm.item_id = c.content_id
      AND ctm.collection_tab_id = 13
      AND ctm.is_deleted = 0
  )
AND EXISTS (SELECT 1 FROM lupo_collection_tabs WHERE collection_tab_id = 13);

-- ======================================================================
-- VERIFICATION QUERY
-- Run this after migration to verify mappings:
--
-- SELECT cm.item_id, c.slug AS content_slug, ct.slug AS tab_slug, cm.sort_order
-- FROM lupo_collection_tab_map cm
-- JOIN lupo_contents c ON cm.item_id = c.content_id
-- JOIN lupo_collection_tabs ct ON cm.collection_tab_id = ct.collection_tab_id
-- WHERE ct.slug = 'doctrine.agent'
--   AND c.slug IN (
--     'doctrine-agent-classification',
--     'doctrine-agent-routing-doctrine',
--     'doctrine-agent-runtime',
--     'doctrine-agent-prompt-doctrine',
--     'doctrine-agent-lifecycle-doctrine',
--     'doctrine-agent-filesystem-doctrine',
--     'doctrine-kernel-agents',
--     'doctrine-meta-agents',
--     'doctrine-lupopedia-agent-dedicated-slot-ranges',
--     'doctrine-faucet-rules-doctrine',
--     'doctrine-emo-agent-rules',
--     'doctrine-ai-integration-safety-doctrine',
--     'doctrine-ai-uncertainty-expression-doctrine'
--   )
--   AND cm.is_deleted = 0
-- ORDER BY cm.sort_order;
-- ======================================================================
