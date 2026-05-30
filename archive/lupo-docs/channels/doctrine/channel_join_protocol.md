# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/doctrine/CHANNEL_JOIN_PROTOCOL.md"
  file_hash: "f851577f8d51a88e8b950043b643e30e2d85a21be31b949e372543eadf557210"
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "lupo-docs\channels\doctrine\CHANNEL_JOIN_PROTOCOL.md"
  file_hash: "355040f33691c86e4e45d86e942ea8fd08b5d8b6fda04063b72d6f4b3f2293cc"
  file_path_from_root: "lupo-docs\channels\doctrine\CHANNEL_JOIN_PROTOCOL.md"
  file_hash: "3727d3812f86e85971276095833ba2829551acd92a69266b7552e82e9ca30856"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for CHANNEL_JOIN_PROTOCOL.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "channel_join_protocolmd"]
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
file.last_modified_system_version: 3.0.72
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
# Version: 3.0.72
# Status: Updated Doctrine
# Domain: Multi-Agent Cognition Layer

## Updates in 3.0.72
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
Active in 3.0.72. Future versions may extend but not break invariants.
