---

## Deferred Doctrine -- Diff Validation Enforcement (Constitutional)

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

Deferred for 4.1.7 implementation.

---

## Tomorrow - Semantic Breakthrough Follow-up
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

Reference: docs/versions/4.1.7/minimum_version.md

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

## TOP 3 MUST-FINISH (4.1.7) - June 1st Minimum Version

**PRIORITY 1: Visitor Chat Core**
- [ ] **MUST-1** -- Visitor chat initiation & operator routing (Live Help core functionality)
  - [ ] **MUST-1a** -- **Path Correctness**: Confirm `config.php` resolves to `/includes/` (not `lupo-includes/`)
  - [ ] **MUST-1b** -- **Session Persistence**: Ensure chat sessions survive the transition from the widget to the database
- [ ] **MUST-2** -- Canned responses & pre-chat questions (Essential Crafty features)
- [ ] **MUST-3** -- Widget/embed functional in shared-hosting environments

**PRIORITY 2: Database Import Path**
- [ ] **MUST-4** -- Crafty 3.7.5 import path with full data integrity
- [ ] **MUST-5** -- Fresh install from install_new_lupopedia.sql completes cleanly
- [ ] **MUST-6** -- Confirm all 34 livehelp_* source tables present before import
- [ ] **MUST-10** -- **Verify Prefix Decoupling**: Ensure installer handles unprefixed filesystem (`includes/`) while maintaining `lupo_` prefixed database tables
- [ ] **MUST-11** -- **Config Generation Audit**: Fix missing configuration values in `config.php` to map to new paths
- [ ] **MUST-12** -- **2.2 -> 3.6.0 Alter Reconstruction**: Implement `ALTER` statements from `craftysyntax-reference/setup.php` for ultra-legacy installs
- [ ] **MUST-13** -- **Fork Detection**: Implement logic to treat forked versions (4.0.x and 5.0.0) as standard 3.7.5 database structures

**PRIORITY 3: Installation Stability**
- [ ] **MUST-7** -- Shared-hosting safe (64 MB limit, subdirectory support)
- [ ] **MUST-8** -- Error handling, security baseline, session persistence
- [ ] **MUST-9** -- Non-AI run audit (system installs without orchestration)
- [ ] **MUST-14** -- **Baseline Live-Help Validation**: Verify core chat functions as "Human-to-Human" tool post-install, preserving original Crafty Syntax behavior

---

## DEFER -- 4.1.8+ (Post-Minimum Version)

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
