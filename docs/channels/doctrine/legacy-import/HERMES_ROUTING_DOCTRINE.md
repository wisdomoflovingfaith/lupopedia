---
wolfie.headers.version: 4.4.1
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created HERMES Routing Doctrine with channel-aware and mood-aware routing. Documents canonical routing behavior using 2-actor RGB mood model and compare-notes protocol."
  mood: "00FF88"
tags:
  categories: ["doctrine", "routing", "architecture", "emotional-geometry"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "doctrine"]
file:
  title: "HERMES Routing Doctrine (Channel-Aware, Mood-Aware)"
  description: "Canonical routing doctrine for HERMES using channel context, actor moods, and compare-notes protocol"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🔀 HERMES Routing Doctrine (Channel-Aware, Mood-Aware)

**Official Doctrine Document**  
**Version: 4.4.x+**  
**Effective Date: 2026-01-20**  
**Status: CANONICAL**

---

## 1. Purpose

**HERMES is the routing layer for Lupopedia.**

It selects which ACTOR speaks next inside a specific channel, using:

- **channel context**
- **actor moods**
- **pairwise coherence**
- **compare-notes protocol**

**Routing is always channel-scoped, never global.**

---

## 2. Core Entities

### 2.1 ACTOR

**Definition**: Human, AI agent, persona, or system actor

**Properties**:
- Identified by `actor_id`
- Has mood history in `actor_moods` table
- Can participate in one or more channels

**Types**:
- Human user
- AI agent
- Persona
- System actor

### 2.2 CHANNEL

**Definition**: Collaboration room with shared context

**Fields** (conceptual):
- `channel_id`
- `channel_type`
- `context_summary`
- `created_by_actor_id`
- `created_utc`

**Scope**: All routing decisions are scoped to a specific channel

### 2.3 MESSAGE

**Definition**: Communication scoped to a channel

**Fields** (conceptual):
- `channel_id`
- `said_from_actor_id`
- `said_to_actor_id`
- `message_text`
- `timestamp_utc`

### 2.4 Visibility Rules

**Humans**: See all messages in the channel

**HERMES**: Sees all messages (system-level visibility)

**AI actors**: See only messages where they are `said_from` or `said_to`

---

## 3. Mood & Coherence Signals

### 3.1 Mood Vector

**Format**: `(R, G, B)` with each axis ∈ {-1, 0, 1}

**Retrieval**: `ActorMoodService::getLatestMood(actor_id)`

**Axes**:
- **R (Red)**: Strife/Conflict/Intensity
- **G (Green)**: Harmony/Cohesion/Warmth
- **B (Blue)**: Memory/Persistence/Reflection

**Example**:
```php
$mood = $moodService->getLatestMood(2); // WOLFIE
// Returns: ['mood_r' => 0, 'mood_g' => 1, 'mood_b' => 1]
```

### 3.2 Coherence Calculation

**Distance** (Manhattan distance):
```
d = |Ra - Rb| + |Ga - Gb| + |Ba - Bb|
```

**Coherence**:
```
c = 1 - (d / 6)
```

**Range**: 0.0 (opposite) → 1.0 (identical)

**Computation**:
```php
$coherence = $coherenceService->computeCoherence($actorA, $actorB);
// Returns: ['coherence' => 0.6667, 'status' => 'healthy', ...]
```

### 3.3 Thresholds

| Coherence | Status | Risk | Action |
|-----------|--------|------|--------|
| c > 0.9 | too_aligned | Echo chamber | Trigger compare-notes |
| 0.3 ≤ c ≤ 0.9 | healthy | None | Normal routing |
| c < 0.3 | too_divergent | Fragmentation | Trigger compare-notes |

**Detection**:
```php
$needsProtocol = $coherenceService->compareNotesNeeded($actorA, $actorB);
// Returns: true if c > 0.9 or c < 0.3
```

---

## 4. Channel-Scoped Routing Loop

### 4.1 Routing Algorithm

**Step 1**: Load channel metadata and list of actors in the channel

