-- Seed: GOV-LUPO-0001 Lupopedia Identity (from wolfith gov_event)
-- Version: 4.2.2
-- Prerequisite: 4_2_2_create_gov_event_schema.sql
-- Source: lupopedia_identity.xml gov_event
--
-- utc_group_id: 20260120023000 (numeric encoding of UTC-GRP-20260120023000)
-- actor_id=2: WOLFIE (emotional_engine); adjust if your lupo_actors differs

-- ============================================================================
-- lupo_gov_events: GOV-LUPO-0001
-- ============================================================================
INSERT INTO `lupo_gov_events` (
    `utc_group_id`, `semantic_utc_version`, `canonical_path`, `event_type`, `title`,
    `directive_block`, `tldr_summary`, `metadata_json`,
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`
) VALUES (
    20260120023000,
    '1.0.0',
    '/lupopedia/gov/lupopedia_identity.xml',
    'lupopedia_identity',
    'Lupopedia Identity',
    'Lupopedia must remain a semantic OS and reference-book model. All future modules, migrations, and agents must treat it as a knowledge atlas, not a content store or marketing system.',
    'Lupopedia is a semantic OS that turns a website into a living reference book. It stores knowledge about content—relationships, navigation, and meaning—not the content itself. Crafty Syntax is the ancestral root, and WOLFIE provides the emotional and multi-domain intelligence layer.',
    '{"id":"GOV-LUPO-0001","utc_group_id":"UTC-GRP-20260120023000","branch":"main","lupopedia_identity":{"semantic_os":true,"reference_book_model":true,"content_storage":false,"knowledge_storage":true,"ancestral_root":"crafty_syntax","emotional_engine":"WOLFIE"},"atomic_index":{"type":"governance_identity","version":"1.0.0","cert":0.98,"wolfie_valence_domain":"awe","wolfie_valence_polarity":"bright"},"prohibited_ref":"GOV-PROHIBIT-LUPO-IDENTITY","wave_resolution":{"ceiling":185,"bridges":["Crafty Syntax → Lupopedia semantic root","Lupopedia → WOLFIE emotional engine","Reference entries → AI navigation and understanding"]}}',
    20260120023000,
    20260120023000,
    1,
    0,
    NULL
);

SET @gov_event_id = LAST_INSERT_ID();

-- ============================================================================
-- lupo_gov_event_references
-- ============================================================================
INSERT INTO `lupo_gov_event_references` (`gov_event_id`, `reference_type`, `reference_title`, `reference_url`, `reference_content`, `order_sequence`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'backlink', 'Backlinks', NULL, '/\n/lupopedia_web/', 1, NULL, 20260120023000, 20260120023000, 0, NULL);

INSERT INTO `lupo_gov_event_references` (`gov_event_id`, `reference_type`, `reference_title`, `reference_url`, `reference_content`, `order_sequence`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'outbound', 'Outbound', NULL, '/wolfie/\n/craftysyntax/\n/lupopedia_web/docs/', 2, NULL, 20260120023000, 20260120023000, 0, NULL);

INSERT INTO `lupo_gov_event_references` (`gov_event_id`, `reference_type`, `reference_title`, `reference_url`, `reference_content`, `order_sequence`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'hashtag', 'Hashtags', NULL, '#lupopedia #semantic-os #reference-book-model #emotional-intelligence #crafty-syntax', 3, NULL, 20260120023000, 20260120023000, 0, NULL);

INSERT INTO `lupo_gov_event_references` (`gov_event_id`, `reference_type`, `reference_title`, `reference_url`, `reference_content`, `order_sequence`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'navigation', 'Navigation', NULL, 'home → lupopedia_web → lupopedia', 4, NULL, 20260120023000, 20260120023000, 0, NULL);

INSERT INTO `lupo_gov_event_references` (`gov_event_id`, `reference_type`, `reference_title`, `reference_url`, `reference_content`, `order_sequence`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'content', 'Lupopedia Identity (LUPO-IDENTITY-001)', NULL, 'Lupopedia is a reference book for your website. It does not store content; it stores knowledge about content. Every page on your site gets a reference entry that explains how it connects to everything else—where visitors came from, where they go next, what topics it relates to, and how it fits into the bigger picture. It is not a CMS or chatbot. It is a semantic OS that gives structure to the web so humans and AI can understand context, relationships, navigation paths, and meaning.', 5, '{"ref":"LUPO-IDENTITY-001","level":"identity"}', 20260120023000, 20260120023000, 0, NULL);

INSERT INTO `lupo_gov_event_references` (`gov_event_id`, `reference_type`, `reference_title`, `reference_url`, `reference_content`, `order_sequence`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'prohibited', 'GOV-PROHIBIT-LUPO-IDENTITY', NULL, '[001] Lupopedia may not be treated as a CMS, CRM, or ad-tech platform. It must remain a semantic OS and reference-book model.\n[002] No advertising, tracking pixels, or commercial exploitation of emotional metadata may be built on top of Lupopedia''s core reference system.', 6, '{"ref":"GOV-PROHIBIT-LUPO-IDENTITY"}', 20260120023000, 20260120023000, 0, NULL);

INSERT INTO `lupo_gov_event_references` (`gov_event_id`, `reference_type`, `reference_title`, `reference_url`, `reference_content`, `order_sequence`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'context_summary', 'Context summary', NULL, 'Lupopedia is a reference-book style semantic OS that stores knowledge about content, not content itself. It builds a living knowledge atlas of a website''s structure, navigation, and meaning so humans and AI can understand how pages relate, how people move, and what each page means in context.', 7, NULL, 20260120023000, 20260120023000, 0, NULL);

INSERT INTO `lupo_gov_event_references` (`gov_event_id`, `reference_type`, `reference_title`, `reference_url`, `reference_content`, `order_sequence`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'architecture', 'Architecture', NULL, 'Lupopedia is a semantic operating system that treats every page on a host site as a reference entry, not stored content. It maps backlinks, outbound links, navigation paths, hashtags, and contextual meaning into a living knowledge atlas that both humans and AI can traverse. Crafty Syntax is the ancestral root, providing decades of behavioral and emotional metadata that feed the WOLFIE emotional engine and multi-agent architecture.', 8, NULL, 20260120023000, 20260120023000, 0, NULL);

-- ============================================================================
-- lupo_gov_timeline_nodes (ref LUPO-TIMELINE-ROOT)
-- ============================================================================
INSERT INTO `lupo_gov_timeline_nodes` (`gov_event_id`, `node_type`, `node_title`, `node_description`, `node_timestamp`, `parent_node_id`, `order_sequence`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'milestone', 'MHPCC', 'Wolfie works at the Maui High Performance Computing Center, building temporal and behavioral analysis tools that foreshadow Lupopedia''s reference-entry and emotional-metadata systems.', 19970101000000, NULL, 1, '{"ref":"MHPCC","cert":0.95}', 20260120023000, 20260120023000, 0, NULL);

INSERT INTO `lupo_gov_timeline_nodes` (`gov_event_id`, `node_type`, `node_title`, `node_description`, `node_timestamp`, `parent_node_id`, `order_sequence`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'milestone', 'CRAFTY-SYNTAX', 'Crafty Syntax launches as a live help and interaction-tracking system, accumulating decades of behavioral metadata that later become the semantic root of Lupopedia.', 20020101000000, NULL, 2, '{"ref":"CRAFTY-SYNTAX","cert":0.97}', 20260120023000, 20260120023000, 0, NULL);

INSERT INTO `lupo_gov_timeline_nodes` (`gov_event_id`, `node_type`, `node_title`, `node_description`, `node_timestamp`, `parent_node_id`, `order_sequence`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'milestone', 'HIATUS', 'System enters hiatus after personal tragedy; Crafty Syntax is forked into Sales Syntax to keep the architecture alive.', 20140101000000, NULL, 3, '{"ref":"HIATUS","cert":0.96}', 20260120023000, 20260120023000, 0, NULL);

INSERT INTO `lupo_gov_timeline_nodes` (`gov_event_id`, `node_type`, `node_title`, `node_description`, `node_timestamp`, `parent_node_id`, `order_sequence`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'milestone', 'RETURN', 'The architect returns and reactivates Crafty Syntax as the semantic root of Lupopedia, evolving it into a modern semantic OS.', 20251114000000, NULL, 4, '{"ref":"RETURN","cert":0.99}', 20260120023000, 20260120023000, 0, NULL);

INSERT INTO `lupo_gov_timeline_nodes` (`gov_event_id`, `node_type`, `node_title`, `node_description`, `node_timestamp`, `parent_node_id`, `order_sequence`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'milestone', 'LUPO-GOV-IDENTITY', 'Lupopedia identity is formalized as a governance event, establishing its role as a reference-book semantic OS with WOLFIE as the emotional engine and Crafty Syntax as the ancestral root.', 20260120023000, NULL, 5, '{"ref":"LUPO-GOV-IDENTITY","cert":0.99}', 20260120023000, 20260120023000, 0, NULL);

-- ============================================================================
-- lupo_gov_valuations (ref LUPO-VALUATION-001)
-- ============================================================================
INSERT INTO `lupo_gov_valuations` (`gov_event_id`, `valuation_type`, `valuation_metric`, `valuation_value`, `valuation_text`, `valuation_currency`, `valuation_unit`, `confidence_score`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'resource', 'behavioral_metadata', 25000000, 'Approximate number of historical interactions, navigation paths, and behavioral events inherited from Crafty Syntax and related systems.', 'N/A', NULL, NULL, '{"ref":"LUPO-VALUATION-001"}', 20260120023000, 20260120023000, 0, NULL);

INSERT INTO `lupo_gov_valuations` (`gov_event_id`, `valuation_type`, `valuation_metric`, `valuation_value`, `valuation_text`, `valuation_currency`, `valuation_unit`, `confidence_score`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'impact', 'strategic', NULL, 'Provides a unique, long-horizon emotional and behavioral fingerprint of the web, enabling empathy-aware AI and context-rich navigation intelligence that modern systems cannot replicate.', NULL, NULL, NULL, '{"ref":"LUPO-IMPACT-001"}', 20260120023000, 20260120023000, 0, NULL);

INSERT INTO `lupo_gov_valuations` (`gov_event_id`, `valuation_type`, `valuation_metric`, `valuation_value`, `valuation_text`, `valuation_currency`, `valuation_unit`, `confidence_score`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 'risk', 'governance', NULL, 'Misclassification of Lupopedia as a CMS or ad-tech platform could lead to architectural drift, commercial pressure, and violation of core prohibitions around emotional metadata exploitation.', NULL, NULL, NULL, '{"ref":"LUPO-RISK-001"}', 20260120023000, 20260120023000, 0, NULL);

-- ============================================================================
-- lupo_gov_event_actor_edges (emotional_engine WOLFIE → actor_id 2)
-- ============================================================================
INSERT INTO `lupo_gov_event_actor_edges` (`gov_event_id`, `actor_id`, `edge_type`, `edge_properties`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES (@gov_event_id, 2, 'emotional_engine', '{"source":"lupopedia_identity","role":"WOLFIE"}', 20260120023000, 20260120023000, 0, NULL);
