---
wolfie.headers.version: 4.4.1
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created ActorMoodService documentation. Service provides actor mood logging and retrieval using 2-actor RGB mood model with discrete values (-1, 0, 1). Includes API reference, usage examples, and coherence calculation guidance."
  mood: "00FF88"
tags:
  categories: ["documentation", "services", "emotional-geometry"]
  collections: ["core-docs", "services"]
  channels: ["dev"]
file:
  title: "ActorMoodService Documentation"
  description: "Service layer for actor mood logging and retrieval using 2-actor RGB mood model"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# ðŸŽ­ ActorMoodService Documentation

**Location**: `app/Services/ActorMoodService.php`  
**Namespace**: `App\Services`  
**Version**: 4.4.1  
**Status**: Production Ready  

---

## Overview

The **ActorMoodService** provides a service layer for logging and retrieving actor moods using the canonical 2-actor RGB mood model. It validates discrete mood values and manages persistence in the `actor_moods` table.

### Key Features

âœ… **Discrete Mood Values**: R, G, B âˆˆ {-1, 0, 1} (doctrine-compliant)  
âœ… **UTC Timestamps**: BIGINT YYYYMMDDHHIISS format  
âœ… **Validation**: Automatic validation of mood values and timestamps  
âœ… **History Tracking**: Retrieve mood history with configurable limits  
âœ… **Clean API**: Simple, focused interface for mood operations  
âœ… **No Foreign Keys**: Application-layer relationships only  

---

## Database Schema

### Table: `actor_moods`

**Location**: `database/toon_data/actor_moods.toon`

```sql
CREATE TABLE actor_moods (
    actor_id BIGINT UNSIGNED NOT NULL,
    mood_r TINYINT NOT NULL,
    mood_g TINYINT NOT NULL,
    mood_b TINYINT NOT NULL,
    timestamp_utc BIGINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Fields**:
- `actor_id` - Actor identifier (references `lupo_actor_registry.actor_id`)
- `mood_r` - Red axis: Strife/Conflict âˆˆ {-1, 0, 1}
- `mood_g` - Green axis: Harmony/Cohesion âˆˆ {-1, 0, 1}
- `mood_b` - Blue axis: Memory/Persistence âˆˆ {-1, 0, 1}
- `timestamp_utc` - UTC timestamp in YYYYMMDDHHIISS format

**Note**: No primary key, no auto-increment. This is a log table.

---

## API Reference

### Constructor

```php
public function __construct($db)
```

**Parameters**:
- `$db` (object) - Database connection instance (PDO or PDO_DB)

**Example**:
```php
$db = new PDO_DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_TYPE);
$moodService = new ActorMoodService($db);
```

---

### logMood()

```php
public function logMood(
    int $actorId, 
    int $moodR, 
    int $moodG, 
    int $moodB, 
    ?int $timestampUtc = null
): void
```

Logs an actor mood with discrete RGB values.

**Parameters**:
- `$actorId` (int) - Actor ID (must be positive)
- `$moodR` (int) - Red axis value, must be -1, 0, or 1
- `$moodG` (int) - Green axis value, must be -1, 0, or 1
- `$moodB` (int) - Blue axis value, must be -1, 0, or 1
- `$timestampUtc` (int|null) - Optional UTC timestamp (YYYYMMDDHHIISS), defaults to current UTC

**Returns**: void

**Throws**:
- `InvalidArgumentException` - If mood values are invalid
- `Exception` - If database insert fails

**Example**:
```php
// Log current mood for WOLFIE (actor_id = 2)
$moodService->logMood(
    actorId: 2,
    moodR: 0,   // Neutral strife
    moodG: 1,   // High harmony
    moodB: 1    // Deep memory/reflection
);

