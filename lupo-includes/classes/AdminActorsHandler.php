<?php
/**
 * Admin Actors Handler
 * 
 * Displays all actors (humans and AI agents) with session activity.
 * 
 * @package Lupopedia
 * @subpackage Admin
 * @version 4.0.46
 */

class AdminActorsHandler
{
    /**
     * Render the actors admin interface
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
        $filter_type = isset($_GET['type']) ? $_GET['type'] : 'all';
        $filter_status = isset($_GET['status']) ? $_GET['status'] : 'all';
        
        // Page header
        $html .= '<div style="margin-bottom: 30px;">';
        $html .= '<h2 style="margin: 0 0 10px 0; font-size: 1.5rem; color: #1e293b;">Actors</h2>';
        $html .= '<p style="margin: 0; color: #64748b;">View all actors (humans and AI agents) with their session activity.</p>';
        $html .= '</div>';
        
        // Filters
        $html .= '<div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px;">';
        $html .= '<form method="get" action="' . htmlspecialchars($base . '/admin.php') . '" style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap;">';
        $html .= '<input type="hidden" name="section" value="actors">';
        
        // Type filter
        $html .= '<div style="flex: 1; min-width: 200px;">';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500; color: #334155; font-size: 0.875rem;">Actor Type</label>';
        $html .= '<select name="type" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;">';
        $html .= '<option value="all"' . ($filter_type === 'all' ? ' selected' : '') . '>All Types</option>';
        $html .= '<option value="human"' . ($filter_type === 'human' ? ' selected' : '') . '>Humans (ID ≥ 10000)</option>';
        $html .= '<option value="ai"' . ($filter_type === 'ai' ? ' selected' : '') . '>AI Agents (ID < 10000)</option>';
        $html .= '</select>';
        $html .= '</div>';
        
        // Status filter
        $html .= '<div style="flex: 1; min-width: 200px;">';
        $html .= '<label style="display: block; margin-bottom: 5px; font-weight: 500; color: #334155; font-size: 0.875rem;">Status</label>';
        $html .= '<select name="status" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;">';
        $html .= '<option value="all"' . ($filter_status === 'all' ? ' selected' : '') . '>All Status</option>';
        $html .= '<option value="active"' . ($filter_status === 'active' ? ' selected' : '') . '>Active</option>';
        $html .= '<option value="inactive"' . ($filter_status === 'inactive' ? ' selected' : '') . '>Inactive</option>';
        $html .= '</select>';
        $html .= '</div>';
        
        // Submit button
        $html .= '<div>';
        $html .= '<button type="submit" style="padding: 8px 20px; background: #3b82f6; color: white; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer;">Filter</button>';
        $html .= '</div>';
        
        $html .= '</form>';
        $html .= '</div>';
        
        // Get actors with session info
        $actors = self::getActors($db, $prefix, $filter_type, $filter_status);
        
        // Results count
        $html .= '<div style="margin-bottom: 15px; color: #64748b; font-size: 0.875rem;">';
        $html .= 'Showing ' . count($actors) . ' actor' . (count($actors) !== 1 ? 's' : '');
        $html .= '</div>';
        
        // Actors table
        if (empty($actors)) {
            $html .= '<div style="background: white; padding: 40px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); text-align: center;">';
            $html .= '<p style="color: #94a3b8; margin: 0;">No actors found matching the selected filters.</p>';
            $html .= '</div>';
        } else {
            $html .= '<div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">';
            $html .= '<div style="overflow-x: auto;">';
            $html .= '<table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">';
            
            // Table header
            $html .= '<thead>';
            $html .= '<tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">';
            $html .= '<th style="padding: 12px; text-align: left; font-weight: 600; color: #475569;">ID</th>';
            $html .= '<th style="padding: 12px; text-align: left; font-weight: 600; color: #475569;">Name</th>';
            $html .= '<th style="padding: 12px; text-align: left; font-weight: 600; color: #475569;">Type</th>';
            $html .= '<th style="padding: 12px; text-align: left; font-weight: 600; color: #475569;">Email</th>';
            $html .= '<th style="padding: 12px; text-align: center; font-weight: 600; color: #475569;">Status</th>';
            $html .= '<th style="padding: 12px; text-align: left; font-weight: 600; color: #475569;">Last Session</th>';
            $html .= '<th style="padding: 12px; text-align: left; font-weight: 600; color: #475569;">Last Activity</th>';
            $html .= '<th style="padding: 12px; text-align: left; font-weight: 600; color: #475569;">Created</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            
            // Table body
            $html .= '<tbody>';
            
            foreach ($actors as $actor) {
                $is_ai = $actor['actor_id'] < 10000;
                $is_active = $actor['is_active'] == 1;
                $has_session = !empty($actor['last_session_start']);
                
                $html .= '<tr style="border-bottom: 1px solid #f1f5f9;">';
                
                // ID
                $html .= '<td style="padding: 12px;">';
                $html .= '<code style="font-size: 0.8125rem; background: #f1f5f9; padding: 2px 6px; border-radius: 3px; font-weight: 500;">';
                $html .= htmlspecialchars($actor['actor_id']);
                $html .= '</code>';
                $html .= '</td>';
                
                // Name
                $html .= '<td style="padding: 12px;">';
                $html .= '<div style="font-weight: 500; color: #1e293b;">' . htmlspecialchars($actor['name'] ?: '(No name)') . '</div>';
                if (!empty($actor['username'])) {
                    $html .= '<div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">@' . htmlspecialchars($actor['username']) . '</div>';
                } elseif (!empty($actor['slug'])) {
                    $html .= '<div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">' . htmlspecialchars($actor['slug']) . '</div>';
                }
                $html .= '</td>';
                
                // Type
                $html .= '<td style="padding: 12px;">';
                if ($is_ai) {
                    $html .= '<span style="display: inline-block; padding: 3px 10px; background: #dbeafe; color: #1e40af; border-radius: 12px; font-size: 0.75rem; font-weight: 500;">🤖 AI Agent</span>';
                } else {
                    $html .= '<span style="display: inline-block; padding: 3px 10px; background: #dcfce7; color: #166534; border-radius: 12px; font-size: 0.75rem; font-weight: 500;">👤 Human</span>';
                }
                $html .= '</td>';
                
                // Email
                $html .= '<td style="padding: 12px; color: #64748b;">';
                $html .= htmlspecialchars($actor['email'] ?: '—');
                $html .= '</td>';
                
                // Status
                $html .= '<td style="padding: 12px; text-align: center;">';
                if ($is_active) {
                    $html .= '<span style="display: inline-block; padding: 3px 10px; background: #10b981; color: white; border-radius: 12px; font-size: 0.75rem; font-weight: 500;">Active</span>';
                } else {
                    $html .= '<span style="display: inline-block; padding: 3px 10px; background: #6b7280; color: white; border-radius: 12px; font-size: 0.75rem; font-weight: 500;">Inactive</span>';
                }
                $html .= '</td>';
                
                // Last Session
                $html .= '<td style="padding: 12px; color: #64748b;">';
                if ($has_session) {
                    $html .= htmlspecialchars(self::formatYmdhis($actor['last_session_start']));
                } else {
                    $html .= '<span style="color: #94a3b8;">No sessions</span>';
                }
                $html .= '</td>';
                
                // Last Activity
                $html .= '<td style="padding: 12px; color: #64748b;">';
                if ($has_session && $actor['last_activity']) {
                    $html .= htmlspecialchars(self::formatYmdhis($actor['last_activity']));
                } else {
                    $html .= '<span style="color: #94a3b8;">—</span>';
                }
                $html .= '</td>';
                
                // Created
                $html .= '<td style="padding: 12px; color: #64748b;">';
                $html .= htmlspecialchars(self::formatYmdhis($actor['created_ymdhis']));
                $html .= '</td>';
                
                $html .= '</tr>';
            }
            
            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>';
            $html .= '</div>';
        }
        
        return $html;
    }
    
    /**
     * Get actors with session information
     * 
     * @param PDO_DB $db Database connection
     * @param string $prefix Table prefix
     * @param string $filter_type Type filter (all, human, ai)
     * @param string $filter_status Status filter (all, active, inactive)
     * @return array Array of actor records
     */
    private static function getActors($db, $prefix, $filter_type, $filter_status)
    {
        $where_clauses = array('a.is_deleted = 0');
        $params = array();
        
        // Type filter
        if ($filter_type === 'human') {
            $where_clauses[] = 'a.actor_id >= 10000';
        } elseif ($filter_type === 'ai') {
            $where_clauses[] = 'a.actor_id < 10000';
        }
        
        // Status filter
        if ($filter_status === 'active') {
            $where_clauses[] = 'a.is_active = 1';
        } elseif ($filter_status === 'inactive') {
            $where_clauses[] = 'a.is_active = 0';
        }
        
        $where_sql = implode(' AND ', $where_clauses);
        
        $sql = "SELECT 
                    a.actor_id,
                    a.name,
                    a.slug,
                    a.actor_type,
                    a.is_agent,
                    a.is_active,
                    a.created_ymdhis,
                    a.updated_ymdhis,
                    au.username,
                    au.email,
                    s.created_ymdhis as last_session_start,
                    s.last_seen_ymdhis as last_activity
                FROM {$prefix}actors a
                LEFT JOIN {$prefix}auth_users au ON a.actor_id = au.auth_user_id
                LEFT JOIN (
                    SELECT 
                        actor_id,
                        created_ymdhis,
                        last_seen_ymdhis,
                        ROW_NUMBER() OVER (PARTITION BY actor_id ORDER BY created_ymdhis DESC) as rn
                    FROM {$prefix}sessions
                    WHERE is_deleted = 0
                ) s ON a.actor_id = s.actor_id AND s.rn = 1
                WHERE {$where_sql}
                ORDER BY a.actor_id ASC
                LIMIT 500";
        
        try {
            return $db->fetchAll($sql, $params);
        } catch (Exception $e) {
            // Fallback query without window function (for older MySQL/MariaDB)
            $sql = "SELECT 
                        a.actor_id,
                        a.name,
                        a.slug,
                        a.actor_type,
                        a.is_agent,
                        a.is_active,
                        a.created_ymdhis,
                        a.updated_ymdhis,
                        au.username,
                        au.email,
                        (SELECT created_ymdhis 
                         FROM {$prefix}sessions 
                         WHERE actor_id = a.actor_id AND is_deleted = 0 
                         ORDER BY created_ymdhis DESC 
                         LIMIT 1) as last_session_start,
                        (SELECT last_seen_ymdhis 
                         FROM {$prefix}sessions 
                         WHERE actor_id = a.actor_id AND is_deleted = 0 
                         ORDER BY created_ymdhis DESC 
                         LIMIT 1) as last_activity
                    FROM {$prefix}actors a
                    LEFT JOIN {$prefix}auth_users au ON a.actor_id = au.auth_user_id
                    WHERE {$where_sql}
                    ORDER BY a.actor_id ASC
                    LIMIT 500";
            
            return $db->fetchAll($sql, $params);
        }
    }
    
    /**
     * Format YMDHIS timestamp to human-readable format
     * 
     * @param int|string $ymdhis YYYYMMDDHHIISS timestamp
     * @return string Formatted date string
     */
    private static function formatYmdhis($ymdhis)
    {
        if (empty($ymdhis) || $ymdhis == 0) {
            return '—';
        }
        
        $str = (string) $ymdhis;
        if (strlen($str) !== 14) {
            return '—';
        }
        
        $year = substr($str, 0, 4);
        $month = substr($str, 4, 2);
        $day = substr($str, 6, 2);
        $hour = substr($str, 8, 2);
        $minute = substr($str, 10, 2);
        
        return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $minute;
    }
}
