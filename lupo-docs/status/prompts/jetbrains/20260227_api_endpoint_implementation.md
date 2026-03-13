# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\prompts\jetbrains\20260227_api_endpoint_implementation.md"
  file_hash: "e442adcdc9d08d3db46dfa248826029220e2035f0083274f454f036676ae3e24"
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
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE

---
lupopedia.headers:
  file_path_from_root: "prompts/jetbrains/20260227_api_endpoint_implementation.md"
  file_hash: "ebcd664f6da335da2626c9c927401b4381fa988e4cde867a78fe382084f5dccb"
  system_version: "4.0.50"
  channel_id: 42
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "implementation_prompt"
  purpose: "JetBrains IDE task implementation for creating API endpoints to wire channels_comm.js and enable CRUD operations for admin interface"
  dialog_message: "JetBrains: Implement concrete API endpoints for channels admin interface integration, focusing on operators, departments, chat monitoring, and settings management with proper authentication and RESTful design."
  mood_rgb: "008B8B"
  artifact_kind: "api_implementation"
  traits: ["api", "endpoints", "admin_interface", "crud", "authentication"]
  tags: ["api", "endpoints", "channels", "admin_interface", "jetbrains", "4.0.49"]
  lupo_agent: "jetbrains"

lupopedia.edges:
  file_path_from_root: "prompts\jetbrains\20260227_api_endpoint_implementation.md"
  outbound_edges:
    - { to: "channels/42/threads/DEVELOPMENT_CYCLE_4_0_49/20260227131500_10000_windsurf_api_endpoint_research_prompt.md", type: "implements", weight: 1.0, reason: "Research findings source" }
    - { to: "channels/1/assets/js/channels_comm.js", type: "updates", weight: 0.9, reason: "Wire to concrete endpoints" }
    - { to: "channels/1/admin/", type: "enables", weight: 0.8, reason: "Enable CRUD operations" }
    - { to: "api/channels/admin/", type: "creates", weight: 0.8, reason: "New admin API endpoints" }
    - { to: "docs/api/endpoints/", type: "updates", weight: 0.7, reason: "API documentation" }
  semantic_tags: ["api_implementation", "admin_interface", "channels", "jetbrains"]

  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260227"
  last_verified_by: "lupopedia"
---

# JetBrains IDE Task: Channels Admin API Implementation

**Task ID**: API-CHANNELS-ADMIN-2026-02-27-001  
**Assigned To**: JetBrains (1007)  
**Priority**: High  
**Estimated Time**: 4-6 hours  
**Target Version**: 4.0.49

---

## 🎯 **Mission Objective**

Implement concrete API endpoints to wire `channels_comm.js` into functional backend modules, enabling CRUD operations for the new channels admin interface. Focus on operators, departments, chat monitoring, and settings management with proper authentication and RESTful design patterns.

---

## 🔍 **Research Findings Summary**

Based on analysis of the completed admin interface and existing codebase:

### **Current State**
- ✅ **Admin Shell**: `channels/1/index.php` with modern iframe layout
- ✅ **Admin Pages**: Dashboard, operators, departments, chat_monitor, settings
- ✅ **Frontend JS**: `channels_comm.js` with placeholder communication methods
- ⚠️ **Backend Gap**: No concrete API endpoints implemented yet

### **Existing API Resources**
- `docs/api/endpoints/` - Documentation for existing endpoints
- `modules/` - Existing backend modules
- `api/` - REST API structure
- Database tables: `lupo_actors`, `lupo_departments`, `lupo_dialog_threads`, etc.

---

## 🏗️ **Recommended API Architecture**

### **Base Structure**
```
api/channels/admin/
├── operators.php              # Operator CRUD operations
├── departments.php            # Department CRUD operations  
├── chat_monitor.php           # Chat monitoring endpoints
├── settings.php              # System settings management
└── index.php                 # API documentation/discovery
```

### **Authentication Integration**
- Use existing `lupo_api_tokens` for API authentication
- Validate sessions via `lupo_sessions` table
- Actor-based permissions using `lupo_actors` table

---

## 🔧 **Implementation Plan**

