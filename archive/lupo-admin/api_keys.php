<?php
/*
 * lupopedia.headers:
 * header_format_version: "4.1.3"
 * file_path_from_root: "lupo-admin/api_keys.php"
 * web_path: "https://www.lupopedia.com/lupopedia/lupo-admin/api_keys.php"
 * status: "active"
 * when_updated: "20260420080000"
 * trust_tier: "canonical"
 * questions_toon: null
 * memory_toon: "lupo-memory/admin/canonical/1026/04/api-keys.toon"
 * atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
 * transcript_jsonl: "0/admin/api-keys"
 * artifact_type: implementation
 * artifact_kind: interface
 * channel_key: "admin"
 * federation_node_id: 0
 * thread_id: null
 * content_id: null
 * content_parent_id: null
 * content_slug: "api-keys"
 * default_collection_id: null
 * lupopedia.schema: implementation
 * title: "API Keys Management Interface"
 * summary: "Admin interface for managing external LLM service API keys with CSRF protection."
 */

/**
 * API Keys Management Interface
 * @package Lupopedia Admin
 */

// Ensure we're in admin context
require_once dirname(__DIR__) . '/lupopedia-config.php';

// Simple CSRF token
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['api_keys_token'])) {
    $_SESSION['api_keys_token'] = bin2hex(random_bytes(32));
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['api_keys_token']) {
        die('Invalid request');
    }
    
    // Update config file
    $config_file = dirname(__DIR__) . '/lupopedia-config.php';
    $config_content = file_get_contents($config_file);
    
    // Update API keys
    $api_keys = [
        'chatgpt' => $_POST['chatgpt'] ?? '',
        'deepseek' => $_POST['deepseek'] ?? '',
        'grok' => $_POST['grok'] ?? '',
        'gemini' => $_POST['gemini'] ?? '',
        'copilot_vscode' => $_POST['copilot_vscode'] ?? '',
    ];
    
    // Build new API keys array
    $new_keys_section = "// API Keys for External LLM Services\n// WARNING: Never echo, log, or expose these values in responses\n\$lupopedia_api_keys = [\n";
    foreach ($api_keys as $key => $value) {
        $new_keys_section .= "    '{$key}'      => '" . addslashes($value) . "',\n";
    }
    $new_keys_section .= "];\n";
    
    // Replace in config file
    $pattern = '/\/\/ API Keys for External LLM Services.*?\];\n/s';
    $config_content = preg_replace($pattern, $new_keys_section, $config_content);
    
    file_put_contents($config_file, $config_content);
    
    // Redirect to prevent form resubmission
    header('Location: api_keys.php?saved=1');
    exit;
}

// Load current keys for display (masked)
global $lupopedia_api_keys;
$current_keys = $lupopedia_api_keys ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>API Keys - Lupopedia Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] { width: 400px; padding: 8px; }
        button { padding: 10px 20px; background: #007cba; color: white; border: none; cursor: pointer; }
        button:hover { background: #005a87; }
        .success { color: green; margin: 10px 0; }
        .masked { color: #666; }
    </style>
</head>
<body>
    <h1>API Keys Management</h1>
    
    <?php if (isset($_GET['saved'])): ?>
        <div class="success">API keys saved successfully!</div>
    <?php endif; ?>
    
    <form method="post">
        <input type="hidden" name="token" value="<?php echo $_SESSION['api_keys_token']; ?>">
        
        <div class="form-group">
            <label for="chatgpt">ChatGPT API Key:</label>
            <input type="text" id="chatgpt" name="chatgpt" 
                   value="<?php echo !empty($current_keys['chatgpt']) ? '****' . substr($current_keys['chatgpt'], -4) : ''; ?>"
                   placeholder="Enter ChatGPT API key">
        </div>
        
        <div class="form-group">
            <label for="deepseek">DeepSeek API Key:</label>
            <input type="text" id="deepseek" name="deepseek"
                   value="<?php echo !empty($current_keys['deepseek']) ? '****' . substr($current_keys['deepseek'], -4) : ''; ?>"
                   placeholder="Enter DeepSeek API key">
        </div>
        
        <div class="form-group">
            <label for="grok">Grok API Key:</label>
            <input type="text" id="grok" name="grok"
                   value="<?php echo !empty($current_keys['grok']) ? '****' . substr($current_keys['grok'], -4) : ''; ?>"
                   placeholder="Enter Grok API key">
        </div>
        
        <div class="form-group">
            <label for="gemini">Gemini API Key:</label>
            <input type="text" id="gemini" name="gemini"
                   value="<?php echo !empty($current_keys['gemini']) ? '****' . substr($current_keys['gemini'], -4) : ''; ?>"
                   placeholder="Enter Gemini API key">
        </div>
        
        <div class="form-group">
            <label for="copilot_vscode">Copilot VS Code API Key:</label>
            <input type="text" id="copilot_vscode" name="copilot_vscode"
                   value="<?php echo !empty($current_keys['copilot_vscode']) ? '****' . substr($current_keys['copilot_vscode'], -4) : ''; ?>"
                   placeholder="Enter Copilot VS Code API key">
        </div>
        
        <button type="submit">Save API Keys</button>
    </form>
    
    <p class="masked">Note: Existing keys are masked for security. Only the last 4 characters are shown.</p>
</body>
</html>

<?php
#   lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "lupo-admin/api_keys.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-admin/api_keys.php"
#   status: "active"
#   when_updated: "20260420080000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/admin/canonical/1026/04/api-keys.toon"
#   atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
#   transcript_jsonl: "0/admin/api-keys"
#   artifact_type: implementation
#   artifact_kind: interface
#   channel_key: "admin"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "api-keys"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "API Keys Management Interface"
#   summary: "Admin interface for managing external LLM service API keys with CSRF protection."
# ---------------------------------------------------------------------

/**
 * API Keys Management Interface
 * @package Lupopedia Admin
 */

// Ensure we're in admin context
require_once dirname(__DIR__) . '/lupopedia-config.php';

// Simple CSRF token
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['api_keys_token'])) {
    $_SESSION['api_keys_token'] = bin2hex(random_bytes(32));
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['api_keys_token']) {
        die('Invalid request');
    }
    
    // Update config file
    $config_file = dirname(__DIR__) . '/lupopedia-config.php';
    $config_content = file_get_contents($config_file);
    
    // Update API keys
    $api_keys = [
        'chatgpt' => $_POST['chatgpt'] ?? '',
        'deepseek' => $_POST['deepseek'] ?? '',
        'grok' => $_POST['grok'] ?? '',
        'gemini' => $_POST['gemini'] ?? '',
        'copilot_vscode' => $_POST['copilot_vscode'] ?? '',
    ];
    
    // Build new API keys array
    $new_keys_section = "// API Keys for External LLM Services\n// WARNING: Never echo, log, or expose these values in responses\n\$lupopedia_api_keys = [\n";
    foreach ($api_keys as $key => $value) {
        $new_keys_section .= "    '{$key}'      => '" . addslashes($value) . "',\n";
    }
    $new_keys_section .= "];\n";
    
    // Replace in config file
    $pattern = '/\/\/ API Keys for External LLM Services.*?\];\n/s';
    $config_content = preg_replace($pattern, $new_keys_section, $config_content);
    
    file_put_contents($config_file, $config_content);
    
    // Redirect to prevent form resubmission
    header('Location: api_keys.php?saved=1');
    exit;
}

