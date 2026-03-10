---
lupopedia.headers:
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  lupopedia.version: "1.0"
  lupopedia.schema: "cursor_rule"
  file_path_from_root: "lupo-rules/cursor/versioning-doctrine-single-source.md"
  web_path: "http://www.lupopedia.com/rules/cursor/versioning-doctrine-single-source"
  last_modified_utc: "20260310"
  system_version: "4.0.68"
  rule_name: "Versioning Doctrine — Single Source of Truth"
  rule_type: "constraint"
  artifact_type: "rule"
  artifact_kind: "cursor_doctrine"
  purpose: "Only one versioning doctrine file exists. Always overwrite it; never create duplicates or suffixed copies."
  tags: ["cursor", "versioning", "doctrine"]
  source_path: ".cursor/rules/versioning-doctrine-single-source.mdc"

lupopedia.footer:
  version: "4.0.68"
  last_verified: "20260310"
  last_verified_by: "wolfie"
---
# file: Rule — Versioning Doctrine Single Source — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/cursor/versioning-doctrine-single-source

# Versioning Doctrine — Single Source of Truth (PERMANENT)

Cursor MUST treat the versioning doctrine as a single canonical file. No duplicates or variants are allowed.

## Canonical file

- **Only valid path:** `docs/doctrine/VERSIONING_DOCTRINE.md`
- This is the **single source of truth** for versioning doctrine.

## Replacement rule

When producing or updating the versioning doctrine:

- **Replace** the content of `docs/doctrine/VERSIONING_DOCTRINE.md`.
- **Do NOT** create a new file with a different name.
- **Do NOT** use suffixes such as `_UPDATED`, `_FINALIZED`, `_REWRITE`, `_V2`, etc.
- Delete or archive any old duplicate if consolidating; then write only to the canonical file.
- Update any references in the repo to point to `docs/doctrine/VERSIONING_DOCTRINE.md`.

## Cursor internal rule

- **Never** create a new versioning doctrine file.
- **Always** overwrite the canonical file when updating doctrine content.
- **Always** keep the filename stable: `VERSIONING_DOCTRINE.md` in `docs/doctrine/`.
- **Never** generate suffixes or variants of the versioning doctrine.
- **Never** duplicate the versioning doctrine file.
- **Never** leave outdated versioning doctrine files in the repo.

This rule is permanent and applies to all future edits.
