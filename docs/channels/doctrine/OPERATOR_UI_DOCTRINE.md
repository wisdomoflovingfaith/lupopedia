---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
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
