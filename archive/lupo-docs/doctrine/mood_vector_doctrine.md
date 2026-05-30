---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/MOOD_VECTOR_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/MOOD_VECTOR_DOCTRINE.md"
  status: ""
  when_updated: "20260404190000"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: documentation
  channel_key: null
  federation_node_id: 0
  thread_id: "mood-vector-system"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# MOOD_VECTOR Doctrine (summary)

**Canonical on-disk source:** **`lupo-channels/0/semantic/mood_vector_system/`** — thread **`mood_vector_system`** under channel **`semantic`** (`federation_node_id` **0**). Formal decisions, archive evidence pointers, and ongoing notes live there per **`lupo-docs/prd/17_decisions_format.md`**.

This file remains the **root doctrine entry** in **`lupo-docs/doctrine/`**: a **short summary**. Detailed decision records and **archive evidence** edges are in:

- **`…/decisions/20260404_190000_DECISION_APPROVED_mood_vector_evidence_sources.md`** — evidence links to **`lupo-channels_before_4_0_93/42/threads/...`** artifacts  
- **`…/decisions/20260404_190100_DECISION_APPROVED_mood_vector_color_definitions.md`** — canonical tokens and R/G/B meanings  

---

## What `mood_vector` is

A **six-hex-digit** semantic state vector (`RRGGBB`). In Lupopedia it is **not** only a display color: it encodes **semantic posture** for dialog, routing helpers, audits, and headers. See **Counting-in-Light** for axis vocabulary.

Hybrid model:

1. **Canonical tokens (authoritative)** — decision-safe for directives, gates, audits  
2. **Continuous vector (non-authoritative)** — numeric influence for CADUCEUS/HERMES; not sole decision authority  

Companion field: **`mood_label`** — human-readable; does not replace `mood_vector` for routing or validation.

---

## Core rules (binding)

- Storage: `^[0-9A-Fa-f]{6}$`, no `#` in DB/YAML storage; default **`666666`**.  
- **If** value is a **canonical token** → apply canonical semantics.  
- **Else** → treat as **vector-only**; do not infer blocking/approval/gap semantics from arbitrary hex alone.  

Authoritative token set (decision-safe): **`FF0000`**, **`00FF00`**, **`666666`**, **`B1B1B1`**, **`88FF88`**. See the **color definitions** decision for R/G/B channel interpretation.

---

## Implementation surfaces

| Area | Role |
|------|------|
| `lupo-api/dialog/send-message.php` | Validates hex shape; default `666666` |
| `DialogManager` | Stores mood; positive response default `88FF88` |
| `Caduceus::computeCurrents()` | R/G/B → left/right currents (routing influence) |
| `HERMES` | Uses currents when destination not explicit |
| `lupo-api/v1/dialog/metrics.php` | Telemetry by `mood_vector` |

---

## Limitations (current)

- No validator enforces canonical-token-only emission for all artifacts.  
- Fallback `666666` vs explicit neutral is not distinguished in storage alone.  
- **`mood_label`** recommended but not universally required on all transports.  

---

## Future work

Any extension (new canonical tokens, thresholds, UI rules) should be recorded as **decisions** under **`mood_vector_system`** and summarized here.

---

## Long-form history

The pre–4.0.94 monolithic doctrine body (examples, long channel notes, full agent/runtime sections) is **replaced by this summary**; substance is split between this file, the **two approved decisions** above, and **git history** for the previous single-file version.
