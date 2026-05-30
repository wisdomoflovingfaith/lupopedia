---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: null
  file_path_from_root: "docs/versions/4.0.86/WHAT_TO_DO_NEXT_SESSION.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.86/WHAT_TO_DO_NEXT_SESSION.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: session_guide
  artifact_kind: next_session_planning
  thread_id: "1001"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: "SESSION_GUIDE_CREATED"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# WHAT_TO_DO_NEXT_SESSION — 4.0.86

## 1. OBJECTIVE

Create a deterministic session guide for continuing work on version 4.0.86.

File location:
`docs/versions/4.0.86/WHAT_TO_DO_NEXT_SESSION.md`

---

## 2. PURPOSE

This file must:
- Prevent loss of context
- Define exact next steps
- Separate THINKING (ROSE) from IMPLEMENTATION (ATHENA/HEPHAESTUS)
- Define correct execution order

---

## 3. REQUIRED STRUCTURE

---

# WHAT_TO_DO_NEXT_SESSION — 4.0.86

---

## SECTION 1 — CURRENT SYSTEM STATE (TRUTH)

Summarize:

- What is COMPLETE
- What is PARTIAL
- What is NOT DONE

Must include:

- TG-8 status (accepted with risks)
- Phase 3 enforcement status (inactive)
- Channel 58 status
- Channel 59 status
- Channel 60 status
- Channel 61 status

---

## SECTION 2 — CRITICAL BLOCKERS

List ONLY blockers preventing 4.0.86 completion:

- Phase 3 enforcement not active
- Actor vs faucet identity enforcement missing
- Actor system incomplete
- ROSE system incomplete
- 22-agent requirement incomplete

---

## SECTION 3 — ROSE/DIALOG (THINKING PHASE)

Define what must be discussed BEFORE coding:

### ROSE TOPICS

1. Actor vs Faucet Identity Model
2. Enforcement Timing (when Phase 3 should activate)
3. ROSE packet + mood system final shape
4. Agent system philosophy (what 22 agents actually represent)
5. Whether to finish 4.0.86 vs cut to 4.0.87

Each topic must include:

- Purpose
- Expected outcome
- What decision is needed

---

## SECTION 4 — ATHENA (STRATEGY PHASE)

Define structured plans AFTER ROSE discussion:

1. Phase 3 enforcement activation plan
2. Actor system completion plan (Channel 58)
3. ROSE system implementation plan (Channel 59)
4. Agent system completion plan (Channel 60)

---

## SECTION 5 — HEPHAESTUS (IMPLEMENTATION ORDER)

STRICT ORDER:

1. Fix / activate Phase 3 enforcement
2. Complete Actor system (Channel 58)
3. Implement ROSE system (Channel 59)
4. Complete 22-agent system (Channel 60)

Each step must include:

- Exact files
- Services
- Constraints

---

## SECTION 6 — VALIDATION GATES (LILITH)

Define required validation points:

- Post enforcement activation
- Post actor system completion
- Post ROSE system implementation
- Final system validation

---

## SECTION 7 — COMPLETION DEFINITION

Define EXACTLY:

```text
What makes 4.0.86 DONE
```

Must align with:

- PLAN.md
- TODO.md
- SCOPE_LOCK_SUMMARY.md

---

## SECTION 8 — SESSION START CHECKLIST

Next time system starts:

1. Read this file
2. Start with ROSE discussion
3. Do NOT jump to implementation
4. Follow order strictly

---

## 4. CONSTRAINTS

MUST reflect actual system state (no optimism):
MUST align with existing docs:
- CHANGELOG.md
- PLAN.md
- TODO.md
MUST be deterministic and ordered
MUST separate thinking vs doing

---

## 5. GOAL

Produce:
A SINGLE SOURCE OF TRUTH FOR WHAT TO DO NEXT

ROSE canon reference:
- docs/doctrine/ROSE_DOCTRINE.md
- docs/doctrine/IDENTITY_MODEL.md

---

## 6. NEXT STEP

After file creation:

→ Post summary to Channel 42
→ All actors will use this as next session entry point

---

**status:** NEXT_SESSION_GUIDE_REQUIRED
**output:** WHAT_TO_DO_NEXT_SESSION.md
