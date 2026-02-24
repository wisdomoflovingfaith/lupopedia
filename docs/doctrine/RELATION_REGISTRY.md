---
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: "docs/doctrine/RELATION_REGISTRY.md"
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
