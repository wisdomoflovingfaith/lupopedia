---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.1.5/todo.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.1.5/todo.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/version-4-1-4-todo.toon
  atoms_toon: null
  transcript_jsonl: 0/development/version-4-1-4-todo
  artifact_type: version-doc
  artifact_kind: version-specific
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: version-doc
  prd_cluster: null
  title: Lupopedia 4.1.5 TODO -- Crafty Syntax Features & Human-only Live Help
  summary: 4.1.5 focuses on completing Crafty Syntax features, human-only live help, and preparing for May 1 deadline. Multi-AI orchestration deferred to maintain focus on delivery.
---
lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  generated_by: "cascade"
  validation_status: "pending"
  ascii_compliance: "confirmed"
  last_validated: "20260421124200"
---
<!-- ASCII_ART_BLOCK -->
. /#\ .................../#\ . .------------- LUPOPEDIA Semantic Operating System ------------.
/###\................../###\ .| -------------------------------------------------------------|
/#####\ . ######### . ./#####\ | A two-dimensional, finite, constitutional PRD documentation  |
############################## | architecture that lets docs build software. PRDs reference   |
############################## | other PRDs, forming clusters that define behavior, truth,    |
. ####### ########## ####### .| limits, and system identity. Each file carries a header that |
######## o ###### o ######### .| records the exact prd_cluster (reading order), the full     |
########## ###### ########### .| transcript_jsonl dialog, and atoms_toon for canonical truth,|
. ########################## . | ensuring deterministic lineage and reproducibility.         |
. . . . ############### . . . .| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com  |
. . . . ####|-----|#### . . . .----------------------------------------------------------------
. . . . ####|_____|#### . . . .| https://www.lupopedia.com/                                 |
. . . . ############# . . . . .--------------------------------------------------------------.
<!-- /ASCII_ART_BLOCK -->
## STATUS

This version is CLOSED.

All active TODO items have been moved to:

docs/versions/4.1.6/todo.md

Do not add new tasks here.

# TODO -- Lupopedia 4.1.5 (Target: Stable before May 1, 2026) (CLOSED)

**Scope of 4.1.5:** Complete Crafty Syntax 3.7.5 live-help features with human-only support.  
Multi-AI orchestration deferred to maintain delivery focus.

## Carried Forward From 4.1.3 (Unfinished Tasks)

### A - PRD & Schema Foundation (must be completed first)

- [ ] PRD 02 family finalized (channels projection, no global feed)
- [ ] install_new_lupopedia.sql and import_from_old_crafty_syntax.sql aligned and verified
- [ ] Crafty tables fully mapped per PRD 13
- [ ] Full repo audit: ASCII-only (run `python scripts/sanitize_ascii.py --fix`), headers on every touched file, PRD-first compliance
- [ ] Template-first rule strictly enforced (templates/ before any runtime UI)

Unlocks: B

### B - Channels Engine Core

- [ ] channels/index.php wiring complete using PRD 02 patterns (projection SQL only, visitor sidebar state machine, tab modes, composer with active actor targeting)
- [ ] Session routing via lupo_sessions (from/to actor/session)
- [ ] All new UI elements originate in templates/

Unlocks: C

### C - Crafty Live-Help Parity Features

- [ ] Visitor chat initiation, operator routing, presence, departments
- [ ] Canned responses (storage + quick insert into chat)
- [ ] Auto-invite, pre-chat questions, layer invites, offline messages, push URL
- [ ] Transcript persistence in dialog tables + basic reporting (visits, referrers, sessions)
- [ ] Widget/embed functional in shared-hosting / subfolder installs

Unlocks: D

### D - Installability & Stability

