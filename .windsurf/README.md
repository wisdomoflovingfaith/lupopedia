---
lupopedia.init:
  file_identity: "README.md"
  artifact_type: "windsurf_guide"
  artifact_kind: "documentation"
  namespace: "windsurf"
  system_version: "4.0.75"
  orchestrator_actor: "windsurf"
  delegation_chain: "windsurf:captain"

lupopedia.headers:
  actor_id: 101
  actor_name: "windsurf"
  delegation_chain: "windsurf:captain"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "windsurf_guide"
  file_path_from_root: ".windsurf/README.md"
  last_modified_utc: "20260315"
  system_version: "4.0.75"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Guide for Windsurf rule system and propagation"

lupopedia.footer:
  version: "4.0.75"
  last_verified: "20260315"
  last_verified_by: "windsurf"
  orchestrator: "windsurf"
  next_action:
    - "Run propagation: php lupo-scripts/propagate_agent_rules.php --target=windsurf"
---

# Windsurf Rules Guide

This directory contains Windsurf-specific rule artifacts derived from canonical root rules.

## Files

- **lupopedia_rules.json** - Machine-readable rule index
- **rules/** - Individual rule files with LUPOPEDIA HEADERS

## Propagation

Run: `php lupo-scripts/propagate_agent_rules.php --target=windsurf`

## Source

All rules are derived from canonical root rules in `lupo-rules/root/`.
See lupo-rules/root/README.md for canonical rule documentation.
