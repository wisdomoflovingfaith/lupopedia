---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: plan
  when_updated: "20260406051111"
  file_path_from_root: "lupo-docs/versions/4.0.94/PLAN.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/PLAN.md"
  last_modified_utc: "20260406051111"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-plan"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "plan"
  artifact_kind: "version"
  purpose: "Project plan for Lupopedia 4.0.94"
  tags: ["plan", "version", "4.0.94", "cursor"]
lupopedia.footer:
  last_verified: "20260406051111"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/PLAN.md — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/PLAN.md

# Plan - Lupopedia 4.0.94

**Version:** 4.0.94  
**Status:** Feature complete for constitutional/session/`$UNTRUSTED`/portability/locale scope — **Phase 7 (packaging and testing) in progress**

---

## Phase 1: Constitutional alignment (COMPLETED)

### Completed tasks

- [x] Update PRD 00 with PK Naming Rule (Rule 93.PK_NAMING)
- [x] Update PRD 00 with Absolute-Root Pathing (Rule 93.PATH_PURITY)
- [x] Update PRD 00 with PHP tiered compatibility (Option 4)
- [x] Update PRD 00 Y2038 compliance with honest 32-bit warning
- [x] Create PRD 16 (LUPOPEDIA HEADERS) – APPROVED
- [x] Create PRD 26 (Five-Layer Documentation Architecture) – APPROVED
- [x] Create PRD 30 (PRD Development Guide) – REJECTED (needs rewrite)
- [x] Create PRD 31 (Implementation Folder Guidelines) – REJECTED (merged into PRD 26)
- [x] Create LUPO_LAYERS_DOCTRINE.md (active)
- [x] Deprecate DYNAPI_DOCTRINE.md

### Deferred to 4.0.95

- [ ] Rewrite PRD 30 as writing guide (not metadata spec) — see **`lupo-docs/versions/4.0.95/TODO.md`**

**Note:** Root rules `author` block migration (former P4-002) shipped in **4.0.94**; files without `lupopedia.headers` were out of scope.

---

## Phase 2: Session authority migration (COMPLETED)

### Completed tasks

- [x] Refactor Session class (`app/auth/Session.php`)
- [x] Add `createEmbedSession()` for Eye cookie
- [x] Add metadata helpers (`getDecodedMetadata`, `mergeSessionMetadata`)
- [x] Deprecate AuthSessionManager
- [x] Refactor AuthService to use `lupo_sessions.metadata`
- [x] Refactor login.php to use session metadata
- [x] Refactor select_agent.php to use session metadata
- [x] Refactor admin.php to use session metadata
- [x] Remove `$_SESSION['actor_id']` from core paths (see decision + grep verification)

---

## Phase 3: `$UNTRUSTED` compliance (COMPLETED)

### Completed tasks

- [x] Add `$UNTRUSTED` boundary to install.php
- [x] Add `$UNTRUSTED` boundary to login.php
- [x] Add `$UNTRUSTED` boundary to select_agent.php
- [x] Add `$UNTRUSTED` boundary to admin.php
- [x] Add `$UNTRUSTED` boundary to auth-controller.php
- [x] Add `$UNTRUSTED` boundary to auth-helpers.php
- [x] Add `$UNTRUSTED` boundary to auth-ui-helpers.php
- [x] Add `$UNTRUSTED` boundary to main_layout.php
- [x] Add `$UNTRUSTED` boundary to topbar.php
- [x] Add `$UNTRUSTED` boundary to UrlResolver

---

## Phase 4: Database portability (COMPLETED)

### Completed tasks

- [x] Replace `SHOW TABLES LIKE` with `information_schema.tables`
- [x] Replace `SHOW TABLES LIKE` in InstallWizardDb
- [x] Replace `SHOW TABLES LIKE` in detectLivehelpTables
- [x] Add `tableExists()` method with portable query
- [x] Remove `mcrypt_create_iv()` references
- [x] Remove YAML dependency from ToonSchemaCache (JSON schema path)

---

## Phase 5: Locale support (COMPLETED)

### Completed tasks

- [x] Add `lupo_t()` to auth-controller.php
- [x] Add `lupo_t()` to select_agent.php
- [x] Add `lupo_t()` to topbar.php
- [x] Add `lupo_t()` to main_layout.php
- [x] Add `lupo_t()` to auth-ui-helpers.php
- [x] Add `lupo_t()` to login.php
- [x] Add locale strings to `lupo-includes/lang/lupo-en.php`

---

## Phase 6: Documentation (COMPLETED)

### Completed tasks

- [x] Update CHANGELOG.md (rollup + detailed log + packaging boundary note)
- [x] Update PLAN.md
- [x] Update TODO.md
- [x] Update edges.md
- [x] Update README.md (Softaculous packaging readiness + lineage)
- [x] Add VERSION_SUMMARY.md

---

## Phase 7: Packaging and testing (IN PROGRESS)

Normative gate and packaging narrative: **`lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`** (4.1.0 line); this phase executes **4.0.94 tarball / installer smoke** on Linux shared-host-class environment.

### Completed tasks (dev)

- [x] Unit tests pass on PHP 7.4 (target dev stack)
- [x] Installer works on fresh database (dev)
- [x] Crafty Syntax 3.7.5 import SQL + wizard path exercised (dev; single supported upgrade story until 4.1.0)
- [x] Session metadata persistence verified (login / password / pending agent flows)

### In progress

- [ ] **Pre-release:** Clean DB + remove `lupopedia-config.php` + full **`install.php`** run from current tree + smoke — see **`lupo-docs/versions/4.0.94/TODO.md`** (T-VERIFY-001–004)
- [ ] Run full regression test suite (`sh lupo-scripts/run_tests.sh .`) — **TODO.md** T-VERIFY-005
- [ ] **After verification green:** Softaculous packaging test — build tarball per **`SOFTACULOUS_PACKAGE_BUILD.md`**, deploy on Linux, exercise install + HTTP smoke
- [ ] Test on PHP 5.6 (legacy install flag path)
- [ ] Test on 32-bit PHP (warning verification)

---

## Phase completion status (dependency order)

Per **TASK_PLANNING_DOCTRINE** — status only, no calendar estimates.

| Phase | Status |
|-------|--------|
| Phase 1 — Constitutional alignment | COMPLETED |
| Phase 2 — Session authority migration | COMPLETED |
| Phase 3 — `$UNTRUSTED` compliance | COMPLETED |
| Phase 4 — Database portability | COMPLETED |
| Phase 5 — Locale support | COMPLETED |
| Phase 6 — Documentation | COMPLETED |
| Phase 7 — Packaging and testing | IN PROGRESS |

---

## Blockers

None at this time.

---

## Next actions

1. **Softaculous packaging test** on Linux (primary gate for this phase)
2. Run full regression test suite
3. Track deferred product/docs tasks in **`lupo-docs/versions/4.0.95/TODO.md`**
4. Prepare **4.1.0** / PRD 33 execution planning separately from 4.0.95 patch backlog
