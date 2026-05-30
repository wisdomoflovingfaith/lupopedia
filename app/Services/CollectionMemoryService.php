<?php
/**
 * Collection Memory Service
 * 
 * Creates and manages memory nodes for collections to enable graph traversal.
 * Implements PRD 73 §8 sync strategy for human UI collections ↔ AI memory collections.
 * 
 * @author Lupopedia Development Team
 * @version 4.0.89
 */

namespace App\Services;

use App\Database\DatabaseFactory;
use Exception;

class CollectionMemoryService
{
    private $db;
    private $memoryService;
    
    public function __construct(DatabaseFactory $db, MemoryService $memoryService)
    {
        $this->db = $db;
        $this->memoryService = $memoryService;
    }
    
    /**
     * Create memory node for a collection
     * 
     * @param int $collectionId
     * @return int Memory node ID
     */
    public function createCollectionMemoryNode(int $collectionId): int
    {
        // Get collection details
        $collection = $this->db->fetchOne(
            "SELECT * FROM lupo_collections WHERE collection_id = ? AND is_deleted = 0",
            [$collectionId]
        );
        
        if (!$collection) {
            throw new Exception("Collection not found: $collectionId");
        }
        
        // Check if memory node already exists
        $existingNode = $this->db->fetchOne(
            "SELECT memory_node_id FROM lupo_memory_nodes
             WHERE memory_toon = ? AND is_deleted = 0",
            ["collection_{$collectionId}"]
        );
        
        if ($existingNode) {
            return $existingNode['memory_node_id'];
        }
        
        // Create memory node
        $memoryNodeId = $this->memoryService->createNode([
            'memory_toon' => "collection_{$collectionId}",
            'memory_type' => 'collection',
            'owner_actor_id' => $collection['actor_id'],
            'channel_key' => $collection['channel_key'] ?? 'development',
            'trust_tier' => 'canonical',
            'properties_json' => json_encode([
                'collection_id' => $collection['collection_id'],
                'name' => $collection['name'],
                'slug' => $collection['slug'],
                'color' => $collection['color'],
                'description' => $collection['description'],
                'sort_order' => $collection['sort_order'],
                'is_nav_menu' => $collection['is_nav_menu'],
                'nav_icon' => $collection['nav_icon'],
                'parent_id' => $collection['parent_id'],
                'created_ymdhis' => $collection['created_ymdhis'],
                'updated_ymdhis' => $collection['updated_ymdhis']
            ])
        ]);
        
        // Create parent relationship if this collection has a parent
        if ($collection['parent_id']) {
            $this->createParentRelationship($collectionId, $collection['parent_id']);
        }
        
        // Sync existing items to memory edges
        $this->syncCollectionItems($collectionId, $memoryNodeId);
        
        return $memoryNodeId;
    }
    
    /**
     * Create parent-child relationship between collections
     * 
     * @param int $childCollectionId
     * @param int $parentCollectionId
     */
    public function createParentRelationship(int $childCollectionId, int $parentCollectionId): void
    {
        $childNodeKey = "collection_{$childCollectionId}";
        $parentNodeKey = "collection_{$parentCollectionId}";
        
        $childNodeId = $this->memoryService->getNodeIdByKey($childNodeKey);
        $parentNodeId = $this->memoryService->getNodeIdByKey($parentNodeKey);
        
        if (!$childNodeId || !$parentNodeId) {
            throw new Exception("Memory nodes not found for collections");
        }
        
        // Create parent relationship edge
        $this->memoryService->createEdge([
            'edge_type' => 'parent_collection',
            'from_memory_node_id' => $childNodeId,
            'to_memory_node_id' => $parentNodeId,
            'weight' => 1.0,
            'context_json' => json_encode([
                'collection_hierarchy' => true,
                'child_collection_id' => $childCollectionId,
                'parent_collection_id' => $parentCollectionId
            ])
        ]);
        
        // Create inheritance relationship for memory scope
        $this->memoryService->createEdge([
            'edge_type' => 'memory_scope_inherits',
            'from_memory_node_id' => $childNodeId,
            'to_memory_node_id' => $parentNodeId,
            'weight' => 1.0,
            'context_json' => json_encode([
                'inheritance_type' => 'collection_hierarchy',
                'child_collection_id' => $childCollectionId,
                'parent_collection_id' => $parentCollectionId
            ])
        ]);
    }
    
