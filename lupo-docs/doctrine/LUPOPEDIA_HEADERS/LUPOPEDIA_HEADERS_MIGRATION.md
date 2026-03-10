# file: LUPOPEDIA HEADERS Migration — session: L-LUPO-PLAN — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_MIGRATION
---
flare.headers:
  system_version: "4.0.68"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_MIGRATION.md"
  web_path: "http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_MIGRATION"
  title: "LUPOPEDIA HEADERS Migration"
  session_name: "L-LUPO-PLAN"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
---
# LUPOPEDIA HEADERS — Incremental migration from FLARE

**Version:** 4.0.68+

---

## 1. Principle

Migration from FLARE-headed artifacts to LUPOPEDIA HEADERS is **incremental**, not an instant cutover.

- **Existing FLARE-headed** Markdown and channel artifacts remain valid.
- **Validators** MUST accept both legacy FLARE format and 4.0.68+ LUPOPEDIA-backed artifacts during transition.
- **New or modified** artifacts from 4.0.68 onward SHOULD use LUPOPEDIA HEADERS rules and storage in `lupo_metadata`.

---

## 2. Storage migration path

1. **Parse** existing FLARE YAML headers (from files or legacy content).
2. **Normalize** into the row-based model: root row → block rows → property rows → repeating-structure rows (edges, mappings, actions).
3. **Insert** into `lupo_metadata` with `channel_id`, `parent_metadata_id`, and `class_name` set appropriately.
4. **Optionally** rewrite the file to the LUPOPEDIA format (identity line first, then `---`, YAML, `---`, body).

---

## 3. Validator behavior

- **Legacy FLARE:** Accept files that start with `---` and YAML then identity line, or identity line then `---` and YAML, per existing FLARE doctrine.
- **LUPOPEDIA 4.0.68+:** Enforce identity line first, then `---`, then YAML in canonical block order, then `---`, then body.
- **Block order:** When validating or exporting, enforce canonical order: flame.init → flare.conditional → flare.headers → flare.edges → flare.footer → flame.see → flame.close.
- **Channel resolution:** Support loading headers by `channel_id` as well as by `entity_type` + `entity_id`.

---

## 4. Tooling expectations

- **Export:** Build canonical YAML header block from `lupo_metadata` rows (root → blocks → properties → edges/mappings/actions).
- **Import:** Parse FLARE/LUPOPEDIA YAML into the same row structure and persist to `lupo_metadata`.
- **Lookup:** Resolve headers by entity and/or channel; do not assume entity-only resolution.

---

## 5. Caveats

- Do not claim that all repo files are migrated to LUPOPEDIA HEADERS at 4.0.68 release; document current state accurately.
- Rendered YAML in files is an export artifact; the canonical source of truth for LUPOPEDIA-backed headers is `lupo_metadata`.
