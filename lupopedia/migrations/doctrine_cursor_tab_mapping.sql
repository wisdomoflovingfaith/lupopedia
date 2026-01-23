-- Map specified slugs to doctrine.cursor tab (collection_tab_id: 12)
-- Using safe MySQL derived-table pattern
-- Only using the provided slug list without content verification

INSERT INTO lupo_collection_tab_map 
(collection_tab_id, federations_node_id, item_type, item_id, sort_order, properties, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES
(
    (SELECT t.collection_tab_id FROM (SELECT collection_tab_id FROM lupo_collection_tabs WHERE slug = 'doctrine.cursor') AS t),
    1,
    'content',
    (SELECT c.content_id FROM (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-cursor-doctrine') AS c),
    1,
    NULL,
    20260113000000,
    20260113000000,
    0,
    NULL
),
(
    (SELECT t.collection_tab_id FROM (SELECT collection_tab_id FROM lupo_collection_tabs WHERE slug = 'doctrine.cursor') AS t),
    1,
    'content',
    (SELECT c.content_id FROM (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-cursor-safety-doctrine') AS c),
    2,
    NULL,
    20260113000000,
    20260113000000,
    0,
    NULL
),
(
    (SELECT t.collection_tab_id FROM (SELECT collection_tab_id FROM lupo_collection_tabs WHERE slug = 'doctrine.cursor') AS t),
    1,
    'content',
    (SELECT c.content_id FROM (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-cursor-context-boundary-doctrine') AS c),
    3,
    NULL,
    20260113000000,
    20260113000000,
    0,
    NULL
),
(
    (SELECT t.collection_tab_id FROM (SELECT collection_tab_id FROM lupo_collection_tabs WHERE slug = 'doctrine.cursor') AS t),
    1,
    'content',
    (SELECT c.content_id FROM (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-cursor-hallucination-containment-doctrine') AS c),
    4,
    NULL,
    20260113000000,
    20260113000000,
    0,
    NULL
),
(
    (SELECT t.collection_tab_id FROM (SELECT collection_tab_id FROM lupo_collection_tabs WHERE slug = 'doctrine.cursor') AS t),
    1,
    'content',
    (SELECT c.content_id FROM (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-cursor-agent-coordination-doctrine') AS c),
    5,
    NULL,
    20260113000000,
    20260113000000,
    0,
    NULL
),
(
    (SELECT t.collection_tab_id FROM (SELECT collection_tab_id FROM lupo_collection_tabs WHERE slug = 'doctrine.cursor') AS t),
    1,
    'content',
    (SELECT c.content_id FROM (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-cursor-prompt-template-standard') AS c),
    6,
    NULL,
    20260113000000,
    20260113000000,
    0,
    NULL
),
(
    (SELECT t.collection_tab_id FROM (SELECT collection_tab_id FROM lupo_collection_tabs WHERE slug = 'doctrine.cursor') AS t),
    1,
    'content',
    (SELECT c.content_id FROM (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-cursor-multi-ide-workflow-doctrine') AS c),
    7,
    NULL,
    20260113000000,
    20260113000000,
    0,
    NULL
),
(
    (SELECT t.collection_tab_id FROM (SELECT collection_tab_id FROM lupo_collection_tabs WHERE slug = 'doctrine.cursor') AS t),
    1,
    'content',
    (SELECT c.content_id FROM (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-cursor-session-integrity-doctrine') AS c),
    8,
    NULL,
    20260113000000,
    20260113000000,
    0,
    NULL
),
(
    (SELECT t.collection_tab_id FROM (SELECT collection_tab_id FROM lupo_collection_tabs WHERE slug = 'doctrine.cursor') AS t),
    1,
    'content',
    (SELECT c.content_id FROM (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-cursor-output-sanitization-doctrine') AS c),
    9,
    NULL,
    20260113000000,
    20260113000000,
    0,
    NULL
),
(
    (SELECT t.collection_tab_id FROM (SELECT collection_tab_id FROM lupo_collection_tabs WHERE slug = 'doctrine.cursor') AS t),
    1,
    'content',
    (SELECT c.content_id FROM (SELECT content_id FROM lupo_contents WHERE slug = 'doctrine-cursor-error-recovery-doctrine') AS c),
    10,
    NULL,
    20260113000000,
    20260113000000,
    0,
    NULL
);
