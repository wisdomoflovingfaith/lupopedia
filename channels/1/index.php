<?php
//===========================================================================
//* --                LUPOPEDIA Live Help Channel Interface      -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------

// Load configuration first
require_once("../../lupopedia-config.php");

// Then load bootstrap
require_once("../../lupo-includes/bootstrap.php");

// Validate channel access and get current actor
$channel_id = intval($_GET['channel_id'] ?? 1);
$session_id = $_SESSION['session_id'] ?? '';
$actor_id = getCurrentActorId($session_id);

// Set security headers
setSecurityHeaders();

// Get channel information
$channel_info = getChannelInfo($channel_id);
if (!$channel_info) {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>Channel not found</h1>";
    exit;
}

// Generate CSRF token
$csrf_token = generateCSRFToken($actor_id, $session_id);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Help - Channel <?php echo htmlspecialchars($channel_id); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../../lupo-includes/js/livehelp-communication.js"></script>
    <script src="../../lupo-includes/js/iframe-manager.js"></script>
    <style>
        .channel-interface {
            display: grid;
            grid-template-areas: 
                "header header"
                "sidebar sidebar"
                "main main"
                "chat chat";
            grid-template-columns: 1fr 300px 1fr;
            grid-template-rows: 60px 1fr 300px;
            height: 100vh;
            gap: 0;
        }
        
        .channel-header {
            grid-area: header;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .channel-sidebar {
            grid-area: sidebar;
            background: #f9fafb;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
        }
        
        .channel-main {
            grid-area: main;
            background: #ffffff;
            display: flex;
            flex-direction: column;
        }
        
        .channel-chat {
            grid-area: chat;
            background: #f3f4f6;
            border-top: 1px solid #e5e7eb;
        }
        
        .sidebar-section {
            border-bottom: 1px solid #e5e7eb;
            padding: 16px;
        }
        
        .sidebar-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        
        .iframe-container {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        @media (max-width: 768px) {
            .channel-interface {
                grid-template-columns: 1fr;
                grid-template-rows: 60px 1fr 300px;
            }
            
            .channel-sidebar {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="channel-interface">
        <!-- Channel Header with Lupopedia Navigation -->
        <div class="channel-header">
            <div class="flex items-center space-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">
                        Live Help - Channel <?php echo htmlspecialchars($channel_id); ?>
                    </h1>
                    <p class="text-sm text-gray-600">
                        <?php echo htmlspecialchars($channel_info['name'] ?? 'Channel'); ?>
                    </p>
                </div>
                <div>
                    <!-- Lupopedia Navigation Integration -->
                    <?php include('../../lupo-includes/ui/channel-navigation.php'); ?>
                </div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="channel-main">
            <div class="channel-sidebar">
                <!-- Room Management -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Rooms</h3>
                    <div class="iframe-container">
                        <?php echo createSecureIframe('admin_rooms.php?department=' . $channel_id, 'rooms', 'h-64'); ?>
                    </div>
                </div>
                
                <!-- Connection Status -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Connection Status</h3>
                    <div class="iframe-container">
                        <?php echo createSecureIframe('admin_connect.php?department=' . $channel_id . '&rand=' . time(), 'connection', 'h-48'); ?>
                    </div>
                </div>
                
                <!-- User Management -->
                <div class="sidebar-section flex-1">
                    <h3 class="sidebar-title">Users</h3>
                    <div class="iframe-container">
                        <?php echo createSecureIframe('admin_users.php?department=' . $channel_id, 'users', 'h-96'); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chat Interface -->
        <div class="channel-chat">
            <div class="iframe-container">
                <?php echo createSecureIframe('admin_chat_bot.php?department=' . $channel_id, 'chat', 'w-full'); ?>
            </div>
        </div>
    </div>
    
    <!-- Initialize Communication System -->
    <script>
        // Initialize the livehelp communication system
        document.addEventListener('DOMContentLoaded', function() {
            const livehelp = new LivehelpCommunication(<?php echo $channel_id; ?>, '<?php echo $session_id; ?>');
            const iframeManager = new IframeManager();
            
            // Register all iframes
            iframeManager.registerIframe('rooms', document.querySelector('iframe[name="rooms"]'));
            iframeManager.registerIframe('connection', document.querySelector('iframe[name="connection"]'));
            iframeManager.registerIframe('users', document.querySelector('iframe[name="users"]'));
            iframeManager.registerIframe('chat', document.querySelector('iframe[name="chat"]'));
            
            // Start communication
            livehelp.connect();
        });
    </script>
</body>
</html>

<?php
/**
 * Helper Functions
 */

function getCurrentActorId($session_id) {
    $db = lupo_get_db();
    
    if (!$db) {
        error_log("Database connection failed in getCurrentActorId");
        return 0;
    }
    
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    $sql = "SELECT actor_id FROM {$table_prefix}sessions 
              WHERE session_id = :session_id AND is_deleted = 0";
    
    $result = $db->fetchRow($sql, ['session_id' => $session_id]);
    return $result ? $result['actor_id'] : 0;
}

function getChannelInfo($channel_id) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    $sql = "SELECT * FROM {$table_prefix}channels 
              WHERE channel_id = :channel_id AND is_deleted = 0";
    
    return $db->fetch($sql, ['channel_id' => $channel_id]);
}

function createSecureIframe($src, $name, $class = '') {
    $csp = "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';";
    return sprintf(
        '<iframe src="%s" name="%s" class="%s" 
                sandbox="allow-same-origin allow-scripts" 
                referrerpolicy="strict-origin-when-cross-origin"
                style="border: none; width: 100%; height: 100%;"></iframe>',
        htmlspecialchars($src, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($class, ENT_QUOTES, 'UTF-8')
    );
}

function generateCSRFToken($actor_id, $session_id) {
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

function setSecurityHeaders() {
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; frame-src 'self'; connect-src 'self' ws: wss:");
    header("X-Frame-Options: SAMEORIGIN");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: strict-origin-when-cross-origin");
}
?>
