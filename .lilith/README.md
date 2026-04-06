---
lupopedia.headers:
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:root"
  lupopedia.version: "4.0.79"
  lupopedia.schema: "lilith_guide"
  file_path_from_root: ".lilith/README.md"
  last_modified_utc: "20260406"
  system_version: "4.0.79"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Lilith rule propagation status and guidance"
---

# Lilith Rules Guide

This directory contains Lilith-specific rule artifacts and non-interference policy derived from canonical root rules in `lupo-rules/root/`.

## Propagation

Run: `php lupo-scripts/propagate_agent_rules.php --target=lilith`

## Source

All rules are derived from canonical root rules in `lupo-rules/root/`.
See [lupo-rules/root/README.md](../lupo-rules/root/README.md) for canonical rule documentation.
