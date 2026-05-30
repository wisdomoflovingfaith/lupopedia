---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  file_path_from_root: "FOR_CLAUDE_CODE_2026_04_06.md"
  web_path: "http://www.lupopedia.com/lupopedia/FOR_CLAUDE_CODE_2026_04_06.md"
  questions_toon: null
  when_updated: "20260406181914"
  federation_node_id: 0
  channel_id: 42
  thread_id: "claude-code-sync-2026-04-06"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: documentation
  artifact_kind: agent_sync
  purpose: "Sync summary for Claude Code and other AI agents — April 6, 2026 doctrine, PRD, and system updates"
  tags:
    - "claude_code"
    - "sync"
    - "2026-04-06"
lupopedia.footer:
  last_verified: "20260406181914"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
---

# file: FOR_CLAUDE_CODE_2026_04_06 — delegation: cursor:root — web_path: [http://www.lupopedia.com/lupopedia/FOR_CLAUDE_CODE_2026_04_06.md](http://www.lupopedia.com/lupopedia/FOR_CLAUDE_CODE_2026_04_06.md)

# FOR_CLAUDE_CODE_2026_04_06

## Overview

- This file summarizes all major updates made on April 6, 2026.
- Cursor updated approximately 97 files during doctrine expansion and PRD alignment.
- These changes affect departments, actors, learning boundaries, installation behavior, and the README.

## Department System Updates

- Department 0 doctrine expanded:
  - Dependency-first reasoning (no timeline-based planning).
  - HPC-style parallel cognition requirement.
  - Only Department 0 may train core/system actors.
- Department 1 doctrine added:
  - Represents the domain-root installation context.
  - Handles semantic monitoring widget, collections, and live chat integration.
  - Manages domain-level embedding of Lupopedia.
- Departments 2+:
  - Created automatically during installation.
  - Derived from Crafty Syntax import.
  - May be created by Department 0 or 1 auth_users.

## Actor Learning Boundaries

- Core/system actors (Wolfie, Lilith, Kiros, Thoth) may ONLY learn from Department 0 auth_users.
- Non-core actors learn only from auth_users in their own department.
- Cross-department learning is prohibited unless explicitly defined in a PRD.
- Actor creation rules updated:
  - Actors created during installation or by pairing an agent with a department.
  - Auth_users may only select actors in their department.

## Installation & Crafty Syntax Import

- Lupopedia is always installed in a subdirectory (e.g., example.com/lupopedia).
- Softaculous installer upgrades Crafty Syntax 3.7.5 into Lupopedia.
- Crafty Syntax operators and departments are imported as Departments 2+.
- Semantic monitoring widget, collections system, and floating nav bars documented.

## README Update

- README updated to explain:
  - Why Lupopedia intentionally rejects modern frameworks, ORMs, and vibe-driven defaults.
  - Why explicit schemas, fallback logic, and deterministic engineering are required.
  - Links added to doctrine files for clarity.

## Architect Background Reference File

- New file created: lupo-docs/reference/architect_background.md
- Contains a factual, concise timeline of the architect’s history:
  - University of Wyoming CS (1997–2000)
  - HPC internships at MHPCC (1997–1998)
  - CRM work for City/County of Honolulu (1999)
  - Crafty Syntax origins
  - 2014 personal loss and 12-year hiatus (factual only)
  - Return in 2025 and creation of Lupopedia

## What Claude Code Should Do Next

- Review updated PRDs:
  - PRD 00, 01, 05, 15, 17, 28, 33
- Review updated README.md
- Review new architect_background.md
- Ensure all future work aligns with:
  - Department 0 doctrine
  - Actor learning boundaries
  - Installation model
  - Semantic monitoring and collections system
  - Constitutional engineering rules

## Versioning Doctrine Summary (Critical for All Agents)

- Lupopedia starts at 4.0.x because it is the successor to Crafty Syntax 3.7.5.
- There are NO Lupopedia→Lupopedia upgrades before 4.1.0.
- 4.0.x is the installer-only era.
- 4.1.0 is the first upgradeable version.
- The ONLY valid upgrade path is:

      Crafty Syntax 3.7.5 → Lupopedia 4.0.x

- The upgrade imports all `livehelp_` tables into `lupo_` tables.
- This import captures every edge in the installation.
- Auto-installer acceptance is required before 4.1.0 can exist.
- After 4.1.0, nodes become discoverable and federation begins.

## Cursor IDE boundaries (for Claude Code)

Claude Code should treat **`lupo-rules/root/CURSOR_IDE_RULES.md`** as the **authoritative** Cursor IDE agent doctrine (same repo; different tool). Summary so boundaries stay aligned:

- **Default:** single-file work only; **no** whole-project scan unless the architect explicitly instructs it.
- **Explicit instruction required** for: multi-file refactors, PRD/doctrine/constitutional edits, version-directory edits, validator script edits, migration SQL (architect-provided exact text only).
- **Never:** recursive scans, repo-wide “guess” refactors, wholesale PRD rewrites, invented migrations, “modernizing” proven code without direction, suggesting new npm/Composer runtime deps.
- **Context:** use shorthand files (`CAPTAIN_WOLFIE_WORKFLOW.md`, `FOR_CLAUDE_CODE_*.md`, `root CURSOR.md`, `lupo-rules/root/README.md`) — **ask** the architect instead of scanning when something is not listed.
- **Cursor root shorthand:** `CURSOR.md` tells Cursor to load **`CURSOR_IDE_RULES.md` first**; Claude Code can read the same doctrine file to avoid proposing work that violates Cursor’s rules.

## Castcade Summary (For Claude Code)

- Castcade is the multi-file orchestrator.
- It updates PRDs, doctrine, validators, schema JSON, and version folders.
- It does NOT modify the installer.
- It generates SQL only from JSON schema changes.
- It maintains deterministic, reversible changes.
- Cursor defers to Castcade for multi-file operations.
