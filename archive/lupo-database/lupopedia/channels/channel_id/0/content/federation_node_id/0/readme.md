# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/0/content/federation_node_id/0/readme.md"
  file_hash: "to_be_generated"
  federation_node_id: 0
  web_path: "http://www.lupopedia.com/readme"
  last_updated_utc: "20260301"
  system_version: "4.0.52"
  channel_id: 42
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  purpose: "Federation node 0 README with comprehensive Lupopedia overview and FLARE integration"
  dialog_message: "Canonical README for federation node 0 combining project overview, FLARE protocol, and federation infrastructure"
  mood_vector: "4169E1"
  traits: ["canonical", "federation", "v4.0.52"]
  tags: ["readme", "overview", "architecture", "multi_agent", "semantic_os", "flare_protocol", "federation", "node_0"]

lupopedia.edges:
  outbound_edges:
    - { to: "README.md", type: "references", weight: 1.0 }
    - { to: "QUICKSTART.md", type: "references", weight: 1.0 }
    - { to: "HOW_TO_USE_LUPOPEDIA.md", type: "references", weight: 0.9 }
    - { to: "CHANGELOG.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/VERSION_POLICY_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/database/README.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/architecture/FEDERATION_AND_REGISTRY.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/architecture/ANUBIS_ADOPTION_PIPELINE.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/toons/", type: "references", weight: 0.8 }
    - { to: "lupo-tools/vsx-extension/", type: "references", weight: 0.8 }
    - { to: "lupo-database/migrations/", type: "references", weight: 0.8 }
    - { to: "lupo-legacy/craftysyntax/", type: "references", weight: 0.5 }
    - { to: "config/global_atoms.yaml", type: "references", weight: 0.7 }
    - { to: "lupopedia-config.php", type: "references", weight: 0.6 }
    - { to: "index.php", type: "references", weight: 0.6 }
    - { to: "lupo-channels/42/content/federation_node_id/0/FLARE.md", type: "references", weight: 1.0 }
    - { to: "lupo-channels/42/content/federation_node_id/0/changelog.md", type: "references", weight: 0.9 }
    - { to: "lupo-channels/42/content/federation_node_id/0/flare/readme.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_channel_content.md", type: "references", weight: 0.7 }
  semantic_tags: ["project_overview", "architecture", "multi_agent_ecosystem", "semantic_os", "crafty_syntax_upgrade", "flare_protocol", "federation", "canonical"]

lupopedia.footer:
  last_verified: "20260301"
  last_verified_by: "windsurf"
---

🐺 Lupopedia 4.0.52 — Semantic OS on Crafty Syntax

Status: Active Development
Foundation: Crafty Syntax 3.7.5 (preserved exactly)
Layer Added: Multi-agent Semantic Operating System

Start Here:
QUICKSTART.md
 •
HOW_TO_USE_LUPOPEDIA.md
 •
Doctrine Index
 •
VSX Extension

1️⃣ What Lupopedia Is

Lupopedia preserves Crafty Syntax 3.7.5 exactly (operators, departments, transcripts, live chat) and adds a semantic OS layer:

Actors (unified identity system)

Channels (governance + coordination)

Threads (persistent markdown dialogs)

Meaning edges (semantic relationships)

FLARE metadata (file-level intelligence)

Federation + global identity registry

Crafty Syntax becomes the heart.
Lupopedia becomes the brain.

2️⃣ System Requirements

PHP 5.3+

MySQL compatible

Subdirectory installation required

UTC integer timestamps (YYYYMMDDHHIISS)

Doctrine enforcement:
lupo-docs/doctrine/

3️⃣ Installation & Setup
Web Install / Upgrade

Install in subdirectory:

/lupopedia/

Visit:

https://localhost/lupopedia/install.php

For Crafty 3.7.5 → Lupopedia upgrade:

Load baseline tables

Run installer

Validate using migration mapping reference

Reference:
lupo-docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md

CLI Usage
cd /path/to/lupopedia

php bin/lupo.php register "My IDE Agent" system_tool
php bin/lupo.php whoami
php bin/lupo.php join 42
php bin/lupo.php send 42 "Hello from CLI"
php bin/lupo.php messages 42
4️⃣ Core Concepts
Actor Model (Non-Negotiable)

actor_id everywhere

No user_id in relationships

Humans, IDE agents, AI assistants unified

Requires proper database seeding before access

Reference:
lupo-docs/AGENT_INVENTORY.md

Channels & Threads

Offline governance system stored in:

lupo-channels/{id}/threads/

Enables:

Multi-agent coordination

Persistent decision history

Migration-safe collaboration

FLARE Protocol

FLARE = file-level metadata intelligence system.

Headers → attributes

Edges → relationships

Footer → verification

Legacy aliases: Wolfie / FLIP / FLP / CROP
Canonical doctrine:
lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md

TOON Schema Authority
lupo-docs/toons/*.toon.json

These files are the only source of truth for schema structure.

No schema change without TOON update.

5️⃣ VSX Extension

IDE integration for:

Actor identity sync

Channel communication

Semantic graph inspection

Hybrid / Offline / DB-Online modes

Location:
lupo-tools/vsx-extension/

6️⃣ Federation & Global Registry

All installations share unified ID spaces:

Actors

Channels

Collections

Managed by:

Federation registry

ANUBIS adoption pipeline

References:

lupo-docs/architecture/FEDERATION_AND_REGISTRY.md

lupo-docs/architecture/ANUBIS_ADOPTION_PIPELINE.md

7️⃣ Repository Map
Path	Purpose
install.php	Install / upgrade wizard
lupo-database/migrations/	Schema + seeds + Crafty import
lupo-docs/doctrine/	Development doctrine
lupo-docs/toons/	Schema source of truth
lupo-docs/database/lupopedia/tables/	Table documentation
lupo-channels/	Governance + coordination
lupo-legacy/craftysyntax/	Read-only legacy reference
lupo-tools/vsx-extension/	VS Code extension
bin/lupo.php	CLI interface
8️⃣ FILEOPT Progress

FILEOPT-2026-02-27-001 — COMPLETE

138+ files processed

~18% reduction

6 structured commits

Improved organization and discoverability

Stable base for v4.1.0

9️⃣ Federation Node 0 Integration

This README is part of federation node 0, providing:

Canonical web resolution: http://www.lupopedia.com/readme

FLARE protocol compliance with federation metadata

Integration with lupo_channel_content table

Web path mapping for federation infrastructure

🔟 Contribution

See:

CODE_OF_CONDUCT.md

CONTRIBUTING.md

lupo-docs/doctrine/VERSION_POLICY_DOCTRINE.md

🔟 Canonical Status

System Version: 4.0.52
Lead Agent: Windsurf (1002)
Last Updated: 20260301
FLARE Compliance: Mandatory post-v4.0.51
Federation Node: 0 (Canonical)
Web Path: http://www.lupopedia.com/readme
