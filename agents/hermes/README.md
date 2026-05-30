# Hermes Agent Directory Structure

This agent directory follows the canonical Lupopedia agent structure, with additional folders for modularity and modern development practices.

## Required Files
- `agent.json` — Core agent metadata
- `capabilities.json` — Agent capabilities and skills
- `properties.json` — Agent properties and constraints
- `system_prompt.txt` — System prompt and operational guidance
- `versions/` — Version history (optional)

## Modern Modular Folders
- `api/` — API endpoints, integration logic, or stubs
- `assets/` — Images, icons, or static files
- `components/` — UI or logic components (if agent has a UI or modular logic)
- `context/` — Context providers, shared state, or context logic
- `data/` — Static data, fixtures, or data schemas
- `hooks/` — Reusable logic hooks (for JS/TS or PHP modularity)
- `includes/` — Shared includes, partials, or helper files
- `pages/` — Page-level logic or UI (if agent exposes web UI)
- `tools/` — Tool definitions, scripts, or agent-specific utilities
- `utils/` — Utility functions, helpers, or shared logic

## Doctrine
- All agent directories must be ASCII, lowercase, and contain no spaces or symlinks.
- All files must be valid JSON or UTF-8 text (except approved assets).
- The agent directory is the source of truth for agent configuration; the database is runtime-only.

## See Also
- [PRD: Canonical Agent Definition Model](../../docs/prd/PRD_AGENT_DEFINITION_MODEL.md)
- [PRD: Agents, Faucets, Tool Calls](../../docs/prd/07_agents_faucets.md)
- `_TEMPLATE/` for canonical file templates.
