---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/CONFIGURATION_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/CONFIGURATION_DOCTRINE.md"
  last_modified_utc: "20260403113047"
  when_updated: "20260403113047"
  federation_node_id: 0
  channel_id: 42
  thread_id: "doctrine-header-repair"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "CONFIGURATION DOCTRINE"
  status: active
  tags:
    - "doctrine"
    - "header_repair"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260403113047"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  verified_via:
    type: "audit"
    script: "fix_doctrine_headers"
  next_action:
    - "Run: python lupo-scripts/apply_doctrine_prd_lineage.py --apply"
---

# file: CONFIGURATION_DOCTRINE — delegation: cursor:root

# Configuration Doctrine

## Purpose

Define the configuration file search algorithm and security requirements for Lupopedia, following the traditional open-source auto-installer pattern used by WordPress, phpBB, and similar applications for over 20 years.

## Search Algorithm

The Lupopedia bootstrap (`lupo-includes/bootstrap.php`) searches for `lupopedia-config.php` using this algorithm:

```php
// Search order:
$search_paths = [
    dirname($_SERVER['DOCUMENT_ROOT']),           // 1. Above web root
    dirname(dirname(__FILE__)),                    // 2. Above installation
    dirname(__FILE__)                              // 3. In installation
];

foreach ($search_paths as $path) {
    $config_file = $path . '/lupopedia-config.php';
    if (file_exists($config_file)) {
        require_once $config_file;
        break;
    }
}
```

## Configuration File Search Order

The application MUST search for `lupopedia-config.php` in this exact order:

```
1. One level above web document root
   Example: /home/user/lupopedia-config.php
   (when web root is /home/user/public_html/)

2. One level above the Lupopedia installation directory
   Example: /home/user/lupopedia-config.php
   (when Lupopedia is at /home/user/public_html/lupopedia/)

3. In the Lupopedia installation directory itself
   Example: /home/user/public_html/lupopedia/lupopedia-config.php
```

### Why This Order

| Level | Purpose | Security |
|-------|---------|-----------|
| **Above web root** | Most secure — config not accessible via web | ✅ Highest |
| **Above installation** | Common auto-installer pattern | ✅ High |
| **In installation** | Fallback for manual installs or testing | ⚠️ Lower |

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
├── lupopedia/                    # Web-accessible files
│   ├── index.php
│   ├── admin.php
│   └── ...
├── lupo-includes/               # Application code
│   └── bootstrap.php
├── lupo-config.php              # Config file (OUTSIDE web root)
└── install/                     # Auto-installer interface
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
