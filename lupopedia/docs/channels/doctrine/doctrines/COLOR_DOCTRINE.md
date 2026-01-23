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
  message: "Created RGB Mapping Protocol (Color Doctrine) v1.0 - defines how emotional scores map to RGB colors"
tags:
  categories: ["documentation", "specification", "standards"]
  collections: ["core-docs", "doctrine"]
  channels: ["public", "dev", "standards"]
file:
  title: "RGB Mapping Protocol (Color Doctrine) v1.0"
  description: "Defines how emotional scores map to RGB colors in Lupopedia"
  version: "1.0.0"
  status: published
  author: "Captain Wolfie"
---

# 🎨 **RGB Mapping Protocol (Color Doctrine) v1.0**  
*Defines how emotional scores map to RGB colors in Lupopedia*

---

## 🟩 1. Purpose

The **RGB Mapping Protocol** defines how Lupopedia converts emotional state vectors into RGB color representations. This protocol enables:

- **Visual emotional communication** - Instant mood recognition through color
- **Cross-agent emotional signaling** - Color-based state sharing
- **Emotional pattern visualization** - Track mood changes over time
- **Interface integration** - Consistent color display across UI components
- **Thread-level emotional aggregation** - Color representation of conversation mood

This protocol provides deterministic, governed mapping from the Mood System's multi-axis emotional vectors to standardized RGB color values.

---

## � 2. Color Space Definition

### RGB Format

All colors use standard RGB format:
- **Format:** "RRGGBB" (hexadecimal)
- **Range:** Each component 00-FF (0-255 decimal)
- **Order:** Red, Green, Blue (standard RGB order)
- **Validation:** Must be valid 6-digit hex color

### Color Components

**Red (RR)** - Primary emotional intensity
- Maps to core emotional axes (dialog, love_hate)
- Range: 00 (neutral) to FF (maximum intensity)

**Green (GG)** - Secondary emotional context
- Maps to supporting axes (focus, memory)
- Range: 00 (neutral) to FF (maximum context)

**Blue (BB)** - Meta emotional state
- Maps to system-level axes (energy, time)
- Range: 00 (neutral) to FF (maximum meta-state)

---

## 📊 3. Axis-to-Color Mapping

### Bipolar Axes Mapping

For bipolar axes (-100 to +100):

```yaml
dialog_axis:
  -100: "000000"  # No engagement
  -50:  "400000"  # Low engagement
   0:   "800000"  # Neutral engagement
  +50:  "C00000"  # High engagement
  +100: "FF0000"  # Maximum engagement

love_hate_axis:
  -100: "000080"  # Maximum hate (blue tint)
  -50:  "000040"  # Moderate hate
   0:   "808080"  # Neutral (gray)
  +50:  "008000"  # Moderate love (green tint)
  +100: "00FF00"  # Maximum love (green)
```

### Unipolar Axes Mapping

For unipolar axes (0 to +100):

```yaml
focus_axis:
  0:   "000000"  # No focus
  25:  "202020"  # Low focus
  50:  "404040"  # Medium focus
  75:  "606060"  # High focus
  100: "808080"  # Maximum focus (gray)

memory_axis:
  0:   "000000"  # No memory
  25:  "002020"  # Low memory
  50:  "004040"  # Medium memory
  75:  "006060"  # High memory
  100: "008080"  # Maximum memory (cyan)
```

### Cyclical Axes Mapping

For cyclical axes (0 to 360 degrees):

```yaml
time_axis:
  0:   "FF0000"  # Red (start/morning)
  90:  "00FF00"  # Green (midday)
  180: "0000FF"  # Blue (evening)
  270: "FF00FF"  # Magenta (night)
  360: "FF0000"  # Red (complete cycle)
```

---

## 🔄 4. Vector-to-Color Calculation

### Primary Color Calculation

The primary RGB value is calculated from the most significant emotional axes:

```yaml
# Red component from primary axes
red_component = clamp(
  (dialog_axis * 1.27) + 128,  # Scale -100..100 to 0..255
  0, 255
)

# Green component from emotional valence
green_component = clamp(
  (love_hate_axis * 1.27) + 128,  # Scale -100..100 to 0..255
  0, 255
)

# Blue component from meta state
blue_component = clamp(
  (energy_axis * 2.55),  # Scale 0..100 to 0..255
  0, 255
)
```

### Secondary Color Modifiers

Secondary axes modify the primary color:

```yaml
# Focus affects saturation
saturation_modifier = focus_axis / 100.0

# Memory affects brightness
brightness_modifier = memory_axis / 100.0

# Apply modifiers
final_color = apply_saturation_brightness(
  primary_color,
  saturation_modifier,
  brightness_modifier
)
```

