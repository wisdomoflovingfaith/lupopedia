---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "docs/why/WHY_20260421_MISSING_PRD_86_AND_IMPLEMENTATION_DRIFT.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/why/WHY_20260421_MISSING_PRD_86_AND_IMPLEMENTATION_DRIFT.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: "0/why/missing_prd_86_implementation_drift"
  artifact_type: "why"
  artifact_kind: "failure_analysis"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "why"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_98_A_SYSTEM_FAILURE_ANALYSIS"
  title: "WHY 20260421: Missing PRD 86 and Implementation Drift"
  summary: "System failure analysis: implementation occurred without canonical PRD definition, violating Lupopedia execution order."
---

# WHY 20260421: Missing PRD 86 and Implementation Drift

## 1. INCIDENT

PRD 86 (Immune System / Header Enforcement) did NOT exist. Agents proceeded to:

* write validator logic
* write regression tests  
* write checkpoint/pre-commit gates

Implementation occurred without canonical doctrine.

## 2. ROOT CAUSE

Failure to follow Lupopedia execution order:
PRD → REVIEW → MOCK → CODE

Assumed enforcement rules instead of defining them. Validator + scripts became **de facto doctrine**, which is forbidden. Agents behaved like "modern programmers":

* code first
* discover rules later
* rewrite repeatedly

## 3. VIOLATED DOCTRINE

* PRD-first architecture violated
* PRD 16 referenced but not extended correctly
* PRD 86 should have existed BEFORE any enforcement logic
* PRD 98_A WHY requirement triggered due to systemic failure

## 4. IMPACT

Time wasted in:

* validator rewrites
* test rewrites
* checkpoint script fixes

Inconsistent enforcement:

* strict vs reject-legacy-fields confusion

Duplicate logic across:

* validator
* bash scripts
* batch scripts

Increased error rate (e.g., field count mismatches, drift)

## 5. REQUIRED CORRECTIONS

* STOP all further implementation of immune system logic
* CREATE PRD 86 as canonical definition (see docs/prd/86_A_IMMUNE_SYSTEM_HEADER_ENFORCEMENT.md)
* REVIEW PRD 86 before continuing
* ALIGN validator, tests, and checkpoint scripts to PRD 86 (not the other way around)

## 6. PREVENTION RULE

NO CODE MAY DEFINE DOCTRINE

ALL RULES MUST ORIGINATE IN PRDs

If a rule is being implemented and no PRD exists:
-> STOP
-> WRITE PRD
-> REVIEW
-> THEN IMPLEMENT

## 7. SYSTEM LEARNING

Wolfie's model:

* Plan in PRDs
* Structure via prd_cluster
* Validate via atoms
* Resolve via questions system
* THEN code emerges deterministically

Failure to follow this results in:

* drift
* duplication
* wasted effort

