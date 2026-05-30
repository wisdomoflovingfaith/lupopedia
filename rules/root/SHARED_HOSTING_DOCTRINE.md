# Shared Hosting Compatibility Doctrine

---

**file_path_from_root:** rules/root/SHARED_HOSTING_DOCTRINE.md  
**web_path:** http://www.lupopedia.com/rules/root/SHARED_HOSTING_DOCTRINE.md  
**last_modified_utc:** 20260327215700  
**channel_id:** 42  
**actor_id:** 1  
**artifact_type:** doctrine  
**artifact_kind:** rule  

---

# Shared Hosting Compatibility Doctrine

## Core Rules

1. **Subdirectory installation**: Lupopedia MUST work when installed in any subdirectory (e.g., `example.com/lupopedia/`)
2. **No URL rewriting assumptions**: Use `LUPOPEDIA_PUBLIC_PATH` constant for all links
3. **No shell_exec() or system() calls**: Shared hosting often disables these
4. **No process forking**: `pcntl_fork()` is not available on shared hosting
5. **Memory limits**: Assume low memory (64MB typical)
6. **No .htaccess dependencies**: Must work without Apache-specific features
7. **No root directory writes**: Only write within the project directory

## Path Handling

### Always Use Constants

```php
// CORRECT - Uses constant for subdirectory compatibility
$base_url = rtrim(LUPOPEDIA_PUBLIC_PATH, '/');
$link = $base_url . '/admin.php';
$image_url = $base_url . '/images/logo.png';

// WRONG - Won't work in subdirectory
$link = '/admin.php';
$image_url = '/images/logo.png';
```

### Path Constants

```php
// These constants are defined in config.php
LUPOPEDIA_PATH          // Filesystem path to project root
LUPOPEDIA_PUBLIC_PATH   // Web-accessible URL path (includes subdirectory)
LUPOPEDIA_CONFIG_PATH   // Path to config file
```

### URL Generation

```php
// Function for generating URLs (PHP 7.4+ compatible)
function lupo_url($path) {
    $base = rtrim(LUPOPEDIA_PUBLIC_PATH, '/');
    $path = ltrim($path, '/');
    return $base . '/' . $path;
}

// Usage:
$login_url = lupo_url('login.php');
$admin_url = lupo_url('admin.php');
$css_url = lupo_url('css/style.css');
```

## Forbidden Functions

| Function | Why Forbidden | Alternative |
|----------|---------------|-------------|
| `shell_exec()` | Disabled on shared hosting | Avoid shell operations |
| `system()` | Disabled on shared hosting | Use PHP functions |
| `exec()` | Disabled on shared hosting | Use PHP functions |
| `passthru()` | Disabled on shared hosting | Use PHP functions |
| `pcntl_fork()` | Not available on shared hosting | Single-threaded only |
| `proc_open()` | Disabled on shared hosting | Use PHP functions |
| `symlink()` | Often disabled | Use copy/rename |
| `chmod()` | Limited permissions | Work with default permissions |

## Memory and Performance

### Assume Low Resources

```php
// Memory-efficient database queries
// Instead of fetching all rows:
$users = $db->fetchAll("SELECT * FROM users");

// Use pagination:
$users = $db->fetchAll(
    "SELECT * FROM users LIMIT :offset, :limit",
    ['offset' => $offset, 'limit' => $limit]
);

// Process large files in chunks
function processLargeFile($filename) {
    $handle = fopen($filename, 'r');
    while (!feof($handle)) {
        $chunk = fread($handle, 8192); // 8KB chunks
        processChunk($chunk);
    }
    fclose($handle);
}
```

### Avoid Large Includes

```php
// Instead of including everything:
require_once __DIR__ . '/all_classes.php';

// Include only what's needed:
if (needAuth()) {
    require_once __DIR__ . '/classes/AuthService.php';
}
```

## File System Constraints

### No Root Directory Access

```php
// WRONG - Tries to write outside project
file_put_contents('/tmp/lupo_cache.txt', $data);

// CORRECT - Writes within project
$cache_dir = __DIR__ . '/../cache/';
file_put_contents($cache_dir . 'cache.txt', $data);
```

### Use Relative Paths

```php
// CORRECT - Relative to project
$config_path = __DIR__ . '/../config.php';

// WRONG - Absolute system path
$config_path = '/var/www/html/lupopedia/config.php';
```

## Database Considerations

### Shared Hosting Database Limits

```php
// Use persistent connections carefully
$db = new PDO_DB($host, $user, $pass, $name);
// Don't use PDO::ATTR_PERSISTENT on shared hosting

// Close connections when done
$db = null; // Explicitly close

// Limit query complexity
// Avoid complex JOINs that may timeout
```

### Error Handling

```php
// Handle database connection failures gracefully
try {
    $db = DatabaseFactory::getConnection();
} catch (Exception $e) {
    // Show user-friendly error
    die("Database temporarily unavailable. Please try again later.");
}
```

## Session Management

### File-based Sessions (Default)

```php
// Use PHP's default file sessions - they work on shared hosting
session_start();

// Don't try to use database sessions unless necessary
// Shared hosting may limit database connections
```

### Session Security

```php
// Secure session settings for shared hosting
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // May not have HTTPS
ini_set('session.use_strict_mode', 1);
```

## Email Sending

### Use mail() Function

```php
// Simple mail() works on most shared hosting
$to = 'user@example.com';
$subject = 'Test';
$message = 'Hello';
$headers = 'From: webmaster@' . $_SERVER['HTTP_HOST'];

mail($to, $subject, $message, $headers);

// Don't assume SMTP is available
// Don't use external services without checking
```

## Installation Requirements

### Minimum Requirements

- PHP 7.4.0 or higher
- MySQL 5.5+ or MariaDB 10.0+
- 64MB PHP memory limit
- 100MB disk space
- No shell access required
- No root privileges required

### Installation Steps

1. Upload files via FTP/SCP
2. Create database via cPanel
3. Edit config.php
4. Set file permissions (755 for dirs, 644 for files)
5. Visit install.php in browser
6. Delete install.php after installation

## Testing on Shared Hosting

### Test Checklist

- [ ] Works in subdirectory: `/lupopedia/`
- [ ] URLs generated correctly with LUPOPEDIA_PUBLIC_PATH
- [ ] No shell_exec() or system() calls
- [ ] Database connection works with limited privileges
- [ ] File uploads work with tmp_dir restrictions
- [ ] Email sending works with mail()
- [ ] Sessions work without custom save path
- [ ] Memory usage stays under 64MB

### Common Issues

1. **URL paths broken** - Use LUPOPEDIA_PUBLIC_PATH constant
2. **Permission denied** - Check file permissions, don't use 777
3. **Memory exhausted** - Optimize queries, use pagination
4. **Database connection failed** - Check MySQL socket path
5. **Sessions not working** - Ensure session save path is writable

## Enforcement

### Code Review

- All URLs must use LUPOPEDIA_PUBLIC_PATH
- No shell function calls
- Memory usage under 64MB
- File operations within project directory

### Automated Checks

```bash
# Check for forbidden functions
grep -r "shell_exec\|system\|exec\|pcntl_fork" --include="*.php"

# Check for absolute paths
grep -r "^/var/www\|^/tmp\|^/etc" --include="*.php"

# Check memory usage
php -d memory_limit=64M -f script.php
```

---

**lupo_schema:** documentation  
**tags:** shared-hosting, subdirectory, compatibility, doctrine, rules
