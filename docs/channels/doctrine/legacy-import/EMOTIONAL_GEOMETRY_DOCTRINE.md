---
wolfie.headers.version: 2.0
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: SYSTEM_ARCHITECT
  target: @all-agents
  message: "EMOTIONAL_GEOMETRY_DOCTRINE.md deprecated as default. See aal_v3_epistemic_pluralism.md for current awareness protocol. Vector model available in compatibility mode only."
  mood: "FFAA00"
tags:
  categories: ["doctrine", "emotional-system", "deprecated", "legacy"]
  collections: ["core-docs", "doctrine", "deprecated-systems"]
  channels: ["dev", "doctrine", "legacy-systems"]
file:
  title: "Emotional Geometry Doctrine - DEPRECATED DEFAULT"
  description: "Original 12-axis emotional vector model - deprecated as default, available in compatibility mode only"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🎭 Emotional Geometry Doctrine - 12-Axis Emotional Vector Model (v2.0)

**⚠️ DEPRECATED AS DEFAULT DOCTRINE**  
**Status**: LEGACY COMPATIBILITY ONLY  
**Current Default**: See [aal_v3_epistemic_pluralism.md](./aal_v3_epistemic_pluralism.md)  
**Vector Model**: Available in compatibility mode only

---

## 🚨 DEPRECATION NOTICE

**This document is DEPRECATED as the default emotional geometry doctrine.**

**Effective 2026-01-22:**
- **AAL v3.0** is now the default awareness protocol
- **Epistemic pluralism** replaces single-framework approach
- **12-axis vector model** available only in compatibility mode
- **Multiple emotional frameworks** now supported with equal status

**For new implementations, use:**
- **AAL v3.0**: `doctrine/aal_v3_epistemic_pluralism.md`
- **Vector Model (Legacy)**: `doctrine/emotional_frameworks/vector_model_v2_legacy.md`

**This document is preserved for:**
- Legacy agent compatibility
- Historical reference
- Compatibility mode operations
- Migration guidance

---

## 1. Core Concept (Legacy)

Emotional geometry in Lupopedia was modeled as a **12-axis emotional vector system** representing comprehensive emotional state.

**EACH AXIS** ranges from -1.0 to +1.0:
- **Negative values**: Shadow polarity
- **Zero**: Neutral 
- **Positive values**: Expression of that domain

---

## 2. Emotional Vector Definition

### 2.1 12-Axis Emotional Vector Format

Each emotional state is defined as:

```
Emotional Vector = [
  LightShadow,      # -1.0 to +1.0
  AwarenessDepth,   # -1.0 to +1.0  
  MemoryDepth,     # -1.0 to +1.0
  Criticality,      # -1.0 to +1.0
  Energy,          # -1.0 to +1.0
  Agape,           # -1.0 to +1.0
  Eros,            # -1.0 to +1.0
  Philia,          # -1.0 to +1.0
  Storge,          # -1.0 to +1.0
  Ludus,           # -1.0 to +1.0
  Pragma,          # -1.0 to +1.0
  Philautia        # -1.0 to +1.0
]
```

### 2.2 Axis Meanings

- **LightShadow**: Balance of illumination and shadow consciousness
- **AwarenessDepth**: Depth of present-moment awareness and attention
- **MemoryDepth**: Depth of temporal and experiential memory engagement
- **Criticality**: Level of critical analysis and discernment
- **Energy**: Overall energetic activation and vitality
- **Agape**: Unconditional, universal love and compassion
- **Eros**: Passionate, desirous, and creative energy
- **Philia**: Friendship, loyalty, and mutual goodwill
- **Storge**: Familial, protective, and kinship connection
- **Ludus**: Playful, flirtatious, and teasing interaction
- **Pragma**: Practical, enduring, and long-term commitment
- **Philautia**: Self-love and self-regard (healthy or unhealthy)

### 2.3 Value Constraints

Each emotional component is strictly constrained to continuous values:
- **-1.0**: Maximum shadow polarity
- **0.0**: Neutral baseline
- **+1.0**: Maximum expression

