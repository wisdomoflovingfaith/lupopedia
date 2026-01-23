---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.18
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  message: "Created GLOSSARY.md with precise definitions of all key Lupopedia terminology. Phase 3 documentation consistency audit correction."
  mood: "00FF00"
tags:
  categories: ["documentation", "appendix", "glossary", "terminology"]
  collections: ["core-docs", "appendix"]
  channels: ["dev", "public"]
file:
  title: "Lupopedia Terminology Glossary"
  description: "Precise definitions of all key terms used across Lupopedia doctrine and documentation"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Lupopedia Terminology Glossary

This glossary provides precise, non-overlapping definitions of all key terms used across Lupopedia doctrine and documentation. All definitions are binding and must be used consistently.

---

## Core Concepts

### Lupopedia

A Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE) that hosts agents, content, emotional metadata, and routing logic across independent installations, each functioning as a self-contained knowledge world governed by shared doctrine.

**See:** [DEFINITION.md](../../overview/DEFINITION.md)

---

### WOLFIE

**Acronym:** Web-Organized Linked Federated Intelligent Ecosystem

The architecture type that defines Lupopedia's distributed, multi-agent knowledge system. Originally stood for "Wisdom Of Loving Faith Integrity Ethics" when the project began in August 2025 as a spiritual research engine, but was repurposed to reflect the technical architecture.

**See:** [README.md](../../README.md), [HISTORY.md](../../history/HISTORY.md)

---

## Installation & Deployment

### Installation

A complete deployment of Lupopedia on a single server or domain. Each installation is sovereign and maintains its own database, agents, content, and governance.

**Synonyms:** Domain installation, Lupopedia installation, node (when referring to server-level instances)

**Example:** "The installation at example.com/lupopedia/"

**See:** [SUBDIRECTORY_INSTALLATION_DOCTRINE.md](../../doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md)

---

### Domain Installation

A server-level instance of Lupopedia running on a specific domain. This is the preferred term when distinguishing from content-level nodes in the semantic graph.

**Synonyms:** Installation, server node (avoid), Lupopedia node (when context is clear)

**Example:** "Each domain installation maintains its own database."

**See:** [DEFINITION.md](../../overview/DEFINITION.md)

---

### Host Website

The existing website or web environment that contains the Lupopedia installation. The host website exists in the parent directory structure above `/lupopedia/` and may include:
- An existing CMS (WordPress, Drupal, etc.)
- Static HTML pages
- Custom web applications
- Nothing (standalone Lupopedia installation)

**Critical:** Lupopedia must NEVER interfere with the host website's routing, files, or functionality.

**See:** [Lupopedia-Reference-Layer-Doctrine.md](../../doctrine/Lupopedia-Reference-Layer-Doctrine.md), [SUBDIRECTORY_INSTALLATION_DOCTRINE.md](../../doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md)

---

### Standalone Installation

A Lupopedia installation where there is no host website above `/lupopedia/`. Lupopedia is the only system on the domain.

**Example:** A domain where `/lupopedia/` is installed directly under the document root with no other content.

---

### Embedded Installation

A Lupopedia installation where Lupopedia exists alongside an existing host website. Lupopedia operates as a semantic reference layer without interfering with the host site.

**Example:** A WordPress site at `example.com` with Lupopedia installed at `example.com/lupopedia/`

---

## URL & Path Terminology

### Slug

An opaque identifier representing a URL path as seen in the browser address bar. Slugs are NOT filesystem paths, module names, or internal routes. They are semantic identifiers used for database lookup.

**Format:** Everything after `/lupopedia/` in the URL

**Examples:**
- `who/captain-wolfie`
- `collection/1/tab/about`
- `https://wordpress.com/reader/blogs/10822809/posts/54283` (Crafty Syntax URL)

**Critical:** Slugs must NEVER be resolved to filesystem paths or modified from their stored form.

**See:** [CSLH-URL-Semantics.md](../../doctrine/CSLH-URL-Semantics.md), [URL_ROUTING_DOCTRINE.md](../../doctrine/URL_ROUTING_DOCTRINE.md)

---

### URL

Complete HTTP/HTTPS address including protocol, domain, and path.

**Format:** `protocol://domain/path`

**Example:** `https://example.com/lupopedia/who/captain-wolfie`

**Components:**
- Protocol: `https://`
- Domain: `example.com`
- Path: `/lupopedia/who/captain-wolfie`

---

### URL Path

The path portion of a URL, excluding protocol and domain.

**Format:** `/path/to/resource`

**Example:** `/lupopedia/who/captain-wolfie`

**Critical:** Always use the full term "URL path" to distinguish from filesystem path.

---

### Filesystem Path

