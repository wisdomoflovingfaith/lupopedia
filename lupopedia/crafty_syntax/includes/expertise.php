<?php
function lupo_crafty_emotional_stability($mood_row) {
    if (!$mood_row) {
        return 0;
    }

    $r = (int)$mood_row['mood_r'];
    $g = (int)$mood_row['mood_g'];
    $b = (int)$mood_row['mood_b'];

    // Simple banding: red heavy -> overwhelmed, yellow -> stressed, green/blue -> stable.
    if ($r >= 200 && $g < 120) {
        return -1;
    }
    if ($r >= 200 && $g >= 160) {
        return 0;
    }
    if ($g >= 150 || $b >= 150) {
        return 1;
    }

    return 0;
}

function lupo_crafty_operator_performance($operator_row) {
    if (empty($operator_row['metadata_json'])) {
        return 0;
    }

    $metadata = json_decode($operator_row['metadata_json'], true);
    if (!is_array($metadata)) {
        return 0;
    }

    if (isset($metadata['performance_score'])) {
        $score = (int)$metadata['performance_score'];
        if ($score > 0) {
            return 1;
        }
        if ($score < 0) {
            return -1;
        }
    }

    return 0;
}

function lupo_crafty_operator_availability($status_row) {
    if (!$status_row || empty($status_row['status'])) {
        return -1;
    }

    $status = strtolower($status_row['status']);
    if ($status === 'online') {
        return 1;
    }
    if ($status === 'away') {
        return 0;
    }

    return -1;
}

function lupo_crafty_operator_load_score($status_row) {
    if (!$status_row) {
        return 0;
    }

    $count = (int)$status_row['active_chat_count'];
    if ($count < 3) {
        return 1;
    }
    if ($count <= 5) {
        return 0;
    }

    return -1;
}

