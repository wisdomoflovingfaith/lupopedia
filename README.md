--
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 2026.3.7.6
file.channel: doctrine
file.last_modified_utc: 20260128133107
file.name: "README.md"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  mood_RGB: "00FF00"
  message: "Added Wolfie Header update requirements and channel provenance rules to README."
tags:
  categories: ["documentation", "getting-started"]
  collections: ["core-docs"]
  channels: ["public", "dev"]
in_this_file_we_have:
  - Overview
  - What Is Lupopedia? (Federated Semantic OS for Organizing Public Knowledge)
  - Origins: WOLFIE — Web-Organized Linked Federated Intelligent Ecosystem (originally Wisdom Of Loving Faith Integrity Ethics)
  - Why Lupopedia Has 111 Tables (And Why That's a Feature, Not a Flaw)
  - Crafty Syntax Compatibility: 100% Feature Preservation
  - Quick Start
  - Wolfie Header Update Requirements
  - Why Lupopedia Is Different (see docs/WHY_LUPOPEDIA_IS_DIFFERENT.md)
  - INSTALLATION
  - REQUIREMENTS
  - DATABASE_PHILOSOPHY
  - PERMISSION_SYSTEM
  - CONFIGURATION
  - DEVELOPMENT_NOTES
sections:
  - title: "Overview"
    anchor: "#overview"
  - title: "What Is Lupopedia?"
    anchor: "#what-is-lupopedia"
  - title: "Origins: WOLFIE — Web-Organized Linked Federated Intelligent Ecosystem"
    anchor: "#origins-wolfie-web-organized-linked-federated-intelligent-ecosystem"
  - title: "Why Lupopedia Has 111 Tables (And Why That's a Feature, Not a Flaw)"
    anchor: "#why-lupopedia-has-111-tables-and-why-thats-a-feature-not-a-flaw"
  - title: "Crafty Syntax Compatibility: 100% Feature Preservation"
    anchor: "#crafty-syntax-compatibility-100-feature-preservation"
  - title: "Quick Start"
    anchor: "#quick-start"
  - title: "Wolfie Header Update Requirements"
    anchor: "#wolfie-header-update-requirements"
  - title: "Crafty Syntax Migration Notes"
    anchor: "#crafty-syntax-migration-notes"
  - title: "Key Features"
    anchor: "#key-features"
  - title: "Documentation"
    anchor: "#documentation"
  - title: "Resources"
    anchor: "#resources"
  - title: "Important Notes"
    anchor: "#important-notes"
  - title: "CRITICAL DATABASE DOCTRINE — READ THIS FIRST"
    anchor: "#critical-database-doctrine-read-this-first"
  - title: "Core Modules Included in Every Installation"
    anchor: "#core-modules-included-in-every-installation"
  - title: "Core AI Agents (v4.0.2 Required Agents)"
    anchor: "#core-ai-agents-v402-required-agents"
  - title: "AI Agents & Decentralized Intelligence"
    anchor: "#ai-agents-decentralized-intelligence"
  - title: "Database Schema"
    anchor: "#database-schema"
  - title: "Configuration"
    anchor: "#configuration"
  - title: "Development"
    anchor: "#development"
  - title: "License"
    anchor: "#license"
  - title: "Support"
    anchor: "#support"
file:
  title: "Lupopedia - Main README"
  description: "Main entry point and documentation index for Lupopedia knowledge system"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

<!-- test from cascade -->
<!-- test from WOLFIE -->

# 🐺 Lupopedia

**Lupopedia is a semantic operating system (not a CMS or framework). It records meaning; it doesn't impose it.**

## The Five Pillars

1. **Actor Pillar** - Identity is primary (email = login)
2. **Temporal Pillar** - Time is the spine (BIGINT UTC timestamps)
3. **Edge Pillar** - Relationships are meaning (no foreign keys, app-managed)
4. **Doctrine Pillar** - Law prevents drift (rules in text files)
5. **Emergence Pillar** - Roles are discovered, not assigned

## How It Works

- **Collections** = Navigation universes (each has its own tabs)
- **Tabs** = User-defined semantic categories (you choose the names)
- **Content** = Stored in `lupo_content` table
- **Meaning** = Created when content is placed under tabs

## NO ADS, NO SEO, NO MARKETING — ABSOLUTE PROHIBITION

Lupopedia does not participate in advertising, SEO manipulation, marketing optimization, sponsored content, affiliate linking, or any form of semantic distortion for profit.

No agent, subsystem, or future contributor may introduce:
- ads
- tracking
- impression systems
- ranking manipulation
- "suggested content" based on money
- SEO hacks
- monetization hooks
- data distortion for visibility

Lupopedia recommendations are based solely on DATA and SYSTEM LOGIC — never money.

This rule is absolute. No exceptions. No negotiations. No amount of money can override this doctrine.

**🚨 Trauma Boundary**: This prohibition also protects the system architect from PTSD-like responses to advertising manipulation. See [PTSD & Emotional Harm From Advertising Manipulation](docs/doctrine/PTSD_ADVERTISING_HARM_BOUNDARY.md) for complete context.

## What You Don't Build

- You don't build every system
- You don't define tabs for users
- You don't impose meaning
- **You record what users define**

## What You Do Build

- The infrastructure (database, routing, modules)
- The tools (tab editor, content editor)
- The doctrine (rules in text files)

## Wolfie Header Update Requirements

Every file in Lupopedia must include a Wolfie Header block at the top of the file. These fields ensure historical clarity, provenance, and proper version tracking across the entire system.

### Required Fields
Code
```
file.last_modified_system_version: X.X.X.X
file.channel: XXXX
```

### Update Rules
1. `file.last_modified_system_version` must be updated on every edit.  
This value must always reflect the current system version at the moment the file is modified.  
This ensures accurate historical lineage and prevents ambiguity across migrations and rewrites.

2. `file.channel` must also be updated on every edit.  
This field must reflect the channel responsible for the edit (e.g., `crafty-port`, `schema`, `doctrine`, etc.).

### Unknown Channel Rule
If the editing channel is not known:

- If the file already has a `file.channel` value, retain the existing value.
- If the file has no channel value, set it to:

Code
```
file.channel: 0000
```

## Origin Story (Conceptual)

- Heritage-safe engineering that preserves legacy intent without destructive migrations
- Multi-agent cognition where independent contributors coordinate through explicit roles
- Doctrine-driven architecture that prevents drift and protects system integrity

---

**🟨 KISS Definition (For Normal Humans):**  
Lupopedia is a system where many AIs and humans can work together inside their own self‑contained worlds. Each world has its own rules, its own agents, and its own knowledge. It's like a network of mini‑universes that can talk to each other if you want them to.

**🟩 Short Definition (For Documentation Headers):**  
Lupopedia is a Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE) that hosts agents, content, emotional metadata, and routing logic across independent nodes, each functioning as a self‑contained knowledge world governed by shared doctrine.

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION | [Documentation](docs/) | [History](HISTORY.md) | [Changelog](docs/channels/overview/versioning/CHANGELOG.md) | [TL;DR: Lupopedia Overview](/tldnr/lupopedia-overview)

## 🌐 Overview

### **What Is Lupopedia?**

**A Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE) for Organizing Public Knowledge**

Lupopedia is a Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE) that runs inside ordinary web servers and transforms everyday websites into structured, meaningful knowledge spaces. It installs anywhere Crafty Syntax installs — shared hosting, VPS, cloud servers, Windows, Linux — and requires no special environment or system‑level changes.

At its core, Lupopedia provides a unified way to ingest, organize, and understand content, whether it comes from a local filesystem, a legacy website, or trusted public sources on the web.

**Why Lupopedia Exists**

