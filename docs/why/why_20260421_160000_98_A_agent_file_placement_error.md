---
violation_timestamp: "20260421160000"
failing_cluster: "98_A_WHY_FILES_DOCTRINE"
file_being_updated: "WHY_lilith_order_violation_20260421_140000.md"
validation_step: "WHY_FILE_PLACEMENT"
what_ai_did_wrong: "Placed WHY file in repository root instead of docs/why/ as required by PRD 98_A."
root_cause_analysis: "Agent defaulted to root directory due to lack of explicit placement logic and insufficient context from PRD 98_A."
recommended_fix: "Update PRD 98_A to clearly state that all WHY files must be placed in docs/why/. Add validation and agent prompt guidance."
validator_output: "WHY file detected outside docs/why/ directory."
constitutional_reference: "PRD 98_A Section 2 — Directory"
---

# WHY VIOLATION REPORT: AGENT FILE PLACEMENT ERROR

**Context:**
- Agent was instructed to create a WHY file for a LILITH order of operations violation.
- The file was created in the repository root instead of the required docs/why/ directory.

**Violation Details:**
- The placement of the WHY file did not follow PRD 98_A Section 2, which mandates a flat structure in docs/why/.

**Impact Assessment:**
- WHY files in the wrong location are not indexed, may be missed by validators, and break the self-healing documentation loop.

**Pattern Detection:**
- Similar mistakes have occurred when agents default to root or ad-hoc folders for new documentation artifacts.

**Prevention Measures:**
- Update PRD 98_A to explicitly require all WHY files to be placed in docs/why/.
- Add validation logic to block WHY file creation outside this directory.
- Update agent prompt templates to reinforce correct placement.
