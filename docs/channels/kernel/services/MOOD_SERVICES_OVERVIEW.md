---
wolfie.headers.version: 4.4.1
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created comprehensive Mood Services Overview documentation. Documents ActorMoodService and PackMoodCoherenceService with complete API reference, integration patterns, and compare-notes protocol implementation guidance."
  mood: "00FF88"
tags:
  categories: ["documentation", "services", "emotional-geometry", "architecture"]
  collections: ["core-docs", "services"]
  channels: ["dev"]
file:
  title: "Mood Services Overview"
  description: "Comprehensive documentation for ActorMoodService and PackMoodCoherenceService"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# ðŸŽ­ Mood Services Overview

**Version**: 4.4.1  
**Status**: Production Ready  
**Architecture**: Service Layer  

---

## Executive Summary

Lupopedia's **Mood Services** provide a complete implementation of the 2-actor RGB mood model, including mood logging, retrieval, coherence calculation, and compare-notes protocol threshold detection.

### Services Included

1. **ActorMoodService** - Mood logging and retrieval
2. **PackMoodCoherenceService** - Coherence calculation and protocol triggering

### Key Features

âœ… **Discrete RGB Mood Model**: R, G, B âˆˆ {-1, 0, 1}  
âœ… **Manhattan Distance Coherence**: Simple, interpretable calculations  
âœ… **Automatic Threshold Detection**: c < 0.3 or c > 0.9 triggers protocol  
âœ… **Clean Service Layer**: Dependency injection, no schema coupling  
âœ… **Doctrine Compliant**: BIGINT UTC timestamps, no foreign keys  
âœ… **Production Ready**: Full validation, error handling, documentation  

---

## Architecture Overview

```
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚                 Application Layer                â”‚
â”‚  (DIALOG, HERMES, Compare-Notes Protocol)       â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¬â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
             â”‚
             â†“
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚         PackMoodCoherenceService                 â”‚
â”‚  - computeCoherence(actorA, actorB)             â”‚
â”‚  - compareNotesNeeded(actorA, actorB)           â”‚
â”‚  - getThresholds()                              â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¬â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
             â”‚
             â†“
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚            ActorMoodService                      â”‚
â”‚  - logMood(actorId, R, G, B, timestamp)         â”‚
â”‚  - getLatestMood(actorId)                       â”‚
â”‚  - getMoodHistory(actorId, limit)               â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¬â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
             â”‚
             â†“
â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚         Database: actor_moods                    â”‚
â”‚  - actor_id, mood_r, mood_g, mood_b             â”‚
â”‚  - timestamp_utc (BIGINT YYYYMMDDHHIISS)        â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
```

---

## Service 1: ActorMoodService

### Purpose

Manages actor mood persistence using the 2-actor RGB mood model with discrete values.

### Location

**Path**: `app/Services/ActorMoodService.php`  
**Namespace**: `App\Services`  

### Quick Start

```php
use App\Services\ActorMoodService;

$db = new PDO_DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_TYPE);
$moodService = new ActorMoodService($db);

// Log a mood
$moodService->logMood(
    actorId: 2,      // WOLFIE
    moodR: 0,        // Neutral strife
    moodG: 1,        // High harmony
    moodB: 1         // Deep memory/reflection
);

// Get latest mood
$mood = $moodService->getLatestMood(2);
if ($mood) {
    echo "Latest: R={$mood['mood_r']}, G={$mood['mood_g']}, B={$mood['mood_b']}";
}

// Get mood history
$history = $moodService->getMoodHistory(2, 50); // Last 50 moods
```

### API Reference

#### Constructor

```php
public function __construct($db)
```

**Parameters**:
- `$db` (object) - PDO or PDO_DB instance

#### logMood()

```php
public function logMood(
    int $actorId, 
    int $moodR, 
    int $moodG, 
    int $moodB, 
    ?int $timestampUtc = null
): void
```

**Parameters**:
- `$actorId` - Actor ID (must be > 0)
- `$moodR` - Red axis âˆˆ {-1, 0, 1}
- `$moodG` - Green axis âˆˆ {-1, 0, 1}
- `$moodB` - Blue axis âˆˆ {-1, 0, 1}
- `$timestampUtc` - Optional UTC timestamp (YYYYMMDDHHIISS format)

**Validation**:
- Throws `InvalidArgumentException` if mood values not in {-1, 0, 1}
- Throws `Exception` on database error

#### getLatestMood()

```php
public function getLatestMood(int $actorId): ?array
```

**Returns**: Mood array or null
```php
[
    'actor_id' => int,
    'mood_r' => int,
    'mood_g' => int,
    'mood_b' => int,
    'timestamp_utc' => int
]
```

