<?php
/**
 * Channel message router: broadcast, direct, thread — DB insert + lupo-channels artifact.
 *
 * @package Lupopedia
 * @since   4.0.80
 */

class Lupo_Channel_Message_Router
{
    /** @var PDO_DB|object */
    private $db;
    /** @var string */
    private $tablePrefix;
    /** @var string */
    private $channelsBase;

    public function __construct($db, $tablePrefix)
    {
        $this->db = $db;
        $this->tablePrefix = $tablePrefix;
        $root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : dirname(dirname(__DIR__));
        $this->channelsBase = $root . DIRECTORY_SEPARATOR . 'lupo-channels';
    }

    public function handleBroadcast($channel_id, $from_actor_id, $message_text, $message_type = 'broadcast', $metadata_json = null)
    {
        return $this->insertAndArtifact(
            (int) $channel_id,
            (int) $from_actor_id,
            $message_text,
            $message_type,
            'broadcast',
            null,
            null,
            $metadata_json
        );
    }

    public function handleDirectMessage($channel_id, $from_actor_id, $to_actor_id, $message_text, $message_type = 'direct', $metadata_json = null)
    {
        $to = (int) $to_actor_id;
        if ($to <= 0) {
            return array('success' => false, 'message_id' => null, 'file_path' => null, 'error' => 'Direct messages require to_actor_id');
        }
        return $this->insertAndArtifact(
            (int) $channel_id,
            (int) $from_actor_id,
            $message_text,
            $message_type,
            'direct',
            $to,
            null,
            $metadata_json
        );
    }

    /**
     * @param mixed $thread_id strict numeric dialog_thread_id only
     */
    public function handleThreadMessage($channel_id, $thread_id, $from_actor_id, $message_text, $message_type = 'thread', $metadata_json = null)
    {
        if (!class_exists('Lupo_Channel_Artifact_Validator', false)) {
            require_once dirname(__FILE__) . '/Lupo_Channel_Artifact_Validator.php';
        }
        if (!Lupo_Channel_Artifact_Validator::isValidDialogThreadId($thread_id)) {
            return array('success' => false, 'message_id' => null, 'file_path' => null, 'error' => 'Invalid thread_id: must be a positive numeric dialog_thread_id');
        }
        $tid = (int) $thread_id;
        if (!Lupo_Channel_Artifact_Validator::threadExistsInChannel($this->db, $this->tablePrefix, $channel_id, $tid)) {
            return array('success' => false, 'message_id' => null, 'file_path' => null, 'error' => 'Thread not found for this channel');
        }
        $bodyErr = Lupo_Channel_Artifact_Validator::validateThreadPostBody($message_text, $message_type, $metadata_json);
        if ($bodyErr !== null) {
            return array('success' => false, 'message_id' => null, 'file_path' => null, 'error' => $bodyErr);
        }
        return $this->insertAndArtifact(
            (int) $channel_id,
            (int) $from_actor_id,
            $message_text,
            $message_type,
            'thread',
            null,
            $tid,
            $metadata_json
        );
    }

    private function insertAndArtifact($channel_id, $from_actor_id, $message_text, $message_type, $routing, $to_actor_id, $thread_id, $metadata_json)
    {
        require_once dirname(__FILE__) . '/Lupo_Channel_Artifact_Validator.php';

        $t_msg = $this->tablePrefix . 'dialog_messages';
        $now = (int) gmdate('YmdHis');
        $dateYmd = gmdate('Ymd');
        $timeHis = gmdate('His');

        $message_id = null;
        if (function_exists('lupo_findpuka')) {
            $message_id = lupo_findpuka($this->db, $t_msg, 'dialog_message_id', 1, null);
        }
        if ($message_id === null) {
            $stmt = $this->db->prepare("SELECT MAX(dialog_message_id) AS max_id FROM {$t_msg}");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $message_id = ($row && isset($row['max_id']) && $row['max_id'] !== null) ? ((int) $row['max_id'] + 1) : 1;
        }

        $meta = ($metadata_json === null || $metadata_json === '') ? null : $metadata_json;

        if ($routing === 'thread') {
            $sql = "INSERT INTO {$t_msg} (dialog_message_id, message_id, dialog_thread_id, channel_id, from_actor_id, to_actor_id, message_text, message_type, metadata_json, created_ymdhis, updated_ymdhis, is_deleted) VALUES (:msg_id, 0, :thread_id, :channel_id, :from_id, NULL, :body, :msg_type, :meta, :c, :u, 0)";
            $params = array(
                ':msg_id' => $message_id,
                ':thread_id' => (int) $thread_id,
                ':channel_id' => $channel_id,
                ':from_id' => $from_actor_id,
                ':body' => $message_text,
                ':msg_type' => $message_type,
                ':meta' => $meta,
                ':c' => $now,
                ':u' => $now,
            );
        } elseif ($routing === 'direct') {
            $sql = "INSERT INTO {$t_msg} (dialog_message_id, message_id, dialog_thread_id, channel_id, from_actor_id, to_actor_id, message_text, message_type, metadata_json, created_ymdhis, updated_ymdhis, is_deleted) VALUES (:msg_id, 0, NULL, :channel_id, :from_id, :to_id, :body, :msg_type, :meta, :c, :u, 0)";
            $params = array(
                ':msg_id' => $message_id,
                ':channel_id' => $channel_id,
                ':from_id' => $from_actor_id,
                ':to_id' => (int) $to_actor_id,
                ':body' => $message_text,
                ':msg_type' => $message_type,
                ':meta' => $meta,
                ':c' => $now,
                ':u' => $now,
            );
        } else {
            $sql = "INSERT INTO {$t_msg} (dialog_message_id, message_id, dialog_thread_id, channel_id, from_actor_id, to_actor_id, message_text, message_type, metadata_json, created_ymdhis, updated_ymdhis, is_deleted) VALUES (:msg_id, 0, NULL, :channel_id, :from_id, NULL, :body, :msg_type, :meta, :c, :u, 0)";
            $params = array(
                ':msg_id' => $message_id,
                ':channel_id' => $channel_id,
                ':from_id' => $from_actor_id,
                ':body' => $message_text,
                ':msg_type' => $message_type,
                ':meta' => $meta,
                ':c' => $now,
                ':u' => $now,
            );
        }

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
        } catch (Exception $e) {
            return array('success' => false, 'message_id' => null, 'file_path' => null, 'error' => $e->getMessage());
        }