- [ ] Fresh install from install_new_lupopedia.sql completes cleanly
- [ ] Crafty 3.7.5 import path works with full data integrity
- [ ] Shared-hosting safe (64 MB limit, subdirectory support)
- [ ] Error handling, security baseline, session persistence across pages
- [ ] Non-AI run audit (system installs and runs without orchestration dependencies)
- [ ] Before running import: confirm all 34 livehelp_* source tables present
- [ ] Run migration scripts in documented order (Steps 1-5); verify row counts after Step 4
- [ ] Run drop_old_crafty_syntax_tables.sql as FINAL step only after Step 4 confirmed clean
- [ ] Document livehelp_channels and livehelp_operator_channels as intentionally deferred
      (no data migration in 4.1.3 -- product decision required for 4.1.5+)

Unlocks: E

### E - Stable Substrate (4.1.3 endpoint)

- [ ] Channel projection clean and stable
- [ ] Actor registry and session model locked
- [ ] Doctrine, headers, and PRD alignment fully enforced
- [ ] No drift between docs and code

This completes 4.1.3. Ship when A through E are green.

Unlocks: F

### Database Import Critical Items

- [ ] Document livehelp_config seed dependency (approved with precondition)
- [ ] Product decision needed for livehelp_channels (transient vs persistent)
- [ ] Evaluate operator-channel relationship importance

### 4.1.5+ Development Items (Deferred)

- [ ] Formally remove the "thread_slug" concept from documentation and code
- [ ] Define a helper-only slug generator for filesystem operations
- [ ] Define a naming convention for .toon and .jsonl files
- [ ] Keep all slug logic OUT of the header contract

---

## New 4.1.5 Tasks

### Priority 1: Complete 4.1.3 Foundation

- [ ] Fix atoms reference in all PRD headers
- [ ] Ensure prd_cluster is correct in all active PRDs
- [ ] Update WHY files with latest constitutional changes
- [ ] Freeze 4.1.5 once foundation is solid

### Priority 2: Crafty Syntax Feature Completion

- [ ] Complete all visitor-facing chat features
- [ ] Implement operator dashboard with human-only workflow
- [ ] Add department management and routing logic
- [ ] Build canned response system with quick insert
- [ ] Create auto-invite and pre-chat question modules
- [ ] Implement offline message handling
- [ ] Add transcript viewing and basic reporting

### Priority 3: Installation & Deployment

- [ ] Test full installation cycle on clean environment
- [ ] Validate Crafty 3.7.5 import with real data
- [ ] Ensure shared-hosting compatibility
- [ ] Document installation and upgrade procedures

### Priority 4: Quality Assurance

- [ ] Security audit and hardening
- [ ] Performance testing under load
- [ ] Cross-browser compatibility testing
- [ ] Mobile responsiveness validation

---

## Deadline Focus

**Target Date:** May 1, 2026  
**Primary Goal:** Deliver working Crafty Syntax 3.7.5 parity with human-only live support  
**Secondary Goal:** Stable, installable substrate for future AI orchestration

All effort focused on delivery. Multi-AI features deferred to maintain schedule.

---

## Root TODO Content (Migrated from root TODO.md)

### Immediate: 4.0.x Iterations

- [ ] Stabilize the system across iterative 4.0.x releases.
- [ ] Refine channel structure and migration batches.
- [ ] Implement missing feature surfaces required for reviewer closure.
- [ ] Address Softaculous feedback in each review loop.

### Medium-Term

- [ ] Complete the channel refactor.
- [ ] Implement the `lupopedia_js.php` navigation and tracking system.
- [ ] Build the CLI workflow surface.
- [ ] Build validator and reporting tooling.

### Long-Term: 4.1.0 Milestone

- [ ] Reach production readiness after approved 4.0.x baseline.
- [ ] Complete full deployment hardening.
- [ ] Complete federation integration work that belongs in the post-approval milestone.
- [ ] Enter 4.1.0 only after Softaculous approval of a 4.0.x baseline.

### Legacy Crafty Syntax Upgrade Path Requirements

