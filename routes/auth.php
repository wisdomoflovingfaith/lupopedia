<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AuthenticationController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| These routes handle unified authentication for both Lupopedia and Crafty Syntax
|
*/

// Unified Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'unifiedLogin'])->name('unified.login');
Route::post('/logout', [AuthController::class, 'unifiedLogout'])->name('logout');
Route::get('/session/info', [AuthController::class, 'getSessionInfo'])->name('session.info');
Route::post('/session/validate', [AuthController::class, 'validateSession'])->name('session.validate');

// Legacy Crafty Syntax Authentication Routes (with unified integration)
Route::get('/legacy/craftysyntax/login', function() {
    // Redirect to unified login with context
    return redirect('/login?system_context=crafty_syntax&from=legacy');
})->name('legacy.crafty.login');

Route::get('/legacy/craftysyntax/logout', function() {
    // Handle legacy logout with unified integration
    return app(AuthController::class)->unifiedLogout(request());
})->name('legacy.crafty.logout');

// Admin Authentication Management Routes
Route::middleware(['auth', 'admin'])->prefix('admin/authentication')->name('admin.authentication.')->group(function () {
    Route::get('/', [AuthenticationController::class, 'index'])->name('index');
    Route::get('/mapping', [AuthenticationController::class, 'mapping'])->name('mapping');
    Route::post('/mapping', [AuthenticationController::class, 'storeMapping'])->name('mapping.store');
    Route::delete('/mapping/{id}', [AuthenticationController::class, 'deleteMapping'])->name('mapping.delete');
    Route::get('/sessions', [AuthenticationController::class, 'sessions'])->name('sessions');
    Route::delete('/session/{sessionId}', [AuthenticationController::class, 'terminateSession'])->name('session.terminate');
    Route::get('/synchronization', [AuthenticationController::class, 'synchronization'])->name('synchronization');
    Route::post('/synchronize', [AuthenticationController::class, 'synchronizeUsers'])->name('synchronize');
});

// API Authentication Routes
Route::middleware(['api'])->prefix('api/auth')->name('api.auth.')->group(function () {
    Route::post('/login', [AuthController::class, 'apiLogin'])->name('login');
    Route::post('/logout', [AuthController::class, 'apiLogout'])->name('logout');
    Route::get('/user', [AuthController::class, 'apiUser'])->name('user');
    Route::post('/refresh', [AuthController::class, 'apiRefresh'])->name('refresh');
});

// Context-aware Authentication Routes
Route::middleware(['web'])->group(function () {
    // Crafty Syntax context routes
    Route::prefix('crafty')->name('crafty.auth.')->group(function () {
        Route::get('/login', function() {
            return view('auth.login', ['system_context' => 'crafty_syntax']);
        })->name('login');
        
        Route::post('/login', [AuthController::class, 'unifiedLogin'])->name('login.post');
        Route::post('/logout', [AuthController::class, 'unifiedLogout'])->name('logout');
    });
    
    // Lupopedia context routes
    Route::prefix('lupo')->name('lupo.auth.')->group(function () {
        Route::get('/login', function() {
            return view('auth.login', ['system_context' => 'lupopedia']);
        })->name('login');
        
        Route::post('/login', [AuthController::class, 'unifiedLogin'])->name('login.post');
        Route::post('/logout', [AuthController::class, 'unifiedLogout'])->name('logout');
    });
});

// Authentication Status Routes
Route::middleware(['web'])->group(function () {
    Route::get('/auth/status', function() {
        $authManager = app(\App\Auth\AuthManager::class);
        $unifiedUser = $authManager->getUnifiedUser();
        
        return response()->json([
            'authenticated' => $unifiedUser !== null,
            'user' => $unifiedUser ? [
                'id' => $unifiedUser['user']->id,
                'email' => $unifiedUser['user']->email,
                'name' => $unifiedUser['user']->name ?? $unifiedUser['user']->username,
                'context' => $unifiedUser['context'],
                'type' => $unifiedUser['user']->type ?? 'unknown'
            ] : null,
            'permissions' => $unifiedUser ? $authManager->getUserPermissions() : []
        ]);
    })->name('auth.status');
    
    Route::get('/auth/permissions', function() {
        $authManager = app(\App\Auth\AuthManager::class);
        return response()->json($authManager->getUserPermissions());
    })->name('auth.permissions');
});

// Session Management Routes
Route::middleware(['web'])->prefix('session')->name('session.')->group(function () {
    Route::get('/cleanup', function() {
        $sessionHandler = app(\App\Auth\UnifiedSessionHandler::class);
        $sessionHandler->cleanupExpiredSessions();
        
        return response()->json([
            'success' => true,
            'message' => 'Session cleanup completed'
        ]);
    })->name('cleanup');
    
    Route::get('/active', function() {
        $sessionHandler = app(\App\Auth\UnifiedSessionHandler::class);
        $authManager = app(\App\Auth\AuthManager::class);
        $unifiedUser = $authManager->getUnifiedUser();
        
        if ($unifiedUser) {
            $activeSessions = $sessionHandler->getActiveSessionsForUser($unifiedUser['user']->id);
            
            return response()->json([
                'success' => true,
                'sessions' => $activeSessions->toArray()
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Not authenticated'
        ]);
    })->name('active');
});

// Legacy Compatibility Routes
Route::middleware(['web'])->group(function () {
    // Redirect old Crafty Syntax login URLs to unified system
    Route::get('/livehelp/login.php', function() {
        return redirect('/login?system_context=crafty_syntax&from=legacy_livehelp');
    })->name('legacy.livehelp.login');
    
    Route::get('/admin/login.php', function() {
        return redirect('/login?system_context=crafty_syntax&from=legacy_admin');
    })->name('legacy.admin.login');
    
    // Handle legacy logout URLs
    Route::get('/livehelp/logout.php', function() {
        return app(AuthController::class)->unifiedLogout(request());
    })->name('legacy.livehelp.logout');
    
    Route::get('/admin/logout.php', function() {
        return app(AuthController::class)->unifiedLogout(request());
    })->name('legacy.admin.logout');
});
