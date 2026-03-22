# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/RELATION_REGISTRY.md"
  file_hash: "f958dd8c88c4f9857a38afa47d5e022a2cc6d3ea07a32ad7fa6fbca764a54255"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
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
  file_path_from_root: "lupo-docs\doctrine\RELATION_REGISTRY.md"
  file_hash: "6323a2c25d2b3f61cf9024f08df935e7d667b577148d0501cf20662c7c358a00"
  file_path_from_root: "lupo-docs\doctrine\RELATION_REGISTRY.md"
  file_hash: "27642339a88d4a4add7fc9f5f657ab66d53d43e0a68c04bad8fde6192bcc99d2"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for RELATION_REGISTRY.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "relation_registrymd"]
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
file_path_from_root: "lupo-docs/doctrine/RELATION_REGISTRY.md"
system_version: "4.0.40"
channel_id: 42
mood_rgb: "88FF00"
actor_id: 1003
lupo_agent: "antigravity"
purpose: "Canonical registry for semantic relation types used in FLIP headers/footers"
---

# SEMANTIC RELATION REGISTRY (v1.0)

This registry defines the allowed `relation_type` values and their semantic meanings for use in `inbound_edges` and `outbound_edges`.

## 1. Core Structural Relations
| Relation Type | Direction | Meaning |
|---------------|-----------|---------|
| `consumes` | Outbound | Target is required for execution/loading. |
| `references` | Bi-directional | Target is cited for context but not required for execution. |
| `documents` | Outbound | Source explains or specifies the target. |
| `implements` | Outbound | Source provides the concrete logic for target's abstract definition. |

## 2. Emotional & Contextual Relations
| Relation Type | Direction | Meaning |
|---------------|-----------|---------|
| `emotional_dependency` | Bi-directional | Source's `mood_rgb` is influenced by target's state. |
| `channel_anchor` | Outbound | Source is primary to the target channel. |
| `actor_exclusive` | Outbound | Source is intended for use primarily by a specific actor_id. |

## 3. High-Fidelity (Block-Level) Relations
| Relation Type | Meaning |
|---------------|---------|
| `block_reference` | Points to a specific `#heading` or `anchor` within a file. |
| `task_dependency` | Source block represents a task that depends on target block. |

## 4. Federated & External Relations
| Relation Type | Meaning |
|---------------|---------|
| `external_cite` | Link to a non-Lupopedia resource (GitHub, etc.). |
| `federated_edge` | Connection across nodes in a federated Lupopedia network. |

---

**REGISTRY MAINTAINED BY ACTOR 1003**  
**Draft Version 1.0.0**