- [ ] Support Crafty Syntax installs from version 2.2 through 3.7.5
- [ ] Normalize all Crafty Syntax installs from 3.6.0+ to 3.7.5 handling (including official 3.6.0+, 3.7.x, and forked variants labeled 4.0.0+ and 5.0.0+)
- [ ] Document that multiple Crafty Syntax forks existed with version label changes without database structure changes
- [ ] Research `craftysyntax-reference/setup.php` to extract all ALTER statements, upgrade path logic, and version transition logic for versions 2.2 through 3.6.0
- [ ] Implement installer/import logic to detect Crafty version, run required ALTER statements for pre-3.6.0 installs, and normalize all 3.6.0+ installs to 3.7.5 handling
- [ ] Use `craftysyntax-reference/setup.php` as canonical source for reconstructing old-version database upgrade steps

### Recent Session Notes

- **2026-04-18 ~03:57 UTC** -- EOD handoff: channels **dark channel-sidecar** (right rail); **`?api_dashboard=1`** uses **`templates/admin/api_dashboard.php`** partial + **`admin_layout.php`**; **PRD 45** template-first staged UI workflow + **PRD_INDEX** / **PRD 02** cross-links; captain log **LUPOPEDIA headers** (`20260418_apple2e_2_supercomputer.md`); changelog buffer consolidated into [`docs/versions/4.1.3/changelog.md`](docs/versions/4.1.3/changelog.md). **Next:** unify task surfaces (HERMES `lupopedia_dialog_pending_tasks` vs `TaskService` / `lupopedia_tasks`); optional **PRD 45** implementation mirror once **`docs/implementations/_template`** exists; resume channels / prompt library follow-through.
- **2026-04-12 13:48 UTC** -- PRD **core 01-49** vs **secondary 70-99** renumber; **Database Design Doctrine** -> **PRD 80**; **`PRD_INDEX`** + memory + pseudocode. Changelog: [`docs/versions/4.0.99/CHANGELOG.md`](docs/versions/4.0.99/CHANGELOG.md) (**`[2026-04-12 13:48 UTC]`**). Status: [`docs/versions/4.0.99/status/session_report_20260412_eod_prd_renumber_cursor102.md`](docs/versions/4.0.99/status/session_report_20260412_eod_prd_renumber_cursor102.md). Version backlog: [`docs/versions/4.0.99/TODO.md`](docs/versions/4.0.99/TODO.md) / [`PLAN.md`](docs/versions/4.0.99/PLAN.md).
- **2026-04-12 02:32 UTC** -- Version changelog: [`docs/versions/4.0.99/CHANGELOG.md`](docs/versions/4.0.99/CHANGELOG.md) (KAIROS **`node_status`** + Pattern **#6** hook + **`AGENTS.md`** v4.0.99 header). Status: [`docs/versions/4.0.99/status/session_report_20260412_eod_kairos_pattern6_agents_cursor102.md`](docs/versions/4.0.99/status/session_report_20260412_eod_kairos_pattern6_agents_cursor102.md).

### 2026-04-16 HERMES Task Queue Follow-up

- [x] `bin/agent_poll_tasks.php` reviewed and set to `status: "active"` in header.
- [x] Added `api/v1/tasks/complete.php` for agent completion updates on `lupo_dialog_pending_tasks` (`completed` / `failed`).
- [ ] Unify UI + API task surfaces: `channels/index.php` currently creates/reads `lupo_dialog_pending_tasks` (HERMES path) while `/api/v1/tasks/list.php` is backed by `lupo_tasks` (`TaskService` path).
- [ ] Decide canonical task read surface for sidebar (`Recent Tasks`) and make both human UI + agent poller consume one source of truth.
- [ ] If result summaries are required on HERMES queue completion, add a doctrine-aligned persistence field/path for `result_summary` (currently accepted by endpoint but not stored in `lupo_dialog_pending_tasks`).

---

## Revision note

| Date | Change |
|------|--------|
| 20260421124200 | Created 4.1.5 TODO with unfinished tasks carried forward from 4.1.3 |
| 20260422000000 | Migrated root TODO.md content to version-specific location for rapid development workflow |
