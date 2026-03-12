# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\gov\SAFETY_DOCTRINE.md"
  file_hash: "3a2697eaac5e0ebaeb72c5c1416651c0540ef9acec71b0017b9005bb10a04c72"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\gov\SAFETY_DOCTRINE.md"
  file_hash: "37299ae6c5fcf927868e22708b789424e4664bfe8f8a833a74755a6052af512e"
  file_path_from_root: "docs\channels\gov\SAFETY_DOCTRINE.md"
  file_hash: "81213f693034dac56c3d723ebea04c956c6ea84877f3762be881d7d09b8e389e"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for SAFETY_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "gov", "safety_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.name: "SAFETY_DOCTRINE.md"
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created Safety Doctrine for governance doctrine coverage."
tags:
  categories: ["doctrine", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["gov", "dev"]
file:
  title: "Safety Doctrine"
  description: "System-wide safety constraints that override convenience or optimization."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
in_this_file_we_have:
  - Purpose
  - Scope
  - Governance Rules
  - Enforcement
  - References
---

# Safety Doctrine

## Purpose

System-wide safety constraints that override convenience or optimization.

## Scope

This doctrine applies to governance operations, policy decisions, migrations, and channel governance.

## Governance Rules

- Safety overrides performance, novelty, or speed when in conflict.
- All agents comply with safety doctrine before executing actions.
- Safety checks must be documented in dialog or audit logs.

## Enforcement

Violations require documentation, remediation, and governance review before proceeding.

## References

- ../doctrine/SYSTEM_AGENT_SAFETY_DOCTRINE.md
- ../doctrine/AI_INTEGRATION_SAFETY_DOCTRINE.md
- ../doctrine/LUPOPEDIA_ACTOR_BASELINE_STATE_DOCTRINE.md