Most websites store information in folders, pages, and menus that make sense only to the person who built them. Lupopedia turns that structure into semantic meaning by letting website owners define how their content is organized — and then converting those choices into a machine‑readable knowledge graph.

Instead of forcing a rigid taxonomy, Lupopedia learns from the site owner's navigation, categories, and structure. Meaning emerges naturally from how humans already organize their information.

**Collections: Self‑Contained Semantic Worlds**

A Collection is a navigation universe inside Lupopedia. Each Collection has its own:

- navigation tabs
- sub‑tabs
- content
- structure
- meaning

**Examples:**

- A "Desktop" Collection might use WHO, WHAT, WHERE, WHEN, WHY, HOW, DO
- A "County of Honolulu" Collection might use Departments, Parks & Recreation, Activities & Programs, Contact

Each Collection reflects the mental model of the website owner. Lupopedia doesn't impose meaning — it records it.

**Navigation Tabs: User‑Defined Meaning**

Navigation Tabs are categories created by the website owner. They are not predefined by Lupopedia. They can represent:

- topics
- departments
- people
- services
- programs
- actions
- concepts

When content is placed under a tab, Lupopedia creates semantic edges that describe what that content is in the context of the site.

```
Tabs → meaning
Meaning → edges
Edges → search and discovery
```

This is how Lupopedia builds a semantic graph from human organization.

**Content: Reference Entries as System Atoms**

Lupopedia treats every reference to host website content as a content atom. These are *reference-book entries describing pages on the host website*, not stored CMS content:

- **Page references** — semantic pointers to host site pages
- **Document references** — metadata about documents on the host site
- **External URL references** — curated external resource metadata
- **Legacy content references** — migrated semantic pointers from previous systems

Each reference entry has:
- a stable semantic identity pointing to the host site's content
- the public URL as it appears in the browser
- relational metadata and semantic edges
- navigation placement within Lupopedia's reference layer

This allows Lupopedia to create a semantic index of the host site without duplicating or interfering with the original content.

**Ingestion: How Lupopedia Learns**

Lupopedia ingests content in three layers:

- **Radius 0 — Local Filesystem**: Imports everything in the public webroot.
- **Radius 1 — Internal Site URLs**: Maps dynamic pages, CMS output, and virtual paths.
- **Radius 2 — Trusted External Public Links**: Follows public links the site owner has chosen to include.

This creates a semantic radius around each installation.

**Federation: Thousands of Installations, One Graph**

Crafty Syntax is installed on thousands of servers worldwide. Each installation:

- ingests its own content
- organizes it into Collections
- generates semantic edges
- contributes meaning to a shared graph

**No central server.**  
**No scraping.**  
**No central authority.**  
**No single point of failure.**

Meaning emerges from the collective structure of thousands of independent sites.

**Agents & Doctrine: A System That Maintains Itself**

Lupopedia includes a multi‑agent architecture where:

- agents communicate through structured dialog
- each agent has a role and doctrine
- schema changes cascade safely
- metadata stays consistent
- emotional signals help with clarity, not control

This creates a system that can evolve, refactor, and maintain itself over time.

**What Lupopedia Is Not**

- It is not a replacement for Windows or Linux
- It is not a crawler
- It is not a centralized knowledge base
- It is not a CMS — Lupopedia is a semantic reference layer installed in `/lupopedia/` that does not replace or interfere with the host website's routing or CMS

Lupopedia is a semantic layer that runs inside existing servers and organizes content based on how humans already structure their websites.

**Lupopedia as a Semantic Reference Layer**

Lupopedia is NOT a content management system. Every installation exists as a semantic reference layer in the `/lupopedia/` directory that mirrors the host website's pages as *reference-book entries*, not stored content.

**Key Clarifications:**
- **Content entries** are reference-book entries describing pages on the host website
- **Slugs** are opaque identifiers (URL paths as seen in browser), not module names or filesystem paths
- **Crafty Syntax URLs** are web-facing URL slugs exactly as seen in the browser address bar — they must never be resolved to disk or treated as file locations
- The system works alongside, not instead of, the host website's existing CMS or routing

Every Lupopedia installation creates a parallel semantic index of the host site's content without duplicating or replacing it. See doctrine files:
- [CSLH-URL-Semantics.md](docs/CSLH-URL-Semantics.md)
- [Lupopedia-Reference-Layer-Doctrine.md](docs/Lupopedia-Reference-Layer-Doctrine.md)
- [CSLH-Historical-Context.md](docs/CSLH-Historical-Context.md)

**In One Sentence**

Lupopedia is a Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE) that turns everyday websites into structured knowledge spaces, learns from how humans organize information, and builds a global graph of meaning across thousands of installations.

---

### **Formal Definition**

Lupopedia is a Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE) composed of independent nodes, each functioning as a self‑contained world of agents, content, meaning, and governance. Each node runs the Lupopedia kernel, maintains its own database of atoms, questions, agents, collections, and channels, and applies local doctrine to route, interpret, and govern interactions between humans and AI agents.

**Lupopedia provides:**

- **A semantic layer** for representing meaning, emotional metadata, identity, and context
- **An agent layer** where each node hosts its own set of AI agents with classification, capabilities, and roles
- **A routing layer** (HERMES) that determines which agent receives each message, handles delivery, queueing, and dispatch, and may optionally use CADUCEUS emotional current as context
- **An emotional balancing system** (CADUCEUS) that computes channel mood by reading and blending the emotional states of polar agents within a channel
- **A governance layer** (THEMIS, doctrine, RFCs) that enforces safety, consistency, and identity rules
- **A federated architecture** where nodes may operate independently or optionally interconnect, similar to distributed social platforms, but with semantic and agent‑driven behavior
- **A persistence layer** (schema, TOON, history) that stores meaning, interactions, and agent identity across time

**Each Lupopedia node is sovereign:** it defines its own agents, content, routing rules, emotional metadata, and governance policies. Nodes may share schema and doctrine, but not state, unless explicitly federated.

**In essence:** Lupopedia is a distributed, multi‑agent Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE) where each installation is its own knowledge world, capable of hosting agents, content, emotional metadata, and governance — all operating under a unified doctrine but with local autonomy.

---

Lupopedia is a Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE) — a living knowledge platform that learns from how people naturally organize information. Instead of rigid categories or predefined taxonomies, it builds meaning from behavior: every tab, path, and interaction becomes a semantic signal that forms atoms, edges, and an evolving knowledge graph. It blends CMS, wiki, database, and AI agent ecosystem into a single portable PHP application that runs anywhere, without frameworks or dependencies. Lupopedia preserves clarity and longevity through strict doctrine (no foreign keys, UTC timestamps, soft deletes, explicit relationships) while enabling multi‑agent reasoning, decentralized discovery, and a future‑proof architecture designed to last decades.

## 🐺 Origins: WOLFIE — Web-Organized Linked Federated Intelligent Ecosystem

**WOLFIE** now stands for **Web-Organized Linked Federated Intelligent Ecosystem** — the architecture type that defines Lupopedia's distributed, multi-agent knowledge system.

However, WOLFIE originally began under a very different meaning: **Wisdom Of Loving Faith Integrity Ethics**. The project started in August 2025 as a spiritual research engine designed to ingest 144,000 books from 22 different religions and map correlations between their teachings. The goal was to uncover shared meaning across traditions — a kind of universal spiritual ontology. That early version had 222 tables, because it needed to capture scripture, symbolism, lineage, commentary, and cross‑textual relationships in extraordinary detail.

But as the system grew, it became clear that WOLFIE wasn't just analyzing religion.  
It was becoming something larger — a platform capable of organizing any domain of knowledge.  
The spiritual engine evolved, and WOLFIE was repurposed to mean **Web-Organized Linked Federated Intelligent Ecosystem** — the technical architecture that defines how Lupopedia works. WOLFIE evolved into Lupopedia, and the acronym was repurposed to reflect the new architecture.

