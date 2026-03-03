---
flare.headers:
  flare.version: "1.0"
  file_path_from_root: "lupo-database/lupopedia/actors/actor_id/10000/QUICK_REFERENCE.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "documentation"
  purpose: "Quick reference for Captain Wolfie human (Actor 10000)"
  tags: ["actor-10000", "captain", "quick-reference"]
---

# Actor 10000 — Quick Reference (Captain Wolfie — Human)

**Actor ID:** 10000 | **Slug:** captain_wolfie | **Kind:** human (owner)

## Usage

- **Role:** Human owner and primary authority. Ultimate decision-making; supports and owns IDE agents (1001–1010+). Database and install operations that require human execution are assigned to 10000.
- **Contact / escalation:** See registry and aliases (e.g. email_slug in `lupo-database/lupopedia/actors/actor_id/aliases.csv`). Channel 42 directives are from Captain (10000) to IDE agents.
- **Integration:** Task assignee for critical/high tasks (e.g. db_reset_and_install, drop tables and run install); delegation chain often ends at 10000.

## Key references

| Topic | Location |
|-------|----------|
| Identity / relationships | `README.md` in this directory |
| Profile / config | `profile.json`, `config/`, `identity.json` |
| Registry / aliases | `lupo-database/lupopedia/actors/actor_id/registry.json`, `aliases.csv` |
| Supported IDE agents | README.md (1001–1010); `relationships.json` |

## Escalation

- **Blocking tasks:** Critical install/DB tasks are HUMAN-only (Captain 10000). Do not execute destructive DB operations without explicit directive.
- **Channel 42:** Directives from 10000 to Cursor (1003), Windsurf (1002), etc. Reply in Channel 42 when tasks are complete.

## Troubleshooting

- **Unclear ownership:** Check task `assigned_to` and delegation_chain; human-only tasks stay with 10000.
- **IDE agent coordination:** Captain (10000) supports multiple IDE agents; see AGENTS.md and CONTRIBUTING.md for multi-agent workflow.
