---
lupopedia.headers:
  actor_id: 100
  actor_name: "kiro"
  delegation_chain: "kiro:root"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "kiro_rule"
  file_path_from_root: ".kiro/rules/versioning-doctrine-single-source.md"
  last_modified_utc: "20260402"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/versioning-doctrine-single-source.md"
  artifact_type: "rule"
  artifact_kind: "kiro_doctrine"
---


# Versioning Doctrine — Single Source of Truth (PERMANENT)

Cursor MUST treat the versioning doctrine as a single canonical file. No duplicates or variants are allowed.

## Canonical file

- **Only valid path:** `lupo-docs/doctrine/VERSIONING_DOCTRINE.md`
- This is the **single source of truth** for versioning doctrine.

## Replacement rule

When producing or updating the versioning doctrine:

- **Replace** the content of `lupo-docs/doctrine/VERSIONING_DOCTRINE.md`.
- **Do NOT** create a new file with a different name.
- **Do NOT** use suffixes such as `_UPDATED`, `_FINALIZED`, `_REWRITE`, `_V2`, etc.
- Delete or archive any old duplicate if consolidating; then write only to the canonical file.
- Update any references in the repo to point to `lupo-docs/doctrine/VERSIONING_DOCTRINE.md`.

## Cursor internal rule

- **Never** create a new versioning doctrine file.
- **Always** overwrite the canonical file when updating doctrine content.
- **Always** keep the filename stable: `VERSIONING_DOCTRINE.md` in `lupo-docs/doctrine/`.
- **Never** generate suffixes or variants of the versioning doctrine.
- **Never** duplicate the versioning doctrine file.
- **Never** leave outdated versioning doctrine files in the repo.

This rule is permanent and applies to all future edits.
