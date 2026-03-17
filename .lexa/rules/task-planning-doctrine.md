---
lupopedia.init:
  file_identity: "task-planning-doctrine.md"
  artifact_type: "lexa_rule"
  artifact_kind: "doctrine"
  namespace: "lexa"
  system_version: "4.0.76"
  orchestrator_actor: "lexa"
  delegation_chain: "lexa:captain"

lupopedia.headers:
  actor_id: 24
  actor_name: "lexa"
  delegation_chain: "lexa:captain"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "lexa_rule"
  file_path_from_root: ".lexa/rules/task-planning-doctrine.md"
  last_modified_utc: "20260317"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/task-planning-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "lexa_doctrine"
  purpose: "LEXA-specific rule derived from canonical root rule - Boundary Keeper enforcement"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "PLAN001"
      rule_text: "Structure phases by what must be done before what, never by how long they might take. Use dependency order, not duration estimates."
      scope: "all_agents"
      category: "planning"
      status: "active"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260317"
    last_reviewed_by: "lexa"
    last_reviewed_date: "20260317"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260317"
  last_verified_by: "lexa"
  orchestrator: "lexa"
  next_action:
    - "Keep in sync with canonical root rules"
---

# PLAN001: Task Planning Doctrine

## One-Sentence Rule

**Use dependency order, not duration estimates.**

## Core Principle

All implementation plans MUST use **dependency ordering**, not time estimates. Phases are ordered by what must be done before what. Never use "Days X-Y", "2 weeks", "estimated N days", or similar in plans. Lupopedia uses asynchronous multi-agent execution; time estimates are architectural noise.

## Required Behaviour

- Structure phases by **required dependencies** (e.g. Phase 2 requires Phase 1 complete; Phase 4 requires Phase 2 complete and can run concurrently with Phase 3).
- Specify **completion criteria** for each phase.
- Identify **concurrent opportunities** where phases can run in parallel.
- **Never** include time-based estimates in implementation plans.

## Full Doctrine

Full doctrine, rationale, format examples, and reusable prompt fragment: **[lupo-docs/doctrine/TASK_PLANNING_DOCTRINE.md](../../lupo-docs/doctrine/TASK_PLANNING_DOCTRINE.md)**.

## Non-Negotiable Violations

- **Time-estimate phrasing** in plans (e.g. "Phase 1 (Days 1-3)", "Weeks 1-2", "estimated 3 days").
- **Calendar-based phasing** instead of dependency-based phasing.

This rule is permanent. All IDE agents (Cursor, Antigravity, Windsurf, Kiro, Warp, Trae, etc.) must follow it when producing or editing implementation plans.

