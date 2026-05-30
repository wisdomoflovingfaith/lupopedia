---
lupopedia.headers:
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:root"
  lupopedia.version: "4.0.79"
  lupopedia.schema: "lilith_rule"
  file_path_from_root: ".lilith/rules/flip-doctrine.md"
  last_modified_utc: "20260411"
  system_version: "4.0.79"
  source_path: "lupo-rules/root/flip-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "lilith_doctrine"
  purpose: "Lilith-specific review and dissent rule derivative"
---

# file: Rule — LUPOPEDIA HEADERS (replaces FLIP/FLARE) — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/flip-doctrine

# LUPOPEDIA HEADERS (replaces FLIP / FLARE)

**FLIP** and **FLARE** have been replaced by **LUPOPEDIA HEADERS** as the canonical metadata system name from 4.0.68 onward.

## What to read

- **Canonical doctrine:** lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md — Overview, storage in `lupo_metadata`, schema.
- **Format and file structure:** [LUPOPEDIA_HEADERS_FORMAT.md](../../lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md) — Markdown file structure, required fields, database and channel resolution.
- **Plan and block order:** [LUPOPEDIA_HEADERS_PLAN.md](../../lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md) — Authoritative plan, block order, channel support.
- **Validators and tooling:** [VALIDATORS_AND_TOOLING.md](../../lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md) — How headers work with the database (read/write via `lupo_metadata`) and how headers can be written to the file (export/import).

## Database and writing to file

- **Storage:** Headers are stored in the **`lupo_metadata`** table "metadata" (with table prefix of "lupo_"). Rows are structured as root → blocks → properties → edges/mappings/actions. Lookup by `entity_type` + `entity_id` and/or `channel_id`.
- **Writing to file:** Headers can be **written to the Markdown file** as YAML between `---` delimiters. Tooling can export from `lupo_metadata` to YAML in the file, and import from file YAML into `lupo_metadata`. See VALIDATORS_AND_TOOLING.md for export/import behavior.

## Behavior (unchanged intent)

- Infer file identity, doctrine, and meaning from the header only; do not guess or hallucinate.
- Treat absence of a field as absence. Respect header_atoms and resolve from project atom source.
- When adding or editing a header, follow LUPOPEDIA HEADERS format and version rules (see LUPOPEDIA_HEADERS_FORMAT.md).

This rule is permanent. Use **LUPOPEDIA HEADERS** and the docs above; do not reference FLIP or FLARE as the current system name.

