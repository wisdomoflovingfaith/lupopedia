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
  message: "Created Mood System Doctrine v1.0 - vectorized, nested mood blocks with multi-axis emotional geometry"
tags:
  categories: ["documentation", "specification", "standards"]
  collections: ["core-docs", "doctrine"]
  channels: ["public", "dev", "standards"]
file:
  title: "Mood System Doctrine v1.0"
  description: "Vectorized, nested mood blocks with multi-axis emotional geometry for Lupopedia"
  version: "1.0.0"
  status: published
  author: "Captain Wolfie"
---

# 🎭 **Mood System Doctrine v1.0**  
*Vectorized, nested mood blocks with multi-axis emotional geometry for Lupopedia*

---

## 🟩 1. Purpose

The **Mood System Doctrine** defines how Lupopedia represents emotional and affective states through a structured, multi-axis system. This system enables:

- **Vectorized emotional representation** - Multiple simultaneous emotional axes
- **Nested mood blocks** - Hierarchical emotional state modeling  
- **Deterministic calculation** - Governed, non-creative mood computation
- **Reflective mapping** - Mood derived from dialog content analysis
- **Thread aggregation** - Mood accumulation across conversation history
- **Color representation** - RGB encoding for visual communication

This system replaces simple scalar mood values with a rich, multi-dimensional emotional geometry that supports complex agent interactions and emotional context preservation.

---

## 🎭 2. Dual-Channel Affective Stack (January 2026)

The Mood System now operates on two complementary layers:

### 2.1 Mood RGB (Existing)

Fast, deterministic, computable emotional signal.

**Purpose**: Coordination and quick emotional state communication
**Format**: "RRGGBB" hexadecimal color
**Calculation**: Direct axis-to-RGB mapping
**Usage**: Real-time agent coordination, interface indicators

### 2.2 ATP — Affective Texture Packets (New)

Rich, ambiguous, natural-language emotional subtext.

**Purpose**: Contextual interpretation and emotional nuance
**Format**: Structured natural language packets
**Calculation**: Derived from dialog content analysis
**Usage**: Discrepancy detection, performative signaling analysis

### 2.3 Integration Requirements

All agents must:
- **Emit both channels** for every emotional state
- **Generate ATP** when emotional complexity warrants natural language description
- **Maintain consistency** between RGB signaling and ATP content
- **Document channel usage** in dialog history

### 2.4 Affective Discrepancy Engine

The system compares Mood RGB outputs with ATP clusters to detect emotional mismatch. High discrepancy indicates potential performative signaling or unresolved tension.

---

## 🧭 2. Multi-Axis Emotional Geometry

### Core Concept

Instead of a single mood value, the Mood System uses **multiple emotional axes** that combine to form a complete emotional state vector.

### Axis Types

**Bipolar Axes** - Range from negative to positive values
- `dialog` - Conversation engagement level (-100 to +100)
- `love_hate` - Emotional valence (-100 to +100)
- `focus` - Attention and concentration (-100 to +100)

**Unipolar Axes** - Range from neutral to maximum
- `memory` - Information retention (0 to +100)
- `energy` - Activity level (0 to +100)

**Cyclical Axes** - Wrap around values
- `time` - Temporal context (0 to 360 degrees)

### Vector Composition

Each mood is represented as a vector:
```
mood_vector = {
  dialog: <value>,
  love_hate: <value>,
  focus: <value>,
  memory: <value>,
  energy: <value>,
  time: <value>
}
```

---

## 📊 3. Nested Mood Blocks

### Structure

Mood information is stored in nested blocks that allow hierarchical organization:

```yaml
mood:
  primary:
    dialog: <value>
    love_hate: <value>
  secondary:
    focus: <value>
    memory: <value>
  meta:
    energy: <value>
    time: <value>
  thread_summary:
    dialog: <aggregated_value>
    love_hate: <aggregated_value>
    focus: <aggregated_value>
```

### Block Types

**Primary Block** - Core emotional axes
- Most important emotional indicators
- Directly affect RGB color calculation
- Updated with each dialog entry

