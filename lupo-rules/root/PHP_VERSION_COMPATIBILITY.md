# PHP 5.6+ Compatibility Doctrine

---

**file_path_from_root:** lupo-rules/root/PHP_VERSION_COMPATIBILITY.md  
**web_path:** http://www.lupopedia.com/lupo-rules/root/PHP_VERSION_COMPATIBILITY.md  
**last_modified_utc:** 20260327215700  
**channel_id:** 42  
**actor_id:** 1  
**artifact_type:** doctrine  
**artifact_kind:** rule  

---

# PHP 5.6+ Compatibility Doctrine

## Core Rule

All code MUST run on PHP 5.6.0 or higher. No features introduced in PHP 7.0+ are permitted unless there is a documented polyfill.

## Forbidden PHP 7+ Features

| Feature | Reason | PHP 5.6 Alternative |
|---------|--------|---------------------|
| `??` null coalescing operator | PHP 7.0+ only | `isset($var) ? $var : $default` |
| `<=>` spaceship operator | PHP 7.0+ only | Manual comparison with if/else |
| `int`, `string`, `array` type hints | PHP 7.0+ only | Remove type hints, use `is_int()`, `is_string()` |
| Return type declarations (`: int`, `: string`) | PHP 7.0+ only | Remove return type declarations |
| `declare(strict_types=1)` | PHP 7.0+ only | Remove strict types declaration |
| Anonymous classes (`new class { ... }`) | PHP 7.0+ only | Use named classes |
| `random_bytes()` (use fallback) | PHP 7.0+ only | Use `mcrypt_create_iv()` or `openssl_random_pseudo_bytes()` |
| `??=` null coalescing assignment | PHP 7.4+ only | Use isset() check |
| Array destructuring with `[]` | PHP 7.1+ | Use `list()` construct |
| `Throwable` interface | PHP 7.0+ | Use `Exception` only |
| Multiple catch per exception | PHP 7.1+ | Use separate catch blocks |

## Required Polyfills

For PHP 5.6 compatibility, these functions MUST have polyfills:

```php
// In lupo-includes/functions/php56_polyfills.php

if (!function_exists('random_bytes')) {
    function random_bytes($length) {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length, $strong);
            if ($strong !== false) {
                return $bytes;
            }
        }
        // Fallback to less secure method
        $bytes = '';
        for ($i = 0; $i < $length; $i++) {
            $bytes .= chr(mt_rand(0, 255));
        }
        return $bytes;
    }
}

if (!function_exists('bin2hex')) {
    function bin2hex($str) {
        return unpack('H*', $str)[1];
    }
}
```

## Validation Rules

1. **No PHP 7+ syntax**: Code must not use any PHP 7+ features
2. **Type hints**: Only use `array` for function parameters (PHP 5.6 compatible)
3. **Return types**: No return type declarations allowed
4. **Anonymous functions**: Use `create_function()` or named functions
5. **Array syntax**: Use `array()` not `[]` for PHP 5.4 compatibility

## Testing Requirements

- All code must be tested on PHP 5.6.40 minimum
- Use `php -l filename.php` to check syntax
- No PHP 7+ warnings or errors

## Enforcement

- LEXA will flag any PHP 7+ syntax during code review
- Build process will fail on PHP 7+ features
- Unit tests must pass on PHP 5.6.40

---

**lupo_schema:** documentation  
**tags:** php56, compatibility, doctrine, rules, enforcement
