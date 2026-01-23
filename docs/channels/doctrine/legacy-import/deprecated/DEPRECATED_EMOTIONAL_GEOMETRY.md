---
wolfie.headers.version: 4.4.1
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created deprecated emotional geometry documentation for legacy models (4.0.x–4.2.x). Documents scalar and 5-tuple models replaced by 2-actor RGB mood geometry."
  mood: "FF6600"
tags:
  categories: ["doctrine", "deprecated", "legacy", "emotional-system"]
  collections: ["core-docs", "doctrine", "deprecated"]
  channels: ["dev", "doctrine"]
file:
  title: "Deprecated Emotional Geometry Models (4.0.x–4.2.x)"
  description: "Documentation of deprecated scalar and 5-tuple emotional models replaced in 4.4.x+"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: deprecated
  author: GLOBAL_CURRENT_AUTHORS
---

# ⚠️ Deprecated Emotional Geometry Models (4.0.x–4.2.x)

**Status**: DEPRECATED  
**Replacement**: 2-Actor RGB Mood Geometry (4.4.x+)  
**Effective Date**: 2026-01-20  

---

## ⚠️ DEPRECATION NOTICE

**All emotional geometry models described in this document are DEPRECATED as of version 4.4.x.**

**Replacement**: See [EMOTIONAL_GEOMETRY_DOCTRINE.md](../EMOTIONAL_GEOMETRY_DOCTRINE.md) for the canonical 2-actor RGB mood model.

---

## 1. Overview of Deprecated Models

The following emotional geometry models were used in Lupopedia versions 4.0.x through 4.2.x but have been replaced:

### 1.1 Scalar Emotional Models
- Single-axis emotion representation
- Range: `(-1.0, 1.0)` continuous values
- Global emotional state without actor context

### 1.2 5-Tuple Emotional Models  
- Five-component emotional vectors
- Format: `(x, y1, y2, c1, c2)`
- Complex multi-dimensional emotion space

### 1.3 Non-Actor-Based Models
- Emotions treated as system-wide states
- No relationship context between participants
- Continuous rather than discrete values

---

## 2. Scalar Emotional Model (4.0.x–4.1.x)

### 2.1 Description
The scalar model represented emotion as a single continuous value on a bipolar scale.

```
emotion ∈ (-1.0, 1.0)
```

Where:
- `-1.0` = Maximum negative emotion
- `0.0` = Neutral emotion  
- `1.0` = Maximum positive emotion

### 2.2 Implementation Example (DEPRECATED)
```php
// DEPRECATED - DO NOT USE
function calculateEmotion($input) {
    return min(1.0, max(-1.0, $input));
}
```

### 2.3 Problems with Scalar Model
- **Oversimplification**: Reduced complex emotions to single dimension
- **Loss of Context**: No actor relationship information
- **Continuous Values**: Difficult to make discrete decisions
- **Global State**: No multi-actor support

---

## 3. 5-Tuple Emotional Model (4.1.x–4.2.x)

### 3.1 Description
The 5-tuple model attempted to capture emotional complexity using five components:

```
emotion = (x, y1, y2, c1, c2)
```

Where:
- `x` = Primary emotional axis
- `y1` = Secondary emotional dimension  
- `y2` = Tertiary emotional dimension
- `c1` = Confidence/intensity factor
- `c2` = Temporal persistence factor

### 3.2 Implementation Example (DEPRECATED)
```php
// DEPRECATED - DO NOT USE
class EmotionalVector {
    public $x, $y1, $y2, $c1, $c2;
    
    public function __construct($x, $y1, $y2, $c1, $c2) {
        $this->x = $x;
        $this->y1 = $y1;
        $this->y2 = $y2;
        $this->c1 = $c1;
        $this->c2 = $c2;
    }
}
```

### 3.3 Problems with 5-Tuple Model
- **Complexity**: Five dimensions were difficult to interpret
- **No Actor Context**: Still treated emotion as global system state
- **Arbitrary Components**: No clear semantic meaning for all components
- **Computational Overhead**: Complex calculations without clear benefits

---

## 4. Why These Models Were Deprecated

### 4.1 Lack of Actor Context
Legacy models treated emotion as a system property rather than a relationship between actors:
- No way to represent human-AI emotional interaction
- No multi-agent emotional coordination
- Missing persona and system actor support

### 4.2 Continuous vs. Discrete Values
Legacy models used continuous ranges that created problems:
- Difficult to make binary decisions
- Fuzzy threshold behavior  
- Non-deterministic state transitions
- Poor integration with discrete logic systems

### 4.3 Scalability Issues
Legacy models didn't scale to multi-actor scenarios:
- No coherence measurement between participants
- No threshold-based intervention protocols
- Limited Pack orchestration support
- Poor real-time performance in complex systems

### 4.4 Semantic Clarity
Legacy models lacked clear emotional semantics:
- Arbitrary numerical ranges
- Unclear component meanings
- No connection to natural language emotion terms
- Difficult to debug and validate

---

## 5. Migration Guide

### 5.1 From Scalar Model to 2-Actor RGB

