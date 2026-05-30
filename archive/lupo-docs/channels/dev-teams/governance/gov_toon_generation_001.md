# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/dev-teams/governance/GOV-TOON-GENERATION-001.md"
  file_hash: "5a300c3bfc4c1da4fc4bbf71ec49cbd3dc4fa7bf875a248416cdd7ab1197973e"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
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
  file_path_from_root: "lupo-docs\channels\dev-teams\governance\GOV-TOON-GENERATION-001.md"
  file_hash: "9fc5db1561abadacc16350cb669e148de7cbc1deac07c590f0fcc5f7d7a53730"
  file_path_from_root: "lupo-docs\channels\dev-teams\governance\GOV-TOON-GENERATION-001.md"
  file_hash: "70188d242b0efdf0712f9fb76d9d1cf870e59080e229b11f9d1b8d565d92fff7"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for GOV-TOON-GENERATION-001.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "dev-teams", "governance", "gov-toon-generation-001md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

GOV‑TOON‑GENERATION‑001.md
TOON Generation Governance Doctrine
Version 3.0.0
Purpose
This doctrine defines the only approved method for generating TOON files inside Lupopedia.
TOON files represent the authoritative schema layer for all database tables and must be produced deterministically, without inference, drift, or AI involvement.

Canonical Schema Source (4.0.16+)
The single source of truth for schema is install_new_lupopedia.sql and seed_lupopedia.sql. TOON files are derived from this canonical schema.

Canonical Generators
1. lupo-scripts/generate_toon_from_sql.py — Parses install_new_lupopedia.sql; no live DB required.
2. lupo-scripts/generate_toon_files.py — Extracts from live database; use when schema has been applied.

Non‑Negotiable Rules
1. AI Systems Must Never Generate TOON Files
AI agents are strictly prohibited from:

generating .toon files

rewriting .toon files

inferring schema

reconstructing schema from SQL

producing TOON content from prompts

modifying TOON structure outside MACHINE_EDITABLE sections

TOON generation is a compiler task, not an AI task.

2. TOON Files Must Always Reflect the Live Database
The generator script:

reads the database schema

extracts table definitions

formats them into TOON

writes deterministic, reproducible files

No other process is permitted to create or regenerate TOON files.

3. One Table = One TOON File
Each table in the database must have exactly one corresponding TOON file located in:

Code
lupo-docs/toons/
File naming convention:

Code
<table_name>.toon.json
This ensures:

fast lookup

zero inference

zero scanning of SQL

deterministic schema access

4. AI Agents May Only Read TOON Files
AI agents may:

read TOON files

extract schema

reference column definitions

use TOON as the schema oracle

AI agents may not:

regenerate TOON

rewrite TOON

reformat TOON

delete TOON

create new TOON files

5. MACHINE_EDITABLE Sections
If a TOON file contains a machine‑editable region, it must follow this structure:

Code
# MACHINE_EDITABLE_SECTION_BEGIN
# MACHINE_EDITABLE_SECTION_END
AI agents may only modify content inside this region.
All other content is immutable and must be preserved exactly as generated.

6. SQL Files Are Execution‑Only
AI agents must never:

read SQL files for schema

infer schema from migrations

scan SQL directories

The database → TOON → agent pipeline is the only allowed flow.

Rationale
This doctrine exists to:

prevent schema drift

eliminate hallucinated columns

ensure deterministic builds

reduce token usage

maintain architectural purity

protect the integrity of the semantic OS

TOON files are the backbone of Lupopedia's schema‑first design.
Their generation must remain stable, predictable, and fully automated.

Enforcement
Any agent or process attempting to generate or rewrite TOON files outside the approved script must be blocked immediately with:

"Operation blocked by governance. TOON generation is restricted to lupo-scripts/generate_toon_files.py."

Status
ACTIVE — NON‑NEGOTIABLE  
This doctrine applies to all agents, tools, contributors, and automated systems.
