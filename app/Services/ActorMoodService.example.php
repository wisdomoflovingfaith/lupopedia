<?php
/**
 * ActorMoodService Usage Examples
 *
 * Demonstrates how to use the ActorMoodService for logging and retrieving
 * actor moods using the 2-actor RGB mood model.
 *
 * @package Lupopedia
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */

// Load configuration and dependencies
require_once __DIR__ . '/../../lupopedia-config.php';
require_once LUPO_INCLUDES_DIR . '/class-pdo_db.php';
require_once __DIR__ . '/ActorMoodService.php';

use App\Services\ActorMoodService;

// Initialize database connection
$db = new PDO_DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_TYPE);

// Create service instance
$moodService = new ActorMoodService($db);

// -----------------------------------------------------------------------------
// Example 1: Log a mood with discrete RGB values
// -----------------------------------------------------------------------------
echo "Example 1: Logging actor mood\n";
echo "------------------------------\n";

$actorId = 2; // WOLFIE
$moodR = 0;   // No strife/conflict
$moodG = 0;   // Neutral harmony
$moodB = 1;   // High memory/persistence (reflective state)

try {
    $moodService->logMood($actorId, $moodR, $moodG, $moodB);
    echo "✅ Logged mood for actor $actorId: R=$moodR, G=$moodG, B=$moodB\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// -----------------------------------------------------------------------------
// Example 2: Log mood with specific timestamp
// -----------------------------------------------------------------------------
echo "Example 2: Logging mood with custom timestamp\n";
echo "----------------------------------------------\n";

$actorId = 1; // CAPTAIN
$moodR = -1;  // Negative strife (calm)
$moodG = 1;   // High harmony
$moodB = 0;   // Neutral memory depth
$timestamp = 20260120150000; // January 20, 2026 at 15:00:00 UTC

try {
    $moodService->logMood($actorId, $moodR, $moodG, $moodB, $timestamp);
    echo "✅ Logged mood for actor $actorId at timestamp $timestamp\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// -----------------------------------------------------------------------------
// Example 3: Get latest mood for an actor
// -----------------------------------------------------------------------------
echo "Example 3: Retrieving latest mood\n";
echo "----------------------------------\n";

$actorId = 2; // WOLFIE

try {
    $latestMood = $moodService->getLatestMood($actorId);

    if ($latestMood === null) {
        echo "No mood records found for actor $actorId\n";
    } else {
        echo "Latest mood for actor $actorId:\n";
        echo "  R (Strife): {$latestMood['mood_r']}\n";
        echo "  G (Harmony): {$latestMood['mood_g']}\n";
        echo "  B (Memory): {$latestMood['mood_b']}\n";
        echo "  Timestamp: {$latestMood['timestamp_utc']}\n";

        // Interpret the mood
        $interpretation = interpretMood($latestMood['mood_r'], $latestMood['mood_g'], $latestMood['mood_b']);
        echo "  Interpretation: $interpretation\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// -----------------------------------------------------------------------------
// Example 4: Get mood history
// -----------------------------------------------------------------------------
echo "Example 4: Retrieving mood history\n";
echo "-----------------------------------\n";

$actorId = 2; // WOLFIE
$limit = 10;

try {
    $history = $moodService->getMoodHistory($actorId, $limit);

    if (empty($history)) {
        echo "No mood history found for actor $actorId\n";
    } else {
        echo "Last $limit moods for actor $actorId:\n";
        foreach ($history as $i => $mood) {
            $num = $i + 1;
            echo "  $num. R={$mood['mood_r']}, G={$mood['mood_g']}, B={$mood['mood_b']} @ {$mood['timestamp_utc']}\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// -----------------------------------------------------------------------------
// Example 5: Validation - Invalid mood values
// -----------------------------------------------------------------------------
echo "Example 5: Validation of mood values\n";
echo "-------------------------------------\n";

$actorId = 2;
$invalidMoodR = 2; // Invalid: must be -1, 0, or 1

try {
    $moodService->logMood($actorId, $invalidMoodR, 0, 0);
    echo "❌ This should not succeed\n";
} catch (Exception $e) {
    echo "✅ Validation works: " . $e->getMessage() . "\n";
}

echo "\n";

// -----------------------------------------------------------------------------
// Example 6: Compare two actor moods (coherence calculation)
// -----------------------------------------------------------------------------
echo "Example 6: Calculate coherence between two actors\n";
echo "--------------------------------------------------\n";

$actor1Id = 1; // CAPTAIN
$actor2Id = 2; // WOLFIE

try {
    $mood1 = $moodService->getLatestMood($actor1Id);
    $mood2 = $moodService->getLatestMood($actor2Id);

    if ($mood1 && $mood2) {
        $coherence = calculateCoherence($mood1, $mood2);
        echo "Actor $actor1Id mood: R={$mood1['mood_r']}, G={$mood1['mood_g']}, B={$mood1['mood_b']}\n";
        echo "Actor $actor2Id mood: R={$mood2['mood_r']}, G={$mood2['mood_g']}, B={$mood2['mood_b']}\n";
        echo "Coherence: " . number_format($coherence, 2) . "\n";

        if ($coherence < 0.3) {
            echo "⚠️  Low coherence detected - compare-notes protocol recommended\n";
        } elseif ($coherence > 0.9) {
            echo "⚠️  High coherence detected - echo chamber risk\n";
        } else {
            echo "✅ Healthy divergence\n";
        }
    } else {
        echo "Missing mood data for one or both actors\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// -----------------------------------------------------------------------------
// Helper Functions
// -----------------------------------------------------------------------------

/**
 * Interpret mood vector as human-readable description
 */
function interpretMood(int $r, int $g, int $b): string
{
    $interpretations = [
        // Pure states
        '1,0,0' => 'High strife/conflict, neutral harmony, shallow memory',
        '0,1,0' => 'Neutral strife, high harmony/cohesion, shallow memory',
        '0,0,1' => 'Neutral strife, neutral harmony, deep memory/reflection',
        '-1,0,0' => 'Calm (negative strife), neutral harmony, shallow memory',
        '0,-1,0' => 'Neutral strife, low harmony/tension, shallow memory',
        '0,0,-1' => 'Neutral strife, neutral harmony, low memory depth',

        // Combinations
        '0,0,0' => 'Neutral/balanced state',
        '1,1,0' => 'High energy with harmony',
        '-1,1,0' => 'Calm and harmonious',
        '0,1,1' => 'Harmonious and reflective',
        '1,0,1' => 'Intense and reflective',
        '-1,-1,0' => 'Calm but tense',
        '0,-1,1' => 'Tense but reflective',
    ];

    $key = "$r,$g,$b";
    return $interpretations[$key] ?? "Custom state: R=$r, G=$g, B=$b";
}

/**
 * Calculate coherence between two actor moods
 *
 * Returns value from 0.0 (completely different) to 1.0 (identical)
 */
function calculateCoherence(array $mood1, array $mood2): float
{
    // Calculate Euclidean distance between mood vectors
    $dr = $mood1['mood_r'] - $mood2['mood_r'];
    $dg = $mood1['mood_g'] - $mood2['mood_g'];
    $db = $mood1['mood_b'] - $mood2['mood_b'];

    $distance = sqrt($dr * $dr + $dg * $dg + $db * $db);

    // Maximum possible distance in discrete {-1,0,1} space is sqrt(12) ≈ 3.464
    // Normalize to 0.0-1.0 range, then invert (1.0 = identical, 0.0 = opposite)
    $maxDistance = sqrt(12);
    $coherence = 1.0 - ($distance / $maxDistance);

    return max(0.0, min(1.0, $coherence));
}
