---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: plan
  when_updated: "20260407172944"
  file_path_from_root: "docs/versions/4.0.95/PLAN.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.95/PLAN.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: plan
  artifact_kind: version
  thread_id: "version-4.0.95-plan"
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
# file: docs/versions/4.0.95/PLAN.md — delegation: cursor:root

# Plan - Lupopedia 4.0.95 (FINALIZED)

**Version:** 4.0.95 — **closed** (UTC `20260407172944`)  
**Successor plan / backlog:** **`docs/versions/4.0.96/TODO.md`**

**Inherited from 4.0.94:** See **`docs/versions/4.0.94/VERSION_SUMMARY.md`**.

Open phase items that were not completed in this line were migrated to **`../4.0.96/TODO.md`** (section **From 4.0.95/PLAN.md**). Do not add new execution items here.

---

## Phase 1: Constitutional alignment

- [x] Doctrine / PRD alignment batch (2026-04-06) — departments, actors, learning boundaries, installation narrative; **PRD 00, 01, 05, 15, 28, 33**; root **README**; see **`CHANGELOG.md`**

---

## Phase 2–7 (open work migrated)

Remaining Phase 2–7 tasks (PRD 30, author blocks, session hardening, `$UNTRUSTED`, time utilities, UI externalization, packaging/tests) are listed under **`docs/versions/4.0.96/TODO.md`**.

---

## Phase 6: Documentation (landed in 4.0.95)

- [x] Update **`CHANGELOG.md`** as work lands (2026-04-06 batch recorded; finalization entry 2026-04-07)
- [x] Keep **`edges.md`** aligned (update successor pointer in **4.0.96** as that line grows)

---

## Phase completion status (at closeout)

| Phase | Status at finalize |
|-------|---------------------|
| Phase 1 — Constitutional alignment | Complete (doctrine batch landed 2026-04-06) |
| Phase 2 — Session hardening | Migrated to **4.0.96** |
| Phase 3 — `$UNTRUSTED` sweep | Migrated to **4.0.96** |
| Phase 4 — DB / time | Migrated to **4.0.96** |
| Phase 5 — Locale / UI | Migrated to **4.0.96** |
| Phase 6 — Documentation | Complete for 4.0.95 closeout |
| Phase 7 — Packaging and testing | Migrated to **4.0.96** |

---

## Next actions (for maintainers)

1. Continue execution on **`docs/versions/4.0.96/TODO.md`**.  
2. Record decisions under **`docs/versions/4.0.96/`** (or channel threads) with PRD 17 filenames.  
3. Bump **`GLOBAL_CURRENT_LUPOPEDIA_VERSION`** only when releasing a new **product** patch line (not per this documentation finalize alone).

This output complies with Lupopedia Constitutional Root Rules.
