---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/SEMANTIC_MONITORING_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/SEMANTIC_MONITORING_DOCTRINE.md"
  last_modified_utc: "20260406142956"
  when_updated: "20260406142956"
  when_updated_utc: "20260406142956"
  federation_node_id: 0
  channel_id: 42
  thread_id: "semantic-monitoring-doctrine"
  actor_id: 2
  actor_name: lilith
  delegation_chain: "lilith:audit"
  artifact_type: doctrine
  artifact_kind: architecture
  purpose: "Two JavaScript monitoring surfaces (Semantic OS Eye vs live-help trigger)—PRD pointers, routing truth, Softaculous/installer implications. IDE must read PRDs; do not invent UI."
  status: active
  tags:
    - semantic_os
    - javascript
    - monitoring
    - live_help
    - prd_28
    - prd_11
    - prd_18
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/28_semantic_monitoring_widget.md"
      type: references
      weight: 1.0
      reason: "Canonical Eye / floating widget UI and behavior"
    - to: "lupo-docs/prd/11_analytics_tracking.md"
      type: references
      weight: 1.0
      reason: "Visits, paths, referrers, edge aggregates — storage and semantics"
    - to: "lupo-docs/prd/18_channel_chat_display.md"
      type: references
      weight: 1.0
      reason: "Operator/visitor chat presentation and actor attribution"
    - to: "lupo-docs/prd/04_lupopedia_js_foundation.md"
      type: references
      weight: 0.95
      reason: "JS foundation and embed contracts for Lupopedia-owned scripts"
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: references
      weight: 0.95
      reason: "4.1.0 gate — root livehelp_js.php + lupopedia_js.php test snippets"
    - to: "lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Two-UI strategy — desktop vs mobile web for Eye-class widgets"
    - to: "lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md"
      type: references
      weight: 0.9
      reason: "Who owns desktop hand-coded UI vs IDE-assisted mobile surfaces"
    - to: "livehelp_js.php"
      type: references
      weight: 1.0
      reason: "Root live-help JS generator (PDO / lupo schema)"
    - to: "lupo-includes/modules/crafty_syntax/livehelp-js.php"
      type: references
      weight: 0.95
      reason: "Slug-routed equivalent when served via index.php front controller"
    - to: "lupo-includes/modules/nav/semantic-navbar-js.php"
      type: references
      weight: 1.0
      reason: "Canonical dynamic JS output for semantic navbar / Eye-class shell (4.0.71+)"
    - to: "lupo-includes/modules/module-loader.php"
      type: references
      weight: 1.0
      reason: "Routes livehelp_js, image, semantic navbar, channel APIs, navbar REST shape"
    - to: "lupo-includes/modules/api/semantic-navbar-api.php"
      type: references
      weight: 1.0
      reason: "edges/contexts/… JSON backing for navbar"
    - to: "lupo-docs/doctrine/CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md"
      type: references
      weight: 0.85
      reason: "Chat UI must not use IIFE isolation — differs from optional one-shot Eye embeds"
lupopedia.footer:
  last_verified: "20260406142956"
  verified_by:
    identity_type: actor
    actor_id: 2
    actor_name: lilith
  orchestrator: "cursor:root"
---

# file: Semantic monitoring doctrine — The Eye + live-help JS — Lupopedia

# Semantic monitoring doctrine (The Eye + the chat trigger)

## Purpose

Lupopedia is **not** a generic CMS or blog. It is a **Semantic OS**: it **observes** visitor behavior on sites where it is embedded, **persists** analytics and semantic edges per **PRD 11**, exposes **operator/actor chat** per **PRD 18** and Crafty lineage, and ships **UI chrome** for monitoring and collections per **PRD 28** (and related collection PRDs).

This doctrine names **two** JavaScript-related monitoring **roles** and ties them to **authoritative PRDs** and **actual repo routing**. **IDE agents must read those PRDs** before changing embed code, APIs, or widgets. **Do not** guess floating bars, modals, or REST paths.

## What Lupopedia is (and is not)

| Is | Is not |
|----|--------|
| Semantic OS substrate: paths, visits, edges, collections, chat | WordPress-class blog/CMS as the product model |
| Subdirectory install; **`LUPOPEDIA_PUBLIC_PATH`**-relative URLs | Hardcoded `/lupopedia/` or domain assumptions |
| Crafty Syntax live-help successor (data + transport evolved) | A static-only marketing site |

## System 1 — The Eye (semantic monitoring + PRD 28 UI)

**Role:** Page-context monitoring, semantic navbar / “Eye”-class UX, edges and collections **as specified in PRD 28** (and **PRD 11** for analytics persistence, **PRD 05** for collections navigation where cited).

**Canonical UI specification:** **`lupo-docs/prd/28_semantic_monitoring_widget.md`** — floating chrome, collections behavior, edge presentation, motion/DynAPI constraints, etc.

**Current implementation anchor (repo):**

- Dynamic JS output: **`lupo-includes/modules/nav/semantic-navbar-js.php`**
- Integration entry: **`lupo-includes/modules/nav/semantic_navbar.php`**
- **Routing** (front controller): **`lupo-includes/modules/module-loader.php`** — slugs such as **`nav/semantic-navbar`**, **`nav/semantic-navbar-js`**, **`nav/semantic_navbar`**
- JSON backing for navbar types: **`lupo-includes/modules/api/semantic-navbar-api.php`** — invoked for slugs matching **`edges|contexts|hashtags|folders|qa|references|namespaces|next|previous`/`{slug}`** (see **`module-loader.php`**)

