---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/50_B_CRAFTY_SYNTAX_MODIFIED_FEATURES.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/50_B_CRAFTY_SYNTAX_MODIFIED_FEATURES.md"
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
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_50_B_CRAFTY_SYNTAX_MODIFIED_FEATURES"
  title: "PRD 50_B -- Crafty Syntax Modified Features in Lupopedia"
  summary: "Documentation of intentional divergences and extensions from legacy Crafty Syntax in Lupopedia 4.1.4."
---

# PRD 50_B -- Crafty Syntax Modified Features in Lupopedia

## Purpose
The purpose of this document is to record the intentional divergences, extensions, and architectural evolutions from the legacy Crafty Syntax system into Lupopedia 4.1.4. This ensures that while baseline compatibility is maintained for the May 1 delivery, the platform's advanced orchestration capabilities are clearly distinguished from its ancestral live-help features.

## Methodology
The comparison was performed by analyzing legacy Crafty Syntax code (located in `craftysyntax-reference/`) against the current Lupopedia implementation, Constitutional mandates (`GEMINI.md`), and the 4.1.x PRD suite.

## Modified Feature Set

### Channel System Evolution (CRITICAL)
**Crafty Syntax Behavior**
- Single operator view (one operator row per human).
- Multiple visitor chats handled in a tabbed interface (one tab per session).
- All conversations restricted to a simple operator-visitor pair in a private channel.
**Lupopedia Behavior**
- Actor-based system (Actor-ID is the primary identity).
- Multi-context orchestration where channels are generic communication pipes.
- Support for threads within channels to isolate specific conversation contexts.
- Multi-actor routing using `to_actor_id` (NULL = broadcast).
**What Changed**
- Shift from "Operator-Visitor Chat" to "Multi-Actor Threaded Communication."
- Channels are no longer just "chats"; they are semantic contexts where humans and agents interact.
**Why It Changed**
- To support full AI orchestration on top of the human live-help baseline, allowing multiple agents and humans to collaborate in a single thread.

### Actor / Agent Layer (NEW CAPABILITY)
**Crafty Syntax Behavior**
- Human operators only (mapped 1:1 to users).
- Visitors are transient rows in a flat user table.
**Lupopedia Behavior**
- Polymorphic Actor system: Humans, Agents, and Hybrids.
- Actors are department-scoped; multiple Auth Users can act as the same Actor if they share a department.
- Agents are defined by templates (`lupo_agents`) and instantiated as Actors.
**What Changed**
- Extended from a human-only "operator" model to a multi-type "actor" system with hierarchical resolution (Auth User -> Department -> Actor).
**Why It Changed**
- To provide a foundation for AI orchestration while preserving the ability for humans to step in and fulfill the legacy live-help role using a shared persona.

### Semantic Bar vs LiveHelp Widget
**Crafty Syntax Behavior**
- Static status image widget (`online.gif` / `offline.gif`).
- Simple "getstate" polling to update the icon.
- Opens a dedicated, isolated chat window.
**Lupopedia Behavior**
- Semantic Navbar (PRD 21) and Monitoring Widget ("The Eye", PRD 28).
- Rich, graph-driven interface fetching references, contexts, edges, and Q&A.
- Integrated into the host page as a non-intrusive semantic layer.
**What Changed**
- From a simple availability icon to a dynamic, context-aware semantic navigation and monitoring surface.
**Why It Changed**
- To evolve the user experience from "seeking help" to "navigating a semantic knowledge graph," with chat being just one possible action.

### Transport & Communication Layer
**Crafty Syntax Behavior**
- Dynamic chain probing (flush-xmlhttp-refresh) performed on every page load or session start.
- No permanent lock-in; system probes continuously.
**Lupopedia Behavior**
- Startup Capability Negotiation with One-Way Promotion.
- Session Lock-In: The highest proven transport is stored in the server session (`chattype`) and locked for the duration.
- Explicit Constitutional requirement for 14-digit UTC BIGINT timestamps.
**What Changed**
- Standardized promotion and lock-in of the most capable transport to ensure deterministic performance.
**Why It Changed**
- To eliminate the overhead of continuous probing and ensure a stable connection state for long-running orchestration sessions.

