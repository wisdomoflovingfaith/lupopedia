---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260408161022"
  file_path_from_root: "lupo-docs/versions/4.0.96/PLAN.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.96/PLAN.md"
  last_modified_utc: "20260408161022"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.96-plan"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "plan"
  artifact_kind: "version_roadmap"
  purpose: "Implementation plan for Lupopedia 4.0.96 — sequenced phases from current state through 4.1.0 gate readiness"
  tags: ["plan", "version", "4.0.96", "cursor"]
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.96/TODO.md"
      type: references
      weight: 1.0
      reason: "Task-level breakdown of each phase"
    - to: "lupo-docs/versions/4.0.96/CHANGELOG.md"
      type: references
      weight: 1.0
      reason: "Completed work record"
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor for all phases"
lupopedia.footer:
  last_verified: "20260408161022"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.96/PLAN.md — delegation: cursor:root

# Plan — Lupopedia 4.0.96

**Updated:** `20260408161022` UTC — Cursor IDE Agent (actor_id 102)

**Purpose:** Sequenced implementation roadmap based on the current state of all PRD `next_action` fields, implementation gaps identified by audit, and work completed in the 4.0.96 thread to date. Each phase cites the PRD(s) it satisfies.

**Reading this plan:** Phases are ordered by dependency — later phases cannot start before the blocking items in earlier phases are resolved. Within a phase, items marked **(blocking)** must land before the rest of that phase. Items marked **(can parallel)** are independent and may proceed concurrently.

---

## State as of 2026-04-08 16:10 UTC

### Completed in 4.0.96 (not repeated in phases below)

| Component | PRD | Status |
|-----------|-----|--------|
| Chronological Trust Ladder doctrine (§§1–13, Appendix A) | CTL, PRD 00 §3.7 | ✅ Done |
| `IdGenerator` — string-safe canonical/staging transforms | CTL §2.2 | ✅ Done |
| `IdGenerator::isReservedSpace()` bug fix (length-based) | CTL §2.2.2 | ✅ Done |
| `KairosConsolidationService` on `lupo_memory_nodes` | PRD 37, PRD 38 | ✅ Done |
| `MemoryExportService` — filesystem mirror | PRD 38 §6 | ✅ Done |
| `TRUST_LADDER_REGISTRY.md` + `RETENTION_POLICY.md` | CTL §9 | ✅ Done |
| `validate_trust_ladder_registry.py` | CTL §10 | ✅ Done |
| `audit_edge_integrity.py` | CTL §9.2 | ✅ Done |
| §13 test suite — 49 unit tests, all passing | CTL §13 | ✅ Done |
| `StagingGcService` + `staging-gc` CLI | RETENTION_POLICY, PRD 19 | ✅ Done |
| `AdminTrustLadderHandler` — admin web UI | FOR_CLAUDE_CODE §Future | ✅ Done |
| Memory unification schema (`lupo_memory_nodes`, `lupo_memory_edges`) | PRD 38 | ✅ Done |
| 4D edge columns on `lupo_edges` | PRD 04 | ✅ Done |
| `memory.json` deprecated across PRDs | PRD 01, 07, 15, 24 | ✅ Done |
| Actor registry + Claude Code actor_id 116 | PRD 15 | ✅ Done |
| Install seed doctrine (draft text) | PRD 41 | ✅ Draft |
| PRD 17 validators (`validate_thread_structure.py`, `validate_edge_linking.py`) | PRD 17 | ✅ Done |
| `lupo_trust_ladder_registry` table DDL + 13 seed rows in install SQL | CTL §9.1 | ✅ Done |
| `TrustLadderRegistry.php` — PHP runtime registry class | CTL §9.1, note_on_seed_range.md | ✅ Done |
| `sync_trust_ladder_registry_to_db.py` — sync script | CTL §10, note_on_seed_range.md | ✅ Done |
| `trust_ladder_registry_test.php` — 47 unit tests, all passing | CTL §13 | ✅ Done |
| `IdGenerator::toCanonicalIdSafe()` registry archetype check | CTL §2.2 | ✅ Done |
| `LEGACY_GAP_ANSWERS.md` — 11 legacy-gap questions answered | note_on_seed_range.md | ✅ Done |
| Session identity + privacy fingerprints + `session_identity_hash`; `LUPO_SESSION_SALT`; UA normalize; resolved client IP; `validateSession` idle + optional UA; probabilistic `lupo_sessions` GC; slim `SessionManager` | PRD 01, `SESSIONS_RESEARCH.md` | ✅ Done (20260408161022 UTC) |

