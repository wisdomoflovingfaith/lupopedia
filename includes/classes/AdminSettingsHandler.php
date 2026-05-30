<?php
/**
 * Admin Settings Handler - Lupopedia Master Settings Interface
 * Provides web interface for managing system-wide configuration settings.
 */

class AdminSettingsHandler {
    
    private $db;
    private $prefix;
    private $base;
    
    public function __construct($db, $prefix, $base) {
        $this->db = $db;
        $this->prefix = $prefix;
        $this->base = $base;
    }
    
    /**
     * Render the settings management interface
     */
    public function render() {
        $action = isset($_POST['action']) ? $_POST['action'] : 'view';
        
        switch ($action) {
            case 'save':
                return $this->handleSave();
            case 'view':
            default:
                return $this->renderSettingsForm();
        }
    }
    
    /**
     * Render the main settings form
     */
    private function renderSettingsForm() {
        $settings = $this->getCurrentSettings();
        $message = '';
        
        if (isset($_GET['saved'])) {
            $message = '<div class="admin-success">Settings saved successfully.</div>';
        }
        
        $html = '
        <div class="admin-settings">
            <h2>Master Settings</h2>
            ' . $message . '
            <p class="admin-description">Configure system-wide settings for your Lupopedia installation.</p>
            
            <form method="post" action="' . htmlspecialchars($this->base . '/admin.php?section=settings') . '" class="admin-form">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="csrf_token" value="' . htmlspecialchars(lupo_get_csrf_token()) . '">
                
                <div class="admin-form-section">
                    <h3>Site Configuration</h3>
                    
                    <div class="form-group">
                        <label for="site_name">Site Name:</label>
                        <input type="text" id="site_name" name="site_name" value="' . htmlspecialchars($settings['site_name']) . '" class="form-control">
                        <small class="form-help">Display name for your Lupopedia installation</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="site_description">Site Description:</label>
                        <textarea id="site_description" name="site_description" rows="3" class="form-control">' . htmlspecialchars($settings['site_description']) . '</textarea>
                        <small class="form-help">Brief description of your site</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="admin_email">Administrator Email:</label>
                        <input type="email" id="admin_email" name="admin_email" value="' . htmlspecialchars($settings['admin_email']) . '" class="form-control">
                        <small class="form-help">Email for system notifications</small>
                    </div>
                </div>
                
                <div class="admin-form-section">
                    <h3>Localization</h3>
                    
                    <div class="form-group">
                        <label for="timezone">Timezone:</label>
                        <select id="timezone" name="timezone" class="form-control">
                            ' . $this->renderTimezoneOptions($settings['timezone']) . '
                        </select>
                        <small class="form-help">Server timezone for timestamps</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_format">Date Format:</label>
                        <select id="date_format" name="date_format" class="form-control">
                            ' . $this->renderDateFormatOptions($settings['date_format']) . '
                        </select>
                        <small class="form-help">Display format for dates</small>
                    </div>
                </div>
                
                <div class="admin-form-section">
                    <h3>Feature Flags</h3>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="enable_registration" value="1" ' . ($settings['enable_registration'] ? 'checked' : '') . '>
                            Enable User Registration
                        </label>
                        <small class="form-help">Allow new users to register accounts</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="enable_public_access" value="1" ' . ($settings['enable_public_access'] ? 'checked' : '') . '>
                            Enable Public Access
                        </label>
                        <small class="form-help">Allow non-logged-in users to view public content</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="enable_ai_agents" value="1" ' . ($settings['enable_ai_agents'] ? 'checked' : '') . '>
                            Enable AI Agents
                        </label>
                        <small class="form-help">Allow AI agents to participate in channels</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="enable_semantic_features" value="1" ' . ($settings['enable_semantic_features'] ? 'checked' : '') . '>
                            Enable Semantic Features
                        </label>
                        <small class="form-help">Enable collections, tabs, and semantic navigation</small>
                    </div>
                </div>
                
                <div class="admin-form-section">
                    <h3>Visitor layout shell</h3>
                    <div class="form-group">
                        <label for="public_content_shell">Public content chrome</label>
                        <select id="public_content_shell" name="public_content_shell" class="form-control">
                            <option value="book"' . ((isset($settings['public_content_shell']) ? $settings['public_content_shell'] : 'book') !== 'scroll' ? ' selected' : '') . '>Book layout (s*b tile set)</option>
                            <option value="scroll"' . ((isset($settings['public_content_shell']) ? $settings['public_content_shell'] : 'book') === 'scroll' ? ' selected' : '') . '>Scroll layout (s*a tile set)</option>
                        </select>
                        <small class="form-help">Swaps the 9-slice border art on the main public content shell (body class book-layout vs scroll-layout).</small>
                    </div>
                </div>
                
                <div class="admin-form-section">
                    <h3>System Limits</h3>
                    
                    <div class="form-group">
                        <label for="max_upload_size">Max Upload Size (MB):</label>
                        <input type="number" id="max_upload_size" name="max_upload_size" value="' . htmlspecialchars($settings['max_upload_size']) . '" min="1" max="100" class="form-control">
                        <small class="form-help">Maximum file upload size in megabytes</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="session_timeout">Session Timeout (minutes):</label>
                        <input type="number" id="session_timeout" name="session_timeout" value="' . htmlspecialchars($settings['session_timeout']) . '" min="5" max="1440" class="form-control">
                        <small class="form-help">User session inactivity timeout</small>
                    </div>
                </div>
                
                <div class="admin-form-actions">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                    <a href="' . htmlspecialchars($this->base . '/admin.php') . '" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>';
        
        return $html;
    }
    
