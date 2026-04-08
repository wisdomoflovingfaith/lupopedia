---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: "CURSOR.md"
  web_path: "http://www.lupopedia.com/lupopedia/CURSOR.md"
  last_modified_utc: "20260406132900"
  when_updated: "20260406132900"
  federation_node_id: 0
  channel_id: 42
  thread_id: "cursor-instructions"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: documentation
  artifact_kind: documentation
  purpose: "Cursor-specific rules to prevent excessive token usage and ensure safe updates"
  tags:
    - "cursor"
    - "ide_rules"
    - "token_limits"
    - "safe_updates"
    - "castcade_delegation"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-rules/root/CURSOR_IDE_RULES.md"
      type: references
      weight: 1.0
      reason: "Authoritative Cursor IDE agent doctrine"
    - to: "CAPTAIN_WOLFIE_WORKFLOW.md"
      type: references
      weight: 0.9
      reason: "Workflow documentation"
    - to: "lupo-rules/root/CASTCADE_IDE_RULES.md"
      type: references
      weight: 0.8
      reason: "Castcade delegation rules"
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
    - "Update token limits when needed"
    - "Maintain delegation rules"
    - "Keep aligned with CURSOR_IDE_RULES.md"
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

## Allowed Operations

Cursor may:

- Insert text into a specific file
- Modify a specific file
- Create a specific file
- Perform multi-file refactors ONLY when explicitly instructed

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
