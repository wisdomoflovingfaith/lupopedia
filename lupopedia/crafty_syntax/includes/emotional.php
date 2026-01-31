<?php
/**
 * Crafty Syntax - Emotional Metadata Functions
 *
 * Implements emotional baseline calculation based on TOON doctrine:
 * - Pono (righteousness): positive alignment
 * - Pilau (rot/decay): negative alignment
 * - Kapakai (uncertainty): risk/ambiguity marker
 *
 * Baseline mood_rgb: (200, 200, 200) - neutral gray
 */

/**
 * Calculate mood_rgb from emotional scores
 *
 * @param float $pono_score Pono score (0.00 - 1.00)
 * @param float $pilau_score Pilau score (0.00 - 1.00)
 * @param float $kapakai_score Kapakai score (0.00 - 1.00)
 * @return string Hex color code (e.g., "c8c8c8")
 */
function lupo_crafty_calculate_mood_rgb($pono_score, $pilau_score, $kapakai_score) {
    // Baseline: (200, 200, 200) - neutral gray
    $base_r = 200;
    $base_g = 200;
    $base_b = 200;

    // Pono increases green (righteousness)
    // Range: 0.00 (no effect) to 1.00 (max +55 green)
    $pono_influence = ($pono_score * 55);

    // Pilau decreases green and blue (decay/rot)
    // Range: 0.00 (no effect) to 1.00 (max -100 green/blue)
    $pilau_influence = ($pilau_score * 100);

    // Kapakai decreases blue (uncertainty/ambiguity)
    // Range: 0.00 (no effect) to 1.00 (max -100 blue)
    $kapakai_influence = ($kapakai_score * 100);

    // Calculate final RGB values
    $r = $base_r + ($pono_score * 55); // Pono adds warmth
    $g = $base_g + $pono_influence - $pilau_influence; // Pono adds, Pilau subtracts
    $b = $base_b - $pilau_influence - $kapakai_influence; // Both subtract

    // Clamp to valid RGB range (0-255)
    $r = max(0, min(255, (int)$r));
    $g = max(0, min(255, (int)$g));
    $b = max(0, min(255, (int)$b));

    // Return as hex string
    return sprintf('%02x%02x%02x', $r, $g, $b);
}

/**
 * Calculate emotional stability score
 *
 * @param float $pono_score
 * @param float $pilau_score
 * @return float Emotional stability (-1.00 to +1.00)
 */
function lupo_crafty_emotional_stability($pono_score, $pilau_score) {
    return $pono_score - $pilau_score;
}

/**
 * Get emotional status label
 *
 * @param float $stability Emotional stability score
 * @return string Human-readable label
 */
function lupo_crafty_emotional_label($stability) {
    if ($stability > 0.5) {
        return 'stable';
    } elseif ($stability > 0) {
        return 'balanced';
    } elseif ($stability > -0.5) {
        return 'stressed';
    } else {
        return 'overwhelmed';
    }
}

/**
 * Get emotional datapoint count for operator
 *
 * @param int $operator_id
 * @return int Number of emotional datapoints
 */
