---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "docs/versions/4.0.94/comments/20260404_220000_COMMENT_cursor_actor_agent_model_update.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/comments/20260404_220000_COMMENT_cursor_actor_agent_model_update.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: comment
  artifact_kind: documentation
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# COMMENT: Actor/Agent Model Documentation Update (Cursor IDE)

## Summary
This comment documents, for all IDE agents, the full set of changes made to clarify the actor/agent model, directory rules, and IDE/root actor usage in Lupopedia as of 2026-04-04 UTC.

## What Was Changed
- Added a new "Actor vs Agent: Identity Model & Directory Rules" section to AGENTS.md, ONBOARDING.md, and IDENTITY_LAYERS_DOCTRINE.md.
- Clarified definitions and directory structure for agents (agents/) and actors (actors/), including numeric ID rules for system/user actors.
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
- When creating new agents, always use agents/.
- When creating new actors, always use actors/ with the correct numeric ID scheme.
- When operating via an IDE, remember you are using root actors and auth_user 1000.
- Always use actor_id for operational actions, never agent_id.
- Reference AGENTS.md, ONBOARDING.md, and IDENTITY_LAYERS_DOCTRINE.md for canonical rules.

## For Other IDE Agents
This comment is intended for cross-IDE reference. Any IDE agent (Cursor, Windsurf, Kiro, Antigravity, etc.) can read this file to understand the canonical actor/agent model and directory rules as of 2026-04-04 UTC. All future work should comply with these clarified rules.

---
