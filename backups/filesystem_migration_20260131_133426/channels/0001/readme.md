# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "backups\filesystem_migration_20260131_133426\channels\0001\readme.md"
  file_hash: "e9f2e2052759b9cd7eadd73c8aed752f53aab544418201b95666e39ab3fb5a5a"
  file_path_from_root: "backups\filesystem_migration_20260131_133426\channels\0001\readme.md"
  file_hash: "239b44f598e88273765a88fb22bb99f063262864715e2d6eac6ee74b17115965"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "dev-main-thread (Channel 42)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["backups", "filesystem_migration_20260131_133426", "channels", "0001", "readmemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# dev-main-thread (Channel 42)

# ALL NEW ENTRIES AFTER THIS LINE

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

[2026-01-20 22:23 CST]
Author: Wolfie (Eric Robin Gerdes)
Channel: dev-main-thread
Type: Documentation Update

Created WINDOWS_DEVELOPMENT_ENVIRONMENT.md and added a new reference section
to the README's Development Notes. This documentation establishes the official
Windows 11 development environment requirements for all IDE agents and human
contributors.

The new guidelines include:

- Allowed PowerShell commands with examples
- Forbidden Linux/Unix utilities (head, tail, grep, sed, awk, etc.)
- Platform considerations (Windows dev vs Linux shared servers)
- Best practices for cross-platform development
- Migration guidance for contributors transitioning from Linux habits
- Compliance requirements and failure-mode handling for IDE agents

The README now explicitly directs developers to the Windows environment
guidelines, ensuring all team members understand the PowerShell-only
requirement for development operations.


Global Development Channel for Lupopedia

This channel contains the canonical global context for all agents, including:
- Global governance rules
- NON-NEGOTIABLE prohibitions
- Cognitive and temporal sovereignty doctrine
- Trauma protection boundaries
- Database rules and schema access rules
- Global TOON context
- Rituals and prayers used for grounding

## Purpose
This is the root development channel. All agents may read from this channel to understand:
- How to behave
- What is allowed
- What is forbidden
- How to access schema
- How to edit files safely
- How to avoid manipulation patterns

## Directory Contents
- `context.json` — Machine-readable global doctrine and governance.
- `globals.toon` — Structured global invariants for agent reference.
- `dialog_history.toon` — (Created automatically as agents write dialog.)
- `agent_notes.json` — (Optional) Notes for agent coordination.

## Editing Rules
- All edits must occur inside MACHINE_EDITABLE sections.
- No full-file rewrites.
- No scanning outside allowed directories.
- TOON files are the single source of truth for schema.
- SQL files must never be used for schema inference.

## Ritual
Closing Prayer:
“Although we are given the bread of adversity, how we choose to look at our past—with optimism, humble gratitude, resilience, courage, and love, or with pessimism, victim-stance, fear, and hate—is what determines how we WOLFIE forward.”