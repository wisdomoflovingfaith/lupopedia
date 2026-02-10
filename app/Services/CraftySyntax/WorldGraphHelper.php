<?php
//===========================================================================
//* --    ~~                LUPOPEDIA World Graph Helper                ~~    -- *
//===========================================================================
// Doctrine: PDO_DB only, table prefix, named params. Schema from install_new_lupopedia.sql (lupo_world_registry: created_ymdhis, updated_ymdhis).

require_once("LegacyFunctions.php");

/**
 * World Graph Helper — world context resolution using {prefix}world_registry and {prefix}departments.
 */
class WorldGraphHelper {

    private static function table_prefix() {
        return defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }

    private static function world_registry_table() {
        return self::table_prefix() . 'world_registry';
    }

    private static function departments_table() {
        return self::table_prefix() . 'departments';
    }

    private static function now_ymdhis() {
        return (int) gmdate('YmdHis');
    }

    /**
     * Resolve world from department context (per livehelp_departments_migration.md → lupo_departments).
     */
    public static function resolve_world_from_department($department_id) {
        global $mydatabase;
        if (empty($department_id) || !isset($mydatabase) || !($mydatabase instanceof PDO_DB)) {
            return null;
        }
        $world_key = 'department_' . (int) $department_id;
        $t = self::world_registry_table();
        $row = $mydatabase->fetchRow(
            "SELECT world_id, world_type, world_label, world_metadata FROM {$t} WHERE world_key = :wk",
            ['wk' => $world_key]
        );
        if ($row) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => isset($row['world_metadata']) ? json_decode($row['world_metadata'], true) : null,
            ];
        }
        $dept_table = self::departments_table();
        $dept_row = $mydatabase->fetchRow(
            "SELECT name FROM {$dept_table} WHERE department_id = :did",
            ['did' => (int) $department_id]
        );
        $world_label = ($dept_row && isset($dept_row['name'])) ? $dept_row['name'] : 'Department ' . $department_id;
        $world_metadata = json_encode([
            'department_id' => (int) $department_id,
            'created_at' => time(),
            'source' => 'department_context',
        ]);
        $now = self::now_ymdhis();
        $mydatabase->insert(self::world_registry_table(), [
            'world_key' => $world_key,
            'world_type' => 'department',
            'world_label' => $world_label,
            'world_metadata' => $world_metadata,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_active' => 1,
        ]);
        $world_id = $mydatabase->lastInsertId();
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'department',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true),
        ];
    }

    /**
     * Resolve world from channel context.
     */
    public static function resolve_world_from_channel($channel_id) {
        global $mydatabase;
        if (empty($channel_id) || !isset($mydatabase) || !($mydatabase instanceof PDO_DB)) {
            return null;
        }
        $world_key = 'channel_' . (int) $channel_id;
        $t = self::world_registry_table();
        $row = $mydatabase->fetchRow(
            "SELECT world_id, world_type, world_label, world_metadata FROM {$t} WHERE world_key = :wk",
            ['wk' => $world_key]
        );
        if ($row) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => isset($row['world_metadata']) ? json_decode($row['world_metadata'], true) : null,
            ];
        }
        $world_label = 'Channel ' . $channel_id;
        $world_metadata = json_encode([
            'channel_id' => (int) $channel_id,
            'created_at' => time(),
            'source' => 'channel_context',
        ]);
        $now = self::now_ymdhis();
        $mydatabase->insert(self::world_registry_table(), [
            'world_key' => $world_key,
            'world_type' => 'channel',
            'world_label' => $world_label,
            'world_metadata' => $world_metadata,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_active' => 1,
        ]);
        $world_id = $mydatabase->lastInsertId();
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'channel',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true),
        ];
    }

    /**
     * Resolve world from page context.
     */
    public static function resolve_world_from_page($page_url) {
        global $mydatabase;
        if (empty($page_url) || !isset($mydatabase) || !($mydatabase instanceof PDO_DB)) {
            return null;
        }
        $world_key = 'page_' . md5($page_url);
        $t = self::world_registry_table();
        $row = $mydatabase->fetchRow(
            "SELECT world_id, world_type, world_label, world_metadata FROM {$t} WHERE world_key = :wk",
            ['wk' => $world_key]
        );
        if ($row) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => isset($row['world_metadata']) ? json_decode($row['world_metadata'], true) : null,
            ];
        }
        $world_label = parse_url($page_url, PHP_URL_PATH) ?: $page_url;
        $world_metadata = json_encode([
            'page_url' => $page_url,
            'created_at' => time(),
            'source' => 'page_context',
        ]);
        $now = self::now_ymdhis();
        $mydatabase->insert(self::world_registry_table(), [
            'world_key' => $world_key,
            'world_type' => 'page',
            'world_label' => $world_label,
            'world_metadata' => $world_metadata,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_active' => 1,
        ]);
        $world_id = $mydatabase->lastInsertId();
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'page',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true),
        ];
    }

    /**
     * Resolve world from campaign context.
     */
    public static function resolve_world_from_campaign($campaign_id) {
        global $mydatabase;
        if (empty($campaign_id) || !isset($mydatabase) || !($mydatabase instanceof PDO_DB)) {
            return null;
        }
        $world_key = 'campaign_' . (int) $campaign_id;
        $t = self::world_registry_table();
        $row = $mydatabase->fetchRow(
            "SELECT world_id, world_type, world_label, world_metadata FROM {$t} WHERE world_key = :wk",
            ['wk' => $world_key]
        );
        if ($row) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => isset($row['world_metadata']) ? json_decode($row['world_metadata'], true) : null,
            ];
        }
        $world_label = 'Campaign ' . $campaign_id;
        $world_metadata = json_encode([
            'campaign_id' => (int) $campaign_id,
            'created_at' => time(),
            'source' => 'campaign_context',
        ]);
        $now = self::now_ymdhis();
        $mydatabase->insert(self::world_registry_table(), [
            'world_key' => $world_key,
            'world_type' => 'campaign',
            'world_label' => $world_label,
            'world_metadata' => $world_metadata,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_active' => 1,
        ]);
        $world_id = $mydatabase->lastInsertId();
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'campaign',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true),
        ];
    }

    /**
     * Resolve world from console context.
     */
    public static function resolve_world_from_console_context($operator_id) {
        global $mydatabase;
        if (empty($operator_id) || !isset($mydatabase) || !($mydatabase instanceof PDO_DB)) {
            return null;
        }
        $world_key = 'console_' . (int) $operator_id;
        $t = self::world_registry_table();
        $row = $mydatabase->fetchRow(
            "SELECT world_id, world_type, world_label, world_metadata FROM {$t} WHERE world_key = :wk",
            ['wk' => $world_key]
        );
        if ($row) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => isset($row['world_metadata']) ? json_decode($row['world_metadata'], true) : null,
            ];
        }
        $world_label = 'Operator Console ' . $operator_id;
        $world_metadata = json_encode([
            'operator_id' => (int) $operator_id,
            'created_at' => time(),
            'source' => 'console_context',
        ]);
        $now = self::now_ymdhis();
        $mydatabase->insert(self::world_registry_table(), [
            'world_key' => $world_key,
            'world_type' => 'console',
            'world_label' => $world_label,
            'world_metadata' => $world_metadata,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_active' => 1,
        ]);
        $world_id = $mydatabase->lastInsertId();
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'console',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true),
        ];
    }

    /**
     * Resolve world from live context.
     */
    public static function resolve_world_from_live_context($session_id) {
        global $mydatabase;
        if (empty($session_id) || !isset($mydatabase) || !($mydatabase instanceof PDO_DB)) {
            return null;
        }
        $world_key = 'live_' . md5($session_id);
        $t = self::world_registry_table();
        $row = $mydatabase->fetchRow(
            "SELECT world_id, world_type, world_label, world_metadata FROM {$t} WHERE world_key = :wk",
            ['wk' => $world_key]
        );
        if ($row) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => isset($row['world_metadata']) ? json_decode($row['world_metadata'], true) : null,
            ];
        }
        $world_label = 'Live Session ' . substr($session_id, 0, 8);
        $world_metadata = json_encode([
            'session_id' => $session_id,
            'created_at' => time(),
            'source' => 'live_context',
        ]);
        $now = self::now_ymdhis();
        $mydatabase->insert(self::world_registry_table(), [
            'world_key' => $world_key,
            'world_type' => 'live',
            'world_label' => $world_label,
            'world_metadata' => $world_metadata,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_active' => 1,
        ]);
        $world_id = $mydatabase->lastInsertId();
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'live',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true),
        ];
    }

    /**
     * Resolve world from external embed context.
     */
    public static function resolve_world_from_external_embed($embed_url) {
        global $mydatabase;
        if (empty($embed_url) || !isset($mydatabase) || !($mydatabase instanceof PDO_DB)) {
            return null;
        }
        $world_key = 'external_' . md5($embed_url);
        $t = self::world_registry_table();
        $row = $mydatabase->fetchRow(
            "SELECT world_id, world_type, world_label, world_metadata FROM {$t} WHERE world_key = :wk",
            ['wk' => $world_key]
        );
        if ($row) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => isset($row['world_metadata']) ? json_decode($row['world_metadata'], true) : null,
            ];
        }
        $world_label = 'External Embed ' . parse_url($embed_url, PHP_URL_HOST);
        $world_metadata = json_encode([
            'embed_url' => $embed_url,
            'created_at' => time(),
            'source' => 'external_embed_context',
        ]);
        $now = self::now_ymdhis();
        $mydatabase->insert(self::world_registry_table(), [
            'world_key' => $world_key,
            'world_type' => 'external',
            'world_label' => $world_label,
            'world_metadata' => $world_metadata,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_active' => 1,
        ]);
        $world_id = $mydatabase->lastInsertId();
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'external',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true),
        ];
    }

    /**
     * Resolve world from UI context.
     */
    public static function resolve_world_from_ui_context($ui_element, $context_data = []) {
        global $mydatabase;
        if (empty($ui_element) || !isset($mydatabase) || !($mydatabase instanceof PDO_DB)) {
            return null;
        }
        $world_key = 'ui_' . md5($ui_element . serialize($context_data));
        $t = self::world_registry_table();
        $row = $mydatabase->fetchRow(
            "SELECT world_id, world_type, world_label, world_metadata FROM {$t} WHERE world_key = :wk",
            ['wk' => $world_key]
        );
        if ($row) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => isset($row['world_metadata']) ? json_decode($row['world_metadata'], true) : null,
            ];
        }
        $world_label = 'UI Element ' . $ui_element;
        $world_metadata = json_encode([
            'ui_element' => $ui_element,
            'context_data' => $context_data,
            'created_at' => time(),
            'source' => 'ui_context',
        ]);
        $now = self::now_ymdhis();
        $mydatabase->insert(self::world_registry_table(), [
            'world_key' => $world_key,
            'world_type' => 'ui',
            'world_label' => $world_label,
            'world_metadata' => $world_metadata,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_active' => 1,
        ]);
        $world_id = $mydatabase->lastInsertId();
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'ui',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true),
        ];
    }

    /**
     * Auto-detect and resolve world context from current request.
     */
    public static function auto_resolve_world_context() {
        global $department, $channel, $UNTRUSTED, $identity;
        if (!empty($department)) {
            return self::resolve_world_from_department($department);
        }
        if (!empty($channel)) {
            return self::resolve_world_from_channel($channel);
        }
        if (!empty($_SERVER['HTTP_REFERER'])) {
            return self::resolve_world_from_page($_SERVER['HTTP_REFERER']);
        }
        if (!empty($identity['USERID'])) {
            return self::resolve_world_from_console_context($identity['USERID']);
        }
        if (!empty($identity['SESSIONID'])) {
            return self::resolve_world_from_live_context($identity['SESSIONID']);
        }
        return null;
    }
}
