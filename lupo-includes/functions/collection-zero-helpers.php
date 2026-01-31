<?php
/**
 * Collection 0 (System Documentation) Helpers
 *
 * Ensures Collection 0 exists and is populated with Lupopedia documentation.
 * Collection 0 is system-owned and serves as the documentation landing page
 * for new users after Crafty Syntax migration.
 *
 * @package Lupopedia
 * @since 2026.3.9
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. collection-zero-helpers.php cannot be called directly.");
}

/**
 * Ensure Collection 0 exists and is properly configured
 *
 * Creates Collection 0 if it doesn't exist, or verifies its configuration.
 * Collection 0 is the system documentation collection.
 *
 * @return array|false Collection data if successful, false on error
 */
function lupo_ensure_collection_zero() {
    if (!isset($GLOBALS['mydatabase'])) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("COLLECTION 0: Database connection not available");
        }
        return false;
    }

    $db = $GLOBALS['mydatabase'];
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : str_replace('-', '_', LUPO_PREFIX);

    try {
        // Check if Collection 0 exists
        $check_sql = "SELECT collection_id, name, slug, is_deleted
                      FROM {$table_prefix}collections
                      WHERE collection_id = 0
                      LIMIT 1";
        $stmt = $db->prepare($check_sql);
        $stmt->execute();
        $collection = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($collection) {
            // Collection 0 exists - verify it's not deleted
            if ($collection['is_deleted'] == 1) {
                // Undelete it
                $now = lupo_utc_timestamp();
                $update_sql = "UPDATE {$table_prefix}collections
                               SET is_deleted = 0,
                                   deleted_ymdhis = NULL,
                                   updated_ymdhis = :updated_ymdhis
                               WHERE collection_id = 0";
                $update_stmt = $db->prepare($update_sql);
                $update_stmt->execute([':updated_ymdhis' => $now]);

                if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                    error_log("COLLECTION 0: Restored deleted collection");
                }
            }

            return $collection;
        }

        // Collection 0 doesn't exist - create it
        $now = lupo_utc_timestamp();
        $system_actor_id = 1; // System actor
        $default_node_id = defined('LUPO_DEFAULT_NODE_ID') ? LUPO_DEFAULT_NODE_ID : 1;

        $insert_sql = "INSERT INTO {$table_prefix}collections (
            collection_id,
            federations_node_id,
            actor_id,
            name,
            slug,
            color,
            description,
            sort_order,
            created_ymdhis,
            updated_ymdhis,
            is_deleted
        ) VALUES (
            0,
            :node_id,
            :actor_id,
            'Lupopedia Documentation',
            'lupopedia',
            '2196F3',
            'Official Lupopedia system documentation and guides',
            0,
            :created_ymdhis,
            :updated_ymdhis,
            0
        )";

        $stmt = $db->prepare($insert_sql);
        $stmt->execute([
            ':node_id' => $default_node_id,
            ':actor_id' => $system_actor_id,
            ':created_ymdhis' => $now,
            ':updated_ymdhis' => $now
        ]);

        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("COLLECTION 0: Created system documentation collection");
        }

        // Return the newly created collection
        return [
            'collection_id' => 0,
            'name' => 'Lupopedia Documentation',
            'slug' => 'lupopedia',
            'is_deleted' => 0
        ];

    } catch (PDOException $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("COLLECTION 0 ERROR: " . $e->getMessage());
        }
        return false;
    }
}

/**
 * Populate Collection 0 with documentation tabs
 *
 * Creates or updates documentation tabs in Collection 0.
 * Each tab links to a specific documentation page.
 *
 * @return bool True if successful, false on error
 */
function lupo_populate_collection_zero_tabs() {
    if (!isset($GLOBALS['mydatabase'])) {
        return false;
    }

    $db = $GLOBALS['mydatabase'];
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : str_replace('-', '_', LUPO_PREFIX);

    // Define documentation tabs
    $documentation_tabs = [
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

    $now = lupo_utc_timestamp();
    $system_actor_id = 1;

    try {
        foreach ($documentation_tabs as $tab) {
            // Check if tab already exists
            $check_sql = "SELECT tab_id FROM {$table_prefix}collection_tabs
                          WHERE collection_id = 0
                          AND slug = :slug
                          LIMIT 1";
            $check_stmt = $db->prepare($check_sql);
            $check_stmt->execute([':slug' => $tab['slug']]);
            $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // Update existing tab
                $update_sql = "UPDATE {$table_prefix}collection_tabs
                               SET name = :name,
                                   sort_order = :sort_order,
                                   updated_ymdhis = :updated_ymdhis
                               WHERE tab_id = :tab_id";
                $update_stmt = $db->prepare($update_sql);
                $update_stmt->execute([
                    ':name' => $tab['name'],
                    ':sort_order' => $tab['sort_order'],
                    ':updated_ymdhis' => $now,
                    ':tab_id' => $existing['tab_id']
                ]);
            } else {
                // Insert new tab
                $insert_sql = "INSERT INTO {$table_prefix}collection_tabs (
                    collection_id,
                    name,
                    slug,
                    sort_order,
                    created_ymdhis,
                    updated_ymdhis,
                    is_deleted
                ) VALUES (
                    0,
                    :name,
                    :slug,
                    :sort_order,
                    :created_ymdhis,
                    :updated_ymdhis,
                    0
                )";

                $insert_stmt = $db->prepare($insert_sql);
                $insert_stmt->execute([
                    ':name' => $tab['name'],
                    ':slug' => $tab['slug'],
                    ':sort_order' => $tab['sort_order'],
                    ':created_ymdhis' => $now,
                    ':updated_ymdhis' => $now
                ]);
            }
        }

        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("COLLECTION 0: Populated " . count($documentation_tabs) . " documentation tabs");
        }

        return true;

    } catch (PDOException $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("COLLECTION 0 TABS ERROR: " . $e->getMessage());
        }
        return false;
    }
}

/**
 * Get Collection 0 URL
 *
 * Returns the full URL to Collection 0 documentation.
 *
 * @param string $tab_slug Optional tab slug to link to specific tab
 * @return string Collection 0 URL
 */
function lupo_get_collection_zero_url($tab_slug = null) {
    $base_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
    $url = $base_url . '/collection/0/lupopedia';

    if ($tab_slug) {
        $url .= '/' . $tab_slug;
    }

    return $url;
}

/**
 * Initialize Collection 0 (run on system boot)
 *
 * Ensures Collection 0 exists and is populated.
 * Safe to call multiple times - idempotent.
 *
 * @return bool True if successful, false on error
 */
function lupo_initialize_collection_zero() {
    $collection = lupo_ensure_collection_zero();
    if (!$collection) {
        return false;
    }

    return lupo_populate_collection_zero_tabs();
}
