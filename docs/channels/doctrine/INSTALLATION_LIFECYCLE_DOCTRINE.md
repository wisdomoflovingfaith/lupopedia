---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-10
author: GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created Installation Lifecycle Doctrine documentation defining the complete lifecycle stages from conception through archival for Lupopedia installations."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "installation", "lifecycle"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Installation Lifecycle Doctrine Overview
  - Installation Lifecycle Stages (10 stages)
  - Stage 1 (Conception)
  - Stage 2 (Initialization)
  - Stage 3 (Federation Registration)
  - Stage 4 (Semantic Graph Construction)
  - Stage 5 (Crafty Syntax Import - Optional)
  - Stage 6 (Agent Activation)
  - Stage 7 (Operation)
  - Stage 8 (Upgrade)
  - Stage 9 (Deprecation)
  - Stage 10 (Archival)
  - Installation Invariants
file:
  title: "Installation Lifecycle Doctrine"
  description: "Documents the complete lifecycle of a Lupopedia installation, from conception through archival, ensuring safety, sovereignty, and deterministic behavior across the federation."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Installation Lifecycle Doctrine

This section documents the complete lifecycle of a Lupopedia installation. Each installation is a sovereign semantic universe with its own database, content, semantic graph, agents, navigation tabs, Crafty Syntax lineage, and federation identity.

Installations do not share content.  
Installations do not merge graphs.  
Installations communicate only through URLs and federation metadata.

---

## Installation Lifecycle Stages

Every installation passes through the following stages:

1. Conception  
2. Initialization  
3. Federation Registration  
4. Semantic Graph Construction  
5. Crafty Syntax Import (optional)  
6. Agent Activation  
7. Operation  
8. Upgrade  
9. Deprecation  
10. Archival  

Each stage has strict doctrine to ensure safety, sovereignty, and deterministic behavior across the federation.

---

## Stage 1 — Conception

An installation begins as:

- a domain or subdomain,
- a database,
- a filesystem,
- a configuration file,
- a federation identity.

Conception rules:
- installation must have a unique base URL,
- installation must have its own database,
- installation must not share tables with other installations,
- installation must not inherit content from other installations.

---

## Stage 2 — Initialization

Initialization occurs when Lupopedia is installed.

Initialization tasks:
- create required tables,
- create required directories,
- create default agents,
- create default navigation tabs,
- create default Collections,
- create default semantic graph structures,
- create federation registry entries.

Initialization must be:
- deterministic,
- idempotent,
- doctrine-aligned.

---

## Stage 3 — Federation Registration

Every installation registers:

- federation_node_id = 0 → lupopedia.com  
- federation_node_id = 1 → itself  

Additional installations may be added manually or automatically.

Registration rules:
- federation_node_id must never change,
- base URL must be canonical,
- installations must not impersonate each other,
- installations must not modify each other's registry entries.

---

## Stage 4 — Semantic Graph Construction

Each installation constructs its own semantic graph.

Graph construction includes:
- creating entity records,
- creating relationship records,
- creating metadata records,
- generating inheritance edges from Navigation Tabs,
- generating placement edges from Collections,
- generating linkage edges from content URLs.

Graph construction must NOT:
- merge graphs across installations,
- crawl remote installations,
- ingest remote content,
- infer meaning creatively.

---

## Stage 5 — Crafty Syntax Import (Optional)

If the installation originated from Crafty Syntax:

- import help pages,
- import departments,
- import categories,
- import Q&A mappings,
- import navigation structure,
- import content files.

Crafty Syntax import rules:
- import is one-way,
- import is immutable historical truth,
- import must not be rewritten,
- import must not be merged with other installations.

---

## Stage 6 — Agent Activation

Each installation activates its agents:

- kernel agents (shared across all installations),
- application agents (local),
- external_ai agents (local),
- utility agents (local).

Activation rules:
- agent registry is local,
- agent prompts are local,
- capabilities are local,
- faucet rules are local.

Agents must not:
- access other installations,
- modify federation registry,
- modify semantic graph directly.

---

## Stage 7 — Operation

During normal operation, an installation:

- serves content,
- updates content,
- updates semantic graph metadata,
- invokes agents,
- participates in federation,
- maintains navigation tabs,
- maintains Collections.

Operation rules:
- installation must remain sovereign,
- installation must not crawl remote content,
- installation must not merge graphs,
- installation must not impersonate other installations.

---

## Stage 8 — Upgrade

Upgrades may be triggered by:

- auto-installers,
- CASTCADE,
- manual updates,
- version migrations.

Upgrade tasks:
- update database schema,
- update agents,
- update doctrine,
- update semantic graph structures,
- update navigation tabs (if needed),
- update federation metadata.

Upgrade rules:
- upgrades must be idempotent,
- upgrades must preserve content,
- upgrades must preserve semantic graph,
- upgrades must preserve federation registry,
- upgrades must not break lineage.

---

## Stage 9 — Deprecation

An installation may be deprecated when:

- domain expires,
- owner shuts it down,
- installation is replaced,
- installation is migrated.

Deprecation rules:
- installation must be marked inactive,
- federation registry must retain stale entry,
- stale entries must not break federation,
- content must remain readable until archival.

---

## Stage 10 — Archival

Archival is the final stage.

Archival tasks:
- freeze database,
- freeze semantic graph,
- freeze content,
- freeze federation metadata,
- move installation to archive storage.

Archival rules:
- archived installations must remain readable,
- archived installations must never be modified,
- archived installations must never be deleted,
- archived installations must never rejoin federation.

---

## Installation Invariants

Across all stages:

1. Installations must remain sovereign.  
2. Installations must not merge content.  
3. Installations must not merge semantic graphs.  
4. Installations must not crawl remote installations.  
5. Installations must not impersonate each other.  
6. Installations must preserve lineage.  
7. Installations must preserve federation identity.  
8. Installations must remain deterministic.  
9. Installations must remain auditable.  
10. Installations must remain doctrine-aligned.  

---