// Log mood with specific timestamp
$moodService->logMood(
    actorId: 1,
    moodR: -1,  // Calm (negative strife)
    moodG: 0,   // Neutral harmony
    moodB: 0,   // Neutral memory
    timestampUtc: 20260120150000
);
```

**RGB Axis Semantics**:
- **R (Red)**: Strife/Conflict/Intensity
  - `-1` = Calm, low conflict
  - `0` = Neutral
  - `1` = High strife, tension
  
- **G (Green)**: Harmony/Cohesion/Warmth
  - `-1` = Low harmony, tension
  - `0` = Neutral
  - `1` = High harmony, supportive
  
- **B (Blue)**: Memory/Persistence/Reflection
  - `-1` = Shallow memory, present-focused
  - `0` = Neutral
  - `1` = Deep memory, reflective

---

### getLatestMood()

```php
public function getLatestMood(int $actorId): ?array
```

Retrieves the most recent mood for an actor.

**Parameters**:
- `$actorId` (int) - Actor ID

**Returns**: `array|null`
- Returns mood array if found
- Returns `null` if no mood records exist

**Mood Array Format**:
```php
[
    'actor_id' => int,
    'mood_r' => int,
    'mood_g' => int,
    'mood_b' => int,
    'timestamp_utc' => int
]
```

**Throws**:
- `InvalidArgumentException` - If actor ID is invalid
- `Exception` - If database error occurs

**Example**:
```php
$latestMood = $moodService->getLatestMood(2); // WOLFIE

if ($latestMood === null) {
    echo "No mood records found";
} else {
    echo "Latest mood: R={$latestMood['mood_r']}, ";
    echo "G={$latestMood['mood_g']}, ";
    echo "B={$latestMood['mood_b']}";
}
```

---

### getMoodHistory()

```php
public function getMoodHistory(int $actorId, int $limit = 50): array
```

Retrieves mood history for an actor, ordered by timestamp descending (most recent first).

**Parameters**:
- `$actorId` (int) - Actor ID
- `$limit` (int) - Maximum records to return (default: 50, max: 1000)

**Returns**: `array` - Array of mood records

**Throws**:
- `InvalidArgumentException` - If actor ID or limit is invalid
- `Exception` - If database error occurs

**Example**:
```php
$history = $moodService->getMoodHistory(2, 10); // Last 10 moods for WOLFIE

foreach ($history as $mood) {
    echo "R={$mood['mood_r']}, G={$mood['mood_g']}, B={$mood['mood_b']} ";
    echo "@ {$mood['timestamp_utc']}\n";
}
```

---

## Usage Examples

### Basic Mood Logging

```php
use App\Services\ActorMoodService;

// Initialize
$db = new PDO_DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_TYPE);
$moodService = new ActorMoodService($db);

// Log a reflective, harmonious state for WOLFIE
$moodService->logMood(
    actorId: 2,
    moodR: 0,   // No conflict
    moodG: 1,   // High harmony
    moodB: 1    // Deep reflection
);
```

### Retrieve and Display Latest Mood

```php
$mood = $moodService->getLatestMood(2);

if ($mood) {
    echo "Actor {$mood['actor_id']} mood:\n";
    echo "  Strife: {$mood['mood_r']}\n";
    echo "  Harmony: {$mood['mood_g']}\n";
    echo "  Memory: {$mood['mood_b']}\n";
    echo "  Timestamp: {$mood['timestamp_utc']}\n";
}
```

### Calculate Coherence Between Two Actors

```php
function calculateCoherence(array $mood1, array $mood2): float
{
    // Calculate Euclidean distance
    $dr = $mood1['mood_r'] - $mood2['mood_r'];
    $dg = $mood1['mood_g'] - $mood2['mood_g'];
    $db = $mood1['mood_b'] - $mood2['mood_b'];
    
    $distance = sqrt($dr * $dr + $dg * $dg + $db * $db);
    
    // Normalize: max distance in {-1,0,1} space is sqrt(12) â‰ˆ 3.464
    $maxDistance = sqrt(12);
    $coherence = 1.0 - ($distance / $maxDistance);
    
    return max(0.0, min(1.0, $coherence));
}

// Usage
$mood1 = $moodService->getLatestMood(1); // CAPTAIN
$mood2 = $moodService->getLatestMood(2); // WOLFIE

