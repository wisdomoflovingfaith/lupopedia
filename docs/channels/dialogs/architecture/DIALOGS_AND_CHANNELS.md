---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "0066FF"
  message: "Created DIALOGS_AND_CHANNELS.md to clarify the distinction between threads (one-on-one conversations in database) and channels (multi-agent collaboration contexts stored as dialog history files). Documents naming conventions, examples, and relationship to CADUCEUS and HERMES."
tags:
  categories: ["documentation", "architecture", "reference"]
  collections: ["core-docs", "architecture"]
  channels: ["dev"]
in_this_file_we_have:
  - Threads vs Channels Distinction
  - Thread Storage (Database)
  - Channel Storage (Dialog Files)
  - Channel Naming Conventions
  - Channel Examples
  - Relationship to CADUCEUS and HERMES
  - Why This Distinction Matters
file:
  title: "Dialogs and Channels Architecture"
  description: "Complete explanation of the distinction between threads (one-on-one conversations) and channels (multi-agent collaboration contexts), including storage, naming conventions, and relationship to routing and emotional balancing subsystems"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# **Dialogs and Channels Architecture**

## Overview

Lupopedia maintains two distinct types of conversation contexts: **threads** and **channels**. Understanding this distinction is critical for routing, agent collaboration, emotional geometry, and documentation consistency.

**Key Principle:**  
- **Threads** = One-on-one conversations stored in the database  
- **Channels** = Multi-agent collaboration contexts stored as dialog history files

---

## 1. Threads: One-on-One Conversations

### Definition

A **thread** is a one-on-one conversation between two participants (human-to-human, human-to-agent, or agent-to-agent). Threads are stored in the database and managed by the DialogManager subsystem.

### Storage

Threads are stored in database tables:

- **`dialog_threads`** â€” Thread metadata and context
- **`dialog_messages`** â€” Individual messages within threads

### Characteristics

- **Participants:** Exactly two (one sender, one recipient per message)
- **Storage:** Database tables (`dialog_threads`, `dialog_messages`)
- **Routing:** Direct addressing (no HERMES routing required)
- **Emotional Context:** Individual message moods, not channel-level balancing
- **Visibility:** Private to the two participants

### Example

```
Thread 42:
- Human (BOB) â†’ Agent (LILITH)
- Agent (LILITH) â†’ Human (BOB)
- Human (BOB) â†’ Agent (LILITH)
```

This is a private conversation stored in `dialog_messages` with `thread_id = 42`.

---

## 2. Channels: Multi-Agent Collaboration Contexts

### Definition

A **channel** is a shared collaboration context containing humans and AI agents working together on a task. Channels enable multi-agent coordination, emotional balancing, and collaborative problem-solving.

### Storage

Channels are stored as dialog history files in the `dialogs/` folder:

- **Location:** `dialogs/<channel_name>_dialog.md`
- **Format:** Markdown files with dialog entries
- **Ordering:** Newest-first (most recent entries at the top)

### Characteristics

- **Participants:** Multiple humans and AI agents
- **Storage:** Markdown files in `dialogs/` folder
- **Routing:** HERMES routing subsystem (determines which agent receives each message)
- **Emotional Context:** CADUCEUS balances emotional currents from polar agents
- **Visibility:** Shared space (all participants can see messages, subject to visibility rules)

### Channel Architecture

Within each channel:
- Two agents are designated as **polar agents** (the symbolic "serpents" on the caduceus)
- Example: LILITH = one pole, WOLFIE = the opposite pole
- These two agents define the emotional extremes of the channel
- CADUCEUS reads their moods and blends them to produce channel emotional current
- HERMES routes messages within the channel, optionally using CADUCEUS emotional current as context

---

## 3. Channel Naming Convention

### File Naming Pattern

Channel dialog files follow this naming convention:

```
dialogs/<channel_name>_dialog.md
```

### Rules

- **Lowercase:** Channel names use lowercase letters
- **Underscores:** Multi-word channel names use underscores (e.g., `routing_development`)
- **Suffix:** All channel dialog files end with `_dialog.md`
- **No spaces:** Channel names never contain spaces

### Examples

- `dialogs/changelog_dialog.md` â€” Changelog channel
- `dialogs/readme_dialog.md` â€” README channel
- `dialogs/routing_changelog.md` â€” Routing changelog channel
- `dialogs/routing_development_dialog.md` â€” Routing development channel (if renamed from "routing")

**Note:** Some channel files may omit the `_dialog` suffix in the filename (e.g., `routing_changelog.md`), but the standard convention is `<channel_name>_dialog.md`.

---

## 4. Channel Examples

### Example 1: Changelog Channel

**File:** `dialogs/changelog_dialog.md`

**Purpose:** Documents changes and updates to the system

**Participants:** CURSOR, KIRO, Captain_wolfie, and other agents contributing to changelog entries

**Content:** Dialog entries documenting version changes, feature additions, and system updates

### Example 2: README Channel

**File:** `dialogs/readme_dialog.md`

**Purpose:** Documents README updates and documentation changes

**Participants:** CURSOR, KIRO, documentation agents

**Content:** Dialog entries documenting README modifications and documentation updates

### Example 3: Routing Development Channel

**File:** `dialogs/routing_changelog.md` (or `dialogs/routing_development_dialog.md`)

**Purpose:** Documents routing subsystem development and corrections

**Participants:** CURSOR, KIRO, HERMES, CADUCEUS (as subjects of discussion)

**Content:** Dialog entries documenting routing architecture corrections, HERMES/CADUCEUS clarifications, and related development work

---

## 5. The `dialogs/` Folder

### Purpose

The `dialogs/` folder contains **CHANNEL dialog logs**, not thread logs.

### Contents

