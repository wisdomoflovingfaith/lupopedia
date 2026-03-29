---
lupopedia.headers:
  version_when_written: "4.0.88"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/COUNTING_IN_LIGHT.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/COUNTING_IN_LIGHT.md"
  last_modified_utc: "20260327_000000"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:wolfie"
  artifact_type: "doctrine"
  artifact_kind: "canonical_axis_reference"
  purpose: "Canonical doctrine-level explanation of Counting-in-Light as the axis model behind mood_rgb and mood_label interpretation."
  tags: ["counting_in_light", "mood_rgb", "mood_label", "doctrine", "4.0.88"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/MOOD_RGB_DOCTRINE.md", type: "complements", weight: 1.0, reason: "MOOD_RGB doctrine defines current operational semantics and canonical token authority." }
    - { to: "dialog.yaml", type: "formalizes", weight: 0.9, reason: "Dialog spec carries the message-level mood field shape." }
    - { to: "lupo-docs/doctrine/ROSE_DOCTRINE.md", type: "applies_to", weight: 0.9, reason: "ROSE commentary relies on Counting-in-Light interpretation." }
    - { to: "lupo-agents/3/COUNTING_IN_LIGHT.md", type: "guides", weight: 0.8, reason: "DIALOG agent guide uses this axis model operationally." }
    - { to: "lupo-docs/channels/appendix/appendix/COUNTING_IN_LIGHT.md", type: "supersedes_as_canonical_home", weight: 0.8, reason: "Appendix copy is retained as lineage/mirror, but doctrine path is canonical." }

lupopedia.footer:
  last_verified: "20260327_000000"
  last_verified_by: "Cursor IDE Agent (Lead Orchestration)"
  approval_status: "approved"
  approval_target_version: "4.0.88"
  next_action:
    - "Use this doctrine path as the canonical home for Counting-in-Light references."
    - "Treat the appendix copy as a mirrored lineage/reference surface, not the primary canonical location."
---

# COUNTING_IN_LIGHT Doctrine

Counting-in-Light is the doctrine-level axis model behind `mood_rgb`.

It explains why the repository stores a six-hex `RRGGBB` token while insisting that the field is not merely decorative UI color. The stored value is a compact, RGB-shaped encoding of a three-axis semantic mood/light vector.

## Canonical Role

Use this file for:

- the conceptual axis model behind `mood_rgb`
- the explanation for why `mood_rgb` looks like color but is not only color
- the relationship between axis semantics, human interpretation, and `mood_label`

Do not use this file alone to infer current runtime token authority. For binding operational behavior, canonical tokens, and decision-safe semantics, use [lupo-docs/doctrine/MOOD_RGB_DOCTRINE.md](lupo-docs/doctrine/MOOD_RGB_DOCTRINE.md).

## The Three Axes

### R - Strife / Chaos / Conflict Intensity

Represents urgency, danger, agitation, and blocking pressure.

### G - Harmony / Attachment / Cohesion Intensity

Represents reassurance, support, cohesion, and stabilizing alignment.

### B - Memory Depth / Persistence Weight

Represents reflection, retained context, significance beyond the moment, and interpretive depth.

## Storage Format

- canonical stored shape: `RRGGBB`
- storage form: six hex digits, no leading `#`
- canonical field name: `mood_rgb`

The name is historically confusing. It reads like a simple display color, but in Lupopedia it is primarily semantic metadata.

## mood_label Companion Field

`mood_label` is the human-readable companion to `mood_rgb`.

Use it to explain the intended reading of the vector without requiring human readers to decode hex mentally.

Examples:

- `mood_rgb: "666666"` + `mood_label: "neutral coordination"`
- `mood_rgb: "FF4400"` + `mood_label: "critical review"`
- `mood_rgb: "3399CC"` + `mood_label: "understanding insight"`
- `mood_rgb: "CC0000"` + `mood_label: "critical error"`
- `mood_rgb: "00FF00"` + `mood_label: "stabilizing guidance"`
- `mood_rgb: "0000FF"` + `mood_label: "reflective memory"`

`mood_label` is recommended wherever humans are expected to read mood-bearing output, and strongly preferred for long-form ROSE commentary.

## ROSE Relevance

ROSE is the persona most likely to emit long-form interpretive commentary after reading many channel threads.

For ROSE-style commentary:

- `mood_rgb` remains the machine-readable encoded signal
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