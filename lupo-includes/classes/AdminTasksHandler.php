<?php
/**
 * Admin Tasks Handler
 *
 * Displays tasks from lupo_tasks table with filtering by channel, status, and priority.
 * Uses canonical schema: task_status and task_priority (varchar) on lupo_tasks; no lupo_task_statuses or lupo_task_priorities.
 *
 * @package Lupopedia
 * @version 4.0.67
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

        // Get filter parameters (channel is int; status and priority are varchar from lupo_tasks)
        $filter_channel = isset($_GET['channel']) ? (int) $_GET['channel'] : 0;
        $filter_status = isset($_GET['status']) ? trim((string) $_GET['status']) : '';
        $filter_priority = isset($_GET['priority']) ? trim((string) $_GET['priority']) : '';

        // Build query - canonical lupo_tasks has task_status, task_priority (varchar), no status_id/priority_id
        $where = array('t.is_deleted = 0');
        $params = array();

        if ($filter_channel > 0) {
            $where[] = 't.channel_id = :channel_id';
            $params['channel_id'] = $filter_channel;
        }

        if ($filter_status !== '') {
            $where[] = 't.task_status = :task_status';
            $params['task_status'] = $filter_status;
        }

        if ($filter_priority !== '') {
            $where[] = 't.task_priority = :task_priority';
            $params['task_priority'] = $filter_priority;
        }

        $where_sql = implode(' AND ', $where);

        // Get tasks - no joins to lupo_task_statuses or lupo_task_priorities (removed in v4.0.55)
        $sql = "SELECT
                    t.task_id,
                    t.channel_id,
                    t.owner_actor_id,
                    t.task_key,
                    t.title,
                    t.description,
                    t.task_status,
                    t.task_priority,
                    t.created_ymdhis,
                    t.updated_ymdhis,
                    t.started_ymdhis,
                    t.completed_ymdhis,
                    t.estimated_duration_seconds,
                    c.channel_name,
                    a.name as owner_name
                FROM {$prefix}tasks t
                LEFT JOIN {$prefix}channels c ON t.channel_id = c.channel_id
                LEFT JOIN {$prefix}actors a ON t.owner_actor_id = a.actor_id
                WHERE {$where_sql}
                ORDER BY t.created_ymdhis DESC
                LIMIT 100";

        $tasks = $db->fetchAll($sql, $params);

        // Get channels for filter dropdown
        $channels = $db->fetchAll("SELECT channel_id, channel_name FROM {$prefix}channels WHERE is_deleted = 0 ORDER BY channel_name");

        // Get distinct statuses from lupo_tasks (canonical: no lupo_task_statuses table)
        $statuses = $db->fetchAll("SELECT DISTINCT task_status as status_value FROM {$prefix}tasks WHERE is_deleted = 0 AND task_status IS NOT NULL AND task_status != '' ORDER BY task_status");
        foreach ($statuses as $i => $row) {
            $statuses[$i]['status_name'] = $row['status_value'];
        }

        // Get distinct priorities from lupo_tasks (canonical: no lupo_task_priorities table)
        $priorities = $db->fetchAll("SELECT DISTINCT task_priority as priority_value FROM {$prefix}tasks WHERE is_deleted = 0 AND task_priority IS NOT NULL AND task_priority != '' ORDER BY task_priority");
        foreach ($priorities as $i => $row) {
            $priorities[$i]['priority_name'] = $row['priority_value'];
        }

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

        // Status filter (varchar task_status)
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Status:</label>';
        $html .= '<select name="status" style="padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '<option value="">All Statuses</option>';
        foreach ($statuses as $s) {
            $val = isset($s['status_value']) ? $s['status_value'] : '';
            $selected = ($filter_status !== '' && $filter_status === $val) ? ' selected' : '';
            $html .= '<option value="' . htmlspecialchars($val) . '"' . $selected . '>' . htmlspecialchars(isset($s['status_name']) ? $s['status_name'] : $val) . '</option>';
        }
        $html .= '</select>';
        $html .= '</div>';

        // Priority filter (varchar task_priority)
        $html .= '<div>';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500;">Priority:</label>';
        $html .= '<select name="priority" style="padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px;">';
        $html .= '<option value="">All Priorities</option>';
        foreach ($priorities as $p) {
            $val = isset($p['priority_value']) ? $p['priority_value'] : '';
            $selected = ($filter_priority !== '' && $filter_priority === $val) ? ' selected' : '';
            $html .= '<option value="' . htmlspecialchars($val) . '"' . $selected . '>' . htmlspecialchars(isset($p['priority_name']) ? $p['priority_name'] : $val) . '</option>';
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
                // Status display - task_status is varchar on lupo_tasks
                $status_key = isset($task['task_status']) ? $task['task_status'] : '';
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

                // Priority display - task_priority is varchar on lupo_tasks
                $priority_key = isset($task['task_priority']) ? $task['task_priority'] : '';
                $priority_colors = array(
                    'critical' => '#dc3545',
                    'high' => '#fd7e14',
                    'normal' => '#0066cc',
                    'low' => '#6c757d'
                );
                $priority_color = isset($priority_colors[$priority_key]) ? $priority_colors[$priority_key] : '#6c757d';

                // Format created date
                $created = self::formatYmdhis(isset($task['created_ymdhis']) ? $task['created_ymdhis'] : null);

                // Format duration (convert seconds to minutes)
                $duration_display = '-';
                if (isset($task['estimated_duration_seconds']) && $task['estimated_duration_seconds'] > 0) {
                    $minutes = round($task['estimated_duration_seconds'] / 60);
                    $duration_display = $minutes . ' min';
                }

                $status_label = ($status_key !== '' && $status_key !== null) ? $status_key : '—';
                $priority_label = ($priority_key !== '' && $priority_key !== null) ? $priority_key : '—';

                $html .= '<tr style="border-bottom: 1px solid #eee;">';
                $html .= '<td style="padding: 10px;">' . htmlspecialchars(isset($task['task_key']) && $task['task_key'] ? $task['task_key'] : $task['task_id']) . '</td>';
                $html .= '<td style="padding: 10px;"><strong>' . htmlspecialchars(isset($task['title']) && $task['title'] ? $task['title'] : '(No title)') . '</strong>';
                if (!empty($task['description'])) {
                    $desc = substr($task['description'], 0, 100);
                    if (strlen($task['description']) > 100) {
                        $desc .= '...';
                    }
                    $html .= '<br><small style="color: #666;">' . htmlspecialchars($desc) . '</small>';
                }
                $html .= '</td>';
                $html .= '<td style="padding: 10px;">' . htmlspecialchars(isset($task['channel_name']) && $task['channel_name'] ? $task['channel_name'] : 'Channel ' . $task['channel_id']) . '</td>';
                $html .= '<td style="padding: 10px;">' . htmlspecialchars(isset($task['owner_name']) && $task['owner_name'] ? $task['owner_name'] : 'Actor ' . $task['owner_actor_id']) . '</td>';
                $html .= '<td style="padding: 10px;"><span style="display: inline-block; padding: 3px 8px; background: ' . $status_color . '; color: white; border-radius: 3px; font-size: 12px; font-weight: 500;">' . htmlspecialchars($status_label) . '</span></td>';
                $html .= '<td style="padding: 10px;"><span style="display: inline-block; padding: 3px 8px; background: ' . $priority_color . '; color: white; border-radius: 3px; font-size: 12px; font-weight: 500;">' . htmlspecialchars($priority_label) . '</span></td>';
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
     * @param int|string|null $ymdhis YYYYMMDDHHIISS timestamp
     * @return string Formatted date
     */
    private static function formatYmdhis($ymdhis)
    {
        if (!$ymdhis) {
            return '-';
        }

        $str = (string) $ymdhis;
        if (strlen($str) < 14) {
            return $str;
        }

        $year = substr($str, 0, 4);
        $month = substr($str, 4, 2);
        $day = substr($str, 6, 2);
        $hour = substr($str, 8, 2);
        $min = substr($str, 10, 2);

        return "{$year}-{$month}-{$day} {$hour}:{$min}";
    }
}
