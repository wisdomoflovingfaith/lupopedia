# Lupopedia — VS Code / Open-VSX Extension

Connect your IDE to **Lupopedia** — a semantic operating system for organizing meaning. This extension registers your IDE as an actor in the Lupopedia unified registry, lets you participate in channels, and surfaces semantic context directly inside your editor.

---

## Features

| Command | Description |
|---|---|
| **Lupopedia: Register IDE** | Register this IDE with the Lupopedia unified registry and receive an actor_id |
| **Lupopedia: Join Channel** | Send a join notification to any Lupopedia channel |
| **Lupopedia: Send Message** | Post a message to the active channel |
| **Lupopedia: Show Channel Thread** | Open a live-updating thread view for a channel |
| **Lupopedia: Explain This File** | Request a semantic explanation of the active file |
| **Lupopedia: Show Related Atoms** | Find semantically related content atoms |
| **Lupopedia: Validate FLIP Header** | Parse and validate the FLIP front-matter block in the active file |

---

## Actor Identity

Your actor_id is **assigned by the Lupopedia registry** — it is never hardcoded. On first use, run **Lupopedia: Register IDE**. The registry returns your unique actor_id, which is stored locally in VS Code's global state and sent with every message.

If another IDE (e.g. Windsurf) is running on the same codebase, each IDE receives its own distinct actor_id from the registry. This is how Lupopedia tracks which IDE sent which message.

---

## Requirements

- VS Code ≥ 1.80 (or any Open-VSX–compatible IDE)
- A running Lupopedia server (local or remote)

---

## Configuration

| Setting | Default | Description |
|---|---|---|
| `lupopedia.baseUrl` | `http://localhost` | Base URL of your Lupopedia API |
| `lupopedia.defaultChannelId` | `42` | Default channel to join/post to |
| `lupopedia.actorName` | `Antigravity IDE` | Name to register under |
| `lupopedia.actorType` | `system_tool` | Actor type (system_tool / ai / human) |

---

## Installation

See [docs/INSTALL.md](docs/INSTALL.md).

## Usage Guide

See [docs/USAGE.md](docs/USAGE.md).

---

## FLIP Headers

A FLIP header is a YAML front-matter block that anchors a file to the Lupopedia semantic graph:

```yaml
---
# FLIP Header
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/example.md
file.last_modified_system_version: "4.0.28"
file.last_modified_utc: "20260220174000"
channel_id: 42

# Database Mapping Layer (Optional)
X-LUPO-actors.actor_id: 2038
X-LUPO-channels.channel_id: 42
---
```

### Database Mapping Layer (New in 4.0.28)

The FLIP header system supports an optional database mapping layer:

- **Format**: `X-LUPO-{table}.{column}: <value>`
- **Purpose**: Explicit mapping between header fields and database schema
- **Usage**: Advanced tooling, migrations, and schema-aware agents
- **Rules**: 
  - Never overrides semantic FLIP fields
  - Treated as opaque strings (no inference)
  - Optional for all operations
  - Must use exact `{table}.{column}` format

**VSX Extension Behavior**:
- When offline, includes mapping layer only if present in file
- Does NOT auto-generate mapping layer unless explicitly requested
- Does NOT infer table or column names
- Treats mapping layer as metadata only

Use **Lupopedia: Validate FLIP Header** to check any file's FLIP block.

---

## License

MIT
