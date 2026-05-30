# Session Report — 2026-04-13 00:30 UTC
## Gemini Operating Contract & Channel UI Refinement

**Actor:** GEMINI (111)
**Facilitated by:** Gemini CLI
**Focus:** Constitutional Continuity and PRD 02 Compliance

### 1. Observations & Learnings
*   **Architectural Ancestry:** The connection to DynAPI/DHTML "Dynamic Layers" in `lupo-layers.js` is a critical "missing link" in understanding how Lupopedia handles UI. It explains the preference for absolute positioning and manual DOM manipulation over modern layout engines.
*   **Transport Negotiation:** The "Capability Negotiation" model (probing for XMLHTTP before locking in) is a sophisticated resilience pattern. It ensures the system remains functional even if the server-side `flush()` or `ob_flush()` is blocked by a proxy or shared hosting limitation.
*   **Security Silos:** `DialogMvpService::fetchLastThreadMessages` enforces a strict specialist silo (THOTH/ROSE only). This is a vital security invariant that must be preserved to prevent data leakage between unrelated agent tasks.
*   **Dropdown Gap:** The absence of a channel dropdown in `channels/index.php` was a major barrier to multi-channel collaboration. URL-hacking is not a sustainable UX for a "Command Center."
*   **Operating Contract:** The creation of `GEMINI.md` solves the "session amnesia" problem by providing a permanent, non-negotiable set of operating rules that transcend individual task contexts.

### 2. Troubles & Obstacles
*   **PDO/HY093 Friction:** Encountered SQLSTATE[HY093] due to mixing literal values (e.g., `0`) with named parameters in PDO prepared statements when `ATTR_EMULATE_PREPARES` is false. Resolved by strictly using named parameters for all values in the insert array.
*   **Schema Drift:** Identified missing columns in `lupo_dialog_read_log` (specifically `last_read_created_ymdhis`) that were requested by the task but were not present in the JSON schema. Logged as `OQ-04` in `open_questions.md`.
*   **Context Volume:** Synthesizing multiple large source documents into a single `GEMINI.md` required careful filtering to maintain "high-signal" content without exceeding token efficiency limits.

### 3. Strategy Alignment
*   **Survival Over Fashion:** All changes to `channels/index.php` were implemented in Vanilla PHP/JS with zero external dependencies, fulfilling the core mandate.
*   **Deterministic Behavior:** Added explicit `IdGenerator::generate()` calls for all new primary keys, removing `MAX+1` race conditions.
*   **Interleaved Timeline:** Maintained the strict single-column chronological feed in the UI, rejecting any "bubble" or "nested" chat patterns.

### 4. Status
*   **Channels UI:** COMPLETED (Dropdown, Auto-join, Sidebar Scoping).
*   **GEMINI.md**: COMPLETED (Operational Contract).
*   **Delta Analysis**: COMPLETED (Gemini vs Claude).
*   **Backlog**: UPDATED (`todo.md`, `plan.md`).