#### getMoodHistory()

```php
public function getMoodHistory(int $actorId, int $limit = 50): array
```

**Parameters**:
- `$actorId` - Actor ID
- `$limit` - Max records (default: 50, max: 1000)

**Returns**: Array of mood records (most recent first)

### RGB Axis Semantics

#### R (Red) - Strife/Conflict/Intensity
- `-1` = Calm, low conflict
- `0` = Neutral
- `1` = High strife, tension

#### G (Green) - Harmony/Cohesion/Warmth
- `-1` = Low harmony, tension
- `0` = Neutral
- `1` = High harmony, supportive

#### B (Blue) - Memory/Persistence/Reflection
- `-1` = Shallow memory, present-focused
- `0` = Neutral
- `1` = Deep memory, reflective

### Database Schema

**Table**: `actor_moods`  
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

**Notes**:
- No primary key (log table)
- No auto-increment
- No foreign keys (application-layer relationships)
- BIGINT UTC timestamps only

---

## Service 2: PackMoodCoherenceService

### Purpose

Computes coherence between two actors and detects when compare-notes protocol should be triggered.

### Location

**Path**: `app/Services/PackMoodCoherenceService.php`  
**Namespace**: `App\Services`  

### Quick Start

```php
use App\Services\ActorMoodService;
use App\Services\PackMoodCoherenceService;

$moodService = new ActorMoodService($db);
$coherenceService = new PackMoodCoherenceService($moodService);

// Compute coherence
$coherence = $coherenceService->computeCoherence(1, 2); // CAPTAIN & WOLFIE

if ($coherence) {
    echo "Coherence: {$coherence['coherence']}\n";
    echo "Status: {$coherence['status']}\n";
    
    // Check if action needed
    if ($coherenceService->compareNotesNeeded(1, 2)) {
        triggerCompareNotesProtocol(1, 2, $coherence);
    }
}
```

### Coherence Formula

**Manhattan Distance**:
```
d = |Ra - Rb| + |Ga - Gb| + |Ba - Bb|
```

**Coherence Score**:
```
c = 1 - (d / 6)
```

**Properties**:
- Range: 0.0 (completely different) to 1.0 (identical)
- Max distance in {-1, 0, 1} space: 6
- Simple, interpretable, deterministic

### API Reference

#### Constructor

```php
public function __construct(ActorMoodService $moodService)
```

**Dependency Injection**: Requires ActorMoodService instance

#### computeCoherence()

```php
public function computeCoherence(int $actorAId, int $actorBId): ?array
```

**Returns**: Coherence data array or null
```php
[
    'actor_a_id' => int,
    'actor_b_id' => int,
    'mood_a' => ['r' => int, 'g' => int, 'b' => int],
    'mood_b' => ['r' => int, 'g' => int, 'b' => int],
    'distance' => int,        // Manhattan distance (0-6)
    'coherence' => float,     // Coherence score (0.0-1.0)
    'status' => string        // 'too_aligned' | 'too_divergent' | 'healthy'
]
```

**Returns null if**: Either actor has no mood records

#### compareNotesNeeded()

```php
public function compareNotesNeeded(int $actorAId, int $actorBId): bool
```

**Returns**: `true` if status is `too_aligned` or `too_divergent`

**Example**:
```php
if ($coherenceService->compareNotesNeeded(1, 2)) {
    // Trigger compare-notes protocol
    echo "âš ï¸ Protocol activation needed";
}
```

#### getThresholds()

```php
public function getThresholds(): array
```

**Returns**: Threshold configuration
```php
[
    'high' => 0.9,
    'low' => 0.3,
    'healthy_range' => ['min' => 0.3, 'max' => 0.9]
]
```

### Status Values

#### too_aligned (c > 0.9)
- **Risk**: Echo chamber, groupthink
- **Action**: Trigger compare-notes protocol
- **Example**: Identical or nearly identical moods

#### too_divergent (c < 0.3)
- **Risk**: Fragmentation, communication breakdown
- **Action**: Trigger compare-notes protocol
- **Example**: Opposite moods across all axes

#### healthy (0.3 â‰¤ c â‰¤ 0.9)
- **Status**: Optimal collaboration
- **Action**: None required
- **Example**: Moderate differences, productive diversity

### Coherence Examples

