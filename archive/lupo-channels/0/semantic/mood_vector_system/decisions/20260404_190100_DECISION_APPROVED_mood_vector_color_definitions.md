---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260404190000"
  file_path_from_root: "lupo-channels/0/semantic/mood_vector_system/decisions/20260404_190100_DECISION_APPROVED_mood_vector_color_definitions.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-channels/0/semantic/mood_vector_system/decisions/20260404_190100_DECISION_APPROVED_mood_vector_color_definitions.md"
  questions_toon: null
  federation_node_id: 0
  channel_id: 0
  thread_id: "mood-vector-system"
  actor_id: 102
  actor_name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "decision"
  artifact_kind: "channel_decision"
  purpose: "Canonical tokens and R/G/B channel meanings for mood_vector"
  status: "approved"
  tags:
    - "mood_vector"
    - "decision"
    - "tokens"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/MOOD_VECTOR_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Root doctrine summary"
    - to: "lupo-docs/doctrine/COUNTING_IN_LIGHT.md"
      type: complements
      weight: 1.0
      reason: "Axis model and naming"
lupopedia.footer:
  last_verified: "20260404190000"
  verified_by:
    actor_id: 102
  orchestrator: "cursor:root"
---

# DECISION (APPROVED): Mood Vector — color definitions and tokens

**Status:** APPROVED  
**UTC:** 20260404_190100

## Storage

- Form: **`RRGGBB`** (six hex digits, no `#` in storage)
- Default: **`666666`**
- Field name: **`mood_vector`** (see also companion **`mood_label`** for human readers)

## Two layers

1. **Canonical tokens (authoritative)** — decision-safe for audits, directives, gate states  
2. **Continuous vector (non-authoritative)** — CADUCEUS / HERMES routing influence only; not standalone decision authority

## Authoritative canonical tokens (4.0.85+)

| Token | Role | Notes |
|-------|------|--------|
| `FF0000` | Blocking / correction / directive | |
| `00FF00` | Approval / gate pass / proceed | |
| `666666` | Neutral observation / default | |
| `B1B1B1` | Ambiguity / gap / clarification | |
| `88FF88` | Positive response default (`DialogManager`) | Not governance-grade approval alone |

## R / G / B interpretation (operational)

- **R** — urgency, blocking pressure, correction demand  
- **G** — approval, alignment, completion, stabilization  
- **B** — reflection, ambiguity, clarification pressure, retained context depth  

## Precedence

If a value is a **canonical token**, apply canonical behavior. Otherwise treat as **vector-only** signal (numeric routing may apply; do not infer strong semantic authority from arbitrary hex alone).

Full narrative, runtime rules, CADUCEUS alignment, and examples — see **`lupo-docs/doctrine/MOOD_VECTOR_DOCTRINE.md`** (summary) and prior repository history for the long-form doctrine text.
