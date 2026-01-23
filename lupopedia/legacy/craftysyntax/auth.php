<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Live Help                ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
//----------------------------------------------------------------------------
// Please check https://lupopedia.com/ or REGISTER your program for updates
// --------------------------------------------------------------------------
// NOTICE: Do NOT remove or copyright and/or license information any files. 
//         doing so will automatically terminate your rights to use program.
//         If you change program you MUST clause your changes and note
//         that the original program is CRAFTY SYNTAX Live help or you will 
//         also be terminating your rights to use program and any segment 
//         of it.        
// --------------------------------------------------------------------------
// LICENSE:
//     This program is free software; you can redistribute it and/or
//     modify it under the terms of the GNU General Public License
//     as published by the Free Software Foundation; 
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program in a file named LICENSE.txt .
//===========================================================================

/**
 * Unified Authentication Wrapper for Crafty Syntax
 * 
 * This file provides backward compatibility while delegating to the unified
 * authentication system. All existing function signatures are preserved.
 */

// Include the unified authentication system
require_once(dirname(__DIR__) . '/../app/auth/UnifiedSessionHandler.php');
require_once(dirname(__DIR__) . '/../app/auth/AuthManager.php');

// Initialize unified authentication components
$unifiedSessionHandler = new \App\Auth\UnifiedSessionHandler();
$authManager = new \App\Auth\($unifiedSessionHandler);

/**
 * Legacy validate_user function - delegates to unified authentication
 * 
 * @param string $username
 * @param string $password
 * @param array &$identity
 * @return bool
 */