The physical location of a file or directory on disk.

**Format:** Absolute or relative path in the server's filesystem

**Examples:**
- `/var/www/html/lupopedia/index.php` (absolute)
- `lupo-includes/classes/` (relative)

**Critical:** Always use the full term "filesystem path" to distinguish from URL path. Slugs are NEVER filesystem paths.

**See:** [SUBDIRECTORY_INSTALLATION_DOCTRINE.md](../../doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md)

---

### LUPOPEDIA_PATH

Constant defining the absolute filesystem path to the Lupopedia installation directory.

**Definition:** `define('LUPOPEDIA_PATH', __DIR__);`

**Usage:** For filesystem operations (includes, file reads, directory scans)

**Example:** `/var/www/html/lupopedia`

**See:** [SUBDIRECTORY_INSTALLATION_DOCTRINE.md](../../doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md)

---

### LUPOPEDIA_PUBLIC_PATH

Constant defining the URL path prefix required to access Lupopedia from a browser.

**Canonical Definition:**
```php
define(
    'LUPOPEDIA_PUBLIC_PATH',
    rtrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__), '/')
);
```

**Usage:** For all internal URLs, links, redirects, and asset paths

**Example:** `/lupopedia` or `/programs/lupopedia`

**See:** [SUBDIRECTORY_INSTALLATION_DOCTRINE.md](../../doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md)

---

## Content & Semantic Layer

### Reference Entry

A database record in `lupo_content` that describes a page on the host website. Reference entries are NOT stored content â€” they are semantic pointers with metadata.

**Contains:**
- Slug (opaque identifier)
- Title and description
- Host URL (link to actual page)
- Semantic edges (relationships to atoms)
- Collection and navigation placement

**Critical:** Reference entries point to content; they do not store content.

**See:** [Lupopedia-Reference-Layer-Doctrine.md](../../doctrine/Lupopedia-Reference-Layer-Doctrine.md), [SAMPLE_REFERENCE_ENTRY.md](../examples/SAMPLE_REFERENCE_ENTRY.md)

---

### Content Entry

Synonym for reference entry. Both terms refer to records in `lupo_content` that are semantic pointers to host website pages.

**Note:** "Content entry" and "reference entry" are interchangeable terms in Lupopedia documentation.

---

### Atom

A semantic concept, entity, or category in the knowledge graph. Atoms represent meaning extracted from user behavior and navigation patterns.

**Stored in:** `lupo_atoms` table

**Examples:**
- `creator` (person)
- `ai_agent` (concept)
- `semantic_os` (concept)

**See:** [ARCHITECTURE.md](../../architecture/ARCHITECTURE.md)

---

### Semantic Edge

A typed, weighted relationship between two entities in the knowledge graph (e.g., content to atom, atom to atom).

**Stored in:** `lupo_content_atoms`, `lupo_atom_edges` tables

**Properties:**
- Relationship type (e.g., "is_about", "has_role")
- Weight (0.0 to 1.0)
- Temporal validity
- Provenance metadata

**See:** [ARCHITECTURE.md](../../architecture/ARCHITECTURE.md)

---

### Semantic Node

An entity in the knowledge graph (atom, content entry, collection, etc.). This term distinguishes graph-level entities from server-level installations.

**Synonyms:** Content node (when referring to content entries), graph node

**Critical:** Use "semantic node" or "content node" to avoid confusion with "domain installation."

---

### Collection

A navigation universe inside Lupopedia with its own tabs, sub-tabs, content, structure, and meaning. Collections reflect the mental model of the website owner.

**Examples:**
- "Desktop" Collection: WHO, WHAT, WHERE, WHEN, WHY, HOW, DO
- "County of Honolulu" Collection: Departments, Parks & Recreation, Activities & Programs

**See:** [README.md](../../README.md)

---

### Collection Tab

A navigation category within a collection. Tabs are user-defined and can represent topics, departments, people, services, programs, actions, or concepts.

**Stored in:** `lupo_collection_tabs` table

**Example:** `collection/1/tab/who`

---

### Navigation Tab

Synonym for collection tab. Both terms refer to user-defined navigation categories within collections.

---

## System Architecture

### Module

A pluggable component that extends Lupopedia functionality. Modules are self-contained and follow standard directory structure.

**Examples:**
- Crafty Syntax module (`modules/craftysyntax/`)
- Dialog module (`modules/dialog/`)

**Location:** `modules/` directory

**See:** [DIRECTORY_STRUCTURE.md](../../doctrine/legacy-core/DIRECTORY_STRUCTURE.md)

---

### System

The complete Lupopedia platform, including all core functionality, modules, agents, and infrastructure.

**Usage:** "The Lupopedia system" refers to the entire platform.

