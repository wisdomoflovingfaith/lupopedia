# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\architecture\layout-context-schema.md"
  file_hash: "935ace4babc619e7fcc465a9b892f381ed2c435248732cbfb0cdfdc1dd0c97db"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\architecture\layout-context-schema.md"
  file_hash: "b1047749693aa77937afc57922dfb8dfe0f2000eb5e9723d8103af3faec9252d"
  file_path_from_root: "docs\architecture\layout-context-schema.md"
  file_hash: "6c344c13dc874cc1150d788d32e88ce66c95918dd0dc508e923c5219318c3da5"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Layout Context Schema"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "architecture", "layout-context-schemamd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Layout Context Schema

This document defines the unified context array passed to the main layout renderer.

## LayoutContext (array)

- page_body (string, required)
  - Rendered inner HTML for the page.
  - Populated by: content controllers, truth controllers, Crafty Syntax controller.

- page_title (string, optional)
  - Human-readable page title.
  - Populated by: content_show_by_slug, content_handle_slug, TRUTH handlers, Crafty Syntax handlers.

- content (array|null, optional)
  - Canonical content row or metadata row for list pages.
  - Populated by: content controllers, TRUTH handlers, Crafty Syntax handlers.

- related_edges (array, optional)
  - Related edge records for canonical content pages.
  - Populated by: content_show_by_slug.

- collection (array|null, optional)
  - Collection metadata (if available).
  - Populated by: content_handle_slug and TRUTH collection handlers.

- tags (array, optional)
  - Tag list (strings or records).
  - Populated by: content_handle_slug and TRUTH handlers (when available).

- channel (array|null, optional)
  - Channel metadata (if available).
  - Populated by: channel handlers (future or module-specific).

- semantic_context (array, optional)
  - Semantic context payload.
  - Populated by: content_handle_slug and TRUTH handlers.

- content_references (array, optional)
  - Content references.
  - Populated by: content_handle_slug and TRUTH handlers.

- content_links (array, optional)
  - Linked content records.
  - Populated by: content_handle_slug and TRUTH handlers.

- content_sections (array|null, optional)
  - Section IDs or section metadata.
  - Populated by: content_handle_slug and TRUTH handlers.

- tabs_data (array, optional)
  - Collection tabs for navigation.
  - Populated by: collection handlers and TRUTH collection handlers.

- current_collection (string|null, optional)
  - Human-readable collection name.
  - Populated by: collection handlers and TRUTH collection handlers.

- collection_id (int|null, optional)
  - Collection ID (for URL generation).
  - Populated by: collection handlers and TRUTH collection handlers.

- prev_content (array|null, optional)
  - Previous content metadata.
  - Populated by: content_handle_slug (and other content list handlers).

- next_content (array|null, optional)
  - Next content metadata.
  - Populated by: content_handle_slug (and other content list handlers).

- meta (array, optional)
  - Small metadata map for layout/head usage.
  - Fields:
    - description (string)
    - slug (string)
    - created_ymdhis (string)
    - updated_ymdhis (string)
  - Populated by: content controllers, TRUTH handlers, Crafty Syntax handlers.

- ui_state (array, optional)
  - UI flags (view modes, active tab, etc.).
  - Populated by: module-specific handlers as needed.

## Legacy compatibility

`render_main_layout` accepts legacy arguments (page body + content + metadata) and normalizes them into this schema.