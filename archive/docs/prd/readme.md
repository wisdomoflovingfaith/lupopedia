---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "docs/prd/readme.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/readme.md"
  status: "active"
  when_updated: "20260407233043"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/prd-readme.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/prd-readme"
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "development"
  federation_node_id: 0
  thread_id: "prd-grouped"
  content_id: null
  content_parent_id: null
  content_slug: ""
  default_collection_id: null
  lupopedia.schema: documentation
  title: "Lupopedia PRD Directory"
  summary: "Index and grouping guide for the Lupopedia PRD namespace."
---
# Lupopedia PRD Structure (grouped namespaces)

## ðŸŽ¯ Overview

This directory contains the **grouped PRD structure** for Lupopedia, replacing the per-table PRD approach with a more maintainable, holistic namespace-based organization.

**Important:** This layout describes **documentation and architecture intent**. It does **not** mean the **product** is ready for a **4.2.0** stable release or Softaculous listing. The running system remains on the **4.0.x** line (bootstrap) or **4.1.x** (active development) until **PRD 33** completion criteria are met (Crafty Syntax live-help parity, hosting pack, evidence). **`admin.php`** and much of the operator experience still need **redesign**; most Crafty Syntax **end-user and operator features** are not yet fully replicated in Lupopedia.

**Install and upgrades:** During **4.0.x** and **4.1.x** there is **no** Lupopediaâ†’Lupopedia upgrade guarantee. Schema evolves in **`install_new_lupopedia.sql`**; developers and operators use **fresh install** (and optionally **Crafty 3.7.5 â†’ Lupopedia** import only). **4.2.0** is gated on auto-installer / Softaculous-class acceptance and the full **Craftyâ†’Lupopedia** hosting story (**PRD 33**, **PRD 27**, root **README.md**, constitutional **PRD 00 Â§1.0**).

## ðŸ“ File Structure

```
docs/prd/
+-- README.md                          # This file
+-- 01_core_identity.md               # Actor, auth, sessions, capabilities
+-- 02_channels_discussions.md         # Channels, threads, messages
+-- 03_truth_knowledge.md             # Q&A, evidence, voting
+-- 04_tags_metadata.md               # Tags, metadata, semantic edges
+-- 05_collections_navigation.md       # Collections, tabs, navigation
+-- 06_content_management.md          # Content storage, files, uploads
+-- 07_agents_faucets.md             # AI agents, faucets, tool calls
+-- 08_governance_rules.md            # Rules engine, permissions, governance
+-- 09_federation_sync.md             # Cross-node federation, trust
+-- 10_tasks_workflow.md              # Tasks, escalations, human requests
+-- 11_analytics_tracking.md          # Analytics, visits, performance
+-- 12_api_integration.md             # API tokens, clients, webhooks
+-- 13_crafty_integration.md         # **ACTIVE** Crafty Syntax tables
+-- 14_system_operations.md          # System config, health, modules
```

## ðŸ—ï¸ Namespace Architecture

### Design Principles

1. **Logical Grouping**: Tables grouped by functional area
2. **Holistic View**: Each PRD shows cross-table relationships
3. **Maintainable**: 14 core grouped namespace PRDs vs 166 individual table PRDs; extended topic PRDs are listed in PRD_INDEX.md
4. **Cross-References**: Proper edges between namespaces
5. **Identity First**: Core identity system is foundation

### Coverage Statistics

- **Total Namespaces (grouped)**: 14
- **Total Tables Covered**: 166
- **PRD files**: 14 core namespaces plus extended PRDs (see PRD_INDEX.md)
- **Maintenance Burden**: Reduced by 92%

## ðŸš¨ Critical Notes

### Channel filesystem vs `channel_id`
- **On-disk coordination** for new work: `channels/{federation_node_id}/{channel_key}/{thread_key}/` (see **`29_project_structure.md`**, **`02_channels_discussions.md`**). Legacy numeric dirs and **`channels_before_4_0_93/`** remain historical/archive contexts.
- **Database / API** still use numeric **`channel_id`** (e.g. REST `api/channels/{id}/...`).