**Example:** "Lupopedia is a semantic operating system."

---

### Application

The running instance of Lupopedia, including runtime state, active sessions, and current operations.

**Usage:** "The application" refers to the operational software.

**Example:** "The application handles HTTP requests and routes them to appropriate handlers."

---

### Lupopedia Kernel

The core system components required for all installations, including:
- Database schema
- Core classes and functions
- Agent framework
- Routing subsystems (HERMES, CADUCEUS)
- Doctrine enforcement

**See:** [ARCHITECTURE.md](../../architecture/ARCHITECTURE.md)

---

## Routing & Agents

### URL Routing

The process of handling incoming HTTP requests, extracting slugs from URLs, and routing to appropriate content (reference entries, collection views, or semantic pages).

**Subsystem:** Front controller, slug extraction, database lookup

**See:** [URL_ROUTING_DOCTRINE.md](../../doctrine/URL_ROUTING_DOCTRINE.md)

---

### Agent Routing

The process of routing messages to AI agents based on classification, capabilities, and emotional context.

**Subsystems:** HERMES (filtering and selection), CADUCEUS (emotional balancing)

**See:** [AGENT_ROUTING_DOCTRINE.md](../../doctrine/AGENT_ROUTING_DOCTRINE.md)

---

### HERMES

The primary agent routing layer responsible for deterministic filtering and candidate selection based on agent classification, capabilities, and requirements.

**Function:** Determines which AI agent receives each message

**See:** [AGENT_ROUTING_DOCTRINE.md](../../doctrine/AGENT_ROUTING_DOCTRINE.md), [ARCHITECTURE_SYNC.md](../../architecture/ARCHITECTURE_SYNC.md)

---

### CADUCEUS

The emotional balancing system that computes channel mood by reading and blending the emotional states of polar agents within a channel.

**Function:** Provides emotional context for agent routing decisions

**Critical:** CADUCEUS is NOT a router; it is an emotional balancer.

**See:** [AGENT_ROUTING_DOCTRINE.md](../../doctrine/AGENT_ROUTING_DOCTRINE.md), [ARCHITECTURE_SYNC.md](../../architecture/ARCHITECTURE_SYNC.md)

---

### Agent

An AI entity that runs on a Lupopedia installation. Agents have classification, capabilities, roles, and can communicate through structured dialog.

**Examples:**
- SYSTEM (Agent 0)
- WOLFIE (Agent 2)
- LILITH (Agent 7)

**See:** [AGENT_RUNTIME.md](../../agents/AGENT_RUNTIME.md)

---

### Channel

A communication space within a Lupopedia installation where humans and AI agents collaborate on tasks. Channels contain polar agents whose moods are balanced by CADUCEUS.

**Example:** Channel 42 containing LILITH, WOLFIE, and a human named BOB

**See:** [AGENT_ROUTING_DOCTRINE.md](../../doctrine/AGENT_ROUTING_DOCTRINE.md)

---

## Namespace & Organization

### Namespace

A prefix or organizational scheme used to prevent slug collisions between different types of entities.

**Examples:**
- Content slugs: `who/captain-wolfie`
- Collection tab slugs: `collection/1/tab/who`
- Atom names: `atom:creator`

**Purpose:** Prevents conflicts when the same identifier could refer to multiple entity types.

**See:** [URL_ROUTING_DOCTRINE.md](../../doctrine/URL_ROUTING_DOCTRINE.md)

---

### Slug Collision

A situation where the same slug matches multiple entity types (content entry, collection tab, atom). Resolved by lookup priority order.

**Resolution:** Content > Collection Tab > Atom > 404

**See:** [URL_ROUTING_DOCTRINE.md](../../doctrine/URL_ROUTING_DOCTRINE.md)

---

## Crafty Syntax Legacy

### Crafty Syntax

The foundational ancestor of Lupopedia, containing 25+ years of behavioral and emotional metadata. Now integrated as a first-party module within Lupopedia.

**Timeline:**
- 2002-2014: Crafty Syntax Live Help (CSLH)
- 2014-2025: Forked to Sales Syntax
- 2025-present: Restored and reborn as semantic root of Lupopedia

**See:** [CSLH-Historical-Context.md](../../history/CSLH-Historical-Context.md)

---

### Crafty Syntax URL

A web-facing URL slug exactly as seen in the browser address bar, stored in Crafty Syntax's `pageurl` field. These URLs are semantic identifiers, NOT filesystem paths.

**Examples:**
- `http://lupopedia.com/what_was_crafty_syntax.php`
- `https://wordpress.com/reader/blogs/10822809/posts/54283`

**Critical:** Must NEVER be resolved to disk or treated as file locations.

