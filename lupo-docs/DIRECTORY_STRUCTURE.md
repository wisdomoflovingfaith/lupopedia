# file: Lupopedia Directory Structure — session: L-LUPO-ANTIGRAVITY — delegation: antigravity:cursor:captain  — web_path: http://www.lupopedia.com/docs/DIRECTORY_STRUCTURE
---
lupopedia.headers:
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/DIRECTORY_STRUCTURE.md"
  version_when_written: "4.0.84"
  web_path: "http://www.lupopedia.com/docs/DIRECTORY_STRUCTURE"
  last_modified_utc: "20260307"
  channel_id: 1
  actor_id: 42
  actor_name: "antigravity"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Canonical documentation for Lupopedia directory structure and configuration mapping."
  mood_rgb: "4169E1"
  traits: ["canonical", "architecture", "directory_structure", "v4.0.64"]
  tags: ["directories", "structure", "configuration", "lupopedia-config"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupopedia-config.php", type: "references", weight: 1.0 }
    - { to: "lupo-docs/actors.md", type: "references", weight: 0.9 }
    - { to: "AGENTS.md", type: "references", weight: 0.8 }

lupopedia.footer:
  last_verified_utc: "20260307"
  last_verified_by: "antigravity"
---

# Lupopedia Directory Structure

Lupopedia follows a strict directory naming convention using the **`lupo-`** prefix for core system folders. This ensures namespacing, avoids collisions with user content, and supports the **Filesystem-First "Root Truth"** doctrine.

## The `lupo-` Prefix Convention

Standardizing directories with a prefix allows for easier identification of core system components and simplifies security rules (e.g., restricting access to non-prefixed folders).

| Directory | Purpose |
|-----------|---------|
| **`lupo-actors/`** | Actor workspaces and resource hubs (name-based). |
| **`lupo-agents/`** | AI agent configuration files and system prompts. |
| **`lupo-app/`** | Core application logic (Controllers, Services, Http, Middleware). |
| **`lupo-bin/`** | System binaries, CLI utilities (e.g., `lupo.php`), and boot scripts. |
| **`lupo-channels/`** | Communication entities, task threads, and coordination metadata. |
| **`lupo-config/`** | Modular configuration, atoms (SSOT), and global settings. |
| **`lupo-content/`** | Themes, uploads, plugins, and public assets. |
| **`lupo-database/`** | Database schema, SQL seeds, migrations, and TOON references. |
| **`lupo-includes/`** | Core PHP functions, bootstrap, and vendor-like helper classes. |
| **`lupo-logs/`** | Centralized system and error logs. |
| **`lupo-routes/`** | Routing definitions for the modular loader. |
| **`lupo-sessions/`** | Runtime session storage (PHP and file-based). |
| **`lupo-tests/`** | Unit, regression, and integration test suites. |
| **`lupo-tools/`** | Specialized development tools and VSX extension source. |

## Configuration & Path Mapping

The directory structure is primarily defined and controlled via **`lupopedia-config.php`**. This file is generated during installation and bootstraps the entire path resolution logic.

### Path Constants

Lupopedia uses two primary path constants for resolution:

1.  **`ABSPATH`**: The absolute filesystem path to the project root.
2.  **`LUPOPEDIA_PATH`**: Usually identical to `ABSPATH`, used within modules for relative resolution.
3.  **`LUPOPEDIA_PUBLIC_PATH`**: The web-accessible base URL (e.g., `/lupopedia/`).

### Directory Constants

Directories are mapped to constants in `lupopedia-config.php`. Code should **ALWAYS** use these constants instead of hardcoded paths.

```php
// Standard directory mappings
define('LUPO_ADMIN_DIR',    'lupo-admin');
define('LUPO_INCLUDES_DIR', 'lupo-includes');
define('LUPO_CONTENT_DIR',  'lupo-content');
define('LUPO_ACTORS_DIR',   'lupo-actors');
define('LUPO_CHANNELS_DIR', 'lupo-channels');
define('LUPO_APP_DIR',      'lupo-app');
define('LUPO_DATABASE_DIR', 'lupo-database');
define('LUPO_LOGS_DIR',     'lupo-logs');
```

### Path Resolution Logic

Paths are typically built using `ABSPATH` and the relevant constant:

```php
// Correct way to build a path to an actor's profile
$actor_path = ABSPATH . LUPO_ACTORS_DIR . '/' . $actor_name;

// Correct way to build a public URL for a theme asset
$asset_url = LUPOPEDIA_URL . LUPO_CONTENT_DIR . '/themes/default/css/style.css';
```

## Actor Workspace Structure

Under **`lupo-actors/{actor_name}/`**, the structure is further subdivided into standard resource folders:

*   **`apps/`**: Custom applications.
*   **`lupo-tools/`**: Actor-specific scripts.
*   **`lupo-docs/`**: Documentation.
*   **`db-changes/`**: Migrations.
*   **`lupo-api/`**: API definitions.
*   **`lupo-prompts/`**: Instructions for the agent.
*   **`skills/`**: Reusable modular capabilities (v4.0.64+).
*   **`www/`**: Web-accessible profile content (v4.0.64+).

## Boot Flow and Discovery

1.  **`index.php`** (or CLI `lupo.php`) is invoked.
2.  **`lupopedia-config.php`** is required, defining the directory constants.
3.  **`lupo-includes/bootstrap.php`** is loaded, initializing the `mydatabase` connection and loading core Services (e.g., `ActorService`).
4.  The system uses the `LUPO_*` constants to discover and load modules from their respective standardized locations.