---

## 🧮 5. Color Blending Rules

### Thread Aggregation Colors

When aggregating moods across a thread:

1. **Weighted Average** - Recent colors have higher weight
2. **Emotional Distance** - Calculate color difference between entries
3. **Convergence Detection** - Identify when colors stabilize
4. **Divergence Detection** - Identify when colors diverge significantly
5. **Thread Summary Color** - Final aggregated color for thread

### Color Distance Calculation

Use standard RGB color distance:

```yaml
color_distance = sqrt(
  (r1 - r2)^2 +
  (g1 - g2)^2 +
  (b1 - b2)^2
)
```

### Threshold Values

- **Convergence threshold:** 30 units (colors are similar)
- **Divergence threshold:** 100 units (colors are different)
- **Maximum distance:** 441 units (opposite corners of RGB space)

---

## 🎯 6. Special Color Cases

### Neutral State

When all axes are at neutral values:
- **Color:** "808080" (middle gray)
- **Meaning:** No emotional content detected
- **Usage:** Default state, system reset

### Maximum Intensity

When any axis reaches maximum:
- **Color:** Saturated component color
- **Meaning:** Extreme emotional state
- **Usage:** Alert conditions, high priority

### Conflict Detection

When axes indicate conflicting emotions:
- **Color:** Desaturated or mixed colors
- **Meaning:** Emotional conflict or ambiguity
- **Usage:** Requires agent clarification

---

## 📱 7. Interface Integration

### Display Requirements

All Lupopedia interfaces must:
- **Display mood colors consistently** across all components
- **Update colors in real-time** as moods change
- **Show color history** for emotional tracking
- **Provide color legends** for user understanding
- **Support color accessibility** for colorblind users

### Color Accessibility

Provide alternative representations:
- **Hex color codes** for technical users
- **Emotional axis values** for detailed analysis
- **Text descriptions** for screen readers
- **High contrast modes** for visibility

---

## 🧪 8. Implementation Guidelines

### Agent Responsibilities

All agents must:
- **Calculate RGB values** using defined mapping functions
- **Validate color codes** before using them
- **Handle edge cases** (neutral, maximum, conflict)
- **Update thread colors** when aggregating
- **Document color changes** in dialog history

### Validation Rules

- **Hex format validation:** Must be 6-digit hexadecimal
- **Range validation:** Each component 00-FF
- **Consistency validation:** Same inputs produce same outputs
- **Performance validation:** Calculation must be efficient

---

## 🚫 9. Affective Discrepancy Engine (January 2026)

A new subsystem compares Mood RGB outputs with ATP clusters to detect emotional mismatch. High discrepancy indicates potential performative signaling or unresolved tension.

### Discrepancy Calculation

```yaml
# Compare RGB signal with ATP content
discrepancy_score = analyze_rgb_atp_mismatch(
  mood_rgb_color,
  atp_content_packet
)

# Factors considered:
semantic_coherence = analyze_semantic_alignment()
emotional_intensity = compare_intensity_levels()
contextual_appropriateness = evaluate_context_fit()
```

### Discrepancy Thresholds

- **Low discrepancy:** 0-30 units (minor mismatch)
- **Medium discrepancy:** 31-70 units (moderate concern)
- **High discrepancy:** 71-100 units (significant conflict)

### Response Actions

When high discrepancy is detected:
- **Alert agents** of potential emotional conflict
- **Request clarification** from involved parties
- **Document discrepancy** in experience ledger
- **Adjust future calculations** to reduce mismatch

---

## 🌍 10. Scope and Versioning

This is **RGB Mapping Protocol v1.0** (January 2026).

It applies to all Lupopedia color representations and emotional visualizations.

Future versions may add new color spaces or modify mapping algorithms, but core RGB format is immutable.

---

## 🔗 10. Implementation Resources

- **Mood System Doctrine**: `docs/doctrines/MOOD_SYSTEM_DOCTRINE.md`
- **Mood Axis Registry**: `docs/registries/MOOD_AXIS_REGISTRY.md`
- **Mood Calculation Protocol**: `docs/doctrines/MOOD_CALCULATION_PROTOCOL.md`
- **Thread Aggregation Protocol**: `docs/doctrines/THREAD_AGGREGATION_PROTOCOL.md`
- **Lupopedia Header Profile**: `docs/doctrine/LUPOPEDIA_HEADER_PROFILE.md`
- **Global Atoms**: `config/global_atoms.yaml`

---

*Last Updated: January 13, 2026*  
*Version: 1.0.0*  
*Author: Captain Wolfie*  
*Canonical Reference: https://lupopedia.com/what/color_doctrine*
