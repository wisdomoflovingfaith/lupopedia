---
lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "implementation_plan"
  file_path_from_root: "plan_codex.md"
  last_modified_utc: "20260314"
  system_version: "4.0.74"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "jetbrains_codex"
  delegation_chain: "wolfie:root"
  artifact_type: "implementation_plan"
  artifact_kind: "remediation_backlog"
  purpose: "Codex-owned remediation plan"
lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260314"
  last_verified_by: "codex"
  orchestrator: "wolfie"
  next_action:
    - "Execute P0 identity and docs-path fixes"
---
# Lupopedia Remediation Plan (Codex Edition)

## P0

1. Canonicalize actor/agent/faucet ID sources across registry, docs, and seeds.
2. Add header key normalization strategy (`flare.*` compatibility read, `lupopedia.*` canonical write).
3. Fix `lupo-docs/` vs `lupo-docs/` path drift in root docs.

## P1

1. Generate one schema inventory artifact separating lupo-install/TOON/migration counts.
2. Add markdown link-check pass for root docs.
3. Define merge process from `_codex` files into shared canonical files.

## P2

1. Continue legacy FLARE naming cleanup in active docs.
2. Add changelog entry standards requiring file evidence.