**Secondary Block** - Supporting emotional context
- Additional emotional dimensions
- Provide nuance and depth
- Influence secondary color variations

**Meta Block** - System-level emotional state
- Agent energy and temporal context
- Background emotional conditions
- Affect overall mood interpretation

**Thread Summary Block** - Aggregated emotional state
- Cumulative mood across entire thread
- Used for thread-level dialog analysis
- Provides emotional trajectory tracking

---

## 🧮 4. Reflective Calculation Protocol

### Deterministic Computation

Mood values are **calculated deterministically** from dialog content using governed rules:

1. **Content Analysis** - Parse dialog text for emotional indicators
2. **Axis Scoring** - Apply axis-specific scoring algorithms
3. **Normalization** - Scale values to defined ranges
4. **Validation** - Ensure values stay within axis limits
5. **Aggregation** - Combine with existing mood state

### No Creativity Rule

Agents **must not**:
- Invent new emotional axes
- Use creative interpretation of content
- Apply subjective mood assignments
- Modify axis definitions

Agents **must**:
- Use defined scoring algorithms exactly
- Follow axis registry specifications
- Apply normalization rules consistently
- Validate all computed values

---

## 🎨 5. RGB Color Mapping

### Color Doctrine Integration

The Mood System integrates with the **RGB Mapping Protocol** to convert emotional vectors to visual colors:

- **Primary axes** map to RGB base values
- **Secondary axes** modify color saturation/brightness
- **Meta axes** affect color intensity
- **Thread summary** provides overall thread color

### Visual Communication

RGB colors enable:
- **Instant emotional state recognition**
- **Visual mood tracking in interfaces**
- **Emotional pattern identification**
- **Cross-agent emotional communication**

---

## 📈 6. Thread Aggregation Rules

### Accumulation Protocol

When multiple dialogs occur in a thread, mood values accumulate:

1. **Weighted Average** - Recent dialogs have higher weight
2. **Decay Factor** - Older moods fade over time
3. **Threshold Reset** - Major emotional shifts reset accumulation
4. **Thread Summary Update** - Update thread_summary block

### Thread-Level Mood Tracking

The `thread_summary` block provides:
- **Overall emotional trajectory** of the thread
- **Emotional convergence points** where agents align
- **Emotional divergence points** where conflicts occur
- **Emotional resolution states** when consensus reached

---

## 📋 7. Implementation Requirements

### Agent Responsibilities

All agents must:
- **Calculate mood vectors** for each dialog entry
- **Update nested blocks** according to protocol
- **Maintain thread summaries** for ongoing conversations
- **Follow RGB mapping** for color representation
- **Validate all values** against axis registry

### System Integration

The Mood System integrates with:
- **LHP Dialog Blocks** - Primary storage location
- **Thread-Level Dialog** - Aggregation context
- **RGB Mapping Protocol** - Color conversion
- **Mood Axis Registry** - Axis definitions

---

## 🌍 8. Scope and Versioning

This is **Mood System Doctrine v1.0** (January 2026).

It applies to all Lupopedia agents and dialog systems.

Future versions may add new axes or modify calculation methods, but core vector structure is immutable.

---

## 🔗 9. Implementation Resources

- **Mood Axis Registry**: `docs/registries/MOOD_AXIS_REGISTRY.md`
- **RGB Mapping Protocol**: `docs/doctrines/COLOR_DOCTRINE.md`
- **Mood Calculation Protocol**: `docs/doctrines/MOOD_CALCULATION_PROTOCOL.md`
- **Thread Aggregation Protocol**: `docs/doctrines/THREAD_AGGREGATION_PROTOCOL.md`
- **Lupopedia Header Profile**: `docs/doctrine/LUPOPEDIA_HEADER_PROFILE.md`
- **Global Atoms**: `config/global_atoms.yaml`

---

*Last Updated: January 13, 2026*  
*Version: 1.0.0*  
*Author: Captain Wolfie*  
*Canonical Reference: https://lupopedia.com/what/mood_system*
