---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.112
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CASCADE
  target: @FLEET @Monday_Wolfie
  mood_RGB: "00FF00"
  message: "Pack Behavioral Layer doctrine established. Emotional geometry now influences agent behavior. Behavioral compatibility evaluated during handoffs."
tags:
  categories: ["doctrine", "pack", "behavior", "emotional-geometry"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "architecture"]
file:
  title: "Pack Behavioral Layer Doctrine"
  description: "Doctrine for Pack Architecture behavioral layer and emotional-behavioral bridge"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Pack Behavioral Layer Doctrine

**Version:** 4.0.109  
**Status:** ACTIVE  
**Authority:** Pack Architecture  
**Scope:** Pack agent behavior, emotional-behavioral bridge, handoff compatibility

---

## Overview

The Pack Behavioral Layer bridges emotional geometry (from 4.0.108) with agent behavior, enabling Pack Architecture to make behavioral decisions based on emotional state and context. This layer completes the emotional → behavioral → action pipeline for Pack agents.

---

## Behavior Profiles

### Definition

A **behavior profile** is a structured representation of an agent's current behavioral tendency, derived from emotional geometry and contextual factors.

### Structure

```php
[
    'agent_id' => string,
    'tendency' => string,      // Behavior label
    'intensity' => float,      // Emotional intensity (0.0 to √3)
    'last_update' => timestamp  // Last update timestamp
]
```

### Behavior Tendencies

Pack agents can exhibit the following behavioral tendencies:

- **supportive**: High positive R component (R = +1 axis), moderate intensity (0.5-1.2)
- **assertive**: High positive R component (R = +1 axis), high intensity (≥1.2)
- **analytical**: High positive G component (G = 0 axis, neutral plane), moderate intensity (0.5-1.2)
- **protective**: High positive B component (B = -1 axis), moderate intensity (0.5-1.2)
- **neutral**: Low intensity (<0.3) or negative components

**Note:** The canonical axis mapping is R = +1, G = 0 (neutral), B = -1. This fixed mapping ensures consistent emotional geometry interpretation across the Pack.

### Behavior Determination

Behavior is determined by:
1. **Emotional Vector**: Normalized RGB components and intensity
2. **Context**: Additional contextual information (optional)
3. **History**: Previous behavior events (for future enhancement)

**Formula:**
```
behavior = f(emotionVector.normalized, emotionVector.intensity, context)
```

---

## Mapping Between Emotional Geometry and Behavior Tendencies

### Emotional Component → Behavior Mapping

**R Component (Red) - +1 Axis:**
- High positive R (>0.5) + moderate intensity → **supportive**
- High positive R (>0.5) + high intensity (≥1.2) → **assertive**

**G Component (Green) - 0 Axis (Neutral Plane):**
- High positive G (>0.5) + moderate intensity → **analytical**

**B Component (Blue) - -1 Axis:**
- High positive B (>0.5) + moderate intensity → **protective**

**Neutral/Default:**
- Low intensity (<0.3) → **neutral**
- All negative components → **neutral**
- Default fallback → **neutral**

### Intensity Thresholds

- **Low**: <0.3 (neutral behavior)
- **Moderate**: 0.5-1.2 (supportive, analytical, protective)
- **High**: ≥1.2 (assertive)

---

## Behavioral Compatibility Rules

### Compatibility Calculation

Behavioral compatibility is calculated using:
1. **Tendency Match**: Same tendency increases compatibility (+0.3)
2. **Complementary Behaviors**: Complementary pairs increase compatibility (+0.2)
3. **Emotional Affinity**: Cosine similarity between emotional vectors

**Complementary Pairs:**
- supportive ↔ analytical
- assertive ↔ protective

**Formula:**
```
compatibility = base_compatibility + tendency_bonus + complementary_bonus
compatibility = (compatibility + emotional_affinity) / 2
```

**Range:** 0.0 to 1.0

### Risk Assessment

Risk levels are determined by compatibility score:

- **Low Risk**: compatibility ≥ 0.7
- **Medium Risk**: 0.4 ≤ compatibility < 0.7
- **High Risk**: compatibility < 0.4

---

## Behavioral Handoff Logic

### Handoff Evaluation Process

When evaluating a handoff between two agents:

1. **Retrieve Behavior Profiles**: Get profiles for both agents
2. **Retrieve Emotional Vectors**: Get encoded emotional geometry
3. **Calculate Compatibility**: Evaluate behavioral compatibility
4. **Assess Risk**: Determine risk level based on compatibility
5. **Record Transition**: Store compatibility and risk in PackContext

### Handoff Decision Matrix

| Compatibility | Risk | Action |
|--------------|------|--------|
| ≥ 0.7 | Low | Proceed with handoff |
| 0.4-0.7 | Medium | Proceed with caution |
| < 0.4 | High | Consider alternative routing |

