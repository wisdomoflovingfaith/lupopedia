---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: version_readme
  when_updated: "20260407172944"
  file_path_from_root: "lupo-docs/versions/4.0.96/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.96/README.md"
  last_modified_utc: "20260407172944"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "version_readme"
  artifact_kind: "version"
  purpose: "Version overview for Lupopedia 4.0.96 — current patch line"
  tags: ["version", "4.0.96", "active", "cursor"]
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.95/README.md"
      type: references
      weight: 1.0
      reason: "Prior finalized line (4.0.95)"
    - to: "lupo-docs/versions/4.0.96/TODO.md"
      type: references
      weight: 1.0
      reason: "Active backlog"
    - to: "lupo-docs/versions/4.0.96/CHANGELOG.md"
      type: references
      weight: 1.0
      reason: "Patch-line changelog"
    - to: "lupo-docs/versions/4.0.96/SUMMARY.md"
      type: references
      weight: 0.95
      reason: "Goals summary"
lupopedia.footer:
  last_verified: "20260407172944"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.96/README.md — delegation: cursor:root

# Lupopedia 4.0.96 — current patch line

**Status:** Active (working line)
**Runtime / atoms:** **`GLOBAL_CURRENT_LUPOPEDIA_VERSION`** is **4.0.96** in **`lupo-config/global_atoms.yaml`**.
**Predecessor:** [4.0.95](../4.0.95/README.md) (finalized).

---

## 5W1H Version Overview (as of 2026-04-07 21:00 UTC)

See [SUMMARY.md](SUMMARY.md) and [CHANGELOG.md](CHANGELOG.md) for a full 5W1H breakdown of all work completed in this thread, including:

- Memory model overhaul (4D edge model, memory.json deprecation, root memory nodes)
- Actor separation (Claude Code, registry, docs)
- Doctrine expansion (context-typed, status-aware, directional edges)
- Schema and install SQL updates (lupo_edges, lupo_memory_nodes, lupo_contents, lupo_folders)
- File-backed content system (lupo-content/)
- Content & Analytics Ingestion Pipeline (Crafty Syntax import, PRD 11)
- Lossy Abbreviation Dialect (PRD 37)
- All PRDs, registry, and version docs updated

---

## Where to look

| Artifact | Path |
|----------|------|
| Backlog | [TODO.md](TODO.md) |
| Changelog | [CHANGELOG.md](CHANGELOG.md) |
| Goals | [SUMMARY.md](SUMMARY.md) |
| Migration notes | [MIGRATION_NOTES.md](MIGRATION_NOTES.md) |

---

## Canonical version

The shipping "current version" string is **`4.0.96`**, per **VERSIONING_DOCTRINE** §1 and **`GLOBAL_CURRENT_LUPOPEDIA_VERSION`**. Root **[README.md](../../../README.md)** states this for humans and agents.

This output complies with Lupopedia Constitutional Root Rules.
