---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: QUICKSTART.md
  web_path: https://www.lupopedia.com/lupopedia/QUICKSTART.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/quickstart-md.toon
  atoms_toon: null
  transcript_jsonl: 0/development/quickstart-md
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: guide
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE
  title: QUICKSTART.md -- Install Quick-Start
  summary: Short install steps at package root for FTP and auto-installer extracts; complements INSTALL.txt and README.txt.
---
# file: QUICKSTART — delegation: cursor:root - web_path: [http://www.lupopedia.com/lupopedia/QUICKSTART.md](http://www.lupopedia.com/lupopedia/QUICKSTART.md)

# Quick start (distribution package)

Use this page for a **fast path** after you unzip or upload the app. **Details:** [INSTALL.txt](INSTALL.txt) (plain text, FTP-friendly).

## 1. Upload

Put the `lupopedia/` tree on your host under the URL prefix you will use (subdirectory installs are normal).

## 2. Database

Create an empty database and user. Note host, database name, username, and password for the wizard.

## 3. Run `install.php`

Open `install.php` in the browser. Enter database credentials when prompted, complete install or Crafty 3.7.5 upgrade as offered, then finish the **site configuration** step — the wizard **writes** `lupopedia-config.php` (DB settings, randomly generated security keys, base URL, site options). You normally do **not** create that file by hand first.

## 4. Rewrites (Apache)

The zip has **no** `.htaccess` until the wizard succeeds on Apache with a writable docroot; then the installer can write rewrite rules. On Nginx or IIS, configure rewrites per your host.

---

**Auto-installers** may already emit a valid `lupopedia-config.php`; then open the site URL directly.

**Developers:** a full git checkout includes `lupo-docs/`; **Softaculous / FTP packages exclude** that tree by design.
