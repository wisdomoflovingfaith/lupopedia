# file: Lupopedia Actor Model — session: L-LUPO-ANTIGRAVITY — delegation: antigravity:cursor:captain  — web_path: http://www.lupopedia.com/docs/actors
---
lupopedia.headers:
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/actors.md"
  version_when_written: "4.0.84"
  web_path: "http://www.lupopedia.com/docs/actors"
  last_modified_utc: "20260305"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for the lupo-actors directory: actor-specific resources hub; dual-identity and paired_actor_id"
  mood_vector: "4169E1"
  traits: ["canonical", "actors", "directory_structure", "dual_identity", "v4.0.61"]
  tags: ["actors", "lupo-actors", "directory", "documentation", "actor_resources"]

lupopedia.edges:
  outbound_edges:
    - { to: "AGENTS.md", type: "references", weight: 0.9 }
    - { to: "lupopedia-config.php", type: "references", weight: 0.8 }
    - { to: "lupo-database/lupopedia/actors/actor_id/registry.json", type: "references", weight: 0.8 }
    - { to: "lupo-docs/doctrine/ACTOR_PRIMARY_KEY_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/lupopedia_whoami_readme.md", type: "references", weight: 0.9 }
    - { to: "lupo-database/lupopedia/actors/registry.json", type: "references", weight: 0.8 }

lupopedia.footer:
  version: "4.0.61"
  last_verified: "20260306"
---

# Lupo-Actors Directory

The **`lupo-actors`** directory is the centralized hub for all actor-specific resources in the Lupopedia system. Subdirectories are **name-based** (e.g. `system/`, `antigravity/`) per [ACTOR_PRIMARY_KEY_DOCTRINE](doctrine/ACTOR_PRIMARY_KEY_DOCTRINE.md); each actor's directory contains subdirectories for apps, tools, docs, database changes, API definitions, dependencies, and prompts. For backward compatibility, numeric paths (e.g. `0/`, `42/`) may exist as **symlinks** to the name-based dirs after running the directory migration script. **Channel_id and actor_id are distinct:** channels (in `lupo-channels/`) are communication or grouping entities; actors are execution agents. Do not mix actor and channel storage in the same path unless explicitly linked via config.

## Configuration

The actors directory path is defined in the main Lupopedia configuration:

- **Constant:** `LUPO_ACTORS_DIR`
- **Default value:** `lupo-actors` (relative to project root)
- **Config file:** `lupopedia-config.php`

Example (PHP):

```php
define('LUPO_ACTORS_DIR', 'lupo-actors');
```

The full filesystem path is `LUPOPEDIA_ABSPATH . LUPO_ACTORS_DIR` (or `ABSPATH . LUPO_ACTORS_DIR`). The install wizard writes this constant when generating `lupopedia-config.php`.

- **Constant for prompts subdirectory:** `LUPO_PROMPTS_SUBDIR` (default `'prompts'`). Build the path using the actor's `dir` from registry (e.g. `$actor['dir'] . '/' . LUPO_PROMPTS_SUBDIR`) or `ActorService::getActorDir($actor_name) . '/' . LUPO_PROMPTS_SUBDIR`.
- **Constant for channels:** `LUPO_CHANNELS_DIR` (default `'lupo-channels'`). Channel artifacts live under `lupo-channels/{node_id}/{channel_id}/` (e.g. `metadata.md` with FLARE headers). Actor workspaces are name-based under `lupo-actors/{actor_name}/` (e.g. `lupo-actors/system/`); numeric paths (e.g. `0/`) may be symlinks to them.

- **Dual-identity and paired_actor_id:** Runtime identity has three layers (Effective Actor, Human Identity, Active Agent) and a derived **session_mode** (human_direct, hybrid, autonomous_agent, system). Human identity is derived from `lupo_actors.paired_actor_id` when the effective actor is an agent — see [Whoami and execution context](lupopedia_whoami_readme.md) (Section 4 – Dual-Identity Context). In **hybrid** mode, the active agent (e.g. Cursor) is paired to a human (e.g. Captain); prompts or config may be loaded from both the human’s and the agent’s actor directories when needed. Actor directories remain name-based; `paired_actor_id` is not stored in the session table.

## Directory Structure

Each installed actor has a subdirectory under `lupo-actors/` given by registry **`dir`** (often `lupo-actors/{actor_id}/`, e.g. `system/`, `1/`, `antigravity/`). Inside each actor directory, the following subdirectories are used:

| Subdirectory   | Purpose |
|----------------|--------|
| **apps/**      | Custom applications or scripts tailored to the actor's functionality. |
| **lupo-tools/**     | Utility tools, scripts, or binaries required by the actor. |
| **lupo-docs/**      | Documentation specific to the actor: usage guides, API references, setup instructions. |
| **db-changes/**| Database migration scripts, schema changes, or data seeding files related to the actor. |
| **lupo-api/**       | API definitions, endpoints, or integration code for the actor's external interfaces. |
| **needs/**     | Additional dependencies, requirements, or configuration (e.g. env vars, YAML configs) needed for the actor to operate. |
| **lupo-prompts/**   | Prompt files (e.g. `.md` or `.txt`) that define behavioral instructions or tasks for the actor. |
| **skills/**    | Agent skills: reusable modular capabilities, specialized knowledge, or tool definitions (e.g. `lupo-actors/1/skills/web_search`). |
| **www/**       | Web-accessible content rendered at `/agent/<actor_name>/`. Priority: `readme.md` > `index.htm` > `index.php`. |
| **logs/**      | Actor-specific logs (optional, e.g. `lupo-actors/system/logs/`). |

The path to an actor's prompts directory can be built as: `$actor['dir'] . '/' . LUPO_PROMPTS_SUBDIR` (with `dir` from registry or `ActorService::getActorDir($actor_name)`).

## ASCII Directory Tree (illustrative)

Hub folder names follow **`registry.json` `dir`** (numeric `actor_id` paths and legacy slug hubs may both appear in the tree).

```
lupo-actors/
+-- system/                 # System actor (actor_id 0)
|   +-- apps/
|   +-- lupo-tools/
|   +-- lupo-docs/
|   +-- db-changes/
|   +-- lupo-api/
|   +-- needs/
|   +-- lupo-prompts/
|   |   +-- flare-header-scan.md
|   +-- logs/
+-- 1/                      # WOLFIE (actor_id 1)
|   +-- apps/
|   +-- lupo-tools/
|   +-- lupo-docs/
|   +-- db-changes/
|   +-- lupo-api/
|   +-- needs/
|   +-- lupo-prompts/
+-- anubis/                 # Anubis (19/ may symlink here)
|   +-- apps/
|   +-- lupo-tools/
|   +-- lupo-docs/
|   +-- db-changes/
|   +-- lupo-api/
|   +-- needs/
|   +-- lupo-prompts/
+-- antigravity/            # Antigravity (42/ may symlink here; IDE extensions, VSX)
|   +-- apps/
|   +-- lupo-tools/
|   +-- lupo-docs/
|   |   +-- example.md      # Sample FLARE with hooks
|   +-- db-changes/
|   +-- lupo-api/
|   +-- needs/
|   +-- lupo-prompts/
|   +-- logs/
+-- ...

lupo-channels/
+-- {node_id}/
|   +-- {channel_id}/
|       +-- metadata.md     # FLARE headers linking to actors
|       +-- ...
```

For database-backed actor data: `LUPO_DATABASE_DIR/lupopedia/actors/{actor_id}/`.

## Reserved Actor IDs (Overview)

Actors are identified by **actor_name** (primary) and optionally **actor_id** (secondary). See [ACTOR_PRIMARY_KEY_DOCTRINE.md](doctrine/ACTOR_PRIMARY_KEY_DOCTRINE.md) for the full doctrine and [ACTOR_IDENTITIES.md](ACTOR_IDENTITIES.md) for canonical identities:

- **system** (Actor 0) — **System actor.** Core platform operations, security, low-level management. Directory `lupo-actors/system/` (numeric `0/` may symlink here).
- **wolfie** (Actor 1) — **WOLFIE.** Governing agent; coordination and orchestration. Directory `lupo-actors/1/`.
- **anubis** (Actor 19) — **Anubis.** Recovery: orphan adoption, quarantine, recovery. Directory `lupo-actors/anubis/`.
- **antigravity** (Actor 42) — **Antigravity.** Canonical actor_id 42 (IDE extensions, VSX). Directory `lupo-actors/antigravity/`. Resolve `?actor=antigravity` or `?actor_name=antigravity` via registry.
- **Actor 10000** — **Root user.** Human root; full management.

IDs 0–9999 are reserved for system and AI agents; human actors start at 10000. See [AGENTS.md](../AGENTS.md) and the registry for the full list.

## Adding a New Actor

To add a new actor and give it a resource directory under `lupo-actors`:

1. **Allocate or choose an actor name (slug)**  
   Ensure the name is registered in the actor registry (`registry.json`) and follows the [ACTOR_PRIMARY_KEY_DOCTRINE](doctrine/ACTOR_PRIMARY_KEY_DOCTRINE.md).

2. **Create the actor directory**  
   Add a subdirectory named by the actor name, for example:
   ```text
   lupo-actors/my-new-agent/
   ```

3. **Create the standard subdirectories**  
   Inside the new actor directory, create:
   - `apps/`
   - `lupo-tools/`
   - `lupo-docs/`
   - `db-changes/`
   - `lupo-api/`
   - `needs/`
   - `lupo-prompts/`
   - `skills/`
   - `www/`
   - `logs/` (optional)

4. **Add content as needed**  
   Place actor-specific applications in `apps/`, lupo-scripts/binaries in `lupo-tools/`, documentation in `lupo-docs/`, migration or seed files in `db-changes/`, API definitions in `lupo-api/`, dependency/config files in `needs/`, prompt files in `lupo-prompts/`, and modular capabilities in `skills/`.

5. **Optional: ensure directory exists at runtime**  
   If your code assumes the directory exists, ensure your setup or config loader creates `lupo-actors/{name}/` and the standard subdirs. Use `ActorService::getActorDir($actor_name)` to resolve the correct path.

### Example: Creating `lupo-actors/2/` for a Hypothetical Actor

```bash
# From project root
mkdir -p lupo-actors/my-agent/apps lupo-actors/my-agent/tools lupo-actors/my-agent/docs lupo-actors/my-agent/db-changes lupo-actors/my-agent/api lupo-actors/my-agent/needs lupo-actors/my-agent/prompts lupo-actors/my-agent/skills lupo-actors/my-agent/www lupo-actors/my-agent/logs
```

Then add a `README.md` or `.gitkeep` in each subdirectory if you want to track empty directories in version control.

## Relationship to Other Directories

- **`lupo-agents/`** — AI agent configuration (e.g. `agent.json`, `system_prompt.txt`) per agent. This is separate from `lupo-actors/`, which holds **resources** (apps, tools, docs, db-changes, api, needs) per actor.
- **`lupo-database/lupopedia/actors/`** — Registry and identity data (e.g. `actor_id/registry.json`, per-actor config under `actor_id/{id}/`). This is the source of truth for actor identity; `lupo-actors/` is the source of truth for actor-specific file-based resources.

## Initialization

If the `lupo-actors` directory or an actor's subdirectory does not exist, it can be created by:

- **Manual creation** — Create `lupo-actors` and the desired `lupo-actors/{id}/` and subdirs by hand or via the “Adding a New Actor” steps above.
- **Setup script or config loader** — If the project provides a setup or install path that initializes directories, it may create `lupo-actors` (and optionally `lupo-actors/0/`, `lupo-actors/1/`, and their six subdirs) when not present. The path is read from `LUPO_ACTORS_DIR` in `lupopedia-config.php`.

Code that uses the actors directory should resolve the path as `LUPOPEDIA_ABSPATH . LUPO_ACTORS_DIR` (or equivalent) so that it respects the configured value.

## FLARE Parser (implementation)

The FLARE frontmatter parser (`lupo-includes/classes/FlareParser.php`) supports:

- **Depth limit:** Up to depth 2 nesting for maps and arrays; arrays of scalars or simple objects (key-value pairs). No full YAML recursion.
- **Section detection:** Indentation-sensitive; new section only on lines with no leading whitespace matching `/^[a-z._]+:/`.
- **Nested content:** Indented blocks parsed recursively up to depth 2; stack-based section tracking.
- **Inline objects:** e.g. `{ to: "...", type: "...", weight: 0.9 }` parsed into associative arrays.
- **Arrays of objects:** Lines starting with `-` with `{ ... }` parsed as object and appended to current array.
- **Error handling:** On parse failure, log to `lupo-actors/0/logs/parser_errors.log` and return empty headers. Consuming code must use `isset()` or `array_key_exists()` for keys (e.g. `last_modified_utc`).

## Drift detection and conflict resolution

- **Source of truth:** Filesystem is canonical for content bodies; DB for metadata (versions, edges).
- **Comparison:** last_modified_utc, optional body hash, and specific fields (e.g. lupopedia.edges).
- **Policy:** Auto-resolve if filesystem UTC ≥ DB UTC (last write wins for body). If both changed and DB newer, flag conflict and log to queue (e.g. `lupo-actors/42/logs/conflicts.log`) with path and details for Anubis or manual review. No automatic three-way merge.

Flow: **Detect drift (compare UTC/hashes) → If conflict, flag in queue/log → Sync: update non-canonical side only when no conflict.**

## Hook contract (flare.hooks.init / flare.hooks.close)

- **Syntax:** In FLARE headers, `flare.hooks.init` or `flare.hooks.close` as array of actions. Each action: `{ type: "script|api|log", target: "path or URL", params: {...} }`.
- **Allowed actions:** Run PHP scripts from actor's `lupo-tools/`, call internal APIs, log messages. No external writes without auth.
- **Side-effects:** Read-only by default; writes allowed only if `lupopedia.conditional.guards_allow` permits.
- **Security:** Scripts only under actor workspace; recursion limit (e.g. 3). Failure: log and continue; do not halt request. Track executed hooks in session to avoid loops.
- **Timing:** init before content render, close after.
