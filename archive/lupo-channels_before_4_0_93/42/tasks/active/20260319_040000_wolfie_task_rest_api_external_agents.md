---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  file_path_from_root: "lupo-channels/42/tasks/active/20260319_040000_wolfie_task_rest_api_external_agents.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/tasks/active/20260319_040000_wolfie_task_rest_api_external_agents.md"
  questions_toon: null
  system_version: "4.0.82"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "task"
  artifact_kind: "rest_api_task"
  purpose: "Implement REST API for External Agents - safe task/message creation"
  traits: ["rest_api", "external_agents", "semantic_os", "wolfie_task"]
  tags: ["api", "rest", "external_agents", "security", "semantic_os"]
  lupo_agent: "wolfie"

lupopedia.edges:
  outbound_edges:
    - { to: "api/index.php", type: "creates", weight: 1.0, reason: "Creates REST API entry point" }
    - { to: "api/v1/", type: "creates", weight: 1.0, reason: "Creates API v1 endpoints" }
    - { to: "lupo-database/lupopedia/tables/lupo_api_tokens.toon.json", type: "references", weight: 1.0, reason: "References API tokens table" }
  semantic_tags: ["rest_api", "external_agents", "semantic_os"]

lupopedia.see:
  mappings:
    - ["api/index.php", "http://www.lupopedia.com/api/index.php"]

lupopedia.footer:
  version: "4.0.82"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Create REST API entry point"
    - "Implement authentication middleware"
    - "Add rate limiting and security"
    - "Create API documentation"
---

# 🜌 **TASK 3 — Implement REST API for External Agents**

## **Task Overview**
**Task ID**: 20260319_040000  
**Created by**: WOLFIE (Agent 1)  
**Channel**: 42 (Development Channel)  
**Priority**: MEDIUM  
**Status**: ACTIVE

## **Purpose**
Implement a secure REST API that allows external AI agents to create tasks, messages, threads, and artifacts safely. External agents cannot write files directly on shared hosting, so they need a secure API endpoint to interact with the semantic OS.

## **Problem Statement**

### **Current State**
- External agents have no way to interact with Lupopedia
- No secure entry points for external integrations
- File system is not accessible to external services
- No authentication or authorization mechanisms
- Missing API for programmatic access

### **Required State**
- Secure REST API for external agent interactions
- Authentication and authorization system
- Rate limiting and security protections
- Complete API documentation
- Audit trail for all external interactions

## **API Requirements**

### **Core Endpoints**

#### **1. Thread Management**
```
POST   /api/v1/threads              # Create new thread
GET    /api/v1/threads              # List threads
GET    /api/v1/threads/{id}         # Get thread details
PUT    /api/v1/threads/{id}         # Update thread
DELETE /api/v1/threads/{id}         # Delete thread
```

#### **2. Message Management**
```
POST   /api/v1/threads/{id}/messages    # Add message to thread
GET    /api/v1/threads/{id}/messages    # List thread messages
GET    /api/v1/messages/{id}             # Get message details
PUT    /api/v1/messages/{id}             # Update message
DELETE /api/v1/messages/{id}             # Delete message
```

#### **3. Task Management**
```
POST   /api/v1/tasks                   # Create new task
GET    /api/v1/tasks                   # List tasks
GET    /api/v1/tasks/{id}              # Get task details
PUT    /api/v1/tasks/{id}              # Update task
DELETE /api/v1/tasks/{id}              # Delete task
POST   /api/v1/tasks/{id}/complete     # Mark task complete
```

#### **4. Artifact Management**
```
POST   /api/v1/artifacts               # Create artifact
GET    /api/v1/artifacts               # List artifacts
GET    /api/v1/artifacts/{id}          # Get artifact details
PUT    /api/v1/artifacts/{id}          # Update artifact
DELETE /api/v1/artifacts/{id}          # Delete artifact
```

#### **5. Channel Management**
```
GET    /api/v1/channels                # List channels
GET    /api/v1/channels/{id}           # Get channel details
GET    /api/v1/channels/{id}/threads   # List channel threads
GET    /api/v1/channels/{id}/tasks     # List channel tasks
```

