<?php
/**
 * Channels Controller - Admin Channel Viewing
 * Handles channel viewing functionality for admin panel
 * Doctrine-aligned integration for Lupopedia
 */

class ChannelsController
{

    protected $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function admin_list()
    {
        $stmt = $this->db->prepare("
            SELECT channel_id, channel_key, channel_name, channel_type,
                   channel_slug, status_flag, department_id
            FROM lupo_channels
            WHERE is_deleted = 0
            ORDER BY channel_id ASC
        ");
        $stmt->execute();
        $channels = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get metrics for all channels
        $metrics = [];
        foreach ($channels as $channel) {
            $metrics[$channel['channel_id']] = $this->getChannelMetrics($channel['channel_id']);
        }

        include __DIR__ . '/../../../views/admin/channels.php';
    }

    public function admin_view($channel_id)
    {
        $channel_id = (int) $channel_id;

        // Load channel
        $stmt = $this->db->prepare("
            SELECT * FROM lupo_channels WHERE channel_id = ?
        ");
        $stmt->execute([$channel_id]);
        $channel = $stmt->fetch(PDO::FETCH_ASSOC);

        // Load metrics
        $metrics = $this->getChannelMetrics($channel_id);

        // Load last 10 messages
        $messages = $this->getRecentMessages($channel_id);

        include __DIR__ . '/../../../views/admin/channel_view.php';
    }

    protected function getChannelMetrics($channel_id)
    {
        $metrics = [];

        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM lupo_dialog_threads WHERE channel_id = ?
        ");
        $stmt->execute([$channel_id]);
        $metrics['threads'] = $stmt->fetchColumn();

        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM lupo_dialog_messages WHERE channel_id = ?
        ");
        $stmt->execute([$channel_id]);
        $metrics['messages'] = $stmt->fetchColumn();

        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM lupo_actor_channels ac
            JOIN lupo_actors a ON a.actor_id = ac.actor_id
            WHERE ac.channel_id = ? AND a.actor_type = 'agent'
        ");
        $stmt->execute([$channel_id]);
        $metrics['agents'] = $stmt->fetchColumn();

        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM lupo_actor_channels ac
            JOIN lupo_actors a ON a.actor_id = ac.actor_id
            WHERE ac.channel_id = ? AND a.actor_type = 'user'
        ");
        $stmt->execute([$channel_id]);
        $metrics['users'] = $stmt->fetchColumn();

        $stmt = $this->db->prepare("
            SELECT MAX(created_ymdhis) FROM lupo_dialog_messages WHERE channel_id = ?
        ");
        $stmt->execute([$channel_id]);
        $metrics['last_activity'] = $stmt->fetchColumn();

        return $metrics;
    }

    protected function getRecentMessages($channel_id)
    {
        $stmt = $this->db->prepare("
            SELECT dm.dialog_message_id, dm.from_actor_id, dm.message_text, 
                   dm.created_ymdhis, a.name as actor_name, a.actor_type
            FROM lupo_dialog_messages dm
            JOIN lupo_actors a ON dm.from_actor_id = a.actor_id
            WHERE dm.channel_id = ? AND dm.is_deleted = 0
            ORDER BY dm.created_ymdhis DESC
            LIMIT 10
        ");
        $stmt->execute([$channel_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
