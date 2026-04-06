---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md"
  last_modified_utc: "20260404073004"
  when_updated: "20260404073004"
  federation_node_id: 0
  channel_id: 42
  thread_id: "chat-ui-js-shared-state"
  actor_id: 2
  actor_name: lilith
  delegation_chain: "lilith:audit"
  artifact_type: doctrine
  artifact_kind: javascript_policy
  purpose: "Chat UI JavaScript must share cross-script state; forbid IIFE isolation that breaks polling, typing, and actor chrome."
  status: active
  tags:
    - javascript
    - chat
    - shared_state
    - no_iife
    - es3
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/18_channel_chat_display.md"
      type: references
      weight: 1.0
      reason: "Channel chat UI, actor colors, transport fallbacks"
    - to: "lupo-docs/doctrine/SEMANTIC_MONITORING_DOCTRINE.md"
      type: references
      weight: 0.85
      reason: "Eye vs chat; mobile/desktop separation — different JS rules may apply to standalone widgets"
    - to: "lupo-ui/js/chat-display.js"
      type: references
      weight: 1.0
      reason: "Canonical ES3-style global constructor pattern (ChatDisplay)"
    - to: "lupo-ui/js/chat-display-legacy.js"
      type: references
      weight: 0.9
      reason: "Legacy transport tier; same shared-page discipline"
lupopedia.footer:
  last_verified: "20260404073004"
  verified_by:
    identity_type: actor
    actor_id: 2
    actor_name: lilith
  orchestrator: "cursor:root"
---

# file: Chat UI JavaScript shared state — no IIFE isolation — Lupopedia

# Chat UI JavaScript — shared state doctrine (no IIFE isolation)

## Purpose

On **operator and visitor chat surfaces** (e.g. **`live.php`**-class shells, **channel** pages, **admin** multi-chat chrome), **multiple** script units on the **same document** must **read and write shared state**: polling timers, last-sequence cursors, typing indicators, thread focus, actor color maps, CSRF tokens, and **`LUPOPEDIA_PUBLIC_PATH`**-relative endpoints.

**Immediately invoked function expressions (IIFE)** that wrap the **entire** chat UI implementation in a **private** scope **hide** that state from sibling scripts and **break** cross-script coordination.

This doctrine is **binding** for **new and refactored** chat UI JavaScript in Lupopedia unless an **APPROVED** PRD decision documents a different loading model (e.g. a **single** bundled entry that **explicitly** attaches one public API to **`window`**).

## LILITH instruction (IDE)

```yaml
instruction_for_ide: |
  In Lupopedia chat UI (live.php-class, channel chat, admin chat console), multiple
  JavaScript units on the same page MUST share variables, functions, and state.
  Do NOT wrap chat UI logic in an IIFE that hides implementation from other scripts.
  Prefer: window.Lupopedia.Chat (or equivalent), Crafty-style prefixed globals, or one
  explicit global constructor — as in lupo-ui/js/chat-display.js (global ChatDisplay).
  IIFE is acceptable only for standalone widgets that expose a deliberate public API or
  that do not participate in chat cross-script state (see exceptions below).
```

## Why IIFE breaks chat UI here

| Need | IIFE-wrapped “module” |
|------|------------------------|
| Script A starts poll timer; script B must stop or backoff | Private `pollTimer` invisible to B |
| Typing script updates shared map; display script reads it | Closed-over state |
| Legacy **`image.php`** / buffer transport handoff | Must share last cursor / format flags |
| Same-page **`ChatDisplay`** + inline hooks | Must call into the same live instance |

**Isolation is the wrong goal** for this surface. **Explicit shared scope** is required.

## Approved patterns (use one or combine)

### 1. Namespaced global object (recommended for new glue code)

```javascript
window.Lupopedia = window.Lupopedia || {};
window.Lupopedia.Chat = window.Lupopedia.Chat || {};
// Attach timers, caches, render helpers as properties — all scripts see the same object.
```

### 2. Department- or channel-prefixed identifiers (Crafty lineage)

PHP emits **unique suffixes** per department/channel so **coexisting** iframes or pages do not collide:

- `chat_pollTimer_<?php echo (int) $channel_id; ?>`
- `function chat_sendMessage_<?php echo (int) $channel_id; ?>() { ... }`

### 3. Plain globals with distinct **`lupo_` / `lupopedia_` prefixes**

```javascript
var lupopedia_chat_lastSince = '';
function lupopedia_chat_startPolling() { ... }
```

### 4. Single global constructor (current **`chat-display.js`** pattern)

**`lupo-ui/js/chat-display.js`** exposes **`ChatDisplay`** as a **global** constructor with prototype methods — **not** hidden inside an IIFE. New work should **stay compatible** with **ES3** constraints documented in that file (**no** `const`/`let`/arrow functions in those tiers unless a separate **APPROVED** modern bundle exists).

## Forbidden in chat UI (unless exception applies)

| Pattern | Reason |
|---------|--------|
| `(function () { ... })();` wrapping chat state | Hides bindings from other scripts |
| `(() => { ... })();` | Same; also violates ES3 tier for core chat |
| **Arrow functions** in **`chat-display.js`**-class paths | **ES3-compatible** policy for those files (browser JS, not PHP) |

## Exceptions (IIFE allowed)

- **Standalone embeds** that **do not** share live chat state with other page scripts and **export** one explicit API (e.g. **`window.SomeWidget.init`**).
- **Third-party** snippets studied under reverse-engineering doctrine — **do not** copy IIFE style into **Lupopedia chat** without meeting sharing rules above.
- **PRD 28** / **Eye**-class widgets under **`MOBILE_SEPARATION_DOCTRINE.md`** may use one-shot wrappers **only** if they **do not** hold chat polling/typing state that **`chat-display`** or siblings must touch.

## Constitutional fit

- **Explicit relationships in application logic** extends to **explicit JavaScript linkage**: do not **hide** state that another in-tree script must coordinate.
- **No ORM magic** analog: no **magic modules** that assume no one else touches chat state.

## Reference

| Item | Location |
|------|-----------|
| Chat UI PRD | **`lupo-docs/prd/18_channel_chat_display.md`** |
| Canonical widget | **`lupo-ui/js/chat-display.js`** (`ChatDisplay`) |
| Semantic vs chat split | **`lupo-docs/doctrine/SEMANTIC_MONITORING_DOCTRINE.md`** |

```yaml
verdict: "NO IIFE for chat UI state isolation. Use shared globals or window.Lupopedia.Chat."
next_action: "Refactors and new chat scripts attach shared state to an agreed global surface."
final_truth: "Chat scripts must share state; IIFE hides it — do not use it for that layer."
```

This output complies with Lupopedia Constitutional Root Rules.
