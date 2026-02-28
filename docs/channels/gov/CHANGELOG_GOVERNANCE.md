# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\gov\CHANGELOG_GOVERNANCE.md"
  file_hash: "311f8e3dc29e063206dc86501ed66fabed9fe0c1f9668f4e0fc82d4ecd2f9dd1"
  file_path_from_root: "docs\channels\gov\CHANGELOG_GOVERNANCE.md"
  file_hash: "b85c6c0bbbadfd030c575fc0aefcb56af3fa0cb79779a4d3d8cfd799ac7d017a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for CHANGELOG_GOVERNANCE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "gov", "changelog_governancemd"]
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
file.name: "CHANGELOG_GOVERNANCE.md"
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created Changelog Governance for governance doctrine coverage."
tags:
  categories: ["doctrine", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["gov", "dev"]
file:
  title: "Changelog Governance"
  description: "Rules for maintaining the canonical changelog."
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

# Changelog Governance

## Purpose

Rules for maintaining the canonical changelog.

## Scope

This doctrine applies to governance operations, policy decisions, migrations, and channel governance.

## Governance Rules

- Every governance change must be logged in the changelog.
- Entries must include what changed, why, and impact.
- Changelog is immutable history, not a rewrite target.

## Enforcement

Violations require documentation, remediation, and governance review before proceeding.

## References

- ../overview/versioning/CHANGELOG.md
- ../overview/logs/changelog_dialog.md
- ../../doctrine/VERSIONING_DOCTRINE.md