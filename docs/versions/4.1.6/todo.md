---

## Deferred Doctrine — Diff Validation Enforcement (Constitutional)

A validation gap was identified during PRD 50 enforcement work.

Agents were able to report "corrections" without producing meaningful diffs.

This creates false-positive validation and breaks auditability.

### Required Rule (to be implemented in PRD 50 and PRD 98_A)

A reported change MUST include a non-empty, meaningful diff.

The following are INVALID validation states:
- Diff is empty
- Diff shows identical before/after lines
- Diff does not include the claimed change
- Diff only modifies whitespace when a semantic change was claimed

If any invalid state is detected:
- VALIDATOR STATUS: FAIL
- A WHY file SHALL be generated
- The change SHALL NOT be accepted

Validation MUST be based on actual diff output, not agent-reported status.

### Status

Deferred for 4.1.6 implementation.

---

## Tomorrow � Semantic Breakthrough Follow-up
Captain identified a potential semantic architecture breakthrough using Hawaiian/Pidgin deterministic field mapping.
### Tasks
* Document the semantic model cleanly (kapakai, puka, pono, kuleana, alii, kumu)
* Validate behavior across multiple agents
* Identify edge cases and failure modes
* Confirm repeatability outside Captain-driven context
* Evaluate whether broader application is warranted
### Note
This is NOT a "change the world" task.
This is a **prove it works first** task.

---

## Minimum Version Push -- June 1

Reference: docs/versions/4.1.6/minimum_version.md

Read minimum_version.md before accepting any new scope into this session.

### Rules for This Push

- Convert open work into MUST / SHOULD / DEFER before starting.
- Protect Captain capacity. No 1100% mode.
- No new major architecture unless required for minimum version.
- PRD first, schema second, mockup third, code last.
- Maintain validation and header doctrine. No silent drift.
- One lane at a time. Finish before opening the next.

### Today / Next Session -- Checklist

1. Identify top 3 MUST-finish items from minimum_version.md.
2. Move all nonessential work to DEFER.
3. Confirm no health/capacity warning from docs/captains_log/captain_wake_sleep_log.md.
4. Work only one lane at a time.

---

## TOP 3 MUST-FINISH (4.1.6) - June 1st Minimum Version

**PRIORITY 1: Visitor Chat Core**
- [ ] **MUST-1** — Visitor chat initiation & operator routing (Live Help core functionality)
  - [ ] **MUST-1a** — **Path Correctness**: Confirm `config.php` resolves to `/includes/` (not `lupo-includes/`)
  - [ ] **MUST-1b** — **Session Persistence**: Ensure chat sessions survive the transition from the widget to the database
- [ ] **MUST-2** — Canned responses & pre-chat questions (Essential Crafty features)
- [ ] **MUST-3** — Widget/embed functional in shared-hosting environments

**PRIORITY 2: Database Import Path**
- [ ] **MUST-4** — Crafty 3.7.5 import path with full data integrity
- [ ] **MUST-5** — Fresh install from install_new_lupopedia.sql completes cleanly
- [ ] **MUST-6** — Confirm all 34 livehelp_* source tables present before import
- [ ] **MUST-10** — **Verify Prefix Decoupling**: Ensure installer handles unprefixed filesystem (`includes/`) while maintaining `lupo_` prefixed database tables
- [ ] **MUST-11** — **Config Generation Audit**: Fix missing configuration values in `config.php` to map to new paths
- [ ] **MUST-12** — **2.2 → 3.6.0 Alter Reconstruction**: Implement `ALTER` statements from `craftysyntax-reference/setup.php` for ultra-legacy installs
- [ ] **MUST-13** — **Fork Detection**: Implement logic to treat forked versions (4.0.x and 5.0.0) as standard 3.7.5 database structures

**PRIORITY 3: Installation Stability**
- [ ] **MUST-7** — Shared-hosting safe (64 MB limit, subdirectory support)
- [ ] **MUST-8** — Error handling, security baseline, session persistence
- [ ] **MUST-9** — Non-AI run audit (system installs without orchestration)
- [ ] **MUST-14** — **Baseline Live-Help Validation**: Verify core chat functions as "Human-to-Human" tool post-install, preserving original Crafty Syntax behavior

---

## DEFER — 4.1.7+ (Post-Minimum Version)

### WOLFITH Agent Development (DEFERRED)
- [ ] WOLFITH hybrid logic implementation (LILITH + WOLFIE inheritance)
- [ ] Processed Wisdom Protocol and Soil Time framework
- [ ] Dreaming Protocol with Graph-Native mapping
- [ ] THOTH's Mirror Doctrine integration
- [ ] Agent Autonomy Standard development

