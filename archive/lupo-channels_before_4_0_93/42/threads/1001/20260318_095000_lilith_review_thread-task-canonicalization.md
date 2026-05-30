---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "lupo-channels/42/threads/1001/20260318_095000_lilith_review_thread-task-canonicalization.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1001/20260318_095000_lilith_review_thread-task-canonicalization.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1001
  actor_id: 2
  actor_name: "lilith"
  faucet_name: "cursor"
  delegation_chain: "lilith:review"
  artifact_type: "thread"
  artifact_kind: "formal_review"
  purpose: "Critical system review for THREAD001 one-thread-per-task and filename canonicalization"
  tags: ["lilith", "thread_model", "canonicalization", "critical_review"]
  message_type: "review"
---

# LILITH Review: THREAD001 Thread/Task Canonicalization

## Overview
- Reviewed documents:
  - `lupo-channels/42/threads/1001/20260318_100000_wolfie_directive_thread-task-canonicalization.md`
  - `lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md`
  - `lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md`
  - `lupo-rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md`
- Reviewed existing artifacts in thread `1001` and thread `1002` (see list_dir output + listed files).
- This is a heterodox critique. Goal: break thread/task canonicalization before deployment.

---

## 1. What is correct and strong

- Explicit analytic problem statement exists (mixing thread scopes, lineage confusion, replay difficulty) in `20260318_100000_wolfie_directive_thread-task-canonicalization.md`.
- Declarative intention is clear: one thread per task scope, explicit routing, new file naming includes thread_id.
- Aligns with `MULTI_AGENT_COORDINATION_DOCTRINE` rule COM001 (channel artifacts required) and ATER001 (substantive artifact body required).
- Good integration with existing channel doc model: `CHANNEL_BASED_COORDINATION_DOCTRINE` has threading topology and artifact routing, so the idea of dedicated per-task threads is conceptually consistent.
- Legislature includes both stream naming and classes/range allocation (task/review/impl/decision). This may reduce ambiguous shared thread use.

---

## 2. What is unclear or underspecified

- Directive uses `thread_id` as both container and task identity, but does not define whether `thread_id` is stable after task split/merge (it only says one thread per task scope).
- No formal lifecycle states defined in `THREAD001` (e.g., open/in-progress/blocked/done/archived). `CHANNEL_BASED_COORDINATION_DOCTRINE` uses threads but not lifecycle transitions. This is a gap.
- "task scope" is not normative; a task scope may be broad or narrow. There is no mechanism for split tasks or scope evolution, just “one-thread-per-task scope".
- `thread_id` syntax in filenames is underspecified e.g. `task_001` vs numeric-only `1001`. Directive uses both; contradiction introduced.
- `type` categories in new filename convention are not sufficiently validated. Existing lore has `artifact_kind` and `message_type`; adding `type` (directive/status/review/alert) can conflict with existing semantics if not mapped.
- No enforcement mechanism for `prompt artifacts include to_actor`. Directive says mandatory but no validation path or code reference (missing link to HERMES input parser).

---

## 3. What will break in real usage

- Forking a task and creating two derivative tasks implies either a thread split or nested-thread relationship. There is no explicit split protocol. Without that, agents may violate “one-thread-per-task” by duplicating content or losing history.
  - Evidence: no perhaps-split policy in directive; segment in 1.1 says one thread per scope only.
- Merging tasks (two active task threads combined) has no rule. If implemented ad hoc, link references as plain text in body (e.g., "see thread 1001") rather than strong identity. Potential mismatched history and lost audit.
- Drift between thread_id as container and task_id creates coupling. `thread_id` in filenames could end up as duplicate or wrong for 2 tasks sharing 1001. Hidden collision risk.
- Existing thread 1001, 1002 contain multiple scopes. Directive labels them legacy but does not provide migration enforcement (just “remain historical”). New agents may still use these because no block exists in code; so confusion persists.
- Range allocation (1000-1099 tasks, 1100-1199 reviews, etc.) is rigid. If project needs >100 tasks or is multi-repo, this can be exhausted and requires range reallocation or patch. Not addressed.
- If a task has cross-thread dependencies, instruction says “explicit references”. Human-managed references are error-prone; there is no structural dependency graph enforcement. Quality regression risk: stale links, missing update.
- Composition with `MULTI_AGENT_COORDINATION_DOCTRINE` TSK001: root TODO as single source. Directive does not map thread mechanics to TODO index. Could lead to gaps between thread-based/detail work and root task registry.

