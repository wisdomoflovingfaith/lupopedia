---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_contents.md"
  web_path: "[lupo_contents](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_contents)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "content"
  purpose: "Documentation for lupo_contents table - primary content records for knowledge and documentation entities"
  tags: ["database", "table", "content"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_contents table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=15 python_hits=11"
  outbound_edges:
    - { to: "database.table.lupo_contents", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "debug_collection_zero.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "install.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-database/lupopedia/content/lupo-app/Services/AnubisUnknownRecipientService.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-database/lupopedia/content/lupo-app/Services/FlareValidatorService.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-database/lupopedia/content/lupo-app/Services/SavedCollectionsService.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-includes/classes/UrlResolver.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/functions/url_resolver.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/modules/content/content-controller.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/modules/content/content-model.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/modules/content/edge-controller.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/modules/content/lookup-helpers.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/modules/truth/truth-controller.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/modules/truth/truth-model.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-scripts/audit_schema_doctrine.php", type: "USED_IN_PHP", weight: 0.7 }
    - { to: "lupo-scripts/validate_tab_mappings.php", type: "USED_IN_PHP", weight: 0.7 }
    - { to: "analyze_unused_tables.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/audit_schema_doctrine.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/export_channel_snapshots.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/flare_edge_suggester.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/generate_flip_headers.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/generate_headers.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/import_os.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/import_os_fixed.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/rebuild_lupo_contents.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-tools/generate_flip_header.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_contents ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_contents
# Table: lupo_contents

## Table Overview

- **Purpose:** Primary content records for knowledge and documentation entities. Stores articles, pages, and semantic content with title, slug, body/content, format (e.g. markdown), channel and actor scope, status, visibility, and optional JSON denormalizations (tags, sections, revision history, media, etc.).
- **Category:** Content / Knowledge
- **Status:** Active (present in install schema)
- **Version introduced:** 4.0.x

## Where This Table Is Used

- **Content routing and display:** Modules resolve content by slug or content_id for public and channel-scoped pages; `lupo_contents` is the main source for body, title, and metadata.
- **Knowledge base and help:** Help tree and knowledge collections reference content rows; `default_collection_id` and collection maps link content to collections.
- **Author and channel scope:** `actor_id` and `channel_id` scope content to an actor and channel; used for permission and filtering in admin and API.
- **Semantic and truth integration:** Content can be referenced by truth/knowledge tables and edges; `file_path_from_root` and FLIP-style fields support file-artifact linkage.
- **Content lifecycle:** Status (draft/published), visibility, triage_status, and soft delete (`is_deleted`, `deleted_ymdhis`) drive workflow and display. Timestamps use BIGINT YYYYMMDDHHIISS UTC.

## Key Columns (summary)

| Column | Type | Description |
|--------|------|-------------|
| content_id | bigint | Primary key. |
| content_parent_id | bigint | Optional parent content for hierarchy. |
| channel_id | bigint | Channel this content belongs to. |
| actor_id | bigint | Content author/owner. |
| title | varchar(255) | Display title. |
| slug | varchar(255) | URL-friendly identifier. |
| body / content | text | Main content body. |
| content_type | varchar(50) | e.g. article. |
| format | varchar(20) | e.g. markdown. |
| status | varchar(64) | e.g. draft, published. |
| visibility | varchar(64) | e.g. public, private. |
| created_ymdhis | bigint | Creation timestamp (BIGINT UTC). |
| updated_ymdhis | bigint | Last update. |
| is_deleted | tinyint | Soft delete flag. |
| deleted_ymdhis | bigint | Soft delete timestamp. |

Many additional columns exist for JSON denormalizations (tags, atom_mappings, category_mappings, content_events, revision_history, etc.); see install SQL or TOON for the full list.

## Relationships

- **Logical references:** `actor_id` → lupo_actors; `channel_id` → lupo_channels; `content_parent_id` → lupo_contents; `default_collection_id` → collection tables. No database foreign keys; application code enforces relationships.
- **Join patterns:** Lookup by `content_id` or `slug` (and channel_id/actor_id where scoped); joins to lupo_help_tree, collection maps, and truth/knowledge where content is referenced.

## Doctrine Notes

- **No foreign keys.** All referential integrity in application code.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC; set in PHP with `gmdate('YmdHis')`.
- **Soft delete:** Filter by `is_deleted = 0` unless querying deleted content.
