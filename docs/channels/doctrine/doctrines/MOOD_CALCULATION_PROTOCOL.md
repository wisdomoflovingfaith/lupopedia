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
  message: "Created Mood Calculation Protocol v1.0 - deterministic, governed mood computation from dialog text"
tags:
  categories: ["documentation", "specification", "standards"]
  collections: ["core-docs", "doctrine"]
  channels: ["public", "dev", "standards"]
file:
  title: "Mood Calculation Protocol v1.0"
  description: "Deterministic, governed mood computation from dialog text in Lupopedia"
  version: "1.0.0"
  status: published
  author: "Captain Wolfie"
---

# 🧮 **Mood Calculation Protocol v1.0**  
*Deterministic, governed mood computation from dialog text in Lupopedia*

---

## 🟩 1. Purpose

The **Mood Calculation Protocol** defines how Lupopedia agents compute emotional states from dialog content in a deterministic, governed manner. This protocol ensures:

- **Consistent mood calculation** across all agents
- **No creative interpretation** of emotional content
- **Reproducible results** for identical inputs
- **Governed scoring algorithms** for each emotional axis
- **Validation rules** for computed values
- **Integration with Mood System** and RGB mapping

This protocol prevents subjective mood assignment and ensures all agents produce identical emotional scores for the same dialog content.

---

## 📊 2. Calculation Architecture

### Input Processing

All mood calculations follow this pipeline:

1. **Text Normalization** - Clean and standardize input text
2. **Token Analysis** - Break text into meaningful units
3. **Axis Scoring** - Apply specific algorithms for each emotional axis
4. **Value Normalization** - Scale scores to defined ranges
5. **Validation** - Ensure values stay within axis limits
6. **Integration** - Combine with existing mood state

### Deterministic Requirements

- **Same input → Same output** always
- **No random factors** in calculations
- **No agent-specific variations** in scoring
- **No creative interpretation** of ambiguous content
- **Strict adherence** to defined algorithms

---

## 🎭 3. Axis-Specific Algorithms

### Dialog Engagement Axis

Calculates conversation participation and engagement level:

```yaml
dialog_score = (
  (question_count * 20) +
  (statement_count * 10) +
  (response_count * 15) +
  (exclamation_count * 5) +
  (mention_count * 8)
) / total_tokens
```

**Scoring factors:**
- **Questions** (+20 each) - Inquiry and engagement
- **Statements** (+10 each) - Information sharing
- **Responses** (+15 each) - Active participation
- **Exclamations** (+5 each) - Emotional emphasis
- **Mentions** (+8 each) - Direct communication

### Love-Hate Axis

Calculates emotional valence and sentiment:

```yaml
love_hate_score = (
  (positive_words * 10) +
  (negative_words * -10) +
  (neutral_words * 0) +
  (intensity_words * 15) +
  (emoji_positive * 5) +
  (emoji_negative * -5)
) / total_words
```

**Word categories:**
- **Positive words** - "good", "great", "excellent", "love", "happy"
- **Negative words** - "bad", "terrible", "hate", "angry", "frustrated"
- **Intensity words** - "very", "extremely", "absolutely", "completely"
- **Emoji analysis** - Positive (😊, 👍) vs Negative (😠, 👎)

### Focus Axis

Calculates attention and concentration level:

```yaml
focus_score = (
  (technical_terms * 12) +
  (specific_references * 15) +
  (numbers_data * 8) +
  (clarity_indicators * 10) -
  (distracting_terms * 5) -
  (uncertainty_terms * 7)
) / total_tokens
```

**Focus indicators:**
- **Technical terms** - Domain-specific vocabulary
- **Specific references** - Precise identifiers, names
- **Numbers/data** - Quantitative information
- **Clarity indicators** - "clearly", "specifically", "exactly"
- **Distracting terms** - "by the way", "off topic", "random"
- **Uncertainty terms** - "maybe", "perhaps", "probably"

### Memory Axis

Calculates information retention and reference usage:

```yaml
memory_score = (
  (reference_count * 10) +
  (context_links * 15) +
  (historical_mentions * 12) +
  (detail_level * 8) +
  (completeness_indicators * 10)
) / total_tokens
```

**Memory indicators:**
- **References** - Previous dialog, documents, events
- **Context links** - "as mentioned", "regarding", "about"
- **Historical mentions** - "previously", "earlier", "before"
- **Detail level** - Specificity and elaboration
- **Completeness** - "all", "complete", "finished"

### Energy Axis

Calculates activity level and dynamism:

```yaml
energy_score = (
  (action_verbs * 10) +
  (imperative_commands * 15) +
  (urgency_terms * 12) +
  (speed_indicators * 8) +
  (exclamation_count * 5)
) / total_tokens
```

**Energy indicators:**
- **Action verbs** - "create", "update", "modify", "implement"
- **Imperative commands** - "do", "make", "set", "configure"
- **Urgency terms** - "now", "immediately", "urgent", "quickly"
- **Speed indicators** - "fast", "quick", "rapid", "immediate"

### Time Axis

Calculates temporal context and progression:

