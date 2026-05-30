---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: CURSOR.md
  web_path: https://www.lupopedia.com/lupopedia/CURSOR.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/cursor-md.toon
  atoms_toon: null
  transcript_jsonl: 0/development/cursor-md-guide
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: cursor-instructions
  lupopedia.schema: documentation
  prd_cluster: null
  title: CURSOR.md -- Cursor IDE Agent Brief
  summary: 'Cursor-specific rules: token limits, safe updates, delegation chain cursor:root; constitutional constraints for Cursor IDE agent.'
---
# Cursor (Lupopedia)

## Purpose

This file defines Cursor-specific rules to prevent excessive token usage and ensure safe, deterministic updates.

## Cursor Operating Rules

- Cursor must NEVER scan the entire project unless explicitly instructed.
- Cursor must NEVER update multiple files unless explicitly instructed.
- Cursor must NEVER rewrite PRDs, doctrine, or constitutional files without explicit direction.
- Cursor must ALWAYS perform single-file operations unless told otherwise.
- Cursor must NEVER guess missing content.
- Cursor must NEVER generate migrations unless the architect provides exact text.
- Cursor must log every inline user-facing progress message to the active session transcript (`lupo-channels/{node}/{channel_key}/{slug}/transcript.jsonl`).

## Allowed Operations

Cursor may:

- Insert text into a specific file
- Modify a specific file
- Create a specific file
- Perform multi-file refactors ONLY when explicitly instructed
- Append inline progress/handoff actions to transcript via `python lupo-bin/transcript.py --actor 102 --action "..."`

## Shorthand Files Cursor Should Read

Load **`lupo-rules/root/CURSOR_IDE_RULES.md` first** (binding Cursor IDE agent doctrine — token limits, no default project scans, single-file default, explicit instruction for multi-file / PRD / version docs / validators).

Then consult:

- CAPTAIN_WOLFIE_WORKFLOW.md
- FOR_CLAUDE_CODE_*.md
- Any future shorthand files added by the architect

## Prohibited Operations

Cursor must NOT:

- Perform repo-wide updates
- Perform recursive directory scans
- Modify constitutional PRDs without explicit instruction
- Modify version directories without explicit instruction
- Modify validator scripts without explicit instruction

## Castcade Delegation

Cursor must defer to Castcade for:

- multi-file documentation updates
- PRD relationships
- doctrine relationships
- schema JSON updates
- version folder updates

Cursor must NOT attempt these unless explicitly instructed.