**Step 2**: Load latest mood for each actor
```php
foreach ($channelActors as $actorId) {
    $moods[$actorId] = $moodService->getLatestMood($actorId);
}
```

**Step 3**: Compute pairwise coherence only for actors in this channel
```php
foreach ($actorPairs as [$actorA, $actorB]) {
    $coherence[$actorA][$actorB] = $coherenceService->computeCoherence($actorA, $actorB);
}
```

**Step 4**: Compute routing bias per actor
```php
// For each actor with a mood
$bias = [
    'analytical' => ($mood['mood_g'] + $mood['mood_b']) / 2.0,
    'creative' => ($mood['mood_r'] + $mood['mood_b']) / 2.0,
    'stability' => 1 - (abs($mood['mood_r']) + abs($mood['mood_g']) + abs($mood['mood_b'])) / 3.0
];

$weight = $bias['stability'] * $bias['analytical'];
```

**Step 5**: Apply coherence adjustments

**too_aligned** → Reduce weights for highly aligned actors:
```php
if ($coherence['status'] === 'too_aligned') {
    $weight *= 0.5; // Reduce to prevent echo chamber
}
```

**too_divergent** → Pause actors, prefer mediators:
```php
if ($coherence['status'] === 'too_divergent') {
    $pausedActors[] = $actorId;
    // Prefer mediator actors with balanced moods
}
```

**Step 6**: Select next actor: highest non-paused weight
```php
$nextActor = null;
$maxWeight = -1;

foreach ($weights as $actorId => $weight) {
    if (!in_array($actorId, $pausedActors) && $weight > $maxWeight) {
        $maxWeight = $weight;
        $nextActor = $actorId;
    }
}
```

**Step 7**: Emit routing decision
```php
return [
    'channel_id' => $channelId,
    'said_from' => $currentActor,
    'said_to' => $nextActor,
    'reason' => 'mood_aware_channel_routing',
    'bias' => $bias,
    'coherence_snapshot' => $coherence,
    'timestamp_utc' => gmdate('YmdHis')
];
```

### 4.2 Routing Decision Structure

**Fields**:
- `channel_id`: Channel where routing occurred
- `said_from`: Current speaker
- `said_to`: Next speaker (selected by HERMES)
- `reason`: Routing reason code
- `bias`: Routing bias values (analytical, creative, stability)
- `coherence_snapshot`: Pairwise coherence values at routing time
- `timestamp_utc`: BIGINT UTC timestamp (YYYYMMDDHHIISS)

---

## 5. Compare-Notes Protocol

### 5.1 Trigger Conditions

When `compareNotesNeeded(a, b)` is true inside a channel:
- Coherence c > 0.9 (too aligned)
- Coherence c < 0.3 (too divergent)

### 5.2 Protocol Execution

**Step 1**: HERMES emits a `compare_notes` action instead of routing
```php
return [
    'action' => 'compare_notes',
    'channel_id' => $channelId,
    'actor_a' => $actorA,
    'actor_b' => $actorB,
    'coherence' => $coherenceData,
    'reason' => $coherenceData['status'] // 'too_aligned' or 'too_divergent'
];
```

**Step 2**: DIALOG runs a short sync exchange between the two actors
- Actors share current perspectives
- System facilitates alignment discussion
- No routing occurs during protocol

**Step 3**: New moods are logged
```php
// After protocol completes
$moodService->logMood($actorA, $newR, $newG, $newB);
$moodService->logMood($actorB, $newR, $newG, $newB);
```

**Step 4**: HERMES recomputes routing state and resumes normal routing
```php
// Reload moods
$moodA = $moodService->getLatestMood($actorA);
$moodB = $moodService->getLatestMood($actorB);

// Recompute coherence
$newCoherence = $coherenceService->computeCoherence($actorA, $actorB);

// Resume routing if coherence now healthy
if ($newCoherence['status'] === 'healthy') {
    resumeNormalRouting($channelId);
}
```

### 5.3 Protocol Flow Diagram

