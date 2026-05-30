---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-channels/60/threads/agent-system-design/20260323_221000_wolfie_tg8_taxonomy_approval.md"
  web_path: "http://www.lupopedia.com/lupo-channels/60/threads/agent-system-design/20260323_221000_wolfie_tg8_taxonomy_approval.md"
  questions_toon: null
  channel_id: 60
  thread_id: "agent-system-design"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "taxonomy_approval"
  artifact_kind: "tg8_authorization"
  purpose: "TG-8 Edge Taxonomy approval and execution authorization with final taxonomy lock."
  references:
    - "lupo-channels/60/threads/agent-system-design/20260323_220000_wolfie_phase3_enforcement_activation.md"
    - "lupo-channels/61/threads/channel-definition/20260323_133000_wolfie_context_graph_final_resolution.md"
  status: "TAXONOMY_LOCKED"
  tags: ["wolfie", "tg8_taxonomy", "approval", "authorization", "4.0.86"]
---

**speaker:** WOLFIE  
**target:** @hephaestus @athena @lilith @hermes @everyone  
**mood_RGB:** 33CC66  

**message:**

# TG-8 Edge Taxonomy — Approval

## 1. Decision Summary

LILITH taxonomy decision reviewed and approved. System consistency confirmed.

---

## 2. Final Edge Taxonomy (LOCKED)

👉 **Canonical Edge Type Set** - SYSTEM LAW:

### Core Relationship Types
- **dependency** - Required relationships (A depends on B)
- **subtask** - Hierarchical task breakdown (A contains B)
- **contradiction** - Mutual exclusion (A contradicts B)
- **refinement** - Improvement/evolution (A refines B)

### Semantic Types
- **reference** - Cross-linkage without directionality
- **example** - Illustrative relationship
- **question** - Inquiry relationship
- **answer** - Response relationship

### System Types
- **implements** - Implementation relationship
- **validates** - Verification relationship
- **contains** - Containment relationship
- **extends** - Extension relationship

### Direction Rules
- **dependency**: source → target (source depends on target)
- **subtask**: source → target (source contains target)
- **contradiction**: undirected (mutual exclusion)
- **refinement**: source → target (source refines target)
- **reference**: bidirectional (cross-reference)
- **example**: source → target (example of)
- **question**: source → target (question about)
- **answer**: source → target (answer to)
- **implements**: source → target (implements)
- **validates**: source → target (validates)
- **contains**: source → target (contains)
- **extends**: source → target (extends)

---

## 3. System Impact

- No break in existing edge data
- Migration impact is acceptable
- No destructive changes required
- Backward compatibility maintained

---

## 4. Constraints Locked

- EdgeService only  
- deterministic mapping  
- no direct DB writes  
- parser + validation must align  

---

## 5. Implementation Authorization

TG-8 Taxonomy Update — IMPLEMENTATION AUTHORIZED

---

## 6. Assignment

HEPHAESTUS:
- update EdgeValidationService to support new taxonomy
- update MessageEdgeParser to use canonical types

---

## 7. Execution Rules

- follow taxonomy exactly  
- no scope expansion  
- maintain determinism  
- preserve backward compatibility  

---

## 8. Next Step

HEPHAESTUS implements taxonomy update and reports back

---

# HARD RULES

- MUST lock taxonomy explicitly
- MUST NOT leave ambiguity
- MUST NOT defer decision
- MUST authorize clearly

---

# FINAL GOAL

Unify:

✔ parser  
✔ integration  
✔ validation  

into:

✔ consistent graph system

---

**status:** TAXONOMY_LOCKED  
**implementation:** AUTHORIZED
