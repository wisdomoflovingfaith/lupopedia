---
wolfie.headers.version: 4.4.1
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created Mood Services Integration Guide. Documents complete integration of ActorMoodService and PackMoodCoherenceService into Lupopedia, including database setup, CLI tools, and runtime usage."
  mood: "00FF88"
tags:
  categories: ["documentation", "integration", "services", "emotional-geometry"]
  collections: ["core-docs", "services"]
  channels: ["dev"]
file:
  title: "Mood Services Integration Guide"
  description: "Complete integration guide for ActorMoodService and PackMoodCoherenceService"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🎭 Mood Services Integration Guide

**Version**: 4.4.1  
**Status**: Complete  
**Date**: 2026-01-20  

---

## Executive Summary

This guide documents the complete integration of the **2-actor RGB mood services** into Lupopedia, providing mood logging, retrieval, coherence calculation, and compare-notes protocol detection.

### What's Been Integrated

✅ **Database Table**: `actor_moods` with optimized indexes  
✅ **Service Layer**: ActorMoodService + PackMoodCoherenceService  
✅ **CLI Tool**: debug-actor-mood.php for testing and debugging  
✅ **Documentation**: Complete API reference and usage guides  
✅ **Doctrine**: Updated EMOTIONAL_GEOMETRY_DOCTRINE.md  

---

## 1. Database Setup

### 1.1 Table Creation

**Migration**: `database/migrations/4.4.1_create_actor_moods_table.sql`

```sql
CREATE TABLE IF NOT EXISTS actor_moods (
    actor_id BIGINT UNSIGNED NOT NULL COMMENT 'Actor ID from lupo_actor_registry',
    mood_r TINYINT NOT NULL COMMENT 'Red axis: Strife/Conflict (-1, 0, 1)',
    mood_g TINYINT NOT NULL COMMENT 'Green axis: Harmony/Cohesion (-1, 0, 1)',
    mood_b TINYINT NOT NULL COMMENT 'Blue axis: Memory/Persistence (-1, 0, 1)',
    timestamp_utc BIGINT UNSIGNED NOT NULL COMMENT 'UTC timestamp in YYYYMMDDHHIISS format',
    INDEX idx_actor_timestamp (actor_id, timestamp_utc DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Actor mood logging table for 2-actor RGB mood model';
```

### 1.2 Run Migration

```bash
# From Lupopedia root directory
mysql -u your_user -p lupopedia < database/migrations/4.4.1_create_actor_moods_table.sql
```

### 1.3 Verify Table

```sql
-- Check table exists
SHOW TABLES LIKE 'actor_moods';

-- Check schema
DESCRIBE actor_moods;

-- Check indexes
SHOW INDEX FROM actor_moods;
```

**Expected Output**:
```
+---------------+--------+------+-----+---------+-------+
| Field         | Type   | Null | Key | Default | Extra |
+---------------+--------+------+-----+---------+-------+
| actor_id      | bigint | NO   | MUL | NULL    |       |
| mood_r        | tinyint| NO   |     | NULL    |       |
| mood_g        | tinyint| NO   |     | NULL    |       |
| mood_b        | tinyint| NO   |     | NULL    |       |
| timestamp_utc | bigint | NO   |     | NULL    |       |
+---------------+--------+------+-----+---------+-------+
```

---

## 2. Service Layer Integration

### 2.1 Service Files

**Created Files**:
- `app/Services/ActorMoodService.php` - Mood logging and retrieval
- `app/Services/PackMoodCoherenceService.php` - Coherence calculation
- `app/Services/ActorMoodService.example.php` - Usage examples
- `app/Services/PackMoodCoherenceService.example.php` - Coherence examples

### 2.2 Service Registration

Services use **dependency injection** and do not require explicit registration in a container. Initialize them where needed:

```php
// Initialize database connection
$db = new PDO_DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_TYPE);

// Initialize services
$moodService = new ActorMoodService($db);
$coherenceService = new PackMoodCoherenceService($moodService);
```

### 2.3 Autoloading

Services are loaded via manual `require_once` statements. No autoloader configuration needed:

```php
require_once __DIR__ . '/app/Services/ActorMoodService.php';
require_once __DIR__ . '/app/Services/PackMoodCoherenceService.php';

use App\Services\ActorMoodService;
use App\Services\PackMoodCoherenceService;
```

---

## 3. CLI Tool Setup

### 3.1 Tool Location

**Path**: `bin/cli/debug-actor-mood.php`

### 3.2 Make Executable (Unix/Linux/Mac)

```bash
chmod +x bin/cli/debug-actor-mood.php
```

### 3.3 Available Commands

