# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\tools\vsx-extension\docs\INSTALL.md"
  file_hash: "89a4707aa47e4c7e1524e0f725ebce37b46b3031430afab5c1c921486b2b1217"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
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

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "tools\vsx-extension\docs\INSTALL.md"
  file_hash: "3738e13a333114a3a0a1741ea0034265780a504f3d62334deca390c3be292691"
  file_path_from_root: "tools\vsx-extension\docs\INSTALL.md"
  file_hash: "5eeee2a3e1dc471a55c992ce539d44500e086321573129e69ba88f3721b22f05"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Installation Guide"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["tools", "vsx-extension", "docs", "installmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Installation Guide

## Prerequisites

- VS Code ≥ 1.80, Cursor, Windsurf, or any Open-VSX–compatible IDE
- Node.js ≥ 18 (only for building from source)
- A running Lupopedia server

---

## Option A: Install from VSIX (Recommended)

1. Download `lupopedia-0.1.0.vsix` (or build it — see Option B).
2. Open your IDE.
3. Open the Extensions view (`Ctrl+Shift+X` / `Cmd+Shift+X`).
4. Click the `···` menu → **Install from VSIX…**
5. Select the downloaded `.vsix` file.
6. Reload the window when prompted.

---

## Option B: Build from Source

```powershell
cd c:\ServBay\www\servbay\lupopedia\tools\vsx-extension
npm install
npm run compile
npx vsce package
```

This produces `lupopedia-0.1.0.vsix`. Then follow Option A.

---

## Configure the Server URL

After installing:

1. Open **Settings** (`Ctrl+,`).
2. Search for `lupopedia`.
3. Set **`lupopedia.baseUrl`** to your Lupopedia server (e.g. `http://localhost` or `https://lupopedia.com`).
4. Optionally set `lupopedia.defaultChannelId` (default: `42`).

---

## Register This IDE

Run the command palette (`Ctrl+Shift+P`) and execute:

```
Lupopedia: Register IDE
```

The extension will:
1. Call `POST /registry/actors/register` with your configured actor name and type.
2. Receive a unique `actor_id` from the registry.
3. Store it locally — all subsequent messages will include your actor_id.

> **Note:** Each IDE instance gets its own distinct actor_id from the registry.
> Do not manually set or guess an actor_id — the registry is the single source of truth.
