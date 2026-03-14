# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\channels\42\threads\DEVELOPMENT_CYCLE_4_0_47\20260226051200_10000_1002_livehelp_implementation_plan.md"
  file_hash: "68544b940c083343ba0ef912c850f75023cfcead14fa4e3da6289ecdb1e3d8df"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-channels\42\threads\DEVELOPMENT_CYCLE_4_0_47\20260226051200_10000_1002_livehelp_implementation_plan.md"
  file_hash: "8f7b79c1ea7a8ffc487c1694f834cc60a90c056bb0f8441bc1ff5257561062ed"
  file_path_from_root: "lupo-channels\42\threads\DEVELOPMENT_CYCLE_4_0_47\20260226051200_10000_1002_livehelp_implementation_plan.md"
  file_hash: "24ee80c3036e9da28722f46c1998ad249bbbe95fa8f07dc8ec38ffaab39a9781"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260226051200_10000_1002_livehelp_implementation_plan.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "42", "threads", "development_cycle_4_0_47", "20260226051200_10000_1002_livehelp_implementation_planmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_47/20260226051200_10000_1002_livehelp_implementation_plan.md",
  system_version: "4.0.47",
  channel_id: 42,
  mood_rgb: "32CD32",
  purpose: "Implementation plan for modernizing livehelp interface based on frame analysis recommendations",
  last_modified_utc: "20260226",
  delegation_chain: "10000:1002",
  actor_id: 1002,
  lupo_agent: "windsurf",
  artifact_type: "implementation_plan",
  artifact_kind: "development_plan",
  traits: ["livehelp", "implementation", "modernization", "iframes", "channels"],
  hashtags: ["#livehelp", "#implementation", "#modernization", "#iframes", "#channels"],
  engagement: {
    likes: 0,
    shares: 0,
    views: 0,
    last_interaction_utc: "20260226"
  },
  graph_stats: {
    inbound_count: 3,
    outbound_count: 6,
    centrality_score: 0.65
  }
}
flip.footer: {
  inbound_edges: [
    { from: "lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_47/20260226051100_10000_1002_livehelp_frame_analysis.md", type: "implements", weight: 1.0, hashtag: "#implementation" }
  ],
  outbound_edges: [
    { to: "lupo-channels/1/", type: "creates", weight: 0.9, hashtag: "#channels" },
    { to: "lupo-channels/1/admin_chat_xmlhttp.php", type: "modernizes", weight: 0.8, hashtag: "#legacy" }
  ],
  referenced_by_actors: [1002],
  references: {
    by_files: [
      "lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_47/20260226051100_10000_1002_livehelp_frame_analysis.md"
    ],
    by_actors: [1002]
  },
  semantic_tags: [
    "livehelp_modernization",
    "iframe_implementation",
    "channel_interface",
    "php_development",
    "crafty_syntax_migration"
  ],
  enrichment: {
    llm_inferred_edges: [],
    federated_metrics: {}
  },
  version: "4.0.47",
  last_verified_utc: "20260226",
  last_verified_by: "windsurf"
}
---

# Livehelp Implementation Plan

## Overview
Based on the comprehensive frame analysis, this implementation plan outlines the step-by-step modernization of the legacy Crafty Syntax livehelp interface using modern iframe-based design within Lupopedia's channel system.

## Phase 1: Structural Foundation

### 1.1 Create Modern Channel Interface Template
**File**: `lupo-channels/1/index.php`

**Requirements:**
- Replace `<frameset>` with responsive CSS Grid layout
- Implement semantic HTML5 structure
- Add Tailwind CSS for modern styling
- Ensure mobile responsiveness
- Integrate with Lupopedia's header navigation system

**Implementation Steps:**
1. Create HTML structure with semantic divs
2. Implement CSS Grid for layout management
3. Add responsive breakpoints for mobile/tablet/desktop
4. Integrate with existing channel routing system
5. **Replace admin_options.php with Lupopedia header navigation**

