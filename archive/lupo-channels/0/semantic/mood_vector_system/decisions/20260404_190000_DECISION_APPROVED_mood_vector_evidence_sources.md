---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260404190000"
  file_path_from_root: "lupo-channels/0/semantic/mood_vector_system/decisions/20260404_190000_DECISION_APPROVED_mood_vector_evidence_sources.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-channels/0/semantic/mood_vector_system/decisions/20260404_190000_DECISION_APPROVED_mood_vector_evidence_sources.md"
  last_modified_utc: "20260404190000"
  federation_node_id: 0
  channel_id: 0
  thread_id: "mood-vector-system"
  actor_id: 102
  actor_name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "decision"
  artifact_kind: "channel_decision"
  purpose: "Record archive evidence threads used to validate Mood Vector operational tokens"
  status: "approved"
  tags:
    - "mood_vector"
    - "decision"
    - "evidence"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/MOOD_VECTOR_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Root doctrine summary"
    - to: "lupo-channels_before_4_0_93/42/threads/1037/20260321_160000_lilith_versioning_doctrine_gap_analysis.md"
      type: evidence
      weight: 0.9
      reason: "B1B1B1 used in live ambiguity/gap-analysis work"
    - to: "lupo-channels_before_4_0_93/42/threads/1045/20260321_185000_wolfie_system_correction_directive.md"
      type: evidence
      weight: 0.9
      reason: "FF0000 used in mandatory correction directives"
    - to: "lupo-channels_before_4_0_93/42/threads/1045/20260321_193000_wolfie_phase_2_gate_pass.md"
      type: evidence
      weight: 0.9
      reason: "00FF00 used for gate-pass and approval states"
    - to: "lupo-channels_before_4_0_93/42/threads/1036/20260321_150000_athena_canonical_actor_architecture_and_repair_plan.md"
      type: evidence
      weight: 0.85
      reason: "666666 used for neutral architectural analysis"
lupopedia.footer:
  last_verified: "20260404190000"
  verified_by:
    actor_id: 102
  orchestrator: "cursor:root"
---

# DECISION (APPROVED): Mood Vector — evidence sources

**Status:** APPROVED  
**UTC:** 20260404_190000

## Summary

Operational **mood_vector** semantics in Lupopedia were validated against live thread artifacts in **channel 42** (pre–4.0.93 on-disk tree). Those files are preserved under **`lupo-channels_before_4_0_93/42/`** — not under the active **`lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/`** tree.

The **`lupopedia.edges`** block above retains the **evidence** links to those archive paths so validators and agents can trace token usage (e.g. `B1B1B1`, `FF0000`, `00FF00`, `666666`) to real artifacts.

## Scope

- Historical evidence only; does not require recreating numeric channel 42 under active `lupo-channels/`.
- New Mood Vector decisions and narrative should extend this thread and **`lupo-docs/doctrine/MOOD_VECTOR_DOCTRINE.md`** (summary).
