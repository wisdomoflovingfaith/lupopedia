<?php

namespace App\Services;

/**
 * Saved collections — render tree for nav, load tab children, count tab items.
 * Uses PDO_DB and LUPO_TABLE_PREFIX. TOON: collection_id, collection_tab_id, collection_tab_map_id, lupo_contents.content_id.
 */

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

class SavedCollectionsService
{
    /** @var \PDO_DB */
    private $db;

    /** @var string */
    private $prefix;

    public function __construct($db)
    {
        $this->db = $db;
        $this->prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }

    /**
     * Render saved collections tree for nav. Uses collection_id, name, slug (slug as type).
     * Permissions: lupo_permissions target_type='collection', target_id=collection_id.
     *
     * @param int $userId Auth user ID (0 = public, all collections)
     * @return array
     */
    public function renderSavedCollections(int $userId): array
    {
        $collectionsData = [];
        $collT = $this->db->quoteIdentifier($this->prefix . 'collections');
        $permT = $this->db->quoteIdentifier($this->prefix . 'permissions');
        if ($userId === 0) {
            $collections = $this->db->fetchAll(
                "SELECT collection_id, name, slug FROM {$collT} c WHERE (c.is_deleted = 0 OR c.is_deleted IS NULL) ORDER BY c.slug, c.name",
                []
            );
        } else {
            $collections = $this->db->fetchAll(
                "SELECT DISTINCT c.collection_id, c.name, c.slug FROM {$collT} c INNER JOIN {$permT} cp ON c.collection_id = cp.target_id AND cp.target_type = :target_type AND (cp.is_deleted = 0 OR cp.is_deleted IS NULL) WHERE cp.user_id = :user_id ORDER BY c.slug, c.name",
                ['user_id' => $userId, 'target_type' => 'collection']
            );
        }
        foreach ($collections as $collection) {
            $collectionId = (int) $collection['collection_id'];
            $collectionType = $collection['slug'];
            $tabs = $this->db->fetchAll(
                "SELECT collection_tab_id, name, sort_order FROM " . $this->db->quoteIdentifier($this->prefix . 'collection_tabs') . " WHERE collection_id = :collection_id AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY sort_order, name",
                ['collection_id' => $collectionId]
            );
            $tabsWithChildren = [];
            foreach ($tabs as $tab) {
                $tabId = (int) $tab['collection_tab_id'];
                $children = $this->loadTabChildren($tabId);
                $itemCount = 0;
                foreach ($children as $child) {
                    if (($child['item_type'] ?? '') === 'content' || ($child['item_type'] ?? '') === 'link') {
                        $itemCount++;
                    } else {
                        $itemCount += $this->countTabItems($child['item_id']);
                    }
                }
                $tabsWithChildren[] = [
                    'id' => $tab['collection_tab_id'],
                    'tab_name' => $tab['name'],
                    'sort_order' => (int) $tab['sort_order'],
                    'children' => $children,
                    'item_count' => $itemCount,
                ];
            }
            $totalCount = 0;
            foreach ($tabsWithChildren as $t) {
                $totalCount += $t['item_count'];
            }
            if (!isset($collectionsData[$collectionType])) {
                $collectionsData[$collectionType] = ['type' => $collectionType, 'count' => 0, 'tabs' => []];
            }
            $collectionsData[$collectionType]['tabs'] = array_merge($collectionsData[$collectionType]['tabs'], $tabsWithChildren);
            $collectionsData[$collectionType]['count'] += $totalCount;
        }
        foreach ($collectionsData as &$data) {
            usort($data['tabs'], function ($a, $b) {
                return $a['sort_order'] <=> $b['sort_order'];
            });
        }
        unset($data);
        return $collectionsData;
    }

    /**
     * Load children of a tab from collection_tab_map (TOON: collection_tab_map_id, collection_tab_id, item_type, item_id).
     *
     * @param int $tabId collection_tab_id
     * @return array
     */
    public function loadTabChildren(int $tabId): array
    {
        $mapT = $this->db->quoteIdentifier($this->prefix . 'collection_tab_map');
        $tabsT = $this->db->quoteIdentifier($this->prefix . 'collection_tabs');
        $contT = $this->db->quoteIdentifier($this->prefix . 'contents');
        $rows = $this->db->fetchAll(
            "SELECT collection_tab_map_id, item_type, item_id, sort_order, properties FROM {$mapT} WHERE collection_tab_id = :tab_id AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY sort_order",
            ['tab_id' => $tabId]
        );
        $children = [];
        foreach ($rows as $mapping) {
            $itemType = $mapping['item_type'];
            $itemId = (int) $mapping['item_id'];
            $properties = !empty($mapping['properties']) ? json_decode($mapping['properties'], true) : [];
            $properties = is_array($properties) ? $properties : [];
            $child = [
                'id' => $mapping['collection_tab_map_id'],
                'item_type' => $itemType,
                'item_id' => $itemId,
                'sort_order' => (int) $mapping['sort_order'],
                'properties' => $properties,
            ];
            if ($itemType === 'tab') {
                $subTab = $this->db->fetchRow("SELECT collection_tab_id, name, sort_order FROM {$tabsT} WHERE collection_tab_id = :tab_id LIMIT 1", ['tab_id' => $itemId]);
                if ($subTab) {
                    $child['tab_name'] = $subTab['name'];
                    $child['tab_id'] = $subTab['collection_tab_id'];
                    $child['children'] = $this->loadTabChildren($itemId);
                    $child['item_count'] = $this->countTabItems($itemId);
                }
            } elseif ($itemType === 'content') {
                $content = $this->db->fetchRow("SELECT content_id, title FROM {$contT} WHERE content_id = :content_id LIMIT 1", ['content_id' => $itemId]);
                if ($content) {
                    $child['title'] = $content['title'];
                    $child['content_id'] = $content['content_id'];
                }
            } elseif ($itemType === 'link') {
                $child['url'] = $properties['url'] ?? '#';
                $child['label'] = $properties['label'] ?? 'Link';
            }
            $children[] = $child;
        }
        return $children;
    }

    /**
     * Count items in a tab (content + link, recursively).
     *
     * @param int $tabId collection_tab_id
     * @return int
     */
    public function countTabItems(int $tabId): int
    {
        $mapT = $this->db->quoteIdentifier($this->prefix . 'collection_tab_map');
        $rows = $this->db->fetchAll(
            "SELECT item_type, item_id FROM {$mapT} WHERE collection_tab_id = :tab_id AND (is_deleted = 0 OR is_deleted IS NULL)",
            ['tab_id' => $tabId]
        );
        $count = 0;
        foreach ($rows as $m) {
            if ($m['item_type'] === 'content' || $m['item_type'] === 'link') {
                $count++;
            } elseif ($m['item_type'] === 'tab') {
                $count += $this->countTabItems((int) $m['item_id']);
            }
        }
        return $count;
    }
}