| Actor A Mood | Actor B Mood | Distance | Coherence | Status |
|--------------|--------------|----------|-----------|--------|
| (0, 0, 0)    | (0, 0, 0)    | 0        | 1.0000    | too_aligned |
| (1, 1, 1)    | (1, 1, 1)    | 0        | 1.0000    | too_aligned |
| (0, 1, 0)    | (0, 0, 1)    | 2        | 0.6667    | healthy |
| (1, 0, 0)    | (0, 1, 0)    | 2        | 0.6667    | healthy |
| (1, 1, 1)    | (-1, -1, -1) | 6        | 0.0000    | too_divergent |
| (1, -1, 1)   | (-1, 1, -1)  | 6        | 0.0000    | too_divergent |

---

## Integration Patterns

### Pattern 1: DIALOG Agent Integration

```php
// After DIALOG generates mood_rgb, extract discrete values and log
function logDialogMood(int $actorId, string $moodRgbHex): void
{
    global $moodService;
    
    // Convert hex RGB to discrete values
    [$r, $g, $b] = hexToDiscreteMood($moodRgbHex);
    
    // Log mood
    $moodService->logMood($actorId, $r, $g, $b);
}

function hexToDiscreteMood(string $hex): array
{
    // Extract RGB from hex (e.g., "FF0088" â†’ [255, 0, 136])
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // Map to discrete {-1, 0, 1}
    return [
        mapToDiscrete($r),
        mapToDiscrete($g),
        mapToDiscrete($b)
    ];
}

function mapToDiscrete(int $value): int
{
    if ($value < 85) return -1;
    if ($value > 170) return 1;
    return 0;
}
```

### Pattern 2: HERMES Routing Integration

```php
// Use mood for routing bias calculations
function getRoutingBias(int $actorId): ?array
{
    global $moodService;
    
    $mood = $moodService->getLatestMood($actorId);
    
    if (!$mood) {
        return null;
    }
    
    // Calculate routing bias from mood
    return [
        'analytical_bias' => ($mood['mood_g'] + $mood['mood_b']) / 2.0,
        'creative_bias' => ($mood['mood_r'] + $mood['mood_b']) / 2.0,
        'memory_depth' => $mood['mood_b']
    ];
}
```

### Pattern 3: Compare-Notes Protocol Trigger

```php
// Automated protocol triggering
function monitorActorPairCoherence(int $actorA, int $actorB): void
{
    global $coherenceService;
    
    if ($coherenceService->compareNotesNeeded($actorA, $actorB)) {
        $coherence = $coherenceService->computeCoherence($actorA, $actorB);
        
        // Log event
        logCompareNotesEvent($actorA, $actorB, $coherence);
        
        // Trigger protocol
        triggerCompareNotesProtocol($actorA, $actorB, $coherence);
    }
}

function triggerCompareNotesProtocol(int $actorA, int $actorB, array $coherence): void
{
    $status = $coherence['status'];
    $c = $coherence['coherence'];
    
    switch ($status) {
        case 'too_aligned':
            // Echo chamber prevention
            echo "âš ï¸ Actors {$actorA} & {$actorB} too aligned (c={$c})\n";
            echo "Action: Introduce diverse perspectives\n";
            break;
            
        case 'too_divergent':
            // Fragmentation prevention
            echo "âš ï¸ Actors {$actorA} & {$actorB} too divergent (c={$c})\n";
            echo "Action: Facilitate alignment discussion\n";
            break;
    }
}
```

### Pattern 4: Multi-Actor Monitoring

```php
// Monitor all active actor pairs
function monitorAllPairs(array $actorIds): array
{
    global $coherenceService;
    
    $results = [];
    
    for ($i = 0; $i < count($actorIds); $i++) {
        for ($j = $i + 1; $j < count($actorIds); $j++) {
            $actorA = $actorIds[$i];
            $actorB = $actorIds[$j];
            
            $coherence = $coherenceService->computeCoherence($actorA, $actorB);
            
            if ($coherence && $coherenceService->compareNotesNeeded($actorA, $actorB)) {
                $results[] = [
                    'actors' => [$actorA, $actorB],
                    'coherence' => $coherence,
                    'priority' => calculatePriority($coherence)
                ];
            }
        }
    }
    
    // Sort by priority
    usort($results, fn($a, $b) => $b['priority'] <=> $a['priority']);
    
    return $results;
}

function calculatePriority(array $coherence): float
{
    $c = $coherence['coherence'];
    
    // Higher priority for more extreme coherence values
    if ($c > 0.9) {
        return 1.0 - $c; // More extreme = higher priority
    } else {
        return $c; // Lower coherence = higher priority
    }
}
```

---

## Testing Patterns

### Unit Test Examples

