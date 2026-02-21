<?php
/**
 * DialogMessageVerifier - Verify and list dialog messages with FLIP header support
 * 
 * PHP 5.3 compatible. PDO_DB only. No short arrays, no null coalescing.
 * 
 * @package Lupopedia
 */

class DialogMessageVerifier {

    protected $db;
    protected $prefix;

    /**
     * Constructor
     * 
     * @param PDO $db Database connection
     * @param string|null $prefix Table prefix (default: LUPO_TABLE_PREFIX or 'lupo_')
     */
    public function __construct($db, $prefix = null) {
        $this->db = $db;
        $this->prefix = $prefix !== null ? $prefix : (defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_');
    }

    /**
     * Get total count of messages
     * 
     * @return int
     */
    public function getTotalMessageCount() {
        $table = $this->prefix . 'dialog_messages';
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$table}");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get total count of threads
     * 
     * @return int
     */
    public function getTotalThreadCount() {
        $table = $this->prefix . 'dialog_threads';
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$table}");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get stored message count for a channel
     * 
     * @param int $channel_id
     * @return int|null
     */
    public function getStoredChannelMessageCount($channel_id) {
        $table = $this->prefix . 'dialog_channels';
        $stmt = $this->db->prepare("SELECT message_count FROM {$table} WHERE channel_id = ?");
        $stmt->execute(array($channel_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int) $row['message_count'] : null;
    }

    /**
     * Get actual message count for a channel
     * 
     * @param int $channel_id
     * @return int
     */
    public function getActualChannelMessageCount($channel_id) {
        $table = $this->prefix . 'dialog_messages';
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$table} WHERE channel_id = ?");
        $stmt->execute(array($channel_id));
        return (int) $stmt->fetchColumn();
    }

    /**
     * Extract forwarded_for from metadata JSON
     * 
     * @param string|null $metadata_json
     * @return string|null
     */
    public function extractForwardedFor($metadata_json) {
        if ($metadata_json === null || $metadata_json === '') {
            return null;
        }
        $data = json_decode($metadata_json, true);
        if (!is_array($data)) {
            return null;
        }
        // Check common paths for forwarded_for
        if (isset($data['x_lupo_forwarded_for'])) {
            return $data['x_lupo_forwarded_for'];
        }
        if (isset($data['forward_headers']['X-Lupo-Forwarded-For'])) {
            return $data['forward_headers']['X-Lupo-Forwarded-For'];
        }
        if (isset($data['forwarded_for'])) {
            return $data['forwarded_for'];
        }
        return null;
    }

    /**
     * Get all messages with full details
     * 
     * @param int $limit Maximum messages to return (0 = no limit)
     * @param int $offset Offset for pagination
     * @return array
     */
    public function getAllMessages($limit = 0, $offset = 0) {
        $table = $this->prefix . 'dialog_messages';
        $sql = "SELECT 
                dialog_message_id,
                dialog_thread_id,
                channel_id,
                from_actor_id,
                to_actor_id,
                message_text,
                message_type,
                metadata_json,
                mood_rgb,
                mood_framework,
                created_ymdhis,
                updated_ymdhis,
                is_deleted,
                deleted_ymdhis
            FROM {$table}
            ORDER BY dialog_message_id ASC";
        
        if ($limit > 0) {
            $sql .= " LIMIT " . (int) $limit . " OFFSET " . (int) $offset;
        }
        
        $stmt = $this->db->query($sql);
        $messages = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['forwarded_for'] = $this->extractForwardedFor($row['metadata_json']);
            $messages[] = $row;
        }
        return $messages;
    }

    /**
     * Get messages that originated from a specific actor (via forwarded_for)
     * 
     * @param int $actor_id
     * @return array
     */
    public function getMessagesByOrigin($actor_id) {
        $table = $this->prefix . 'dialog_messages';
        // Cannot efficiently query JSON in PHP 5.3 with PDO, so fetch all and filter
        // For large tables, consider adding a dedicated column in 4.0.25+
        $all = $this->getAllMessages();
        $filtered = array();
        foreach ($all as $msg) {
            if ($msg['forwarded_for'] == $actor_id) {
                $filtered[] = $msg;
            }
        }
        return $filtered;
    }

    /**
     * Generate verification report
     * 
     * @return array
     */
    public function generateVerificationReport() {
        $report = array(
            'total_messages' => $this->getTotalMessageCount(),
            'total_threads' => $this->getTotalThreadCount(),
            'channels' => array(),
            'messages_with_forwarded_for' => array(),
        );

        // Check channels 42 and 420 specifically
        foreach (array(42, 420) as $ch) {
            $actual = $this->getActualChannelMessageCount($ch);
            $stored = $this->getStoredChannelMessageCount($ch);
            $report['channels'][$ch] = array(
                'actual_count' => $actual,
                'stored_count' => $stored,
                'match' => ($actual === $stored),
            );
        }

        // Find all messages with forwarded_for
        $all = $this->getAllMessages();
        foreach ($all as $msg) {
            if ($msg['forwarded_for'] !== null) {
                $report['messages_with_forwarded_for'][] = array(
                    'id' => $msg['dialog_message_id'],
                    'forwarded_for' => $msg['forwarded_for'],
                    'from_actor' => $msg['from_actor_id'],
                    'channel' => $msg['channel_id'],
                );
            }
        }

        return $report;
    }

    /**
     * Render verification report as HTML (for admin display)
     * 
     * @return string
     */
    public function renderReportHtml() {
        $report = $this->generateVerificationReport();
        $html = '<div class="dialog-verification-report">';
        $html .= '<h3>Dialog Message Verification Report</h3>';
        
        $html .= '<table class="verification-table">';
        $html .= '<tr><th>Total Messages</th><td>' . $report['total_messages'] . '</td></tr>';
        $html .= '<tr><th>Total Threads</th><td>' . $report['total_threads'] . '</td></tr>';
        $html .= '</table>';

        $html .= '<h4>Channel Counts</h4>';
        $html .= '<table class="verification-table">';
        $html .= '<tr><th>Channel</th><th>Actual</th><th>Stored</th><th>Match</th></tr>';
        foreach ($report['channels'] as $ch => $data) {
            $match = $data['match'] ? '✅' : '❌';
            $html .= '<tr>';
            $html .= '<td>' . $ch . '</td>';
            $html .= '<td>' . $data['actual_count'] . '</td>';
            $html .= '<td>' . ($data['stored_count'] !== null ? $data['stored_count'] : 'NULL') . '</td>';
            $html .= '<td>' . $match . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        if (!empty($report['messages_with_forwarded_for'])) {
            $html .= '<h4>Messages with X-Lupo-Forwarded-For</h4>';
            $html .= '<table class="verification-table">';
            $html .= '<tr><th>ID</th><th>Forwarded For</th><th>From Actor</th><th>Channel</th></tr>';
            foreach ($report['messages_with_forwarded_for'] as $msg) {
                $html .= '<tr>';
                $html .= '<td>' . $msg['id'] . '</td>';
                $html .= '<td>' . $msg['forwarded_for'] . '</td>';
                $html .= '<td>' . $msg['from_actor'] . '</td>';
                $html .= '<td>' . $msg['channel'] . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
        }

        $html .= '</div>';
        return $html;
    }
}
