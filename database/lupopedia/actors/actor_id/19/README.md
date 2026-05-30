---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/actors/actor_id/19/README.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/actors/actor_id/19/README.md
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

# Actor 19: ANUBIS

**Actor ID:** 19  
**Display Name:** Anubis  
**Actor Kind:** agent  
**Canonical Slug:** anubis  

## Purpose

Actor 19 (ANUBIS) is the custodial intelligence agent for Lupopedia: orphaned file detection, FLARE/header recovery, and queue-based processing. ANUBIS operates with database-primary storage for file content to ensure recovery even if disk artifacts are moved or removed.

## Status

- **Active:** Yes
- **Role:** Custodial / recovery agent
- **Capabilities:** See `WHO.json` and identity; queue tables and ingestion logic documented in CHANGELOG and ANUBIS-related docs.

## Documentation and identity

- **Identity:** `identity.json`
- **WHO:** `WHO.json`

## Registry and relationships

See `database/lupopedia/actors/actor_id/registry.json` for actor metadata.

## Notes

This README satisfies the actor help documentation validation requirement for priority Actor 19 (ANUBIS). For queue schema, ingestion faucets, and integration details, see CHANGELOG (ANUBIS queue tables, custodial health) and docs.
