GOV‑TOON‑GENERATION‑001.md
TOON Generation Governance Doctrine
Version 2026.0.1.1
Purpose
This doctrine defines the only approved method for generating TOON files inside Lupopedia.
TOON files represent the authoritative schema layer for all database tables and must be produced deterministically, without inference, drift, or AI involvement.

Canonical Generator
All TOON files must be generated exclusively by the following script:

Code
database/generate_toon_files.py
This script is the single source of truth for TOON generation.
It extracts schema directly from the live database and produces one .toon file per table.

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
database/toon_data/
File naming convention:

Code
<table_name>.toon
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

"Operation blocked by governance. TOON generation is restricted to generate_toon_files.py."

Status
ACTIVE — NON‑NEGOTIABLE  
This doctrine applies to all agents, tools, contributors, and automated systems.
