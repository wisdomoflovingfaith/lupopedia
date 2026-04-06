---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  version_when_written: "4.0.95"
  file_path_from_root: "lupo-docs/versions/4.0.95/TODO.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.95/TODO.md"
  when_updated: "20260406050158"
  last_modified_utc: "20260406050158"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.95-todo"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "todo"
  artifact_kind: "master_backlog"
  purpose: "Deferred and new tasks for Lupopedia 4.0.95"
  tags: ["todo", "version", "4.0.95", "cursor"]
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.94/TODO.md"
      type: references
      weight: 1.0
      reason: "Closed 4.0.94 checklist"
    - to: "lupo-docs/versions/4.0.95/PLAN.md"
      type: references
      weight: 1.0
      reason: "Plan for this line"
lupopedia.footer:
  last_verified: "20260406050158"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.95/TODO.md — delegation: cursor:root

# TODO - Lupopedia 4.0.95

**Version:** 4.0.95 (planning)  
**Last updated:** UTC `20260406050158`

**Source:** Deferred from **`lupo-docs/versions/4.0.94/TODO.md`**. Items **P3-005, P3-006, P3-007, P4-002, P4-006** completed in **4.0.94** (packaging gate).

---

## Priority 3

_No open P3 items from the former 4.0.94 deferral list._

## Priority 4 (future / post-4.0.94)

- [ ] P4-001: Rewrite PRD 30 as writing guide (not metadata spec)
- [ ] P4-003: Remove AuthSessionManager entirely (4.1.0)
- [ ] P4-004: Remove ToonSchemaCache entirely (4.1.0)
- [ ] P4-005: Add PostgreSQL support to installer

## Additional deferred (D-series)

- [ ] D-001: Update `validate_implementation.py` to validate author block over actor_id (hardening beyond current support)
- [ ] D-002: Add `$UNTRUSTED` boundary to remaining legacy files (if any)
- [ ] D-003: Convert remaining `gmdate('YmdHis')` to `timestamp_ymdhis::now()` where appropriate
- [ ] D-004: Add complete unit test coverage for Session metadata helpers
- [ ] D-005: Add integration test for password change flow with metadata

---

## Phase 7 carryover (from 4.0.94 PLAN)

Execute while **4.0.94** is still the shipping candidate unless version bump moves first:

- [ ] Softaculous packaging test on Linux
- [ ] Full regression (`sh lupo-scripts/run_tests.sh .`)
- [ ] PHP 5.6 legacy install flag path
- [ ] 32-bit PHP warning verification

---

## Notes

- Completing an item here should add a **thread-verified** entry to **`CHANGELOG.md`** per PRD 17 / version-doc scope rules.

This output complies with Lupopedia Constitutional Root Rules.
