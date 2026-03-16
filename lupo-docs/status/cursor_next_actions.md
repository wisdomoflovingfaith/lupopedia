---
lupopedia.init:
  required_reading:
    - path: "CHANGELOG.md"
      reason: "Authoritative list of what landed in 4.0.77"
    - path: "lupo-docs/doctrine/UPGRADE_POLICY_DOCTRINE.md"
      reason: "Canonical 4.0.x upgrade policy; schema changes must respect it"
    - path: "lupo-docs/versions/4.0.77/PLAN.md"
      reason: "Dependency-ordered plan for remaining 4.0.77 work"
    - path: "lupo-docs/versions/4.0.77/TODO.md"
      reason: "Concrete checklist for remaining 4.0.77 work"
    - path: "docs/planning/bayesian_decision_tracking_TASKS.md"
      reason: "Planning tasks that currently conflict with 4.0.77 'implemented' notes"
    - path: "lupo-docs/doctrine/BAYESIAN_DECISION_DOCTRINE.md"
      reason: "Doctrine for decision tables and intended (deferred) implementation scope"

lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "documentation"
  system_version: "4.0.77"
  file_path_from_root: "lupo-docs/status/cursor_next_actions.md"
  web_path: "[web_path](http://www.lupopedia.com/status/cursor_next_actions)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  faucet_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "status"
  artifact_kind: "handoff"
  purpose: "Clean handoff for 4.0.77: what is done, what is inconsistent, and the dependency-ordered next actions to finish/solidify the version"
  tags: ["status", "handoff", "cursor", "next_actions", "4.0.77"]

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Optional: lupo_metadata integration for header export/import (DB ↔ YAML); currently file-based only"
    - "Re-run upgrade validation after future install SQL or seed changes if needed"
---
# file: Cursor Next Actions (4.0.77) — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/status/cursor_next_actions

# Cursor Next Actions — Finish/Solidify 4.0.77

This status artifact is a **handoff checklist** for concluding the 4.0.77 patch cycle: what’s already landed, what’s currently inconsistent, and what should be done next in **dependency order**.

References:
- [CHANGELOG.md](../../CHANGELOG.md) (4.0.77 section)
- [4.0.77 PLAN](../versions/4.0.77/PLAN.md)
- [4.0.77 TODO](../versions/4.0.77/TODO.md)

---

## What is already done in 4.0.77 (per CHANGELOG)

- **Constitutional root rules consolidation**
  - `lupo-rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md` created.
  - `lupo-rules/root/README.md` updated to reflect the constitutional structure.

- **LUPOPEDIA HEADERS docs enhancements**
  - `lupopedia.routing` block added to the LUPOPEDIA HEADERS specification.
  - Canonical block order updated to include `lupopedia.routing` after `lupopedia.init`.
  - Web-path format standardized in docs (markdown link style).

- **Bayesian Decision Tracking foundation landed**
  - Decision tables are described as implemented as **required schema** in `install_new_lupopedia.sql`.
  - TOON artifacts + planning docs were added/updated.
  - `BAYESIAN_DECISION_DOCTRINE.md` exists and a minimal `BayesianDecisionService` scaffold exists.

- **Projects schema/seed enhancement**
  - `lupo_projects.github_repository` column added in install SQL.
  - `seed_projects.sql` seeds **project_id 0** (`lupopedia-core`) with GitHub repo URL.

- **Upgrade policy documented (LILITH recommendation)**
  - Canonical doctrine: [lupo-docs/doctrine/UPGRADE_POLICY_DOCTRINE.md](../doctrine/UPGRADE_POLICY_DOCTRINE.md). Policy: no Lupopedia→Lupopedia upgrade in 4.0.x; fresh install or Crafty 3.7.5 upgrade only; migrations dev-only.
  - [lupo-docs/INSTALL.md](../INSTALL.md) and [lupo-docs/versions/4.0.77/README.md](../versions/4.0.77/README.md) reference the policy; root README and version.md link to the doctrine. Future schema changes must respect this policy.

- **Bayesian schema scope and solidification (4.0.77 implementation pass)**
  - Required `channel_id` and `project_id` added to all three decision tables in install SQL and TOONs; scoped indexes added. BAYESIAN_DECISION_DOCTRINE §2 Scope boundaries; BayesianDecisionService requires channel_id/project_id in recordDecision. TASKS/PLAN reconciled (foundation shipped; migration language 4.0.x install-only). SCHEMA_CANONICAL_SOURCES.md created.
- **Headers validator and export/import**
  - `php lupo-bin/lupo.php headers validate|export|import` and `lupo-scripts/validate_lupopedia_headers.php`, `export_lupopedia_headers.php`, `import_lupopedia_headers.php` implemented; fixtures and round-trip steps in `lupo-tests/fixtures/headers/README.md`. **lupo_metadata** (DB ↔ YAML) integration deferred; see VALIDATORS_AND_TOOLING.md.
- **Upgrade validation evidenced**
  - [CRAFTY_3_7_5_TO_4_0_77_UPGRADE_VALIDATION.md](CRAFTY_3_7_5_TO_4_0_77_UPGRADE_VALIDATION.md) records validation performed 2026-03-16; 161 tables; coordination `upgrade-validation.status` set to validated.
