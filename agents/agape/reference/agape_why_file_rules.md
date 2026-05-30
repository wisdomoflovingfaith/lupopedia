# AGAPE WHY File Rules Documentation

## Overview

WHY files are the constitutional memory layer for documenting violations, validation failures, and system boundary events. They provide transparent, auditable documentation of all enforcement actions and decisions.

## WHY File Purpose

### Constitutional Memory Layer
- **Purpose**: Maintain permanent record of violations and responses
- **Authority**: Constitutional requirement for transparency
- **Scope**: All doctrine violations, validation failures, and enforcement actions
- **Access**: Reviewable by authorized agents and auditors

### Learning Transfer Support
- **Pattern Documentation**: Record violation patterns for future prevention
- **Remediation Tracking**: Document fixes and their effectiveness
- **Agent Education**: Provide examples for agent training and improvement
- **System Evolution**: Support doctrine refinement based on real violations

## WHY File Triggers

### Automatic Triggers
WHY files are automatically generated when:

1. **Doctrine Violations Detected**
   - Constitutional rule violations
   - PRD non-compliance
   - Header format violations
   - Structural rule breaches

2. **Validation Failures Occur**
   - Input validation errors
   - Safety check failures
   - Boundary condition violations
   - Malformed data rejection

3. **Unsafe Operations Blocked**
   - Security policy violations
   - Permission denied events
   - Resource limit exceeded
   - System boundary breaches

4. **Repeated Patterns Emerge**
   - Chronic violation patterns
   - Recurring validation failures
   - Systematic rule violations
   - Agent behavior issues

5. **System Boundaries Exceeded**
   - Resource consumption limits
   - Rate limiting violations
   - Capacity threshold breaches
   - Performance degradation events

### Manual Triggers
- Agent-initiated violation reports
- Human-triggered compliance checks
- Audit findings and recommendations
- Security incident documentation

## WHY File Structure

### Required Fields
```json
{
  "why_id": "why_20260423183000_001",
  "created_utc": "20260423183000",
  "violation_type": "doctrine_violation",
  "severity": 2,
  "source_artifact": "agents/agent_name/config.json",
  "source_instruction": "Invalid header format detected",
  "detected_by": "AGAPE",
  "explanation": "Detailed violation description",
  "suggested_fix": "Remediation recommendations",
  "resolved_utc": null,
  "linked_events": ["evt_20260423183000_001"],
  "actor_responsible": 123,
  "actor_slug": "agent_name",
  "impact_assessment": "Moderate impact on system integrity",
  "prevention_measures": "Enhanced validation checks",
  "learning_transfer_notes": "Add to agent training materials"
}
```

### Field Descriptions

#### Core Identification
- **why_id**: Unique identifier (why_YYYYMMDDHHIISS_NNN)
- **created_utc**: Timestamp of violation detection
- **violation_type**: Category of violation
- **severity**: Impact level (0-3, where 3 is critical)

#### Source Information
- **source_artifact**: File, component, or system where violation occurred
- **source_instruction**: Specific instruction or input that triggered violation
- **detected_by**: "AGAPE" or specific subsystem/component

#### Detailed Documentation
- **explanation**: Comprehensive description of the violation
- **suggested_fix**: Specific remediation recommendations
- **impact_assessment**: Evaluation of violation impact
- **prevention_measures**: Steps to prevent recurrence

#### Resolution Tracking
- **resolved_utc**: Timestamp when violation was resolved
- **linked_events**: Related event IDs for context
- **actor_responsible**: Actor ID responsible for violation
- **actor_slug**: Actor slug for identification

#### Learning Support
- **learning_transfer_notes**: Notes for agent training and improvement
- **prevention_measures**: Specific prevention strategies

## Violation Type Taxonomy

### Doctrine Violations
- **constitutional_violation**: Constitutional rule breaches
- **prd_violation**: PRD non-compliance
- **header_violation**: Header format violations
- **structural_violation**: Structural rule breaches

