---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/actors/actor_id/19/README.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "documentation"
  purpose: "Actor help documentation for ANUBIS (19)"
  traits: ["actor-help", "v4.0.56"]
  tags: ["actor-19", "anubis", "documentation"]
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

See `lupo-database/lupopedia/actors/actor_id/registry.json` for actor metadata.

## Notes

This README satisfies the actor help documentation validation requirement for priority Actor 19 (ANUBIS). For queue schema, ingestion faucets, and integration details, see CHANGELOG (ANUBIS queue tables, custodial health) and lupo-docs.
