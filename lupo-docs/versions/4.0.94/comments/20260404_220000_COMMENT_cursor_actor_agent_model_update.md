---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/versions/4.0.94/comments/20260404_220000_COMMENT_cursor_actor_agent_model_update.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/comments/20260404_220000_COMMENT_cursor_actor_agent_model_update.md
  last_modified_utc: '20260404220000'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: comment
  artifact_kind: documentation
  purpose: Full explanation of actor/agent model documentation update for cross-IDE reference
---

# COMMENT: Actor/Agent Model Documentation Update (Cursor IDE)

## Summary
This comment documents, for all IDE agents, the full set of changes made to clarify the actor/agent model, directory rules, and IDE/root actor usage in Lupopedia as of 2026-04-04 UTC.

## What Was Changed
- Added a new "Actor vs Agent: Identity Model & Directory Rules" section to AGENTS.md, ONBOARDING.md, and IDENTITY_LAYERS_DOCTRINE.md.
- Clarified definitions and directory structure for agents (lupo-agents/) and actors (lupo-actors/), including numeric ID rules for system/user actors.
- Explained that system actors (IDs 0–2025) are created for every agent, always in department 0 (root), used by the root user.
- Explained that user actors use deterministic BIGINT IDs (YYYYMMDDHHIISS + 0000–9999), always >2025, and are department/user-scoped.
- Added a section on IDE usage: IDEs (Cursor, VS Code, Windsurf, etc.) always operate as root department users, using root actors, with auth_user 1000 (captain wolfie).
- Clarified that agents are AI logic/config only, never used directly for orchestration or permissions.
- Clarified that actors are the only valid operational identity for orchestration, permissions, and web admin.
- Clarified that faucets (IDEs, APIs) are not actors or agents, but execution surfaces.
- Provided concrete examples and implementation guidance to prevent future confusion.

## Why This Was Done
- To eliminate confusion between agents and actors for all contributors and IDE agents.
- To enforce correct directory and identity usage for system and user actors.
- To ensure all IDEs and agents follow the canonical model for orchestration, permissions, and documentation.

## How to Use This Information
- When creating new agents, always use lupo-agents/.
- When creating new actors, always use lupo-actors/ with the correct numeric ID scheme.
- When operating via an IDE, remember you are using root actors and auth_user 1000.
- Always use actor_id for operational actions, never agent_id.
- Reference AGENTS.md, ONBOARDING.md, and IDENTITY_LAYERS_DOCTRINE.md for canonical rules.

## For Other IDE Agents
This comment is intended for cross-IDE reference. Any IDE agent (Cursor, Windsurf, Kiro, Antigravity, etc.) can read this file to understand the canonical actor/agent model and directory rules as of 2026-04-04 UTC. All future work should comply with these clarified rules.

---
