---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: rules/root/flip-doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/rules/root/flip-doctrine.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: rule
  artifact_kind: cursor_doctrine
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: cursor_rule
  prd_cluster: null
  title: null
  summary: null
---
# file: Rule — LUPOPEDIA HEADERS (replaces FLIP/FLARE) — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/flip-doctrine

# LUPOPEDIA HEADERS (replaces FLIP / FLARE)

**FLIP** and **FLARE** have been replaced by **LUPOPEDIA HEADERS** as the canonical metadata system name from 4.0.68 onward.

## What to read

- **Canonical doctrine:** docs/doctrine/LUPOPEDIA_HEADERS/README.md — Overview, storage in `lupo_metadata`, schema.
- **Format and file structure:** [LUPOPEDIA_HEADERS_FORMAT.md](../../docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md) — Markdown file structure, required fields, database and channel resolution.
- **Plan and block order:** [LUPOPEDIA_HEADERS_PLAN.md](../../docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md) — Authoritative plan, block order, channel support.
- **Validators and tooling:** [VALIDATORS_AND_TOOLING.md](../../docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md) — How headers work with the database (read/write via `lupo_metadata`) and how headers can be written to the file (export/import).

## Database and writing to file

- **Storage:** Headers are stored in the **`lupo_metadata`** table "metadata" (with table prefix of "lupo_"). Rows are structured as root → blocks → properties → edges/mappings/actions. Lookup by `entity_type` + `entity_id` and/or `channel_id`.
- **Writing to file:** Headers can be **written to the Markdown file** as YAML between `---` delimiters. Tooling can export from `lupo_metadata` to YAML in the file, and import from file YAML into `lupo_metadata`. See VALIDATORS_AND_TOOLING.md for export/import behavior.

## Behavior (unchanged intent)

- Infer file identity, doctrine, and meaning from the header only; do not guess or hallucinate.
- Treat absence of a field as absence. Respect header_atoms and resolve from project atom source.
- When adding or editing a header, follow LUPOPEDIA HEADERS format and version rules (see LUPOPEDIA_HEADERS_FORMAT.md).

This rule is permanent. Use **LUPOPEDIA HEADERS** and the docs above; do not reference FLIP or FLARE as the current system name.
