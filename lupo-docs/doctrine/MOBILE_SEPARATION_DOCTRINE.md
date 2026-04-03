---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md"
  last_modified_utc: "20260403140117"
  when_updated: "20260403140117"
  federation_node_id: 0
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: doctrine
  artifact_kind: mobile_ux_separation
  purpose: "Two-UI strategy + runtime routing; Eye widget desktop vs mobile (PRD 28); build order in WOLFIE_WORKFLOW_DOCTRINE; shared DB; PRD 33 / 35"
  status: active
  tags:
    - mobile
    - separation
    - book_layout
    - dynapi
    - responsive_forbidden
    - doctrine
    - ux
    - two_ui_strategy
    - wolfie_desktop
    - ai_mobile
    - eye_widget
    - prd_28
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/35_mobile_native_app_separation.md"
      type: references
      weight: 1.0
      reason: "PRD for mobile web vs native operator app; out-of-scope items"
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: references
      weight: 1.0
      reason: "Visitor-facing mobile browser chat parity (§7.4)"
    - to: "lupo-docs/prd/28_semantic_monitoring_widget.md"
      type: references
      weight: 0.85
      reason: "Embeds and widgets coexist with mobile routing at entry points"
    - to: "craftysyntax-reference/mobile/"
      type: references
      weight: 0.95
      reason: "Legacy Crafty mobile tree — preserve client chat; relocate operator surfaces per product plan"
    - to: "lupo-includes/js/dynapi/"
      type: references
      weight: 0.9
      reason: "DynAPI / dynlayer — floating layers and motion; not collapsible into generic responsive-only CSS"
    - to: "craftysyntax-reference/javascript/dynapi/"
      type: references
      weight: 0.85
      reason: "Reference dynapi sources for page-turn and layer metaphors"
    - to: "lupo-ui/"
      type: references
      weight: 0.85
      reason: "Shared UI asset roots; desktop vs mobile surfaces split by product routing"
    - to: "lupo-docs/LESSONS_LEARNED_FROM_THE_WILD_WEST.md"
      type: references
      weight: 0.95
      reason: "Hand-coded desktop UI policy; IDE integrates WOLFIE templates"
    - to: "lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Mobile-first build for logic, desktop-second for art; complements runtime rules here"
lupopedia.footer:
  last_verified: "20260403140117"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  next_action:
    - "Implement entry-point detection and /mobile/ routing; optional WOLFIE_WORKFLOW (mobile skeleton first, desktop masterpiece second)"
    - "Archive or relocate mobile admin webviews from craftysyntax-reference/mobile/ per PRD 35"
---

# file: MOBILE_SEPARATION_DOCTRINE — web_path: /lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md

# Mobile separation doctrine

## The core principle

> *Desktop is desktop. Mobile is mobile. They do not share layouts.*

No responsive CSS as the only mobile strategy. No media queries pretending to be a full mobile product. No “one layout to rule them all” that loads desktop and mobile rules in one DOM.

This is **not** a special rule only for “book” pages. **Any** feature where **interaction or structure** differs by device (mouse vs touch, layers vs simplified view, follow-cursor vs not) should be implemented as **separate desktop and mobile UIs** that read the **same content from the database**—not as one DOM with two stylesheets.

## Content once, two UIs

- **Data / copy / messages** — Single source (database, API, config). **Not** two copies of the same article in different tables.
- **HTML / JS / motion** — **May and often must** differ: desktop template + scripts vs **`/mobile/...`** template + scripts.
- **Duplication** is in **presentation and behavior**, not in **canonical content**, when product requires different interaction models.

### Development workflow (build order)

For **new consumer / visitor-facing** features, the preferred **implementation order** is: **mobile web UI first** (functional skeleton), then **desktop masterpiece** (WOLFIE hand-coded)—see **WOLFIE_WORKFLOW_DOCTRINE.md**. **Admin / operator** surfaces are **desktop-first** (same doc: **The admin exception**). That is **how** you build; **this** document still governs **runtime** (detection, **`/mobile/`** vs desktop routes, Two-UI ownership).

