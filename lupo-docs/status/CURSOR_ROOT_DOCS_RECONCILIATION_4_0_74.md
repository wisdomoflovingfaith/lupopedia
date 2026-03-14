---
lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/status/CURSOR_ROOT_DOCS_RECONCILIATION_4_0_74.md"
  last_modified_utc: "20260314"
  system_version: "4.0.74"
  channel_id: 42
  actor_id: 1003
  artifact_type: "status"
  artifact_kind: "reconciliation"
  purpose: "Root canonical docs (CHANGELOG, plan, TODO, README) reconciled to v4.0.74 verified state."

lupopedia.footer:
  last_verified: "20260314"
  last_verified_by: "cursor"
  next_action:
    - "Keep root docs in sync after upgrade-path and fresh-install tests"
---
# file: CURSOR_ROOT_DOCS_RECONCILIATION_4_0_74 — status

# Root Docs Reconciliation 4.0.74

Short report of updates applied to root canonical docs so they reflect **v4.0.74** and verified repository state. Inputs: repo truth, CHANGELOG, Windsurf/KIRO review findings, install SQL and seed wiring.

---

## 1. CHANGELOG.md

**Updated:**
- 4.0.74 intro: clarified that this release includes schema/seed changes (lupo_projects, 12-table expansion) and path normalization, not “no runtime behavior changes.”
- **New subsection:** *lupo_projects and seed wiring (4.0.74)* — lupo_projects in install SQL; seed_projects.sql created and wired in four installer paths (bootstrap, upgrade run, new-install run, main seed loop); table doc and SCHEMA_REGISTRY reference.

**Already present (verified):**
- 12-table install expansion; install SQL and Crafty upgrade; installer script path (`lupo-scripts/import_channels_and_artifacts.py`); prefix/TOON corrections and **legacy/ intentional exception**.

---

## 2. plan.md

**Updated:**
- **New section:** *4.0.74 implemented (verified)* — lupo_projects + seed_projects wiring; 12-table expansion and 159 table count; path/prefix normalization with **legacy/ intentional exception**; advisory table-count doctrine; installer script path.
- next_action: TOON line simplified to “install SQL remains authoritative; TOONs are derived.”

**Already present:**
- P0/P1/P2 structure; Antigravity delivery; upgrade path test; faucet plan references; TABLE_COUNT_DOCTRINE and SCHEMA_REGISTRY edges.

---

## 3. TODO.md

**Updated:**
- last_modified_utc / last_verified set to 20260314.
- Intro: v4.0.74 described as current release with schema, seed wiring, path normalization, and **legacy/ exception** in place; remaining work = validation and optional follow-ups.
- **Immediate:** Only upgrade test and fresh-install test kept as checkboxes.
- **Optional / follow-up:** Orchestrator rules note; TOON output path unification/documentation.
- **By version:** v4.0.74 summarized as implemented (lupo_projects, 12-table, path norm, legacy exception, 159 count) with remaining validation tests; pointers to CHANGELOG and plan.

**Removed/trimmed:**
- Redundant “Still needing to be done” phrasing; TODO now reflects only real remaining work.

---

## 4. README.md

**Updated:**
- **Database domains:** Explicit that **install SQL is canonical** and TOONs are derived; script paths `lupo-scripts/generate_toon_from_sql.py` and `lupo-scripts/generate_toon_files.py`; table ceiling advisory; `lupo_projects` added to domain list.

**Already present (verified):**
- Version 4.0.74; lupo-* layout; **legacy/ intentional exception**; actors vs faucets; references to TABLE_COUNT_DOCTRINE, lupo_projects, seed_projects, plan, report, AGENTS, lupo-docs.

---

## 5. Windsurf findings carried forward

- 4.0.74 work reflected as doctrinally compliant in root docs.
- lupo_projects and seed_projects.sql status stated clearly (install SQL + seed file + installer wiring).
- Path normalization and **legacy/ as intentional exception** documented in README and plan.
- Advisory table-count doctrine and install-SQL authority called out in README, plan, and TABLE_COUNT_DOCTRINE.
- TOON/install/planning distinction: install SQL canonical; TOONs derived; planning/future_features separate.

---

## 6. KIRO-level corrections preserved

- Paths in root docs use lupo-scripts, lupo-docs, lupo-database (no stale scripts/ or docs/).
- 12-table batch described as implemented where repo state supports it (install SQL + migration + docs).

---

## 7. Unresolved / follow-up

- **TOON output path:** Script may write to `lupo-database/lupopedia/toon/` or `lupo-docs/toons/` depending on config; doctrine is “install SQL authoritative, TOONs derived.” Unify or document in tooling as needed.
- **Validation:** Upgrade-path and fresh-install tests remain the main open validation; results to be recorded in plan/report when run.

---

## 8. legacy/ exception

**Confirmed and documented:** **`legacy/`** is the **intentional exception** to the lupo- prefix rule. It holds legacy read-only code and is not renamed. Stated in README, plan (4.0.74 implemented), and [CURSOR_4_0_74_PREFIX_PATH_AND_TOON_CORRECTIONS.md](CURSOR_4_0_74_PREFIX_PATH_AND_TOON_CORRECTIONS.md).

---
*Cursor (actor_id 1003) — root docs reconciliation 4.0.74 — 2026-03-14*
