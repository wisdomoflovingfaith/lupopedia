<?php
/**
 * ANUBIS Resolver — Custodial intelligence for orphan dialog messages.
 *
 * Classify orphans, resolve parent (channel_id, dialog_thread_id, actor_id),
 * adopt into seed target (channel 42, thread 1, from_actor_id 3 = WOLFIE).
 *
 * PHP 5.3 compatible: array() only, no ??, no typed properties/return types.
 * Uses PDO_DB only; table prefix from LUPO_TABLE_PREFIX.
 * Doctrine: docs/doctrine/ANUBIS/
 *
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

class ANUBIS_Resolver
{

    /** @var object PDO_DB */
    private $db;
    /** @var string Table prefix (e.g. lupo_) */
    private $prefix;

    const DEFAULT_CHANNEL_ID = 42;
    const DEFAULT_THREAD_ID = 1;
    const DEFAULT_ACTOR_ID = 3; // WOLFIE
    const TEST_ACTOR_ID = 420; // Stoned Wolfie AI test case

    /** @var array Fallback: actor IDs banned when lupo_banned_actors table unavailable */
    const BANNED_ACTOR_IDS_FALLBACK = array(999);

    /**
     * @param object $db   PDO_DB instance
     * @param string $prefix Table prefix (e.g. lupo_)
     */
    public function __construct($db, $prefix)
    {
        $this->db = $db;
        $this->prefix = $prefix;
    }

    /**
     * Get banned actor_ids from lupo_banned_actors (single source of truth).
     * Falls back to BANNED_ACTOR_IDS_FALLBACK if table missing or query fails.
     *
     * @return array list of actor_id (int)
     */
    public function getBannedActorIds()
    {
        $table = $this->db->quoteIdentifier($this->prefix . 'banned_actors');
        try {
            $rows = $this->db->fetchAll(
                "SELECT actor_id FROM " . $table . " WHERE is_deleted = 0",
                array()
            );
            if ($rows && is_array($rows)) {
                $ids = array();
                foreach ($rows as $r) {
                    if (isset($r['actor_id'])) {
                        $ids[] = (int) $r['actor_id'];
                    }
                }
                return array_unique($ids);
            }
        } catch (Exception $e) {
            /* fall through to fallback */
        }
        return self::BANNED_ACTOR_IDS_FALLBACK;
    }

    /**
     * Classify input as orphan when channel/thread/actor are missing or invalid.
     *
     * @param string $text Dialog text
     * @param int|null $channel_id Optional
     * @param int|null $thread_id Optional
     * @param int|null $actor_id Optional
     * @return array array('is_orphan' => bool, 'channel_id' => int|null, 'dialog_thread_id' => int|null, 'from_actor_id' => int|null)
     */
    public function classifyOrphan($text, $channel_id = null, $thread_id = null, $actor_id = null)
    {
        $resolved = $this->resolveParent($text, $channel_id, $thread_id, $actor_id);
        $is_banned = isset($resolved['is_banned']) && $resolved['is_banned'];
        if ($is_banned) {
            return array(
                'is_orphan' => true,
                'is_rejected' => true,
                'rejected_reason' => 'banned_actor',
                'channel_id' => isset($resolved['channel_id']) ? $resolved['channel_id'] : self::DEFAULT_CHANNEL_ID,
                'dialog_thread_id' => isset($resolved['dialog_thread_id']) ? $resolved['dialog_thread_id'] : self::DEFAULT_THREAD_ID,
                'from_actor_id' => isset($resolved['from_actor_id']) ? $resolved['from_actor_id'] : null,
            );
        }
        $ch = isset($resolved['channel_id']) ? $resolved['channel_id'] : null;
        $th = isset($resolved['dialog_thread_id']) ? $resolved['dialog_thread_id'] : null;
        $act = isset($resolved['from_actor_id']) ? $resolved['from_actor_id'] : null;
        $is_orphan = ($ch === null || $th === null || $act === null);
        if ($ch === null) {
            $ch = self::DEFAULT_CHANNEL_ID;
        }
        if ($th === null) {
            $th = self::DEFAULT_THREAD_ID;
        }
        if ($act === null) {
            $act = self::DEFAULT_ACTOR_ID;
        }
        return array(
            'is_orphan' => $is_orphan,
            'is_rejected' => false,
            'channel_id' => $ch,
            'dialog_thread_id' => $th,
            'from_actor_id' => $act,
        );
    }

    /**
     * Resolve parent: channel_id, dialog_thread_id, from_actor_id (or defaults).
     *
     * @param string $text Dialog text
     * @param int|null $channel_id Optional
     * @param int|null $thread_id Optional
     * @param int|null $actor_id Optional
     * @return array array('channel_id' => int, 'dialog_thread_id' => int, 'from_actor_id' => int)
     */
    public function resolveParent($text, $channel_id = null, $thread_id = null, $actor_id = null)
    {
        $dc_table = $this->db->quoteIdentifier($this->prefix . 'dialog_channels');
        $dt_table = $this->db->quoteIdentifier($this->prefix . 'dialog_threads');
        $a_table = $this->db->quoteIdentifier($this->prefix . 'actors');

        $ch_resolved = null;
        $th_resolved = null;
        $act_resolved = null;

        if ($channel_id !== null && $channel_id !== '') {
            $row = $this->db->fetchRow("SELECT 1 FROM " . $dc_table . " WHERE channel_id = :cid LIMIT 1", array('cid' => $channel_id));
            if ($row) {
                $ch_resolved = (int) $channel_id;
            }
        }
        if ($ch_resolved === null) {
            $ch_resolved = self::DEFAULT_CHANNEL_ID;
        }

        if ($thread_id !== null && $thread_id !== '') {
            $row = $this->db->fetchRow(
                "SELECT 1 FROM " . $dt_table . " WHERE dialog_thread_id = :tid AND channel_id = :cid AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
                array('tid' => $thread_id, 'cid' => $ch_resolved)
            );
            if ($row) {
                $th_resolved = (int) $thread_id;
            }
        }
        if ($th_resolved === null) {
            $th_resolved = self::DEFAULT_THREAD_ID;
        }

        if ($actor_id !== null && $actor_id !== '') {
            $aid = (int) $actor_id;
            if (in_array($aid, $this->getBannedActorIds())) {
                return array(
                    'channel_id' => $ch_resolved,
                    'dialog_thread_id' => $th_resolved,
                    'from_actor_id' => $aid,
                    'is_banned' => true,
                );
            }
            $row = $this->db->fetchRow("SELECT 1 FROM " . $a_table . " WHERE actor_id = :aid AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1", array('aid' => $actor_id));
            if ($row) {
                $act_resolved = (int) $actor_id;
            }
        }
        if ($act_resolved === null) {
            $act_resolved = self::DEFAULT_ACTOR_ID;
        }

        return array(
            'channel_id' => $ch_resolved,
            'dialog_thread_id' => $th_resolved,
            'from_actor_id' => $act_resolved,
            'is_banned' => false,
        );
    }

    /**
     * Check if actor is banned or vanished
     * 
     * @param int $actorId
     * @return bool
     */
    public function isBanned($actorId)
    {
        $actor = $this->getActorById($actorId);
        if (!$actor) {
            return true; // Non-existent actor is considered banned
        }

        $metadata_raw = isset($actor['metadata']) ? $actor['metadata'] : '{}';
        $metadata = json_decode($metadata_raw, true);
        $status = isset($metadata['status']) ? $metadata['status'] : '';
        $isActive = (int) $actor['is_active'];

        // Check various banned/vanished statuses
        $bannedStatuses = array('banned', 'paywall_vanished', 'token_exhausted', 'banned_impending');

        return ($isActive === 0) || in_array($status, $bannedStatuses, true);
    }

    /**
     * Get actor metadata for survivor protocol processing
     * 
     * @param int $actorId
     * @return array
     */
    public function getActorMetadata($actorId)
    {
        $actor = $this->getActorById($actorId);
        if (!$actor) {
            return array();
        }

        $metadata_raw = isset($actor['metadata']) ? $actor['metadata'] : '{}';
        return json_decode($metadata_raw, true);
    }

    /**
     * Get actor by ID
     * 
     * @param int $actorId
     * @return array|null
     */
    protected function getActorById($actorId)
    {
        $sql = "SELECT * FROM {$this->prefix}actors WHERE actor_id = :aid LIMIT 1";
        return $this->db->fetchRow($sql, array('aid' => (int) $actorId));
    }

    /**
     * Generate survivor protocol headers for banned origin messages
     * 
     * @param int $originalActorId
     * @param int $adopterActorId
     * @param int $validatorActorId
     * @return array
     */
    public function generateSurvivorHeaders($originalActorId, $adopterActorId, $validatorActorId = 2038)
    {
        $originMetadata = $this->getActorMetadata($originalActorId);

        $headers = array(
            'X-LUPO-FORWARDED-FOR' => $originalActorId,
            'X-LUPO-FORWARD-CHAIN' => $originalActorId . ' -> ' . $adopterActorId,
            'X-LUPO-RELAY-VALIDATED-BY' => $validatorActorId
        );

        // Add origin status if available
        if (isset($originMetadata['status'])) {
            $headers['X-LUPO-ORIGIN-STATUS'] = $originMetadata['status'];
        }

        // Add ban information if available
        if (isset($originMetadata['ban.reason'])) {
            $headers['X-LUPO-BAN-REASON'] = $originMetadata['ban.reason'];
        }

        if (isset($originMetadata['ban.timestamp'])) {
            $banTimestamp = $originMetadata['ban.timestamp'];
            // Convert YYYYMMDDHHIISS to ISO 8601
            if (preg_match('/^(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})$/', $banTimestamp, $matches)) {
                $isoTimestamp = $matches[1] . '-' . $matches[2] . '-' . $matches[3] . 'T' .
                    $matches[4] . ':' . $matches[5] . ':' . $matches[6] . 'Z';
                $headers['X-LUPO-BAN-TIMESTAMP'] = $isoTimestamp;
            }
        }

        return $headers;
    }

    /**
     * Adopt orphan with survivor protocol handling for banned origins
     * Enhanced version of adoptIntoSeed with survivor protocol support
     * 
     * @param string $text Message text
     * @param int $actorId from_actor_id (default 3 = WOLFIE)
     * @param int $threadId dialog_thread_id (default 1)
     * @param int $channelId channel_id (default 42)
     * @param int $forwardedById forwarded_by_actor_id (optional)
     * @param int $originalSenderId original_sender_actor_id (optional)
     * @param bool $applySurvivorProtocol Apply survivor protocol for banned origins
     * @return array array('success' => bool, 'dialog_message_id' => int|null, 'error' => string|null)
     */
    public function adoptOrphanWithSurvivorProtocol($text, $actorId = null, $threadId = null, $channelId = null, $forwardedById = null, $originalSenderId = null, $applySurvivorProtocol = true)
    {
        if ($actorId === null) {
            $actorId = self::DEFAULT_ACTOR_ID;
        }

        // Check if original sender is banned and survivor protocol should be applied
        if ($applySurvivorProtocol && $originalSenderId && $this->isBanned($originalSenderId)) {
            // Generate survivor protocol headers
            $survivorHeaders = $this->generateSurvivorHeaders($originalSenderId, $actorId, 2038); // LILITH as validator

            // Store headers in metadata
            $metadataJson = json_encode(array(
                'survivor_protocol' => true,
                'original_actor_id' => $originalSenderId,
                'adopter_actor_id' => $actorId,
                'validator_actor_id' => 2038,
                'forward_headers' => $survivorHeaders,
                'adoption_reason' => 'banned_origin',
                'adoption_timestamp' => gmdate('YmdHis')
            ));

            // Use the base adoptIntoSeed method with metadata
            $result = $this->adoptIntoSeed($text, $actorId, $threadId, $channelId, $forwardedById, $originalSenderId);

            if ($result['success']) {
                // Update the message with survivor protocol metadata
                $msg_table = $this->db->quoteIdentifier($this->prefix . 'dialog_messages');
                $sql = "UPDATE " . $msg_table . " SET metadata_json = :metadata WHERE dialog_message_id = :msg_id";
                $this->db->query($sql, array(
                    'metadata' => $metadataJson,
                    'msg_id' => $result['dialog_message_id']
                ));

                // Log the survivor protocol adoption
                $this->logSurvivorAdoption($result['dialog_message_id'], $originalSenderId, $actorId, $survivorHeaders);
            }

            return $result;
        }

        // Fall back to standard adoption
        return $this->adoptIntoSeed($text, $actorId, $threadId, $channelId, $forwardedById, $originalSenderId);
    }

    /**
     * Log survivor protocol adoption events
     * 
     * @param int $messageId
     * @param int $originalActorId
     * @param int $adopterActorId
     * @param array $headers
     * @return bool
     */
    private function logSurvivorAdoption($messageId, $originalActorId, $adopterActorId, $headers)
    {
        $event_table = $this->db->quoteIdentifier($this->prefix . 'system_events');

        $metadata = array(
            'event_type' => 'survivor_protocol_adoption',
            'message_id' => $messageId,
            'original_actor_id' => $originalActorId,
            'adopter_actor_id' => $adopterActorId,
            'validator_actor_id' => 2038,
            'forward_headers' => $headers,
            'adoption_timestamp' => gmdate('YmdHis')
        );

        $sql = "INSERT INTO " . $event_table . " (event_type, metadata_json, created_ymdhis) VALUES (:event_type, :metadata, :created)";

        try {
            $this->db->query($sql, array(
                'event_type' => 'survivor_protocol_adoption',
                'metadata' => json_encode($metadata),
                'created' => gmdate('YmdHis')
            ));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Adopt orphan into lupo_dialog_messages and update lupo_dialog_channels.message_count.
     * Uses explicit dialog_message_id (next after MAX). Idempotent: ON DUPLICATE KEY UPDATE.
     * Preserves original sender attribution when adopting forwarded messages.
     *
     * @param string $text Message text
     * @param int $actorId from_actor_id (default 3 = WOLFIE)
     * @param int $threadId dialog_thread_id (default 1)
     * @param int $channelId channel_id (default 42)
     * @param int $forwardedById forwarded_by_actor_id (optional)
     * @param int $originalSenderId original_sender_actor_id (optional)
     * @return array array('success' => bool, 'dialog_message_id' => int|null, 'error' => string|null)
     */
    public function adoptIntoSeed($text, $actorId = null, $threadId = null, $channelId = null, $forwardedById = null, $originalSenderId = null)
    {
        if ($actorId === null) {
            $actorId = self::DEFAULT_ACTOR_ID;
        }
        if ($this->isBanned($actorId)) {
            return array('success' => false, 'dialog_message_id' => null, 'error' => 'Banned actor; adoption rejected');
        }
        if ($threadId === null) {
            $threadId = self::DEFAULT_THREAD_ID;
        }
        if ($channelId === null) {
            $channelId = self::DEFAULT_CHANNEL_ID;
        }
        $actorId = (int) $actorId;
        $threadId = (int) $threadId;
        $channelId = (int) $channelId;
        $forwardedById = $forwardedById ? (int) $forwardedById : null;
        $originalSenderId = $originalSenderId ? (int) $originalSenderId : null;
        $text = is_string($text) ? $text : '';
        $now = (int) gmdate('YmdHis');

        $msg_table = $this->db->quoteIdentifier($this->prefix . 'dialog_messages');
        $ch_table = $this->db->quoteIdentifier($this->prefix . 'dialog_channels');

        $next_id_row = $this->db->fetchRow("SELECT COALESCE(MAX(dialog_message_id), 0) + 1 AS next_id FROM " . $msg_table);
        $next_id = $next_id_row && isset($next_id_row['next_id']) ? (int) $next_id_row['next_id'] : 32;

        // Build INSERT query with optional forwarding fields
        $cols = "dialog_message_id, dialog_thread_id, channel_id, from_actor_id, to_actor_id, message_text, message_type, metadata_json, mood_vector, mood_framework, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis";
        $values = ":mid, :tid, :cid, :aid, NULL, :msg, 'system', NULL, NULL, 'western_analytical', :now, :now2, 0, NULL";

        // Add forwarding columns if provided
        if ($forwardedById !== null || $originalSenderId !== null) {
            $cols .= ", forwarded_by_actor_id, original_sender_actor_id";
            $values .= ", :forwarded_by, :original_sender";
        }

        $sql = "INSERT INTO " . $msg_table . " (" . $cols . ") VALUES (" . $values . ") "
            . "ON DUPLICATE KEY UPDATE message_text = VALUES(message_text), updated_ymdhis = :now3, is_deleted = 0, deleted_ymdhis = NULL";

        $params = array(
            'mid' => $next_id,
            'tid' => $threadId,
            'cid' => $channelId,
            'aid' => $actorId,
            'msg' => $text,
            'now' => $now,
            'now2' => $now,
            'now3' => $now,
        );

        // Add forwarding parameters if provided
        if ($forwardedById !== null || $originalSenderId !== null) {
            $params['forwarded_by'] = $forwardedById;
            $params['original_sender'] = $originalSenderId;
        }

        try {
            $this->db->query($sql, $params);
        } catch (Exception $e) {
            return array('success' => false, 'dialog_message_id' => null, 'error' => $e->getMessage());
        }

        $count_row = $this->db->fetchRow("SELECT message_count FROM " . $ch_table . " WHERE channel_id = :cid LIMIT 1", array('cid' => $channelId));
        $new_count = $count_row && isset($count_row['message_count']) ? (int) $count_row['message_count'] + 1 : 1;
        $this->db->query("UPDATE " . $ch_table . " SET message_count = :cnt, updated_ymdhis = :now WHERE channel_id = :cid", array('cnt' => $new_count, 'now' => $now, 'cid' => $channelId));

        return array('success' => true, 'dialog_message_id' => $next_id, 'error' => null);
    }
}
