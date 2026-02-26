<?php
/**
 * Admin Actor Status Handler
 * 
 * Provides interface for managing actor status, online/offline state, and token limits.
 * Uses metadata system for storing status information.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

class AdminActorStatusHandler {
    private $db;
    private $table_prefix;
    
    public function __construct($db, $table_prefix = 'lupo_') {
        $this->db = $db;
        $this->table_prefix = $table_prefix;
    }
    
    /**
     * Get all actors with their status information
     */
    public function getActorsWithStatus($filters = array()) {
        $where_conditions = array('a.is_deleted = 0');
        $params = array();
        
        // Filter by actor type
        if (!empty($filters['actor_type'])) {
            $where_conditions[] = 'a.actor_type = :actor_type';
            $params['actor_type'] = $filters['actor_type'];
        }
        
        // Filter by active status
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $where_conditions[] = 'a.is_active = :is_active';
            $params['is_active'] = $filters['is_active'];
        }
        
        // Filter by agent status
        if (isset($filters['is_agent']) && $filters['is_agent'] !== '') {
            $where_conditions[] = 'a.is_agent = :is_agent';
            $params['is_agent'] = $filters['is_agent'];
        }
        
        $where_clause = implode(' AND ', $where_conditions);
        
        $sql = "SELECT a.actor_id, a.actor_type, a.slug, a.name, a.is_active, a.is_agent, a.created_ymdhis, a.updated_ymdhis,
                       COALESCE(m.property_value, 'offline') as online_status,
                       COALESCE(m2.property_value, '0') as token_limit,
                       COALESCE(m3.property_value, NULL) as token_reset_date,
                       COALESCE(m4.property_value, '') as status_message
                FROM {$this->table_prefix}actors a
                LEFT JOIN {$this->table_prefix}metadata m ON a.actor_id = m.entity_id AND m.entity_type = 'actor' AND m.property_key = 'online_status'
                LEFT JOIN {$this->table_prefix}metadata m2 ON a.actor_id = m2.entity_id AND m2.entity_type = 'actor' AND m2.property_key = 'token_limit'
                LEFT JOIN {$this->table_prefix}metadata m3 ON a.actor_id = m3.entity_id AND m3.entity_type = 'actor' AND m3.property_key = 'token_reset_date'
                LEFT JOIN {$this->table_prefix}metadata m4 ON a.actor_id = m4.entity_id AND m4.entity_type = 'actor' AND m4.property_key = 'status_message'
                WHERE $where_clause
                ORDER BY a.actor_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Update actor status
     */
    public function updateActorStatus($actor_id, $status_data) {
        $now = gmdate('YmdHis');
        $errors = array();
        
        // Validate actor exists
        $actor = $this->db->fetchRow(
            "SELECT actor_id, name FROM {$this->table_prefix}actors WHERE actor_id = :actor_id AND is_deleted = 0 LIMIT 1",
            array('actor_id' => $actor_id)
        );
        
        if (!$actor) {
            return array('success' => false, 'message' => 'Actor not found');
        }
        
        // Update online status
        if (isset($status_data['online_status'])) {
            $this->updateActorMetadata($actor_id, 'online_status', $status_data['online_status'], $now);
        }
        
        // Update token limit
        if (isset($status_data['token_limit'])) {
            $token_limit = (int) $status_data['token_limit'];
            $this->updateActorMetadata($actor_id, 'token_limit', (string) $token_limit, $now);
        }
        
        // Update token reset date
        if (isset($status_data['token_reset_date'])) {
            $reset_date = $status_data['token_reset_date'];
            if ($reset_date && preg_match('/^\d{8}$/', $reset_date)) {
                $this->updateActorMetadata($actor_id, 'token_reset_date', $reset_date, $now);
            } elseif (empty($reset_date)) {
                $this->deleteActorMetadata($actor_id, 'token_reset_date');
            }
        }
        
        // Update status message
        if (isset($status_data['status_message'])) {
            $message = trim($status_data['status_message']);
            if ($message) {
                $this->updateActorMetadata($actor_id, 'status_message', $message, $now);
            } else {
                $this->deleteActorMetadata($actor_id, 'status_message');
            }
        }
        
        // Update actor active status if provided
        if (isset($status_data['is_active'])) {
            $this->db->update(
                $this->table_prefix . 'actors',
                array('is_active' => $status_data['is_active'] ? 1 : 0, 'updated_ymdhis' => $now),
                'actor_id = :actor_id',
                array('actor_id' => $actor_id)
            );
        }
        
        return array(
            'success' => true, 
            'message' => "Status updated for {$actor['name']} (ID: $actor_id)"
        );
    }
    
    /**
     * Update or insert actor metadata
     */
    private function updateActorMetadata($actor_id, $property_key, $property_value, $now) {
        // Check if metadata exists
        $existing = $this->db->fetchRow(
            "SELECT metadata_id FROM {$this->table_prefix}metadata 
             WHERE entity_type = 'actor' AND entity_id = :actor_id AND property_key = :property_key AND is_deleted = 0 LIMIT 1",
            array('actor_id' => $actor_id, 'property_key' => $property_key)
        );
        
        if ($existing && isset($existing['metadata_id'])) {
            // Update existing
            $this->db->update(
                $this->table_prefix . 'metadata',
                array('property_value' => $property_value, 'updated_ymdhis' => $now),
                'metadata_id = :metadata_id',
                array('metadata_id' => $existing['metadata_id'])
            );
        } else {
            // Insert new
            $next_id = (int) $this->db->fetchOne(
                "SELECT COALESCE(MAX(metadata_id), 0) + 1 FROM {$this->table_prefix}metadata",
                array()
            );
            
            $this->db->insert($this->table_prefix . 'metadata', array(
                'metadata_id' => $next_id,
                'entity_type' => 'actor',
                'entity_id' => $actor_id,
                'domain_id' => null,
                'meta_type' => null,
                'property_key' => $property_key,
                'property_value' => $property_value,
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_deleted' => 0,
            ));
        }
    }
    
    /**
     * Delete actor metadata
     */
    private function deleteActorMetadata($actor_id, $property_key) {
        $this->db->update(
            $this->table_prefix . 'metadata',
            array('is_deleted' => 1, 'updated_ymdhis' => gmdate('YmdHis')),
            "entity_type = 'actor' AND entity_id = :actor_id AND property_key = :property_key",
            array('actor_id' => $actor_id, 'property_key' => $property_key)
        );
    }
    
    /**
     * Get actor status summary
     */
    public function getStatusSummary() {
        $summary = array();
        
        // Online status counts
        $online_counts = $this->db->fetchAll(
            "SELECT COALESCE(m.property_value, 'offline') as status, COUNT(*) as count
             FROM {$this->table_prefix}actors a
             LEFT JOIN {$this->table_prefix}metadata m ON a.actor_id = m.entity_id AND m.entity_type = 'actor' AND m.property_key = 'online_status' AND m.is_deleted = 0
             WHERE a.is_deleted = 0
             GROUP BY COALESCE(m.property_value, 'offline')"
        );
        
        foreach ($online_counts as $row) {
            $summary['online_status'][$row['status']] = (int) $row['count'];
        }
        
        // Actor type counts
        $type_counts = $this->db->fetchAll(
            "SELECT actor_type, COUNT(*) as count
             FROM {$this->table_prefix}actors
             WHERE is_deleted = 0
             GROUP BY actor_type"
        );
        
        foreach ($type_counts as $row) {
            $summary['actor_types'][$row['actor_type']] = (int) $row['count'];
        }
        
        // Token-limited actors
        $token_limited = $this->db->fetchOne(
            "SELECT COUNT(*) FROM {$this->table_prefix}actors a
             JOIN {$this->table_prefix}metadata m ON a.actor_id = m.entity_id AND m.entity_type = 'actor' AND m.property_key = 'token_limit' AND m.is_deleted = 0
             WHERE a.is_deleted = 0",
            array()
        );
        
        $summary['token_limited'] = (int) $token_limited;
        
        return $summary;
    }
    
    /**
     * Render the actor status management interface
     */
    public function render() {
        $filters = array(
            'actor_type' => isset($_GET['actor_type']) ? $_GET['actor_type'] : '',
            'is_active' => isset($_GET['is_active']) ? $_GET['is_active'] : '',
            'is_agent' => isset($_GET['is_agent']) ? $_GET['is_agent'] : ''
        );
        
        $actors = $this->getActorsWithStatus($filters);
        $summary = $this->getStatusSummary();
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $error = 'Invalid CSRF token';
            } else {
                $actor_id = (int) $_POST['actor_id'];
                $status_data = array(
                    'online_status' => $_POST['online_status'],
                    'token_limit' => $_POST['token_limit'],
                    'token_reset_date' => $_POST['token_reset_date'],
                    'status_message' => $_POST['status_message'],
                    'is_active' => isset($_POST['is_active'])
                );
                
                $result = $this->updateActorStatus($actor_id, $status_data);
                if ($result['success']) {
                    $success = $result['message'];
                    // Refresh data
                    $actors = $this->getActorsWithStatus($filters);
                    $summary = $this->getStatusSummary();
                } else {
                    $error = $result['message'];
                }
            }
        }
        
        ?>
        <div class="admin-section">
            <div class="admin-header">
                <h2>Actor Status Management</h2>
                <p>Manage actor online/offline status, token limits, and availability.</p>
            </div>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <!-- Status Summary -->
            <div class="status-summary">
                <h3>Status Summary</h3>
                <div class="summary-cards">
                    <div class="summary-card">
                        <h4>Online Status</h4>
                        <?php foreach ($summary['online_status'] as $status => $count): ?>
                            <div class="status-item">
                                <span class="status-badge status-<?php echo htmlspecialchars($status); ?>"><?php echo htmlspecialchars($status); ?></span>
                                <span class="status-count"><?php echo $count; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="summary-card">
                        <h4>Actor Types</h4>
                        <?php foreach ($summary['actor_types'] as $type => $count): ?>
                            <div class="status-item">
                                <span class="type-badge"><?php echo htmlspecialchars($type); ?></span>
                                <span class="status-count"><?php echo $count; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="summary-card">
                        <h4>Token Management</h4>
                        <div class="status-item">
                            <span class="type-badge">Token Limited</span>
                            <span class="status-count"><?php echo $summary['token_limited']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="filters">
                <h3>Filters</h3>
                <form method="GET" class="filter-form">
                    <input type="hidden" name="section" value="actor_status">
                    
                    <div class="filter-group">
                        <label>Actor Type:</label>
                        <select name="actor_type">
                            <option value="">All Types</option>
                            <option value="agent" <?php echo $filters['actor_type'] === 'agent' ? 'selected' : ''; ?>>Agents</option>
                            <option value="human" <?php echo $filters['actor_type'] === 'human' ? 'selected' : ''; ?>>Humans</option>
                            <option value="ide_agent" <?php echo $filters['actor_type'] === 'ide_agent' ? 'selected' : ''; ?>>IDE Agents</option>
                            <option value="user" <?php echo $filters['actor_type'] === 'user' ? 'selected' : ''; ?>>Users</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Active Status:</label>
                        <select name="is_active">
                            <option value="">All</option>
                            <option value="1" <?php echo $filters['is_active'] === '1' ? 'selected' : ''; ?>>Active</option>
                            <option value="0" <?php echo $filters['is_active'] === '0' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Agent Status:</label>
                        <select name="is_agent">
                            <option value="">All</option>
                            <option value="1" <?php echo $filters['is_agent'] === '1' ? 'selected' : ''; ?>>Agents</option>
                            <option value="0" <?php echo $filters['is_agent'] === '0' ? 'selected' : ''; ?>>Non-Agents</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="?section=actor_status" class="btn btn-secondary">Clear</a>
                </form>
            </div>
            
            <!-- Actors Table -->
            <div class="actors-table">
                <h3>Actors (<?php echo count($actors); ?> found)</h3>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Online Status</th>
                            <th>Active</th>
                            <th>Token Limit</th>
                            <th>Reset Date</th>
                            <th>Status Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($actors as $actor): ?>
                            <tr>
                                <td><?php echo $actor['actor_id']; ?></td>
                                <td><?php echo htmlspecialchars($actor['name']); ?></td>
                                <td>
                                    <span class="type-badge"><?php echo htmlspecialchars($actor['actor_type']); ?></span>
                                    <?php if ($actor['is_agent']): ?>
                                        <span class="agent-badge">Agent</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo htmlspecialchars($actor['online_status']); ?>">
                                        <?php echo htmlspecialchars($actor['online_status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($actor['is_active']): ?>
                                        <span class="active-badge">Active</span>
                                    <?php else: ?>
                                        <span class="inactive-badge">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($actor['token_limit'] && $actor['token_limit'] > 0): ?>
                                        <?php echo htmlspecialchars($actor['token_limit']); ?>
                                    <?php else: ?>
                                        <span class="no-limit">Unlimited</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($actor['token_reset_date']): ?>
                                        <?php 
                                        $date = $actor['token_reset_date'];
                                        $formatted = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);
                                        echo htmlspecialchars($formatted);
                                        ?>
                                    <?php else: ?>
                                        <span class="no-reset">Not set</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($actor['status_message']): ?>
                                        <span class="status-message" title="<?php echo htmlspecialchars($actor['status_message']); ?>">
                                            <?php echo htmlspecialchars(substr($actor['status_message'], 0, 30)); ?>
                                            <?php if (strlen($actor['status_message']) > 30): ?>...<?php endif; ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="showEditModal(<?php echo $actor['actor_id']; ?>, '<?php echo htmlspecialchars($actor['name']); ?>', '<?php echo htmlspecialchars($actor['online_status']); ?>', '<?php echo htmlspecialchars($actor['token_limit']); ?>', '<?php echo htmlspecialchars($actor['token_reset_date']); ?>', '<?php echo htmlspecialchars($actor['status_message']); ?>', <?php echo $actor['is_active'] ? 'true' : 'false'; ?>)">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Edit Modal -->
        <div id="editModal" class="modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Edit Actor Status</h3>
                    <button class="modal-close" onclick="hideEditModal()">&times;</button>
                </div>
                <form method="POST" class="edit-form">
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="actor_id" id="edit_actor_id">
                    
                    <div class="form-group">
                        <label>Actor:</label>
                        <span id="edit_actor_name" class="actor-name"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="online_status">Online Status:</label>
                        <select name="online_status" id="online_status" required>
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                            <option value="busy">Busy</option>
                            <option value="away">Away</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="is_active">
                            <input type="checkbox" name="is_active" id="is_active">
                            Actor is Active
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label for="token_limit">Token Limit (0 = unlimited):</label>
                        <input type="number" name="token_limit" id="token_limit" min="0" value="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="token_reset_date">Token Reset Date (YYYYMMDD, empty = no reset):</label>
                        <input type="text" name="token_reset_date" id="token_reset_date" placeholder="20260303" pattern="[0-9]{8}">
                        <small>Format: YYYYMMDD (e.g., 20260303 for March 3, 2026)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="status_message">Status Message:</label>
                        <textarea name="status_message" id="status_message" rows="3" placeholder="Optional status message..."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Status</button>
                        <button type="button" class="btn btn-secondary" onclick="hideEditModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        
        <style>
        .status-summary {
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }
        
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 15px;
        }
        
        .summary-card {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }
        
        .summary-card h4 {
            margin: 0 0 10px 0;
            color: #495057;
            font-size: 14px;
            font-weight: 600;
        }
        
        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .status-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .status-online { background: #d4edda; color: #155724; }
        .status-offline { background: #f8d7da; color: #721c24; }
        .status-busy { background: #fff3cd; color: #856404; }
        .status-away { background: #e2e3e5; color: #383d41; }
        .status-maintenance { background: #d1ecf1; color: #0c5460; }
        
        .type-badge {
            background: #e9ecef;
            color: #495057;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .agent-badge {
            background: #007bff;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 500;
            margin-left: 5px;
        }
        
        .active-badge {
            background: #28a745;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
        }
        
        .inactive-badge {
            background: #6c757d;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
        }
        
        .no-limit, .no-reset {
            color: #6c757d;
            font-style: italic;
            font-size: 12px;
        }
        
        .status-message {
            display: block;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .filters {
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }
        
        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .filter-group select {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        
        .actors-table {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        
        .modal-content {
            position: relative;
            background: white;
            margin: 50px auto;
            padding: 0;
            width: 90%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h3 {
            margin: 0;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
        }
        
        .edit-form {
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-group input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
        }
        
        .form-group small {
            display: block;
            margin-top: 5px;
            color: #6c757d;
            font-size: 12px;
        }
        
        .actor-name {
            font-weight: 600;
            color: #495057;
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
        }
        
        .alert {
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        </style>
        
        <script>
        function showEditModal(actorId, actorName, onlineStatus, tokenLimit, tokenResetDate, statusMessage, isActive) {
            document.getElementById('edit_actor_id').value = actorId;
            document.getElementById('edit_actor_name').textContent = actorName + ' (ID: ' + actorId + ')';
            document.getElementById('online_status').value = onlineStatus;
            document.getElementById('is_active').checked = isActive;
            document.getElementById('token_limit').value = tokenLimit || '0';
            document.getElementById('token_reset_date').value = tokenResetDate || '';
            document.getElementById('status_message').value = statusMessage || '';
            document.getElementById('editModal').style.display = 'block';
        }
        
        function hideEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                hideEditModal();
            }
        }
        </script>
        <?php
    }
}