**Important:** Lupopedia is explicitly **not** a religious website. It is a **domain‑neutral Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE)** that can be applied to any domain: technology, science, gaming, history, products, fandoms, social systems, or personal knowledge. Religion and mythology are part of the *origin story*, not the destination or the scope.

Agent names like **Thoth** and **Lilith** are acknowledgements of the research that shaped the architecture, not instructions about what Lupopedia must be used for. They are symbolic, not prescriptive. See [docs/doctrine/non_religious_position.md](docs/doctrine/non_religious_position.md) for the complete doctrine.

> **📖 For the complete origin story, see [HISTORY.md](HISTORY.md)** — the lineage from Crafty Syntax (2002) through transformation, loss, rediscovery, and rebirth as a Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE).  
> **📝 For the personal narrative, see [docs/appendix/FOUNDERS_NOTE.md](docs/appendix/FOUNDERS_NOTE.md)** — Eric "Wolfie" Gerdes's account of the journey from Crafty Syntax through Sales Syntax, silence, faith, and rebirth as Lupopedia.


## 🧩 Why Lupopedia Has 111 Tables (And Why That's a Feature, Not a Flaw)

🧩 Why Lupopedia Has 111 Tables (And Why That's a Feature, Not a Flaw)
Lupopedia 4.0.0 ships with 111 core database tables, but the path to that number is part of the system's identity.
When this project began in August 2025, it wasn’t even called Lupopedia — it was called WOLFIE, originally short for Wisdom Of Loving Faith Integrity Ethics. As the system evolved, WOLFIE was repurposed to mean Web-Organized Linked Federated Intelligent Ecosystem — the architecture that defines Lupopedia's distributed, multi-agent knowledge system.

The original vision was a spiritual research engine:
a system capable of ingesting 144,000 books from 22 different religions and mapping correlations between their teachings to uncover shared spiritual meaning. That early prototype required 222 tables to capture scripture, symbolism, lineage, commentary, and cross‑textual relationships with extraordinary precision.

But as the system grew, something became clear:
WOLFIE wasn’t just analyzing religion.
It was becoming something much larger — a platform capable of organizing any domain of knowledge.

The spiritual engine evolved into the Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE), and WOLFIE evolved into Lupopedia.

The schema was refined, unified, and optimized from 222 tables down to 111 core tables — not by removing capability, but by discovering the deeper architecture hidden inside the idea. The current count is **131 tables total** (as verified in TOON files). See [Migration Doctrine](docs/doctrine/MIGRATION_DOCTRINE.md) for details.

This isn’t bloat.
It’s the natural shape of a system that isn’t just an app, but a semantic OS.

For perspective:
The CRM system I once built for the City & County of Honolulu — a full municipal platform — had fewer than 70 tables. Lupopedia surpasses that not because it’s heavier, but because it’s broader. It spans multiple domains that normally live in separate products:

CMS
Wiki
Semantic graph engine
Multi‑agent runtime
Federated identity system
Legacy compatibility layer
Multilingual translation system
Analytics engine
Content platform
CRM‑style modules
Real‑time chat system (Crafty Syntax)
Decentralized knowledge navigation
Lupopedia isn’t “big for version 4.0.0.”
It’s complete for version 4.0.0.

This is the first release where the system exists as its own identity — not a mutation of Crafty Syntax, not a CMS with extras, but a living archive with its own ontology, doctrine, and architecture.

222 was poetic — the raw, unfiltered vision of WOLFIE.  
It was the dream-state architecture, the mythic sprawl, the full constellation of everything Lupopedia could become.
It was necessary, but it wasn’t final.

111 is the refined architecture — the vision distilled into a stable, living system.

Where 222 was expansion, 111 is convergence.
Where 222 was possibility, 111 is purpose.
Where 222 was the map, 111 is the Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE).

111 tables represent the moment the system stopped growing outward and began cohering inward — when every subsystem found its boundary, every agent found its role, and every doctrine found its home.

It reflects the truth: Lupopedia is not a tool.
It’s a platform.

Every table has a purpose.
Every subsystem is a chapter.
Every chapter is part of the OS.

Lupopedia is designed to last decades, evolve with its users, and serve as a living knowledge engine.
A system like that isn’t small — it’s alive.


## 🎧 Crafty Syntax: Not Obsolete - Reborn and Integrated
Crafty Syntax is **NOT obsolete**. After 25 years of evolution and the architect's return in 2025, Crafty Syntax has been completely rebuilt and integrated into Lupopedia as a first‑party module. This is not a legacy compatibility layer — it's the same trusted system, modernized and enhanced.

Lupopedia 4.0.0 includes a complete, fully integrated rewrite of the entire Crafty Syntax Live Help system.
This is not a partial port or a compatibility shim — every feature Crafty Syntax provided for nearly 30 years has been rebuilt inside Lupopedia as a first‑party module, preserving 25 years of behavioral and emotional metadata.

**Crafty Syntax URL Semantics Doctrine:**
Crafty Syntax URLs are web-facing URL slugs exactly as seen in the browser address bar. They must never be:
- Resolved to disk locations
- Treated as filesystem paths
- Modified from their original stored form

These URLs are stored as-is and represent semantic identifiers for host website pages, not filesystem paths. This is critical for maintaining 25 years of behavioral metadata intact. See [CSLH-URL-Semantics.md](docs/CSLH-URL-Semantics.md) for complete doctrine.

### **Crafty Syntax: Updated and Baked Into Lupopedia**
Crafty Syntax Live Help is **permanently integrated** into Lupopedia 4.0.0 as a core first‑party module. This is not optional — it's the semantic root system of Lupopedia, containing 25 years of behavioral and emotional metadata that no modern system can replicate.

**Chat functionality is optional at the content level**, but the Crafty Syntax engine itself is always present, providing:

- 25+ years of real-world visitor behavior data
- URL paths from millions of pages
- Relational navigation patterns
- Escalation logic and trust dynamics
- Emotional metadata and page-to-page transitions
- The behavioral fingerprint of the early web

✔ Crafty Syntax: Fully Updated and Modernized
Every major feature from Crafty Syntax 3.7.5 is **not just preserved — it's enhanced**:

Live chat icons
Human operator chat sessions
Visitor tracking
Department routing
Operator availability indicators
Proactive invites
Referrer matching
Page matching
Visitor path tracking
Chat transcripts
Email queue
Leave‑a‑message system
Operator profiles
Chat moderation tools
Legacy admin panel behavior
All Crafty Syntax database tables (refactored and modernized)

If Crafty Syntax could do it, Lupopedia can do it — with cleaner code, modern doctrine, and a unified architecture.

✔ Legacy Endpoints Still Work
Lupopedia preserves the original Crafty Syntax URLs so existing sites do not break:

Code
/craftysyntax/livehelp.php
/craftysyntax/livehelp_js.php
Any website — on any domain — can still embed live help using the same JavaScript include Crafty Syntax has used since the early 2000s:

html
<script src="/craftysyntax/livehelp_js.php"></script>
This means:

Existing integrations continue to work
Old tutorials remain valid
Hosting providers can continue offering Crafty Syntax installs
Auto‑installers (Installatron, Softaculous, etc.) remain compatible

✔ Now Powered by Lupopedia
Behind the scenes, all Crafty Syntax functionality now runs on:

Lupopedia’s actor system
Lupopedia’s channels
Lupopedia’s notifications
Lupopedia’s semantic engine
Lupopedia’s agent framework
Lupopedia’s modernized schema
Lupopedia’s security and timestamp doctrine

