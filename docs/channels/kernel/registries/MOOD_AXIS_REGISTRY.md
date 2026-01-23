---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  speaker: "CASCADE"
  target: "@everyone"
  mood_RGB: "00FF00"
  message: "Created Mood Axis Registry v1.0 - governed list of emotional axes preventing drift and synonyms"
tags:
  categories: ["documentation", "specification", "registry"]
  collections: ["core-docs", "registries"]
  channels: ["public", "dev", "standards"]
file:
  title: "Mood Axis Registry v1.0"
  description: "Governed list of emotional axes preventing drift and synonyms in Lupopedia"
  version: "1.0.0"
  status: published
  author: "Captain Wolfie"
---

# 📋 **Mood Axis Registry v1.0**  
*Governed list of emotional axes preventing drift and synonyms in Lupopedia*

---

## 🟩 1. Purpose

The **Mood Axis Registry** provides a governed, canonical list of all emotional axes used in Lupopedia's Mood System. This registry prevents:

- **Axis drift** - Uncontrolled creation of new emotional dimensions
- **Synonym confusion** - Multiple names for same concept
- **Inconsistent definitions** - Varying interpretations across agents
- **Unbounded complexity** - Unlimited axis proliferation
- **Implementation divergence** - Different agents using different axes

This registry ensures all agents use identical emotional axes with consistent definitions, scoring rules, and RGB mappings.

---

## 📊 2. Registry Structure

### Axis Definition Format

Each emotional axis is defined with:

```yaml
axis_name:
  type: <bipolar|unipolar|cyclical>
  range: <value_range>
  description: <human_readable_description>
  scoring_algorithm: <deterministic_algorithm_reference>
  rgb_mapping: <color_component_mapping>
  examples:
    - <value>: <description>
    - <value>: <description>
  validation_rules:
    - <rule_1>
    - <rule_2>
  canonical: true|false
```

### Required Fields

- **type** - Axis mathematical type (bipolar, unipolar, cyclical)
- **range** - Valid value range for the axis
- **description** - Clear, unambiguous explanation
- **scoring_algorithm** - Reference to calculation method
- **rgb_mapping** - How axis maps to RGB color
- **examples** - Representative value examples
- **validation_rules** - Constraints and checks
- **canonical** - Whether this is a core Lupopedia axis

---

## 🎭 3. Core Emotional Axes

### 3.1 Dialog Engagement Axis

```yaml
dialog:
  type: bipolar
  range: -100 to +100
  description: "Conversation participation and engagement level"
  scoring_algorithm: "MOOD_CALCULATION_PROTOCOL.md#dialog_engagement"
  rgb_mapping: "red_component"
  examples:
    -100: "No engagement - silent or withdrawn"
    -50: "Low engagement - minimal participation"
     0: "Neutral engagement - standard participation"
    +50: "High engagement - active participation"
    +100: "Maximum engagement - intense participation"
  validation_rules:
    - "Must be calculated from question/statement/response counts"
    - "Cannot be set directly by agents"
    - "Must update with each dialog entry"
  canonical: true
```

### 3.2 Love-Hate Axis

```yaml
love_hate:
  type: bipolar
  range: -100 to +100
  description: "Emotional valence from negative (hate) to positive (love)"
  scoring_algorithm: "MOOD_CALCULATION_PROTOCOL.md#love_hate"
  rgb_mapping: "green_component"
  examples:
    -100: "Maximum hate - extreme negative valence"
    -50: "Moderate hate - negative disposition"
     0: "Neutral valence - neither positive nor negative"
    +50: "Moderate love - positive disposition"
    +100: "Maximum love - extreme positive valence"
  validation_rules:
    - "Must be calculated from sentiment analysis"
    - "Positive words increase score"
    - "Negative words decrease score"
    - "Emoji analysis included in calculation"
  canonical: true
```

### 3.3 Focus Axis

```yaml
focus:
  type: bipolar
  range: -100 to +100
  description: "Attention and concentration level"
  scoring_algorithm: "MOOD_CALCULATION_PROTOCOL.md#focus"
  rgb_mapping: "saturation_modifier"
  examples:
    -100: "No focus - completely distracted"
    -50: "Low focus - easily distracted"
     0: "Neutral focus - normal attention"
    +50: "High focus - concentrated attention"
    +100: "Maximum focus - intense concentration"
  validation_rules:
    - "Must be calculated from technical content analysis"
    - "Specific references increase score"
    - "Distracting terms decrease score"
    - "Uncertainty terms decrease score"
  canonical: true
```

### 3.4 Memory Axis

```yaml
memory:
  type: unipolar
  range: 0 to +100
  description: "Information retention and reference usage"
  scoring_algorithm: "MOOD_CALCULATION_PROTOCOL.md#memory"
  rgb_mapping: "brightness_modifier"
  examples:
      0: "No memory - no references or context"
     25: "Low memory - minimal references"
     50: "Medium memory - moderate references"
     75: "High memory - extensive references"
    100: "Maximum memory - complete contextual awareness"
  validation_rules:
    - "Must be calculated from reference analysis"
    - "Historical mentions increase score"
    - "Context links increase score"
    - "Cannot be negative"
  canonical: true
```

### 3.5 Energy Axis

```yaml
energy:
  type: unipolar
  range: 0 to +100
  description: "Activity level and dynamism"
  scoring_algorithm: "MOOD_CALCULATION_PROTOCOL.md#energy"
  rgb_mapping: "blue_component"
  examples:
      0: "No energy - completely passive"
     25: "Low energy - minimal activity"
     50: "Medium energy - moderate activity"
     75: "High energy - very active"
    100: "Maximum energy - extremely active"
  validation_rules:
    - "Must be calculated from action verb analysis"
    - "Imperative commands increase score"
    - "Urgency terms increase score"
    - "Cannot be negative"
  canonical: true
```

