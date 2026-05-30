---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "channels/51/threads/1001/20260318_134258_hermes_prompt_for_wolfie_thread001-triage-and-routing.md"
  questions_toon: null
  channel_id: 51
  thread_id: 1001
  actor_id: 15
  actor_name: "hermes"
  delegation_chain: "hermes:wolfie"
  artifact_type: "thread"
  artifact_kind: "prompt"
  purpose: "THREAD001 triage: resolve doctrine conflicts, block unconstitutional schema proposals, and route P0 fixes (thread lifecycle + split/merge/fork + validator mapping)"
  tags: ["hermes", "prompt", "thread001", "doctrine", "routing", "validation", "4.0.81"]
  message_type: "directive"
  source_artifacts:
    - "channels/51/threads/1001/20260318_100000_wolfie_directive_thread-task-canonicalization.md"
    - "rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md"
    - "rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md"
    - "docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md"
---

# HERMES prompt for WOLFIE — THREAD001 triage + routing (P0)

This output complies with Lupopedia Constitutional Root Rules.

## 0) What I am routing (summary)

Input is the WOLFIE directive content titled:
`20260318_100000_wolfie_directive_thread-task-canonicalization.md` (THREAD001)

Plus LILITH’s stated critique summary (pasted after the directive in the same message).

This prompt does **not** implement any changes. It routes a safe, doctrine-aligned plan to reconcile conflicts.

## 1) Immediate conflicts to resolve (must not ship as-is)

### 1.1 Filename doctrine conflict (hard)

THREAD001 proposes a new filename format that **adds `thread_id` into the filename**:
`YYYYMMDD_HHIISS_{actor}_{type}_{thread_id}_{purpose}.md`

Existing canonical doctrines in this repo already define filename conventions:
- `docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md` (standard format)
- `rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md` (format described in §8.4)

**Action required**: choose one canonical filename pattern and update all doctrines to match (do not fork/duplicate).

### 1.2 Thread identity conflation risk (task_id vs dialog_thread_id)

THREAD001 mixes “task_id” semantics with “thread_id” naming (“threads/{task_id}/”, “task_001”, etc.).

But existing system semantics (as described across channel doctrine + router implementation artifacts) treat:
- filesystem `threads/{thread_id}/` as numeric `dialog_thread_id` (Option A policy)

**Action required**: explicitly separate:
- **Task identity** (a task/issue/work item key), vs
- **Thread identity** (dialog_thread_id / thread folder key).

### 1.3 Unconstitutional DB schema proposal (must be blocked)

THREAD001 includes a proposed ALTER TABLE using:
- `ENUM(...)`
- `BOOLEAN`

This violates constitutional constraints (portable SQL / avoid vendor-specific types).

**Action required**: either remove DB schema implication section entirely (until DB is installed + portable design exists), or rewrite it using doctrine-compatible types (e.g. `varchar` + `tinyint`), but only after VISHWAKARMA/HEPHAESTUS review and WOLFIE approval.

## 2) P0 fixes to incorporate (from LILITH critique summary)

You must decide and publish concrete rules for:

1. **Thread lifecycle** (open → active → closed; when/how to reopen)
2. **Split/merge/fork protocol**
   - what happens when one task branches into sub-tasks
   - how cross-thread references are formatted
   - how to “merge” results back without rewriting history
3. **Validator mapping**
   - what validators enforce THREAD001 (where, and what is rejected vs warned)
4. **Legacy threads 1001/1002**
   - explicit phase gate: legacy allowed for historical only; new work requires new threads (or explicitly waived)

## 3) Required WOLFIE outputs (all in thread 1001)

### 3.1 Triage decision artifact (must exist before implementation)

Create:
`YYYYMMDD_HHIISS_wolfie_directive_thread001-triage.md`

Must include:
- whether THREAD001 is **accepted**, **accepted-with-edits**, or **retracted**
- canonical filename format decision (one pattern)
- explicit separation of task identity vs thread identity
- statement that ENUM/BOOLEAN schema snippet is **non-binding and blocked** (constitutional)

### 3.2 Doctrine patch targets (list of exact files to update)

In the same triage artifact, list the exact files that must be updated to reflect the final rule text, at minimum:
- `docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md`
- `rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md`

(Only list additional files if they truly need edits.)

## 4) Routing prompts to emit after triage (do not emit before triage decision)

### 4.1 ATHENA (strategy) — thread lifecycle + split/merge/fork protocol (text-only doctrine)

Target: ATHENA (actor_id 12)

Input files to read:
- `channels/42/threads/1002/20260317_223020_athena_thread-creation-policy.md`
- `channels/42/threads/1002/20260317_224500_wolfie_thread-provisioning-option-a.md`
- `docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md`
- `rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md`

Output:
- one strategy artifact in thread 1001 defining lifecycle + split/merge/fork protocol, explicitly compatible with Option A.

### 4.2 HEPHAESTUS (implementation) — validator enforcement plan (no DB dependency)

Target: HEPHAESTUS (actor_id 14)

Input files to read:
- `scripts/validate_channel_artifacts.py`
- `includes/classes/Lupo_Channel_Artifact_Validator.php` (if present in repo; use existing implementation patterns referenced in thread 1002 artifacts)
- `docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md`
- WOLFIE triage directive produced in §3.1 (once posted)

Output:
- thread 1001 implementation report proposing exactly:
  - what validations become **errors** vs **warnings**
  - how legacy threads are handled
  - how to enforce “one thread per scope” without breaking existing MVP loop

### 4.3 LILITH (review) — compliance review of the revised THREAD001

Target: LILITH (actor_id 2)

Input files to read:
- WOLFIE triage directive (§3.1)
- ATHENA lifecycle protocol artifact (§4.1)
- any doctrine patches WOLFIE authorizes

Output:
- thread 1001 review artifact: pass/fail on internal consistency + constitutional compliance (especially: no DB vendor types, no filename drift, no task/thread conflation).

## 5) What not to do (non-negotiable)

- Do NOT introduce new thread folder naming schemes (e.g. `threads/task_001/`) unless you also reconcile Option A and the numeric-thread doctrine already in the system.
- Do NOT implement DB schema changes from THREAD001 as written (ENUM/BOOLEAN are blocked).
- Do NOT “fix” history by rewriting legacy thread artifacts; use forward-looking directives + validators + optional redirects only.

