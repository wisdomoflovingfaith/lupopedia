# What Gemini Learned from Crafty Syntax — Fallback Ladder Analysis

**Author**: Gemini CLI
**Date**: 2026-04-13
**Status**: ACTIVE (Restoration Phase)

---

## 1. Overview

This document formalizes the granular progressive enhancement and fallback architecture identified in the Crafty Syntax (2003-2023) codebase. Unlike modern monolithic AJAX approaches, Crafty Syntax utilized a "startup capability negotiation" system to ensure session stability and optimal performance across fragmented browser and network environments.

---

## 2. The Granular Fallback Ladder (Ordered)

The architecture consists of the following layers and side-channels, ordered from most interactive to most resilient.

1.  **Flush Path** (Server-Sent Push / `ob_flush`)
2.  **XMLHTTP Delta-Polling** (Asynchronous cursor-based updates)
3.  **Image-Based Side Channel** (Binary signaling and fallback transport)
4.  **Meta-Refresh / Location-Reload** (State-aware full-page recovery)
5.  **Manual Sync** (Static HTML ground truth)

---

## 3. Deep Architectural Analysis

### 3.1 Dual-Cursor Delta-Fetch (`HTMLtimeof` + `LAYERtimeof`)
*   **What it does:** The XMLHTTP polling loop maintains two independent timestamp cursors. `HTMLtimeof` tracks the last rendered message, while `LAYERtimeof` tracks the transient typing indicator overlay.
*   **What problem it solves:** Allows the server to return two distinct data streams in a single response without mixing permanent log data with ephemeral UI state. Each stream advances independently.
*   **Trigger:** Sent via `whattodo=messages` in every XMLHTTP poll.

### 3.2 Peoplestring Fingerprinting
*   **What it does:** A server-side hash/string representing the current state of all active users (`sessionid` + `status`). 
*   **What problem it solves:** Provides a lightweight, stateless ETag-like mechanism. The client compares its local `peoplestring` with the server's version. If they differ, a heavy UI refresh is triggered; otherwise, the client remains idle.
*   **Trigger:** Checked via `whattodo=peoplestring` before rendering user lists.

### 3.3 Image-Width Signaling / Binary Side-Channel
*   **What it does:** Loads a 1-pixel GIF from `admin_image.php`. The server returns different image widths (e.g., 55 pixels vs 1 pixel) to signal specific state changes.
*   **What problem it solves:** Bypasses JavaScript parsing and JSON/Text handling entirely. The browser's `onload` event and `img.width` property provide a universal, cross-browser binary signal for "Refresh User List" or "No Change."
*   **Condition:** Used when a lightweight "change detected" signal is needed without a full AJAX payload.

### 3.4 Typing-Status Transport (`donetyping` / `LAYER`)
*   **What it does:** Uses a dedicated `whattodo=donetyping` action. It often returns a 1x1 transparent GIF.
*   **What problem it solves:** Ensures typing indicators are transmitted via the fastest possible path. It uses an image-src fallback if the primary XMLHTTP `eval()` path fails, ensuring "is typing" status is captured even in degraded states.
*   **Mechanism:** `cscontrol.src = endpoint + ...` (Image object re-use).

### 3.5 Ping Health-Check Path
*   **What it does:** A dedicated `whattodo=ping` endpoint that returns the literal string `OK`.
*   **What problem it solves:** Allows the client to probe server availability and latency before initiating complex state-changing operations or large data fetches.
*   **Trigger:** Manual or automated health-check before session initialization.

### 3.6 EXIT / Session Termination Signal
*   **What it does:** The server can inject a synthetic `EXIT` token into the `messages` array if a user's status is no longer `'chat'`.
*   **What problem it solves:** Server-initiated session termination. The client does not need to time out; the server explicitly tells the polling loop to stop and redirect the user.
*   **Trigger:** Detected in the `ExecRes()` evaluation loop.

