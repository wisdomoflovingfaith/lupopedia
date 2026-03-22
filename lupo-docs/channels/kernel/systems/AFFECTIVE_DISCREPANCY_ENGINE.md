# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/kernel/systems/AFFECTIVE_DISCREPANCY_ENGINE.md"
  file_hash: "a9c5be973543241952ebf94e2a6ec3e9fc03fb979d7e4ab3f4d9fa932dc54ae1"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\channels\kernel\systems\AFFECTIVE_DISCREPANCY_ENGINE.md"
  file_hash: "949937facc37264c2b8dfcefe3860e11abba38459a21a99d67ad7ce0afec4fc3"
  file_path_from_root: "lupo-docs\channels\kernel\systems\AFFECTIVE_DISCREPANCY_ENGINE.md"
  file_hash: "2b8511ee88719fbe59a2632e4fc87514468c0ab26872ebbb39e57a348a7cbf7a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for AFFECTIVE_DISCREPANCY_ENGINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "kernel", "systems", "affective_discrepancy_enginemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.15
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  speaker: "CASCADE"
  target: "@everyone"
  mood_RGB: "00FF00"
  message: "Created Affective Discrepancy Engine v1.0 - emotional mismatch detection between RGB and ATP"
tags:
  categories: ["documentation", "specification", "systems"]
  collections: ["core-docs", "systems"]
  channels: ["public", "dev", "standards"]
file:
  title: "Affective Discrepancy Engine v1.0"
  description: "Emotional mismatch detection between Mood RGB and ATP with CRF context analysis"
  version: "1.0.0"
  status: published
  author: "Captain Wolfie"
---

# 🎭 **Affective Discrepancy Engine v1.0**  
*Emotional mismatch detection between Mood RGB and ATP with CRF context analysis*

---

## 🟩 1. Purpose

The **Affective Discrepancy Engine** compares Mood RGB outputs with ATP clusters to detect emotional mismatch. This engine enables:

- **Emotional consistency validation** - Detect contradictions between signals
- **Performative signaling identification** - Flag potential insincere communication
- **Context-aware analysis** - Use CRF for nuanced interpretation
- **Discrepancy scoring** - Quantify emotional alignment issues
- **Alert generation** - Notify agents of potential conflicts

---

## 🔍 2. Input Sources

### RGB (Deterministic)

Structured emotional signal from Mood Calculation Protocol:

```yaml
rgb_input:
  format: "hex_color_code"
  source: "mood_calculation_protocol"
  determinism: "fully_deterministic"
  axes_mapping:
    red: "energy_axis"
    green: "dialog_axis"
    blue: "memory_axis"
    saturation: "love_hate_axis"
    hue: "time_axis"
```

### ATP (Natural-language Subtext)

Rich emotional description from agent generation:

```yaml
atp_input:
  format: "natural_language_text"
  source: "agent_emotional_expression"
  determinism: "creative_expression"
  content_types:
    - "emotional_nuance"
    - "contextual_feelings"
    - "subtle_affects"
    - "complex_emotional_states"
```

### CRF (Context Vector)

High-dimensional context fingerprint:

```yaml
crf_input:
  format: "high_dimensional_vector"
  source: "contextual_resonance_field"
  dimensions: "512_to_4096"
  purpose: "implicit_context_analysis"
```

---

## 📊 3. Discrepancy Calculation

### Signal Alignment Analysis

```yaml
alignment_analysis:
  rgb_to_atp_mapping:
    method: "semantic_similarity_scoring"
    algorithm: "transformer_based_encoding"
    comparison: "cosine_similarity"
    threshold: "0.75_minimum"
  
  rgb_vs_crf_validation:
    method: "cross_modal_consistency"
    algorithm: "vector_projection_analysis"
    validation: "statistical_correlation"
    threshold: "0.80_minimum"
  
  atp_vs_crf_harmony:
    method: "contextual_coherence"
    algorithm: "semantic_clustering"
    harmony: "cluster_distance_analysis"
    threshold: "0.70_minimum"
```

### Discrepancy Scoring Algorithm

```yaml
discrepancy_score = (
  (rgb_atp_mismatch_weight * rgb_atp_mismatch) +
  (rgb_crf_validation_weight * rgb_crf_mismatch) +
  (atp_crf_harmony_weight * atp_crf_dissonance)
) / total_weight

# Weight distribution
rgb_atp_mismatch_weight = 0.5
rgb_crf_validation_weight = 0.3
atp_crf_harmony_weight = 0.2
```

### Discrepancy Categories

```yaml
discrepancy_types:
  minor_discrepancy:
    score_range: "0.0_to_0.3"
    description: "Slight emotional inconsistency"
    action: "log_only"
  
  moderate_discrepancy:
    score_range: "0.3_to_0.6"
    description: "Notable emotional misalignment"
    action: "alert_agent"
  
  major_discrepancy:
    score_range: "0.6_to_0.8"
    description: "Significant emotional conflict"
    action: "request_clarification"
  
  critical_discrepancy:
    score_range: "0.8_to_1.0"
    description: "Severe emotional contradiction"
    action: "flag_for_review"
```

