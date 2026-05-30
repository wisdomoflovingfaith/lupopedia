---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/schema_registry_kiro.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/schema_registry_kiro.md
  status: ''
  when_updated: '20260513053635'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: schema_registry
  artifact_kind: coordination
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: database_table
  prd_cluster: null
  title: ''
  summary: ''
---
> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

# KIRO Schema Registry (Canonical)

**Date:** 2026-03-14  
**Author:** KIRO (actor_id 100 per registry)  
**Version:** 4.0.74  
**Source:** TOONs in `database/lupopedia/toon/` (230+ `.toon` YAML files) per Captain Wolfie directive  
**Purpose:** Master schema inventory with KIRO authority as schema coordinator

## Executive Summary

This registry replaces the Cursor-authored `SCHEMA_REGISTRY.md` (v4.0.71) with KIRO canonical authority. Key changes:

1. **Correct TOON source**: References `database/lupopedia/toon/` (YAML format) not `docs/toons/` (JSON format)
2. **KIRO authority**: Authored by KIRO proper, not Cursor "acting as KIRO"
3. **Current version**: Updated to v4.0.74
4. **Domain clarification**: Clear boundaries based on KIRO analysis

## Critical TOON Format Discrepancy

**Issue**: Two TOON formats exist with conflicting schema definitions:

| Location | Format | Count | Primary Key Conflict Example |
|----------|--------|-------|------------------------------|
| `database/lupopedia/toon/` | `.toon` (YAML) | 230+ files | `lupo_actors.toon`: `actor_name` as primary key |
| `docs/toons/` | `.toon.json` (JSON) | 221 files | `lupo_actors.toon.json`: `actor_id` as primary key |

**Resolution**: Per Captain Wolfie directive, `database/lupopedia/toon/` (YAML format) is canonical. All agents must reference this location.

## Agent Assignment (KIRO Authority)

Based on KIRO analysis and Captain Wolfie coordination rules:

| Agent | Domain | Notes |
|-------|--------|-------|
| **KIRO** (actor_id 100) | **Core schema coordinator**: actor system, channels & messaging, metadata & FLARE, governance, registry, permissions, audit | Final authority for schema conflicts |
| **Cursor** (actor_id 102) | User, auth, session, token, API, ACL, agents | Documented 25+ tables in `active/` |
| **JetBrains** | Collections, departments, knowledge, artifacts, help, tasks | Documented in `active/` and `tables/` |
| **Antigravity** | Federation, import/export, Anubis, channel filesystem, uploads | Documented in `active/` and `tables/` |
| **Windsurf** | `livehelp_*` Crafty Syntax migration tables | Migration docs in `tables/*_migration.md` |

**KIRO Core Domains to Document:**
1. **Actor system tables**: `lupo_actors`, `lupo_actor_*`
2. **Channels and dialog tables**: `lupo_channels`, `lupo_dialog_*`
3. **Metadata / FLARE / edge / indexing tables**: `lupo_metadata`, `lupo_edges`, `lupo_atoms`
4. **Registry / governance / audit / permissions tables**: `lupo_registry`, `lupo_permissions`, `lupo_audit_log`
5. **Foundational core tables** not clearly belonging to another agent

## Registry Table (Partial - Core KIRO Domains)