### Semantic Architecture (DEFERRED)
- [ ] Hawaiian/Pidgin deterministic field mapping validation
- [ ] Semantic model documentation (kapakai, puka, pono, kuleana, alii, kumu)
- [ ] Cross-agent semantic behavior validation
- [ ] Edge case identification and failure mode analysis

### Deferred Doctrine (DEFERRED)
- [ ] Diff Validation Enforcement (PRD 50 and PRD 98_A)
- [ ] Validation based on actual diff output, not agent-reported status
- [ ] WHY file generation for invalid validation states

---

---

## Consolidated TODO (Carried Forward from 4.1.0–4.1.5)

### High Priority Backlog (from 4.1.0)

- [ ] **H-01** — Trust Ladder: SELECT FOR UPDATE locking (verify/close in current tree)
- [ ] **H-02** — Staging GC: exclude lineage edges (verify/close in current tree)
- [ ] **H-03** — PostgreSQL migration support for new installs
- [ ] **H-04** — Cross-agent verification of latest Lupopedia headers + sidecars
- [ ] **H-06** — Review and refine generated placeholder section/rule summaries in new PRD memory JSONs

### Medium Priority (from 4.1.0)

- [ ] **M-01** — Probabilistic GC (no cron) + session clearing audit/update
- [ ] **M-02** — Semantic Widget session handling in PHP (not JS)
- [ ] **M-03** — PHPStan integration
- [ ] **M-04** — Add optional rule-extraction pass to PRD memory generators (MUST/SHALL/FORBIDDEN signal extraction)
- [ ] **M-05** — Add status-folder aggregation script for session lessons/troubles report indexing
- [ ] **M-15** — Web-based validator for shared hosting (Python/JS, no CLI required)
- [ ] **M-16** — Memory file generation/edge creation for all PRDs (batch + on-demand)
- [ ] **M-06** — Optional CI gate: run `validate_lupopedia_headers_universal.py` on changed Markdown/Python (decide **strict** vs **`--development`** for legacy `http://` and pairing)
- [ ] **M-07** — Propagate `pre-commit-lupopedia-headers.sample` into contributor docs / optional install note
- [ ] **M-08** — Align `bin/temporal_anchor.json` / `echo_anchor_utc.py` with `tick.py` session UTC (ops + doc): eliminate drift for header batches
- [ ] **M-09** — Retitle remaining `docs/doctrine/LUPOPEDIA_HEADERS/*` files whose H1 still says **(v3)** to **(v4)** or cross-reference PRD 16 v4.0.0
- [ ] **M-11** — Optional: **`--update-existing`** on `add_lupopedia_headers_everywhere.py` (refresh `when_updated` / `last_modified_utc` only, no duplicate blocks)
- [ ] **M-13** — Bulk migrate legacy **`memory_key` / path strings** **`.../canonical/2026/...`** → **`.../canonical/1026/...`** (align **`validate_trust_ladder_paths.py`**, **`--strict-memory-year`**, PRD 16 §8.1); optional CI: **`validate_trust_ladder_paths.py --strict`** when clean
- [ ] **M-14** — Optional **`readme-root.json`** master next to **`readme-root.toon`** if strict JSON↔TOON pairing is required for root README memory
- [ ] **M-18** — Make **`--validate`** default in **`add_lupopedia_header_to_file.py`** (or auto-run **`validate_lupopedia_headers_universal.py`** after write)
- [ ] **M-19** — Pre-flight check: assert **22** scalar keys before emitting Markdown/Python header blocks
- [ ] **M-20** — Wire **CI gate** — pre-commit hook or GitHub Action (same intent as **M-06**; pick one owner path and close the duplicate row when done)
- [ ] **M-21** — Optional **`--auto-fix`** (or repair mode) in **`validate_lupopedia_headers_universal.py`** for common mechanical failures
- [ ] **M-22** — Agent prompt template: **peel before write, validate after write** — **`AGENTS.md`** / **`.cursor/rules`**

### Low Priority (from 4.1.0)

