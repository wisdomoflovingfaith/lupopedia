/* ---------------------------------------------------------
   Lupopedia Kernel Seeds (Prefix-Aware)
   Installer replaces {{prefix}} with actual table prefix.
   --------------------------------------------------------- */

/* ---------------------------------------------------------
   1. Kernel Domain (domain_id = 0)
   --------------------------------------------------------- */
INSERT INTO `{{prefix}}domains` (
    domain_id,
    domain,
    domain_base,
    display_name,
    description,
    contact_email,
    settings,
    is_active,
    last_seen_ymdhis,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis,
    is_federated,
    default_department,
    federation_key,
    last_synced_ymdhis
) VALUES (
    0,
    'lupopedia.com',
    'lupopedia.com',
    'Lupopedia',
    'Primary domain for the Lupopedia semantic OS.',
    'admin@lupopedia.com',
    JSON_OBJECT('purpose','root-domain','protected',TRUE,'auto_created',TRUE),
    1,
    NULL,
    20260106085500,
    20260106085500,
    0,
    NULL,
    0,
    NULL,
    NULL,
    NULL
);

/* ---------------------------------------------------------
   2. Kernel Channel (channel_id = 0)
   --------------------------------------------------------- */
INSERT INTO `{{prefix}}channels` (
    channel_id,
    domain_id,
    agent_id,
    channel_key,
    channel_name,
    description,
    metadata_json,
    status_flag,
    start_ymdhis,
    end_ymdhis,
    message_count,
    duration_seconds,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
) VALUES (
    0,
    0,
    1,
    'system/kernel',
    'System Kernel Channel',
    'Reserved channel for bootstrapping, migrations, and OS-level events.',
    JSON_OBJECT('purpose','kernel','protected',TRUE,'auto_created',TRUE),
    1,
    20260106084500,
    NULL,
    0,
    NULL,
    20260106084500,
    20260106084500,
    0,
    NULL
);

/* ---------------------------------------------------------
   3. Kernel Actor (actor_id = 0)
   --------------------------------------------------------- */
INSERT INTO `{{prefix}}actors` (
    actor_id,
    actor_type,
    slug,
    name,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted,
    deleted_ymdhis,
    actor_source_id,
    actor_source_type,
    metadata
) VALUES (
    0,
    'service',
    'system-kernel',
    'System Kernel Actor',
    20260106085000,
    20260106085000,
    1,
    0,
    NULL,
    NULL,
    'system',
    JSON_OBJECT(
        'purpose','kernel',
        'description','Represents the Lupopedia OS itself.',
        'protected',TRUE,
        'version','1.0.0'
    )
);

/* ---------------------------------------------------------
   4. Kernel Actor → Kernel Channel Link
   --------------------------------------------------------- */
INSERT INTO `{{prefix}}actor_channels` (
    actor_channel_id,
    actor_id,
    channel_id,
    status,
    start_date,
    bg_color,
    text_color,
    channel_color,
    alt_text_color,
    last_read_ymdhis,
    muted_until_ymdhis,
    preferences_json,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
) VALUES (
    2,
    0,
    0,
    'A',
    20260106090000,
    '000000',
    'FFFFFF',
    'F7FAFF',
    '000000',
    NULL,
    NULL,
    JSON_OBJECT('notifications',JSON_OBJECT('enabled',FALSE),'ui',JSON_OBJECT('theme','kernel')),
    20260106090000,
    20260106090000,
    0,
    NULL
);

/* ---------------------------------------------------------
   5. Kernel Thread (dialog_threads_id = 2)
   --------------------------------------------------------- */
INSERT INTO `{{prefix}}dialog_threads` (
    dialog_threads_id,
    domain_id,
    channel_id,
    conversation_id,
    project_slug,
    task_name,
    thread_date,
    agent_name,
    summary_text,
    status,
    artifacts,
    wolfie_json,
    created_at,
    updated_at
) VALUES (
    2,
    0,
    0,
    'kernel_boot_0001',
    'system',
    'kernel_bootstrap',
    NOW(),
    'System Kernel Actor',
    'Kernel boot sequence initialized.',
    'Ongoing',
    NULL,
    JSON_OBJECT(
        'wolfie_header',JSON_OBJECT('version','4.0.0','speaker','System Kernel Actor','message','Bootstrapping Lupopedia kernel.'),
        'agent_intent',JSON_OBJECT('actor_id',0,'agent_name','System Kernel Actor','role','kernel','action','bootstrap','channel','system/kernel','scope',JSON_ARRAY('migrations/','bootstrap/'),'internal',TRUE),
        'channels',JSON_ARRAY('system/kernel')
    ),
    NOW(),
    NOW()
);

/* ---------------------------------------------------------
   6. Kernel Boot Message (dialog_message_id = 2)
   --------------------------------------------------------- */
INSERT INTO `{{prefix}}dialog_messages` (
    dialog_message_id,
    dialog_threads_id,
    domain_id,
    channel_id,
    from_actor,
    to_actor,
    agent_id,
    agent_name,
    target,
    message_text,
    wolfie_json,
    message_type,
    is_broadcast,
    metadata_json,
    in_reply_to,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
) VALUES (
    2,
    2,
    0,
    0,
    0,
    NULL,
    NULL,
    'System Kernel Actor',
    '@system',
    'Lupopedia kernel boot sequence started. Initializing core subsystems.',
    JSON_OBJECT('agent_intent',JSON_OBJECT('actor_id',0,'agent_name','System Kernel Actor','role','kernel','action','announce_boot','channel','system/kernel','scope',JSON_ARRAY('bootstrap/'),'internal',TRUE)),
    'system',
    1,
    NULL,
    NULL,
    20260106090500,
    20260106090500,
    0,
    NULL
);

/* ---------------------------------------------------------
   7. Kernel Subsystem Initialization Message (dialog_message_id = 3)
   --------------------------------------------------------- */
INSERT INTO `{{prefix}}dialog_messages` (
    dialog_message_id,
    dialog_threads_id,
    domain_id,
    channel_id,
    from_actor,
    to_actor,
    agent_id,
    agent_name,
    target,
    message_text,
    wolfie_json,
    message_type,
    is_broadcast,
    metadata_json,
    in_reply_to,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
) VALUES (
    3,
    2,
    0,
    0,
    0,
    NULL,
    NULL,
    'System Kernel Actor',
    '@system',
    'Initializing core subsystems: registry, migrations, refactors, agent loader, and semantic indexer.',
    JSON_OBJECT(
        'agent_intent',JSON_OBJECT(
            'actor_id',0,
            'agent_name','System Kernel Actor',
            'role','kernel',
            'action','initialize_subsystems',
            'channel','system/kernel',
            'scope',JSON_ARRAY('registry/','migrations/','refactors/','agent_loader/','semantic_indexer/'),
            'internal',TRUE
        )
    ),
    'system',
    1,
    NULL,
    2,
    20260106091000,
    20260106091000,
    0,
    NULL
);