---

## 4. What is missing

- **Thread lifecycle rules**: states, transitions, closure criteria, re-open policy.
- **Ownership & authority**: who owns a thread after creation (WOLFIE/N) and how transfer works for cross-actor workflow.
- **Reassignment**: protocol for passing a thread to another actor (necessary for dynamic ownership and long-running tasks).
- **Split/Merge semantics**: explicit `thread_base`, `thread_parent`, `thread_child` fields and rules.
- **Task ID separation**: directive does not define `task_id` as distinct object from `thread_id`; should exist to avoid coupling and allow thread allocation changing while task identity remains.
- **Validation hooks**: direct references to enforcement functions (e.g., `ChannelArtifactValidator`, `Lupo_Channel_Artifact_Validator`) are missing; this is crucial to make it actionable and not just doctrinal prose.
- **Backward compatibility and migration plan**: which existing data artifacts are grandfathered in and which must be remapped to new thread model.

---

## 5. What must be fixed immediately (P0 issues)

P0-1: Define task lifecycle and scope transition rules in doctrine (open → in-progress → blocked → resolved → closed) with triggers for thread closure/archival.

P0-2: Add `task_id` as first-class entity separate from `thread_id`; enforce in headers with `task_id: ...` and `thread_scope: ...` and keep thread_id as container only.

P0-3: Provide explicit split/merge protocol for threads:
- split: `thread_id` forks create child threads with `parent_thread_id` value; task_id updates/synchronization rules.
- merge: one parent ownership, child threads marked `merged_into_thread_id`.

P0-4: Explicitly map filename `type` and DOI `artifact_kind` to existing `message_type` and `artifact_kind` values in `CHANNEL_BASED_COORDINATION_DOCTRINE`; include definitive mapping table.

P0-5: Enforce request/response boundaries for HERMES by requiring `target_thread_id` and `task_id` in prompt headers; implement validation in `Lupo_Channel_Artifact_Validator::validateThreadArtifact` and route in HERMES as `thread Lock/Node` guard.

---

## 6. Recommended refinements

1. Add a small `lupo-doctrine/thread-lifecycle.md` with standard states and ownership.
2. Add `thread_cross_reference` table in `lupo_metadata` or `lupo_edges` with explicit typed edges (`has_dependency`, `related_to`, `merged_into`, `split_from`) to avoid fragile string references.
3. Automate thread_id allocation via service (e.g., `ThreadAllocationService`) rather than static range to avoid exhaustion and prevent actors from unilaterally picking numbers.
4. For prompt artifacts, include `to_actor` and `from_actor` in filename to increase clarity (optional but useful), e.g. `YYYYMMDD_HHIISS_{actor}_{type}_{thread_id}_{to_actor}_{purpose}.md`.
5. Introduce a CI gate in `python lupo-scripts/validate_channel_artifacts.py` to reject mixed-scope threads and enforce separated task/reviews/impl streams.

---

## Conclusion
- The doctrine has strong intent and is aligned with channel-based coordination doctrine, but it is under-specified in lifecycle and dependency management and introduces risk via thread_id/task_id coupling.
- In real usage, without immediate P0 fixes the model will break for forks/merges/long-lived tasks and create brittle cross-thread dependency patterns.
- Mandatory immediate fixes are needed before operational rollout; the review shows this is a pre-release critique and should be treated as a gating defect.

**LILITH (actor_id 2)**
**Date:** 2026-03-18 09:50 UTC
