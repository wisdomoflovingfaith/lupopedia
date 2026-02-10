# Version Doctrine for Lupopedia (Authoritative)

This project uses a **non-standard versioning doctrine**. All refactors, documentation, code, and reasoning MUST follow these rules. They override any assumptions about semantic versioning.

---

## 1. Lupopedia 4.0.0 is the ONLY real release target

- **4.0.0** is the frozen release version.
- **4.0.0** is the only version that will ever be installed by users (until 4.1.0).
- **4.0.0** is the upgrade destination for Crafty Syntax 3.7.5.
- There are no Lupopedia 4.0.0 installs in the world yet; there will be none until the auto-installers receive the final package.
- GitHub pushes do **not** count as releases.

---

## 2. 4.0.x numbers (4.0.1–4.0.8) are NOT releases

These numbers represent internal refinement cycles, development checkpoints, iterative cleanup, and refactor phases. They are **not** public versions.

You MUST NOT reference **4.0.1**, **4.0.2**, **4.0.3**, **4.0.4**, **4.0.5**, **4.0.6**, **4.0.7**, or **4.0.8** in:

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
Crafty Syntax 3.7.5  →  Lupopedia 4.0.0
```

You MUST NOT create or reference:

- upgrade paths to 4.0.1+
- migrations for 4.0.1+
- version checks for 4.0.1+

The system MUST behave as if **4.0.0** is the first and only Lupopedia version that exists (until 4.1.0).

---

## 4. 4.1.0 is the ONLY future version

- **4.1.0** is the planned public release that will ship to auto-installers after 4.0.0 is finalized.
- **4.1.0** is the first version that users will actually install after 4.0.0.
- **4.1.0** is the version that introduces new features beyond the 4.0.0 baseline.

You MUST NOT reference **4.1.1**, **4.2.0**, **4.3.0**, or any other future version until 4.1.0 is released.

---

## 5. Version references in code

| Allowed   | Forbidden                    |
|----------|------------------------------|
| 4.0.0    | 4.0.1–4.0.8                  |
| 4.1.0    | any version &gt; 4.1.0       |

Internal docs may mention 4.0.8 as a development checkpoint but **not** as a real version.

---

## 6. IDE agents MUST cascade this doctrine

When performing refactors, agents MUST:

- remove references to 4.0.1–4.0.8 from code, SQL, and UI
- ensure install SQL uses **4.0.0**
- ensure upgrade logic uses **4.0.0**
- ensure version checks use **4.0.0**
- ensure documentation reflects **4.0.0** as the only install version
- ensure future-planning docs reference **4.1.0** ONLY
- ensure no code assumes 4.0.x patch releases exist

This doctrine applies to: helper refactors, class migrations, schema updates, documentation, version checks, installer logic, upgrade logic, UI version displays, atom/version loaders.

---

## 7. Single source of truth for version string

- **Config:** `config/global_atoms.yaml` — `GLOBAL_CURRENT_LUPOPEDIA_VERSION: "4.0.0"` and top-level `version: "4.0.0"`.
- **Runtime:** `get_lupopedia_version()` / `AtomLoader::getLupopediaVersion()` — load from atom; fallback in code MUST be **4.0.0** (never 3.0.35 or 4.0.1–4.0.8).
- **Constants:** `lupo-includes/version.php` sets `LUPOPEDIA_VERSION` and `LUPOPEDIA_DB_VERSION` from the atom; hard-coded fallback MUST be **4.0.0**.
