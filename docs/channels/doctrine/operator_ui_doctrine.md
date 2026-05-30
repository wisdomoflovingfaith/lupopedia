> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/doctrine/OPERATOR_UI_DOCTRINE.md"
  file_hash: "6439f80979d82e4dbcb04d1093838cb0d2f17f469011526388db39fc68b60df0"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\doctrine\OPERATOR_UI_DOCTRINE.md"
  file_hash: "ad77ea71bba4ff7b68570a3140102cb5a12c5d2be58f388009e4b87ef02de60a"
  file_path_from_root: "docs\channels\doctrine\OPERATOR_UI_DOCTRINE.md"
  file_hash: "98f2219c47420f34bb630970479cb9087ec930a7a34b8e9397dbb754a44e8b4f"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for OPERATOR_UI_DOCTRINE.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "operator_ui_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-10
author: GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created Operator UI Doctrine documentation defining operator-facing user interface philosophy and behavior inherited from Crafty Syntax."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "ui", "operator"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Operator UI Doctrine Overview
  - Operator Channel Context
  - Thread Presentation
  - Tabs and Thread Navigation
  - Real-Time Message Flow
  - Visibility Rules in the UI
  - Design Principles
  - Historical Note
file:
  title: "Operator UI Doctrine"
  description: "Documents the operator-facing user interface philosophy and behavior inherited from the original Crafty Syntax system and preserved in the modern Lupopedia architecture."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Operator UI Doctrine

This section documents the operator-facing user interface philosophy and behavior inherited from the original Crafty Syntax system and preserved in the modern Lupopedia architecture. The operator UI is designed for high-efficiency, multi-thread, real-time support operations.

---

## Overview

The operator UI is a multi-thread, multi-color, single-screen cockpit.
It allows human operators to monitor and respond to multiple active
conversations simultaneously without switching pages or losing context.

This UI was a defining feature of the original Crafty Syntax system and
is preserved as a core doctrine in Lupopedia.

---

## Operator Channel Context

Operators may only be active in one channel at a time.

Within that channel:
- All active threads are visible simultaneously.
- Each thread has its own background color.
- Messages from all threads update in real time.
- Operators can focus on any thread using tabs at the bottom of the UI.

Operators may "peek" into Channel 1 to see new visitors, but they do not
chat there. Channel 1 is an intake queue, not a conversation space.

Operators may also "peek" into other channels to see which threads and operators are active in those channels. When viewing other channels, operators see threads and operators in the sidebar (like the "Chatting Users" panel), but threads from channels the operator is not actively in do not appear as tabs. For example, if Bob is chatting in a different channel, he may appear in the sidebar as an active operator, but there is no tab for Bob's thread because the operator is not actively in that channel.

---

## Thread Presentation

Each thread is displayed as a separate panel within the operator's
current channel workspace.

Thread panels:
- Have unique background colors.
- Display messages chronologically.
- Update in real time as new messages arrive.
- May be collapsed or expanded depending on UI design.
- Are visually distinct to reduce cognitive load.

Thread color is assigned per thread, not per channel.

---

## Tabs and Thread Navigation

A tab bar is displayed at the bottom of the operator UI.

Each tab corresponds to a thread within the current channel.

Tab behavior:
- Clicking a tab focuses that thread.
- Focusing a thread does not hide other threads.
- All threads remain visible on the screen.
- Tabs provide quick navigation without context switching.

This design allows operators to maintain situational awareness across
multiple conversations.

---

## Real-Time Message Flow

Messages from all threads inside the operator's current channel appear
in real time.

Operators do not need to:
- switch pages,
- reload the interface,
- or enter/exit conversations.

This real-time multi-thread visibility is a core doctrine inherited from
Crafty Syntax and must be preserved.

---

## Visibility Rules in the UI

Operators see:
- all messages in all threads within their current channel.

Visitors see:
- only messages addressed to them.

AI agents:
- do not use the operator UI,
- may be active in multiple channels simultaneously,
- receive only messages addressed to them.

---

## Design Principles

The operator UI is built on the following principles:

1. **Zero Context Switching**
   Operators should never lose sight of other conversations.

2. **Color as Cognitive Separation**
   Each thread's background color provides instant visual distinction.

3. **Single-Screen Awareness**
   All active threads are visible at once.

4. **Tab-Based Focus**
   Tabs allow operators to focus a thread without hiding others.

5. **Real-Time Responsiveness**
   All threads update live without manual refresh.

6. **Operator-Centric Workflow**
   The UI is optimized for human operators, not AI agents.

---

## Historical Note

The original Crafty Syntax system (2002–2006) was the only live chat
platform of its era to implement:
- multi-thread visibility,
- multi-color thread separation,
- and single-screen operator control.

This doctrine is preserved in Lupopedia as a defining architectural
principle.

---
