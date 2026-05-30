---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: CAPTAIN_WOLFIE_WORKFLOW.md
  web_path: https://www.lupopedia.com/lupopedia/CAPTAIN_WOLFIE_WORKFLOW.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/captain-wolfie-workflow.toon
  atoms_toon: null
  transcript_jsonl: 0/development/captain-wolfie-workflow
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: captain-wolfie-workflow
  lupopedia.schema: documentation
  prd_cluster: null
  title: CAPTAIN_WOLFIE_WORKFLOW.md -- Development Workflow
  summary: Canonical development workflow for Lupopedia using multiple IDEs and AI agents.
---
# Captain Wolfie workflow

## Purpose

This document defines the canonical development workflow for Lupopedia, including how the architect uses multiple IDEs, AI agents, and manual editing tools.

## Tool Roles

Describe the three-tool workflow:

1. Notepad++
   - Primary editor for large changes
   - Used for project-wide search/replace
   - Used for manual refactoring
   - Zero token cost, deterministic behavior

2. Claude Code
   - Used for surgical, single-file edits
   - Used for rewriting one PRD, validator, or README section
   - Does NOT load the entire repo
   - Ideal for precise, isolated changes

3. External AI (Copilot)
   - Generates PRDs, doctrine, README sections, sync files, pseudocode, validators
   - Produces large documents OUTSIDE the IDE
   - Architect pastes results manually into files

4. Cursor
   - Used sparingly due to token limits
   - Only for multi-file refactors or code navigation
   - Never used for "update everything" operations
   - Only used when absolutely necessary
   - **Binding rules:** `lupo-rules/root/CURSOR_IDE_RULES.md` — load before any Cursor operation; aligns this workflow with Cursor’s scope and token-efficiency limits

## Workflow Summary

Describe the workflow:

- All large documents (PRDs, doctrine, README sections) are generated externally.
- The architect pastes final text into Notepad++.
- Claude Code is used for precise, single-file updates.
- Cursor is used only for complex refactors or navigation.
- No tool is allowed to modify multiple files unless explicitly instructed.
- No tool is allowed to guess or infer missing content.

## Shorthand Files

List the shorthand files used by IDE agents:

- FOR_CLAUDE_CODE_*.md — sync files for Claude Code
- CAPTAIN_WOLFIE_WORKFLOW.md — this file
- CURSOR.md — Cursor-specific instructions (points to full doctrine below)
- lupo-rules/root/CURSOR_IDE_RULES.md — **Cursor IDE agent doctrine** (load first in Cursor; no default whole-repo scans; single-file default; explicit instruction for sensitive edits)
- Any future shorthand files must follow the same pattern:
  - root-level
  - uppercase
  - single purpose
  - safe for AI agents to read

## Castcade Agent Role

Castcade is the multi-file orchestrator for Lupopedia. It manages PRDs,
doctrine, schema JSON, validators, and version folders. It does NOT modify the
installer. It follows JSON schemas exactly and generates SQL only when derived
from schema changes. It is used for large documentation updates and structural
refactors.
