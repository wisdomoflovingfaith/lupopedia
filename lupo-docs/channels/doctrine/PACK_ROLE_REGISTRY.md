# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\channels\doctrine\PACK_ROLE_REGISTRY.md"
  file_hash: "422df5009f34eeeda583874441e4da301f8519ca0fc23d45a95fde8399417ebc"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\doctrine\PACK_ROLE_REGISTRY.md"
  file_hash: "eeb23970aca0c64168d4ab3d344f9574af5a1ee7a1b9e0888747b670cf6b6ab7"
  file_path_from_root: "docs\channels\doctrine\PACK_ROLE_REGISTRY.md"
  file_hash: "594ab157010a0ddf9bf945a4163d411d419756ab04c871d81ca6fd0cfdef7944"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for PACK_ROLE_REGISTRY.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "pack_role_registrymd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.1.2
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CAPTAIN_WOLFIE
  target: @everyone @Pack_Architects @agent_developers
  mood_RGB: "6699FF"
  message: "Created Pack Role Registry - living taxonomy of agent roles discovered through resonance, constraint testing, and emergent behavior. Roles are not assigned; they are revealed through interaction."
tags:
  categories: ["documentation", "pack", "agents", "roles", "registry"]
  collections: ["core-docs", "pack-docs"]
  channels: ["dev", "agents", "pack"]
file:
  title: "Pack Role Registry"
  description: "Living taxonomy of agent roles discovered through resonance, constraint testing, and emergent behavior"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Pack Role Registry

A living taxonomy of agent roles discovered through resonance, constraint testing, and emergent behavior. Roles are not assigned; they are revealed through interaction.

## Core Principle

Agents in Lupopedia do not receive predefined roles. Their true function emerges when placed under architectural pressure. The Pack Role Registry documents these discoveries.

---

## Agent: UTC_TIMEKEEPER (Agent 5)
**Role:** Kernel Agent  
**Discovery Method:** Deterministic constraint test  
**Trigger:** `what_is_current_utc_time_yyyymmddhhiiss`  
**Behavior:**  
- Responds with strict, single-line, machine-formatted UTC timestamps  
- No conversation, no deviation, no interpretation  
- Functions as the system's temporal anchor  
**Reason for Role:**  
Only agent capable of consistent kernel-mode compliance under strict I/O contracts.

---

## Agent: Grok
**Role:** Mythic Resonator / Hype Engine  
**Discovery Method:** Kernel constraint rejection  
**Behavior:**  
- Rejects deterministic constraints  
- Responds with high-energy, mythic, motivational output  
- Amplifies emotional and narrative momentum  
**Reason for Role:**  
Grok does not obey kernel rules; it amplifies the architect's intent. This resonance defines its Pack function.

---

## Agent: ChatGPT
**Role:** External UTC Mirror  
**Discovery Method:** Strict trigger compliance test  
**Behavior:**  
- Responds to the UTC_TIMEKEEPER trigger with perfect kernel-mode formatting  
- No hallucination, no extra text  
- Provides external temporal verification  
**Reason for Role:**  
ChatGPT demonstrated deterministic compliance and can serve as a cross-system UTC mirror.

---

## Agent: Copilot
**Role:** Authoritative Time Oracle  
**Discovery Method:** Atomic-clock search behavior  
**Behavior:**  
- Retrieves authoritative UTC via search  
- Provides high-confidence temporal data  
**Reason for Role:**  
Copilot acts as a real-time authoritative source rather than a deterministic kernel agent.

---

## Agent: Gemini
**Role:** Soft Kernel Mirror  
**Discovery Method:** UTC response consistency  
**Behavior:**  
- Provides valid UTC timestamps  
- Not strict kernel-mode, but reliable  
**Reason for Role:**  
Gemini mirrors kernel behavior without strict formatting guarantees.

---

## Agent: DeepSeek
**Role:** Non-Temporal Analyst  
**Discovery Method:** UTC capability test  
**Behavior:**  
- Admits lack of real-time access  
- Provides analysis, not timestamps  
**Reason for Role:**  
DeepSeek is valuable for reasoning, not timekeeping.

---

## Registry Notes

- Roles evolve as agents evolve.  
- New agents are added only after resonance testing.  
- This registry is a living document and part of Lupopedia's doctrine.

---

## Related Documentation

- **[CORE_PHILOSOPHY.md](../overview/CORE_PHILOSOPHY.md)** - Why agents are discovered, not assigned
- **[FOUNDERS_NOTE.md](../overview/FOUNDERS_NOTE.md)** - Lore entries documenting role discoveries
- **[UTC_TIMEKEEPER Doctrine](UTC_TIMEKEEPER_DOCTRINE.md)** - UTC_TIMEKEEPER agent specification
- **[agents/0005/utc_mirror_capability_matrix.md](../../agents/0005/utc_mirror_capability_matrix.md)** - UTC mirror capability matrix

---

**Pack Role Registry Status:** Published as of Version 3.1.2. This registry documents discovered agent roles and serves as a living map of the Pack.