```php
// Test mood logging
function testLogMood_WithValidValues_Succeeds(): void
{
    $moodService = new ActorMoodService($db);
    
    // Should not throw
    $moodService->logMood(1, -1, 0, 1);
    
    $mood = $moodService->getLatestMood(1);
    assert($mood['mood_r'] === -1);
    assert($mood['mood_g'] === 0);
    assert($mood['mood_b'] === 1);
}

// Test validation
function testLogMood_WithInvalidValue_ThrowsException(): void
{
    $moodService = new ActorMoodService($db);
    
    try {
        $moodService->logMood(1, 2, 0, 0); // 2 is invalid
        assert(false, "Should have thrown exception");
    } catch (InvalidArgumentException $e) {
        assert(true); // Expected
    }
}

// Test coherence calculation
function testComputeCoherence_WithIdenticalMoods_Returns1(): void
{
    $moodService = new ActorMoodService($db);
    $coherenceService = new PackMoodCoherenceService($moodService);
    
    // Log identical moods
    $moodService->logMood(1, 0, 1, 1);
    $moodService->logMood(2, 0, 1, 1);
    
    $coherence = $coherenceService->computeCoherence(1, 2);
    
    assert($coherence['coherence'] === 1.0);
    assert($coherence['status'] === 'too_aligned');
}

// Test threshold detection
function testCompareNotesNeeded_WithHighCoherence_ReturnsTrue(): void
{
    $moodService = new ActorMoodService($db);
    $coherenceService = new PackMoodCoherenceService($moodService);
    
    // Log identical moods (c = 1.0 > 0.9)
    $moodService->logMood(1, 1, 0, -1);
    $moodService->logMood(2, 1, 0, -1);
    
    $needed = $coherenceService->compareNotesNeeded(1, 2);
    
    assert($needed === true);
}
```

---

## Performance Considerations

### Indexing Recommendations

```sql
-- Optimize getLatestMood() and getMoodHistory()
CREATE INDEX idx_actor_moods_lookup 
ON actor_moods(actor_id, timestamp_utc DESC);

-- Optimize by timestamp range (if needed)
CREATE INDEX idx_actor_moods_timestamp 
ON actor_moods(timestamp_utc);
```

### Caching Strategy

```php
// Cache latest moods in memory for active actors
class MoodCache
{
    private $cache = [];
    private $ttl = 300; // 5 minutes
    
    public function getLatestMood(int $actorId, ActorMoodService $service): ?array
    {
        $key = "mood_{$actorId}";
        
        if (isset($this->cache[$key])) {
            $cached = $this->cache[$key];
            if (time() - $cached['cached_at'] < $this->ttl) {
                return $cached['data'];
            }
        }
        
        $mood = $service->getLatestMood($actorId);
        
        $this->cache[$key] = [
            'data' => $mood,
            'cached_at' => time()
        ];
        
        return $mood;
    }
}
```

### Batch Operations

```php
// Batch mood logging
function batchLogMoods(array $moods, ActorMoodService $service, $db): void
{
    $db->beginTransaction();
    
    try {
        foreach ($moods as $mood) {
            $service->logMood(
                $mood['actor_id'],
                $mood['mood_r'],
                $mood['mood_g'],
                $mood['mood_b'],
                $mood['timestamp_utc'] ?? null
            );
        }
        
        $db->commit();
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}
```

---

## Error Handling Best Practices

### Pattern 1: Graceful Degradation

```php
function getActorCoherence(int $actorA, int $actorB): ?float
{
    global $coherenceService;
    
    try {
        $coherence = $coherenceService->computeCoherence($actorA, $actorB);
        return $coherence ? $coherence['coherence'] : null;
    } catch (Exception $e) {
        error_log("Coherence calculation failed: " . $e->getMessage());
        return null; // Graceful degradation
    }
}
```

### Pattern 2: Validation with Helpful Messages

```php
function validateAndLogMood(int $actorId, int $r, int $g, int $b): bool
{
    global $moodService;
    
    // Validate before calling service
    if (!in_array($r, [-1, 0, 1])) {
        echo "Invalid R value: must be -1, 0, or 1\n";
        return false;
    }
    
    if (!in_array($g, [-1, 0, 1])) {
        echo "Invalid G value: must be -1, 0, or 1\n";
        return false;
    }
    
    if (!in_array($b, [-1, 0, 1])) {
        echo "Invalid B value: must be -1, 0, or 1\n";
        return false;
    }
    
    try {
        $moodService->logMood($actorId, $r, $g, $b);
        return true;
    } catch (Exception $e) {
        error_log("Mood logging failed: " . $e->getMessage());
        return false;
    }
}
```

---

## Files Reference

### Service Classes