## The Two-UI Strategy: WOLFIE desktop, AI mobile

### The principle

> *Desktop gets the masterpiece. Mobile gets the interface.*

**Desktop (WOLFIE):** hand-coded templates (Notepad-class workflow), **DynAPI**, liquid layouts, mouse-linked widgets, book spreads, floating layers—**intentional, dense, desktop-native**.

**Mobile web (IDE / AI):** **generated or assisted** simple markup and scripts—**touch-first**, minimal motion, **no mouse-dependent** behaviors. **Utility**, not a shrunk copy of the desktop DOM.

This is a **division of labor**, not a value judgment: mobile users need **fast, clear** surfaces; desktop users get the **full** experience WOLFIE builds.

### Why

| Reason | Explanation |
|--------|-------------|
| **Desktop is the canvas** | Decades of desktop-first craft; complex motion and layering belong there. |
| **Mobile is utility** | Small screen, touch, no hover—**get the job done** with clear controls. |
| **Time and focus** | Hand-coding every mobile pixel is optional; **simple** AI-generated mobile UI is **allowed** when WOLFIE approves. |
| **Clean ownership** | Desktop assets = WOLFIE’s patterns; mobile web = **explicitly** simpler generated or assisted code—**not** mixed into desktop files. |
| **Same data** | Database and services stay **one** source; only **templates/JS** differ by route. |

### Division of labor (product default)

| Surface | Author | Character |
|---------|--------|-----------|
| **Desktop** | WOLFIE (hand-coded) | Liquid layouts, DynAPI, book spreads, eye-follow-mouse, floating pen layers, scroll choreography—**art and expression** |
| **Mobile web** | AI / IDE **with WOLFIE review** | Single-column, large tap targets, short forms, **no** hover or cursor tracking—**functional** |

**Native operator app** remains **PRD 35** (separate from this split).

### Workflow (conceptual)

| Step | Desktop | Mobile web |
|------|---------|------------|
| Author | WOLFIE | AI generates; WOLFIE **reviews** and **merges** |
| Output | Hand-written PHP/HTML/CSS/JS in product paths (e.g. themes, `lupo-ui/`, page templates) | Simpler files under **`mobile/`** or parallel routes—**no** DynAPI complexity unless **APPROVED** |
| Review | Human (WOLFIE) | WOLFIE approves or rejects; IDE **does not** ship mobile UI without product sign-off |

### Detection and routing (sketch)

Use **explicit route mapping**—do **not** rely on `basename($_SERVER['REQUEST_URI'])` alone (see implementation sketch below). Conceptually:

```php
if (lupo_is_mobile_request()) {
    // Serve mobile web UI (AI-assisted / simple templates)
    $mobile_file = get_mobile_template_for_route(/* product mapping */);
    require $mobile_file;
} else {
    // Serve WOLFIE hand-coded desktop UI
    $desktop_file = get_desktop_template_for_route(/* product mapping */);
    require $desktop_file;
}
```

Paths must use **filesystem** constants (e.g. **`LUPOPEDIA_ABSPATH`**, **`LUPOPEDIA_PATH`**) per project—not URL strings in `require`.

### What desktop gets (WOLFIE)

- Liquid layouts that respond to **window** geometry.
- Book spreads and DynAPI-style motion where product requires.
- Mouse-following or hover-driven widgets (e.g. Eye-class surfaces).
- Floating layers, “pen” writing, scroll/unscroll choreography.
- **IDE:** **integrate only**—see **LESSONS_LEARNED_FROM_THE_WILD_WEST.md** section **7** and **AGENTS.md** hand-coding policy.

### What mobile web gets (AI / IDE)

- Touch-optimized controls; single-column flow.
- Short forms; no **required** hover or mouse tracking.
- **No** complex desktop-only animation as the **default**; optional subtle motion only if it does not depend on cursor position.
- **WOLFIE** remains **accountable** for merge and behavior—**deterministic** shipping is still application-owned.

