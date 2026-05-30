<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "scripts/sync_actors_to_db.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175911"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
lupopedia.headers:
  when_updated: "20260324175617"
  file_path_from_root: "scripts/sync_actors_to_db.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175617"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
 * Actor Directory to Database Synchronization Script
 * PHP 5.3+ Compatible - Lupopedia v4.0.48
 * 
 * Syncs actor directory data with database tables for Identity Capsule system
 * Reads from actors/<id>/ directory and updates database accordingly
 */

// Bootstrap Lupopedia (ensure we have database connection)
require_once(dirname(__DIR__) . '/includes/bootstrap.php');

/**
 * Sync Actor Directory to Database
 * 
 * @param int $actor_id Actor ID to sync
 * @return array Sync results
 */
function sync_actor_to_db($actor_id)
{
    $results = array(
        'success' => false,
        'actor_id' => $actor_id,
        'synced_files' => array(),
        'errors' => array(),
        'timestamp' => gmdate('YmdHis')
    );

    try {
        // Get database connection
        $db = DatabaseFactory::getConnection();

        // Validate actor directory exists
        $lupo_actors_dir = defined('LUPO_ACTORS_DIR') ? LUPO_ACTORS_DIR : 'actors';
        $actor_dir = LUPOPEDIA_PATH . '/' . $lupo_actors_dir . '/' . $actor_id;
        if (!is_dir($actor_dir)) {
            throw new Exception("Actor directory not found: {$actor_dir}");
        }

        // Sync WHO.json to lupo_actors.metadata_json
        $who_file = $actor_dir . '/WHO.json';
        if (file_exists($who_file)) {
            $who_data = json_decode(file_get_contents($who_file), true);
            if ($who_data === null) {
                throw new Exception("Invalid JSON in WHO.json for actor {$actor_id}");
            }

            // Update actor record
            $update_sql = "UPDATE lupo_actors SET 
                metadata_json = :who_json,
                who_json_sync_status = 'synced',
                last_sync_ymdhis = :sync_time,
                updated_ymdhis = :sync_time
                WHERE actor_id = :actor_id";

            $stmt = $db->prepare($update_sql);
            $stmt->execute(array(
                ':who_json' => json_encode($who_data),
                ':sync_time' => $results['timestamp'],
                ':actor_id' => $actor_id
            ));

            $results['synced_files'][] = 'WHO.json';
        }

        // Sync resume.json to lupo_actor_history
        $resume_file = $actor_dir . '/history/resume.json';
        if (file_exists($resume_file)) {
            $resume_data = json_decode(file_get_contents($resume_file), true);
            if ($resume_data === null) {
                throw new Exception("Invalid JSON in resume.json for actor {$actor_id}");
            }

            // Process best_work entries
            if (isset($resume_data['best_work']) && is_array($resume_data['best_work'])) {
                foreach ($resume_data['best_work'] as $achievement) {
                    // Generate history_id (simple increment)
                    $history_id = $actor_id * 1000 + count($results['synced_files']);

                    $insert_sql = "INSERT INTO lupo_actor_history (
                        history_id, actor_id, achievement_id, title, description, impact,
                        date_ymdhis, channel_id, tags, metrics, created_ymdhis, updated_ymdhis
                    ) VALUES (
                        :history_id, :actor_id, :achievement_id, :title, :description, :impact,
                        :date_ymdhis, :channel_id, :tags, :metrics, :created_ymdhis, :updated_ymdhis
                    )";

                    $stmt = $db->prepare($insert_sql);
                    $stmt->execute(array(
                        ':history_id' => $history_id,
                        ':actor_id' => $actor_id,
                        ':achievement_id' => isset($achievement['achievement_id']) ? $achievement['achievement_id'] : null,
                        ':title' => isset($achievement['title']) ? $achievement['title'] : '',
                        ':description' => isset($achievement['description']) ? $achievement['description'] : null,
                        ':impact' => isset($achievement['impact']) ? $achievement['impact'] : null,
                        ':date_ymdhis' => isset($achievement['date_utc']) ? str_replace('-', '', $achievement['date_utc']) : $results['timestamp'],
                        ':channel_id' => isset($achievement['channel']) ? $achievement['channel'] : null,
                        ':tags' => isset($achievement['tags']) ? json_encode($achievement['tags']) : null,
                        ':metrics' => isset($achievement['metrics']) ? json_encode($achievement['metrics']) : null,
                        ':created_ymdhis' => $results['timestamp'],
                        ':updated_ymdhis' => $results['timestamp']
                    ));
                }
                $results['synced_files'][] = 'history/resume.json';
            }
        }

        // Sync capabilities to lupo_capability_usage
        $who_file = $actor_dir . '/WHO.json';
        if (file_exists($who_file)) {
            $who_data = json_decode(file_get_contents($who_file), true);
            if (isset($who_data['whoami']['capabilities']) && is_array($who_data['whoami']['capabilities'])) {
                foreach ($who_data['whoami']['capabilities'] as $capability) {
                    // Generate usage_id
                    $usage_id = $actor_id * 1000 + array_search($capability, $who_data['whoami']['capabilities']);

                    $insert_sql = "INSERT INTO lupo_capability_usage (
                        usage_id, actor_id, capability, usage_count, success_rate,
                        avg_response_time_ms, last_used_ymdhis, created_ymdhis, updated_ymdhis
                    ) VALUES (
                        :usage_id, :actor_id, :capability, :usage_count, :success_rate,
                        :avg_response_time_ms, :last_used_ymdhis, :created_ymdhis, :updated_ymdhis
                    )";

                    $stmt = $db->prepare($insert_sql);
                    $stmt->execute(array(
                        ':usage_id' => $usage_id,
                        ':actor_id' => $actor_id,
                        ':capability' => $capability,
                        ':usage_count' => 0,
                        ':success_rate' => 1.0,
                        ':avg_response_time_ms' => 0,
                        ':last_used_ymdhis' => $results['timestamp'],
                        ':created_ymdhis' => $results['timestamp'],
                        ':updated_ymdhis' => $results['timestamp']
                    ));
                }
                $results['synced_files'][] = 'capabilities (from WHO.json)';
            }
        }

        $results['success'] = true;

    } catch (Exception $e) {
        $results['errors'][] = $e->getMessage();
    }

    return $results;
}

