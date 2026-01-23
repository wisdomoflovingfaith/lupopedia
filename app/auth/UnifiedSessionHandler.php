<?php

namespace App\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;

class UnifiedSessionHandler
{
    /**
     * Session context constants
     */
    const CONTEXT_LUPOPEDIA = 'lupopedia';
    const CONTEXT_CRAFTY_SYNTAX = 'crafty_syntax';
    const CONTEXT_UNIFIED = 'unified';

    /**
     * Create or update unified session
     */
    public function createUnifiedSession($userId, $systemContext, $sessionData = [])
    {
        $sessionId = Session::getId();
        $expiresAt = Carbon::now()->addMinutes(config('session.lifetime'));

        // Store in unified sessions table
        DB::table('unified_sessions')->updateOrInsert(
            ['session_id' => $sessionId],
            [
                'user_id' => $userId,
                'system_context' => $systemContext,
                'session_data' => json_encode($sessionData),
                'expires_at' => $expiresAt,
                'updated_at' => now()
            ]
        );

        // Set unified cookie
        $this->setUnifiedCookie($sessionId, $systemContext);

        return $sessionId;
    }

    /**
     * Get unified session data
     */
    public function getUnifiedSession($sessionId)
    {
        $session = DB::table('unified_sessions')
            ->where('session_id', $sessionId)
            ->where('expires_at', '>', now())
            ->first();

        if ($session) {
            return [
                'user_id' => $session->user_id,
                'system_context' => $session->system_context,
                'session_data' => json_decode($session->session_data, true),
                'expires_at' => $session->expires_at
            ];
        }

        return null;
    }

    /**
     * Migrate existing session to unified format
     */
    public function migrateExistingSession($userId, $legacyContext)
    {
        $sessionId = Session::getId();
        $existingSession = $this->getUnifiedSession($sessionId);

        if (!$existingSession) {
            return $this->createUnifiedSession($userId, $legacyContext);
        }

        return $sessionId;
    }

    /**
     * Set unified authentication cookie
     */
    private function setUnifiedCookie($sessionId, $systemContext)
    {
        $cookieName = 'lupo_unified_session';
        $cookieValue = json_encode([
            'session_id' => $sessionId,
            'context' => $systemContext,
            'timestamp' => time()
        ]);

        Cookie::queue($cookieName, $cookieValue, config('session.lifetime'), '/', null, true, true);
    }

    /**
     * Get unified session from cookie
     */
    public function getUnifiedSessionFromCookie()
    {
        $cookieValue = Cookie::get('lupo_unified_session');
        
        if ($cookieValue) {
            $cookieData = json_decode($cookieValue, true);
            if (isset($cookieData['session_id'])) {
                return $this->getUnifiedSession($cookieData['session_id']);
            }
        }

        return null;
    }

    /**
     * Destroy unified session
     */
    public function destroyUnifiedSession($sessionId)
    {
        DB::table('unified_sessions')
            ->where('session_id', $sessionId)
            ->delete();

        // Clear unified cookie
        Cookie::forget('lupo_unified_session');
    }

    /**
     * Detect system context from request
     */
    public function detectSystemContext($request)
    {
        $path = $request->path();
        
        // Check for Crafty Syntax paths
        if (strpos($path, 'livehelp') !== false || 
            strpos($path, 'crafty_syntax') !== false ||
            $request->is('legacy/*')) {
            return self::CONTEXT_CRAFTY_SYNTAX;
        }

        // Default to Lupopedia
        return self::CONTEXT_LUPOPEDIA;
    }

    /**
     * Clean up expired sessions
     */
    public function cleanupExpiredSessions()
    {
        DB::table('unified_sessions')
            ->where('expires_at', '<=', now())
            ->delete();
    }

    /**
     * Get active sessions for user
     */
    public function getActiveSessionsForUser($userId)
    {
        return DB::table('unified_sessions')
            ->where('user_id', $userId)
            ->where('expires_at', '>', now())
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    /**
     * Validate session integrity
     */
    public function validateSessionIntegrity($sessionId)
    {
        $session = $this->getUnifiedSession($sessionId);
        
        if (!$session) {
            return false;
        }

        // Additional validation logic can be added here
        return true;
    }
}
