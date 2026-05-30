---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-docs/versions/4.1.3/status/channels-interface-implementation-status.md"
  status: "active"
  when_updated: "20260417000000"
  trust_tier: "staging"
  artifact_type: documentation
  artifact_kind: status_report
  channel_key: "development"
  federation_node_id: 0
  title: "Channels Interface Implementation Status — v4.1.3"
  summary: "Status report for channels/index.php UI implementation against PRD 02 v4.1.3. Covers what is done, what is pending, CraftySyntax pattern references, and open questions."
---

# Channels Interface Implementation Status — v4.1.3

**File:** `channels/index.php`
**PRD reference:** `lupo-docs/prd/02_channels_discussions.md` (v4.1.3)
**Date:** 2026-04-17
**Reporter:** Claude Code (Actor 116)

---

## Constitutional Rule (Non-Negotiable)

> A **channel** is one single chronological feed. Messages from all threads intermix in that
> feed. Thread identity is expressed only by the **full-width colored background** of each
> message row — never by tabs, columns, or grouped containers inside the feed.

All implementation decisions below are subordinate to this rule.

---

## Outer Architecture (Immutable)

The `.channel-live-wrapper` CSS grid is locked and must not be changed:

```
grid-template-columns: 1fr 280px
grid-template-rows: 1fr auto (+ auto-generated rows 3, 4)

Row 1, Col 1 : #lupo-feed           (message feed + feed-top nav)
Row 2, Col 1 : .active-target-bar   (SENDING TO: ACTOR)
Row 3, Col 1 : .actor-tab-bar       (actor tabs)
Row 4, Col 1 : .channel-controls    (textarea + send buttons)
Rows 1-5, Col 2 : .channel-sidebar  (legend + actors + files + tasks)
```

---

## Fall-Forward Transport (Canonical Pattern)

The transport follows the CraftySyntax "Base → probe → XMLHTTP lock-in → polling" pattern.
This is a constitutional invariant — do not rewrite or bypass.

```
Page load (Base mode)
  └─ 500ms delay → startupNegotiation()
       └─ fetch probe (?promote=1)
            ├─ OK  → lockIntoAsync() → setInterval(poll, 2500ms) [LOCKED]
            └─ FAIL → remain in Base mode; retry on next user action
                         └─ form.submit() fallback on AJAX failure
```

**DOM size guard:** `DOM_RELOAD_THRESHOLD = 500`. At 500 lines, `location.reload()`.
CraftySyntax equivalent: `refreshes > 15 → shouldireload()`.

**Historical reference:** `lupo-archive/legacy/craftysyntax-3.7.5/admin_chat_bot.php`
- `ExecRes()` → our `appendLine()`
- `shouldireload()` → our DOM reload guard
- `safeSubmit()` → our AJAX submit with `form.submit()` fallback
- `flag_imtyping` → our typing guard (see pending items)
- `up()` inline scroll call → our `feedEl.scrollTop = feedEl.scrollHeight`

---

## Implementation Status

### DONE

| Feature | Location | Notes |
|---|---|---|
| Fall-forward transport (Base → probe → XMLHTTP → poll) | `<script>` IIFE | Matches CraftySyntax pattern |
| `startupNegotiation()` | JS | 500ms delay, one-way promotion |
| `lockIntoAsync()` | JS | Sets interval, updates status bar |
| `poll()` with `after_time` cursor | JS | `isPollInFlight` guard present |
| `appendLine()` DOM append | JS | Increments `domLineCount` |
| `DOM_RELOAD_THRESHOLD = 500` | JS | `location.reload()` at threshold |
| AJAX submit + `form.submit()` fallback | JS | Fires on network failure |
| Enter-to-send, Shift+Enter for newline | JS | `keydown` listener on textarea |
| Active Target Bar (`SENDING TO: ACTOR`) | HTML | `#active-target-bar` with flex layout |
| Actor tab bar | HTML | 7 tabs: CURSOR/AUGGIE/GEMINI/CASCADE + LILITH/ROSE/THOTH |
| Observer vs Active tab doctrine | CSS/JS | Black tabs for observers, bright for active |
| `setActiveActorTab()` | JS | Updates label, color, hidden fields, sessionStorage |
| `syncInputToActor()` | JS | CSS variable `--input-bg` synced to actor color |
| Active Output Rule (`.last-active-message`) | JS | `applyActiveOutputRule()` after each poll |
| Dual-button logic (Send Message / Send Task) | HTML/PHP | Separate `action` values, HERMES routing |
| HERMES routing functions | PHP | `hermes_route_message`, `hermes_write_to_transcript`, `hermes_write_to_staging_toon` |
| `fetchRecentFiles()` + `renderRecentFiles()` | JS | Actor-filtered, sidebar only |
| `fetchRecentTasks()` + `renderRecentTasks()` | JS | Actor-filtered, sidebar only |
| Sidebar: Channel selector, Members list | HTML/PHP | Dropdown + actor rows |
| Sidebar: LEGEND section | HTML | Status dot color reference (A, IDLE, SLEEPING, THROTTLED, FAILED) |
| Feed-top nav (`#feed-nav`) — HTML + CSS | HTML/PHP | Actors region (pills + End Chat), Files region, Tasks region |
| Actor pills with status dot (ACTIVE/SILENT/AFK) | PHP | Dynamic from `$act['status']`; defaults to SILENT |
| `data-actor-name` attribute on each `.chat-line` | PHP | Enables reliable JS actor targeting |
| Message type CSS classes (alert/decision/question/stderr/directed/system) | PHP/CSS | `switch` on `message_type` |
| CURSOR color correction: `#1E88E5` → `#e6a817` (amber) | HTML | Per PRD canonical screenshot |
| AUGGIE color correction: `#7c4dff` → `#1565c0` (blue) | HTML | Per PRD canonical screenshot |

