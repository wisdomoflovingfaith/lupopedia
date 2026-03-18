---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  file_path_from_root: "lupo-channels/42/tasks/active/20260319_270000_wolfie_task_doctrine_enforcement_validator.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/tasks/active/20260319_270000_wolfie_task_doctrine_enforcement_validator.md"
  last_modified_utc: "20260319"
  system_version: "4.0.82"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "task"
  artifact_kind: "doctrine_validation_task"
  purpose: "Implement Doctrine Enforcement Validator - headers, file placement, structure"
  traits: ["doctrine_enforcement", "validation", "semantic_os", "wolfie_task"]
  tags: ["doctrine", "validation", "headers", "file_structure", "semantic_os"]
  lupo_agent: "wolfie"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-scripts/DoctrineValidator.php", type: "creates", weight: 1.0, reason: "Creates the doctrine validator" }
    - { to: "lupo-rules/root/", type: "validates", weight: 1.0, reason: "Validates against root rules" }
    - { to: "lupo-docs/doctrine/", type: "validates", weight: 1.0, reason: "Validates doctrine compliance" }
  semantic_tags: ["doctrine_enforcement", "validation", "semantic_os"]

lupopedia.see:
  mappings:
    - ["DoctrineValidator.php", "http://www.lupopedia.com/lupo-scripts/DoctrineValidator.php"]

lupopedia.footer:
  version: "4.0.82"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Create DoctrineValidator.php script"
    - "Implement header validation rules"
    - "Add file placement validation"
    - "Create automated enforcement actions"
---

# 🜎 **TASK 6 — Implement Doctrine Enforcement Validator**

## **Task Overview**
**Task ID**: 20260319_270000  
**Created by**: WOLFIE (Agent 1)  
**Channel**: 42 (Development Channel)  
**Priority**: HIGH  
**Status**: ACTIVE

## **Purpose**
Implement a comprehensive Doctrine Enforcement Validator that validates headers, file placement, task structure, channel structure, and version markers. Doctrine must be enforceable, not optional. This validator ensures the semantic OS maintains structural integrity and follows all established rules.

## **Problem Statement**

### **Current State**
- Doctrine exists but is not automatically enforced
- Manual validation is error-prone and inconsistent
- Files can violate rules without detection
- No automated enforcement mechanisms
- Version markers can become inconsistent

### **Required State**
- All doctrine violations are automatically detected
- Validation runs continuously and on-demand
- Enforcement actions are triggered automatically
- Structural integrity is maintained
- Version consistency is enforced

## **Validation Requirements**

### **1. Header Validation**
Validate LUPOPEDIA HEADERS in all files:

```yaml
lupopedia.headers:
  lupopedia.version: "4.0.82"           # Must match current version
  file_path_from_root: "path/to/file"  # Must be accurate
  web_path: "http://www.lupopedia.com/path"  # Must resolve
  last_modified_utc: "YYYYMMDD"         # Must be valid date
  system_version: "4.0.82"              # Must match current version
  channel_id: 42                        # Must be valid channel
  actor_id: 1                          # Must be valid actor
  actor_name: "wolfie"                  # Must match actor_id
  artifact_type: "task|broadcast|thread" # Must be valid type
  artifact_kind: "specific_kind"       # Must be appropriate
  purpose: "clear_purpose"              # Must be descriptive
  traits: ["trait1", "trait2"]          # Must be relevant
  tags: ["tag1", "tag2"]                # Must be relevant
  lupo_agent: "agent_name"              # Must be valid agent
```

### **2. File Placement Validation**
Ensure files are in correct locations:

```
lupo-channels/<channel_id>/
├── broadcasts/     # Only broadcast artifacts
├── threads/        # Only thread artifacts
├── tasks/          # Only task artifacts
│   ├── active/     # Only active tasks
│   ├── completed/  # Only completed tasks
│   └── pending/    # Only pending tasks
├── content/        # Only shared content
├── direct/         # Only direct messages
└── rules/          # Only channel rules
```

### **3. Task Structure Validation**
Validate task files have required structure:
- Proper headers
- Task metadata
- Success criteria
- Dependencies
- Next actions

### **4. Channel Structure Validation**
Validate channel directories:
- Required subdirectories exist
- Proper naming conventions
- Valid channel IDs
- Channel metadata consistency

### **5. Version Marker Validation**
Ensure version consistency:
- All files show current version
- No mixed versions in active files
- Historical files preserve old versions
- Version transitions are properly documented

## **Implementation Plan**

### **Phase 1: Core Validator**
1. **Create DoctrineValidator.php**
   - Header validation engine
   - File placement validator
   - Structure validation rules
   - Version consistency checker

2. **Validation Rules Engine**
   - Load rules from `lupo-rules/root/`
   - Parse doctrine specifications
   - Create validation rule sets
   - Implement rule prioritization

3. **File Scanner**
   - Recursive directory scanning
   - File type detection
   - Metadata extraction
   - Change detection

### **Phase 2: Enforcement Engine**
1. **Violation Detection**
   - Real-time monitoring
   - Scheduled validation runs
   - On-demand validation
   - Violation categorization

2. **Enforcement Actions**
   - Automatic fixes (safe operations)
   - Task creation for complex fixes
   - Notification system
   - Audit trail logging

