---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260408190824"
  file_path_from_root: "docs/versions/4.0.96/TODO.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.96/TODO.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: todo
  artifact_kind: master_backlog
  thread_id: "version-4.0.96-todo"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: docs/versions/4.0.96/TODO.md — delegation: cursor:root

# TODO — Lupopedia 4.0.96 (Session + Ladder + seed band)

**Updated:** `20260408190824` UTC — Cursor IDE Agent (actor_id 102)

**Split (2026-04-08):** All **non-session, non-ladder** open work (PRD phases, CLI, KAIROS ingest, ROSE, packaging gate prep, install verification, federation question, and most hygiene items) was consolidated into **`docs/versions/4.0.97/TODO.md`**. This file retains **Session**, **Chronological Trust Ladder**, and **seed PK band** follow-ups only.

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
- [x] `scripts/generate_session_salt.php` — operator/installer salt helper — **20260408161022 UTC**
- [x] Trust Ladder: SELECT FOR UPDATE locking (Q3) — H-01
- [x] Trust Ladder: StagingGcService exclude lineage edges (Q5) — H-02
- [x] Session: Verify session_identity_hash populated on all paths — H-03
- [x] Session: Verify LUPO_SESSION_SALT in config — H-04

---

## High Priority (Session + Ladder — stays in 4.0.96)

| ID | Task | Status |
|----|------|--------|
| H-01 | *(reserved — was Trust Ladder locking; marked done above)* | ✅ Done |
| M-01 | Trust Ladder: Exponential backoff for suffix exhaustion | Pending |
| M-02 | Session: IPv6 test coverage | Pending |

---

## Session testing (stays in 4.0.96)

- [ ] **D-004:** Unit test coverage for Session metadata helpers (`getDecodedMetadata`, `mergeSessionMetadata`) — PRD 01
- [ ] **D-005:** Integration test for password change flow with session metadata — PRD 01

---

## Seed PK band + TRUST_LADDER_REGISTRY (stays in 4.0.96)

**PRDs:** PRD 42, PRD 41 §2.1, CTL §9.1

- [ ] Run `validate_trust_ladder_registry.py` and cross-check each `INSERT` in seed SQL files against install DDL table names
- [ ] Confirm `content_id` values in all seed SQL files are `< 1,000,000` (PRD 41 §2.1 system seed band)
- [ ] Where any seed `content_id` is out-of-band: re-key OR add explicit exception entry in `TRUST_LADDER_REGISTRY.md`
- [ ] Verify `validate_seed_registry.py` covers content-table PKs; extend if not
- [ ] Document audit findings in `docs/versions/4.0.96/status/STATUS_SEED_CONTENT_RECONCILIATION_{timestamp}.md` when run

---

## Ladder enforcement in KAIROS / install seed (stays in 4.0.96)

**Requires PRD 41 approval** (track approvals in **4.0.97** Phase 1; implementation lands here once doctrine is approved).

- [ ] Add install-seed immutability guard in `KairosConsolidationService::consolidate()`: throw when target PK is in seed space — PRD 41 §4, CTL §9.1
- [ ] Implement `ActorService::revertToInstall(int $actorId)` — PRD 41 §4
- [ ] Unit test: `revertToInstall()` — seed row unchanged; canonical row overwritten

---

## Packaging gate — ladder-only verification (stays in 4.0.96)

- [ ] 32-bit PHP verification — no `(int)` cast on 18-digit ladder PKs in any code path — CTL §2.2.1

---

## Moved to 4.0.97

The following categories were **moved** to **`docs/versions/4.0.97/TODO.md`** (UTC `20260408190824`):

- 4.0.94 **T-VERIFY-*** and **packaging** smoke; **Step 3 Actor Reconstruction Pass**
- PRD **approval** workflows, **PRD 38 §11** amendments, **CLI memory** suite, **KAIROS** channel ingest + contradiction (non-seed parts), **GC bootstrap** wiring for `GarbageCollector` / config triggers, **ROSE** Phase B, **validator / hygiene** items, **semantic widget** server-side session gate (product), **federation navigation compiler** open question file, **installer/PRD review** status artifacts, **Phase 8** general regression / Softaculous / PRD 33 / PRD 40 (except the ladder 32-bit PK bullet above)

---

## Notes

- Do not duplicate tasks that now live in **4.0.97**; update **4.0.97/TODO.md** when closing items there.
- Completing any item must produce a **`CHANGELOG.md`** entry with UTC timestamp per PRD 17.

This output complies with Lupopedia Constitutional Root Rules.
