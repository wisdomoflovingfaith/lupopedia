-- ======================================================================
-- Batch Insert: Lupopedia Channels 5100-5115
-- Generated: 2026-01-25
-- Purpose: Initialize core system channels for Lupopedia Semantic OS
-- ======================================================================

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
VALUES 
(
    1, 1, 1, 'lupopedia', 'lupopedia', 'chat_room', 'en',
    'Lupopedia', 'Primary Lupopedia knowledge and system channel.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5100, NULL, 0, NULL
),
(
    1, 1, 1, 'doctrine', 'doctrine', 'chat_room', 'en',
    'Doctrine', 'Canonical doctrine, rules, and system philosophy.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5101, NULL, 0, NULL
),
(
    1, 1, 1, 'kernel-logs', 'kernel-logs', 'chat_room', 'en',
    'Kernel Logs', 'System kernel logs, events, and internal diagnostics.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5102, NULL, 1, NULL
),
(
    1, 1, 1, 'migration-orchestrator', 'migration-orchestrator', 'chat_room', 'en',
    'Migration Orchestrator', 'State machine logs, transitions, and migration lifecycle.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5103, NULL, 1, NULL
),
(
    1, 1, 1, 'agents', 'agents', 'chat_room', 'en',
    'Agents / Hermes', 'Agent fleet coordination, Hermes routing, and multi-agent activity.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5104, NULL, 0, NULL
),
(
    1, 1, 1, 'emotional-metadata', 'emotional-metadata', 'chat_room', 'en',
    'Emotional Metadata', 'Emotional state tracking, affective signals, and metadata doctrine.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5105, NULL, 0, NULL
),
(
    1, 1, 1, 'system-events', 'system-events', 'chat_room', 'en',
    'System Events', 'Global system events, signals, and lifecycle notifications.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5106, NULL, 1, NULL
),
(
    1, 1, 1, 'hermes-routing', 'hermes-routing', 'chat_room', 'en',
    'Hermes Routing Logs', 'Routing decisions, message dispatch, and Hermes transport diagnostics.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5107, NULL, 1, NULL
),
(
    1, 1, 1, 'semantic-index', 'semantic-index', 'chat_room', 'en',
    'Semantic Index', 'Semantic graph, embeddings, and cross-channel indexing.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5108, NULL, 0, NULL
),
(
    1, 1, 1, 'kernel-debug', 'kernel-debug', 'chat_room', 'en',
    'Kernel Debug', 'Low-level kernel debugging, traces, and diagnostic output.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5109, NULL, 1, NULL
),
(
    1, 1, 1, 'pack-playground', 'pack-playground', 'chat_room', 'en',
    'Pack / Multi-Agent Playground', 'Experimental multi-agent interactions, Pack behavior, and collaborative testing.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5110, NULL, 0, NULL
),
(
    1, 1, 1, 'ui-creature', 'ui-creature', 'chat_room', 'en',
    'UI / Creature Interface', 'User interface, creature UI elements, and interactive display systems.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5111, NULL, 0, NULL
),
(
    1, 1, 1, 'doctrine-compiler', 'doctrine-compiler', 'chat_room', 'en',
    'Doctrine Compiler', 'Doctrine parsing, compilation, and system-wide doctrine enforcement.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5112, NULL, 1, NULL
),
(
    1, 1, 1, 'emotional-engine', 'emotional-engine', 'chat_room', 'en',
    'Emotional Engine', 'Affective computation, emotional geometry, and internal emotional modeling.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5113, NULL, 0, NULL
),
(
    1, 1, 1, 'semantic-router', 'semantic-router', 'chat_room', 'en',
    'Semantic Router', 'Semantic routing, intent classification, and cross-channel dispatch.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5114, NULL, 1, NULL
),
(
    1, 1, 1, 'kernel-panic', 'kernel-panic', 'chat_room', 'en',
    'Kernel Panic Archive', 'Critical kernel faults, panic dumps, and emergency diagnostic logs.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125192700, 20260125192700, 0, NULL, NULL, NULL,
    '4.0.72', 5115, NULL, 1, NULL
);

-- ======================================================================
-- Summary: 16 channels inserted (5100-5115)
-- Kernel channels: 7 (5102, 5103, 5106, 5107, 5109, 5112, 5114, 5115)
-- User channels: 9 (5100, 5101, 5104, 5105, 5108, 5110, 5111, 5113)
-- ======================================================================