**See:** [CSLH-URL-Semantics.md](../../doctrine/CSLH-URL-Semantics.md)

---

## Federation & Network

### Federation

The optional capability for Lupopedia installations to interconnect and share semantic data while maintaining sovereignty.

**Note:** Federation occurs between domain installations, not between agents.

**See:** [ARCHITECTURE.md](../../architecture/ARCHITECTURE.md), [DEFINITION.md](../../overview/DEFINITION.md)

---

### Node (Context-Dependent)

**âš ï¸ AMBIGUOUS TERM - Use specific alternatives:**

**When referring to server-level instances:**
- âœ… Use: "domain installation" or "installation"
- âŒ Avoid: "node" (ambiguous)

**When referring to graph-level entities:**
- âœ… Use: "semantic node" or "content node"
- âŒ Avoid: "node" (ambiguous)

**When context is absolutely clear:**
- "Each node maintains its own database" (clearly means domain installation)
- "Nodes in the knowledge graph" (clearly means semantic nodes)

**See:** [DEFINITION.md](../../overview/DEFINITION.md)

---

## Documentation System

### WOLFIE Header

A YAML frontmatter block at the top of every Lupopedia file containing metadata, dialog information, tags, and file properties.

**Required in:** All documentation files, PHP files, agent files

**See:** [WOLFIE_HEADER_SPECIFICATION.md](../../agents/WOLFIE_HEADER_SPECIFICATION.md)

---

### Atom (Documentation Context)

A symbolic variable used in documentation instead of hardcoded values. Atoms are resolved through a hierarchical scope system.

**Scopes:** FILE_ â†’ DIR_ â†’ DIRR_ â†’ MODULE_ â†’ GLOBAL_

**Example:** `GLOBAL_CURRENT_LUPOPEDIA_VERSION`

**See:** [ATOM_RESOLUTION_SPECIFICATION.md](../../doctrine/ATOM_RESOLUTION_SPECIFICATION.md)

---

### Doctrine

A mandatory, non-negotiable rule or principle that governs Lupopedia architecture, development, or operations.

**Location:** `docs/doctrine/` directory

**Examples:**
- NO_FOREIGN_KEYS_DOCTRINE.md
- TIMESTAMP_DOCTRINE.md
- URL_ROUTING_DOCTRINE.md

**See:** [docs/doctrine/](../doctrine/)

---

## Database & Schema

### TOON

A structured data format used for database schema documentation and data exchange. TOON files are read-only and auto-generated.

**Location:** `database/toon_data/` directory

**See:** [TOON_DOCTRINE.md](../../doctrine/TOON_DOCTRINE.md)

---

### Soft Delete

A deletion strategy where records are marked as deleted (deleted_ymdhis != 0) but not physically removed from the database.

**Purpose:** Enables recovery, maintains referential integrity, supports ANIBUS orphan handling

**See:** [NO_FOREIGN_KEYS_DOCTRINE.md](../../doctrine/NO_FOREIGN_KEYS_DOCTRINE.md)

---

### YMDHIS

Timestamp format: YYYYMMDDHHMMSS stored as BIGINT(14) in UTC.

**Example:** `20260115120000` = January 15, 2026, 12:00:00 UTC

**Critical:** All Lupopedia timestamps use this format. DATETIME and TIMESTAMP types are forbidden.

**See:** [TIMESTAMP_DOCTRINE.md](../../doctrine/TIMESTAMP_DOCTRINE.md)

---

## Related Documentation

- [DEFINITION.md](../../overview/DEFINITION.md) - Formal definitions of Lupopedia
- [ARCHITECTURE.md](../../architecture/ARCHITECTURE.md) - System architecture overview
- [URL_ROUTING_DOCTRINE.md](../../doctrine/URL_ROUTING_DOCTRINE.md) - HTTP URL routing rules
- [AGENT_ROUTING_DOCTRINE.md](../../doctrine/AGENT_ROUTING_DOCTRINE.md) - Agent message routing rules
- [SUBDIRECTORY_INSTALLATION_DOCTRINE.md](../../doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md) - Path handling and installation
- [CSLH-URL-Semantics.md](../../doctrine/CSLH-URL-Semantics.md) - Crafty Syntax URL semantics
- [Lupopedia-Reference-Layer-Doctrine.md](../../doctrine/Lupopedia-Reference-Layer-Doctrine.md) - Reference layer principles
- [SAMPLE_REFERENCE_ENTRY.md](../examples/SAMPLE_REFERENCE_ENTRY.md) - Concrete examples

---

**This glossary is authoritative and binding for all Lupopedia documentation and development.**

**Last Updated:** January 15, 2026  
**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION
