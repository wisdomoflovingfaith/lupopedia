---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260408161022"
  file_path_from_root: "lupo-docs/versions/4.0.96/TODO.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.96/TODO.md"
  last_modified_utc: "20260408161022"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.96-todo"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "todo"
  artifact_kind: "master_backlog"
  purpose: "Active task backlog for Lupopedia 4.0.96 — keyed to PLAN.md phases and PRD next_action fields"
  tags: ["todo", "version", "4.0.96", "cursor"]
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.96/PLAN.md"
      type: references
      weight: 1.0
      reason: "Phase context and PRD citations for each task group"
    - to: "lupo-docs/versions/4.0.96/CHANGELOG.md"
      type: references
      weight: 1.0
      reason: "Land completed tasks here with UTC thread discipline per PRD 17"
lupopedia.footer:
  last_verified: "20260408161022"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.96/TODO.md — delegation: cursor:root

# TODO — Lupopedia 4.0.96

**Updated:** `20260408161022` UTC — Cursor IDE Agent (actor_id 102)

**How to use:** Check items off as they land. Each completed item must produce a `CHANGELOG.md` entry with UTC timestamp per PRD 17. Items marked **[BLOCKING]** must complete before later items in the same phase can begin. Phase order follows `PLAN.md`.

---

## Already completed in 4.0.96 (reference only — do not re-do)

- [x] Chronological Trust Ladder doctrine (§§1–13 + Appendix A) — PRD 00 §3.7, CTL
- [x] `IdGenerator` string-safe transforms — CTL §2.2
- [x] `IdGenerator::isReservedSpace()` bug fix — CTL §2.2.2
- [x] `KairosConsolidationService` migrated to `lupo_memory_nodes` — PRD 37, PRD 38
- [x] `MemoryExportService` — PRD 38 §6
- [x] `TRUST_LADDER_REGISTRY.md` + `RETENTION_POLICY.md` — CTL §9
- [x] `validate_trust_ladder_registry.py` — CTL §10
- [x] `audit_edge_integrity.py` — CTL §9.2
- [x] §13 test suite (49 unit/integration tests) — CTL §13
- [x] `StagingGcService` + `staging-gc` CLI + `lupo.php` routing — RETENTION_POLICY, PRD 19
- [x] `AdminTrustLadderHandler` — admin web UI for trust ladder — FOR_CLAUDE_CODE §Future
- [x] Memory unification schema in install SQL — PRD 38
- [x] 4D edge columns on `lupo_edges` — PRD 04
- [x] `memory.json` deprecated across PRDs — PRD 01, 07, 15, 24
- [x] Actor registry + Claude Code actor_id 116 created — PRD 15
- [x] Install seed doctrine draft text authored — PRD 41 (draft, not yet approved)
- [x] PRD 17 validators: `validate_pseudocode_discipline.py`, `validate_thread_structure.py`, `validate_edge_linking.py` wired into `run_tests.sh` — Cursor 20260408120642
- [x] `lupo_trust_ladder_registry` table DDL + 13 bootstrap seed rows — `install_new_lupopedia.sql` — CTL §9.1, note_on_seed_range.md
- [x] `TrustLadderRegistry.php` — PHP registry class with fail-closed behavior, dev mode, bootstrap safety, `validatePkForTable()`, `assertInvariants()` — CTL §9.1
- [x] `sync_trust_ladder_registry_to_db.py` — sync script with `--dry-run`, `--force`, `--strict`, invariant validation, exit codes 0/1/2/3 — CTL §10
- [x] `trust_ladder_registry_test.php` — 47 unit tests (7 sections A–G), all passing — CTL §13
- [x] `IdGenerator::toCanonicalIdSafe()` registry check (optional, class_exists guard) — CTL §2.2
- [x] `LEGACY_GAP_ANSWERS.md` — 11 legacy-gap questions answered with implementation decisions — note_on_seed_range.md
- [x] Session Model A — salted Class C / IPv6-64 fingerprints, `LUPO_SESSION_SALT`, `session_identity_hash`, UA normalize, resolved client IP, `ensureTimestampClass`, `validateSession` + `isExpired` + optional `LUPO_SESSION_VALIDATE_UA` — `app/auth/Session.php`, `lupopedia-config.php`, install SQL — PRD 01, SESSIONS_RESEARCH — **20260408161022 UTC**
- [x] Probabilistic `lupo_sessions` GC (`maybeProbabilisticGarbageCollect`), lock via `sys_get_temp_dir`, `SessionManager::tick()` GC-only + bootstrap ordering — `Session.php`, `SessionManager.php`, `bootstrap.php` — **20260408161022 UTC**
- [x] `lupo-scripts/generate_session_salt.php` — operator/installer salt helper — **20260408161022 UTC**

