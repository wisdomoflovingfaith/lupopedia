<?php
function lupo_crafty_should_escalate($signals) {
    $reasons = [];

    $confidence = isset($signals['confidence']) ? (float)$signals['confidence'] : null;
    if ($confidence !== null && $confidence < 0.55) {
        $reasons[] = 'confusion_low_confidence';
    }

    $repeat_count = isset($signals['repeat_count']) ? (int)$signals['repeat_count'] : 0;
    if ($repeat_count >= 2) {
        $reasons[] = 'confusion_repeat';
    }

    $user_emotion = isset($signals['user_emotion']) ? strtolower((string)$signals['user_emotion']) : '';
    if (in_array($user_emotion, ['angry', 'distressed', 'frustrated', 'confused'], true)) {
        $reasons[] = 'conflict_user_emotion';
    }

    if (!empty($signals['policy_boundary'])) {
        $reasons[] = 'policy_boundary';
    }

    if (!empty($signals['human_judgment'])) {
        $reasons[] = 'human_judgment_required';
    }

    $mismatch_score = isset($signals['mismatch_score']) ? (float)$signals['mismatch_score'] : null;
    $mismatch_threshold = isset($signals['mismatch_threshold']) ? (float)$signals['mismatch_threshold'] : 0.5;
    if ($mismatch_score !== null && $mismatch_score > $mismatch_threshold) {
        $reasons[] = 'emotional_mismatch';
    }

    return [
        'should_escalate' => !empty($reasons),
        'reasons' => $reasons
    ];
}

function lupo_crafty_extract_topic($thread_row) {
    if (!empty($thread_row['metadata_json'])) {
        $meta = json_decode($thread_row['metadata_json'], true);
        if (is_array($meta)) {
            if (!empty($meta['topic'])) {
                return (string)$meta['topic'];
            }
            if (!empty($meta['intent'])) {
                return (string)$meta['intent'];
            }
        }
    }

    if (!empty($thread_row['task_name'])) {
        return (string)$thread_row['task_name'];
    }

    if (!empty($thread_row['summary_text'])) {
        return (string)$thread_row['summary_text'];
    }

    return '';
}

function lupo_crafty_resolve_department_id($db, $table_prefix, $topic, $thread_row) {
    if (!empty($thread_row['metadata_json'])) {
        $meta = json_decode($thread_row['metadata_json'], true);
        if (is_array($meta)) {
            if (!empty($meta['department_id'])) {
                return (int)$meta['department_id'];
            }
            if (!empty($meta['department_name'])) {
                $match = lupo_crafty_match_department_by_name($db, $table_prefix, (string)$meta['department_name']);
                if ($match) {
                    return $match;
                }
            }
        }
    }

    if ($topic !== '') {
        $match = lupo_crafty_match_department_by_topic($db, $table_prefix, $topic);
        if ($match) {
            return $match;
        }
    }

    return null;
}

