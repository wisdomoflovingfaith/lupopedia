---
lupopedia.headers:
  actor_id: 100
  actor_name: "kiro"
  delegation_chain: "kiro:root"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "kiro_rule"
  file_path_from_root: ".kiro/rules/database-offline-fallback-import-doctrine.md"
  last_modified_utc: "20260314"
  system_version: "4.0.75"
  source_path: "lupo-rules/root/database-offline-fallback-import-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "kiro_doctrine"
---

# DB008: Database Offline Fallback and Filesystem Sync Doctrine

## Core Principle

Lupopedia is designed to be highly resilient. Because IDE Agents (like Windsurf, Cursor, Kiro) often operate across a file system without a direct TCP connection into `MySQL` or `PostgreSQL`, the File System acts as the definitive fallback state database. 

## Architectural Constraints

1. **Filesystem as a Surrogate Database**
   - If attempting to insert rows into a channel (e.g., Dialog Messages, Tasks, Heartbeats) but the database is inaccessible, data is written locally.
   - Files are stored categorically. Example: Dialog Messages for a channel mapped to `lupo-channels/0/threads/4.0.x/`.
   - Structural records like Actors or Agents fallback to flat CSV tables under `lupo-database/lupopedia/csv/`.
   - Core config parameters remain inside `config/global_atoms.yaml` and `.env` equivalents.

2. **Rehydration / Bi-Directional Sync**
   - A fallback is not a dead end. Every fallback artifact (like a markdown thread tracking dialog messages) must be structurally sound enough to be programmatically parsed and inserted directly back into the live MySQL database once connections resume.
   - Operations performed by agents directly inside the `lupo-channels/` or `lupo-database/lupopedia/csv/` directories must maintain strict formatting matching the database TOON schemas natively tracking that logic.

## Non-Negotiable Violations

- **Unstructured Fallback**: Dropping unstructured or malformed notes in a random temp directory instead of strictly mapped `lupo-channels/` or `lupo-database/` structures.
- **Sync Blocking**: Creating fallback files that cannot be re-ingested (e.g., writing human narrative data when the TOON file expects a rigid `BIGINT` timestamp and discrete foreign keys).
