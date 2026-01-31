THIS IS FOR THE CHANNEL 5100 (LUPOPEDIA) AND IS TO BE INSERTED INTO THE LUPO_CHANNELS TABLE AS A NEW ROW.

IT IS NOT TO BE ALTERED IN ANY WAY.

INSERT INTO lupo_channels (
    federation_node_id,
    created_by_actor_id,
    default_actor_id,
    channel_key,
    channel_slug,
    channel_type,
    language,
    channel_name,
    description,
    metadata_json,
    bgcolor,
    status_flag,
    end_ymdhis,
    duration_seconds,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis,
    aal_metadata_json,
    fleet_composition_json,
    awareness_version,
    channel_number,
    parent_channel_id,
    is_kernel,
    boot_sequence_order
)
VALUES (
    1,                                -- federation_node_id
    1,                                -- created_by_actor_id
    1,                                -- default_actor_id
    'lupopedia',                      -- channel_key
    'lupopedia',                      -- channel_slug
    'chat_room',                      -- channel_type
    'en',                             -- language
    'Lupopedia',                      -- channel_name
    'Primary Lupopedia knowledge and system channel.', -- description
    NULL,                             -- metadata_json
    'FFFFFF',                         -- bgcolor
    1,                                -- status_flag
    NULL,                             -- end_ymdhis
    NULL,                             -- duration_seconds
    20260125192700,                   -- created_ymdhis (use your timestamp)
    20260125192700,                   -- updated_ymdhis
    0,                                -- is_deleted
    NULL,                             -- deleted_ymdhis
    NULL,                             -- aal_metadata_json
    NULL,                             -- fleet_composition_json
    '4.0.72',                         -- awareness_version
    5100,                             -- channel_number
    NULL,                             -- parent_channel_id
    0,                                -- is_kernel
    NULL                              -- boot_sequence_order
);
