<?php
/**
 * PRD 16 section 9 -- DB-first transcript append (canonical write path).
 *
 * Resolves dialog_transcript slug `{federation_node_id}/{channel_key}/{thread_key}`,
 * auto-creates dialog_threads row when missing (idempotent by UNIQUE triple),
 * inserts lupo_dialog_messages with channel_key denormalized.
 *
 * Human-visible: rows are loaded by the same channel projection as channels/index.php and
 * api/dialog/fetch-messages.php. Human senders (lupo_actors.is_agent = 0) MUST receive the same
 * first-person ingest rewrite as DialogMvpService::createDialogMessage (PRD 00 / PRD 02 RULE
 * 93.FIRST_PERSON_DISPLAY_FORBIDDEN) via DialogMvpService::rewriteHumanDialogMessageBodyForInsert.
 */
class TranscriptAppendService
{
    public static function getTablePrefix()
    {
        return defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }

    public static function nowYmdHis()
    {
        return (int) gmdate('YmdHis');
    }

    /**
     * Normalize third segment of dialog_transcript to thread_key (ASCII slug).
     *
     * @param string $slug
     * @return string
     */
    public static function normalizeThreadKeySegment($slug)
    {
        $s = strtolower(trim((string) $slug));
        $s = str_replace(array(' ', '_'), '-', $s);
        $s = preg_replace('/[^a-z0-9.-]+/', '-', $s);
        $s = trim($s, '-');
        return substr($s, 0, 255);
    }

    /**
     * Parse dialog_transcript DB slug into node, channel key segment, thread_key.
     *
     * @param string $dialog_transcript
     * @return array|null { federation_node_id, channel_key_segment, thread_key }
     */
    public static function parseDialogTranscript($dialog_transcript)
    {
        $raw = trim((string) $dialog_transcript);
        if ($raw === '') {
            return null;
        }
        $parts = explode('/', $raw);
        if (count($parts) !== 3) {
            return null;
        }
        $node = (int) $parts[0];
        $ch = trim($parts[1]);
        $tk = self::normalizeThreadKeySegment($parts[2]);
        if ($ch === '' || $tk === '') {
            return null;
        }
        return array(
            'federation_node_id' => $node,
            'channel_key_segment' => $ch,
            'thread_key' => $tk,
        );
    }

