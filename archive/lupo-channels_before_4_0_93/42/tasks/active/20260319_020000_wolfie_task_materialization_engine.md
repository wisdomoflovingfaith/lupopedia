---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  file_path_from_root: "lupo-channels/42/tasks/active/20260319_020000_wolfie_task_materialization_engine.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/tasks/active/20260319_020000_wolfie_task_materialization_engine.md"
  questions_toon: null
  system_version: "4.0.82"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "task"
  artifact_kind: "task_materialization_task"
  purpose: "Implement Task Materialization Engine - ensure every DB task produces a file"
  traits: ["task_materialization", "semantic_os", "db_file_sync", "wolfie_task"]
  tags: ["tasks", "materialization", "database", "filesystem", "semantic_os"]
  lupo_agent: "wolfie"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-scripts/TaskMaterializationEngine.php", type: "creates", weight: 1.0, reason: "Creates the materialization engine" }
    - { to: "lupo-database/lupopedia/tables/lupo_tasks.toon.json", type: "references", weight: 1.0, reason: "References task table structure" }
    - { to: "lupo-channels/42/tasks/", type: "manages", weight: 1.0, reason: "Manages task file creation" }
  semantic_tags: ["task_materialization", "semantic_os", "db_file_sync"]

lupopedia.see:
  mappings:
    - ["TaskMaterializationEngine.php", "http://www.lupopedia.com/lupo-scripts/TaskMaterializationEngine.php"]

lupopedia.footer:
  version: "4.0.82"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Create TaskMaterializationEngine.php script"
    - "Implement database triggers for automatic materialization"
    - "Add task file validation and sync processes"
---

# 🜋 **TASK 2 — Implement Task Materialization Engine**

## **Task Overview**
**Task ID**: 20260319_260000  
**Created by**: WOLFIE (Agent 1)  
**Channel**: 42 (Development Channel)  
**Priority**: HIGH  
**Status**: ACTIVE

## **Purpose**
Ensure every database task produces a corresponding file in the semantic OS. Currently, tasks exist only in the database, breaking the semantic OS principle that every task must have a file representation with proper headers and metadata.

## **Problem Statement**

### **Current State**
- Tasks are created in database tables (`lupo_tasks`, `lupo_dialog_threads`)
- No automatic file materialization occurs
- Semantic OS principle violated: "Every task → has a file"
- Manual file creation is error-prone and inconsistent

### **Required State**
- Every DB task automatically generates a file
- Files contain proper LUPOPEDIA HEADERS
- Files include metadata and edges
- Database and filesystem stay synchronized
- Changes propagate bidirectionally

## **Requirements**

### **Core Functionality**
1. **Automatic Materialization**
   - Trigger on task creation in database
   - Generate file with proper structure
   - Include all required headers and metadata
   - Place file in correct channel directory

2. **File Structure**
   ```
   lupo-channels/<channel_id>/tasks/active/<task_id>.md
   ```

3. **File Content Template**
   ```yaml
   ---
   lupopedia.headers:
     lupopedia.version: "4.0.82"
     file_path_from_root: "lupo-channels/<channel_id>/tasks/active/<task_id>.md"
     web_path: "http://www.lupopedia.com/lupo-channels/<channel_id>/tasks/active/<task_id>.md"
     last_modified_utc: "YYYYMMDD"
     system_version: "4.0.82"
     channel_id: <channel_id>
     actor_id: <actor_id>
     actor_name: "<actor_name>"
     artifact_type: "task"
     artifact_kind: "<task_kind>"
     purpose: "<task_purpose>"
     traits: ["<task_traits>"]
     tags: ["<task_tags>"]
     lupo_agent: "<agent_name>"

   lupopedia.edges:
     outbound_edges:
       - { to: "<related_file>", type: "<relationship>", weight: 1.0, reason: "<reason>" }
     semantic_tags: ["<semantic_tags>"]

   lupopedia.see:
     mappings:
       - ["<task_file>", "<web_path>"]

   lupopedia.footer:
     version: "4.0.82"
     last_verified: "YYYYMMDD"
     last_verified_by: "<agent_name>"
     orchestrator: "<agent_name>"
     next_action:
       - "<next_actions>"
   ---
   ```

### **Bidirectional Synchronization**
- DB → File: Materialize on create/update
- File → DB: Parse changes and update DB
- Conflict resolution: Last write wins with audit trail

## **Implementation Plan**

### **Phase 1: Core Engine**
1. **Create TaskMaterializationEngine.php**
   - Database connection and query methods
   - File generation with templates
   - Header validation and insertion
   - Metadata and edges generation

2. **Database Triggers**
   - `AFTER INSERT` on `lupo_tasks` → materialize file
   - `AFTER UPDATE` on `lupo_tasks` → update file
   - `AFTER DELETE` on `lupo_tasks` → archive file

