---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-08
author: Wolfie (Eric Robin Gerdes)
architect: Captain Wolfie
dialog:
  speaker: cursor
  target: @everyone
  message: "Created CONFIGURATION_DOCTRINE.md documenting Lupopedia's WordPress-style configuration model, single config file approach, and why it avoids modern framework patterns."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "configuration"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
file:
  title: "Lupopedia Configuration Doctrine"
  description: "Doctrine explaining Lupopedia's WordPress-style configuration system and why it avoids modern framework patterns"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: "Captain Wolfie"
---

# ðŸŸ¦ **Lupopedia Configuration Doctrine**

## **Why Lupopedia Does NOT Use /src, Composer, .env, or Frameworkâ€‘Style Layouts**

This doctrine explains the architectural philosophy behind Lupopedia's configuration system and file layout. It exists so that AI tools, contributors, and future maintainers understand why Lupopedia is intentionally built differently from modern frameworkâ€‘driven PHP applications.

**This is not an accident.**  
**This is not ignorance.**  
**This is hardâ€‘earned experience.**

---

# ðŸŸ© **1. Lupopedia Must Run Everywhere â€” Not Just in Perfect Environments**

Lupopedia is designed to run on:

- shared hosting
- cPanel
- cheap VPS
- Apache with bad configs
- Nginx with missing rules
- servers without Composer
- servers without Docker
- servers without SSH
- servers where you only have FTP
- servers where you can't modify the document root

This is the same environment Crafty Syntax survived in for 20 years.

Modern frameworks assume:

- you control the server
- you can edit vhosts
- you can run Composer
- you can use .env files
- you have root access
- you have a perfect deployment pipeline

Lupopedia assumes none of that.

This is why the configuration system is built the way it is.

---

# ðŸŸ¦ **2. Lupopedia Installation Structure and Config File Location**

## **Installation Location**

Lupopedia is **NOT** installed at the web server document root.  
It **ALWAYS** lives inside a subdirectory (e.g., `/lupopedia/`).  
This is intentional and required for backward compatibility with Crafty Syntax.

**Example structure:**
```
/public_html/                    â† Document root
  /lupopedia/                    â† Lupopedia installation (subdirectory)
    /index.php
    /lupo-includes/
    /lupo-content/
    ...
/lupopedia-config.php            â† Config file (outside web root, preferred)
```

## **Config File Search Logic (MANDATORY - DO NOT SIMPLIFY)**

The config file `lupopedia-config.php` is **NEVER** assumed to be in:
- âŒ The project root
- âŒ The document root
- âŒ The same directory as `index.php`

Instead, the config file is searched in the following **exact order**:

1. **One directory ABOVE the server's DOCUMENT_ROOT** (Most secure, preferred)
   ```php
   dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php'
   ```
   Example: If `DOCUMENT_ROOT = /home/user/public_html`, searches `/home/user/lupopedia-config.php`

2. **One directory above DOCUMENT_ROOT + the Lupopedia public path**
   ```php
   dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php'
   ```
   Example: If `DOCUMENT_ROOT = /home/user/public_html` and `LUPOPEDIA_PUBLIC_PATH = '/lupopedia'`, searches `/home/user/lupopedia/lupopedia-config.php`

3. **Inside the Lupopedia directory itself** (Fallback)
   ```php
   LUPOPEDIA_PATH . '/lupopedia-config.php'
   ```
   Example: If Lupopedia is in `/home/user/public_html/lupopedia/`, searches `/home/user/public_html/lupopedia/lupopedia-config.php`

If none of these exist, the installer (`lupopedia-setup.php`) is executed.

## **CRITICAL RULES FOR CURSOR**

**DO NOT:**
- âŒ Move Lupopedia files to the document root
- âŒ Rewrite the config search logic
- âŒ Assume WordPress-style installation patterns
- âŒ Simplify the path logic
- âŒ Collapse the search paths into a single location
- âŒ Assume the config file is next to `index.php`
- âŒ Assume the config file is inside the Lupopedia folder
- âŒ Assume the config file is inside the document root

**MUST:**
- âœ… Preserve the multi-path config search logic exactly as written
- âœ… Support installs where the config file is stored OUTSIDE the public web root for security
- âœ… Maintain backward compatibility with Crafty Syntax subdirectory installations

## **Why This Matters**

Because you've lived through:

- remote file includes
- directory traversal
- misconfigured Apache
- PHP files being downloaded instead of executed
- shared hosting exposing config files
- attackers scanning for `/config/` and `/src/`
- people calling .php files directly

You learned the hard way that:

**If a file contains secrets, it must not be webâ€‘accessible.**  
**Ever. Under any circumstances.**