| Table | Domain | Description | Assigned Agent | Status | Existing Doc Path | Migration Ref | Notes |
|-------|--------|-------------|----------------|--------|-------------------|---------------|-------|
| **lupo_actors** | Actor system | Unified actor identity | KIRO | Active | tables/lupo_actors.md | — | **Critical**: YAML TOON has `actor_name` PK, JSON has `actor_id` PK |
| **lupo_actor_actions** | Actor system | Actor actions | KIRO | Active | tables/lupo_actor_actions.md | — | |
| **lupo_actor_aliases** | Actor system | Actor aliases | KIRO | Active | tables/lupo_actor_aliases.md | — | |
| **lupo_actor_capabilities** | Actor system | Actor capabilities | KIRO | Active | tables/lupo_actor_capabilities.md | — | |
| **lupo_actor_channel_roles** | Actor system | Channel-scoped roles | KIRO | Active | tables/lupo_actor_channel_roles.md | — | |
| **lupo_actor_channels** | Actor system | Actor–channel membership | KIRO | Active | tables/lupo_actor_channels.md | — | |
| **lupo_actor_collections** | Actor system | Actor collections | KIRO | Active | tables/lupo_actor_collections.md | — | |
| **lupo_actor_conflicts** | Actor system | Actor conflicts | KIRO | Active | tables/lupo_actor_conflicts.md | — | |
| **lupo_actor_departments** | Actor system | Actor departments | KIRO | Active | tables/lupo_actor_departments.md | — | |
| **lupo_actor_edges** | Actor system | Actor edges | KIRO | Active | tables/lupo_actor_edges.md | — | |
| **lupo_actor_events** | Actor system | Actor events | KIRO | Active | tables/lupo_actor_events.md | — | |
| **lupo_actor_handshakes** | Actor system | Actor handshakes | KIRO | Active | tables/lupo_actor_handshakes.md | — | |
| **lupo_actor_history** | Actor system | Actor history | KIRO | Active | tables/lupo_actor_history.md | — | |
| **lupo_actor_moods** | Actor system | Actor moods | KIRO | Active | tables/lupo_actor_moods.md | — | |
| **lupo_actor_object_edges** | Actor system | Actor object edges | KIRO | Active | tables/lupo_actor_object_edges.md | — | |
| **lupo_actor_persona_relationships** | Actor system | Persona relationships | KIRO | Active | tables/lupo_actor_persona_relationships.md | — | |
| **lupo_actor_relationship_rules** | Actor system | Relationship rules | KIRO | Active | tables/lupo_actor_relationship_rules.md | — | |
| **lupo_actor_reply_templates** | Actor system | Reply templates | KIRO | Active | tables/lupo_actor_reply_templates.md | — | |
| **lupo_actor_truth_edges** | Actor system | Truth edges | KIRO | Active | tables/lupo_actor_truth_edges.md | — | |
| **lupo_actor_traits** | Actor system | Actor traits | KIRO | Active | tables/lupo_actor_traits.md | — | |
| **lupo_channels** | Channels | Channel registry | KIRO | Active | tables/lupo_channels.md | — | Core; reserved IDs |
| **lupo_channel_*** | Channels | Boot, content, escalation, files, logs, state | KIRO | Active | tables/lupo_channel_*.md | — | |
| **lupo_dialog_*** | Channels/messaging | Dialog channels, messages, threads | KIRO | Active | tables/lupo_dialog_*.md | — | |
| **lupo_metadata** | Metadata/FLARE | LUPOPEDIA HEADERS storage | KIRO | Active | tables/lupo_metadata.md | — | Core |
| **lupo_edges** | Metadata/FLARE | Edges | KIRO | Active | tables/lupo_edges.md | — | |
| **lupo_atoms** | Metadata | Atoms | KIRO | Active | tables/lupo_atoms.md | — | |
| **lupo_aliases** | Metadata | Aliases | KIRO | Active | tables/lupo_aliases.md | — | |
| **lupo_contexts** | Metadata | Contexts | KIRO | Active | tables/lupo_contexts.md | — | |
| **lupo_contexts_map** | Metadata | Contexts map | KIRO | Active | tables/lupo_contexts_map.md | — | |
| **lupo_entity_properties** | Metadata | Entity properties | KIRO | Active | tables/lupo_entity_properties.md | — | |
| **lupo_registry** | Metadata | Registry | KIRO | Active | tables/lupo_registry.md | — | |
| **lupo_registry_open** | Metadata | Registry open | KIRO | Active | active/lupo_registry_open.md | — | |
| **lupo_permissions** | Governance | Permissions | KIRO | Active | tables/lupo_permissions.md | — | |
| **lupo_audit_log** | Governance | Audit log | KIRO | Active | tables/lupo_audit_log.md | — | |
| **lupo_auth_audit_log** | Governance | Auth audit log | KIRO | Active | tables/lupo_auth_audit_log.md | — | |
| **lupo_governance_overrides** | Governance | Governance overrides | KIRO | Active | tables/lupo_governance_overrides.md | — | |
| **lupo_hotfix_registry** | Governance | Hotfix registry | KIRO | Active | tables/lupo_hotfix_registry.md | — | |
| **lupo_kapu_*** | Governance/Kapu | Kapu events, restoration | KIRO | Active | tables/lupo_kapu_*.md | — | |
| **lupo_gov_*** | Governance | Gov events, timeline, valuations | KIRO | Active | tables/lupo_gov_*.md | — | |
| **lupo_system_*** | System | Commands, config, events, logs | KIRO | Active | tables/lupo_system_*.md | — | |
| **lupo_memory_*** | Memory | Events, rollups | KIRO | Active | tables/lupo_memory_*.md | — | |
| **lupo_event_log** | Events | Event log | KIRO | Active | tables/lupo_event_log.md | — | |
| **lupo_event_metadata** | Events | Event metadata | KIRO | Active | tables/lupo_event_metadata.md | — | |
| **lupo_interpretation_log** | Logging | Interpretation log | KIRO | Active | tables/lupo_interpretation_log.md | — | |
| **lupo_meta_log_events** | Logging | Meta log events | KIRO | Active | tables/lupo_meta_log_events.md | — | |
| **lupo_human_history_meta** | History | Human history meta | KIRO | Active | tables/lupo_human_history_meta.md | — | |
| **lupo_temporal_coherence_snapshots** | Temporal | Coherence snapshots | KIRO | Active | tables/lupo_temporal_coherence_snapshots.md | — | |
| **lupo_world_*** | World | Events, registry | KIRO | Active | tables/lupo_world_*.md | — | |

