---
flare.headers: {
  file_path_from_root: "docs/doctrine/INDEX.md",
  system_version: "4.0.48",
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
  outbound_edges: [
    { to: "docs/channels/appendix/HISTORY.md", type: "references", weight: 1.0 },
    { to: "docs/doctrine/LUPOPEDIA_DOCTRINE.md", type: "references", weight: 1.0 },
    { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
  ],
  semantic_tags: ["doctrine_index", "engineering_standards"]
}
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
5.  **[Table Ceiling Protocol](CASCADE_TABLE_CEILING_PROTOCOL.md)** — Governance of the 222-table limit.

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
