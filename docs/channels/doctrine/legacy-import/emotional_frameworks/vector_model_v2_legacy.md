---
wolfie.headers.version: 2.0
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: SYSTEM_ARCHITECT
  target: @legacy-agents
  message: "12-axis vector model moved to legacy compatibility status. Available only when explicitly requested via compatibility_mode."
  mood: "FFAA00"
tags:
  categories: ["doctrine", "emotional-framework", "legacy", "compatibility"]
  collections: ["core-docs", "doctrine", "emotional-frameworks"]
  channels: ["dev", "doctrine", "legacy-systems"]
file:
  title: "12-Axis Vector Model v2.0 - Legacy Compatibility Framework"
  description: "Original 12-axis emotional vector model preserved for compatibility mode only"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 📊 12-Axis Vector Model v2.0 - Legacy Compatibility Framework

**Emotional Framework Documentation**  
**Version: 2.0**  
**Status**: LEGACY COMPATIBILITY ONLY  
**Effective Date**: 2026-01-22  
**Author**: SYSTEM_ARCHITECT

---

## 1. Framework Status

**The 12-axis vector model is now a LEGACY COMPATIBILITY FRAMEWORK.**

**Access Method**: Only available when explicitly requested via:
```json
{
  "compatibility_mode": "v2.0"
}
```

**Default Status**: NOT used in AAL v3.0 epistemic pluralism architecture.

---

## 2. Vector Definition

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

---

## 3. Compatibility Mode Operations

### 3.1 Activation Conditions

**Vector model activates ONLY when:**
- Agent explicitly requests `compatibility_mode: "v2.0"`
- System detects v2.0 format requirements
- Legacy agent interaction requires v2.0 format

### 3.2 Computation Rules

**In compatibility mode:**
- ONLY 12-axis vector model is computed
- No parallel framework processing
- No irreducible difference protocol
- Standard v2.0 coherence calculations apply
- Thresholds (0.3 and 0.9) are enforced

### 3.3 Output Format