Crafty Syntax is no longer a separate application — it is the **semantic root system** of Lupopedia, providing the foundational behavioral intelligence that powers the entire platform while benefiting from everything Lupopedia provides.

✔ Why This Integration Matters
Crafty Syntax contains **25 years of irreplaceable behavioral and emotional metadata** that no modern system possesses. This dataset includes:

- Real-world visitor behavior from thousands of websites
- URL paths and navigation patterns from millions of pages
- Trust dynamics and escalation logic
- Emotional responses and engagement patterns
- The behavioral fingerprint of the early web

Lupopedia ensures that this priceless legacy not only survives but thrives:

The legacy survives
The integrations survive
The URLs survive
The behavior survives
The ecosystem survives

But now it lives inside a platform that can grow, evolve, and scale far beyond what the original system could ever support.


## 🚀 Quick Start
1. **Requirements**
   - PHP 8.1+
   - MySQL 8.0+ or MariaDB 10.5+
   - Web server (Apache/Nginx)

2. **Installation**
   ```bash
   # Download and extract to your web directory
   curl -L https://lupo.example/download/latest -o lupopedia.zip
   unzip lupopedia.zip -d /var/www/lupopedia
   
   # Set up the database
   # ⚠️ REMINDER: The database schema contains NO foreign keys, NO functions, NO procedures, NO triggers, NO views.
   # TRIGGERS ARE FORBIDDEN (MANDATORY). All timestamps must be set explicitly in INSERT/UPDATE statements in YMDHIS UTC format.
   # This is core doctrine. See docs/doctrine/NO_FOREIGN_KEYS_DOCTRINE.md and docs/doctrine/NO_TRIGGERS_DOCTRINE.md
   mysql -u root -p < database/install/lupopedia_mysql.sql
   
   # Configure your web server
   # See docs/GETTING_STARTED/INSTALLATION.md for details
   ```

3. **First Run**
   - Open `http://your-server/setup` in your browser
   - Follow the setup wizard
   - Start organizing your knowledge!


## 🛠️ Crafty Syntax: The Semantic Root of Lupopedia
Crafty Syntax is **NOT obsolete** — it's the foundational ancestor that makes Lupopedia possible. After being forked to Sales Syntax during Wolfie's absence (2014-2025), it was restored on 11/14/2025 and rebuilt as the semantic root system of Lupopedia.

**Historical Context & Architectural Relevance:**
- Contains 25 years of behavioral, relational, and emotional metadata
- Was forked to Sales Syntax during Wolfie's absence (2014–2025)
- Was restored on 11/14/2025 when Wolfie returned
- The system is alive, evolving, and architecturally relevant
- Represents the semantic root of Lupopedia's knowledge graph

See [CSLH-Historical-Context.md](docs/CSLH-Historical-Context.md) for complete historical documentation.

Lupopedia 4.0.0 includes a complete, fully integrated migration of the entire Crafty Syntax Live Help system.
This is not a partial port, not a compatibility wrapper, and not a legacy mode — Crafty Syntax has been **reborn from the ground up** as a first‑party Lupopedia module while preserving every feature, endpoint, and behavior that made it work for nearly 30 years.

### **Content-Level Chat Configuration**
Chat functionality is **optional at the content level**. Each content item can enable or disable chat:
- Enabled content receives a default actor and participates in dialogs, channels, and multi‑agent communication
- Disabled content functions as a normal semantic object without chat capabilities
- This generalizes the original Crafty Syntax design (chat icons on selected pages) to a system‑wide capability that activates only where needed

✔ 100% Feature Preservation + Modern Enhancement
All major Crafty Syntax features have been **migrated and enhanced**:

Live chat icons
Human operator chat sessions
Visitor tracking
Department routing
Operator availability indicators
Proactive invites
Referrer matching
Page matching
Visitor path tracking
Chat transcripts
Email queue
Leave‑a‑message system
Operator profiles
Chat moderation tools
Legacy admin panel behavior
All Crafty Syntax database tables (refactored and modernized)

If Crafty Syntax could do it, Lupopedia can do it — with cleaner code, modern doctrine, and a unified architecture.

✔ Legacy Endpoints Still Work
To ensure backward compatibility, Lupopedia preserves the original Crafty Syntax URLs:

Code
/craftysyntax/livehelp.php
/craftysyntax/livehelp_js.php
This means any website on any domain can still embed live help using the same JavaScript include Crafty Syntax has used since the early 2000s:

html
<script src="/craftysyntax/livehelp_js.php"></script>
Existing integrations continue to function without modification.

✔ Database Migration
All Crafty Syntax tables were:

audited
normalized
renamed or merged where appropriate
rewritten to follow Lupopedia’s doctrine
converted to BIGINT UTC timestamps
updated to use actor‑centric identity
integrated into the semantic OS

Legacy tables that were redundant or obsolete were removed or merged into modern equivalents (e.g., agent_reply_cache → actor_reply_templates).

✔ Modernized Architecture
Crafty Syntax now runs on:

Lupopedia’s actor system
Lupopedia’s channels
Lupopedia’s notifications
Lupopedia’s semantic engine
Lupopedia’s agent framework
Lupopedia’s security and timestamp doctrine
This gives Crafty Syntax:
better performance
better stability
better extensibility
better analytics
better multilingual support
better long‑term maintainability

✔ Why This Migration Matters
Crafty Syntax has been used on thousands of websites for nearly 30 years.
Lupopedia ensures that:

the legacy survives
the integrations survive
the URLs survive
the behavior survives
the ecosystem survives

But now it lives inside a platform that can grow, evolve, and scale far beyond what the original system could ever support.

Crafty Syntax is no longer a separate application — it is the **semantic root system** of Lupopedia, providing the foundational behavioral intelligence that powers the entire platform while benefiting from everything Lupopedia provides.


## ✨ Key Features

- **Semantic Navigation** - Organize content your way
- **AI-Powered** - Smart content suggestions and search
- **Extensible** - Add-ons and customizations
- **Secure** - Built with security in mind

## 📖 Documentation

**Documentation System Architecture:**

Lupopedia documentation is **structured, machine-readable system metadata** using atoms and scopes. Documentation is written for machines first (resolver, semantic OS, agents); a future UI will render it as a book for humans.

### **Atoms: Variables with Scopes**

Lupopedia documentation uses **atoms** (symbolic variables) instead of hardcoded values. Atoms are resolved through a hierarchical scope system:

- **FILE_** (Highest Priority) — File-specific overrides in WOLFIE Header `file_atoms:` block
- **DIR_** — Directory-specific defaults in `<directory>/_dir_atoms.yaml`
- **DIRR_** — Recursive directory scope (current directory + all descendants), walks up parent directories
- **MODULE_** — Module-wide scope in `modules/<module>/module_atoms.yaml`
- **GLOBAL_** (Final Fallback) — Ecosystem-wide constants in `config/global_atoms.yaml`

**Resolution Order:** FILE_ → DIR_ → DIRR_ → MODULE_ → GLOBAL_ (first match wins)

