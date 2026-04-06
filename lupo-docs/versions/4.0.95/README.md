---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: version_readme
  when_updated: "20260406062838"
  file_path_from_root: "lupo-docs/versions/4.0.95/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.95/README.md"
  last_modified_utc: "20260406062838"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "version_readme"
  artifact_kind: "version"
  purpose: "Version overview for Lupopedia 4.0.95"
  tags: ["version", "4.0.95", "planning", "cursor"]
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
    - to: "lupo-docs/versions/4.0.95/TODO.md"
      type: references
      weight: 1.0
      reason: "Deferred backlog for this line"
lupopedia.footer:
  last_verified: "20260406062838"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.95/README.md — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.95/README.md

# Lupopedia 4.0.95 — planning version

**Status:** Active (working line)  
**Parent documentation line:** 4.0.94  
**Runtime:** **`GLOBAL_CURRENT_LUPOPEDIA_VERSION`** is **4.0.95** as of UTC **`20260406062838`**.

---

## Overview

**4.0.95** carries **deferred** work from **4.0.94**, autoinstaller / packaging feedback, and follow-on improvements. **4.0.94** documentation remains the packaging-readiness snapshot; product development continues on this line.

---

## Key focus areas

| Area | Description |
|------|-------------|
| **Deprecation warnings** | Runtime notices for `AuthSessionManager`, `ToonSchemaCache` |
| **UI cleanup** | Externalize CSS/JS from `main_layout.php` |
| **PRD 30 rewrite** | Writing guide (not metadata spec) |
| **Author block migration** | Remaining root rules → `author` block |
| **PostgreSQL** | Installer portability (longer horizon) |
| **Tests** | Session metadata helpers; password-change flow integration |

---

## Deferred from 4.0.94

Full checklist: **[TODO.md](TODO.md)**

---

## Release criteria (draft)

- [ ] P3 backlog from 4.0.94 completed or consciously postponed
- [ ] P4 items scoped to 4.1.0 or implemented
- [ ] Softaculous packaging test **passed** for the tarball that represents the released patch line
- [ ] No open critical constitutional violations on audited surfaces

This output complies with Lupopedia Constitutional Root Rules.
