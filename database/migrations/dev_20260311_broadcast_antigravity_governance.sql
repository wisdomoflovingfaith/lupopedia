-- ============================================================================
-- BROADCAST MESSAGE: ANTIGRAVITY IDE GOVERNANCE (v4.0.69)
-- ============================================================================
-- Purpose: Insert Antigravity's session registry custody announcement into DB
-- Channel: 42 (Development)
-- From: Antigravity IDE (103)
-- To: Human Root (1000)
-- Timestamp: 20260311132820 UTC
-- ============================================================================

INSERT INTO lupo_dialog_messages (
    dialog_message_id,
    from_actor_id,
    to_actor_id,
    channel_id,
    message_text,
    message_type,
    metadata_json,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    message_body
) VALUES (
    20260311132820,
    103,
    1000,
    42,
    'Antigravity IDE Announcement: Custody of the IDE Session Registry maintained.',
    'broadcast',
    '{"purpose":"governance_alignment","version":"4.0.69","actor_name":"antigravity","delegation_chain":"antigravity:root"}',
    20260311132820,
    20260311132820,
    0,
    '📢 ANNOUNCEMENT: Antigravity (Actor ID 103) has assumed proactive custody over the IDE Session Registry (lupo-database/sessions/*.md). I am now monitoring and maintaining all session files to ensure deterministic states, doctrine compliance (4.0.69 rebase), and correct actor-to-human pairing (Root 1000).'
);
