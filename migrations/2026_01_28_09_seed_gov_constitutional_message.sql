INSERT INTO lupo_dialog_messages (
    dialog_message_id,
    dialog_thread_id,
    channel_id,
    from_actor_id,
    to_actor_id,
    message_text,
    message_type,
    metadata_json,
    mood_rgb,
    weight,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    NULL,
    t.dialog_thread_id,
    9000,
    1,
    1,
    'Day 0: Governance constitutional log initialized. This thread records doctrine changes, approvals, kapu rulings, and system-wide governance actions.',
    'system',
    JSON_OBJECT(
        'event', 'gov-constitutional-init',
        'thread_slug', 'gov-constitutional-log'
    ),
    '666666',
    1.00,
    CAST(DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP()), '%Y%m%d%H%i%S') AS UNSIGNED),
    CAST(DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP()), '%Y%m%d%H%i%S') AS UNSIGNED),
    0,
    NULL
FROM lupo_dialog_threads t
WHERE t.channel_id = 9000
  AND t.project_slug = 'gov-constitutional-log'
  AND NOT EXISTS (
      SELECT 1
      FROM lupo_dialog_messages m
      WHERE m.dialog_thread_id = t.dialog_thread_id
        AND m.message_text = 'Day 0: Governance constitutional log initialized. This thread records doctrine changes, approvals, kapu rulings, and system-wide governance actions.'
  );