---

## Phase 1 — Doctrine finalization and approval gates
**PRDs:** PRD 41, PRD 38, PRD 00, PRD 37

- [ ] **[BLOCKING]** LILITH constitutional audit of PRD 41 full text — *PRD 41 (status: draft → approved)*
- [ ] **[BLOCKING]** WOLFIE product approval of PRD 41 — update `status` field in header to `approved`
- [ ] Apply PRD 38 §11.1 — update PRD 00 §5.7 to make memory graph + export mirror a constitutional requirement — *PRD 38 §11.1, PRD 00*
- [ ] Apply PRD 38 §11.2 — remove remaining `memory.json` references from PRD 07; document unified graph path — *PRD 38 §11.2, PRD 07*
- [ ] Apply PRD 38 §11.3 — align PRD 15 workspace file tree and actor learning-process steps to `lupo_memory_nodes` — *PRD 38 §11.3, PRD 15*
- [ ] Apply PRD 38 §11.4 — align PRD 24 CLI memory spec section to unified graph (not `lupo_actor_memory`) — *PRD 38 §11.4, PRD 24*
- [ ] Apply PRD 38 §11.5 — align PRD 37 KAIROS observation/canonical storage description to PRD 38 §4.2 — *PRD 38 §11.5, PRD 37*
- [ ] Resolve `lupo_edges` vs `lupo_memory_edges` dual-table: write explicit split documentation in PRD 38 prose — *PRD 38 `next_action`*
- [ ] Update `TRUST_LADDER_REGISTRY.md` with final participation class for `lupo_memory_edges` — *CTL §9.1, PRD 38*
- [ ] Regenerate PRD 38 shorthand (`python lupo-scripts/generate_prd_shorthands.py --prd 38 --force`) after prose amendments

---

## Phase 2 — CLI memory command suite
**PRDs:** PRD 24 `next_action`

- [ ] **[BLOCKING]** Create `lupo-includes/classes/MemoryCommands.php` with static `dispatch($argv)` method — *PRD 24 §5*
- [ ] Implement `memory add` sub-command — staging INSERT; `--canonical` flag for immediate promotion via `toCanonicalIdSafe()` — *PRD 24 §5.1, PRD 38 §4.2*
- [ ] Implement `memory list` sub-command — `--actor N`, `--type`, canonical-first query priority — *PRD 24 §5.2, CTL §4*
- [ ] Implement `memory get` sub-command — `--memory-id`; JSON output — *PRD 24 §5.3*
- [ ] Implement `memory update` sub-command — UPDATE `memory_value` on canonical node; reject staging without `--force` — *PRD 24 §5.4*
- [ ] Implement `memory delete` sub-command — soft-delete by `--memory-id`; reject seed-tier rows — *PRD 24 §5.5*
- [ ] Implement `memory export` sub-command — invoke `MemoryExportService::exportFull()`; `--output-dir` — *PRD 24 §5.6, PRD 38 §6*
- [ ] Implement `memory archive` sub-command — PRD 38 §8 Option B: soft-delete original, insert new canonical, add `archived_to` edge — *PRD 24 §5.8, PRD 38 §8*
- [ ] Implement `memory restore` sub-command — reverse archive; `restored_from` edge — *PRD 24 §5.9*
- [ ] Implement `memory edges` sub-command — list `lupo_memory_edges` rows by `--from-id` or `--to-id`; `--edge-type` filter — *PRD 24 §5.7*
- [ ] Add `case 'memory':` dispatch to `lupo-bin/lupo.php` — *PRD 24 `next_action`*
- [ ] Update `lupo-bin/lupo.php` help text with `memory` command and sub-command list
- [ ] Create `lupo-tests/integration/memory_cli_test.php` — add/list/get/delete lifecycle; canonical promotion; export generates mirror file; archive+restore round-trip — *PRD 24, CTL §13*

---

## Phase 3 — KAIROS channel ingest and contradiction resolution
**PRDs:** PRD 37, PRD 41 (requires Phase 1 approval first for 3.3)

