# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/directives/AGENT_REGISTRY_IMPLEMENTATION_4.0.57
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "directive"
  file_path_from_root: "prompts/cursor/20260306_agent_registry_implementation.md"
  web_path: "http://www.lupopedia.com/directives/AGENT_REGISTRY_IMPLEMENTATION_4.0.57"
  last_modified_utc: "20260306"
  system_version: "4.0.57"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "1003:10000"
  agent_name_identity: "Cursor IDE Agent"
  artifact_type: "directive"
  artifact_kind: "implementation_task"
  purpose: "Implement Agent Registry Refinement v4.0.57 based on LILITH/Grok review"
  mood_rgb: "FF4500"
  traits: ["directive", "v4.0.57", "registry", "implementation", "actionable"]
  tags: ["cursor", "registry", "identity", "implementation", "v4.0.57"]
  lupo_agent: "cursor"
lupopedia.init:
  execution_mode: "required"
  pre_actions:
    - type: dependency_check
      target: "docs/status/AGENT_REGISTRY_REFINEMENT_4.0.57.md"
    - type: dependency_check
      target: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "updates", weight: 1.0 }
    - { to: "docs/status/AGENT_IDENTITY_REGISTRY_4.0.57.md", type: "references", weight: 0.9 }
    - { to: "docs/status/AGENT_REGISTRY_REFINEMENT_4.0.57.md", type: "references", weight: 0.9 }
    - { to: "lupo-database/lupopedia/actors/actor_id/registry.json", type: "references", weight: 0.9 }
    - { to: "docs/status/LILITH_FLAME_FAUCET_REPORT.md", type: "references", weight: 0.8 }
    - { to: "docs/status/FLARE_FEDERATION_REFINEMENT_4.0.57.md", type: "references", weight: 0.7 }
lupopedia.see:
  mappings:
    - ["prompts/cursor/20260306_agent_registry_implementation.md", "http://www.lupopedia.com/directives/AGENT_REGISTRY_IMPLEMENTATION_4.0.57"]
lupopedia.footer:
  version: "4.0.57"
  last_verified: "20260306"
  last_verified_by: "lilith"
---

# CURSOR IMPLEMENTATION DIRECTIVE — AGENT REGISTRY REFINEMENT v4.0.57

**To:** Cursor IDE Agent (1003)  
**From:** LILITH (2) via Captain Wolfie (10000)  
**Date:** 20260306  
**Subject:** Implement Agent Registry Refinement based on review feedback  
**Priority:** HIGH

## Executive summary

Consolidate feedback from Grok and LILITH reviews: establish the **actor registry as the canonical source of truth** and integrate the **agent_name_identity** header field. Tasks: FLARE_DOCTRINE Section 24 (24.1–24.5), registry path clarification, enhanced tooling example, hardcoded-ID detection script, example table disclaimer, cross-references, AGENTS.md section, validation.

## Completion checklist

- [x] FLARE_DOCTRINE Section 24 with subsections 24.1–24.5
- [x] Registry paths clarified (canonical vs per-actor vs shorthand)
- [x] Tooling example enhanced (get_actor_by_id, get_actor_by_slug, error handling)
- [x] check_hardcoded_ids.py created
- [x] Example tables include "Always resolve from the registry"
- [x] Cross-references to LILITH_FLAME_FAUCET_REPORT and FLARE_FEDERATION_REFINEMENT
- [x] AGENTS.md Agent Identity Registry section
- [x] Validation (flare_validate.py, registry JSON, optional check_hardcoded_ids)

## Channel 42 completion message

CURSOR: Agent Registry Refinement v4.0.57 implementation complete. FLARE_DOCTRINE Section 24 created; registry paths clarified; hardcoded ID detection script added; tooling examples enhanced; docs updated; cross-references added; validation passes. Registry now fully canonical. agent_name_identity documented. Ready for v4.0.57 finalization.
