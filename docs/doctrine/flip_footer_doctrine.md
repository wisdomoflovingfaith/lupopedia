# FLIP Footer Doctrine

**Version:** 1.0  
**Effective:** 4.0.29+  
**Issued:** 2026-02-23 via Channel 42 Broadcast  

---

## Purpose

FLIP Headers describe a file's identity (path, version, creation metadata). FLIP Footers describe the **reverse graph** — what other files, services, docs, and entities **reference or depend on** this file.

Together, Headers + Footers create a bidirectional semantic map of the codebase.

## When to Add Footers

- All new files MUST include a FLIP footer
- Existing files SHOULD have footers added incrementally as agents edit them
- Footers do NOT need to be exhaustive on first pass — agents add references as they discover them
- Footers MUST be updated when a new consumer/reference is added to the file

## Footer Format

### PHP Files

```php
/*
 * flip.footer:
 *   referenced_by:
 *     - path/to/file.php
 *   consumed_by_services:
 *     - ServiceClassName
 *   cited_by_docs:
 *     - docs/doctrine/some_doc.md
 *   graph_edges_in:
 *     - edge_type: "depends_on"
 *       source: "path/to/source.php"
 *   related_toons:
 *     - docs/toons/table_name.toon.json
 *   related_atoms:
 *     - ATOM_NAME
 *   channels:
 *     - 42
 */
```

### SQL Files

```sql
-- flip.footer:
--   referenced_by:
--     - database/migrations/other_migration.sql
--   consumed_by_services:
--     - InstallerService
--   cited_by_docs:
--     - docs/doctrine/database/table_doc.md
```

### Markdown Files

```html
<!-- flip.footer:
  referenced_by:
    - docs/directives/channel_42_broadcast.md
  cited_by_docs:
    - AGENTS.md
-->
```

### YAML/JSON Config Files

```yaml
# flip.footer:
#   referenced_by:
#     - lupo-includes/version.php
#   consumed_by_services:
#     - VersionLoader
```

## Required Fields (Minimum)

Each footer MUST include at least ONE of the following fields:

- **referenced_by** — Files that `require`, `include`, `import`, or directly reference this file
- **consumed_by_services** — Service classes that use this file's output or API
- **cited_by_docs** — Documentation files (`.md`) that cite or reference this file
- **graph_edges_in** — Semantic graph edges pointing INTO this file (edge_type + source)
- **related_toons** — TOON files that describe tables used in this file
- **related_atoms** — Atom names from `config/global_atoms.yaml` used in this file
- **channels** — Channel IDs that are relevant to this file's creation or context

## Optional Fields

- **consumed_by_migrations** — Migration SQL files that depend on this file
- **consumed_by_tests** — Test files that exercise this file's functionality
- **consumed_by_agents** — Agent config directories that reference this file

## Rules

1. Paths in footers are relative to project root (same as FLIP header `file_path_from_root`)
2. Footers are YAML formatted inside the file's comment syntax
3. Footers are placed at the END of the file, after all functional code
4. Footers are informational — they do not affect runtime behavior
5. Agents MUST NOT remove footer entries unless the reference is confirmed deleted
6. Agents SHOULD add footer entries when they add a new `require_once`, `include`, or reference to a file
7. Footers are NOT auto-generated — they are maintained by agents during development

## Relationship to FLIP Headers

- **FLIP Header** = "I am this file, I was created at this time, by this agent"
- **FLIP Footer** = "These other files reference me, these services use me, these docs cite me"

Headers are outward-facing identity. Footers are inward-facing dependency tracking.

---

*Doctrine issued as part of Channel 42 Broadcast. See `docs/directives/channel_42_broadcast.md`.*

<!-- flip.footer:
  referenced_by:
    - docs/directives/channel_42_broadcast.md
    - AGENTS.md
  cited_by_docs:
    - docs/directives/channel_42_broadcast.md
  channels:
    - 42
-->
