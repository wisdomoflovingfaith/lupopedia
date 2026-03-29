# No Composer Doctrine

---

**file_path_from_root:** lupo-rules/root/NO_COMPOSER_DOCTRINE.md  
**web_path:** http://www.lupopedia.com/lupo-rules/root/NO_COMPOSER_DOCTRINE.md  
**last_modified_utc:** 20260327215700  
**channel_id:** 42  
**actor_id:** 1  
**artifact_type:** doctrine  
**artifact_kind:** rule  

---

# No Composer Doctrine

## Absolute Rule

Lupopedia MUST NOT use Composer, Packagist, or any external PHP package manager for dependency management. However, self-contained external libraries that are manually bundled in `lupo-includes/` are PERMITTED.

## Clarification: What's Forbidden vs Permitted

### FORBIDDEN (Composer-based)
- `composer.json` or `composer.lock` files
- `vendor/` directory created by Composer
- `require __DIR__ . '/vendor/autoload.php'`
- Any library that requires `composer install` to work
- Package managers (Composer, npm, yarn, etc.)

### PERMITTED (Self-contained libraries)
- External libraries placed in `lupo-includes/{library-name}/`
- Manual inclusion with `require_once`
- Libraries that work without Composer
- WordPress-style bundling (all dependencies in repo)
- See EXTERNAL_LIBRARIES_DOCTRINE.md for complete rules on external libraries

### RELATIONSHIP TO EXTERNAL_LIBRARIES_DOCTRINE

This doctrine forbids Composer and package managers. The **EXTERNAL_LIBRARIES_DOCTRINE.md** defines how self-contained external libraries may be used. Both doctrines must be followed together:

1. **NO_COMPOSER_DOCTRINE.md** - Prohibits Composer-based dependencies
2. **EXTERNAL_LIBRARIES_DOCTRINE.md** - Permits self-contained libraries with specific rules

**Example:** PHPMailer is allowed because it's self-contained in `lupo-includes/PHPMailer/` and follows the rules in EXTERNAL_LIBRARIES_DOCTRINE.md.

## Rationale

- Lupopedia is designed for shared hosting environments where Composer may not be available
- The project must work after a simple `git clone` and web server configuration
- No external dependencies beyond PHP core and MySQL
- Shared hosting often disables shell_exec() needed for Composer

## Forbidden Patterns

| Pattern | Why Forbidden | Alternative |
|---------|---------------|-------------|
| `vendor/` directory | Composer dependency folder | Use `lupo-includes/` for all dependencies |
| `composer.json` or `composer.lock` | Composer package files | Manual dependency management |
| `require __DIR__ . '/vendor/autoload.php'` | Composer autoloader | Custom autoloader with spl_autoload_register() |
| `use Some\External\Library` | External dependencies not in core | Include files directly with require_once |
| `composer install` command | Requires Composer | Manual installation process |
| `composer update` command | Requires Composer | Manual updates via git |

## Permitted Autoloading

### Custom Autoloader (PHP 5.6 compatible)

```php
// In lupo-includes/autoload.php
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    
    // Look in standard include paths
    $paths = [
        __DIR__ . '/classes/',
        __DIR__ . '/modules/',
        __DIR__ . '/functions/'
    ];
    
    foreach ($paths as $path) {
        $full_path = $path . $file;
        if (file_exists($full_path)) {
            require_once $full_path;
            return;
        }
    }
});
```

### Manual Includes

```php
// For core classes
require_once __DIR__ . '/class-DatabaseFactory.php';
require_once __DIR__ . '/class-pdo_db.php';

// For custom classes
require_once __DIR__ . '/classes/AuthService.php';
require_once __DIR__ . '/classes/AuthSessionManager.php';
```

## Directory Structure

```
lupo-includes/
├── autoload.php          # Custom autoloader
├── class-DatabaseFactory.php
├── class-pdo_db.php
├── classes/              # All custom classes
│   ├── AuthService.php
│   ├── AuthSessionManager.php
│   └── ...
├── modules/              # Reusable modules
│   ├── auth/
│   └── channels/
└── functions/            # Utility functions
    ├── security.php
    └── php56_polyfills.php
```

## Dependency Management

### Internal Dependencies Only

All dependencies must be:

1. **In-house**: Developed specifically for Lupopedia
2. **In-tree**: Checked into the repository
3. **PHP core**: Using built-in PHP functions
4. **MySQL native**: Using PDO with raw SQL

### External Libraries (FORBIDDEN)

- No Symfony components
- No Laravel packages
- No Guzzle HTTP client
- No Monolog logging
- No Doctrine ORM
- No Twig templating

## Validation

### Automated Checks

```bash
# Check for composer files
find . -name "composer.json" -o -name "composer.lock" -o -name "vendor" -type d

# Check for vendor/autoload.php includes
grep -r "vendor/autoload.php" --include="*.php"

# Check for external namespace imports
grep -r "use Symfony\\\|use Laravel\\\|use Guzzle" --include="*.php"
```

### Manual Review

- All require_once paths must be relative to project root
- No external package references in documentation
- Installation instructions must not mention Composer

## Installation Process

The installation process must be:

1. `git clone` the repository
2. Configure web server (Apache/Nginx)
3. Create database and run SQL script
4. Edit config file
5. Visit install.php

No `composer install` step allowed.

## Enforcement

- LEXA will reject any code with Composer dependencies
- ANUBIS will quarantine any files referencing vendor/autoload.php
- Build process will fail on composer.json detection

---

**lupo_schema:** documentation  
**tags:** no-composer, shared-hosting, dependencies, doctrine, rules
