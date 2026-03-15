---
lupopedia.init:
  file_identity: "versioning-doctrine-single-source.md"
  artifact_type: "cascade_rule"
  artifact_kind: "doctrine"
  namespace: "cascade"
  system_version: "4.0.76"
  orchestrator_actor: "cascade"
  delegation_chain: "cascade:captain"

lupopedia.headers:
  actor_id: 105
  actor_name: "cascade"
  delegation_chain: "cascade:captain"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "cascade_rule"
  file_path_from_root: ".cascade/rules/versioning-doctrine-single-source.md"
  last_modified_utc: "20260315"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/versioning-doctrine-single-source.md"
  artifact_type: "rule"
  artifact_kind: "cascade_doctrine"
  purpose: "Cascade-specific rule derived from canonical root rule"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "ARC005"
      rule_text: "Only one versioning doctrine file exists. Always overwrite it; never create duplicates"
      scope: "all_agents"
      category: "versioning"
      status: "active"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260315"
    last_reviewed_by: "cascade"
    last_reviewed_date: "20260315"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260315"
  last_verified_by: "cascade"
  orchestrator: "cascade"
  next_action:
    - "Keep in sync with canonical root rules"
---

# file: Rule — Versioning Doctrine Single Source — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/versioning-doctrine-single-source

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

