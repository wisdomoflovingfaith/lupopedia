<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Auth\AuthManager;
use App\Auth\UnifiedSessionHandler;

class AuthenticationController extends Controller
{
    protected $authManager;
    protected $sessionHandler;

    public function __construct(AuthManager $authManager, UnifiedSessionHandler $sessionHandler)
    {
        $this->authManager = $authManager;
        $this->sessionHandler = $sessionHandler;
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display authentication mapping dashboard
     */
    public function index()
    {
        $mappings = $this->getUserMappings();
        $unmappedUsers = $this->getUnmappedUsers();
        $unmappedOperators = $this->getUnmappedOperators();
        $stats = $this->getAuthenticationStats();

        return view('admin.authentication.index', compact('mappings', 'unmappedUsers', 'unmappedOperators', 'stats'));
    }

    /**
     * Display user mapping interface
     */
    public function mapping()
    {
        $lupoUsers = $this->getLupopediaUsers();
        $craftyOperators = $this->getCraftyOperators();
        $existingMappings = $this->getUserMappings();

        return view('admin.authentication.mapping', compact('lupoUsers', 'craftyOperators', 'existingMappings'));
    }

    /**
     * Create or update user mapping
     */
    public function storeMapping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lupo_user_id' => 'required|exists:users,id',
            'crafty_operator_id' => 'required|exists:livehelp_operators,operatorid',
            'mapping_type' => 'required|in:manual,auto,imported',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $this->authManager->createUserMapping(
                $request->lupo_user_id,
                $request->crafty_operator_id,
                $request->mapping_type,
                $request->notes
            );

            // Update users table with crafty_operator_id
            DB::table('users')
                ->where('id', $request->lupo_user_id)
                ->update(['crafty_operator_id' => $request->crafty_operator_id]);

            // Log the mapping creation
            $this->authManager->logAuthEvent(
                'user_mapping_created',
                $request->lupo_user_id,
                $request->crafty_operator_id,
                'admin',
                true,
                null,
                [
                    'mapping_type' => $request->mapping_type,
                    'notes' => $request->notes,
                    'created_by' => auth()->id()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'User mapping created successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating user mapping: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user mapping
     */
    public function deleteMapping($id)
    {
        try {
            $mapping = DB::table('lupo_crafty_user_mapping')->where('id', $id)->first();
            
            if (!$mapping) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mapping not found'
                ], 404);
            }

            // Remove crafty_operator_id from users table
            DB::table('users')
                ->where('id', $mapping->lupo_user_id)
                ->update(['crafty_operator_id' => null]);

            // Delete the mapping
            DB::table('lupo_crafty_user_mapping')->where('id', $id)->delete();

            // Log the deletion
            $this->authManager->logAuthEvent(
                'user_mapping_deleted',
                $mapping->lupo_user_id,
                $mapping->crafty_operator_id,
                'admin',
                true,
                null,
                [
                    'deleted_by' => auth()->id()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'User mapping deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user mapping: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display session management dashboard
     */
    public function sessions()
    {
        $activeSessions = $this->getActiveSessions();
        $sessionStats = $this->getSessionStats();
        $recentActivity = $this->getRecentAuthenticationActivity();

        return view('admin.authentication.sessions', compact('activeSessions', 'sessionStats', 'recentActivity'));
    }

    /**
     * Terminate user session
     */
    public function terminateSession($sessionId)
    {
        try {
            $session = $this->sessionHandler->getUnifiedSession($sessionId);
            
            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found'
                ], 404);
            }

            // Destroy the session
            $this->sessionHandler->destroyUnifiedSession($sessionId);

            // Log the termination
            $this->authManager->logAuthEvent(
                'session_terminated',
                $session['user_id'],
                null,
                'admin',
                true,
                null,
                [
                    'terminated_by' => auth()->id(),
                    'original_context' => $session['system_context']
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Session terminated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error terminating session: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display synchronization tools
     */
    public function synchronization()
    {
        $syncStatus = $this->getSynchronizationStatus();
        $lastSync = $this->getLastSyncTime();
        $syncStats = $this->getSyncStatistics();

        return view('admin.authentication.synchronization', compact('syncStatus', 'lastSync', 'syncStats'));
    }

    /**
     * Trigger user synchronization
     */
    public function synchronizeUsers(Request $request)
    {
        $syncType = $request->input('sync_type', 'bidirectional');
        
        try {
            $results = $this->performUserSynchronization($syncType);

            // Log synchronization
            $this->authManager->logAuthEvent(
                'user_synchronization',
                null,
                null,
                'admin',
                true,
                null,
                [
                    'sync_type' => $syncType,
                    'results' => $results,
                    'triggered_by' => auth()->id()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'User synchronization completed successfully',
                'results' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during synchronization: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user mappings with details
     */
    private function getUserMappings()
    {
        return DB::table('lupo_crafty_user_mapping')
            ->leftJoin('users', 'lupo_crafty_user_mapping.lupo_user_id', '=', 'users.id')
            ->leftJoin('livehelp_operators', 'lupo_crafty_user_mapping.crafty_operator_id', '=', 'livehelp_operators.operatorid')
            ->select(
                'lupo_crafty_user_mapping.*',
                'users.email as lupo_email',
                'users.name as lupo_name',
                'livehelp_operators.email as crafty_email',
                'livehelp_operators.operatorname as crafty_name'
            )
            ->orderBy('lupo_crafty_user_mapping.created_at', 'desc')
            ->get();
    }

    /**
     * Get unmapped Lupopedia users
     */
    private function getUnmappedUsers()
    {
        return DB::table('users')
            ->leftJoin('lupo_crafty_user_mapping', 'users.id', '=', 'lupo_crafty_user_mapping.lupo_user_id')
            ->whereNull('lupo_crafty_user_mapping.lupo_user_id')
            ->select('users.id', 'users.email', 'users.name')
            ->get();
    }

    /**
     * Get unmapped Crafty Syntax operators
     */
    private function getUnmappedOperators()
    {
        return DB::table('livehelp_operators')
            ->leftJoin('lupo_crafty_user_mapping', 'livehelp_operators.operatorid', '=', 'lupo_crafty_user_mapping.crafty_operator_id')
            ->whereNull('lupo_crafty_user_mapping.crafty_operator_id')
            ->select('livehelp_operators.operatorid', 'livehelp_operators.email', 'livehelp_operators.operatorname')
            ->get();
    }

    /**
     * Get authentication statistics
     */
    private function getAuthenticationStats()
    {
        return [
            'total_mappings' => DB::table('lupo_crafty_user_mapping')->count(),
            'active_sessions' => DB::table('unified_sessions')->where('expires_at', '>', now())->count(),
            'total_lupo_users' => DB::table('users')->count(),
            'total_crafty_operators' => DB::table('livehelp_operators')->count(),
            'mapped_users' => DB::table('users')->whereNotNull('crafty_operator_id')->count(),
            'mapped_operators' => DB::table('lupo_crafty_user_mapping')->distinct('crafty_operator_id')->count()
        ];
    }

    /**
     * Get active sessions with user details
     */
    private function getActiveSessions()
    {
        return DB::table('unified_sessions')
            ->leftJoin('users', 'unified_sessions.user_id', '=', 'users.id')
            ->where('unified_sessions.expires_at', '>', now())
            ->select(
                'unified_sessions.*',
                'users.email as user_email',
                'users.name as user_name'
            )
            ->orderBy('unified_sessions.updated_at', 'desc')
            ->get();
    }

    /**
     * Get session statistics
     */
    private function getSessionStats()
    {
        $now = now();
        $dayAgo = $now->copy()->subDay();
        $weekAgo = $now->copy()->subWeek();
        $monthAgo = $now->copy()->subMonth();

        return [
            'total_active' => DB::table('unified_sessions')->where('expires_at', '>', $now)->count(),
            'last_24h' => DB::table('auth_audit_log')->where('created_at', '>', $dayAgo)->count(),
            'last_7d' => DB::table('auth_audit_log')->where('created_at', '>', $weekAgo)->count(),
            'last_30d' => DB::table('auth_audit_log')->where('created_at', '>', $monthAgo)->count(),
            'by_context' => DB::table('unified_sessions')
                ->where('expires_at', '>', $now)
                ->selectRaw('system_context, COUNT(*) as count')
                ->groupBy('system_context')
                ->pluck('count', 'system_context')
                ->toArray()
        ];
    }

    /**
     * Get recent authentication activity
     */
    private function getRecentAuthenticationActivity()
    {
        return DB::table('auth_audit_log')
            ->leftJoin('users', 'auth_audit_log.user_id', '=', 'users.id')
            ->leftJoin('livehelp_operators', 'auth_audit_log.crafty_operator_id', '=', 'livehelp_operators.operatorid')
            ->select(
                'auth_audit_log.*',
                'users.email as user_email',
                'livehelp_operators.email as operator_email'
            )
            ->orderBy('auth_audit_log.created_at', 'desc')
            ->limit(50)
            ->get();
    }

    /**
     * Perform user synchronization
     */
    private function performUserSynchronization($syncType)
    {
        $results = [
            'users_synced' => 0,
            'operators_synced' => 0,
            'mappings_created' => 0,
            'errors' => []
        ];

        try {
            switch ($syncType) {
                case 'lupo_to_crafty':
                    $results = $this->syncLupopediaToCrafty();
                    break;
                case 'crafty_to_lupo':
                    $results = $this->syncCraftyToLupopedia();
                    break;
                case 'bidirectional':
                default:
                    $results = $this->syncBidirectional();
                    break;
            }
        } catch (\Exception $e) {
            $results['errors'][] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Sync from Lupopedia to Crafty Syntax
     */
    private function syncLupopediaToCrafty()
    {
        // Implementation for syncing Lupopedia users to Crafty Syntax
        return [
            'users_synced' => 0,
            'operators_synced' => 0,
            'mappings_created' => 0,
            'errors' => ['Sync method not yet implemented']
        ];
    }

    /**
     * Sync from Crafty Syntax to Lupopedia
     */
    private function syncCraftyToLupopedia()
    {
        // Implementation for syncing Crafty operators to Lupopedia
        return [
            'users_synced' => 0,
            'operators_synced' => 0,
            'mappings_created' => 0,
            'errors' => ['Sync method not yet implemented']
        ];
    }

    /**
     * Bidirectional synchronization
     */
    private function syncBidirectional()
    {
        // Implementation for bidirectional sync
        return [
            'users_synced' => 0,
            'operators_synced' => 0,
            'mappings_created' => 0,
            'errors' => ['Sync method not yet implemented']
        ];
    }

    /**
     * Get synchronization status
     */
    private function getSynchronizationStatus()
    {
        return [
            'last_sync' => $this->getLastSyncTime(),
            'auto_sync_enabled' => false, // This would come from config
            'sync_frequency' => 'manual', // This would come from config
            'pending_syncs' => 0
        ];
    }

    /**
     * Get last synchronization time
     */
    private function getLastSyncTime()
    {
        $lastSync = DB::table('auth_audit_log')
            ->where('event_type', 'user_synchronization')
            ->orderBy('created_at', 'desc')
            ->first();

        return $lastSync ? $lastSync->created_at : null;
    }

    /**
     * Get sync statistics
     */
    private function getSyncStatistics()
    {
        return [
            'total_syncs' => DB::table('auth_audit_log')
                ->where('event_type', 'user_synchronization')
                ->count(),
            'successful_syncs' => DB::table('auth_audit_log')
                ->where('event_type', 'user_synchronization')
                ->where('success', true)
                ->count(),
            'failed_syncs' => DB::table('auth_audit_log')
                ->where('event_type', 'user_synchronization')
                ->where('success', false)
                ->count()
        ];
    }

    /**
     * Get Lupopedia users for mapping
     */
    private function getLupopediaUsers()
    {
        return DB::table('users')
            ->select('id', 'email', 'name')
            ->orderBy('email')
            ->get();
    }

    /**
     * Get Crafty Syntax operators for mapping
     */
    private function getCraftyOperators()
    {
        return DB::table('livehelp_operators')
            ->select('operatorid', 'email', 'operatorname')
            ->orderBy('email')
            ->get();
    }
}
