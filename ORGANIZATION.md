---
lupopedia.headers:
  file_path_from_root: "ORGANIZATION.md"
  version_when_written: "4.0.88"
  last_modified_utc: "20260325203002"
  last_modified_system_version: "4.0.88"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  artifact_type: "documentation"
  artifact_kind: "organization_guide"
  purpose: "Root map of lupo-* directories and their responsibilities"
  delegation_chain: "cursor:root"
  web_path: "http://www.lupopedia.com/lupopedia/ORGANIZATION.md"
lupopedia.footer:
  last_verified: "20260325203002"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "cursor"
  orchestrator: "cursor:root"
---

# Lupopedia Directory Organization

This document explains the purpose of each root directory that uses the `lupo-` prefix.

## Core Runtime and App

- `lupo-app/`: Root application entry support and service overlay.
- `lupo-includes/`: Core runtime loader, modules, classes, and procedural helpers.
- `lupo-routes/`: Route maps and endpoint dispatch wiring.
- `lupo-api/`: API endpoints and API support scripts.
- `lupo-views/`: View layer resources.
- `lupo-templates/`: Template assets used by rendering flows.

## Data and Schema

- `lupo-database/`: SQL install scripts, migrations, seeds, import maps, and TOON files.
- `lupo-meta/`: Metadata-oriented project artifacts.
- `lupo-content/`: Content storage and content-facing resources.
- `lupo-collections/`: Collection-related data and structures.

## Agents, Actors, Channels

- `lupo-actors/`: Actor-specific resources, docs, tools, and needs.
- `lupo-agents/`: Agent configuration and capability definitions.
- `lupo-channels/`: Channel registries, thread assets, and channel tooling.
- `lupo-chats/`: Chat-oriented resources.
- `lupo-prompts/`: Prompt artifacts and prompt templates.
- `lupo-skills/`: Skill definitions and skill support artifacts.
- `lupo-rules/`: Canonical doctrine and rule enforcement files.

## Tooling, Operations, and Logs

- `lupo-scripts/`: Maintenance and automation scripts.
- `lupo-tools/`: Supporting tools and utility helpers.
- `lupo-bin/`: CLI binaries and command utilities.
- `lupo-install/`: Installation support assets.
- `lupo-config/`: Configuration files and atoms.
- `lupo-cache/`: Cache artifacts.
- `lupo-tmp/`: Temporary workspace files.
- `lupo-logs/`: Runtime and diagnostic logs.
- `lupo-sessions/`: Session storage artifacts.
- `lupo-backups/`: Backup snapshots.
- `lupo-uploads/`: User-uploaded files and upload staging.

## Admin and Research

- `lupo-admin/`: Admin-facing functionality and resources.
- `lupo-admin_sections/`: Admin panel section modules.
- `lupo-docs/`: Documentation, doctrine, and version-specific records.
- `lupo-research/`: Research workspaces and exploratory material.
- `lupo-images/`: Image assets.
- `lupo-archive/`: Archived legacy docs/scripts (excluded from git in this workspace).

## Notes

- Hidden tooling directories (for example `.cursor/`, `.kiro/`, `.windsurf/`) are IDE/agent control surfaces and intentionally keep their dot-prefixed naming.
- Root non-prefixed third-party/runtime directories may still exist for compatibility and can be migrated separately when safe.

## Footer Quick Read

Use the `lupopedia.footer` block to answer verification identity quickly:

- `verified_by.identity_type`: verifier authority type (`actor` or `agent`).
- `verified_by.actor_id`: canonical authority actor id for verification.
- `verified_by.agent_name_identity`: human-readable identity label when available.
- `verified_by.department_id_delta`: reserved for department-scoped override (`0` means none applied yet).
- `verified_via.type`: verification execution surface (`faucet` or `direct`).
- `verified_via.faucet_slug`: faucet used by execution path; use `none` for direct verification.

For this file now:

- Verified authority: actor `102`.
- Verifier identity label: Cursor IDE Agent (Lead Orchestration).
- Faucet used: `cursor`.

## Write Policy

Write to `lupo-*` folders by default. Do not place new operational artifacts in root.

- Tests: write to `lupo-tests/`.
- Channel work artifacts (status, summary, report, handoff): write to `lupo-channels/<channel_id>/threads/<thread_id>/`.
- Documentation: write to `lupo-docs/` (except core root docs).
- Scripts and tools: write to `lupo-scripts/` and `lupo-tools/`.

Root is reserved for stable project entry surfaces and required runtime files.

Allowed root documentation surfaces:

- `README.md`
- `CHANGELOG.md`
- `CHANGELOG_ARCHIVE.md`
- `plan.md`
- `report.md`
- `TODO.md`

If channel/thread artifact context exists but no thread folder is present, create the thread folder first, then write the artifact there.
