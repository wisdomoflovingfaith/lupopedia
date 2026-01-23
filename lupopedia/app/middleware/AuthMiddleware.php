<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Auth\UnifiedSessionHandler;
use App\Auth\AuthManager;

class AuthMiddleware
{
    protected $sessionHandler;
    protected $authManager;

    public function __construct(UnifiedSessionHandler $sessionHandler, AuthManager $authManager)
    {
        $this->sessionHandler = $sessionHandler;
        $this->authManager = $authManager;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Detect system context
        $systemContext = $this->sessionHandler->detectSystemContext($request);
        
        // Store context in session for later use
        session(['system_context' => $systemContext]);

        // Check unified authentication
        if (!$this->authManager->checkUnifiedAuth()) {
            return $this->handleUnauthenticatedRequest($request, $systemContext);
        }

        // Get unified user information
        $unifiedUser = $this->authManager->getUnifiedUser();
        
        if (!$unifiedUser) {
            return $this->handleUnauthenticatedRequest($request, $systemContext);
        }

        // Share user information with all views
        view()->share('currentUser', $unifiedUser['user']);
        view()->share('systemContext', $systemContext);
        view()->share('isAuthenticated', true);

        // Log authentication activity
        $this->logAuthenticationActivity($request, $unifiedUser, $systemContext);

        // Update user activity
        $this->updateUserActivity($unifiedUser, $systemContext);

        return $next($request);
    }

    /**
     * Handle unauthenticated requests based on system context
     */
    protected function handleUnauthenticatedRequest(Request $request, $systemContext)
    {
        // Clear any existing session data
        session()->flush();

        // Redirect based on system context
        switch ($systemContext) {
            case UnifiedSessionHandler::CONTEXT_CRAFTY_SYNTAX:
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Authentication required',
                        'redirect' => '/legacy/craftysyntax/login.php'
                    ], 401);
                }
                return redirect('/legacy/craftysyntax/login.php')
                    ->with('error', 'Please login to access this page');

            case UnifiedSessionHandler::CONTEXT_LUPOPEDIA:
            default:
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Authentication required',
                        'redirect' => '/login'
                    ], 401);
                }
                return redirect('/login')
                    ->with('error', 'Please login to access this page');
        }
    }

    /**
     * Log authentication activity for security monitoring
     */
    protected function logAuthenticationActivity(Request $request, $unifiedUser, $systemContext)
    {
        $activityData = [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'request_path' => $request->path(),
            'method' => $request->method(),
            'timestamp' => now()
        ];

        // Log to auth audit table
        $this->authManager->logAuthEvent(
            'middleware_access',
            $unifiedUser['user']->id,
            $unifiedUser['user']->crafty_operator_id ?? null,
            $systemContext,
            true,
            null,
            $activityData
        );
    }

    /**
     * Update user activity tracking
     */
    protected function updateUserActivity($unifiedUser, $systemContext)
    {
        $sessionId = session()->getId();
        
        // Update unified session activity
        DB::table('unified_sessions')
            ->where('session_id', $sessionId)
            ->update([
                'updated_at' => now()
            ]);

        // Update legacy Crafty Syntax activity if applicable
        if ($systemContext === UnifiedSessionHandler::CONTEXT_CRAFTY_SYNTAX) {
            $this->updateCraftyActivity($unifiedUser);
        }
    }

    /**
     * Update Crafty Syntax activity tracking
     */
    protected function updateCraftyActivity($unifiedUser)
    {
        if (!isset($unifiedUser['user']->crafty_operator_id)) {
            return;
        }

        $timestamp = date("YmdHis");
        $sessionId = session()->getId();

        // Update livehelp_users table for Crafty Syntax compatibility
        DB::table('livehelp_users')
            ->where('user_id', $unifiedUser['user']->crafty_operator_id)
            ->orWhere('sessionid', $sessionId)
            ->update([
                'lastaction' => $timestamp,
                'isonline' => 'Y',
                'ipaddress' => request()->ip()
            ]);
    }

    /**
     * Check if user has required permissions for the route
     */
    protected function checkPermissions($unifiedUser, $requiredPermissions = [])
    {
        if (empty($requiredPermissions)) {
            return true;
        }

        $userPermissions = $this->authManager->getUserPermissions();

        foreach ($requiredPermissions as $permission) {
            if (in_array($permission, $userPermissions)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle permission denied scenarios
     */
    protected function handlePermissionDenied(Request $request, $systemContext)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient permissions',
                'error_code' => 'PERMISSION_DENIED'
            ], 403);
        }

        // Redirect based on system context
        switch ($systemContext) {
            case UnifiedSessionHandler::CONTEXT_CRAFTY_SYNTAX:
                return redirect('/legacy/craftysyntax/admin.php')
                    ->with('error', 'You do not have permission to access this page');

            case UnifiedSessionHandler::CONTEXT_LUPOPEDIA:
            default:
                return redirect('/dashboard')
                    ->with('error', 'You do not have permission to access this page');
        }
    }

    /**
     * Validate session integrity
     */
    protected function validateSessionIntegrity()
    {
        $sessionId = session()->getId();
        return $this->sessionHandler->validateSessionIntegrity($sessionId);
    }

    /**
     * Handle session validation failures
     */
    protected function handleSessionValidationFailure(Request $request, $systemContext)
    {
        // Log security event
        $this->authManager->logAuthEvent(
            'session_validation_failed',
            null,
            null,
            $systemContext,
            false,
            'Session integrity validation failed'
        );

        // Clear invalid session
        session()->flush();
        $this->sessionHandler->destroyUnifiedSession(session()->getId());

        return $this->handleUnauthenticatedRequest($request, $systemContext);
    }
}