### Namespace 13: Crafty Integration
- **STATUS**: ACTIVE, NOT DEPRECATED
- **IMPORTANCE**: Essential for Crafty Syntax 3.7.5 import
- **RUNTIME**: Required for LiveHelp chat functionality
- **DO NOT**: Remove or modify without updating import scripts

### Migration Path (documentation only)
- **FROM**: `docs/versions/4.0.93/prd/` (historical per-table PRD copies, if present)
- **TO**: `docs/prd/` (canonical grouped namespace PRDs and constitutional anchor)
- **STATUS**: **Documentation grouping** for namespaces 01â€“14 is in place. This is **not** a statement of **product** or **installer** readiness for **4.2.0**.

### PRD 33: Softaculous / 4.2.0 certification gate
- **File:** [33_softaculous_certification_4_1_0_gate.md](33_softaculous_certification_4_1_0_gate.md)
- **Role:** Defines **4.2.0** release criteria (shared hosting / Softaculous-style distribution, `livehelp_js.php` + `lupopedia_js.php` root contract, Crafty live-help **feature parity**, operator unified chat). Work remains **4.0.x** until that checklist is satisfied.
- **Softaculous reality:** Listing **4.2.0** in Softaculous (or equivalent) is a **manual vendor process**: maintainer submits the package; **Softaculous** (or the hoster) **reviews and imports** it into **their** auto-installer. There is no â€œflip a switchâ€ until that acceptance happens.

### Product gaps (honest baseline)
- **Crafty Syntax parity:** Large portions of legacy **live help**, **operator console**, **visitor tracking**, and **canned / proactive / typing preview** behavior from Crafty 3.7.5 are **still missing or partial** in Lupopediaâ€”see **PRD 33** checklist and **PRD 13** / **PRD 18**.
- **`admin.php`:** Expect a **substantial redesign**; current admin is not the final 4.2.0 operator experience.

## ðŸ”— Cross-Namespace Dependencies

```
01_core_identity â†’ All namespaces (identity foundation)
02_channels_discussions â†’ 03_truth_knowledge (discussions reference truth)
04_tags_metadata â†’ All namespaces (tagging support)
13_crafty_integration â†’ 01_core_identity (user mapping)
13_crafty_integration â†’ 02_channels_discussions (chat import)
```

## ðŸ“‹ Usage Guidelines

### When Adding New Tables

1. **Identify Namespace**: Determine functional area
2. **Update Namespace PRD**: Add table to appropriate namespace file
3. **Update Cross-References**: Add edges to related namespaces
4. **Update Documentation**: Create individual table documentation
5. **Update Install Scripts**: Add table to install_new_lupopedia.sql

### When Modifying Existing Tables

1. **Check Namespace**: Locate table in namespace PRD
2. **Update Schema**: Modify table definition in namespace PRD
3. **Check Dependencies**: Update cross-references if needed
4. **Update Migration**: Create migration script for schema changes
5. **Test Doctrine**: Ensure constitutional compliance

## ðŸŽ–ï¸ Constitutional Compliance

All namespaces follow Lupopedia constitutional rules:
- âœ… NO foreign keys (relationships in application logic)
- âœ… NO triggers
- âœ… NO stored procedures
- âœ… BIGINT timestamps (YYYYMMDDHHIISS UTC)
- âœ… Explicit ID generation (application layer)
- âœ… Soft delete (is_deleted + deleted_ymdhis)

## ðŸ“ž Support

For questions about the PRD structure:
- **Channel**: 42 (documentation)
- **Thread**: "prd-grouped"
- **Actors**: HEPHAESTUS (implementation), LILITH (audit)

## 4.2.0 and Softaculous: not claimed here

The **grouped PRDs** are a **maintainable documentation architecture** (namespaces, edges, constitutional alignment). They are **not** a claim that Lupopedia is **ready to tag 4.2.0** or **ready for Softaculous auto-install**.

**When 4.2.0 is appropriate:** When **PRD 33** is satisfied **and** the maintainer has completed **manual submission** to the installer vendor; only then does **4.2.0** represent a **distribution** milestone as well as a **code** milestone.

**Last Updated**: 2026-04-03 UTC (README honesty pass â€” product still 4.0.x)