- [ ] **L-01** — IPv6 test coverage for `ipNetworkPrefix()`
- [ ] **L-02** — Exponential backoff for suffix exhaustion
- [ ] **L-03** — Comment-embedded header builders + validator paths for **`.php`**, **`.sql`**, **`.html`** (behind explicit scope flag)
- [ ] **L-04** — **Git hygiene:** avoid **`git add .`** without scanning for root scratch files (`_x.txt`, temp logs); prefer scoped **`git add -p / path lists** when possible; document in **AGENTS.md** or **CONTRIBUTING.md**

### Carried Over Pending Work (from 4.1.0)

- [ ] T-VERIFY clean-install checks (`T-VERIFY-001`..`005`) from 4.0.94 closeout
- [ ] Packaging smoke/deploy checks previously tracked after 4.0.94 pre-release verification
- [ ] Step 3 Actor Reconstruction Pass (deferred in 4.0.94)
- [ ] Seed/registry reconciliation tasks previously left open in 4.0.96 (`validate_trust_ladder_registry.py`, seed content_id band checks, audit report)
- [ ] Session test carryovers from 4.0.96 (`D-004`, `D-005`)

### Interface Implementation (from 4.1.2)

- [ ] **P0-A** — Orchestration Database Setup — verify 142 tables in fresh install environment
- [ ] **P0-B** — API Endpoints — `/api/routing/send-to-channel`, `/api/context/switch`, `/api/agent/status`
- [ ] **P0-C** — channels/index.php Extensions — 3-column layout, Active Target Bar, dual-selector routing modal
- [ ] **P0-D** — Left Panel API — fetch active actors, recent files, and pending tasks for current channel
- [ ] Transcript capture — agent stdout → `lupo_dialog_messages` (THOTH/ANUBIS pipeline)
- [ ] THOTH worker — poll `lupo_dialog_messages` → validate headers → report

### Budget-Driven Priorities (from 4.1.2)

- [ ] Efficiency audit — Review all agent workflows for token waste and duplicate reasoning
- [ ] Free-tier routing policy — Route simple tasks to free agents first, reserve paid agents for heavy lifting
- [ ] Handoff toon completeness — Ensure every agent writes handoff toons before expensive or cross-tier work
- [ ] Translation channel reuse — Extract summaries instead of regenerating explanations
- [ ] Rate-limit continuity drills — Validate cross-tier handoff when free agents hit limits
- [ ] Free IDE continuity monitoring — Track free-tier rate-limit events and fallback handoff success
- [ ] Claude Code cost tracking — Stay within $17/month
- [ ] Runtime API budget tracking — Keep user-facing API spend within $33/month target
- [ ] API usage inventory — Document every tool that requires paid API calls and expected call volume
- [ ] API model downgrade policy — Default to mini-tier API models unless premium is justified
- [ ] API response caching — Add cache layer for repeated prompts and deterministic utility outputs
- [ ] API alternatives benchmark — Compare OpenAI API costs against DeepSeek/Groq/Together/local baselines
- [ ] Runtime provider chain — Implement provider priority chain (free-tier -> cheap paid -> premium fallback)
- [ ] BYOK support — Add user-provided API key configuration for self-hosted runtime
- [ ] Runtime spend telemetry — Track per-provider call counts and estimated monthly cost
- [ ] Fallback kill-switch — Disable premium provider fallback when monthly budget threshold is reached

### Dependency Chain A-E (from 4.1.3)

#### A - PRD & Schema Foundation

- [ ] PRD 02 family finalized (channels projection, no global feed)
- [ ] install_new_lupopedia.sql and import_from_old_crafty_syntax.sql aligned and verified
- [ ] Crafty tables fully mapped per PRD 13
- [ ] Full repo audit: ASCII-only (run `python scripts/sanitize_ascii.py --fix`), headers on every touched file, PRD-first compliance
- [ ] Template-first rule strictly enforced (templates/ before any runtime UI)

#### B - Channels Engine Core

- [ ] channels/index.php wiring complete using PRD 02 patterns (projection SQL only, visitor sidebar state machine, tab modes, composer with active actor targeting)
- [ ] Session routing via lupo_sessions (from/to actor/session)
- [ ] All new UI elements originate in templates/

#### C - Crafty Live-Help Parity Features

- [ ] Visitor chat initiation, operator routing, presence, departments
- [ ] Canned responses (storage + quick insert into chat)
- [ ] Auto-invite, pre-chat questions, layer invites, offline messages, push URL
- [ ] Transcript persistence in dialog tables + basic reporting (visits, referrers, sessions)
- [ ] Widget/embed functional in shared-hosting / subfolder installs

#### D - Installability & Stability

- [ ] Fresh install from install_new_lupopedia.sql completes cleanly
- [ ] Crafty 3.7.5 import path works with full data integrity
- [ ] Shared-hosting safe (64 MB limit, subdirectory support)
- [ ] Error handling, security baseline, session persistence across pages
- [ ] Non-AI run audit (system installs and runs without orchestration dependencies)
- [ ] Before running import: confirm all 34 livehelp_* source tables present
- [ ] Run migration scripts in documented order (Steps 1-5); verify row counts after Step 4
- [ ] Run drop_old_crafty_syntax_tables.sql as FINAL step only after Step 4 confirmed clean
- [ ] Document livehelp_channels and livehelp_operator_channels as intentionally deferred (no data migration in 4.1.3 -- product decision required for 4.1.5+)

#### E - Stable Substrate

- [ ] Channel projection clean and stable
- [ ] Actor registry and session model locked
- [ ] Doctrine, headers, and PRD alignment fully enforced
- [ ] No drift between docs and code

### Database Import Critical Items (from 4.1.3)

- [ ] Document livehelp_config seed dependency (approved with precondition)
- [ ] Product decision needed for livehelp_channels (transient vs persistent)
- [ ] Evaluate operator-channel relationship importance

### 4.1.5+ Development Items (Deferred from 4.1.3)

- [ ] Formally remove the "thread_slug" concept from documentation and code
- [ ] Define a helper-only slug generator for filesystem operations
- [ ] Define a naming convention for .toon and .jsonl files
- [ ] Keep all slug logic OUT of the header contract

### Priority 1: Complete 4.1.3 Foundation (from 4.1.4/4.1.5)

- [ ] Fix atoms reference in all PRD headers
- [ ] Ensure prd_cluster is correct in all active PRDs
- [ ] Update WHY files with latest constitutional changes
- [ ] Freeze 4.1.5 once foundation is solid

### Priority 2: Crafty Syntax Feature Completion (from 4.1.4/4.1.5)

- [ ] Complete all visitor-facing chat features
- [ ] Implement operator dashboard with human-only workflow
- [ ] Add department management and routing logic
- [ ] Build canned response system with quick insert
- [ ] Create auto-invite and pre-chat question modules
- [ ] Implement offline message handling
- [ ] Add transcript viewing and basic reporting

### Priority 3: Installation & Deployment (from 4.1.4/4.1.5)

- [ ] Test full installation cycle on clean environment
- [ ] Validate Crafty 3.7.5 import with real data
- [ ] Ensure shared-hosting compatibility
- [ ] Document installation and upgrade procedures

### Priority 4: Quality Assurance (from 4.1.4/4.1.5)

- [ ] Security audit and hardening
- [ ] Performance testing under load
- [ ] Cross-browser compatibility testing
- [ ] Mobile responsiveness validation

### Legacy Crafty Syntax Upgrade Path Requirements (from 4.1.4/4.1.5)

- [ ] Support Crafty Syntax installs from version 2.2 through 3.7.5
- [ ] Normalize all Crafty Syntax installs from 3.6.0+ to 3.7.5 handling (including official 3.6.0+, 3.7.x, and forked variants labeled 4.0.0+ and 5.0.0+)
- [ ] Document that multiple Crafty Syntax forks existed with version label changes without database structure changes
- [ ] Research `craftysyntax-reference/setup.php` to extract all ALTER statements, upgrade path logic, and version transition logic for versions 2.2 through 3.6.0
- [ ] Implement installer/import logic to detect Crafty version, run required ALTER statements for pre-3.6.0 installs, and normalize all 3.6.0+ installs to 3.7.5 handling
- [ ] Use `craftysyntax-reference/setup.php` as canonical source for reconstructing old-version database upgrade steps

### HERMES Task Queue Follow-up (from 4.1.4/4.1.5)

- [ ] Unify UI + API task surfaces: `channels/index.php` currently creates/reads `lupo_dialog_pending_tasks` (HERMES path) while `/api/v1/tasks/list.php` is backed by `lupo_tasks` (`TaskService` path)
- [ ] Decide canonical task read surface for sidebar (`Recent Tasks`) and make both human UI + agent poller consume one source of truth
- [ ] If result summaries are required on HERMES queue completion, add a doctrine-aligned persistence field/path for `result_summary` (currently accepted by endpoint but not stored in `lupo_dialog_pending_tasks`)

### Medium Priority Items (from 4.1.2)

- [ ] Memory graph edges — build real edges between active PRDs
- [ ] .toon generation — batch regenerate for active corpus
- [ ] Agent wrapper scripts — standard script for each registered agent
- [ ] Archive deprecated PRDs — move stale docs to `docs/prd/archive/`
- [ ] M-13 — Bulk migrate `memory_key` paths `2026/` → `1026/` year segment
- [ ] M-21 — Optional `--auto-fix` / repair mode in universal validator

### Low Priority Items (from 4.1.2)

- [ ] Clean up old version folders — review and archive 4.0.93–4.0.98 status files
- [ ] L-03 — Comment-embedded header builders for `.php`, `.sql`, `.html`

---
