# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\INDEX.md"
  file_hash: "b50570a731b3260bf6404c1bb67b0f1b5456ee177d0f863dd717ffded3b6f26f"
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
  file_path_from_root: "docs\doctrine\INDEX.md"
  file_hash: "c22e123318d7a67c50a6c2294d8ebfa3496963b9cb1efbb75f4e38c7272185af"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for INDEX.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "indexmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
flare.headers: {
  file_path_from_root: "docs/doctrine/INDEX.md",
  file_hash: "042bfc20d863726ffc65e17d1330dfe132aae733ec1a04081cd4262c687d46c0"
  system_version: "4.0.50"
  channel_id: 42,
  actor_id: 1003,
  last_modified_utc: "20260227",
  delegation_chain: "10000:1003",
  artifact_type: "documentation",
  purpose: "Central index for Lupopedia engineering and architectural doctrines",
  mood_rgb: "00FF00",
  traits: ["canonical", "documentation", "index", "v4.0.48", "history-update"],
  tags: ["doctrine", "index", "architecture", "v4.0.48", "history-update"],
  lupo_agent: "antigravity"
}
flare.edges: {
  file_path_from_root: "docs\doctrine\INDEX.md"
  outbound_edges: [
    { to: "docs/channels/appendix/HISTORY.md", type: "references", weight: 1.0 },
    { to: "docs/doctrine/LUPOPEDIA_DOCTRINE.md", type: "references", weight: 1.0 },
    { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
  ],
  semantic_tags: ["doctrine_index", "engineering_standards"]
}
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer: {
  last_verified_utc: "20260227",
  last_verified_by: "antigravity"
}
---

# Lupopedia Doctrine Index

## Purpose
Governs Semantic OS operations via non-negotiable architectural boundaries and engineering standards. All development must align with these rules to ensure multi-agent safety and long-term portability.

## Core Doctrines

1.  **[Lupopedia Master Doctrine](LUPOPEDIA_DOCTRINE.md)** — The foundational rules of the system.
2.  **[FLARE Protocol Doctrine](FLARE/FLARE_DOCTRINE.md)** — File-level metadata and relationship standards.
3.  **[Database Philosophy](../architecture/DATABASE_PHILOSOPHY.md)** — Why we use no foreign keys, triggers, or stored procedures.
4.  **[Identity Authority](IDENTITY_AUTHORITY_DOCTRINE.md)** — Universal actor model and registry hierarchy.
5.  **[Table Ceiling Protocol](CASCADE_TABLE_CEILING_PROTOCOL.md)** — Governance of the 199-table limit.

## Context & History

> [!TIP]
> To understand the "why" behind these strict doctrines, review the project's evolution from a 2002 open-source live help system to a modern Semantic OS.

*   **[Full Project History](../channels/appendix/HISTORY.md)** — From Crafty Syntax to Lupopedia.
*   **[Founder's Note](../channels/appendix/appendix/FOUNDERS_NOTE.md)** — The personal narrative behind the architecture.

## Navigation Layers
1. **Identity**: Actors, semantics, membership
2. **Channels**: Types, routing, groupings
3. **Routing**: Message flow logic
4. **Emotional**: Metadata structures
5. **Kernel**: System behavior

---
*Last updated: 2026-02-27 (v4.0.48)*