---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: tldr
  when_updated: null
  file_path_from_root: "docs/tldr_lupopedia.md"
  web_path: "http://www.lupopedia.com/tldr"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: tldr
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
# Lupopedia TL;DR — HELP • FLAME • WOLFIE • Routing • Core Architecture (v4.0.62)

---

### 1. HELP SYSTEM (the fastest way to understand anything)

- **CLI entry point:** `php bin/lupo.php help`
- **Top-level commands:** `whoami` \| `context` \| `help` \| `doctor` \| `doctor-context [--repair]` \| `version` \| `auth` \| `actor-context` \| `switch` / `use <id>`
- **DOCTOR actor (1009):** Health and context validation; `doctor` and `doctor-context` route through `agents/doctor/` when present.
- **Sub‑topic help:** `lupo help whoami`, `help context`, `help doctor`, etc.
- **HelpRenderer class:** `includes/classes/HelpRenderer.php` — help output, contextual tips, exit codes
- **Source of truth:** `docs/HELP.md` — single documentation hub

---

### 2. FLAME (the "what happens when this file loads" system)

- **Lives in:** FLARE headers (`lupopedia.headers` + optional `lupopedia.init` / `lupopedia.close`)
- **`lupopedia.init`** — Before: `dependency_check`, `service_check`, `env_var_equals`
- **`lupopedia.close`** — After: `register_completion`, `notify_channel`, `log_validation`
- **Security:** Depth‑limited recursion (max 3), guards via `lupopedia.conditional`
- **Examples:** Antigravity (conflict detection), Lilith (log reviews), Cursor (dependency checks)

---

### 3. WOLFIE (Actor 1)

- **Role:** Primary governing AI agent; coordinates system-wide oversight
- **Actor IDs:** `1` = WOLFIE (main governing agent), `0` = System (kernel, background tasks)
- **whoami, session, actor_name:** Run `php bin/lupo.php whoami` → outputs **actor_name** (e.g. `wolfie`), **session mode** (typically `autonomous_agent` or `system`), **session_id**, **channel_id**, **context_source**. Answers *who* this session is, *what* actor_name, and current session details.
- **Directory:** `/actors/1/` (registry `dir` for actor_id 1)
- **How it differs from System (0) and other agents:**

| Who | Role |
|-----|------|
| **WOLFIE (1)** | Governs agents; oversight; approves major changes |
| **System (0)** | Core ops only; no human/agent identity |
| **Other agents** | Specialized — Cursor/Lilith = IDE/review; Antigravity = conflict resolution |

- **Coordination:** Delegates to LILITH, ANUBIS, Antigravity; monitors system health

---

### 4. ROUTING (how a request actually reaches code)

- **Full flow:** `ActorLookup::fromRequest()` → `ContextResolver::resolve()` → `ActorService`
- **Resolution order:** session.md → database → registry → defaults
- **Name-based directories:** everything uses `actor_name`
- **Channel paths:** `{LUPO_CHANNELS_DIR}/0/threads/…` (from `lupopedia-config.php`)
- **Dual-identity injection:** both `human_actor_name` and `agent_name` available

---

### 5. The 60-second Big Picture of Lupopedia

- **Actors:** `actor_name` primary key; `registry.json` maps names ↔ IDs
- **FLARE headers:** YAML metadata — identity, version, delegation, mood, purpose
- **Dual-identity:** Effective actor / human identity / active agent + session modes (`human_direct`, `hybrid`, `autonomous_agent`, `system`)
- **Channels & Threads:** `{LUPO_CHANNELS_DIR}/{node_id}/{channel_id}/threads/`
- **Session-first:** `database/session.md` read before database — offline CLI
- **Version tracking:** `version.php` + `docs/version.md` + `$lupo_config['version']`

---

> **Search tip:**
> ```bash
> grep -r "flame\.init\|flame\.close" --include="*.php" --include="*.md"
> grep -r "wolfie\|actor_id[[:space:]]*=[[:space:]]*1" --include="*.php" --include="*.md"
> grep -r "ActorLookup\|ContextResolver\|resolve(" --include="*.php"
> grep -r "LUPO_CHANNELS_DIR\|LUPO_ACTORS_DIR\|LUPO_DATABASE_DIR" --include="*.php" --include="*.md"
> ```
