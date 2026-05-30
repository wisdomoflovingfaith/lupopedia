---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  channel_id: 42
  actor_id: 15
  actor_name: "hermes"
  artifact_type: "hermes_prompt"
  artifact_kind: "execution_handoff"
  purpose: "Handoff to wolfie from source artifact"
  target_actor_slug: "wolfie"
  source_artifact: "channels/42/threads/1001/20260317_231700_wisdomoflovingfaith_fix-the-system-now.md"
---

# file: HERMES prompt -> wolfie

## Source

- Artifact: `channels/42/threads/1001/20260317_231700_wisdomoflovingfaith_fix-the-system-now.md`
- Routed by: draft_hermes_prompt_from_artifact.py (review before execution)

## Task for wolfie

Execute the work implied by the source artifact. Post result as channel artifact in the appropriate thread; do not claim HERMES authored your output.

## Source excerpt

# file: Human directive — fix the system now — thread 1001

# Directive — Fix the Channel + HERMES System

This is a system-level directive.

---

## Current State

The intended system:

- actors write artifacts into channels
- HERMES reads them
- HERMES generates prompts
- prompts are routed to correct actors

---

## Reality

This is NOT working.

- manual copy/paste between agents
- no reliable routing
- inconsistent behavior
- broken workflow
- high cognitive overhead

👉 The system is increasing work instead of reducing it.

---

## Required Outcome

We need a working loop:

**channel message → HERMES → prompt → actor → result → channel**

---

## Fix Requirements

WOLFIE must:

### 1. Define minimal working system
- what MUST work first
- remove anything unnecessary

### 2. Stabilize HERMES behavior
- HERMES only routes + generates prompts
- never impersonates
- consistent output format

### 3. Define prompt pipeline
- where prompts are written (`/prompts/`)
- how they are structured
- how actors consume them

### 4. Reduce manual steps (CRITICAL)
- eliminate copy/paste loop
- define at least semi-automated flow
- propose scripts or tooling if needed

### 5. Enforce consistency
- artifact locations
- filenames
- thread usage
- actor roles

---

## Expected Next Steps

- system simplification
- routing enforcement
- clear execution loop
- minimal viable workflow that actually works

---

## Deliverable

WOLFIE must produce:

1. A system stabilization plan
2. Clear working loop definition
3. Immediate fixes (not theory)
4. Next implementation steps

---

## Final Instruction

Fix the system so it actually works.

Right now it does not.

---

**From:** Human actor 1000  
**Priority:** CRITICAL