### Constitutional alignment

| Discipline | Two-UI Strategy |
|------------|-----------------|
| Different environments | Desktop vs mobile **explicit**; no one DOM pretending to be both. |
| Deterministic shipping | Desktop code is **reviewed** human craft; mobile code is **reviewed** before release. |
| Explicit over implicit | Routes choose **which** template family—not `@media` as sole strategy. |
| Ownership | WOLFIE **owns** desktop UI; mobile web is **AI-assisted** but **merged under WOLFIE’s** repo rules. |

**Note:** “AI-generated mobile” does **not** relax **PDO_DB**, **auth**, or **security** doctrine—only the **presentation** layer may be generated; **server** logic stays in PHP under project rules.

### Voice (WOLFIE)

Desktop is the canvas; mobile is what fits in a pocket. **Masterpiece on desktop; interface on mobile.** Letting AI help with **simple** mobile markup while **owning** desktop craft is **division of labor**, not laziness.

## The rule

| Concern | Requirement |
|--------|----------------|
| **Detection** | Mobile vs desktop at an **entry point** (User-Agent and/or agreed product signals). |
| **Redirect** | If mobile web visitor **then** serve **`/mobile/...`** page (or equivalent under `LUPOPEDIA_PUBLIC_PATH`). If desktop **then** serve **`/...`** desktop pages. |
| **Forbidden** | Same page template with only different CSS as the **sole** mobile strategy (“same DOM, different CSS”). |

Subdirectory installs must prefix paths with **`LUPOPEDIA_PUBLIC_PATH`** (e.g. `/lupopedia/mobile/chat.php`) — never hardcode site root.

## Why

| Approach | Result |
|----------|--------|
| **Shared layout with responsive CSS only** | Bloated payloads, compromise on both sides. |
| **Separate mobile pages** | Optimized for mobile, intentional markup. |
| **Separate desktop pages** | Full feature set without mobile constraints. |
| **Detection then redirect** | Clean separation before heavy layout work. |

## When “responsive” is not enough (examples, not an exhaustive list)

The **book-style spread** is **one** example. The same doctrine applies to **every** feature where desktop and phone are different **interaction** surfaces, including:

| Example area | Desktop-oriented behavior | Why mobile is not “the same with CSS” |
|----------------|----------------------------|----------------------------------------|
| **Book / spread UI** | Liquid “pages”, borders, two-page metaphor, DynAPI-style motion | Touch and viewport break the metaphor if you only shrink CSS. |
| **Liquid scroll / scripted layout** | JS that **scrolls and unscrolls** regions, coordinates **floating layers** | Reflow or `@media` does not replace a different **script + DOM** contract. |
| **Floating pen / layered writing** | Text drawn or positioned in **floating div layers** with desktop timing | Mobile needs a **simpler** path (fewer layers, different gestures)—same **words** from DB, different **wiring**. |
| **Eye / widget follows mouse** (e.g. semantic **Eye**, PRD 28) | Cursor-tracking, hover affordances | **There is no mouse to follow** on a phone; mobile gets a **different** control model (tap, simplified panel, static placement)—not a hidden desktop script. |

**Principle:** if the **correct** desktop UX depends on **mouse, hover, or dense layered chrome**, the **correct** mobile UX is usually a **different page and different JS**, still fed from the **same** backend data.

### What industry “responsive” assumes

Many sites use **one HTML structure** and **different CSS** via `@media`: nav, content, footer, sidebar. **Reflow** and **hide/show** carry the mobile experience.

That is fine for **simple** pages. It fails when **behavior** (not just width) changes.

### Why responsive-only CSS breaks interaction-rich UIs

| Requirement | Responsive-only (same DOM) | Separate mobile page (different DOM / JS) |
|-------------|------------------------------|----------------------------------------|
| Page borders / spread | Shrunk, hidden, or inconsistent | Redrawn or omitted by design |
| Turn / layer animation | Disabled or fragile | Replaced or simplified for touch |
| Liquid “pages” / scroll choreography | Reflow destroys timing | Rebuilt as sections or screens |
| Floating layers / pen metaphor | Clumsy on touch | Simpler structure + scripts |
| Cursor-following Eye / hover | Nonsense without a cursor | Different UI; same **data** where applicable |

