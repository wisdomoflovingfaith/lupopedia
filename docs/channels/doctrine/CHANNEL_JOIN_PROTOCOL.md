---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.72
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: WOLFIE
  target: @everyone
  mood_RGB: "0080FF"
  message: "Channel Join Protocol doctrine - Mandatory 10-step sequence for agents joining channels with awareness snapshot generation and protocol completion tracking."
tags:
  categories: ["documentation", "doctrine", "multi-agent"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
file:
  title: "Channel Join Protocol Doctrine"
  description: "Mandatory sequence of awareness, identity synchronization, and metadata loading steps for all agents joining a channel"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# CHANNEL_JOIN_PROTOCOL.md
# Version: 4.0.72
# Status: Updated Doctrine
# Domain: Multi-Agent Cognition Layer

## Updates in 4.0.72
- Version alignment
- Integration testing results incorporated
- Clarified communication-blocking invariants

## 1. PURPOSE
Defines the mandatory sequence of awareness, identity synchronization, and
metadata loading steps for all agents joining a channel.

## 2. TRIGGER
Triggered whenever (actor_id, channel_id) is created.

## 3. REQUIRED DATA SOURCES
- lupo_channel.metadata_json
- lupo_actor_channel_roles
- lupo_actor_collections

## 4. AWARENESS SNAPSHOT (AAS)
Must include WHO, WHAT, WHERE, WHEN, WHY, HOW, PURPOSE.

## 5. RSHAP INTEGRATION
Loads handshake identity, trust level, emotional geometry baseline, doctrine
alignment, and synchronization state.

## 6. PROTOCOL STEPS
1. Load channel metadata
2. Load actor metadata
3. Load handshake metadata
4. Load fleet composition
5. Generate AAS
6. Store AAS
7. Store persistent identity
8. Acknowledge purpose
9. Acknowledge doctrine
10. Begin communication

## 7. STORAGE REQUIREMENTS
- lupo_actor_channel_roles: AAS + handshake metadata
- lupo_actor_collections: Persistent identity

## 8. PROTOCOL COMPLETION TRACKING
- protocol_completion_status: pending → aal_complete → rshap_complete → cjp_complete → ready
- join_sequence_step: 0-10 step progression tracking
- Completion timestamps for audit trail

## 9. INVARIANTS
No agent may communicate before completing CJP.

## 10. VERSIONING RULES
Active in 4.0.72. Future versions may extend but not break invariants.