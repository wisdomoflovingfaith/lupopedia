---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/help_system.md"
  last_modified_utc: "20260306"
  system_version: "4.0.61"
  channel_id: 0
  actor_name: "cursor"
  purpose: "User-friendly help system implementation for v4.0.61"
  traits: ["documentation", "feature", "v4.0.61", "help"]
  tags: ["help", "documentation", "implementation"]
  lupo_agent: "cursor"
---

# User-Friendly Help System

## Overview

Version 4.0.61 adds a structured help system: **HelpRenderer** class, **lupo-docs/HELP.md** hub, topic-specific help, and context-aware suggestions.

## Components

### HelpRenderer (lupo-includes/classes/HelpRenderer.php)

- `showMainHelp()` — Categorized menu (Getting started, Actors, Channels, System, Advanced, Topic help, Exit codes)
- `showQuickRef()` — One-line command reference
- `showTopicHelp($topic)` — whoami, context, actors, workspace, flare, version, doctor, see, auth
- `getContextualSuggestions()` — Tips by session mode
- `openWebHelp()` — Open browser to web help URL
- `formatTopicHelp($title, $content)` — Version banner for topics

### Documentation Hub (lupo-docs/HELP.md)

- Getting started, Identity and actors, CLI commands, Workspace, Database, FLARE, Reports
- Links to lupo-docs/version.md, lupopedia_whoami_readme.md, auth.md, CLI.md

### CLI Integration

- `php lupo-bin/lupo.php help` — Main menu
- `php lupo-bin/lupo.php help --quick` — Quick reference
- `php lupo-bin/lupo.php help --web` — Open web help
- `php lupo-bin/lupo.php help <topic>` — Topic help

## Files

- `lupo-includes/classes/HelpRenderer.php`
- `lupo-docs/HELP.md`
- `lupo-docs/CLI.md`
- `lupo-bin/lupo.php` (help case)
- `web-help.php` (optional)

## Verification

- HelpRenderer produces formatted output
- All topics have help
- Contextual suggestions by session mode
- Documentation hub links to resources