The multi-path search allows:
- Maximum security (config outside web root)
- Flexibility (works in different hosting environments)
- Backward compatibility (Crafty Syntax subdirectory pattern)
- Progressive fallback (secure â†’ less secure â†’ local)

This protects:

- DB credentials
- salts
- keys
- node identity
- federation identity
- prefix
- runtime constants

No framework can match this portability and security.

---

# ðŸŸ© **3. Remote Index Doctrine (MANDATORY FOR CURSOR)**

Lupopedia is designed to run from a subdirectory (e.g., `/lupopedia/`) and **MUST** support being loaded from **ANY directory** on the host website via an optional `remote-index.php` file.

## **Two Entry Points**

Lupopedia has two entry point files:

1. **`index.php`** - Located inside the Lupopedia directory (`/lupopedia/index.php`)
   - Automatically detects the folder name for `LUPOPEDIA_PUBLIC_PATH`
   - Uses `__DIR__` to determine `LUPOPEDIA_PATH`
   - Standard entry point for direct access to Lupopedia

2. **`remote-index.php`** - Can be placed **ANYWHERE** on the host website
   - Portable entry point that loads Lupopedia from its subdirectory
   - Requires manual configuration of `LUPOPEDIA_PUBLIC_PATH`
   - Allows Lupopedia to be loaded from any directory without modifying the host website

## **RULE 1: Lupopedia is ALWAYS installed in a subdirectory**

Lupopedia is **NOT** installed at the document root. It **ALWAYS** lives inside a folder such as `/lupopedia/`. This is intentional and required for backward compatibility with Crafty Syntax.

**Cursor must NOT attempt to move Lupopedia files to the document root.**

## **RULE 2: remote-index.php can be placed ANYWHERE**

The file `remote-index.php` is a portable entry point that may be placed in **ANY directory** on the host website. It must load Lupopedia from its subdirectory without requiring the user to modify their website.

**Example use cases:**
- Placed in document root to load Lupopedia from `/lupopedia/` subdirectory
- Placed in `/blog/` to load Lupopedia from `/lupopedia/` subdirectory
- Placed in `/admin/` to load Lupopedia from `/lupopedia/` subdirectory
- Any other directory structure

**Cursor must NOT rewrite or relocate `remote-index.php`.**

## **RULE 3: Config file search order MUST NOT be changed**

The config file (`lupopedia-config.php`) is searched in **EXACTLY** this order:

1. One directory ABOVE the server's `DOCUMENT_ROOT`
2. One directory above `DOCUMENT_ROOT` + `LUPOPEDIA_PUBLIC_PATH`
3. Inside the Lupopedia directory itself

**Cursor must NOT simplify, collapse, or reorder these search paths.**

Both `index.php` and `remote-index.php` use the **exact same** config search logic.

## **RULE 4: DOCUMENT_ROOT is NOT the install root**

**Cursor must NOT assume that Lupopedia lives in `DOCUMENT_ROOT`.**  
`DOCUMENT_ROOT` is **ONLY** used as a reference point for locating the config file.

Lupopedia lives in a subdirectory, and `DOCUMENT_ROOT` is just a reference point for security (placing config files outside the web root).

## **RULE 5: Lupopedia must remain portable and non-intrusive**

**Cursor must NOT:**
- âŒ Move files to the root
- âŒ Rewrite `.htaccess`
- âŒ Assume WordPress-style installation
- âŒ Assume config files live next to `index.php`
- âŒ Assume a single install path
- âŒ Remove or simplify multi-path logic
- âŒ Require modification of host website structure
- âŒ Force Lupopedia into document root

**The multi-path config search is REQUIRED** for:
- Security (config outside web root)
- Portability (works in different hosting environments)
- Compatibility (legacy Crafty Syntax installations)
- Flexibility (remote-index.php can be anywhere)

**Cursor must preserve this architecture exactly.**

---

# ðŸŸ© **4. Every Executable File Checks for LUPOPEDIA_CONFIG_LOADED**

Every include, class, and script begins with:

```php
if (!defined('LUPOPEDIA_CONFIG_LOADED')) exit;
```

This is not paranoia.  
This is battleâ€‘tested security.

You've lived through:

- direct file access
- remote file inclusion
- attackers calling .php files directly
- people bypassing index.php
- shared hosting misconfigurations

Modern devs don't understand this because:

- frameworks hide everything
- autoloaders protect them
- routing layers intercept requests
- they've never deployed to shared hosting
- they've never been hacked on a $5/mo server

You have.

This guard is simple, universal, and bulletproof.

---

# ðŸŸ¦ **5. Lupopedia Does NOT Use /src or Frameworkâ€‘Style Directory Layouts**

When AI tools tried to generate:

