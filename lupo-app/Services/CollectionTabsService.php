<?php

namespace App\Services;

/**
 * Collection tabs — load tabs for a collection (root + children), get collection name.
 * Uses PDO_DB and LUPO_TABLE_PREFIX. TOON: collection_tab_id, name, slug, collection_tab_parent_id.
 */

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

class CollectionTabsService
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
     * Load collection tabs for UI (root + children). Returns format: [ 'Tab Name' => ['_slug' => 'slug', 'Sub1', 'Sub2'], ... ].
     *
     * @param int $collectionId
     * @return array
     */
    public function loadCollectionTabs(int $collectionId): array
    {
        $t = $this->db->quoteIdentifier($this->prefix . 'collection_tabs');
        $root = $this->db->fetchAll(
            "SELECT collection_tab_id, name, slug, sort_order, collection_tab_parent_id FROM {$t} WHERE collection_id = :collection_id AND collection_tab_parent_id IS NULL AND is_active = 1 AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY sort_order ASC, name ASC",
            ['collection_id' => $collectionId]
        );
        $tabsData = [];
        foreach ($root as $tab) {
            $tabId = (int) $tab['collection_tab_id'];
            $children = $this->db->fetchAll(
                "SELECT collection_tab_id, name, slug, sort_order FROM {$t} WHERE collection_id = :collection_id AND collection_tab_parent_id = :parent_id AND is_active = 1 AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY sort_order ASC, name ASC",
                ['collection_id' => $collectionId, 'parent_id' => $tabId]
            );
            $subTabs = ['_slug' => $tab['slug']];
            foreach ($children as $c) {
                $subTabs[] = $c['name'];
            }
            $tabsData[$tab['name']] = $subTabs;
        }
        return $tabsData;
    }

    /**
     * Get collection name by collection_id.
     *
     * @param int $collectionId
     * @return string|null
     */
    public function getCollectionName(int $collectionId): ?string
    {
        $t = $this->db->quoteIdentifier($this->prefix . 'collections');
        $row = $this->db->fetchRow(
            "SELECT name FROM {$t} WHERE collection_id = :collection_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            ['collection_id' => $collectionId]
        );
        return $row ? $row['name'] : null;
    }
}
