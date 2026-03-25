# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/VERSION_DOCTRINE.md"
  file_hash: "f7ad088b261c91e5425279886dff73496d53d5d44ac15187c9e954dfd0a74457"
  last_updated_utc: "20260228155738"
  system_version: "4.0.88"
  channel_id: 1
  actor_id: 102
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "cursor"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\doctrine\VERSION_DOCTRINE.md"
  file_hash: "f53ceb4e3c93527d4ff11e4686756c12a5b0475b97a320d04c1b3ee8802951da"
  file_path_from_root: "lupo-docs\doctrine\VERSION_DOCTRINE.md"
  file_hash: "f17f54922bf008d228b5d81c3e47f7a3d6c7256441e716156560479701b10d66"
  last_updated_utc: "20260228"
  system_version: "4.0.88"
  channel_id: 1
  actor_id: 102
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for VERSION_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "version_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.88"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "cursor"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: lupo-docs/doctrine/VERSION_DOCTRINE.md
file.last_modified_system_version: "4.0.88"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: lupo-docs/doctrine/VERSION_DOCTRINE.md
---

# Version Doctrine for Lupopedia (Authoritative)

This project uses a **non-standard versioning doctrine**. All refactors, documentation, code, and reasoning MUST follow these rules. They override any assumptions about semantic versioning.

---

## 1. Lupopedia 3.0.0 is the ONLY real release target

- **3.0.0** is the frozen release version.
- **3.0.0** is the only version that will ever be installed by users (until 4.1.0).
- **3.0.0** is the upgrade destination for Crafty Syntax 3.7.5.
- There are no Lupopedia 3.0.0 installs in the world yet; there will be none until the auto-installers receive the final package.
- GitHub pushes do **not** count as releases.

---

## 2. 4.0.x numbers (4.0.1–3.0.8) are NOT releases

These numbers represent internal refinement cycles, development checkpoints, iterative cleanup, and refactor phases. They are **not** public versions.

You MUST NOT reference **4.0.1**, **3.0.2**, **3.0.3**, **3.0.4**, **3.0.5**, **3.0.6**, **3.0.7**, or **3.0.8** in:

- code
- comments
- install SQL
- upgrade SQL
- migrations
- UI
- metadata
- version checks

They may appear in **internal docs only** as development notes.

---

## 3. The ONLY valid upgrade path

```
Crafty Syntax 3.7.5  →  Lupopedia 3.0.0
```

You MUST NOT create or reference:

- upgrade paths to 4.0.1+
- migrations for 4.0.1+
- version checks for 4.0.1+

The system MUST behave as if **3.0.0** is the first and only Lupopedia version that exists (until 4.1.0).

---

## 4. 4.1.0 is the ONLY future version

- **4.1.0** is the planned public release that will ship to auto-installers after 3.0.0 is finalized.
- **4.1.0** is the first version that users will actually install after 3.0.0.
- **4.1.0** is the version that introduces new features beyond the 3.0.0 baseline.

You MUST NOT reference **4.1.1**, **4.2.0**, **4.3.0**, or any other future version until 4.1.0 is released.

**Internal schema-sync and development snapshots** use **3.x.x** (e.g. 3.0.46), not 4.0.x. These are pre-release internal markers only; the next public release is 4.1.0.

---

## 5. Version references in code

| Allowed   | Forbidden                    |
|----------|------------------------------|
| 3.0.0    | 4.0.1–3.0.8                  |
| 4.1.0    | any version &gt; 4.1.0       |

Internal docs may mention 3.0.8 as a development checkpoint but **not** as a real version.

---

## 6. IDE agents MUST cascade this doctrine

When performing refactors, agents MUST:

- remove references to 4.0.1–3.0.8 from code, SQL, and UI
- ensure install SQL uses **3.0.0**
- ensure upgrade logic uses **3.0.0**
- ensure version checks use **3.0.0**
- ensure documentation reflects **3.0.0** as the only install version
- ensure future-planning docs reference **4.1.0** ONLY
- ensure no code assumes 4.0.x patch releases exist

This doctrine applies to: helper refactors, class migrations, schema updates, documentation, version checks, installer logic, upgrade logic, UI version displays, atom/version loaders.

---

## 7. Single source of truth for version string

- **Config:** `config/global_atoms.yaml` — `GLOBAL_CURRENT_LUPOPEDIA_VERSION: "3.0.0"` and top-level `version: "3.0.0"`.
- **Runtime:** `get_lupopedia_version()` / `AtomLoader::getLupopediaVersion()` — load from atom; fallback in code MUST be **3.0.0** (never 3.0.35 or 4.0.1–3.0.8).
- **Constants:** `lupo-includes/version.php` sets `LUPOPEDIA_VERSION` and `LUPOPEDIA_DB_VERSION` from the atom; hard-coded fallback MUST be **3.0.0**.
