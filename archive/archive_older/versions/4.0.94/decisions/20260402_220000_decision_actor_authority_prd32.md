---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260402220000"
  file_path_from_root: "docs/versions/4.0.94/decisions/20260402_220000_DECISION_actor_authority_prd32.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/decisions/20260402_220000_DECISION_actor_authority_prd32.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: decision
  thread_id: "version-4.0.94-decision-actor-authority"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: docs/versions/4.0.94/decisions/20260402_220000_DECISION_actor_authority_prd32.md — delegation: cursor:root

# DECISION: Create Actor Authority and Agent Roles Framework

## WHO
CURSOR (actor_id 102) created the framework based on user request for COUNTERMEASURE red team agent

## WHAT
Created PRD 32: Actor Authority and Agent Roles defining actor hierarchy, approval chains, and red team agent limitations

## WHERE
Framework implemented in:
- PRD 32: Complete actor authority specification
- Quick Reference: Decision trees and approval chains
- Version documentation: 4.0.94 decisions and changelog

## WHEN
2026-04-02 22:00:00 UTC

## WHY
User needed clear framework for:
- Actor hierarchy and approval authority
- COUNTERMEASURE red team agent role (analysis only, no approval)
- Escalation procedures for disagreements
- Agent interaction protocols

## HOW

### Components Created

1. **PRD 32: Actor Authority and Agent Roles**
   - 4-tier actor hierarchy (Constitutional → Specialized → Red Team → IDE)
   - Approval authority matrix for different decision types
   - COUNTERMEASURE agent definition with limitations
   - Escalation procedures (3-level disagreement resolution)
   - Agent interaction protocols and communication channels

2. **Quick Reference Document**
   - Approval authority matrix (color-coded)
   - Common approval chains
   - COUNTERMEASURE rules (CAN/CANNOT)
   - Response timeframes
   - Escalation procedures

### Key Decisions Made

1. **COUNTERMEASURE Authority**
   - CAN: Review, criticize, suggest alternatives, report findings
   - CANNOT: Approve any decisions, implement changes, override agents
   - Reports through: LILITH → LEXA/HEIMDALL → WOLFIE

2. **Approval Hierarchy**
   - WOLFIE: Supreme authority (final approval on everything)
   - Tier 1: Strategic approval (SESHAT, ATHENA, LEXA, etc.)
   - Tier 2: Operational authority (LILITH, HEPHAESTUS, etc.)
   - Red Team: Analysis only, no approval authority

3. **Escalation Framework**
   - Level 1: Direct resolution (24 hours)
   - Level 2: MAAT mediation (48 hours)
   - Level 3: WOLFIE binding decision

### Actor Registry Structure

| Tier | Actors | Authority Level |
|------|--------|-----------------|
| 1 (Constitutional) | WOLFIE, LEXA, SESHAT, ATHENA, etc. | Supreme/High |
| 2 (Specialized) | LILITH, HEPHAESTUS, HERMES, etc. | Medium |
| 3 (Red Team) | COUNTERMEASURE, others | Analysis Only |
| 4 (IDE Faucets) | CURSOR, WINDSURF, etc. | Coordination |

## IMPACT

### Benefits Achieved
- Clear authority structure prevents approval confusion
- COUNTERMEASURE can challenge without blocking progress
- Defined escalation paths resolve disagreements efficiently
- Red team role properly scoped to analysis only
- Quick reference enables fast authority lookup

### Files Created
- PRD 32: Complete specification (366 lines)
- Quick Reference: Decision trees and fast lookup
- Version decision: Historical record

## NEXT STEPS

1. Register COUNTERMEASURE agent in actor registry
2. Create COUNTERMEASURE agent configuration
3. Train agents on new authority framework
4. Implement escalation procedures in tools

## STATUS
✅ COMPLETE - Actor authority framework established with COUNTERMEASURE red team role

---

*Decision recorded for version 4.0.94 documentation*