3. **Recovery Procedures**
   - Backup and restore
   - Rollback mechanisms
   - Error recovery
   - Manual override procedures

### **Phase 3: Integration**
1. **WOLFIE Integration**
   - Automatic task creation for violations
   - Priority-based enforcement
   - System integrity monitoring
   - Reporting and analytics

2. **Agent Integration**
   - Agent-specific validation rules
   - Agent permission validation
   - Agent activity monitoring
   - Agent compliance reporting

## **Technical Specifications**

### **PHP Class Structure**
```php
<?php
class DoctrineValidator {
    private $db;
    private $rule_engine;
    private $file_scanner;
    private $enforcement_engine;
    
    public function __construct($db_connection) {
        $this->db = $db_connection;
        $this->rule_engine = new ValidationRuleEngine();
        $this->file_scanner = new FileScanner();
        $this->enforcement_engine = new EnforcementEngine();
    }
    
    public function validateAll() {
        // Validate entire system
    }
    
    public function validateFile($file_path) {
        // Validate specific file
    }
    
    public function validateChannel($channel_id) {
        // Validate specific channel
    }
    
    public function validateHeaders($file_path) {
        // Validate LUPOPEDIA HEADERS
    }
    
    public function validateFilePlacement($file_path) {
        // Validate file location
    }
    
    public function validateVersionConsistency() {
        // Check version consistency
    }
    
    public function enforceViolations($violations) {
        // Apply enforcement actions
    }
}

class ValidationRuleEngine {
    public function loadRules() {
        // Load validation rules
    }
    
    public function validate($subject, $rules) {
        // Apply validation rules
    }
}

class EnforcementEngine {
    public function createTaskForViolation($violation) {
        // Create WOLFIE task for fix
    }
    
    public function applyAutomaticFix($violation) {
        // Apply safe automatic fixes
    }
    
    public function notifyViolation($violation) {
        // Send notifications
    }
}
```

### **Validation Rules Schema**
```php
$validation_rules = [
    'headers' => [
        'required' => ['lupopedia.version', 'file_path_from_root', 'channel_id', 'actor_id'],
        'format' => [
            'lupopedia.version' => 'semantic_version',
            'last_modified_utc' => 'date_YYYYMMDD',
            'channel_id' => 'integer',
            'actor_id' => 'integer'
        ],
        'references' => [
            'channel_id' => 'lupo_channels',
            'actor_id' => 'lupo_actors'
        ]
    ],
    'file_placement' => [
        'broadcasts' => ['artifact_type' => 'broadcast'],
        'threads' => ['artifact_type' => 'thread'],
        'tasks' => ['artifact_type' => 'task'],
        'content' => ['artifact_type' => 'content'],
        'direct' => ['artifact_type' => 'direct'],
        'rules' => ['artifact_type' => 'rule']
    ],
    'structure' => [
        'task' => ['headers', 'purpose', 'requirements', 'success_criteria'],
        'broadcast' => ['headers', 'content', 'scope'],
        'thread' => ['headers', 'messages', 'participants']
    ]
];
```

### **Violation Types**
```php
$violation_types = [
    'header_missing' => [
        'severity' => 'high',
        'auto_fix' => false,
        'action' => 'create_task'
    ],
    'header_invalid' => [
        'severity' => 'medium',
        'auto_fix' => true,
        'action' => 'auto_correct'
    ],
    'file_misplaced' => [
        'severity' => 'high',
        'auto_fix' => true,
        'action' => 'move_file'
    ],
    'version_inconsistent' => [
        'severity' => 'medium',
        'auto_fix' => true,
        'action' => 'update_version'
    ],
    'structure_invalid' => [
        'severity' => 'medium',
        'auto_fix' => false,
        'action' => 'create_task'
    ]
];
```

## **Success Criteria**

- [ ] All doctrine violations are automatically detected
- [ ] Validation runs continuously without performance impact
- [ ] Enforcement actions are appropriate and effective
- [ ] System maintains structural integrity
- [ ] Version consistency is enforced
- [ ] False positive rate is <5%
- [ ] Validation completes within 30 seconds for full system

## **Testing Strategy**

### **Unit Tests**
- Header validation tests
- File placement tests
- Structure validation tests
- Version consistency tests

### **Integration Tests**
- End-to-end validation flow
- Enforcement action tests
- Performance benchmarking
- Error handling tests

### **Compliance Tests**
- Doctrine compliance validation
- Rule engine accuracy
- Enforcement effectiveness
- System integrity verification

## **Monitoring and Reporting**

### **Dashboard Metrics**
- Validation status overview
- Violation trends
- Enforcement effectiveness
- System health indicators

### **Alerts**
- Critical violations
- System integrity breaches
- Validation failures
- Performance issues

### **Reports**
- Daily validation summary
- Weekly compliance report
- Monthly system health report
- Violation analysis

## **Dependencies**

- PHP 5.6+ compatibility
- File system access
- Database access
- Existing rule system
- WOLFIE task system

## **Next Actions**

1. Create `DoctrineValidator.php` script
2. Implement header validation rules
3. Add file placement validation
4. Create automated enforcement actions
5. Set up continuous monitoring
6. Create validation dashboard

---

**Task Status**: ACTIVE  
**Assigned to**: WOLFIE (Agent 1)  
**Due Date**: 2026-03-19  
**Dependencies**: None  
**Blockers**: None