```
/src/
/vendor/
/app/
/config/
/bootstrap/
/routes/
```

â€¦it was doing what modern frameworks do.

But Lupopedia is not a framework.  
Lupopedia is a portable application.

**Why you reject /src:**

- `/src` is often inside the document root
- shared hosting doesn't allow moving it
- `.htaccess` is not always respected
- PHP files can be downloaded if PHP breaks
- attackers scan `/src` by default
- frameworks assume perfect server configs
- you've been hacked through exposed `/src` before

**Your architecture is:**

```
/lupopedia-config.php        â† outside web root
/public_html/
    /craftysyntax/
    /lupopedia/
        /classes/
        /includes/
        /modules/
        /assets/
        index.php
```

This is the correct layout for a portable PHP application.

---

# ðŸŸ© **6. Lupopedia Avoids .env Files Entirely**

**Why?**

Because .env files:

- are often placed in the document root
- are often worldâ€‘readable
- are often misconfigured
- are often leaked
- rely on server configuration
- rely on environment variables being supported
- are not portable across shared hosting

You've seen .env files leaked in the wild.  
You've seen people accidentally commit them.  
You've seen servers serve them as plain text.

So Lupopedia uses:

```php
define('LUPO_DB_HOST', '...');
define('LUPO_DB_NAME', '...');
define('LUPO_DB_USER', '...');
define('LUPO_DB_PASS', '...');
```

Inside a file outside the web root.

This is the safest, most portable method.

---

# ðŸŸ¦ **7. Lupopedia Avoids Composer Autoloaders**

Not because you don't know them â€”  
but because Composer:

- requires CLI access
- requires SSH
- requires root or userâ€‘level package installs
- requires a writable filesystem
- requires a build pipeline
- is not supported on shared hosting
- breaks portability
- exposes `/vendor/` to the web root
- encourages frameworkâ€‘style layouts

Lupopedia must run on:

- FTPâ€‘only servers
- shared hosting
- legacy PHP environments
- servers without Composer

So Lupopedia uses:

- simple includes
- predictable file paths
- no autoloading magic
- no dependency trees

This is intentional.

---

# ðŸŸ© **8. lupopedia-config.php is the Single Source of Truth**

Lupopedia uses a WordPress-style installation model:

- All runtime variables are defined in `lupopedia-config.php`
- This file is generated by the installer
- Cursor must not create additional config files
- Cursor must not create environment variable systems
- Cursor must not hardcode values
- All table names must use `LUPO_PREFIX` from `lupopedia-config.php`
- All database credentials, salts, keys, and node identity values come from this file

**Cursor must treat `lupopedia-config.php` as the single source of truth for configuration.**

**Cursor must NOT modify the structure or logic of `lupopedia-config.php`.**  
**Cursor may ONLY add new configuration constants or settings when explicitly instructed.**  
**Cursor must NOT convert this file to OOP, namespaces, autoloaders, or framework-based configuration.**  
**The procedural WordPress-style configuration pattern must remain intact.**

---

# ðŸŸ¦ **9. Table Names Use LUPO_PREFIX**

All database table references must use the `LUPO_PREFIX` constant from `lupopedia-config.php`:

```php
$table_name = LUPO_PREFIX . 'contents';
$table_name = LUPO_PREFIX . 'atoms';
$table_name = LUPO_PREFIX . 'dialog_messages';
```

**Never hardcode table names.**  
**Always use `LUPO_PREFIX`.**

This allows:

- multiple installations in the same database
- easy prefix changes
- portability across environments
- WordPress-style multi-site capability

---

# ðŸŸ© **10. Lupopedia's Security Model Comes From Experience, Not Theory**

You've lived through:

- remote file includes
- SQL injection
- XSS
- CSRF
- directory traversal
- config leaks
- PHP execution failures
- shared hosting misconfigurations
- attackers calling .php files directly
- Crafty Syntax's early vulnerabilities

Every scar became a doctrine rule.

Lupopedia is built on:

- simplicity
- predictability
- portability
- explicit guards
- explicit includes
- explicit configuration
- explicit file placement

This is not "old school."  
This is battleâ€‘tested engineering.

---

# ðŸŸ© **11. bootstrap.php Must Remain Procedural**

The `bootstrap.php` file is the procedural initialization file that sets up error reporting, database connections, security headers, and timezone configuration.

**Cursor must NOT modify the structure or logic of `bootstrap.php`.**  
**Cursor may ONLY add new include statements or initialization hooks when explicitly instructed.**  
**Cursor must NOT convert this file to OOP, namespaces, autoloaders, or framework patterns.**  
**The procedural bootstrap is intentional and must remain intact.**

