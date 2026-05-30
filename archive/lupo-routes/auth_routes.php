<?php

/**
 * Auth route map — plain PHP. No Laravel, no middleware.
 * Returns an array of [method => path => [controllerClass, methodName]] for your router.
 * Controllers receive array input and return arrays; caller sends JSON or redirects.
 */

return [
    'GET' => [
        '/login' => null, // render login form; POST /login -> AuthController::unifiedLogin
        '/session/info' => [\App\Http\Controllers\AuthController::class, 'getSessionInfo'],
        '/session/validate' => [\App\Http\Controllers\AuthController::class, 'validateSession'],
        '/legacy/craftysyntax/login' => null, // redirect to /login?system_context=crafty_syntax&from=legacy
        '/legacy/craftysyntax/logout' => [\App\Http\Controllers\AuthController::class, 'unifiedLogout'],
        '/admin/authentication' => [\App\Http\Controllers\Admin\AuthenticationController::class, 'getIndexData'],
        '/admin/authentication/mapping' => [\App\Http\Controllers\Admin\AuthenticationController::class, 'getMappingData'],
        '/admin/authentication/sessions' => [\App\Http\Controllers\Admin\AuthenticationController::class, 'getActiveSessions'],
        '/admin/authentication/synchronization' => null, // use getSessionStats, getSyncStatistics, etc.
        '/auth/status' => null, // use AuthGuard + getUnifiedUser, return JSON
        '/auth/permissions' => null, // when implemented: return permissions combining user_id, department_id, and channel_roles (no group tables)
        '/session/cleanup' => null,
        '/session/active' => null,
        '/livehelp/login.php' => null,
        '/admin/login.php' => null,
        '/livehelp/logout.php' => [\App\Http\Controllers\AuthController::class, 'unifiedLogout'],
        '/admin/logout.php' => [\App\Http\Controllers\AuthController::class, 'unifiedLogout'],
    ],
    'POST' => [
        '/login' => [\App\Http\Controllers\AuthController::class, 'unifiedLogin'],
        '/logout' => [\App\Http\Controllers\AuthController::class, 'unifiedLogout'],
        '/session/validate' => [\App\Http\Controllers\AuthController::class, 'validateSession'],
        '/admin/authentication/mapping' => [\App\Http\Controllers\Admin\AuthenticationController::class, 'storeMapping'],
        '/admin/authentication/synchronize' => null, // implement if needed; no Laravel
    ],
    'DELETE' => [
        '/admin/authentication/mapping/{id}' => [\App\Http\Controllers\Admin\AuthenticationController::class, 'deleteMapping'],
        '/admin/authentication/session/{sessionId}' => null, // implement session termination in front controller if needed
    ],
];
