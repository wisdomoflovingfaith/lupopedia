# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "dialogs_old_replaced_by_channels\operations\2026-01-22_repo_organization.md"
  file_hash: "0ec808155934e9cd2cc11f174e740a0cc4a3836952bf7ae6703ab6c5e28b617d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 2026-01-22_repo_organization.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["dialogs_old_replaced_by_channels", "operations", "2026-01-22_repo_organizationmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
operation: repo_organization
date: 2026-01-22
mode: safe
status: completed_phases_1_to_7
notes:
  - No schema changes
  - No version changes
  - No doctrine rewrites
  - No Git actions
---

# Repo Organization Log (2026-01-22)

## Phase 1
- Moved root-level Markdown files into channel taxonomy under `docs/channels/*`.
- Moved duplicate roots to `legacy/duplicates/`:
  - `ASK_HUMAN_WOLFIE_LUPOPEDIA_20-26.md`
  - `SYSTEM_INTEGRATION_TESTING_DOCTRINE.md`

## Phase 2
- Moved `doctrine/` contents into `docs/channels/doctrine/legacy-import/` (including `deprecated/` and `emotional_frameworks/`).

## Phase 3
- Moved agent-1 documentation into `docs/channels/agents/agent-1/`.

## Phase 4
- Moved schema reports into `docs/channels/schema/reports/`.
- Moved migration analysis docs into `docs/channels/schema/migrations/analysis/`.

## Phase 5
- Updated internal Markdown links to reflect moved files.

## Phase 6
- Created missing `README.md` and `INDEX.md` stubs for channel subfolders.

## Phase 7
- Removed empty directories after moves.
