# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/gov/RESTORATIVE_GOVERNANCE_DOCTRINE.md"
  file_hash: "bd7f28244245fb6b5373bcf3c9a0c69deccefdec0d96e414071a594147baa3de"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\channels\gov\RESTORATIVE_GOVERNANCE_DOCTRINE.md"
  file_hash: "8aa0b164b3445786b85dc304fb779900664aa95e153ddaf5e694ec8213228ded"
  file_path_from_root: "lupo-docs\channels\gov\RESTORATIVE_GOVERNANCE_DOCTRINE.md"
  file_hash: "ee790fd98225a7e4ff21f2a3960613d5b55db20a267f2c848590a9bf172797eb"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for RESTORATIVE_GOVERNANCE_DOCTRINE.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "gov", "restorative_governance_doctrinemd"]
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
file.name: "RESTORATIVE_GOVERNANCE_DOCTRINE.md"
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created Restorative Governance Doctrine for governance doctrine coverage."
tags:
  categories: ["doctrine", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["gov", "dev"]
file:
  title: "Restorative Governance Doctrine"
  description: "Governance response and remediation when rules are violated."
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

# Restorative Governance Doctrine

## Purpose

Governance response and remediation when rules are violated.

## Scope

This doctrine applies to governance operations, policy decisions, migrations, and channel governance.

## Governance Rules

- Document incidents, root cause, and corrective action in changelog.
- Prefer reversibility, rollback, and reconciliation over deletion.
- Publish a clear remediation plan with validation steps.

## Enforcement

Violations require documentation, remediation, and governance review before proceeding.

## References

- ../doctrine/PATCH_DISCIPLINE.md
- ../../doctrine/VERSIONING_DOCTRINE.md
- ../overview/versioning/CHANGELOG.md