**Lupopedia Header Integration:**
The legacy `admin_options.php?tab=live` is replaced by Lupopedia's comprehensive header navigation system which includes:
- User login/authentication interface
- Help system access
- Content management links
- QA system integration
- Navigation breadcrumbs and search

**Updated Component Mapping:**
- **Channel Controls**: Use Lupopedia header navigation instead of `admin_options.php`
- **Room Management**: `admin_rooms.php` (unchanged)
- **Connection Status**: `admin_connect.php` (unchanged)
- **User Management**: `admin_users.php` (unchanged)
- **Chat Interface**: `admin_chat_bot.php` (unchanged)

**Implementation:**
```php
// Create iframe wrapper with security attributes
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
```

## Phase 2: Communication Modernization

### 2.1 Update xmlhttp.js to Modern Standards
**File**: `lupo-includes/js/livehelp-communication.js`

**Modern Features:**
- Replace XML HTTP polling with Fetch API
- Implement WebSocket connection with fallback to polling
- Add automatic reconnection logic
- Implement proper error handling and retry mechanisms

**Implementation:**
```javascript
class LivehelpCommunication {
    constructor(channelId, sessionId) {
        this.channelId = channelId;
        this.sessionId = sessionId;
        this.websocket = null;
        this.pollingInterval = null;
        this.messageQueue = [];
        this.isConnected = false;
    }

    async connect() {
        try {
            // Try WebSocket first
            this.websocket = new WebSocket(`wss://localhost/lupopedia/ws/channels/${this.channelId}`);
            this.setupWebSocketEvents();
        } catch (error) {
            // Fallback to HTTP polling
            this.startPolling();
        }
    }

    setupWebSocketEvents() {
        this.websocket.onopen = () => {
            this.isConnected = true;
            this.flushMessageQueue();
        };

        this.websocket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            this.routeMessage(data);
        };

        this.websocket.onclose = () => {
            this.isConnected = false;
            this.startPolling(); // Fallback to polling
        };
    }

    async sendMessage(message) {
        if (this.isConnected && this.websocket) {
            this.websocket.send(JSON.stringify(message));
        } else {
            this.messageQueue.push(message);
        }
    }

    flushMessageQueue() {
        while (this.messageQueue.length > 0) {
            const message = this.messageQueue.shift();
            this.websocket.send(JSON.stringify(message));
        }
    }
}
```

### 2.2 Cross-Iframe Communication
**Implementation**: Use `postMessage` API for secure communication

```javascript
class IframeManager {
    constructor() {
        this.iframeWindows = new Map();
        this.setupMessageListener();
    }

    registerIframe(name, iframeElement) {
        this.iframeWindows.set(name, iframeElement.contentWindow);
    }

    setupMessageListener() {
        window.addEventListener('message', (event) => {
            // Validate origin for security
            if (event.origin !== window.location.origin) {
                return;
            }

            this.routeMessage(event.data);
        });
    }

    sendMessage(targetIframe, message) {
        const targetWindow = this.iframeWindows.get(targetIframe);
        if (targetWindow) {
            targetWindow.postMessage(message, window.location.origin);
        }
    }

    routeMessage(data) {
        switch (data.type) {
            case 'user_update':
                this.sendMessage('users', data);
                break;
            case 'connection_change':
                this.sendMessage('connection', data);
                break;
            case 'new_message':
                this.sendMessage('chat', data);
                break;
        }
    }
}
```

## Phase 3: Backend Integration

### 3.1 Update admin_chat_xmlhttp.php
**File**: `lupo-channels/1/admin_chat_xmlhttp.php`

**Modernizations:**
- Add JSON response format alongside XML
- Implement WebSocket message broadcasting
- Add proper HTTP headers for CORS
- Integrate with Lupopedia session management

**Implementation:**
```php
<?php
require_once("../../lupo-includes/bootstrap.php");

// Validate session and get actor info
$session_id = $_SESSION['session_id'] ?? '';
$actor_id = getCurrentActorId($session_id);

// Set proper headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle different request types
$action = $_GET['action'] ?? 'get_messages';

