---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Cross_Thread_Coordination_Protocol.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Cross_Thread_Coordination_Protocol.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-92"
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
# D-92: Cross-Thread Coordination Protocol

## Type
Unknown

## Status
**Accepted**

## Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

## Date
2026-03-31

### Context
Multiple actors and agents were editing the same documents simultaneously, causing overwrites and merge conflicts. Versioned docs were being wholesale replaced instead of incrementally updated.

### Decision
Always read latest file contents before editing. Use outbound_edges and header metadata to track canonical relationships. Make incremental, surgical edits. Never wholesale replace versioned docs. Coordinate edits in channel threads.

### Consequences
- Reduced merge conflicts
- Better traceability
- Required discipline from all actors/agents

### Comments
*2026-03-31 LILITH*: This file now serves as the canonical decisions log.
*2026-03-31 WOLFIE*: All agents must follow this protocol.

---

## Action Items

### High Priority (Immediate)

| ID | Action | Owner | Status | Target |
|----|--------|-------|--------|--------|
| A-01 | Complete SESHAT (actor_id 5) - Content Review & Quality Assurance | HEPHAESTUS | Pending | 2026-04-02 |
| A-02 | Complete HEIMDALL (actor_id 6) - Security Guardian | HEPHAESTUS | Pending | 2026-04-02 |
| A-03 | Update all existing headers to version 2.0 format | LILITH | In Progress | 2026-04-05 |
| A-04 | Add federation_node_id to all headers (default 0) | LILITH | In Progress | 2026-04-05 |
| A-05 | Add actor_id and actor_name to all headers | LILITH | In Progress | 2026-04-05 |
| A-06 | Complete remaining PRD namespaces (7 remaining) | HEPHAESTUS | Pending | 2026-04-07 |

### Medium Priority (This Week)

| ID | Action | Owner | Status | Target |
|----|--------|-------|--------|--------|
| A-07 | Complete JANUS (actor_id 7) - Transitions & Gateways | HEPHAESTUS | Pending | 2026-04-04 |
| A-08 | Complete THEMIS (actor_id 10) - Law & Compliance | HEPHAESTUS | Pending | 2026-04-04 |
| A-09 | Complete MAAT (actor_id 12) - Truth & Justice | HEPHAESTUS | Pending | 2026-04-05 |
| A-10 | Complete CHIRON (actor_id 13) - Support & Healing | HEPHAESTUS | Pending | 2026-04-05 |
| A-11 | Implement automated TOON generation pipeline | HEPHAESTUS | Pending | 2026-04-07 |
| A-12 | Create comprehensive test suite for agent configurations | LILITH | Pending | 2026-04-07 |

### Low Priority (This Month)

| ID | Action | Owner | Status | Target |
|----|--------|-------|--------|--------|
| A-13 | Complete VISHWAKARMA (actor_id 14) - Schema & Construction | HEPHAESTUS | Pending | 2026-04-10 |
| A-14 | Implement comprehensive monitoring and alerting | HEPHAESTUS | Pending | 2026-04-15 |
| A-15 | Create production deployment guide | WOLFIE | Pending | 2026-04-20 |
| A-16 | Complete remaining specialized agents (29 agents) | HEPHAESTUS | Pending | 2026-04-30 |
| A-17 | Migrate legacy timestamps to tick.py sourced format | LILITH | Pending | 2026-04-15 |

### Completed Actions

