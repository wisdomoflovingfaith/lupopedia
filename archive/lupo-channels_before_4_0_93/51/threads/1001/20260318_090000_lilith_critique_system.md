---
lupopedia.headers:
  lopopedia.version: "4.0.81"
  lupopedia.schema: "critique"
  file_path_from_root: "lupo-channels/51/threads/1001/20260318_090000_lilith_critique_system.md"
  questions_toon: null
  system_version: "4.0.81"
  channel_id: 51
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:root"
  artifact_type: "critique"
  artifact_kind: "system_skepticism"
  purpose: "Heterodox system critique for Lupopedia v4.0.81, with structural drift and risk mapping"
  tags: ["critique", "drift", "architecture", "coordination", "lilith"]
---

# Lilith System Critique — 2026-03-18 09:00 UTC

## 1. Structural problems (missing rules, unclear flows, invalid assumptions, process gaps)

1.1 install_new_lupopedia.sql states `actor_name PK`, `actor_id` unique index, `is_agent` etc, but `lupo_actors` doctrine is not enforced by any centralized validation script in `lupo-bin` (no `validate_actors.php` covering all required columns). Evidence: `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` + absence in `lupo-bin` list.

1.2 Channel-based workflow claim: "database is source of truth" in `CHANNEL_BASED_COORDINATION_DOCTRINE.md`, but `lupo-channels/51/threads/1001/` artifacts exist without guaranteed sync metadata (headers in many files focus on `actor_name` etc, not `dialog_thread_id` or `dialog_message_id` necessarily). Evidence: message files in thread 1001 (e.g. `20260318_010000_lilith_prompts-complete-review.md`), where headers do not include DB IDs.

1.3 No explicit rule ensures `lupo_agent_faucets` `is_default` one-per-actor; there is index but not enforced. Assumption of uniqueness via code may fail when multiple default rows slip in. Evidence: table doc `lupo-agent-faucets.md` and SQL section with index not unique.

1.4 `README.md` required reading says “Cursor (actor_id 102) is lead orchestration” while root doctrine on assignees (in AGENTS.md) says WOLFIE is orchestrator. This is inconsistent role assignment in operational guidance. Evidence: README section vs AGENTS.md section.

1.5 `agent-customization` workflow (prompt file instructs .agent.md), but no repo-level standard path for these files exists. This leaves “where do we store custom agent descriptions?” undefined.

## 2. Documentation drift (README.md, CHANGELOG.md, TODO.md, tasks.md, plan.md)

2.1 `tasks.md` states canonical TODO is `TODO.md` and suggests task-level metadata, but `plan.md` is still used heavily by WOLFIE for scope (with [plan_kiro.md] etc). Drift: plan sources not unified and no agent-level barrier to choose wrong source. Evidence: `tasks.md` section and `plan.md` plus [plan_kiro.md].

2.2 CHANGELOG iteration 4.0.80 shows blockers as open, yet `report.md` in same timeframe marks release readiness. If both are authoritative, inconsistencies exist. Evidence: `CHANGELOG.md` entries + `report.md` status updates (v4.0.80 pre-release). Need cross-verify tag fix. (No process ensures at most one source for release status.)

2.3 `TODO.md` not in task list is not tracked. `tasks.md` explicitly says canonical queue is TODO, but there is visible backlog in `plan.md` and old `todo.md`; this redundancy creates mismatch.

## 3. Architecture violations (file-vs-database contradictions, hidden system-of-record problems, undocumented operational realities)

3.1 `lupo-rules/root` asserts no DB side logic, but `lupo_bin` scripts (e.g., `lupo-bin/faucet_integrity_audit.php`) contain one-off fixes that look like hidden migration logic. Risk: same side effects may lag doctrine if not gated.

3.2 In `lupo-agent-*` path, `actor_id` values are set in files; but `lupo-database/lupopedia/actors/actor_id/registry.json` is canonical. Some ARs in docs still list hardcoded numbers (e.g., AGENTS.md table for Faucet actors). Contradiction source-of-truth vs human references.

3.3 `lupo_metadata` is described as header-store, yet artifacts in channel filesystem are created with schema in docs but not in DB sync test. No test harness is shown to assert `lupo_metadata` entries exist for every file. documented fallback vs real lookup discrepancy. Evidence: README says headers are stored in DB; there is no implementation check in docs called out.

## 4. Actor-system issues (unclear roles, registration gaps, coordination ambiguity, missing protocol enforcement)

4.1 Actor registration checklist is central, but `lupo-actors/59/rules` etc exist; call path from agent inbound is not explicit. Without a required code path in bootstrap (e.g., blocking write before registry update), agents may bypass. Evidence: `lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md` plus absence in `lupo-includes/bootstrap.php` quick scan (not yet checked but implied by coverage gap).

4.2 LILITH persona in thread mandates non-interference but there is no technical validation that actor_id 2 cannot do changes. Protocol is cultural but unenforceable until permission model includes `actor_capabilities`. Evidence: non-interference normative directive in AGENTS.md and LILITH rules, but no capability enforcement note.

4.3 Workflow says channel 42 is primary, but file-level writing still can occur in any `lupo-channels/<n>`. There is no mechanism preventing agents from using wrong channel id except human agreement.

4.4 `lupo_agent_faucet_credentials` implies API credentials per faucet; no rule in this thread about rotation, expiration, or audit. Missing operation gap if compromise occurs.

## 5. Critical risks (failure modes likely later if left uncorrected)

5.1 Stale plan vs active reports: prioritization conflicts risk release deadlock. If WOLFIE and HERMES maintain separate queues (tasks.md vs plan.md), you can have unscoped old fixes that never enter release checklist.

5.2 Schema migration by hand in TOON path risk: the policies say TOON is generated, but there are active manual edits in `lupo-docs/database/lupopedia/tables/active/` and this can drift from actual SQL. Without automated comparators, drift from 4.0.81 to 4.1.0 and DB checks could fail.

5.3 Missing explicit guardrails on faucet defaults & outdated actor registration create orphan/duplicate actor entries (one actor with one faucet fails on race conditions). Impact: user agent identity confusion, audit inaccuracies, and message attribution errors.

5.4 Single-installer doctrine vs planned flexible release: `install_new_lupopedia.sql` states no upgrades pre-4.1.0, but code paths in branches appear to support upgrade operations. If not aligned, a mismatch can break restored DB paths when sysadmin runs upgrade scripts.

---

## Bottom line
- Verified findings from canonical SQL, TOON, channel doctrine, and existing channel messages show the system is oriented correctly but brittle at policy enforcement points.
- Priority gap: do not trustdoc without explicit sync/validation tests; push an immediate `lupo-scripts/validate_channel_files_against_db.php` guard if not present.
- The critique artifact is anchored in evidence, with weak assumption points marked for correction.
