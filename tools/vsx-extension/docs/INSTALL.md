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