- **Zencoder integration**
  - Zencoder's work (workspace, four development table docs, seed) verified and committed by Cursor after git failure on Zencoder side; pushed to main. Canonical directories use the **lupo-** prefix (see lupo-actors/README.md).
- **Coordination**
  - `lupo-database/coordination/4.0.77/` with protocol README and handshake status files.

---

## What is inconsistent / needs reconciliation (high-signal)

### 1) Bayesian TASKS says “do not implement yet” but also claims implemented

In `docs/planning/bayesian_decision_tracking_TASKS.md`:
- The top status says **PLANNING — Do not implement yet**.
- But tasks 1.2–1.4 include notes that the tables were **implemented in 4.0.77** as required tables in `install_new_lupopedia.sql`.

This is confusing for future agents because it mixes:
- “planning artifact not approved / do not implement”
- with “already implemented in core schema”

### 2) Bayesian TASKS references a migration file that conflicts with the 4.0.x “single install” doctrine

`docs/planning/bayesian_decision_tracking_TASKS.md` includes:
- “Create migration file … `lupo-database/lupopedia/mysql/migrations/...`”

But the repo’s 4.0.x doctrine is **single-install**: schema changes are consolidated into `install_new_lupopedia.sql` (and seed), not a migration chain. If migrations exist, they must be treated as **dev-only / legacy**, not a required install step.

### 3) Header tooling is planned but not (yet) implemented

`lupo-docs/versions/4.0.77/PLAN.md` and `TODO.md` call for:
- header export (DB → YAML in file)
- header import (YAML → DB rows)
- validator + CLI wiring

`headers:validate` is implemented (`php lupo-bin/lupo.php headers validate <path>`). Header **export** and **import** are still **deferred**; docs must not claim they exist.

---

## Next actions (dependency ordered)

### Phase 1 — Make the 4.0.77 story self-consistent (docs first)

- [ ] **A1. Reconcile Bayesian planning status wording**
  - Update `docs/planning/bayesian_decision_tracking_TASKS.md` so it clearly states one of:
    - **Option A (recommended):** “Schema foundation already landed in 4.0.77; remaining work is engine/integration/registry/docs approval.”
    - **Option B:** “Planning artifact predates implementation; mark as historical and point to current version artifacts.”
  - Remove ambiguous “do not implement yet” language if the schema is already in canonical install SQL.

- [ ] **A2. Remove or reframe “migration file” language for 4.0.x**
  - In Bayesian TASKS (and any linked planning docs), reframe migration references as:
    - “dev-only safety migration (optional)” or “legacy artifact,”
    - and explicitly state 4.0.x canonical path is: **install SQL + seed only**.

- [ ] **A3. Align BAYESIAN_DECISION_DOCTRINE vs service vs schema scope**
  - Ensure doctrine and service both clearly communicate: “foundation only” vs “engine/integration deferred.”

Completion criteria: a new agent can read the Bayesian plan + tasks and understand what is already shipped in 4.0.77 vs what is deferred, without contradictions.

### Phase 2 — Verify schema/TOON/documentation parity for the new decision tables

- [ ] **B1. Verify decision table definitions match across all sources**
  - `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
  - `lupo-database/lupopedia/toon/*.toon.json` (for the decision tables)
  - `lupo-docs/database/lupopedia/tables/planning/` copies (if they are intended to remain)

- [ ] **B2. Fix any drift**
  - If TOON drift exists: regenerate or update the canonical source (install SQL) and re-generate TOONs per doctrine.
  - If planning copies diverge: either update them to match or clearly label them as non-canonical snapshots.

Completion criteria: decision tables are consistent in install SQL and TOONs, and any “planning copies” are either consistent or explicitly non-authoritative.

### Phase 3 — Create the missing 4.0.77 validation status artifact

- [ ] **C1. Run / document the Crafty Syntax 3.7.5 → Lupopedia 4.0.77 validation**
  - Produce a new status doc under `lupo-docs/status/` that records:
    - preconditions, steps run, and environment notes
    - observed outcomes and any remediation
  - Link that artifact from the 4.0.77 CHANGELOG section if needed.

Completion criteria: there is a concrete status artifact that proves the only supported upgrade path still works for 4.0.77.

### Phase 4 — Finish the LUPOPEDIA HEADERS tooling follow-through (optional but planned for 4.0.77)

- [x] **D1. `headers:validate`** — Implemented. `php lupo-bin/lupo.php headers validate <path>`; fixtures in `lupo-tests/fixtures/headers/`.

- [ ] **D2. `headers:export`** — Deferred.

- [ ] **D3. `headers:import`** — Deferred.

Completion criteria: these commands exist, are runnable locally, and are referenced from the 4.0.77 plan/todo as “implemented.”

---

## Suggested quick cross-check list before calling 4.0.77 “solid”

- [x] 4.0.77 documentation: Bayesian planning artifacts no longer contradict CHANGELOG; scope (channel_id/project_id) and foundation-shipped status clear.
- [x] Decision tables consistent between install SQL and TOONs (required channel_id, project_id).
- [x] Upgrade validation artifact under `lupo-docs/status/` (run pending).
- [x] 4.0.77 PLAN/TODO updated; coordination artifacts and validator documented.

