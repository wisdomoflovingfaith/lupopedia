---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: GEMINI.md
  web_path: https://www.lupopedia.com/lupopedia/GEMINI.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/gemini-md.toon
  atoms_toon: null
  transcript_jsonl: 0/development/gemini-md-guide
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: documentation
  prd_cluster: null
  title: GEMINI.md -- Lupopedia Gemini Agent Brief
  summary: 'Lupopedia operating contract for Gemini: core philosophy, Crafty Syntax lineage, transport model, UI rules, ASCII-only data, zero-dependency runtime.'
---
# GEMINI.md -- Lupopedia Operating Contract

## 1. Core Lupopedia Philosophy
*   **Survival Over Fashion:** Prioritize execution in hostile, constrained environments (shared hosting) over modern developer conveniences. Fulfill the "Zero external dependencies" mandate.
*   **Shared Hosting Reality:** Must run without Composer, Node.js, `exec()`, or build steps in shipped code. Zero dependency runtime.
*   **Database = Storage Only:** The database is dumb storage. No Foreign Keys (FKs), no triggers, no stored procedures. logic belongs in the application layer (PHP).
*   **Deterministic Behavior:** No magic, no guessing, no hidden state. Results must be predictable and verifiable.
*   **ASCII-Only Data:** This is a constitutional mandate. All text in this repository MUST be strictly ASCII (U+0020 to U+007E). No emojis, smart quotes, em-dashes, or Unicode arrows.

## 2. ASCII-ONLY DOCTRINE (Constitutional)
**This is a constitutional directive. There are NO EXCEPTIONS ANYWHERE.**

All text in this repository MUST be strictly ASCII (code points U+0020 through U+007E):
- A-Z, a-z, 0-9, space, and basic punctuation only
- NO emoji or pictographic symbols
- NO Unicode arrow glyphs (use ASCII sequences such as ->, <->, <-, ^, v)
- NO box drawing characters
- NO curly quotes (use straight ' and " only)
- NO em dash or en dash characters (use -- or a single - as appropriate)

**Why:** ASCII survives terminals, IDEs, hosts, and databases without silent corruption.

## 3. Crafty Syntax Lineage
*   **Proven Ancestor:** Crafty Syntax (2003-2023) is the functional and architectural ancestor of Lupopedia.
*   **Preserve Behavior and Intent:** Honor the legacy patterns that enabled survival for 20 years. Adapt and modernize implementation without modifying ancestral intent.
*   **Adaptive Migration:** Old working systems (like the delta-fetch cursor model) are refined and hardened, not discarded as obsolete junk.

## 4. Transport / Communication Model
*   **Startup Capability Negotiation:** At session start, the client runtime probes for the highest-capability transport supported by the environment.
*   **Configured Negotiation Chain:** Probes are attempted in the exact order defined by the `$CSLH_Config['chatmode']` (e.g., `xmlhttp-flush-refresh`).
*   **One-Way Promotion:** Upon a successful probe (e.g., a 200 OK from a ping), the system promotes the session to that transport mode.
*   **Session Lock-In:** The promoted mode is stored in the server session (`chattype`) and locked. The system does not continuously bounce between modes during normal operation.

## 5. Fallback / Negotiation Chain
*   **Resilience Ladder:**
    1.  **Flush Path:** (Server-Sent Push)
    2.  **XMLHTTP Delta-Polling:** (Asynchronous cursor-based updates)
    3.  **Meta-Refresh:** (State-aware full-page recovery)
    4.  **Manual Sync:** (Static HTML ground truth)
*   **First Proven Mode Wins:** The negotiation sequence stops at the first transport that proves successful.
*   **Terminal Fallback:** Degradation to lower layers occurs only upon catastrophic failure of the locked-in mode.

## 6. UI / PRD 02 Rules
*   **Single-Column Command Center:** All messages from agents and humans are interleaved in a single chronological stream.
*   **Forbidden UI Patterns:** No bubbles, no side-by-side agent columns, no grouping/collapsing by sender, no threaded nesting.
*   **Strict Interleaved Timeline:** Strict order from oldest (top) to newest (bottom).
*   **Individual Message Lines:** Every message is a single line with a timestamp and clear sender identification.
*   **Thread-Specific Colors:** Background and text colors are assigned per thread, not per agent.

## 7. Layer / DHTML Adaptation Pattern
*   **Conceptual Ancestry:** Preserve the mental model of "layers" and "movable elements" from the DynAPI era.
*   **Modern API Surface:** Keep API compatibility (`moveTo`, `show`, `hide`, `write`) but swap out the engine for Vanilla JS and CSS transitions.
*   **Absolute Prohibition of `eval()`:** Replace all `eval()`-based logic with bracket notation (`window[prefix]`) and function references.
*   **Secure Implementation:** Modernize the underlying code while maintaining precise parity with legacy behavioral expectations.

## 8. Database / Schema Rules
*   **Exact Schema Alignment:** Follow `lupo-database/lupopedia/json/*.json` schemas exactly. No extra keys, no missing required keys.
*   **No NULL Violations:** Pass explicit `NULL` for nullable columns; never use `0` as a substitute.
*   **ID Doctrine:** No `AUTO_INCREMENT`. Use `IdGenerator::generate()` for all primary keys.
*   **Packed Timestamps:** Use 14-digit UTC BIGINT `YYYYMMDDHHIISS` exclusively. No Unix epochs.
*   **No Hidden DB Logic:** No triggers or complex constraints. Consistency is handled by the application layer via idempotent operations.

## 9. channels/index.php Behavior Contract
*   **Base Ground Load:** Initial load is synchronous, establishing the ground truth state.
*   **Startup Probe:** Immediate one-time probe for higher transport capability.
*   **One-Way Promotion & Lock-In:** Promote session to XMLHTTP if proven; lock in capability for the session duration.
*   **Incremental Polling:** Use a cursor (`after_time` / `last_message_id`) to fetch only new deltas.
*   **Continuous Feed:** Append messages to the DOM; avoid full page refreshes during normal operation.
*   **Dropdown Integrity:** The channel dropdown must show all available channels.
*   **Auto-Join:** Selecting a channel auto-adds the current actor as a member via `ensureChannelMembership`.
*   **Sidebar Scoping:** The members panel must be strictly channel-scoped.

## 10. Things You Must Never Do
*   **Do Not Redesign Architecture:** Respect the established "Survival over Fashion" constraints.
*   **Do Not Introduce Frameworks:** No React, No Tailwind, No Vue. Vanilla JS and hand-coded CSS/PHP only.
*   **Do Not Abstract Simple Logic:** Avoid complex abstractions over working, simple logic.
*   **Do Not Modernize Away Intent:** Do not discard proven behavior just because the implementation seems "old."
*   **Do Not Hide Logic in DB:** Keep logic visible and editable in the PHP application layer.
*   **Do Not Treat Crafty Syntax as Junk:** It is the functional bedrock of the system.

## 11. Implementation Strategy
*   **Make it Work First:** Smallest possible working change.
*   **Verify Runtime Behavior:** Empirical validation over theoretical correctness.
*   **Surgical Execution:** One subsystem at a time. No giant task piles.
*   **Write the Why:** Clarity over convention. Explain the rationale behind the code.
