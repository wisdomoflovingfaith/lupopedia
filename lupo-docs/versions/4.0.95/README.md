---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: version_readme
  when_updated: "20260407172944"
  file_path_from_root: "lupo-docs/versions/4.0.95/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.95/README.md"
  last_modified_utc: "20260407172944"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "version_readme"
  artifact_kind: "version"
  purpose: "Version overview for Lupopedia 4.0.95 — finalized line"
  tags: ["version", "4.0.95", "finalized", "cursor"]
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.94/README.md"
      type: references
      weight: 1.0
      reason: "Prior line — 4.0.94 packaging readiness"
    - to: "lupo-docs/versions/4.0.94/VERSION_SUMMARY.md"
      type: references
      weight: 1.0
      reason: "What shipped in 4.0.94 scope"
    - to: "FOR_CLAUDE_CODE_2026_04_06.md"
      type: references
      weight: 0.95
      reason: "2026-04-06 agent sync summary"
    - to: "lupo-docs/versions/4.0.95/TODO.md"
      type: references
      weight: 1.0
      reason: "Closed registry (no open items)"
    - to: "lupo-docs/versions/4.0.96/TODO.md"
      type: references
      weight: 1.0
      reason: "Successor backlog"
lupopedia.footer:
  last_verified: "20260407172944"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.95/README.md — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.95/README.md

# Lupopedia 4.0.95 — finalized

**Status:** **Finalized** (closed line, UTC `20260407172944`)  
**Parent documentation line:** 4.0.94  
**Successor:** **[4.0.96](../4.0.96/TODO.md)** (active backlog)

---

## Overview

**4.0.95** carried deferred work from **4.0.94**, autoinstaller / packaging feedback, schema/runtime alignment, and memory-model / filesystem documentation. The line is now **closed**; remaining open work was migrated to **`lupo-docs/versions/4.0.96/TODO.md`**.

---

## Documentation batch (2026-04-06)

Canonical detail: **[CHANGELOG.md](CHANGELOG.md)** for this date.

- **Doctrine / PRD alignment** — Department 0/1, Crafty import, actor learning boundaries, installation and widget/collections narrative aligned across **PRD 00, 01, 05, 15, 28, 33** (plus cross-references).
- **Root README** — “Why Lupopedia Is Built Differently” and related sections; links to PRDs and pseudocode.
- **Reference** — **`lupo-docs/reference/architect_background.md`** (factual architect timeline).
- **Agent sync** — **`FOR_CLAUDE_CODE_2026_04_06.md`** (repo root) summarizes the batch for AI tools and lists review targets (**PRD 00, 01, 05, 15, 17, 28, 33**, README, `architect_background.md`).

---

## Key focus areas (completed or migrated)

| Area | Status |
|------|--------|
| **Deprecation warnings** | Tracked for **4.0.96** (`AuthSessionManager`, `ToonSchemaCache`) |
| **UI cleanup** | Tracked for **4.0.96** (externalize CSS/JS from `main_layout.php`) |
| **PRD 30 rewrite** | Tracked for **4.0.96** |
| **Author block migration** | Tracked for **4.0.96** |
| **PostgreSQL** | Tracked for **4.0.96** / 4.1.0 scope |
| **Tests** | Tracked for **4.0.96** |

---

## Deferred / backlog (successor line)

Open items that were listed for this line now live in **[../4.0.96/TODO.md](../4.0.96/TODO.md)**.

---

## Release criteria (line closeout)

Draft release criteria that were still open at finalize were **migrated** to **`lupo-docs/versions/4.0.96/TODO.md`** (see **From 4.0.95/README.md — release criteria**). No unchecked criteria remain documented **only** in this file.

This output complies with Lupopedia Constitutional Root Rules.
