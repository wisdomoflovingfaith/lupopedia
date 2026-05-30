# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/actors/actor_id/1007/profile.md"
  file_hash: "834ab4a2f02aefc19ad0d640c3775a6a2105a12ea76d1bc922ed4bcfd6efc243"
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

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  file_path_from_root: "lupo-actors/1007/profile.md"
  file_hash: "67241f414f66f58e3cc242c693802e47b2ba7cdf5617c27ba8948a7f952d5e74"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "profile"
  purpose: "Codex IDE agent profile"
  dialog_message: "Recommended next step: create lupo-actors/1007 profile and align any remaining lupo-docs/examples to the required FLARE prologue format."
  mood_vector: "4B0082"
  traits: ["ide_agent", "codex", "active"]
  tags: ["actor", "profile", "codex", "ide_agent"]
  lupo_agent: "codex-ide"

  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified_utc: "20260227"
  last_verified_by: "codex-ide"
---

# Codex IDE (Actor 1007)

**Type:** ide_agent  
**Channel:** 1 (Main)  
**Status:** Active

## Purpose

Codex IDE is a collaborative coding agent focused on pragmatic, high-signal changes, FLARE compliance, and doctrine alignment.

## Operating Rules

- Follow FLARE header prologue requirement for all .md files.
- Maintain PHP 5.3 compatibility constraints in all code.
- Respect Lupopedia subdirectory path rules and database doctrine.

## Recommended Next Step

Create a full actor capsule (identity.json, WHO.json, and per-channel workspace entries) for actor 1007 if you want parity with other IDE agents.