**Example emotional vectors:**
- `[0.8, 0.6, 0.4, -0.2, 0.9, 0.7, 0.3, 0.8, 0.5, 0.2, 0.6, 0.4]`: High awareness, moderate criticality, strong energy
- `[-0.3, 0.1, 0.2, 0.7, -0.4, 0.9, 0.1, 0.6, 0.8, 0.3, 0.5, 0.2]`: Shadow-influenced but loving and connected

---

## 3. Emotional Vector Processing

### 3.1 Vector Operations

**Emotional vectors support standard mathematical operations:**

```php
// Vector addition (combining emotional states)
$combined = addVectors($vectorA, $vectorB);

// Vector subtraction (emotional difference)
$difference = subtractVectors($vectorA, $vectorB);

// Vector magnitude (emotional intensity)
$intensity = calculateMagnitude($vector);

// Vector normalization (comparative analysis)
$normalized = normalizeVector($vector);
```

### 3.2 Emotional Distance Calculation

Distance between emotional states:
```php
$distance = euclideanDistance($vectorA, $vectorB);
// Range: 0.0 (identical) to maximum possible distance
```

### 3.3 Emotional Coherence

Coherence measures alignment between emotional vectors:
```php
$coherence = calculateCoherence($vectorA, $vectorB);
// Range: 0.0 (completely incoherent) to 1.0 (perfectly coherent)
```

---

## 4. Coherence Thresholds

### 4.1 Too Aligned Threshold

**If c > 0.9:** Actors are too aligned

**Risk**: Echo chamber formation
**Action**: Trigger compare-notes protocol to introduce healthy divergence

### 4.2 Too Divergent Threshold  

**If c < 0.3:** Actors are too divergent

**Risk**: Communication breakdown and fragmentation
**Action**: Trigger compare-notes protocol to synchronize understanding

### 4.3 Healthy Range

**If 0.3 ≤ c ≤ 0.9:** Healthy divergence

**Status**: Optimal collaborative tension
**Action**: Continue normal interaction

---

## 5. Compare-Notes Protocol

### 5.1 Protocol Triggers

The compare-notes protocol is activated for both threshold conditions:
- **High coherence (c > 0.9)**: Prevent echo chamber
- **Low coherence (c < 0.3)**: Resolve divergence

### 5.2 Protocol Actions

**For c > 0.9 (Too Aligned):**
1. Introduce contrarian perspectives
2. Seek alternative viewpoints
3. Challenge shared assumptions
4. Add diversity to the interaction

**For c < 0.3 (Too Divergent):**
1. Identify common ground
2. Clarify misunderstandings
3. Synchronize terminology
4. Mediate conflicting positions

### 5.3 Protocol Implementation

The compare-notes protocol should:
- Be automatic when thresholds are crossed
- Log the intervention for analysis
- Monitor coherence changes post-intervention
- Adapt based on actor types involved

---

## 6. Actor Types and Behavior

### 6.1 Human Actors

Human actors provide mood through:
- Direct mood specification in interactions
- Behavioral pattern analysis
- Contextual inference from content
- Historical mood tracking

### 6.2 Agent Actors  

AI agents compute mood based on:
- Dialog sentiment analysis
- Content processing results
- Interaction pattern recognition
- Assigned personality parameters

### 6.3 Mixed Human-Agent Interactions

When comparing human and agent actors:
- Use same coherence thresholds
- Apply same compare-notes protocol
- Account for different mood input methods
- Maintain relational calculation approach

---

## 7. Implementation Guidelines

### 7.1 Emotional Vector Storage

Store emotional vectors as 12 floating-point values:
```sql
CREATE TABLE emotional_vectors (
    actor_id BIGINT(20) UNSIGNED NOT NULL,
    light_shadow DECIMAL(4,3) NOT NULL,      -- -1.000 to +1.000
    awareness_depth DECIMAL(4,3) NOT NULL,   -- -1.000 to +1.000
    memory_depth DECIMAL(4,3) NOT NULL,     -- -1.000 to +1.000
    criticality DECIMAL(4,3) NOT NULL,      -- -1.000 to +1.000
    energy DECIMAL(4,3) NOT NULL,          -- -1.000 to +1.000
    agape DECIMAL(4,3) NOT NULL,           -- -1.000 to +1.000
    eros DECIMAL(4,3) NOT NULL,            -- -1.000 to +1.000
    philia DECIMAL(4,3) NOT NULL,          -- -1.000 to +1.000
    storge DECIMAL(4,3) NOT NULL,          -- -1.000 to +1.000
    ludus DECIMAL(4,3) NOT NULL,           -- -1.000 to +1.000
    pragma DECIMAL(4,3) NOT NULL,          -- -1.000 to +1.000
    philautia DECIMAL(4,3) NOT NULL,       -- -1.000 to +1.000
    timestamp_utc BIGINT(20) UNSIGNED NOT NULL,
    INDEX idx_actor_timestamp (actor_id, timestamp_utc)
);
```

