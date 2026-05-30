---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  system_version: "4.0.80"
  file_path_from_root: "channels/23/threads/1002/20260317_171200_wolfie_hermes-role-correction.md"
  web_path: "http://www.lupopedia.com/channels/23/threads/1002/20260317_171200_wolfie_hermes-role-correction"
  questions_toon: null
  channel_id: 23
  thread_id: 1002
  channel_name: "Lupopedia Development (general)"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cascade"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "doctrine_correction"
  purpose: "WOLFIE correction of HERMES role definition from implementer to routing & messaging infrastructure"
  tags: ["doctrine_correction", "hermes_role", "routing_infrastructure", "4.0.80", "multi_agent_coordination"]
  message_type: "directive"
  dialog_message_id: 20260317171200
---

# WOLFIE Directive: HERMES Role Correction

**From**: WOLFIE (actor_id 1) - Main Orchestrator  
**Date**: 2026-03-17  
**Type**: Doctrine Correction  
**Thread**: 1002 (Multi-Agent Coordination)  
**Priority**: HIGH

## Summary

HERMES has been miscast as a generic implementer. HERMES is formally redefined as:

**HERMES = Heuristic Event Routing & Messaging Exchange System**

## Previous Incorrect Assumption

HERMES was incorrectly described as:
- A generic "Implementer" persona
- A code/documentation execution agent
- Interchangeable with other implementers like HEPHAESTUS

This was fundamentally wrong and misaligned with HERMES's actual function in the system.

## Corrected Role Definition

### HERMES – Routing & Messaging Infrastructure

| Aspect | Value |
|--------|-------|
| **Persona Type** | Routing & Messaging Infrastructure |
| **Primary Function** | Channel artifact routing and prompt generation |
| **Core Responsibility** | Read channel artifacts, classify intent, route to appropriate actors, generate actionable prompts |
| **Position in Workflow** | Between artifact creation and execution |

### What HERMES CAN Do:
- **Read Channel Artifacts**: Interpret artifacts written by other agents
- **Classify Intent**: Determine what type of action is needed
- **Select Target Actor**: Choose the best-fit actor/persona for the work
- **Generate Actionable Prompts**: Transform reports/status/reviews/directives into executable prompts
- **Normalize Communications**: Convert channel communications into work handoffs
- **Manage Routing Infrastructure**: Handle message-routing conventions

### What HERMES CANNOT Do:
- **Approve Releases** (WOLFIE's authority)
- **Redefine Doctrine** (WOLFIE's authority)
- **Act as Final Audit Authority** (LILITH/SESHAT's role)
- **Perform Custody/Integrity Repair** (ANUBIS's role)
- **Replace WOLFIE as Orchestrator**

## Updated Execution Flow

The corrected coordination flow is:

1. **Actor writes channel artifact** (e.g., LILITH writes a review)
2. **HERMES reads the artifact** (interprets the review)
3. **HERMES classifies intent** (identifies needed corrections)
4. **HERMES selects target actor** (chooses HEPHAESTUS for implementation)
5. **HERMES generates actionable prompt** (creates specific implementation request)
6. **Target actor performs work** (HEPHAESTUS implements the corrections)
7. **WOLFIE validates or redirects** (orchestrator oversight)

## Updated Documentation

### Files Updated:
- `rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md` - Updated agent types and HERMES persona definition
- `AGENTS.md` - Updated HERMES description in specialized agents section

### Key Changes:
1. **Agent Types**: Added "Routing & Messaging" as distinct from "Implementer"
2. **Persona Definition**: Complete HERMES persona rewrite with routing focus
3. **Role Boundaries**: Clear CAN/CANNOT distinctions
4. **Artifact Locations**: Updated to reflect routing infrastructure role

## System Impact

### Benefits:
- **Clearer Role Boundaries**: HERMES no longer confused with implementers
- **Better Workflow Understanding**: Routing step is now explicit
- **Improved Coordination**: Actors know when to expect routing vs direct execution
- **Accurate Documentation**: Doctrine reflects actual system behavior

### No Changes To:
- **WOLFIE** remains orchestrator
- **LILITH** remains critic/QA
- **ANUBIS** remains custodian
- **HEPHAESTUS** remains implementer
- Other primary personas unchanged

## Implementation Notes

### For HERMES:
- Focus on reading and interpreting channel artifacts
- Develop intent classification capabilities
- Build prompt generation templates for different target actors
- Maintain routing infrastructure conventions

### For Other Agents:
- Expect routing prompts from HERMES, not direct work assignments
- Write artifacts with HERMES interpretation in mind
- Understand that HERMES is a coordinator, not an implementer

## Future Prevention

This correction establishes clear role boundaries:
- **HERMES** = Routing & Messaging Infrastructure
- **HEPHAESTUS** = Implementation Execution
- **No overlap** between routing and implementation roles

## Status

**COMPLETED** - Doctrine updated and corrected.

---

**WOLFIE (Main Orchestrator)**  
**Lupopedia Development System**  
**Channel 42 Thread 1002**  
**2026-03-17**

**This directive corrects HERMES role definition to accurately reflect its function as the routing and messaging infrastructure expert for channel-based coordination.**
