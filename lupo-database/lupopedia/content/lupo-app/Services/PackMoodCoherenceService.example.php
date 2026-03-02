<?php
/**
 * PackMoodCoherenceService Usage Examples
 *
 * Demonstrates how to use PackMoodCoherenceService for computing coherence
 * between two actors and triggering compare-notes protocols.
 *
 * @package Lupopedia
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */

// Load configuration and dependencies
require_once __DIR__ . '/../../lupopedia-config.php';
require_once LUPO_INCLUDES_DIR . '/class-pdo_db.php';
require_once __DIR__ . '/ActorMoodService.php';
require_once __DIR__ . '/PackMoodCoherenceService.php';

use App\Services\ActorMoodService;
use App\Services\PackMoodCoherenceService;

// Initialize services
$db = new PDO_DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_TYPE);
$moodService = new ActorMoodService($db);
$coherenceService = new PackMoodCoherenceService($moodService);

echo "PackMoodCoherenceService Examples\n";
echo "==================================\n\n";

// -----------------------------------------------------------------------------
// Example 1: Basic coherence calculation
// -----------------------------------------------------------------------------
echo "Example 1: Basic Coherence Calculation\n";
echo "---------------------------------------\n";

// First, log some moods for testing
$moodService->logMood(1, -1, 1, 0);  // CAPTAIN: calm, harmonious
$moodService->logMood(2, 0, 1, 1);   // WOLFIE: neutral, harmonious, reflective

