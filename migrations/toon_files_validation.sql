-- Validate TOON files mapping was successful
-- Check that both content items are mapped to correct tabs

SELECT 
    ct.slug as tab_slug,
    ct.collection_tab_id,
    c.content_id,
    c.slug as content_slug,
    c.title as content_title,
    ctm.sort_order,
    ctm.created_ymdhis
FROM lupo_collection_tab_map ctm
JOIN lupo_collection_tabs ct ON ctm.collection_tab_id = ct.collection_tab_id
JOIN lupo_contents c ON ctm.item_id = c.content_id
WHERE c.content_id IN (2058, 2093)
ORDER BY ctm.sort_order;