/**
 * Sync All Actors
 * 
 * @return array Overall sync results
 */
function sync_all_actors()
{
    $results = array(
        'success' => true,
        'total_actors' => 0,
        'synced_actors' => 0,
        'failed_actors' => 0,
        'actor_results' => array(),
        'timestamp' => gmdate('YmdHis')
    );

    // Get all actor directories
    $lupo_actors_dir = defined('LUPO_ACTORS_DIR') ? LUPO_ACTORS_DIR : 'actors';
    $actors_dir = LUPOPEDIA_PATH . '/' . $lupo_actors_dir;
    $dirs = scandir($actors_dir);

    foreach ($dirs as $dir) {
        if ($dir === '.' || $dir === '..') {
            continue;
        }

        $actor_path = $actors_dir . '/' . $dir;
        if (is_dir($actor_path) && is_numeric($dir)) {
            $results['total_actors']++;
            $actor_result = sync_actor_to_db(intval($dir));
            $results['actor_results'][] = $actor_result;

            if ($actor_result['success']) {
                $results['synced_actors']++;
            } else {
                $results['failed_actors']++;
                $results['success'] = false;
            }
        }
    }

    return $results;
}

/**
 * Main execution
 */
if (php_sapi_name() === 'cli') {
    // Command line usage
    $options = getopt('a:h', array('actor:', 'help'));

    if (isset($options['h']) || isset($options['help'])) {
        echo "Actor Directory to Database Sync Script\n";
        echo "Usage: php sync_actors_to_db.php [-a <actor_id>] [-h]\n";
        echo "  -a, --actor    Sync specific actor ID\n";
        echo "  -h, --help     Show this help\n";
        echo "\nIf no actor ID specified, syncs all actors.\n";
        exit(0);
    }

    if (isset($options['a']) || isset($options['actor'])) {
        $actor_id = isset($options['a']) ? intval($options['a']) : intval($options['actor']);
        echo "Syncing actor {$actor_id}...\n";
        $result = sync_actor_to_db($actor_id);

        if ($result['success']) {
            echo "✅ Actor {$actor_id} synced successfully\n";
            echo "   Synced files: " . implode(', ', $result['synced_files']) . "\n";
        } else {
            echo "❌ Actor {$actor_id} sync failed\n";
            echo "   Errors: " . implode(', ', $result['errors']) . "\n";
            exit(1);
        }
    } else {
        echo "Syncing all actors...\n";
        $result = sync_all_actors();

        echo "Total actors: {$result['total_actors']}\n";
        echo "Synced: {$result['synced_actors']}\n";
        echo "Failed: {$result['failed_actors']}\n";

        if ($result['failed_actors'] > 0) {
            echo "\nFailed actors:\n";
            foreach ($result['actor_results'] as $actor_result) {
                if (!$actor_result['success']) {
                    echo "  Actor {$actor_result['actor_id']}: " . implode(', ', $actor_result['errors']) . "\n";
                }
            }
            exit(1);
        } else {
            echo "✅ All actors synced successfully\n";
        }
    }
} else {
    // Web usage - return JSON
    header('Content-Type: application/json');

    if (isset($_GET['actor_id'])) {
        $actor_id = intval($_GET['actor_id']);
        $result = sync_actor_to_db($actor_id);
    } else {
        $result = sync_all_actors();
    }

    echo json_encode($result, JSON_PRETTY_PRINT);
}
?>
