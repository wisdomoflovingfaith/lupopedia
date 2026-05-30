---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/CONFIGURATION_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/CONFIGURATION_DOCTRINE.md"
  status: "active"
  when_updated: "20260404212949"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: null
  federation_node_id: 0
  thread_id: "doctrine-header-repair"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: CONFIGURATION_DOCTRINE — delegation: cursor:root

# Configuration Doctrine

## Purpose

Define the configuration file search algorithm and security requirements for Lupopedia, following the traditional open-source auto-installer pattern used by WordPress, phpBB, and similar applications for over 20 years.

## Search Algorithm

**Canonical implementation:** `lupo-includes/classes/LupopediaConfigResolver.php` — used by `index.php`, `admin.php`, `install.php`, `channel.php`, `lupo-ajax.php`, `livehelp_js.php`, `image.php`, `login.php`, and related entrypoints **before** loading `lupopedia-config.php`.

`lupo-includes/bootstrap.php` does **not** search; it runs only **after** the config file has been loaded and defined `LUPOPEDIA_CONFIG_LOADED`.

Lupopedia is **always** installed under a subdirectory URL (e.g. `example.com/lupopedia/`). Discovery therefore **prefers** config **outside** the web document root, then the install folder, then a guarded parent path.

Order in `LupopediaConfigResolver::resolve()` (first existing file wins):

1. `dirname($_SERVER['DOCUMENT_ROOT'])/lupopedia-config.php` — one level **above** the web root (shared-hosting default).
2. `dirname(DOCUMENT_ROOT)` + public path segment + `/lupopedia-config.php` — legacy layout (e.g. config under a folder named like the URL segment next to `public_html`).
3. `LUPOPEDIA_PATH/lupopedia-config.php` — install directory (wizard default, dev convenience).
4. `dirname(LUPOPEDIA_PATH)/lupopedia-config.php` only if that parent does **not** contain `lupo-includes/bootstrap.php` (not another Lupopedia tree; mirrors WordPress skipping parent `wp-config` when the parent is another install).

If `DOCUMENT_ROOT` is unset (e.g. some CLI), steps **1–2** are skipped; resolution uses **3**, then **4**.

### Why above DOCUMENT_ROOT first

| Step | Purpose |
|------|---------|
| **Above web root** | Config is not inside `DOCUMENT_ROOT`; aligns with subdirectory installs and hoster guidance. |
| **Legacy above-root + segment** | Older documented layout still supported. |
| **Install directory** | Default wizard write location and local dev. |
| **Parent of install (guarded)** | Develop-style or manual placement beside the app folder. |

## Wizard write destination (WordPress `setup-config.php`)

`InstallWizardConfigWriter::writeConfig` uses `LupopediaConfigResolver::defaultWriteTargets()`:

- If `lupopedia-config-sample.php` exists in **LUPOPEDIA_PATH**, write `lupopedia-config.php` there.
- Else if the sample exists only in **dirname(LUPOPEDIA_PATH)** and that parent is not another Lupopedia install, write there (mirrors WordPress choosing `dirname(ABSPATH)/wp-config.php` when the sample is one level up).

Generated `ABSPATH` in the config file always points at the **install root** (Lupopedia application directory), not necessarily the directory that holds the config file. `LUPOPEDIA_PUBLIC_PATH` in generated config is derived from the install folder basename so it stays correct when the config file is outside the install directory.

## Security Implications

- **NEVER place `lupopedia-config.php` in the web root**
- The configuration file contains database credentials and system secrets
- Config file must NOT be web-accessible
- Auto-installers MUST place the config file outside web root

## Environment-Specific Recommendations

| Environment | Recommended Location | Reason |
|-------------|---------------------|---------|
| **Development (local)** | In installation directory | Convenience for testing |
| **Production (shared hosting)** | One level above web root | Maximum security |
| **Auto-installer package** | One level above installation directory | Auto-installer standard |

## Configuration Override

If you need to specify a custom location, define `LUPOPEDIA_CONFIG_PATH` before bootstrap:

```php
define('LUPOPEDIA_CONFIG_PATH', '/absolute/path/to/lupopedia-config.php');
require_once 'lupo-includes/bootstrap.php';
```

## Auto-Installer Integration

### For Softaculous, Fantastico, Installatron, etc.

When creating a package for auto-installers:

1. **Place `lupopedia-config.php` one level above installation directory**
2. **Do NOT include the config file in the web-accessible package**
3. **Document the expected location in installation instructions**
4. **Use relative paths in the bootstrap to support all environments**

### Package Structure Example

```
lupopedia-4.0.89/
+-- lupopedia/                    # Web-accessible files
|   +-- index.php
|   +-- admin.php
|   +-- ...
+-- lupo-includes/               # Application code
|   +-- bootstrap.php
+-- lupo-config.php              # Config file (OUTSIDE web root)
+-- install/                     # Auto-installer interface
```

## Implementation Requirements

### Bootstrap Implementation

The `lupo-includes/bootstrap.php` file MUST:

1. Search in the exact order specified above
2. Set `LUPOPEDIA_CONFIG_PATH` constant when found
3. Fail gracefully with clear error message if not found
4. Log the search path for debugging

### Error Handling

```php
if (!$config_found) {
    $error_message = "Configuration file not found. Please create lupopedia-config.php in one of these locations:\n";
    foreach ($search_paths as $i => $path) {
        $error_message .= ($i + 1) . ". $path/lupopedia-config.php\n";
    }
    die($error_message);
}
```

## Validation

### Security Check

```php
// Verify config is not web-accessible
if (strpos($_SERVER['REQUEST_URI'], 'lupopedia-config.php') !== false) {
    die('Configuration file cannot be accessed directly.');
}
```

### Path Validation

```php
// Ensure we have absolute paths
$config_file = realpath($config_file);
if (!$config_file || !file_exists($config_file)) {
    die("Invalid configuration file path: $config_file");
}
```

## Migration Path

### From Existing Installations

If upgrading from an installation with `lupopedia-config.php` in the web root:

1. Move the config file one level above web root
2. Update any hardcoded paths in the config
3. Test the bootstrap search order
4. Update documentation to reflect new location

### For Development

Developers can use environment variable or local config:

```bash
# Development override
export LUPOPEDIA_CONFIG_PATH=/path/to/dev/lupopedia-config.php
```

---

**lupo_schema:** doctrine  
**tags:** configuration, security, auto-installer, bootstrap, shared-hosting