```bash
# Log a mood
php bin/cli/debug-actor-mood.php log-mood <actor_id> <r> <g> <b>

# Get latest mood
php bin/cli/debug-actor-mood.php get-mood <actor_id>

# Get mood history
php bin/cli/debug-actor-mood.php get-history <actor_id> [limit]

# Show coherence
php bin/cli/debug-actor-mood.php show-coherence <actor_a_id> <actor_b_id>

# Show help
php bin/cli/debug-actor-mood.php help
```

### 3.4 Example Usage

```bash
# Log a mood for WOLFIE (actor_id = 2)
# R=0 (neutral strife), G=1 (high harmony), B=1 (deep memory)
php bin/cli/debug-actor-mood.php log-mood 2 0 1 1

# Get latest mood for WOLFIE
php bin/cli/debug-actor-mood.php get-mood 2

# Show coherence between CAPTAIN (1) and WOLFIE (2)
php bin/cli/debug-actor-mood.php show-coherence 1 2
```

### 3.5 Expected Output

```
Computing coherence between actors 1 and 2...

✅ Coherence Analysis:
================================================================================

Actor A (ID 1):
  R (Strife): -1
  G (Harmony): 1
  B (Memory): 0

Actor B (ID 2):
  R (Strife): 0
  G (Harmony): 1
  B (Memory): 1

Coherence Metrics:
  Manhattan Distance: 2
  Coherence Score: 0.6667
  Status: healthy

================================================================================

✅ Healthy divergence detected (0.3 ≤ c ≤ 0.9)
  Status: Optimal collaboration state
  Action: No intervention needed

Compare-Notes Protocol: ✅ Not needed
```

---

## 4. Runtime Usage

### 4.1 Basic Mood Logging

```php
use App\Services\ActorMoodService;

// Initialize
$db = new PDO_DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_TYPE);
$moodService = new ActorMoodService($db);

// Log a mood
try {
    $moodService->logMood(
        actorId: 2,      // WOLFIE
        moodR: 0,        // Neutral strife
        moodG: 1,        // High harmony
        moodB: 1         // Deep memory
    );
    echo "✅ Mood logged successfully\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
```

### 4.2 Retrieve Latest Mood

```php
// Get latest mood
$mood = $moodService->getLatestMood(2);

if ($mood) {
    echo "Actor {$mood['actor_id']} mood:\n";
    echo "  R (Strife): {$mood['mood_r']}\n";
    echo "  G (Harmony): {$mood['mood_g']}\n";
    echo "  B (Memory): {$mood['mood_b']}\n";
    echo "  Timestamp: {$mood['timestamp_utc']}\n";
} else {
    echo "No mood records found\n";
}
```

### 4.3 Calculate Coherence

```php
use App\Services\PackMoodCoherenceService;

// Initialize coherence service
$coherenceService = new PackMoodCoherenceService($moodService);

// Calculate coherence
$coherence = $coherenceService->computeCoherence(1, 2); // CAPTAIN & WOLFIE

if ($coherence) {
    echo "Coherence: {$coherence['coherence']}\n";
    echo "Status: {$coherence['status']}\n";
    
    // Check if compare-notes needed
    if ($coherenceService->compareNotesNeeded(1, 2)) {
        echo "⚠️ Compare-notes protocol should be triggered\n";
    }
}
```

### 4.4 Monitor Multiple Pairs

```php
// Monitor all active actor pairs
function monitorAllPairs(array $actorIds, PackMoodCoherenceService $service): array
{
    $alerts = [];
    
    for ($i = 0; $i < count($actorIds); $i++) {
        for ($j = $i + 1; $j < count($actorIds); $j++) {
            $actorA = $actorIds[$i];
            $actorB = $actorIds[$j];
            
            if ($service->compareNotesNeeded($actorA, $actorB)) {
                $coherence = $service->computeCoherence($actorA, $actorB);
                $alerts[] = [
                    'actors' => [$actorA, $actorB],
                    'coherence' => $coherence
                ];
            }
        }
    }
    
    return $alerts;
}

// Usage
$activeActors = [1, 2, 3]; // CAPTAIN, WOLFIE, ROSE
$alerts = monitorAllPairs($activeActors, $coherenceService);

foreach ($alerts as $alert) {
    echo "⚠️ Alert: Actors {$alert['actors'][0]} & {$alert['actors'][1]}\n";
    echo "   Status: {$alert['coherence']['status']}\n";
}
```

---

## 5. Integration with Existing Systems

### 5.1 DIALOG Agent Integration

After DIALOG generates mood_rgb, log discrete mood:

```php
// In DIALOG agent processing
function processSummaryAndLogMood(int $actorId, string $moodRgbHex, ActorMoodService $service): void
{
    // Convert hex RGB to discrete values
    [$r, $g, $b] = hexToDiscreteMood($moodRgbHex);
    
    // Log mood
    $service->logMood($actorId, $r, $g, $b);
}

function hexToDiscreteMood(string $hex): array
{
    // Extract RGB (e.g., "FF0088" → [255, 0, 136])
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // Map to discrete {-1, 0, 1}
    return [
        $r < 85 ? -1 : ($r > 170 ? 1 : 0),
        $g < 85 ? -1 : ($g > 170 ? 1 : 0),
        $b < 85 ? -1 : ($b > 170 ? 1 : 0)
    ];
}
```

