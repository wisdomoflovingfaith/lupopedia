---
lupopedia.headers:
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:captain"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "cursor_guide"
  file_path_from_root: ".cursor/README.md"
  last_modified_utc: "20260314"
  system_version: "4.0.75"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Guide for Cursor rule artifacts derived from canonical root rules"
  tags: ["cursor", "rules", "propagation", "doctrine", "4.0.75"]
---

# Cursor Rules — Propagation Guide

This directory contains **Cursor-specific rule artifacts** derived from the canonical Lupopedia root rules. The canonical source of truth is **not** this directory; it is:

**`lupo-rules/root/`**

All `.cursor` rule files and the rule index are **generated outputs**. Do not edit them as the source of doctrine; edit the root rules and re-run propagation.

## Files

- **lupopedia_rules.json** — Machine-readable rule index (id, text, enforcement, scope, source_path, slug). Used by tooling and the Cursor rules enforcement test.
- **rules/*.mdc** — One Cursor rule file per canonical root rule. Cursor IDE reads these for in-editor rule application. Format: Cursor frontmatter (`description`, `alwaysApply`) plus rule body from the root file.

## Propagation

To regenerate Cursor artifacts from the canonical root rules:

```bash
php lupo-scripts/propagate_agent_rules.php --target=cursor
```

This writes **only** to `.cursor/` and does not modify `.kiro/`, `.idea/`, or `.windsurf/`.

To regenerate all IDE targets (Cursor, Kiro, JetBrains, Windsurf):

```bash
php lupo-scripts/propagate_agent_rules.php
```

## Relationship to Other Agents

- **Root rules** (`lupo-rules/root/`) are shared. Kiro, Windsurf, JetBrains (Codex), and Cursor each consume them via the same propagation script with a target-specific flag.
- **Kiro** outputs: `.kiro/lupopedia_rules.json` (and optionally `.kiro/rules/` per Kiro specs).
- **Windsurf** outputs: `.windsurf/lupopedia_rules.json` and `.windsurf/rules/*.md` with LUPOPEDIA HEADERS.
- **Cursor** outputs: `.cursor/lupopedia_rules.json` and `.cursor/rules/*.mdc` with Cursor-native frontmatter.
- Parsing logic and in-memory rule struct (id, text, enforcement, scope, source_path, slug) are shared; only the written format and target directory differ.

## Validation

Run the Cursor rules enforcement test to verify that the propagated artifacts are complete and consistent with the root rules:

```bash
php lupo-tests/unit/cursor_rules_enforcement.php
```

The test checks: JSON loadable, rules array non-empty, required fields present, no duplicate rule IDs, and a corresponding `.cursor/rules/<slug>.mdc` file for each rule.

## Source

Canonical rule definitions and documentation: [lupo-rules/root/README.md](../lupo-rules/root/README.md).
