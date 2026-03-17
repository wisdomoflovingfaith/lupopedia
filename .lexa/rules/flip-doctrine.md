---
lupopedia.init:
  file_identity: "flip-doctrine.md"
  artifact_type: "lexa_rule"
  artifact_kind: "doctrine"
  namespace: "lexa"
  system_version: "4.0.76"
  orchestrator_actor: "lexa"
  delegation_chain: "lexa:captain"

lupopedia.headers:
  actor_id: 24
  actor_name: "lexa"
  delegation_chain: "lexa:captain"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "lexa_rule"
  file_path_from_root: ".lexa/rules/flip-doctrine.md"
  last_modified_utc: "20260317"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/flip-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "lexa_doctrine"
  purpose: "LEXA-specific rule derived from canonical root rule - Boundary Keeper enforcement"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "ARC001"
      rule_text: "FLIP/FLARE have been replaced by LUPOPEDIA HEADERS formatting"
      scope: "all_agents"
      category: "headers"
      status: "active"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260317"
    last_reviewed_by: "lexa"
    last_reviewed_date: "20260317"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260317"
  last_verified_by: "lexa"
  orchestrator: "lexa"
  next_action:
    - "Keep in sync with canonical root rules"
---

# file: Rule — LUPOPEDIA HEADERS (replaces FLIP/FLARE) — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/flip-doctrine

# LUPOPEDIA HEADERS (replaces FLIP / FLARE)

**FLIP** and **FLARE** have been replaced by **LUPOPEDIA HEADERS** as the canonical metadata system name from 4.0.68 onward.

## What to read

- **Canonical doctrine:** [lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md](../../lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) — Overview, storage in `lupo_metadata`, schema.
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