function lupo_crafty_get_emotional_datapoint_count($operator_id) {
    $db = lupo_crafty_db();
    if (!$db) {
        return 0;
    }

    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    try {
        // Count emotional stars created by this operator
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM {$table_prefix}emotional_stars WHERE created_by = :operator_id");
        $stmt->execute([':operator_id' => $operator_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("EMOTIONAL: Failed to count datapoints: " . $e->getMessage());
        }
        return 0;
    }
}

/**
 * Add small variance to emotional scores for sparse data
 *
 * @param float $score Base score
 * @param int $seed Random seed (operator_id)
 * @return float Score with variance
 */
function lupo_crafty_add_emotional_variance($score, $seed) {
    // Use deterministic random based on operator_id
    mt_srand($seed);
    $variance = (mt_rand(-10, 10) / 100); // +/- 0.10
    mt_srand(); // Reset random seed

    return max(0, min(1, $score + $variance));
}

/**
 * Get emotional metadata for display
 *
 * @param array $operator Operator record with pono/pilau/kapakai scores
 * @return array Emotional metadata array
 */
function lupo_crafty_get_emotional_metadata($operator) {
    $operator_id = $operator['operator_id'] ?? 0;
    $datapoint_count = lupo_crafty_get_emotional_datapoint_count($operator_id);

    // If insufficient emotional data, return kapakai gray
    if ($datapoint_count < 10) {
        return [
            'pono' => '0.50',
            'pilau' => '0.00',
            'kapakai' => '1.00',
            'stability' => 0.50,
            'stability_label' => 'insufficient_data',
            'mood_rgb' => '808080', // Kapakai gray
            'datapoint_count' => $datapoint_count
        ];
    }

    // Get scores with variance for realistic display
    $pono = lupo_crafty_add_emotional_variance($operator['pono_score'] ?? 1.0, $operator_id * 2);
    $pilau = lupo_crafty_add_emotional_variance($operator['pilau_score'] ?? 0.0, $operator_id * 3);
    $kapakai = lupo_crafty_add_emotional_variance($operator['kapakai_score'] ?? 0.5, $operator_id * 5);

    $stability = lupo_crafty_emotional_stability($pono, $pilau);
    $mood_rgb = lupo_crafty_calculate_mood_rgb($pono, $pilau, $kapakai);

    return [
        'pono' => number_format($pono, 2),
        'pilau' => number_format($pilau, 2),
        'kapakai' => number_format($kapakai, 2),
        'stability' => round($stability, 2),
        'stability_label' => lupo_crafty_emotional_label($stability),
        'mood_rgb' => $mood_rgb,
        'datapoint_count' => $datapoint_count
    ];
}

function lupo_crafty_emotional_snapshot($operator) {
    return lupo_crafty_get_emotional_metadata($operator);
}

/**
 * Compute thread-level mood from message-level mood_rgb values
 *
 * Implementation follows EMOTIONAL AGGREGATION SPEC v0.2 (weight-free)
 * - Converts hex mood to RGB numeric axes
 * - Applies ephemeral recency influence (not stored, not doctrinal)
 * - Averages axes with recency weighting
 * - Clamps to valid hex range
 *
 * @param int $thread_id Thread ID
 * @param bool $use_recency Apply time-decay weighting (default: true)
 * @return string|null Thread mood as 6-digit hex, or null if no messages
 */
function lupo_crafty_compute_thread_mood($thread_id, $use_recency = true) {
    $db = lupo_crafty_db();
    if (!$db) {
        return null;
    }

    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    try {
        // Fetch messages with mood_rgb and timestamp
        $sql = "SELECT mood_rgb, created_ymdhis, message_type, metadata_json
                FROM {$table_prefix}dialog_messages
                WHERE dialog_thread_id = :thread_id
                  AND is_deleted = 0
                  AND mood_rgb IS NOT NULL
                ORDER BY created_ymdhis ASC";

        $stmt = $db->prepare($sql);
        $stmt->execute([':thread_id' => $thread_id]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($messages)) {
            return null; // No messages with mood
        }

        // Initialize accumulators
        $r_total = 0;
        $g_total = 0;
        $b_total = 0;
        $recency_sum = 0;

        $now = time();

        foreach ($messages as $msg) {
            $mood_rgb = $msg['mood_rgb'];

            // Skip invalid mood values
            if (strlen($mood_rgb) !== 6 || !ctype_xdigit($mood_rgb)) {
                continue;
            }

            // 4.1: Convert hex → numeric tensor
            $r = hexdec(substr($mood_rgb, 0, 2));
            $g = hexdec(substr($mood_rgb, 2, 2));
            $b = hexdec(substr($mood_rgb, 4, 2));

            // 4.2: Recency influence (ephemeral, computed, not stored)
            $recency_factor = 1.0;
            if ($use_recency) {
                $created_timestamp = lupo_ymdhis_to_timestamp($msg['created_ymdhis']);
                $hours_since = ($now - $created_timestamp) / 3600;
                $recency_factor = 1 / (1 + $hours_since);
            }

            // 4.3: Weighted average using recency factor
            $r_total += $r * $recency_factor;
            $g_total += $g * $recency_factor;
            $b_total += $b * $recency_factor;
            $recency_sum += $recency_factor;
        }

        if ($recency_sum == 0) {
            return null; // No valid messages
        }

        // Compute averages
        $r_avg = $r_total / $recency_sum;
        $g_avg = $g_total / $recency_sum;
        $b_avg = $b_total / $recency_sum;

        // 4.4: Normalize to valid hex
        $r_final = max(0, min(255, round($r_avg)));
        $g_final = max(0, min(255, round($g_avg)));
        $b_final = max(0, min(255, round($b_avg)));

        // Encode as 6-digit hex
        return sprintf('%02x%02x%02x', $r_final, $g_final, $b_final);

    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("THREAD MOOD: Failed to compute for thread $thread_id: " . $e->getMessage());
        }
        return null;
    }
}

/**
 * Convert YMDHIS timestamp to Unix timestamp
 *
 * @param string|int $ymdhis YYYYMMDDHHMMSS format
 * @return int Unix timestamp
 */
function lupo_ymdhis_to_timestamp($ymdhis) {
    $ymdhis = (string)$ymdhis;
    if (strlen($ymdhis) !== 14) {
        return time(); // Invalid format, return current time
    }

    $year = substr($ymdhis, 0, 4);
    $month = substr($ymdhis, 4, 2);
    $day = substr($ymdhis, 6, 2);
    $hour = substr($ymdhis, 8, 2);
    $minute = substr($ymdhis, 10, 2);
    $second = substr($ymdhis, 12, 2);

    return mktime((int)$hour, (int)$minute, (int)$second, (int)$month, (int)$day, (int)$year);
}
