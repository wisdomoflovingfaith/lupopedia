---
lupopedia.headers:
  version_when_written: "4.0.94"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/MOOD_RGB_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/MOOD_RGB_DOCTRINE.md"
  last_modified_utc: "20260404190000"
  when_updated: "20260404190000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "mood-rgb-system"
  actor_id: 4
  actor_name: "athena"
  delegation_chain: "athena:wolfie"
  artifact_type: "doctrine"
  artifact_kind: "documentation"
  purpose: "Summary doctrine for mood_rgb; canonical on-disk thread records decisions and archive evidence"
  tags: ["mood_rgb", "dialog", "routing", "semantic_state", "doctrine", "4.0.94"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/0/semantic/mood_rgb_system/README.md", type: "canonical_thread", weight: 1.0, reason: "On-disk canonical coordination for Mood RGB (decisions, questions, answers, comments)." }
    - { to: "lupo-channels/0/semantic/mood_rgb_system/decisions/20260404_190000_DECISION_APPROVED_mood_rgb_evidence_sources.md", type: "references", weight: 1.0, reason: "Archive evidence edges for operational tokens." }
    - { to: "lupo-channels/0/semantic/mood_rgb_system/decisions/20260404_190100_DECISION_APPROVED_mood_rgb_color_definitions.md", type: "references", weight: 1.0, reason: "Canonical tokens and channel definitions." }
    - { to: "lupo-docs/doctrine/COUNTING_IN_LIGHT.md", type: "complements", weight: 1.0, reason: "Counting-in-Light explains the axis model behind mood_rgb." }
    - { to: "dialog.yaml", type: "formalizes", weight: 1.0, reason: "dialog.yaml defines mood_RGB as a six-hex semantic field." }
    - { to: "lupo-api/dialog/send-message.php", type: "formalizes", weight: 1.0, reason: "API input validation and defaults." }
    - { to: "lupo-api/v1/dialog/metrics.php", type: "formalizes", weight: 0.9, reason: "Metrics aggregates mood values as telemetry." }
    - { to: "lupo-includes/classes/caduceus.php", type: "formalizes", weight: 1.0, reason: "CADUCEUS derives routing currents from R, G, B." }
    - { to: "lupo-includes/classes/hermes.php", type: "formalizes", weight: 1.0, reason: "HERMES consumes CADUCEUS currents when no explicit destination exists." }
    - { to: "lupo-docs/channels/doctrine/MOOD_RGB_DOCTRINE.md", type: "supersedes_in_root_doctrine", weight: 0.8, reason: "Older channel-doctrine copy is lineage only." }

    - to: "lupo-docs/prd/28_semantic_monitoring_widget.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260404190000"
  last_verified_by: "athena"
  orchestrator: "wolfie"
  next_action:
    - "Extend lupo-channels/0/semantic/mood_rgb_system/ for new Mood RGB decisions and comments."
    - "Treat non-canonical mood_rgb values as vector-only routing influence unless a later decision extends authority."
---

# MOOD_RGB Doctrine (summary)

**Canonical on-disk source:** **`lupo-channels/0/semantic/mood_rgb_system/`** — thread **`mood_rgb_system`** under channel **`semantic`** (`federation_node_id` **0**). Formal decisions, archive evidence pointers, and ongoing notes live there per **`lupo-docs/prd/17_decisions_format.md`**.

This file remains the **root doctrine entry** in **`lupo-docs/doctrine/`**: a **short summary**. Detailed decision records and **archive evidence** edges are in:

- **`…/decisions/20260404_190000_DECISION_APPROVED_mood_rgb_evidence_sources.md`** — evidence links to **`lupo-channels_before_4_0_93/42/threads/...`** artifacts  
- **`…/decisions/20260404_190100_DECISION_APPROVED_mood_rgb_color_definitions.md`** — canonical tokens and R/G/B meanings  

---

## What `mood_rgb` is

A **six-hex-digit** semantic state vector (`RRGGBB`). In Lupopedia it is **not** only a display color: it encodes **semantic posture** for dialog, routing helpers, audits, and headers. See **Counting-in-Light** for axis vocabulary.

Hybrid model:

1. **Canonical tokens (authoritative)** — decision-safe for directives, gates, audits  
2. **Continuous vector (non-authoritative)** — numeric influence for CADUCEUS/HERMES; not sole decision authority  

Companion field: **`mood_label`** — human-readable; does not replace `mood_rgb` for routing or validation.

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
| `lupo-api/v1/dialog/metrics.php` | Telemetry by `mood_rgb` |

---

## Limitations (current)

- No validator enforces canonical-token-only emission for all artifacts.  
- Fallback `666666` vs explicit neutral is not distinguished in storage alone.  
- **`mood_label`** recommended but not universally required on all transports.  

---

## Future work

Any extension (new canonical tokens, thresholds, UI rules) should be recorded as **decisions** under **`mood_rgb_system`** and summarized here.

---

## Long-form history

The pre–4.0.94 monolithic doctrine body (examples, long channel notes, full agent/runtime sections) is **replaced by this summary**; substance is split between this file, the **two approved decisions** above, and **git history** for the previous single-file version.