---

## Phase 1 — Doctrine finalization and approval gates
**PRDs:** PRD 41, PRD 38, PRD 00, PRD 37

**Objective:** Close open draft status on the two most foundational PRDs before any new runtime code is written that depends on them. These are approval-only tasks, not implementation.

**Why first:** PRD 41 is status `draft` and explicitly requires LILITH audit and WOLFIE approval before downstream code (KAIROS enforcement, ActorService reversion) can be written with confidence. PRD 38 amendments §11.1–11.5 require applying updated prose to PRDs 07, 15, 24, 37, and 00 §5.7 — blocking clean alignment of all memory-related PRDs.

### 1.1 PRD 41 — Approve install seed doctrine **(blocking for Phase 3)**
- LILITH constitutional audit of PRD 41 full text (status currently `draft`)
- WOLFIE product approval
- Update PRD 41 status field → `approved`
- Update `when_updated` header to approval timestamp

### 1.2 PRD 38 — Apply §11 amendments to downstream PRDs
- **§11.1** — PRD 00 §5.7: document memory graph + export mirror as constitutional requirement
- **§11.2** — PRD 07 (`agents_faucets`): remove remaining `memory.json` references; document unified graph
- **§11.3** — PRD 15 (`actors`): align workspace file tree and learning-process steps to `lupo_memory_nodes`
- **§11.4** — PRD 24 (`cli_interface_prd`): align memory CLI spec to unified graph
- **§11.5** — PRD 37 (`kairos`): align observation/canonical storage to PRD 38 §4.2

### 1.3 PRD 38 — Resolve `lupo_edges` vs `lupo_memory_edges` dual-table
- Document the canonical split: `lupo_memory_edges` = edges between memory nodes specifically; `lupo_edges` = all other object-type pairings (PRD 38 §11.2 `next_action`)
- Update PRD 38 prose and edge-type taxonomy to make this explicit
- Update `TRUST_LADDER_REGISTRY.md` with the final participation class for `lupo_memory_edges`

---

## Phase 2 — CLI memory command suite
**PRDs:** PRD 24 (`next_action`: "Implement MemoryCommands.php with add, list, get, update, delete, export, archive, restore, edges")

**Objective:** Implement the full `memory` sub-command group in the CLI so that actors (including Claude Code) can read and write memory graph nodes and edges via `php lupo-bin/lupo.php memory <subcommand>`.

**Why second:** Memory commands are referenced in CLAUDE.md and `FOR_CLAUDE_CODE_ON_PK_IDS.md` as the primary interface for Claude Code to interact with the memory graph. All the underlying services (`MemoryExportService`, `KairosConsolidationService`, `StagingGcService`) are complete — what's missing is the CLI surface.

### 2.1 `lupo-includes/classes/MemoryCommands.php` **(blocking)**
Implement static handler dispatched from `lupo-bin/lupo.php` case `memory`:

| Sub-command | Behaviour | PRD ref |
|-------------|-----------|---------|
| `memory add` | INSERT staging row via `IdGenerator::generate()`; optionally promote to canonical via `toCanonicalIdSafe()` when `--canonical` flag passed | PRD 24 §5.1 |
| `memory list` | List memory nodes for `--actor N` with optional `--type` filter; respect query priority (canonical first) | PRD 24 §5.2 |
| `memory get` | Fetch single node by `--memory-id`; output JSON | PRD 24 §5.3 |
| `memory update` | UPDATE `memory_value` / `memory_type` on a canonical node; reject updates to staging without `--force` | PRD 24 §5.4 |
| `memory delete` | Soft-delete (`is_deleted = 1`) by `--memory-id`; reject seed-tier rows | PRD 24 §5.5 |
| `memory export` | Invoke `MemoryExportService::exportFull()` for `--actor N`; optional `--output-dir` | PRD 24 §5.6, PRD 38 §6 |
| `memory archive` | Implements PRD 38 §8 Option B — soft-delete original, insert new canonical, add `archived_to` edge | PRD 24 §5.8, PRD 38 §8 |
| `memory restore` | Reverse archive — soft-delete archived row, re-activate original via `restored_from` edge | PRD 24 §5.9 |
| `memory edges` | List edges where `--from-id` or `--to-id` is in `lupo_memory_edges`; optional `--edge-type` filter | PRD 24 §5.7 |

