---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260403135339"
  file_path_from_root: "lupo-docs/prd/35_mobile_native_app_separation.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/35_mobile_native_app_separation.md"
  last_modified_utc: "20260403135339"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-35-mobile-native-app-separation"
  prd_id: 35
  prd_slug: mobile_native_app_separation
  author:
    type: "actor"
    id: 102
    name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: prd
  artifact_kind: mobile_strategy
  purpose: "Mobile strategy — Two-UI (WOLFIE desktop, AI-assisted mobile web); same DB; native operator app; no responsive-only as sole UX for rich desktop surfaces"
  status: "draft"
    - prd
    - mobile
    - app
    - separation
    - ios
    - android
    - chat
    - native
    - planned_post_4_0_x
  tags:
    - tag-prd
    - tag-mobile
    - tag-app
    - tag-separation
    - tag-ios
    - tag-android
    - tag-chat
    - tag-native
    - tag-planned-post-4-0-x
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "No shared layouts; detection and /mobile/ routing"
    - to: "lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Mobile-first build for logic; desktop-second masterpiece; complements Two-UI"
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: references
      weight: 1.0
      reason: "Visitor-facing mobile browser chat parity (§7.4)"
    - to: "lupo-docs/prd/28_semantic_monitoring_widget.md"
      type: references
      weight: 0.9
      reason: "Widgets and embeds; entry-point behavior with mobile routing"
    - to: "lupo-docs/prd/18_channel_chat_display.md"
      type: references
      weight: 0.95
      reason: "Channel chat display semantics"
    - to: "craftysyntax-reference/mobile/"
      type: references
      weight: 1.0
      reason: "Legacy mobile tree — preserve client chat; relocate operator surfaces"
lupopedia.footer:
  last_verified: "20260403135339"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  next_action:
    - "Product: approve PRD scope and defer native app to post-4.0.x as stated"
    - "Align implementation folder and backlog rows when coding starts"
---

# file: PRD 35 — Mobile Native App and Responsive Separation — web_path: /lupo-docs/prd/35_mobile_native_app_separation.md

# PRD 35: Mobile Native App and Responsive Separation Doctrine

## Status: DRAFT (not yet implemented)

## Problem statement

The Crafty Syntax mobile implementation under **`craftysyntax-reference/mobile/`** mixes:

- **Client chat** for mobile web browsers (keep; preserve lineage).
- **Operator / admin** surfaces via webviews (replace over time with a **native** operator app).

Industry “responsive” patterns that rely on **one layout and different CSS** are **rejected** as the primary strategy for Lupopedia when **interaction** differs (mouse vs touch, layers vs flat, cursor-follow vs not)—see **MOBILE_SEPARATION_DOCTRINE.md** (book spread is **one** example; liquid scroll, floating layers, Eye-style widgets are others).

**Content vs UI:** canonical **content** lives in the **database** (or services) **once**. **Desktop and mobile** may use **different templates and JavaScript** without duplicating stored copy as two competing sources of truth.

**Two-UI Strategy (product default):** **desktop** = WOLFIE hand-coded (DynAPI, craft); **mobile web** = **AI / IDE–assisted** simple, touch-first UI with **WOLFIE review**—see **MOBILE_SEPARATION_DOCTRINE.md**. Native operator app remains a separate track in this PRD.

## Requirements

### 1. Device detection and redirect

- Detect mobile at an **entry point** (see doctrine).
- **Mobile web** visitors → **`/mobile/{page}`** (or equivalent under **`LUPOPEDIA_PUBLIC_PATH`**).
- **Desktop** → desktop routes.
- **No** shared layout as the only strategy.

### 2. Mobile web (preserve from Crafty where applicable)

- Keep **`craftysyntax-reference/mobile/`** client chat **patterns** for visitors using phone browsers until superseded.
- Optimize and port deliberately; do not silently remove capability without **APPROVED** supersession (**PRD 33**).

### 3. Native mobile app (new)

- **iOS** and **Android** (product timeline).
- **Operators** (admins), not visitors as the primary target.
- **Not** webview-only as the end state; proper native UI and push notifications where product requires.

### 4. Admin separation

- **Desktop admin:** full interface (e.g. **`admin.php`** route family).
- **Mobile web:** **not** the target for full operator admin — operators use the **native app** per this PRD.
- No “compromise” admin UI that is the same for desktop and mobile web.

## Out of scope for 4.0.x

- Native app development (post-4.0.x unless explicitly re-scoped).
- Full mobile web rewrite (preserve Crafty lineage where still in use).

## In scope for 4.0.x (documentation and planning)

- Document **MOBILE_SEPARATION_DOCTRINE.md** (done).
- Update **PRD 33 §7.4** with mobile client checklist.
- Plan archive/move of **admin** portions of **`craftysyntax-reference/mobile/`** (no silent deletion).

## Dependencies

- **PRD 33** — Softaculous / visitor-facing parity.
- **PRD 28** — Semantic monitoring widget behavior.
- **MOBILE_SEPARATION_DOCTRINE.md** — layout rules.

## Next steps

1. Approve this PRD at product level.
2. Track implementation rows in the current version **`TODO.md`** when work starts.
3. Execute archive/move of legacy mobile admin surfaces under a controlled change set.

---

*This output complies with Lupopedia Constitutional Root Rules.*
