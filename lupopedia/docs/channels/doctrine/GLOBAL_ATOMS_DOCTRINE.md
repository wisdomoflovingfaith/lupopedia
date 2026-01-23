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
  message: "Created Global Atoms Doctrine documentation defining the global_atoms.yaml system, atom naming, values, symbolic reference syntax, resolution pipeline, and Cursor rewrite protection."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "atoms", "configuration"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Global Atoms Doctrine Overview
  - Purpose of Global Atoms
  - File Location
  - Atom Naming Doctrine
  - Atom Value Doctrine
  - Symbolic Reference Syntax
  - Resolution Pipeline
  - Directory-Scoped Atoms
  - Atom Inheritance
  - Cursor Rewrite Protection
  - Atom Creation Doctrine
  - Atom Examples
file:
  title: "Global Atoms Doctrine"
  description: "Documents the global atom system used throughout Lupopedia. Global atoms provide deterministic, symbolic constants for ecosystem-wide metadata, preventing drift and ensuring consistency."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Global Atoms Doctrine

This section documents the global atom system used throughout Lupopedia. Global atoms provide deterministic, symbolic constants for ecosystem‑wide metadata. They prevent drift, eliminate duplication, and ensure that all agents, modules, and documentation reference the same authoritative values.

---

## Purpose of Global Atoms

global_atoms.yaml defines:
- ecosystem‑wide constants,
- version identifiers,
- company metadata,
- canonical names,
- URLs,
- doctrine‑level configuration,
- symbolic values used across agents and documentation.

Global atoms exist to:
- prevent rewrite drift,
- enforce consistency,
- provide a single source of truth,
- allow Cursor and other agents to reference stable symbols,
- avoid hard‑coding values in prompts or documentation.

---

## File Location

The file lives at:

/lupopedia/config/global_atoms.yaml

This file is loaded by:
- agents (via registry metadata),
- documentation generators,
- module scaffolding,
- Cursor rewrite protection systems.

---

## Atom Naming Doctrine

Atom names must:
- be uppercase,
- use dot‑notation for hierarchy,
- be deterministic,
- be descriptive,
- avoid creative naming,
- avoid ambiguity.

Examples:
- GLOBAL.LUPOPEDIA.CURRENT_VERSION
- GLOBAL.COMPANY.NAME
- GLOBAL.URL.BASE
- GLOBAL.DIALOG.DEFAULT_PERSONA
- GLOBAL.COLOR.DEFAULT_MOOD

Atoms must never:
- contain spaces,
- contain lowercase letters,
- contain dynamic values,
- contain user‑specific data.

---

## Atom Value Doctrine

Atom values must be:
- static,
- deterministic,
- version‑controlled,
- ecosystem‑wide,
- non‑user‑specific.

Values may be:
- strings,
- numbers,
- booleans,
- lists,
- nested maps.

Values must never be:
- environment‑specific,
- per‑installation,
- per‑user,
- ephemeral,
- time‑dependent.

---

## Symbolic Reference Syntax

Documentation and agents reference atoms using:

@GLOBAL.<ATOM_NAME>

Examples:
- @GLOBAL.LUPOPEDIA.CURRENT_VERSION
- @GLOBAL.COMPANY.NAME
- @GLOBAL.URL.BASE

This symbolic reference:
- prevents drift,
- ensures consistent updates,
- allows Cursor to rewrite safely,
- ensures agents never hard‑code values.

---

## Resolution Pipeline

When an agent or documentation references @GLOBAL.<ATOM_NAME>:

1. The symbolic reference is detected.
2. The atom name is resolved against global_atoms.yaml.
3. The resolved value is injected into:
   - system_prompt.txt,
   - documentation,
   - runtime metadata,
   - agent.php wrappers.

Resolution is:
- deterministic,
- non‑recursive,
- read‑only,
- side‑effect‑free.

---

## Directory‑Scoped Atoms

Directories may define local atoms using:

_dir_atoms.yaml

Rules:
- Local atoms override global atoms only within that directory.
- Local atoms must follow the same naming doctrine.
- Local atoms must not redefine global constants.
- Local atoms must be used sparingly and only for directory‑specific
  metadata.

Example:
- /modules/crafty_syntax/_dir_atoms.yaml

---

## Atom Inheritance

Inheritance rules:
- global_atoms.yaml defines the root namespace.
- _dir_atoms.yaml may extend but not contradict global atoms.
- Agents may define local atoms in memory_profile.json, but these must
  not overlap with global atoms.

Inheritance is hierarchical:
1. global_atoms.yaml
2. directory‑level _dir_atoms.yaml
3. agent‑level metadata (read‑only)

---

## Cursor Rewrite Protection

Cursor must:
- preserve symbolic references exactly,
- never inline resolved values,
- never rewrite atom names,
- never duplicate atom values,
- always use @GLOBAL.<ATOM_NAME> when referencing global constants.

This prevents:
- drift,
- duplication,
- accidental overwrites,
- prompt divergence.

---

## Atom Creation Doctrine

When adding a new atom:
1. Ensure the value is ecosystem‑wide.
2. Ensure the name is deterministic and uppercase.
3. Add it to global_atoms.yaml.
4. Update documentation to reference the symbolic name.
5. Never hard‑code the value anywhere else.

---

## Atom Examples

Example global_atoms.yaml:

GLOBAL:
  LUPOPEDIA:
    CURRENT_VERSION: "4.0.2"
  COMPANY:
    NAME: "Lupopedia LLC"
  URL:
    BASE: "https://lupopedia.com"
  DIALOG:
    DEFAULT_PERSONA: "wolfie.headers.default_persona"
  COLOR:
    DEFAULT_MOOD: "808080"

---
