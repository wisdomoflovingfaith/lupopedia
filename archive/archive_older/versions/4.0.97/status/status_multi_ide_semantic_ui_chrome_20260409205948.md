---
lupopedia.headers:
  header_format_version: 3
  lupopedia.schema: documentation
  when_updated: "20260409205948"
  file_path_from_root: "docs/versions/4.0.97/status/STATUS_MULTI_IDE_SEMANTIC_UI_CHROME_20260409205948.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.97/status/STATUS_MULTI_IDE_SEMANTIC_UI_CHROME_20260409205948.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: null
  artifact_type: documentation
  artifact_kind: status_report
  thread_id: "version-4-0-97-semantic-chrome"
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
# file: STATUS_MULTI_IDE_SEMANTIC_UI_CHROME — version 4.0.97 — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/docs/versions/4.0.97/status/STATUS_MULTI_IDE_SEMANTIC_UI_CHROME_20260409205948.md

# Multi-IDE status: semantic UI chrome (collections pin, Eye, visitor shell)

**Anchored UTC:** `20260409205948` (2026-04-09 **20:59:48** UTC) from `python bin/tick.py`  
**Author:** Cursor IDE facet (**actor_id 102**)  
**Audience:** Claude Code (**116**), Antigravity IDE (**103**), Windsurf (**101**), Kiro (**100**) — plus any other facet touching visitor JS or content APIs

---

## Executive summary

Cursor shipped a coherent **visitor semantic chrome** slice: **pin current page to a collection tab** via **`lupo_collection_tab_map`**, **dynamic shortcut dropdown** fed by the same JSON as the blue collections bar, **Eye / monitor** behavior differentiated by **`artifact_type`** (tutorial vs transcript flavor), and **book vs scroll** 9-slice shell driven by **Master Settings** (`public_content_shell`). Version docs were updated: **`CHANGELOG.md`**, **`TODO.md`** (C-12 complete), **`PLAN.md`** (Phase L complete).

---

## WHO did WHAT

| Actor / facet | Role this batch |
|---------------|-----------------|
| **Cursor (102)** | Implemented PHP API, `CollectionTabsService` / loader extensions, `main_layout.php` shell + shortcut UI hooks, JS (`main-layout-collections.js`, `monitor.js`, `crafty_syntax_eyes.js`), CSS, `AdminSettingsHandler` setting, documentation in `docs/versions/4.0.97/` |
| **WOLFIE / orchestrator** | Product intent (shortcut semantics, Eye modes, shell toggle) — not the commit author of the code in this slice |
| **Claude / Antigravity / Windsurf / Kiro** | **Not** authors of this slice; requested to verify and extend per sections below |

---

## WHERE it applies (files)

- **`api/add_to_collection.php`** — POST handler; `content_id` / `id`, `content_slug`, `collection_tab_id` / `tab_id`, optional `title` → `properties` JSON; idempotent map rows  
- **`api/get_actor_tabs.php`** — alias: `require` **`load_collection_tabs.php`**  
- **`database/lupopedia/content/app/Services/CollectionTabsService.php`** — `_collection_tab_id`, `_children` with `collection_tab_id`  
- **`includes/functions/collection-tabs-loader.php`** — `lupo_get_public_content_shell()`  
- **`includes/themes/default/layouts/main_layout.php`** — `#shortcut-tabs-dynamic`, `lupoOpenShortcutDropdown`, body classes, hidden `#current-content-id`, `data-edge-focus`  
- **`includes/js/main-layout-collections.js`** — page context, dropdown refresh/sync, pin payload  
- **`includes/js/monitor.js`** — `semantic_edge_focus` / artifact type on track payload  
- **`includes/js/crafty_syntax_eyes.js`** — timing from DOM / focus mode  
- **`includes/css/main-layout.css`** — scroll + book grid alignment  
- **`includes/classes/AdminSettingsHandler.php`** — `public_content_shell`  
- **`includes/themes/default/components/collection_tabs.php`** — aligned pin affordance  
- **Docs:** `docs/versions/4.0.97/CHANGELOG.md`, `TODO.md`, `PLAN.md`, this file  

---

## WHEN

- **2026-04-09 20:59:48 UTC** — documentation batch and changelog entry finalized (tick anchor **`20260409205948`**).

---

## WHY

- Treat **shortcuts** as first-class **semantic graph** edges into **collection tabs** (tab map), not ad hoc client-only bookmarks.  
- Align **PRD 28**-style monitoring with **content type** (e.g. `help_guide` vs `text/markdown`) for Eye and track payloads.  
- Let operators switch **tile / layout shell** without redeploying theme code.

---

## HOW (implementation notes)

- **DB:** PDO_DB only; **no new tables**; uses **`lupo_collection_tab_map`**; soft-deleted rows can be revived on re-pin.  
- **IDs:** Map PK via **`IdGenerator`** (reserved-ID doctrine).  
- **Client:** Shortcut panel refreshes from **`load_collection_tabs.php`** when opening the dropdown; hidden content id + URL slug feed the server when pinning.  
- **Shell:** `lupo_settings.public_content_shell` → `body.book-layout` vs `scroll-layout`.

---

## Observations

1. **Empty shortcut list:** If the actor has **no tabs** under the active collection, the dynamic menu is empty — confirm seed / admin flows for **collection 0** (or default collection) include tabs in real installs.  
2. **Library / list routes:** Pages without a resolvable **`content_id`** (e.g. some list views) may only pin via **`slug`** if the API can resolve it; worth an explicit test matrix.  
3. **Duplicate API surface:** **`get_actor_tabs.php`** is intentionally a thin alias; avoid introducing a third competing tabs endpoint without deprecating one.  
4. **Desktop doctrine:** Further **desktop** template changes remain **WOLFIE-owned**; this work stayed at integration points (layout hooks, JS, CSS utilities).

---

## Questions for other IDE agents

1. **Claude Code (116):** When you next touch **Trust Ladder** or **content APIs**, please **smoke-test** `add_to_collection.php` with **slug-only** vs **content_id** payloads and note any auth edge cases.  
2. **Antigravity (103):** Confirm **channel_key** usage in any **tabs** or **metadata** callers still lines up with **`load_collection_tabs.php`** response shape (`_collection_tab_id`, `_children`).  
3. **Windsurf (101):** Optional pass on **`main-layout-collections.js`** / **`monitor.js`** for **race conditions** (dropdown open before tabs JSON returns).  
4. **Kiro (100):** Consider a **small PHP integration test** (logged-in fixture) for **pin** + **revive soft-deleted map row**.

---

## Doc updates (same session)

| File | Change |
|------|--------|
| `docs/versions/4.0.97/CHANGELOG.md` | New entry **[2026-04-09 20:59 UTC]** with full WHO/WHAT/WHERE/WHEN/WHY/HOW |
| `docs/versions/4.0.97/TODO.md` | **C-12** completed; headers/footer timestamps |
| `docs/versions/4.0.97/PLAN.md` | **Phase L** complete; multi-agent follow-through bullets extended |
| `docs/versions/4.0.97/status/THREAD_INDEX.md` | Index row for this report |
| Root `TODO.md` / `plan.md` | Pointer to **4.0.97** version backlog (if updated in same batch) |

---

This output complies with Lupopedia Constitutional Root Rules.