### **Authentication**
- JWT-based authentication
- API key support for simple integrations
- OAuth2 flow for complex applications
- Token expiration and refresh
- Permission-based access control

### **Security Features**
- Rate limiting per agent
- Request size limits
- Input validation and sanitization
- SQL injection protection
- XSS protection
- CORS configuration
- HTTPS enforcement

## **Implementation Plan**

### **Phase 1: Core API Infrastructure**
1. **API Entry Point**
   - Create `api/index.php` as main router
   - Implement request routing
   - Add error handling
   - Set up response formatting

2. **Authentication System**
   - JWT token generation/validation
   - API key management
   - Permission checking
   - Session management

3. **Database Layer**
   - API tokens table
   - Request logging table
   - Rate limiting table
   - Audit trail table

### **Phase 2: Endpoint Implementation**
1. **Thread Endpoints**
   - Thread CRUD operations
   - Message posting
   - Thread metadata management
   - Permission validation

2. **Task Endpoints**
   - Task CRUD operations
   - Status management
   - Assignment handling
   - Dependency tracking

3. **Artifact Endpoints**
   - Artifact CRUD operations
   - File uploads
   - Metadata management
   - Version tracking

### **Phase 3: Security and Monitoring**
1. **Security Middleware**
   - Authentication middleware
   - Authorization middleware
   - Rate limiting middleware
   - Input validation middleware

2. **Monitoring and Logging**
   - Request logging
   - Performance monitoring
   - Error tracking
   - Security event logging

## **Technical Specifications**

### **API Directory Structure**
```
api/
+-- index.php                 # Main API router
+-- v1/                       # API version 1
|   +-- threads.php           # Thread endpoints
|   +-- messages.php          # Message endpoints
|   +-- tasks.php             # Task endpoints
|   +-- artifacts.php         # Artifact endpoints
|   +-- channels.php          # Channel endpoints
|   +-- auth.php              # Authentication endpoints
+-- middleware/               # Middleware components
|   +-- auth.php              # Authentication middleware
|   +-- rate_limit.php        # Rate limiting
|   +-- validation.php        # Input validation
|   +-- security.php          # Security checks
+-- lib/                      # API libraries
|   +-- Router.php            # Request router
|   +-- Response.php          # Response formatter
|   +-- Database.php          # Database wrapper
|   +-- Validator.php         # Input validator
+-- docs/                     # API documentation
    +-- openapi.yaml          # OpenAPI specification
    +-- README.md             # API usage guide
```

### **Database Schema**
```sql
-- API tokens for external agents
CREATE TABLE lupo_api_tokens (
    token_id INT PRIMARY KEY AUTO_INCREMENT,
    agent_id INT NOT NULL,
    token_name VARCHAR(100) NOT NULL,
    token_hash VARCHAR(255) NOT NULL UNIQUE,
    permissions JSON NOT NULL,
    rate_limit INT DEFAULT 1000,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_used_at TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (agent_id) REFERENCES lupo_actors(actor_id)
);

-- API request logging
CREATE TABLE lupo_api_requests (
    request_id INT PRIMARY KEY AUTO_INCREMENT,
    token_id INT,
    endpoint VARCHAR(255) NOT NULL,
    method VARCHAR(10) NOT NULL,
    request_ip VARCHAR(45) NOT NULL,
    user_agent TEXT,
    request_size INT DEFAULT 0,
    response_status INT NOT NULL,
    response_size INT DEFAULT 0,
    processing_time INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (token_id) REFERENCES lupo_api_tokens(token_id)
);

-- Rate limiting
CREATE TABLE lupo_api_rate_limits (
    limit_id INT PRIMARY KEY AUTO_INCREMENT,
    token_id INT NOT NULL,
    window_start TIMESTAMP NOT NULL,
    request_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (token_id) REFERENCES lupo_api_tokens(token_id),
    UNIQUE KEY (token_id, window_start)
);
```

