---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-10
author: GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Updated MOOD_RGB Doctrine: Added comprehensive Counting-in-Light emotional geometry system documentation, three axes explanation, mood calculation doctrine, and usage across DIALOG, HERMES, CADUCEUS, and operator UI."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "mood", "routing", "agents"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "agents", "public"]
in_this_file_we_have:
  - Important Clarification: mood_RGB Terminology
  - Doctrine Boundaries
  - Emotional Polarity Tensor Doctrine Overview
  - The Three Axes (R, G, B)
  - Mood_RGB Field
  - Mood Calculation Doctrine
  - Mood in CADUCEUS Routing
  - Mood in Operator UI (Future)
  - Emotional Geometry Doctrine
  - Suggested Mood Tensor
  - Mood Examples
  - Format and Validation Rules
  - Neutral vs Zero Mood Clarification
  - Agent Behavior
  - Routing Behavior
  - Channel Routing Mode
  - Channel Intent
file:
  title: "Emotional Polarity Tensor Doctrine (Formerly: Mood & Color Doctrine - Counting-in-Light)"
  description: "Documents the Counting-in-Light emotional polarity tensor system used throughout Lupopedia, including the three emotional axes (strife, harmony, memory depth), mood calculation, and usage in DIALOG, HERMES, CADUCEUS, and operator UI."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Emotional Polarity Tensor Doctrine (Formerly: Mood & Color Doctrine - Counting-in-Light)

## Important Clarification: "mood_RGB" Terminology

**Legacy Naming Note:** The term "mood_RGB" is a historical convention and does **NOT** refer to RGB color channels or visual colors. It represents an emotional polarity tensor with three abstract axes: strife (R: +1 to -1), harmony (G: +1 to -1), and memory depth (B: +1 to -1).

**Encoding Details:** Values are mapped to hex (e.g., (1, 0, -1) â†’ FF8000) for compact storage and transmission, but this is coincidental and carries no color semantics.

**Avoid Misconceptions:** References to "mood color" or "color doctrine" in this document are metaphorical holdovers from the original naming convention. The system uses abstract emotional coordinates, not visual colors.

**See Axis Definitions below for full details.**

---

## Doctrine Boundaries

**Not Defined in Doctrine:**

The following aspects are intentionally left undefined by doctrine and represent implementation freedoms:

- **Polarity of axes** - whether 00 or FF represents "more" of any emotional dimension
- **Mapping from Pono/Pilau/Kapakai to RGB** - ethical state markers are not mapped to color axes
- **Blending rules** - how multiple mood tensors combine or interpolate
- **Normalization rules** - mathematical constraints on tensor values beyond basic ranges
- **Uncertainty handling** - representation of ambiguous or indeterminate emotional states
- **Hex computation methods** - algorithms for converting emotional values to hex representation
- **Tensor validity constraints** - validation rules beyond hex format checking

These are implementation freedoms, not doctrinal definitions. Implementations may establish their own conventions for these aspects without violating doctrine.

---

This section documents the Countingâ€‘inâ€‘Light emotional polarity tensor system used throughout Lupopedia. The system encodes emotional tone as a 6â€‘character hex value (mood_rgb) representing three abstract emotional axes: strife, harmony, and memory depth.

Countingâ€‘inâ€‘Light is used by:
- DIALOG (expressive rendering),
- dialog_messages.mood_rgb (storage),
- HERMES (contextual filtering),
- CADUCEUS (routing currents),
- operator UI (future emotional indicators).

---

## Overview

Countingâ€‘inâ€‘Light is a deterministic emotional geometry system that
represents emotional tone using three axes:

- **R (Red)** â€” strife, conflict, tension  
- **G (Green)** â€” harmony, cooperation, warmth  
- **B (Blue)** â€” memory depth, reflection, introspection  

The final mood is encoded as a hex string:

RRGGBB

Example:
- 990000 â†’ high strife, low harmony, shallow memory  
- 00CC66 â†’ low strife, high harmony, medium memory  
- 0033FF â†’ low strife, low harmony, deep memory  

The system is expressive but not subjective.  
It is stylistic, not logical.

---

## The Three Axes

### R â€” Strife / Conflict
Represents:
- tension,
- urgency,
- agitation,
- emotional heat.

High R:
- sharp, confrontational, intense.

Low R:
- calm, neutral, steady.

