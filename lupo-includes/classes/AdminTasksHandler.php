<?php
/**
 * Admin Tasks Handler
 * 
 * Displays tasks from lupo_tasks table with filtering by channel, status, and priority.
 * Shows task details including assignments, dependencies, and metadata.
 * 
 * @package Lupopedia
 * @version 4.0.46
 */

class AdminTasksHandler
{
    /**
     * Render the tasks admin section
     * 
     * @param PDO_DB $db Database connection
     * @param string $prefix Table prefix
     * @param string $base Base URL path
     * @return string HTML output
     */
    public static function render($db, $prefix, $base)
    {
        $html = '';
        
        // Get filter parameters
        $filter_channel = isset($_GET['channel']) ? (int) $_GET['channel'] : 0;
        $filter_status = isset($_GET['status']) ? (int) $_GET['status'] : 0;
        $filter_priority = isset($_GET['priority']) ? (int) $_GET['priority'] : 0;
        
        // Build query
        $where = array('t.is_deleted = 0');
        $params = array();
        
        if ($filter_channel > 0) {
            $where[] = 't.channel_id = :channel_id';
            $params['channel_id'] = $filter_channel;
        }
        
        if ($filter_status > 0) {
            $where[] = 't.status_id = :status_id';
            $params['status_id'] = $filter_status;
        }
        
        if ($filter_priority > 0) {
            $where[] = 't.priority_id = :priority_id';
            $params['priority_id'] = $filter_priority;
        }
        
        $where_sql = implode(' AND ', $where);
        
        // Get tasks
        $sql = "SELECT 
                    t.task_id,
                    t.channel_id,
                    t.owner_actor_id,
                    t.task_key,
                    t.title,
                    t.description,
                    t.status_id,
                    t.priority_id,
                    t.created_ymdhis,
                    t.updated_ymdhis,
                    t.started_ymdhis,
                    t.completed_ymdhis,
                    t.estimated_duration_seconds,
                    c.channel_name,
                    a.name as owner_name,
                    ts.status_key,
                    ts.status_name,
                    tp.priority_key,
                    tp.priority_name,
                    tp.priority_level
                FROM {$prefix}tasks t
                LEFT JOIN {$prefix}channels c ON t.channel_id = c.channel_id
                LEFT JOIN {$prefix}actors a ON t.owner_actor_id = a.actor_id
                LEFT JOIN {$prefix}task_statuses ts ON t.status_id = ts.status_id
                LEFT JOIN {$prefix}task_priorities tp ON t.priority_id = tp.priority_id
                WHERE {$where_sql}
                ORDER BY 
                    tp.priority_level ASC,
                    t.created_ymdhis DESC
                LIMIT 100";
        
        $tasks = $db->fetchAll($sql, $params);
        
        // Get channels for filter dropdown
        $channels = $db->fetchAll("SELECT channel_id, channel_name FROM {$prefix}channels WHERE is_deleted = 0 ORDER BY channel_name");
        
        // Get statuses for filter dropdown
        $statuses = $db->fetchAll("SELECT status_id, status_key, status_name FROM {$prefix}task_statuses WHERE is_deleted = 0 ORDER BY status_name");
        
        // Get priorities for filter dropdown
        $priorities = $db->fetchAll("SELECT priority_id, priority_key, priority_name, priority_level FROM {$prefix}task_priorities WHERE is_deleted = 0 ORDER BY priority_level ASC");
        
        // Start HTML output
        $html .= '<div class="admin-section-tasks">';
        
        // Filters
        $html .= '<div class="admin-filters" style="margin-bottom: 20px; padding: 15px; background: #f5f5f5; border-radius: 4px;">';
        $html .= '<form method="get" action="admin.php" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: end;">';
        $html .= '<input type="hidden" name="section" value="tasks">';
        
        // Channel filter
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Channel:</label>';
        $html .= '<select name="channel" style="padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '<option value="0"' . ($filter_channel === 0 ? ' selected' : '') . '>All Channels</option>';
        foreach ($channels as $ch) {
            $selected = ($filter_channel === (int) $ch['channel_id']) ? ' selected' : '';
            $html .= '<option value="' . (int) $ch['channel_id'] . '"' . $selected . '>' . htmlspecialchars($ch['channel_name']) . '</option>';
        }
        $html .= '</select>';
        $html .= '</div>';
        
        // Status filter
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Status:</label>';
        $html .= '<select name="status" style="padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '<option value="0">All Statuses</option>';
        foreach ($statuses as $s) {
            $selected = ($filter_status === (int) $s['status_id']) ? ' selected' : '';
            $html .= '<option value="' . (int) $s['status_id'] . '"' . $selected . '>' . htmlspecialchars($s['status_name']) . '</option>';
        }
        $html .= '</select>';
        $html .= '</div>';
        
        // Priority filter
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Priority:</label>';
        $html .= '<select name="priority" style="padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '<option value="0">All Priorities</option>';
        foreach ($priorities as $p) {
            $selected = ($filter_priority === (int) $p['priority_id']) ? ' selected' : '';
            $html .= '<option value="' . (int) $p['priority_id'] . '"' . $selected . '>' . htmlspecialchars($p['priority_name']) . '</option>';
        }
        $html .= '</select>';
        $html .= '</div>';
        
        $html .= '<div>';
        $html .= '<button type="submit" style="padding: 6px 15px; background: #0066cc; color: white; border: none; border-radius: 3px; cursor: pointer; margin-top: 24px;">Filter</button>';
        $html .= '</div>';
        
        $html .= '</form>';
        $html .= '</div>';
        
        // Tasks count
        $html .= '<p style="margin-bottom: 15px;"><strong>' . count($tasks) . '</strong> task(s) found</p>';
        
        if (empty($tasks)) {
            $html .= '<p class="admin-empty">No tasks found matching the selected filters.</p>';
        } else {
            // Tasks table
            $html .= '<div style="overflow-x: auto;">';
            $html .= '<table class="admin-table" style="width: 100%; border-collapse: collapse; background: white;">';
            $html .= '<thead>';
            $html .= '<tr style="background: #f0f0f0; border-bottom: 2px solid #ddd;">';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Task ID</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Title</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Channel</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Owner</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Status</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Priority</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Created</th>';
            $html .= '<th style="padding: 10px; text-align: left; font-weight: 600;">Duration</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            
            foreach ($tasks as $task) {
                // Status badge color - use status_key for color mapping
                $status_key = isset($task['status_key']) ? $task['status_key'] : '';
                $status_colors = array(
                    'pending' => '#ffc107',
                    'active' => '#28a745',
                    'in_progress' => '#28a745',
                    'blocked' => '#dc3545',
                    'completed' => '#6c757d',
                    'archived' => '#6c757d',
                    'cancelled' => '#6c757d'
                );
                $status_color = isset($status_colors[$status_key]) ? $status_colors[$status_key] : '#6c757d';
                
                // Priority badge color - use priority_key for color mapping
                $priority_key = isset($task['priority_key']) ? $task['priority_key'] : '';
                $priority_colors = array(
                    'critical' => '#dc3545',
                    'high' => '#fd7e14',
                    'normal' => '#0066cc',
                    'low' => '#6c757d'
                );
                $priority_color = isset($priority_colors[$priority_key]) ? $priority_colors[$priority_key] : '#6c757d';
                
                // Format created date
                $created = self::formatYmdhis($task['created_ymdhis']);
                
                // Format duration (convert seconds to minutes)
                $duration_display = '-';
                if (isset($task['estimated_duration_seconds']) && $task['estimated_duration_seconds'] > 0) {
                    $minutes = round($task['estimated_duration_seconds'] / 60);
                    $duration_display = $minutes . ' min';
                }
                
                $html .= '<tr style="border-bottom: 1px solid #eee;">';
                $html .= '<td style="padding: 10px;">' . htmlspecialchars($task['task_key'] ?: $task['task_id']) . '</td>';
                $html .= '<td style="padding: 10px;"><strong>' . htmlspecialchars($task['title'] ?: '(No title)') . '</strong>';
                if ($task['description']) {
                    $desc = substr($task['description'], 0, 100);
                    if (strlen($task['description']) > 100) $desc .= '...';
                    $html .= '<br><small style="color: #666;">' . htmlspecialchars($desc) . '</small>';
                }
                $html .= '</td>';
                $html .= '<td style="padding: 10px;">' . htmlspecialchars($task['channel_name'] ?: 'Channel ' . $task['channel_id']) . '</td>';
                $html .= '<td style="padding: 10px;">' . htmlspecialchars($task['owner_name'] ?: 'Actor ' . $task['owner_actor_id']) . '</td>';
                $html .= '<td style="padding: 10px;"><span style="display: inline-block; padding: 3px 8px; background: ' . $status_color . '; color: white; border-radius: 3px; font-size: 12px; font-weight: 500;">' . htmlspecialchars($task['status_name'] ?: 'Unknown') . '</span></td>';
                $html .= '<td style="padding: 10px;"><span style="display: inline-block; padding: 3px 8px; background: ' . $priority_color . '; color: white; border-radius: 3px; font-size: 12px; font-weight: 500;">' . htmlspecialchars($task['priority_name'] ?: 'Unknown') . '</span></td>';
                $html .= '<td style="padding: 10px;">' . htmlspecialchars($created) . '</td>';
                $html .= '<td style="padding: 10px;">' . $duration_display . '</td>';
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
