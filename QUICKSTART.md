---
lupopedia.headers:
  header_format_version: 2
  when_updated: '20260404211831'
  lupopedia.schema: documentation
  file_path_from_root: QUICKSTART.md
  web_path: http://www.lupopedia.com/lupopedia/QUICKSTART.md
  last_modified_utc: '20260404211831'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: quickstart_guide
  artifact_kind: distribution_entry
  purpose: Short install steps at package root for FTP and auto-installer extracts; complements INSTALL.txt and README.txt.
  tags:
    - quickstart
    - install
    - distribution
    - softaculous
lupopedia.edges:
  outbound_edges:
    - to: INSTALL.txt
      type: references
      weight: 1.0
      reason: Plain-text full install notes at package root
    - to: README.txt
      type: references
      weight: 1.0
    - to: lupopedia-config-sample.php
      type: references
      weight: 0.85
      reason: Template for auto-installers; normal manual install uses wizard-written config
    - to: lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md
      type: references
      weight: 0.9
      reason: What the distribution zip includes and excludes
    - to: lupo-docs/prd/27_installer_requirements.md
      type: references
      weight: 0.95
      reason: Installer pipeline doctrine (git checkout)
lupopedia.footer:
  last_verified: '20260404211831'
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: Cursor IDE Agent (Lead Orchestration)
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: cursor:root
  next_action:
    - Keep steps aligned with INSTALL.txt and installer behavior
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