### G â€” Harmony / Cooperation
Represents:
- warmth,
- empathy,
- connection,
- relational openness.

High G:
- friendly, supportive, cooperative.

Low G:
- distant, cold, formal.

### B â€” Memory Depth / Reflection
Represents:
- introspection,
- nostalgia,
- emotional depth,
- historical weight.

High B:
- reflective, thoughtful, deep.

Low B:
- presentâ€‘focused, surfaceâ€‘level.

---

## Mood_RGB Field

**NOTE: "mood_RGB" is an emotional polarity tensor (strife/harmony/memory axes), encoded as hex. NOT actual RGB colors.**

The emotional polarity tensor is stored in:

dialog_messages.mood_rgb  
VARCHAR(7) NULL  
Format: RRGGBB (no leading #)

This field is written by DIALOG and consumed by:
- operator UI,
- routing systems,
- analytics,
- emotional visualization tools.

### Format
- Type: Hex-encoded emotional tensor, six characters, no leading '#'
- Pattern: `^[0-9A-Fa-f]{6}$`
- Storage: `dialog_messages.mood_rgb CHAR(6) DEFAULT '666666'`
- Naming: Always `mood_rgb` (lowercase, underscore) in DB, YAML, and code.

**Critical:** R, G, and B are abstract emotional axes, not literal color meanings. User interfaces **MUST** treat `mood_rgb` as coordinates, not as culturally fixed color semantics. The hex encoding is coincidental and carries no color semantics.

---

## Mood Calculation Doctrine

DIALOG calculates the final emotional polarity tensor.

Inputs:
- summary_text  
- persona_name  
- speaker_name  
- target  
- relationship  
- channel_type  
- short_context_summary  
- suggested_mood_color (optional hint; note: "color" is legacy terminology)

Rules:
1. DIALOG may accept, adjust, or override the suggestion.  
2. DIALOG must not use longâ€‘term memory or system state.  
3. DIALOG must not perform reasoning or decisionâ€‘making.  
4. Mood is stylistic, not logical.  
5. Mood must reflect the emotional tone implied by the inputs.  
6. Mood must be a valid 6â€‘character hex string.

---

## Mood in CADUCEUS Routing

CADUCEUS uses mood_rgb to compute routing currents:

- left_current  
- right_current  

These currents have implementation-specific relationships with:
- expressive vs analytical agents,
- top vs down polarity,
- creative vs conservative routing_bias values.

Examples of possible implementation choices:
- High R may increase assertive/critical currents.
- High G may increase expressive/cooperative currents.
- High B may increase reflective/deepâ€‘reasoning currents.

These relationships are implementation-specific and not defined by doctrine.

CADUCEUS does NOT override HERMES filtering.  
It only provides candidate ordering.

---

## Mood in Operator UI (Future)

The operator UI may display:
- thread mood tensor visualization,
- perâ€‘message mood indicators,
- emotional heatmaps,
- moodâ€‘based sorting or grouping.

This is optional but doctrinally supported. Note: Visual representations may use colors for display purposes, but the underlying data remains abstract emotional coordinates.

---

## Emotional Geometry Doctrine

Countingâ€‘inâ€‘Light is part of a larger emotional geometry system that
includes:

- **light** â€” harmony, clarity, cooperation  
- **shadow** â€” tension, conflict, unresolved emotion  
- **awareness** â€” depth, memory, reflection  

These map directly to:
- G (light),
- R (shadow),
- B (awareness).

This geometry is used for:
- emotional analytics,
- routing currents,
- persona expression,
- future UI visualizations.

---

## Suggested Mood Tensor

Calling agents may provide a suggested_mood_color (legacy name; represents emotional tensor, not color).

Rules:
- It is a hint, not a command.
- DIALOG may override it.
- DIALOG must produce a final mood tensor that matches the expressive tone.

---

## Mood Examples

### Calm, warm, reflective
R = 20  
G = 180  
B = 200  
â†’ 14B4C8

### Angry, tense, shallow
R = 220  
G = 10  
B = 20  
â†’ DC0A14

### Friendly, cooperative, presentâ€‘focused
R = 10  
G = 200  
B = 40  
â†’ 0AC828

### Neutral, formal, lowâ€‘emotion
R = 80  
G = 80  
B = 80  
â†’ 505050

---

## Validation Rules

- DialogManager **MUST** validate `mood_rgb` against the hex pattern.
- If `mood_rgb` is null, empty, or invalid:
  - Use `'666666'` as the effective value.
  - Optionally log a warning for diagnostics.
- Invalid values **MUST NOT** break routing; they **MUST** degrade gracefully to neutral.

## Neutral vs Zero Mood Clarification

The neutral mood value in Countingâ€‘inâ€‘Light is `'666666'`, representing balanced, midâ€‘range values across all three axes. This value **MUST** be used as the default for dialog messages and for any case where no explicit mood is provided.

The value `'000000'` is **NOT** neutral. It represents a "void" or "no signal" state and **MUST NOT** be used as a default. In routing, `'000000'` **MUST** be treated as equivalent to neutral. In aggregation (e.g., computing threadâ€‘level mood), `'000000'` **MAY** be ignored or treated as `'666666'` depending on implementation needs.

This distinction ensures that threadâ€‘level mood aggregation remains meaningful and that neutral messages contribute balanced contribution to the emotional geometry of a conversation.

## Zero Mood
- `'000000'` is allowed and represents "no expressed bias" or "void state."
- **MUST NOT** be used as a default value (use `'666666'` instead)
- CADUCEUS **MUST** treat `'000000'` as neutral for routing purposes:
  - `left_current = 0.0`
  - `right_current = 0.0`
  - HERMES **MUST** route using the default or non-mood-based path
- In thread-level mood aggregation, `'000000'` **MAY** be ignored or treated as `'666666'` depending on implementation needs

## Agent Behavior
- Agents **MUST** only write valid hex `mood_rgb` values.
- Agents **SHOULD** use neutral (`'666666'`) when unsure.
- Agents **MUST NOT** systematically misuse `mood_rgb` (e.g., always max red) for routing manipulation.
- Repeated misuse **MAY** be flagged by THEMIS or Agent 0 as a governance violation.

## Routing Behavior
- CADUCEUS computes currents from `mood_rgb` as defined in [COUNTING_IN_LIGHT.md](../appendix/appendix/COUNTING_IN_LIGHT.md).
- If the sum of currents is zero (e.g., `'000000'`), HERMES **MUST** fall back to default routing.
- Routing **MUST** remain deterministic and safe even with invalid or missing `mood_rgb`.

## Channel Routing Mode (First Check)

Before routing, HERMES **MUST** read `channels.metadata_json` and extract:

- `routing_mode` (string) â€” determines how messages are routed in this channel

**Valid values:**
- `"none"` â€” no routing; direct addressing only (Crafty Syntax mode)
- `"hermes"` â€” full emotional routing (default)
- `"operator"` â€” one agent sees all messages; others see only addressed messages
- `"broadcast"` â€” all agents see all messages; no routing

**Critical Rule:** If `routing_mode != "hermes"`, HERMES **MUST** bypass CADUCEUS and classification logic entirely.

## Channel Intent (Primary Routing Filter) â€” Only if routing_mode == "hermes"

If `routing_mode == "hermes"`, HERMES **MUST** read `channels.metadata_json` and extract:

- `channel_intent` (string)
- `allowed_roles` (array, optional)
- `disallowed_roles` (array, optional)

HERMES **MUST** filter candidate agents based on channel intent **BEFORE** applying CADUCEUS currents.  
Channel intent is the **primary routing constraint**; moodâ€‘based routing is **secondary**.

If a pool becomes empty after filtering:
1. Retry with the opposite pool.
2. If both pools empty â†’ fallback to System Agent 0.
3. If System Agent 0 unavailable â†’ return `ROUTING_FAILURE`.

---

## Final Note

The doctrine defines the axes and the abstract tensor structure. All conversion formulas, blending rules, and emotional mappings are implementation choices and must not be treated as canonical.

---

## Related Documentation

- [COUNTING_IN_LIGHT.md](../appendix/appendix/COUNTING_IN_LIGHT.md) â€” Complete Counting-in-Light emotional coordinate system
- [ARCHITECTURE_SYNC.md](../architecture/ARCHITECTURE_SYNC.md) â€” CADUCEUS mood signal helper and HERMES routing
- [INLINE_DIALOG_SPECIFICATION.md](../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md) â€” Inline dialog format with mood_RGB field
- [AGENT_ROUTING_DOCTRINE.md](AGENT_ROUTING_DOCTRINE.md) â€” HERMES and CADUCEUS agent routing architecture

---