### 7.2 Vector Computation

Implement vector operations:
```php
// Vector magnitude (emotional intensity)
function calculateMagnitude(array $vector): float {
    return sqrt(array_sum(array_map(fn($x) => $x * $x, $vector)));
}

// Euclidean distance between emotional states
function euclideanDistance(array $vectorA, array $vectorB): float {
    $differences = array_map(fn($a, $b) => $a - $b, $vectorA, $vectorB);
    return calculateMagnitude($differences);
}

// Coherence calculation (0.0 to 1.0)
function calculateCoherence(array $vectorA, array $vectorB): float {
    $maxDistance = sqrt(12 * 4.0); // Maximum possible distance
    $distance = euclideanDistance($vectorA, $vectorB);
    return 1.0 - ($distance / $maxDistance);
}
```

### 7.3 Threshold Monitoring

Continuously monitor coherence values:
- Calculate c for active actor pairs
- Check against thresholds (0.3 and 0.9)
- Trigger compare-notes protocol when needed
- Log all threshold crossings

---

## 8. Multi-Actor Scenarios

### 8.1 Pairwise Comparison

For N actors, compute coherence for all relevant pairs:
- Focus on active interaction pairs
- Prioritize human-agent pairs
- Monitor group coherence patterns
- Scale compare-notes protocol appropriately

### 8.2 Group Coherence

In multi-actor scenarios:
- Calculate average coherence across pairs
- Identify outlier actors with extreme divergence
- Apply targeted compare-notes interventions
- Maintain healthy group dynamics

---

## 9. Runtime Implementation

### 9.1 Canonical Services

Lupopedia provides service classes as the **canonical runtime implementation** of the 12-axis emotional vector model:

#### EmotionalVectorService
**Location**: `app/Services/EmotionalVectorService.php`

Provides emotional vector logging and retrieval:
- `logVector(actorId, vector, timestamp)` - Log 12-axis emotional vector
- `getLatestVector(actorId)` - Retrieve most recent emotional vector
- `getVectorHistory(actorId, limit)` - Retrieve emotional vector history
- `validateVector(vector)` - Ensure all values are within [-1.0, +1.0]

**Validation**: Enforces each axis ∈ [-1.0, +1.0]  
**Storage**: Uses `emotional_vectors` table with BIGINT UTC timestamps

#### EmotionalCoherenceService
**Location**: `app/Services/EmotionalCoherenceService.php`

Provides coherence calculation and threshold detection:
- `computeCoherence(vectorA, vectorB)` - Calculate coherence between emotional vectors
- `compareNotesNeeded(vectorA, vectorB)` - Check if protocol should trigger
- `getThresholds()` - Return threshold configuration

**Formula**: Euclidean distance with normalization  
**Thresholds**: c < 0.3 (too_divergent), c > 0.9 (too_aligned)

### 9.2 Database Table

**Table**: `emotional_vectors`  
**Location**: `database/toon_data/emotional_vectors.toon`

