---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  version_when_written: "4.0.93"
  when_updated: "20260403202128"
  file_path_from_root: "lupo-docs/prd/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/README.md"
  last_modified_utc: "20260403202128"
  channel_id: 42
  thread_id: "prd-grouped"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root|lilith:audit"
  artifact_type: "documentation"
  artifact_kind: "prd_readme"
  purpose: "Overview of grouped PRD structure; documentation layout is stable — product is 4.0.x until Crafty parity, admin redesign, and Softaculous manual acceptance (4.1.0 gate per PRD 33)"
  tags:
  - "prd"
  - "documentation"
  - "namespace"
  - "4.1.0"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/prd/01_core_identity.md"
      type: references
      weight: 1.0
      reason: "Foundation identity system"
    - to: "lupo-docs/prd/13_crafty_integration.md"
      type: references
      weight: 1.0
      reason: "Essential Crafty Syntax integration"
    - to: "lupo-docs/versions/4.0.93/DATABASE_AUDIT_SUMMARY.md"
      type: references
      weight: 1.0
      reason: "Database audit results"
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: references
      weight: 1.0
      reason: "4.1.0 / Softaculous gate — product readiness is not implied by this README"
lupopedia.footer:
  last_verified: "20260403202128"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
---

# Lupopedia PRD Structure (grouped namespaces)

## 🎯 Overview

This directory contains the **grouped PRD structure** for Lupopedia, replacing the per-table PRD approach with a more maintainable, holistic namespace-based organization.

**Important:** This layout describes **documentation and architecture intent**. It does **not** mean the **product** is ready for a **4.1.0** or Softaculous listing. The running system remains on the **4.0.x** line until **PRD 33** completion criteria are met (Crafty Syntax live-help parity, hosting pack, evidence). **`admin.php`** and much of the operator experience still need **redesign**; most Crafty Syntax **end-user and operator features** are not yet fully replicated in Lupopedia.

**Install and upgrades:** During **4.0.x** there is **no** Lupopedia→Lupopedia upgrade. Schema evolves in **`install_new_lupopedia.sql`**; developers and operators use **fresh install** (and optionally **Crafty 3.7.5 → Lupopedia** import only). **4.1.0** is gated on auto-installer / Softaculous-class acceptance and the full **Crafty→Lupopedia** hosting story (**PRD 33**, **PRD 27**, root **README.md**, constitutional **PRD 00 §1.0**).

## 📁 File Structure

```
lupo-docs/prd/
├── README.md                          # This file
├── 01_core_identity.md               # Actor, auth, sessions, capabilities
├── 02_channels_discussions.md         # Channels, threads, messages
├── 03_truth_knowledge.md             # Q&A, evidence, voting
├── 04_tags_metadata.md               # Tags, metadata, semantic edges
├── 05_collections_navigation.md       # Collections, tabs, navigation
├── 06_content_management.md          # Content storage, files, uploads
├── 07_agents_faucets.md             # AI agents, faucets, tool calls
├── 08_governance_rules.md            # Rules engine, permissions, governance
├── 09_federation_sync.md             # Cross-node federation, trust
├── 10_tasks_workflow.md              # Tasks, escalations, human requests
├── 11_analytics_tracking.md          # Analytics, visits, performance
├── 12_api_integration.md             # API tokens, clients, webhooks
├── 13_crafty_integration.md         # **ACTIVE** Crafty Syntax tables
└── 14_system_operations.md          # System config, health, modules
```

## 🏗️ Namespace Architecture

### Design Principles

1. **Logical Grouping**: Tables grouped by functional area
2. **Holistic View**: Each PRD shows cross-table relationships
3. **Maintainable**: 14 files vs 166 individual table PRDs
4. **Cross-References**: Proper edges between namespaces
5. **Identity First**: Core identity system is foundation

### Coverage Statistics

- **Total Namespaces**: 14
- **Total Tables Covered**: 166
- **PRD Coverage**: 100% (14/14 files)
- **Maintenance Burden**: Reduced by 92%

