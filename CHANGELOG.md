---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: CHANGELOG.md
  web_path: https://www.lupopedia.com/lupopedia/CHANGELOG.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/root-changelog.toon
  atoms_toon: null
  transcript_jsonl: 0/development/changelog-index
  artifact_type: documentation
  artifact_kind: changelog
  channel_key: development
  federation_node_id: 0
  thread_key: changelog-index
  lupopedia.schema: changelog
  prd_cluster: 00_B_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS
  title: Changelog Index - Lupopedia
  summary: Root changelog index routing updates to version-specific directories (4.0.85+).
---
# CHANGELOG

## IMPORTANT: Version-specific documentation (4.0.85+)

**As of 4.0.85**, detailed release notes live under **`lupo-docs/versions/<version>/`**, not in this root file.

### For changes at version 4.0.85 and above

- **Current version changelog**: [`lupo-docs/versions/4.1.4/CHANGELOG.md`](lupo-docs/versions/4.1.4/CHANGELOG.md)
- **Structured metadata** (PLAN, TODO, TASK_REGISTRY, and so on): inside the same **`lupo-docs/versions/<version>/`** tree

### For changes before 4.0.85

- **Historical archive**: [`CHANGELOG_ARCHIVE.md`](CHANGELOG_ARCHIVE.md) (v0.0 through v4.0.84)

### Where release notes live (4.0.85+)

**Do not append detailed implementation notes to this root file.** The canonical changelog for each patch line is:

**[`lupo-docs/versions/<version>/CHANGELOG.md`](lupo-docs/versions/4.0.99/CHANGELOG.md)**

This root **`CHANGELOG.md`** is an **index and router** only.

### How to read this document

1. **Latest patch-line changes** -> open **[`lupo-docs/versions/4.0.99/CHANGELOG.md`](lupo-docs/versions/4.0.99/CHANGELOG.md)** (adjust the folder when the working version moves).
2. **History before 4.0.85** -> **`CHANGELOG_ARCHIVE.md`**
3. **Full version context** -> browse **`lupo-docs/versions/<version>/`** (PLAN, TODO, TASK_REGISTRY, CONTRADICTIONS, DOCTRINE, and so on)

### Enforcement

Each **4.0.85+** version has its own directory; the root file does **not** override version-folder authority. Version-folder changelog discipline (chronological order, **append new entries at the bottom**) is defined in **`lupo-docs/doctrine/VERSIONING_DOCTRINE.md`** **§10.1**.

---

## Version directory index

| Version | Location | Status |
|---------|----------|--------|
| 4.1.4 | [`lupo-docs/versions/4.1.4/`](lupo-docs/versions/4.1.4/) | Active |
| 4.1.3 | [`lupo-docs/versions/4.1.3/`](lupo-docs/versions/4.1.3/) | Archived |
| 4.1.2 | [`lupo-docs/versions/4.1.2/`](lupo-docs/versions/4.1.2/) | Archived |
| 4.1.1 | [`lupo-docs/versions/4.1.1/`](lupo-docs/versions/4.1.1/) | Archived |
| 4.1.0 | [`lupo-docs/versions/4.1.0/`](lupo-docs/versions/4.1.0/) | Planning (auto-installer / PRD 33 gate) |
| 4.0.99 | [`lupo-docs/versions/4.0.99/`](lupo-docs/versions/4.0.99/) | Archived |
| 4.0.98 | [`lupo-docs/versions/4.0.98/`](lupo-docs/versions/4.0.98/) | Archived |
| 4.0.97 | [`lupo-docs/versions/4.0.97/`](lupo-docs/versions/4.0.97/) | Archived |
| 4.0.96 | [`lupo-docs/versions/4.0.96/`](lupo-docs/versions/4.0.96/) | Archived |
| 4.0.95 | [`lupo-docs/versions/4.0.95/`](lupo-docs/versions/4.0.95/) | Archived |
| 4.0.94 | [`lupo-docs/versions/4.0.94/`](lupo-docs/versions/4.0.94/) | Archived |
| 4.0.93 | [`lupo-docs/versions/4.0.93/`](lupo-docs/versions/4.0.93/) | Archived |
| 4.0.92 | [`lupo-docs/versions/4.0.92/`](lupo-docs/versions/4.0.92/) | Archived |
| 4.0.91 | [`lupo-docs/versions/4.0.91/`](lupo-docs/versions/4.0.91/) | Archived |
| 4.0.90 | [`lupo-docs/versions/4.0.90/`](lupo-docs/versions/4.0.90/) | Archived |
| 4.0.89 | [`lupo-docs/versions/4.0.89/`](lupo-docs/versions/4.0.89/) | Archived |
| 4.0.88 | [`lupo-docs/versions/4.0.88/`](lupo-docs/versions/4.0.88/) | Archived |
| 4.0.87 | [`lupo-docs/versions/4.0.87/`](lupo-docs/versions/4.0.87/) | Archived |
| 4.0.86 | [`lupo-docs/versions/4.0.86/`](lupo-docs/versions/4.0.86/) | Archived |
| 4.0.85 | [`lupo-docs/versions/4.0.85/`](lupo-docs/versions/4.0.85/) | Transition point (version-directory model introduced) |
| 4.0.84 | [`lupo-docs/versions/4.0.84/`](lupo-docs/versions/4.0.84/) | Archived |
| 4.0.80 | [`lupo-docs/versions/4.0.80/`](lupo-docs/versions/4.0.80/) | Archived |
| < 4.0.85 (flat history) | [`CHANGELOG_ARCHIVE.md`](CHANGELOG_ARCHIVE.md) | Historical |

**Archive tree:** Older or superseded trees may also appear under [`lupo-docs/versions/archive/`](lupo-docs/versions/archive/).

### Why this model exists

- Change tracking spans database, doctrine, tasks, and contradictions; a single flat root list is easy to skew.
- Version directories hold authoritative, typed artifacts per patch line.
- Root **`CHANGELOG.md`** stays a stable router for humans and agents.