### Session & Identity Model
**Crafty Syntax Behavior**
- `SESSIONID` mapped directly to a visitor or operator row in `livehelp_users`.
- Identity is tied to the browser session and a specific database row.
**Lupopedia Behavior**
- Decoupled Auth Identity (who is logged in) from Runtime Identity (the Actor being portrayed).
- `SessionConfig` (PRD 44) manages transcript paths and orchestration state.
- Visitor identity persists through a more robust session-to-actor mapping.
**What Changed**
- Moved from a tightly coupled "user-is-the-operator" model to a flexible "auth-user-portrays-an-actor" model.
**Why It Changed**
- To allow for "persona switching" and to enable multiple humans or agents to operate the same high-level identity (e.g., "The Support Team").

### Data & Time Handling
**Crafty Syntax Behavior**
- Used `YYYYMMDDHHIISS` UTC for most message timestamps.
- Loose enforcement of numeric types across tables.
**Lupopedia Behavior**
- Strict Constitutional Mandate for 14-digit UTC BIGINT (`YYYYMMDDHHIISS`) for all timestamps.
- "Packed Timestamps" doctrine for all primary storage and synchronization.
**What Changed**
- Standardized and enforced the 14-digit UTC BIGINT format across the entire system.
**Why It Changed**
- To ensure absolute cross-platform/cross-database sorting and comparison without timezone or epoch-rollover risks.

### UI Interaction Model
**Crafty Syntax Behavior**
- Framesets, multiple windows, popups, and DHTML "sliding" layers.
- Diverse UI themes including "bubbles" and side-by-side operator views.
**Lupopedia Behavior**
- Single-column Command Center (PRD 02/GEMINI.md).
- Strict interleaved chronological timeline.
- Forbidden Patterns: No bubbles, no side-by-side columns, no threaded nesting in the UI.
**What Changed**
- Standardized on a single, clean, chronological stream for all message types.
**Why It Changed**
- To ensure UI consistency and readability across all devices, following the "Single Source of Truth" timeline principle.

### Orchestration vs Live Help
**Crafty Syntax Behavior**
- Manual human-to-human communication tool.
- No automated reasoning or multi-step logic.
**Lupopedia Behavior**
- AI Orchestration platform.
- Agents (MAAT, THOTH, LILITH) can perform automated reasoning, schedule tasks, and interact with the database.
- Auto-response capability via ROSE dialog system.
**What Changed**
- From a "tool for humans" to a "platform for Humans + Agents."
**Why It Changed**
- The core mission of Lupopedia is to facilitate AI-driven workflows while using the resilient Crafty Syntax transport layer as its reliable delivery mechanism.

## Key Architectural Shifts
- **From Live Help Tool -> AI Orchestration Platform:** The system's purpose has shifted from a support widget to a collaborative reasoning engine.
- **From Operator-Centric -> Actor-Centric System:** Identity is now polymorphic and decoupled from specific logins.
- **From Chat-Focused -> Semantic Graph + Orchestration:** Conversation is an entry point into the broader semantic memory graph.

## Dependency Note (CRITICAL)
The base Crafty Syntax live-help behavior (flush, XMLHTTP, refresh) is the foundational transport layer. All Lupopedia extensions (Actors, Agents, Orchestration) are built on top of this reliable baseline. Lupopedia's successful delivery on May 1 depends on maintaining full parity with the ancestral transport resilience while activating the new modified features.

## Open Gaps / Risks
- **Actor/User Bindings:** Finalizing the `AuthSessionManager` logic for multi-department actor eligibility is currently in progress.
- **Transport Lock-In:** Ensuring that "One-Way Promotion" correctly handles mobile network drops that might require temporary demotion.
- **UI Performance:** The "Single-Column" requirement with large transcripts (PRD 44) requires efficient cursor-based delta fetching to maintain responsiveness.
