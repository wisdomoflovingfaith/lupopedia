---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/versions/4.0.89/WHAT_TO_DO_NEXT_SESSION.md
  content_id: 1071874546039914702
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.89/WHAT_TO_DO_NEXT_SESSION.md
  last_modified_utc: "20260329235907"
  when_updated: "20260329235907"
  channel_id: 42
  thread_id: "4-0-89-session-handoff"
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: guide
  artifact_kind: documentation
  purpose: Post-session handoff for IDE agents — 4.0.89 headers/import release line; import_content.py --write-back + RECONCILE_PK errata; fastest path to tag; distinct from legacy_research Thoth planning doc
  tags:
    - "4.0.89"
    - handoff
    - headers
    - release
lupopedia.edges:
  outbound_edges:
    - to: lupo-docs/versions/4.0.89/CHANGELOG.md
      type: references
      weight: 1.0
      reason: Release verification + code-fix table (authoritative for H5–H8)
    - to: lupo-docs/versions/4.0.89/TODO.md
      type: references
      weight: 1.0
      reason: Task matrix H1–H9 and sign-off table
    - to: lupo-docs/versions/4.0.89/README.md
      type: references
      weight: 1.0
      reason: Release criteria 1–12
    - to: lupo-docs/versions/4.0.89/legacy_research/WHAT_TO_DO_NEXT_SESSION.md
      type: references
      weight: 0.5
      reason: Separate file — THOTH legacy/Crafty research line; not this handoff
lupopedia.footer:
  last_verified: "20260329235907"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: Cursor IDE Agent (Lead Orchestration)
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: junie:root
  next_action:
    - Reconcile any remaining lupo_contents slug/PK drift with python lupo-scripts/import_content.py --write-back <path.md> then confirm PHP import UPDATE path (H8.6)
    - WOLFIE decides git tag 4.0.89 and any production push
    - Close process gaps in TODO H2.1 H2.3 H4.2 H4.4 (non-blocking for tag if WOLFIE waives with recorded risk)
---

# file: WHAT_TO_DO_NEXT_SESSION — channel: 42 — delegation: cursor:root — web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.89/WHAT_TO_DO_NEXT_SESSION.md](http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.89/WHAT_TO_DO_NEXT_SESSION.md)

# What to do next session (4.0.89 — LUPOPEDIA HEADERS line)

This file is the **session handoff for the headers / import / release-gate track**. Another file exists at **`legacy_research/WHAT_TO_DO_NEXT_SESSION.md`** (THOTH, Crafty Syntax legacy research). **Do not merge or replace those** — different scope.

---

## Read first (in order)

1. **`CHANGELOG.md`** — **Release verification** (H5, H7, H8 tables), **Post-verification code fixes**, **DB migration note**, and **Errata — import tool behavior** (Python `--write-back`, `RECONCILE_PK_UPDATE`, PHP comment fix). That errata block is the authoritative short summary of the **latest** import semantics.
2. **`TODO.md`** — **4.0.89 release sign-off** (bottom), **H5–H9**; **H8** file-write policy updated to match Python/PHP **DB-only default** + optional **`--write-back`**.
3. **`README.md`** — criteria **1–12**, **Dual running log**, **PHP import quick reference** (now lists **both** Python and PHP commands; **`event_id: 10`** documents the policy correction).
4. **Code / scripts** (if you touch imports or parity):
   - `lupo-scripts/import_content.py` — **`--write-back`**; legacy reconciliation **`_remap_stale_content_pk`** / **`_find_legacy_content_id`**; default does **not** rewrite markdown.
   - `lupo-scripts/import_content.php` — docblock no longer claims Python always write-backs.
   - Prior session parity (unchanged unless regressing): `HeaderDbSync.php` CRLF order, `class-pdo_db.php` **`SET NAMES utf8mb4`**, `import_content.py` **`_norm_path_repo`**, `lib/header_validation.py` path / **`thread_id`** rules.

---

## What this programming session added (Cursor / actor 102 — append-only to prior work)