if ($mood1 && $mood2) {
    $coherence = calculateCoherence($mood1, $mood2);
    
    if ($coherence < 0.3) {
        echo "âš ï¸ Low coherence - compare-notes protocol recommended\n";
    } elseif ($coherence > 0.9) {
        echo "âš ï¸ High coherence - echo chamber risk\n";
    } else {
        echo "âœ… Healthy divergence\n";
    }
}
```

---

## Validation Rules

### Mood Values

âœ… **Valid values**: `-1`, `0`, `1` only  
âŒ **Invalid values**: Any other integer (e.g., `2`, `-2`, `5`)

**Example error**:
```
InvalidArgumentException: Invalid mood_r: must be -1, 0, or 1 (got 2). 
See EMOTIONAL_GEOMETRY_DOCTRINE.md
```

### Timestamps

âœ… **Valid format**: BIGINT YYYYMMDDHHIISS (14 digits)  
âœ… **Example**: `20260120150000` (January 20, 2026 at 15:00:00 UTC)  
âŒ **Invalid**: Unix timestamps, ISO8601 strings, DateTime objects

### Actor IDs

âœ… **Valid**: Positive integers (> 0)  
âŒ **Invalid**: Zero, negative numbers

---

## Coherence Thresholds

Based on the 2-actor RGB mood doctrine:

### Low Coherence (c < 0.3)
- **Risk**: Fragmentation
- **Action**: Trigger compare-notes protocol
- **Meaning**: Actors have diverged significantly

### Healthy Range (0.3 â‰¤ c â‰¤ 0.9)
- **Status**: Optimal
- **Action**: None required
- **Meaning**: Healthy divergence, productive collaboration

### High Coherence (c > 0.9)
- **Risk**: Echo chamber
- **Action**: Trigger compare-notes protocol
- **Meaning**: Actors too aligned, risk of groupthink

---

## Integration Points

### DIALOG Agent

```php
// After DIALOG generates mood_rgb, log discrete mood
$moodService->logMood(
    actorId: $dialog_actor_id,
    moodR: $discrete_r,  // Convert from RGB hex
    moodG: $discrete_g,
    moodB: $discrete_b
);
```

### HERMES Routing

```php
// Get latest mood for routing decisions
$mood = $moodService->getLatestMood($actor_id);

if ($mood) {
    // Use mood for routing bias calculation
    $routingBias = calculateRoutingBias($mood);
}
```

### PackMoodCoherenceService

Use **PackMoodCoherenceService** for automated coherence calculations:

```php
use App\Services\ActorMoodService;
use App\Services\PackMoodCoherenceService;

$moodService = new ActorMoodService($db);
$coherenceService = new PackMoodCoherenceService($moodService);

// Compute coherence between two actors
$coherence = $coherenceService->computeCoherence($actor1_id, $actor2_id);

if ($coherence) {
    echo "Coherence: {$coherence['coherence']}\n";
    echo "Status: {$coherence['status']}\n";
    
    // Automatic threshold detection
    if ($coherenceService->compareNotesNeeded($actor1_id, $actor2_id)) {
        triggerCompareNotesProtocol($actor1_id, $actor2_id, $coherence);
    }
}
```

**See**: [PackMoodCoherenceService Documentation](#packmoodcoherenceservice) below

---

## Error Handling

### Common Errors

**Invalid Mood Value**:
```php
try {
    $moodService->logMood(2, 5, 0, 0); // 5 is invalid
} catch (InvalidArgumentException $e) {
    echo "Validation error: " . $e->getMessage();
}
```

**Database Error**:
```php
try {
    $moodService->logMood(2, 0, 1, 1);
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage();
}
```

**No Records Found**:
```php
$mood = $moodService->getLatestMood(999);

if ($mood === null) {
    echo "No mood records found for this actor";
}
```

---

## Files

### Service Class
- **Path**: `app/Services/ActorMoodService.php`
- **Namespace**: `App\Services`
- **Dependencies**: PDO or PDO_DB

### Usage Example
- **Path**: `app/Services/ActorMoodService.example.php`
- **Purpose**: Demonstrates all service methods with examples

### Database Schema
- **Path**: `database/toon_data/actor_moods.toon`
- **Format**: TOON (Table Object Notation)

---

---

## PackMoodCoherenceService

**Location**: `app/Services/PackMoodCoherenceService.php`  
**Namespace**: `App\Services`  

### Overview

The **PackMoodCoherenceService** computes coherence between two actors using the Manhattan distance formula and provides automatic threshold detection for the compare-notes protocol.

### Constructor

```php
public function __construct(ActorMoodService $moodService)
```

Dependency injection of ActorMoodService.

### computeCoherence()

```php
public function computeCoherence(int $actorAId, int $actorBId): ?array
```

Computes coherence using Manhattan distance:
- `d = |Ra - Rb| + |Ga - Gb| + |Ba - Bb|`
- `c = 1 - (d / 6)`

**Returns**: Array with coherence data or null if either actor has no mood.

**Example**:
```php
$coherence = $coherenceService->computeCoherence(1, 2);

