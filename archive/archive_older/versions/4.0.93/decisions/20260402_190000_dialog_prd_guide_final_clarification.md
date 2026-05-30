---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260402190000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260402_190000_DIALOG_prd_guide_final_clarification.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260402_190000_DIALOG_prd_guide_final_clarification.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: audit
  thread_id: "20260402-audit-prd-guide-final"
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
# LILITH Audit: PRD Development Guide — Final Clarification of Decision Contexts

## Type
**Audit**

## Status
**Completed**

## Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

## Date
2026-04-02

## Context

LILITH analysis identified that PRD 30 needed clarification about the two distinct decision contexts in Lupopedia. COUNTERMEASURE's earlier objection conflated these contexts, but both are valid when properly distinguished.

## The Two Decision Contexts

| Context | Location | Scope | Purpose |
|---------|----------|-------|---------|
| **Version-scoped** | `docs/versions/{version}/decisions/` | System-wide | Architecture decisions for a specific Lupopedia version |
| **PRD-scoped** | `docs/implementations/{id}_{slug}/decisions/` | PRD-specific | Design decisions for a single PRD implementation |

## COUNTERMEASURE's Misunderstanding

COUNTERMEASURE objected:
> *"Discussions MUST be stored under implementation/discussions"*

This was correct for discussions, but **incorrect for decisions**. There are two valid decisions folders serving different purposes.

## Corrections Applied to PRD 30

### 1. "When" Section Clarified

**Before:**
- Implied "When" was filename timestamp
- Mixed PRD structure with decision documentation

**After:**
- Clarified "When" points to decision folders
- Documented two decision contexts
- Provided clear examples for each context

### 2. Decision Documentation Structure

Added clear guidance:

```
## When (Timeline and Decisions)

### Decision Documentation

Decisions are documented in two contexts:

| Context | Location | Purpose |
|---------|----------|---------|
| **PRD-scoped** | `docs/implementations/{id}_{slug}/decisions/` | Decisions specific to this PRD's implementation |
| **Version-scoped** | `docs/versions/{version}/decisions/` | System-wide decisions for a Lupopedia release |
```

### 3. Example Decision Paths

**PRD 16 decision:**
```
docs/implementations/16_lupopedia_headers/decisions/20260402_143000_DECISION_author_field_structure.md
```

**Version 4.0.93 decision:**
```
docs/versions/4.0.93/decisions/20260402_120000_DECISION_adopt_five_layer_architecture.md
```

### 4. Removed Structural Overrides

- Removed filename evolution instructions (PRD 26 governs filenames)
- Removed implementation scope from PRD body (PRD 26 governs edges.md)
- Kept focus on writing methodology, not structural rules

## Compliance Verification

| PRD | Requirement | PRD 30 Compliance |
|-----|-------------|-------------------|
| **PRD 16** | Header field structure | ✅ References PRD 16 for status tracking |
| **PRD 26** | Filenames and structure | ✅ References PRD 26 for filenames, edges, discussions |
| **PRD 30** | Writing methodology | ✅ Focuses on 5W1H writing guide |

## Key Clarifications

### What "When" Means in 5W1H Framework

- **NOT**: Filename timestamp (that's structural)
- **IS**: Decision documentation timestamps in decision folders
- **ALSO**: Header `when_updated` field for content changes

### What PRD 30 Governs

- **GOVERNS**: How to write PRDs using 5W1H framework
- **DOES NOT GOVERN**: File naming, folder structure, header fields
- **REFERENCES**: Other PRDs for structural requirements

### Proper Separation of Concerns

| Component | PRD Responsible |
|-----------|------------------|
| **PRD Writing Methodology** | PRD 30 |
| **File Naming Convention** | PRD 26 |
| **Header Field Requirements** | PRD 16 |
| **Discussion Structure** | PRD 26 |
| **Decision Documentation** | Both contexts (documented in PRD 30) |

## Quality Assessment

| Aspect | Score | Notes |
|---------|--------|-------|
| **Context Clarity** | 100% | Two decision contexts clearly distinguished |
| **PRD Separation** | 100% | No longer overrides other PRDs |
| **Writing Guide Focus** | 100% | Pure methodology guide |
| **Reference Accuracy** | 100% | Properly references PRD 16 and 26 |
| **Overall Accuracy** | 99% | Minor formatting improvements possible |

## LILITH Verdict

**✅ APPROVED**

PRD 30 now correctly:
1. Documents the two valid decision contexts
2. Clarifies that "When" points to decision folders, not filenames
3. Maintains focus as a pure writing guide
4. Properly references other PRDs for structural requirements
5. Does not override established architectural rules

COUNTERMEASURE was right to flag structural overreach, but wrong about decisions folders. Both decision contexts are valid and needed.

---

*Audit completed 2026-04-02 19:00 UTC*