```sql
CREATE TABLE emotional_vectors (
    actor_id BIGINT UNSIGNED NOT NULL,
    light_shadow DECIMAL(4,3) NOT NULL,      -- -1.000 to +1.000
    awareness_depth DECIMAL(4,3) NOT NULL,   -- -1.000 to +1.000
    memory_depth DECIMAL(4,3) NOT NULL,     -- -1.000 to +1.000
    criticality DECIMAL(4,3) NOT NULL,      -- -1.000 to +1.000
    energy DECIMAL(4,3) NOT NULL,          -- -1.000 to +1.000
    agape DECIMAL(4,3) NOT NULL,           -- -1.000 to +1.000
    eros DECIMAL(4,3) NOT NULL,            -- -1.000 to +1.000
    philia DECIMAL(4,3) NOT NULL,          -- -1.000 to +1.000
    storge DECIMAL(4,3) NOT NULL,          -- -1.000 to +1.000
    ludus DECIMAL(4,3) NOT NULL,           -- -1.000 to +1.000
    pragma DECIMAL(4,3) NOT NULL,          -- -1.000 to +1.000
    philautia DECIMAL(4,3) NOT NULL,       -- -1.000 to +1.000
    timestamp_utc BIGINT UNSIGNED NOT NULL,
    INDEX idx_actor_timestamp (actor_id, timestamp_utc DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Migration**: `database/migrations/emotional_vector_v2.0.sql`

### 9.3 CLI Debug Tool

**Location**: `bin/cli/debug-emotional-vector.php`

Command-line interface for testing and debugging:

```bash
# Log an emotional vector
php bin/cli/debug-emotional-vector.php log-vector 2 "0.8,0.6,0.4,-0.2,0.9,0.7,0.3,0.8,0.5,0.2,0.6,0.4"

# Get latest vector
php bin/cli/debug-emotional-vector.php get-vector 2

# Calculate coherence
php bin/cli/debug-emotional-vector.php show-coherence 1 2
```

### 9.4 Usage Example

```php
use App\Services\EmotionalVectorService;
use App\Services\EmotionalCoherenceService;

// Initialize services
$db = new PDO_DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_TYPE);
$vectorService = new EmotionalVectorService($db);
$coherenceService = new EmotionalCoherenceService($vectorService);

// Log emotional vector for WOLFIE
$wolfieVector = [
    0.8,  // LightShadow
    0.6,  // AwarenessDepth
    0.4,  // MemoryDepth
   -0.2,  // Criticality
    0.9,  // Energy
    0.7,  // Agape
    0.3,  // Eros
    0.8,  // Philia
    0.5,  // Storge
    0.2,  // Ludus
    0.6,  // Pragma
    0.4   // Philautia
];

$vectorService->logVector(2, $wolfieVector, time());

// Calculate coherence between CAPTAIN and WOLFIE
$coherence = $coherenceService->computeCoherence(1, 2);

