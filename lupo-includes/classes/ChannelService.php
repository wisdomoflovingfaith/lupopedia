<?php
/**
 * wolfie.headers: {
 *   file_path_from_root: "lupo-includes/classes/ChannelService.php",
 *   system_version: "4.0.66",
 *   channel_id: 42,
 *   actor_id: 1006,
 *   purpose: "Manages hierarchical multi-agent coordination within the lupo-channels structure.",
 *   last_modified_utc: "20260308"
 * }
 */

class ChannelService
{
    private $db;
    private $prefix;
    /** @var string Root directory for channels */
    private $basePath;

    /**
     * @param PDO_DB $db Database connection
     * @param string $prefix Table prefix
     */
    public function __construct($db, $prefix)
    {
        $this->db = $db;
        $this->prefix = $prefix;
        $this->basePath = ABSPATH . LUPO_CHANNELS_DIR;
    }

    /**
     * Get path to a specific channel's versioned threads
     *
     * @param int $channelId
     * @param string $version Default 4.0.x
     * @return string
     */
    public function getThreadPath($channelId, $version = '4.0.x')
    {
        return $this->basePath . DIRECTORY_SEPARATOR . $channelId . DIRECTORY_SEPARATOR . 'threads' . DIRECTORY_SEPARATOR . $version;
    }

    /**
     * Post a message to a channel thread
     *
     * @param int    $channelId
     * @param string $threadKey e.g. 'evolution_4_0_65'
     * @param int    $actorId
     * @param string $content
     * @param string $version Default 4.0.x
     * @return bool
     */
    public function postMessage($channelId, $threadKey, $actorId, $content, $version = '4.0.x')
    {
        // 1. Prepare Message Data
        $now = gmdate('YmdHis');
        $messageId = time() . mt_rand(100, 999); // Registry-based ID would be better in prod

        $message = array(
            'message_id' => $messageId,
            'actor_id' => $actorId,
            'content' => $content,
            'timestamp_ymdhis' => $now,
            'consensus_status' => 'active'
        );

        // 2. Persist to Filesystem
        $threadFile = $this->getThreadPath($channelId, $version) . DIRECTORY_SEPARATOR . $threadKey . '.json';
        if (!is_dir(dirname($threadFile))) {
            mkdir(dirname($threadFile), 0755, true);
        }

        $data = array();
        if (is_file($threadFile)) {
            $raw = file_get_contents($threadFile);
            $data = json_decode($raw, true);
        }

        if (!isset($data['messages'])) {
            $data['messages'] = array();
            $data['thread_id'] = $threadKey;
            $data['version'] = $version;
        }

        $data['messages'][] = $message;
        file_put_contents($threadFile, json_encode($data, JSON_PRETTY_PRINT));

        // 3. Persist to DB: canonical table lupo_dialog_messages (not lupo_messages)
        $msgTable = $this->prefix . 'dialog_messages';
        $nextIdRow = $this->db->fetchRow(
            "SELECT COALESCE(MAX(dialog_message_id), 0) + 1 AS next_id FROM " . $msgTable
        );
        $dialogMessageId = isset($nextIdRow['next_id']) ? (int) $nextIdRow['next_id'] : (int) $messageId;
        $messageText = strlen($content) > 1000 ? substr($content, 0, 997) . '...' : $content;
        $insertData = array(
            'dialog_message_id' => $dialogMessageId,
            'message_id' => (int) $messageId,
            'dialog_thread_id' => null,
            'channel_id' => $channelId,
            'from_actor_id' => $actorId,
            'to_actor_id' => null,
            'read_by_actor_id' => 0,
            'read_by_actor_utc' => 0,
            'message_text' => $messageText,
            'message_type' => 'text',
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0
        );
        $this->db->insert($msgTable, $insertData);

        return true;
    }

    /**
     * List all threads in a channel
     */
    public function listThreads($channelId, $version = '4.0.x')
    {
        $path = $this->getThreadPath($channelId, $version);
        if (!is_dir($path))
            return array();

        $threads = array();
        $files = scandir($path);
        foreach ($files as $file) {
            if (substr($file, -5) === '.json') {
                $threads[] = substr($file, 0, -5);
            }
        }
        return $threads;
    }
}