### The rule (structure or interaction changes ⇒ different page)

> *If **layout structure** or **primary interaction** (mouse vs touch, layers vs flat) must change materially, that is a **different page** — not the same page with different CSS.*

- **Industry responsive:** same DOM, different CSS.
- **Lupopedia (interaction-rich):** different DOM and often different JS — typically **detection + redirect** to **`/mobile/...`**, while **reusing** server-side data.

**Simple** layouts (blog body, plain form, basic dashboard): limited responsive CSS **may** be acceptable **when** DOM **and** interaction stay the same.

**Any** desktop-only interaction (mouse follow, hover chains, heavy layers): assume **separate mobile page** unless an **APPROVED** product decision documents a narrow exception.

### The test (for agents and implementers)

1. Ask: **Does interaction or structure change between desktop and mobile?** (not only width)  
   - If **yes** → **separate mobile page** (different DOM/JS); do not rely on `@media` alone.  
   - If **no** → responsive CSS **might** suffice.

2. Ask: **Would “hide some nodes and reflow text” preserve the product?**  
   - If **no** → **separate mobile page.**

### Desktop vs mobile (illustrative)

| Surface | Desktop | Mobile |
|---------|---------|--------|
| DOM / JS | Spreads, liquid scroll, floating layers, mouse-linked widgets | Flatter layout, touch-first, no fake mouse cursor |
| Data | From DB / services | **Same** sources; different templates and scripts |

These are **two UIs** for the same **content or feature**, not one template with two stylesheets.

### What agents must not do

- Treat **book layout** as the **only** case for separation.
- Assume **one layout fits all** breakpoints when **interaction** differs.
- Use **`@media` as the only** mobile strategy when **DOM or JS** must differ.
- **Hide** large subtrees instead of **serving** a mobile-specific page.
- **Duplicate canonical content** in two databases to “solve” mobile—prefer **one content store**, **two presentations**.

### What agents must do

- **Detect** at entry (or product-defined equivalent) and **route** to **`mobile/`** when the product defines a mobile surface.
- **Design** mobile markup and scripts for **touch and no mouse**, not as a shrunk desktop.
- Preserve **DynAPI / layer** and desktop-only behaviors on **desktop routes**; provide **simpler** mobile routes where appropriate.
- **Wire both** to the **same** data layer unless product explicitly requires otherwise.

### Alignment with root rules (explicit, not vague)

| Root discipline | How desktop/mobile separation honors it |
|-----------------|----------------------------------------|
| No assumption of environment | Do not assume desktop; detect and route. |
| Deterministic surfaces | Mobile route serves mobile DOM/JS; desktop route serves desktop DOM/JS. |
| Explicit over implicit | Redirect or router map — not “mobile” by hidden CSS only. |
| No fake state | Avoid large `display:none` trees standing in for a real mobile page. |

## The Eye widget (PRD 28): desktop vs mobile

Canonical product spec: **[PRD 28 — Semantic Monitoring Widget (The Eye)](lupo-docs/prd/28_semantic_monitoring_widget.md)**. This section states **how** Two-UI applies to The Eye: **same tracking intent and backend contracts**, **different presentation** by device.

### Desktop (WOLFIE hand-coded)

- **Mouse-linked eye** — pupil / layer motion follows **cursor** position (no cursor on phone, so this stays on **desktop** routes).
- **Z-layered assets** — eyeball, lids, back-of-eye stack (sprites under product image dirs per PRD 28).
- **DynAPI / layer motion** — floating, smooth updates; **art** layer.
- **Floating chrome** — bottom **navigation** strip and **top collections** bar as specified in PRD 28 (desktop-class layout).

### Mobile web (AI-assisted or simplified templates)