    /**
     * Sync collection items to memory edges
     * 
     * @param int $collectionId
     * @param int $collectionMemoryNodeId
     */
    private function syncCollectionItems(int $collectionId, int $collectionMemoryNodeId): void
    {
        // Get collection tabs
        $tabs = $this->db->fetchAll(
            "SELECT * FROM lupo_collection_tabs 
             WHERE collection_id = ? AND is_deleted = 0 AND is_active = 1",
            [$collectionId]
        );
        
        foreach ($tabs as $tab) {
            // Create memory node for tab if it doesn't exist
            $tabNodeKey = "collection_tab_{$tab['collection_tab_id']}";
            $tabMemoryNodeId = $this->memoryService->getOrCreateNode($tabNodeKey, 'collection_tab', [
                'collection_tab_id' => $tab['collection_tab_id'],
                'name' => $tab['name'],
                'slug' => $tab['slug'],
                'collection_id' => $collectionId
            ]);
            
            // Create collection_contains edge
            $this->memoryService->createEdge([
                'edge_type' => 'collection_contains',
                'from_memory_node_id' => $collectionMemoryNodeId,
                'to_memory_node_id' => $tabMemoryNodeId,
                'weight' => 1.0,
                'context_json' => json_encode([
                    'source' => 'human_curated',
                    'collection_id' => $collectionId,
                    'collection_tab_id' => $tab['collection_tab_id'],
                    'sync_status' => 'synced',
                    'added_by_actor_id' => $tab['actor_id'],
                    'added_ymdhis' => $tab['created_ymdhis']
                ])
            ]);
        }
        
        // Get mapped items (content, PRDs, etc.)
        $mappedItems = $this->db->fetchAll(
            "SELECT * FROM lupo_collection_tab_map 
             WHERE collection_id = ? AND is_deleted = 0",
            [$collectionId]
        );
        
        foreach ($mappedItems as $item) {
            $targetNodeKey = null;
            
            // Determine target node key based on target_type
            switch ($item['target_type']) {
                case 'content':
                    $targetNodeKey = "content_{$item['target_id']}";
                    break;
                case 'prd':
                    $targetNodeKey = "prd_{$item['target_id']}";
                    break;
                case 'actor':
                    $targetNodeKey = "actor_{$item['target_id']}";
                    break;
                default:
                    // Skip unknown types
                    continue;
            }
            
            if ($targetNodeKey) {
                $targetNodeId = $this->memoryService->getNodeIdByKey($targetNodeKey);
                
                if ($targetNodeId) {
                    // Create collection_contains edge
                    $this->memoryService->createEdge([
                        'edge_type' => 'collection_contains',
                        'from_memory_node_id' => $collectionMemoryNodeId,
                        'to_memory_node_id' => $targetNodeId,
                        'weight' => 1.0,
                        'context_json' => json_encode([
                            'source' => 'human_curated',
                            'collection_id' => $collectionId,
                            'target_type' => $item['target_type'],
                            'target_id' => $item['target_id'],
                            'sync_status' => 'synced',
                            'added_by_actor_id' => $item['actor_id'],
                            'added_ymdhis' => $item['created_ymdhis']
                        ])
                    ]);
                }
            }
        }
    }
    
    /**
     * Update collection memory node when collection changes
     * 
     * @param int $collectionId
     */
    public function updateCollectionMemoryNode(int $collectionId): void
    {
        $collection = $this->db->fetchOne(
            "SELECT * FROM lupo_collections WHERE collection_id = ? AND is_deleted = 0",
            [$collectionId]
        );
        
        if (!$collection) {
            // Soft delete memory node if collection was deleted
            $this->deleteCollectionMemoryNode($collectionId);
            return;
        }
        
        $memoryNodeId = $this->memoryService->getNodeIdByKey("collection_{$collectionId}");
        
        if ($memoryNodeId) {
            // Update memory node properties
            $this->memoryService->updateNode($memoryNodeId, [
                'properties_json' => json_encode([
                    'collection_id' => $collection['collection_id'],
                    'name' => $collection['name'],
                    'slug' => $collection['slug'],
                    'color' => $collection['color'],
                    'description' => $collection['description'],
                    'sort_order' => $collection['sort_order'],
                    'is_nav_menu' => $collection['is_nav_menu'],
                    'nav_icon' => $collection['nav_icon'],
                    'parent_id' => $collection['parent_id'],
                    'created_ymdhis' => $collection['created_ymdhis'],
                    'updated_ymdhis' => $collection['updated_ymdhis']
                ])
            ]);
            
            // Update parent relationship if changed
            $this->updateParentRelationship($collectionId, $collection['parent_id']);
            
            // Sync items
            $this->syncCollectionItems($collectionId, $memoryNodeId);
        }
    }
    
