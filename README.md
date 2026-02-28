# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  file_path_from_root: "README.md"
  file_hash: "d10f0134d3f8d347ca86ee872bbb5ad61218f212d0bd5811624e6ddde83d0553"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 10000
  last_modified_utc: "20260227"
  delegation_chain: null
  artifact_type: "guide"
  purpose: "Primary project documentation and architectural overview for Lupopedia Semantic OS with actor identity and access requirements"
  dialog_message: "Updated for 4.0.49 with focus on actor identity, registration, and database seeding requirements for system access."
  mood_rgb: "4169E1"
  artifact_kind: "documentation"
  traits: ["essential", "entrypoint", "comprehensive", "v4.0.49"]
  tags: ["readme", "overview", "architecture", "actor_identity", "database_seeding"]
  lupo_agent: "windsurf"

flare.edges:
  file_path_from_root: "README.md"
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
    - { to: "docs/doctrine/VERSION_POLICY_DOCTRINE.md", type: "references", weight: 1.0, reason: "Critical version policy and blocker information" }
  semantic_tags: ["project_overview", "architecture", "multi_agent_ecosystem", "semantic_os", "crafty_syntax_upgrade", "flare_protocol"]

  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified_utc: "20260227"
  last_verified_by: "antigravity"
---

## 🐺 Lupopedia 4.0.49 — Development Cycle (2026-02-27)
**Status:** Active Development  
**Mission:** Actor identity system, database documentation, and admin interface modernization building on Crafty Syntax 3.7.5 upgrade foundation.

**🚨 CRITICAL VERSION POLICY:** Lupopedia 4.0.x CANNOT advance to 4.1.0 until auto-installers (Installatron, Fantastico, Softaculous) accept 4.0.x as a Crafty Syntax 3.7.5 replacement. See [Version Policy Doctrine](docs/doctrine/VERSION_POLICY_DOCTRINE.md) for complete details.

**👤 ACTOR IDENTITY & ACCESS:** To use Lupopedia, you must be a registered actor in the database. New users must be seeded into the system through proper registration and authentication processes. See [Actor Registration](#actor-registration) below for details.

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

### 👤 Actor Registration & Database Seeding

#### **Actor Identity Requirement**
To use Lupopedia, every user must be a registered actor in the database. The system uses `actor_id` as the universal identity key across all tables.

#### **Registration Process**
1. **Initial Registration**: New actors are created through the registration system
2. **Database Seeding**: Actor records are seeded into `lupo_actors` table
3. **Authentication Setup**: Login credentials created in `lupo_auth_users` table
4. **Profile Creation**: Actor profile and capabilities established
5. **Channel Assignment**: Actor assigned to appropriate channels for access

#### **Required Actor Information**
- **Display Name**: Human-readable name for the actor
- **Email Address**: Unique email for authentication and communication
- **Actor Type**: 'human' for users, 'ai' for AI agents
- **Capabilities**: JSON object defining actor permissions and abilities
- **Department Assignment**: Organizational structure integration
- **Status**: Active/inactive status for access control

#### **CLI Registration Commands**
```bash
# Register new actor (creates database record and authentication)
php bin/lupo.php register "Actor Name" human --email="user@example.com" --department=1

# Register IDE agent (creates AI actor with system tools)
php bin/lupo.php register "My IDE Agent" ai --capabilities="system_tool,development"

# Check current actor identity
php bin/lupo.php whoami

# Join development channel (requires registered actor)
php bin/lupo.php join 42
```

#### **Authentication Requirements**
- **Valid Actor ID**: Must exist in `lupo_actors` table
- **Active Status**: Actor must be marked as active (`is_active = 1`)
- **Authentication Record**: Must have entry in `lupo_auth_users` table
- **Session Management**: Valid session required for system access
- **Permission Check**: Actor capabilities verified for requested operations

#### **Database Seeding Process**
1. **Actor Record Creation**: Insert into `lupo_actors` with proper metadata
2. **Authentication Setup**: Create corresponding `lupo_auth_users` record
3. **Profile Initialization**: Set up actor profile and preferences
4. **Capability Assignment**: Define actor permissions and system access
5. **Channel Membership**: Assign actor to appropriate communication channels

**🔒 Security Note**: All actors must be properly registered and authenticated before accessing any Lupopedia functionality. Unregistered access attempts are blocked by the security system.

### CLI Basics
```bash
cd /path/to/lupopedia

# Register new actor (creates database record and authentication)
php bin/lupo.php register "Actor Name" human --email="user@example.com" --department=1

# Register IDE agent (creates AI actor with system tools)
php bin/lupo.php register "My IDE Agent" ai --capabilities="system_tool,development"

# Check current actor identity
php bin/lupo.php whoami

# Join development channel (requires registered actor)
php bin/lupo.php join 42

# Send message to channel (requires registered actor)
php bin/lupo.php send 42 "Hello from CLI"

# List channel messages (requires registered actor)
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

FLARE headers must start with the exact prologue line `# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE`, followed immediately by `---` and `flare.headers`.

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