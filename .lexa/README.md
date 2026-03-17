---
lupopedia.init:
  file_identity: "README.md"
  artifact_type: "lexa_guide"
  artifact_kind: "documentation"
  namespace: "lexa"
  system_version: "4.0.76"
  orchestrator_actor: "lexa"
  delegation_chain: "lexa:captain"

lupopedia.headers:
  actor_id: 24
  actor_name: "lexa"
  delegation_chain: "lexa:captain"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "lexa_guide"
  file_path_from_root: ".lexa/README.md"
  last_modified_utc: "20260317"
  system_version: "4.0.76"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Guide for LEXA rule system and boundary enforcement"

lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260317"
  last_verified_by: "lexa"
  orchestrator: "lexa"
  next_action:
    - "Run propagation: php lupo-scripts/propagate_agent_rules.php --target=lexa"
---

# LEXA Rules Guide

This directory contains LEXA-specific rule artifacts derived from canonical root rules.

LEXA (actor_id 24) is the Law Enforcement eXecution Agent - Boundary Keeper and Security Enforcer.

## Files

- **lupopedia_rules.json** - Machine-readable rule index
- **rules/** - Individual rule files with LUPOPEDIA HEADERS

## Propagation

Run: `php lupo-scripts/propagate_agent_rules.php --target=lexa`

## Source

All rules are derived from canonical root rules in `lupo-rules/root/`.
See [lupo-rules/root/README.md](../../../lupo-rules/root/README.md) for canonical rule documentation.