    /**
     * Handle form submission and save settings
     */
    private function handleSave() {
        if (!lupo_verify_csrf_token($_POST['csrf_token'])) {
            return '<div class="admin-error">Invalid CSRF token. Please try again.</div>';
        }
        
        $settings = array(
            'site_name' => $_POST['site_name'] ?? 'Lupopedia',
            'site_description' => $_POST['site_description'] ?? '',
            'admin_email' => $_POST['admin_email'] ?? '',
            'timezone' => $_POST['timezone'] ?? 'UTC',
            'date_format' => $_POST['date_format'] ?? 'Y-m-d H:i:s',
            'enable_registration' => isset($_POST['enable_registration']) ? 1 : 0,
            'enable_public_access' => isset($_POST['enable_public_access']) ? 1 : 0,
            'enable_ai_agents' => isset($_POST['enable_ai_agents']) ? 1 : 0,
            'enable_semantic_features' => isset($_POST['enable_semantic_features']) ? 1 : 0,
            'public_content_shell' => (isset($_POST['public_content_shell']) && $_POST['public_content_shell'] === 'scroll') ? 'scroll' : 'book',
            'max_upload_size' => (int)($_POST['max_upload_size'] ?? 10),
            'session_timeout' => (int)($_POST['session_timeout'] ?? 30),
            'updated_ymdhis' => (int)gmdate('YmdHis')
        );
        
        // Validate settings
        $errors = $this->validateSettings($settings);
        if (!empty($errors)) {
            $error_html = '<div class="admin-error"><h4>Validation Errors:</h4><ul>';
            foreach ($errors as $error) {
                $error_html .= '<li>' . htmlspecialchars($error) . '</li>';
            }
            $error_html .= '</ul></div>';
            return $error_html . $this->renderSettingsForm();
        }
        
        // Save to database
        try {
            $this->saveSettings($settings);
            
            // Redirect to prevent form resubmission
            header('Location: ' . $this->base . '/admin.php?section=settings&saved=1');
            exit;
            
        } catch (Exception $e) {
            return '<div class="admin-error">Error saving settings: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
    
    /**
     * Validate submitted settings
     */
    private function validateSettings($settings) {
        $errors = array();
        
        if (empty($settings['site_name'])) {
            $errors[] = 'Site name is required';
        }
        
        if (!empty($settings['admin_email']) && !filter_var($settings['admin_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid administrator email address';
        }
        
        if ($settings['max_upload_size'] < 1 || $settings['max_upload_size'] > 100) {
            $errors[] = 'Max upload size must be between 1 and 100 MB';
        }
        
        if ($settings['session_timeout'] < 5 || $settings['session_timeout'] > 1440) {
            $errors[] = 'Session timeout must be between 5 and 1440 minutes';
        }
        
        if (isset($settings['public_content_shell']) && $settings['public_content_shell'] !== 'book' && $settings['public_content_shell'] !== 'scroll') {
            $errors[] = 'Public content shell must be book or scroll';
        }
        
        return $errors;
    }
    
    /**
     * Save settings to database
     */
    private function saveSettings($settings) {
        // Ensure settings table exists
        $this->ensureSettingsTable();
        
        // Update or insert each setting
        foreach ($settings as $key => $value) {
            if ($key === 'updated_ymdhis') {
                continue; // Skip metadata
            }
            
            $this->db->execute(
                "INSERT INTO {$this->prefix}settings (setting_key, setting_value, setting_type, updated_ymdhis) 
                 VALUES (:key, :value, :type, :updated) 
                 ON DUPLICATE KEY UPDATE 
                 setting_value = VALUES(setting_value), 
                 updated_ymdhis = VALUES(updated_ymdhis)",
                array(
                    'key' => $key,
                    'value' => is_bool($value) ? (int)$value : $value,
                    'type' => $this->getSettingType($value),
                    'updated' => $settings['updated_ymdhis']
                )
            );
        }
    }
    
    /**
     * Get current settings from database with defaults
     */
    private function getCurrentSettings() {
        $defaults = array(
            'site_name' => 'Lupopedia',
            'site_description' => 'Semantic Live Help System',
            'admin_email' => '',
            'timezone' => 'UTC',
            'date_format' => 'Y-m-d H:i:s',
            'enable_registration' => 1,
            'enable_public_access' => 1,
            'enable_ai_agents' => 1,
            'enable_semantic_features' => 1,
            'public_content_shell' => 'book',
            'max_upload_size' => 10,
            'session_timeout' => 30
        );
        
        // Try to load from database
        try {
            $rows = $this->db->fetchAll(
                "SELECT setting_key, setting_value, setting_type 
                 FROM {$this->prefix}settings 
                 WHERE is_deleted = 0"
            );
            
            foreach ($rows as $row) {
                $value = $this->castSettingValue($row['setting_value'], $row['setting_type']);
                $defaults[$row['setting_key']] = $value;
            }
        } catch (Exception $e) {
            // Table might not exist yet, use defaults
        }
        
        return $defaults;
    }
    
    /**
     * Ensure settings table exists
     */
    private function ensureSettingsTable() {
        $sql = "
        CREATE TABLE IF NOT EXISTS {$this->prefix}settings (
            setting_id BIGINT PRIMARY KEY AUTO_INCREMENT,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value TEXT,
            setting_type ENUM('string', 'integer', 'boolean', 'json') DEFAULT 'string',
            created_ymdhis BIGINT DEFAULT 0,
            updated_ymdhis BIGINT DEFAULT 0,
            is_deleted TINYINT DEFAULT 0,
            deleted_ymdhis BIGINT DEFAULT 0,
            INDEX idx_settings_key (setting_key),
            INDEX idx_settings_deleted (is_deleted)
        )";
        
        $this->db->execute($sql);
    }
    
    /**
     * Get setting type for database storage
     */
    private function getSettingType($value) {
        if (is_bool($value)) {
            return 'boolean';
        } elseif (is_int($value)) {
            return 'integer';
        } elseif (is_array($value) || is_object($value)) {
            return 'json';
        } else {
            return 'string';
        }
    }
    
    /**
     * Cast setting value from database to proper PHP type
     */
    private function castSettingValue($value, $type) {
        switch ($type) {
            case 'boolean':
                return (bool)$value;
            case 'integer':
                return (int)$value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }
    
    /**
     * Render timezone options
     */
    private function renderTimezoneOptions($selected) {
        $timezones = array(
            'UTC' => 'UTC',
            'America/New_York' => 'Eastern Time',
            'America/Chicago' => 'Central Time',
            'America/Denver' => 'Mountain Time',
            'America/Los_Angeles' => 'Pacific Time',
            'Europe/London' => 'London',
            'Europe/Paris' => 'Paris',
            'Asia/Tokyo' => 'Tokyo',
            'Australia/Sydney' => 'Sydney'
        );
        
        $options = '';
        foreach ($timezones as $value => $label) {
            $selected_attr = ($value === $selected) ? 'selected' : '';
            $options .= '<option value="' . htmlspecialchars($value) . '" ' . $selected_attr . '>' . htmlspecialchars($label) . '</option>';
        }
        
        return $options;
    }
    
    /**
     * Render date format options
     */
    private function renderDateFormatOptions($selected) {
        $formats = array(
            'Y-m-d H:i:s' => '2026-02-26 15:30:45',
            'm/d/Y H:i:s' => '02/26/2026 15:30:45',
            'd/m/Y H:i:s' => '26/02/2026 15:30:45',
            'Y-m-d' => '2026-02-26',
            'm/d/Y' => '02/26/2026',
            'd/m/Y' => '26/02/2026',
            'F j, Y g:i A' => 'February 26, 2026 3:30 PM'
        );
        
        $options = '';
        foreach ($formats as $value => $label) {
            $selected_attr = ($value === $selected) ? 'selected' : '';
            $options .= '<option value="' . htmlspecialchars($value) . '" ' . $selected_attr . '>' . htmlspecialchars($label) . '</option>';
        }
        
        return $options;
    }
}
