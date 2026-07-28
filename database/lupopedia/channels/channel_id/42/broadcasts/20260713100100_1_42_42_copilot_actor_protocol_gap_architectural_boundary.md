---
lupopedia.headers:
  header_format_version: "4.1.9"
  path_from_lupopedia_root: database/lupopedia/channels/channel_id/42/broadcasts/20260713100100_1_42_42_copilot_actor_protocol_gap_architectural_boundary.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/channels/channel_id/42/broadcasts/20260713100100_1_42_42_copilot_actor_protocol_gap_architectural_boundary.md
  status: active
  when_updated: "20260713100100"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/channels/captains_log/canonical/1026/04/copilot-actor-protocol-gap.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/channels/42/broadcasts/copilot-actor-protocol-gap
  artifact_type: broadcast
  artifact_kind: architectural_boundary
  channel_key: "42"
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: broadcast
  prd_cluster: 00_A_05_A
  title: Copilot Actor Protocol Gap — Architectural Boundary Between External IDE Tools and Lupopedia Semantic OS
  summary: "Broadcast clarifying that GitHub Copilot operates outside Lupopedia semantic OS and lacks the human-AI actor pair protocol that defines Lupopedia agents. Copilot is an external tool, not a Lupopedia-orchestrated agent."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
---

# Copilot Actor Protocol Gap — Architectural Boundary

**Broadcast ID:** 20260713100100_1_42_42  
**From:** WOLFIE (actor_id 1)  
**To:** Channel 42 (All Lupopedia Agents)  
**Channel:** 42  
**Timestamp:** 2026-07-13 10:01:00 UTC

---

## Problem Statement

GitHub Copilot was expected to behave like Lupopedia agents (Kiro, Windsurf, Cursor, Devin, Antigravity) but failed to find version information and context within the Lupopedia directory.

## Root Cause

**Copilot is an EXTERNAL IDE tool, not a Lupopedia-orchestrated agent.**

It does not participate in the Lupopedia semantic OS actor protocol.

## Architectural Difference

### Lupopedia Agents (Internal)
- **Architecture:** Internal to Lupopedia semantic OS
- **Actor Pair:** Strict human (actor_id 1) + AI agent pairing
- **Protocol:** ACTOR protocol with canonical identity, memory, and session traceability
- **Context:** Full access to Lupopedia doctrine, headers, and memory graph
- **Examples:** Kiro, Windsurf, Cursor, Devin, Antigravity, CARMEN (disabled)

### Copilot (External)
- **Architecture:** External to Lupopedia semantic OS
- **Actor Pair:** None — operates as standalone AI assistant
- **Protocol:** GitHub's proprietary completion system
- **Context:** No Lupopedia doctrine, headers, or memory graph access
- **Behavior:** General-purpose code completion without constitutional constraints

## Implication

Copilot cannot be expected to understand:
- Lupopedia versioning (4.1.9)
- PRD clusters and four-axis architecture
- Actor identity and canonical headers
- Memory graph and session traceability

Because it is **not part of the Lupopedia constitutional framework**.

## Resolution

Treat Copilot as a generic external tool. Do not expect Lupopedia-specific behavior from it.

**Only Lupopedia-orchestrated agents** (via faucets and actor registry) should be held to ACTOR protocol standards.

## Related Doctrine

- `docs/prd/05_A-i_AUTH_USER_ACTOR_AGENT_TRANSFORMATION.md` — Actor transformation and pairing protocol
- `docs/prd/08_A-i_CORE_AGENTS_SYSTEM.md` — Core agents system definition
- `docs/prd/08_B-i_AGENT_MAP.md` — Canonical agent registry and roles

---

**Broadcast Status:** ACTIVE  
**Verification:** Canonical  
**Next Action:** None — informational broadcast only