- **Import semantics aligned:** Python **`import_content.py`** matches PHP: **DB + sync by default**; **`--write-back`** sets **`content_id`** in YAML when needed.
- **Slug / stale PK path:** If deterministic **`content_id`** has no row but **`file_path_from_root`** or **`slug`** matches a legacy row, import logs **`RECONCILE_PK_UPDATE`** and remaps **`lupo_contents`** PK plus **`lupo_metadata`** / **`lupo_edges`** entity ids (preserves **`created_ymdhis`**, **`view_count`**, **`version_number`**).
- **Docs corrected (do not reintroduce the old claim):** **`CHANGELOG`** errata + **`TODO` H5/H8** + **`README`** quick reference — neither toolchain “always” mutates markdown.
- **Example runs (this repo):** **`README.md`** and this file were imported with **`--write-back`**; second pass without flag performed **UPDATE** without file churn.

---

## Problems: resolved vs still open

| Topic | Status | Notes |
|-------|--------|--------|
| Duplicate slug on INSERT after **`content_id`** change | **Mitigated in tooling** | Use `python lupo-scripts/import_content.py --write-back <path.md>`; see **CHANGELOG** errata. |
| Docs said Python always write-backed | **Fixed** | **README** **`event_id: 10`**, **TODO H8**, **CHANGELOG**. |
| **H8.6** full PHP import proof on reconciled DB | **Open** | Run **`php lupo-scripts/import_content.php`** (non–dry-run) on an H5 file after any reconciliation; confirm **UPDATE** + **`syncHeaderArtifactToDb`**. |
| **H2.1 / H2.3 / H4.2 / H4.4** | **Open** | Process and parity items; see **TODO** — may be waived for tag with recorded risk (**WOLFIE**). |
| **`git tag 4.0.89`** / push | **Open** | Orchestrator decision. |
| Validator Tier 2 warnings (`when_updated`, footer order) | **Open** | Non-fatal hygiene. |
| **`faucet_prompt_snippet.txt`** | **Open** | Optional; check **`lupo-scripts/`**. |

---

## Fastest path to complete 4.0.89 (dependency order)

1. **Reconcile DB vs files (only if you still see slug or PK errors):** For each affected `.md`, run **`python lupo-scripts/import_content.py --write-back <path>`** from repo root (PowerShell: `Set-Location` first). Prefer path list from repo search over ad-hoc SQL CLI unless your ops policy allows it.
2. **Close the mechanical gate:** **`php lupo-scripts/import_content.php <same-file.md>`** (no **`--write-back`** if you only need DB proof) — confirm **UPDATE** and no duplicate-key errors.
3. **Tag:** When **WOLFIE** accepts, annotated tag **`4.0.89`** referencing headers + dual toolchain + DB authority (message template may live in prior orchestration artifacts — do not invent release text without WOLFIE).
4. **Then** (or in parallel if staffing allows): **TODO** **H2.x**, **H4.x**, **H9.4** extended audit — these are **not** strictly blocking if explicitly waived for this tag.

---


## End-of-session summary (2026-03-28, Cursor)

- **content_id** is now generated as timestamp+random (see CHANGELOG and README event log).
- Both Python and PHP import logic updated; all new imports use the new PK format.
- Doctrine and documentation updated; see `DATABASE_DOCTRINE.md` §4.1.
- No errors found in updated scripts; ready for further testing or migration as needed.

## Suggested next session tasks (detail)

1. **Database:** Finish **`lupo_contents`** alignment for any markdown still failing PHP import; use Python **`--write-back`** + reconciliation path first.
2. **H8.6:** PHP full import smoke test post-reconciliation.
3. **Tag / push:** Per WOLFIE.
4. **H2 / H4:** **`LupopediaHeaderValidator`** parity, admin validation surface, rule packs, org literacy sign-off — **`TODO.md`**.
5. **Hygiene:** Fix invalid **`when_updated`** / footer timestamps where noisy.

---

## For other agents

- **Do not** strip **Release verification** or prior actor tables in **`CHANGELOG.md`** — **append** errata subsections only (this session’s errata is the model).
- **Preserve** **`lupopedia.history`** on **`README.md`**: **append** new **`event_id`** entries; do not renumber existing events.
- **WOLFIE-owned** bullets in **`TODO.md`** footer / **`CHANGELOG`** overview: extend with new bullets rather than replacing orchestration lines unless WOLFIE directs.