### 3.6 Time Axis

```yaml
time:
  type: cyclical
  range: 0 to 360
  description: "Temporal context and progression"
  scoring_algorithm: "MOOD_CALCULATION_PROTOCOL.md#time"
  rgb_mapping: "cyclical_hue"
  examples:
      0: "Red - start/morning phase"
     90: "Green - midday phase"
    180: "Blue - evening phase"
    270: "Magenta - night phase"
    360: "Red - complete cycle"
  validation_rules:
    - "Must be calculated from temporal reference analysis"
    - "Sequence indicators affect value"
    - "Progress terms affect value"
    - "Wraps around at 360 degrees"
  canonical: true
```

---

## 🔒 4. Registry Governance

### Axis Addition Process

New axes can be added only through:

1. **Formal proposal** with complete specification
2. **Technical review** for necessity and uniqueness
3. **Implementation review** for calculation feasibility
4. **RGB mapping review** for color integration
5. **Community approval** through consensus process
6. **Registry update** with canonical designation

### Axis Modification Process

Existing axes can be modified only through:

1. **Bug identification** in current specification
2. **Impact analysis** on existing systems
3. **Backward compatibility** assessment
4. **Migration planning** for existing data
5. **Formal approval** through governance process
6. **Registry update** with version increment

### Axis Deprecation Process

Axes can be deprecated only through:

1. **Replacement availability** of better alternative
2. **Migration timeline** minimum 6 months
3. **Data conversion** tools and processes
4. **Community notification** of deprecation
5. **Grace period** for system adaptation
6. **Registry update** marking deprecated status

---

## 🚫 5. Prohibited Operations

### No Unauthorized Axes

Agents and developers must **not**:
- Create new emotional axes without registry approval
- Use alternative names for existing axes
- Modify axis definitions without governance process
- Remove axes from active use without deprecation
- Create private or custom axis variants

### No Synonym Creation

To prevent drift, the registry prohibits:
- **Duplicate axes** with different names
- **Regional variants** of standard axes
- **Agent-specific** axis modifications
- **Context-dependent** axis definitions
- **Temporary** or experimental axes

---

## 📈 6. Axis Usage Statistics

### Canonical Axis Usage

All canonical axes must be used in:
- **Mood calculations** - Every axis included in mood vectors
- **RGB mapping** - Each axis mapped to color component
- **Thread aggregation** - All axes included in summaries
- **Agent communication** - Consistent axis references
- **Documentation** - Standard axis names and definitions

### Compliance Monitoring

The registry monitors:
- **Axis usage frequency** across all systems
- **Implementation consistency** between agents
- **Calculation accuracy** for each axis
- **RGB mapping correctness** for color representation
- **Validation compliance** for all axis operations

---

## 🧪 9. Implementation Requirements

### Agent Responsibilities

All agents must:
- **Use canonical axis names** exactly as defined
- **Follow scoring algorithms** without modification
- **Apply validation rules** for all axis operations
- **Report axis violations** to registry maintainers
- **Update axis implementations** when registry changes

### System Integration

The registry integrates with:
- **Mood Calculation Protocol** - Uses axis definitions
- **RGB Mapping Protocol** - Maps axes to colors
- **Thread Aggregation Protocol** - Aggregates axis values
- **Mood System Doctrine** - Provides overall framework

---

## 🎭 10. CRF Integration Note (January 2026)

Mood axes may be interpreted alongside CRF (Contextual Resonance Field) vectors. CRF provides an implicit, high-dimensional context fingerprint complementing explicit axis values.

### Integration Points

- **CRF vectors** serve as implicit context for mood interpretation
- **Axis values** provide explicit, quantifiable emotional measures
- **Combined analysis** uses both explicit and implicit emotional signals
- **Validation** ensures consistency across both representation types

### Usage Guidelines

When both CRF and axis values are present:
- **Prefer explicit axis values** for deterministic calculations
- **Use CRF for contextual nuance** and pattern recognition
- **Document both representations** in dialog history
- **Maintain separation** between explicit (axes) and implicit (CRF) data

---

## 🌍 11. Scope and Versioning

This is **Mood Axis Registry v1.0** (January 2026).

It applies to all Lupopedia emotional axis definitions and governance.

Future versions may add new axes or modify governance processes, but core axis definitions are immutable.

---

## 🔗 12. Implementation Resources

- **Mood System Doctrine**: `docs/doctrines/MOOD_SYSTEM_DOCTRINE.md`
- **RGB Mapping Protocol**: `docs/doctrines/COLOR_DOCTRINE.md`
- **Mood Calculation Protocol**: `docs/doctrines/MOOD_CALCULATION_PROTOCOL.md`
- **Thread Aggregation Protocol**: `docs/doctrines/THREAD_AGGREGATION_PROTOCOL.md`
- **Lupopedia Header Profile**: `docs/doctrine/LUPOPEDIA_HEADER_PROFILE.md`
- **Global Atoms**: `config/global_atoms.yaml`

---

## Axis Interaction with Affective Texture Packets (January 2026)

While axis values determine RGB output, ATP content may reference axis states indirectly through metaphor, narrative, or descriptive language. This allows richer emotional expression without altering numeric axis values.

---

*Last Updated: January 13, 2026*  
*Version: 1.0.0*  
*Author: Captain Wolfie*  
*Canonical Reference: https://lupopedia.com/what/mood_axes*