This ensures:
- Predictable initialization order
- Compatibility with shared hosting environments
- No hidden magic or autoloader dependencies
- Explicit, traceable startup sequence
- Progressive enhancement compatibility

---

# ðŸŸ¦ **12. Hybrid Architecture: Procedural Loaders, OOP Subsystems**

Lupopedia uses a **hybrid architecture** that balances simplicity with organization:

**Procedural Layer (Loaders & Bootstrap):**
- `lupopedia-config.php` - procedural configuration
- `bootstrap.php` - procedural initialization
- `lupopedia-loader.php` - procedural orchestrator
- All `*-loader.php` files - procedural subsystem loaders
- These files must remain procedural and must NOT be converted to OOP

**OOP Layer (Subsystems):**
- Database classes (e.g., `class-pdo_db.php`)
- Module classes (inside `/lupo-includes/modules/`)
- Agent classes (e.g., `class-thoth.php`, `class-hermes.php`)
- UI controller classes (inside `/lupo-includes/ui/`)
- Semantic engine classes (e.g., `class-semanticextration.php`)
- These subsystems **may use OOP classes** and OOP is **encouraged** for organization

**What is FORBIDDEN:**
- âŒ Namespaces (`namespace Lupopedia\...`)
- âŒ Autoloaders (Composer, PSR-4, spl_autoload_register)
- âŒ Composer dependencies
- âŒ Framework patterns (Dependency Injection containers, Service Locators)
- âŒ Converting loader files to OOP

**What is ALLOWED:**
- âœ… OOP classes in subsystem files (`class-*.php`)
- âœ… Simple `require_once` includes (no autoloading)
- âœ… Procedural functions alongside OOP classes
- âœ… Hybrid approach: procedural loaders call OOP subsystem classes

**Example Structure:**
```
lupopedia-config.php          â† Procedural (must stay procedural)
bootstrap.php                 â† Procedural (must stay procedural)
lupopedia-loader.php          â† Procedural (must stay procedural)
  â”œâ”€â”€ functions-core.php      â† Procedural (may contain functions)
  â”œâ”€â”€ modules/module-loader.php â† Procedural (loads OOP modules)
  â”œâ”€â”€ agents/agent-loader.php â† Procedural (loads OOP agent classes)
  â””â”€â”€ ui/ui-loader.php        â† Procedural (loads OOP UI controllers)

lupo-includes/
  â”œâ”€â”€ class-pdo_db.php        â† OOP class (allowed and encouraged)
  â”œâ”€â”€ class-thoth.php         â† OOP class (allowed and encouraged)
  â””â”€â”€ modules/
      â””â”€â”€ crafty_syntax/
          â””â”€â”€ class-crafty.php â† OOP class (allowed and encouraged)
```

This hybrid approach provides:
- **Simplicity** at the loader level (no autoloader complexity)
- **Organization** at the subsystem level (OOP classes for complex logic)
- **Portability** (no Composer, no namespaces, works everywhere)
- **Explicit dependencies** (clear require_once statements)

---

# ðŸŸ© **13. Summary for AI Tools (Cursor, Windsurf, Claude)**

Lupopedia's configuration and file layout are intentional:

**Procedural Layer (Must Stay Procedural):**
- `lupopedia-config.php` - procedural configuration (structure must not be modified)
- `bootstrap.php` - procedural initialization (structure must not be modified)
- All `*-loader.php` files - procedural subsystem loaders

**OOP Layer (Allowed and Encouraged):**
- Subsystem classes (database, modules, agents, UI controllers, semantic engine)
- OOP is allowed and encouraged inside subsystem files
- Simple `require_once` includes (no autoloaders)

**Forbidden Patterns:**
- No `/src` directory
- No Composer
- No `.env` files
- No framework structure
- No autoloaders
- No namespaces
- No hardcoded paths
- No direct file access

**Security & Portability:**
- Config lives outside the web root
- Every file checks `LUPOPEDIA_CONFIG_LOADED`
- Everything is portable
- Everything is secure
- Everything is predictable

**This is not a limitation.**  
**This is a hybrid architecture design philosophy.**

---

## **Related Documentation**

- [WHY_NO_FRAMEWORKS.md](WHY_NO_FRAMEWORKS.md) â€” Why Lupopedia avoids frameworks entirely
- [For Installers and Users](../developer/dev/FOR_INSTALLERS_AND_USERS.md) â€” User-friendly explanation of WordPress-style configuration
- [Database Philosophy](../architecture/DATABASE_PHILOSOPHY.md) â€” Application-first validation that supports simple configuration
- [Database Schema](../schema/DATABASE_SCHEMA.md) â€” All table references must use `LUPO_PREFIX`

---

*Last Updated: January 2026*  
*Version: 4.0.1*  
*Author: Captain Wolfie*
