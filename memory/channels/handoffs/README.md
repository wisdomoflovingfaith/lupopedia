---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: memory/channels/handoffs/README.md
  web_path: https://www.lupopedia.com/lupopedia/memory/channels/handoffs/README.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/handoffs-readme.toon
  atoms_toon: null
  transcript_jsonl: 0/development/handoffs-readme
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: handoffs-readme
  lupopedia.schema: documentation
  prd_cluster: null
  title: memory/handoffs/ — actor handoff checkpoint directory
  summary: Pointer to ACTOR_HANDOFF_TOON_PROTOCOL.md, PRD 50, naming convention, disambiguation for .toon memory sidecars.
---
# memory/handoffs/

Actor **handoff TOON** checkpoints (pre-work resilience) live here unless a channel subfolder is introduced later.

Normative: [`docs/doctrine/ACTOR_HANDOFF_TOON_PROTOCOL.md`](../../docs/doctrine/ACTOR_HANDOFF_TOON_PROTOCOL.md).  
Coordination: [PRD 50](../../docs/prd/50_agent_coordination_protocol.md).

Naming: `{channel_key}_{actor_identifier}_handoff.toon` (or `.md` with headers until tooling normalizes).

**Lupopedia Memory TOON (structured sidecar metadata document — NOT a cartoon)**