## 🚨 Critical Notes

### Channel filesystem vs `channel_id`
- **On-disk coordination** for new work: `lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/` (see **`29_project_structure.md`**, **`02_channels_discussions.md`**). Legacy numeric dirs and **`lupo-channels_before_4_0_93/`** remain historical/archive contexts.
- **Database / API** still use numeric **`channel_id`** (e.g. REST `api/lupo-channels/{id}/...`).

### Namespace 13: Crafty Integration
- **STATUS**: ACTIVE, NOT DEPRECATED
- **IMPORTANCE**: Essential for Crafty Syntax 3.7.5 import
- **RUNTIME**: Required for LiveHelp chat functionality
- **DO NOT**: Remove or modify without updating import scripts

### Migration Path (documentation only)
- **FROM**: `lupo-docs/versions/4.0.93/prd/` (historical per-table PRD copies, if present)
- **TO**: `lupo-docs/prd/` (canonical grouped namespace PRDs and constitutional anchor)
- **STATUS**: **Documentation grouping** for namespaces 01–14 is in place. This is **not** a statement of **product** or **installer** readiness for **4.1.0**.

### PRD 33: Softaculous / 4.1.0 certification gate
- **File:** [33_softaculous_certification_4_1_0_gate.md](33_softaculous_certification_4_1_0_gate.md)
- **Role:** Defines **4.1.0** release criteria (shared hosting / Softaculous-style distribution, `livehelp_js.php` + `lupopedia_js.php` root contract, Crafty live-help **feature parity**, operator unified chat). Work remains **4.0.x** until that checklist is satisfied.
- **Softaculous reality:** Listing **4.1.0** in Softaculous (or equivalent) is a **manual vendor process**: maintainer submits the package; **Softaculous** (or the hoster) **reviews and imports** it into **their** auto-installer. There is no “flip a switch” until that acceptance happens.

### Product gaps (honest baseline)
- **Crafty Syntax parity:** Large portions of legacy **live help**, **operator console**, **visitor tracking**, and **canned / proactive / typing preview** behavior from Crafty 3.7.5 are **still missing or partial** in Lupopedia—see **PRD 33** checklist and **PRD 13** / **PRD 18**.
- **`admin.php`:** Expect a **substantial redesign**; current admin is not the final 4.1.0 operator experience.

## 🔗 Cross-Namespace Dependencies

```
01_core_identity → All namespaces (identity foundation)
02_channels_discussions → 03_truth_knowledge (discussions reference truth)
04_tags_metadata → All namespaces (tagging support)
13_crafty_integration → 01_core_identity (user mapping)
13_crafty_integration → 02_channels_discussions (chat import)
```

## 📋 Usage Guidelines

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

## 🎖️ Constitutional Compliance

All namespaces follow Lupopedia constitutional rules:
- ✅ NO foreign keys (relationships in application logic)
- ✅ NO triggers
- ✅ NO stored procedures
- ✅ BIGINT timestamps (YYYYMMDDHHIISS UTC)
- ✅ Explicit ID generation (application layer)
- ✅ Soft delete (is_deleted + deleted_ymdhis)

## 📞 Support

For questions about the PRD structure:
- **Channel**: 42 (documentation)
- **Thread**: "prd-grouped"
- **Actors**: HEPHAESTUS (implementation), LILITH (audit)

## 4.1.0 and Softaculous: not claimed here

The **grouped PRDs** are a **maintainable documentation architecture** (namespaces, edges, constitutional alignment). They are **not** a claim that Lupopedia is **ready to tag 4.1.0** or **ready for Softaculous auto-install**.

**When 4.1.0 is appropriate:** When **PRD 33** is satisfied **and** the maintainer has completed **manual submission** to the installer vendor; only then does **4.1.0** represent a **distribution** milestone as well as a **code** milestone.

**Last Updated**: 2026-04-03 UTC (README honesty pass — product still 4.0.x)