    /**
     * @param object $db PDO_DB
     * @param array  $input channel_key, message, from_actor_id, dialog_transcript; optional created_ymdhis, task, context, federation_node_id override, to_actor_id (directed recipient for pronoun rewrite), metadata_json (JSON object string merged into metadata_json column)
     * @param array  $options token_auth (bool) — skip channel membership when true
     * @return array { ok, dialog_message_id, dialog_thread_id, status } or { ok:false, error_code, message, http_status }
     */
    public static function append($db, $input, $options = array())
    {
        $token_auth = !empty($options['token_auth']);

        if (!class_exists('DialogMvpService', false)) {
            $dm = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'DialogMvpService.php';
            if (is_file($dm)) {
                require_once $dm;
            }
        }
        if (!class_exists('DialogMvpService', false)) {
            return array(
                'ok' => false,
                'error_code' => 'server_error',
                'message' => 'DialogMvpService not loadable',
                'http_status' => 500,
            );
        }

        $channel_key = isset($input['channel_key']) ? trim((string) $input['channel_key']) : '';
        $message = isset($input['message']) ? trim((string) $input['message']) : '';
        $from_actor_id = isset($input['from_actor_id']) ? (int) $input['from_actor_id'] : 0;
        $dialog_transcript = isset($input['dialog_transcript']) ? trim((string) $input['dialog_transcript']) : '';

        if ($channel_key === '' || $message === '' || $from_actor_id <= 0 || $dialog_transcript === '') {
            return array(
                'ok' => false,
                'error_code' => 'invalid_request',
                'message' => 'channel_key, message, from_actor_id, and dialog_transcript are required',
                'http_status' => 400,
            );
        }

        $parsed = self::parseDialogTranscript($dialog_transcript);
        if ($parsed === null) {
            return array(
                'ok' => false,
                'error_code' => 'thread_not_found',
                'message' => 'dialog_transcript must be {federation_node_id}/{channel_key}/{thread_slug} with three non-empty segments',
                'http_status' => 400,
            );
        }

        if ($parsed['channel_key_segment'] !== $channel_key) {
            return array(
                'ok' => false,
                'error_code' => 'thread_not_found',
                'message' => 'channel_key must match middle segment of dialog_transcript',
                'http_status' => 400,
            );
        }

        if (!DialogMvpService::ensureActorExists($db, $from_actor_id)) {
            return array(
                'ok' => false,
                'error_code' => 'invalid_request',
                'message' => 'from_actor_id not found',
                'http_status' => 400,
            );
        }

        $prefix = self::getTablePrefix();
        $t_ch = $prefix . 'channels';
        $t_th = $prefix . 'dialog_threads';
        $t_msg = $prefix . 'dialog_messages';

        $fn = $parsed['federation_node_id'];
        $thread_key = $parsed['thread_key'];

        $channel = $db->fetchRow(
            "SELECT channel_id FROM {$t_ch} WHERE channel_key = :ck AND federation_node_id = :fn AND is_deleted = 0 LIMIT 1",
            array('ck' => $channel_key, 'fn' => $fn)
        );

        if (!$channel || empty($channel['channel_id'])) {
            return array(
                'ok' => false,
                'error_code' => 'thread_not_found',
                'message' => 'Unknown channel_key for federation_node_id',
                'http_status' => 400,
            );
        }

        $channel_id = (int) $channel['channel_id'];

        if (!$token_auth && !DialogMvpService::actorHasChannelAccess($db, $from_actor_id, $channel_id)) {
            return array(
                'ok' => false,
                'error_code' => 'forbidden',
                'message' => 'Actor is not a member of this channel',
                'http_status' => 403,
            );
        }

        $thread = $db->fetchRow(
            "SELECT dialog_thread_id FROM {$t_th} WHERE federation_node_id = :fn AND channel_id = :cid AND thread_key = :tk AND is_deleted = 0 LIMIT 1",
            array('fn' => $fn, 'cid' => $channel_id, 'tk' => $thread_key)
        );

        $now = isset($input['created_ymdhis']) ? (int) $input['created_ymdhis'] : self::nowYmdHis();
        if ($now <= 0) {
            $now = self::nowYmdHis();
        }

        if (!$thread) {
            if (defined('LUPO_TRANSCRIPT_DISABLE_THREAD_CREATE') && LUPO_TRANSCRIPT_DISABLE_THREAD_CREATE) {
                return array(
                    'ok' => false,
                    'error_code' => 'thread_create_denied',
                    'message' => 'Auto-create disabled by configuration',
                    'http_status' => 403,
                );
            }

            $thread_id = DialogMvpService::nextId($db, $t_th, 'dialog_thread_id');
            $title = substr($thread_key, 0, 255);
            $ins = $db->insert($t_th, array(
                'dialog_thread_id' => $thread_id,
                'title' => $title,
                'thread_key' => $thread_key,
                'last_message_ymdhis' => $now,
                'federation_node_id' => $fn,
                'channel_id' => $channel_id,
                'project_slug' => null,
                'task_name' => null,
                'created_by_actor_id' => $from_actor_id,
                'status' => 'Open',
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_deleted' => 0,
                'owner_actor_id' => $from_actor_id,
                'assigned_actor_id' => $from_actor_id,
                'thread_type' => 'discussion',
                'thread_priority' => 'normal',
                'visibility_status' => 'active',
            ));
            if ($ins === false) {
                $thread = $db->fetchRow(
                    "SELECT dialog_thread_id FROM {$t_th} WHERE federation_node_id = :fn AND channel_id = :cid AND thread_key = :tk AND is_deleted = 0 LIMIT 1",
                    array('fn' => $fn, 'cid' => $channel_id, 'tk' => $thread_key)
                );
                if (!$thread) {
                    return array(
                        'ok' => false,
                        'error_code' => 'server_error',
                        'message' => 'Could not create or resolve thread',
                        'http_status' => 500,
                    );
                }
                $dialog_thread_id = (int) $thread['dialog_thread_id'];
            } else {
                $dialog_thread_id = $thread_id;
            }
        } else {
            $dialog_thread_id = (int) $thread['dialog_thread_id'];
        }

        $meta = array();
        if (!empty($input['metadata_json'])) {
            $extra = json_decode((string) $input['metadata_json'], true);
            if (is_array($extra)) {
                foreach ($extra as $k => $v) {
                    $meta[$k] = $v;
                }
            }
        }
        if (!empty($input['task'])) {
            $meta['task'] = $input['task'];
        }
        if (isset($input['context']) && $input['context'] !== null) {
            $meta['context'] = $input['context'];
        }
        $meta_json = count($meta) > 0 ? json_encode($meta) : null;

        $text = substr($message, 0, 1000);
        $to_actor_id_in = isset($input['to_actor_id']) ? (int) $input['to_actor_id'] : 0;
        $to_actor_sql = ($to_actor_id_in > 0) ? $to_actor_id_in : null;

        $rw = DialogMvpService::rewriteHumanDialogMessageBodyForInsert(
            $db,
            $text,
            $from_actor_id,
            $to_actor_sql,
            $meta_json
        );
        $text = $rw['body'];
        $meta_json = $rw['metadata_json'];

        $msg_id = DialogMvpService::nextId($db, $t_msg, 'dialog_message_id');

        $row = array(
            'dialog_message_id' => $msg_id,
            'dialog_thread_id' => $dialog_thread_id,
            'channel_id' => $channel_id,
            'channel_key' => $channel_key,
            'from_actor_id' => $from_actor_id,
            'source_faucet_slug' => '',
            'source_faucet_instance_id' => '',
            'to_actor_id' => $to_actor_sql,
            'read_by_actor_id' => 0,
            'read_by_actor_utc' => 0,
            'message_text' => $text,
            'message_type' => 'text',
            'metadata_json' => $meta_json,
            'mood_vector' => '666666',
            'mood_framework' => 'western_analytical',
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
            'deleted_ymdhis' => null,
        );

        $insm = $db->insert($t_msg, $row);
        if ($insm === false) {
            return array(
                'ok' => false,
                'error_code' => 'server_error',
                'message' => 'Insert dialog_messages failed',
                'http_status' => 500,
            );
        }

        $db->update(
            $t_th,
            array(
                'last_message_ymdhis' => $now,
                'updated_ymdhis' => $now,
            ),
            'dialog_thread_id = :tid',
            array('tid' => $dialog_thread_id)
        );

        return array(
            'ok' => true,
            'dialog_message_id' => (string) $msg_id,
            'dialog_thread_id' => $dialog_thread_id,
            'status' => 'ok',
        );
    }
}