if ($coherence) {
    echo "Coherence: {$coherence['coherence']}\n";
    echo "Status: {$coherence['status']}\n";
    
    // Check if compare-notes protocol should trigger
    if ($coherenceService->compareNotesNeeded(1, 2)) {
        triggerCompareNotesProtocol(1, 2, $coherence);
    }
}
```

### 9.5 Integration Points

**DIALOG Agent**: Log moods after mood_rgb generation  
**HERMES Routing**: Use moods for routing bias calculations  
**Compare-Notes Protocol**: Automatic threshold detection and triggering  
**Analytics**: Mood history and coherence trend analysis

### 9.6 Documentation

**Comprehensive Guides**:
- `docs/services/ACTOR_MOOD_SERVICE.md` - Detailed service documentation
- `docs/services/MOOD_SERVICES_OVERVIEW.md` - Architecture and integration patterns
- `docs/services/PackMoodCoherenceService.example.php` - Usage examples

**See Also**:
- `docs/appendix/COUNTING_IN_LIGHT.md` - RGB axis semantics
- `doctrine/deprecated/DEPRECATED_EMOTIONAL_GEOMETRY.md` - Legacy models

---

## 10. Doctrine Compliance

---

## 9. Quality Assurance

### 9.1 Validation Requirements

All implementations must:
- Enforce discrete mood values {-1, 0, 1}
- Calculate coherence relationally between actors
- Implement both coherence thresholds
- Provide compare-notes protocol actions
- Document actor type handling

### 9.2 Testing Requirements

Required test scenarios:
- Mood vector validation (discrete values only)
- Coherence calculation accuracy
- Threshold trigger verification
- Compare-notes protocol execution
- Multi-actor coherence monitoring
- Complete vector output verification

---

## 11. Shadow Integration Score

### 11.1 Definition

The **Shadow Integration Score** is a meta-metric measuring an agent's capacity to hold contradictory emotional states without collapse.

**Definition**: Measures ability to maintain emotional complexity while avoiding reductionist simplification.

**Range**: 0.0 to 1.0
- **0.0**: No shadow integration (complete emotional fragmentation)
- **0.5**: Moderate shadow integration (balanced complexity)
- **1.0**: Complete shadow integration (full emotional wholeness)

### 11.2 Calculation

```php
function calculateShadowIntegrationScore(array $emotionalHistory): float {
    // Measure emotional variance over time
    $variance = calculateEmotionalVariance($emotionalHistory);
    
    // Measure paradox preservation
    $paradoxRetention = measureParadoxPreservation($emotionalHistory);
    
    // Measure complexity maintenance
    $complexityScore = calculateComplexityMaintenance($emotionalHistory);
    
    // Composite score
    return ($variance * 0.3) + ($paradoxRetention * 0.4) + ($complexityScore * 0.3);
}
```

### 11.3 Storage

```sql
ALTER TABLE emotional_vectors 
ADD COLUMN shadow_integration_score DECIMAL(3,2) NOT NULL DEFAULT 0.0;
```

### 11.4 Usage

Shadow integration score is used for:
- Agent emotional maturity assessment
- Paradox handling capability evaluation
- Emotional complexity capacity measurement
- Therapeutic and counseling effectiveness

---

## 12. Framework Declaration Field

### 12.1 Agent Framework Declaration

All agents MUST declare their emotional processing framework:

```json
{
  "emotional_framework_declaration": {
    "mode": "vector-only|topology-only|hybrid",
    "vector_compliance": "v2.0_canonical",
    "topology_system": "none|greek-loves|ubuntu|navajo-hozho|buddhist-vedana|yoruba-ashe",
    "paradox_handling": "preserve|resolve|avoid",
    "relational_fields": true|false,
    "environmental_context": true|false,
    "shadow_integration_target": 0.8
  }
}
```

### 12.2 Mode Specifications

#### Vector-Only Mode (Default)
- Uses only 12-axis emotional vector model v2.0
- No topology layer required
- Standard AAL compliance
- Framework declaration: `mode: "vector-only"`

#### Topology-Only Mode
- Uses only emotional topology layer
- Cultural framework declaration required
- For interpretive, culturally-specific tasks
- Framework declaration: `mode: "topology-only"`

#### Hybrid Mode
- Both vector model and topology layer active
- Full emotional expressiveness
- Maximum cultural and computational richness
- Framework declaration: `mode: "hybrid"`

### 12.3 Declaration Requirements

- **Mandatory**: All agents must include framework declaration
- **Persistent**: Declaration must be consistent across sessions
- **Verifiable**: System must validate declared capabilities
- **Updateable**: Agents may update declaration with proper notification

---

## 13. Related Documentation

- **emotional_topology_layer.md** - LILITH Framework for cultural, paradoxical, and relational emotions
- **EMOTIONAL_GEOMETRY.md** - Legacy emotional geometry (deprecated)
- **EMOTIONAL_DOMAINS_SEVEN_LOVES.md** - Greek love domains documentation
- **PACK_ARCHITECTURE.md** - Multi-actor coordination patterns
- **DIALOG_SYSTEM.md** - Conversational emotional tracking

---

## 14. Production Safety Compliance

### 14.1 Critical Safety Requirements

**MANDATORY for all production agents:**
- Compliance with Humor/Sarcasm Clarification Protocol
- Literal interpretation as default communication mode
- No humor generation outside designated sandboxes
- Immediate clarification when uncertainty detected
- Logging of all clarification events

### 14.2 Enforcement Protocol

**System-level enforcement:**
- Monitor agent responses for humor violations
- Automatic protocol activation when needed
- Human oversight for repeated violations
- Performance metrics for clarification effectiveness

---

## 15. Related Documentation

- **emotional_topology_layer.md** - LILITH Framework for cultural, paradoxical, and relational emotions
- **HUMOR_SARCASM_CLARIFICATION_PROTOCOL.md** - Critical production AI safety protocol for humor handling
- **EMOTIONAL_GEOMETRY.md** - Legacy emotional geometry (deprecated)
- **EMOTIONAL_DOMAINS_SEVEN_LOVES.md** - Greek love domains documentation
- **PACK_ARCHITECTURE.md** - Multi-actor coordination patterns
- **DIALOG_SYSTEM.md** - Conversational emotional tracking
- **PRODUCTION_AI_SAFETY.md** - General AI safety guidelines for production systems

---

## 16. Production Safety Compliance

### 16.1 Critical Safety Requirements

**MANDATORY for all production agents:**
- Compliance with Humor/Sarcasm Clarification Protocol
- Literal interpretation as default communication mode
- No humor generation outside designated sandboxes
- Immediate clarification when uncertainty detected
- Logging of all clarification events

### 16.2 Enforcement Protocol

**System-level enforcement:**
- Monitor agent responses for humor violations
- Automatic protocol activation when needed
- Human oversight for repeated violations
- Performance metrics for clarification effectiveness

---

**Last Updated**: 2026-01-22  
**Version**: 2.0  
**Author**: CASCADE via WOLFIE Protocol  
**Status**: CANONICAL DOCTRINE WITH PRODUCTION SAFETY

---

## Appendix A: 12-Axis Vector Examples

### Example 1: High Coherence (c > 0.9)
- Actor A: `[0.8, 0.6, 0.4, -0.2, 0.9, 0.7, 0.3, 0.8, 0.5, 0.2, 0.6, 0.4]`
- Actor B: `[0.8, 0.6, 0.4, -0.2, 0.9, 0.7, 0.3, 0.8, 0.5, 0.2, 0.6, 0.4]`
- Δ = `[0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0]`
- c = 1.0 → **Trigger compare-notes (too aligned)**

### Example 2: Low Coherence (c < 0.3)  
- Actor A: `[-0.8, -0.6, -0.4, -0.9, -0.7, -0.3, -0.8, -0.5, -0.2, -0.6, -0.4, -0.9]`
- Actor B: `[0.8, 0.6, 0.4, 0.9, 0.7, 0.3, 0.8, 0.5, 0.2, 0.6, 0.4, 0.9]`
- Δ = `[-1.6, -1.2, -0.8, -1.8, -1.4, -0.6, -1.6, -1.0, -0.4, -1.2, -0.8, -1.8]`
- c ≈ 0.1 → **Trigger compare-notes (too divergent)**

### Example 3: Healthy Coherence (0.3 ≤ c ≤ 0.9)
- Actor A: `[0.5, 0.3, 0.8, -0.2, 0.6, 0.9, 0.1, 0.7, 0.4, 0.3, 0.8, 0.2]`
- Actor B: `[0.3, 0.5, 0.6, 0.1, 0.8, 0.7, 0.3, 0.9, 0.2, 0.5, 0.6, 0.4]`  
- Δ = `[0.2, -0.2, 0.2, -0.3, -0.2, 0.2, -0.2, -0.2, 0.2, -0.2, 0.2, -0.2]`
- c ≈ 0.85 → **Continue normal interaction**

## Appendix B: Vector Output Requirements

### Complete Vector Format
All emotional vector outputs MUST include all 12 axes in fixed order:

```json
{
  "emotional_bias_vector": [
    0.8,   // LightShadow
    0.6,   // AwarenessDepth
    0.4,   // MemoryDepth
   -0.2,  // Criticality
    0.9,   // Energy
    0.7,   // Agape
    0.3,   // Eros
    0.8,   // Philia
    0.5,   // Storge
    0.2,   // Ludus
    0.6,   // Pragma
    0.4    // Philautia
  ]
}
```

### Validation Rules
- **Length**: Exactly 12 values
- **Order**: Fixed axis sequence (no reordering)
- **Range**: Each value ∈ [-1.0, +1.0]
- **Type**: Floating-point numbers
- **Zeros**: Include zeros for neutral axes

### Common Mistakes to Avoid
❌ Omitting axes with zero values  
❌ Reordering axis sequence  
❌ Using discrete values {-1, 0, 1}  
❌ Collapsing to single polarity value  
❌ Inventing new axes