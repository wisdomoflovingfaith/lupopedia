# file: LUPOPEDIA HEADERS — session: L-LUPO-PLAN — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS
---
flare.headers:
  system_version: "4.0.68"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
  web_path: "http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS"
  title: "LUPOPEDIA HEADERS"
  session_name: "L-LUPO-PLAN"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "index"
---
# LUPOPEDIA HEADERS

**LUPOPEDIA HEADERS** are the canonical metadata protocol name from **4.0.68** onward. They replace **FLARE** as the system name while preserving FLARE’s logical block structure and doctrinal lineage.

- **Storage:** `lupo_metadata` table (with table prefix), structured as rows (root → blocks → properties → edges/mappings/actions). No single YAML blob column; no dedicated presentation columns.
- **Schema additions:** Only `channel_id`, `parent_metadata_id`, `class_name`.
- **Format:** Identity line first, then `---`, YAML blocks in canonical order, then `---`, then body.

## Docs in this folder

| Document | Purpose |
|----------|---------|
| [LUPOPEDIA_HEADERS_PLAN.md](./LUPOPEDIA_HEADERS_PLAN.md) | Authoritative plan: schema, storage model, block order, channel support, version rule. |
| [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) | Markdown file structure and required header fields. |
| [LUPOPEDIA_HEADERS_MIGRATION.md](./LUPOPEDIA_HEADERS_MIGRATION.md) | Incremental migration from FLARE, validator and tooling expectations. |

## Quick reference

- **First line:** `# file: {title} — session: {session_name} — delegation: {delegation_chain} — web_path: {web_path}`
- **Then:** `---` → YAML (canonical order) → `---` → body
- **Blocks (FLARE):** flame.init, flare.conditional, flare.headers, flare.edges, flare.footer, flame.see, flame.close
- **Lookup:** by `entity_type` + `entity_id` and/or `channel_id`