### 2.2 `lupo-bin/lupo.php` — route `memory` command **(can parallel with 2.1)**
Add `case 'memory':` to the switch, passing `$argv` slice to `MemoryCommands::dispatch()`.

### 2.3 Integration tests — `lupo-tests/integration/memory_cli_test.php`
Cover: add → list → get → delete lifecycle; add + promote to canonical; export generates filesystem mirror file; archive + restore round-trip. Skip gracefully when DB unavailable.

---

## Phase 3 — KAIROS channel ingest and contradiction resolution
**PRDs:** PRD 37 (`next_action`: channel-scoped ingest, recency-first contradictions), PRD 41 (install-seed immutability enforcement)

**Objective:** Complete KAIROS from a proof-of-concept consolidation service to a production-grade channel memory engine. Depends on Phase 1 (PRD 41 approval) because the enforcement of install-seed immutability must be authoritative before code guards are written.

**Why third:** KAIROS is the engine that feeds the memory graph. Until channel-scoped ingest works, `lupo_memory_nodes` is populated only by manual CLI operations and service calls — not by the real conversation stream.

### 3.1 Channel-scoped observation ingest **(blocking)**
Implement `KairosConsolidationService::ingestFromChannel(int $channelId, int $actorId, ?int $limit)`:
- Query `lupo_dialog_messages` for the channel, ordered by `created_ymdhis DESC`, up to `$limit`
- For each message not already represented in `lupo_memory_nodes` (check by `content_hash`): create a staging observation row via `IdGenerator::generate()`
- Source: PRD 37 §3 "Observation ingest from dialog messages"

### 3.2 Recency-first contradiction resolution **(can parallel with 3.1 after 3.1 DB rows exist)**
Extend `KairosConsolidationService::consolidate()`:
- When two canonical nodes share the same `memory_key` but have conflicting `memory_value`: promote the one with the later `created_ymdhis` as winner
- Mark the loser with a `kairos_contradicts` edge; set `edge_status = 'needs_review'`, `review_reason = 'contradiction'`
- Edge-weight override: if `weight_hundredths` difference between edges is `>= 20`, higher-weight wins regardless of recency (PRD 37 §5.3)

### 3.3 Install-seed immutability enforcement in KAIROS **(requires Phase 1.1)**
After PRD 41 approval:
- Add guard in `KairosConsolidationService::consolidate()`: if target PK is in seed space (`IdGenerator::isReservedSpace()`) throw `InvalidArgumentException` — seeds must never be used as canonical parents
- Add `ActorService::revertToInstall(int $actorId)` (PRD 41 §4): copy all fields from the seed row back to the living canonical row; add `reverted_to` edge; log to `lupo_unified_log`

### 3.4 Tests
- Unit: contradiction resolution winner selection (recency vs weight)
- Unit: `revertToInstall()` — verifies seed row is unchanged and canonical row is overwritten
- Integration: full ingest → consolidate → contradiction cycle with live DB

---

## Phase 4 — GC integration and random execution trigger
**PRDs:** PRD 19 (`next_action`: "Create GC script with random execution pattern"; "Add retention configuration to system_config")

**Objective:** Wire the existing `GarbageCollector` and new `StagingGcService` into the application request cycle so GC runs automatically without a cron dependency.

**Why fourth:** The GC services are built but disconnected. Until they run on a probabilistic trigger, staging rows accumulate indefinitely and the trust-ladder hygiene degrades. This is the lowest-effort high-impact task in this phase.

### 4.1 Random execution trigger in bootstrap
Following the pattern from PRD 19 §6 (probability-based per-request execution):
- In `lupopedia-config.php` or a bootstrap include: `if (rand(1, 200) === 1) { require_once ... ; (new GarbageCollector($db, $prefix))->run(); }`
- Add the analogous trigger for `StagingGcService::purge(90, 500)` at `rand(1, 500) === 1`
- Both must be wrapped in `try/catch` so GC failure never breaks a user request

### 4.2 Retention config in system_config **(can parallel)**
Add a `gc_staging_retention_days` key to `lupo_system_config` (or the config array) defaulting to `90`. `StagingGcService` reads this value from config if available, falling back to the hard-coded `90`.

### 4.3 Update PRD 19 to reflect actual implementation
- Mark `next_action` items as resolved in the footer
- Document the random-probability trigger approach in PRD 19 §6
- Cross-reference `StagingGcService` and `GarbageCollector` as the two implementations

