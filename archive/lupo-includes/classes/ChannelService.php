<?php

/**
 * LUPOPEDIA HEADERS (class file — YAML excerpt; canonical format: lupo-docs/doctrine/LUPOPEDIA_HEADERS/)
 *
 * lupopedia.headers:
 *   lupopedia.schema: class
 *   file_path_from_root: lupo-includes/classes/ChannelService.php
 *   last_modified_utc: "20260405230727"
 *   when_updated: "20260405230727"
 *   channel_id: 42
 *   actor_id: 102
 *   delegation_chain: cursor:root
 *   artifact_type: class
 *   artifact_kind: service
 *   purpose: Channel thread JSON mirror under lupo-channels and inserts into lupo_dialog_messages.
 *   tags: [channels, dialog_messages, service, IdGenerator]
 */

require_once __DIR__ . '/IdGenerator.php';
require_once __DIR__ . '/TimestampYmdhis.php';

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
     * Validate postMessage inputs (no path separators / traversal in thread key).
     *
     * @param int    $channelId
     * @param string $threadKey
     * @param int    $actorId
     * @param string $content
     * @param string $version
     * @return bool
     */
    private function postMessageInputsValid($channelId, $threadKey, $actorId, $content, $version)
    {
        if (!is_numeric($channelId) || (int) $channelId <= 0) {
            return false;
        }
        if (!is_string($threadKey) || $threadKey === '') {
            return false;
        }
        if (strlen($threadKey) > 200 || strpos($threadKey, '..') !== false) {
            return false;
        }
        if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $threadKey)) {
            return false;
        }
        if (!is_numeric($actorId) || (int) $actorId < 0) {
            return false;
        }
        if (!is_string($content) || trim($content) === '') {
            return false;
        }
        if (strlen($content) > 65536) {
            return false;
        }
        if (!is_string($version) || $version === '' || strlen($version) > 64) {
            return false;
        }
        if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $version)) {
            return false;
        }
        return true;
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
        if (!$this->postMessageInputsValid($channelId, $threadKey, $actorId, $content, $version)) {
            if (function_exists('error_log')) {
                error_log('ChannelService::postMessage invalid arguments');
            }
            return false;
        }

        $channelId = (int) $channelId;
        $actorId = (int) $actorId;

        $now = (string) timestamp_ymdhis::now();
        $dialogMessageId = IdGenerator::generate();
        if (!IdGenerator::validateFormat($dialogMessageId)) {
            if (function_exists('error_log')) {
                error_log('ChannelService::postMessage IdGenerator returned invalid id');
            }
            return false;
        }

        $msgTable = $this->prefix . 'dialog_messages';
        $message = array(
            'message_id' => $dialogMessageId,
            'actor_id' => $actorId,
            'content' => $content,
            'timestamp_ymdhis' => $now,
            'consensus_status' => 'active'
        );

        $threadFile = $this->getThreadPath($channelId, $version) . DIRECTORY_SEPARATOR . $threadKey . '.json';
        $threadDir = dirname($threadFile);
        if (!is_dir($threadDir)) {
            if (!@mkdir($threadDir, 0755, true) && !is_dir($threadDir)) {
                if (function_exists('error_log')) {
                    error_log('ChannelService::postMessage mkdir failed: ' . $threadDir);
                }
                return false;
            }
        }

        $data = array();
        if (is_file($threadFile)) {
            $raw = @file_get_contents($threadFile);
            if ($raw !== false && $raw !== '') {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    $data = $decoded;
                }
            }
        }

        if (!isset($data['messages'])) {
            $data['messages'] = array();
            $data['thread_id'] = $threadKey;
            $data['version'] = $version;
        }

        $data['messages'][] = $message;
        $written = @file_put_contents($threadFile, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
        if ($written === false) {
            if (function_exists('error_log')) {
                error_log('ChannelService::postMessage file_put_contents failed: ' . $threadFile);
            }
            return false;
        }

        $messageText = strlen($content) > 1000 ? substr($content, 0, 997) . '...' : $content;
        $insertData = array(
            'dialog_message_id' => $dialogMessageId,
            'message_id' => $dialogMessageId,
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

        try {
            $this->db->insert($msgTable, $insertData);
        } catch (Exception $e) {
            if (function_exists('error_log')) {
                error_log('ChannelService::postMessage DB insert failed: ' . $e->getMessage());
            }
            return false;
        }

        return true;
    }

    /**
     * List all threads in a channel
     */
    public function listThreads($channelId, $version = '4.0.x')
    {
        $path = $this->getThreadPath($channelId, $version);
        if (!is_dir($path)) {
            return array();
        }

        $threads = array();
        $files = scandir($path);
        if ($files === false) {
            return array();
        }
        foreach ($files as $file) {
            if (substr($file, -5) === '.json') {
                $threads[] = substr($file, 0, -5);
            }
        }
        return $threads;
    }
}
