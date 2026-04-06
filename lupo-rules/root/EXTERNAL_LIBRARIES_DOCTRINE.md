# External Libraries Doctrine

---

**file_path_from_root:** lupo-rules/root/EXTERNAL_LIBRARIES_DOCTRINE.md  
**web_path:** http://www.lupopedia.com/lupo-rules/root/EXTERNAL_LIBRARIES_DOCTRINE.md  
**last_modified_utc:** 20260327210500  
**channel_id:** 42  
**actor_id:** 1  
**artifact_type:** doctrine  
**artifact_kind:** rule  

---

# External Libraries Doctrine

## Core Principle

Lupopedia is a self-contained application designed for shared hosting environments. All dependencies must be included in the repository and require no external installation steps beyond `git clone`.

## Permitted Locations for External Code

External libraries, frameworks, or code blocks MUST be placed in:

```
lupo-includes/
├── PHPMailer/          # Example: PHPMailer library
├── tcpdf/              # Example: PDF generation
├── other-library/      # Any other self-contained library
└── classes/            # Custom Lupopedia classes (not external)
```

## Rules for Including External Code

1. **No Composer**: The library must be self-contained and not require `composer install`.
2. **Manual Inclusion**: Use `require_once` or `include_once` with explicit paths.
3. **No Autoloader Magic**: No reliance on `spl_autoload_register()` from external code unless that code is included in the library itself.
4. **PHP 7.4+ Compatibility**: The library must work on PHP 7.4+.
5. **No Framework Dependencies**: The library must not require Laravel, Symfony, or other frameworks.

## Example: Including PHPMailer

```php
// Correct: Manual inclusion
require_once dirname(__DIR__) . '/lupo-includes/PHPMailer/PHPMailer.php';
require_once dirname(__DIR__) . '/lupo-includes/PHPMailer/SMTP.php';
require_once dirname(__DIR__) . '/lupo-includes/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
// ... configure and send
```

```php
// Incorrect: Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';  // FORBIDDEN
```

## When to Use External Libraries

- **Email sending**: PHPMailer (already in `lupo-includes/PHPMailer/`)
- **PDF generation**: tcpdf or similar (must be bundled)
- **Image processing**: GD extension (PHP core) or bundled library
- **Markdown parsing**: Bundled library in `lupo-includes/` 

## When NOT to Use External Libraries

- **Frameworks**: Laravel, Symfony, CodeIgniter — these are too heavy and require Composer
- **ORM**: Eloquent, Doctrine — use raw SQL with PDO
- **Authentication**: Use Lupopedia's own `lupo_auth_users` system
- **Routing**: Use Lupopedia's `lupo_route_slug()` function

## Audit Checklist

Before adding any external code, verify:

- [ ] Code is placed in `lupo-includes/{library-name}/` 
- [ ] No `composer.json` or `composer.lock` files
- [ ] No `vendor/` directory references
- [ ] Works with PHP 7.4+
- [ ] Includes are manual (`require_once` with explicit paths)
- [ ] Does not rely on external network requests (CDN, APIs) for core functionality

## Permitted vs Forbidden Patterns

### Permitted (Good)

| Pattern | Example | Notes |
|---------|---------|-------|
| **Self-contained libraries in `lupo-includes/`** | `lupo-includes/PHPMailer/` | Must be included manually, no Composer |
| **Manual includes** | `require_once __DIR__ . '/PHPMailer/PHPMailer.php';` | Direct file inclusion |
| **Pure PHP libraries** | Any library that works without autoloader magic | Must run on PHP 7.4+ |
| **WordPress-style bundling** | All dependencies in the project directory | Traditional open-source approach |

### Forbidden (Bad)

| Pattern | Example | Why |
|---------|---------|-----|
| **Composer** | `composer.json`, `vendor/autoload.php` | Requires Composer, not available on shared hosting |
| **Composer autoloader** | `require __DIR__ . '/vendor/autoload.php';` | Relies on external tool |
| **Laravel Framework** | Any `Illuminate\*` namespace | Full framework, autoloader, dependencies |
| **Symfony Components** | Any `Symfony\*` namespace | Usually require Composer |
| **NPM/Node** | Any JavaScript package manager | Not relevant for PHP core |
| **External CDN dependencies** | `https://cdn.jsdelivr.net/...` | Must work offline, must be bundled |

## Implementation Guidelines

### Directory Structure

```
lupo-includes/
├── PHPMailer/
│   ├── src/
│   │   ├── PHPMailer.php
│   │   ├── SMTP.php
│   │   └── Exception.php
│   └── (no composer.json, no vendor/)
├── tcpdf/
│   ├── tcpdf.php
│   ├── tcpdf_config.php
│   └── (no composer.json, no vendor/)
└── classes/
    ├── AuthService.php
    └── (Lupopedia-specific classes)
```

### Loading Pattern

```php
// In your application code:
require_once __DIR__ . '/lupo-includes/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/lupo-includes/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/lupo-includes/PHPMailer/src/Exception.php';

// Use the library
$mail = new PHPMailer\PHPMailer\PHPMailer(true);
```

## Migration from Composer Libraries

If you find a library that only distributes via Composer:

1. **Download the source** (not the package)
2. **Extract to `lupo-includes/{library-name}/`**
3. **Remove Composer files** (`composer.json`, `composer.lock`, `vendor/`)
4. **Update includes** to use manual paths
5. **Test** functionality

## Examples of Good External Libraries

- **PHPMailer**: Email sending (self-contained)
- **TCPDF**: PDF generation (self-contained)
- **SimplePie**: RSS parsing (self-contained)
- **HtmlPurifier**: HTML sanitization (self-contained)

## Examples of Bad Dependencies

- **Laravel**: Full framework (requires Composer)
- **Symfony Components**: Usually require Composer
- **Doctrine ORM**: Requires Composer and autoloader
- **Guzzle HTTP**: Requires Composer

## Enforcement

### Automated Detection

```bash
# Check for Composer files
find . -name "composer.json" -o -name "composer.lock" -o -type d -name "vendor"

# Check for vendor/autoload.php
grep -r "vendor/autoload.php" --include="*.php"

# Check external libraries are in right place
find lupo-includes -maxdepth 1 -type d | grep -v "^\.$"
```

### Manual Review

- All external code must be in `lupo-includes/`
- No Composer references anywhere
- Manual includes only
- PHP 7.4 compatibility verified

---

**lupo_schema:** documentation  
**tags:** external-libraries, phpmailer, no-composer, self-contained, doctrine