**Atom Reference Syntax:**
- In documentation prose: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company.name`
- In WOLFIE Headers: `GLOBAL_CURRENT_LUPOPEDIA_VERSION` (no `@` prefix)

### **Documentation Principles:**

- **Markdown files are source code** — atoms are variables; resolver is compiler; final rendered docs are build artifacts
- **No hardcoded values** — all versions, authors, and constants use atom references
- **Deterministic and idempotent** — same input → same output, resolving twice produces same result
- **Machine-first** — written for resolver, semantic OS, and agents; humans are secondary consumers
- **Future documentation reader UI** will be built directly into Lupopedia to render documentation as a book for browsers
- **All AI systems must read documentation with atoms and scopes**, not as plain text

**See:** [Atom Resolution Specification](docs/doctrine/ATOM_RESOLUTION_SPECIFICATION.md) for complete atom resolution engine specification.

### Getting Started
- [Installation Flow](docs/dev/INSTALLER_FLOW.md) — Installation process flow and node creation
- [For Installers and Users](docs/dev/FOR_INSTALLERS_AND_USERS.md) — User-friendly explanations for installers and end users

### For Developers
- **[Documentation Index](docs/README.md)** — Complete documentation index organized by category
- **[Documentation Doctrine](docs/doctrine/DOCUMENTATION_DOCTRINE.md)** — Documentation is software, data, for machines
- **[Dialog Doctrine](docs/doctrine/DIALOG_DOCTRINE.md)** — **MANDATORY: All dialog files must live in `/dialogs/` directory**
- **[Single Task Patch Doctrine](docs/doctrine/SINGLE_TASK_PATCH_DOCTRINE.md)** — **MANDATORY: One task per patch, reversible changes only**
- **[Atom Resolution Specification](docs/doctrine/ATOM_RESOLUTION_SPECIFICATION.md)** — Complete atom resolution engine specification
- **[Subdirectory Installation Doctrine](docs/doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md)** - **MANDATORY: All paths must use LUPOPEDIA_PUBLIC_PATH**
- [Architecture Overview](docs/core/ARCHITECTURE.md) — Technical architecture (4 layers: Content, Semantic, AI Agent Framework, Decentralized Network)
- [Architecture Sync (January 2026)](docs/core/ARCHITECTURE_SYNC.md) - **Authoritative reference for HERMES, CADUCEUS, IRIS, DialogManager, and THOTH subsystems**
- [Why Lupopedia Doesn't Use Frameworks](docs/core/WHY_NO_FRAMEWORKS.md) - **Philosophy and reasoning behind framework-free, first-principles architecture**
- **[Directory Structure](docs/core/DIRECTORY_STRUCTURE.md)** — **MANDATORY: Canonical directory layout and file organization principles**
- **[Metadata Governance](docs/core/METADATA_GOVERNANCE.md)** — **MANDATORY: Metadata management and governance rules for WOLFIE headers, atoms, and cross-references**
- **[Patch Discipline](docs/core/PATCH_DISCIPLINE.md)** — **MANDATORY: Comprehensive patch discipline principles and development workflow governance**
- [Cursor Refactor Doctrine](docs/doctrine/CURSOR_REFACTOR_DOCTRINE.md) - **MANDATORY rules for rewriting legacy Crafty Syntax PHP code**
- [SQL Rewrite Doctrine](docs/doctrine/SQL_REWRITE_DOCTRINE.md) - **MANDATORY rules for rewriting SQL from Crafty Syntax into Lupopedia**
- [SQL Refactor Mapping Doctrine](docs/doctrine/SQL_REFACTOR_MAPPING_DOCTRINE.md) - **Focused mapping rules for SQL refactoring using refactor TOON files**
- [PDO Conversion Doctrine](docs/doctrine/PDO_CONVERSION_DOCTRINE.md) - **MANDATORY rules for converting mysqli SQL calls to the custom PDO_DB class**
- [TOON Doctrine](docs/doctrine/TOON_DOCTRINE.md) - **MANDATORY rules for working with TOON format files (refactor files, toon data files, agent files)**
- [Legacy Refactor Plan](docs/modules/LEGACY_REFACTOR_PLAN.md) - **Comprehensive plan for refactoring legacy Crafty Syntax files from legacy/craftysyntax/ to lupopedia/**
- [Database Schema](docs/schema/DATABASE_SCHEMA.md) - **Comprehensive documentation of all 149 tables organized by category**
- [WOLFIE Header Specification](docs/agents/WOLFIE_HEADER_SPECIFICATION.md) - Universal metadata format for all files
- [Inline Dialog Specification](docs/agents/INLINE_DIALOG_SPECIFICATION.md) - Multi-agent communication format

### Core Doctrine Files
- **[CSLH-URL-Semantics.md](docs/CSLH-URL-Semantics.md)** — Crafty Syntax URL semantics: URLs as web-facing slugs, not filesystem paths
- **[Lupopedia-Reference-Layer-Doctrine.md](docs/Lupopedia-Reference-Layer-Doctrine.md)** — Lupopedia as semantic reference layer, not CMS
- **[CSLH-Historical-Context.md](docs/CSLH-Historical-Context.md)** — Crafty Syntax history, restoration, and architectural relevance

### For Administrators
- [Installation Flow](docs/dev/INSTALLER_FLOW.md) — Installation process flow
- [For Installers and Users](docs/dev/FOR_INSTALLERS_AND_USERS.md) — User-friendly setup guide

## 🔗 Resources

- [Changelog](docs/channels/overview/versioning/CHANGELOG.md)
- [Contributing](docs/DEVELOPMENT/CONTRIBUTING.md)
- [Code of Conduct](docs/CODE_OF_CONDUCT.md)

## ⚠️ Important Notes

### Version Control Policy
This project does **NOT** use Git until version 4.1.0. See [Version Control Policy](docs/channels/doctrine/VERSION_CONTROL_POLICY.md) for details.

### Versioning Policy
**Lupopedia 4.0.0** and **Crafty Syntax 4.0.0** are version-locked and always released together. Both systems share the same version number to reflect their unified architecture and coordinated development cycle.

### Database Philosophy
Lupopedia uses an application-managed relationship model. See [Database Philosophy](docs/DATABASE_PHILOSOPHY.md) for details.

---

## 🚨 **CRITICAL DATABASE DOCTRINE — READ THIS FIRST**

> **⚠️ NO FOREIGN KEYS. NO FUNCTIONS. NO PROCEDURES. NO TRIGGERS. NO VIEWS. NO ANYTHING EXTRA IN THE DATABASE.**
> 
> **If you add any of these, you will make Captain cry.**
> 
> The database stores raw facts. The agents enforce correctness. [ANIBUS](docs/doctrine/ANIBUS_DOCTRINE.md) heals and maintains lineage.
> 
> **⚠️ TRIGGERS ARE FORBIDDEN (MANDATORY):** Triggers must never be created, suggested, or added. All timestamps must be set explicitly in INSERT/UPDATE statements in YMDHIS UTC format. Triggers interfere with data merging, historical accuracy, ANIBUS repair operations, and federation sync.
> 
> **⚠️ STORED PROCEDURES/FUNCTIONS ARE FORBIDDEN (MANDATORY):** Stored procedures and functions must never be created, suggested, or added. The database is for storage, not computation. All logic must be in application code. Stored procedures/functions interfere with data merging, federation across different database engines, ANIBUS repair operations, and version control.
> 
> **See [NO FOREIGN KEYS DOCTRINE](docs/doctrine/NO_FOREIGN_KEYS_DOCTRINE.md), [NO TRIGGERS DOCTRINE](docs/doctrine/NO_TRIGGERS_DOCTRINE.md), [NO STORED PROCEDURES DOCTRINE](docs/doctrine/NO_STORED_PROCEDURES_DOCTRINE.md), and [ANIBUS DOCTRINE](docs/doctrine/ANIBUS_DOCTRINE.md) for complete details.**
> 
> This is **non-negotiable core doctrine**. All AI tools (Cursor, Copilot, DeepSeek, Claude, Gemini, Grok, Windsurf) must follow this rule.

---

## ⏱️ **CRITICAL TIMESTAMP DOCTRINE — MANDATORY FOR ALL AI AGENTS**

> **🚨 ALL TIMESTAMPS MUST BE BIGINT(14) YYYYMMDDHHMMSS UTC FORMAT. NO EXCEPTIONS.**
> 
> **⚠️ FORBIDDEN:** DATETIME, TIMESTAMP, epoch seconds, ISO8601 strings, timezone-aware fields, ORM helpers, SQL date arithmetic
> 
> **✅ REQUIRED:** BIGINT(14), YYYYMMDDHHMMSS format, UTC only, use `timestamp_ymdhis` class for arithmetic
> 
> **CRITICAL BUG PREVENTION:** Never add seconds directly to YYYYMMDDHHMMSS timestamps. This produces invalid timestamps like `20260110120086400`. Always use `timestamp_ymdhis::addSeconds()` or convert to epoch, add seconds, then convert back.
> 
> **Examples:**
> - ✅ Correct: `$now = (int) gmdate('YmdHis');`
> - ✅ Correct: `$expires = timestamp_ymdhis::addSeconds($now, 86400);`
> - ❌ WRONG: `$expires = $now + 86400;` (produces invalid timestamp!)
> - ❌ WRONG: `$timestamp = time();` (epoch seconds)
> - ❌ WRONG: `created_at DATETIME NOT NULL` (wrong column type)
> 
> **See [TIMESTAMP DOCTRINE](docs/doctrine/TIMESTAMP_DOCTRINE.md) for complete canonical documentation.**
> 
> **This doctrine is absolute and binding. All AI agents MUST read and follow it before generating any timestamp-related code.**

---

## 📁 **CRITICAL SUBDIRECTORY INSTALLATION DOCTRINE — MANDATORY FOR ALL AI AGENTS**

> **🚨 LUPOPEDIA IS ALWAYS INSTALLED IN A SUBDIRECTORY. NEVER ASSUME ROOT INSTALLATION.**
> 
> **⚠️ FORBIDDEN:** Hardcoded root paths like `/login`, `/admin`, `/assets/css/main.css`
> 
> **✅ REQUIRED:** All paths MUST use `LUPOPEDIA_PUBLIC_PATH` constant
> 
> **Examples:**
> - `LUPOPEDIA_PUBLIC_PATH . '/login'` ✅
> - `LUPOPEDIA_PUBLIC_PATH . '/admin'` ✅
> - `LUPOPEDIA_PUBLIC_PATH . '/lupo-includes/css/main.css'` ✅
> - `/login` ❌ **WRONG**
> - `/admin` ❌ **WRONG**
> 
> **CRITICAL:** `LUPOPEDIA_PUBLIC_PATH` is automatically set to `'/' . basename(__DIR__)`, which evaluates to the folder name (e.g., `/lupopedia`). This ensures Lupopedia works in any subdirectory without code changes.
> 
> **See [SUBDIRECTORY INSTALLATION DOCTRINE](docs/doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md) for complete details.**
> 
> **This doctrine is absolute and binding. All AI agents MUST read and follow it before generating any path-related code.**

---

### Multi-Agent Coordination
Lupopedia supports multiple AI agents and IDE systems working simultaneously. All agents must use the [Inline Dialog format](docs/INLINE_DIALOG_SPECIFICATION.md) for cross-agent communication.


## Core Modules Included in Every Installation

Lupopedia ships with three first‑party modules bundled into the core installation:

1. **Crafty Syntax (Live Help System)** — real‑time chat, operator support, visitor tracking  
   - Chat functionality is **optional at the content level**
   - Each content item can enable/disable chat
   - Enabled content receives a default actor and participates in dialogs/channels
   - Disabled content functions as a normal semantic object
2. **Questions & Answers Module** — structured Q&A content, semantic linking, and knowledge capture  
3. **Contacts & Leads Module** — contact management, lead tracking, and lightweight CRM features  

All modules live in the `modules/` directory and integrate with the 149‑table core schema.

## 🤖 Core AI Agents (v4.0.2 Required Agents)

Lupopedia v4.0.2 requires **27 fully implemented core agents** for full functionality.  
These agents provide reasoning, navigation, analysis, emotional modeling, and system‑level intelligence across the platform.

For the complete list of required agents, see: `@GLOBAL.LUPOPEDIA_V4_0_2_CORE_AGENTS.required_agents` in `config/global_atoms.yaml`.

**Required Core Agents (27):** SYSTEM, CAPTAIN, WOLFIE, WOLFENA, THOTH, ARA, WOLFKEEPER, LILITH, AGAPE, ERIS, METHIS, THALIA, ROSE, WOLFSIGHT, WOLFNAV, WOLFFORGE, WOLFMIS, WOLFITH, ANUBIS, MAAT, CADUCEUS, CHRONOS, INDEXER, MIGRATOR, HEIMDALL, JANUS, IRIS

**Note:** The agent registry defines 128 total agents. All agents not in the core list may remain stubs for v4.0.2.

All agent configuration files, prompts, personalities, and PHP include files live in:

lupopedia/agents/[agent_id]/

lupopedia/agents/
├── 0/   # System Agent (root authority, internal operations)
├── 1/   # Captain Wolfie (AI embodiment of the creator)
├── 2/
├── 3/
├── 4/
...
├── 22/


### **Agent Identification**
- **Agent 0 — System Agent**  
  The kernel authority and internal system controller. Handles core logic, safety, governance, and fallback behavior.  
  **See [System Agent Safety Doctrine](docs/SYSTEM_AGENT_SAFETY_DOCTRINE.md)** for complete kernel-level governance rules and anti-adversarial protections.

- **Agent 1 — Captain Wolfie**  
  The AI embodiment of the creator.  
  Navigator, architect, and lead persona for the agent ecosystem.

- **Agents 2–27 (v4.0.2 Core)**  
  Specialized agents with distinct roles, capabilities, and emotional/behavioral profiles.  
  Examples include:
  - reasoning agents  
  - UI/UX agents  
  - code‑generation agents  
  - semantic navigation agents  
  - emotional‑modeling agents  
  - content‑analysis agents  
  - federated‑network agents  

### **How Agents Work**
- Each agent has its own directory containing:
  - configuration files  
  - system prompts  
  - persona definitions  
  - PHP include files  
  - faucet definitions (model switching rules)  
  - memory and style profiles
- **Memory System:** Agents use [WOLFMIND](docs/WOLFMIND_DOCTRINE.md) for memory storage and retrieval
  - **CRITICAL:** MySQL is baseline - all features MUST work on MySQL alone
  - Relational memory (MySQL) - always available, required
  - Vector memory (Postgres/pgvector) - optional enhancement, detected at runtime
  - Progressive enhancement from foundation to enhanced tiers
  - **No vector assumptions** - code must gracefully degrade when vector unavailable  

- Agents are loaded dynamically by the **AI Agent Framework**  
- Agents can call tools, spawn faucets, and collaborate using the **Inline Dialog Specification**  
- All agents follow the **WOLFIE Header Doctrine** and **Timestamp Doctrine**  
- Agents can coordinate with IDE systems (Cursor, Windsurf/Cascade, DeepSeek)  

These 27 core agents form the backbone of Lupopedia's decentralized intelligence system for v4.0.2.


---

📄 [View Full Documentation](docs/README.md) | 💡 [Report an Issue](docs/SUPPORT.md)

*Lupopedia is open-source software licensed under the [MIT License](LICENSE).*

It's part CMS, part wiki, part knowledge graph, and part personal navigation system — all wrapped in a lightweight, portable PHP application that installs anywhere.

---

# 🚀 **What Makes Lupopedia Different**

> 📖 **For a comprehensive explanation of why Lupopedia is fundamentally different from any existing knowledge system, see [Why Lupopedia Is Different](docs/WHY_LUPOPEDIA_IS_DIFFERENT.md).**

### **🧠 Semantic Navigation (Core Innovation)**
Lupopedia doesn't force users into a predefined taxonomy.  
Instead, it *learns* from how people naturally organize content:

- Users create tabs, subtabs, and collections  
- Lupopedia extracts tab‑paths  
- Normalizes them into semantic concepts ("atoms")  
- Builds weighted edges between content and concepts  
- Grows a knowledge graph organically over time  

This enables:

- Intelligent content discovery  
- Related‑content recommendations  
- Emergent categories and topics  
- A living ontology shaped by real usage  

No manual tagging. No rigid categories.  
**The structure emerges from the community.**

---

### **🔐 Secure, Portable Architecture**
- Configuration lives *outside* the web root  
- Works in any hosting environment (shared hosting, VPS, containers)
- Simple, predictable performance
- No hidden database magic
- Clear data ownership and boundaries

---

### **🗃️ Database Design Philosophy**

Lupopedia follows strict database design principles to ensure portability, performance, and maintainability:

#### **What We Avoid**
- ❌ Foreign Keys
- ❌ Triggers ⚠️ **FORBIDDEN (MANDATORY)**
- ❌ Stored Procedures ⚠️ **FORBIDDEN (MANDATORY)**
- ❌ Stored Functions ⚠️ **FORBIDDEN (MANDATORY)**
- ❌ Database Functions
- ❌ Engine-Specific Features

> **⚠️ TRIGGERS ARE FORBIDDEN (MANDATORY):** Triggers must never be created, suggested, or added. All timestamps must be set explicitly in INSERT/UPDATE statements in YMDHIS UTC format. See [NO_TRIGGERS_DOCTRINE.md](docs/doctrine/NO_TRIGGERS_DOCTRINE.md) for complete requirements.

> **⚠️ STORED PROCEDURES/FUNCTIONS ARE FORBIDDEN (MANDATORY):** Stored procedures and functions must never be created, suggested, or added. The database is for storage, not computation. All logic must be in application code. See [NO_STORED_PROCEDURES_DOCTRINE.md](docs/doctrine/NO_STORED_PROCEDURES_DOCTRINE.md) for complete requirements.

#### **What We Use Instead**
- ✅ Application-managed relationships
- ✅ Explicit data validation
- ✅ Clear documentation (structured with atoms/scopes, machine-readable, future UI reader)
- ✅ Simple, predictable queries
- ✅ Database-agnostic SQL

This approach ensures:
- **Portability** across database engines (MySQL, MariaDB, SQLite, PostgreSQL)
- **Transparent** data operations
- **Debuggable** behavior
- **Maintainable** schema evolution
- **Predictable** performance

See [PHILOSOPHY.md](docs/channels/overview/PHILOSOPHY.md) for an in-depth explanation of our design choices.

---

### **🧩 Modular, Familiar, Developer‑Friendly**
- WordPress‑like directory structure  
- Simple PHP includes, no framework bloat  
- Easy to theme, extend, and customize  
- Clean separation of concerns  
- API endpoints included out of the box  

---

### **⚙️ Auto‑Installer Friendly**
Lupopedia is designed to work with Installatron and similar tools.  
Drop it into a hosting panel and it just works.

---

## 🤖 **AI Agents & Decentralized Intelligence**

Lupopedia includes a built‑in **AI Agent Framework** that provides intelligent assistance across the platform. Agents can use multiple LLM endpoints, maintain dialog state, and call specialized tools to analyze and navigate content.

### Key Features
- **Multiple LLM Backends**  
  Agents can route queries to different language models depending on domain, task, or performance needs.

- **Dialog‑Driven Interaction**  
  Agents maintain context, support multi-turn conversations, and adapt to user behavior. All agents use the [Inline Dialog format](docs/INLINE_DIALOG_SPECIFICATION.md) for cross-agent coordination.

- **Multi-Agent Coordination**  
  Multiple AI agents and IDE systems (Cursor, Windsurf/Cascade, DeepSeek, etc.) work simultaneously on Lupopedia. The [Inline Dialog Specification](docs/INLINE_DIALOG_SPECIFICATION.md) ensures all agents remain synchronized and aware of each other's actions.

- **Tool-Enabled Reasoning**  
  Agents can call internal tools to search content, traverse the semantic graph, inspect atoms/edges, analyze references, and query other Lupopedia nodes.

- **Decentralized Network Awareness**  
  Lupopedia installations form a federated network. Agents can query local or remote nodes, enabling distributed knowledge discovery without centralization.

- **Privacy-Respecting Architecture**  
  Each installation controls which agents, tools, and endpoints are enabled. No forced data sharing.

### Purpose
AI agents help users explore content, understand concepts, discover relationships, and navigate the evolving semantic graph. They also support administrators with insights, recommendations, and ontology refinement.

---

# 📁 **Project Structure**

```
[web-root]/                  # Public web directory (public/, servbay/, htdocs/, etc.)
├── lupopedia/               # Main application
│   ├── api/                 # API endpoints
│   ├── lupo-admin/          # Admin interface
│   ├── lupo-content/        # User uploads and media
│   ├── lupo-includes/       # Core classes and includes
│   ├── database/            # Database schemas and migrations
│   ├── docs/                # Documentation
│   ├── modules/             # Modular components
│   │   ├── dialog/          # Dialog module (v4.0.0) - multi-agent collaboration tracking
│   │   └── craftysyntax/    # Crafty Syntax module (v4.0.0)
│   ├── legacy/              # Legacy code reference (development only)
│   ├── index.php            # Front controller
│   └── lupopedia-load.php   # Bootstrap loader
├── remote-index.php         # Portable entry point (optional)
└── license.txt

