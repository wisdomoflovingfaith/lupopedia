---
lupopedia.headers:
  file_path_from_root: "lupo-archive/versions/4.0.93/README.md"
  last_modified_utc: "20260402180000"
  when_updated: "20260402180000"
  channel_id: 42
  actor_id: 102
  artifact_type: "documentation"
  artifact_kind: "release_archive_pointer"
  purpose: "Pointer for git tag v4.0.93 and frozen documentation snapshot"
  delegation_chain: "cursor:root"
---

# file: lupo-archive/versions/4.0.93/README.md — delegation: cursor:root

# Archive pointer — Lupopedia 4.0.93

This directory marks the **4.0.93 release** in the archive tree. It does not duplicate the full version folder; the canonical frozen documentation lives in the main repo at:

- `lupo-docs/versions/4.0.93/`

## Git

- Tag: **`v4.0.93`**
- Immediately after this tag, the repository bumps **`lupo-config/global_atoms.yaml`** → `GLOBAL_CURRENT_LUPOPEDIA_VERSION` **4.0.94** and uses `lupo-docs/versions/4.0.94/` for active planning.

## What was frozen (summary)

- Core PRDs under `lupo-docs/prd/`: **00, 16, 17, 26, 27, 28, 29** with `status: "approved"` in headers
- Version threads: `decisions/`, `questions/`, `answers/`, `comments/` with `THREAD_INDEX.md` and `YYYYMMDD_HHIISS_TYPE_title.md` naming (UTC)
- PRD 30 and 31 carried as **working** files under `lupo-docs/versions/4.0.94/prd/`