### Validation Failures
- **input_validation_failure**: Input data validation errors
- **safety_check_failure**: Safety validation failures
- **boundary_violation**: Boundary condition violations
- **format_violation**: Data format violations

### Security Violations
- **permission_denied**: Access control violations
- **security_policy_violation**: Security policy breaches
- **authentication_failure**: Authentication issues
- **authorization_failure**: Authorization problems

### System Violations
- **resource_limit_exceeded**: Resource consumption over limits
- **rate_limit_violation**: Rate limiting breaches
- **capacity_threshold_breach**: Capacity limit violations
- **performance_violation**: Performance standard breaches

## Severity Classification

### Severity 0 - Informational
- Minor documentation issues
- Cosmetic violations
- Low-impact formatting errors
- Informational alerts

### Severity 1 - Warning
- Moderate documentation issues
- Non-critical validation failures
- Minor structural violations
- Performance concerns

### Severity 2 - Error
- Significant validation failures
- Important structural violations
- Security policy concerns
- System integrity issues

### Severity 3 - Critical
- Constitutional violations
- Critical security breaches
- System integrity threats
- Major structural failures

## WHY File Generation Process

### Detection Phase
```php
function detect_violation($input, $rules) {
    foreach ($rules as $rule) {
        if (!$rule->validate($input)) {
            return [
                'violation_type' => $rule->getType(),
                'severity' => $rule->getSeverity(),
                'source_instruction' => $rule->getFailedInstruction(),
                'explanation' => $rule->getExplanation()
            ];
        }
    }
    return null;
}
```

### WHY File Creation
```php
function create_why_file($violation_data, $context) {
    $why_data = [
        'why_id' => generate_why_id(),
        'created_utc' => get_current_utc_timestamp(),
        'violation_type' => $violation_data['violation_type'],
        'severity' => $violation_data['severity'],
        'source_artifact' => $context['source_artifact'],
        'source_instruction' => $violation_data['source_instruction'],
        'detected_by' => 'AGAPE',
        'explanation' => $violation_data['explanation'],
        'suggested_fix' => generate_suggested_fix($violation_data),
        'actor_responsible' => $context['actor_id'],
        'actor_slug' => $context['actor_slug']
    ];
    
    return store_why_file($why_data);
}
```

### Suggested Fix Generation
```php
function generate_suggested_fix($violation_data) {
    switch ($violation_data['violation_type']) {
        case 'header_violation':
            return "Add missing header fields per PRD 16_C section 4.2";
        case 'input_validation_failure':
            return "Validate input data structure and types before processing";
        case 'constitutional_violation':
            return "Review constitutional requirements and adjust accordingly";
        default:
            return "Review violation details and implement appropriate fix";
    }
}
```

## WHY File Storage

### DB Mode Storage
```sql
CREATE TABLE lupo_why_files (
    why_id VARCHAR(50) PRIMARY KEY,
    created_utc BIGINT NOT NULL,
    violation_type VARCHAR(50) NOT NULL,
    severity INT NOT NULL,
    source_artifact VARCHAR(255) NOT NULL,
    source_instruction TEXT NOT NULL,
    detected_by VARCHAR(50) NOT NULL,
    explanation TEXT NOT NULL,
    suggested_fix TEXT,
    resolved_utc BIGINT,
    linked_events JSON,
    actor_responsible INT,
    actor_slug VARCHAR(50),
    impact_assessment TEXT,
    prevention_measures TEXT,
    learning_transfer_notes TEXT
);
```