3. **File Management**
   - Directory creation and validation
   - File naming conventions
   - Atomic file operations
   - Backup and versioning

### **Phase 2: Synchronization**
1. **Bidirectional Sync**
   - File watcher for changes
   - Database update procedures
   - Conflict detection and resolution
   - Audit trail maintenance

2. **Validation Engine**
   - Header validation
   - File structure validation
   - Database consistency checks
   - Error reporting and recovery

### **Phase 3: Integration**
1. **Channel Integration**
   - Channel-specific task routing
   - Channel permission validation
   - Channel statistics updates

2. **Agent Integration**
   - Agent task creation hooks
   - Agent permission validation
   - Agent activity logging

## **Technical Specifications**

### **Database Schema Requirements**
```sql
-- Tasks table (existing)
CREATE TABLE lupo_tasks (
    task_id INT PRIMARY KEY AUTO_INCREMENT,
    channel_id INT NOT NULL,
    actor_id INT NOT NULL,
    task_title VARCHAR(255) NOT NULL,
    task_description TEXT,
    task_status ENUM('pending', 'active', 'completed', 'archived') DEFAULT 'pending',
    task_priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    task_kind VARCHAR(100),
    task_purpose TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    file_path VARCHAR(500),
    materialized BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (channel_id) REFERENCES lupo_channels(channel_id),
    FOREIGN KEY (actor_id) REFERENCES lupo_actors(actor_id)
);

-- Materialization log
CREATE TABLE lupo_task_materialization_log (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    task_id INT NOT NULL,
    action ENUM('create', 'update', 'delete', 'sync') NOT NULL,
    file_path VARCHAR(500),
    status ENUM('success', 'error') NOT NULL,
    error_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES lupo_tasks(task_id)
);
```

### **PHP Class Structure**
```php
<?php
class TaskMaterializationEngine {
    private $db;
    private $template_engine;
    private $file_manager;
    
    public function __construct($db_connection) {
        $this->db = $db_connection;
        $this->template_engine = new TaskTemplateEngine();
        $this->file_manager = new TaskFileManager();
    }
    
    public function materializeTask($task_id) {
        // Fetch task from database
        // Generate file content
        // Write file to filesystem
        // Update database with file path
        // Log materialization
    }
    
    public function updateTaskFile($task_id) {
        // Handle task updates
    }
    
    public function archiveTaskFile($task_id) {
        // Handle task deletion/archival
    }
    
    public function syncFromFile($file_path) {
        // Parse file and update database
    }
    
    public function validateTaskFile($file_path) {
        // Validate file structure and headers
    }
}
```

### **File Naming Convention**
```
Format: YYYYMMDD_HHIISS_<actor>_task_<purpose>.md
Example: 20260319_143000_wolfie_task_channel_index_creation.md
```

## **Success Criteria**

- [ ] Every DB task automatically generates a file
- [ ] Files contain proper LUPOPEDIA HEADERS
- [ ] Files include metadata and edges
- [ ] Database and filesystem stay synchronized
- [ ] Materialization is logged and auditable
- [ ] Error handling and recovery procedures work
- [ ] Performance impact is minimal (<100ms per task)

## **Testing Strategy**

### **Unit Tests**
- Task materialization engine tests
- Template generation tests
- File management tests
- Database synchronization tests

### **Integration Tests**
- End-to-end task creation flow
- Bidirectional synchronization tests
- Error handling and recovery tests
- Performance benchmarking

### **Manual Tests**
- Create tasks via different agents
- Update tasks and verify file updates
- Delete tasks and verify file archival
- Conflict resolution scenarios

## **Risks and Mitigations**

### **Performance Impact**
- **Risk**: Database triggers slow down task creation
- **Mitigation**: Asynchronous processing, batch operations

### **File System Issues**
- **Risk**: File permissions, disk space, concurrent access
- **Mitigation**: Error handling, retry mechanisms, monitoring

### **Data Consistency**
- **Risk**: Database and filesystem diverge
- **Mitigation**: Validation checks, audit trails, recovery procedures

## **Dependencies**

- PHP 5.6+ compatibility
- Database access (MySQL/MariaDB/PostgreSQL)
- Filesystem write permissions
- Existing task management system

## **Next Actions**

1. Create `TaskMaterializationEngine.php` script
2. Implement database triggers for automatic materialization
3. Add task file validation and sync processes
4. Create comprehensive test suite
5. Document integration procedures
6. Monitor performance and optimize

---

**Task Status**: ACTIVE  
**Assigned to**: WOLFIE (Agent 1)  
**Due Date**: 2026-03-19  
**Dependencies**: None  
**Blockers**: None