### **Phase 1: Operators API (1.5 hours)**
1. **Create `api/channels/admin/operators.php`**
   ```php
   <?php
   require_once LUPOPEDIA_PATH . '/app/Auth/Session.php';
   require_once LUPOPEDIA_PATH . '/app/Services/ActorService.php';
   
   $session = new \App\Auth\Session();
   if (!$session->isValidApiRequest()) {
       http_response_code(401);
       echo json_encode(['error' => 'Unauthorized']);
       exit;
   }
   
   $method = $_SERVER['REQUEST_METHOD'];
   $action = $_GET['action'] ?? '';
   
   switch ($method) {
       case 'GET':
           if ($action === 'list') {
               $operators = getActiveOperators();
               echo json_encode(['success' => true, 'data' => $operators]);
           } elseif ($action === 'get' && isset($_GET['id'])) {
               $operator = getOperatorById($_GET['id']);
               echo json_encode(['success' => true, 'data' => $operator]);
           }
           break;
           
       case 'POST':
           $data = json_decode(file_get_contents('php://input'), true);
           if ($action === 'create') {
               $result = createOperator($data);
               echo json_encode(['success' => $result, 'data' => $result]);
           } elseif ($action === 'update' && isset($_GET['id'])) {
               $result = updateOperator($_GET['id'], $data);
               echo json_encode(['success' => $result, 'data' => $result]);
           }
           break;
           
       case 'DELETE':
           if (isset($_GET['id'])) {
               $result = deleteOperator($_GET['id']);
               echo json_encode(['success' => $result]);
           }
           break;
   }
   
   function getActiveOperators() {
       $db = DatabaseFactory::getConnection();
       $sql = "SELECT a.actor_id, a.name, a.email, a.is_active, a.department_id, d.name as department_name 
                FROM lupo_actors a 
                LEFT JOIN lupo_departments d ON a.department_id = d.department_id 
                WHERE a.can_login = 1 AND a.is_deleted = 0 
                ORDER BY a.name";
       return $db->fetchAll($sql);
   }
   
   function createOperator($data) {
       $db = DatabaseFactory::getConnection();
       $sql = "INSERT INTO lupo_actors (actor_id, name, email, can_login, is_active, created_ymdhis) 
                VALUES (?, ?, ?, ?, ?, ?)";
       $params = [
           generateActorId(),
           $data['name'],
           $data['email'] ?? '',
           1,
           gmdate('YmdHis')
       ];
       return $db->insert($sql, $params);
   }
   ```

### **Phase 2: Departments API (1 hour)**
1. **Create `api/channels/admin/departments.php`**
   ```php
   // Similar structure for department CRUD
   // Use lupo_departments table
   // Include department validation and federation support
   ```

### **Phase 3: Chat Monitor API (1.5 hours)**
1. **Create `api/channels/admin/chat_monitor.php`**
   ```php
   // Real-time chat monitoring endpoints
   // Use lupo_dialog_threads and lupo_dialog_messages
   // Support WebSocket connection status
   ```

### **Phase 4: Settings API (1 hour)**
1. **Create `api/channels/admin/settings.php`**
   ```php
   // Channel configuration management
   // Use lupo_collections for channel settings
   // Support federation node configuration
   ```

---

## 🔌 **Update channels_comm.js**

### **Wire to New Endpoints**
```javascript
// channels/1/assets/js/channels_comm.js - Updated version
class ChannelsCommunication {
    constructor() {
        this.baseUrl = '/api/channels/admin/';
        this.setupMessageListener();
    }
    
    async fetchOperators(action = 'list', data = null) {
        try {
            const response = await fetch(this.baseUrl + 'operators.php?action=' + action, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-API-Token': this.getApiToken()
                }
            });
            return await response.json();
        } catch (error) {
            console.error('Failed to fetch operators:', error);
            return { success: false, error: error.message };
        }
    }
    
    async createOperator(operatorData) {
        try {
            const response = await fetch(this.baseUrl + 'operators.php?action=create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-API-Token': this.getApiToken()
                },
                body: JSON.stringify(operatorData)
            });
            return await response.json();
        } catch (error) {
            console.error('Failed to create operator:', error);
            return { success: false, error: error.message };
        }
    }
    
    // Similar methods for update, delete, departments, chat_monitor, settings
}
```

---

## 🔐 **Security Implementation**

### **Authentication Layer**
```php
// Add to each API endpoint
function validateApiRequest() {
    $headers = getallheaders();
    $token = $headers['X-API-Token'] ?? '';
    
    if (empty($token)) {
        return false;
    }
    
    $db = DatabaseFactory::getConnection();
    $sql = "SELECT actor_id FROM lupo_api_tokens 
             WHERE token_key = ? AND is_active = 1 AND is_deleted = 0";
    $result = $db->fetchOne($sql, [$token]);
    
    return $result !== false;
}
```

### **Input Validation**
```php
function sanitizeInput($data, $type = 'string') {
    switch ($type) {
        case 'int':
            return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
        case 'email':
            return filter_var($data, FILTER_SANITIZE_EMAIL);
        case 'string':
        default:
            return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}
```