---

### PENDING

| Feature | Priority | Notes |
|---|---|---|
| **Feed-top nav: channel-scoped Files + Tasks** (JS fetch) | HIGH | `fetchFeedNavFiles()` / `fetchFeedNavTasks()` — must use channel_id only, NO actor_id filter. Distinct from left-panel actor-filtered fetch. |
| **Actor-specific message row colors** | HIGH | White backgrounds everywhere. PHP passes `msg_bg` but THOTH override only. Need per-actor overrides: CURSOR=blue, GEMINI=green, AURORA=teal, SYSTEM=dark gray, etc. |
| **Smart auto-scroll** (near-bottom tolerance) | MEDIUM | Current `appendLine` always force-scrolls. CraftySyntax `up()` did same. Need: skip scroll if user has scrolled up more than ~80px (reading history). |
| **Typing guard on DOM reload** | MEDIUM | CraftySyntax `shouldireload()` checked `flag_imtyping`. Our reload at threshold fires even if user is mid-compose. Add: skip `location.reload()` if textarea has content. |
| **Poll error backoff** | LOW | Consecutive poll failures currently just log. Add simple counter: after 3 failures, double interval; reset on success. |
| **End Chat button backend** | LOW | Front-end stub exists (logs to console). Needs `POST /api/chat/end` endpoint + thread archive logic. |
| **Enter Key Toggle button** | LOW | PRD 02 specifies toggle between SEND mode (Enter=submit) and DRAFT mode (Enter=newline). UI button + `$_SESSION['enter_mode']` not yet wired. |
| **Collapsible sidebar sections** | LOW | PRD specifies each section individually collapsible via `$_SESSION['left_panel_collapsed']`. Not implemented. |
| **Left panel actor click → pending tasks** | LOW | PRD: clicking an actor row shows their pending tasks. Not implemented. |
| **Per-message cross-channel send button** | LOW | PRD: every message row has a `[send to actor]` button. Not implemented. |
| **`/api/context/switch` POST on tab change** | LOW | Currently tab state is client-side only (sessionStorage). PRD specifies `POST /api/context/switch` to update `$_SESSION['active_target_actor_id']`. |

---

## CraftySyntax Pattern Library References

| Our Feature | CraftySyntax Source | Pattern |
|---|---|---|
| `startupNegotiation()` | `csgetimage()` / `tabgetimage()` initial `setTimeout` | Delayed probe after load; detect state-change via response |
| `lockIntoAsync()` | `chattype = "xmlhttp"` assignment | One-way mode promotion; never bounces back |
| `poll()` + `after_time` cursor | `ExecRes()` + `refreshes` counter | Incremental append; cursor advances each round |
| `DOM_RELOAD_THRESHOLD` + `location.reload()` | `shouldireload()` after `refreshes > 15` | Controlled memory-guard reload |
| AJAX submit + `form.submit()` fallback | `safeSubmit()` → `f.submit()` | Never lose a message; native POST as safety net |
| `appendLine()` + `scrollTop = scrollHeight` | `print $buffer_html` + `<script>up()</script>` | Stream fragment into DOM; scroll after append |
| Typing flag guard (PENDING) | `flag_imtyping` in `shouldireload()` | Suppress destructive reload while user is composing |
| Buffer-streaming (historical) | `sendbuffer()` loop in `admin_chat_flush.php` | Proto-SSE: server pushes HTML fragments; our `fetch`+`appendLine` is the modern equivalent |

---

## Open Questions

| ID | Question | Status |
|---|---|---|
| OQ-CS-01 | Should `DOM_RELOAD_THRESHOLD` be raised above 500 now that `#feed-nav` adds extra DOM nodes? The current count includes only `.chat-line` elements via `domLineCount`, so the nav nodes are not counted — confirmed safe. | RESOLVED: no change needed |
| OQ-CS-02 | `fetchFeedNavFiles` / `fetchFeedNavTasks` — do the `/api/files/recent` and `/api/tasks/list` endpoints support channel-only queries (no actor_id)? Or do they require actor_id and return all when actor_id=0? | OPEN |
| OQ-CS-03 | Actor status (`$act['status']`) — `DialogMvpService::getChannelMembers()` does not currently JOIN `lupo_agent_status`. Pills default to SILENT. When should a heartbeat JOIN be added? | OPEN |
| OQ-CS-04 | End Chat scoping: PRD says End Chat is "scoped to that actor's thread." Does this mean archive the actor's thread_id for today only, or remove the actor from the channel? | OPEN |
| OQ-CS-05 | `applyActiveOutputRule()` uses text parsing + `data-actor-name` to find the last message from the active actor. After `location.reload()` (DOM guard), the active actor is restored from `sessionStorage` — but `applyActiveOutputRule()` is only called in `setActiveActorTab()` and after polls, not on load. Add a call after the default tab is restored on page load? | OPEN — likely yes |

---

## Next Pieces

1. **Piece 2** — Actor-specific message row colors (CURSOR/GEMINI/THOTH/AURORA/SYSTEM overrides). PHP `msg_bg` is already passed; need per-actor CSS overrides and fallback logic.
2. **Piece 3** — Feed-top nav JS: `fetchFeedNavFiles()` / `fetchFeedNavTasks()` (channel-scoped). End Chat console stub → real handler.
3. **Piece 4** — Smart auto-scroll + typing guard on DOM reload + poll error backoff (CraftySyntax fidelity polish).
4. **Piece 5** — Active Target Bar: `POST /api/context/switch` wiring + tab color visual improvements.
