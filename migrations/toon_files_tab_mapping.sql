-- Map TOON-related content files to correct tabs
-- Using safe MySQL derived-table pattern

INSERT INTO lupo_collection_tab_map 
(collection_tab_id, federations_node_id, item_type, item_id, sort_order, properties, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES
(
    -- doctrine-toon-doctrine (content_id: 2058) → doctrine.mythic (collection_tab_id: 20)
    (SELECT t.collection_tab_id FROM (SELECT collection_tab_id FROM lupo_collection_tabs WHERE slug = 'doctrine.mythic') AS t),
    1,
    'content',
    (SELECT c.content_id FROM (SELECT content_id FROM lupo_contents WHERE content_id = 2058) AS c),
    1,
    NULL,
    20260113000000,
    20260113000000,
    0,
    NULL
),
(
    -- dev-toon-metadata-recommendations (content_id: 2093) → appendix.mythic (collection_tab_id: 30)
    (SELECT t.collection_tab_id FROM (SELECT collection_tab_id FROM lupo_collection_tabs WHERE slug = 'appendix.mythic') AS t),
    1,
    'content',
    (SELECT c.content_id FROM (SELECT content_id FROM lupo_contents WHERE content_id = 2093) AS c),
    2,
    NULL,
    20260113000000,
    20260113000000,
    0,
    NULL
);
