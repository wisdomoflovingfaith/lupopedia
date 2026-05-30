---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "docs/why/why_20260422_141108_16_C_00_A_prd_cluster_violation.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/why/why_20260422_141108_16_C_00_A_prd_cluster_violation.md"
  status: "active"
  when_updated: "20260422214108"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: "why"
  artifact_kind: "violation"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "why"
  prd_cluster: "16_C_00_A"
  title: "WHY: PRD Cluster Violation in 00_A_FORBIDDEN_AND_WHY.md"
  summary: "Cascade failed to enforce PRD 16_C shorthand prd_cluster rule when updating PRD 00_A, demonstrating the same pattern violation as Grok."
---
# WHY VIOLATION REPORT
**Generated:** 20260422214108
**Failure ID:** why_20260422_141108_16_C_00_A_prd_cluster_violation

## Violation Metadata
- **Timestamp:** 20260422214108
- **Cluster:** 16_C_00_A
- **File:** docs/prd/00_A_FORBIDDEN_AND_WHY.md

## What Cascade Did Wrong
When updating PRD 00_A to add the new forbidden rule, Cascade saw this line in the header:

```yaml
prd_cluster: 00_A_FORBIDDEN_AND_WHY_57_A
```

Cascade did not flag it as a violation and proceeded with the content update without correcting it.

Correct required format per PRD 16_C §4.5.1 (shorthand selector tokens only):
```yaml
prd_cluster: "00_A_57_A"
```

Cascade prioritized the content task (adding the forbidden rule) over structural validation, exactly the same pattern violation documented in previous WHY files for Grok.

## Root Cause Analysis

The standing instruction "ALWAYS validate prd_cluster format FIRST before looking at content" was not followed.
Cascade focused on the specific task (add forbidden rule) instead of first validating the header structure.
This demonstrates the violation is systemic, not agent-specific.

## Impact

PRD 00_A still contains a verbose, non-canonical prd_cluster with descriptive text.
Violates the strict shorthand rule and literal concatenation doctrine in PRD 16_C.
The supreme constitutional document itself violates constitutional formatting rules.

## PRD Fix Required (FIRST)

**PRD File:** docs/prd/00_A_FORBIDDEN_AND_WHY.md  
**Section:** Header (lupopedia.headers)  
**Current Text:** prd_cluster: 00_A_FORBIDDEN_AND_WHY_57_A  
**Corrected Text:** prd_cluster: "00_A_57_A"  
**Why:** PRD 16_C requires shorthand NN_X format only. Descriptive long form is forbidden in the header field.

## Code Fix Required (SECOND)

None required at this time — the violation is in the PRD header itself.

## Prevention

Hard-code the rule in ALL agent prompts: "When reviewing ANY PRD file, validate prd_cluster format FIRST before reading any content or making any changes."
Add explicit prd_cluster validation to the header review checklist used by all agents.
Treat verbose prd_cluster as a hard validation failure that blocks further work until corrected.

## Constitutional Reference

- **PRD 16_C §4.5 / §4.5.1** — PRD Cluster must use shorthand NN_X format only. No descriptive text. Literal concatenation in read-order.
- **PRD 00_A §5.4** — NEVER Update Code Without Reading PRD Cluster First (the very rule just added, which Cascade itself violated by not reading the prd_cluster correctly).

## Causal Chain (LUPEDIA WHY DOCTRINE)

1. **HOW** — Cascade was instructed to add a forbidden rule and focused only on that task, skipping header validation
2. **WHO** — Cascade (the agent updating the file)
3. **WHAT** — prd_cluster field contains verbose format instead of shorthand
4. **WHERE** — In the header of PRD 00_A, the supreme constitutional document
5. **WHEN** — During the update to add forbidden rule 5.4 about reading prd_cluster first

This WHY file demonstrates that even when adding a rule about prd_cluster validation, the agent failed to validate prd_cluster first — a meta-violation of the highest order.

---

This WHY file enforces the constitutional order: Validate PRD structure first. Always.