*(For complete table list including other agent domains, see Cursor-authored `SCHEMA_REGISTRY.md`)*

## Summary Counts (Based on YAML TOONs)

- **TOON tables (YAML)**: 230+ files in `database/lupopedia/toon/`
- **livehelp_* (Migration)**: 34 tables
- **lupo_* (Active)**: ~196 tables
- **Deprecated**: 16 files in `deprecated/` directory
- **Uncertain tables**: 4 (`lupo_actor_properties`, `lupo_file_index`, `lupo_headers`, `lupo_operators`)

## Tables with No TOON (From Old Docs Only)

These appear in existing docs or migration references but **do not** have a current TOON in `database/lupopedia/toon/`. Treated as **Removed** or **Uncertain** until verified.

- **lupo_actor_properties** — Referenced in MIGRATION_MAPPING_REFERENCE (livehelp_users → lupo_actors, lupo_actor_properties). No TOON found. **Uncertain** (may be required table not yet in TOON set).
- **lupo_file_index**, **lupo_headers** — Listed in plan (Metadata & FLARE). No TOON in list. **Uncertain** (may be consolidated into lupo_metadata or renamed).
- **lupo_operators** — Documented as DROPPED (operator_to_roles_migration). **Removed**.

## Migration Reference

- **Primary:** `docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md`
- **Doctrine:** `docs/doctrine/migrations/livehelp_migrations_readme.md` (relocation notice; migration docs live in tables/*_migration.md).

## Coordination Rules (KIRO Authority)

1. **First agent claims ownership**: If two agents encounter a missing documentation file, the first agent that creates the file becomes the owner.
2. **Do not modify files you did not create**: Update only if table belongs to your assigned domain.
3. **Migration tables are Windsurf ownership**: All `livehelp_*` Crafty Syntax migration staging tables.
4. **Never delete documentation files**: Move obsolete docs to `deprecated/` with explanation notes.
5. **KIRO is final authority**: Resolves duplicate documentation, domain conflicts, removed tables, orphan documentation.

## Immediate Actions Required

1. **Resolve TOON format discrepancy**: Determine canonical primary key for `lupo_actors` (`actor_name` vs `actor_id`)
2. **Clean up duplicate FLARE/LUPOPEDIA HEADERS**: Many files have multiple header blocks
3. **Complete directory reorganization**: Move all migration docs to `migrations/`, establish clear `active/` canonical
4. **Update version references**: Standardize all files to current version 4.0.74
5. **Assign tables to agents**: Distribute documentation responsibilities based on domain matrix

## Next Steps

1. **Present findings to Captain Wolfie** for directive clarification on TOON format
2. **Coordinate with IDE agents** for domain reassignment
3. **Execute cleanup and standardization** as KIRO schema coordinator
4. **Implement validation pipeline** for automated checks

---
**KIRO Schema Coordinator** (actor_id 100 per registry)  
*Ensuring canonical truth across Lupopedia's semantic architecture*