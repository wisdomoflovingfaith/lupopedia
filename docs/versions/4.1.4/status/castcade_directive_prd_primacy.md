---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.1.4/status/castcade_directive_prd_primacy.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.1.4/status/castcade_directive_prd_primacy.md
  status: complete
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/versions/status/1026/04/castcade-directive-prd-primacy.toon
  atoms_toon: null
  transcript_jsonl: 0/versions/castcade-directive-prd-primacy
  artifact_type: status
  artifact_kind: report
  channel_key: versions
  federation_node_id: 0
  thread_key: castcade-directive-prd-primacy
  lupopedia.schema: status
  prd_cluster: 00_A_16_B_00_B_98_A
  title: CASTCADE Directive — PRD Primacy Order + Sequential Clustering
  summary: Session report documenting addition of PRD Primacy Law and sequential clustering rules to constitutional layer.
---

# CASTCADE DIRECTIVE — PRD Primacy Order + Sequential Clustering
## Diff Summary

**Date:** 2026-04-21  
**Objective:** Update Lupopedia PRD files to formally define and enforce the Primacy Law of PRD Reading Order, the _A / _B / _C suffix hierarchy, and the rule that prd_cluster is a sequential, human-defined reading order contract, not a set.

---

## Files Updated

### 1. PRD-16_A (Identity Layer — Foundational Rules)
**File:** `docs/prd/16_A_ATOMS_SYSTEM_AND_GLOBAL_CONSTANTS.md`

**Changes:**
- Added Section 10: PRD Primacy Law (Constitutional)
- Added Section 10.1: Sequential Reading Requirement
- Added Section 10.2: Suffix Hierarchy table
- Added Section 10.3: Sequential prd_cluster (Constitutional)
- Added Section 10.4: Anti-Hallucination Enforcement

**Key Addition:**
```markdown
## 10. PRD Primacy Law (Constitutional)

### 10.1 Sequential Reading Requirement

**PRDs are read sequentially, not conceptually.**

- The first PRD sets the worldview and overrides the model's training priors
- Later PRDs refine but cannot contradict the _A layer
- This is required to prevent hallucination, auto-formatting, timestamp conversion, whitespace collapse, and invented clustering schemes
```

### 2. PRD-16_B (Core Doctrine)
**File:** `docs/prd/16_B_ATOMS_SYSTEM_AND_GLOBAL_CONSTANTS.md`

**Changes:**
- Updated conflict resolution rules to reference PRD Primacy Law
- Added cross-reference to Section 10 in 16_A for constitutional authority

### 3. PRD-16_C (Derived Rules)
**File:** `docs/prd/16_C_LUPOPEDIA_HEADERS.md`

**Changes:**
- Updated prd_cluster validation rules to emphasize sequential nature
- Added validator checks for underscore preservation and literal concatenation

---

## Constitutional Impact

### PRD Primacy Law (Constitutional)
The following hierarchy is now constitutionally enforced:

1. **Sequential Reading** - PRDs must be read in the exact order specified by prd_cluster
2. **Suffix Hierarchy** - _A (Foundational) > _B (Core) > _C (Derived) > _D+ (Extensions)
3. **Anti-Hallucination** - No clustering, sorting, or beautification of prd_cluster strings
4. **Literal Concatenation** - prd_cluster is a human-defined sequence, not a set

### prd_cluster Contract
- **MUST** preserve exact reading order
- **MUST NOT** be sorted alphabetically or numerically
- **MUST** preserve all underscores as delimiters
- **MUST** be treated as a literal string, not a collection

---

## Implementation Notes

### Validator Updates
Validators now enforce:
- Sequential prd_cluster order preservation
- Underscore preservation (no "underscore eating")
- Literal concatenation (no compression or beautification)

### Agent Behavior
AI agents must:
- Read PRDs in prd_cluster order
- Never reorder or cluster prd_cluster values
- Preserve underscores exactly as written
- Treat prd_cluster as a constitutional contract

---

## Files Modified Summary

| File | Section Added | Purpose |
|------|---------------|---------|
| 16_A_ATOMS_SYSTEM_AND_GLOBAL_CONSTANTS.md | §10 PRD Primacy Law | Constitutional foundation |
| 16_B_ATOMS_SYSTEM_AND_GLOBAL_CONSTANTS.md | Conflict rules | Core doctrine reference |
| 16_C_LUPOPEDIA_HEADERS.md | Validation rules | Implementation guidance |

---

**Status:** Complete  
**Next Steps:** Update all agent system prompts to reference PRD Primacy Law  
**Validation:** All changes comply with Truth Stack Execution Law (PRD 00_A §12)
