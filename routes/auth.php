<?php

/**
 * DEPRECATED: Laravel route file. This project does not use Laravel or middleware.
 *
 * Use the plain PHP route map instead:
 *   $routes = require __DIR__ . '/auth_routes.php';
 *
 * Wire routes in your own front controller or router using:
 *   - Arrays for request input ($_GET, $_POST, or parsed body)
 *   - AuthGuard (App\Auth\AuthGuard) for auth checks
 *   - Controller methods that accept array input and return arrays
 *   - Your code to send JSON (header + echo json_encode) or redirect (header('Location: ...'))
 *
 * Do not use Illuminate\Support\Facades\Route or any middleware.
 */

trigger_error('routes/auth.php is deprecated; use auth_routes.php and your own router', E_USER_DEPRECATED);

return require __DIR__ . '/auth_routes.php';
