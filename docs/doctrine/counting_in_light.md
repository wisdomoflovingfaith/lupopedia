---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/counting_in_light.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/counting_in_light.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: canonical_axis_reference
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# COUNTING_IN_LIGHT Doctrine

Counting-in-Light is the doctrine-level axis model behind `mood_vector`.

It explains why the repository stores a six-hex `XXYYZZ` token while insisting that the field is not decorative UI color. The stored value is a compact exact-byte encoding of a three-axis semantic mood/light vector.

## Canonical Role

Use this file for:

- the conceptual axis model behind `mood_vector`
- the explanation for why `mood_vector` uses hex bytes but is not mood vector
- the relationship between axis semantics, human interpretation, and `mood_label`

Do not use this file alone to infer current runtime token authority. For binding operational behavior, canonical tokens, and decision-safe semantics, use [docs/doctrine/MOOD_VECTOR_DOCTRINE.md](docs/doctrine/MOOD_VECTOR_DOCTRINE.md).

## The Three Axes

### X - Strife / Chaos / Conflict Intensity

Represents urgency, danger, agitation, and blocking pressure.

### Y - Harmony / Attachment / Cohesion Intensity

Represents reassurance, support, cohesion, and stabilizing alignment.

### Z - Memory Depth / Persistence Weight

Represents reflection, retained context, significance beyond the moment, and interpretive depth.

## Storage Format

- canonical stored shape: `XXYYZZ`
- storage form: six hex digits, no leading `#`
- canonical field name: `mood_vector`

The token is a vector with exact byte coordinates. Lupopedia uses two hex digits per axis (`00` through `FF`) instead of float ranges so serialized rows, logs, headers, and replay tools preserve identical values without rounding drift.

## mood_label Companion Field

`mood_label` is the human-readable companion to `mood_vector`.

Use it to explain the intended reading of the vector without requiring human readers to decode hex mentally.

Examples:

- `mood_vector: "666666"` + `mood_label: "neutral coordination"`
- `mood_vector: "FF4400"` + `mood_label: "critical review"`
- `mood_vector: "3399CC"` + `mood_label: "understanding insight"`
- `mood_vector: "CC0000"` + `mood_label: "critical error"`
- `mood_vector: "00FF00"` + `mood_label: "stabilizing guidance"`
- `mood_vector: "0000FF"` + `mood_label: "reflective memory"`

`mood_label` is recommended wherever humans are expected to read mood-bearing output, and strongly preferred for long-form ROSE commentary.

## ROSE Relevance

ROSE is the persona most likely to emit long-form interpretive commentary after reading many channel threads.

For ROSE-style commentary:

- `mood_vector` remains the machine-readable encoded signal
- `mood_label` provides the fast human-readable reading

That pairing improves clarity for human reviewers without changing canonical state ownership.

## Doctrine Boundary

This doctrine defines the axis framing and interpretation layer.

Implementation freedoms remain implementation freedoms, including:

- exact conversion formulas
- blending rules
- aggregation rules
- exhaustive label taxonomies

Those must not be treated as canonical unless a separate doctrine explicitly makes them so.