---

## 📊 **Database Integration**

### **Key Tables to Use**
- `lupo_actors` - Operator accounts
- `lupo_departments` - Department management
- `lupo_dialog_threads` - Chat sessions
- `lupo_dialog_messages` - Chat messages
- `lupo_api_tokens` - API authentication
- `lupo_sessions` - Session management

### **Sample Queries**
```php
function getDepartmentOperators($departmentId) {
    $db = DatabaseFactory::getConnection();
    $sql = "SELECT a.actor_id, a.name, a.is_active 
             FROM lupo_actors a 
             WHERE a.department_id = ? AND a.can_login = 1 AND a.is_deleted = 0 
             ORDER BY a.name";
    return $db->fetchAll($sql, [$departmentId]);
}

function getActiveChatSessions($limit = 50) {
    $db = DatabaseFactory::getConnection();
    $sql = "SELECT dt.dialog_thread_id, dt.actor_id, a.name as operator_name, dt.created_ymdhis 
             FROM lupo_dialog_threads dt 
             JOIN lupo_actors a ON dt.actor_id = a.actor_id 
             WHERE dt.is_deleted = 0 
             ORDER BY dt.created_ymdhis DESC 
             LIMIT ?";
    return $db->fetchAll($sql, [$limit]);
}
```

---

## 📝 **API Documentation**

### **Create `docs/api/endpoints/channels_admin.md`**
```markdown
# Channels Admin API Endpoints

## Overview
RESTful API endpoints for the Channels Admin Interface, providing CRUD operations for operators, departments, chat monitoring, and settings.

## Authentication
All endpoints require valid API token via `X-API-Token` header.

## Endpoints

### Operators
- `GET /api/channels/admin/operators.php?action=list` - List all operators
- `GET /api/channels/admin/operators.php?action=get&id={id}` - Get specific operator
- `POST /api/channels/admin/operators.php?action=create` - Create new operator
- `POST /api/channels/admin/operators.php?action=update&id={id}` - Update operator
- `DELETE /api/channels/admin/operators.php?id={id}` - Delete operator

### Departments
- `GET /api/channels/admin/departments.php?action=list` - List departments
- `POST /api/channels/admin/departments.php?action=create` - Create department
- `POST /api/channels/admin/departments.php?action=update&id={id}` - Update department
- `DELETE /api/channels/admin/departments.php?id={id}` - Delete department

### Chat Monitor
- `GET /api/channels/admin/chat_monitor.php?action=active` - Active chat sessions
- `GET /api/channels/admin/chat_monitor.php?action=history&limit={n}` - Chat history
- `GET /api/channels/admin/chat_monitor.php?action=stats` - Chat statistics

### Settings
- `GET /api/channels/admin/settings.php?action=get` - Get channel settings
- `POST /api/channels/admin/settings.php?action=update` - Update settings
```

---

## 🚀 **Success Criteria**

1. ✅ **Operators API**: Full CRUD operations working
2. ✅ **Departments API**: Department management functional
3. ✅ **Chat Monitor API**: Real-time monitoring enabled
4. ✅ **Settings API**: Configuration management working
5. ✅ **Authentication**: Secure API token validation
6. ✅ **Frontend Integration**: channels_comm.js fully wired
7. ✅ **Documentation**: Complete API documentation
8. ✅ **Testing**: All endpoints tested and working
9. ✅ **Security**: Input validation and authentication implemented
10. ✅ **Performance**: Efficient queries with proper indexing

---

## 🔄 **Testing Checklist**

### **API Testing**
- [ ] Operators list/create/update/delete
- [ ] Departments list/create/update/delete
- [ ] Chat monitor active/history/stats
- [ ] Settings get/update
- [ ] Authentication with invalid/valid tokens
- [ ] Input validation and sanitization

### **Integration Testing**
- [ ] Frontend-backend communication
- [ ] Real-time updates in admin interface
- [ ] Error handling and user feedback
- [ ] Mobile responsiveness with API data

### **Security Testing**
- [ ] SQL injection prevention
- [ ] XSS protection
- [ ] CSRF token validation
- [ ] Rate limiting
- [ ] Authorization checks

---

**⚡ Implementation Priority**: Start with Operators API as it's the most critical, then proceed with departments and chat monitoring. Ensure all endpoints follow RESTful conventions and include proper error handling.

**🎯 Key Success Factor**: The admin interface should transition from "static data snapshots" to "fully functional dynamic interface" with real-time data updates and complete CRUD operations.