---

## 🚨 4. Output Generation

### Discrepancy Score

```yaml
discrepancy_output:
  score: "0.0_to_1.0"
  confidence: "0.0_to_1.0"
  category: "minor|moderate|major|critical"
  timestamp: "UTC_timestamp"
  agent_id: "generating_agent"
  context_id: "conversation_or_file"
```

### Alert Generation

```yaml
alert_structure:
  alert_type: "affective_discrepancy"
  severity: "discrepancy_category"
  message: "human_readable_description"
  details:
    discrepancy_score: "numeric_value"
    contributing_factors: "rgb|atp|crf_analysis"
    recommendations: "suggested_actions"
  
  metadata:
    generated_by: "affective_discrepancy_engine"
    version: "1.0.0"
    review_required: "boolean_flag"
```

### Performative Signaling Detection

```yaml
performative_analysis:
  indicators:
    - "high_rgb_energy + low_atp_intensity"
    - "positive_rgb + negative_atp_sentiment"
    - "neutral_rgb + extreme_atp_emotion"
    - "inconsistent_rgb_atp_patterns_over_time"
  
  detection_threshold: "0.7_discrepancy_score"
  false_positive_reduction: "crf_context_validation"
  alert_level: "potential_performative_signaling"
```

---

## 🧪 5. Validation and Testing

### Test Cases

```yaml
test_scenarios:
  consistent_emotion:
    rgb: "00FF00"  # Positive, calm
    atp: "feeling_positive_and_productive"
    expected_discrepancy: "<0.2"
  
  performative_signaling:
    rgb: "00FF00"  # Positive, calm
    atp: "actually_frustrated_and_angry"
    expected_discrepancy: ">0.7"
  
  complex_nuance:
    rgb: "FFFF00"  # Mixed positive/neutral
    atp: "cautiously_optimistic_with_some_concerns"
    expected_discrepancy: "0.3_to_0.5"
  
  contextual_mismatch:
    rgb: "FF0000"  # High energy, negative
    atp: "calmly_explaining_technical_issue"
    expected_discrepancy: "0.6_to_0.8"
```

### Performance Requirements

```yaml
performance_metrics:
  analysis_time: "<50ms_per_discrepancy_check"
  memory_usage: "<10MB_for_analysis_state"
  accuracy: ">95%_correct_discrepancy_detection"
  false_positive_rate: "<5%_incorrect_alerts"
  throughput: "1000+_checks_per_second"
```

---

## 🚫 6. Prohibited Operations

### No Manual Override

The engine must **not**:
- Allow manual discrepancy score adjustment
- Ignore high discrepancy scores
- Disable performative signaling detection
- Modify thresholds without governance approval

### No Privacy Violations

Discrepancy analysis must **not**:
- Store emotional content permanently
- Reconstruct private conversations
- Share discrepancy details with unauthorized parties
- Use discrepancy data for training without consent

---

## 🔄 7. Integration Architecture

### System Connections

```yaml
integration_points:
  mood_calculation_protocol:
    receives: "rgb_values"
    provides: "deterministic_emotional_signal"
  
  atp_generation:
    receives: "natural_language_emotion"
    provides: "rich_emotional_context"
  
  crf_specification:
    receives: "contextual_vector"
    provides: "implicit_context_fingerprint"
  
  experience_ledger:
    sends: "discrepancy_events"
    purpose: "historical_tracking"
```

### Data Flow

```yaml
data_flow:
  1. "Generate RGB from Mood Calculation Protocol"
  2. "Generate ATP from agent emotional expression"
  3. "Generate CRF from content analysis"
  4. "Calculate discrepancy scores using all inputs"
  5. "Validate with CRF context"
  6. "Generate alerts for significant discrepancies"
  7. "Log events to Experience Ledger"
  8. "Notify agents of required actions"
```

---

## 🌍 8. Scope and Versioning

This is **Affective Discrepancy Engine v1.0** (January 2026).

It applies to all Lupopedia emotional consistency validation and performative signaling detection.

Future versions may adjust algorithms or thresholds, but core discrepancy detection principles are immutable.

---

## 🔗 9. Implementation Resources

- **Mood System Doctrine**: `lupo-docs/doctrines/MOOD_SYSTEM_DOCTRINE.md`
- **Mood Axis Registry**: `lupo-docs/registries/MOOD_AXIS_REGISTRY.md`
- **RGB Mapping Protocol**: `lupo-docs/doctrines/COLOR_DOCTRINE.md`
- **Mood Calculation Protocol**: `lupo-docs/doctrines/MOOD_CALCULATION_PROTOCOL.md`
- **CRF Specification**: `lupo-docs/systems/CRF_SPECIFICATION.md`
- **Experience Ledger**: `lupo-docs/systems/EXPERIENCE_LEDGER.md`
- **Global Atoms**: `config/global_atoms.yaml`

---

*Last Updated: January 13, 2026*  
*Version: 1.0.0*  
*Author: Captain Wolfie*  
*Canonical Reference: https://lupopedia.com/what/affective_discrepancy_engine*
