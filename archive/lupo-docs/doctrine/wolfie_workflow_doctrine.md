---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md"
  status: "active"
  when_updated: "20260406142956"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: workflow
  channel_key: null
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: WOLFIE_WORKFLOW_DOCTRINE — web_path: /lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md

# WOLFIE workflow doctrine

## Mobile-first, desktop-masterpiece

### The principle

> *Mobile first for logic. Desktop second for art.*

**Stage 1 — AI / IDE (mobile web UI):** ship **function** first—data flow, forms, buttons, API calls, navigation that **works**. Semantic HTML, basic CSS, vanilla JS. **No** desktop-only art: no heavy DynAPI, no mouse-follow, no book-spread chrome as the default for this stage.

**Stage 2 — WOLFIE (desktop UI):** take the **working** mobile surface as the **skeleton**—same backend contracts—and **transform** into the **desktop** experience: liquid layouts, DynAPI motion, layers, mouse-linked widgets, intentional pixels.

**Runtime** (which URL serves which device) stays under **MOBILE_SEPARATION_DOCTRINE.md**. This document defines **preferred build order** and **division of labor**, not a replacement for entry-point detection.

**Scope:** the **mobile-first → desktop-masterpiece** sequence applies to **consumer / visitor-facing** surfaces unless **The admin exception** (below) applies.

### Why this order

| Reason | Explanation |
|--------|-------------|
| **Mobile forces simplicity** | Small viewport; only essential structure survives. |
| **Logic surfaces first** | Fields, validation, and endpoints become clear before chrome. |
| **Desktop adds soul after** | No polished art on top of broken data flow. |
| **Separation of concerns** | AI / IDE owns **functional** mobile web templates; WOLFIE owns **desktop** craft (see **LESSONS** section 7, **AGENTS** hand-coding policy). |
| **One backend** | Same **PDO_DB**, same services, same business rules—two **presentation** tracks. (**Installer** DB driver carve-out: **`lupo-docs/doctrine/DATABASE_DOCTRINE.md`** — **Runtime database access (PDO_DB) and installer exception**.) |

### The workflow (stages)

| Stage | Owner | Focus | Output |
|-------|--------|-------|--------|
| **1** | AI / IDE | Mobile web UI — function only | Working **mobile** route: structure, forms, fetch/submit, basic layout |
| **2** | WOLFIE | Desktop UI — art | Hand-coded **desktop** route: liquid layout, DynAPI, mouse effects, spreads |
| **3** | Product + IDE | Integration | Both wired to **same** PHP services and DB; detection/routing per **MOBILE_SEPARATION_DOCTRINE** |

### What Stage 1 delivers (mobile skeleton)

- Simple, semantic HTML; readable on a phone.
- CSS sufficient for **clarity**, not **gallery** quality.
- JS for validation, submission, API calls—**working** paths.
- No **required** hover, no cursor tracking, no desktop-only animation stack.
- **Goal:** *it works* and shows **what data** the feature needs.

### What Stage 2 adds (desktop masterpiece)

- Liquid layouts, book metaphors, DynAPI-style layers where product requires.
- Mouse-following or hover-driven widgets **on desktop routes only**.
- Floating layers, pen-style writing, scroll choreography—**WOLFIE hand-coded**.
- **Same** endpoints and **same** field semantics as Stage 1 unless product explicitly extends them.

### Shared backend (non-negotiable)

- **One** set of database access patterns (**PDO_DB**), **one** auth/session story, **one** business rules layer.
- Mobile and desktop **templates** differ; **server** logic is not forked casually.

### Constitutional alignment (consumer default)

| Discipline | This workflow |
|------------|----------------|
| Explicit environments | Mobile build clarifies function; desktop build adds presentation. |
| Deterministic behavior | Stage 1 must **work** before Stage 2 **decorates**. |
| Own your code | WOLFIE **owns** desktop hand-coded surfaces; AI-assisted mobile is **reviewed** before merge. |
| No fake state | Skeleton is **real** working UI—not mocks hiding missing APIs. |

## The admin exception: desktop-first

### The rule

| Interface type | Workflow | Reason |
|----------------|----------|--------|
| **Consumer (visitor-facing)** | Mobile-first → desktop masterpiece | Most public traffic is mobile; logic surfaces on small screens first. |
| **Admin / operator (professional)** | **Desktop-first** | Tools for people at desks: density, multi-pane chat, settings, analytics. |
| **Operator on a phone** | **Native app** (**PRD 35**) — not mobile web admin | Essential controls, touch-first; **not** a full web admin in a narrow viewport. |

### Admin / operator surfaces (desktop-first)

Build **for desktop first** (WOLFIE hand-coded, Notepad-class workflow). Examples (names are product-shaped, not an exhaustive file list):

- **`admin.php`** route family — full admin / operator shell.
- **`live.php`**-class **operator chat console** — multi-session, multi-color channel chrome.
- **Settings and configuration** panels.
- **Analytics / path / referrer** viewers (`data.php`-class parity, rollups).
- **Operator controls** and department tools.
- **Any** UI whose primary user is an **authenticated operator or administrator** doing work shifts—not a casual visitor.

**Mobile web admin** as a **full** replacement for these surfaces is **out of scope**; operators on mobile use the **native app** track in **PRD 35** (or desktop), not a responsive shrink of `admin.php`.

### Why admin is desktop-first

| Reason | Explanation |
|--------|-------------|
| **Professional tool** | Operators assume keyboard, mouse, and screen area. |
| **Complex controls** | Many options, tabs, and simultaneous chats. |
| **Data density** | Paths, referrers, visit trees need readable columns. |
| **Multi-color / multi-chat** | Actor attribution and channel strips need **real estate**. |
| **Mobile = native** | Pocket use is **app** UX, not a webview of the full desktop admin. |

### Admin workflow (summary)

| Step | Task |
|------|------|
| **1** | WOLFIE builds **desktop** admin / operator UI first—working against **PDO_DB** and auth. |
| **2** | **Visitor / consumer** features still follow **mobile-first → desktop masterpiece** where they are separate surfaces. |
| **3** | **Native mobile app** for operators (**PRD 35**) — **TBD** implementation; **not** “AI builds mobile web admin” as the primary plan. |

### Consumer workflow (default — unchanged)

| Step | Task |
|------|------|
| **1** | AI / IDE: functional **mobile web** skeleton. |
| **2** | WOLFIE: **desktop** masterpiece; same backend. |

### Summary table

| Interface | Workflow | Primary creator | Primary target |
|-----------|----------|-----------------|----------------|
| Visitor chat, public pages, Eye-class widgets | Mobile-first → desktop masterpiece | AI → WOLFIE | End users |
| **Admin, operator console, analytics, config** | **Desktop-first** | **WOLFIE** | Operators at desks |
| Operator on phone | Native app (**PRD 35**) | Product / app track | Operators mobile |

### Voice (WOLFIE)

**Consumers:** the AI builds the **skeleton** on mobile; I add the **soul** on desktop.

**Operators:** I build the **desk** first—**admin** and **live** consoles are **professional tools**. Pocket operators get an **app**, not a cramped web clone.

Same **database**. Different **first** platform by **who** uses the tool.

---

*This output complies with Lupopedia Constitutional Root Rules.*
