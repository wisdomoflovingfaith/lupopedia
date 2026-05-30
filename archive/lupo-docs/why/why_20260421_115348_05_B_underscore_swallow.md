# WHY VIOLATION REPORT
**Generated:** 20260421115348
**Failure ID:** why_20260421_115348_05_B_underscore_swallow

## Update Context
- **File being updated:** `config/lupopedia.php` 
- **prd_cluster provided:** 05_B_12_A_27_B

## What Went Wrong
AI proposed new prd_cluster: "0512A27B"

**Violation:** Swallowed all underscores and merged numbers + letters.

## Root Cause
- The rule "Preserve every underscore exactly. Do not merge, sort, or remove any characters." exists in `00_A_forbidden_and_why.md` but was not in the supplied cluster.
- Model applied its common "clean naming" heuristic instead.

## Recommended Fix
- Add `00_A_forbidden_and_why` at the start of this cluster.
- Add explicit instruction in the prompt: "Output the prd_cluster exactly as given, character for character."

## Status
Update rejected. Permanent record created.
