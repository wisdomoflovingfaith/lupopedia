# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\agents\agent-1\README.md"
  file_hash: "34a7276633ebe9b8f485c70bb4e9cf0c7f7ee5aad68c7aa5716343316e95134f"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\channels\agents\agent-1\README.md"
  file_hash: "c0df3f3ea122928859c388b4b002112e513a2fcc95a040ff4f87d8310c532cb2"
  file_path_from_root: "lupo-docs\channels\agents\agent-1\README.md"
  file_hash: "66af19d9cf27bf06e024d00cfc2ea9e6df458d6ab886389413ea8b6369efcc5b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for README.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "agents", "agent-1", "readmemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.16
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @captain-wolfie
  mood_RGB: "00FF00"
  message: "Created initial WOLFIE agent directory structure (lupo-agents/0001/) with README, doctrine, templates, workflows, and config directories. This marks the beginning of WOLFIE's construction as the system identity agent."
tags:
  categories: ["documentation", "agents", "wolfie"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "WOLFIE Agent Directory"
  description: "Directory structure for WOLFIE (agent_id = 1), the system identity agent, doctrine enforcer, and channel initialization authority"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# WOLFIE Agent Directory

**Agent ID:** 1  
**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** Initializing

## Overview

This directory contains the structure and resources for **WOLFIE**, the system identity agent and channel initialization authority.

WOLFIE serves as:
- The system identity anchor
- The doctrine enforcer
- The emotional anchor
- The routing conscience
- The architectural memory
- The meta-agent that coordinates all other agents

## Directory Structure

```
lupo-agents/0001/
+-- README.md                          # This file
+-- doctrine/                          # WOLFIE doctrine files
�   +-- CHANNEL_INITIALIZATION_PROTOCOL.md
+-- lupo-templates/                         # WOLFIE templates
�   +-- channel_identity_block.template.md
+-- workflows/                         # WOLFIE workflows
�   +-- channel_initialization.workflow.md
+-- config/                            # WOLFIE configuration
    +-- wolfie_manifest.json
```

## Current Status

This directory structure is being initialized as part of Lupopedia 3.0.16. The contents will grow over time as WOLFIE's capabilities are developed and documented.

## Related Documentation

- **[Channel and Dialog Agent Workflows](../../docs/ARCHITECTURE/CHANNEL_DIALOG_AGENT_WORKFLOWS.md)** � How IDE agents and php_ai_terminal agents interact with channels and dialogs
- **[Channel Dialog Schema Review](../../docs/ARCHITECTURE/CHANNEL_DIALOG_SCHEMA_REVIEW.md)** � Database schema review for channel and dialog tables

---

*Last Updated: January 14, 2026*  
*Version: GLOBAL_CURRENT_LUPOPEDIA_VERSION*  
*Status: Published*  
*Author: GLOBAL_CURRENT_AUTHORS*