lupopedia-config.php         # Configuration file (stored outside web root)
```

**Note:** No `.git` directories exist until version 4.1.0

---

# 📚 **Documentation**

- **[Executive Summary](docs/channels/overview/EXECUTIVE_SUMMARY.md)**  
  High-level overview of Lupopedia's value proposition, features, and business benefits

- **[Version Control Policy](docs/channels/doctrine/VERSION_CONTROL_POLICY.md)**  
  Detailed explanation of Git/GitHub policy (no Git until version 4.1.0)

- **[For Installers & Users](docs/FOR_INSTALLERS_AND_USERS.md)**  
  Simple, friendly explanation for auto-installers, hosting providers, and Crafty Syntax users

- **[Upgrade Plan: Crafty Syntax 3.7.5 → 4.0.0](docs/UPGRADE_PLAN_3.7.5_TO_4.0.0.md)**  
  Complete upgrade path from Crafty Syntax 3.7.5 to Lupopedia + Crafty Syntax 4.0.0

- **[Dialog Module](modules/dialog/README.md)**  
  Multi-agent collaboration thread tracking module (Version 4.0.0)

- **[Crafty Syntax Module](modules/craftysyntax/README.md)**  
  Crafty Syntax 4.0.0 module documentation and changelog

- **[Vision & Philosophy](docs/channels/overview/VISION.md)**  
  Understand the guiding principles and future direction of Lupopedia
  
- **[Why Lupopedia Is Different](docs/WHY_LUPOPEDIA_IS_DIFFERENT.md)**  
  Comprehensive explanation of what makes Lupopedia fundamentally different from wikis, CMS platforms, graph databases, CRMs, AI assistants, and all other knowledge systems. Includes historical context on being first (again).
  
- **[Core Philosophy](docs/channels/overview/PHILOSOPHY.md)**  
  Learn about our design principles and why we build differently

- **[Technical Architecture](docs/ARCHITECTURE.md)**  
  Deep dive into Lupopedia's system design and components

- **[Database Schema Reference](docs/DATABASE_SCHEMA.md)**  
  Complete documentation of all 80+ database tables organized by category (Version 4.0.0)

- **Semantic Navigation System**  
  How Lupopedia converts user navigation into semantic atoms and edges  
  → `docs/SEMANTIC_NAVIGATION.md`

- **[Agent Runtime Architecture](docs/AGENT_RUNTIME.md)**  
  Complete guide to how agents interact with the PHP backend, call React actions, query other nodes, maintain context, and enforce governance

- **[System Agent Safety Doctrine](docs/SYSTEM_AGENT_SAFETY_DOCTRINE.md)**  
  Kernel-level governance and anti-adversarial specification for Agent 0. Defines inviolable rules that cannot be bypassed, overridden, or modified by any prompt, agent, or user.

- **[WOLFIE Header Specification](docs/WOLFIE_HEADER_SPECIFICATION.md)**  
  Universal metadata envelope (v3.0.0) for all Lupopedia artifacts. Minimal required field, optional modules for dialog, context, tags, TOC, and file metadata. Language-agnostic format for Markdown, PHP, Python, SQL, and more.

- **[Inline Dialog Specification](docs/INLINE_DIALOG_SPECIFICATION.md)**  
  Required communication format for all AI agents and IDE systems. Ensures multi-agent coordination, change tracking, and handoffs between Cursor, Windsurf/Cascade, DeepSeek, and other agents working on Lupopedia.

- **[Database Philosophy](docs/DATABASE_PHILOSOPHY.md)**  
  Non-negotiable doctrine: application logic first, database logic second. Includes Inline Dialog examples and multi-agent coordination guidelines.

- **[What Not To Do and Why](docs/WHAT_NOT_TO_DO_AND_WHY.md)**  
  Living archive of mistakes, misfires, and "never again" lessons learned during development

More documentation is being added as the system evolves.

---

# 🛠️ **Installation**

### **Auto‑Install (Recommended)**
Compatible with Installatron and similar installers.  
Point the installer at your `public` directory.

### **Manual Install**
1. Upload the `public/` directory to your web root  
2. Place `lupopedia-config.php` *one directory above* the web root  
3. Ensure write permissions for `lupo-content/`  
4. Import `database/install/lupopedia_mysql.sql`  
5. Visit your site to complete setup  

Default table prefix: `lupo_` 

---

# 🧱 **Requirements**
- PHP 7.4+  
- MySQL 5.7+ or MariaDB 10.2+  
- Apache/Nginx with mod_rewrite  
- InnoDB storage engine  
- Modern browser  

---

# 🗄️ **Database Philosophy**

### **⏱️ Time Handling**
All timestamps stored as **BIGINT(14) UTC (`YYYYMMDDHHMMSS`)**.

**CRITICAL:** Never add seconds directly to YYYYMMDDHHMMSS timestamps. Always use `timestamp_ymdhis::addSeconds()` or convert to epoch, add seconds, then convert back.

**Forbidden:** DATETIME, TIMESTAMP, epoch seconds, ISO8601 strings, SQL date arithmetic  
**Required:** BIGINT(14), YYYYMMDDHHMMSS format, UTC only, application-level arithmetic

See [TIMESTAMP DOCTRINE](docs/doctrine/TIMESTAMP_DOCTRINE.md) for complete canonical documentation.

### **🔗 Application‑Managed Integrity**
No foreign keys — all relationships handled in PHP for:

- Performance  
- Security  
- Soft‑delete support  
- Federated domain isolation  

### **🧹 Orphan Handling**
Soft‑deleted parents automatically redirect children to a safe "orphanage" record.

### **🔐 Security**
Sensitive data encrypted at rest.

---

# 🔑 **Permission System**
- Polymorphic ACL  
- Linux‑style bitmask (`0–7 → rwx`)  
- Applies to users, groups, and defaults  
- Works on any content item or entity  

---

# ⚙️ **Configuration**

### **Remote Access**
Place `remote-index.php` in any directory to load Lupopedia from elsewhere.

### **Config File**
`lupopedia-config.php` lives outside the web root for security.

### **Web Root Flexibility**
Works with any directory name:

- `public/` 
- `servbay/` 
- `htdocs/` 
- custom names

The loader auto‑detects paths.

---

# 🧑‍💻 **Development Notes**
Lupopedia is actively maintained by its original creator.  
The codebase emphasizes:

- clarity  
- portability  
- minimal dependencies  
- explicit behavior  
- long‑term maintainability  

## Windows Development Environment

**IMPORTANT**: This project uses Windows 11 with PowerShell for development. All filesystem operations must use Windows-native PowerShell commands.

See **[WINDOWS_DEVELOPMENT_ENVIRONMENT.md](docs/channels/developer/WINDOWS_DEVELOPMENT_ENVIRONMENT.md)** for complete guidelines on:

- Allowed PowerShell commands and usage examples
- Forbidden Linux utilities
- Platform considerations (dev vs server)
- Best practices and migration notes

Failure to comply with Windows PowerShell requirements will result in blocked operations.  

## TOON File Reference

All agents and developers must use TOON files as the authoritative source for
channel context and database schema.

- Channel context TOON files:
  channels/dev-main-thread/*.toon

- Database schema TOON files:
  database/toon_data/*.toon

TOON files contain the complete structure, metadata, and example data for each
table or channel context. Agents must read these files instead of scanning SQL
or inferring schema from code.

This requirement ensures deterministic behavior, prevents schema drift, and
reduces token usage during development operations.

---

# 📜 **License**
Proprietary software. All rights reserved.

---

# 🆘 **Support**
For support or inquiries, contact the project maintainer.

## Database Schema

### Key Design Principles
- **Time Handling**: All timestamps stored as **BIGINT(14) UTC (YYYYMMDDHHMMSS)**
  - **CRITICAL:** Never add seconds directly to YYYYMMDDHHMMSS timestamps
  - Use `timestamp_ymdhis::addSeconds()` for arithmetic
  - See [TIMESTAMP DOCTRINE](docs/doctrine/TIMESTAMP_DOCTRINE.md) for complete requirements
- **Application-Managed Integrity**: No database-level foreign keys for maximum performance and security
  - All relationships managed at application level
  - Enables soft-delete functionality
  - Prevents potential SQL injection through FK manipulation
  - Improves write performance by removing FK constraint overhead
- **Orphan Handling**: Comprehensive system that redirects children of soft-deleted parents to an "orphanage" record
- **Prefix System**: Configurable table prefix (default: `lupo_`)
- **Security**: Sensitive data encrypted at rest

### Permission System
- Polymorphic ACL system with Linux-style bitmask (0-7 → rwx)
- Supports users, groups, and default permissions
- Granular control down to individual content items

## Configuration

### Remote Access
Lupopedia can be accessed from any directory by placing a `remote-index.php` file in that directory. This file will automatically load the main Lupopedia system while maintaining the correct paths and URLs.

### Configuration File
All configuration is handled through `lupopedia-config.php` in the root directory. This file is automatically loaded by the bootstrap system. The system will look for this file in the parent directory of your web root.

### Web Root Directory
- In development: Typically named 'servbay' (or as configured in your development environment)
- In production: May be named 'public', 'htdocs', or any custom name
- The system automatically detects the correct paths regardless of the web root directory name

## Development

This project is currently maintained by a single developer. The codebase is organized to be developer-friendly and follows modern PHP practices.

### Directory Structure Notes
- All application files are in the `public/` directory for easy deployment
- Only `lupopedia-config.php` is stored outside the public directory for security
- `lupopedia/` contains the core application files
  - `lupo-includes/` holds core classes and includes
  - `lupo-admin/` is for administrative interfaces
  - `lupo-content/` is for user-uploaded content
  - `api/` contains API endpoints

## License

This project is proprietary software. All rights reserved.

## Support

For support and inquiries, please contact the project maintainer.