// Load current keys for display (masked)
global $lupopedia_api_keys;
$current_keys = $lupopedia_api_keys ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>API Keys - Lupopedia Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] { width: 400px; padding: 8px; }
        button { padding: 10px 20px; background: #007cba; color: white; border: none; cursor: pointer; }
        button:hover { background: #005a87; }
        .success { color: green; margin: 10px 0; }
        .masked { color: #666; }
    </style>
</head>
<body>
    <h1>API Keys Management</h1>
    
    <?php if (isset($_GET['saved'])): ?>
        <div class="success">API keys saved successfully!</div>
    <?php endif; ?>
    
    <form method="post">
        <input type="hidden" name="token" value="<?php echo $_SESSION['api_keys_token']; ?>">
        
        <div class="form-group">
            <label for="chatgpt">ChatGPT API Key:</label>
            <input type="text" id="chatgpt" name="chatgpt" 
                   value="<?php echo !empty($current_keys['chatgpt']) ? '****' . substr($current_keys['chatgpt'], -4) : ''; ?>"
                   placeholder="Enter ChatGPT API key">
        </div>
        
        <div class="form-group">
            <label for="deepseek">DeepSeek API Key:</label>
            <input type="text" id="deepseek" name="deepseek"
                   value="<?php echo !empty($current_keys['deepseek']) ? '****' . substr($current_keys['deepseek'], -4) : ''; ?>"
                   placeholder="Enter DeepSeek API key">
        </div>
        
        <div class="form-group">
            <label for="grok">Grok API Key:</label>
            <input type="text" id="grok" name="grok"
                   value="<?php echo !empty($current_keys['grok']) ? '****' . substr($current_keys['grok'], -4) : ''; ?>"
                   placeholder="Enter Grok API key">
        </div>
        
        <div class="form-group">
            <label for="gemini">Gemini API Key:</label>
            <input type="text" id="gemini" name="gemini"
                   value="<?php echo !empty($current_keys['gemini']) ? '****' . substr($current_keys['gemini'], -4) : ''; ?>"
                   placeholder="Enter Gemini API key">
        </div>
        
        <div class="form-group">
            <label for="copilot_vscode">Copilot VS Code API Key:</label>
            <input type="text" id="copilot_vscode" name="copilot_vscode"
                   value="<?php echo !empty($current_keys['copilot_vscode']) ? '****' . substr($current_keys['copilot_vscode'], -4) : ''; ?>"
                   placeholder="Enter Copilot VS Code API key">
        </div>
        
        <button type="submit">Save API Keys</button>
    </form>
    
    <p class="masked">Note: Existing keys are masked for security. Only the last 4 characters are shown.</p>
</body>
</html>
