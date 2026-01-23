#!/usr/bin/env php
<?php
/**
 * Debug Actor Mood CLI Command
 *
 * Provides command-line access to ActorMoodService and PackMoodCoherenceService
 * for debugging and testing the 2-actor RGB mood model.
 *
 * Commands:
 * - log-mood <actor_id> <r> <g> <b>          : Log a mood for an actor
 * - get-mood <actor_id>                      : Get latest mood for an actor
 * - get-history <actor_id> [limit]           : Get mood history for an actor
 * - show-coherence <actor_a_id> <actor_b_id> : Calculate coherence between two actors
 *
 * Usage:
 *   php bin/cli/debug-actor-mood.php log-mood 2 0 1 1
 *   php bin/cli/debug-actor-mood.php get-mood 2
 *   php bin/cli/debug-actor-mood.php get-history 2 10
 *   php bin/cli/debug-actor-mood.php show-coherence 1 2
 *
 * @package Lupopedia
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 * @author CURSOR
 */

// Ensure script is run from command line
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Bootstrap Lupopedia
require_once __DIR__ . '/../../lupopedia-config.php';
require_once LUPO_INCLUDES_DIR . '/class-pdo_db.php';
require_once __DIR__ . '/../../app/Services/ActorMoodService.php';
require_once __DIR__ . '/../../app/Services/PackMoodCoherenceService.php';

use App\Services\ActorMoodService;
use App\Services\PackMoodCoherenceService;

// Initialize services
$db = new PDO_DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_TYPE);
$moodService = new ActorMoodService($db);
$coherenceService = new PackMoodCoherenceService($moodService);

// Parse command line arguments
$command = $argv[1] ?? null;

if (!$command) {
    showUsage();
    exit(1);
}