**Before (DEPRECATED)**:
```php
$emotion = 0.7; // Positive emotion
```

**After (CURRENT)**:
```php
$actor_a_mood = ['R' => 0, 'G' => 0, 'B' => 1];  // Care/harmony/affinity
$actor_b_mood = ['R' => 0, 'G' => 0, 'B' => 1];  // Aligned mood
$coherence = calculateCoherence($actor_a_mood, $actor_b_mood);
```

### 5.2 From 5-Tuple Model to 2-Actor RGB

**Before (DEPRECATED)**:
```php
$emotion = new EmotionalVector(0.8, 0.3, -0.1, 0.9, 0.7);
```

**After (CURRENT)**:
```php
// Map primary axis (x) to RGB components
$mood = [
    'R' => mapToDiscrete($emotion->x, 'conflict'),
    'G' => mapToDiscrete($emotion->y1, 'tension'), 
    'B' => mapToDiscrete($emotion->y2, 'care')
];
```

### 5.3 Migration Utility Functions

```php
// Helper functions for migration (example)
function mapScalarToRGB($scalar_value) {
    if ($scalar_value > 0.3) return ['R' => 0, 'G' => 0, 'B' => 1];
    if ($scalar_value < -0.3) return ['R' => 1, 'G' => 0, 'B' => 0];
    return ['R' => 0, 'G' => 1, 'B' => 0]; // Uncertain/tension
}

function mapToDiscrete($value, $axis_type) {
    if ($value > 0.3) return 1;
    if ($value < -0.3) return -1;
    return 0;
}
```

---

## 6. Code Locations Requiring Updates

### 6.1 File Patterns to Search
When migrating from deprecated models, search for:
- References to scalar emotion calculations
- 5-tuple emotional vector classes
- Continuous emotion ranges (-1.0, 1.0)
- Non-actor emotional state management

### 6.2 Common Variable Names
Look for variables named:
- `$emotion` (scalar)
- `$emotional_state` (global)
- `$mood_value` (continuous)
- `EmotionalVector` (5-tuple class)
- `emotion_x`, `emotion_y1`, etc. (tuple components)

### 6.3 Function Signatures to Update
Replace functions like:
- `calculateEmotion($input)` → `calculateActorMood($actor_id, $context)`
- `getEmotionalState()` → `compareActorMoods($actor_a, $actor_b)`
- `setEmotion($value)` → `updateActorMood($actor_id, $rgb_mood)`

---

## 7. Historical Context

### 7.1 Evolution Timeline
- **4.0.x**: Introduced scalar emotional model for basic sentiment
- **4.1.x**: Expanded to 5-tuple model for complexity
- **4.2.x**: Attempted optimizations of tuple model
- **4.3.x**: Recognized limitations and began actor-centric design
- **4.4.x**: Full replacement with 2-actor RGB mood model

### 7.2 Lessons Learned
- **Actor Context is Essential**: Emotion is always relational
- **Discrete is Better than Continuous**: For system decision-making
- **Semantic Clarity Matters**: RGB axes have clear meanings
- **Scalability from the Start**: Multi-actor support is critical

---

## 8. References and Further Reading

### 8.1 Current Documentation
- [EMOTIONAL_GEOMETRY_DOCTRINE.md](../EMOTIONAL_GEOMETRY_DOCTRINE.md) - Canonical 2-actor model
- [MOOD_RGB_DOCTRINE.md](../MOOD_RGB_DOCTRINE.md) - RGB mood specification
- [COUNTING_IN_LIGHT.md](../appendix/COUNTING_IN_LIGHT.md) - Discrete value foundation

### 8.2 Migration Support
- Contact development team for migration assistance
- Review 4.4.x upgrade documentation
- Test emotional geometry changes thoroughly
- Update any custom emotional logic implementations

---

**Deprecated Date**: 2026-01-20  
**Replacement Version**: 4.4.x+  
**Migration Deadline**: No hard deadline, but strongly recommended  
**Support Status**: Legacy models will not receive updates or bug fixes

---

## Appendix A: Complete Deprecation List

### A.1 Deprecated Classes
- `EmotionalVector` (5-tuple model)
- `ScalarEmotion` (single-value model)  
- `EmotionCalculator` (continuous range calculator)
- `GlobalEmotionalState` (system-wide emotion manager)

### A.2 Deprecated Functions
- `calculateEmotion($scalar)`
- `getEmotionalRange($min, $max)`
- `normalizeEmotion($value)`
- `updateGlobalEmotion($state)`
- `getEmotionVector($x, $y1, $y2, $c1, $c2)`

### A.3 Deprecated Configuration
- `EMOTION_RANGE_MIN` = -1.0
- `EMOTION_RANGE_MAX` = 1.0  
- `EMOTION_COMPONENTS` = 5
- `EMOTION_PRECISION` = 0.01

### A.4 Deprecated Database Columns
- `emotion_scalar` (FLOAT)
- `emotion_x`, `emotion_y1`, `emotion_y2`, `emotion_c1`, `emotion_c2` (FLOAT)
- `global_emotion_state` (FLOAT)

All replaced by discrete RGB mood components and actor-relationship tables.