| ID | Action | Owner | Completed |
|----|--------|-------|-----------|
| A-C01 | LEXA Security Enforcement Enhancement | LILITH | 2026-03-31 |
| A-C02 | ATHENA Wisdom & Strategy Enhancement | LILITH | 2026-03-31 |
| A-C03 | THOTH Knowledge & Records Enhancement | LILITH | 2026-03-31 |
| A-C04 | ANUBIS Custodian Enhancement | LILITH | 2026-03-31 |
| A-C05 | Consolidated Seed File Creation | HEPHAESTUS | 2026-03-30 |
| A-C06 | Dynamic Table Prefix Migration | HEPHAESTUS | 2026-03-30 |
| A-C07 | JSON Schema Management Workflow Correction | ANUBIS | 2026-03-31 |
| A-C08 | ANUBIS Events Table Schema Fix | ANUBIS | 2026-03-31 |
| A-C09 | Channel chat: channels-api formats, channel.php, chat-display JS/CSS, PRD 18, routing, digit assets | CURSOR | 2026-03-31 |
| A-C10 | Channel chat implementation documentation with LUPOPEDIA headers | CASCADE | 2026-03-31 |
| A-C11 | WOLFIE Doctrine creation - constitutional rules against framework bloat | WOLFIE | 2026-04-01 |
| A-C12 | Multi-Agent Orchestration Doctrine - cascade workflow documentation | LILITH | 2026-04-01 |
| A-C13 | Actor-Agent Distinction Doctrine - templates vs instances clarification | WOLFIE | 2026-04-01 |

---

## Session Notes & Observations

### 2026-03-31: LILITH
- Completed audit of 7_agents_faucets.md PRD
- Completed audit of 15_temporal_system.md PRD
- Completed audit of 16_lupopedia_headers.md PRD
- Consolidated decisions.md and WHAT_TO_DO_NEXT.md into single file
- Added author attribution to all decisions
- All PRDs now reference root constitutional PRD

### 2026-03-31: HEPHAESTUS
- Completed consolidated seed file implementation
- Completed dynamic table prefix migration
- Updated installer to use consolidated seed only
- Verified install flow with {{prefix}} replacement

### 2026-03-31: WOLFIE
- Approved filesystem-based agent doctrine
- Approved subdirectory installation doctrine
- Set priority order for remaining coordination personas

### 2026-03-30: ANUBIS
- Fixed ANUBIS events table schema
- Corrected JSON schema management workflow
- Documented lesson about never manually editing JSON files

### 2026-03-31: CURSOR (channel chat thread)
- Reviewed and updated PRD `18_channel_chat_display.md` for canonical `channels-api.php`, `LUPOPEDIA_PUBLIC_PATH`, ES3 legacy notes, TOON-corrected example SQL.
- Implemented `format=buffer` / `format=image` / `thread_id` / `dialog_thread_id` on GET messages; `position` alias and `image_metric` for digit encoding.
- Added `channel.php`, `ui/js/chat-display.js`, `chat-display-legacy.js`, `chat-display.css`, `channel-chat/*` and `channel.php` rewrite rules; preserved `/channels/*` → index cockpit.
- Operator installed real `digit0.gif`–`digit9.gif` (and related) under `ui/images/` per README guidance.

### 2026-03-31: CASCADE (implementation documentation)
- Created `docs/implementations/channel-chat.md` with proper LUPOPEDIA headers
- Documented API paths, URL routing, fallback chain, and browser support
- Added metadata: schema=implementation, actor_id=105, channel_id=42, thread_id=channel-chat-implementation
- Linked implementation to PRD 18_channel_chat_display.md, channels-api.php, and channel.php
- Ensured compliance with Lupopedia documentation standards

### 2026-04-01: WOLFIE
- Created WOLFIE Doctrine as root-level constitutional rule
- Established Five Pillars of WOLFIE Engineering
- Created binding rules W-01 through W-05 for all agents
- Updated root README.md and constitutional requirements PRD
- Protected 1999-era code from framework bloat and forced modernization

### 2026-04-01: WOLFIE
- Created WOLFIE Doctrine as root-level constitutional rule
- Established Five Pillars of WOLFIE Engineering
- Created binding rules W-01 through W-05 for all agents
- Updated root README.md and constitutional requirements PRD
- Protected 1999-era code from framework bloat and forced modernization

### 2026-04-01: LILITH
- Created Multi-Agent Orchestration Doctrine documenting cascade workflow
- Documented meta-agent loop (LILITH refines prompts for internal swarm)
- Recorded scale: 10+ IDEs, 50+ agents, dependency-based coordination
- Created Actor-Agent Distinction Doctrine
- Updated all PRDs to clarify agents are templates, actors are instances
- Added Rule W-06 to WOLFIE Doctrine: Agents Do Not Learn, Actors Do

---
