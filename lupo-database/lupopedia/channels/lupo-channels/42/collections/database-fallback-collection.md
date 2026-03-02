# Collection: Fallback Database (File/Folder/CSV-based)

This collection represents the entire `lupo-database/` structure, designed to provide a high-resolution, offline fallback for the 210+ remaining tables.

## Purpose
Establishing a redundant, file-based persistence layer that ensures system accessibility and data integrity when the primary PDO/SQL database is unreachable.

## Structure Overview (`lupo-database/`)

| Folder | Root-Truth Mapping | Description |
|--------|---------------------|-------------|
| `lupopedia/channels/` | `lupo_channels` | Moved recursively from `lupo-channels/`. Contains all channel metadata, broadcasts, tasks, plans, threads, and collections. |
| `lupopedia/actors/` | `lupo_actors` | Moved from `lupo-actors/`. Contains actor profiles, sessions, and roles. |
| `lupopedia/content/` | `lupo_contents` | Moved from `lupo-content/` or `lupo-docs/`. Contains key system documents. |
| `lupopedia/collections/` | Unified Object Groups | New directory containing TOON-based object collections for high-level schema mapping. Integrated with moved channel collections. |
| `lupopedia/atoms/` | `lupo_atoms` | New directory for fine-grained system atoms and constants (YAML-based). |
| `lupopedia/contents/` | `lupo_contents` (All-in-one) | Backup repository for all Markdown/TEXT content from the system. |

## Relationship to the 210 Optimized Tables
All 210+ tables currently in the SQL database will have corresponding paths in the fallback system. For tables that are not as file-heavy (e.g., lookup or session recovery), CSV files will serve as the persistent storage. File-heavy tables will continue to use Markdown formats in their respective `lupopedia/` folders.

## Version
Updated as part of Phase 2 for version 4.0.55.
