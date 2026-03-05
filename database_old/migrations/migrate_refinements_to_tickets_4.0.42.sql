-- MIGRATE lupo_doctrine_refinements INTO THE TICKET SYSTEM (Version 4.0.42)
-- Every doctrine refinement becomes a ticket on channel 42 (Development).

INSERT INTO lupo_tickets (ticket_id, channel_id, actor_id, status, priority, subject, created_ymdhis, updated_ymdhis, metadata_json)
SELECT 
    doctrine_refinement_id, 
    42, 
    1, 
    CASE 
        WHEN approval_status = 'approved' THEN 'closed'
        WHEN approval_status = 'rejected' THEN 'closed'
        ELSE 'open'
    END,
    'medium',
    CONCAT('Doctrine Refinement: ', refinement_type, ' for ', doctrine_file_path),
    created_ymdhis,
    COALESCE(applied_ymdhis, created_ymdhis),
    JSON_OBJECT(
        'cip_event_id', cip_event_id,
        'doctrine_file_path', doctrine_file_path,
        'refinement_type', refinement_type,
        'before_content_hash', before_content_hash,
        'after_content_hash', after_content_hash,
        'approved_by', approved_by,
        'refinement_version', refinement_version
    )
FROM lupo_doctrine_refinements;

INSERT INTO lupo_ticket_messages (ticket_message_id, ticket_id, actor_id, message_text, created_ymdhis)
SELECT 
    doctrine_refinement_id + 1000000, -- Offset to avoid collision if necessary
    doctrine_refinement_id,
    1, 
    change_description,
    created_ymdhis
FROM lupo_doctrine_refinements;

-- Note: lupo_doctrine_refinements table is now deprecated and can be dropped after verification.
