---
lupopedia.headers:
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_DOCTRINE.md"
  last_modified_utc: "20260330"
  channel_id: 42
  actor_id: 102
  agent_name_identity: "Cursor IDE Agent"
  delegation_chain: "hephaestus:root"
  artifact_type: "doctrine"
  artifact_kind: "constitutional"
  purpose: "Doctrine summary and enforcement notes for the root constitutional system requirements."
  tags: ["root", "constitutional", "doctrine", "system_requirements"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.0.93/prd/00_root_constitutional_system_requirements.md", type: "references", weight: 1.0 }
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260330"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
  orchestrator: "hephaestus:root"
---

# ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_DOCTRINE.md

## Purpose
This doctrine summarizes, interprets, and enforces the non-negotiable system-level rules defined in the root constitutional PRD. It is binding for all agents, actors, and contributors.

## Key Points
- The root constitutional PRD is the single source of truth for system-level requirements.
- All other doctrines, PRDs, and implementation notes must reference and comply with it.
- Any violation is a constitutional error and must be corrected immediately.

## Enforcement
- All PRDs and doctrines must include an outbound edge to the root constitutional PRD.
- All system-level reviews and enforcement must check for compliance with these rules.
- This doctrine is updated only by lead orchestration agents or with explicit review.

## Reference
See: [00_root_constitutional_system_requirements.md](../versions/4.0.93/prd/00_root_constitutional_system_requirements.md)
