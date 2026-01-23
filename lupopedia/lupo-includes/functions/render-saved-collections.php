<?php
/**
 * Render Saved Collections Navigation
 * 
 * Loads data from the database and builds nested arrays for the saved collections navigation.
 * This function queries lupo_collections, lupo_collection_tabs, and lupo_collection_tab_map
 * to build the data structure needed by the component template.
 * 
 * IMPORTANT: Uses lupo_permissions as the ONLY source of truth for access control.
 * Only returns collections the user has permission to read (via user_id or group_id).
 * Uses target_type='collection' and target_id to identify collection permissions.
 * The user_id and group_id fields in lupo_collections are metadata only, not access control.
 * 
 * @param int $userId The current user ID
 * @return array Array structure for the component template
 */
function render_saved_collections($userId) {
    // Check if database connection exists
    if (!isset($GLOBALS['mydatabase'])) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    $collectionsData = [];
    
    try {
        // For viewing: always return all collections (permissions apply to editing only)
        // For userId = 0 (not logged in), return all collections for public viewing
        if ($userId == 0) {
            // Return all collections for public viewing
            $sql = "SELECT DISTINCT c.id, c.name, c.type
                    FROM lupo_collections c
                    ORDER BY c.type, c.name";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $collections = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // For logged-in users: filter by permissions (for editing control)
            // Step 1: Get user's group IDs (for checking group-based permissions)
            $sql = "SELECT group_id 
                    FROM lupo_actor_group_membership 
                    WHERE actor_group_membership_id = :user_id 
                    AND is_active = 1";
            $stmt = $db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            $userGroups = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Build permission check query - collections the user has access to via permissions
            // Check both direct user permissions and group permissions
            // lupo_permissions is the ONLY source of truth for access control (polymorphic table)
            $params = [':user_id' => $userId, ':target_type' => 'collection'];
            $whereParts = ["(cp.user_id = :user_id AND cp.group_id IS NULL AND cp.target_type = :target_type)"];
            
            // Add group-based permissions if user belongs to groups
            if (!empty($userGroups)) {
                $groupPlaceholders = [];
                foreach ($userGroups as $idx => $groupId) {
                    $key = ':group_id_' . $idx;
                    $groupPlaceholders[] = $key;
                    $params[$key] = $groupId;
                }
                if (!empty($groupPlaceholders)) {
                    $whereParts[] = "(cp.user_id IS NULL AND cp.group_id IN (" . implode(',', $groupPlaceholders) . ") AND cp.target_type = :target_type)";
                }
            }
            
            // Query collections that user has permission to read
            // Uses polymorphic permissions table with target_type='collection' and target_id=c.id
            $sql = "SELECT DISTINCT c.id, c.name, c.type
                    FROM lupo_collections c
                    INNER JOIN lupo_permissions cp ON c.id = cp.target_id AND cp.target_type = :target_type
                    WHERE (" . implode(' OR ', $whereParts) . ")
                    ORDER BY c.type, c.name";
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $collections = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        // Step 2: For each collection, load its top-level tabs
        foreach ($collections as $collection) {
            $collectionId = $collection['id'];
            $collectionType = $collection['type'];
            
            // Load top-level tabs for this collection
            $sql = "SELECT id, tab_name, sort_order 
                    FROM lupo_collection_tabs 
                    WHERE collection_id = :collection_id 
                    ORDER BY sort_order, tab_name";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([':collection_id' => $collectionId]);
            $tabs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Step 3: For each tab, load its children from collection_tab_map
            $tabsWithChildren = [];
            foreach ($tabs as $tab) {
                $tabId = $tab['id'];
                $children = load_tab_children($db, $tabId);
                
                // Count items (content and links, not nested tabs)
                $itemCount = 0;
                foreach ($children as $child) {
                    if ($child['item_type'] === 'content' || $child['item_type'] === 'link') {
                        $itemCount++;
                    } else {
                        // For nested tabs, count their items recursively
                        $itemCount += count_tab_items($db, $child['item_id']);
                    }
                }
                
                $tabsWithChildren[] = [
                    'id' => $tab['id'],
                    'tab_name' => $tab['tab_name'],
                    'sort_order' => $tab['sort_order'],
                    'children' => $children,
                    'item_count' => $itemCount
                ];
            }
            
            // Count total items in this collection type
            $totalCount = 0;
            foreach ($tabsWithChildren as $tab) {
                $totalCount += $tab['item_count'];
            }
            
            // Store collection data keyed by type
            if (!isset($collectionsData[$collectionType])) {
                $collectionsData[$collectionType] = [
                    'type' => $collectionType,
                    'count' => 0,
                    'tabs' => []
                ];
            }
            
            // Merge tabs from all collections of this type
            $collectionsData[$collectionType]['tabs'] = array_merge(
                $collectionsData[$collectionType]['tabs'],
                $tabsWithChildren
            );
            $collectionsData[$collectionType]['count'] += $totalCount;
        }
        
        // Sort tabs within each collection type by sort_order
        foreach ($collectionsData as &$collectionTypeData) {
            usort($collectionTypeData['tabs'], function($a, $b) {
                return $a['sort_order'] <=> $b['sort_order'];
            });
        }
        unset($collectionTypeData);
        
    } catch (PDOException $e) {
        // On error, return empty array
        error_log("Error rendering saved collections: " . $e->getMessage());
        return [];
    }
    
    return $collectionsData;
}

/**
 * Load children of a tab from collection_tab_map
 * 
 * Recursively loads tab children, handling nested tabs, content items, and links.
 * This is a polymorphic mapping system where item_type determines the item structure.
 * 
 * @param PDO $db Database connection
 * @param int $tabId The tab ID
 * @return array Array of child items with their data loaded
 */
function load_tab_children($db, $tabId) {
    $sql = "SELECT id, item_type, item_id, sort_order, properties 
            FROM lupo_collection_tab_map 
            WHERE collection_tab_id = :tab_id 
            ORDER BY sort_order";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([':tab_id' => $tabId]);
    $mappings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $children = [];
    foreach ($mappings as $mapping) {
        $itemType = $mapping['item_type'];
        $itemId = $mapping['item_id'];
        $properties = $mapping['properties'] ? json_decode($mapping['properties'], true) : [];
        
        $child = [
            'id' => $mapping['id'],
            'item_type' => $itemType,
            'item_id' => $itemId,
            'sort_order' => $mapping['sort_order'],
            'properties' => $properties
        ];
        
        // Load item details based on type
        if ($itemType === 'tab') {
            // Load the sub-tab from collection_tabs
            $sql = "SELECT id, tab_name, sort_order 
                    FROM lupo_collection_tabs 
                    WHERE id = :tab_id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':tab_id' => $itemId]);
            $subTab = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($subTab) {
                $child['tab_name'] = $subTab['tab_name'];
                $child['tab_id'] = $subTab['id'];
                
                // Load children of this sub-tab recursively
                $child['children'] = load_tab_children($db, $itemId);
                
                // Count items in this sub-tab
                $child['item_count'] = count_tab_items($db, $itemId);
            }
            
        } elseif ($itemType === 'content') {
            // Load content title from lupo_content
            $sql = "SELECT id, title 
                    FROM lupo_content 
                    WHERE id = :content_id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':content_id' => $itemId]);
            $content = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($content) {
                $child['title'] = $content['title'];
                $child['content_id'] = $content['id'];
            }
            
        } elseif ($itemType === 'link') {
            // Load link data from properties JSON
            $child['url'] = $properties['url'] ?? '#';
            $child['label'] = $properties['label'] ?? 'Link';
        }
        
        $children[] = $child;
    }
    
    return $children;
}

/**
 * Count items in a tab recursively (excluding nested tabs themselves)
 * 
 * Counts only content items and links, not the nested tabs themselves.
 * Recursively counts items within nested tabs.
 * 
 * @param PDO $db Database connection
 * @param int $tabId The tab ID
 * @return int Count of items (content + links, recursively including nested tabs)
 */
function count_tab_items($db, $tabId) {
    $sql = "SELECT item_type, item_id 
            FROM lupo_collection_tab_map 
            WHERE collection_tab_id = :tab_id";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([':tab_id' => $tabId]);
    $mappings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $count = 0;
    foreach ($mappings as $mapping) {
        if ($mapping['item_type'] === 'content' || $mapping['item_type'] === 'link') {
            $count++;
        } elseif ($mapping['item_type'] === 'tab') {
            // Recursively count items in nested tab
            $count += count_tab_items($db, $mapping['item_id']);
        }
    }
    
    return $count;
}
