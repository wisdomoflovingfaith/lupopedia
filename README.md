---
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
flare.headers:
  file_path_from_root: "README.md"
  system_version: "4.0.48"
  channel_id: 1
  actor_id: 1001
  last_modified_utc: "20260227"
  delegation_chain: "1001:10000"
  artifact_type: "guide"
  purpose: "Primary project documentation and architectural overview for Lupopedia Semantic OS with FLARE protocol"
  mood_rgb: "4169E1"
  artifact_kind: "documentation"
  traits: ["essential", "entrypoint", "comprehensive", "v4.0.48"]
  tags: ["readme", "overview", "architecture", "multi_agent", "semantic_os", "flare_protocol"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "QUICKSTART.md", type: "references", weight: 1.0 }
    - { to: "HOW_TO_USE_LUPOPEDIA.md", type: "references", weight: 0.9 }
    - { to: "CHANGELOG.md", type: "references", weight: 0.8 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }
    - { to: "docs/README.md", type: "references", weight: 0.7 }
    - { to: "tools/vsx-extension/", type: "references", weight: 0.8 }
    - { to: "database/migrations/", type: "references", weight: 0.7 }
    - { to: "legacy/craftysyntax/", type: "references", weight: 0.5 }
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "docs/FLARE_HEADERS_QUICK_REFERENCE.md", type: "references", weight: 0.9 }
    - { to: "docs/doctrine/database/README.md", type: "references", weight: 0.8 }
    - { to: "docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.8 }
    - { to: "docs/toons/", type: "references", weight: 0.7 }
    - { to: "config/global_atoms.yaml", type: "references", weight: 0.8 }
    - { to: "lupopedia-config.php", type: "references", weight: 0.7 }
    - { to: "index.php", type: "references", weight: 0.6 }
  semantic_tags: ["project_overview", "architecture", "multi_agent_ecosystem", "semantic_os", "crafty_syntax_upgrade", "flare_protocol"]

flare.footer:
  last_verified_utc: "20260227"
  last_verified_by: "antigravity"
---

## 🐺 Lupopedia 4.0.48 — Development Cycle (2026-02-27)
**Status:** Active Development  
**Mission:** FLARE enhancement, database documentation, and repository cleanup building on Crafty Syntax 3.7.5 upgrade foundation.

**Start Here:** [QUICKSTART.md](QUICKSTART.md) • [HOW_TO_USE_LUPOPEDIA.md](HOW_TO_USE_LUPOPEDIA.md) • [Doctrine Index](docs/doctrine/) • [VSX Extension](tools/vsx-extension/)

---

## Table of Contents

1. [What Lupopedia Is](#what-lupopedia-is)
2. [Quickstart](#quickstart)
   - [Web Install/Upgrade](#web-installupgrade)
   - [CLI Basics](#cli-basics)
3. [Repository Map](#repository-map)
4. [Core Concepts](#core-concepts)
5. [Non-Negotiable Doctrine](#non-negotiable-doctrine)
6. [VSX Extension](#vsx-extension)
7. [Federation & Registry](#federation--registry)

---

## What Lupopedia Is

Lupopedia preserves **Crafty Syntax 3.7.5 exactly** (live chat, operators, departments, transcripts) and adds a **semantic OS layer** with actors, channels, meaning edges, and doctrine. Multiple IDE agents collaborate in real-time through the same codebase using unified actor identities and channel-based communication.

**Core transformation:** Crafty Syntax becomes the heart, Lupopedia becomes the brain. Everything familiar + infinitely extended + collaboratively enhanced.

---

### 🕰️ History

Lupopedia evolves from Crafty Syntax, which began in 2002 as a popular open-source live help system. With over 1.1 million downloads through SourceForge and auto-installers like Fantastico, it became a staple of the early 2000s web. After a decade-long hiatus due to the founder's personal journey, the project returned in 2025, evolving from a spiritual research engine (WOLFIE) into a full semantic operating system.

See [HISTORY.md](docs/channels/appendix/HISTORY.md) for the complete historical narrative and evolution from WOLFIE to Lupopedia.
See also the [Founder's Note](docs/channels/appendix/appendix/FOUNDERS_NOTE.md).

---

## Quickstart

### Web Install/Upgrade

1. **Install in subdirectory** (required doctrine)
2. Visit: `https://localhost/lupopedia/install.php`
3. **Crafty 3.7.5 → Lupopedia upgrade:** Load baseline tables → run installer → validate with [migration mapping docs](docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md)

### CLI Basics

```bash
cd /path/to/lupopedia
php bin/lupo.php register "My IDE Agent" system_tool
php bin/lupo.php whoami
php bin/lupo.php join 42
php bin/lupo.php send 42 "Hello from CLI"
php bin/lupo.php messages 42
```

---

## Repository Map

- `install.php` — Install/upgrade wizard
- `database/migrations/` — Schema + seeds + Crafty import
- `docs/database/lupopedia/tables/` — Complete table documentation and schema reference
- `docs/doctrine/` — Non-negotiable development rules
- `docs/toons/` — Schema source of truth
- `channels/` — Offline governance (broadcasts/tasks/actors)
- `legacy/craftysyntax/` — Read-only reference code
- `tools/vsx-extension/` — VS Code extension
- `bin/lupo.php` — CLI interface

---

## Core Concepts

**Actor Model** — Universal identity system. `actor_id` everywhere for humans, IDE agents, and AI assistants. No `user_id` in relationships. [Canonical IDs](docs/AGENT_INVENTORY.md)

**Channels & Threads** — Offline governance system. Development coordination via persistent markdown messages in `channels/{id}/threads/`. [Full documentation](docs/architecture/CHANNELS_AND_THREADS.md)

**FLARE Protocol** — File-level metadata system. Headers contain attributes, footers contain relationships. FLARE is canonical; legacy aliases: Wolfie/FLIP/FLP/CROP. [FLARE Doctrine](docs/doctrine/FLARE/FLARE_DOCTRINE.md)

**TOON Schema Authority** — `docs/toons/*.toon.json` files are the only source of truth for database structure. No schema changes without TOON updates. [Schema Doctrine](docs/doctrine/database/README.md)

---

## Non-Negotiable Doctrine

**Database Rules**
- ✅ No foreign keys, triggers, stored procedures, views
- ✅ UTC integer timestamps only (YYYYMMDDHHIISS format)
- ✅ TOON-based schema changes only
- ✅ Subdirectory installation required
- ✅ PHP 5.3+ compatibility (no modern features)

**Full doctrine:** [docs/doctrine/](docs/doctrine/)

---

## VSX Extension

The VSX extension integrates your IDE into the multi-agent ecosystem. Provides real-time access to actor model, semantic content graph, and channel communication. Three modes: DB-online, hybrid, offline fallback.

**Extension docs:** [tools/vsx-extension/README.md](tools/vsx-extension/README.md)

---

## Federation & Registry

All installations worldwide share unified ID spaces for actors, channels, and collections. Global registry ensures consistent identity across federated nodes. ANUBIS pipeline manages adoption and collision resolution.

**Federation docs:** [docs/architecture/FEDERATION_AND_REGISTRY.md](docs/architecture/FEDERATION_AND_REGISTRY.md)  
**ANUBIS docs:** [docs/architecture/ANUBIS_ADOPTION_PIPELINE.md](docs/architecture/ANUBIS_ADOPTION_PIPELINE.md)
