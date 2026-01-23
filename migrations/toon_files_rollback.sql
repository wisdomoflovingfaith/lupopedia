-- Rollback TOON files mapping
-- Remove the two mapping entries if needed

DELETE FROM lupo_collection_tab_map 
WHERE item_id IN (2058, 2093)
AND item_type = 'content'
AND federations_node_id = 1;