### 5.2 HERMES Routing Integration

Use mood for routing bias:

```php
// Get mood-based routing bias
function getRoutingBias(int $actorId, ActorMoodService $service): ?array
{
    $mood = $service->getLatestMood($actorId);
    
    if (!$mood) {
        return null;
    }
    
    return [
        'analytical_bias' => ($mood['mood_g'] + $mood['mood_b']) / 2.0,
        'creative_bias' => ($mood['mood_r'] + $mood['mood_b']) / 2.0,
        'memory_depth' => $mood['mood_b']
    ];
}
```

### 5.3 Compare-Notes Protocol

Automatic protocol triggering:

```php
// Check and trigger compare-notes
function checkAndTriggerProtocol(
    int $actorA, 
    int $actorB, 
    PackMoodCoherenceService $service
): void {
    if ($service->compareNotesNeeded($actorA, $actorB)) {
        $coherence = $service->computeCoherence($actorA, $actorB);
        
        // Log event
        error_log("Compare-notes triggered: Actors $actorA & $actorB");
        error_log("Coherence: {$coherence['coherence']}, Status: {$coherence['status']}");
        
        // Trigger protocol (implement as needed)
        // triggerCompareNotesProtocol($actorA, $actorB, $coherence);
    }
}
```

---

## 6. Testing Integration

### 6.1 Test Database Table

```sql
-- Insert test mood
INSERT INTO actor_moods (actor_id, mood_r, mood_g, mood_b, timestamp_utc)
VALUES (2, 0, 1, 1, 20260120150000);

-- Query latest mood
SELECT * FROM actor_moods WHERE actor_id = 2 ORDER BY timestamp_utc DESC LIMIT 1;

-- Count moods by actor
SELECT actor_id, COUNT(*) as mood_count 
FROM actor_moods 
GROUP BY actor_id;
```

### 6.2 Test Services

```php
// Test mood logging
$moodService->logMood(1, -1, 1, 0);
$moodService->logMood(2, 0, 1, 1);

// Test retrieval
$mood1 = $moodService->getLatestMood(1);
$mood2 = $moodService->getLatestMood(2);

assert($mood1['mood_r'] === -1);
assert($mood2['mood_r'] === 0);

// Test coherence
$coherence = $coherenceService->computeCoherence(1, 2);
assert($coherence !== null);
assert(isset($coherence['status']));
```

### 6.3 Test CLI Tool

```bash
# Log test moods
php bin/cli/debug-actor-mood.php log-mood 1 -1 1 0
php bin/cli/debug-actor-mood.php log-mood 2 0 1 1

# Verify retrieval
php bin/cli/debug-actor-mood.php get-mood 1
php bin/cli/debug-actor-mood.php get-mood 2

# Test coherence
php bin/cli/debug-actor-mood.php show-coherence 1 2
```

---

## 7. Troubleshooting

### 7.1 Table Not Found

**Error**: `Table 'actor_moods' doesn't exist`

**Solution**:
```bash
# Run migration
mysql -u your_user -p lupopedia < database/migrations/4.4.1_create_actor_moods_table.sql

# Verify
mysql -u your_user -p lupopedia -e "SHOW TABLES LIKE 'actor_moods';"
```

### 7.2 Invalid Mood Values

**Error**: `Invalid mood_r: must be -1, 0, or 1 (got 2)`

**Solution**: Mood values must be discrete {-1, 0, 1}. Check your input:
```php
// ❌ Invalid
$moodService->logMood(2, 2, 0, 0); // 2 is invalid

// ✅ Valid
$moodService->logMood(2, 1, 0, 0); // Use 1 instead
```

### 7.3 CLI Tool Permission Denied

**Error**: `Permission denied: bin/cli/debug-actor-mood.php`

**Solution**:
```bash
# Make executable
chmod +x bin/cli/debug-actor-mood.php

# Or run with php explicitly
php bin/cli/debug-actor-mood.php help
```

### 7.4 No Mood Records Found

**Error**: `getLatestMood()` returns `null`

**Solution**: Ensure moods have been logged:
```bash
# Log a test mood
php bin/cli/debug-actor-mood.php log-mood 2 0 0 0

# Verify
php bin/cli/debug-actor-mood.php get-mood 2
```

### 7.5 Timestamp Format Errors

**Error**: `Timestamp must be in YYYYMMDDHHIISS format`

