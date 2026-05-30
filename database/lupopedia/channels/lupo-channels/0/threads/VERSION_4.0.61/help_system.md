---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/help_system.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/help_system.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: null
  artifact_kind: null
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: null
  title: null
  summary: null
---

# User-Friendly Help System

## Overview

Version 4.0.61 adds a structured help system: **HelpRenderer** class, **docs/HELP.md** hub, topic-specific help, and context-aware suggestions.

## Components

### HelpRenderer (includes/classes/HelpRenderer.php)

- `showMainHelp()` — Categorized menu (Getting started, Actors, Channels, System, Advanced, Topic help, Exit codes)
- `showQuickRef()` — One-line command reference
- `showTopicHelp($topic)` — whoami, context, actors, workspace, flare, version, doctor, see, auth
- `getContextualSuggestions()` — Tips by session mode
- `openWebHelp()` — Open browser to web help URL
- `formatTopicHelp($title, $content)` — Version banner for topics

### Documentation Hub (docs/HELP.md)

- Getting started, Identity and actors, CLI commands, Workspace, Database, FLARE, Reports
- Links to docs/version.md, lupopedia_whoami_readme.md, auth.md, CLI.md

### CLI Integration

- `php bin/lupo.php help` — Main menu
- `php bin/lupo.php help --quick` — Quick reference
- `php bin/lupo.php help --web` — Open web help
- `php bin/lupo.php help <topic>` — Topic help

## Files

- `includes/classes/HelpRenderer.php`
- `docs/HELP.md`
- `docs/CLI.md`
- `bin/lupo.php` (help case)
- `web-help.php` (optional)

## Verification

- HelpRenderer produces formatted output
- All topics have help
- Contextual suggestions by session mode
- Documentation hub links to resources
