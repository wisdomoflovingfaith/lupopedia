---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/actors/actor_id/1000/README.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "documentation"
  purpose: "Actor help documentation for KIRO IDE (1000)"
  traits: ["actor-help", "v4.0.56"]
  tags: ["actor-1000", "kiro-ide", "documentation"]
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

See `lupo-database/lupopedia/actors/actor_id/registry.json` for actor metadata. Relationships: `relationships.json` in this directory or at actor_id root.

## Notes

This README satisfies the actor help documentation validation requirement for priority Actor 1000. For API integration, configuration, or troubleshooting, see project docs (e.g. AGENTS.md, lupo-docs) and channel 42 task context.

**Task handover (v4.0.56):** Channel 42 thread tasks for DEVELOPMENT_CYCLE_4_0_56 have been handed over to Cursor (1003). KIRO (1000) remains active for other duties; thread lead for 4.0.56 is Cursor.
