---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Updated TERMINOLOGY.md: Clarified that nodes are domain installations (server installations), not AI agents. Added distinction between nodes, agents, and channels."
  mood: "00FF00"
tags:
  categories: ["documentation", "reference", "terminology"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Lupopedia Terminology"
  description: "Standard terminology used throughout Lupopedia's documentation for consistency and clarity"
  version: "4.0.1"
  status: published
  author: "Captain Wolfie"
---

# 📖 Lupopedia Terminology

This document defines the standard terminology used throughout Lupopedia's documentation to ensure consistency and clarity.

## Core Concepts

| Term | Aliases | Definition | Example |
|------|---------|------------|---------|
| **Atom** | Node, Entity | The fundamental unit of content in Lupopedia's semantic graph | `article:42`, `user:7` |
| **Edge** | Relationship, Link | A directed connection between two atoms with a specific type | `is_author_of`, `contains` |
| **Tab Path** | Path, Location | A hierarchical string representing content location | `/docs/architecture/overview` |
| **Soft Delete** | Archive | Marking content as deleted without removing it from the database | `deleted_at = NOW()` |
| **AI Agent** | Agent, Bot | An autonomous software agent that processes and generates content | Content Moderation Agent |
| **Knowledge Graph** | Semantic Graph, Ontology | The structured representation of relationships between content items | The entire Lupopedia content graph |
| **Node** | Installation, Domain Installation | A domain installation of Lupopedia (server installation, not an AI agent). Each node is a sovereign world with its own database, agents, content, and governance. Identified by `domain_name`, `domain_root` (canonical URL), and `install_url`. | `acme.lupo.example` |
| **Domain** | Knowledge Domain | A subject area or knowledge field (e.g., tech, science, games) | `tech`, `science` |
| **Agent** | AI Agent, Bot | An AI entity that runs on a node (WOLFIE, LILITH, MAAT, etc.). Agents are not nodes; they run on nodes. | `WOLFIE`, `LILITH` |
| **Channel** | Communication Space | A communication space within a node for multi-agent conversations. Channels are shared spaces, not nodes. | Support channel, Dev channel |

## Database Terms

| Term | Aliases | Definition | Example |
|------|---------|------------|---------|
| **Base Table** | Core Table | A primary table storing entity data | `atoms`, `edges`, `users` |
| **Extension Table** | Property Table | A table that extends a base table with additional fields | `atom_strings`, `atom_integers` |
| **EAV** | Entity-Attribute-Value | A data model that stores properties as key-value pairs | Storing article metadata |

## Versioning

| Term | Aliases | Definition | Example |
|------|---------|------------|---------|
| **Version** | Release | A specific, immutable state of the codebase | `1.0.2` |
| **Branch** | Fork | A parallel development line | `main`, `feature/semantic-search` |
| **Tag** | Label | A named reference to a specific commit | `v1.0.2` |

## API Terms

| Term | Aliases | Definition | Example |
|------|---------|------------|---------|
| **Endpoint** | Route | A specific URL pattern that handles requests | `GET /api/v1/atoms` |
| **Payload** | Request Body | Data sent in an API request | `{"title":"New Post"}` |
| **Response** | Result | Data returned from an API call | `{"id": 42, "title":"New Post"}` |

## Best Practices

1. **Consistency**: Always prefer the primary term over its aliases in documentation
2. **Context**: Use the most specific term that applies to the context
3. **Definitions**: When introducing a term, provide its definition on first use
4. **Cross-Reference**: Link to this document when introducing new terminology

## Adding New Terms

1. Add new terms in the appropriate section
2. Include all common aliases
3. Provide a clear, concise definition
4. Add an example if helpful
5. Update the version number below when making changes

---
*Document version: 1.0.0*  
*Last updated: 2025-01-04*
