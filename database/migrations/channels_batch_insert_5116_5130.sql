-- ======================================================================
-- Safe Batch Insert: Lupopedia Channels 5116-5130
-- Generated: 2026-01-25
-- Purpose: Extend core system channels for Lupopedia Semantic OS
-- Handles duplicates via INSERT IGNORE
-- ======================================================================

INSERT IGNORE INTO lupo_channels (
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
-- 5116: Hermes Sandbox
(
    1,1,1,'hermes-sandbox','hermes-sandbox','chat_room','en',
    'Hermes Sandbox','Testing ground for Hermes routing, dispatch, and transport experiments.',
    NULL,'FFFFFF',1,NULL,NULL,20260125193700,20260125193700,0,NULL,NULL,NULL,
    '4.0.72',5116,NULL,0,NULL
),

-- 5117: Agent Training Grounds
(
    1,1,1,'agent-training','agent-training','chat_room','en',
    'Agent Training Grounds','Simulation space for agent learning, behavior shaping, and controlled trials.',
    NULL,'FFFFFF',1,NULL,NULL,20260125193700,20260125193700,0,NULL,NULL,NULL,
    '4.0.72',5117,NULL,0,NULL
),

-- 5118: Legacy Importer
(
    1,1,1,'legacy-importer','legacy-importer','chat_room','en',
    'Legacy Importer','Legacy system ingestion, compatibility layers, and migration tooling.',
    NULL,'FFFFFF',1,NULL,NULL,20260125193700,20260125193700,0,NULL,NULL,NULL,
    '4.0.72',5118,NULL,1,NULL
),

-- 5119: Emotional Debugger
(
    1,1,1,'emotional-debugger','emotional-debugger','chat_room','en',
    'Emotional Debugger','Debugging emotional metadata, affective signals, and internal emotional states.',
    NULL,'FFFFFF',1,NULL,NULL,20260125193700,20260125193700,0,NULL,NULL,NULL,
    '4.0.72',5119,NULL,0,NULL
),

-- 5120: Semantic Playground
(
    1,1,1,'semantic-playground','semantic-playground','chat_room','en',
    'Semantic Playground','Experimental semantic graph operations, embeddings, and prototype routing.',
    NULL,'FFFFFF',1,NULL,NULL,20260125193700,20260125193700,0,NULL,NULL,NULL,
    '4.0.72',5120,NULL,0,NULL
),

-- 5121: Kernel Metrics
(
    1,1,1,'kernel-metrics','kernel-metrics','chat_room','en',
    'Kernel Metrics','Performance metrics, throughput, load, and kernel instrumentation.',
    NULL,'FFFFFF',1,NULL,NULL,20260125193700,20260125193700,0,NULL,NULL,NULL,
    '4.0.72',5121,NULL,1,NULL
),

-- 5122: Agent Health Monitor
(
    1,1,1,'agent-health','agent-health','chat_room','en',
    'Agent Health Monitor','Agent uptime, health checks, diagnostics, and lifecycle monitoring.',
    NULL,'FFFFFF',1,NULL,NULL,20260125193700,20260125193700,0,NULL,NULL,NULL,
    '4.0.72',5122,NULL,0,NULL
),

-- 5123: Doctrine Validator
(
    1,1,1,'doctrine-validator','doctrine-validator','chat_room','en',
    'Doctrine Validator','Validation of doctrine rules, constraints, and compliance checks.',
    NULL,'FFFFFF',1,NULL,NULL,20260125193700,20260125193700,0,NULL,NULL,NULL,
    '4.0.72',5123,NULL,1,NULL
),

-- 5124: Emotional Archive
(
    1,1,1,'emotional-archive','emotional-archive','chat_room','en',
    'Emotional Archive','Historical emotional metadata, long-term affective storage, and analysis.',
    NULL,'FFFFFF',1,NULL,NULL,20260125193700,20260125193700,0,NULL,NULL,NULL,
    '4.0.72',5124,NULL,0,NULL
),

-- 5125: Semantic Diff Engine
(
    1,1,1,'semantic-diff','semantic-diff','chat_room','en',
    'Semantic Diff Engine','Semantic comparison, diffing, and cross-version semantic analysis.',
    NULL,'FFFFFF',1,NULL,NULL,20260125193700,20260125193700,0,NULL,NULL,NULL,
    '4.0.72',5125,NULL,1,NULL
),

-- 5126: Kernel Watchdog
(
    1,1,1,'kernel-watchdog','kernel-watchdog','chat_room','en',
    'Kernel Watchdog','Kernel safety monitoring, watchdog timers, and fault prevention.',
    NULL,'FFFFFF',1,NULL,NULL,20260125193700,20260125193700,0,NULL,NULL,NULL,
    '4.0.72',5126,NULL,1,NULL
),

-- 5127: Agent Persona Lab
(
    1,1,1,'persona-lab','persona-lab','chat_room','en',
    'Agent Persona Lab','Persona shaping, behavioral tuning, and agent identity experiments.',
    NULL,'FFFFFF',1,NULL,NULL,20260125193700,20260125193700,0,NULL,NULL,NULL,
    '4.0.72',5127,NULL,0,NULL
),

-- 5128: Semantic Stress Test
(
    1,1,1,'semantic-stress','semantic-stress','chat_room','en',
    'Semantic Stress Test','High-load semantic routing, graph pressure tests, and scaling trials.',
    NULL,'FFFFFF',1,NULL,NULL,20260125193700,20260125193700,0,NULL,NULL,NULL,
    '4.0.72',5128,NULL,1,NULL
),

-- 5129: Emotional Synthesis Lab
(
    1,1,1,'emotional-synthesis','emotional-synthesis','chat_room','en',
    'Emotional Synthesis Lab','Generation and synthesis of emotional states and affective patterns.',
    NULL,'FFFFFF',1,NULL,NULL,20260125193700,20260125193700,0,NULL,NULL,NULL,
    '4.0.72',5129,NULL,0,NULL
),

-- 5130: Kernel Recovery
(
    1,1,1,'kernel-recovery','kernel-recovery','chat_room','en',
    'Kernel Recovery','Recovery routines, kernel repair, and post-panic restoration.',
    NULL,'FFFFFF',1,NULL,NULL,20260125193700,20260125193700,0,NULL,NULL,NULL,
    '4.0.72',5130,NULL,1,NULL
);

-- ======================================================================
-- Summary: 15 additional channels inserted (5116-5130)
-- Kernel channels: 7 (5118, 5121, 5123, 5125, 5126, 5128, 5130)
-- User channels: 8 (5116, 5117, 5119, 5120, 5122, 5124, 5127, 5129)
-- Total channels in range 5100-5130: 31 channels
-- ======================================================================
