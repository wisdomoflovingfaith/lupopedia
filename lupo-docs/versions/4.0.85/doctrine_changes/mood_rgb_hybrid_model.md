---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "lupo-docs/versions/4.0.85/doctrine_changes/mood_rgb_hybrid_model.md"
  last_modified_utc: "20260322_184651"
  channel_id: 42
  thread_id: 2015
  actor_id: 4
  actor_name: "athena"
  artifact_type: "documentation"
  artifact_kind: "doctrine_overlay"
  purpose: "Version-scoped canonical summary of the 4.0.85 mood_rgb hybrid model resolved in Thread 2015."
---

# 4.0.85 mood_rgb Hybrid Model

## Purpose

This document is the canonical 4.0.85 version-scoped summary of Thread 2015 final outcomes.

The root doctrine remains:

- `lupo-docs/doctrine/MOOD_RGB_DOCTRINE.md`

This file states what 4.0.85 made authoritative so readers do not need the Thread 2015 artifacts.

## Final Model

`mood_rgb` is a hybrid system with two layers.

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

If `mood_rgb` is canonical:

- canonical behavior must apply

If `mood_rgb` is not canonical:

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
