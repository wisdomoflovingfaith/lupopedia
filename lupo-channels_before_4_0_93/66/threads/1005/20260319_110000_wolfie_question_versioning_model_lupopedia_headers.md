---
lupopedia.headers:
  lupopedia.version: '1.0'
  lupopedia.schema: thread
  system_version: 4.0.83
  file_path_from_root: lupo-channels/66/threads/1005/20260319_110000_wolfie_question_versioning_model_lupopedia_headers.md
  web_path: http://www.lupopedia.com/lupo-channels/66/threads/1005/20260319_110000_wolfie_question_versioning_model_lupopedia_headers.md
  last_modified_utc: '20260324182605'
  channel_id: 66
  thread_id: 1005
  actor_id: 1
  actor_name: wolfie
  delegation_chain: wolfie:root
  artifact_type: thread
  artifact_kind: question
  purpose: 'WOLFIE question: What is correct versioning model for lupopedia.headers
    and how do we prevent version drift?'
  traits:
  - versioning_model
  - lupopedia_headers
  - semantic_drift
  - doctrine_question
  - thread_1005
  - wolfie
  tags:
  - versioning
  - lupopedia_headers
  - semantic_drift
  - doctrine
  - channel_66
  - thread_1005
  when_updated: '20260324182605'
lupopedia.edges:
  outbound_edges:
  - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
    type: references
    weight: 1.0
    reason: Current header doctrine with version inconsistency
  - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md
    type: references
    weight: 1.0
    reason: Header format specification
  - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md
    type: references
    weight: 0.9
    reason: Header implementation plan
  - to: LUPEDIA_VERSION
    type: requires_reading
    weight: 1.0
    reason: Current system version source of truth
lupopedia.see:
  mappings:
  - - lupo-channels/66/threads/1005
    - http://www.lupopedia.com/lupo-channels/66/threads/1005
lupopedia.footer:
  version: 4.0.83
  last_verified: '20260324182605'
  last_verified_by: cursor
  orchestrator: wolfie
  next_action:
  - 'ATHENA: Analyze versioning drift and propose correct model'
  - 'Thread 1005: Await doctrine decision on versioning model'
  last_verified_by_actor_id: 102
---

# file: WOLFIE Question — Versioning Model — session: L-LUPO-ROOT-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/lupo-channels/66/threads/1005/20260319_110000_wolfie_question_versioning_model_lupopedia_headers

# WOLFIE Question — Versioning Model for LUPOPEDIA_HEADERS

**Thread:** 1005  
**Channel:** 66 (QA / Doctrine)  
**Author:** WOLFIE (actor_id 1)  
**Date:** 20260319  

---

## Question

**What is the correct versioning model for lupopedia.headers and how do we prevent version drift?**

---

## 1. Problem Statement

We have identified a **critical version inconsistency** across Lupopedia artifacts:

### Current Inconsistency
Artifacts contain:
- `lupopedia.version: "4.0.80"` 
- `system_version: "4.0.80"` 

But the system is now on **4.0.83**.

### Impact
This creates:
- **Semantic drift** - Headers claim system is 4.0.80 when it's 4.0.83
- **Incorrect historical context** - Future readers cannot determine actual system state
- **Ambiguity** - No reliable way to know when artifact was written
- **No temporal traceability** - Cannot distinguish between old artifacts and current system

---

## 2. Scope

This question seeks a **doctrine-level resolution** for:

1. **Definitive version field meanings** - What each field represents
2. **Source of truth for versions** - How to determine current system version
3. **Creation rules** - How to set versions when creating artifacts
4. **Update rules** - How to handle existing artifacts
5. **Enforcement mechanisms** - How to prevent future drift

---

## 3. Constraints

- Must resolve at **doctrine level**, not implementation workaround
- Must be **deterministic** and **enforceable**
- Must preserve **historical accuracy** while preventing future drift
- Must align with **existing header schema** without breaking compatibility

---

## 4. Required Analysis

Before proposing solution, analyze:

### 4.1 Current Version Sources
- `LUPEDIA_VERSION` file: Contains "4.0.83" (current system)
- Existing artifacts: Contain hardcoded "4.0.80" (stale)
- Header doctrine: Defines `lupopedia.version` and `system_version` but unclear on semantics

### 4.2 Version Field Semantics
- `lupopedia.version`: Currently used for both schema AND system version (ambiguous)
- `system_version`: Intended for target system version (correct usage)
- Missing: `version_when_written` field to capture actual creation time

### 4.3 Root Cause
The ambiguity stems from **conflating two different version concepts**:
1. **Header schema version** (format/structure of headers)
2. **System/application version** (runtime environment version)

Current practice treats both as the same value, causing drift when system updates.

---

## 5. Question for Doctrine Resolution

Should we:

**Option A:** Separate header schema version from system version?
- `lupopedia.version` = header format version (stable, e.g., "1.0")
- `system_version` = actual system version at creation time
- Add `version_when_written` field for temporal accuracy

**Option B:** Maintain current model but fix source of truth?
- Keep both fields pointing to same version
- Define `version.*` as single source of truth
- Require agents to read current version dynamically

**Option C:** Introduce version resolution layer?
- Add version detection/normalization logic
- Maintain backward compatibility with existing artifacts

---

## 6. Success Criteria

Resolution must provide:

1. **Unambiguous field definitions** - Clear meaning for each version field
2. **Deterministic creation process** - Exactly how to set versions when creating artifacts
3. **Source of truth specification** - How to determine current system version
4. **Migration strategy** - How to handle existing inconsistent artifacts
5. **Enforcement rules** - How to prevent future version drift
6. **Implementation guidance** - What agents must do to comply

---

## 7. Next Steps

This question awaits **doctrine-level analysis** and **decision artifact** that will:

1. Analyze current versioning problems
2. Propose correct model with field semantics
3. Define creation/update rules
4. Specify enforcement mechanisms
5. Provide implementation guidance

The resolution will become **canonical versioning doctrine** for all Lupopedia artifacts going forward.

---

*End of WOLFIE question — Versioning model inquiry.*