- **No mouse follow** — use **static** or **simple** animated eye (GIF / PNG sequence / CSS), not cursor tracking.
- **Same behavioral job** — page presence, navigation context, semantic monitoring goals aligned with PRD 28 (exact transport: **`lupopedia_js.php`** / **`livehelp_js.php`**-class pipelines per product wiring—not a second incompatible protocol).
- **Touch-first chrome** — bottom nav and collections **bar** equivalents: **larger targets**, no hover-only affordances.
- **Utility first** — lightweight DOM and JS; **no** full desktop DynAPI stack as the default on mobile web.

### Shared backend (one truth)

Both surfaces must converge on the **same** server-side semantics: session / identity, page and path semantics, referrer and visit records—**PDO_DB**, **no** duplicate business rules. Payloads and endpoints are **product-defined** (see PRD 28, **§6** root scripts, and **`install_new_lupopedia.sql`** for `lupo_*` visit/path tables). Do **not** invent a parallel “shadow” tracker with different column meanings.

### Principle

> *Same data and same monitoring obligations. Different presentation. Appropriate for the device.*

Desktop: **art that tracks**. Mobile: **utility that tracks**. Both **see** the same obligations; only **input** and **motion** differ.

## The Crafty Syntax precedent

The existing **`craftysyntax-reference/mobile/`** tree shows **device-oriented** separation:

- **Keep** mobile **client** chat for phone browsers (e.g. `mobile/chat.php`-class flows) where product still serves visitors.
- **Do not** treat **operator admin** in that tree as the long-term model: operator tools belong in a **native app** (see **PRD 35**), not as the only webview stack.

The mistake was **mixing** client and operator admin in one directory without a clear product split; **separation by device** was directionally correct. Those mobile entry points were **different pages** with **different DOM**, not the same templates with smaller CSS.

## Implementation sketch (documentation only)

PHP must remain **5.6-compatible** in core paths; examples use `isset()` for portability.

```php
function lupo_is_mobile_request() {
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $mobile_keywords = array('Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone');
    foreach ($mobile_keywords as $keyword) {
        if (stripos($ua, $keyword) !== false) {
            return true;
        }
    }
    return false;
}
```

**Redirect** is product-defined: map a desktop request path to a **`mobile/`** counterpart (often under **`LUPOPEDIA_PUBLIC_PATH`** + `mobile/`). Do **not** rely on `basename($_SERVER['REQUEST_URI'])` alone for every route; use an explicit map or router.

## Directory shape (conceptual)

```
webroot/
├── index.php              # Desktop entry
├── chat.php               # Desktop chat
├── admin.php              # Desktop admin
├── mobile/
│   ├── index.php          # Mobile entry (separate layout)
│   └── chat.php           # Mobile client chat (Crafty lineage preserved)
└── app/                   # Native mobile app (future; not webview-only)
```

## What is forbidden

| Practice | Why forbidden |
|----------|----------------|
| **`@media (max-width: …)` as the only mobile strategy** | Desktop assets still load; hidden nodes still cost. |
| **Same DOM, different CSS** | Easy to ship “responsive” without true mobile UX. |
| **Shared admin UI for desktop and mobile web** | Operator work needs full desktop; mobile web admin is **not** the target for operators (see **PRD 35**). |

## What is required

| Requirement | Why |
|-------------|-----|
| Device detection at entry point | Redirect before heavy layout. |
| Separate mobile page files | Clean, testable, intentional. |
| Preserve Crafty mobile **client** chat where still used | Working path; improve only with **APPROVED** supersession. |
| Native mobile app for operators | Webviews are not the end state for operator tooling. |

## Constitutional and PRD alignment

- **PRD 33 §7.4** — Visitor-facing parity includes **mobile browser** chat (see checklist item).
- **PRD 33 §5.1** — Shared hosting: detection must work without vendor-specific APIs.
- **PRD 35** — Native app scope, archive/move of legacy mobile admin surfaces.

---

*This output complies with Lupopedia Constitutional Root Rules.*
