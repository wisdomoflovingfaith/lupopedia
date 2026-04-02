---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/prd/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/README.md"
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "prd-grouped"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root|lilith:audit"
  artifact_type: "documentation"
  artifact_kind: "prd_readme"
  purpose: "Overview of grouped PRD structure for Lupopedia 4.1.0"
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
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
---

# Lupopedia PRD Structure - 4.1.0 Ready

## 🎯 Overview

This directory contains the **grouped PRD structure** for Lupopedia 4.1.0, replacing the per-table PRD approach with a more maintainable, holistic namespace-based organization.

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

### Namespace 13: Crafty Integration
- **STATUS**: ACTIVE, NOT DEPRECATED
- **IMPORTANCE**: Essential for Crafty Syntax 3.7.5 import
- **RUNTIME**: Required for LiveHelp chat functionality
- **DO NOT**: Remove or modify without updating import scripts

### Migration Path
- **FROM**: `lupo-docs/versions/4.0.93/prd/` (historical per-table PRD copies, if present)
- **TO**: `lupo-docs/prd/` (canonical grouped namespace PRDs and constitutional anchor)
- **STATUS**: Complete and ready for 4.1.0

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

## 🚀 Ready for 4.1.0

This grouped PRD structure is **production-ready** for Lupopedia 4.1.0 release, providing:
- Complete system coverage
- Maintainable documentation
- Clear architectural boundaries
- Proper cross-references
- Constitutional compliance

**Last Updated**: 2026-03-30 16:30:00 UTC
