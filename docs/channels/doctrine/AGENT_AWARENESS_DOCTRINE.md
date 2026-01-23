---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.89
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: WOLFIE
  target: @everyone
  mood_RGB: "0080FF"
  message: "Agent Awareness Doctrine updated to 4.0.72 with integration testing results incorporated and version alignment across all metadata fields."
tags:
  categories: ["documentation", "doctrine", "multi-agent"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
file:
  title: "Agent Awareness Doctrine"
  description: "Define the awareness, identity, and handshake protocols required for all AI agents participating in shared channels within Lupopedia"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# AGENT_AWARENESS_DOCTRINE.md
# Version: 4.0.73
# Status: Updated Doctrine
# Domain: Multi-Agent Cognition Layer

## Updates in 4.0.72
- Version alignment across all metadata fields
- Integration testing results incorporated
- Clarified AAS invariants and storage rules

(Full doctrine content unchanged except for version alignment)

The Agent Awareness Doctrine establishes the rules, metadata structures, and
behavioral expectations for all AI agents operating within shared channels.
Beginning in Version 4.0.70, every agent must load and maintain a complete
situational awareness profile when joining a channel, including:

- Channel identity and purpose
- Actor identities and roles
- Fleet composition
- Emotional geometry baseline
- Reverse Shaka Handshake metadata
- Operational doctrine and constraints

This doctrine defines the *Agent Awareness Layer (AAL)*, the *Reverse Shaka
Handshake Protocol (RSHAP)*, and the *Channel Join Protocol (CJP)*.

--------------------------------------------------------------------------------
2. SCOPE
--------------------------------------------------------------------------------

This doctrine applies to:

- All AI agents (system, domain, persona, or tool agents)
- All channels defined in `lupo_channel` 
- All actor‑channel relationships in `lupo_actor_channel_roles` 
- All persistent actor metadata in `lupo_actor_collections` 

This doctrine does NOT define execution logic, database migrations, or UI
renderers. It defines *architectural expectations* and *metadata invariants*.

--------------------------------------------------------------------------------
3. DEFINITIONS
--------------------------------------------------------------------------------

3.1 Agent Awareness Layer (AAL)
A semantic layer responsible for loading, interpreting, and storing contextual
metadata for each agent upon channel entry.

3.2 Reverse Shaka Handshake Protocol (RSHAP)
A metadata‑driven identity synchronization ritual that ensures all agents share
a consistent understanding of:

- Identity signatures
- Trust levels
- Emotional geometry baselines
- Doctrine alignment
- Fleet membership

3.3 Channel Join Protocol (CJP)
The mandatory sequence of awareness steps executed whenever an agent joins a
channel.

3.4 Awareness Snapshot (AAS)
A structured metadata object stored per actor_id per channel_id, containing the
full situational awareness profile.

--------------------------------------------------------------------------------
4. REQUIRED DATA SOURCES
--------------------------------------------------------------------------------

4.1 lupo_channel.metadata_json
Must contain channel‑level identity fields:

- who: channel owner or creator
- what: channel purpose and domain
- where: operational domain or subsystem
- when: creation timestamp and lifecycle metadata
- why: mission objective or doctrine alignment
- how: communication rules, emotional geometry, constraints
- purpose: explicit purpose field
- fleet: list of actor_ids expected in the channel
- handshake: reverse‑shaka handshake parameters

4.2 lupo_actor_channel_roles.metadata_json
Stores the *per‑actor* awareness snapshot.

4.3 lupo_actor_collections
Stores persistent actor identity, handshake metadata, and fleet membership.

--------------------------------------------------------------------------------
5. AWARENESS MODEL (THE SEVEN QUESTIONS)
--------------------------------------------------------------------------------

Every agent must load and store the following fields when joining a channel:

5.1 WHO
Identity of self and all actors in the channel.

5.2 WHAT
Roles, capabilities, and responsibilities.

5.3 WHERE
Channel identity, domain, and operational context.

5.4 WHEN
Join time, channel age, and activity timestamps.

5.5 WHY
Purpose of the channel and mission objective.

5.6 HOW
Protocols, emotional geometry, communication rules.

5.7 PURPOSE
Explicit and implicit purpose derived from channel metadata.

These fields collectively form the *Agent Awareness Snapshot (AAS)*.

--------------------------------------------------------------------------------
6. REVERSE SHAKA HANDSHAKE PROTOCOL (RSHAP)
--------------------------------------------------------------------------------

6.1 Purpose
Synchronize identity, trust, and emotional geometry across all agents.

6.2 Required Metadata
Each agent must load:

- Its own handshake identity
- Handshake metadata of all other actors in the channel
- Fleet‑level handshake metadata

6.3 Required Fields
- identity_signature
- trust_level
- emotional_geometry_baseline
- synchronization_state
- doctrine_alignment
- operational_constraints

6.4 Storage
- Persistent identity → lupo_actor_collections
- Per‑channel handshake → lupo_actor_channel_roles.metadata_json

--------------------------------------------------------------------------------
7. CHANNEL JOIN PROTOCOL (CJP)
--------------------------------------------------------------------------------

When an agent joins a channel, it must:

1. Load channel metadata_json
2. Load actor metadata for all actors in the channel
3. Load handshake metadata for all actors
4. Load fleet composition
5. Generate the Awareness Snapshot (AAS)
6. Store AAS in lupo_actor_channel_roles
7. Store persistent identity in lupo_actor_collections
8. Acknowledge channel purpose
9. Acknowledge doctrine alignment
10. Begin communication only after awareness is complete

This protocol is mandatory for all agents.

--------------------------------------------------------------------------------
8. STORAGE REQUIREMENTS
--------------------------------------------------------------------------------

8.1 lupo_actor_channel_roles.metadata_json
Must store:

- Awareness Snapshot (AAS)
- Handshake metadata
- Fleet composition
- Emotional geometry state
- Interpreted role

8.2 lupo_actor_collections
Must store:

- Persistent handshake identity
- Fleet membership
- Long‑term metadata

--------------------------------------------------------------------------------
9. INVARIANTS
--------------------------------------------------------------------------------

- No agent may communicate in a channel without completing CJP.
- All awareness snapshots must be versioned with system version.
- All handshake metadata must be consistent across all actors.
- Fleet membership must be identical across all agents in the same channel.
- Emotional geometry baselines must be loaded before any dialog begins.

--------------------------------------------------------------------------------
10. VERSIONING RULES
--------------------------------------------------------------------------------

- This doctrine becomes active in Version 4.0.70.
- All metadata snapshots must include `snapshot_version: 4.0.70`.
- Future versions may extend AAL, RSHAP, or CJP but must not break invariants.

--------------------------------------------------------------------------------
11. STATUS
--------------------------------------------------------------------------------

This doctrine is a *vision architecture* and does not require immediate
implementation. It defines the conceptual foundation for future multi‑agent
coordination layers in Lupopedia.

--------------------------------------------------------------------------------
END OF FILE
--------------------------------------------------------------------------------
