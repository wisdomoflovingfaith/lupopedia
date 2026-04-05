---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260404220006"
  last_modified_utc: "20260404220006"
  file_path_from_root: "lupo-docs/implementations/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/README.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "implementations-index"
  actor_id: 102
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "index"
  purpose: "Index of lupo-docs/implementations workspaces"
  tags:
    - "implementations"
    - "index"
---

# Implementations index

Workspaces for **PRD-driven** implementation tracking.

## Cross-cutting agent reference (not PRD-scoped)

| Folder | Purpose |
|--------|---------|
| **security_audit_cursor_ide** | [IDE security audit checklist](security_audit_cursor_ide/README.md) — shared hosting, **PRD 00 §17** (**RULE 93.SECURITY**); LILITH / THOTH review hooks |

## Directory naming (read first)

Each folder under **`lupo-docs/implementations/`** that tracks a numbered PRD **must** be named **`{prd_file_stem}`** — the **same string** as the canonical PRD filename under **`lupo-docs/prd/`** **without** **`.md`** (e.g. **`36_rose_multi_persona_synthetic_dialog`** for **`36_rose_multi_persona_synthetic_dialog.md`**). **Do not** invent shorthand paths (e.g. **`prd_36_rose/`**). **Constitution:** **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** **§5.8**. **Full spec:** **[PRD 31 — Implementation folder guidelines](../prd/31_implementation_folder_guidelines.md)** (layout, **`decisions/`** / **`questions/`** / **`answers/`** / **`comments/`**, scaffold).

**Scaffold:** **`python lupo-scripts/scaffold_implementation.py --prd <n> --title "<slug>"`** — choose **`title`** so **`<n>_<slug>`** equals **`prd_file_stem`**. The script creates **`THREAD_INDEX.md`** in **`questions/`**, **`answers/`**, **`comments/`**, **`decisions/`**, and **`status/`** (see **PRD 31** scaffold section).

| Folder | PRD | Notes |
|--------|-----|--------|
| **36_rose_multi_persona_synthetic_dialog** | [PRD 36](../prd/36_rose_multi_persona_synthetic_dialog.md) | ROSE synthetic choir — **`lupo-docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md`**, constitution **§5.10.3** |
| **34_federation_node_semantic_network** | [PRD 34](../prd/34_federation_node_semantic_network.md) | Draft — federation trust, discovery, actor ratings; **no runtime work until PRD approved** |
| **33_softaculous_certification_4_1_0_gate** | [PRD 33](../prd/33_softaculous_certification_4_1_0_gate.md) | Softaculous / 4.1.0 gate; **`status/`** holds LILITH audit import |
| 30_channel_usage_patterns | PRD 30 | |
| 31_implementation_folder_guidelines | PRD 31 | |
| 25_departments_systems | PRD 25 | |
| 26_five_layer_documentation_architecture | PRD 26 | |
| 29_project_structure | PRD 29 | |
| security_audit_cursor_ide | (PRD 00 §17 companion) | IDE security checklist — not a numbered PRD stem |

## Template

- **`_template/`** — Canonical scaffold source per **PRD 31**; copied by **`scaffold_implementation.py`** into **`{prd_file_stem}/`**.

---

This file complies with Lupopedia Constitutional Root Rules.
