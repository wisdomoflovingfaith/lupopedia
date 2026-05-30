---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260408161022"
  file_path_from_root: "docs/versions/4.0.96/status/STATUS_SESSION_IDENTITY_AND_GC_20260408161022.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.96/status/STATUS_SESSION_IDENTITY_AND_GC_20260408161022.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: status
  artifact_kind: handoff
  thread_id: ""
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
# Status — Session identity + probabilistic GC (2026-04-08 16:10 UTC)

## WHO did WHAT

| Actor | Role |
|-------|------|
| **Cursor IDE Agent** (actor_id **102**) | Implemented `App\Auth\Session` fingerprinting, `session_identity_hash`, salt + config, UA normalization, `resolvedClientIp`, `ensureTimestampClass`, `isExpired` / `validateSession`, `maybeProbabilisticGarbageCollect`, slim `SessionManager`, bootstrap wiring, `install_new_lupopedia.sql` columns/indexes, `generate_session_salt.php`, and version **CHANGELOG / PLAN / TODO** updates this batch. |
| **Claude Code** (actor_id **116**) | Authored **`SESSIONS_RESEARCH.md`** (gap analysis and formulas); no code ownership of the final PHP in this batch. |
| **Human orchestrator** | Directed scope, privacy constraints, and end-of-day documentation + git push. |

## WHERE it applies

- **Runtime:** `app/auth/Session.php`, `includes/classes/SessionManager.php`, `includes/bootstrap.php`, `lupopedia-config.php`.
- **Schema:** `database/lupopedia/mysql/install/install_new_lupopedia.sql` (`session_identity_hash`, indexes).
- **Tooling:** `scripts/generate_session_salt.php`.
- **Docs:** `docs/versions/4.0.96/CHANGELOG.md`, `PLAN.md`, `TODO.md`, this file.

## WHEN

**`20260408161022` UTC** — **2026-04-08, 16:10 UTC** (`python bin/tick.py` anchor for this batch).

## WHY

- Close gaps from **`SESSIONS_RESEARCH.md`**: unsalted/full-IP audit hashes, proxy-aware IP, D-003-safe clocks, optional composite identity hash, shared-hosting GC without cron.
- Separate **anonymous vs named** (`is_named`) idle bands for UX and cleanup.

## HOW (short)

- Packed UTC cutoffs via **`timestamp_ymdhis::subtractSeconds`**, never raw integer math on `YmdHis`.
- GC: probability + lock file; **`SessionManager::tick()`** only runs **`maybeProbabilisticGarbageCollect`**; **`validateSession()`** owns idle expiry + touch.

---

## Where we left off (for the next reader)

1. **CHANGELOG / PLAN / TODO** updated for **20260408161022 UTC** with full **WHO/WHAT/WHERE/WHEN/WHY/HOW** in **`CHANGELOG.md`**.
2. **Code** is in place; **fresh DB** from current **install** SQL includes new columns/indexes. Existing dev DBs may need a one-time schema align (project norm: reinstall from install SQL for 4.0.x — no Lupopedia→Lupopedia migration chain).
3. **TOON/JSON** exports under `docs/toons/` or `database/lupopedia/json/` were **not** regenerated in this session; run project **`generate_toon_from_sql.py`** / pipeline when schema export must match install.

---

## Observations

- **`is_named`** encodes “visitor named session” UX, **not** “logged-in vs guest actor” in all cases; if product wants TTL by **`actor_id`** or auth tier, that would be a **follow-up design** (separate from this batch).
- **`LUPO_SESSION_VALIDATE_UA`** defaults **false**; turning it on logs users out on UA change (browser upgrade, some mobile WebViews).
- **Probabilistic GC** deletes **stale rows**; semantics match **`markExpired`**-style cleanup volume over time, not instant deletion on every boundary.

## Improvement suggestions (not implemented)

1. **Optional `ClientIpResolver` class** in `includes/classes/` — single implementation shared by Session, visits, and analytics (avoid drift from Crafty `get_ipaddress()` reference).
2. **GC observability** — log affected row counts when `LUPOPEDIA_DEBUG` (requires capturing `DELETE` rowCount from `PDO_DB` if extended).
3. **“Last GC run” in `lupo_system_config`** — cap GC to once per N minutes without relying only on lock + probability (heavier; only if tables grow large).
4. **PRD 01** — align prose to final **`Session.php`** behavior and resolved-IP story (some lines still referenced older `REMOTE_ADDR`-only wording).
5. **Integration test** — scripted request that asserts `session_identity_hash` populated and GC path callable (test-only DB).

---

This output complies with Lupopedia Constitutional Root Rules.
