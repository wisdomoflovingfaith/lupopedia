# lupo-memory/

This directory stores memory node JSON files for Lupopedia, organized by creation date and slug.

## Structure

- Each memory node is exported as a single JSON file.
- Files are stored in subfolders by year and month of creation:
  - `lupo-memory/YYYY/MM/{memory_slug}`
    - `YYYY` = 4-digit year from `created_ymdhis`
    - `MM` = 2-digit month from `created_ymdhis`
    - `{memory_slug}` = unique, filesystem-safe slug from the `lupo_memory_nodes` table
- Example: `lupo-memory/2026/04/qualified-lead-acme-corp.json`

## Purpose

- Provides a filesystem backup and restore path for memory nodes.
- Enables external tools to process, audit, or migrate memory node data.
- Mirrors the canonical memory node schema in `lupo-docs/prd/01_core_identity.md`.

## Reference
- See `lupo-docs/prd/01_core_identity.md` for schema and slug rules.
- See `lupo-docs/prd/29_project_structure.md` for project directory conventions.
