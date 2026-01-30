<?php
/**
 * wolfie.header.identity: identity-helpers
 * wolfie.header.placement: /lupo-includes/functions/identity-helpers.php
 * wolfie.header.version: 4.2.0
 * wolfie.header.dialog:
 *   speaker: JETBRAINS
 *   target: @everyone
 *   message: "Added actor identity utilities for anonymous allocation, jsrn assignment, and merge behavior."
 *   mood: "00FF00"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. identity-helpers.php cannot be called directly.");
}

/**
 * Allocate the next available anonymous actor_id in [1000, 9999].
 *
 * @param PDO $db Database connection.
 * @return int|null Allocated actor_id or null if exhausted.
 */
function allocateAnonymousActorId(PDO $db): ?int {
    $sql = "SELECT actor_id
            FROM lupo_actors
            WHERE actor_id BETWEEN 1000 AND 9999
            ORDER BY actor_id";

    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    $expected = 1000;
    foreach ($rows as $actor_id) {
        $actor_id = (int)$actor_id;
        if ($actor_id > $expected) {
            return $expected;
        }
        if ($actor_id === $expected) {
            $expected++;
        }
    }

    if ($expected <= 9999) {
        return $expected;
    }

    // TODO: Decide whether to recycle inactive anonymous actors or return null.
    return null;
}

/**
 * Get or allocate a JSRN for the given actor.
 *
 * @param PDO $db Database connection.
 * @param int $actorId Actor ID to assign a jsrn to.
 * @return int Assigned jsrn.
 */
function getOrAllocateJsrnForActor(PDO $db, int $actorId): int {
    $sql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(metadata_json, '$.jsrn')) AS jsrn
            FROM lupo_actors
            WHERE actor_id = :actor_id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':actor_id' => $actorId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['jsrn'] !== null) {
        return (int)$row['jsrn'];
    }

    $sql = "SELECT DISTINCT CAST(JSON_UNQUOTE(JSON_EXTRACT(metadata_json, '$.jsrn')) AS UNSIGNED) AS jsrn
            FROM lupo_actors
            WHERE JSON_EXTRACT(metadata_json, '$.jsrn') IS NOT NULL
            ORDER BY jsrn";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    $expected = 1;
    foreach ($rows as $jsrn) {
        $jsrn = (int)$jsrn;
        if ($jsrn > $expected) {
            break;
        }
        if ($jsrn === $expected) {
            $expected++;
        }
    }

    $assignSql = "UPDATE lupo_actors
                  SET metadata_json = JSON_SET(COALESCE(metadata_json, JSON_OBJECT()), '$.jsrn', :jsrn)
                  WHERE actor_id = :actor_id";
    $assignStmt = $db->prepare($assignSql);
    $assignStmt->execute([
        ':jsrn' => $expected,
        ':actor_id' => $actorId,
    ]);

    return $expected;
}

/**
 * Merge an anonymous actor into a real actor.
 *
 * @param PDO $db Database connection.
 * @param int $tempActorId Anonymous actor_id (1000-9999).
 * @param int $realActorId Real actor_id (>= 10000).
 * @return void
 */
function mergeAnonymousActorIntoRealActor(PDO $db, int $tempActorId, int $realActorId): void {
    $db->beginTransaction();

    try {
        $updateTables = [
            'lupo_sessions' => 'actor_id',
            'lupo_actor_events' => 'actor_id',
            'lupo_session_events' => 'actor_id',
            'lupo_tab_events' => 'actor_id',
            'lupo_content_events' => 'actor_id',
            'lupo_world_events' => 'actor_id',
            'lupo_dialog_messages' => 'from_actor_id',
        ];

        foreach ($updateTables as $table => $column) {
            $sql = "UPDATE {$table}
                    SET {$column} = :real_actor_id
                    WHERE {$column} = :temp_actor_id";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':real_actor_id' => $realActorId,
                ':temp_actor_id' => $tempActorId,
            ]);
        }

        // TODO: Add additional actor_id-bearing tables as schema evolves.
        // TODO: Add update for to_actor_id when applicable (dialog routing/agent tables).
        // TODO: Add merge logic for actor-channel relations if required by future doctrine.

        $tempMeta = [];
        $realMeta = [];

        $metaSql = "SELECT metadata_json
                    FROM lupo_actors
                    WHERE actor_id = :actor_id";
        $metaStmt = $db->prepare($metaSql);
        $metaStmt->execute([':actor_id' => $tempActorId]);
        $tempRow = $metaStmt->fetch(PDO::FETCH_ASSOC);
        if ($tempRow) {
            $decoded = json_decode($tempRow['metadata_json'] ?? '{}', true);
            $tempMeta = is_array($decoded) ? $decoded : [];
        }

        $metaStmt->execute([':actor_id' => $realActorId]);
        $realRow = $metaStmt->fetch(PDO::FETCH_ASSOC);
        if ($realRow) {
            $decoded = json_decode($realRow['metadata_json'] ?? '{}', true);
            $realMeta = is_array($decoded) ? $decoded : [];
        }

        $mergedMeta = array_merge($tempMeta, $realMeta);
        $updateReal = "UPDATE lupo_actors
                       SET metadata_json = :metadata_json
                       WHERE actor_id = :real_actor_id";
        $updateRealStmt = $db->prepare($updateReal);
        $updateRealStmt->execute([
            ':metadata_json' => json_encode($mergedMeta),
            ':real_actor_id' => $realActorId,
        ]);

        $updateTemp = "UPDATE lupo_actors
                       SET metadata_json = JSON_SET(COALESCE(metadata_json, JSON_OBJECT()), '$.merged_into', :real_actor_id),
                           is_deleted = 1
                       WHERE actor_id = :temp_actor_id";
        $updateTempStmt = $db->prepare($updateTemp);
        $updateTempStmt->execute([
            ':real_actor_id' => $realActorId,
            ':temp_actor_id' => $tempActorId,
        ]);

        // TODO: GC cleanup for merged anonymous actors when retention policy is defined.

        $db->commit();
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}

?>
