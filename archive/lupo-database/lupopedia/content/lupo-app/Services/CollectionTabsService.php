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
    /**
     * Content rows mapped directly onto a tab (collection_tab_map item_type = content).
     *
     * @param int $tabId
     * @return array[] rows with content_id, title, slug
     */
    private function fetchMapContentRowsForTab($tabId)
    {
        $tabId = (int) $tabId;
        if ($tabId <= 0) {
            return array();
        }
        $mapT = $this->db->quoteIdentifier($this->prefix . 'collection_tab_map');
        $cT = $this->db->quoteIdentifier($this->prefix . 'contents');
        $sql = "SELECT c.content_id, c.title, c.slug FROM {$mapT} m INNER JOIN {$cT} c ON m.item_type = 'content' AND m.item_id = c.content_id
            WHERE m.collection_tab_id = :tid AND (m.is_deleted = 0 OR m.is_deleted IS NULL)
            AND (c.is_deleted = 0 OR c.is_deleted IS NULL) AND (c.is_active = 1 OR c.is_active IS NULL)
            ORDER BY m.sort_order ASC, c.title ASC";
        $rows = $this->db->fetchAll($sql, array('tid' => $tabId));
        return is_array($rows) ? $rows : array();
    }

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
            $subTabs = array(
                '_slug' => $tab['slug'],
                '_collection_tab_id' => $tabId,
            );
            if (isset($tab['visibility_rule']) && $tab['visibility_rule'] !== null && $tab['visibility_rule'] !== '') {
                $subTabs['_visibility_rule'] = $tab['visibility_rule'];
            }
            if (isset($tab['tab_type']) && $tab['tab_type'] !== null && $tab['tab_type'] !== '') {
                $subTabs['_tab_type'] = $tab['tab_type'];
            }
            $subTabs['_children'] = array();
            foreach ($children as $c) {
                $cid = (int) $c['collection_tab_id'];
                $mapped = $this->fetchMapContentRowsForTab($cid);
                $childRow = array(
                    'name' => $c['name'],
                    'slug' => $c['slug'],
                    'collection_tab_id' => $cid,
                );
                if (!empty($mapped)) {
                    $childRow['map_contents'] = array();
                    foreach ($mapped as $row) {
                        $childRow['map_contents'][] = array(
                            'name' => isset($row['title']) ? $row['title'] : '',
                            'slug' => isset($row['slug']) ? $row['slug'] : '',
                            'content_id' => isset($row['content_id']) ? (int) $row['content_id'] : 0,
                        );
                    }
                }
                $subTabs['_children'][] = $childRow;
            }
            if (empty($subTabs['_children'])) {
                foreach ($this->fetchMapContentRowsForTab($tabId) as $row) {
                    $subTabs['_children'][] = array(
                        'name' => isset($row['title']) ? $row['title'] : '',
                        'slug' => isset($row['slug']) ? $row['slug'] : '',
                        'collection_tab_id' => 0,
                        'content_id' => isset($row['content_id']) ? (int) $row['content_id'] : 0,
                    );
                }
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
            "SELECT collection_id, name, slug, nav_icon, sort_order, properties FROM {$t} WHERE is_nav_menu = 1 AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY sort_order ASC, name ASC",
            array()
        );
        return is_array($rows) ? $rows : array();
    }

    /**
     * Nav menu collections grouped for master Collections dropdown (properties JSON: nav_group, nav_group_sort, nav_item_label).
     *
     * @return array[] Each: group_label, group_sort, items: list of collection_id, label, slug, sort_order
     */
    public function getNavMenuCollectionsGrouped(): array
    {
        $rows = $this->getCollectionsForNavMenu();
        if (empty($rows)) {
            return array();
        }
        $buckets = array();
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $cid = isset($row['collection_id']) ? (int) $row['collection_id'] : 0;
            $group = 'Other';
            $groupSort = 999;
            $itemLabel = isset($row['name']) ? (string) $row['name'] : ('Collection ' . $cid);
            $propsRaw = isset($row['properties']) ? $row['properties'] : null;
            if ($propsRaw !== null && $propsRaw !== '') {
                $pj = is_array($propsRaw) ? $propsRaw : json_decode((string) $propsRaw, true);
                if (is_array($pj)) {
                    if (!empty($pj['nav_group']) && is_string($pj['nav_group'])) {
                        $group = $pj['nav_group'];
                    }
                    if (isset($pj['nav_group_sort']) && is_numeric($pj['nav_group_sort'])) {
                        $groupSort = (int) $pj['nav_group_sort'];
                    }
                    if (!empty($pj['nav_item_label']) && is_string($pj['nav_item_label'])) {
                        $itemLabel = $pj['nav_item_label'];
                    }
                }
            }
            if (!isset($buckets[$group])) {
                $buckets[$group] = array(
                    'group_label' => $group,
                    'group_sort' => $groupSort,
                    'items' => array(),
                );
            } else {
                if ($groupSort < $buckets[$group]['group_sort']) {
                    $buckets[$group]['group_sort'] = $groupSort;
                }
            }
            $buckets[$group]['items'][] = array(
                'collection_id' => $cid,
                'label' => $itemLabel,
                'slug' => isset($row['slug']) ? (string) $row['slug'] : '',
                'sort_order' => isset($row['sort_order']) ? (int) $row['sort_order'] : 0,
            );
        }
        $out = array_values($buckets);
        usort($out, function ($a, $b) {
            $ga = isset($a['group_sort']) ? (int) $a['group_sort'] : 999;
            $gb = isset($b['group_sort']) ? (int) $b['group_sort'] : 999;
            if ($ga !== $gb) {
                return $ga - $gb;
            }
            return strcmp((string) $a['group_label'], (string) $b['group_label']);
        });
        foreach ($out as $idx => $g) {
            if (!empty($g['items']) && is_array($g['items'])) {
                usort($out[$idx]['items'], function ($x, $y) {
                    $sx = isset($x['sort_order']) ? (int) $x['sort_order'] : 0;
                    $sy = isset($y['sort_order']) ? (int) $y['sort_order'] : 0;
                    if ($sx !== $sy) {
                        return $sx - $sy;
                    }
                    return ((int) ($x['collection_id'] ?? 0)) - ((int) ($y['collection_id'] ?? 0));
                });
            }
        }
        return $out;
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
