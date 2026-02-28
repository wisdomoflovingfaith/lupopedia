# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\gov\CHANNEL_REGISTRY_GOVERNANCE.md"
  file_hash: "f0e46587f7b4d6a804194f4025cc0f1f5a80adc1d8d4ec9328c83c43bdab075b"
  file_path_from_root: "docs\channels\gov\CHANNEL_REGISTRY_GOVERNANCE.md"
  file_hash: "6d74f786342348745127afdde20e521fc51d7e8a1da4df97afd99231322b8f1d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for CHANNEL_REGISTRY_GOVERNANCE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "gov", "channel_registry_governancemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.name: "CHANNEL_REGISTRY_GOVERNANCE.md"
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created Channel Registry Governance for governance doctrine coverage."
tags:
  categories: ["doctrine", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["gov", "dev"]
file:
  title: "Channel Registry Governance"
  description: "Rules for maintaining the canonical channel registry."
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

# Channel Registry Governance

## Purpose

Rules for maintaining the canonical channel registry.

## Scope

This doctrine applies to governance operations, policy decisions, migrations, and channel governance.

## Governance Rules

- Registry is derived from lupo_channels and must be regenerated, not hand edited.
- Channel keys must be unique and follow naming rules.
- Registry changes require changelog entries.

## Enforcement

Violations require documentation, remediation, and governance review before proceeding.

## References

- ../dev-teams/governance/REGISTRY.md
- ../doctrine/CHANNEL_DOCTRINE.md
- ../agents/agent-1/doctrine/CHANNEL_MANIFEST_SPEC.md