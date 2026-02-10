<?php

namespace App\Services;

/**
 * Collection Zero (system documentation) — ensure collection 0 exists,
 * populate tabs, get URL, initialize. Uses PDO_DB and LUPO_TABLE_PREFIX only.
 */

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

class CollectionZeroService
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
     * Ensure Collection 0 exists; create or restore if deleted.
     *
     * @return array|false Collection row or false on error
     */
    public function ensureCollectionZero()
    {
        $t = $this->db->quoteIdentifier($this->prefix . 'collections');
        $row = $this->db->fetchRow(
            "SELECT collection_id, name, slug, is_deleted FROM {$t} WHERE collection_id = 0 LIMIT 1",
            []
        );
        if ($row) {
            if ((int) ($row['is_deleted'] ?? 0) === 1) {
                $now = class_exists('timestamp_ymdhis') ? timestamp_ymdhis::now() : (int) gmdate('YmdHis');
                $this->db->query(
                    "UPDATE {$t} SET is_deleted = 0, deleted_ymdhis = NULL, updated_ymdhis = :updated_ymdhis WHERE collection_id = 0",
                    ['updated_ymdhis' => $now]
                );
            }
            return $row;
        }
        $now = class_exists('timestamp_ymdhis') ? timestamp_ymdhis::now() : (int) gmdate('YmdHis');
        $nodeId = defined('LUPO_DEFAULT_NODE_ID') ? LUPO_DEFAULT_NODE_ID : 1;
        $this->db->query(
            "INSERT INTO {$t} (collection_id, federations_node_id, actor_id, name, slug, color, description, sort_order, created_ymdhis, updated_ymdhis, is_deleted) VALUES (0, :node_id, 1, 'Lupopedia Documentation', 'lupopedia', '2196F3', 'Official Lupopedia system documentation and guides', 0, :created_ymdhis, :updated_ymdhis, 0)",
            ['node_id' => $nodeId, 'created_ymdhis' => $now, 'updated_ymdhis' => $now]
        );
        return [
            'collection_id' => 0,
            'name' => 'Lupopedia Documentation',
            'slug' => 'lupopedia',
            'is_deleted' => 0,
        ];
    }

    /**
     * Populate Collection 0 with documentation tabs.
     *
     * @return bool
     */
    public function populateCollectionZeroTabs(): bool
    {
        $tabs = [
            ['name' => 'Overview', 'slug' => 'overview', 'sort_order' => 10, 'content_path' => '/docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md#overview'],
            ['name' => 'Actor Model', 'slug' => 'actor-model', 'sort_order' => 20, 'content_path' => '/docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md#1-identity-model-actors-not-users'],
            ['name' => 'Database Doctrine', 'slug' => 'database-doctrine', 'sort_order' => 30, 'content_path' => '/docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md#2-database-doctrine'],
            ['name' => 'Python vs PHP', 'slug' => 'python-vs-php', 'sort_order' => 40, 'content_path' => '/docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md#3-language-boundaries-python-vs-php'],
            ['name' => 'TOON Schema', 'slug' => 'toon-schema', 'sort_order' => 50, 'content_path' => '/docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md#4-toon-files-as-schema-source-of-truth'],
            ['name' => 'Upload Structure', 'slug' => 'upload-structure', 'sort_order' => 60, 'content_path' => '/docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md#5-upload-and-file-doctrine'],
            ['name' => 'Session Model', 'slug' => 'session-model', 'sort_order' => 70, 'content_path' => '/docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md#6-session-and-login-doctrine'],
            ['name' => 'Agent Model', 'slug' => 'agent-model', 'sort_order' => 80, 'content_path' => '/docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md#7-agent-model-and-registry'],
            ['name' => 'LEXA Sentinel', 'slug' => 'lexa-sentinel', 'sort_order' => 90, 'content_path' => '/docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md#8-lexa-doctrine-sentinel--boundary-keeper'],
            ['name' => 'Multi-Agent Workflow', 'slug' => 'multi-agent', 'sort_order' => 100, 'content_path' => '/docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md#9-multi-agent-workflow-doctrine'],
            ['name' => 'Quick Reference', 'slug' => 'quick-reference', 'sort_order' => 110, 'content_path' => '/docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md#11-quick-reference-for-ai-agents'],
            ['name' => 'Crafty Syntax Migration', 'slug' => 'crafty-syntax', 'sort_order' => 120, 'content_path' => '/docs/doctrine/CRAFTY_SYNTAX_INTEGRATION_PLAN.md'],
            ['name' => 'AI Agent Boot', 'slug' => 'ai-agent-boot', 'sort_order' => 130, 'content_path' => '/docs/doctrine/AI_AGENT_BOOT_NOTES.md'],
        ];
        $t = $this->db->quoteIdentifier($this->prefix . 'collection_tabs');
        $now = class_exists('timestamp_ymdhis') ? timestamp_ymdhis::now() : (int) gmdate('YmdHis');
        foreach ($tabs as $tab) {
            $existing = $this->db->fetchRow(
                "SELECT collection_tab_id FROM {$t} WHERE collection_id = 0 AND slug = :slug LIMIT 1",
                ['slug' => $tab['slug']]
            );
            if ($existing) {
                $this->db->query(
                    "UPDATE {$t} SET name = :name, sort_order = :sort_order, updated_ymdhis = :updated_ymdhis WHERE collection_tab_id = :tab_id",
                    ['name' => $tab['name'], 'sort_order' => $tab['sort_order'], 'updated_ymdhis' => $now, 'tab_id' => $existing['collection_tab_id']]
                );
            } else {
                $this->db->insert($this->prefix . 'collection_tabs', [
                    'collection_id' => 0,
                    'federations_node_id' => defined('LUPO_DEFAULT_NODE_ID') ? LUPO_DEFAULT_NODE_ID : 1,
                    'name' => $tab['name'],
                    'slug' => $tab['slug'],
                    'sort_order' => $tab['sort_order'],
                    'created_ymdhis' => $now,
                    'updated_ymdhis' => $now,
                    'is_active' => 1,
                    'is_deleted' => 0,
                ]);
            }
        }
        return true;
    }

    /**
     * Get Collection 0 URL with optional tab slug.
     *
     * @param string|null $tabSlug
     * @return string
     */
    public function getCollectionZeroUrl(?string $tabSlug = null): string
    {
        $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
        $url = $base . '/collection/0/lupopedia';
        if ($tabSlug !== null && $tabSlug !== '') {
            $url .= '/' . $tabSlug;
        }
        return $url;
    }

    /**
     * Initialize Collection 0: ensure then populate tabs.
     *
     * @return bool
     */
    public function initializeCollectionZero(): bool
    {
        $collection = $this->ensureCollectionZero();
        if ($collection === false) {
            return false;
        }
        return $this->populateCollectionZeroTabs();
    }
}