**Solution**: Use 14-digit format:
```php
// ❌ Invalid
$timestamp = time(); // Unix timestamp

// ✅ Valid
$timestamp = (int) gmdate('YmdHis'); // 20260120150000
```

---

## 8. Performance Optimization

### 8.1 Index Usage

The `idx_actor_timestamp` index optimizes queries:
```sql
-- Uses index efficiently
SELECT * FROM actor_moods 
WHERE actor_id = 2 
ORDER BY timestamp_utc DESC 
LIMIT 1;
```

### 8.2 Caching Strategy

Cache latest moods for active actors:
```php
class MoodCache {
    private $cache = [];
    private $ttl = 300; // 5 minutes
    
    public function getLatestMood(int $actorId, ActorMoodService $service): ?array {
        $key = "mood_$actorId";
        
        if (isset($this->cache[$key])) {
            $cached = $this->cache[$key];
            if (time() - $cached['time'] < $this->ttl) {
                return $cached['data'];
            }
        }
        
        $mood = $service->getLatestMood($actorId);
        $this->cache[$key] = ['data' => $mood, 'time' => time()];
        
        return $mood;
    }
}
```

### 8.3 Batch Operations

Use transactions for batch mood logging:
```php
$db->beginTransaction();
try {
    foreach ($moods as $mood) {
        $moodService->logMood($mood['actor_id'], $mood['r'], $mood['g'], $mood['b']);
    }
    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    throw $e;
}
```

---

## 9. Documentation Reference

### 9.1 Service Documentation
- `docs/services/ACTOR_MOOD_SERVICE.md` - Detailed ActorMoodService docs
- `docs/services/MOOD_SERVICES_OVERVIEW.md` - Architecture overview
- `docs/services/PackMoodCoherenceService.example.php` - Usage examples

### 9.2 Doctrine Documentation
- `doctrine/EMOTIONAL_GEOMETRY_DOCTRINE.md` - Canonical 2-actor RGB model
- `docs/appendix/COUNTING_IN_LIGHT.md` - RGB axis semantics
- `doctrine/deprecated/DEPRECATED_EMOTIONAL_GEOMETRY.md` - Legacy models

### 9.3 Database Schema
- `database/toon_data/actor_moods.toon` - TOON definition
- `database/migrations/4.4.1_create_actor_moods_table.sql` - Migration script

---

## 10. Checklist

Use this checklist to verify integration:

### Database Setup
- [ ] Migration executed successfully
- [ ] Table `actor_moods` exists
- [ ] Index `idx_actor_timestamp` exists
- [ ] Test insert/select works

### Service Layer
- [ ] `ActorMoodService.php` exists in `app/Services/`
- [ ] `PackMoodCoherenceService.php` exists in `app/Services/`
- [ ] Services can be instantiated
- [ ] Test mood logging works
- [ ] Test coherence calculation works

### CLI Tool
- [ ] `debug-actor-mood.php` exists in `bin/cli/`
- [ ] Tool is executable (Unix/Linux/Mac)
- [ ] `log-mood` command works
- [ ] `get-mood` command works
- [ ] `show-coherence` command works

### Documentation
- [ ] `EMOTIONAL_GEOMETRY_DOCTRINE.md` updated with runtime section
- [ ] Service documentation reviewed
- [ ] Integration guide reviewed (this document)

### Testing
- [ ] Manual mood logging tested
- [ ] Mood retrieval tested
- [ ] Coherence calculation tested
- [ ] CLI tool commands tested
- [ ] Integration with existing systems planned

---

## 11. Next Steps

### Phase 1: Basic Integration (Complete)
✅ Database table created  
✅ Services implemented  
✅ CLI tool available  
✅ Documentation complete  

### Phase 2: System Integration (Recommended)
- [ ] Integrate with DIALOG agent for automatic mood logging
- [ ] Integrate with HERMES for mood-aware routing
- [ ] Implement compare-notes protocol triggering
- [ ] Add mood analytics and trending

### Phase 3: Advanced Features (Future)
- [ ] Real-time coherence monitoring dashboard
- [ ] Mood-based agent selection
- [ ] Multi-actor coherence optimization
- [ ] ML-based mood prediction

---

## 12. Support

### Questions or Issues?

1. **Check Documentation**: Review service docs in `docs/services/`
2. **Run CLI Tool**: Test with `debug-actor-mood.php help`
3. **Check Logs**: Review error logs for detailed error messages
4. **Verify Schema**: Ensure database table matches migration

### Common Integration Patterns

See `docs/services/MOOD_SERVICES_OVERVIEW.md` for:
- DIALOG integration patterns
- HERMES routing integration
- Compare-notes protocol implementation
- Multi-actor monitoring examples

---

**Integration Complete**: 2026-01-20  
**Version**: 4.4.1  
**Status**: Production Ready  

All mood services are now integrated and ready for use! 🎭

---