| File | Purpose |
|------|---------|
| `app/Services/ActorMoodService.php` | Mood logging and retrieval |
| `app/Services/PackMoodCoherenceService.php` | Coherence calculation and protocol detection |

### Example Files

| File | Purpose |
|------|---------|
| `app/Services/ActorMoodService.example.php` | 6 usage examples for ActorMoodService |
| `app/Services/PackMoodCoherenceService.example.php` | 10 usage examples for PackMoodCoherenceService |

### Documentation

| File | Purpose |
|------|---------|
| `docs/services/ACTOR_MOOD_SERVICE.md` | Detailed ActorMoodService documentation |
| `docs/services/MOOD_SERVICES_OVERVIEW.md` | This file (overview) |

### Database Schema

| File | Purpose |
|------|---------|
| `database/toon_data/actor_moods.toon` | Table definition in TOON format |

---

## Related Documentation

### Canonical Emotional Geometry

- [EMOTIONAL_GEOMETRY_DOCTRINE.md](../doctrine/EMOTIONAL_GEOMETRY_DOCTRINE.md) - 2-Actor RGB Mood Model
- [COUNTING_IN_LIGHT.md](../../appendix/appendix/COUNTING_IN_LIGHT.md) - RGB Axis Semantics (R=Strife, G=Harmony, B=Memory)
- [MOOD_RGB_DOCTRINE.md](../../doctrine/MOOD_RGB_DOCTRINE.md) - Mood Color System

### Deprecated Models

- [DEPRECATED_EMOTIONAL_GEOMETRY.md](../../doctrine/deprecated/DEPRECATED_EMOTIONAL_GEOMETRY.md) - Legacy scalar/5-tuple models
- [LEGACY_EMOTIONAL_GEOMETRY_CLEANUP.md](../../overview/reports/LEGACY_EMOTIONAL_GEOMETRY_CLEANUP.md) - Repository cleanup report

### Architecture

- [ARCHITECTURE_SYNC.md](../../architecture/ARCHITECTURE_SYNC.md) - Lupopedia architecture overview
- [HERMES_AND_CADUCEUS.md](../../agents/HERMES_AND_CADUCEUS.md) - Routing system integration

---

## Doctrine Compliance

âœ… **Version Atom Doctrine**: Uses `GLOBAL_CURRENT_LUPOPEDIA_VERSION`  
âœ… **No Foreign Keys**: Application-layer relationships only  
âœ… **BIGINT UTC Timestamps**: YYYYMMDDHHIISS format  
âœ… **Discrete Mood Values**: R, G, B âˆˆ {-1, 0, 1}  
âœ… **2-Actor RGB Model**: Canonical implementation  
âœ… **No Triggers**: All logic in application layer  
âœ… **Clean Service Layer**: Dependency injection, testable  
âœ… **No Schema Coupling**: Services don't create/modify tables  

---

## Future Enhancements

### Phase 1: Analytics (Planned)

- Mood trend analysis over time
- Actor mood clustering
- Coherence history tracking
- Aggregate statistics by time window

### Phase 2: Visualization (Planned)

- Real-time coherence dashboard
- Mood history charts
- Network graphs of actor coherence
- Compare-notes protocol event log

### Phase 3: Advanced Features (Roadmap)

- Mood prediction based on history
- Multi-actor coherence optimization
- Automatic protocol tuning
- ML-based mood pattern recognition

---

## Quick Reference

### Mood Values

```
R (Red)   - Strife/Conflict    : {-1, 0, 1}
G (Green) - Harmony/Cohesion   : {-1, 0, 1}
B (Blue)  - Memory/Persistence : {-1, 0, 1}
```

### Coherence Formula

```
d = |Ra - Rb| + |Ga - Gb| + |Ba - Bb|  (Manhattan distance, 0-6)
c = 1 - (d / 6)                         (Coherence score, 0.0-1.0)
```

### Thresholds

```
c > 0.9       â†’ too_aligned   (echo chamber risk)
0.3 â‰¤ c â‰¤ 0.9 â†’ healthy       (optimal range)
c < 0.3       â†’ too_divergent (fragmentation risk)
```

### Typical Workflow

1. **Log Mood**: `$moodService->logMood($actorId, $r, $g, $b)`
2. **Compute Coherence**: `$coherence = $coherenceService->computeCoherence($actorA, $actorB)`
3. **Check Status**: `if ($coherence['status'] !== 'healthy') { trigger_protocol(); }`

---

**Created**: 2026-01-20  
**Version**: 4.4.1  
**Status**: Production Ready  
**Maintainer**: CURSOR  

---