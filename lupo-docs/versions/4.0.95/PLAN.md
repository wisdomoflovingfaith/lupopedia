---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: plan
  when_updated: "20260406171149"
  file_path_from_root: "lupo-docs/versions/4.0.95/PLAN.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.95/PLAN.md"
  last_modified_utc: "20260406171149"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.95-plan"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "plan"
  artifact_kind: "version"
  purpose: "Project plan for Lupopedia 4.0.95 (reset from 4.0.94 template)"
  tags: ["plan", "version", "4.0.95", "cursor"]
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.94/PLAN.md"
      type: references
      weight: 1.0
      reason: "Completed prior line plan"
    - to: "lupo-docs/versions/4.0.95/TODO.md"
      type: references
      weight: 1.0
      reason: "Backlog for this line"
lupopedia.footer:
  last_verified: "20260406171149"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.95/PLAN.md — delegation: cursor:root

# Plan - Lupopedia 4.0.95

**Version:** 4.0.95  
**Status:** Planning — reset checklist; execute in dependency order (no calendar estimates)

**Inherited from 4.0.94:** See **`lupo-docs/versions/4.0.94/VERSION_SUMMARY.md`**.

---

## Phase 1: Constitutional alignment

- [x] Doctrine / PRD alignment batch (2026-04-06) — departments, actors, learning boundaries, installation narrative; **PRD 00, 01, 05, 15, 28, 33**; root **README**; see **`CHANGELOG.md`**
- [ ] Close PRD 30 rewrite (writing guide)
- [ ] Migrate remaining root rules to `author` block (where applicable)

---

## Phase 2: Session authority hardening

- [ ] Runtime deprecation warnings for `AuthSessionManager` callers
- [ ] Unit tests for Session metadata helpers (`getDecodedMetadata`, `mergeSessionMetadata`)
- [ ] Integration test: password change flow with metadata

---

## Phase 3: `$UNTRUSTED` sweep

- [ ] Audit remaining legacy PHP for direct superglobal use; add `$UNTRUSTED` where required

---

## Phase 4: Database / time utilities

- [ ] Replace remaining `gmdate('YmdHis')` with `timestamp_ymdhis::now()` where doctrine requires
- [ ] PostgreSQL installer support (scoping TBD — may slip to 4.1.0)

---

## Phase 5: Locale / UI

- [ ] Externalize inline CSS from `main_layout.php`
- [ ] Externalize inline JS from `main_layout.php`

---

## Phase 6: Documentation

- [x] Update **`CHANGELOG.md`** as work lands (2026-04-06 batch recorded)
- [x] Keep **`edges.md`** aligned with new threads (2026-04-06)

---

## Phase 7: Packaging and testing

- [ ] Confirm Softaculous packaging test from 4.0.94 line is green; re-run if 4.0.95 changes ship
- [ ] Full regression (`sh lupo-scripts/run_tests.sh .`)
- [ ] PHP 5.6 legacy path
- [ ] 32-bit PHP warning verification

---

## Phase completion status

| Phase | Status |
|-------|--------|
| Phase 1 — Constitutional alignment | IN PROGRESS (doctrine batch landed 2026-04-06) |
| Phase 2 — Session hardening | PENDING |
| Phase 3 — `$UNTRUSTED` sweep | PENDING |
| Phase 4 — DB / time | PENDING |
| Phase 5 — Locale / UI | PENDING |
| Phase 6 — Documentation | IN PROGRESS (changelog/edges updated 2026-04-06) |
| Phase 7 — Packaging and testing | PENDING |

---

## Next actions

1. Burn down **`TODO.md`** in priority order  
2. Record decisions under **`decisions/`** with PRD 17 filenames  
3. Bump **`GLOBAL_CURRENT_LUPOPEDIA_VERSION`** only when releasing **4.0.95** as product version

This output complies with Lupopedia Constitutional Root Rules.