function validate_user($username, $password, &$identity)
{
    global $authManager, $mydatabase, $UNTRUSTED;
    
    // Try unified authentication first
    $unifiedUser = $authManager->getUnifiedUser();
    
    if ($unifiedUser && $unifiedUser['user']->email === $username) {
        // User is already authenticated via unified system
        $identity['IDENTITY'] = $unifiedUser['user']->id;
        $identity['SESSIONID'] = session_id();
        $identity['IP_ADDR'] = $_SERVER['REMOTE_ADDR'];
        $identity['HOSTNAME'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        
        // Log successful authentication
        $authManager->logAuthEvent('legacy_validate_success', $unifiedUser['user']->id, null, 'crafty_syntax');
        
        return true;
    }
    
    // Fall back to legacy Crafty Syntax authentication
    return legacy_validate_user($username, $password, $identity);
}

/**
 * Legacy Crafty Syntax authentication (original logic preserved)
 */
function legacy_validate_user($username, $password, &$identity)
{
    global $mydatabase, $UNTRUSTED;
    
    $query = "SELECT * FROM livehelp_operators 
              WHERE username = '" . filter_sql($username) . "' 
              AND password = '" . filter_sql(md5($password)) . "'";
    
    $result = $mydatabase->query($query);
    $operator = $result->fetchRow(DB_FETCHMODE_ASSOC);
    
    if ($operator) {
        $identity['IDENTITY'] = $operator['operatorid'];
        $identity['SESSIONID'] = session_id();
        $identity['IP_ADDR'] = $_SERVER['REMOTE_ADDR'];
        $identity['HOSTNAME'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        
        // Create unified session for legacy login
        global $unifiedSessionHandler;
        $sessionData = [
            'login_time' => date('Y-m-d H:i:s'),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'original_context' => 'crafty_syntax',
            'legacy_auth' => true
        ];
        
        $unifiedSessionHandler->createUnifiedSession(
            null, // No Lupopedia user ID for pure Crafty Syntax login
            'crafty_syntax',
            $sessionData
        );
        
        return true;
    }
    
    return false;
}

/**
 * Check if current user is authenticated (unified + legacy)
 */
function is_authenticated()
{
    global $authManager;
    
    // Check unified authentication
    if ($authManager->checkUnifiedAuth()) {
        return true;
    }
    
    // Check legacy Crafty Syntax session
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === 'Y';
}

/**
 * Get current user information
 */
function get_current_user()
{
    global $authManager;
    
    $unifiedUser = $authManager->getUnifiedUser();
    
    if ($unifiedUser) {
        return $unifiedUser['user'];
    }
    
    // Fall back to legacy session data
    if (isset($_SESSION['user_id'])) {
        $query = "SELECT * FROM livehelp_operators WHERE operatorid = " . intval($_SESSION['user_id']);
        global $mydatabase;
        $result = $mydatabase->query($query);
        return $result->fetchRow(DB_FETCHMODE_ASSOC);
    }
    
    return null;
}

/**
 * Logout function that works with both unified and legacy systems
 */
function unified_logout()
{
    global $unifiedSessionHandler, $authManager;
    
    $sessionId = session_id();
    
    // Destroy unified session
    $unifiedSessionHandler->destroyUnifiedSession($sessionId);
    
    // Log logout event
    $unifiedUser = $authManager->getUnifiedUser();
    if ($unifiedUser) {
        $authManager->logAuthEvent('logout', $unifiedUser['user']->id, null, 'crafty_syntax');
    }
    
    // Clear legacy session data
    unset($_SESSION['authenticated']);
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    
    return true;
}

/**
 * Check if user has specific permission (unified + legacy)
 */
function user_has_permission($permission)
{
    global $authManager;
    
    // Check unified permissions
    $permissions = $authManager->getUserPermissions();
    
    if (in_array($permission, $permissions)) {
        return true;
    }
    
    // Fall back to legacy permission check
    $user = get_current_user();
    
    if ($user) {
        switch ($permission) {
            case 'admin':
                return isset($user['isadmin']) && $user['isadmin'] === 'Y';
            case 'chat':
                return true; // All operators can chat
            case 'reports':
                return isset($user['isadmin']) && $user['isadmin'] === 'Y';
        }
    }
    
    return false;
}

/**
 * Get user's department access (legacy compatibility)
 */
function get_user_departments($operatorId)
{
    global $mydatabase;
    
    $query = "SELECT * FROM livehelp_operator_departments 
              WHERE operatorid = " . intval($operatorId);
    
    $result = $mydatabase->query($query);
    $departments = [];
    
    while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
        $departments[] = $row['departmentid'];
    }
    
    return $departments;
}

/**
 * Update user session activity (unified + legacy)
 */
function update_user_activity($operatorId, $action = 'activity')
{
    global $mydatabase, $unifiedSessionHandler;
    
    $sessionId = session_id();
    $timestamp = date("YmdHis");
    
    // Update legacy session
    $query = "UPDATE livehelp_users 
              SET lastaction = '$timestamp', 
                  isonline = 'Y' 
              WHERE sessionid = '$sessionId'";
    $mydatabase->query($query);
    
    // Update unified session
    $unifiedSession = $unifiedSessionHandler->getUnifiedSession($sessionId);
    if ($unifiedSession) {
        $sessionData = $unifiedSession['session_data'];
        $sessionData['last_activity'] = $timestamp;
        $sessionData['last_action'] = $action;
        
        DB::table('unified_sessions')
            ->where('session_id', $sessionId)
            ->update([
                'session_data' => json_encode($sessionData),
                'updated_at' => now()
            ]);
    }
    
    // Log activity
    global $authManager;
    $authManager->logAuthEvent('user_activity', null, $operatorId, 'crafty_syntax', true, null, ['action' => $action]);
}

/**
 * Backward compatibility function for filter_sql
 */
if (!function_exists('filter_sql')) {
    function filter_sql($string)
    {
        return addslashes($string);
    }
}

/**
 * Initialize authentication state
 */
function init_authentication()
{
    global $authManager, $unifiedSessionHandler;
    
    // Clean up expired sessions
    $unifiedSessionHandler->cleanupExpiredSessions();
    
    // Check if we have a valid unified session
    $unifiedSession = $unifiedSessionHandler->getUnifiedSessionFromCookie();
    
    if ($unifiedSession) {
        // Restore session state from unified session
        $_SESSION['authenticated'] = 'Y';
        $_SESSION['user_id'] = $unifiedSession['user_id'];
        $_SESSION['system_context'] = $unifiedSession['system_context'];
    }
}

// Initialize authentication on include
init_authentication();

?>
