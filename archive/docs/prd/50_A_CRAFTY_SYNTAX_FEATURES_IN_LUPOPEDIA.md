---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/50_A_CRAFTY_SYNTAX_FEATURES_IN_LUPOPEDIA.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/50_A_CRAFTY_SYNTAX_FEATURES_IN_LUPOPEDIA.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_50_A_CRAFTY_SYNTAX_FEATURES_IN_LUPOPEDIA"
  title: "PRD 50 -- Crafty Syntax Feature Extraction (Legacy System)"
  summary: "Comprehensive extraction of Crafty Syntax Live Help features, transport models, and hidden behaviors for Lupopedia migration."
---

# PRD 50 -- Crafty Syntax Feature Extraction (Legacy System)

## Purpose
This document captures the functional and technical architecture of the legacy Crafty Syntax Live Help (CSLH) system (v3.x - v4.1.x). It serves as the canonical reference for porting and modernizing behaviors into Lupopedia, ensuring the "Survival Over Fashion" mandate is met by preserving the resilient patterns of its ancestor.

## System Overview
Crafty Syntax is a high-resilience, multi-transport real-time communication system designed to operate in heterogeneous environments with varying degrees of JavaScript and network capability. Its core philosophy is "negotiated promotion" -- starting with simple, reliable methods and upgrading to more efficient ones if the environment supports them.

## Feature Categories

### 1. Chat System
*   **Session-Based Identity:** Users are identified via a persistent `SESSIONID` (tracked in `livehelp_users`).
*   **Keystroke Monitoring (Live Typing):**
    *   Client-side `sayingwhat()` function runs every 5 seconds.
    *   If the input field contains > 2 characters, the actual content is sent to the server.
    *   **Keystroke Delivery:** Content is stored in `livehelp_messages` with `typeof='writediv'`.
    *   **Visibility:** Both operators and visitors see the other party's draft in real-time via a floating `DynLayer` (Typing Indicator).
