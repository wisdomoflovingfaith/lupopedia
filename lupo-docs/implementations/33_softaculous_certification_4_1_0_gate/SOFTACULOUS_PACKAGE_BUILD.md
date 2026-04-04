---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260404165054"
  file_path_from_root: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md"
  last_modified_utc: "20260404165054"
  federation_node_id: 0
  channel_id: 42
  thread_id: "33-softaculous-package"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root|lilith:directive"
  parent_prd: "33_softaculous_certification_4_1_0_gate"
  artifact_type: "implementation"
  artifact_kind: "distribution_spec"
  purpose: "Softaculous / FTP-safe 4.1.0 package — WordPress pattern: zero dotfiles in zip; install wizard writes .htaccess; no .gitkeep"
  tags:
  - "softaculous"
  - "ftp"
  - "distribution"
  - "4.1.0"
  - "lilith_directive"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: implements
      weight: 1.0
      reason: "Release gate and hosting distribution"
    - to: "lupo-scripts/build_softaculous_package.sh"
      type: references
      weight: 1.0
      reason: "Canonical packager (run from git checkout; script is excluded from zip)"
    - to: "lupo-install/InstallWizardHtaccessWriter.php"
      type: references
      weight: 1.0
      reason: "Writes .htaccess after successful config (Apache); aligns with repo root rules"
lupopedia.footer:
  last_verified: "20260404154659"
  verified_by:
    identity_type: "agent"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
  orchestrator: "cursor:root"
  next_action:
    - "Run build_softaculous_package.sh; extracted tree must have zero .htaccess until install.php finishes"
    - "InstallWizardHtaccessWriter creates runtime dirs; bootstrap also mkdir missing dirs for auto-installer installs"
---

# file: Softaculous package build — web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md](http://www.lupopedia.com/lupopedia/lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md)

# Softaculous package build (4.1.0) — complete specification

## 1. Why (FTP, WordPress precedent, and zero dotfiles)

End users often upload distributions with **FTP**. Many clients **skip or mishandle** dot-prefixed paths (`.git`, `.cursor`, …). **WordPress** (widest Softaculous footprint) ships **no** `.htaccess` in the zip: it is **generated at install** so users never lose a hidden file and rules match the environment.

**Lupopedia rule (4.1.0 distribution):** The archive **MUST NOT** contain **any** dot-prefixed **files or directories** — **including** **`.htaccess`** and **`.htpasswd`**. After **`install.php`** completes the config step successfully, **`InstallWizardHtaccessWriter`** writes:

- **`<webroot>/.htaccess`** — same rewrite and hardening as the development tree (mod_rewrite, security headers, file blocks).
- **`lupo-database/.htaccess`** — deny direct web access to SQL and seeds.

**Git / developer checkouts** may still keep `.htaccess` in the repo for local Apache; only the **Softaculous / FTP zip** omits it.

**No `.gitkeep`.** Writable dirs are **`mkdir`**’d by the installer.

**Nginx / IIS:** Do not use `.htaccess`. Operators must map equivalent rules in **`nginx.conf`** / **`web.config`**. The install wizard **logs a warning** if it cannot write `.htaccess`; it does **not** fail the install.

This output complies with Lupopedia Constitutional Root Rules.

---

## 1b. Silent install / auto-installer contract

**Softaculous, Installatron, and similar tools** create the database and write **`lupopedia-config.php`** from a template. They often **do not** open **`install.php`** in a browser.

**`index.php` behavior:** It searches for **`lupopedia-config.php`** (same three-path order as production). If the file **exists**, it **`require_once`**’s it and runs the app — **no redirect** to **`install.php`**. Redirect happens **only** when no config file is found.

**`install.php`** defines **`LUPO_INSTALLING`** (constant) at load so future code can distinguish wizard context from normal requests.

**Implication for script authors:** Point the installed URL at **`index.php`** (or directory default). End users should see the app once DB credentials are valid and schema exists — not a loop back to the wizard. Schema creation remains the auto-installer’s responsibility (their post-install hooks or a documented “first visit to install.php” if you require it for that release).

---

## 1c. `lupopedia-config-sample.php` (root)

Ship **`lupopedia-config-sample.php`** in the package root. It mirrors production config shape: **`define()`** for database and salts (WordPress-classic style), then **`require_once`** **`lupo-includes/bootstrap.php`**.

**Softaculous-style placeholders** in the sample (replace with real values for manual copy-paste):