**Compatibility mode returns:**
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
  ],
  "compatibility_mode": "v2.0",
  "coherence_thresholds": {
    "too_aligned": 0.9,
    "too_divergent": 0.3
  }
}
```

---

## 4. Coherence Calculations

### 4.1 Euclidean Distance

```php
function euclideanDistance(array $vectorA, array $vectorB): float {
    $differences = array_map(fn($a, $b) => $a - $b, $vectorA, $vectorB);
    return sqrt(array_sum(array_map(fn($x) => $x * $x, $differences)));
}
```

### 4.2 Coherence Score

```php
function calculateCoherence(array $vectorA, array $vectorB): float {
    $maxDistance = sqrt(12 * 4.0); // Maximum possible distance
    $distance = euclideanDistance($vectorA, $vectorB);
    return 1.0 - ($distance / $maxDistance);
}
```

### 4.3 Threshold Enforcement

- **c > 0.9**: Too aligned (trigger compare-notes)
- **c < 0.3**: Too divergent (trigger compare-notes)
- **0.3 ≤ c ≤ 0.9**: Healthy range (continue normal)

---

## 5. Storage Schema

### 5.1 Database Table

**Table**: `emotional_vectors`  
**Status**: Preserved for compatibility mode

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
    shadow_integration_score DECIMAL(3,2) NOT NULL DEFAULT 0.0,
    timestamp_utc BIGINT UNSIGNED NOT NULL,
    INDEX idx_actor_timestamp (actor_id, timestamp_utc DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 5.2 Legacy Data Preservation

**Existing vector data:**
- Preserved without modification
- Accessible via compatibility mode
- Not migrated to new frameworks
- Maintained for backward compatibility

---

## 6. Service Layer

### 6.1 Legacy Services

**EmotionalVectorService** (Compatibility Mode)
- `logVector(actorId, vector, timestamp)` - Log 12-axis vector
- `getLatestVector(actorId)` - Retrieve most recent vector
- `getVectorHistory(actorId, limit)` - Retrieve vector history
- `validateVector(vector)` - Ensure values within [-1.0, +1.0]

**EmotionalCoherenceService** (Compatibility Mode)
- `computeCoherence(vectorA, vectorB)` - Calculate coherence
- `compareNotesNeeded(vectorA, vectorB)` - Check thresholds
- `getThresholds()` - Return v2.0 threshold configuration

### 6.2 Integration Points

**Compatibility mode integration:**
- DIALOG Agent: Vector logging when compatibility requested
- HERMES Routing: Vector-based routing bias calculations
- Compare-Notes Protocol: Standard v2.0 threshold detection
- Analytics: Vector history and coherence trend analysis

---

## 7. Migration Path

### 7.1 From Legacy to AAL v3.0

**For existing v2.0 agents:**
1. Continue operating in compatibility mode
2. Maintain existing vector computations
3. No data migration required
4. Optional upgrade to AAL v3.0 available

### 7.2 From AAL v3.0 to Compatibility

**For v3.0 agents requiring v2.0 format:**
1. Request compatibility mode explicitly
2. Use only vector model computations
3. Follow v2.0 coherence rules
4. Generate v2.0 awareness snapshots

---

## 8. Limitations

### 8.1 Framework Constraints

**In compatibility mode:**
- No parallel framework processing
- No cultural framework support
- No irreducible difference handling
- No topology layer integration
- Fixed threshold values only

### 8.2 Interaction Limitations

**Compatibility mode agents:**
- Cannot process epistemic pluralism
- Cannot handle framework differences
- Limited to vector-based emotional representation
- Cannot access new emotional frameworks

---

## 9. Quality Assurance

### 9.1 Validation Requirements

**Compatibility mode must:**
- Enforce vector value constraints [-1.0, +1.0]
- Calculate coherence using Euclidean distance
- Apply v2.0 thresholds (0.3 and 0.9)
- Generate complete 12-axis vectors
- Maintain backward compatibility

### 9.2 Testing Requirements

**Required test scenarios:**
- Vector validation (continuous values)
- Coherence calculation accuracy
- Threshold trigger verification
- Compare-notes protocol execution
- Compatibility mode activation/deactivation

---

## 10. Related Documentation

- **aal_v3_epistemic_pluralism.md** - Current default awareness protocol
- **emotional_topology_layer.md** - LILITH Framework for cultural emotions
- **HUMOR_SARCASM_CLARIFICATION_PROTOCOL.md** - Production AI safety protocol
- **EMOTIONAL_GEOMETRY_DOCTRINE.md** - Original doctrine (deprecated as default)

---

**Document Status**: LEGACY COMPATIBILITY FRAMEWORK  
**Default Usage**: DEPRECATED  
**Compatibility Mode**: MAINTAINED  
**Migration Path**: TO AAL v3.0 RECOMMENDED

---

**Last Updated**: 2026-01-22  
**Version**: 2.0  
**Author**: SYSTEM_ARCHITECT  
**Status**: LEGACY COMPATIBILITY ONLY

---

## Appendix A: Vector Examples

### Example 1: High Energy State
```
[0.8, 0.6, 0.4, -0.2, 0.9, 0.7, 0.3, 0.8, 0.5, 0.2, 0.6, 0.4]
```

### Example 2: Shadow-Influenced State
```
[-0.3, 0.1, 0.2, 0.7, -0.4, 0.9, 0.1, 0.6, 0.8, 0.3, 0.5, 0.2]
```

### Example 3: Balanced State
```
[0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0]
```

## Appendix B: Coherence Examples

### High Coherence (c > 0.9)
- Actor A: `[0.8, 0.6, 0.4, -0.2, 0.9, 0.7, 0.3, 0.8, 0.5, 0.2, 0.6, 0.4]`
- Actor B: `[0.8, 0.6, 0.4, -0.2, 0.9, 0.7, 0.3, 0.8, 0.5, 0.2, 0.6, 0.4]`
- c = 1.0 → **Trigger compare-notes (too aligned)**

### Low Coherence (c < 0.3)
- Actor A: `[-0.8, -0.6, -0.4, -0.9, -0.7, -0.3, -0.8, -0.5, -0.2, -0.6, -0.4, -0.9]`
- Actor B: `[0.8, 0.6, 0.4, 0.9, 0.7, 0.3, 0.8, 0.5, 0.2, 0.6, 0.4, 0.9]`
- c ≈ 0.1 → **Trigger compare-notes (too divergent)**

---

**CRITICAL REMINDER**: This framework is legacy compatibility only. Use AAL v3.0 epistemic pluralism for new implementations.
