---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  version_when_written: "4.0.94"
  when_updated: "20260402190000"
  file_path_from_root: "lupo-docs/versions/4.0.94/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/README.md"
  last_modified_utc: "20260402190000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-readme"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "version_readme"
  purpose: "Working version folder for Lupopedia 4.0.94 development"
  tags:
  - "version"
  - "4.0.94"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.93/README.md"
      type: references
      weight: 1.0
      reason: "Previous frozen release"
    - to: "lupo-docs/versions/4.0.94/PLAN.md"
      type: references
      weight: 1.0
      reason: "Current plan"
    - to: "lupo-docs/versions/4.0.94/prd/"
      type: references
      weight: 0.95
      reason: "Working PRDs (30, 31)"
    - to: "lupo-docs/prd/29_project_structure.md"
      type: references
      weight: 0.9
      reason: "Project layout including lupo-channels vs archive"
lupopedia.footer:
  last_verified: "20260402190000"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/README.md — delegation: cursor:root

# Lupopedia 4.0.94 (working)

This is the **active** version documentation directory. Release **4.0.93** is frozen under `lupo-docs/versions/4.0.93/`.

## Layout

Same structure as 4.0.93: `PLAN.md`, `TODO.md`, `CHANGELOG.md`, `edges.md`, `decisions/`, `questions/`, `answers/`, `comments/`, plus **`prd/`** for PRDs that are not part of the 4.0.93 frozen core set.

## Thread naming

Use `YYYYMMDD_HHIISS_TYPE_title.md` (UTC). Valid `TYPE` values: `DECISION`, `QUESTION`, `ANSWER`, `COMMENT`, `DIALOG`, `DIRECTIVE`. See `lupo-docs/versions/4.0.93/README.md` for the full table.

## Working PRDs

- `prd/30_prd_development_guide.md` — rewrite as a PRD writing guide
- `prd/31_context_system.md` — redesign (no parallel classification; must align with PRD 26)

## Channels on disk

- **Archive:** `lupo-channels_before_4_0_93/` — legacy channel files (read-only reference). It is **not** a full migration target; use **new** threads under `lupo-channels/` for documentation-system work and organization.
- **Layout PRD:** `lupo-docs/prd/29_project_structure.md` — top-level directory map (includes the archive row).
- **Channel PRD:** `lupo-docs/prd/02_channels_discussions.md` — threads, discussions, coordination semantics.
