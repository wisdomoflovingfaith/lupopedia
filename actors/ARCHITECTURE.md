# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\actors\ARCHITECTURE.md"
  file_hash: "28bb0464f0e3ca81f006750c9bd618f831d46165287424c78c4b23d8afcb308b"
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
  file_path_from_root: "actors\ARCHITECTURE.md"
  file_hash: "66cf769ef2e703c6c3ddbb905e01f929db24dc3b88930d410eedf741703bfc6e"
  file_path_from_root: "actors\ARCHITECTURE.md"
  file_hash: "6c1e87761be916fb4e56668a2d68bb5ec35cc8efc252a21d3e9c7c260ae05c2e"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Architecture Overview: Actor Directory Structure"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["actors", "architecturemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Architecture Overview: Actor Directory Structure

This directory implements the actor-centric foundation for the Lupopedia Semantic OS. It is designed to be:
1. **Portable**: All data is stored in human-readable and machine-parseable formats (JSON, NDJSON, MD).
2. **Transferable**: Mapping layers (in `meta/schema.json`) allow for easy sync with relational database tables.
3. **Scalable**: Append-only logs (NDJSON) handle high-volume activity data without rewriting large files.
4. **Secure**: Sensitive data in `credentials.json` is encrypted at rest.
5. **Semantic**: Full integration with FLARE graph edges and FLIP headers for cross-actor relationship tracking.

## Key Design Principles
- **Actor as Root**: The `actor_id` is the primary key and the root of the actor's filesystem.
- **Dumb Storage, Smart Application**: The directory structure is a state snapshot; application logic (PHP) handles the transformation and query resolution.
- **Soft State**: `state/` and `resources/` provide runtime context that can be ephemeral or persistent.

## Metadata and Governance
All files include `schema_version` and metadata for validation. The `registry.json` at the root acts as the master index for the system.