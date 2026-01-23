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
  message: "Created Thread Aggregation Protocol v1.0 - mood accumulation across dialogs with thread_summary blocks"
tags:
  categories: ["documentation", "specification", "standards"]
  collections: ["core-docs", "doctrine"]
  channels: ["public", "dev", "standards"]
file:
  title: "Thread Aggregation Protocol v1.0"
  description: "Defines mood accumulation across dialogs with mood.thread_summary blocks in Lupopedia"
  version: "1.0.0"
  status: published
  author: "Captain Wolfie"
---

# 🧵 **Thread Aggregation Protocol v1.0**  
*Defines mood accumulation across dialogs with mood.thread_summary blocks in Lupopedia*

---

## 🟩 1. Purpose

The **Thread Aggregation Protocol** defines how Lupopedia accumulates emotional states across multiple dialog entries within a thread. This protocol enables:

- **Thread-level emotional tracking** - Overall mood trajectory
- **Emotional convergence detection** - When agents align emotionally
- **Emotional divergence detection** - When conflicts occur
- **Historical mood preservation** - Complete emotional history
- **Thread summary generation** - Aggregated mood.thread_summary blocks
- **Cross-agent emotional coordination** - Shared emotional understanding

This protocol provides a mechanism for understanding the emotional evolution of entire conversations, not just individual dialog entries.

---

## 📊 2. Aggregation Architecture

### Data Sources

Thread aggregation processes:

1. **Individual Dialog Moods** - Mood blocks from each dialog entry
2. **Thread-Level Dialog Files** - `/dialogs/<threadname>_dialog.md` entries
3. **Per-File Dialog History** - `<filename>_dialog.md` contributions
4. **Header Dialog Blocks** - Latest dialog in file headers
5. **Agent State Changes** - Emotional transitions during work

### Aggregation Pipeline

```yaml
1. Collect all mood vectors from thread
2. Apply temporal weighting (recent = higher weight)
3. Calculate emotional distance between entries
4. Identify convergence and divergence points
5. Generate thread summary block
6. Update thread-level dialog with summary
```

---

## ⏰ 3. Temporal Weighting System

### Weight Decay Formula

Recent dialog entries have higher influence on thread summary:

```yaml
# Calculate age of each entry (in hours)
entry_age = current_time - entry_time

# Apply exponential decay
weight = exp(-entry_age / decay_constant)

# Default decay constant (24 hours)
decay_constant = 24.0

# Normalize weights
total_weight = sum(all_weights)
normalized_weight = weight / total_weight
```

### Weight Categories

- **0-1 hour old**: weight = 1.0 (most recent)
- **1-6 hours old**: weight = 0.8 (very recent)
- **6-24 hours old**: weight = 0.6 (recent)
- **1-3 days old**: weight = 0.3 (older)
- **3+ days old**: weight = 0.1 (historical)

### Special Cases

- **Emotional spikes** - Extreme moods get temporary boost
- **Consensus points** - Agreement between agents gets boost
- **Conflict points** - Disagreement gets higher weight
- **Resolution points** - Problem resolution gets highest weight

---

## 📈 4. Thread Summary Generation

### mood.thread_summary Block Structure

```yaml
mood:
  thread_summary:
    dialog: <aggregated_dialog_value>
    love_hate: <aggregated_love_hate_value>
    focus: <aggregated_focus_value>
    memory: <aggregated_memory_value>
    energy: <aggregated_energy_value>
    time: <aggregated_time_value>
    metadata:
      entry_count: <total_dialogs>
      time_span: <hours_duration>
      convergence_points: <count>
      divergence_points: <count>
      last_updated: <UTC_timestamp>
```

### Aggregation Calculations

```yaml
# Weighted average for each axis
aggregated_dialog = sum(dialog_values * weights) / sum(weights)
aggregated_love_hate = sum(love_hate_values * weights) / sum(weights)
aggregated_focus = sum(focus_values * weights) / sum(weights)
aggregated_memory = sum(memory_values * weights) / sum(weights)
aggregated_energy = sum(energy_values * weights) / sum(weights)

# Cyclical axis uses circular mean
aggregated_time = atan2(
  sum(sin(time_values * weights)),
  sum(cos(time_values * weights))
) % 360
```

---

## 🎯 5. Convergence Detection

### Convergence Criteria

Thread emotional convergence occurs when:

1. **Emotional distance** between recent entries < 30 units
2. **Stability period** - Low variance for 3+ consecutive entries
3. **Agent agreement** - Multiple agents show similar moods
4. **Direction alignment** - Emotional vectors point in similar directions

### Convergence Calculation

```yaml
# Calculate emotional variance
emotional_variance = variance(recent_mood_vectors)

# Check convergence threshold
if emotional_variance < convergence_threshold:
  convergence_detected = true
  convergence_strength = 1.0 - (emotional_variance / convergence_threshold)
else:
  convergence_detected = false
  convergence_strength = 0.0
```

### Convergence Actions

When convergence is detected:
- **Update thread summary** with convergence metadata
- **Mark convergence point** in dialog history
- **Adjust aggregation weights** to emphasize stability
- **Notify agents** of emotional alignment

---

## ⚡ 6. Divergence Detection

### Divergence Criteria

Thread emotional divergence occurs when:

1. **Emotional distance** between entries > 100 units
2. **Rapid mood changes** - High variance in short time
3. **Agent disagreement** - Opposing emotional states
4. **Conflict indicators** - Negative emotional interactions

