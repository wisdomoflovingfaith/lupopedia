# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "includes/bootstrap.php"

lupopedia.conditional:
  guards:
    execution_mode: "advisory"
    allow:
      actor_ids: [0, 1004]
      agent_names: ["system", "antigravity"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-11T00:00:00Z"
    conditions:
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents"]
    what:
      artifact_type: "guide"
      objective: "Collection: Fallback Database (File/Folder/CSV-based)"
    where:
      repo_paths: ["database\lupopedia\channels\channels\42\collections\database-fallback-collection.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T10:08:32Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "database/lupopedia/channels/channel_id/42/collections/database-fallback-collection.md"
  file_hash: "b7836b339484f545846e41617f241a4655a9591ef29068f084a5e92e8d98aed5"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Collection: Fallback Database (File/Folder/CSV-based)"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["database", "lupopedia", "channels", "channels", "42", "collections"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["database\lupopedia\channels\channels\42\collections\database-fallback-collection.md", "http://www.lupopedia.com/DATABASE-FALLBACK-COLLECTION"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# Collection: Fallback Database (File/Folder/CSV-based)

This collection represents the entire `database/` structure, designed to provide a high-resolution, offline fallback for the 210+ remaining tables.

## Purpose
Establishing a redundant, file-based persistence layer that ensures system accessibility and data integrity when the primary PDO/SQL database is unreachable.

## Structure Overview (`database/`)

| Folder | Root-Truth Mapping | Description |
|--------|---------------------|-------------|
| `lupopedia/channels/` | `lupo_channels` | Moved recursively from `channels/`. Contains all channel metadata, broadcasts, tasks, plans, threads, and collections. |
| `lupopedia/actors/` | `lupo_actors` | Moved from `actors/`. Contains actor profiles, sessions, and roles. |
| `lupopedia/content/` | `lupo_contents` | Moved from `content/` or `docs/`. Contains key system documents. |
| `lupopedia/collections/` | Unified Object Groups | New directory containing TOON-based object collections for high-level schema mapping. Integrated with moved channel collections. |
| `lupopedia/atoms/` | `lupo_atoms` | New directory for fine-grained system atoms and constants (YAML-based). |
| `lupopedia/contents/` | `lupo_contents` (All-in-one) | Backup repository for all Markdown/TEXT content from the system. |

## Relationship to the 210 Optimized Tables
All 210+ tables currently in the SQL database will have corresponding paths in the fallback system. For tables that are not as file-heavy (e.g., lookup or session recovery), CSV files will serve as the persistent storage. File-heavy tables will continue to use Markdown formats in their respective `lupopedia/` folders.

## Version
Updated as part of Phase 2 for version 4.0.55.