```
Message arrives → HERMES checks coherence
                    │
                    ├─ c in [0.3, 0.9] → Normal routing
                    │
                    ├─ c > 0.9 → Compare-notes (too aligned)
                    │              │
                    │              └─ DIALOG sync → New moods → Resume
                    │
                    └─ c < 0.3 → Compare-notes (too divergent)
                                   │
                                   └─ DIALOG sync → New moods → Resume
```

---

## 6. Constraints

### 6.1 Channel Scoping

**MANDATORY**: Routing is always channel-scoped

- HERMES never routes across channels
- Each channel has independent routing state
- Coherence is computed only for actors in the same channel
- Routing decisions reference `channel_id`

### 6.2 Database Constraints

**No foreign keys**: Application-layer relationships only

**Timestamp format**: BIGINT UTC (YYYYMMDDHHIISS format)

**Mood values**: R, G, B ∈ {-1, 0, 1} (discrete only)

### 6.3 Emotional Geometry

**MANDATORY**: Always use the 2-actor RGB model

- Moods are discrete: {-1, 0, 1}
- Coherence uses Manhattan distance
- Thresholds: c < 0.3 and c > 0.9
- No legacy scalar or 5-tuple models

**See**: `doctrine/EMOTIONAL_GEOMETRY_DOCTRINE.md`

### 6.4 Visibility Rules

**HERMES must respect said_from / said_to visibility rules**:

- AI actors see only messages where they are `said_from` or `said_to`
- Humans see all messages in the channel
- HERMES has system-level visibility for routing decisions
- Visibility is enforced at application layer, not database

---

## 7. Implementation Reference

### 7.1 Service Dependencies

**Required Services**:
- `ActorMoodService` - Mood logging and retrieval
- `PackMoodCoherenceService` - Coherence calculation
- `HERMES` - Routing layer (implements this doctrine)

**Database Tables**:
- `actor_moods` - Mood history storage
- `channels` - Channel metadata (conceptual)
- `messages` - Message storage (conceptual)

### 7.2 Example Implementation

```php
class HERMES {
    private $moodService;
    private $coherenceService;
    
    public function route(int $channelId, int $currentActorId): array {
        // Step 1: Load channel actors
        $channelActors = $this->getChannelActors($channelId);
        
        // Step 2: Load moods
        $moods = [];
        foreach ($channelActors as $actorId) {
            $moods[$actorId] = $this->moodService->getLatestMood($actorId);
        }
        
        // Step 3: Compute coherence
        $coherenceMap = [];
        foreach ($channelActors as $actorA) {
            foreach ($channelActors as $actorB) {
                if ($actorA !== $actorB) {
                    $coherenceMap[$actorA][$actorB] = 
                        $this->coherenceService->computeCoherence($actorA, $actorB);
                }
            }
        }
        
        // Step 4: Check for compare-notes triggers
        foreach ($coherenceMap as $actorA => $pairs) {
            foreach ($pairs as $actorB => $coherence) {
                if ($this->coherenceService->compareNotesNeeded($actorA, $actorB)) {
                    return $this->emitCompareNotes($channelId, $actorA, $actorB, $coherence);
                }
            }
        }
        
        // Step 5: Compute routing weights
        $weights = [];
        foreach ($channelActors as $actorId) {
            if (!isset($moods[$actorId])) continue;
            
            $mood = $moods[$actorId];
            $bias = $this->computeBias($mood);
            $weights[$actorId] = $bias['stability'] * $bias['analytical'];
            
            // Apply coherence adjustments
            foreach ($coherenceMap[$actorId] ?? [] as $coherence) {
                if ($coherence['status'] === 'too_aligned') {
                    $weights[$actorId] *= 0.5;
                }
            }
        }
        
        // Step 6: Select next actor
        $nextActor = $this->selectNextActor($weights, $channelActors);
        
        // Step 7: Emit routing decision
        return [
            'channel_id' => $channelId,
            'said_from' => $currentActorId,
            'said_to' => $nextActor,
            'reason' => 'mood_aware_channel_routing',
            'coherence_snapshot' => $coherenceMap,
            'timestamp_utc' => gmdate('YmdHis')
        ];
    }
    
    private function computeBias(array $mood): array {
        return [
            'analytical' => ($mood['mood_g'] + $mood['mood_b']) / 2.0,
            'creative' => ($mood['mood_r'] + $mood['mood_b']) / 2.0,
            'stability' => 1 - (abs($mood['mood_r']) + abs($mood['mood_g']) + abs($mood['mood_b'])) / 3.0
        ];
    }
}
```

