# LUPOPEDIA HEADERS (replaces FLARE) — see http://www.lupopedia.com/flare_apply
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/FLARE/FLARE_APPLY.md"
  web_path: "http://www.lupopedia.com/flare_apply"
  last_updated_utc: "20260304"
  system_version: "4.0.57"
  channel_id: 1
  actor_id: 1003
  artifact_type: "documentation"
  purpose: "Documentation for lupo-tools/flare_apply.py — FLARE header application and federation fixes"
  mood_rgb: "4169E1"
  traits: ["flare", "tooling", "v4.0.57"]
  tags: ["flare_apply", "flare", "doctrine", "tooling"]

lupopedia.see:
  mappings:
    - ["lupo-docs/doctrine/FLARE/FLARE_APPLY.md", "http://www.lupopedia.com/flare_apply"]
    - ["lupo-docs/doctrine/FLARE/FLARE_APPLY.md", "https://www.lupopedia.com/flare_apply"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-tools/flare_apply.py", type: "documents", weight: 1.0 }
    - { to: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "lupo-tools/flare_validate.py", type: "references", weight: 0.8 }
  semantic_tags: ["flare", "tooling", "doctrine"]

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "cursor"
---

# FLARE Apply Tool Documentation

This document describes **lupo-tools/flare_apply.py**, the Lupopedia tool for applying and refreshing FLARE headers on Markdown files.

## Purpose of flare_apply

- **Apply FLARE headers** to Markdown files that lack them.
- **Refresh existing headers** (path normalization, `web_path`, `lupopedia.see`) when run with `--refresh`.
- **Batch process** specific directories with `--batch <dir>`.
- Support **federation header fixes**: unique `web_path` with directory context to avoid URL collisions (e.g. `tasks/active/task-001` vs `tasks/completed/task-001`).
- **Path normalization:** All `file_path_from_root` and path-derived URLs use forward slashes; backslashes are normalized to avoid YAML parse errors.

## CLI usage examples

```bash
# Apply headers only to files that don't have one
python lupo-tools/flare_apply.py

# Force regeneration of existing FLARE headers (path normalization, web_path, etc.)
python lupo-tools/flare_apply.py --refresh

# Limit processing to a subdirectory
python lupo-tools/flare_apply.py --batch lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks

# Refresh headers in a batch directory
python lupo-tools/flare_apply.py --refresh --batch lupo-docs/doctrine/FLARE
```

## Batch header regeneration

- Use `--batch <dir>` to restrict which files are processed. The script still writes the **full** list of discovered Markdown paths to `flare_md_index.txt` so validators and other tools see a complete index.
- Use `--refresh` to replace existing FLARE header blocks with newly generated ones (path normalization, `web_path`, YAML validation).

## Federation header fixes

- **URL uniqueness:** When the same filename appears in multiple directories (e.g. `task-001.md` in `tasks/active/` and `tasks/completed/`), the derived `web_path` includes directory context so each file has a unique canonical URL.
- **Base URL:** For federation node 0, the default base URL is `http://www.lupopedia.com`; other nodes may use `LUPO_NODE_BASE_URL` when set.

## Path normalization

- All paths in generated headers use **forward slashes** (`/`). Backslashes in `file_path_from_root` are normalized so YAML parses cleanly and URLs are consistent across platforms.

## Safety notes

- The tool validates generated YAML after building a header; invalid YAML is not written.
- Existing content below the FLARE block is preserved when replacing a header in `--refresh` mode.
- Do not break LUPO_APP_DIR, OAuth guards, or existing federation URL logic; this tool is additive for header metadata only.

## Related docs

- **FLARE_DOCTRINE** — Core FLARE protocol: `lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md` (see http://www.lupopedia.com/doctrine/FLARE/FLARE_DOCTRINE).
- **Federation refinement** — `lupo-docs/status/FLARE_FEDERATION_REFINEMENT_4.0.57.md`.
- **flare_validate.py** — Validates FLARE headers and federation URL uniqueness.
