---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260404055811"
  file_path_from_root: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/README.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "33-softaculous-4-1-0-gate-implementation"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  parent_prd: "33_softaculous_certification_4_1_0_gate"
  artifact_type: "implementation"
  artifact_kind: "overview"
  purpose: "Implementation workspace for PRD 33 Softaculous certification and 4.1.0 release gate"
  tags:
    - "implementation"
    - "prd33"
    - "softaculous"
    - "4.1.0"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: implements
      weight: 1.0
      reason: "Parent PRD"
    - to: "lupo-docs/versions/4.0.94/TODO.md"
      type: references
      weight: 0.9
      reason: "Backlog traceability per PRD 33 Section 12"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md"
      type: references
      weight: 1.0
      reason: "Softaculous / FTP distribution package spec and build script pointer"
---

# Implementation: PRD 33 — Softaculous certification and 4.1.0 gate

## Overview

This folder tracks **implementation work**, **questions**, **decisions**, and **audit/status** artifacts for **[PRD 33](../../prd/33_softaculous_certification_4_1_0_gate.md)** (release gate: hosting certification + Crafty live-help parity).

## Status

| Field | Value |
|-------|--------|
| **PRD header status** | `planning` (see PRD `lupopedia.headers` until WOLFIE/product sets `approved`) |
| **Implementation phase** | Workspace initialized; coding per **PRD Section 12** + version **TODO** |
| **Reviewer** | LILITH audit recorded under **`status/`** and PRD **Section 13** |

## Folder layout

| Path | Role |
|------|------|
| **`status/`** | Rolling **review snapshots**, **audit imports**, operational state (see **`status/README.md`**) |
| **`decisions/`** | Formal **APPROVED** (or rejected) **decision** artifacts with **`DECISION_*`** naming |
| **`questions/`** | Structured uncertainty (critical / optimization / clarification) |
| **`answers/`** | Human answers to questions |
| **`comments/`** | Dialogue and notes |
| **`changelog.md`** | Changes to this implementation tree |
| **`SOFTACULOUS_PACKAGE_BUILD.md`** | **FTP-safe zip/tar spec**, dotfile rules, validation checklist, **`lupo-scripts/build_softaculous_package.sh`** |
| **`todo.md`** | Pointer to backlog + local stubs |
| **`authors.md`** | Contributors |
| **`edges.md`** | Cross-links |

## Related

- **Distribution / Softaculous package:** [SOFTACULOUS_PACKAGE_BUILD.md](SOFTACULOUS_PACKAGE_BUILD.md)
- **PRD 33:** [33_softaculous_certification_4_1_0_gate.md](../../prd/33_softaculous_certification_4_1_0_gate.md)
- **Backlog:** [lupo-docs/versions/4.0.94/TODO.md](../../versions/4.0.94/TODO.md)
- **Crafty reference:** `craftysyntax-reference/` (repository root)

---

This file complies with Lupopedia Constitutional Root Rules.