    /**
     * Delete collection memory node (soft delete)
     * 
     * @param int $collectionId
     */
    public function deleteCollectionMemoryNode(int $collectionId): void
    {
        $memoryNodeId = $this->memoryService->getNodeIdByKey("collection_{$collectionId}");
        
        if ($memoryNodeId) {
            // Soft delete memory node
            $this->memoryService->deleteNode($memoryNodeId);
            
            // Soft delete related edges
            $this->memoryService->deleteEdgesFromNode($memoryNodeId);
            $this->memoryService->deleteEdgesToNode($memoryNodeId);
        }
    }
    
    /**
     * Update parent relationship
     * 
     * @param int $collectionId
     * @param int|null $newParentId
     */
    private function updateParentRelationship(int $collectionId, ?int $newParentId): void
    {
        $childNodeId = $this->memoryService->getNodeIdByKey("collection_{$collectionId}");
        
        if (!$childNodeId) {
            return;
        }
        
        // Remove existing parent relationships
        $this->memoryService->deleteEdgesByType($childNodeId, 'parent_collection');
        $this->memoryService->deleteEdgesByType($childNodeId, 'memory_scope_inherits');
        
        // Create new parent relationship if exists
        if ($newParentId) {
            $this->createParentRelationship($collectionId, $newParentId);
        }
    }
    
    /**
     * Add item to collection (creates memory edge)
     * 
     * @param int $collectionId
     * @param string $targetType
     * @param int $targetId
     * @param int $actorId
     */
    public function addItemToCollection(int $collectionId, string $targetType, int $targetId, int $actorId): void
    {
        $collectionNodeId = $this->memoryService->getNodeIdByKey("collection_{$collectionId}");
        
        if (!$collectionNodeId) {
            // Create collection memory node if it doesn't exist
            $collectionNodeId = $this->createCollectionMemoryNode($collectionId);
        }
        
        $targetNodeKey = null;
        switch ($targetType) {
            case 'content':
                $targetNodeKey = "content_{$targetId}";
                break;
            case 'prd':
                $targetNodeKey = "prd_{$targetId}";
                break;
            case 'actor':
                $targetNodeKey = "actor_{$targetId}";
                break;
        }
        
        if ($targetNodeKey) {
            $targetNodeId = $this->memoryService->getNodeIdByKey($targetNodeKey);
            
            if ($targetNodeId) {
                // Create collection_contains edge
                $this->memoryService->createEdge([
                    'edge_type' => 'collection_contains',
                    'from_memory_node_id' => $collectionNodeId,
                    'to_memory_node_id' => $targetNodeId,
                    'weight' => 1.0,
                    'context_json' => json_encode([
                        'source' => 'human_curated',
                        'collection_id' => $collectionId,
                        'target_type' => $targetType,
                        'target_id' => $targetId,
                        'sync_status' => 'synced',
                        'added_by_actor_id' => $actorId,
                        'added_ymdhis' => time()
                    ])
                ]);
            }
        }
    }
    
    /**
     * Remove item from collection (soft deletes memory edge)
     * 
     * @param int $collectionId
     * @param string $targetType
     * @param int $targetId
     */
    public function removeItemFromCollection(int $collectionId, string $targetType, int $targetId): void
    {
        $collectionNodeId = $this->memoryService->getNodeIdByKey("collection_{$collectionId}");
        
        if (!$collectionNodeId) {
            return;
        }
        
        $targetNodeKey = null;
        switch ($targetType) {
            case 'content':
                $targetNodeKey = "content_{$targetId}";
                break;
            case 'prd':
                $targetNodeKey = "prd_{$targetId}";
                break;
            case 'actor':
                $targetNodeKey = "actor_{$targetId}";
                break;
        }
        
        if ($targetNodeKey) {
            $targetNodeId = $this->memoryService->getNodeIdByKey($targetNodeKey);
            
            if ($targetNodeId) {
                // Find and soft delete the edge
                $edge = $this->db->fetchOne(
                    "SELECT edge_id FROM lupo_memory_edges 
                     WHERE from_memory_node_id = ? AND to_memory_node_id = ? 
                     AND edge_type = 'collection_contains' AND is_deleted = 0",
                    [$collectionNodeId, $targetNodeId]
                );
                
                if ($edge) {
                    $this->memoryService->deleteEdge($edge['edge_id']);
                }
            }
        }
    }
    
