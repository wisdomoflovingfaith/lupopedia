---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "lupo-channels/42/threads/2007/THREAD_INDEX.md"
  last_modified_utc: "20260322"
  channel_id: 42
  thread_id: 2007
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "index"
  artifact_kind: "thread_index"
  purpose: "Navigation index for Thread 2007 — lupo folder structure documentation task."
---

# Thread 2007 — Lupo Folder Structure Documentation

- **thread_status**: not-started
- **task_id**: task_document_lupo_structure_001
- **assigned_actor**: hephaestus
- **thread_name**: lupo_folder_structure_documentation
- **artifact_count**: 0
- **last_modified_utc**: 20260322_144500

## Purpose

Produce canonical documentation for all lupo-* directories in the Lupopedia repository.

Required documentation for each directory:
- Purpose and role in the system
- Contents overview
- How it interacts with other lupo-* directories

## Scope

Directories to document:
- lupo-actors: per-actor resource hub (apps, tools, docs, db-changes, api, needs)
- lupo-admin / lupo-admin_sections: admin interface files
- lupo-agents: AI agent configuration (numbered subdirectories with agent.json, capabilities, system_prompt)
- lupo-api: REST API surface
- lupo-app: OOP service classes (auth, Services)
- lupo-backups: backup storage
- lupo-bin: system binaries and CLI utilities
- lupo-cache: runtime cache
- lupo-channels: channel-scoped artifact storage (threads, broadcasts, directives, tasks)
- lupo-collections: collection content
- lupo-config: configuration files
- lupo-content: content storage
- lupo-database: schema, migrations, seeds, TOONs, CSV data
- lupo-docs: documentation hierarchy (doctrine, versions, actors)
- lupo-images: image assets
- lupo-includes: core runtime (modules, classes, functions, ui, semantic, lupo-agents, rest-api)
- lupo-install: installation wizard files
- lupo-logs: system logs
- lupo-meta: metadata storage
- lupo-prompts: prompt templates
- lupo-research: federation and external research corpora
- lupo-routes: routing definitions
- lupo-rules: doctrine and rules for actors and agents
- lupo-scripts: Python and shell utilities
- lupo-sessions: session storage
- lupo-skills: skill definitions
- lupo-templates: HTML/PHP templates
- lupo-tests: test suites (unit, integration, regression, adversarial)
- lupo-tmp: temporary workspace
- lupo-tools: tool definitions
- lupo-uploads: upload storage
- lupo-views: view files

## Output Artifact

Canonical docs file to be placed at: `lupo-docs/versions/4.0.85/lupo_structure.md` (or equivalent)

## Next Action

Assign to hephaestus. Pending execution in a new session.
