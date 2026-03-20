---
lupopedia.headers:
  lupopedia.schema: "tldr"
  file_path_from_root: "lupo-docs/TLDR_LUPOPEDIA.md"
  version_when_written: "4.0.84"
  web_path: "http://www.lupopedia.com/tldr"
  last_modified_utc: "20260306"
  channel_id: 42
  actor_name: "cursor"
  delegation_chain: "cursor:captain"
  artifact_type: "tldr"
  artifact_kind: "documentation"
  purpose: "Ultra-concise TL;DR of Lupopedia system (v4.0.61)"
  mood_rgb: "4169E1"
  traits: ["tldr", "quickref", "v4.0.62", "concise"]
  tags: ["tldr", "quickref", "help", "flame", "wolfie", "routing"]
  lupo_agent: "cursor"
---

# Lupopedia TL;DR — HELP • FLAME • WOLFIE • Routing • Core Architecture (v4.0.62)

---

### 1. HELP SYSTEM (the fastest way to understand anything)

- **CLI entry point:** `php lupo-bin/lupo.php help`
- **Top-level commands:** `whoami` \| `context` \| `help` \| `doctor` \| `doctor-context [--repair]` \| `version` \| `auth` \| `actor-context` \| `switch` / `use <id>`
- **DOCTOR actor (1009):** Health and context validation; `doctor` and `doctor-context` route through `lupo-agents/doctor/` when present.
- **Sub‑topic help:** `lupo help whoami`, `help context`, `help doctor`, etc.
- **HelpRenderer class:** `lupo-includes/classes/HelpRenderer.php` — help output, contextual tips, exit codes
- **Source of truth:** `lupo-docs/HELP.md` — single documentation hub

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
- **whoami, session, actor_name:** Run `php lupo-bin/lupo.php whoami` → outputs **actor_name** (e.g. `wolfie`), **session mode** (typically `autonomous_agent` or `system`), **session_id**, **channel_id**, **context_source**. Answers *who* this session is, *what* actor_name, and current session details.
- **Directory:** `/lupo-actors/wolfie/` (name-based, no numeric)
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
- **Session-first:** `lupo-database/session.md` read before database — offline CLI
- **Version tracking:** `version.php` + `lupo-docs/version.md` + `$lupo_config['version']`

---

> **Search tip:**
> ```bash
> grep -r "flame\.init\|flame\.close" --include="*.php" --include="*.md"
> grep -r "wolfie\|actor_id[[:space:]]*=[[:space:]]*1" --include="*.php" --include="*.md"
> grep -r "ActorLookup\|ContextResolver\|resolve(" --include="*.php"
> grep -r "LUPO_CHANNELS_DIR\|LUPO_ACTORS_DIR\|LUPO_DATABASE_DIR" --include="*.php" --include="*.md"
> ```