    /**
     * Get collection hierarchy (parents and children)
     * 
     * @param int $collectionId
     * @return array
     */
    public function getCollectionHierarchy(int $collectionId): array
    {
        $collectionNodeId = $this->memoryService->getNodeIdByKey("collection_{$collectionId}");
        
        if (!$collectionNodeId) {
            return [];
        }
        
        $hierarchy = [
            'collection_id' => $collectionId,
            'parents' => [],
            'children' => [],
            'siblings' => []
        ];
        
        // Get parents
        $parentEdges = $this->memoryService->getOutgoingEdges($collectionNodeId, 'parent_collection');
        foreach ($parentEdges as $edge) {
            $parentNode = $this->memoryService->getNode($edge['to_memory_node_id']);
            if ($parentNode && str_starts_with($parentNode['memory_toon'], 'collection_')) {
                $parentId = str_replace('collection_', '', $parentNode['memory_toon']);
                $hierarchy['parents'][] = [
                    'collection_id' => (int) $parentId,
                    'memory_node_id' => $parentNode['memory_node_id'],
                    'properties' => json_decode($parentNode['properties_json'], true)
                ];
            }
        }
        
        // Get children
        $childEdges = $this->memoryService->getIncomingEdges($collectionNodeId, 'parent_collection');
        foreach ($childEdges as $edge) {
            $childNode = $this->memoryService->getNode($edge['from_memory_node_id']);
            if ($childNode && str_starts_with($childNode['memory_toon'], 'collection_')) {
                $childId = str_replace('collection_', '', $childNode['memory_toon']);
                $hierarchy['children'][] = [
                    'collection_id' => (int) $childId,
                    'memory_node_id' => $childNode['memory_node_id'],
                    'properties' => json_decode($childNode['properties_json'], true)
                ];
            }
        }
        
        // Get siblings (collections with same parent)
        foreach ($hierarchy['parents'] as $parent) {
            $parentSiblings = $this->memoryService->getIncomingEdges($parent['memory_node_id'], 'parent_collection');
            foreach ($parentSiblings as $edge) {
                $siblingNode = $this->memoryService->getNode($edge['from_memory_node_id']);
                if ($siblingNode && 
                    str_starts_with($siblingNode['memory_toon'], 'collection_') &&
                    $siblingNode['memory_node_id'] != $collectionNodeId) {
                    $siblingId = str_replace('collection_', '', $siblingNode['memory_toon']);
                    $hierarchy['siblings'][] = [
                        'collection_id' => (int) $siblingId,
                        'memory_node_id' => $siblingNode['memory_node_id'],
                        'properties' => json_decode($siblingNode['properties_json'], true)
                    ];
                }
            }
        }
        
        return $hierarchy;
    }
    
    /**
     * Get all collections for an actor via memory graph
     * 
     * @param int $actorId
     * @return array
     */
    public function getActorCollections(int $actorId): array
    {
        $collections = [];
        
        // Get actor's memory node
        $actorNodeId = $this->memoryService->getNodeIdByKey("actor_{$actorId}");
        
        if (!$actorNodeId) {
            return $collections;
        }
        
        // Find collections where actor has access
        $accessEdges = $this->memoryService->getOutgoingEdges($actorNodeId, 'has_access_to');
        
        foreach ($accessEdges as $edge) {
            $collectionNode = $this->memoryService->getNode($edge['to_memory_node_id']);
            if ($collectionNode && str_starts_with($collectionNode['memory_toon'], 'collection_')) {
                $collectionId = str_replace('collection_', '', $collectionNode['memory_toon']);
                $collections[] = [
                    'collection_id' => (int) $collectionId,
                    'memory_node_id' => $collectionNode['memory_node_id'],
                    'access_weight' => $edge['weight'],
                    'properties' => json_decode($collectionNode['properties_json'], true)
                ];
            }
        }
        
        return $collections;
    }
}
