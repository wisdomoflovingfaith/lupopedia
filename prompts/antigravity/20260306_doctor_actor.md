---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "directive"
  file_path_from_root: "prompts/antigravity/20260306_doctor_actor.md"
  web_path: "http://www.lupopedia.com/directives/ANTIGRAVITY_DOCTOR_ACTOR"
  last_modified_utc: "20260306"
  system_version: "4.0.62"
  channel_id: 42
  actor_id: 42
  actor_name: "antigravity"
  delegation_chain: "antigravity:captain"
  artifact_type: "directive"
  artifact_kind: "actor_creation"
  purpose: "Create DOCTOR actor AI agent for system health and diagnostics"
  mood_rgb: "FF4500"
  traits: ["directive", "v4.0.62", "doctor", "actor", "creation"]
  tags: ["antigravity", "doctor", "actor", "diagnostics", "health"]
  agent_name_identity: "Antigravity Agent"
  lupo_agent: "antigravity"

lupopedia.init:
  execution_mode: "required"
  pre_actions:
    - type: dependency_check
      target: "lupo-includes/classes/ContextKernel.php"
    - type: dependency_check
      target: "lupo-bin/lupo.php"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-agents/1009/", type: "creates", weight: 1.0 }
    - { to: "lupo-agents/1009/agent.json", type: "creates", weight: 1.0 }
    - { to: "lupo-agents/1009/capabilities.json", type: "creates", weight: 0.9 }
    - { to: "lupo-agents/1009/system_prompt.txt", type: "creates", weight: 0.9 }
    - { to: "lupo-includes/classes/DoctorService.php", type: "creates", weight: 0.8 }
  semantic_tags: ["antigravity", "doctor", "actor", "directive"]

lupopedia.see:
  mappings:
    - ["prompts/antigravity/20260306_doctor_actor.md", "http://www.lupopedia.com/directives/ANTIGRAVITY_DOCTOR_ACTOR"]

lupopedia.close:
  post_actions:
    - type: notify_completion
      channel_id: 42
      message: "DOCTOR actor AI agent created"
  actor_id: 42

lupopedia.footer:
  version: "4.0.62"
  last_verified: "20260306"
  last_verified_by: "captain"
---

# ANTIGRAVITY DIRECTIVE — CREATE "DOCTOR" ACTOR AI AGENT

**To:** Antigravity Agent (actor_name: antigravity, actor_id: 42)  
**From:** Captain Wolfie (actor_name: captain)  
**Date:** 20260306  
**Subject:** Create DOCTOR actor AI agent for system health, diagnostics, and repair  
**Priority:** HIGH

## Executive summary

Create a dedicated actor for system health, diagnostics, and repair. Doctor functionality is centralized in the DOCTOR actor (actor_id 1009, actor_name: doctor).

| Aspect | Current | Proposed |
|--------|---------|----------|
| **Identity** | No dedicated actor | actor_id: 1009, actor_name: doctor |
| **Capabilities** | Implicit in CLI | Explicit in capabilities.json |
| **CLI integration** | `lupo doctor`, `lupo doctor-context` | Same, routed through actor when present |

## DOCTOR actor specifications

- **Actor name:** doctor  
- **Actor ID:** 1009  
- **Actor type:** agent (system_agent role)  
- **Slug:** doctor  
- **Purpose:** System health, diagnostics, repair  

**Capabilities:** health_check, context_validation, repair, diagnostic_reporting.

## Phases (see implementation)

1. Register DOCTOR in actor registry (id 1009).  
2. Create lupo-agents/1009/ with agent.json, capabilities.json, system_prompt.txt.  
3. Create DoctorService.php and actor handlers (doctor.php, doctor-context.php).  
4. Update lupo.php to route doctor / doctor-context through actor with fallback.  
5. Update HELP.md and related docs.

**END OF DIRECTIVE**