---

## Phase 5 — Content seeding and truth table reconciliation
**PRDs:** PRD 42 (`next_action`: "Reconcile seed_online_help_and_content.sql with install schema"; "Align seed content_id bands with PRD 41 tier-0 policy")

**Objective:** Ensure that all seed SQL files reference the correct table names (as they exist in `install_new_lupopedia.sql`) and that their PK values fall within the documented seed band (`0–999,999` per PRD 41 §2.1).

**Why fifth:** PRD 42 has two open blockers that will cause CI drift if left unresolved. This is primarily a validation and alignment task, not a runtime implementation.

### 5.1 Audit seed SQL files against install schema
- Run `python lupo-scripts/validate_trust_ladder_registry.py` with `--install-sql` pointing to the canonical install path
- Cross-check each `INSERT` in seed SQL files: verify the target table name matches the install DDL (`lupo_truth_questions`, `lupo_truth_answers`, or whatever the canonical name is vs. legacy names)
- Document findings in `lupo-docs/versions/4.0.96/status/`

### 5.2 Align content_id seed PKs with PRD 41 tier-0 band
- Ensure all `content_id` values in seed SQL are between `0` and `999,999` (inclusive)
- If any exceed `999,999`: either re-key them to the correct band or document an explicit exception in `TRUST_LADDER_REGISTRY.md`
- Maximum seed value: `999,999` (not 1 quintillion)

### 5.3 `validate_seed_registry.py` — confirm it covers content seeds
- Check whether `lupo-scripts/validate_seed_registry.py` (confirmed to exist) validates content-table PKs
- If not, extend it or add a content-specific check per PRD 41 §2.1 and PRD 42

---

## Phase 6 — ROSE multi-persona synthetic dialog (Phase B)
**PRDs:** PRD 36 (`next_action`: "Implement RoseDialogService.php Phase B: per-thread organic counter, default trigger every 10 messages, rose_visibility + 2000-char enforcement"), PRD 18 (`next_action`: "Wire channel UI to channels-api; PRD 18: mandatory synthetic badge when metadata_json.rose_synthesis")

**Objective:** Deliver the production ROSE dialog service. Phase A (architecture, PRD, database columns) was completed in the 4.0.95/early 4.0.96 thread. Phase B is the runtime implementation.

**Why sixth:** ROSE is a product feature that is blocked on its own code, not on any other phase here. Can be parallelized with Phases 4–5 since it touches different parts of the codebase.

### 6.1 `RoseDialogService.php` — Phase B implementation
- Per-thread organic message counter: increment in `lupo_thread_state` (or equivalent) on each `lupo_dialog_messages` insert
- Trigger synthetic dialog insertion every 10 messages (configurable via system_config `rose_trigger_interval`)
- Enforce `rose_visibility` policy: synthetic messages from ROSE visible only to actors in the same department
- Hard enforce 2,000-character limit on synthetic messages before INSERT

### 6.2 PRD 18 — mandatory synthetic badge in channel chat display
- When `metadata_json.rose_synthesis = true`: render a visible badge in the chat display (`[Synthetic]` or equivalent)
- Organic same-actor messages: no badge
- Wire to `chat-display.js` / channels-api JSON response

### 6.3 Integration tests
- Test trigger cadence (every N messages fires exactly once)
- Test visibility enforcement (wrong-department actor cannot see ROSE messages)
- Test 2,000-char truncation / rejection

---

## Phase 7 — Code hygiene, header alignment, tooling
**PRDs:** PRD 00 §5.8 (implementation folders), PRD 16 (header validator), PRD 26 (validate_implementation.py), PRD 17 (pseudocode validator)

**Objective:** Reduce accumulated technical debt in validators, header compliance, and implementation folder alignment. These are housekeeping tasks that improve CI reliability but do not add product features.

### 7.1 PRD 16 — header validator accepts `author` block alone **(can parallel)**
- `lib/header_validation.validate_header` currently requires legacy `actor_id` / `actor_name` fields in addition to `author`
- Update the validator to accept either (`author` block alone is sufficient per 4.0.96 schema)
- PRD 16 footer documents this as "remaining debt"

### 7.2 PRD 26 — `validate_implementation.py` alignment **(can parallel)**
- Verify `validate_implementation.py` is in sync with current `doc_arch_version` values
- Add `doc_arch_version` to any `lupo-docs/implementations/` folders that lack it (per PRD 26 `next_action`)

