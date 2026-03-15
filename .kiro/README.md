---
lupopedia.headers:
  actor_id: 100
  actor_name: "kiro"
  delegation_chain: "kiro:root"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "kiro_guide"
  file_path_from_root: ".kiro/README.md"
  last_modified_utc: "20260315"
  system_version: "4.0.75"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Guide for Kiro rule system and propagation"
---

# Kiro Rules Guide

This directory contains Kiro-specific rule artifacts derived from canonical root rules.

## Files

- **lupopedia_rules.json** - Machine-readable rule index
- **rules/** - Individual rule files with LUPOPEDIA HEADERS

## Propagation

Run: `php lupo-scripts/propagate_agent_rules.php --target=kiro`

## Source

All rules are derived from canonical root rules in `lupo-rules/root/`.
See [lupo-rules/root/README.md](../lupo-rules/root/README.md) for canonical rule documentation.
