---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  version_when_written: "4.0.94"
  file_path_from_root: "lupo-docs/versions/4.0.94/TODO.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/TODO.md"
  when_updated: "20260407015813"
  last_modified_utc: "20260407015813"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-todo"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "todo"
  artifact_kind: "master_backlog"
  purpose: "Closed task list for Lupopedia 4.0.94; deferred work lives in 4.0.95"
  tags: ["todo", "version", "4.0.94", "cursor"]
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.94/PLAN.md"
      type: references
      weight: 1.0
      reason: "Plan for this version"
    - to: "lupo-docs/versions/4.0.94/CHANGELOG.md"
      type: references
      weight: 1.0
      reason: "Version changelog"
    - to: "lupo-docs/versions/4.0.94/VERSION_SUMMARY.md"
      type: references
      weight: 1.0
      reason: "Packaging handoff summary"
    - to: "lupo-docs/versions/4.0.95/TODO.md"
      type: references
      weight: 1.0
      reason: "Deferred and next-line backlog"
    - to: "lupo-docs/versions/4.0.93/TODO.md"
      type: references
      weight: 0.9
      reason: "Frozen 4.0.93 completed record"
    - to: "lupo-docs/versions/4.0.94/decisions/20260406_042624_DECISION_session_authority_migration.md"
      type: references
      weight: 1.0
      reason: "Session authority Model A decision"
    - to: "lupo-docs/versions/4.0.94/decisions/20260406_173021_DECISION_version_4_0_94_five_w_one_h_doc_sync_closeout.md"
      type: references
      weight: 1.0
      reason: "5W1H version-doc close-out receipt (2026-04-06)"
    - to: "lupo-docs/versions/4.0.94/decisions/20260407_015813_DECISION_cursor_install_schema_merge_receipt.md"
      type: references
      weight: 1.0
      reason: "Install merge receipt — schema_review → install_new_lupopedia.sql (2026-04-07)"
lupopedia.footer:
  last_verified: "20260407015813"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/TODO.md — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/TODO.md

# TODO - Lupopedia 4.0.94

**Version:** 4.0.94  
**Last updated:** UTC `20260407015813` (`python lupo-bin/tick.py`)

---

## What is left before 4.0.94 release (inline summary)

| Bucket | Status |
|--------|--------|
| **Scoped implementation (P1–P2, P3-001–P3-007, P4-002, P4-006)** | **Done** — see checklists below |
| **Deferred product/architecture (4.0.95+)** | Not blocking 4.0.94 — P4-001, P4-003–P4-005, D-001–D-005 |
| **Pre-release verification (you + CI)** | **Open** — fresh manual install from tree (**§ below**), then full test suite where `sh` is available |
| **Packaging (Softaculous tarball, Linux smoke, install-from-zip)** | **After** pre-release verification is green — see **`PLAN.md`** Phase 7 |

**Order WOLFIE described:** finish any remaining **non-packaging** gates → run **manual clean install** from **`install.php`** using **this repo** → then **packaging / external install** tests.

---

## Pre-release verification (before packaging) — OPEN

Do these **from the current project files** (not from an old tarball):

- [ ] **T-VERIFY-001 — Clean DB:** Drop all Lupopedia tables (or empty and recreate DB per your safe procedure; follow **`SAFE_MIGRATION_DOCTRINE`** / wizard docs — no raw production `mysql` on shared rules unless your environment allows it).
- [ ] **T-VERIFY-002 — No stale config:** Remove **`lupopedia-config.php`** (and any duplicate above docroot if your tree uses one) so the wizard is forced to recreate config.
- [ ] **T-VERIFY-003 — Manual install:** Open **`install.php`**, run the full install + seed path against the canonical SQL under **`lupo-database/lupopedia/mysql/`** (install + seed as documented for fresh Crafty→Lupopedia or empty DB story you use).
- [ ] **T-VERIFY-004 — Smoke:** Login, **`admin.php`**, one public content/channel path, collections chrome if that layout is exercised.
- [ ] **T-VERIFY-005 — Automated tests:** Run **`sh lupo-scripts/run_tests.sh .`** from repo root on a host that has `sh` (Git Bash/WSL/Linux). On Windows without `sh`, run unit suite via PHP loop or document equivalent until full suite is green.

**Completion criteria:** T-VERIFY-001–004 pass on your reference stack; T-VERIFY-005 pass or recorded substitute with same coverage intent.

---

## Packaging (after pre-release verification) — OPEN