- [ ] **[BLOCKING — requires Phase 1.1 complete]** Add install-seed immutability guard in `KairosConsolidationService::consolidate()`: throw `InvalidArgumentException` when target PK is in seed space — *PRD 41 §4, CTL §9.1*
- [ ] Implement `KairosConsolidationService::ingestFromChannel(int $channelId, int $actorId, ?int $limit)` — query `lupo_dialog_messages`; create staging observation rows for messages not already in `lupo_memory_nodes` (content_hash dedup) — *PRD 37 §3 `next_action`*
- [ ] Extend `KairosConsolidationService::consolidate()` — recency-first contradiction resolution: when two canonical nodes share `memory_key` with conflicting `memory_value`, promote the later `created_ymdhis` as winner — *PRD 37 §5.2 `next_action`*
- [ ] Extend contradiction resolution — edge-weight override: when `weight_hundredths` difference `>= 20`, higher-weight wins regardless of recency — *PRD 37 §5.3 `next_action`*
- [ ] Mark loser in contradiction with `kairos_contradicts` edge; `edge_status = 'needs_review'`, `review_reason = 'contradiction'` — *PRD 37 §5, PRD 04 (review_reason routing)*
- [ ] **[requires Phase 1.1 complete]** Implement `ActorService::revertToInstall(int $actorId)` — copy all fields from seed row to living canonical; add `reverted_to` edge; log to `lupo_unified_log` — *PRD 41 §4*
- [ ] Unit test: contradiction resolution — recency winner, weight-override winner — *PRD 37, CTL §13*
- [ ] Unit test: `revertToInstall()` — seed row unchanged; canonical row overwritten — *PRD 41 §4*
- [ ] Integration test: full ingest → consolidate → contradiction cycle with live DB — *PRD 37*

---

## Phase 4 — GC integration and random execution trigger
**PRDs:** PRD 19 `next_action`

- [ ] Add probabilistic `GarbageCollector::run()` trigger in bootstrap (`rand(1, 200) === 1`); wrapped in try/catch — *PRD 19 §6*
- [ ] Add probabilistic `StagingGcService::purge()` trigger in bootstrap (`rand(1, 500) === 1`); wrapped in try/catch — *PRD 19 §6, RETENTION_POLICY*
- [ ] Add `gc_staging_retention_days` key to `lupo_system_config` / config array; `StagingGcService` reads it with fallback to `90` — *PRD 19 `next_action`: "Add retention configuration to system_config"*
- [ ] Update PRD 19 body text — document random-probability trigger approach in §6; mark `next_action` items as resolved — *PRD 19*
- [ ] Update PRD 19 `next_action` footer — "Test path aggregation with real visit data" (verify `GarbageCollector` aggregates `lupo_paths` + `lupo_referers_daily` correctly on non-empty DB) — *PRD 19*
- [ ] Confirm `GarbageCollector.php` `lupo_actor_memory` references (lines ~321, ~401) are still intentional or remove if table is deprecated — *PRD 38 (memory unification), PRD 19*

---

## Phase 5 — Content seeding and truth table reconciliation
**PRDs:** PRD 42 `next_action`, PRD 41 §2.1

- [ ] Run `validate_trust_ladder_registry.py` and cross-check each `INSERT` in seed SQL files against install DDL table names — *PRD 42 `next_action`: "Reconcile seed_online_help_and_content.sql with install schema"*
- [ ] Confirm `content_id` values in all seed SQL files are `< 1,000,000` (PRD 41 §2.1 system seed band) — *PRD 42 `next_action`: "Align seed content_id bands with PRD 41 tier-0 policy"*
- [ ] Where any seed `content_id` is out-of-band: re-key to correct band OR add an explicit exception entry in `TRUST_LADDER_REGISTRY.md` — *PRD 41 §2.1, PRD 42*
- [ ] Verify `validate_seed_registry.py` covers content-table PKs; extend if not — *PRD 42, PRD 41*
- [ ] Document audit findings in `lupo-docs/versions/4.0.96/status/STATUS_SEED_CONTENT_RECONCILIATION_{timestamp}.md` — *PRD 17 (thread discipline)*

---

## Phase 6 — ROSE multi-persona synthetic dialog (Phase B)
**PRDs:** PRD 36 `next_action`, PRD 18 `next_action`

- [ ] **[BLOCKING]** Implement `RoseDialogService.php` Phase B — per-thread organic message counter in `lupo_thread_state` (or equivalent) — *PRD 36 `next_action`*
- [ ] Implement ROSE trigger: fire synthetic dialog insertion every N messages (configurable via `rose_trigger_interval` system_config key, default 10) — *PRD 36*
- [ ] Enforce `rose_visibility` policy: synthetic ROSE messages visible only to actors in the same department — *PRD 36*
- [ ] Hard enforce 2,000-character limit on ROSE synthetic message `message_body` before INSERT — *PRD 36*
- [ ] PRD 18 — render mandatory `[Synthetic]` badge in channel chat display when `metadata_json.rose_synthesis = true` — *PRD 18 `next_action`*
- [ ] Wire badge rendering to `channels-api` JSON response field and `chat-display.js` — *PRD 18*
- [ ] Integration test: ROSE trigger fires exactly once per N messages; visibility enforcement; 2,000-char truncation/rejection — *PRD 36, PRD 18*