function lupo_crafty_match_department_by_name($db, $table_prefix, $department_name) {
    $stmt = $db->prepare("SELECT department_id
        FROM {$table_prefix}departments
        WHERE is_deleted = 0
          AND name = :name
        LIMIT 1");
    $stmt->execute([':name' => $department_name]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (int)$row['department_id'] : null;
}

function lupo_crafty_match_department_by_topic($db, $table_prefix, $topic) {
    $topic = strtolower($topic);
    $stmt = $db->query("SELECT department_id, name, description
        FROM {$table_prefix}departments
        WHERE is_deleted = 0
        ORDER BY name ASC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $name = strtolower((string)$row['name']);
        if ($name !== '' && strpos($topic, $name) !== false) {
            return (int)$row['department_id'];
        }

        $description = strtolower((string)$row['description']);
        if ($description !== '' && strpos($description, $topic) !== false) {
            return (int)$row['department_id'];
        }
    }

    return null;
}

function lupo_crafty_resolve_channel_id($db, $table_prefix, $department_id, $thread_row) {
    if (!empty($thread_row['channel_id'])) {
        return (int)$thread_row['channel_id'];
    }

    if ($department_id) {
        $stmt = $db->prepare("SELECT name
            FROM {$table_prefix}departments
            WHERE department_id = :department_id
              AND is_deleted = 0
            LIMIT 1");
        $stmt->execute([':department_id' => (int)$department_id]);
        $dept_row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($dept_row) {
            $dept_name = strtolower((string)$dept_row['name']);
            $channels = $db->query("SELECT channel_id, channel_name, channel_key
                FROM {$table_prefix}channels
                WHERE is_deleted = 0
                  AND status_flag = 1
                ORDER BY channel_name ASC")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($channels as $channel) {
                $channel_name = strtolower((string)$channel['channel_name']);
                $channel_key = strtolower((string)$channel['channel_key']);
                if ($dept_name !== '' && (strpos($channel_name, $dept_name) !== false || strpos($channel_key, $dept_name) !== false)) {
                    return (int)$channel['channel_id'];
                }
            }
        }
    }

    return null;
}

function lupo_crafty_select_fallback_operator($db, $table_prefix) {
    $stmt = $db->query("SELECT o.operator_id
        FROM {$table_prefix}operators o
        JOIN {$table_prefix}actor_roles ar
          ON ar.actor_id = o.actor_id
        WHERE o.is_active = 1
          AND ar.role_key = 'supervisor'
          AND ar.is_deleted = 0
        LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (int)$row['operator_id'] : null;
}

function lupo_crafty_update_thread_escalation($db, $table_prefix, $thread_id, $payload) {
    $stmt = $db->prepare("SELECT metadata_json
        FROM {$table_prefix}dialog_threads
        WHERE dialog_thread_id = :thread_id
          AND is_deleted = 0
        LIMIT 1");
    $stmt->execute([':thread_id' => (int)$thread_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $metadata = [];
    if ($row && !empty($row['metadata_json'])) {
        $decoded = json_decode($row['metadata_json'], true);
        if (is_array($decoded)) {
            $metadata = $decoded;
        }
    }

    $metadata['escalation'] = $payload;
    $now = lupo_utc_timestamp();

    $stmt = $db->prepare("UPDATE {$table_prefix}dialog_threads
        SET metadata_json = :metadata_json,
            escalated_to_operator_id = :escalated_to_operator_id,
            escalation_reason = :escalation_reason,
            escalation_timestamp = :escalation_timestamp,
            updated_ymdhis = :updated_ymdhis
        WHERE dialog_thread_id = :thread_id
          AND is_deleted = 0");
    $stmt->execute([
        ':metadata_json' => json_encode($metadata, JSON_UNESCAPED_SLASHES),
        ':escalated_to_operator_id' => isset($payload['operator_id']) ? (int)$payload['operator_id'] : null,
        ':escalation_reason' => isset($payload['escalation_reason']) ? (string)$payload['escalation_reason'] : (isset($payload['reason']) ? (string)$payload['reason'] : (isset($payload['reasons']) ? implode(',', (array)$payload['reasons']) : null)),
        ':escalation_timestamp' => isset($payload['timestamp_ymdhis']) ? (int)$payload['timestamp_ymdhis'] : $now,
        ':updated_ymdhis' => $now,
        ':thread_id' => (int)$thread_id
    ]);
}

function lupo_crafty_increment_operator_load($db, $table_prefix, $operator_id) {
    $now = lupo_utc_timestamp();
    $stmt = $db->prepare("SELECT operator_status_id, active_chat_count
        FROM {$table_prefix}operator_status
        WHERE operator_id = :operator_id
        LIMIT 1");
    $stmt->execute([':operator_id' => (int)$operator_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $new_count = (int)$row['active_chat_count'] + 1;
        $stmt = $db->prepare("UPDATE {$table_prefix}operator_status
            SET active_chat_count = :active_chat_count,
                updated_ymdhis = :updated_ymdhis
            WHERE operator_status_id = :status_id");
        $stmt->execute([
            ':active_chat_count' => $new_count,
            ':updated_ymdhis' => $now,
            ':status_id' => (int)$row['operator_status_id']
        ]);
        return;
    }

    $stmt = $db->prepare("INSERT INTO {$table_prefix}operator_status (
        operator_id,
        status,
        last_seen_ymdhis,
        active_chat_count,
        max_chat_capacity,
        created_ymdhis,
        updated_ymdhis
    ) VALUES (
        :operator_id,
        'online',
        :last_seen_ymdhis,
        1,
        3,
        :created_ymdhis,
        :updated_ymdhis
    )");
    $stmt->execute([
        ':operator_id' => (int)$operator_id,
        ':last_seen_ymdhis' => $now,
        ':created_ymdhis' => $now,
        ':updated_ymdhis' => $now
    ]);
}

function lupo_crafty_log_agent_escalation($db, $table_prefix, $context, $payload) {
    if (empty($context['agent_id']) || empty($context['domain_id'])) {
        return;
    }

    $stmt = $db->prepare("INSERT INTO {$table_prefix}agent_tool_calls (
        agent_id,
        faucet_id,
        domain_id,
        tool_name,
        action_type,
        input_json,
        output_json,
        provider,
        model_name,
        tokens_prompt,
        tokens_completion,
        tokens_total,
        cost_usd,
        latency_ms,
        status,
        error_message,
        parent_call_id,
        thread_id,
        message_id,
        created_ymdhis,
        completed_ymdhis
    ) VALUES (
        :agent_id,
        :faucet_id,
        :domain_id,
        :tool_name,
        :action_type,
        :input_json,
        :output_json,
        :provider,
        :model_name,
        0,
        0,
        0,
        0.000000,
        0,
        :status,
        NULL,
        :parent_call_id,
        :thread_id,
        :message_id,
        :created_ymdhis,
        :completed_ymdhis
    )");

    $now = lupo_utc_timestamp();
    $stmt->execute([
        ':agent_id' => (int)$context['agent_id'],
        ':faucet_id' => isset($context['faucet_id']) ? (int)$context['faucet_id'] : null,
        ':domain_id' => (int)$context['domain_id'],
        ':tool_name' => 'escalation_handoff',
        ':action_type' => 'escalation',
        ':input_json' => json_encode($payload, JSON_UNESCAPED_SLASHES),
        ':output_json' => json_encode(['status' => 'handoff'], JSON_UNESCAPED_SLASHES),
        ':provider' => isset($context['provider']) ? (string)$context['provider'] : null,
        ':model_name' => isset($context['model_name']) ? (string)$context['model_name'] : null,
        ':status' => 'success',
        ':parent_call_id' => isset($context['parent_call_id']) ? (int)$context['parent_call_id'] : null,
        ':thread_id' => isset($context['thread_id']) ? (int)$context['thread_id'] : null,
        ':message_id' => isset($context['message_id']) ? (int)$context['message_id'] : null,
        ':created_ymdhis' => $now,
        ':completed_ymdhis' => $now
    ]);
}

function lupo_crafty_escalate_thread($thread_id, $signals, $context = []) {
    $decision = lupo_crafty_should_escalate($signals);
    if (!$decision['should_escalate']) {
        return [
            'escalated' => false,
            'reasons' => [],
            'operator_id' => null,
            'department_id' => null,
            'channel_id' => null
        ];
    }

    $db = lupo_crafty_db();
    if (!$db) {
        return [
            'escalated' => false,
            'reasons' => $decision['reasons'],
            'operator_id' => null,
            'department_id' => null,
            'channel_id' => null
        ];
    }

    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $stmt = $db->prepare("SELECT dialog_thread_id, channel_id, task_name, summary_text, metadata_json
        FROM {$table_prefix}dialog_threads
        WHERE dialog_thread_id = :thread_id
          AND is_deleted = 0
        LIMIT 1");
    $stmt->execute([':thread_id' => (int)$thread_id]);
    $thread_row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$thread_row) {
        return [
            'escalated' => false,
            'reasons' => $decision['reasons'],
            'operator_id' => null,
            'department_id' => null,
            'channel_id' => null
        ];
    }

    $topic = lupo_crafty_extract_topic($thread_row);
    $department_id = lupo_crafty_resolve_department_id($db, $table_prefix, $topic, $thread_row);
    $channel_id = lupo_crafty_resolve_channel_id($db, $table_prefix, $department_id, $thread_row);

    $operator_id = lupo_crafty_select_operator($department_id, $channel_id);
    if (!$operator_id) {
        $operator_id = lupo_crafty_select_fallback_operator($db, $table_prefix);
    }

    if ($operator_id) {
        $payload = [
            'operator_id' => (int)$operator_id,
            'department_id' => $department_id ? (int)$department_id : null,
            'channel_id' => $channel_id ? (int)$channel_id : null,
            'reasons' => $decision['reasons'],
            'topic' => $topic,
            'timestamp_ymdhis' => lupo_utc_timestamp()
        ];

        lupo_crafty_update_thread_escalation($db, $table_prefix, $thread_id, $payload);
        lupo_crafty_increment_operator_load($db, $table_prefix, $operator_id);

        $context['thread_id'] = (int)$thread_id;
        $context['message_id'] = isset($context['message_id']) ? (int)$context['message_id'] : null;
        lupo_crafty_log_agent_escalation($db, $table_prefix, $context, $payload);
    }

    return [
        'escalated' => (bool)$operator_id,
        'reasons' => $decision['reasons'],
        'operator_id' => $operator_id ? (int)$operator_id : null,
        'department_id' => $department_id ? (int)$department_id : null,
        'channel_id' => $channel_id ? (int)$channel_id : null
    ];
}
