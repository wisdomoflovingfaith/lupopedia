<?php
/**
 * Crafty Syntax - Kapu Protocol (Operator Self-Care)
 *
 * Kapu = sacred/forbidden/protected state
 * When an operator needs rest or self-care, they invoke kapu protocol
 *
 * Effects:
 * - Log kapu request
 * - Reduce operator capacity to 1
 * - Trigger escalation for active chats
 * - Slightly increase pono score (self-care is righteous)
 */

/**
 * Invoke kapu protocol for operator
 *
 * @param int $operator_id Operator ID
 * @param string $reason Optional reason for kapu (logged but not required)
 * @return array Result with success status and message
 */
function lupo_crafty_invoke_kapu($operator_id, $reason = '') {
    $db = lupo_crafty_db();
    if (!$db) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }

    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $timestamp = lupo_utc_timestamp();

    try {
        $db->beginTransaction();

        // Get current emotional state before kapu
        $emotional_sql = "SELECT pono_score, pilau_score, kapakai_score
                          FROM {$table_prefix}operators
                          WHERE operator_id = :operator_id";
        $stmt = $db->prepare($emotional_sql);
        $stmt->execute([':operator_id' => $operator_id]);
        $emotional = $stmt->fetch(PDO::FETCH_ASSOC);

        $pono_at_invoke = $emotional['pono_score'] ?? 1.0;
        $pilau_at_invoke = $emotional['pilau_score'] ?? 0.0;
        $kapakai_at_invoke = $emotional['kapakai_score'] ?? 0.5;

        // 1. Log kapu request with emotional state
        $log_sql = "INSERT INTO {$table_prefix}operator_kapu_log
                    (operator_id, reason, invoked_ymdhis, created_ymdhis, updated_ymdhis,
                     pono_at_invoke, pilau_at_invoke, kapakai_at_invoke)
                    VALUES (:operator_id, :reason, :timestamp, :timestamp, :timestamp,
                            :pono_at_invoke, :pilau_at_invoke, :kapakai_at_invoke)";
        $stmt = $db->prepare($log_sql);
        $stmt->execute([
            ':operator_id' => $operator_id,
            ':reason' => $reason ?: 'Self-care requested',
            ':timestamp' => $timestamp,
            ':pono_at_invoke' => $pono_at_invoke,
            ':pilau_at_invoke' => $pilau_at_invoke,
            ':kapakai_at_invoke' => $kapakai_at_invoke
        ]);

        $kapu_log_id = $db->lastInsertId();

        // 2. Update operator status to reduce capacity
        $status_sql = "UPDATE {$table_prefix}operator_status
                       SET max_chat_capacity = 1,
                           status = 'kapu',
                           updated_ymdhis = :timestamp
                       WHERE operator_id = :operator_id";
        $stmt = $db->prepare($status_sql);
        $stmt->execute([
            ':operator_id' => $operator_id,
            ':timestamp' => $timestamp
        ]);

        // 3. Increase pono score slightly (self-care is pono)
        $pono_sql = "UPDATE {$table_prefix}operators
                     SET pono_score = LEAST(1.0, pono_score + 0.05),
                         updated_ymdhis = :timestamp
                     WHERE operator_id = :operator_id";
        $stmt = $db->prepare($pono_sql);
        $stmt->execute([
            ':operator_id' => $operator_id,
            ':timestamp' => $timestamp
        ]);

        // 4. Trigger escalation for active chats (mark for reassignment)
        $escalation_sql = "INSERT INTO {$table_prefix}operator_escalations
                          (operator_id, thread_id, escalation_reason, priority, status, created_ymdhis, updated_ymdhis)
                          SELECT
                              :operator_id,
                              dt.dialog_thread_id,
                              'kapu_invoked',
                              'high',
                              'pending',
                              :timestamp,
                              :timestamp
                          FROM {$table_prefix}dialog_threads dt
                          JOIN {$table_prefix}operator_chat_assignments oca
                              ON dt.dialog_thread_id = oca.thread_id
                          WHERE oca.operator_id = :operator_id
                            AND dt.status = 'active'
                            AND oca.status = 'active'";
        $stmt = $db->prepare($escalation_sql);
        $stmt->execute([
            ':operator_id' => $operator_id,
            ':timestamp' => $timestamp
        ]);

        $db->commit();

        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("KAPU: Operator $operator_id invoked kapu protocol. Reason: $reason");
        }

        return [
            'success' => true,
            'message' => 'Kapu protocol activated. You are now in protected state.',
            'kapu_log_id' => $kapu_log_id
        ];

    } catch (Exception $e) {
        $db->rollBack();
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("KAPU: Failed to invoke kapu for operator $operator_id: " . $e->getMessage());
        }
        return [
            'success' => false,
            'message' => 'Failed to activate kapu protocol: ' . $e->getMessage()
        ];
    }
}

/**
 * Release kapu protocol for operator
 *
 * @param int $operator_id Operator ID
 * @return array Result with success status and message
 */
function lupo_crafty_release_kapu($operator_id) {
    $db = lupo_crafty_db();
    if (!$db) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }

    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $timestamp = lupo_utc_timestamp();

    try {
        $db->beginTransaction();

        // Get current emotional state after kapu rest period
        $emotional_sql = "SELECT pono_score, pilau_score, kapakai_score
                          FROM {$table_prefix}operators
                          WHERE operator_id = :operator_id";
        $stmt = $db->prepare($emotional_sql);
        $stmt->execute([':operator_id' => $operator_id]);
        $emotional = $stmt->fetch(PDO::FETCH_ASSOC);

        $pono_at_release = $emotional['pono_score'] ?? 1.0;
        $pilau_at_release = $emotional['pilau_score'] ?? 0.0;
        $kapakai_at_release = $emotional['kapakai_score'] ?? 0.5;

        // Update most recent kapu log entry with release data
        $log_sql = "UPDATE {$table_prefix}operator_kapu_log
                    SET released_ymdhis = :timestamp,
                        pono_at_release = :pono_at_release,
                        pilau_at_release = :pilau_at_release,
                        kapakai_at_release = :kapakai_at_release,
                        updated_ymdhis = :timestamp
                    WHERE operator_id = :operator_id
                      AND released_ymdhis IS NULL
                    ORDER BY invoked_ymdhis DESC
                    LIMIT 1";
        $stmt = $db->prepare($log_sql);
        $stmt->execute([
            ':operator_id' => $operator_id,
            ':timestamp' => $timestamp,
            ':pono_at_release' => $pono_at_release,
            ':pilau_at_release' => $pilau_at_release,
            ':kapakai_at_release' => $kapakai_at_release
        ]);

        // Restore normal capacity
        $status_sql = "UPDATE {$table_prefix}operator_status
                       SET max_chat_capacity = 5,
                           status = 'online',
                           updated_ymdhis = :timestamp
                       WHERE operator_id = :operator_id";
        $stmt = $db->prepare($status_sql);
        $stmt->execute([
            ':operator_id' => $operator_id,
            ':timestamp' => $timestamp
        ]);

        $db->commit();

        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("KAPU: Operator $operator_id released from kapu protocol");
        }

        return [
            'success' => true,
            'message' => 'Kapu protocol released. Normal capacity restored.'
        ];

    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("KAPU: Failed to release kapu for operator $operator_id: " . $e->getMessage());
        }
        return [
            'success' => false,
            'message' => 'Failed to release kapu protocol: ' . $e->getMessage()
        ];
    }
}