switch ($action) {
    case 'get_messages':
        $messages = getChannelMessages($_GET['channel_id'], $_GET['offset'] ?? 0);
        echo json_encode(['status' => 'success', 'data' => $messages]);
        break;
        
    case 'send_message':
        $result = sendMessageToChannel($_GET['channel_id'], $actor_id, $_POST['message']);
        echo json_encode(['status' => $result ? 'success' : 'error']);
        break;
        
    case 'get_online_users':
        $users = getOnlineUsers($_GET['channel_id']);
        echo json_encode(['status' => 'success', 'data' => $users]);
        break;
}

function getChannelMessages($channelId, $offset = 0) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    $sql = "SELECT dm.*, da.name as from_actor_name, da2.name as to_actor_name
              FROM {$table_prefix}dialog_messages dm
              LEFT JOIN {$table_prefix}actors da ON dm.from_actor_id = da.actor_id
              LEFT JOIN {$table_prefix}actors da2 ON dm.to_actor_id = da2.actor_id
              WHERE dm.channel_id = :channel_id 
              AND dm.is_deleted = 0
              ORDER BY dm.created_ymdhis ASC 
              LIMIT 50 OFFSET :offset";
    
    return $db->fetchAll($sql, [
        'channel_id' => $channelId,
        'offset' => $offset
    ]);
}

function sendMessageToChannel($channelId, $actorId, $message) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    // Generate timestamp-based dialog_message_id
    $current_ymdhis = gmdate('YmdHis');
    $max_id_result = $db->fetch("SELECT MAX(dialog_message_id) as max_id FROM {$table_prefix}dialog_messages");
    $max_id = $max_id_result['max_id'] ?? 0;
    
    $dialog_message_id = ($max_id < $current_ymdhis) ? $current_ymdhis : $max_id + 1;
    
    $sql = "INSERT INTO {$table_prefix}dialog_messages 
              (dialog_message_id, dialog_thread_id, channel_id, from_actor_id, to_actor_id, 
               message_text, message_type, created_ymdhis, updated_ymdhis, is_deleted)
              VALUES 
              (:dialog_message_id, :dialog_thread_id, :channel_id, :from_actor_id, :to_actor_id,
               :message_text, :message_type, :created_ymdhis, :updated_ymdhis, 0)";
    
    return $db->insert($sql, [
        'dialog_message_id' => $dialog_message_id,
        'dialog_thread_id' => 1, // Default thread for channel
        'channel_id' => $channelId,
        'from_actor_id' => $actorId,
        'to_actor_id' => 0, // Broadcast to channel
        'message_text' => substr($message, 0, 1000), // Limit to message_text size
        'message_type' => 'text',
        'created_ymdhis' => $current_ymdhis,
        'updated_ymdhis' => $current_ymdhis
    ]);
}
```

### 3.2 Session Management Integration
**Integration Points:**
- Connect with `lupo_sessions` table for proper session tracking
- Use actor-based authentication instead of session IDs
- Implement proper session lifecycle management

**Implementation:**
```php
function getCurrentActorId($session_id) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    $sql = "SELECT actor_id FROM {$table_prefix}sessions 
              WHERE session_id = :session_id AND is_deleted = 0";
    
    $result = $db->fetch($sql, ['session_id' => $session_id]);
    return $result ? $result['actor_id'] : 0;
}

