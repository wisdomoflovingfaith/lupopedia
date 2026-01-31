<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Auth\UnifiedSessionHandler;
use Carbon\Carbon;

class AuthController extends Controller
{
    protected $sessionHandler;

    public function __construct(UnifiedSessionHandler $sessionHandler)
    {
        $this->sessionHandler = $sessionHandler;
    }

    /**
     * Show the login form
     */
    public function showLoginForm(Request $request)
    {
        $systemContext = $request->input('system_context', 'lupopedia');
        $redirectUrl = $request->input('redirect', '');

        return view('auth.login', [
            'system_context' => $systemContext,
            'redirect' => $redirectUrl
        ]);
    }

    /**
     * Unified login method for both Lupopedia and Crafty Syntax
     */
    public function unifiedLogin(Request $request)
    {
        // Detect system context
        $systemContext = $this->sessionHandler->detectSystemContext($request);
        
        // Validate login credentials
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->handleLoginFailure($validator->errors(), $systemContext);
        }

        // Attempt authentication based on context
        $authResult = $this->attemptUnifiedAuthentication($request, $systemContext);

        if (!$authResult['success']) {
            return $this->handleLoginFailure($authResult['message'], $systemContext);
        }

        // Create unified session
        $sessionData = [
            'login_time' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'original_context' => $systemContext
        ];

        $sessionId = $this->sessionHandler->createUnifiedSession(
            $authResult['user_id'],
            $systemContext,
            $sessionData
        );

        // Update last login timestamp
        $this->updateLastLogin($authResult['user_id']);

        // Redirect based on original context
        return $this->redirectToSystemInterface($systemContext, $authResult);
    }

    /**
     * Attempt unified authentication across both systems
     */
    private function attemptUnifiedAuthentication($request, $systemContext)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        // First try Lupopedia authentication
        $lupoResult = $this->attemptLupopediaAuth($email, $password);
        
        if ($lupoResult['success']) {
            return $lupoResult;
        }

        // Then try Crafty Syntax authentication
        $craftyResult = $this->attemptCraftySyntaxAuth($email, $password);
        
        if ($craftyResult['success']) {
            return $craftyResult;
        }

        return ['success' => false, 'message' => 'Invalid credentials'];
    }

    /**
     * Attempt Lupopedia authentication
     */
    private function attemptLupopediaAuth($email, $password)
    {
        $user = DB::table('users')
            ->where('email', $email)
            ->first();

        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        // Verify password using existing Lupopedia method
        if (password_verify($password, $user->password)) {
            return [
                'success' => true,
                'user_id' => $user->id,
                'user_type' => 'lupopedia',
                'user_data' => $user
            ];
        }

        return ['success' => false, 'message' => 'Invalid password'];
    }

    /**
     * Attempt Crafty Syntax authentication
     */
    private function attemptCraftySyntaxAuth($email, $password)
    {
        // Check if this is a Crafty Syntax operator
        $operator = DB::table('livehelp_operators')
            ->where('email', $email)
            ->first();

        if (!$operator) {
            return ['success' => false, 'message' => 'Operator not found'];
        }

        // Verify password using Crafty Syntax method
        if ($this->verifyCraftyPassword($password, $operator->password)) {
            // Check if there's a user mapping
            $mapping = DB::table('lupo_crafty_user_mapping')
                ->where('crafty_operator_id', $operator->operatorid)
                ->first();

            $userId = $mapping ? $mapping->lupo_user_id : null;

            return [
                'success' => true,
                'user_id' => $userId,
                'operator_id' => $operator->operatorid,
                'user_type' => 'crafty_syntax',
                'user_data' => $operator
            ];
        }

        return ['success' => false, 'message' => 'Invalid password'];
    }

    /**
     * Verify Crafty Syntax password format
     */
    private function verifyCraftyPassword($inputPassword, $storedPassword)
    {
        // Crafty Syntax uses MD5 hashing
        return md5($inputPassword) === $storedPassword;
    }

    /**
     * Handle login failure with appropriate response
     */
    private function handleLoginFailure($errors, $systemContext)
    {
        if ($systemContext === UnifiedSessionHandler::CONTEXT_CRAFTY_SYNTAX) {
            // Return Crafty Syntax compatible error response
            return response()->json([
                'success' => false,
                'message' => is_array($errors) ? implode(', ', $errors) : $errors
            ], 401);
        }

        // Return Lupopedia compatible error response
        return back()->withErrors($errors)->withInput();
    }

    /**
     * Redirect to appropriate system interface
     */
    private function redirectToSystemInterface($systemContext, $authResult)
    {
        // Check for redirect URL from session or request
        $redirectUrl = session('login_redirect') ?? request()->input('redirect');

        // Clear the redirect from session
        session()->forget('login_redirect');

        // If redirect URL exists and is safe (not external), use it
        if ($redirectUrl && $this->isInternalUrl($redirectUrl)) {
            return redirect($redirectUrl)->with('success', 'Login successful');
        }

        // Default redirects based on context
        switch ($systemContext) {
            case UnifiedSessionHandler::CONTEXT_CRAFTY_SYNTAX:
                // Redirect to Crafty Syntax operator interface
                return redirect('/livehelp/index.php')->with('success', 'Login successful');

            case UnifiedSessionHandler::CONTEXT_LUPOPEDIA:
            default:
                // Redirect to Lupopedia dashboard
                return redirect('/dashboard')->with('success', 'Login successful');
        }
    }

    /**
     * Check if URL is internal (safe to redirect to)
     */
    private function isInternalUrl($url)
    {
        // Allow relative URLs
        if (strpos($url, '/') === 0) {
            return true;
        }

        // Disallow external URLs (those with :// in them)
        if (strpos($url, '://') !== false) {
            return false;
        }

        return true;
    }

    /**
     * Unified logout method
     */
    public function unifiedLogout(Request $request)
    {
        $sessionId = session()->getId();
        
        // Destroy unified session
        $this->sessionHandler->destroyUnifiedSession($sessionId);
        
        // Clear Laravel session
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        // Detect context for appropriate redirect
        $systemContext = $this->sessionHandler->detectSystemContext($request);

        if ($systemContext === UnifiedSessionHandler::CONTEXT_CRAFTY_SYNTAX) {
            return redirect('/livehelp/login.php')->with('success', 'Logged out successfully');
        }

        return redirect('/login')->with('success', 'Logged out successfully');
    }

    /**
     * Update last login timestamp
     */
    private function updateLastLogin($userId)
    {
        if ($userId) {
            DB::table('users')
                ->where('id', $userId)
                ->update(['last_login_at' => now()]);
        }
    }

    /**
     * Get user session information
     */
    public function getSessionInfo(Request $request)
    {
        $sessionId = session()->getId();
        $unifiedSession = $this->sessionHandler->getUnifiedSession($sessionId);

        if (!$unifiedSession) {
            return response()->json(['error' => 'No active session'], 401);
        }

        return response()->json([
            'user_id' => $unifiedSession['user_id'],
            'system_context' => $unifiedSession['system_context'],
            'expires_at' => $unifiedSession['expires_at']
        ]);
    }

    /**
     * Validate session integrity
     */
    public function validateSession(Request $request)
    {
        $sessionId = session()->getId();
        $isValid = $this->sessionHandler->validateSessionIntegrity($sessionId);

        return response()->json(['valid' => $isValid]);
    }
}