// Execute command
try {
    switch ($command) {
        case 'log-mood':
            logMood($argv);
            break;

        case 'get-mood':
            getMood($argv);
            break;

        case 'get-history':
            getHistory($argv);
            break;

        case 'show-coherence':
            showCoherence($argv);
            break;

        case 'help':
        case '--help':
        case '-h':
            showUsage();
            break;

        default:
            echo "Unknown command: $command\n\n";
            showUsage();
            exit(1);
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// ============================================================================
// Command Implementations
// ============================================================================

/**
 * Log a mood for an actor
 *
 * Usage: log-mood <actor_id> <r> <g> <b>
 */
function logMood(array $argv): void
{
    global $moodService;

    if (count($argv) < 6) {
        echo "Usage: log-mood <actor_id> <r> <g> <b>\n";
        echo "  actor_id: Actor ID (positive integer)\n";
        echo "  r: Red axis (-1, 0, or 1)\n";
        echo "  g: Green axis (-1, 0, or 1)\n";
        echo "  b: Blue axis (-1, 0, or 1)\n";
        exit(1);
    }

    $actorId = (int) $argv[2];
    $r = (int) $argv[3];
    $g = (int) $argv[4];
    $b = (int) $argv[5];

    echo "Logging mood for actor $actorId...\n";
    echo "  R (Strife/Conflict): $r\n";
    echo "  G (Harmony/Cohesion): $g\n";
    echo "  B (Memory/Persistence): $b\n";

    $moodService->logMood($actorId, $r, $g, $b);

    echo "✅ Mood logged successfully\n";
    echo "  Timestamp: " . gmdate('Y-m-d H:i:s') . " UTC\n";
}

/**
 * Get latest mood for an actor
 *
 * Usage: get-mood <actor_id>
 */
function getMood(array $argv): void
{
    global $moodService;

    if (count($argv) < 3) {
        echo "Usage: get-mood <actor_id>\n";
        exit(1);
    }

    $actorId = (int) $argv[2];

    echo "Retrieving latest mood for actor $actorId...\n\n";

    $mood = $moodService->getLatestMood($actorId);

    if ($mood === null) {
        echo "❌ No mood records found for actor $actorId\n";
        exit(0);
    }

    echo "✅ Latest Mood:\n";
    echo "  Actor ID: {$mood['actor_id']}\n";
    echo "  R (Strife/Conflict): {$mood['mood_r']}\n";
    echo "  G (Harmony/Cohesion): {$mood['mood_g']}\n";
    echo "  B (Memory/Persistence): {$mood['mood_b']}\n";
    echo "  Timestamp: " . formatTimestamp($mood['timestamp_utc']) . "\n";
    echo "\n";
    echo "Interpretation: " . interpretMood($mood['mood_r'], $mood['mood_g'], $mood['mood_b']) . "\n";
}

/**
 * Get mood history for an actor
 *
 * Usage: get-history <actor_id> [limit]
 */
function getHistory(array $argv): void
{
    global $moodService;

    if (count($argv) < 3) {
        echo "Usage: get-history <actor_id> [limit]\n";
        echo "  limit: Optional, defaults to 10\n";
        exit(1);
    }

    $actorId = (int) $argv[2];
    $limit = isset($argv[3]) ? (int) $argv[3] : 10;

    echo "Retrieving last $limit moods for actor $actorId...\n\n";

    $history = $moodService->getMoodHistory($actorId, $limit);

    if (empty($history)) {
        echo "❌ No mood records found for actor $actorId\n";
        exit(0);
    }

    echo "✅ Mood History ($limit records):\n";
    echo str_repeat("=", 80) . "\n";

    foreach ($history as $i => $mood) {
        $num = $i + 1;
        echo "$num. R={$mood['mood_r']}, G={$mood['mood_g']}, B={$mood['mood_b']} @ " . formatTimestamp($mood['timestamp_utc']) . "\n";
    }

    echo str_repeat("=", 80) . "\n";
}

/**
 * Show coherence between two actors
 *
 * Usage: show-coherence <actor_a_id> <actor_b_id>
 */
function showCoherence(array $argv): void
{
    global $coherenceService;

    if (count($argv) < 4) {
        echo "Usage: show-coherence <actor_a_id> <actor_b_id>\n";
        exit(1);
    }

    $actorAId = (int) $argv[2];
    $actorBId = (int) $argv[3];

    echo "Computing coherence between actors $actorAId and $actorBId...\n\n";

    $coherence = $coherenceService->computeCoherence($actorAId, $actorBId);

    if ($coherence === null) {
        echo "❌ Cannot compute coherence: one or both actors have no mood records\n";
        exit(0);
    }

    // Display results
    echo "✅ Coherence Analysis:\n";
    echo str_repeat("=", 80) . "\n";

    echo "\nActor A (ID {$coherence['actor_a_id']}):\n";
    echo "  R (Strife): {$coherence['mood_a']['r']}\n";
    echo "  G (Harmony): {$coherence['mood_a']['g']}\n";
    echo "  B (Memory): {$coherence['mood_a']['b']}\n";

    echo "\nActor B (ID {$coherence['actor_b_id']}):\n";
    echo "  R (Strife): {$coherence['mood_b']['r']}\n";
    echo "  G (Harmony): {$coherence['mood_b']['g']}\n";
    echo "  B (Memory): {$coherence['mood_b']['b']}\n";

    echo "\nCoherence Metrics:\n";
    echo "  Manhattan Distance: {$coherence['distance']}\n";
    echo "  Coherence Score: {$coherence['coherence']}\n";
    echo "  Status: {$coherence['status']}\n";

    echo "\n";
    echo str_repeat("=", 80) . "\n";

    // Interpret status
    $status = $coherence['status'];
    $c = $coherence['coherence'];

    switch ($status) {
        case 'too_aligned':
            echo "\n⚠️  WARNING: Actors too aligned (c > 0.9)\n";
            echo "  Risk: Echo chamber, groupthink\n";
            echo "  Recommendation: Trigger compare-notes protocol\n";
            echo "  Action: Introduce diverse perspectives\n";
            break;

        case 'too_divergent':
            echo "\n⚠️  WARNING: Actors too divergent (c < 0.3)\n";
            echo "  Risk: Fragmentation, communication breakdown\n";
            echo "  Recommendation: Trigger compare-notes protocol\n";
            echo "  Action: Facilitate alignment discussion\n";
            break;

        case 'healthy':
            echo "\n✅ Healthy divergence detected (0.3 ≤ c ≤ 0.9)\n";
            echo "  Status: Optimal collaboration state\n";
            echo "  Action: No intervention needed\n";
            break;
    }

    echo "\n";

    // Check if compare-notes needed
    $needed = $coherenceService->compareNotesNeeded($actorAId, $actorBId);
    echo "Compare-Notes Protocol: " . ($needed ? "⚠️  REQUIRED" : "✅ Not needed") . "\n";
}

/**
 * Show usage information
 */
function showUsage(): void
{
    echo "\n";
    echo "Debug Actor Mood - CLI Tool\n";
    echo str_repeat("=", 80) . "\n";
    echo "\n";
    echo "USAGE:\n";
    echo "  php bin/cli/debug-actor-mood.php <command> [arguments]\n";
    echo "\n";
    echo "COMMANDS:\n";
    echo "\n";
    echo "  log-mood <actor_id> <r> <g> <b>\n";
    echo "    Log a mood for an actor\n";
    echo "    Arguments:\n";
    echo "      actor_id : Actor ID (positive integer)\n";
    echo "      r        : Red axis (Strife/Conflict) ∈ {-1, 0, 1}\n";
    echo "      g        : Green axis (Harmony/Cohesion) ∈ {-1, 0, 1}\n";
    echo "      b        : Blue axis (Memory/Persistence) ∈ {-1, 0, 1}\n";
    echo "\n";
    echo "  get-mood <actor_id>\n";
    echo "    Get latest mood for an actor\n";
    echo "    Arguments:\n";
    echo "      actor_id : Actor ID\n";
    echo "\n";
    echo "  get-history <actor_id> [limit]\n";
    echo "    Get mood history for an actor\n";
    echo "    Arguments:\n";
    echo "      actor_id : Actor ID\n";
    echo "      limit    : Optional, number of records (default: 10)\n";
    echo "\n";
    echo "  show-coherence <actor_a_id> <actor_b_id>\n";
    echo "    Calculate coherence between two actors\n";
    echo "    Arguments:\n";
    echo "      actor_a_id : First actor ID\n";
    echo "      actor_b_id : Second actor ID\n";
    echo "\n";
    echo "  help, --help, -h\n";
    echo "    Show this help message\n";
    echo "\n";
    echo "EXAMPLES:\n";
    echo "\n";
    echo "  # Log a mood (neutral strife, high harmony, deep memory)\n";
    echo "  php bin/cli/debug-actor-mood.php log-mood 2 0 1 1\n";
    echo "\n";
    echo "  # Get latest mood for actor 2 (WOLFIE)\n";
    echo "  php bin/cli/debug-actor-mood.php get-mood 2\n";
    echo "\n";
    echo "  # Get last 20 moods for actor 1 (CAPTAIN)\n";
    echo "  php bin/cli/debug-actor-mood.php get-history 1 20\n";
    echo "\n";
    echo "  # Show coherence between CAPTAIN and WOLFIE\n";
    echo "  php bin/cli/debug-actor-mood.php show-coherence 1 2\n";
    echo "\n";
    echo "RGB AXIS SEMANTICS:\n";
    echo "\n";
    echo "  R (Red)   - Strife/Conflict/Intensity\n";
    echo "    -1 : Calm, low conflict\n";
    echo "     0 : Neutral\n";
    echo "     1 : High strife, tension\n";
    echo "\n";
    echo "  G (Green) - Harmony/Cohesion/Warmth\n";
    echo "    -1 : Low harmony, tension\n";
    echo "     0 : Neutral\n";
    echo "     1 : High harmony, supportive\n";
    echo "\n";
    echo "  B (Blue)  - Memory/Persistence/Reflection\n";
    echo "    -1 : Shallow memory, present-focused\n";
    echo "     0 : Neutral\n";
    echo "     1 : Deep memory, reflective\n";
    echo "\n";
    echo "COHERENCE THRESHOLDS:\n";
    echo "\n";
    echo "  c > 0.9       : too_aligned (echo chamber risk)\n";
    echo "  0.3 ≤ c ≤ 0.9 : healthy (optimal collaboration)\n";
    echo "  c < 0.3       : too_divergent (fragmentation risk)\n";
    echo "\n";
    echo str_repeat("=", 80) . "\n";
    echo "\n";
}

// ============================================================================
// Helper Functions
// ============================================================================

/**
 * Format BIGINT UTC timestamp for display
 *
 * @param int $timestamp UTC timestamp in YYYYMMDDHHIISS format
 * @return string Formatted timestamp
 */
function formatTimestamp(int $timestamp): string
{
    $str = (string) $timestamp;

    if (strlen($str) !== 14) {
        return "Invalid timestamp";
    }

    $year = substr($str, 0, 4);
    $month = substr($str, 4, 2);
    $day = substr($str, 6, 2);
    $hour = substr($str, 8, 2);
    $minute = substr($str, 10, 2);
    $second = substr($str, 12, 2);

    return "$year-$month-$day $hour:$minute:$second UTC";
}

/**
 * Interpret mood vector as human-readable description
 *
 * @param int $r Red axis value
 * @param int $g Green axis value
 * @param int $b Blue axis value
 * @return string Mood interpretation
 */
function interpretMood(int $r, int $g, int $b): string
{
    $interpretations = [
        // Neutral
        '0,0,0' => 'Neutral/balanced state',

        // Pure states
        '1,0,0' => 'High strife/conflict',
        '-1,0,0' => 'Calm (low strife)',
        '0,1,0' => 'Harmonious',
        '0,-1,0' => 'Low harmony/tension',
        '0,0,1' => 'Reflective/deep memory',
        '0,0,-1' => 'Present-focused/shallow memory',

        // Two-axis combinations
        '1,1,0' => 'Intense but harmonious',
        '1,-1,0' => 'Intense and tense',
        '1,0,1' => 'Intense and reflective',
        '1,0,-1' => 'Intense and present-focused',
        '-1,1,0' => 'Calm and harmonious',
        '-1,-1,0' => 'Calm but tense',
        '-1,0,1' => 'Calm and reflective',
        '-1,0,-1' => 'Calm and present-focused',
        '0,1,1' => 'Harmonious and reflective',
        '0,1,-1' => 'Harmonious and present-focused',
        '0,-1,1' => 'Tense but reflective',
        '0,-1,-1' => 'Tense and present-focused',

        // Three-axis combinations
        '1,1,1' => 'Intensely harmonious with deep memory',
        '1,1,-1' => 'Intensely harmonious but present-focused',
        '1,-1,1' => 'Intense, tense, and reflective',
        '1,-1,-1' => 'Intense, tense, and present-focused',
        '-1,1,1' => 'Calm, harmonious, and reflective',
        '-1,1,-1' => 'Calm, harmonious, and present-focused',
        '-1,-1,1' => 'Calm, tense, and reflective',
        '-1,-1,-1' => 'Calm, tense, and present-focused',
    ];

    $key = "$r,$g,$b";
    return $interpretations[$key] ?? "Custom state: R=$r, G=$g, B=$b";
}
