---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260404220006"
  file_path_from_root: "docs/implementations/security_audit_cursor_ide/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/security_audit_cursor_ide/README.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: checklist
  thread_id: "security-audit-cursor-ide"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: Cursor IDE security audit checklist (shared hosting) — delegation: cursor:root — web_path: [http://www.lupopedia.com/lupopedia/docs/implementations/security_audit_cursor_ide/README.md](http://www.lupopedia.com/lupopedia/docs/implementations/security_audit_cursor_ide/README.md)

# Cursor IDE security audit checklist (shared hosting)

**Constitutional law:** [PRD 00 — Section 17](../prd/00_root_constitutional_system_requirements.md) (**§17**, **RULE 93.SECURITY**).

When **writing** or **reviewing** code, apply these **Practical Empathy** checks so the system survives on a minimal shared host without assuming cloud-native perimeter defense.

---

## 1. Path and inclusion integrity (RFI / LFI)

| Check | Question |
|--------|----------|
| **Anchor** | Is execution anchored on **`LUPOPEDIA_PATH`** or **`ABSPATH`** derived from config / **`__DIR__`**, not on unchecked user strings? |
| **Stream block** | Does the path resolver reject **`://`** and **NUL** (`\0`) so **`require`** cannot target remote streams? |
| **Traversal** | Are **`realpath()`** or normalized comparisons used so **`../`** cannot escape a known root? |

**Reference:** `LupopediaConfigResolver::isSafeLocalConfigPath()`, `includes/bootstrap.php` (**`LUPOPEDIA_PATH`** vs **`ABSPATH`**).

---

## 2. Database neutrality and injection (SQLi)

| Check | Question |
|--------|----------|
| **Bouncer** | Are all queries executed through **`DatabaseFactory::getConnection()`** / **`PDO_DB`**? Raw **`PDO`** / **`mysqli`** in new core paths is forbidden. |
| **Explicit lists** | Does every **`INSERT`** name **all** columns explicitly? |
| **Typing** | Are IDs and numeric limits cast to **`(int)`** / **`(float)`** before binding where appropriate? |

**Constitutional:** PRD 00 **§3**, **§17.3**; `DATABASE_NEUTRAL_SQL_DOCTRINE`.

---

## 3. Environmental probing (Survivability Doctrine)

| Check | Question |
|--------|----------|
| **Extension gates** | **`extension_loaded()`** / **`function_exists()`** before **`curl`**, **`gd`**, etc.? |
| **Permission probes** | **`is_writable()`** (or equivalent) before writes, with a **human-readable** failure path? |
| **No auto-chmod** | On failure, **report** to the operator — do **not** **`chmod`** to “repair” the host. |

**Constitutional:** PRD 00 **§15.1**, **§15.3**, **§14.6** (**Survivability Doctrine**).

---

## 4. Direct access prevention (information leakage)

| Check | Question |
|--------|----------|
| **Marker merge** | Do sensitive trees use **`.htaccess`** **`Deny from all`** (or equivalent) where the installer maintains markers (**Apache-compatible** stacks only)? |
| **Index silence** | Are **blank `index.php` / `index.html`** present where the product ships them so directory listing does not leak structure? |

**Reference:** `InstallWizardHtaccessWriter.php`, PRD 00 **§15.4**, **§17.5**.

---

## Reviewer roles

| Persona | Use this checklist to … |
|---------|-------------------------|
| **LILITH** (**actor_id 2**) | Flag “simplified” removal of path checks, stream blocks, or fallbacks — **cognitive tax** on Cursor output; **LIL001** still applies. |
| **THOTH** | Cross-check claims against **TOON** exports, **install SQL**, and **table docs** — real gaps over imaginary threats. |

---

This file complies with Lupopedia Constitutional Root Rules.