**Root script name `lupopedia_js.php`:**

- **PRD 33**, **README**, and version **TODO** rows treat **`lupopedia_js.php`** at the **document root** as the **public embed contract** for the semantic / Eye pipeline (post-install test snippet).
- At the time of this doctrine revision, that **exact root filename may not exist** in every checkout; **4.1.0 / packaging work** must either **add** a thin root **`lupopedia_js.php`** that bootstraps the same generator, or **update PRDs** with an approved alias—**do not** assume IDE agents can skip the contract. Until unified, treat **`…/nav/semantic-navbar-js`** (under **`LUPOPEDIA_PUBLIC_PATH`**) as the **live** generator path.

**Storage / time doctrine:** persisted analytics and edges use **BIGINT UTC** **`YYYYMMDDHHIISS`** and schema per **PRD 11** and table docs — not WordPress-style `DATETIME`.

## System 2 — The chat trigger (`livehelp_js.php`)

**Role:** Visitor-side **live help** activation: Crafty-compatible probes, department/channel context, transport toward **channel.php** / **livehelp** stack and **PRD 18** operator surfaces.

**Repo anchors:**

- **Root generator:** **`livehelp_js.php`** (bootstraps config, emits JS; **`PDO_DB`** / **`lupo_`** tables only — **installer exception** for DB driver rules: **`lupo-docs/doctrine/DATABASE_DOCTRINE.md`** — **Runtime database access (PDO_DB) and installer exception**)
- **Slug-routed module:** **`lupo-includes/modules/crafty_syntax/livehelp-js.php`** when the request is normalized to **`livehelp_js`** / **`livehelp-js`** (**`module-loader.php`**)
- **Legacy image / state channel:** root **`image.php`** and **`lupo-includes/modules/crafty_syntax/visitor-image.php`** (for **`what=`** patterns routed as **`image`**)
- **Chat pages / stream:** **`livehelp`**, **`chat`**, **`visitor-chat-stream`**, etc. — **`module-loader.php`**

Chat **does not** replace the Eye; sites may load **one or both** embeds. Transport remains **plain PHP + XMLHttpRequest-style polling** in legacy parity paths unless a **written PRD** adds another channel.

## Combined architecture (conceptual)

```text
Host site pages
  ├─ <script src="{LUPOPEDIA_PUBLIC_PATH}/livehelp_js.php?…">  →  visitor live help (PRD 18 lineage)
  └─ <script src="{LUPOPEDIA_PUBLIC_PATH}/nav/semantic-navbar-js">  (or future lupopedia_js.php)
        →  semantic navbar / Eye-class UI (PRD 28) + calls into navbar API slug shape

Lupopedia server (same install)
  ├─ index.php front controller + module-loader routes
  ├─ semantic-navbar-api.php  (edges/contexts/… per slug)
  ├─ channels APIs (e.g. api/channel/messages, api/channel/check, …)
  └─ image.php / visitor-image.php for Crafty-class state GIFs / payloads
```

**Softaculous / installer:** the distribution must ship **root** **`livehelp_js.php`**, **`image.php`**, **`index.php`**, **`lupo-includes/`** (including **`modules/nav/`**, **`modules/crafty_syntax/`**, **`modules/api/`**), and **rewrite rules** so slugs and **`api/`**-style paths reach **`index.php`** — see **`InstallWizardHtaccessWriter`** and **PRD 33**.

## API and routing discipline

- **Do not** invent fictional **`POST /api/track/entry`** trees in docs unless they exist in **`module-loader.php`** and ship in the repo.
- **Navbar data** uses the **documented** pattern **`{type}/{slug}`** (e.g. **`edges/my-page`**) backed by **`semantic-navbar-api.php`**.
- **Channel chat** uses **existing** **`api/channel/…`** and related **channels** module endpoints as wired in **`module-loader.php`**.
- For **new** analytics ingest, extend **PRD 11** and wire **explicit** routes—no silent magic URLs.

## Mobile and desktop

**`lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md`** — same semantic obligations, **different** DOM/JS where interaction diverges. **WOLFIE**-owned desktop chrome vs IDE-assisted **mobile web** per **`WOLFIE_WORKFLOW_DOCTRINE.md`**.

## LILITH instruction (binding summary)

```yaml
instruction_for_ide: |
  Lupopedia is a Semantic OS, not a CMS template product.
  Two monitoring roles: (1) Eye / semantic navbar + PRD 28 UI, (2) livehelp_js chat trigger + Crafty lineage.
  Read PRD 28, PRD 11, PRD 18 (and PRD 05 where collections apply) before changing embeds or UI.
  Do not invent REST paths or widget layouts.
  Root lupopedia_js.php is a 4.1.0-class embed contract; align implementation or PRD alias with nav/semantic-navbar-js.
  Softaculous package must ship both pipelines' runtime files and working rewrites.
```

## Reference table

| Topic | Canonical doc |
|-------|----------------|
| Eye widget UI | **`lupo-docs/prd/28_semantic_monitoring_widget.md`** |
| Analytics / visits / edges | **`lupo-docs/prd/11_analytics_tracking.md`** |
| Chat display / actors | **`lupo-docs/prd/18_channel_chat_display.md`** |
| JS foundation | **`lupo-docs/prd/04_lupopedia_js_foundation.md`** |
| 4.1.0 / embed checklist | **`lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`** |
| Mobile vs desktop | **`lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md`** |
| Build / consumer order | **`lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md`** |

This output complies with Lupopedia Constitutional Root Rules.