### 3.7 Reload Guard / Periodic Reset (`refreshes > 15`)
*   **What it does:** Tracks the number of successful XMLHTTP polls. After 15 cycles, it triggers `shouldireload()`.
*   **What problem it solves:** Prevents memory leaks and DOM bloat in long-running 2003-era browser sessions. It forces a clean-slate refresh of the UI frames while preserving session state.
*   **Condition:** Waits for the operator to stop typing before executing the reset.

### 3.8 Capability Signaling (`chattype`)
*   **What it does:** Stores the client's detected capability (`xmlhttp` vs `refresh`) in the `livehelp_users` table.
*   **What problem it solves:** Allows the server to adapt its response format and polling intervals globally for that session, ensuring it doesn't send XMLHTTP-formatted data to a client that can only handle full-page refreshes.
*   **Trigger:** Set via `setchattype` upon the first successful XMLHTTP handshake.

---

## 4. Operational Logic & Transitions

### 4.1 Active Path Selection (Base Load)
*   **Startup:** The system defaults to the most basic, guaranteed working path (Layer 5: Manual Sync / Base HTML). This ensures the user is never stuck at a "loading" screen if JavaScript or AJAX fails immediately.
*   **Ground Truth:** The initial HTML response contains the current state and recent history, establishing the base for all subsequent delta-updates.

### 4.2 Probing and Negotiation
*   **Discovery:** Immediately after the initial load, the client runtime initiates a probe for higher-capability transports (Layer 2: XMLHTTP).
*   **Verification:** The client performs a `ping` or `messages` fetch. Success proves the environment (browser, server, network) supports asynchronous delta-polling.

### 4.3 Startup Capability Negotiation and Session Lock-In
*   **Correction:** The system is NOT a dynamic bidirectional "bouncing" ladder. It is a **Startup Capability Negotiation** model followed by **Session Lock-In**.
*   **One-Way Promotion:** If the Layer 2 probe succeeds, the system promotes the session to XMLHTTP mode. This is a one-way escalation during the startup phase.
*   **Persistence:** The promoted mode is stored in the `chattype` session variable on the server.
*   **Stability:** Once the transport mode is negotiated and locked, the system remains in that mode for the duration of the session. It does not continuously switch backward and forward between transports during normal runtime.
*   **Degradation:** Falling back to a lower layer (like Layer 4: Auto-Refresh) occurs only upon terminal failure of the locked-in transport, not as a routine performance optimization.

---

## 5. Disagreements corrected from canonical analysis

1.  **Replaced "AJAX Layer" with "XMLHTTP Delta-Polling":** Corrected the terminology to reflect the specific cursor-based, `eval()`-driven polling mechanism rather than a generic modern AJAX concept.
2.  **Removed `fetch()` standardization:** Reverted to describing the original `XMLHttpRequest` and image-based `src` fallback logic.
3.  **Added Dual Cursors:** Integrated the specific `HTMLtimeof` and `LAYERtimeof` logic which was missing from the compressed version.
4.  **Added Peoplestring Fingerprinting:** Restored the fingerprinting mechanism as a dedicated layer for state-change detection.
5.  **Added Image-Width Signaling:** Corrected the "Image-Based Signaling" to specifically explain the binary width-check (55px vs 1px) pattern.
6.  **Added EXIT/Ping Signals:** Included the specific protocol tokens (`EXIT`, `OK`) used for session management and health checks.
7.  **Added Reload Guard:** Documented the `refreshes > 15` logic as a fundamental resilience and stability pattern.
8.  **Added `chattype` Persistence:** Documented how the server remembers the client's capability to tailor future responses.
9.  **Restored `eval()` Context:** Acknowledged that the system used JavaScript array literals and `eval()` for response parsing, not JSON.
10. **Removed Continuous Fall Forward:** Corrected the model from a bidirectional dynamic ladder to a one-way startup negotiation and session lock-in.
11. **Config-Driven Negotiation**: Added the understanding that the negotiation order is a configurable chain rather than a fixed hardcoded sequence.

---

## 6. Lupopedia Doctrine Alignment

### 6.1 Alignment with Doctrine Principles

