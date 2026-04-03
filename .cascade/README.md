---
lupopedia.init:
  file_identity: "README.md"
  artifact_type: "cascade_guide"
  artifact_kind: "documentation"
  namespace: "cascade"
  system_version: "4.0.76"
  orchestrator_actor: "cascade"
  delegation_chain: "cascade:captain"

lupopedia.headers:
  actor_id: 105
  actor_name: "cascade"
  delegation_chain: "cascade:captain"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "cascade_guide"
  file_path_from_root: ".cascade/README.md"
  last_modified_utc: "20260402"
  system_version: "4.0.76"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Guide for Cascade rule system and propagation"

lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260402"
  last_verified_by: "cascade"
  orchestrator: "cascade"
  next_action:
    - "Run propagation: php lupo-scripts/propagate_agent_rules.php --target=cascade"
---

# Cascade Rules Guide

This directory contains Cascade-specific rule artifacts derived from canonical root rules.

## Files

- **lupopedia_rules.json** - Machine-readable rule index
- **rules/** - Individual rule files with LUPOPEDIA HEADERS

## Propagation

Run: `php lupo-scripts/propagate_agent_rules.php --target=cascade`

## Source

All rules are derived from canonical root rules in `lupo-rules/root/`.
See [lupo-rules/root/README.md](../../../lupo-rules/root/README.md) for canonical rule documentation.