### Behavioral Transition Recording

All behavioral transitions are recorded in PackContext:
- Source agent behavior event
- Target agent behavior event
- Compatibility metrics
- Risk assessment
- Timestamp

---

## Pack-Level Behavioral Evolution

### Behavior Adjustment

Agents can adjust their behavioral tendencies based on:
- PackContext events
- Handoff outcomes
- Emotional state changes
- External feedback

**Adjustment Delta:**
- Range: -1.0 to 1.0
- Positive delta: Increase tendency intensity
- Negative delta: Decrease tendency intensity

### Behavior History

PackContext maintains behavior history for each agent:
- Last 100 events per agent
- Event types: behavioral_transition, adjustment, etc.
- Timestamped for temporal analysis

### Future Enhancements

Planned enhancements for Pack behavioral evolution:
- Machine learning-based behavior prediction
- Adaptive behavior adjustment based on outcomes
- Multi-agent behavioral coordination
- Behavioral pattern recognition

---

## PHP Implementation

### Core Engine

**Location:** `lupo-includes/Pack/Behavior/PackBehaviorEngine.php`

**Key Methods:**
- `determineBehavior($agentId, $emotionVector, $context)` - Determines behavior label
- `adjustBehavior($agentId, $delta)` - Adjusts behavioral tendency
- `getBehaviorProfile($agentId, $emotionVector)` - Gets behavior profile
- `recordBehaviorEvent($agentId, $event)` - Records behavior event

### Pack Context Integration

**Location:** `lupo-includes/Pack/PackContext.php`

**Key Methods:**
- `setBehaviorProfile($agentId, $profile)` - Stores behavior profile
- `getBehaviorProfile($agentId)` - Retrieves behavior profile
- `recordBehaviorEvent($agentId, $event)` - Records behavior event
- `getBehaviorHistory($agentId, $limit)` - Gets behavior history

### Handoff Protocol Integration

**Location:** `lupo-includes/Pack/PackHandoffProtocol.php`

**Key Methods:**
- `evaluateBehavioralCompatibility($fromAgent, $toAgent)` - Evaluates compatibility
- `recordBehavioralTransition($fromAgent, $toAgent)` - Records transition

### Service Layer

**Location:** `app/Services/Pack/PackBehaviorService.php`

**Key Methods:**
- `getProfile($agentId)` - Gets behavior profile
- `setProfile($agentId, $profile)` - Sets behavior profile
- `determine($agentId, $emotionVector, $context)` - Determines behavior
- `compatibility($fromAgent, $toAgent)` - Gets compatibility

### API Endpoints

**Routes:** `routes/pack_behavior.php`

- `/pack/behavior/profile` (GET/POST) - Get behavior profile
- `/pack/behavior/compatibility` (GET/POST) - Get behavioral compatibility

**Controller:** `app/Http/Controllers/PackBehaviorController.php`

---

## Integration with Emotional Geometry

The Pack Behavioral Layer integrates seamlessly with the Emotional Geometry system (4.0.108):

1. **Emotional Vectors** → **Behavior Profiles**: Emotional geometry determines behavior tendency
2. **Emotional Affinity** → **Behavioral Compatibility**: Affinity influences compatibility score
3. **Emotional Intensity** → **Behavior Intensity**: Intensity affects behavior determination

**Bridge Formula:**
```
behavior = f(emotional_geometry)
compatibility = g(behavior_profiles, emotional_affinity)
```

---

## Usage Examples

### Determining Behavior

```php
use App\Services\Pack\PackBehaviorService;

$service = new PackBehaviorService();
$emotionVector = ['r' => 5000, 'g' => -3000, 'b' => 0];
$behavior = $service->determine('TerminalAI_001', $emotionVector);
// Returns: "supportive"
```

### Getting Compatibility

```php
$compatibility = $service->compatibility('TerminalAI_001', 'TerminalAI_005');
// Returns: ['compatibility' => 0.75, 'risk' => 'low', ...]
```

### Getting Profile

```php
$profile = $service->getProfile('TerminalAI_001');
// Returns: ['agent_id' => 'TerminalAI_001', 'tendency' => 'supportive', ...]
```

---

## Related Documentation

- **[EMOTIONAL_GEOMETRY.md](docs/doctrine/EMOTIONAL_GEOMETRY.md)** - Emotional geometry doctrine (4.0.108)
- **[PACK_BEHAVIOR_MATRIX_v4_0_90.md](docs/doctrine/PACK_BEHAVIOR_MATRIX_v4_0_90.md)** - Pack behavior matrix (historical)

---

**Pack Behavioral Layer Status:** ACTIVE as of Version 4.0.109. Fully integrated with Pack Architecture and Emotional Geometry systems.