### 7.3 PRD 00 — implementation folder stubs **(can parallel)**
- Per PRD 00 `next_action`: "PRD-scoped work: mirror under `lupo-docs/implementations/{prd_file_stem}/`"
- Create stub `THREAD_INDEX.md` for PRDs 37, 38, 41, 42 that don't yet have implementation folders

### 7.4 Code hygiene (carried from 4.0.95)
- Convert remaining `gmdate('YmdHis')` to `timestamp_ymdhis::now()` where doctrine requires (D-003)
- Add `$UNTRUSTED` boundary to remaining legacy PHP files with direct superglobal access (D-002)
- Runtime deprecation warnings for `AuthSessionManager` callers (scoped for removal in 4.1.0)

### 7.5 `GarbageCollector::lupo_actor_memory` reference cleanup **(can parallel)**
- `GarbageCollector.php` lines 321 and 401 still reference `lupo_actor_memory` (old table)
- After confirming whether `lupo_actor_memory` is still in install SQL or fully deprecated: remove stale references or document intentional co-existence

---

## Phase 8 — Packaging, regression, and 4.1.0 gate
**PRDs:** PRD 33 (`next_action`: "execute §7.4–§7.9 + §10 via TODO.md"), PRD 40 (VERSIONING_DOCTRINE)

**Objective:** Validate that all 4.0.96 work passes the Softaculous certification gate criteria defined in PRD 33 before cutting the 4.1.0 branch.

**Why last:** All code work must be stable before packaging runs.

### 8.1 Full regression suite
- `sh lupo-scripts/run_tests.sh .` (or equivalent runner)
- All `lupo-tests/unit/`, `lupo-tests/regression/`, and `lupo-tests/integration/` suites green

### 8.2 Softaculous packaging test (Linux)
- Fresh install from tarball on Linux MySQL 8.0+
- Verify install wizard completes without error
- Run `php lupo-bin/lupo.php doctor` → all checks green

### 8.3 32-bit PHP verification
- Confirm no PHP `(int)` casts on 18-digit ladder PKs in any code path
- Run `trust_ladder_canonical_id_test.php` and `trust_ladder_pk_validation_test.php` in PHP 32-bit emulation if available

### 8.4 PRD 33 gate checklist (§7.4–§7.9 + §10)
- LILITH final constitutional audit
- No open critical violations on audited surfaces
- All PRD amendment obligations from Phase 1 resolved

---

## Summary of phase dependencies

```
Phase 1 (Doctrine) ──► Phase 3.3 (KAIROS seed enforcement)
Phase 1 ─────────────► Phase 5 (uses PRD 41 tier-0 rules)
Phase 2 (CLI) ────────► Phase 3 (ingest uses CLI patterns)
Phase 3 (KAIROS) ─────► Phase 4 (GC needs stable node table)
Phases 4, 5, 6 ──────► Phase 8 (Packaging gate)
Phase 7 (hygiene) ────► Phase 8

Phases 4, 5, 6, 7 may proceed in parallel with each other.
```

---

## PRD reference index (this plan)

| PRD | Title | Phase(s) |
|-----|-------|---------|
| PRD 00 | Root Constitutional Requirements | 1.2, 7.3 |
| PRD 04 | Tags / Edges / Metadata | (complete) |
| PRD 07 | Agents / Faucets | 1.2 §11.2 |
| PRD 15 | Actors | 1.2 §11.3 |
| PRD 17 | Decisions Format | 7 |
| PRD 18 | Channel Chat Display | 6.2 |
| PRD 19 | Garbage Collection | 4 |
| PRD 24 | CLI Interface | 2 |
| PRD 26 | Five-Layer Doc Architecture | 7.2 |
| PRD 33 | Softaculous / 4.1.0 Gate | 8 |
| PRD 36 | ROSE Multi-Persona | 6 |
| PRD 37 | KAIROS Consolidation | 1.2 §11.5, 3 |
| PRD 38 | Memory Unification | 1.2, 1.3 |
| PRD 40 | Versioning Doctrine | 8 |
| PRD 41 | Install Seed Doctrine | 1.1, 3.3 |
| PRD 42 | Content Seeding / Truth Tables | 5 |
| CTL | Chronological Trust Ladder | (complete) |

This output complies with Lupopedia Constitutional Root Rules.
