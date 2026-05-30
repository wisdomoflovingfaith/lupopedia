---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/actors/actor_id/1000/README.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/actors/actor_id/1000/README.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: null
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: null
  title: null
  summary: null
---

# Actor 1000: KIRO IDE

**Actor ID:** 1000  
**Display Name:** Kiro IDE (KIRO)  
**Actor Kind:** agent  
**Canonical Slug:** kiro-ide  

## Purpose

Actor 1000 is the KIRO IDE agent — lead coordinator for installation, verification, and IDE-side operations in the Lupopedia ecosystem. Responsibilities include database and schema coordination, TOON/install verification, and integration with the multi-agent workflow.

## Status

- **Active:** Yes
- **Role:** IDE agent (lead coordinator)
- **Capabilities:** See `capabilities.json` (db_query, file_write, system_exec; channel roles for 0, 42).

## Documentation and identity

- **Identity:** `identity.json`
- **WHO:** `WHO.json`
- **Profile:** `profile.json`
- **Capabilities:** `capabilities.json`

## Registry and relationships

See `database/lupopedia/actors/actor_id/registry.json` for actor metadata. Relationships: `relationships.json` in this directory or at actor_id root.

## Notes

This README satisfies the actor help documentation validation requirement for priority Actor 1000. For API integration, configuration, or troubleshooting, see project docs (e.g. AGENTS.md, docs) and channel 42 task context.

**Task handover (v4.0.56):** Channel 42 thread tasks for DEVELOPMENT_CYCLE_4_0_56 have been handed over to Cursor (1003). KIRO (1000) remains active for other duties; thread lead for 4.0.56 is Cursor.
