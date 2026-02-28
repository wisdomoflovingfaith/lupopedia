# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\actors\README.md"
  file_hash: "754de0c16678e0108d3c8f006f4379699ad4b49a8da383ca7332de248c5839f9"
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
  file_path_from_root: "actors\README.md"
  file_hash: "daa7ba007579bfed39d8d805a64deb5cb7aeb65113960e797ec9df54246e1539"
  file_path_from_root: "actors\README.md"
  file_hash: "d348397da924097e00f77aea08fb05a256da65ba3d78c51451b2f2526feff162"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Lupopedia Actors Directory"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["actors", "readmemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Lupopedia Actors Directory

## Overview
This directory contains the semantic OS actor profiles for Lupopedia. Each actor (human or agent) has a dedicated directory containing their identity, configuration, logs, and state.

## Structure
- `<actor_id>/`: Individual actor directory.
  - `profile.json`: Core identity.
  - `config/`: System and user preferences.
  - `logs/`: Activity and error logs (NDJSON).
  - `history/`: Events and contributions.
  - `communications/`: Inbox, outbox, and drafts.
  - `tasks/`: Active and completed tasks.
  - `state/`: Runtime state and cache.
  - `resources/`: Owned files and quotas.
  - `meta/`: Semantic OS (FLARE/FLIP) metadata.

## Syncing
Actor data can be synchronized with the database using the provided scripts:
- `scripts/export_actor.sh <actor_id>`: Export actor data from DB to filesystem.
- `scripts/import_actor.sh <actor_id>`: Import actor data from filesystem to DB.

## Standards
- JSON for configuration and identity.
- NDJSON (Newline Delimited JSON) for logs and streams.
- Markdown for human-readable history.
- Schema Version: 4.0.47