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
  message: "Created Federation Doctrine documentation defining the federation architecture, installation sovereignty, federation registry, discovery, identity, and interaction with semantic graphs, agents, and navigation tabs."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "federation"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Federation Doctrine Overview
  - Federation Registry
  - Installation Sovereignty
  - Federation Discovery
  - Federation Identity
  - Cross-Installation References
  - Federation & Semantic Graphs
  - Federation & Crafty Syntax Import
  - Federation & Agents
  - Federation & Navigation Tabs
  - Federation & Installation Lifecycle
file:
  title: "Federation Doctrine"
  description: "Documents the federation architecture of Lupopedia, including installation sovereignty, federation registry, discovery, identity, and interaction with semantic graphs, agents, navigation tabs, and Crafty Syntax import."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Federation Doctrine

This section documents the federation architecture of Lupopedia. Federation defines how installations discover each other, identify each other, reference each other, and maintain sovereignty. Federation does NOT merge content, semantic graphs, or databases. Each installation is a sovereign semantic universe.

---

## Overview

A Lupopedia federation is a network of independent installations. Each installation:

- has its own database,
- has its own content,
- has its own semantic graph,
- has its own ingestion radius,
- has its own agents (except kernel agents),
- has its own navigation tabs,
- has its own Crafty Syntax import,
- has its own Q&A mappings.

Federation connects installations, not content.

---

## Federation Registry

Each installation maintains a table:

lupo_federation_nodes

This table lists known installations.

Rules:
- federation_node_id = 0 → lupopedia.com (root)
- federation_node_id = 1 → this installation (local)
- federation_node_id >= 2 → other installations this one knows about

Each row contains:
- installation URL,
- installation title,
- installation description,
- installation public key (future),
- last_seen timestamp,
- trust level (future).

---

## Installation Sovereignty

Each installation is sovereign.

This means:
- no installation may modify another's content,
- no installation may modify another's semantic graph,
- no installation may crawl another's private data,
- no installation may merge graphs across federation,
- no installation may override another's navigation tabs,
- no installation may override another's agents.

Installations communicate only through:
- URLs,
- metadata,
- federation registry entries.

---

## Federation Discovery

Installations may discover each other through:

- manual entry,
- auto-installers,
- shared links,
- federation pings (future),
- shared Collections (future).

Discovery rules:
- discovery is opt-in,
- discovery does not imply trust,
- discovery does not imply content sharing.

---

## Federation Identity

Each installation is identified by:
- its base URL,
- its federation_node_id,
- its public metadata.

Identity rules:
- URLs must be canonical,
- URLs must not contain session data,
- URLs must not contain private paths,
- federation_node_id must never change.

---

## Cross-Installation References

Installations may reference each other's content by URL.

Rules:
- references create local entities,
- references store metadata only,
- references do NOT ingest remote content,
- references do NOT merge semantic graphs,
- references do NOT crawl remote installations.

Cross-installation references are URL-based only.

---

## Federation & Semantic Graphs

Semantic graphs are local.

Federation does NOT:
- merge graphs,
- synchronize graphs,
- replicate graphs,
- unify graphs.

Each installation maintains its own graph.

Cross-installation references appear as:
- external entities,
- radius 2 content,
- URL-identified objects.

---

## Federation & Crafty Syntax Import

Crafty Syntax import is per installation.

Each installation imports:
- its own Crafty Syntax data,
- its own help pages,
- its own departments,
- its own categories,
- its own Q&A mappings.

Federation does NOT:
- share Crafty Syntax data,
- merge Crafty Syntax imports,
- synchronize Crafty Syntax content.

---

## Federation & Agents

Agents are local to each installation.

Rules:
- kernel agents are identical across installations,
- application agents may differ,
- external_ai agents may differ,
- agent registry is local,
- agent prompts are local,
- agent capabilities are local.

Federation does NOT:
- share agents,
- synchronize agents,
- merge agent registries.

---

## Federation & Navigation Tabs

Navigation Tabs are local.

Rules:
- tabs do not federate,
- tabs do not sync,
- tabs do not merge,
- tabs do not define global meaning.

Future versions may introduce Shared Collections.

---

## Federation & Installation Lifecycle

When an installation is created:
- it registers itself as federation_node_id = 1,
- it registers lupopedia.com as federation_node_id = 0,
- it may register other installations manually or automatically.

When an installation is deactivated:
- it remains in other installations' federation registries,
- cross-installation references remain valid,
- semantic graph entries are preserved.

---
