---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_MIGRATION.md"
  web_path: "[web_path](http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_MIGRATION)"
  title: "LUPOPEDIA HEADERS Migration"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  session_name: "L-LUPO-ROOT-CURSOR"
  actor_id: 1003
  actor_name: "cursor"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  federation_node_id: 1
  context_source: "default"
  department_id: 0
  thread_id: 0
  agent_name: "cursor"
  actor_type: "agent"
  actor_nature: "ide"
  human_actor_name: "root"
  paired_actor_id: 10000
---
# file: LUPOPEDIA HEADERS Migration — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_MIGRATION

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
4. **Optionally** rewrite the file to the LUPOPEDIA format: first line `---`, then YAML blocks, then `---`, then identity line `# file: ...`, then body.

---

## 3. Validator behavior

- **Legacy FLARE:** Accept files that start with `---` and YAML then identity line, or identity line then `---` and YAML, per existing FLARE doctrine.
- **LUPOPEDIA 4.0.68+:** Enforce first line `---`, then YAML in canonical block order, then `---`, then identity line `# file: ...`, then body.
- **Block order:** When validating or exporting, enforce canonical order: lupopedia.init → lupopedia.conditional → lupopedia.headers → lupopedia.session → lupopedia.edges → lupopedia.footer → lupopedia.see → lupopedia.close (or legacy lupopedia.init, flare.*, lupopedia.see, lupopedia.close). Session fields (session_id, session_name, etc.) belong in lupopedia.session.
- **Channel resolution:** Support loading headers by `channel_id` as well as by `entity_type` + `entity_id`. Optional **channel_name** (human-readable) and **thread_name** (when thread-scoped) may be stored as properties for display; resolution remains by channel_id (and thread_id when applicable).

---

## 4. Tooling expectations

- **Export:** Build canonical YAML header block from `lupo_metadata` rows (root → blocks → properties → edges/mappings/actions).
- **Import:** Parse FLARE/LUPOPEDIA YAML into the same row structure and persist to `lupo_metadata`.
- **Lookup:** Resolve headers by entity and/or channel; do not assume entity-only resolution.

---

## 5. Caveats

- Do not claim that all repo files are migrated to LUPOPEDIA HEADERS at 4.0.68 release; document current state accurately.
- Rendered YAML in files is an export artifact; the canonical source of truth for LUPOPEDIA-backed headers is `lupo_metadata`.
