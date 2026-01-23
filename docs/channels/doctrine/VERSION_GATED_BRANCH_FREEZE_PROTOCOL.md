---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.5
file.last_modified_utc: 20260120000000
file.name: "VERSION_GATED_BRANCH_FREEZE_PROTOCOL.md"
GOV-AD-PROHIBIT-001: true
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_BRANCH_BUDGET
  - GLOBAL_BRANCH_THAW_VERSION
temporal_edges:
  actor_identity: "Eric (Captain Wolfie)"
  system_context: "Branch Freeze Active until 4.2.0 / Canonical Trunk Enforcement / Fork Justification Required"
dialog:
  speaker: CAPTAIN_WOLFIE
  target: @everyone @CURSOR @agents
  message: "Version-Gated Branch Freeze: no branching until 4.2.0. Branch_Budget=0. Fork requires human-steward–approved fork_justification artifact. Canonical trunk enforced."
tags:
  categories: ["documentation", "doctrine", "governance", "branch-freeze"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "architecture"]
---

# VERSION_GATED_BRANCH_FREEZE_PROTOCOL

**Effective:** Until version 4.2.0  
**Status:** ACTIVE ARCHITECTURE LAW  
**Source:** Captain Wolfie (Eric Gerdes), governance diagnosis 2026-01-19

---

## The Problem (Canonical Diagnosis)

1. **No branch budget.** Every turn is treated as a green light to create a new branch, idea, or subsystem.
2. **No canonical trunk enforcement.** Actors are not required to attach their work to the mainline.
3. **No fork justification protocol.** Forks happen because they can, not because they should.
4. **No version-gated branching rule.** The system does not know that branching is forbidden until 4.2.0.
5. **Multi-actor creativity outrunning governance.** In early Lupopedia versions the system is too good at generating possibilities.

> If branching is allowed, branching becomes the default.

---

## The Solution: Five Rules

### Rule 1 — No Branching Until v4.2.0

**Until Lupopedia version 4.2.0, no actor may create a new branch, fork, or alternate design path unless:**

1. The canonical trunk is **blocked**, AND  
2. A **blocking artifact** is registered, AND  
3. A **fork justification artifact** is **approved by a human steward**.

If any condition fails, the fork is **illegal**.

---

### Rule 2 — Fork Justification Artifact

**Artifact type:** `fork_justification`

Stored in `lupo_artifacts` with `type = 'fork_justification'`. The `content` field MUST be JSON with:

| Field           | Type   | Required | Description |
|----------------|--------|----------|-------------|
| `reason`       | string | yes      | Why the fork is necessary |
| `blockedby`    | bigint | yes      | `artifact_id` of the blocking artifact |
| `proposed_branch` | string | yes   | Name or identifier of the proposed branch |

When **approved** by a human steward, the same JSON MAY include:

| Field                 | Type   | Description |
|----------------------|--------|-------------|
| `approved`           | bool   | `true` when a human steward has approved |
| `approved_by_actor_id` | bigint | `actor_id` of the human steward (must be human) |
| `approved_utc`       | string | YYYYMMDDHHMMSS of approval |

**If a valid, approved `fork_justification` artifact does not exist, the fork is illegal.**

---

### Rule 3 — Canonical Trunk Enforcement

**All actors must attach their work to the canonical trunk unless a `fork_justification` artifact has been approved (by a human steward) for an exception.**

This forces alignment. No silent forks.

---

### Rule 4 — Branch Budget = 0 Until v4.2.0

**Branch_Budget = 0 until version 4.2.0.**

Any attempt to exceed the budget is **rejected**.

- Config/atoms: `GLOBAL_BRANCH_BUDGET: 0`, `GLOBAL_BRANCH_THAW_VERSION: "4.2.0"`.
- Query: `GET /api/v1/governance/branch-budget` returns `branch_budget`, `thaw_version`, `utc_timestamp`.

---

### Rule 5 — Human Steward Approval Required

**No automated or AI actor may approve its own fork justification.**

**Only a human steward may approve forks until v4.2.0.**

This stops runaway self-expansion.

---

## The Result

- Lupopedia stops branching every turn.  
- Actors stay on the canonical trunk.  
- Creativity is constrained; governance is restored.  
- Version 4.2.0 becomes the **branch thaw** milestone.

---

## Cross-References

- **Atoms:** `config/global_atoms.yaml` — `GLOBAL_BRANCH_BUDGET`, `GLOBAL_BRANCH_THAW_VERSION`
- **API:** `GET /api/v1/governance/branch-budget` — machine-readable branch budget and thaw version
- **Artifact type:** `fork_justification` in `lupo_artifacts`; `POST /api/v1/artifact` with `type: "fork_justification"` and `content` as JSON per Rule 2
- **Docs:** `docs/api/MINIMAL_REST_API.md` — fork_justification content schema

---

----------------------------------------------------------------------
END OF DOCTRINE