function lupo_crafty_select_operator($department_id, $channel_id) {
    $db = lupo_crafty_db();
    if (!$db) {
        return null;
    }

    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    $operators = $db->query("SELECT o.operator_id,
            o.actor_id,
            o.metadata_json,
            o.department_id
        FROM {$table_prefix}operators o
        WHERE o.is_active = 1")->fetchAll(PDO::FETCH_ASSOC);

    if (!$operators) {
        return null;
    }

    $actor_ids = array_column($operators, 'actor_id');
    $operator_ids = array_column($operators, 'operator_id');

    $actor_roles = [];
    if ($actor_ids) {
        $placeholders = implode(',', array_fill(0, count($actor_ids), '?'));
        $stmt = $db->prepare("SELECT actor_id, role_key
            FROM {$table_prefix}actor_roles
            WHERE actor_id IN ($placeholders)
              AND is_deleted = 0");
        $stmt->execute($actor_ids);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $actor_roles[$row['actor_id']][] = $row['role_key'];
        }
    }

    $actor_departments = [];
    if ($actor_ids) {
        $placeholders = implode(',', array_fill(0, count($actor_ids), '?'));
        $stmt = $db->prepare("SELECT actor_id, department_id
            FROM {$table_prefix}actor_departments
            WHERE actor_id IN ($placeholders)
              AND is_deleted = 0");
        $stmt->execute($actor_ids);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $actor_departments[$row['actor_id']][] = (int)$row['department_id'];
        }
    }

    $actor_channels = [];
    if ($actor_ids) {
        $placeholders = implode(',', array_fill(0, count($actor_ids), '?'));
        $stmt = $db->prepare("SELECT actor_id, channel_id
            FROM {$table_prefix}actor_channels
            WHERE actor_id IN ($placeholders)
              AND is_deleted = 0
              AND status = 'A'");
        $stmt->execute($actor_ids);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $actor_channels[$row['actor_id']][] = (int)$row['channel_id'];
        }
    }

    $actor_moods = [];
    if ($actor_ids) {
        $placeholders = implode(',', array_fill(0, count($actor_ids), '?'));
        $stmt = $db->prepare("SELECT actor_id, mood_r, mood_g, mood_b
            FROM {$table_prefix}actor_moods
            WHERE actor_id IN ($placeholders)");
        $stmt->execute($actor_ids);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $actor_moods[$row['actor_id']] = $row;
        }
    }

    $operator_status = [];
    if ($operator_ids) {
        $placeholders = implode(',', array_fill(0, count($operator_ids), '?'));
        $stmt = $db->prepare("SELECT operator_id, status, last_seen_ymdhis, active_chat_count, max_chat_capacity, updated_ymdhis
            FROM {$table_prefix}operator_status
            WHERE operator_id IN ($placeholders)");
        $stmt->execute($operator_ids);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $operator_status[$row['operator_id']] = $row;
        }
    }

    $candidates = [];
    foreach ($operators as $operator) {
        $actor_id = (int)$operator['actor_id'];
        $operator_id = (int)$operator['operator_id'];

        $roles = isset($actor_roles[$actor_id]) ? $actor_roles[$actor_id] : [];
        $dept_memberships = isset($actor_departments[$actor_id]) ? $actor_departments[$actor_id] : [];
        $channel_memberships = isset($actor_channels[$actor_id]) ? $actor_channels[$actor_id] : [];

        $role_match = in_array('operator', $roles, true) || in_array('expert', $roles, true) ? 1 : 0;
        $department_match = in_array((int)$department_id, $dept_memberships, true) ? 1 : 0;
        $channel_match = in_array((int)$channel_id, $channel_memberships, true) ? 1 : 0;

        $mood_row = isset($actor_moods[$actor_id]) ? $actor_moods[$actor_id] : null;
        $emotional = lupo_crafty_emotional_stability($mood_row);
        if ($emotional < 0) {
            continue;
        }

        $status_row = isset($operator_status[$operator_id]) ? $operator_status[$operator_id] : null;
        $availability = lupo_crafty_operator_availability($status_row);
        if ($availability < 0) {
            continue;
        }

        $load = lupo_crafty_operator_load_score($status_row);
        $performance = lupo_crafty_operator_performance($operator);

        $score = ($department_match * 4)
            + ($channel_match * 4)
            + ($role_match * 3)
            + ($emotional * 2)
            + ($availability * 2)
            + ($load * 1)
            + ($performance * 1);

        $last_assignment = $status_row && !empty($status_row['updated_ymdhis'])
            ? (int)$status_row['updated_ymdhis']
            : 0;

        $candidates[] = [
            'operator_id' => $operator_id,
            'score' => $score,
            'load' => $status_row ? (int)$status_row['active_chat_count'] : 0,
            'last_assignment' => $last_assignment
        ];
    }

    if (!$candidates) {
        return null;
    }

    usort($candidates, function ($a, $b) {
        if ($a['score'] !== $b['score']) {
            return $b['score'] <=> $a['score'];
        }
        if ($a['load'] !== $b['load']) {
            return $a['load'] <=> $b['load'];
        }
        return $a['last_assignment'] <=> $b['last_assignment'];
    });

    return $candidates[0]['operator_id'];
}

function lupo_crafty_operator_expertise_snapshot($target_department_id = null, $target_channel_id = null) {
    $db = lupo_crafty_db();
    if (!$db) {
        return [];
    }

    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    $operators = $db->query("SELECT o.operator_id,
            o.actor_id,
            o.department_id,
            o.metadata_json,
            o.pono_score,
            o.pilau_score,
            o.kapakai_score,
            a.name AS actor_name
        FROM {$table_prefix}operators o
        LEFT JOIN {$table_prefix}actors a
          ON a.actor_id = o.actor_id
        WHERE o.is_active = 1")->fetchAll(PDO::FETCH_ASSOC);

    if (!$operators) {
        return [];
    }

    $actor_ids = array_values(array_filter(array_unique(array_column($operators, 'actor_id'))));
    $operator_ids = array_values(array_filter(array_unique(array_column($operators, 'operator_id'))));

    $actor_roles = [];
    if ($actor_ids) {
        $placeholders = implode(',', array_fill(0, count($actor_ids), '?'));
        $stmt = $db->prepare("SELECT actor_id, role_key
            FROM {$table_prefix}actor_roles
            WHERE actor_id IN ($placeholders)
              AND is_deleted = 0");
        $stmt->execute($actor_ids);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $actor_roles[$row['actor_id']][] = $row['role_key'];
        }
    }

    $actor_departments = [];
    if ($actor_ids) {
        $placeholders = implode(',', array_fill(0, count($actor_ids), '?'));
        $stmt = $db->prepare("SELECT actor_id, department_id
            FROM {$table_prefix}actor_departments
            WHERE actor_id IN ($placeholders)
              AND is_deleted = 0");
        $stmt->execute($actor_ids);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $actor_departments[$row['actor_id']][] = (int)$row['department_id'];
        }
    }

    $actor_channels = [];
    if ($actor_ids) {
        $placeholders = implode(',', array_fill(0, count($actor_ids), '?'));
        $stmt = $db->prepare("SELECT actor_id, channel_id
            FROM {$table_prefix}actor_channels
            WHERE actor_id IN ($placeholders)
              AND is_deleted = 0
              AND status = 'A'");
        $stmt->execute($actor_ids);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $actor_channels[$row['actor_id']][] = (int)$row['channel_id'];
        }
    }

    $actor_moods = [];
    if ($actor_ids) {
        $placeholders = implode(',', array_fill(0, count($actor_ids), '?'));
        $stmt = $db->prepare("SELECT actor_id, mood_r, mood_g, mood_b
            FROM {$table_prefix}actor_moods
            WHERE actor_id IN ($placeholders)");
        $stmt->execute($actor_ids);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $actor_moods[$row['actor_id']] = $row;
        }
    }

    $operator_status = [];
    if ($operator_ids) {
        $placeholders = implode(',', array_fill(0, count($operator_ids), '?'));
        $stmt = $db->prepare("SELECT operator_id, status, last_seen_ymdhis, active_chat_count, max_chat_capacity, updated_ymdhis
            FROM {$table_prefix}operator_status
            WHERE operator_id IN ($placeholders)");
        $stmt->execute($operator_ids);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $operator_status[$row['operator_id']] = $row;
        }
    }

    $department_ids = [];
    foreach ($operators as $operator) {
        if (!empty($operator['department_id'])) {
            $department_ids[] = (int)$operator['department_id'];
        }
        $actor_id = (int)$operator['actor_id'];
        if (!empty($actor_departments[$actor_id])) {
            $department_ids = array_merge($department_ids, $actor_departments[$actor_id]);
        }
    }
    $department_ids = array_values(array_unique(array_filter($department_ids)));
    $department_names = [];
    if ($department_ids) {
        $placeholders = implode(',', array_fill(0, count($department_ids), '?'));
        $stmt = $db->prepare("SELECT department_id, name
            FROM {$table_prefix}departments
            WHERE department_id IN ($placeholders)
              AND is_deleted = 0");
        $stmt->execute($department_ids);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $department_names[(int)$row['department_id']] = $row['name'];
        }
    }

    $channel_ids = [];
    foreach ($actor_channels as $channels) {
        $channel_ids = array_merge($channel_ids, $channels);
    }
    $channel_ids = array_values(array_unique(array_filter($channel_ids)));
    $channel_names = [];
    if ($channel_ids) {
        $placeholders = implode(',', array_fill(0, count($channel_ids), '?'));
        $stmt = $db->prepare("SELECT channel_id, channel_name
            FROM {$table_prefix}channels
            WHERE channel_id IN ($placeholders)
              AND is_deleted = 0
              AND status_flag = 1");
        $stmt->execute($channel_ids);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $channel_names[(int)$row['channel_id']] = $row['channel_name'];
        }
    }

    $snapshot = [];
    foreach ($operators as $operator) {
        $actor_id = (int)$operator['actor_id'];
        $operator_id = (int)$operator['operator_id'];

        $roles = isset($actor_roles[$actor_id]) ? $actor_roles[$actor_id] : [];
        $dept_memberships = isset($actor_departments[$actor_id]) ? $actor_departments[$actor_id] : [];
        $channel_memberships = isset($actor_channels[$actor_id]) ? $actor_channels[$actor_id] : [];

        if (!empty($operator['department_id']) && !in_array((int)$operator['department_id'], $dept_memberships, true)) {
            $dept_memberships[] = (int)$operator['department_id'];
        }

        $role_match = in_array('operator', $roles, true) || in_array('expert', $roles, true) ? 1 : 0;

        $mood_row = isset($actor_moods[$actor_id]) ? $actor_moods[$actor_id] : null;
        $emotional = lupo_crafty_emotional_stability($mood_row);

        $status_row = isset($operator_status[$operator_id]) ? $operator_status[$operator_id] : null;
        $availability = lupo_crafty_operator_availability($status_row);
        $load = lupo_crafty_operator_load_score($status_row);
        $performance = lupo_crafty_operator_performance($operator);

        $target_department = $target_department_id !== null ? (int)$target_department_id : (int)$operator['department_id'];
        $target_channel = $target_channel_id !== null ? (int)$target_channel_id : (isset($channel_memberships[0]) ? (int)$channel_memberships[0] : 0);

        $department_match = $target_department ? (in_array($target_department, $dept_memberships, true) ? 1 : 0) : 0;
        $channel_match = $target_channel ? (in_array($target_channel, $channel_memberships, true) ? 1 : 0) : 0;

        $score = ($department_match * 4)
            + ($channel_match * 4)
            + ($role_match * 3)
            + ($emotional * 2)
            + ($availability * 2)
            + ($load * 1)
            + ($performance * 1);

        $department_labels = [];
        foreach ($dept_memberships as $dept_id) {
            if (isset($department_names[$dept_id])) {
                $department_labels[] = $department_names[$dept_id];
            }
        }

        $channel_labels = [];
        foreach ($channel_memberships as $chan_id) {
            if (isset($channel_names[$chan_id])) {
                $channel_labels[] = $channel_names[$chan_id];
            }
        }

        $snapshot[] = [
            'operator_id' => $operator_id,
            'actor_id' => $actor_id,
            'operator_name' => $operator['actor_name'] ?: 'Operator #' . $operator_id,
            'department_names' => $department_labels,
            'channel_names' => $channel_labels,
            'emotional_stability' => $emotional,
            'availability_score' => $availability,
            'availability_label' => $status_row ? $status_row['status'] : 'offline',
            'load_score' => $load,
            'active_chat_count' => $status_row ? (int)$status_row['active_chat_count'] : 0,
            'performance_score' => $performance,
            'expertise_score' => $score,
            'role_match' => $role_match,
            'department_match' => $department_match,
            'channel_match' => $channel_match,
            'pono_score' => $operator['pono_score'],
            'pilau_score' => $operator['pilau_score'],
            'kapakai_score' => $operator['kapakai_score']
        ];
    }

    usort($snapshot, function ($a, $b) {
        return $b['expertise_score'] <=> $a['expertise_score'];
    });

    return $snapshot;
}
