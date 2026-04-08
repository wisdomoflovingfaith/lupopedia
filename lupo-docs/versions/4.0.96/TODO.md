---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  version_when_written: "4.0.96"
  file_path_from_root: "lupo-docs/versions/4.0.96/TODO.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.96/TODO.md"
  when_updated: "20260407172944"
  last_modified_utc: "20260407172944"
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
  purpose: "Active backlog for Lupopedia 4.0.96 — tasks carried from closed 4.0.95 line"
  tags: ["todo", "version", "4.0.96", "cursor"]
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.95/CHANGELOG.md"
      type: references
      weight: 1.0
      reason: "Finalized 4.0.95 changelog — migration note"
    - to: "lupo-docs/versions/4.0.95/TODO.md"
      type: references
      weight: 1.0
      reason: "Closed 4.0.95 registry (no open items)"
lupopedia.footer:
  last_verified: "20260407172944"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.96/TODO.md — delegation: cursor:root

# TODO - Lupopedia 4.0.96

**Version:** 4.0.96 (active planning line)  
**Last updated:** UTC `20260407172944`

**Source:** Open work migrated from **`lupo-docs/versions/4.0.95/`** when **4.0.95** was finalized.

---

## Carried Over from 4.0.95

### From `4.0.95/CHANGELOG.md` (schema / repository operations)

- [ ] **Option B migration:** `wolfie` → `/1`, `lilith` → `/2` folder move (was interrupted before execution).
- [ ] **Step 3 — Actor Reconstruction Pass** (blocked on Option B).
- [ ] **Install SQL merge:** apply corrected schema back into `install_new_lupopedia.sql` (review artifacts exist separately).

### From `4.0.95/TODO.md` — Priority 4 / deferred

- [ ] **P4-001:** Rewrite PRD 30 as writing guide (not metadata spec).
- [ ] **P4-003:** Remove `AuthSessionManager` entirely (4.1.0).
- [ ] **P4-004:** Remove `ToonSchemaCache` entirely (4.1.0).
- [ ] **P4-005:** Add PostgreSQL support to installer.

### From `4.0.95/TODO.md` — D-series

- [ ] **D-001:** Update `validate_implementation.py` to validate author block over `actor_id` (hardening beyond current support).
- [ ] **D-002:** Add `$UNTRUSTED` boundary to remaining legacy files (if any).
- [ ] **D-003:** Convert remaining `gmdate('YmdHis')` to `timestamp_ymdhis::now()` where appropriate.
- [ ] **D-004:** Add complete unit test coverage for Session metadata helpers.
- [ ] **D-005:** Add integration test for password change flow with metadata.

### From `4.0.95/TODO.md` — Phase 7 carryover (packaging / validation)

- [ ] Softaculous packaging test on Linux.
- [ ] Full regression: `sh lupo-scripts/run_tests.sh .`
- [ ] PHP 5.6 legacy install flag path.
- [ ] 32-bit PHP warning verification.

### From `4.0.95/README.md` — release criteria (draft items)

- [ ] P3 backlog from 4.0.94 completed or consciously postponed.
- [ ] P4 items scoped to 4.1.0 or implemented.
- [ ] Softaculous packaging test **passed** for the tarball that represents the released patch line.
- [ ] No open critical constitutional violations on audited surfaces.

### From `4.0.95/PLAN.md` — open phase items

**Phase 1**

- [ ] Close PRD 30 rewrite (writing guide).
- [ ] Migrate remaining root rules to `author` block (where applicable).

**Phase 2 — Session authority hardening**

- [ ] Runtime deprecation warnings for `AuthSessionManager` callers.
- [ ] Unit tests for Session metadata helpers (`getDecodedMetadata`, `mergeSessionMetadata`).
- [ ] Integration test: password change flow with metadata.

**Phase 3 — `$UNTRUSTED` sweep**

- [ ] Audit remaining legacy PHP for direct superglobal use; add `$UNTRUSTED` where required.

**Phase 4 — Database / time utilities**

- [ ] Replace remaining `gmdate('YmdHis')` with `timestamp_ymdhis::now()` where doctrine requires.
- [ ] PostgreSQL installer support (scoping TBD — may slip to 4.1.0).

**Phase 5 — Locale / UI**

- [ ] Externalize inline CSS from `main_layout.php`.
- [ ] Externalize inline JS from `main_layout.php`.

**Phase 7 — Packaging and testing** (overlaps Phase 7 list above; track once)

- [ ] Confirm Softaculous packaging test from 4.0.94 line is green; re-run if 4.0.96 changes ship.
- [ ] Full regression (`sh lupo-scripts/run_tests.sh .`).
- [ ] PHP 5.6 legacy path.
- [ ] 32-bit PHP warning verification.

---

## Notes

- Completing an item here should add a **thread-verified** entry to **`CHANGELOG.md`** per PRD 17 / version-doc scope rules.

This output complies with Lupopedia Constitutional Root Rules.