if ($coherence) {
    // $coherence['actor_a_id'] => 1
    // $coherence['actor_b_id'] => 2
    // $coherence['mood_a'] => ['r' => 0, 'g' => 1, 'b' => 1]
    // $coherence['mood_b'] => ['r' => -1, 'g' => 1, 'b' => 0]
    // $coherence['distance'] => 2
    // $coherence['coherence'] => 0.6667
    // $coherence['status'] => 'healthy'
}
```

### compareNotesNeeded()

```php
public function compareNotesNeeded(int $actorAId, int $actorBId): bool
```

Returns `true` if coherence is outside healthy range (status is `too_aligned` or `too_divergent`).

**Example**:
```php
if ($coherenceService->compareNotesNeeded(1, 2)) {
    echo "âš ï¸ Compare-notes protocol should be triggered\n";
}
```

### getThresholds()

```php
public function getThresholds(): array
```

Returns threshold configuration for documentation/UI.

**Example**:
```php
$thresholds = $coherenceService->getThresholds();
// ['high' => 0.9, 'low' => 0.3, 'healthy_range' => ['min' => 0.3, 'max' => 0.9]]
```

### Status Values

- **`too_aligned`**: c > 0.9 (echo chamber risk)
- **`too_divergent`**: c < 0.3 (fragmentation risk)
- **`healthy`**: 0.3 â‰¤ c â‰¤ 0.9 (optimal range)

### Files

- **Service**: `app/Services/PackMoodCoherenceService.php`
- **Examples**: `app/Services/PackMoodCoherenceService.example.php`

---

## Related Documentation

### Canonical Emotional Geometry
- [EMOTIONAL_GEOMETRY_DOCTRINE.md](../doctrine/EMOTIONAL_GEOMETRY_DOCTRINE.md) - 2-Actor RGB Mood Model
- [COUNTING_IN_LIGHT.md](../../appendix/appendix/COUNTING_IN_LIGHT.md) - RGB Axis Semantics
- [MOOD_RGB_DOCTRINE.md](../../doctrine/MOOD_RGB_DOCTRINE.md) - Mood Color System

### Deprecated Models
- [DEPRECATED_EMOTIONAL_GEOMETRY.md](../../doctrine/deprecated/DEPRECATED_EMOTIONAL_GEOMETRY.md) - Legacy scalar/5-tuple models

### Cleanup Report
- [LEGACY_EMOTIONAL_GEOMETRY_CLEANUP.md](../../overview/reports/LEGACY_EMOTIONAL_GEOMETRY_CLEANUP.md) - Repository sweep results

---

## Testing

### Unit Tests (Future)

```php
// Test valid mood values
testLogMood_WithValidValues_Succeeds();

// Test invalid mood values
testLogMood_WithInvalidValue_ThrowsException();

// Test timestamp handling
testLogMood_WithNullTimestamp_UsesCurrentUtc();

// Test retrieval
testGetLatestMood_WithRecords_ReturnsLatestRecord();
testGetLatestMood_WithNoRecords_ReturnsNull();

// Test history
testGetMoodHistory_WithLimit_ReturnsCorrectCount();
testGetMoodHistory_WithNoRecords_ReturnsEmptyArray();
```

---

## Performance Considerations

### Indexing Recommendations

```sql
-- For getLatestMood() performance
CREATE INDEX idx_actor_moods_lookup 
ON actor_moods(actor_id, timestamp_utc DESC);
```

### History Query Optimization

- Default limit: 50 records
- Maximum limit: 1000 records (enforced)
- Always ordered by `timestamp_utc DESC`

### Bulk Logging

For high-volume logging, consider batch inserts:

```php
$db->beginTransaction();
try {
    foreach ($moods as $mood) {
        $moodService->logMood(...$mood);
    }
    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    throw $e;
}
```

---

## Doctrine Compliance

âœ… **Version Atom Doctrine**: Uses `GLOBAL_CURRENT_LUPOPEDIA_VERSION`  
âœ… **No Foreign Keys**: Application-layer relationships only  
âœ… **BIGINT UTC Timestamps**: YYYYMMDDHHIISS format  
âœ… **Discrete Mood Values**: R, G, B âˆˆ {-1, 0, 1}  
âœ… **2-Actor RGB Model**: Canonical emotional geometry implementation  
âœ… **No Triggers**: All logic in application layer  

---

**Created**: 2026-01-20  
**Version**: 4.4.1  
**Status**: Production Ready  
**Maintainer**: CURSOR  

---