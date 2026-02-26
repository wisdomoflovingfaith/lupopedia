<?php
/**
 * Admin Channels Handler
 * 
 * Displays channels with their associated tasks and broadcast messages.
 * Shows channel details, task counts, and recent broadcasts.
 * 
 * @package Lupopedia
 * @version 4.0.46
 */

class AdminChannelsHandler
{
    /**
     * Render the channels admin section
     * 
     * @param PDO_DB $db Database connection
     * @param string $prefix Table prefix
     * @param string $base Base URL path
     * @return string HTML output
     */
    public static function render($db, $prefix, $base)
    {
        $html = '';
        
        // Get all channels with task and broadcast counts
        $sql = "SELECT 
                    c.channel_id,
                    c.channel_name,
                    c.channel_key,
                    c.channel_type,
                    c.description,
                    c.created_ymdhis,
                    c.status_flag,
                    COUNT(DISTINCT t.task_id) as task_count,
                    COUNT(DISTINCT CASE WHEN t.is_deleted = 0 THEN t.task_id END) as active_task_count
                FROM {$prefix}channels c
                LEFT JOIN {$prefix}tasks t ON c.channel_id = t.channel_id
                WHERE c.is_deleted = 0
                GROUP BY c.channel_id, c.channel_name, c.channel_key, c.channel_type, c.description, c.created_ymdhis, c.status_flag
                ORDER BY c.channel_id ASC";
        
        $channels = $db->fetchAll($sql);
        
        // Start HTML output
        $html .= '<div class="admin-section-channels">';
        $html .= '<h2 style="margin-top: 0;">Channels <span style="background: #e2e8f0; color: #475569; padding: 2px 10px; border-radius: 20px; font-size: 0.875rem;">' . count($channels) . ' Total</span></h2>';
        
        if (empty($channels)) {
            $html .= '<p class="admin-empty">No channels found.</p>';
        } else {
            // Channels list
            foreach ($channels as $channel) {
                $channel_id = (int) $channel['channel_id'];
                
                // Get tasks for this channel
                $tasks_sql = "SELECT 
                                t.task_id,
                                t.task_key,
                                t.title,
                                t.status_id,
                                t.priority_id,
                                t.created_ymdhis,
                                ts.status_name,
                                ts.status_key,
                                tp.priority_name,
                                tp.priority_key
                            FROM {$prefix}tasks t
                            LEFT JOIN {$prefix}task_statuses ts ON t.status_id = ts.status_id
                            LEFT JOIN {$prefix}task_priorities tp ON t.priority_id = tp.priority_id
                            WHERE t.channel_id = :channel_id AND t.is_deleted = 0
                            ORDER BY tp.priority_level ASC, t.created_ymdhis DESC
                            LIMIT 10";
                
                $tasks = $db->fetchAll($tasks_sql, array('channel_id' => $channel_id));
                
                // Get broadcast messages for this channel (from database)
                $broadcasts = self::getBroadcastMessagesFromDB($db, $prefix, $channel_id);
                
                // Channel card
                $html .= '<div style="margin-bottom: 30px; padding: 20px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">';
                
                // Channel header
                $html .= '<div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">';
                $html .= '<div>';
                $html .= '<h3 style="margin: 0 0 5px 0; font-size: 1.25rem;">';
                $html .= '<span style="color: #0f172a;">' . htmlspecialchars($channel['channel_name']) . '</span>';
                $html .= ' <span style="color: #94a3b8; font-size: 0.875rem; font-weight: normal;">(ID: ' . $channel_id . ')</span>';
                $html .= '</h3>';
                $html .= '<div style="font-size: 0.875rem; color: #64748b;">';
                $html .= '<code style="background: #f1f5f9; padding: 2px 6px; border-radius: 3px;">' . htmlspecialchars($channel['channel_key']) . '</code>';
                $html .= ' • <span style="text-transform: capitalize;">' . htmlspecialchars($channel['channel_type']) . '</span>';
                if ($channel['status_flag']) {
                    $html .= ' • <span style="color: #16a34a; font-weight: 500;">Active</span>';
                } else {
                    $html .= ' • <span style="color: #dc2626;">Inactive</span>';
                }
                $html .= '</div>';
                if ($channel['description']) {
                    $html .= '<p style="margin: 8px 0 0 0; color: #475569; font-size: 0.875rem;">' . htmlspecialchars($channel['description']) . '</p>';
                }
                $html .= '</div>';
                
                // Stats badges
                $html .= '<div style="display: flex; gap: 10px;">';
                $html .= '<div style="text-align: center; padding: 8px 12px; background: #f0f9ff; border-radius: 6px;">';
                $html .= '<div style="font-size: 1.25rem; font-weight: 600; color: #0369a1;">' . (int) $channel['active_task_count'] . '</div>';
                $html .= '<div style="font-size: 0.75rem; color: #64748b;">Tasks</div>';
                $html .= '</div>';
                $html .= '<div style="text-align: center; padding: 8px 12px; background: #fef3c7; border-radius: 6px;">';
                $html .= '<div style="font-size: 1.25rem; font-weight: 600; color: #d97706;">' . count($broadcasts) . '</div>';
                $html .= '<div style="font-size: 0.75rem; color: #64748b;">Broadcasts</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                
                // Tasks section
                $html .= '<div style="margin-bottom: 20px;">';
                $html .= '<h4 style="margin: 0 0 10px 0; font-size: 1rem; color: #334155; display: flex; align-items: center; gap: 8px;">';
                $html .= '<span>📋 Tasks</span>';
                if (count($tasks) > 0) {
                    $html .= '<span style="background: #e0e7ff; color: #4338ca; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem;">' . count($tasks) . '</span>';
                }
                $html .= '</h4>';
                
                if (empty($tasks)) {
                    $html .= '<p style="color: #94a3b8; font-size: 0.875rem; margin: 0;">No tasks on this channel.</p>';
                } else {
                    $html .= '<div style="overflow-x: auto;">';
                    $html .= '<table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">';
                    $html .= '<thead>';
                    $html .= '<tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">';
                    $html .= '<th style="padding: 8px; text-align: left; font-weight: 600; color: #64748b;">Task</th>';
                    $html .= '<th style="padding: 8px; text-align: left; font-weight: 600; color: #64748b;">Title</th>';
                    $html .= '<th style="padding: 8px; text-align: center; font-weight: 600; color: #64748b;">Status</th>';
                    $html .= '<th style="padding: 8px; text-align: center; font-weight: 600; color: #64748b;">Priority</th>';
                    $html .= '<th style="padding: 8px; text-align: left; font-weight: 600; color: #64748b;">Created</th>';
                    $html .= '</tr>';
                    $html .= '</thead>';
                    $html .= '<tbody>';
                    
                    foreach ($tasks as $task) {
                        // Status color
                        $status_key = isset($task['status_key']) ? $task['status_key'] : '';
                        $status_colors = array(
                            'pending' => '#fbbf24',
                            'active' => '#10b981',
                            'in_progress' => '#10b981',
                            'blocked' => '#ef4444',
                            'completed' => '#6b7280',
                            'archived' => '#6b7280',
                            'cancelled' => '#6b7280'
                        );
                        $status_color = isset($status_colors[$status_key]) ? $status_colors[$status_key] : '#6b7280';
                        
                        // Priority color
                        $priority_key = isset($task['priority_key']) ? $task['priority_key'] : '';
                        $priority_colors = array(
                            'critical' => '#dc2626',
                            'high' => '#f59e0b',
                            'normal' => '#3b82f6',
                            'low' => '#6b7280'
                        );
                        $priority_color = isset($priority_colors[$priority_key]) ? $priority_colors[$priority_key] : '#6b7280';
                        
                        $html .= '<tr style="border-bottom: 1px solid #f1f5f9;">';
                        $html .= '<td style="padding: 8px;"><code style="font-size: 0.75rem; background: #f1f5f9; padding: 2px 6px; border-radius: 3px;">' . htmlspecialchars($task['task_key'] ?: $task['task_id']) . '</code></td>';
                        $html .= '<td style="padding: 8px;">' . htmlspecialchars($task['title'] ?: '(No title)') . '</td>';
                        $html .= '<td style="padding: 8px; text-align: center;"><span style="display: inline-block; padding: 2px 8px; background: ' . $status_color . '; color: white; border-radius: 4px; font-size: 0.75rem; font-weight: 500;">' . htmlspecialchars($task['status_name'] ?: 'Unknown') . '</span></td>';
                        $html .= '<td style="padding: 8px; text-align: center;"><span style="display: inline-block; padding: 2px 8px; background: ' . $priority_color . '; color: white; border-radius: 4px; font-size: 0.75rem; font-weight: 500;">' . htmlspecialchars($task['priority_name'] ?: 'Unknown') . '</span></td>';
                        $html .= '<td style="padding: 8px;">' . htmlspecialchars(self::formatYmdhis($task['created_ymdhis'])) . '</td>';
                        $html .= '</tr>';
                    }
                    
                    $html .= '</tbody>';
                    $html .= '</table>';
                    $html .= '</div>';
                    
                    if (count($tasks) >= 10) {
                        $html .= '<p style="margin: 10px 0 0 0; font-size: 0.75rem; color: #64748b;"><a href="' . htmlspecialchars($base . '/admin.php?section=tasks&channel=' . $channel_id) . '" style="color: #2563eb;">View all tasks for this channel →</a></p>';
                    }
                }
                $html .= '</div>';
                
                // Broadcasts section
                $html .= '<div>';
                $html .= '<h4 style="margin: 0 0 10px 0; font-size: 1rem; color: #334155; display: flex; align-items: center; gap: 8px;">';
                $html .= '<span>📢 Broadcast Messages</span>';
                if (count($broadcasts) > 0) {
                    $html .= '<span style="background: #fef3c7; color: #d97706; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem;">' . count($broadcasts) . '</span>';
                }
                $html .= '</h4>';
                
                if (empty($broadcasts)) {
                    $html .= '<p style="color: #94a3b8; font-size: 0.875rem; margin: 0;">No broadcast messages on this channel.</p>';
                } else {
                    $html .= '<div style="display: grid; gap: 10px;">';
                    
                    foreach ($broadcasts as $broadcast) {
                        $html .= '<div style="padding: 12px; background: #fefce8; border-left: 3px solid #eab308; border-radius: 4px;">';
                        $html .= '<div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">';
                        $html .= '<div style="font-weight: 600; color: #713f12; font-size: 0.875rem;">' . htmlspecialchars($broadcast['message_type']) . '</div>';
                        $html .= '<div style="font-size: 0.75rem; color: #a16207;">' . htmlspecialchars($broadcast['date']) . '</div>';
                        $html .= '</div>';
                        
                        // Show message content (first 500 chars)
                        if ($broadcast['message_text']) {
                            $message = $broadcast['message_text'];
                            $preview = strlen($message) > 500 ? substr($message, 0, 500) . '...' : $message;
                            $html .= '<div style="color: #854d0e; font-size: 0.8125rem; white-space: pre-wrap; margin-bottom: 8px;">' . htmlspecialchars($preview) . '</div>';
                        }
                        
                        $html .= '<div style="margin-top: 5px; font-size: 0.75rem; color: #a16207;">';
                        $html .= 'From: <strong>Actor ' . htmlspecialchars($broadcast['from_actor_id']) . '</strong>';
                        if ($broadcast['to_actor_id'] && $broadcast['to_actor_id'] != 0) {
                            $html .= ' → To: <strong>Actor ' . htmlspecialchars($broadcast['to_actor_id']) . '</strong>';
                        }
                        if ($broadcast['priority']) {
                            $html .= ' • Priority: <strong>' . htmlspecialchars($broadcast['priority']) . '</strong>';
                        }
                        $html .= '</div>';
                        $html .= '</div>';
                    }
                    
                    $html .= '</div>';
                }
                $html .= '</div>';
                
                $html .= '</div>'; // End channel card
            }
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Get broadcast messages for a channel from database
     * 
     * @param PDO_DB $db Database connection
     * @param string $prefix Table prefix
     * @param int $channel_id Channel ID
     * @return array Array of broadcast message info
     */
    private static function getBroadcastMessagesFromDB($db, $prefix, $channel_id)
    {
        $broadcasts = array();
        
        try {
            $sql = "SELECT 
                        dialog_message_id,
                        channel_id,
                        from_actor_id,
                        to_actor_id,
                        message_type,
                        message_text,
                        message_body,
                        mood_rgb,
                        created_ymdhis,
                        updated_ymdhis
                    FROM {$prefix}dialog_doctrine
                    WHERE channel_id = :channel_id 
                    AND is_deleted = 0
                    ORDER BY created_ymdhis DESC
                    LIMIT 10";
            
            $results = $db->fetchAll($sql, array('channel_id' => $channel_id));
            
            foreach ($results as $row) {
                $broadcasts[] = array(
                    'message_id' => $row['dialog_message_id'],
                    'message_type' => ucfirst($row['message_type']),
                    'message_text' => $row['message_body'] ?: $row['message_text'],
                    'from_actor_id' => $row['from_actor_id'],
                    'to_actor_id' => $row['to_actor_id'],
                    'priority' => null,
                    'visibility' => null,
                    'date' => self::formatYmdhis($row['created_ymdhis'])
                );
            }
        } catch (Exception $e) {
            // If database query fails, fall back to filesystem
            return self::getBroadcastMessages($channel_id);
        }
        
        // If no broadcasts in database, try filesystem as fallback
        if (empty($broadcasts)) {
            return self::getBroadcastMessages($channel_id);
        }
        
        return $broadcasts;
    }
    
    /**
     * Get broadcast messages for a channel from filesystem (fallback)
     * 
     * @param int $channel_id Channel ID
     * @return array Array of broadcast message info
     */
    private static function getBroadcastMessages($channel_id)
    {
        $broadcasts = array();
        $broadcasts_dir = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH . '/channels/' . $channel_id . '/broadcasts' : '';
        
        if (!$broadcasts_dir || !is_dir($broadcasts_dir)) {
            return $broadcasts;
        }
        
        $files = glob($broadcasts_dir . '/*.md');
        if (!$files) {
            return $broadcasts;
        }
        
        // Sort by filename (which includes timestamp) descending
        rsort($files);
        
        // Limit to 5 most recent
        $files = array_slice($files, 0, 5);
        
        foreach ($files as $file) {
            $filename = basename($file);
            
            // Parse filename: YYYYMMDDHHIISS_fromActorId_toActorId_channelId_slug.md
            $parts = explode('_', $filename);
            if (count($parts) < 4) {
                continue;
            }
            
            $timestamp = $parts[0];
            $from_actor_id = isset($parts[1]) ? $parts[1] : '0';
            $to_actor_id = isset($parts[2]) ? $parts[2] : '0';
            
            // Read first few lines to get title
            $content = file_get_contents($file);
            $lines = explode("\n", $content);
            $title = '';
            $preview = '';
            
            // Look for first heading or first non-empty line
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || strpos($line, '---') === 0 || strpos($line, 'wolfie.headers') !== false || strpos($line, 'flip.footer') !== false) {
                    continue;
                }
                if (strpos($line, '#') === 0) {
                    $title = trim(str_replace('#', '', $line));
                    break;
                } elseif (!$title && strlen($line) > 0) {
                    $title = substr($line, 0, 100);
                    break;
                }
            }
            
            // Get preview (first paragraph after title)
            $in_content = false;
            foreach ($lines as $line) {
                $line = trim($line);
                if ($in_content && !empty($line) && strpos($line, '#') !== 0) {
                    $preview = substr($line, 0, 150);
                    if (strlen($line) > 150) {
                        $preview .= '...';
                    }
                    break;
                }
                if (!empty($title) && strpos($line, $title) !== false) {
                    $in_content = true;
                }
            }
            
            $broadcasts[] = array(
                'filename' => $filename,
                'timestamp' => $timestamp,
                'date' => self::formatYmdhis($timestamp),
                'from_actor_id' => $from_actor_id,
                'to_actor_id' => $to_actor_id !== '0' ? $to_actor_id : '',
                'message_type' => 'Broadcast (File)',
                'message_text' => $preview,
                'priority' => null
            );
        }
        
        return $broadcasts;
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
