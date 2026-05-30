---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/35_A-i_MOBILE_NATIVE_APP_SEPARATION.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/35_A-i_MOBILE_NATIVE_APP_SEPARATION.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/35_mobile_native_app_separation.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/mobile-native-app-separation
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_35_A-i
  title: 'file: PRD 35 - Mobile Native App and Responsive Separation - web_path: /docs/prd/35_mobile_native_app_separation.md'
  summary: null
---
# file: PRD 35 - Mobile Native App and Responsive Separation - web_path: /docs/prd/35_mobile_native_app_separation.md

# PRD 35: Mobile Native App and Responsive Separation Doctrine

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## Status: DRAFT (not yet implemented)

## Problem statement

The Crafty Syntax mobile implementation under **`craftysyntax-reference/mobile/`** mixes:

- **Client chat** for mobile web browsers (keep; preserve lineage).
- **Operator / admin** surfaces via webviews (replace over time with a **native** operator app).

Industry -responsive- patterns that rely on **one layout and different CSS** are **rejected** as the primary strategy for Lupopedia when **interaction** differs (mouse vs touch, layers vs flat, cursor-follow vs not)-see **MOBILE_SEPARATION_DOCTRINE.md** (book spread is **one** example; liquid scroll, floating layers, Eye-style widgets are others).

**Content vs UI:** canonical **content** lives in the **database** (or services) **once**. **Desktop and mobile** may use **different templates and JavaScript** without duplicating stored copy as two competing sources of truth.

**Two-UI Strategy (product default):** **desktop** = WOLFIE hand-coded (DynAPI, craft); **mobile web** = **AI / IDE-assisted** simple, touch-first UI with **WOLFIE review**-see **MOBILE_SEPARATION_DOCTRINE.md**. Native operator app remains a separate track in this PRD.

## Requirements

### 1. Device detection and redirect

- Detect mobile at an **entry point** (see doctrine).
- **Mobile web** visitors - **`/mobile/{page}`** (or equivalent under **`LUPOPEDIA_PUBLIC_PATH`**).
- **Desktop** - desktop routes.
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
- **Mobile web:** **not** the target for full operator admin - operators use the **native app** per this PRD.
- No -compromise- admin UI that is the same for desktop and mobile web.

## Out of scope for 4.0.x

- Native app development (post-4.0.x unless explicitly re-scoped).
- Full mobile web rewrite (preserve Crafty lineage where still in use).

## In scope for 4.0.x (documentation and planning)

- Document **MOBILE_SEPARATION_DOCTRINE.md** (done).
- Update **PRD 33 -7.4** with mobile client checklist.
- Plan archive/move of **admin** portions of **`craftysyntax-reference/mobile/`** (no silent deletion).

## Dependencies

- **PRD 33** - Softaculous / visitor-facing parity.
- **PRD 28** - Semantic monitoring widget behavior.
- **MOBILE_SEPARATION_DOCTRINE.md** - layout rules.

## Next steps

1. Approve this PRD at product level.
2. Track implementation rows in the current version **`TODO.md`** when work starts.
3. Execute archive/move of legacy mobile admin surfaces under a controlled change set.

---

*This output complies with Lupopedia Constitutional Root Rules.*
