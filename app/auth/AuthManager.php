<?php

namespace App\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Auth\UnifiedSessionHandler;

class AuthManager
{
    protected $sessionHandler;

    public function __construct(UnifiedSessionHandler $sessionHandler)
    {
        $this->sessionHandler = $sessionHandler;
    }

    /**
     * Check if user is authenticated in unified context
     */
    public function checkUnifiedAuth()
    {
        // First check Laravel's native auth
        if (Auth::check()) {
            return true;
        }

        // Check unified session
        $unifiedSession = $this->sessionHandler->getUnifiedSessionFromCookie();
        
        if ($unifiedSession) {
            // Validate session integrity
            if ($this->sessionHandler->validateSessionIntegrity($unifiedSession['session_id'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get current authenticated user with context awareness
     */
    public function getUnifiedUser()
    {
        // Try Laravel auth first
        if (Auth::check()) {
            return [
                'user' => Auth::user(),
                'context' => 'lupopedia',
                'source' => 'laravel_auth'
            ];
        }

        // Try unified session
        $unifiedSession = $this->sessionHandler->getUnifiedSessionFromCookie();
        
        if ($unifiedSession) {
            $user = $this->getUserById($unifiedSession['user_id']);
            
            if ($user) {
                return [
                    'user' => $user,
                    'context' => $unifiedSession['system_context'],
                    'source' => 'unified_session'
                ];
            }
        }

        return null;
    }

    /**
     * Get user by ID with system context awareness
     */
    private function getUserById($userId)
    {
        if (!$userId) {
            return null;
        }

        // Try Lupopedia users table first
        $user = DB::table('users')->where('id', $userId)->first();
        
        if ($user) {
            return (object) [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name ?? $user->username,
                'type' => 'lupopedia',
                'crafty_operator_id' => $user->crafty_operator_id
            ];
        }

        return null;
    }

    /**
     * Get Crafty Syntax operator by ID
     */
    public function getCraftyOperator($operatorId)
    {
        if (!$operatorId) {
            return null;
        }

        $operator = DB::table('livehelp_operators')
            ->where('operatorid', $operatorId)
            ->first();

        if ($operator) {
            return (object) [
                'id' => $operator->operatorid,
                'email' => $operator->email,
                'name' => $operator->operatorname,
                'type' => 'crafty_syntax'
            ];
        }

        return null;
    }

    /**
     * Check if current user is a Crafty Syntax operator
     */
    public function isCraftyOperator()
    {
        $unifiedUser = $this->getUnifiedUser();
        
        if (!$unifiedUser) {
            return false;
        }

        // Check if user has crafty_operator_id
        if (isset($unifiedUser['user']->crafty_operator_id)) {
            return true;
        }

        // Check if context is crafty_syntax
        if ($unifiedUser['context'] === 'crafty_syntax') {
            return true;
        }

        return false;
    }

    /**
     * Get user permissions with context awareness
     */
    public function getUserPermissions()
    {
        $unifiedUser = $this->getUnifiedUser();
        
        if (!$unifiedUser) {
            return [];
        }

        $permissions = [];

        // Get Lupopedia permissions
        if ($unifiedUser['user']->type === 'lupopedia') {
            $permissions = $this->getLupopediaPermissions($unifiedUser['user']->id);
        }

        // Get Crafty Syntax permissions if applicable
        if ($this->isCraftyOperator()) {
            $craftyPermissions = $this->getCraftyPermissions($unifiedUser['user']);
            $permissions = array_merge($permissions, $craftyPermissions);
        }

        return array_unique($permissions);
    }

    /**
     * Get Lupopedia user permissions
     */
    private function getLupopediaPermissions($userId)
    {
        // This would typically query a permissions table
        // For now, return basic permissions based on user role
        $user = DB::table('users')->where('id', $userId)->first();
        
        if (!$user) {
            return [];
        }

        $permissions = ['basic_access'];

        if (isset($user->role)) {
            switch ($user->role) {
                case 'admin':
                    $permissions[] = 'admin_access';
                    $permissions[] = 'user_management';
                    $permissions[] = 'system_configuration';
                    break;
                case 'editor':
                    $permissions[] = 'content_editing';
                    $permissions[] = 'collection_management';
                    break;
                case 'operator':
                    $permissions[] = 'chat_support';
                    $permissions[] = 'visitor_tracking';
                    break;
            }
        }

        return $permissions;
    }

    /**
     * Get Crafty Syntax operator permissions
     */
    private function getCraftyPermissions($user)
    {
        $permissions = ['crafty_basic_access'];

        if (isset($user->crafty_operator_id)) {
            $operator = $this->getCraftyOperator($user->crafty_operator_id);
            
            if ($operator) {
                $permissions[] = 'chat_support';
                $permissions[] = 'visitor_tracking';
                $permissions[] = 'operator_dashboard';
                
                // Add admin permissions if applicable
                if (isset($operator->isadmin) && $operator->isadmin) {
                    $permissions[] = 'crafty_admin';
                    $permissions[] = 'operator_management';
                }
            }
        }

        return $permissions;
    }

    /**
     * Validate user access to resource
     */
    public function validateAccess($resource, $action = 'read')
    {
        $permissions = $this->getUserPermissions();
        
        // Define permission requirements for different resources
        $permissionMap = [
            'admin' => ['admin_access'],
            'users' => ['user_management'],
            'collections' => ['collection_management', 'content_editing'],
            'chat' => ['chat_support'],
            'analytics' => ['analytics_access'],
        ];

        if (isset($permissionMap[$resource])) {
            $requiredPermissions = $permissionMap[$resource];
            
            // Check if user has any of the required permissions
            foreach ($requiredPermissions as $requiredPermission) {
                if (in_array($requiredPermission, $permissions)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get user mapping between Lupopedia and Crafty Syntax
     */
    public function getUserMapping($userId, $operatorId = null)
    {
        $query = DB::table('lupo_crafty_user_mapping');

        if ($userId) {
            $query->where('lupo_user_id', $userId);
        }

        if ($operatorId) {
            $query->where('crafty_operator_id', $operatorId);
        }

        return $query->first();
    }

    /**
     * Create user mapping
     */
    public function createUserMapping($userId, $operatorId, $mappingType = 'manual', $notes = null)
    {
        return DB::table('lupo_crafty_user_mapping')->insert([
            'lupo_user_id' => $userId,
            'crafty_operator_id' => $operatorId,
            'mapping_type' => $mappingType,
            'notes' => $notes,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Log authentication event
     */
    public function logAuthEvent($eventType, $userId = null, $operatorId = null, $systemContext = null, $success = true, $errorMessage = null)
    {
        $request = request();
        
        DB::table('auth_audit_log')->insert([
            'user_id' => $userId,
            'crafty_operator_id' => $operatorId,
            'event_type' => $eventType,
            'system_context' => $systemContext,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'success' => $success,
            'error_message' => $errorMessage,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
