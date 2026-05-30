# No Framework Doctrine

---

**file_path_from_root:** rules/root/NO_FRAMEWORK_DOCTRINE.md  
**web_path:** http://www.lupopedia.com/rules/root/NO_FRAMEWORK_DOCTRINE.md  
**last_modified_utc:** 20260327215700  
**channel_id:** 42  
**actor_id:** 1  
**artifact_type:** doctrine  
**artifact_kind:** rule  

---

# No Framework Doctrine

## Core Rule

Lupopedia MUST NOT use Laravel, Symfony, CodeIgniter, or any other PHP framework.

## Forbidden Patterns

| Pattern | Framework | Why Forbidden | Alternative |
|---------|-----------|---------------|-------------|
| `@extends('layout')` | Laravel Blade | Requires Blade engine | PHP include files |
| `@section('content')` | Laravel Blade | Requires Blade engine | PHP variables/functions |
| `@yield('content')` | Laravel Blade | Requires Blade engine | PHP echo statements |
| `{{ $variable }}` | Laravel Blade | Requires Blade engine | `<?php echo htmlspecialchars($variable); ?>` |
| `Route::get()` | Laravel routing | Requires Laravel router | Custom routing functions |
| `$request->input()` | Laravel request | Requires Request object | `$_POST`, `$_GET`, `filter_input()` |
| `Eloquent\Model` | Laravel ORM | Requires Eloquent | Raw PDO queries |
| `$this->validate()` | Laravel validation | Requires Validator | Manual validation functions |
| `middleware()` | Laravel | Requires middleware stack | Direct function calls |
| `View::make()` | Laravel views | Requires View system | PHP include/require |
| `DB::` facade | Laravel DB | Requires Laravel DB | PDO directly |
| `Config::get()` | Laravel config | Requires Config system | PHP constants/arrays |

## Permitted Patterns

### Template System (Pure PHP)

```php
// Instead of Blade templates, use PHP templates:
<?php include __DIR__ . '/templates/header.php'; ?>

<div class="content">
    <h1><?php echo htmlspecialchars($title); ?></h1>
    <p><?php echo htmlspecialchars($content); ?></p>
</div>

<?php include __DIR__ . '/templates/footer.php'; ?>
```

### Routing (Custom Functions)

```php
// Instead of Laravel routes:
// Route::get('/admin', 'AdminController@index');

// Use custom routing:
function handleRequest() {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    switch ($path) {
        case '/admin':
            require_once __DIR__ . '/controllers/admin.php';
            break;
        case '/login':
            require_once __DIR__ . '/controllers/login.php';
            break;
        default:
            http_response_code(404);
            echo 'Page not found';
    }
}
```

### Database Access (PDO Only)

```php
// Instead of Eloquent:
// $user = User::find($id);

// Use PDO directly:
$db = DatabaseFactory::getConnection();
$user = $db->fetchRow(
    "SELECT * FROM users WHERE id = :id",
    ['id' => $id]
);
```

### Input Handling (PHP Native)

```php
// Instead of Laravel:
// $email = $request->input('email');

// Use PHP native:
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if ($email === false) {
    $email = $_POST['email'] ?? '';
}
```

### Validation (Manual)

```php
// Instead of Laravel:
// $validated = $request->validate(['email' => 'required|email']);

// Use manual validation:
function validateEmail($email) {
    if (empty($email)) {
        return 'Email is required';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Invalid email format';
    }
    return true;
}
```

## Specific Violations Found

### Laravel Blade Templates

The following files contain Laravel Blade syntax and must be converted:

1. `database/lupopedia/content/app/views/admin/authentication/index.blade.php`
2. `database/lupopedia/content/app/views/admin/authentication/mapping.blade.php`

These files use:
- `@extends('layouts.admin')`
- `@section('title', '...')`
- `@section('content')`
- `{{ $variable }}`
- `@csrf`
- `@error('field')`
- `@endphp`

### Laravel References in Code

References to Laravel components found:
- `Illuminate\Http\Request` in some files
- `Route::` method calls in route files
- Blade template engine references

## Migration Plan

### Step 1: Convert Blade Templates

Convert `.blade.php` files to pure PHP:

```php
// Convert:
@extends('layouts.admin')
@section('content')
<div>{{ $title }}</div>
@endsection

// To:
<?php include __DIR__ . '/layouts/admin.php'; ?>
<div><?php echo htmlspecialchars($title); ?></div>
```

### Step 2: Remove Laravel Imports

Replace:
```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
```

With:
```php
// No imports needed - use PHP superglobals
```

### Step 3: Replace Routing

Convert Laravel routes to custom router:
```php
// From: Route::get('/admin', 'AdminController@index');
// To: Add case in handleRequest() switch
```

## Template System Replacement

### Layout System

```php
// layouts/admin.php
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($title ?? 'Admin'); ?></title>
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <?php include __DIR__ . '/partials/navbar.php'; ?>
    
    <main>
        <?php echo $content ?? ''; ?>
    </main>
    
    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
```

### Content Pages

```php
// admin/authentication/index.php
<?php
$title = 'Authentication Management';
$content = '
<div class="container">
    <h1>Authentication Management</h1>
    <!-- Content here -->
</div>';

include __DIR__ . '/../../layouts/admin.php';
?>
```

## Enforcement

### Automated Detection

```bash
# Find Blade templates
find . -name "*.blade.php"

# Find Laravel syntax
grep -r "@extends\|@section\|@yield\|{{ " --include="*.php"

# Find Laravel imports
grep -r "use Illuminate" --include="*.php"
```

### Manual Review

- All template files must end in `.php` not `.blade.php`
- No Laravel namespace imports
- No Laravel facades or helper functions
- All routing must go through custom router

## Success Criteria

- [ ] No `.blade.php` files exist
- [ ] No `@extends`, `@section`, `@yield` syntax
- [ ] No `{{ variable }}` syntax in PHP files
- [ ] No `Illuminate\` namespace imports
- [ ] No `Route::` method calls
- [ ] All templates use pure PHP
- [ ] Custom routing system in place

---

**lupo_schema:** documentation  
**tags:** no-framework, no-laravel, pure-php, doctrine, rules
