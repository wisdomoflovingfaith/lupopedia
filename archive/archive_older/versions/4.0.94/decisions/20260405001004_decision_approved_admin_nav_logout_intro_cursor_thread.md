---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260405001004"
  file_path_from_root: "docs/versions/4.0.94/decisions/20260405001004_DECISION_APPROVED_admin_nav_logout_intro_cursor_thread.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/decisions/20260405001004_DECISION_APPROVED_admin_nav_logout_intro_cursor_thread.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: decision
  thread_id: "version-4.0.94-decisions"
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
# file: DECISION admin_nav_logout_intro — delegation: cursor:root

# DECISION (APPROVED): Admin scroll nav — logout / intro replay, logo, actor strip (code receipt)

## 5W1H

| Element | Record |
|---------|--------|
| **WHO** | Cursor IDE agent (`actor_id` **102**), orchestration `cursor:root`. |
| **WHAT** | **(1)** **`logout.php`** — stop using bare `302` to `login.php`; emit minimal HTML that runs `sessionStorage.removeItem('lupo_admin_scroll_intro_v1')` then `window.location.replace(login)` (+ meta refresh + noscript link). **(2)** **`includes/js/admin-intro-scroll.js`** — file comment updated: intro replays after logout clears the key. **(3)** **`includes/themes/default/layouts/admin_layout.php`** — left nav lead: **`.admin-nav-logo`** link + `img`; optional overrides **`$admin_nav_logo_src`**, **`$admin_nav_logo_href`**, **`$admin_nav_logo_alt`**; default image `LUPOPEDIA_PUBLIC_PATH/images/logoface.png`, default href `index.php`. **(4)** Same layout — remove **`ACTOR:`** prefix in top nav; display name truncated to **15** code units (UTF-8 via **`mb_strlen` / `mb_substr`** when available, else **`strlen` / `substr`**) + **`..`** when longer; full name in **`title`**. **(5)** **`includes/css/admin-intro-scroll.css`** — **`.admin-nav-lead`** flex left; **`.admin-nav-logo`** **90×60** px slot, **`object-fit: contain`** on `img`; **`.admin-nav-actor-text`** **`max-width: 12em`** (was **`42vw`**). |
| **WHERE** | Repo paths above; version receipt: **`docs/versions/4.0.94/`** — **`CHANGELOG`**, **`PLAN`** Phase **L**, **`TODO`**, **`edges`**, **`WHAT_TO_WORK_ON_NEXT_SESSION`**, **`decisions/`** + **`comments/`** indexes, this file, paired **COMMENT** `20260405001004_…`. |
| **WHEN** | Evidence batch UTC **`20260405001004`** (`python bin/tick.py` this documentation batch). |
| **WHY** | **Intro:** `sessionStorage` is per browser tab; PHP logout did not clear it, so re-login in the **same tab** skipped the scroll intro. **Logo:** Operator asked for a fixed left-corner brand slot opposite the actor strip. **Actor strip:** Reduce chrome width; avoid overflow from long names. |
| **HOW** | PHP emits transitional HTML on logout; vanilla JS + existing admin layout/CSS; no new dependencies. |

## APPROVED scope (thread-verified)

- Logout path clears **`lupo_admin_scroll_intro_v1`** before redirect to login.
- Admin top nav includes configurable **90×60** logo area.
- Actor name display: no **`ACTOR:`** label; **15** + **`..`** truncation with **`title`** = full string.

## WHAT NOT claimed (this thread)

- **No** PRD **16 / 26 / 30 / 31** edits, **COUNTERMEASURE** feedback, or validator enhancements from unrelated directives — those belong to other threads unless separately evidenced in **`CHANGELOG`**.
- **No** guarantee that **every** sign-out path (e.g. custom JS logout elsewhere) clears **`sessionStorage`** — only **`logout.php`** updated here; mirror **`removeItem`** if another surface bypasses it.
- **No** change to **`basic-nav`** “Acting as:” block (legacy header below scroll shell).

## Outcome

**APPROVED** as accurate **version-folder receipt** for this **admin UI / logout / nav chrome** thread. Next orchestrator focus (human note): start working through **Crafty Syntax 3.7.5** feature parity list easy → hard — see **`WHAT_TO_WORK_ON_NEXT_SESSION.md`** and **`TODO.md`**.

This output complies with Lupopedia Constitutional Root Rules.