        $subdir = 'broadcasts';
        if ($routing === 'direct') {
            $subdir = 'direct' . DIRECTORY_SEPARATOR . (int) $to_actor_id;
        } elseif ($routing === 'thread') {
            $subdir = 'threads' . DIRECTORY_SEPARATOR . (int) $thread_id;
        }

        $slug = Lupo_Channel_Artifact_Validator::resolveActorSlug($this->db, $this->tablePrefix, $from_actor_id);
        $purpose = $routing . '-m' . $message_id;
        $filename = Lupo_Channel_Artifact_Validator::buildCanonicalFilename($dateYmd, $timeHis, $slug, $purpose);
        if (!Lupo_Channel_Artifact_Validator::isValidCanonicalFilename($filename)) {
            $filename = $dateYmd . '_' . $timeHis . '_' . $slug . '_' . $routing . '-m' . $message_id . '.md';
        }

        $rel = 'lupo-channels/' . $channel_id . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $subdir) . '/' . $filename;
        $file_path = $this->generateChannelArtifact(
            $channel_id,
            $subdir,
            $filename,
            $message_text,
            array(
                'routing' => $routing,
                'dialog_message_id' => $message_id,
                'from_actor_id' => $from_actor_id,
                'channel_id' => $channel_id,
                'created_ymdhis' => $now,
                'message_type' => $message_type,
                'to_actor_id' => $to_actor_id,
                'dialog_thread_id' => $thread_id,
            )
        );

        return array('success' => true, 'message_id' => $message_id, 'file_path' => $file_path ? $file_path : $rel, 'error' => null);
    }

    public function generateChannelArtifact($channel_id, $subdirectory, $filename, $content, $metadata)
    {
        if (!Lupo_Channel_Artifact_Validator::isValidCanonicalFilename($filename)) {
            return null;
        }
        if (strpos($subdirectory, 'threads' . DIRECTORY_SEPARATOR) === 0 || strpos($subdirectory, 'threads/') === 0) {
            $parts = preg_split('#[/\\\\]#', $subdirectory);
            if (isset($parts[1]) && !Lupo_Channel_Artifact_Validator::isValidDialogThreadId($parts[1])) {
                return null;
            }
        }
        $base = $this->channelsBase . DIRECTORY_SEPARATOR . (int) $channel_id . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $subdirectory);
        if (!is_dir($base)) {
            if (!@mkdir($base, 0755, true)) {
                return null;
            }
        }
        $full = $base . DIRECTORY_SEPARATOR . $filename;
        $yaml = "---\n";
        $yaml .= "lupopedia.version: \"4.0.80\"\n";
        $yaml .= "artifact_kind: channel_message\n";
        foreach ($metadata as $k => $v) {
            if ($v === null) {
                continue;
            }
            $yaml .= $k . ': ' . json_encode($v, JSON_UNESCAPED_UNICODE) . "\n";
        }
        $yaml .= "file_path_from_root: " . json_encode(str_replace('\\', '/', 'lupo-channels/' . (int) $channel_id . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $subdirectory) . '/' . $filename)) . "\n";
        $yaml .= "---\n\n";
        $written = @file_put_contents($full, $yaml . $content);
        if ($written === false) {
            return null;
        }
        return $full;
    }
}
