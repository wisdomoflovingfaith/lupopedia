INSERT INTO lupo_dialog_threads (
    dialog_thread_id,
    federation_node_id,
    channel_id,
    project_slug,
    task_name,
    created_by_actor_id,
    summary_text,
    status,
    artifacts,
    metadata_json,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
) VALUES (
    NULL,
    1,
    9000,
    'gov-constitutional-log',
    'GOVERNANCE LOG — Constitutional Record',
    1,
    'The canonical governance log for constitutional events, doctrine changes, approvals, kapu rulings, and system-wide governance actions.',
    'Open',
    NULL,
    JSON_OBJECT(
        'thread_slug', 'gov-constitutional-log',
        'bg_color', '000000',
        'text_color', 'FFFFFF',
        'alt_text_color', 'CCCCCC',
        'default_actor_id', 1,
        'status_flag', 1
    ),
    CAST(DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP()), '%Y%m%d%H%i%S') AS UNSIGNED),
    CAST(DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP()), '%Y%m%d%H%i%S') AS UNSIGNED),
    0,
    NULL
);
