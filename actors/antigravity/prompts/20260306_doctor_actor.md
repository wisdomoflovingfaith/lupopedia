---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/antigravity/prompts/20260306_doctor_actor.md
  web_path: https://www.lupopedia.com/lupopedia/actors/antigravity/prompts/20260306_doctor_actor.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: directive
  artifact_kind: actor_creation
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: directive
  prd_cluster: null
  title: null
  summary: null
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
2. Create agents/1009/ with agent.json, capabilities.json, system_prompt.txt.  
3. Create DoctorService.php and actor handlers (doctor.php, doctor-context.php).  
4. Update lupo.php to route doctor / doctor-context through actor with fallback.  
5. Update HELP.md and related docs.

**END OF DIRECTIVE**
