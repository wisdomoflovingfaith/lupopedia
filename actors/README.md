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
