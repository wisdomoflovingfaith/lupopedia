---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.1.3/notes.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.1.3/notes.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/version-4-1-3-notes.toon
  atoms_toon: null
  transcript_jsonl: 0/development/4_1_3_notes
  artifact_type: version-doc
  artifact_kind: reference
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: version-doc
  prd_cluster: null
  title: Lupopedia 4.1.3 Notes — Channel Routing, Presence, and Message Projection Model
  summary: 'Defines the routing and visibility model inherited from Crafty Syntax: strict saidto/saidfrom message projection with separate presence awareness. No participant has global visibility by default.'
---

# NOTES — Lupopedia 4.1.3

## Core Principle

Lupopedia is **not a shared chat room**.

It is a:

> **Channel-based routing system with strict message projection and separate presence awareness**

This model originates from Crafty Syntax Live Help and is preserved in 4.1.3.

---

## Channel Model

A **channel** is an internal routing context.

- All messages are written into the same channel
- Multiple conversations exist simultaneously
- Messages are NOT globally visible

The channel acts as a **routing bus**, not a shared conversation surface.

---

## dialog_messages Table (Core Mechanism)

All communication is stored in:

> `dialog_messages`

Each message includes:

- `channel_id`
- `from_actor_id` (saidfrom)
- `to_actor_id` (saidto)
- `message_text`
- `timestamp_ymdhis`
- (optional) `thread_id` / `visitor_id`

These fields fully determine message visibility.

---

## Message Routing Logic

Messages are routed using:

- **channel_id** → grouping
- **from_actor_id** → sender
- **to_actor_id** → recipient

There is **no implicit broadcast**.

---

## Message Projection (Strict Visibility Rules)

### Absolute Rule

> **Participants ONLY see messages where they are sender or recipient.**

This applies to:

- visitors
- actors (human operators)
- AI actors (future)

---

### Visitor

A visitor sees:

- messages sent **to them**
- messages they **sent**

A visitor does NOT see:

- other visitors
- actor-to-actor communication
- unrelated threads

---

### Actor (Human Operator)

An actor sees:

- messages sent **to them**
- messages sent **from them**

An actor does NOT see:

- conversations between other actors
- conversations with other visitors (unless routed to them)

There is **no default “see all” behavior**.

---

### Supervisor / Captain (Optional Elevated Mode)

A supervisor may have:

- full channel visibility

This must be:

- explicitly enabled
- not default behavior

---

## Presence Layer (Separate from Visibility)

The system includes a **presence layer** that is distinct from message routing.

Actors can see:

- which actors are online
- which visitors are on the channel

This allows:

- selecting a participant
- initiating a conversation

However:

> **Presence does NOT grant message visibility.**

---

## Example (Crafty Syntax Behavior)

- Devin ↔ Support  
- Eric ↔ Support  

Result:

- Devin does NOT see Eric’s messages  
- Eric does NOT see Devin’s messages  
- Support sees both ONLY because messages are routed to/from Support  

Support does NOT see messages between Devin and Eric unless explicitly routed.

---

## Threads

Threads represent:

- a visitor interaction
- or a specific conversation context

Threads are:

- grouped within a channel
- filtered by routing rules

Threads do NOT override visibility rules.

---

## Architectural Insight

The system is:

> **one shared message store with multiple filtered projections**

NOT:

- multiple isolated chat rooms
- duplicated data stores
- global message visibility

---

## Implications for 4.1.3

- Multi-conversation support already exists
- Routing model is complete and proven
- UI must reflect projection, not channel contents

---

## Extension to AI (4.2.0+)

AI actors integrate directly:

- assigned an `actor_id`
- use same routing fields
- follow same visibility rules

AI does NOT require a new messaging system.

---

## Critical Constraint

**No message leakage is allowed.**

- No actor sees messages not routed to/from them
- No visitor sees internal actor communication
- Presence must not expose message content

This is a **hard system invariant**.

---

## Summary

Lupopedia communication is:

- channel-based (shared storage)
- routing-driven (saidto/saidfrom)
- projection-controlled (visibility)
- presence-aware (but not visibility-granting)

This architecture:

- was proven in Crafty Syntax
- supports multiple simultaneous conversations
- safely extends to AI actors

---

**End of NOTES.md**