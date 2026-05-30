---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260409233804"
  file_path_from_root: "lupo-docs/status/collections_try2_main_layout_session_20260409.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/status/collections_try2_main_layout_session_20260409.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: status_report
  artifact_kind: engineering_note
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
# file: collections_try2_main_layout_session_20260409 — delegation: cursor:root — web_path: [lupo-docs/status/collections_try2_main_layout_session_20260409.md](http://www.lupopedia.com/lupopedia/lupo-docs/status/collections_try2_main_layout_session_20260409.md)

# Collections try2 on `main_layout` — session status (2026-04-09)

## Summary

Try2 collections chrome (**`saved-collections-nav-try2.php`** + **`saved-collections-nav-try2.js`** + **`main_layout.php`**) behaved correctly on isolated debug pages, but **real content routes** could still look “broken” after **AJAX** refreshed the green tab row. The failure mode was **not** missing layout include on **`content/index.php`** (that path already uses **`render_main_layout()`**). The failure mode was **stale client-generated HTML** inside **`loadCollectionTabs`** in **`main-layout-collections.js`**.

## Who did what

| Actor | Role |
|-------|------|
| **Cursor IDE Agent (actor_id 102)** | Implemented try2 markup parity in **`loadCollectionTabs`**; aligned **`lupoMasterCollectionsHydrateIfEmpty`**, **`lupoMasterPanelLooksEmpty`**, and **`lupoCloseMasterCollectionsDropdown`** with try2 class names and close behavior; documented in **`lupo-docs/versions/4.0.97/CHANGELOG.md`** and this report. |
| **Human orchestrator (WOLFIE workspace)** | Confirmed behavior in browser; requested changelog + status documentation with explicit time-of-day. |

## Troubles and observations

1. **False trail:** Suspecting **`content/index.php`** did not load the same chrome. **Actual:** **`content/index.php`** is only a front controller; **`content-controller.php`** returns **`render_main_layout()`**, which already included the try2 partial and scripts.
2. **Real bug:** **`loadCollectionTabs`** replaced **`#collection-tabs-container`** with **legacy** classes (**`saved-collections-dropdown`**, **`toggleSavedCollectionsDropdown`**, etc.). First paint could show try2 (from PHP); **after** loading a collection from the API, the DOM reverted to legacy shape → **CSS/JS mismatch**.
3. **Master dropdown:** Try2 uses **`lupoDbgNavToggle`** and **`lupoDbgMasterHydrateIfNeeded`** in **`saved-collections-nav-try2.js`**. Older helpers in **`main-layout-collections.js`** still assumed **`.saved-collections-dropdown-content`** / **`.saved-collections-button`**. If any code path called **`lupoCloseMasterCollectionsDropdown`** or the legacy hydrate, the blue master control could fail to reset **aria** / **active** state on try2 DOM. Helpers were broadened to recognize **`.dropdown-panel`** / **`.dropdown-button`** and to prefer **`lupoDbgNavCloseAll()`** on row click when available.
4. **Tooling:** **PowerShell** rejected **`cd /d ... && python ...`** (`&&` not valid in older PS). Use **`Set-Location ...; python ...`** or run commands separately.

## What we learned

1. **Treat server partials and AJAX string builders as one contract.** When introducing a new UI variant (try2), search for **all** places that emit the same region of the DOM (PHP **and** JS), not only the theme partial.
2. **`#collection-tabs-container` has two authors:** initial **PHP** (`saved-collections-nav-try2.php`) and **runtime** **`loadCollectionTabs`**. They must stay in lockstep.
3. **`main-layout.js` DOMContentLoaded** already avoids double-fetch when **`.lupo-dropdown`** exists inside the container; that guard is correct but **does not** fix post-load innerHTML replacement — the replacement itself must be try2-shaped.
4. **Documentation timestamp discipline:** Use **`python lupo-bin/tick.py`** for **UTC** when writing **`last_modified_utc`** / changelog **WHEN** fields; include **hour** for operator clarity.

## Related artifacts

- **Changelog (4.0.97):** `lupo-docs/versions/4.0.97/CHANGELOG.md` — entry **[2026-04-09 23:38 UTC]**.
- **Implementation touchpoint:** `lupo-includes/js/main-layout-collections.js` (`loadCollectionTabs` + master helper block).

This output complies with Lupopedia Constitutional Root Rules.