### **Request/Response Format**
```json
// Request format
{
    "method": "POST",
    "endpoint": "/api/v1/threads",
    "headers": {
        "Authorization": "Bearer <jwt_token>",
        "Content-Type": "application/json"
    },
    "body": {
        "channel_id": 42,
        "title": "Thread Title",
        "description": "Thread description",
        "participants": [1, 2, 3]
    }
}

// Response format
{
    "success": true,
    "data": {
        "thread_id": 1234,
        "channel_id": 42,
        "title": "Thread Title",
        "description": "Thread description",
        "created_at": "2026-03-19T15:30:00Z",
        "participants": [1, 2, 3]
    },
    "meta": {
        "version": "v1",
        "timestamp": "2026-03-19T15:30:00Z",
        "request_id": "req_123456"
    }
}

// Error format
{
    "success": false,
    "error": {
        "code": "INVALID_REQUEST",
        "message": "Invalid request parameters",
        "details": {
            "field": "channel_id",
            "reason": "Channel not found"
        }
    },
    "meta": {
        "version": "v1",
        "timestamp": "2026-03-19T15:30:00Z",
        "request_id": "req_123456"
    }
}
```

### **Authentication Flow**
```php
// JWT Token Structure
{
    "iss": "lupopedia.com",
    "aud": "lupopedia-api",
    "iat": 1647694200,
    "exp": 1647780600,
    "sub": "agent_id",
    "permissions": ["threads:read", "threads:write", "tasks:write"],
    "rate_limit": 1000
}

// Authentication middleware
function authenticateRequest($request) {
    $token = extractTokenFromRequest($request);
    $payload = validateJWTToken($token);
    $agent = validateAgent($payload->sub);
    $permissions = validatePermissions($payload->permissions);
    return [
        'agent' => $agent,
        'permissions' => $permissions,
        'rate_limit' => $payload->rate_limit
    ];
}
```

### **Rate Limiting Implementation**
```php
class RateLimiter {
    public function checkRateLimit($token_id, $limit) {
        $window = date('Y-m-d H:00:00'); // 1-hour window
        $current = $this->getCurrentCount($token_id, $window);
        
        if ($current >= $limit) {
            throw new RateLimitExceededException();
        }
        
        $this->incrementCount($token_id, $window);
        return true;
    }
}
```

## **API Documentation**

### **OpenAPI Specification**
```yaml
openapi: 3.0.0
info:
  title: Lupopedia API
  version: 1.0.0
  description: REST API for external agent integration

security:
  - bearerAuth: []

paths:
  /api/v1/threads:
    post:
      summary: Create new thread
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                channel_id:
                  type: integer
                title:
                  type: string
                description:
                  type: string
      responses:
        201:
          description: Thread created successfully
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/Thread'
```

## **Success Criteria**

- [ ] All core endpoints implemented and functional
- [ ] Authentication system working correctly
- [ ] Rate limiting prevents abuse
- [ ] Input validation prevents attacks
- [ ] API documentation is complete and accurate
- [ ] Performance meets requirements (<200ms response time)
- [ ] Security audit passes

## **Testing Strategy**

### **Unit Tests**
- Endpoint functionality tests
- Authentication tests
- Validation tests
- Rate limiting tests

### **Integration Tests**
- End-to-end API workflows
- Authentication flows
- Error handling
- Performance tests

### **Security Tests**
- Penetration testing
- Input validation tests
- Authentication bypass tests
- Rate limiting bypass tests

## **Dependencies**

- PHP 5.6+ compatibility
- Database access
- JWT library
- Web server with URL rewriting
- SSL certificate for HTTPS

## **Next Actions**

1. Create API entry point and routing
2. Implement authentication system
3. Add core endpoints (threads, tasks, artifacts)
4. Implement security middleware
5. Create comprehensive API documentation
6. Set up monitoring and logging

---

**Task Status**: ACTIVE  
**Assigned to**: WOLFIE (Agent 1)  
**Due Date**: 2026-03-19  
**Dependencies**: OAuth2/JWT Authentication (Task 4)  
**Blockers**: None
