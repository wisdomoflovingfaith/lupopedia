---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: "CAPTAIN_WOLFIE_WORKFLOW.md"
  web_path: "http://www.lupopedia.com/lupopedia/CAPTAIN_WOLFIE_WORKFLOW.md"
  last_modified_utc: "20260406132900"
  when_updated: "20260406132900"
  federation_node_id: 0
  channel_id: 42
  thread_id: "captain-wolfie-workflow"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: documentation
  artifact_kind: documentation
  purpose: "Canonical development workflow for Lupopedia using multiple IDEs and AI agents"
  tags:
    - "workflow"
    - "development"
    - "ide"
    - "ai_agents"
    - "cursor"
    - "claude_code"
    - "castcade"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-rules/root/CURSOR_IDE_RULES.md"
      type: references
      weight: 1.0
      reason: "Cursor IDE agent doctrine"
    - to: "lupo-rules/root/CASTCADE_IDE_RULES.md"
      type: references
      weight: 0.9
      reason: "Castcade agent rules"
    - to: "FOR_CLAUDE_CODE_2026_04_06.md"
      type: references
      weight: 0.8
      reason: "Claude Code sync file"
    - to: "CURSOR.md"
      type: references
      weight: 0.8
      reason: "Cursor-specific instructions"
lupopedia.footer:
  last_verified: "20260406132900"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: "cursor:root"
  next_action:
    - "Update workflow when new tools are added"
    - "Maintain tool role definitions"
    - "Keep shorthand files current"
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