- [ ] Build tarball per **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md`** / **`lupo-scripts/build_softaculous_package.sh`** (where applicable).
- [ ] Linux shared-host-class deploy + HTTP smoke from **package** (not only from git checkout).
- [ ] Optional Phase 7 follow-ups: PHP 5.6 legacy install flag path; 32-bit PHP warning check — see **`PLAN.md`** Phase 7.

---

## Priority 1 (critical) — COMPLETE

- [x] P1-001 through P1-010 — see **`VERSION_SUMMARY.md`** / **`CHANGELOG.md`** rollup

## Priority 2 (high) — COMPLETE

- [x] P2-001 through P2-009

## Priority 3 (nice to have) — COMPLETE for items shipped in 4.0.94

- [x] P3-001: Add `createEmbedSession()` to Session class
- [x] P3-002: Add metadata helpers to Session class
- [x] P3-003: Update validate_implementation.py with conditional fields
- [x] P3-004: Update validate_lupopedia_headers_universal.py with author support
- [x] P3-005: Add runtime deprecation warning to `AuthSessionManager` (when `LUPOPEDIA_DEBUG`)
- [x] P3-006: Move inline CSS from `main_layout.php` to `lupo-includes/css/main-layout.css` (border tiles remain inline for `LUPOPEDIA_PUBLIC_PATH`)
- [x] P3-007: Move inline JS from `main_layout.php` to `main-layout.js` and `main-layout-collections.js` (PHP config blob for API paths)

## Priority 4 — packaging gate (4.0.94)

- [x] P4-002: Update `lupo-rules/root/` headers to structured `author` block (flat `actor_id` / `actor_name` removed where present in front matter)
- [x] P4-006: Add runtime deprecation warning to `ToonSchemaCache` (when `LUPOPEDIA_DEBUG`)

**Still deferred (4.0.95+):** P4-001, P4-003, P4-004, P4-005 — see **`lupo-docs/versions/4.0.95/TODO.md`**.

---

## DEFERRED TO 4.0.95

**Authoritative checklist:** [`lupo-docs/versions/4.0.95/TODO.md`](../4.0.95/TODO.md)

Includes (non-exhaustive): P4-001, P4-003–P4-005; D-001–D-005; Phase 7 packaging/regression carryover; optional validator/runtime hardening beyond this gate.

---

## Completed (this release line)

| Area | Notes |
|------|--------|
| P1 / P2 / P3 (001–004) | Constitutional, session, `$UNTRUSTED`, locale, validators |
| P3-005–P3-007, P4-002, P4-006 | Deprecation logs, `main_layout` external CSS/JS, root rules `author` block |
| Version docs | CHANGELOG, PLAN, edges, session decision, **VERSION_SUMMARY**, **4.0.95** scaffold |
| Version docs (2026-04-06 17:30) | **5W1H** close-out — decision **`173021`**, Q/A **`173022`**, **`CHANGELOG`** hourly prepend, **`edges`** handoff to **4.0.95** / **`FOR_CLAUDE_CODE`** / root **`CHANGELOG`** |
| Channel 66 | Extended integration test + ingester discovery fix |
| Schema review (2026-04-06 20:00) | Structural analysis, `schema_corrected_core.sql`, `schema_corrected_missing.sql`, corrected identity model — `lupo-database/lupopedia/mysql/schema_review/` |
| Install merge (2026-04-07 ~02:00) | **`install_new_lupopedia.sql`** merged from corrected SQL — **170** tables; receipt **`decisions/20260407_015813_…`** |
| CHRONOS activation (2026-04-06 20:00) | Kernel agent `lupo-agents/chronos/` fully activated — agent.json, identity.json, tools.json (15 tools), capabilities.json (18), system_prompt.txt |
| Migration docs (2026-04-06 20:00) | Import SQL corrected (4 edits); `livehelp_users_migration.md` + `livehelp_operator_departments_migration.md` updated; `new_schema_tables_crafty_mapping.md` created |

---

## Open items from 2026-04-06 20:00 UTC epoch (carry to next session)

- [x] **Option B migration:** Completed — filesystem hubs `lupo-actors/1/` and `lupo-actors/2/`; `lupo-database/lupopedia/actors/registry.json` `dir` updated; repo references updated; `lupo-actors/wolfie/` and `lupo-actors/lilith/` removed.
- [ ] **Step 3: Actor Reconstruction Pass** — deferred.

## Open items after install merge (2026-04-07 ~02:00 UTC)

- [x] **Apply corrected schema to `install_new_lupopedia.sql`** — done; see **`decisions/20260407_015813_DECISION_cursor_install_schema_merge_receipt.md`** and **`CHANGELOG.md`** epoch **`[2026-04-07 02:00 UTC]`**.
- [x] **T-SCHEMA-RUNTIME-001:** Completed UTC `20260407123924` — `install_wizard_classes.php` and `seed_4.1.0.sql` aligned; removed columns (`metadata`, `adversarial_role`, `adversarial_oversight_actor_id`) purged; `lupo_agents` → `lupo_agent_definitions`; adversarial relationship migrated to `lupo_actor_relationships`.
- [x] **T-SCHEMA-TOOLCALLS-001:** Completed UTC `20260407123924` — `lupo_agent_tool_calls` CREATE TABLE restored to `install_new_lupopedia.sql` with `actor_id bigint NOT NULL` per SECTION 9. Install table count now **171**.

## Blocked tasks

None currently.

---

## Notes

- **4.0.95** is the **active working line**; runtime **`GLOBAL_CURRENT_LUPOPEDIA_VERSION`** was bumped to **4.0.95** (UTC **`20260406062838`**). This **4.0.94** TODO is a closed-period record.
- PRD 30 / PRD 31 rejection context unchanged — see **`CHANGELOG.md`** rollup.