- Channel dialog history files (`*_dialog.md`)
- Multi-agent collaboration records
- Documentation of channel-level activities
- Agent coordination logs

### What It Does NOT Contain

- Thread conversation logs (those are in the database)
- One-on-one conversation records
- Private message histories

### Critical Rule

**All files in `dialogs/` represent channel-level multi-agent collaboration, not thread-level one-on-one conversations.**

---

## 6. Relationship to CADUCEUS and HERMES

### Channels and CADUCEUS

**CADUCEUS operates at the channel level:**

- CADUCEUS reads the moods of the two polar agents within a channel
- CADUCEUS averages or blends their emotional states
- CADUCEUS produces a "channel mood" (emotional current) that other subsystems can use
- CADUCEUS does NOT operate on threads (threads have no polar agents)

**Example:**
```
Channel: routing_development
Polar Agents: CURSOR (DIVERGE pole), KIRO (OPTIMIZE pole)
CADUCEUS: Blends their moods to produce channel emotional current
```

### Channels and HERMES

**HERMES routes messages within channels:**

- HERMES determines which agent receives each message in a channel
- HERMES handles delivery, queueing, and dispatch
- HERMES may optionally use CADUCEUS emotional current as context for routing decisions
- HERMES does NOT route thread messages (threads use direct addressing)

**Example:**
```
Channel: routing_development
Message arrives â†’ HERMES routes to appropriate agent
HERMES may use CADUCEUS emotional current as context
```

### Threads Do NOT Participate

**Critical Rule:** Threads do NOT participate in CADUCEUS or HERMES:

- Threads use direct addressing (`to_actor` field)
- Threads have no polar agents
- Threads have no channel emotional current
- Threads bypass HERMES routing
- Threads bypass CADUCEUS emotional balancing

**Threads are simple, direct conversations stored in the database.**

---

## 7. Why This Distinction Matters

### For Routing

- **Channels:** HERMES routes messages based on mood, agent classification, and channel intent
- **Threads:** Direct addressing, no routing required

Understanding this distinction prevents confusion about when HERMES is invoked and when direct addressing is used.

### For Agent Collaboration

- **Channels:** Enable multi-agent coordination, swarm routing, and collaborative problem-solving
- **Threads:** Enable private, one-on-one conversations

This distinction clarifies when agents collaborate vs. when they communicate privately.

### For Emotional Geometry

- **Channels:** CADUCEUS balances emotional currents from polar agents, producing channel-level mood
- **Threads:** Individual message moods, no emotional balancing

This distinction explains why CADUCEUS only operates on channels, not threads.

### For Documentation Consistency

- **Channels:** Documented in `dialogs/` folder as markdown files
- **Threads:** Stored in database tables, not documented as files

This distinction clarifies where to look for different types of conversation records.

### For System Architecture

- **Channels:** Part of the multi-agent emotional architecture
- **Threads:** Part of the basic messaging infrastructure

This distinction helps understand how Lupopedia's emotional geometry integrates with its messaging system.

---

## 8. Summary

### Threads

- **What:** One-on-one conversations
- **Storage:** Database tables (`dialog_threads`, `dialog_messages`)
- **Routing:** Direct addressing
- **Emotional Context:** Individual message moods
- **CADUCEUS:** Does NOT participate
- **HERMES:** Does NOT participate

### Channels

- **What:** Multi-agent collaboration contexts
- **Storage:** Markdown files in `dialogs/` folder (`<channel_name>_dialog.md`)
- **Routing:** HERMES routing subsystem
- **Emotional Context:** CADUCEUS balances polar agent moods
- **CADUCEUS:** Computes channel emotional current
- **HERMES:** Routes messages within channel

### Key Takeaway

**Channels enable multi-agent collaboration with emotional geometry. Threads enable simple, direct conversations. The distinction is fundamental to Lupopedia's architecture.**

---

## Related Documentation

- **[HERMES and CADUCEUS](../../agents/HERMES_AND_CADUCEUS.md)** â€” Complete reference for HERMES routing and CADUCEUS emotional balancing
- **[Architecture Sync](../../architecture/ARCHITECTURE_SYNC.md)** â€” Multi-agent chat architecture and channel definitions
- **[RFC 4003](../../architecture/protocols/CADUCEUS_ROUTING_RFC.md)** â€” CADUCEUS Emotional Balancing Standard
- **[RFC 4004](../../architecture/protocols/HERMES_ROUTING_RFC.md)** â€” HERMES Routing Layer Standard
- **[Dialog History Specification](../agents/DIALOG_HISTORY_SPEC.md)** â€” Technical specification for dialog storage

---

*Last Updated: January 2026*  
*Version: GLOBAL_CURRENT_LUPOPEDIA_VERSION*  
*Status: Published*  
*Author: GLOBAL_CURRENT_AUTHORS*

## Related Documentation

- [WHY_MULTIPLE_IDES_AND_AGENTS.md](../../architecture/WHY_MULTIPLE_IDES_AND_AGENTS.md) â€” Explains why Captain Wolfie uses multiple IDEs, multiple agents, and multiple LLM faucets, and how channels organize agent collaboration.
- [CASE_STUDY_MULTI_IDE_CADUCEUS_HERMES.md](../../architecture/CASE_STUDY_MULTI_IDE_CADUCEUS_HERMES.md) â€” Real-world example of multi-IDE collaboration and routing/emotional balancing corrections.
- [multi-ide-workflow.md](../../architecture/multi-ide-workflow.md) â€” Technical documentation of the multi-IDE workflow.
- [HERMES_AND_CADUCEUS.md](../../agents/HERMES_AND_CADUCEUS.md) â€” Detailed explanation of routing and emotional balancing subsystems.
