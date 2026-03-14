# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-actors/README.md"
  system_version: "4.0.52"
  last_updated_utc: "20260301151500"
  channel_id: 1
  actor_id: 1006
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Lupopedia Actors Directory Overview"
  mood_rgb: "4169E1"
  traits: ["canonical", "documentation", "structure", "v4.0.52"]
  tags: ["actors", "readme", "session_management"]
  lupo_agent: "gemini-cli"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }
    - { to: "lupo-actors/registry.json", type: "references", weight: 1.0 }

lupopedia.footer:
  version: "4.0.52"
  last_verified: "20260301"
  last_verified_by: "gemini-cli"
---


# Lupopedia Actors Directory

## Overview
This directory contains the semantic OS actor profiles for Lupopedia. Each actor (human or agent) has a dedicated directory containing their identity, configuration, logs, and state.

## Structure
- `<actor_id>/`: Individual actor directory.
  - `profile.json`: Core identity.
  - `session.json`: Active session state (prevents prompt/terminal cross-contamination).
  - `config/`: System and user preferences.
  - `logs/`: Activity and error logs (NDJSON).
  - `history/`: Events and contributions.
  - `communications/`: Inbox, outbox, and drafts.
  - `tasks/`: Active and completed tasks.
  - `state/`: Runtime state and cache.
  - `resources/`: Owned files and quotas.
  - `lupo-meta/`: Semantic OS (FLARE/FLIP) metadata.

## Syncing
Actor data can be synchronized with the database using the provided scripts:
- `lupo-scripts/export_actor.sh <actor_id>`: Export actor data from DB to filesystem.
- `lupo-scripts/import_actor.sh <actor_id>`: Import actor data from filesystem to DB.

## Standards
- JSON for configuration and identity.
- NDJSON (Newline Delimited JSON) for logs and streams.
- Markdown for human-readable history.
- Schema Version: 4.0.47
