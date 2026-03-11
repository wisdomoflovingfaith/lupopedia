<?php

namespace App\Services;

/**
 * Collection tabs — load tabs for a collection (root + children), get collection name.
 * Channel/nav: getCollectionsForNavMenu(), getCollectionsForChannel() for resource-bundle resolution.
 * Uses PDO_DB and LUPO_TABLE_PREFIX. TOON: collection_tab_id, name, slug, collection_tab_parent_id, visibility_rule, tab_type.
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
            "SELECT collection_tab_id, name, slug, sort_order, collection_tab_parent_id, visibility_rule, tab_type FROM {$t} WHERE collection_id = :collection_id AND collection_tab_parent_id IS NULL AND is_active = 1 AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY sort_order ASC, name ASC",
            array('collection_id' => $collectionId)
        );
        $tabsData = array();
        foreach ($root as $tab) {
            $tabId = (int) $tab['collection_tab_id'];
            $children = $this->db->fetchAll(
                "SELECT collection_tab_id, name, slug, sort_order, visibility_rule, tab_type FROM {$t} WHERE collection_id = :collection_id AND collection_tab_parent_id = :parent_id AND is_active = 1 AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY sort_order ASC, name ASC",
                array('collection_id' => $collectionId, 'parent_id' => $tabId)
            );
            $subTabs = array('_slug' => $tab['slug']);
            if (isset($tab['visibility_rule']) && $tab['visibility_rule'] !== null && $tab['visibility_rule'] !== '') {
                $subTabs['_visibility_rule'] = $tab['visibility_rule'];
            }
            if (isset($tab['tab_type']) && $tab['tab_type'] !== null && $tab['tab_type'] !== '') {
                $subTabs['_tab_type'] = $tab['tab_type'];
            }
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
            array('collection_id' => $collectionId)
        );
        return $row ? $row['name'] : null;
    }

    /**
     * Get collections flagged as global navigation menus (is_nav_menu = 1).
     * For top-level dropdown / header nav. Returns collection_id, name, slug, nav_icon.
     *
     * @return array
     */
    public function getCollectionsForNavMenu()
    {
        $t = $this->db->quoteIdentifier($this->prefix . 'collections');
        $rows = $this->db->fetchAll(
            "SELECT collection_id, name, slug, nav_icon FROM {$t} WHERE is_nav_menu = 1 AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY sort_order ASC, name ASC",
            array()
        );
        return is_array($rows) ? $rows : array();
    }

    /**
     * Get collections scoped to a channel (channel_id = :channel_id).
     * For channel sidebar / resource bundle. Returns collection_id, name, slug, nav_icon.
     *
     * @param int $channelId
     * @return array
     */
    public function getCollectionsForChannel($channelId)
    {
        $channelId = (int) $channelId;
        if ($channelId <= 0) {
            return array();
        }
        $t = $this->db->quoteIdentifier($this->prefix . 'collections');
        $rows = $this->db->fetchAll(
            "SELECT collection_id, name, slug, nav_icon FROM {$t} WHERE channel_id = :channel_id AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY sort_order ASC, name ASC",
            array('channel_id' => $channelId)
        );
        return is_array($rows) ? $rows : array();
    }
}