---

## 8. Testing & Validation

### 8.1 Test Scenarios

**Scenario 1**: Healthy coherence (c = 0.5)
- Expected: Normal routing
- Expected: No compare-notes triggered
- Expected: Routing based on mood bias

**Scenario 2**: Too aligned (c = 1.0)
- Expected: Compare-notes triggered
- Expected: DIALOG sync initiated
- Expected: New moods logged after protocol

**Scenario 3**: Too divergent (c = 0.1)
- Expected: Compare-notes triggered
- Expected: Mediator preference activated
- Expected: Alignment discussion facilitated

**Scenario 4**: Multi-actor channel
- Expected: Pairwise coherence computed for all pairs
- Expected: Channel-scoped routing only
- Expected: No cross-channel routing

### 8.2 Validation Criteria

✅ All routing decisions include `channel_id`  
✅ Coherence computed only for channel actors  
✅ Compare-notes triggers at correct thresholds  
✅ Mood values are discrete {-1, 0, 1}  
✅ Timestamps are BIGINT UTC  
✅ No foreign keys used  
✅ Visibility rules enforced  

---

## 9. Related Documentation

### 9.1 Canonical References

- `doctrine/EMOTIONAL_GEOMETRY_DOCTRINE.md` - 2-Actor RGB mood model
- `docs/services/ACTOR_MOOD_SERVICE.md` - Mood service documentation
- `docs/services/MOOD_SERVICES_OVERVIEW.md` - Service architecture
- `docs/appendix/COUNTING_IN_LIGHT.md` - RGB axis semantics

### 9.2 Deprecated Models

- `doctrine/deprecated/DEPRECATED_EMOTIONAL_GEOMETRY.md` - Legacy models (DO NOT USE)

### 9.3 Implementation Guides

- `docs/services/MOOD_SERVICES_INTEGRATION.md` - Integration guide
- `bin/cli/debug-actor-mood.php` - CLI debugging tool

---

## 10. Semantic Integrity: No Advertising or Manipulation

Lupopedia's semantic engine must remain pure, unmanipulated, and free from commercial influence.

No subsystem may distort meaning, ranking, or visibility for financial gain.

All suggestions and outputs must be based solely on internal data, logic, and doctrine.

### 10.1 HERMES Routing Purity

The HERMES routing system must:
- Select actors based only on genuine coherence calculations
- Never bias routing decisions for commercial purposes
- Maintain channel integrity independent of external influence
- Provide authentic actor selection based on system logic

### 10.2 Prohibited Behaviors

The following are strictly forbidden in HERMES routing:
- Manipulating actor selection for engagement optimization
- Altering coherence thresholds for user retention
- Biasing routing decisions for commercial outcomes
- Distorting channel visibility for advertising targeting
- Using routing data for monetization purposes

### 10.3 Data Integrity Requirements

All HERMES routing data must:
- Originate from authentic user interactions
- Be processed without commercial bias
- Be stored without manipulation for profit
- Be accessed only for legitimate system functions
- Never be shared with advertising systems

---

## 11. Doctrine Compliance

✅ **Channel-Scoped**: All routing is channel-scoped  
✅ **Mood-Aware**: Uses 2-actor RGB mood model  
✅ **Coherence-Based**: Applies compare-notes protocol  
✅ **No Foreign Keys**: Application-layer relationships  
✅ **BIGINT UTC**: Timestamps in YYYYMMDDHHIISS format  
✅ **Discrete Values**: R, G, B ∈ {-1, 0, 1}  
✅ **Visibility Rules**: Enforced for AI actors  

---

**Created**: 2026-01-20  
**Version**: 4.4.1  
**Status**: CANONICAL  
**Maintainer**: CURSOR  

This doctrine is the authoritative reference for HERMES routing behavior.

---