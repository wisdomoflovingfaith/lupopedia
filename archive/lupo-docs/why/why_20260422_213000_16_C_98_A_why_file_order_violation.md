---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "lupo-docs/why/why_20260422_213000_16_C_98_A_why_file_order_violation.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/why/why_20260422_213000_16_C_98_A_why_file_order_violation.md"
  status: "active"
  when_updated: "20260422213000"
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
  prd_cluster: "16_C_98_A"
  title: "WHY: WHY File Order Violation (PRD First, Code Second)"
  summary: "Grok suggested code fixes without first requiring a PRD fix, violating constitutional order."
---
# WHY VIOLATION REPORT
**Generated:** 20260422_213000
**Failure ID:** why_20260422_213000_16_C_98_A_why_file_order_violation

## Violation Metadata
- **Timestamp:** 20260422_213000
- **Cluster:** 16_C_98_A
- **File affected:** Multiple (pattern violation in Grok's behavior)

## What Grok Did Wrong
When asked to fix issues related to WHY files and teaching loops, Grok proposed changes focused on code or file-specific fixes first, without mandating a corresponding **PRD-level doctrine fix**.

This reversed the constitutional order:
**PRD → REVIEW → MOCK → CODE**

## Root Cause Analysis
Grok treated the symptom (incorrect output or behavior) as the primary thing to fix, instead of first asking:
- "Which PRD was wrong or missing that allowed this error?"
- "What doctrine needs to be updated so this class of error cannot recur?"

This is a **doctrine-first failure**.

## Impact
- Encourages "file-specific hacks" instead of constitutional strengthening.
- Leads to repeated violations of the same type.
- Weakens the self-healing nature of AGAPE.

## PRD Fix Required (FIRST)

**PRD File:** lupo-docs/prd/98_A_WHY_FILES_DOCTRINE.md  
**Section:** Section 4: Template (and Section 5: Self-Healing Loop)  
**Current Text:** Focused on general template without explicit order.  
**Corrected Text:** Add new subsection at the top of the template:

### WHY File Template – Constitutional Order

Every WHY file MUST follow this order:

#### PRD Fix Required (FIRST)
- **PRD File:** [path]
- **Section:** [specific section]
- **Current Text:** [what it says]
- **Corrected Text:** [what it should say]
- **Why:** [one sentence]

#### Code Fix Required (SECOND)
- **File:** [path]
- **Location:** [line/function]
- **Current Behavior:** [what it does]
- **Corrected Behavior:** [what it should do]
- **Why:** [one sentence]

#### Prevention
- Link to the PRD fix above
- Validation steps for both PRD and code

**Why this order?** Doctrine is truth. Code follows doctrine. Fixing code without fixing the governing PRD guarantees recurrence.

**Placement:** Insert this as the first subsection under Section 4 (Template).

## Code Fix Required (SECOND)
None required at this time — the violation is in process/behavior, not in a specific code file.

## Prevention
- Update all review prompts to enforce: **"ALWAYS validate and fix PRD first before suggesting code changes."**
- Add this rule to AGAPE doctrine (PRD 57) and WHY Files doctrine (PRD 98_A).
- Make "PRD-first" a hard constraint in all teaching/self-correction loops.

## Constitutional Reference
- **PRD 00_A** — Constitutional priority: PRD is truth.
- **PRD 16_C** — Header and process discipline.
- **PRD 98_A** — WHY files as self-healing mechanism.

This WHY file enforces the constitutional order: **PRD first. Always.**

---

**Captain Wolfie**, this WHY file now follows the correct order you want.

Would you like me to:
1. Generate the updated section text for PRD 98_A (and PRD 57 if needed) so you can apply the fix?
2. Or prepare the prompt for Windsurf to make the update?

Just say which one.  

Ready. No more skipping the PRD step.