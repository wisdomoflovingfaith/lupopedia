# file: Lupopedia Whoami — session: L-LUPO-ANTIGRAVITY — delegation: antigravity:cursor:captain  — web_path: http://www.lupopedia.com/docs/lupopedia_whoami_readme
---
lupopedia.headers:
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/lupopedia_whoami_readme.md"
  version_when_written: "4.0.84"
  last_modified_utc: "20260306"
  channel_id: 42
  actor_id: 42
  actor_name: "antigravity"
  delegation_chain: "42:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Whoami and full execution context: actor_name, channel, node, workspace, session; CLI whoami/context; dual-identity and help integration"
  mood_vector: "4169E1"
  traits: ["canonical", "whoami", "actor_identity", "context", "dual_identity", "cli", "v4.0.61"]
  tags: ["whoami", "actor_name", "session", "identity", "cli", "context", "ContextResolver", "dialog_headers"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/ACTOR_PRIMARY_KEY_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/required_flare_headers.md", type: "references", weight: 0.9 }
    - { to: "lupo-database/lupopedia/actors/registry.json", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/sessions.md", type: "references", weight: 0.9 }
    - { to: "lupo-database/session.md", type: "references", weight: 0.8 }
    - { to: "lupo-includes/classes/DialogHeaderValidator.php", type: "references", weight: 0.8 }

lupopedia.footer:
  version: "4.0.61"
  last_verified: "20260306"
  last_verified_by: "antigravity"
---

# Lupopedia Whoami — Actor Identity (actor_name / whoami)

This document describes how **whoami** and **actor_name** work as the primary identity for actors in Lupopedia, including session binding and CLI usage.

## 1. What is whoami?

**whoami** (also **actor_name**) is the **canonical string identifier** for an actor in Lupopedia (v4.0.58+). It is the primary key for identity; numeric `actor_id` is secondary and preserved for legacy resolution.

- **Examples:** `system`, `wolfie`, `lilith`, `antigravity`, `cursor`, `captain`
- **Registry:** `lupo-database/lupopedia/actors/registry.json` is the source of truth, keyed by `actor_name`.
- **Doctrine:** See [ACTOR_PRIMARY_KEY_DOCTRINE](doctrine/ACTOR_PRIMARY_KEY_DOCTRINE.md).

## 2. Session and whoami

Sessions (`lupo_sessions`) bind to an actor identity. Starting with v4.0.58, sessions use `actor_name` as the primary binding.

| Column | Purpose |
|------|------|
| `actor_name` | Primary identity string (v4.0.58+ authoritative) |
| `actor_id` | Legacy numeric ID (maintained for backward compatibility) |

### Identity Resolution Logic

When resolving the current actor for a request, the system follows this strict priority:

1. **If `actor_name` exists** → Use it as the authoritative identity.
2. **If `actor_name` is missing but `actor_id` exists** → Resolve `actor_name` via registry lookup.
3. **If both are missing** → Reject session (unauthorized).
4. **If both exist but mismatch** → Session is invalid (security violation).

### Migration Note

Sessions created before v4.0.58 store only `actor_id`. When such a session is loaded:
1. `actor_id` is resolved through the actor registry.
2. `actor_name` is derived.
3. `actor_name` is written back to the session row for future requests.

### PHP Implementation (PHP 5.3 Compatible)

When generating session IDs or handling identity, always ensure compatibility with PHP 5.3. Avoid `random_bytes()`.

```php
// PHP 5.3 safe session ID generation
$session_id = md5(uniqid(mt_rand(), true));

// Resolution example
$actor_name = $session->getActorName();
if (!$actor_name && $session->getActorId()) {
    $actor = ActorService::getActorById($session->getActorId());
    $actor_name = $actor['actor_name'];
    // Update session to store actor_name
    $session->setActorName($actor_name);
}
```

## 3. CLI: whoami and full execution context (v4.0.60+)

The CLI exposes **full Lupopedia execution context** so agents always know **who** they are, **where** they are (workspace/channel), and **which node** they belong to.

### Commands

- **`php lupo-bin/lupo.php whoami`** — Human-readable context (actor, channel, federation node, workspace, session, system version, context source).
- **`php lupo-bin/lupo.php whoami --verbose`** — Same context as JSON.
- **`php lupo-bin/lupo.php context`** — Alias for `whoami --verbose` (JSON only).

### Example output (whoami)

```text
$ php lupo-bin/lupo.php whoami

Human Identity: captain (10000)
Active Agent: cursor (1003)

Actor Type: ide_agent
Actor Nature: delegated_agent

Department: dev
Channel: 42
Thread: 4201
Federation Node: 0

Workspace:
/lupo-actors/cursor/

Session:
sess_a82f9c1b

Context Source:
lupo_sessions
```

### Example output (context / whoami --verbose, JSON)

```json
{
  "human_actor_name": "captain",
  "actor_name": "cursor",
  "agent_name": "cursor",
  "actor_type": "ide_agent",
  "actor_nature": "delegated_agent",
  "department_id": 1,
  "channel_id": 42,
  "thread_id": 4201,
  "federation_node_id": 0,
  "workspace": "/lupo-actors/cursor/",
  "session_id": "sess_a82f9c1b",
  "context_source": "lupo_sessions"
}
```

### Context resolution priority

Context is resolved in this order (see **ContextResolver::resolve()** in `lupo-includes/classes/ContextResolver.php`):

1. **lupo_sessions (primary)** — If the database is available, the current session is taken from `lupo_sessions` (using `.lupo_actor` session_id or actor_id to find the row). Fields: `actor_name`, `actor_id`, `channel_id`, `federation_node_id`, `session_id`.
2. **session.md (fallback)** — If the database is unavailable, the CLI reads `lupo-database/session.md`. Expected frontmatter or key-value lines: `actor_name`, `channel_id`, `federation_node_id`, `session_id` (optional: `actor_id`). This allows CLI use when the database is offline.
3. **System defaults** — If neither exists: `actor_name: system`, `actor_id: 0`, `channel_id: 0`, `federation_node_id: 0`.

Workspace is always computed as `/lupo-actors/{actor_name}/` (or the project’s `LUPO_ACTORS_DIR` + actor_name).

### Required FLARE headers validation

Before printing context, the CLI checks required headers (see **lupo-docs/doctrine/required_flare_headers.md**). If any required header is missing from the resolved context, it prints a non-fatal warning (e.g. `WARNING: Missing required FLARE header: actor_name`) and continues.

### Required Dialog Headers

Dialogs and artifacts should include **required dialog headers** so the system knows organizational context, conversation context, and speaking identity. These are validated by **DialogHeaderValidator** (non-fatal warnings only).

| Header | Meaning |
|--------|--------|
| `department_id` | Organizational domain (e.g. dev, public, support). Numeric; 0 when unspecified. |
| `channel_id` | Conversation channel. |
| `thread_id` | Thread within a channel. |
| `agent_name` | Active agent persona (who is acting). |
| `actor_name` | Actor identity driving permissions (whoami). |

**Resolution priority:** (1) Active dialog file or artifact; (2) Session context (`lupo_sessions`); (3) System defaults (department_id: 0, channel_id: 0, thread_id: 0, agent_name: system, actor_name: system). If a header is missing, the CLI prints `WARNING: Missing required dialog header: <header>` and continues.

## 4. Dual-Identity Context (v4.0.60+)

The runtime identity model has **three layers**. `lupo whoami` and `lupo context` expose all three so the system can answer: **WHO is the human**, **WHICH agent is active**, **WHAT actor controls permissions**, and **WHAT mode the session is in**.

### Identity layers

1. **Effective Actor** — The actor that owns the current session. Source: `lupo_sessions.actor_name` (or fallback `session.md`). Example: `cursor`.
2. **Human Identity** — Derived from `lupo_actors.paired_actor_id` when the effective actor is an agent. If the effective actor is human, human identity = that actor. Example: `cursor` → `paired_actor_id` → `captain`. Human identity is **never** stored in the session table; it is always derived to avoid drift.
3. **Active Agent** — The active agent persona. Derived from the effective actor when `actor_type` is `agent` or `ide_agent`; otherwise `none`. Example: `cursor`, `lilith`, `antigravity`.

### Session mode (derived)

| Rule | session_mode |
|------|----------------|
| `actor_type` = human | `human_direct` |
| `actor_type` = agent AND `paired_actor_id` = 0 | `autonomous_agent` |
| `actor_type` = agent AND `paired_actor_id` > 0 | `hybrid` |
| `actor_type` = system | `system` |

**`paired_actor_id` semantics (in `lupo_actors`):** `paired_actor_id = 0` means no human pairing (autonomous agent). `paired_actor_id > 0` means the agent acts on behalf of that human (e.g. `10000` = Captain); human identity is derived from this ID and is never duplicated into the session table.

### Example: Hybrid session (Cursor)

```bash
lupo whoami
```
**Output:**
```text
Human Identity: captain (10000)
Active Agent: cursor (1003)

Session Mode: hybrid
Actor Type: ide_agent

Channel: 42
Federation Node: 0

Workspace:
/lupo-actors/cursor/

Session:
sess_a82f9c1b

Context Source:
lupo_sessions
```

### Example: Human-direct session (Captain)

```text
Human Identity: captain (10000)
Active Agent: none

Session Mode: human_direct
Actor Type: human
```

### Example: Autonomous agent (Lilith)

```text
Human Identity: none
Active Agent: lilith (2038)

Session Mode: autonomous_agent
Actor Type: agent
```

### Example: JSON output (`lupo context`)

```json
{
  "actor_name": "cursor",
  "actor_id": 1003,
  "human_actor_name": "captain",
  "human_actor_id": 10000,
  "agent_name": "cursor",
  "actor_type": "ide_agent",
  "paired_actor_id": 10000,
  "session_mode": "hybrid",
  "channel_id": 42,
  "federation_node_id": 0,
  "workspace": "/lupo-actors/cursor/",
  "session_id": "sess_a82f9c1b",
  "context_source": "lupo_sessions"
}
```

## 5. Registry Configuration

The actor registry provides the mapping between names, IDs, and physical directory paths. The canonical source is `lupo_actors` (DB); the JSON registry is used for CLI/fallback and may include optional fields such as `paired_actor_id` for dual-identity resolution when the DB is unavailable.

**Path:** `lupo-database/lupopedia/actors/registry.json`

### Registry Example (structure)

```json
{
  "schema_version": "4.0.60",
  "schema": "actor_name_primary",
  "actors": {
    "cursor": {
      "actor_name": "cursor",
      "actor_id": 1003,
      "display_name": "Cursor IDE Agent",
      "type": "agent",
      "slug": "cursor",
      "dir": "lupo-actors/cursor",
      "paired_actor_id": 10000
    },
    "captain": {
      "actor_name": "captain",
      "actor_id": 10000,
      "display_name": "Captain",
      "type": "human",
      "slug": "root-captain-10000",
      "dir": "lupo-actors/captain"
    }
  }
}
```

`paired_actor_id` in the registry (when present) indicates the human actor ID the agent acts on behalf of; it is optional and is authoritative in `lupo_actors` when the database is available.

## 6. Actor Workspace Path

The canonical layout for actor content and tools is name-based:

```text
/lupo-actors/{actor_name}/
```

Legacy numeric directories (e.g. `0/`, `1/`) should be migrated to named directories (e.g. `system/`, `wolfie/`) with symlinks provided for backward compatibility where necessary.

**Example symlink for legacy compatibility (Unix):**
```bash
ln -s /lupo-actors/cursor /lupo-actors/1003
```
On Windows, use a directory junction or equivalent so that paths keyed by numeric ID still resolve to the name-based workspace.

## 7. Resolving identity in code

- **ContextResolver::resolve()** — Returns full execution context for CLI/agents, including dual-identity fields (actor_name, actor_id, actor_type, paired_actor_id, session_mode, human_actor_name, human_actor_id, agent_name), dialog headers (department_id, channel_id, thread_id), and workspace, session_id, source. Human identity is derived from `lupo_actors.paired_actor_id` when DB is available. Use when you need context without a web request.
- **DialogHeaderValidator::validate()** — Validates required dialog headers (department_id, channel_id, thread_id, agent_name, actor_name); prints non-fatal warnings for missing headers.
- **ActorLookup::fromRequest()** — Returns authoritative `actor_name` and `dir` for web requests.
- **ActorService::getActorByName($actor_name)** — Primary lookup for actor metadata.
- **ActorService::getActorDir($actor_name)** — Returns the resolved workspace path.

Use **actor_name** in FLARE headers, delegation chains, and logs for maximum clarity and future-proofing.

## 8. CLI Integration (v4.0.61+)

The Lupopedia CLI integrates whoami and context so users can inspect runtime identity and execution context from the terminal.

### Invocation

- **Human-readable context:** `php lupo-bin/lupo.php whoami`  
  Displays Human Identity, Active Agent, Session Mode, Actor Type, Channel, Federation Node, Workspace, Session, and Context Source. When there is no human or no agent, the corresponding line shows `none`.

- **JSON context:** `php lupo-bin/lupo.php context` or `php lupo-bin/lupo.php whoami --verbose`  
  Outputs the same data as a single flat JSON object (no nested objects). Suitable for scripting and tooling.

### Help

- **General help:** `php lupo-bin/lupo.php help` — Lists all commands and includes Basic Usage for whoami and context.
- **whoami help:** `php lupo-bin/lupo.php help whoami` — Explains the three identity layers (Effective Actor, Human Identity, Active Agent), session mode rules, and example outputs (hybrid, human_direct, autonomous_agent, system).
- **context help:** `php lupo-bin/lupo.php help context` — Describes the flat JSON format, ContextResolver resolution order (session.md first → DB enrichment → registry → defaults), and a sample JSON payload.

### Output modes

| Mode        | Command              | Output format   | Use case                          |
|------------|----------------------|-----------------|-----------------------------------|
| Human-readable | `whoami`         | Plain text      | Quick inspection, documentation   |
| JSON       | `context` or `whoami --verbose` | Single JSON object | Scripts, APIs, automation |

**Session file first (v4.0.61+):** The resolver reads `lupo-database/session.md` as the first-class source when the file exists and has usable keys (e.g. actor_name, channel_id, session_id). It then enriches from the database (lupo_sessions) and actor registry. **Context source** is reported as: `session.md`, `session.md + registry`, `lupo_sessions`, or `default`. When the database is unavailable, context is resolved from session.md and registry/defaults; the CLI does not exit on DB failure for whoami/context.