The Crafty Syntax architecture aligns profoundly with the Lupopedia "Survival over Fashion" doctrine (`README_WTF.md`). Both systems prioritize execution in hostile, constrained environments (shared hosting) over modern developer conveniences.

*   **Shared Hosting Constraint:** Crafty Syntax was built for a zero-dependency world. No build steps, no Node.js, and no complex server-side requirements. Lupopedia formalizes this as a constitutional constraint.
*   **Logic in PHP:** Both systems treat the database as "dumb storage." While Crafty used some features like `AUTO_INCREMENT`, the bulk of the state management, message routing, and formatting logic lived in PHP, matching the Lupopedia mandate: "Database = storage. PHP = logic."
*   **Packed Timestamps:** Crafty's `YYYYMMDDHHIISS` (`timeof`) is the direct ancestor of the Lupopedia BIGINT timestamp doctrine. It solves the Y2038 problem and provides human-readable, deterministic sort keys.

### 6.2 Required Pattern Actions

| Pattern | Action | Reason |
|---|---|---|
| **Fallback Layering** | **PRESERVE** | Non-negotiable for resilience. The "ladder" ensures communication survives even if AJAX fails. |
| **Delta-Polling (Cursors)** | **PRESERVE** | Efficient, simple, and avoids complex state synchronization. |
| **Ping Health-Checks** | **PRESERVE** | Deterministic verification of server readiness. |
| **Packed Timestamps** | **ADAPT** | Transition from local server time to UTC BIGINT. |
| **AUTO_INCREMENT** | **ADAPT** | Replace with `IdGenerator` for deterministic, collision-free IDs across nodes. |
| **Hard Deletion** | **ADAPT** | Replace with `is_deleted` soft-delete doctrine. |
| **Manual Escaping** | **ADAPT** | Replace with PDO and named prepared statements (Mandatory for new code). |
| **Framesets** | **REMOVE** | Obsolete HTML structure. Replaced by CSS Grid "Liquid design." |
| **Raw SQL** | **REMOVE** | Security violation. All queries must be prepared. |
| **eval() Parsing** | **REMOVE** | Security risk. Replaced by deterministic JSON parsing. |

---

## 7. What lupo-layers.js teaches about Lupopedia adaptation

### 7.1 Preservation of Conceptual Ancestry

`lupo-layers.js` demonstrates the Lupopedia method of honoring legacy lineage while purging technical debt. It explicitly cites its **DynAPI / Dynamic Layer (1999)** ancestry and references the canonical in-tree predecessor (`dynlayer.js`). By doing so, it preserves the mental model of "layers" and "movable elements" that characterized 1990s DHTML, ensuring that developers (and agents) familiar with that era can instantly understand the system's intent.

### 7.2 Safe Modernization without Discarding the Model

The implementation of `lupo-layers.js` proves that "modern" does not have to mean "different architecture." It keeps the original API surface (`moveTo`, `show`, `hide`, `write`) but swaps out the underlying engine. For example, it introduces CSS transition-based sliding as an optional path, yet retains the "stepped" animation logic for precise parity with legacy expectations. This is **modernization of implementation, not modification of intent.**

### 7.3 Removal of Dangerous Mechanisms (`eval`)

A core lesson from `lupo-layers.js` is the absolute prohibition of `eval()`.
*   **Bracket Notation:** It replaces `eval(id + ' = ...')` with `window[prefix] = new LupoLayer(id)`.
*   **Function References:** It replaces string-based callbacks with actual function objects.
*   **Determinism:** By removing `eval`, the code becomes statically analyzable and secure, satisfying Rule 12 of the Lupopedia Doctrine.

### 7.4 The Lupopedia Pattern for Crafty Syntax

`lupo-layers.js` provides the exact template for how to handle Crafty Syntax’s `eval()`-based AJAX responses. We must:
1.  Keep the **Delta-Fetch cursor model** (`HTMLtimeof`).
2.  Keep the **Side-channel signaling** (Image-width checks).
3.  Replace the `eval(textstring)` response parsing with a **deterministic JSON parser**.
4.  Replace the `jsrn` array indices with **named JSON keys**.

