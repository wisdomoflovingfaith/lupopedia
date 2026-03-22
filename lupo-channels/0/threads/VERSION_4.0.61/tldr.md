---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "tldr"
  file_path_from_root: "lupo-channels/0/threads/VERSION_4.0.61/tldr.md"
  web_path: "http://www.lupopedia.com/threads/VERSION_4.0.61/tldr"
  last_modified_utc: "20260306"
  system_version: "4.0.61"
  channel_id: 0
  actor_name: "cursor"
  delegation_chain: "cursor:captain"
  artifact_type: "tldr"
  artifact_kind: "documentation"
  purpose: "Thread-local copy of Lupopedia TL;DR (v4.0.61)"
  mood_rgb: "4169E1"
  traits: ["tldr", "quickref", "v4.0.61", "thread_copy", "config_path"]
  tags: ["tldr", "quickref", "help", "flame", "wolfie", "routing", "thread"]
  lupo_agent: "cursor"
---

# Lupopedia TL;DR — HELP • FLAME • WOLFIE • Routing • Core Architecture (v4.0.61)

**Thread location:** `{LUPO_CHANNELS_DIR}/0/threads/VERSION_4.0.61/tldr.md`  
**Canonical original:** [lupo-docs/TLDR_LUPOPEDIA.md](../../../../lupo-docs/TLDR_LUPOPEDIA.md)

---

### 1. HELP SYSTEM (the fastest way to understand anything)

- **CLI entry point:** `php lupo-bin/lupo.php help`
- **Top-level commands:** `whoami`, `context`, `help`, `doctor`, `version`, `auth`, `actor-context`, `switch`
- **Sub‑topic help:** `lupo help whoami`, `help context`, `help doctor`
- **HelpRenderer class:** `lupo-includes/classes/HelpRenderer.php`
- **Source of truth:** `lupo-docs/HELP.md`

---

### 2. FLAME (the "what happens when this file loads" system)

- **Lives in:** FLARE headers (`lupopedia.headers` + optional `lupopedia.init` / `lupopedia.close`)
- **`lupopedia.init`** — Before: `dependency_check`, `service_check`, `env_var_equals`
- **`lupopedia.close`** — After: `register_completion`, `notify_channel`, `log_validation`
- **Security:** depth‑limited recursion (max 3), guards via `lupopedia.conditional`
- **Examples:** Antigravity (conflict detection), Lilith (log reviews), Cursor (dependency checks)

---

### 3. WOLFIE (Actor 1)

- **Role:** Primary governing AI agent; coordinates system-wide oversight
- **Actor IDs:** `1` = WOLFIE, `0` = System
- **whoami, session, actor_name:** `php lupo-bin/lupo.php whoami` → **actor_name** (e.g. `wolfie`), **session mode** (typically `autonomous_agent` or `system`), **session_id**, **channel_id**, **context_source** — who this session is, what actor_name, current session details
- **Directory:** `/lupo-actors/wolfie/` (name-based)
- **How it differs from System (0) and other agents:**

| Who | Role |
|-----|------|
| **WOLFIE (1)** | Governs agents; oversight; approves major changes |
| **System (0)** | Core ops only; no human/agent identity |
| **Other agents** | Specialized — Cursor/Lilith = IDE/review; Antigravity = conflict resolution |

- **Coordination:** Delegates to LILITH, ANUBIS, Antigravity; monitors health

---

### 4. ROUTING (how a request actually reaches code)

- **Full flow:** `ActorLookup::fromRequest()` → `ContextResolver::resolve()` → `ActorService`
- **Resolution order:** session.md → database → registry → defaults
- **Name-based directories:** everything uses `actor_name`
- **Channel paths:** `{LUPO_CHANNELS_DIR}/0/threads/…` (from `lupopedia-config.php`)
- **Dual-identity injection:** both `human_actor_name` and `agent_name` available

---

### 5. The 60-second Big Picture

- **Actors:** `actor_name` primary key; `registry.json` maps names ↔ IDs
- **FLARE headers:** YAML metadata — identity, version, delegation
- **Dual-identity:** three layers + session modes (`human_direct`, `hybrid`, `autonomous_agent`, `system`)
- **Channels & Threads:** `{LUPO_CHANNELS_DIR}/{node_id}/{channel_id}/threads/`
- **Session-first:** `lupo-database/session.md` read before database
- **Version tracking:** `version.php` + `lupo-docs/version.md` + `$lupo_config['version']`

---

> **Search tip:**
> ```bash
> grep -r "flame\.init\|flame\.close" --include="*.php" --include="*.md"
> grep -r "wolfie\|actor_id[[:space:]]*=[[:space:]]*1" --include="*.php" --include="*.md"
> grep -r "ActorLookup\|ContextResolver\|resolve(" --include="*.php"
> grep -r "LUPO_CHANNELS_DIR\|LUPO_ACTORS_DIR\|LUPO_DATABASE_DIR" --include="*.php" --include="*.md"
> ```
