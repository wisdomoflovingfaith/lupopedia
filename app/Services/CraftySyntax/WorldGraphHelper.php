<?php
//===========================================================================
//* --    ~~                LUPOPEDIA World Graph Helper                ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2026 Lupopedia Development Team
//===========================================================================

// LEGACY PRESERVATION NOTICE:
// This file implements World Graph Layer functionality under HERITAGE-SAFE MODE.
// DO NOT MODIFY LEGACY BEHAVIOR - Only add world context resolution.
// Reference: PHASE11_WORLD_GRAPH_INTEGRATION_REPORT.md

require_once("LegacyFunctions.php");

/**
 * World Graph Helper Class
 * 
 * Provides world context resolution for TOON events while preserving
 * all legacy Crafty Syntax behavior under HERITAGE-SAFE MODE.
 */
class WorldGraphHelper {
    
    /**
     * Resolve world from department context
     */
    public static function resolve_world_from_department($department_id) {
        global $mydatabase;
        
        if (empty($department_id)) {
            return null;
        }
        
        $world_key = "department_" . intval($department_id);
        
        // Check if world exists
        $query = "SELECT world_id, world_type, world_label, world_metadata 
                  FROM lupo_world_registry 
                  WHERE world_key = '" . addslashes($world_key) . "'";
        $result = $mydatabase->query($query);
        
        if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => json_decode($row['world_metadata'], true)
            ];
        }
        
        // Create new world entry
        $department_query = "SELECT nameofdepartment FROM livehelp_departments 
                            WHERE departmentid = " . intval($department_id);
        $dept_result = $mydatabase->query($department_query);
        $dept_row = $dept_result->fetchRow(DB_FETCHMODE_ASSOC);
        
        $world_label = isset($dept_row['nameofdepartment']) ? $dept_row['nameofdepartment'] : "Department " . $department_id;
        $world_metadata = json_encode([
            'department_id' => $department_id,
            'created_at' => time(),
            'source' => 'department_context'
        ]);
        
        $insert_query = "INSERT INTO lupo_world_registry 
                        (world_key, world_type, world_label, world_metadata, created_at)
                        VALUES ('" . addslashes($world_key) . "', 'department', '" . addslashes($world_label) . "', 
                               '" . addslashes($world_metadata) . "', " . time() . ")";
        
        $mydatabase->query($insert_query);
        $world_id = $mydatabase->insertId();
        
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'department',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true)
        ];
    }
    
    /**
     * Resolve world from channel context
     */
    public static function resolve_world_from_channel($channel_id) {
        global $mydatabase;
        
        if (empty($channel_id)) {
            return null;
        }
        
        $world_key = "channel_" . intval($channel_id);
        
        // Check if world exists
        $query = "SELECT world_id, world_type, world_label, world_metadata 
                  FROM lupo_world_registry 
                  WHERE world_key = '" . addslashes($world_key) . "'";
        $result = $mydatabase->query($query);
        
        if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => json_decode($row['world_metadata'], true)
            ];
        }
        
        // Create new world entry
        $world_label = "Channel " . $channel_id;
        $world_metadata = json_encode([
            'channel_id' => $channel_id,
            'created_at' => time(),
            'source' => 'channel_context'
        ]);
        
        $insert_query = "INSERT INTO lupo_world_registry 
                        (world_key, world_type, world_label, world_metadata, created_at)
                        VALUES ('" . addslashes($world_key) . "', 'channel', '" . addslashes($world_label) . "', 
                               '" . addslashes($world_metadata) . "', " . time() . ")";
        
        $mydatabase->query($insert_query);
        $world_id = $mydatabase->insertId();
        
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'channel',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true)
        ];
    }
    
    /**
     * Resolve world from page context
     */
    public static function resolve_world_from_page($page_url) {
        global $mydatabase;
        
        if (empty($page_url)) {
            return null;
        }
        
        $world_key = "page_" . md5($page_url);
        
        // Check if world exists
        $query = "SELECT world_id, world_type, world_label, world_metadata 
                  FROM lupo_world_registry 
                  WHERE world_key = '" . addslashes($world_key) . "'";
        $result = $mydatabase->query($query);
        
        if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => json_decode($row['world_metadata'], true)
            ];
        }
        
        // Create new world entry
        $world_label = parse_url($page_url, PHP_URL_PATH) ?: $page_url;
        $world_metadata = json_encode([
            'page_url' => $page_url,
            'created_at' => time(),
            'source' => 'page_context'
        ]);
        
        $insert_query = "INSERT INTO lupo_world_registry 
                        (world_key, world_type, world_label, world_metadata, created_at)
                        VALUES ('" . addslashes($world_key) . "', 'page', '" . addslashes($world_label) . "', 
                               '" . addslashes($world_metadata) . "', " . time() . ")";
        
        $mydatabase->query($insert_query);
        $world_id = $mydatabase->insertId();
        
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'page',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true)
        ];
    }
    
    /**
     * Resolve world from campaign context
     */
    public static function resolve_world_from_campaign($campaign_id) {
        global $mydatabase;
        
        if (empty($campaign_id)) {
            return null;
        }
        
        $world_key = "campaign_" . intval($campaign_id);
        
        // Check if world exists
        $query = "SELECT world_id, world_type, world_label, world_metadata 
                  FROM lupo_world_registry 
                  WHERE world_key = '" . addslashes($world_key) . "'";
        $result = $mydatabase->query($query);
        
        if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => json_decode($row['world_metadata'], true)
            ];
        }
        
        // Create new world entry
        $world_label = "Campaign " . $campaign_id;
        $world_metadata = json_encode([
            'campaign_id' => $campaign_id,
            'created_at' => time(),
            'source' => 'campaign_context'
        ]);
        
        $insert_query = "INSERT INTO lupo_world_registry 
                        (world_key, world_type, world_label, world_metadata, created_at)
                        VALUES ('" . addslashes($world_key) . "', 'campaign', '" . addslashes($world_label) . "', 
                               '" . addslashes($world_metadata) . "', " . time() . ")";
        
        $mydatabase->query($insert_query);
        $world_id = $mydatabase->insertId();
        
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'campaign',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true)
        ];
    }
    
    /**
     * Resolve world from console context
     */
    public static function resolve_world_from_console_context($operator_id) {
        global $mydatabase;
        
        if (empty($operator_id)) {
            return null;
        }
        
        $world_key = "console_" . intval($operator_id);
        
        // Check if world exists
        $query = "SELECT world_id, world_type, world_label, world_metadata 
                  FROM lupo_world_registry 
                  WHERE world_key = '" . addslashes($world_key) . "'";
        $result = $mydatabase->query($query);
        
        if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => json_decode($row['world_metadata'], true)
            ];
        }
        
        // Create new world entry
        $world_label = "Operator Console " . $operator_id;
        $world_metadata = json_encode([
            'operator_id' => $operator_id,
            'created_at' => time(),
            'source' => 'console_context'
        ]);
        
        $insert_query = "INSERT INTO lupo_world_registry 
                        (world_key, world_type, world_label, world_metadata, created_at)
                        VALUES ('" . addslashes($world_key) . "', 'console', '" . addslashes($world_label) . "', 
                               '" . addslashes($world_metadata) . "', " . time() . ")";
        
        $mydatabase->query($insert_query);
        $world_id = $mydatabase->insertId();
        
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'console',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true)
        ];
    }
    
    /**
     * Resolve world from live context
     */
    public static function resolve_world_from_live_context($session_id) {
        global $mydatabase;
        
        if (empty($session_id)) {
            return null;
        }
        
        $world_key = "live_" . md5($session_id);
        
        // Check if world exists
        $query = "SELECT world_id, world_type, world_label, world_metadata 
                  FROM lupo_world_registry 
                  WHERE world_key = '" . addslashes($world_key) . "'";
        $result = $mydatabase->query($query);
        
        if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => json_decode($row['world_metadata'], true)
            ];
        }
        
        // Create new world entry
        $world_label = "Live Session " . substr($session_id, 0, 8);
        $world_metadata = json_encode([
            'session_id' => $session_id,
            'created_at' => time(),
            'source' => 'live_context'
        ]);
        
        $insert_query = "INSERT INTO lupo_world_registry 
                        (world_key, world_type, world_label, world_metadata, created_at)
                        VALUES ('" . addslashes($world_key) . "', 'live', '" . addslashes($world_label) . "', 
                               '" . addslashes($world_metadata) . "', " . time() . ")";
        
        $mydatabase->query($insert_query);
        $world_id = $mydatabase->insertId();
        
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'live',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true)
        ];
    }
    
    /**
     * Resolve world from external embed context
     */
    public static function resolve_world_from_external_embed($embed_url) {
        global $mydatabase;
        
        if (empty($embed_url)) {
            return null;
        }
        
        $world_key = "external_" . md5($embed_url);
        
        // Check if world exists
        $query = "SELECT world_id, world_type, world_label, world_metadata 
                  FROM lupo_world_registry 
                  WHERE world_key = '" . addslashes($world_key) . "'";
        $result = $mydatabase->query($query);
        
        if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => json_decode($row['world_metadata'], true)
            ];
        }
        
        // Create new world entry
        $world_label = "External Embed " . parse_url($embed_url, PHP_URL_HOST);
        $world_metadata = json_encode([
            'embed_url' => $embed_url,
            'created_at' => time(),
            'source' => 'external_embed_context'
        ]);
        
        $insert_query = "INSERT INTO lupo_world_registry 
                        (world_key, world_type, world_label, world_metadata, created_at)
                        VALUES ('" . addslashes($world_key) . "', 'external', '" . addslashes($world_label) . "', 
                               '" . addslashes($world_metadata) . "', " . time() . ")";
        
        $mydatabase->query($insert_query);
        $world_id = $mydatabase->insertId();
        
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'external',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true)
        ];
    }
    
    /**
     * Resolve world from UI context
     */
    public static function resolve_world_from_ui_context($ui_element, $context_data = []) {
        global $mydatabase;
        
        if (empty($ui_element)) {
            return null;
        }
        
        $world_key = "ui_" . md5($ui_element . serialize($context_data));
        
        // Check if world exists
        $query = "SELECT world_id, world_type, world_label, world_metadata 
                  FROM lupo_world_registry 
                  WHERE world_key = '" . addslashes($world_key) . "'";
        $result = $mydatabase->query($query);
        
        if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
            return [
                'world_id' => $row['world_id'],
                'world_key' => $world_key,
                'world_type' => $row['world_type'],
                'world_label' => $row['world_label'],
                'world_metadata' => json_decode($row['world_metadata'], true)
            ];
        }
        
        // Create new world entry
        $world_label = "UI Element " . $ui_element;
        $world_metadata = json_encode([
            'ui_element' => $ui_element,
            'context_data' => $context_data,
            'created_at' => time(),
            'source' => 'ui_context'
        ]);
        
        $insert_query = "INSERT INTO lupo_world_registry 
                        (world_key, world_type, world_label, world_metadata, created_at)
                        VALUES ('" . addslashes($world_key) . "', 'ui', '" . addslashes($world_label) . "', 
                               '" . addslashes($world_metadata) . "', " . time() . ")";
        
        $mydatabase->query($insert_query);
        $world_id = $mydatabase->insertId();
        
        return [
            'world_id' => $world_id,
            'world_key' => $world_key,
            'world_type' => 'ui',
            'world_label' => $world_label,
            'world_metadata' => json_decode($world_metadata, true)
        ];
    }
    
    /**
     * Auto-detect and resolve world context from current request
     */
    public static function auto_resolve_world_context() {
        global $department, $channel, $UNTRUSTED, $identity;
        
        // Try department context first
        if (!empty($department)) {
            return self::resolve_world_from_department($department);
        }
        
        // Try channel context
        if (!empty($channel)) {
            return self::resolve_world_from_channel($channel);
        }
        
        // Try page context from referer
        if (!empty($_SERVER['HTTP_REFERER'])) {
            return self::resolve_world_from_page($_SERVER['HTTP_REFERER']);
        }
        
        // Try console context for operators
        if (!empty($identity['USERID'])) {
            return self::resolve_world_from_console_context($identity['USERID']);
        }
        
        // Try live context from session
        if (!empty($identity['SESSIONID'])) {
            return self::resolve_world_from_live_context($identity['SESSIONID']);
        }
        
        return null;
    }
}

?>