---

## Phase 7 — Code hygiene, header alignment, tooling
**PRDs:** PRD 00 §5.8, PRD 16, PRD 26, PRD 17

- [ ] PRD 16 — update `lib/header_validation.validate_header` to accept `author` block alone (no legacy `actor_id`/`actor_name` required) — *PRD 16 `next_action`: "remaining debt"*
- [ ] PRD 26 — verify `validate_implementation.py` is in sync with current `doc_arch_version` — *PRD 26 `next_action`*
- [ ] PRD 26 — add `doc_arch_version` to `lupo-docs/implementations/` folders lacking it — *PRD 26 `next_action`*
- [ ] PRD 00 §5.8 — create stub `lupo-docs/implementations/` folders with `THREAD_INDEX.md` for PRDs 37, 38, 41, 42 — *PRD 00 `next_action`*
- [ ] D-002: Audit remaining legacy PHP for direct superglobal use; add `$UNTRUSTED` boundary where required — *PRD 00 (security boundary doctrine)*
- [ ] D-003: Convert remaining `gmdate('YmdHis')` calls to `timestamp_ymdhis::now()` in files where doctrine requires packed UTC — *PRD 00 §3.5, PRD 15*
- [ ] D-004: Add unit test coverage for Session metadata helpers (`getDecodedMetadata`, `mergeSessionMetadata`) — *PRD 01 (session doctrine)*
- [ ] D-005: Add integration test for password change flow with session metadata — *PRD 01*
- [ ] Add `AuthSessionManager` runtime deprecation warnings on every call path (target: removal in 4.1.0) — *PRD 01, carried from 4.0.95*
- [ ] PRD 30 — rewrite as channel usage writing guide (not metadata spec); close P4-001 — *PRD 30 `next_action`*

---

## Phase 8 — Packaging, regression, and 4.1.0 gate
**PRDs:** PRD 33, PRD 40

- [ ] **[BLOCKING — all prior phases complete]** Full regression suite: `sh lupo-scripts/run_tests.sh .` — all unit, regression, and integration tests green — *PRD 33 §7.4*
- [ ] Softaculous packaging test on Linux — fresh install from tarball, MySQL 8.0+; `php lupo-bin/lupo.php doctor` green — *PRD 33 §7.5*
- [ ] 32-bit PHP verification — no `(int)` cast on 18-digit ladder PKs in any code path — *CTL §2.2.1, carried from 4.0.95*
- [ ] PHP 5.6 legacy install flag path — verify installer completes — *carried from 4.0.95*
- [ ] LILITH final constitutional audit (PRD 33 §13) — *PRD 33 §7.9*
- [ ] Confirm no open critical constitutional violations on audited surfaces — *PRD 33 §10*
- [ ] Confirm all PRD amendment obligations from Phase 1 (§11.1–11.5) are resolved and shorthand regenerated — *PRD 38, PRD 33*
- [ ] Cut 4.1.0 release candidate per PRD 40 versioning doctrine — *PRD 40*

---

## Deferred to 4.1.0 (out of scope for 4.0.96)

- [ ] P4-003: Remove `AuthSessionManager` entirely — *PRD 01, planned 4.1.0*
- [ ] P4-004: Remove `ToonSchemaCache` entirely — *planned 4.1.0*
- [ ] P4-005: PostgreSQL support in installer — *PRD 27, scoping TBD*
- [ ] D-001: Update `validate_implementation.py` to validate `author` block over `actor_id` — *PRD 16, PRD 26*
- [ ] PRD 34 — runtime federation sync — deferred per PRD 34 `next_action`: "Defer runtime federation sync until post-4.0.x stabilization"
- [ ] PRD 35 — native mobile app — deferred per PRD 35 `next_action`: "Product: approve PRD scope and defer native app to post-4.0.x"
- [ ] PRD 20 — `federation_nodes/` directory restructure — deferred; directory exists but reorganization is out of scope for 4.0.96

---

## Notes

- Completing any item must produce a `CHANGELOG.md` entry with UTC timestamp per PRD 17 / version-doc scope rules.
- The `[BLOCKING]` marker within a phase means subsequent phase items cannot safely land until it is complete.
- Phase 1 approval tasks (PRD 41) require human actors (LILITH, WOLFIE) — Claude Code and Cursor cannot self-approve constitutional doctrine.
- See `PLAN.md` for phase rationale and dependency graph.

This output complies with Lupopedia Constitutional Root Rules.