*   **Message Buffering:** The system uses `sendbuffer()` and `OB_Stuff_To_Min()` to ensure network buffers (especially IE's 256-byte limit) are filled, allowing immediate rendering of flushed content.
*   **Unique Timestamps:** To avoid primary key collisions, the system enforces 1-second sleeps or incrementing logic to ensure every message has a unique `timeof` (YYYYMMDDHHIISS).

### 2. Transport Layer (Fall-Forward Model)
The system uses a sophisticated negotiation chain defined by `$CSLH_Config['chatmode']` (e.g., `xmlhttp-flush-refresh`).
*   **Probing Mechanism:** 
    *   `is_xmlhttp.php` and `is_flush.php` act as "probes".
    *   They attempt the high-capability mode and use a JS callback (`flushworks()`) or variable check (`XMLHTTP_supported`) to confirm success.
    *   If confirmation fails within a timeout (e.g., 5.5 seconds), the system redirects to the next fallback in the chain.
*   **Image-Based Control Channel (Low-Tech Signaling):**
    *   In non-AJAX environments, the system uses a hidden "control image" (`cscontrol`).
    *   The client requests `image.php?what=userstat` repeatedly (every 10-20 seconds).
    *   **The Signaling Hack:** The server returns different images with specific widths.
        *   **Width 55:** Signals "Operator wants to chat" -> triggers `openLiveHelp()`.
        *   **Width 25:** Signals "Layer Invite" -> triggers DHTML invite display.
*   **Digit-Based Data Encoding:**
    *   For transferring data (like an invite ID) without AJAX, the system requests three images (`ones`, `tens`, `hundreds`).
    *   The server returns `digit0.gif` through `digit9.gif`.
    *   The client reads the filename or width to reconstruct the 3-digit ID.

### 3. Live Typing Monitoring
*   **Implementation:** Hybrid JS/PHP. Keystrokes are captured via `ONKEYDOWN` and periodically synced via `image.php?what=startedtyping`.
*   **Server Logic:** Typing state is ephemeral but stored in the message table to allow unified retrieval via the standard message loop.
*   **Throttling:** Updates are limited to every 5 seconds or upon content change to minimize server load.

### 4. Operator System
*   **Channel Routing:** Operators and visitors meet on a `channel` created via `createchannel()`.
*   **Multi-Chat Management:** Operators can handle multiple visitors simultaneously, with separate channels tracked in `livehelp_operator_channels`.
*   **Operator Status:** Managed via `isonline` and `isoperator` flags in `livehelp_users`.
*   **Presence Alerting:** Sounds (`insitewav`) are triggered via hidden `<EMBED>` tags when new visitors arrive.

### 5. Visitor Tracking
*   **Path Tracking:** Every request from `livehelp_js.php` sends the current `page`, `title`, and `referer`.
*   **Visit History:** Stored in `livehelp_visit_track`, allowing operators to see the visitor's trail.
*   **Who is Online:** `admin_users_xmlhttp.php` provides a real-time list of visitors, their location, and their engagement status.

### 6. UI / Widget System
*   **Widget Injection:** `livehelp_js.php` is the primary entry point. It detects a DIV with ID `craftysyntax` and injects the chat icon and invite layers.
*   **Floating Invites:** DHTML layers that "slide" or "float" to stay visible as the user scrolls, controlled by `moveDHTML_D()`.
*   **Cross-Page Persistence:** Uses cookies and `SESSIONID` to maintain chat state across page reloads.

### 7. Fallback & Resilience
*   **Browser Compatibility:** Explicit support for NS4 (Netscape 4 layers), IE4 (document.all), and W3C (getElementById).
*   **Non-JS Fallback:** While the primary widget requires JS, the `refresh` mode handles chat for browsers with limited JS by using `<meta http-equiv="refresh">` in the chat frames.
*   **Network Failure:** Retries are handled by the loop timers (`setTimeout`) which resume after errors.

### 8. Admin / Backend
*   **Dynamic Configuration:** `config_cslh.php` and `livehelp_config` table store transport preferences, refresh rates, and UI themes.
*   **Departmentalization:** Visitors are routed to specific departments, each with its own settings, questions, and assigned operators.
*   **Transcripts:** Chats are logged and can be emailed or viewed via `view_transcript.php`.

### 9. Database Interaction Model
*   **Polling-Dominant:** The system assumes a polling model for both visitor and operator (typically 2-10 second intervals).
*   **Flat Schema:** No foreign keys; relationships are handled by matching `user_id` or `channel` in application logic.
*   **State in Messages:** Even UI commands (like `writediv` or `transfer`) are sent through the message table as "special" message types.

### 10. Hidden Behaviors & Observations
*   **Implicit Timing Logic:** The system uses `sleep(1)` to guarantee unique timestamps in high-concurrency environments.
*   **Mac-Specific Hacks:** `imagesfordumbmac` DIVs exist because legacy Mac browsers had difficulty updating cached image properties.
*   **JS-to-PHP Signaling:** Keystrokes are sent to PHP even before the user hits "Send," allowing the operator to "get ahead" of the visitor's question.

## Key Architectural Patterns
*   **Polling-First Design:** Optimized for environments where persistent connections (WebSockets) are unavailable.
*   **Progressive Enhancement:** Probes for AJAX/Flush, but stays operational on Image/Refresh.
*   **Server-Authoritative State:** All chat history and routing state resides in the database, not client-side memory.

## Mapping to Lupopedia (High Level Only)
*   **Transport Promotion:** Lupopedia's `xmlhttp-flush-refresh` chain is a direct implementation of the CSLH probe model.
*   **ASCII-Only Doctrine:** Aligns with CSLH's use of basic character sets to avoid terminal/db corruption.
*   **No FK Policy:** Lupopedia continues the CSLH tradition of application-layer relationship management.

## Open Questions
*   **DHTML Layer Performance:** How does the legacy "sliding" logic perform on modern high-DPI screens without CSS transitions?
*   **Flush Mode Compatibility:** How many modern shared hosts still support `ob_flush()` and `flush()` for Server-Sent Events?
