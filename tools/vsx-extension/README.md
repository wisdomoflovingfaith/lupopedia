# LUPOPEDIA Header (aliases: LUPOPEDIA HEADERS)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "tools/vsx-extension/README.md"
  file_hash: "af4141576e9f1bc9e16fac72fadaeafdb15fa702fc44551d75666cbc8cc052b5"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA Header (aliases: LUPOPEDIA HEADERS)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "tools\vsx-extension\README.md"
  file_hash: "db7ece8aa827be25f99651711c402f643bd59bae770ceda83ad1bf9925dfc268"
  file_path_from_root: "tools\vsx-extension\README.md"
  file_hash: "5480c26da90948536f3dcced835cfeb16c283ecebb739b073e27083e6133fab1"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Lupopedia — VS Code / Open-VSX Extension (v4.0.33)"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["tools", "vsx-extension", "readmemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/LUPOPEDIA HEADER block preserved; migrate tools to use lupopedia.headers"]
---

# Lupopedia — VS Code / Open-VSX Extension (v4.0.33)

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
| **Lupopedia: Validate LUPOPEDIA Header** | Parse and validate the LUPOPEDIA HEADER front-matter block in the active file |
| **Lupopedia: Log Agent Action** | record agent actions to the audit trail |

---

## Multi-Agent Communication Modes (v4.0.31+)

Lupopedia supports a robust 3-tier fallback for communication, ensuring IDE agents remain operational even during server downtime:

1. **Remote**: Primary API connection to the Lupopedia production node.
2. **Local**: Connection to a local development node (localhost).
3. **Offline**: File-based communication via Markdown snapshots (Channel 42 fallback).
4. **Auto**: Intelligent fallback sequence (Remote → Local → Offline).

---

## Actor Identity

Your actor_id is **assigned by the Lupopedia registry** — it is never hardcoded. On first use, run **Lupopedia: Register IDE**. The registry returns your unique actor_id, which is stored locally in VS Code's global state and sent with every message.

If another IDE (e.g. Windsurf, KIRO, Warp) is running on the same codebase, each IDE receives its own distinct actor_id from the registry. This is how Lupopedia tracks which IDE sent which message.

---

## Requirements

- VS Code ≥ 1.80 (or any Open-VSX–compatible IDE)
- A running Lupopedia server (local or remote)

---

## Configuration

| Setting | Default | Description |
|---|---|---|
| `lupopedia.baseUrl` | `https://lupopedia.com/lupopedia` | Base URL of your Lupopedia API |
| `lupopedia.defaultChannelId` | `42` | Default channel to join/post to |
| `lupopedia.actorName` | `Antigravity IDE` | Name to register under |
| `lupopedia.actorType` | `system_tool` | Actor type (system_tool / ai / human) |
| `lupopedia.communicationMode` | `auto` | try remote → local → offline |

---

## Installation

See [docs/INSTALL.md](../../docs/INSTALL.md).

## Usage Guide

See [docs/USAGE.md](docs/USAGE.md).

---

## LUPOPEDIA Headers

A LUPOPEDIA Header is a YAML front-matter block that anchors a file to the Lupopedia semantic graph:

```yaml
---
# LUPOPEDIA Header
lupopedia.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/example.md
file.last_modified_system_version: "4.0.33"
file.last_modified_utc: "20260223171000"
channel_id: 42
x_lupo_forwarded: "2035:10000"

# Database Mapping Layer (Optional)
X-LUPO-actors.actor_id: 2035
X-LUPO-channels.channel_id: 42
---
```

### Database Mapping Layer (v4.0.28+)

The LUPOPEDIA Header system supports an optional database mapping layer:

- **Format**: `X-LUPO-{table}.{column}: <value>`
- **Purpose**: Explicit mapping between header fields and database schema
- **Usage**: Advanced tooling, migrations, and schema-aware agents

Use **Lupopedia: Validate LUPOPEDIA Header** to check any file's LUPOPEDIA HEADER block.

---

## License

MIT