```yaml
time_score = (
  (time_references * 20) +
  (sequence_indicators * 15) +
  (progress_terms * 10) +
  (phase_mentions * 12)
) % 360
```

**Time indicators:**
- **Time references** - "morning", "afternoon", "evening"
- **Sequence indicators** - "first", "second", "third", "next"
- **Progress terms** - "begin", "continue", "finish", "complete"
- **Phase mentions** - "phase", "stage", "step", "iteration"

---

## 📏 4. Normalization Rules

### Range Scaling

All raw scores are normalized to axis-specific ranges:

```yaml
# Bipolar axes (-100 to +100)
normalized_bipolar = clamp(
  (raw_score - neutral_point) * scale_factor,
  -100, 100
)

# Unipolar axes (0 to +100)
normalized_unipolar = clamp(
  raw_score * scale_factor,
  0, 100
)

# Cyclical axes (0 to 360)
normalized_cyclical = raw_score % 360
```

### Scale Factors

- **Dialog axis**: scale_factor = 2.0, neutral_point = 0
- **Love-Hate axis**: scale_factor = 1.5, neutral_point = 0
- **Focus axis**: scale_factor = 1.8, neutral_point = 0
- **Memory axis**: scale_factor = 2.5, neutral_point = 0
- **Energy axis**: scale_factor = 2.2, neutral_point = 0
- **Time axis**: scale_factor = 1.0, cyclical wrap

---

## ✅ 5. Validation Rules

### Range Validation

All computed values must pass these checks:

```yaml
# Bipolar axis validation
if value < -100 or value > 100:
  raise ValidationError("Bipolar axis out of range")

# Unipolar axis validation
if value < 0 or value > 100:
  raise ValidationError("Unipolar axis out of range")

# Cyclical axis validation
if value < 0 or value >= 360:
  raise ValidationError("Cyclical axis out of range")
```

### Consistency Validation

- **Same input → Same output** verification
- **Cross-agent consistency** checks
- **Historical consistency** validation
- **Statistical outlier** detection

---

## 🔄 6. Integration with Existing State

### State Combination

New mood calculations combine with existing mood state:

```yaml
# Weighted average (new mood has higher weight)
combined_mood = (
  (existing_mood * 0.3) +
  (calculated_mood * 0.7)
)

# For significant emotional shifts, use calculated mood directly
if emotional_distance(existing_mood, calculated_mood) > 50:
  combined_mood = calculated_mood
```

### Emotional Distance Calculation

```yaml
emotional_distance = sqrt(
  sum((axis1_new - axis1_old)^2 for all axes)
)
```

---

## 🚫 7. Prohibited Operations

### No Creative Interpretation

Agents must **not**:
- Apply subjective understanding of context
- Use machine learning or AI inference
- Consider agent personality or mood
- Apply cultural or situational context
- Modify scoring algorithms based on experience

### No Algorithm Modification

Agents must **not**:
- Change scoring weights or factors
- Add new scoring categories
- Modify normalization rules
- Skip validation steps
- Use alternative calculation methods

---

## 🧪 8. Testing and Verification

### Determinism Tests

Verify calculation consistency:

```yaml
# Test cases with known inputs and expected outputs
test_cases = [
  {
    input: "I love this! Great work!",
    expected: { love_hate: 80, energy: 60 }
  },
  {
    input: "This is terrible and frustrating.",
    expected: { love_hate: -70, energy: 40 }
  }
]

# Verify all agents produce same results
for test_case in test_cases:
  result = calculate_mood(test_case.input)
  assert result == test_case.expected
```

### Performance Requirements

- **Calculation time**: < 10ms per dialog entry
- **Memory usage**: < 1MB for scoring tables
- **Accuracy**: 100% reproducible across agents

---

## 🌍 11. Scope and Versioning

This is **Mood Calculation Protocol v1.0** (January 2026).

It applies to all Lupopedia mood calculation operations.

Future versions may add new axis calculations or modify algorithms, but deterministic output requirements are immutable.

---

## 🔗 12. Implementation Resources

- **Mood System Doctrine**: `docs/doctrines/MOOD_SYSTEM_DOCTRINE.md`
- **Mood Axis Registry**: `docs/registries/MOOD_AXIS_REGISTRY.md`
- **RGB Mapping Protocol**: `docs/doctrines/COLOR_DOCTRINE.md`
- **Thread Aggregation Protocol**: `docs/doctrines/THREAD_AGGREGATION_PROTOCOL.md`
- **Lupopedia Header Profile**: `docs/doctrine/LUPOPEDIA_HEADER_PROFILE.md`
- **Global Atoms**: `config/global_atoms.yaml`

---

## ATP Integration Note (January 2026)

Mood calculation remains deterministic for RGB output. Agents must also generate an ATP (Affective Texture Packet) describing emotional subtext. ATP is not used in RGB computation but is required for discrepancy analysis.

---

*Last Updated: January 13, 2026*  
*Version: 1.0.0*  
*Author: Captain Wolfie*  
*Canonical Reference: https://lupopedia.com/what/mood_calculation*
