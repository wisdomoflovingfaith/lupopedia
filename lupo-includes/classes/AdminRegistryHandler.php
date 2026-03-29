<?php
/**
 * Admin Registry Handler
 * 
 * DEPRECATED: Registry tables removed in favor of timestamp-based ID generation.
 * This class is retained for backward compatibility but should be removed.
 * 
 * Use IdGenerator::generate() for new timestamp-based IDs.
 * 
 * @package Lupopedia
 * @version 4.0.89
 * @deprecated 4.0.89 - Use timestamp-based ID generation instead
 */

class AdminRegistryHandler
{
    /**
     * Render registry admin section (DEPRECATED)
     * 
     * @param PDO_DB $db Database connection
     * @param string $prefix Table prefix
     * @param string $base Base URL path
     * @return string HTML output
     * @deprecated Use IdGenerator::generate() instead of registry
     */
    public static function render($db, $prefix, $base)
    {
        $html = '';
        
        // DEPRECATED: Registry functionality removed
        // Use IdGenerator::generate() for timestamp-based IDs
        $html .= '<div class="alert alert-warning">';
        $html .= '<strong>WARNING:</strong> Registry system is deprecated. ';
        $html .= 'Use IdGenerator::generate() for timestamp-based IDs.';
        $html .= '</div>';
        
        // Handle form submission for adding new registry entry
        $add_success = false;
        $add_error = '';
        
        if (isset($_POST['action']) && $_POST['action'] === 'add_registry' && isset($_POST['csrf_token'])) {
            // Verify CSRF token
            if (function_exists('lupo_verify_csrf_token') && lupo_verify_csrf_token($_POST['csrf_token'])) {
                $add_result = self::handleAddRegistry($db, $prefix);
                $add_success = $add_result['success'];
                $add_error = $add_result['error'];
            } else {
                $add_error = 'Invalid security token. Please try again.';
            }
        }
        
        // Get filter parameters
        $filter_entity_type = isset($_GET['entity_type']) ? trim((string) $_GET['entity_type']) : '';
        $filter_is_kernel = isset($_GET['is_kernel']) ? trim((string) $_GET['is_kernel']) : '';
        $filter_is_active = isset($_GET['is_active']) ? trim((string) $_GET['is_active']) : '';
        
        // Build query
        $where = array('r.is_deleted = 0');
        $params = array();
        
        if ($filter_entity_type !== '') {
            $where[] = 'r.entity_type = :entity_type';
            $params['entity_type'] = $filter_entity_type;
        }
        
        if ($filter_is_kernel !== '') {
            $where[] = 'r.is_kernel = :is_kernel';
            $params['is_kernel'] = (int) $filter_is_kernel;
        }
        
        if ($filter_is_active !== '') {
            $where[] = 'r.is_active = :is_active';
            $params['is_active'] = (int) $filter_is_active;
        }
        
        $where_sql = implode(' AND ', $where);
        
        // Get registry entries
        $sql = "SELECT 
                    r.registry_id,
                    r.entity_type,
                    r.entity_index_id,
                    r.entity_index,
                    r.federation_node_id,
                    r.entity_key,
                    r.entity_name,
                    r.entity_table,
                    r.is_kernel,
                    r.is_active,
                    r.created_ymdhis,
                    r.reserved_ymdhis
                FROM {$prefix}registry r
                WHERE {$where_sql}
                ORDER BY r.entity_type ASC, r.entity_index_id ASC
                LIMIT 200";
        
        $entries = $db->fetchAll($sql, $params);
        
        // Get distinct entity types for filter dropdown
        $entity_types = $db->fetchAll("SELECT DISTINCT entity_type FROM {$prefix}registry WHERE is_deleted = 0 ORDER BY entity_type");
        
        // Start HTML output
        $html .= '<div class="admin-section-registry">';
        
        // Success/Error messages
        if ($add_success) {
            $html .= '<div style="padding: 15px; margin-bottom: 20px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; color: #155724;">';
            $html .= '<strong>Success:</strong> Registry entry added successfully.';
            $html .= '</div>';
        }
        
        if ($add_error !== '') {
            $html .= '<div style="padding: 15px; margin-bottom: 20px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; color: #721c24;">';
            $html .= '<strong>Error:</strong> ' . htmlspecialchars($add_error);
            $html .= '</div>';
        }
        
        // Add Registry Form
        $html .= '<div style="margin-bottom: 30px; padding: 20px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px;">';
        $html .= '<h3 style="margin-top: 0;">Add New Registry Entry</h3>';
        $html .= '<form method="post" action="admin.php?section=registry" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">';
        $html .= '<input type="hidden" name="action" value="add_registry">';
        $html .= '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(function_exists('lupo_get_csrf_token') ? lupo_get_csrf_token() : '') . '">';
        
        // Entity Type
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Entity Type: <span style="color: red;">*</span></label>';
        $html .= '<input type="text" name="entity_type" required maxlength="50" placeholder="e.g., actor, channel, agent" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '</div>';
        
        // Entity Index ID
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Entity Index ID: <span style="color: red;">*</span></label>';
        $html .= '<input type="number" name="entity_index_id" required min="0" placeholder="e.g., 1000" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '</div>';
        
        // Entity Index
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Entity Index:</label>';
        $html .= '<input type="number" name="entity_index" min="0" value="0" placeholder="0" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '</div>';
        
        // Entity Key
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Entity Key:</label>';
        $html .= '<input type="text" name="entity_key" maxlength="255" placeholder="e.g., kiro-ide" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '</div>';
        
        // Entity Name
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Entity Name:</label>';
        $html .= '<input type="text" name="entity_name" maxlength="255" placeholder="e.g., Kiro IDE" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '</div>';
        
        // Entity Table
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Entity Table:</label>';
        $html .= '<input type="text" name="entity_table" maxlength="255" placeholder="e.g., lupo_actors" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '</div>';
        
        // Federation Node ID
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Federation Node ID:</label>';
        $html .= '<input type="number" name="federation_node_id" min="0" value="0" placeholder="0" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '</div>';
        
        // Is Kernel
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Is Kernel:</label>';
        $html .= '<select name="is_kernel" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '<option value="0">No</option>';
        $html .= '<option value="1">Yes</option>';
        $html .= '</select>';
        $html .= '</div>';
        
        // Submit button
        $html .= '<div style="display: flex; align-items: end;">';
        $html .= '<button type="submit" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 3px; cursor: pointer; font-weight: 500;">Add Entry</button>';
        $html .= '</div>';
        
        $html .= '</form>';
        $html .= '<p style="margin-top: 10px; margin-bottom: 0; color: #666; font-size: 14px;"><strong>Note:</strong> Existing registry entries cannot be modified or deleted through this interface for data integrity.</p>';
        $html .= '</div>';
        
        // Filters
        $html .= '<div class="admin-filters" style="margin-bottom: 20px; padding: 15px; background: #f5f5f5; border-radius: 4px;">';
        $html .= '<form method="get" action="admin.php" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: end;">';
        $html .= '<input type="hidden" name="section" value="registry">';
        
        // Entity Type filter
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Entity Type:</label>';
        $html .= '<select name="entity_type" style="padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '<option value="">All Types</option>';
        foreach ($entity_types as $et) {
            $selected = ($filter_entity_type === $et['entity_type']) ? ' selected' : '';
            $html .= '<option value="' . htmlspecialchars($et['entity_type']) . '"' . $selected . '>' . htmlspecialchars($et['entity_type']) . '</option>';
        }
        $html .= '</select>';
        $html .= '</div>';
        
        // Is Kernel filter
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Kernel:</label>';
        $html .= '<select name="is_kernel" style="padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '<option value="">All</option>';
        $html .= '<option value="1"' . ($filter_is_kernel === '1' ? ' selected' : '') . '>Kernel Only</option>';
        $html .= '<option value="0"' . ($filter_is_kernel === '0' ? ' selected' : '') . '>Non-Kernel</option>';
        $html .= '</select>';
        $html .= '</div>';
        
        // Is Active filter
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Status:</label>';
        $html .= '<select name="is_active" style="padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '<option value="">All</option>';
        $html .= '<option value="1"' . ($filter_is_active === '1' ? ' selected' : '') . '>Active</option>';
        $html .= '<option value="0"' . ($filter_is_active === '0' ? ' selected' : '') . '>Inactive</option>';
        $html .= '</select>';
        $html .= '</div>';
        
        $html .= '<div>';
        $html .= '<button type="submit" style="padding: 6px 15px; background: #0066cc; color: white; border: none; border-radius: 3px; cursor: pointer;">Filter</button>';
        $html .= '</div>';
        
        $html .= '</form>';
        $html .= '</div>';
        
        // Entry count
        $html .= '<p style="margin-bottom: 15px;"><strong>' . count($entries) . '</strong> registry entry(ies) found (limit 200)</p>';
        
        if (empty($entries)) {
            $html .= '<p class="admin-empty">No registry entries found matching the selected filters.</p>';
        } else {
            // Registry table
            $html .= '<div style="overflow-x: auto;">';
            $html .= '<table class="admin-table" style="width: 100%; border-collapse: collapse; background: white;">';
            $html .= '<thead>';
            $html .= '<tr style="background: #f0f0f0; border-bottom: 2px solid #ddd;">';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Registry ID</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Entity Type</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Index ID</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Index</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Key</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Name</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Table</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Node</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Kernel</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Status</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Created</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            
            foreach ($entries as $entry) {
                $is_kernel_badge = $entry['is_kernel'] ? '<span style="display: inline-block; padding: 2px 6px; background: #dc3545; color: white; border-radius: 3px; font-size: 11px; font-weight: 500;">KERNEL</span>' : '';
                $is_active_badge = $entry['is_active'] ? '<span style="display: inline-block; padding: 2px 6px; background: #28a745; color: white; border-radius: 3px; font-size: 11px; font-weight: 500;">ACTIVE</span>' : '<span style="display: inline-block; padding: 2px 6px; background: #6c757d; color: white; border-radius: 3px; font-size: 11px; font-weight: 500;">INACTIVE</span>';
                
                $created = self::formatYmdhis($entry['created_ymdhis']);
                
                $html .= '<tr style="border-bottom: 1px solid #eee;">';
                $html .= '<td style="padding: 10px;">' . (int) $entry['registry_id'] . '</td>';
                $html .= '<td style="padding: 10px;"><strong>' . htmlspecialchars($entry['entity_type']) . '</strong></td>';
                $html .= '<td style="padding: 10px;">' . (int) $entry['entity_index_id'] . '</td>';
                $html .= '<td style="padding: 10px;">' . (int) $entry['entity_index'] . '</td>';
                $html .= '<td style="padding: 10px;">' . htmlspecialchars($entry['entity_key'] ?: '-') . '</td>';
                $html .= '<td style="padding: 10px;">' . htmlspecialchars($entry['entity_name'] ?: '-') . '</td>';
                $html .= '<td style="padding: 10px;"><code style="font-size: 12px;">' . htmlspecialchars($entry['entity_table'] ?: '-') . '</code></td>';
                $html .= '<td style="padding: 10px;">' . (int) $entry['federation_node_id'] . '</td>';
                $html .= '<td style="padding: 10px;">' . $is_kernel_badge . '</td>';
                $html .= '<td style="padding: 10px;">' . $is_active_badge . '</td>';
                $html .= '<td style="padding: 10px;">' . htmlspecialchars($created) . '</td>';
                $html .= '</tr>';
            }
            
            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Handle adding a new registry entry
     * 
     * @param PDO_DB $db Database connection
     * @param string $prefix Table prefix
     * @return array Result with 'success' and 'error' keys
     */
    private static function handleAddRegistry($db, $prefix)
    {
        $result = array('success' => false, 'error' => '');
        
        // Validate required fields
        if (empty($_POST['entity_type']) || empty($_POST['entity_index_id'])) {
            $result['error'] = 'Entity Type and Entity Index ID are required.';
            return $result;
        }
        
        $entity_type = trim((string) $_POST['entity_type']);
        $entity_index_id = (int) $_POST['entity_index_id'];
        $entity_index = isset($_POST['entity_index']) ? (int) $_POST['entity_index'] : 0;
        $entity_key = isset($_POST['entity_key']) ? trim((string) $_POST['entity_key']) : null;
        $entity_name = isset($_POST['entity_name']) ? trim((string) $_POST['entity_name']) : null;
        $entity_table = isset($_POST['entity_table']) ? trim((string) $_POST['entity_table']) : null;
        $federation_node_id = isset($_POST['federation_node_id']) ? (int) $_POST['federation_node_id'] : 0;
        $is_kernel = isset($_POST['is_kernel']) ? (int) $_POST['is_kernel'] : 0;
        
        // Check if entry already exists
        $existing = $db->fetchRow(
            "SELECT registry_id FROM {$prefix}registry WHERE entity_type = :entity_type AND entity_index_id = :entity_index_id AND federation_node_id = :federation_node_id AND is_deleted = 0",
            array(
                'entity_type' => $entity_type,
                'entity_index_id' => $entity_index_id,
                'federation_node_id' => $federation_node_id
            )
        );
        
        if ($existing) {
            $result['error'] = 'Registry entry already exists for this entity type, index ID, and federation node.';
            return $result;
        }
        
        // Insert new entry
        $now = gmdate('YmdHis');
        
        $insert_data = array(
            'entity_type' => $entity_type,
            'entity_index_id' => $entity_index_id,
            'entity_index' => $entity_index,
            'federation_node_id' => $federation_node_id,
            'entity_key' => $entity_key,
            'entity_name' => $entity_name,
            'entity_table' => $entity_table,
            'is_kernel' => $is_kernel,
            'is_active' => 1,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'reserved_ymdhis' => $now,
            'is_deleted' => 0
        );
        
        $insert_result = $db->insert($prefix . 'registry', $insert_data);
        
        if ($insert_result) {
            $result['success'] = true;
        } else {
            $result['error'] = 'Failed to insert registry entry. Please check database logs.';
        }
        
        return $result;
    }
    
    /**
     * Format YMDHIS timestamp to readable date
     * 
     * @param int|string $ymdhis YYYYMMDDHHIISS timestamp
     * @return string Formatted date
     */
    private static function formatYmdhis($ymdhis)
    {
        if (!$ymdhis) return '-';
        
        $str = (string) $ymdhis;
        if (strlen($str) < 14) return $str;
        
        $year = substr($str, 0, 4);
        $month = substr($str, 4, 2);
        $day = substr($str, 6, 2);
        $hour = substr($str, 8, 2);
        $min = substr($str, 10, 2);
        
        return "{$year}-{$month}-{$day} {$hour}:{$min}";
    }
}
