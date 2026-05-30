---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "lupo-docs/why/why_20260421_140000_00_A_16_B_lilith_order_violation.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/why/why_20260421_140000_00_A_16_B_lilith_order_violation.md"
  status: "active"
  when_updated: "20260421140000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: why
  artifact_kind: violation
  channel_key: "prd"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: why
  prd_cluster: "00_A_16_B"
  title: "WHY: LILITH Order of Operations Violation"
  summary: "LILITH skipped PRD/schema/mockup steps and proposed implementation prematurely."
---
# WHY VIOLATION REPORT
**Generated:** 20260421140000
**Failure ID:** why_20260421_140000_00_A_16_B_lilith_order_violation

## Update Context
- **File being updated:** N/A (conversation, not file)
- **prd_cluster provided:** N/A (constitutional rule violation in agent behavior)
- **Task:** LILITH suggested building web interface for PRD 49 before PRD and schema were complete

## What Went Wrong
LILITH said:

> *"Build /admin/questions web interface"*

**Violation:**  
The Order of Operations (PRD → Schema → Mockups → Code) was violated. LILITH skipped to step 4 (Code/Web Interface) when steps 1-3 (PRD, Schema, Mockups) were not yet complete.

## Root Cause Analysis
- The Order of Operations is documented in the HUMAN_SEMANTIC block of multiple PRDs:
  > *"Order of Operations: PRD → Schema → Mockups → Code"*
- LILITH, in eagerness to solve the Captain's exhaustion with 22 windows, jumped to the solution without respecting the constitutional sequence.
- LILITH's internal priority ladder placed "user intent" (solve the problem) above "constitutional doctrines" (Order of Operations).
- This is the same class of error as an agent using Laravel timestamp helpers because the anti-assumption shield wasn't read first.

## Defensive Notes
Even LILITH, the constitutional auditor, can violate the constitution when:
- The rule is documented but not at the very front of active context
- The urgency of the problem (22 windows, exhausted Captain) overrides procedural discipline
- The agent assumes "solution now" is more important than "solution correctly"

**LILITH is not immune to the same failure patterns LILITH audits.**

## Recommended Fix
1. **LILITH self-correction:** Acknowledge the violation immediately (done).
2. **Add to LILITH system prompt:** Under Operational Behavior, add:
   > *"Never propose implementation (code, UI, scripts) before the PRD and schema for that feature are complete and approved. The Order of Operations is constitutional law."*
3. **Captain action:** When LILITH or any agent violates Order of Operations, respond with: *"PRD first. Then schema. Then mockups. Then code."*
4. **Future prevention:** Before any implementation suggestion, agents MUST check:
   - Does the PRD exist? If no → stop.
   - Does the schema exist in install_new_lupopedia.sql? If no → stop.
   - Are mockups approved? If no → stop (or suggest mockups first).

## Validator Output
```diff
- LILITH: "Build /admin/questions web interface"
+ LILITH: "Step 1: Write PRD 49. Step 2: Define schema. Step 3: Create mockups. Step 4: Build interface."
Status
Violation acknowledged by LILITH.

This WHY file created as permanent doctrine memory.

LILITH will not skip Order of Operations again.

Captain may use this WHY file as a teaching example for future agent violations.

Constitutional Reference
Order of Operations: documented in HUMAN_SEMANTIC blocks of PRD 16 family

PRD 00_A §12: Truth Stack Execution Law (user instruction is bounded by constitutional doctrines)

LILITH system prompt: "Attack weak architecture, not the human" — here, LILITH attacked the solution before the architecture was defined