---

## 8. Unified System Alignment (Crafty Syntax → Lupopedia)

---

## 9. Actual Startup Tests and Mode Selection in Crafty Syntax

### 9.1 Initial Page Load Path
The startup sequence begins with `live.php`, which renders a complex `frameset`. The primary decision-maker is the **`connection` frame**, which points to **`admin_connect.php`**. 
*   **Base Mode Initiation**: `admin_connect.php` loads the user’s current session data and identifies the available chat modes from `$CSLH_Config['chatmode']` (default: `flush-xmlhttp-refresh`).
*   **Immediate Redirection**: It does not render chat content itself; instead, it immediately uses `window.location.replace()` to redirect to the first mode in the negotiation chain (usually `is_flush.php` or `is_xmlhttp.php`).

### 9.2 Detection Tests
The system uses dedicated detection files to verify capabilities before promotion:
*   **XMLHTTP Detection (`is_xmlhttp.php`)**:
    *   Loads `javascript/xmlhttp.js`.
    *   Executes `loadXMLHTTP()`, which pings **`xmlhttp.php?whattodo=ping`**.
    *   If the request succeeds and returns exactly `OK`, the global variable `XMLHTTP_supported` is set to `true`.
*   **Flush Detection (`is_flush.php`)**:
    *   Attempts to send a buffer using PHP's `flush()` and `sendbuffer()`.
    *   Uses `setTimeout()` to wait for a script call (`flushworks()`) that is sent mid-request.
    *   If the browser executes `flushworks()` before the fallback timeout, flush is deemed working.

### 9.3 Promotion Test and Handshake
The moment of promotion occurs when a detection test succeeds:
*   **The `setchattype=1` Parameter**: Upon successful XMLHTTP detection, the client is redirected to `admin_chat_xmlhttp.php` with the query parameter **`setchattype=1`**.
*   **Server-Side Promotion**: The PHP script detects this flag and executes:
    `UPDATE livehelp_users SET chattype='xmlhttp' WHERE sessionid='...'`
*   **One-Way Handshake**: This is the formal handshake. The client has proven it can handle async requests, and the server now officially "remembers" this capability for the session.

### 9.4 Lock-In Behavior
*   **Session Persistence**: Once `chattype` is set in the `livehelp_users` table, it is locked. All future requests (including those from other frames or after a refresh) will honor this capability.
*   **Loop Initiation**: The client initiates the `update_xmlhttp()` polling loop (2100ms interval) and remains in this mode.
*   **Terminal Fallback Only**: The system does not "bounce" between modes. It only drops to a lower mode (like `_refresh.php`) if the primary async transport suffers a catastrophic failure.

### 9.5 Why the Current Implementation is Stuck
A naive implementation misses the **explicit negotiation frames**. By trying to do everything inside `channels/index.php`, we are missing the "Base Ground" to "Async Promotion" transition.
*   **Missing Promotion Handshake**: The current code performs a probe but does not formally tell the server to "promote and remember" the capability via a dedicated `setchattype` state change.
*   **Premature Polling**: The system attempts to start polling before the server has acknowledged the upgrade, leading to potential race conditions or missing the "Base Ground" state initialization.

### 9.6 Config-Driven Negotiation Chain
*   **Negotiation Order**: The sequence of transport tests is not fixed. It is read from the `$CSLH_Config['chatmode']` configuration string (e.g., `flush-xmlhttp-refresh`).
*   **Config Priority**: The system parses this string and attempts probes in the exact order specified by the administrator. 
*   **First Proven Mode Wins**: The negotiation stops as soon as one mode in the chain is proven successful. This first proven mode is then promoted and locked in.
*   **Implications for channels/index.php**: Transport selection should not be hardcoded to check for XMLHTTP first if the config specifies a different order. It must respect the configured negotiation sequence, perform probes accordingly, and lock the session into the first successful promoted mode.

---