### File Mode Storage
```json
// File: database/agape/why/why_20260423183000_001.json
{
  "why_id": "why_20260423183000_001",
  "created_utc": "20260423183000",
  "violation_type": "header_violation",
  "severity": 2,
  "source_artifact": "agents/agent_name/config.json",
  "source_instruction": "Missing required header fields",
  "detected_by": "AGAPE",
  "explanation": "File missing required LUPOPEDIA header fields per PRD 16_C",
  "suggested_fix": "Add all 22 required header fields in correct order",
  "resolved_utc": null,
  "linked_events": ["evt_20260423183000_001"],
  "actor_responsible": 123,
  "actor_slug": "agent_name",
  "impact_assessment": "Moderate impact on file compliance",
  "prevention_measures": "Implement header validation before file creation",
  "learning_transfer_notes": "Add header requirements to agent training materials"
}
```

## Resolution Tracking

### Resolution Process
```php
function resolve_why_file($why_id, $resolution_data) {
    $why_file = load_why_file($why_id);
    
    $why_file['resolved_utc'] = get_current_utc_timestamp();
    $why_file['resolution_details'] = $resolution_data['details'];
    $why_file['resolution_method'] = $resolution_data['method'];
    $why_file['verification_required'] = $resolution_data['verification_required'];
    
    return update_why_file($why_file);
}
```

### Verification Requirements
- **Verification Hook**: Method to verify fix effectiveness
- **Test Cases**: Specific tests to validate resolution
- **Monitoring**: Ongoing monitoring for recurrence
- **Documentation**: Updated documentation if needed

## Learning Transfer Integration

### Pattern Analysis
```php
function analyze_violation_patterns($timeframe = '30 days') {
    $patterns = [];
    
    // Group by violation type
    $by_type = group_why_files_by('violation_type', $timeframe);
    
    // Identify recurring patterns
    foreach ($by_type as $type => $violations) {
        if (count($violations) > 3) {
            $patterns[$type] = [
                'frequency' => count($violations),
                'severity_distribution' => calculate_severity_distribution($violations),
                'common_sources' => extract_common_sources($violations),
                'prevention_recommendations' => generate_prevention_recommendations($violations)
            ];
        }
    }
    
    return $patterns;
}
```

### Training Material Updates
```php
function update_training_materials($patterns) {
    foreach ($patterns as $type => $pattern) {
        if ($pattern['frequency'] > 5) {
            // Add to agent training materials
            add_to_training_materials([
                'violation_type' => $type,
                'examples' => extract_examples($pattern),
                'prevention_measures' => $pattern['prevention_recommendations'],
                'severity' => $pattern['severity_distribution']
            ]);
        }
    }
}
```

## Quality Assurance

### WHY File Validation
```php
function validate_why_file($why_data) {
    $required_fields = [
        'why_id', 'created_utc', 'violation_type', 'severity',
        'source_artifact', 'source_instruction', 'detected_by', 'explanation'
    ];
    
    foreach ($required_fields as $field) {
        if (!isset($why_data[$field]) || empty($why_data[$field])) {
            return false;
        }
    }
    
    // Validate severity range
    if ($why_data['severity'] < 0 || $why_data['severity'] > 3) {
        return false;
    }
    
    // Validate timestamp format
    if (!is_valid_timestamp($why_data['created_utc'])) {
        return false;
    }
    
    return true;
}
```

### Audit Requirements
- **Completeness**: All required fields present
- **Accuracy**: Information is correct and verifiable
- **Consistency**: Format and structure consistent
- **Traceability**: Links to related events and artifacts

## Access and Security

### Access Control
- **Read Access**: Authorized agents and auditors
- **Write Access**: AGAPE and authorized enforcement agents
- **Modify Access**: Original actor or authorized administrators
- **Delete Access**: Restricted to system administrators

### Privacy Considerations
- **Actor Identification**: Use actor slugs where possible
- **Sensitive Data**: Minimize sensitive information in WHY files
- **Data Retention**: Follow established retention policies
- **Audit Trail**: Maintain access logs for WHY file operations

---
**Last Updated**: 20260423183000  
**Agent**: AGAPE v2.0.0  
**Status**: Active WHY File Documentation