function updateSessionActivity($session_id, $actor_id) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    $current_ymdhis = gmdate('YmdHis');
    
    $sql = "UPDATE {$table_prefix}sessions 
              SET last_seen_ymdhis = :last_seen, updated_ymdhis = :updated
              WHERE session_id = :session_id AND actor_id = :actor_id";
    
    return $db->update($sql, [
        'last_seen' => $current_ymdhis,
        'updated' => $current_ymdhis,
        'session_id' => $session_id,
        'actor_id' => $actor_id
    ]);
}
```

## Phase 4: Security Implementation

### 4.1 Content Security Policy
**Implementation**: Add CSP headers to all iframe sources

```php
function setSecurityHeaders() {
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; frame-src 'self'; connect-src 'self' ws: wss:");
    header("X-Frame-Options: SAMEORIGIN");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: strict-origin-when-cross-origin");
}
```

### 4.2 CSRF Protection
**Implementation**: Token-based request validation

```php
function generateCSRFToken($actor_id, $session_id) {
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

function validateCSRFToken($token) {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}
```

### 4.3 Input Sanitization
**Implementation**: Comprehensive input validation

```php
function sanitizeInput($input, $type = 'string') {
    switch ($type) {
        case 'int':
            return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        case 'email':
            return filter_var($input, FILTER_SANITIZE_EMAIL);
        case 'url':
            return filter_var($input, FILTER_SANITIZE_URL);
        default:
            return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
```

## Phase 5: Performance Optimizations

### 5.1 Lazy Loading Implementation
**Implementation**: Load iframes only when needed

```javascript
class IframeLazyLoader {
    constructor() {
        this.loadedIframes = new Set();
        this.intersectionObserver = new IntersectionObserver(this.handleIntersection.bind(this));
    }

    observeIframe(iframeElement) {
        this.intersectionObserver.observe(iframeElement);
    }

    handleIntersection(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting && !this.loadedIframes.has(entry.target)) {
                this.loadIframe(entry.target);
                this.loadedIframes.add(entry.target);
            }
        });
    }

    loadIframe(iframeElement) {
        const src = iframeElement.dataset.src;
        if (src) {
            iframeElement.src = src;
            iframeElement.dataset.src = ''; // Prevent reload
        }
    }
}
```

### 5.2 Connection Pooling
**Implementation**: Reuse HTTP connections efficiently

```php
class ConnectionPool {
    private $connections = [];
    private $maxConnections = 10;

    function getConnection($host, $port = 80) {
        $key = "$host:$port";
        
        if (isset($this->connections[$key]) && count($this->connections[$key]) < $this->maxConnections) {
            return array_pop($this->connections[$key]);
        }
        
        $connection = $this->createNewConnection($host, $port);
        $this->connections[$key][] = $connection;
        return $connection;
    }

    function returnConnection($connection) {
        // Parse connection key and return to pool
        // Implementation details...
    }
}
```

## Implementation Timeline

### Week 1: Foundation
- **Day 1-2**: Create channel interface template
- **Day 3-4**: Implement iframe integration points
- **Day 5-7**: Add responsive design and CSS

### Week 2: Communication
- **Day 8-10**: Modernize xmlhttp.js with Fetch API
- **Day 11-12**: Implement WebSocket support
- **Day 13-14**: Add cross-iframe communication

### Week 3: Backend Integration
- **Day 15-17**: Update admin_chat_xmlhttp.php
- **Day 18-19**: Integrate session management
- **Day 20-21**: Add security headers and validation

### Week 4: Security & Performance
- **Day 22-24**: Implement CSP and CSRF protection
- **Day 25-26**: Add lazy loading and performance optimizations
- **Day 27-28**: Testing and debugging

## Testing Strategy

### Unit Testing
- Test iframe communication protocols
- Validate message routing between components
- Test session management integration
- Verify security token generation/validation

### Integration Testing
- Test with existing Crafty Syntax backend
- Validate compatibility with current database
- Test real-time communication under load
- Verify mobile responsiveness

### Performance Testing
- Measure iframe load times
- Test WebSocket connection stability
- Validate memory usage under load
- Test fallback mechanisms

## Success Criteria

1. **Functional Parity**: All legacy features work in modern interface
2. **Performance Improvement**: Faster load times and reduced resource usage
3. **Security Enhancement**: Modern security standards implemented
4. **Mobile Compatibility**: Responsive design works on all devices
5. **Integration Success**: Seamless integration with Lupopedia systems

This implementation plan provides a comprehensive roadmap for modernizing the livehelp interface while maintaining compatibility with existing systems and following modern web development best practices.