| Placeholder | Typical injection |
|-------------|-------------------|
| `[[softdb]]` | Database name |
| `[[softdbuser]]` | Database user |
| `[[softdbpass]]` | Database password |
| `[[softdbhost]]` | Database host |

**`ABSPATH`** uses **`__DIR__ . '/'`** like **`wp-config-sample.php`**, so when the generated file lives in the install directory, paths resolve correctly for subdirectory installs (e.g. `/tools/support/lupopedia/`).

---

## 2. Archive names

- `lupopedia-4.1.0.zip`
- `lupopedia-4.1.0.tar.gz`

Inner directory (top level inside the archive): **`lupopedia/`** (all application files live under that folder).

The canonical builder writes to **`dist/`** at the repo root by default (override with **`DIST_DIR`**).

---

## 3. Illustrative runtime layout (after extract, **before** install)

Not every path exists in every tag; the **packager includes whatever is present** except excluded trees. **Before** running **`install.php`**:

- `index.php`, `admin.php`, `install.php`, `livehelp_js.php`, `lupopedia_js.php` as applicable for the release
- **`lupopedia-config-sample.php`** (template for hosts / auto-installers; **not** a live `lupopedia-config.php` in the zip)
- **`license.txt`**, **`README.md`**, **`README.txt`** at package root (reviewer-friendly; not dotfiles)
- `lupo-includes/`, `app/`, `lupo-ui/`, `lupo-emoji/`, `config/`, `lupo-install/` (contains **`InstallWizardHtaccessWriter.php`**), `lupo-database/lupopedia/mysql/install/`, `lupo-database/lupopedia/mysql/import/`
- **No** `.htaccess` anywhere (FTP-safe)

**After** install config succeeds on **Apache** with a writable docroot: **`.htaccess`** and **`lupo-database/.htaccess`** appear.

---

## 4. Dot-prefix policy (distribution archive)

### In the zip / tar: nothing

| Name | In Softaculous package? |
|------|-------------------------|
| `.htaccess` | **No** — generated by **`InstallWizardHtaccessWriter`** at end of install. |
| `.htpasswd` | **No** — create only on hosts that use basic auth; not part of default zip. |
| Any other `.*` file or directory | **No** |

### Never include (same as before)

| Pattern | Reason |
|---------|--------|
| `.git/`, `.gitignore`, `.gitmodules`, `.github/` | VCS / CI — not runtime. |
| `.cursor/`, `.windsurf/`, `.kiro/`, `.lexa/`, `.lilith/`, `.cascade/`, `.vscode/`, `.idea/`, `.agents/`, `.codex/` | IDE / agent tooling. |
| `.gitkeep` | Installer creates empty dirs. |
| `__pycache__/`, `*.pyc` | Python — not PHP runtime. |
| `.DS_Store`, `Thumbs.db`, `*.swp`, `*.tmp` | OS / editor noise. |
| `phantom_paths.txt` | Audit scratch — not runtime. |

---

## 5. Development trees to exclude from the zip

| Path | Reason |
|------|--------|
| `lupo-docs/` | Documentation only. |
| `lupo-scripts/` | Dev scripts (the **builder** lives here; run it **before** packaging — it is **not** inside the zip). |
| `craftysyntax-reference/` | Reference tree — not runtime. |
| `lupo-archive/` | Entire archive dir excluded (includes **`legacy/wordpress-reference/`** study tree; GPL) — not runtime; keeps zip small. |
| `lupo-legacy/` | Legacy reference — not runtime (adjust if a release explicitly ships legacy). |
| `lupo-research/` | Research sandboxes. |
| `lupo-tests/` | Tests. |
| `lupo-database/lupopedia/toon/`, `lupo-database/lupopedia/json/` | Generated schema mirrors — **install SQL is authoritative**. |
| `lupo-tools/`, `node_modules/` | Tooling / npm caches. |

### Critical correction (runtime)

**`lupo-database/lupopedia/content/`** (especially **`lupo-app/`**) **MUST ship** — `LUPO_APP_DIR` points here (`lupopedia-config.php`, installer). **Do not** exclude this tree in the Softaculous package.

---

## 6. Empty directories and install-time writes

FTP may omit empty folders. **`InstallWizardHtaccessWriter::ensureRuntimeDirectories`** (called from **`install.php`** after config write) creates:

- `lupo-cache/`
- `lupo-logs/`
- `lupo-uploads/`
- `lupo-tmp/` (aligned with **`start_over.php`**)

**Bootstrap self-heal:** **`lupo-includes/bootstrap.php`** also **`@mkdir(..., 0755, true)`** those four directories under **`LUPOPEDIA_ABSPATH`** when missing, so **auto-installer** installs that never run the wizard still get writable folders on first request.

Do **not** rely on `.gitkeep` in the archive.

---

## 7. Build script

**Path:** `lupo-scripts/build_softaculous_package.sh`

**Requirements:** `rsync`, `find`, `zip`, `tar` (e.g. **Git Bash** on Windows, or Linux/macOS).

```bash
# From repo root (after chmod +x on Unix):
./lupo-scripts/build_softaculous_package.sh 4.1.0

# Custom output directory:
DIST_DIR=/path/to/out ./lupo-scripts/build_softaculous_package.sh 4.1.0
```

The script **rsyncs** with dev-tree excludes, then **deletes** all remaining dot-directories, **all** dot-prefixed files (**including** `.htaccess` and `.htpasswd`), and all **`.gitkeep`** files.

**Explicitly not excluded** (ship when present at repo root): **`license.txt`**, **`README.md`**, **`README.txt`**, **`lupopedia-config-sample.php`**. The **`rsync`** exclude list does not match those names.

**Excluded on purpose:** **`lupopedia-config.php`** and **`lupopedia-config_backup.php`** — never pack a developer machine’s live credentials; end users copy from the sample or receive a generated config from the host panel.

---

## 8. Pre-submission validation (extracted package)

Run from the **extracted** `lupopedia/` root **before** running the installer:

- [ ] `find . -name '.git' -type d` → no results  
- [ ] `find . -name '.cursor' -type d` → no results  
- [ ] `find . -name '__pycache__' -type d` → no results  
- [ ] `find . -name '*.pyc'` → no results  
- [ ] `find . -name '.DS_Store'` → no results  
- [ ] `find . -name '.gitkeep'` → no results  
- [ ] `find . -name '.htaccess' -type f` → **no results** (WordPress pattern)  
- [ ] Run **`install.php`** → config log should report **Wrote Apache rewrite file** (Apache + writable root) or **warn** on Nginx/IIS  
- [ ] After install (Apache): `find . -name '.htaccess' -type f` → docroot + `lupo-database/.htaccess`  
- [ ] Fresh install on **subdirectory** shared hosting  
- [ ] Crafty 3.7.5 import path (if offered for that release)  
- [ ] Operator login and chat smoke test  

---

## 9. Summary table

| Item | In package? | Reason |
|------|-------------|--------|
| `.htaccess` / `.htpasswd` | **No** | Installer creates `.htaccess` on Apache when possible |
| `.git/`, IDE dotdirs, `.gitkeep` | No | VCS / IDE noise; FTP-hidden files |
| `lupo-docs/`, `lupo-scripts/`, `lupo-tests/`, `lupo-research/`, `lupo-archive/` | No | Non-runtime |
| `lupo-database/lupopedia/content/lupo-app/` | **Yes** | **`LUPO_APP_DIR`** runtime |
| `lupo-install/InstallWizardHtaccessWriter.php` | **Yes** | Post-install `.htaccess` + wizard dirs |
| **`lupopedia-config-sample.php`** | **Yes** | Auto-installer / host template (`[[softdb*]]` placeholders) |
| **`lupopedia-config.php`** (live) | **No** | Packager **excludes** — avoid leaking credentials; use sample |
| **`license.txt`**, **`README.md`**, **`README.txt`** | **Yes** | Certification / user clarity at zip root |
| Rest of PHP / JS / CSS / SQL needed to run | Yes | Runtime tree |

---

## 10. Subdirectory URLs (JS / public path)

Installs under deep paths (e.g. **`example.com/support/lupopedia/`**) require **`LUPOPEDIA_PUBLIC_PATH`** (and templates that emit asset URLs) to stay **prefix-aware**. Prefer server-rendered script/style URLs that use the same constants **`index.php`** uses — avoid hardcoded **`/lupopedia/`** in shipped JS when that path is meant to be dynamic.

---

## Related

- **PRD 33:** [33_softaculous_certification_4_1_0_gate.md](../../prd/33_softaculous_certification_4_1_0_gate.md)  
- **Implementation README:** [README.md](README.md)
