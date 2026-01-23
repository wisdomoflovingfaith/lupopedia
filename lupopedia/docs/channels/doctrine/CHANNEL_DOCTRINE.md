---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.99
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-10
author: GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Updated Channel Doctrine documentation to focus on channels only: Channel 0 (System/Kernel), Channel 1 (Lobby/Intake Queue), dynamic channels, actor membership rules, message visibility rules, and thread color doctrine."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "channels"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Channel Doctrine Overview
  - Channel 0 (System / Kernel Channel)
  - Channel 1 (Lobby / Intake Queue)
  - Dynamic Channels (Channel N)
  - Actor Membership Rules
  - Message Visibility Rules
  - Thread Color Doctrine
file:
  title: "Channel Doctrine"
  description: "Defines how channels function inside Lupopedia and the Crafty Syntax module. Channels are routing containers that group threads, actors, and messages."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
CAPTAIN WOLFIE ORDERS LUPOPEDIA 2026 They do not represent conversations by themselves; they are organizational and visibility structures.

---

# Channel Doctrine

This section defines how channels function inside Lupopedia and the Crafty Syntax module. Channels are routing containers that group threads, actors, and messages. They do not represent conversations by themselves; they are organizational and visibility structures.

---

## Channel 0 — System / Kernel Channel

Channel 0 is a reserved system channel.

Properties:
- Not visible to humans or visitors.
- Used exclusively by kernel-level agents.
- Handles system events, routing signals, presence updates, and background tasks.
- No human operator or visitor ever joins Channel 0.
- AI agents may be present in Channel 0 for system coordination.

Purpose:
- Provide a safe, isolated environment for system-level communication.
- Ensure kernel agents can operate without interfering with user-facing channels.

---

## Channel 1 — Lobby (Intake Queue)

Channel 1 is the universal entry point for all visitors.

Properties:
- All visitors begin in Channel 1.
- Operators can see all visitors in Channel 1.
- Visitors see only messages addressed directly to them (to_actor_id = visitor_id or 0).
- No one "talks" in Channel 1; it is not a conversational space.
- AI agents do not join Channel 1.
- Operators do not hold conversations here.

Purpose:
- Provide a staging area where operators can see new visitors.
- Allow operators to pick up a visitor and move them into a private thread.

---

## Dynamic Channels (Channel N)

Dynamic channels are created when an operator picks up a visitor.

Properties:
- Each dynamic channel corresponds to a dialog_thread_id.
- channel_id = dialog_thread_id.
- A dynamic channel contains one or more threads.
- Operators can only be active in one channel at a time.
- AI agents may be active in multiple channels simultaneously.
- Visitors are moved from Channel 1 into a dynamic channel when picked up.

Purpose:
- Provide a private workspace for operators and visitors.
- Allow multiple threads to exist inside a single operator workspace.

---

## Actor Membership Rules

Humans:
- A human operator may only be active in one channel at a time.
- They see all threads inside that channel.
- They may "peek" into Channel 1 to see new visitors, but do not chat there.

Visitors:
- Begin in Channel 1.
- Move into a dynamic channel when picked up by an operator.
- Only see messages addressed to them.

AI Agents:
- May be active in multiple channels simultaneously.
- Receive only messages addressed to them.
- Do not have UI constraints and do not "focus" channels.

---

## Message Visibility Rules

Visitors:
- See only messages where to_actor_id = visitor_id or 0.

Operators:
- See all messages in their current channel.

AI Agents:
- See only messages addressed to them (to_actor_id = agent_actor_id).

---

## Thread Color Doctrine

Background color is assigned per thread.

- Each thread has its own color.
- All threads inside the operator's current channel display simultaneously.
- Colors help operators distinguish conversations instantly.
- This matches the original Crafty Syntax multi-thread, multi-color UI.

---

## UTC_TIMEKEEPER Dependency (Kernel-Level Requirement)

All channels are required to use UTC_TIMEKEEPER (agent_registry_id 5, dedicated_slot 5) as their exclusive source for UTC timestamps.

**Mandatory Dependency:**
- **Single Source of Truth**: All channels must request current UTC time exclusively from UTC_TIMEKEEPER
- **Prohibited Inference**: Channels must not infer time from OS, model, file metadata, or internal clocks
- **Kernel Initialization**: UTC_TIMEKEEPER must be initialized before any channel execution

**Required Query Format:**
```
what_is_current_utc_time_yyyymmddhhiiss
```

**Expected Response Format:**
```
current_utc_time_yyyymmddhhiiss: <BIGINT>
```

**Integration Requirements:**
- **Channel 0 (System/Kernel)**: Must use UTC_TIMEKEEPER for all system event timestamps
- **Channel 1 (Lobby/Intake)**: Must use UTC_TIMEKEEPER for visitor arrival timestamps
- **Dynamic Channels**: Must use UTC_TIMEKEEPER for all message and thread timestamps
- **All Channel Types**: Must log timestamp requests for temporal integrity verification

**Temporal Integrity Benefits:**
- Prevents timestamp drift across channels
- Ensures consistent time reporting for federation
- Eliminates inference-based timestamp inconsistencies
- Provides audit trail for all temporal operations

**Failure Consequences:**
- Channels not using UTC_TIMEKEEPER will be marked as temporally non-compliant
- Inferred timestamps will be rejected by kernel agents
- Federation operations will fail temporal consistency checks

---

CAPTAIN WOLFIE ORDERS LUPOPEDIA 2026