try {
    $coherence = $coherenceService->computeCoherence(1, 2);

    if ($coherence === null) {
        echo "One or both actors have no mood records\n";
    } else {
        echo "Actor A (ID {$coherence['actor_a_id']}):\n";
        echo "  Mood: R={$coherence['mood_a']['r']}, G={$coherence['mood_a']['g']}, B={$coherence['mood_a']['b']}\n";

        echo "Actor B (ID {$coherence['actor_b_id']}):\n";
        echo "  Mood: R={$coherence['mood_b']['r']}, G={$coherence['mood_b']['g']}, B={$coherence['mood_b']['b']}\n";

        echo "\nCoherence Metrics:\n";
        echo "  Manhattan Distance: {$coherence['distance']}\n";
        echo "  Coherence Score: {$coherence['coherence']}\n";
        echo "  Status: {$coherence['status']}\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// -----------------------------------------------------------------------------
// Example 2: Check if compare-notes is needed
// -----------------------------------------------------------------------------
echo "Example 2: Compare-Notes Protocol Detection\n";
echo "--------------------------------------------\n";

try {
    $needed = $coherenceService->compareNotesNeeded(1, 2);

    if ($needed) {
        echo "⚠️  Compare-notes protocol SHOULD be triggered\n";
        echo "   (Coherence outside healthy range)\n";
    } else {
        echo "✅ Compare-notes protocol NOT needed\n";
        echo "   (Coherence within healthy range)\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// -----------------------------------------------------------------------------
// Example 3: High coherence scenario (echo chamber risk)
// -----------------------------------------------------------------------------
echo "Example 3: High Coherence (Echo Chamber Risk)\n";
echo "----------------------------------------------\n";

// Log identical moods
$moodService->logMood(1, 1, 0, -1);  // CAPTAIN
$moodService->logMood(2, 1, 0, -1);  // WOLFIE (identical)

try {
    $coherence = $coherenceService->computeCoherence(1, 2);

    if ($coherence) {
        echo "Coherence: {$coherence['coherence']}\n";
        echo "Status: {$coherence['status']}\n";

        if ($coherence['status'] === 'too_aligned') {
            echo "⚠️  WARNING: Actors too aligned (c > 0.9)\n";
            echo "   Risk: Echo chamber, groupthink\n";
            echo "   Action: Trigger compare-notes protocol\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// -----------------------------------------------------------------------------
// Example 4: Low coherence scenario (fragmentation risk)
// -----------------------------------------------------------------------------
echo "Example 4: Low Coherence (Fragmentation Risk)\n";
echo "----------------------------------------------\n";

// Log opposite moods
$moodService->logMood(1, 1, -1, 1);   // CAPTAIN: high strife, low harmony, deep memory
$moodService->logMood(2, -1, 1, -1);  // WOLFIE: calm, high harmony, shallow memory (opposite)

try {
    $coherence = $coherenceService->computeCoherence(1, 2);

    if ($coherence) {
        echo "Coherence: {$coherence['coherence']}\n";
        echo "Status: {$coherence['status']}\n";

        if ($coherence['status'] === 'too_divergent') {
            echo "⚠️  WARNING: Actors too divergent (c < 0.3)\n";
            echo "   Risk: Fragmentation, communication breakdown\n";
            echo "   Action: Trigger compare-notes protocol\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// -----------------------------------------------------------------------------
// Example 5: Healthy divergence scenario
// -----------------------------------------------------------------------------
echo "Example 5: Healthy Divergence\n";
echo "------------------------------\n";

// Log moderately different moods
$moodService->logMood(1, 0, 1, 0);   // CAPTAIN: neutral, harmonious
$moodService->logMood(2, 0, 0, 1);   // WOLFIE: neutral, neutral, reflective

try {
    $coherence = $coherenceService->computeCoherence(1, 2);

    if ($coherence) {
        echo "Coherence: {$coherence['coherence']}\n";
        echo "Status: {$coherence['status']}\n";

        if ($coherence['status'] === 'healthy') {
            echo "✅ Healthy divergence detected (0.3 ≤ c ≤ 0.9)\n";
            echo "   No action needed - productive collaboration state\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// -----------------------------------------------------------------------------
// Example 6: Get threshold configuration
// -----------------------------------------------------------------------------
echo "Example 6: Threshold Configuration\n";
echo "-----------------------------------\n";

$thresholds = $coherenceService->getThresholds();

echo "Coherence Thresholds:\n";
echo "  High (Echo Chamber): c > {$thresholds['high']}\n";
echo "  Low (Fragmentation): c < {$thresholds['low']}\n";
echo "  Healthy Range: {$thresholds['healthy_range']['min']} ≤ c ≤ {$thresholds['healthy_range']['max']}\n";

echo "\n";

// -----------------------------------------------------------------------------
// Example 7: Multi-actor coherence monitoring
// -----------------------------------------------------------------------------
echo "Example 7: Multi-Actor Coherence Monitoring\n";
echo "--------------------------------------------\n";

// Define actor pairs to monitor
$actorPairs = [
    [1, 2],  // CAPTAIN & WOLFIE
    [1, 3],  // CAPTAIN & ROSE
    [2, 3],  // WOLFIE & ROSE
];

foreach ($actorPairs as $pair) {
    [$actorA, $actorB] = $pair;

    try {
        $coherence = $coherenceService->computeCoherence($actorA, $actorB);

        if ($coherence) {
            echo "Actors {$actorA} & {$actorB}: c={$coherence['coherence']} ({$coherence['status']})\n";
        } else {
            echo "Actors {$actorA} & {$actorB}: No mood data\n";
        }
    } catch (Exception $e) {
        echo "Actors {$actorA} & {$actorB}: Error - {$e->getMessage()}\n";
    }
}

echo "\n";

// -----------------------------------------------------------------------------
// Example 8: Real-time coherence monitoring function
// -----------------------------------------------------------------------------
echo "Example 8: Real-Time Monitoring Function\n";
echo "-----------------------------------------\n";

/**
 * Monitor coherence and trigger actions if needed
 */
function monitorCoherence(PackMoodCoherenceService $service, int $actorA, int $actorB): void
{
    $coherence = $service->computeCoherence($actorA, $actorB);

    if ($coherence === null) {
        echo "Cannot monitor - missing mood data\n";
        return;
    }

    $c = $coherence['coherence'];
    $status = $coherence['status'];

    echo "Monitoring Actors {$actorA} & {$actorB}:\n";
    echo "  Coherence: $c\n";
    echo "  Status: $status\n";

    switch ($status) {
        case 'too_aligned':
            echo "  ⚠️  ACTION: Trigger compare-notes (echo chamber prevention)\n";
            // triggerCompareNotesProtocol($actorA, $actorB, 'too_aligned');
            break;

        case 'too_divergent':
            echo "  ⚠️  ACTION: Trigger compare-notes (fragmentation prevention)\n";
            // triggerCompareNotesProtocol($actorA, $actorB, 'too_divergent');
            break;

        case 'healthy':
            echo "  ✅ No action needed - healthy collaboration\n";
            break;
    }
}

// Run monitoring
monitorCoherence($coherenceService, 1, 2);

echo "\n";

// -----------------------------------------------------------------------------
// Example 9: Distance interpretation
// -----------------------------------------------------------------------------
echo "Example 9: Distance Interpretation\n";
echo "-----------------------------------\n";

/**
 * Interpret Manhattan distance
 */
function interpretDistance(int $distance): string
{
    switch ($distance) {
        case 0:
            return "Identical moods";
        case 1:
        case 2:
            return "Very similar moods";
        case 3:
        case 4:
            return "Moderately different moods";
        case 5:
        case 6:
            return "Very different moods";
        default:
            return "Unknown distance";
    }
}

try {
    $coherence = $coherenceService->computeCoherence(1, 2);

    if ($coherence) {
        $distance = $coherence['distance'];
        echo "Manhattan Distance: $distance\n";
        echo "Interpretation: " . interpretDistance($distance) . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// -----------------------------------------------------------------------------
// Example 10: Batch coherence analysis
// -----------------------------------------------------------------------------
echo "Example 10: Batch Coherence Analysis\n";
echo "-------------------------------------\n";

/**
 * Analyze coherence for multiple actor pairs
 */
function batchCoherenceAnalysis(PackMoodCoherenceService $service, array $actorPairs): array
{
    $results = [];

    foreach ($actorPairs as $pair) {
        [$actorA, $actorB] = $pair;

        try {
            $coherence = $service->computeCoherence($actorA, $actorB);

            if ($coherence) {
                $results[] = [
                    'pair' => "{$actorA}-{$actorB}",
                    'coherence' => $coherence['coherence'],
                    'status' => $coherence['status'],
                    'needs_action' => in_array($coherence['status'], ['too_aligned', 'too_divergent'])
                ];
            }
        } catch (Exception $e) {
            // Skip pairs with errors
            continue;
        }
    }

    return $results;
}

$pairs = [[1, 2], [1, 3], [2, 3], [1, 4], [2, 4]];
$results = batchCoherenceAnalysis($coherenceService, $pairs);

echo "Batch Analysis Results:\n";
foreach ($results as $result) {
    $action = $result['needs_action'] ? '⚠️ ' : '✅';
    echo "  $action {$result['pair']}: c={$result['coherence']} ({$result['status']})\n";
}

echo "\n";
echo "Examples completed.\n";
