---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: null
  file_path_from_root: "docs/versions/4.0.85/doctrine_changes/mood_vector_hybrid_model.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: doctrine_overlay
  thread_id: 2015
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# 4.0.85 mood_vector Hybrid Model

## Purpose

This document is the canonical 4.0.85 version-scoped summary of Thread 2015 final outcomes.

The root doctrine remains:

- `docs/doctrine/MOOD_VECTOR_DOCTRINE.md`

This file states what 4.0.85 made authoritative so readers do not need the Thread 2015 artifacts.

## Final Model

`mood_vector` is a hybrid system with two layers.

### Layer 1 - Canonical Tokens (Authoritative)

The authoritative 4.0.85 canonical tokens are:

- `FF0000`
- `00FF00`
- `666666`
- `B1B1B1`
- `88FF88`

These tokens are decision-safe and define required agent behavior.

### Layer 2 - Continuous Vector (Non-Authoritative)

All syntactically valid six-hex values remain allowed in runtime.

These values may influence:

- CADUCEUS numeric currents
- HERMES routing bias
- telemetry and signal weighting

They do not define strong semantic authority by themselves.

## Binding Precedence Rule

If `mood_vector` is canonical:

- canonical behavior must apply

If `mood_vector` is not canonical:

- treat it as vector-only influence
- do not infer strong semantic meaning
- do not use it alone for blocking, approval, contradiction closure, or final judgment

## Canonical Behavioral Contract

- `FF0000`
  - block or escalate
  - requires correction behavior
- `00FF00`
  - approve or proceed
  - may support closure/pass behavior
- `B1B1B1`
  - clarification required
  - open question/follow-up behavior
- `666666`
  - explicit neutral signal
  - no action from token alone
- `88FF88`
  - positive/supportive response signal
  - not governance-grade approval by itself

## Runtime Alignment Rule

CADUCEUS and HERMES may use any valid RGB values numerically, but that numeric processing is routing influence only, not semantic authority.

## 4.0.85 Outcome

Thread 2015 resolved the structural conflict identified by LILITH:

- token semantics now have bounded authority
- continuous values now have bounded non-authoritative influence
- doctrine no longer leaves the system split between incompatible discrete and continuous models
