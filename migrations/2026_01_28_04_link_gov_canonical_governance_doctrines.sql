INSERT INTO lupo_edges (
    left_object_type,
    left_object_id,
    right_object_type,
    right_object_id,
    edge_type,
    channel_id,
    channel_key,
    weight_score,
    sort_num,
    actor_id,
    is_deleted,
    deleted_ymdhis,
    created_ymdhis,
    updated_ymdhis,
    semantic_weight,
    relationship_type,
    bidirectional,
    context_scope
)
SELECT
    'channel',
    9000,
    'content',
    c.content_id,
    'HAS_CONTENT',
    9000,
    'gov',
    0,
    0,
    1,
    0,
    0,
    CAST(DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP()), '%Y%m%d%H%i%S') AS UNSIGNED),
    CAST(DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP()), '%Y%m%d%H%i%S') AS UNSIGNED),
    0.00,
    'semantic',
    0,
    NULL
FROM lupo_contents c
WHERE c.slug IN (
    'doctrine-ai-integration-safety-doctrine',
    'doctrine-gov-prohibit-001',
    'doctrine-system-agent-safety-doctrine'
)
AND NOT EXISTS (
    SELECT 1
    FROM lupo_edges e
    WHERE e.left_object_type = 'channel'
      AND e.left_object_id = 9000
      AND e.right_object_type = 'content'
      AND e.right_object_id = c.content_id
      AND e.edge_type = 'HAS_CONTENT'
);
