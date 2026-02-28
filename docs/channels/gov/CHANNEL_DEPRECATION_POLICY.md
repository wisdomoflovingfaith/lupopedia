# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\gov\CHANNEL_DEPRECATION_POLICY.md"
  file_hash: "54887bc4799be88f69199d5d673b9b9dc76e43df7fa410d6771edd02b82d9b51"
  file_path_from_root: "docs\channels\gov\CHANNEL_DEPRECATION_POLICY.md"
  file_hash: "7351b84c207fe614ad4ae1aa01bfa999607a32dd133a8f88814fde261364f40c"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for CHANNEL_DEPRECATION_POLICY.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "gov", "channel_deprecation_policymd"]
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
file.name: "CHANNEL_DEPRECATION_POLICY.md"
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created Channel Deprecation Policy for governance doctrine coverage."
tags:
  categories: ["doctrine", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["gov", "dev"]
file:
  title: "Channel Deprecation Policy"
  description: "Policy for deprecating channels while preserving history and provenance."
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

# Channel Deprecation Policy

## Purpose

Policy for deprecating channels while preserving history and provenance.

## Scope

This doctrine applies to governance operations, policy decisions, migrations, and channel governance.

## Governance Rules

- Deprecation must preserve content and edges for audit.
- Status changes require documentation and changelog entry.
- No silent deletion of historical channel records.

## Enforcement

Violations require documentation, remediation, and governance review before proceeding.

## References

- ../../doctrine/VERSIONING_DOCTRINE.md
- ../doctrine/METADATA_GOVERNANCE.md
- ../overview/versioning/CHANGELOG.md