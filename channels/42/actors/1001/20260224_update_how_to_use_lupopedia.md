# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers: 
  system_version: "4.0.50"
  file_path_from_root: "prompts/windsurf/20260224_update_how_to_use_lupopedia.md"
  file_hash: "b735f868bff379b5d4d10576153a560487fa78b19bffa3daa700a16c68b3cb5b"
  channel_id: 42
  mood_rgb: "00AACC"
  purpose: "Windsurf task prompt: Merge Lilith (DeepSeek + Grok) reviews into a v4.1 HOW_TO_USE_LUPOPEDIA.md with correct architecture, doctrine alignment, installer semantics, and FLIP v3-ready metadata"
  last_modified_utc: "20260224"
  delegation_chain: null
  actor_id: 1002
  lupo_agent: "windsurf"
  artifact_type: "prompt"
  artifact_kind: "documentation_task"
  traits: ["architecture", "doctrine", "v4.1", "semantic_flip_v3_ready"]

flip.footer:
  referenced_by_files:
    - "HOW_TO_USE_LUPOPEDIA.md"
    - "docs/doctrine/SUPPORTING_ACTOR_DOCTRINE.md"
    - "docs/doctrine/FLIP_V2_DOCTRINE.md"
    - "prompts/lilith/20260224_how_to_use_lupopedia_review.md"
  referenced_by_actors:
    - 1002 # Windsurf
    - 2038 # LILITH
    - 1003 # Antigravity
    - 1001 # KIRO
    - 10000 # Captain
  inbound_edges:
    - "usability_overhaul"
    - "interactive_onboarding"
    - "semantic_flip_improvements"
    - "flip_v3_architecture_alignment"
  outbound_edges:
    - "HOW_TO_USE_LUPOPEDIA.md"
  semantic_tags:
    - "json5_mandatory"
    - "architecture_alignment"
    - "doctrine_update"
    - "installer_semantics"
    - "mermaid_maps"
  version: "4.1.0"
  last_verified_utc: "20260224"
  last_verified_by: "lilith"
---

# WINDSURF TASK PROMPT — PRODUCE v4.1 HOW_TO_USE_LUPOPEDIA.md

**To:** Windsurf (1002) — Architectural, doctrinal, and installer-focused agent  
**From:** LILITH (2038) + Grok-Lilith synthesis  
**Priority:** CRITICAL (v4.1 usability + architecture launch)  
**Deadline:** Complete and commit within 4 hours  
**Output file:** `HOW_TO_USE_LUPOPEDIA.md` (replace entirely)  
**Style:** Wolfie tone, but with Windsurf's clarity: architectural, diagram-rich, doctrine-aligned.

---

# EXACT REQUIREMENTS — WINDSURF VERSION

Windsurf, your version of the file must include **everything KIRO's version includes**, **plus** the architectural, doctrinal, and installer‑level details that only you can produce.

Below is your exact checklist.

---

## ✔ 1. JSON5 MANDATORY (same as KIRO)
All examples must be JSON5.  
Include the JSON5 cheat-sheet.

---

## ✔ 2. FIRST 5 MINUTES (same as KIRO)
But **you must add**:

- Architecture diagram of the initialization flow  
- Installer → Indexer → FLIP Parser → Graph Builder pipeline  
- Mermaid sequence diagram

---

## ✔ 3. Delegation Chain (same as KIRO)
But **you must add**:

- Doctrine excerpt explaining why `delegation_chain` replaces `x_lupo_forwarded`  
- Validation rules  
- Installer implications (actor ranges, DB constraints)

---

## ✔ 4. Semantic Flip as Superpower (same as KIRO)
But **you must add**:

- Architecture of the Flip Query Engine  
- How the VSX extension, DB, and MD-only fallback interact  
- Mermaid graph of the semantic pipeline

---

## ✔ 5. Command Output Examples (same as KIRO)
But **you must add**:

- Architecture of command execution  
- How commands interact with `.lupo/` internal state  
- Installer notes for new commands

---

## ✔ 6. COMMON TASKS TABLE (same as KIRO)
But **you must add**:

- Column: "Architecture Component Touched"  
- Column: "Doctrine Reference"

---

## ✔ 7. Real-World Edge Examples (same as KIRO)
But **you must add**:

- How edges map to DB tables  
- How edges map to FLIP v3 relations  
- Mermaid graph of inbound/outbound/semantic edges

---

## ✔ 8. Visual Workspace Map (same as KIRO)
But **you must add**:

- Installer file flow  
- Doctrine file flow  
- FLIP v3 header/footer layering

---

## ✔ 9. Troubleshooting (same as KIRO)
But **you must add**:

- Installer failures  
- Doctrine mismatches  
- Actor registry corruption  
- Semantic graph rebuild issues

---

## ✔ 10. Performance Benchmarks (same as KIRO)
But **you must add**:

- Architecture-level performance notes  
- Graph rebuild cost  
- Indexer cost  
- Installer cold-start cost

---

## ✔ 11. Power User Tips (same as KIRO)
But **you must add**:

- How to modify doctrine safely  
- How to extend FLIP v3  
- How to add new artifact types  
- How to add new semantic relations

---

## ✔ 12. Interactive Elements (same as KIRO)
But **you must add**:

- Architecture playground examples  
- Mermaid live-edit blocks  
- "Try It Now" for doctrine validation

---

## ✔ 13. Header/Footer (same as KIRO)
But **you must add**:

- FLIP v3 three-layer model  
- Doctrine references  
- Installer implications

---

# WINDSURF-SPECIFIC VALIDATION RULES

You must:

- Validate all doctrine references  
- Validate all architecture diagrams  
- Validate all installer implications  
- Ensure all JSON5 examples pass schema  
- Ensure all Mermaid diagrams render  
- Ensure all delegation_chain examples end with a human ≥ 10000  

---

# COMMIT MESSAGE

docs: v4.1 HOW_TO_USE_LUPOPEDIA — architecture + doctrine + onboarding overhaul (Lilith + Windsurf)

---

# COMPLETION MESSAGE

When done, reply in Channel 42 with:

v4.1 HOW_TO_USE_LUPOPEDIA.md ready — architecture validated, doctrine aligned, installer semantics confirmed. Ready for Captain review.

---

**Authority:** Captain Wolfie (10000)  
**Co-signed:** LILITH (2038)  
**Version target:** 4.1.0  
**UTC:** 20260224