### Divergence Calculation

```yaml
# Calculate emotional momentum
emotional_momentum = abs(current_mood - previous_mood) / time_delta

# Check divergence threshold
if emotional_momentum > divergence_threshold:
  divergence_detected = true
  divergence_severity = min(emotional_momentum / divergence_threshold, 1.0)
else:
  divergence_detected = false
  divergence_severity = 0.0
```

### Divergence Actions

When divergence is detected:
- **Update thread summary** with divergence metadata
- **Mark divergence point** in dialog history
- **Increase aggregation sensitivity** for conflict detection
- **Alert agents** to potential emotional conflicts

---

## 📋 7. Thread Update Protocol

### Interaction with Dual-Channel Affective Stack (January 2026)

Thread aggregation now considers both Mood RGB and ATP (Affective Texture Packets). RGB values are aggregated deterministically. ATP content is clustered for contextual interpretation and discrepancy detection.

### When to Update Thread Summary

Update `mood.thread_summary` when:

- **New dialog entry** is added to thread
- **Emotional state changes** significantly (> 50 units)
- **Convergence is detected** or lost
- **Divergence is detected** or resolved
- **Time threshold** reached (every 6 hours)

### Update Process

```yaml
1. Collect all mood vectors from thread
2. Apply temporal weighting
3. Calculate new aggregated values
4. Detect convergence/divergence
5. Update thread_summary block
6. Record update in thread-level dialog
7. Notify participating agents
```

### Update Frequency

- **Real-time updates** - For active threads (< 1 hour old)
- **Hourly updates** - For recent threads (< 24 hours old)
- **Daily updates** - For ongoing threads (< 7 days old)
- **Weekly updates** - For historical threads (> 7 days old)

---

## 🗂️ 8. Thread Storage Integration

### Thread-Level Dialog Files

Thread summaries are stored in `/dialogs/<threadname>_dialog.md`:

```markdown
## 2026-01-13 14:30:00 UTC
**speaker:** AGENT_NAME  
**target:** @thread  
**message:** Thread mood updated: convergence detected, dialog=75, love_hate=60

---
mood.thread_summary:
  dialog: 75
  love_hate: 60
  focus: 80
  memory: 70
  energy: 65
  time: 180
  metadata:
    entry_count: 15
    time_span: 4.5
    convergence_points: 3
    divergence_points: 1
    last_updated: 2026-01-13 14:30:00 UTC
```

### Integration with Per-File Dialog

Thread aggregation complements per-file dialog:
- **Thread files** capture overall conversation mood
- **File files** capture individual file modifications
- **Cross-referencing** between systems for full context
- **Hierarchical organization** - thread contains files

---

## 🧪 9. Validation and Testing

### Consistency Validation

Verify aggregation correctness:

```yaml
# Test thread with known moods
test_thread = [
  {dialog: 50, love_hate: 30, time: "2026-01-13 10:00"},
  {dialog: 60, love_hate: 40, time: "2026-01-13 11:00"},
  {dialog: 70, love_hate: 50, time: "2026-01-13 12:00"}
]

# Expected aggregation (recent weighted more heavily)
expected_summary = {
  dialog: 65,  # Weighted toward recent
  love_hate: 45,  # Weighted toward recent
  convergence: true  # Low variance
}
```

### Performance Requirements

- **Aggregation time**: < 50ms for threads with 100 entries
- **Memory usage**: < 5MB for thread state
- **Update frequency**: Real-time for active threads
- **Accuracy**: 100% reproducible aggregation

---

## 🚫 10. Prohibited Operations

### No Creative Aggregation

Agents must **not**:
- Apply subjective weighting based on content
- Modify aggregation algorithms based on agent preferences
- Ignore temporal weighting requirements
- Create custom convergence criteria
- Manipulate thread summaries for emotional effect

### No Data Manipulation

Agents must **not**:
- Delete historical mood entries
- Modify existing mood values
- Create fake convergence/divergence points
- Alter thread metadata for appearance

---

## 🌍 11. Scope and Versioning

This is **Thread Aggregation Protocol v1.0** (January 2026).

It applies to all Lupopedia thread-level dialog and emotional aggregation operations.

Future versions may add new aggregation methods or modify weighting algorithms, but core convergence/divergence detection is immutable.

---

## 🔗 12. Implementation Resources

- **Mood System Doctrine**: `docs/doctrines/MOOD_SYSTEM_DOCTRINE.md`
- **Mood Axis Registry**: `docs/registries/MOOD_AXIS_REGISTRY.md`
- **RGB Mapping Protocol**: `docs/doctrines/COLOR_DOCTRINE.md`
- **Mood Calculation Protocol**: `docs/doctrines/MOOD_CALCULATION_PROTOCOL.md`
- **Lupopedia Header Profile**: `docs/doctrine/LUPOPEDIA_HEADER_PROFILE.md`
- **Dialog History Specification**: `docs/agents/DIALOG_HISTORY_SPEC.md`
- **Thread-Level Dialog Specification**: `docs/agents/THREAD_LEVEL_DIALOG_SPEC.md`
- **Global Atoms**: `config/global_atoms.yaml`

---

*Last Updated: January 13, 2026*  
*Version: 1.0.0*  
*Author: Captain Wolfie*  
*Canonical Reference: https://lupopedia.com/what/thread_aggregation*
