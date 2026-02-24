---
wolfie.headers: {
  file_path_from_root: "docs/README.md",
  system_version: "4.0.39",
  channel_id: 42,
  mood_rgb: "708090",
  purpose: "Documentation directory index and navigation guide for Lupopedia doctrine and architecture",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "guide",
  artifact_kind: "index",
  traits: ["essential", "navigation", "v4.0.39"],
  hashtags: ["#docs", "#index", "#doctrine", "#architecture", "#navigation"],
  engagement: {
    likes: 0,
    shares: 0,
    views: 0,
    last_interaction_utc: "20260224"
  },
  graph_stats: {
    inbound_count: 5,
    outbound_count: 8,
    centrality_score: 0.70
  }
}

flip.footer: {
  inbound_edges: [
    { from: "README.md", type: "references", weight: 1.0, hashtag: "#overview" },
    { from: "QUICKSTART.md", type: "references", weight: 0.8, hashtag: "#onboarding" },
    { from: "HOW_TO_USE_LUPOPEDIA.md", type: "references", weight: 0.7, hashtag: "#guide" },
    { from: "docs/doctrine/", type: "indexed_by", weight: 0.9, hashtag: "#doctrine" },
    { from: "docs/status/", type: "indexed_by", weight: 0.6, hashtag: "#status" }
  ],
  outbound_edges: [
    { to: "README.md", type: "references", weight: 0.9, hashtag: "#overview" },
    { to: "docs/doctrine/", type: "indexes", weight: 1.0, hashtag: "#doctrine" },
    { to: "docs/status/", type: "indexes", weight: 0.7, hashtag: "#status" },
    { to: "docs/versions/", type: "indexes", weight: 0.7, hashtag: "#roadmap" },
    { to: "docs/toons/", type: "indexes", weight: 0.6, hashtag: "#schema" },
    { to: "channels/51/identity-layer-architecture.md", type: "references", weight: 0.9, hashtag: "#architecture" },
    { to: "docs/AGENT_INVENTORY.md", type: "references", weight: 0.7, hashtag: "#actors" },
    { to: "docs/status/AGENT_TASK_TRACKER.md", type: "references", weight: 0.6, hashtag: "#coordination" }
  ],
  referenced_by_actors: [1001, 1002, 1003, 10000],
  references: {
    by_files: ["README.md", "QUICKSTART.md", "HOW_TO_USE_LUPOPEDIA.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["documentation_index", "navigation", "doctrine_reference", "architecture_guide"],
  enrichment: {
    llm_inferred_edges: [],
    federated_metrics: {}
  },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}
---

FILE: docs/README.md
TYPE: md

# Documentation Index

This directory hosts doctrine files, architecture notes, emotional-metadata specifications, and multi-agent rules. It keeps core philosophy and technical references organized outside the repository root.

If you add a new doctrine or architectural note, place it here (or in the appropriate subfolder) and link it from `README.md`.

## Core Architecture

- **[Channel System Doctrine (51 and system channels)](../channels/51/identity-layer-architecture.md)** - Lupopedia Channel Architecture: Complete documentation of the Semantic OS identity layer covering channels, actors, and memberships.

### Channel Numbering Clarifications

Lupopedia currently contains ~222 channels.

Channel numbers are not sequential and do not represent a fixed range.

High values (e.g., 51-series) are intentional and correspond to subsystem groupings.

channel_number is a semantic identifier, not an index or capacity limit.

The total count of channels is meaningful; the numeric gaps between them are not.

This ensures contributors understand that channel numbering is non-linear by design and should not be interpreted as a contiguous namespace.
